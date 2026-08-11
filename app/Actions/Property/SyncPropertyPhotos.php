<?php

namespace App\Actions\Property;

use App\Models\Property;
use App\Models\PropertyPhoto;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Throwable;

class SyncPropertyPhotos
{
    /**
     * Upload new photos, optionally choose a cover.
     *
     * @param  array<int, UploadedFile>  $uploadedFiles
     * @param  int|null  $coverIndex  Index in $uploadedFiles that becomes the cover.
     *                                If null, does not change the current cover unless there is none.
     * @param  array<int, int>  $deletePhotoIds  IDs of existing PropertyPhoto rows to delete.
     */
    public function handle(
        Property $property,
        array $uploadedFiles = [],
        ?int $coverIndex = null,
        array $deletePhotoIds = [],
    ): void {
        $writtenPaths = [];

        try {
            DB::transaction(function () use ($property, $uploadedFiles, $coverIndex, $deletePhotoIds, &$writtenPaths) {
                $disk = Storage::disk('public');

                // Delete requested photos (row + file)
                if (! empty($deletePhotoIds)) {
                    $toDelete = PropertyPhoto::query()
                        ->where('property_id', $property->id)
                        ->whereIn('id', $deletePhotoIds)
                        ->get();

                    foreach ($toDelete as $photo) {
                        $disk->delete($photo->path);
                        $photo->delete();
                    }
                }

                // Store new files
                $newRows = [];
                foreach ($uploadedFiles as $file) {
                    if (! $file instanceof UploadedFile) {
                        continue;
                    }
                    $path = $file->store("properties/{$property->id}", 'public');
                    $writtenPaths[] = $path;
                    $newRows[] = PropertyPhoto::create([
                        'property_id' => $property->id,
                        'path' => $path,
                        'is_cover' => false,
                        'sort_order' => 0,
                    ]);
                }

                // Cover selection
                if ($coverIndex !== null && isset($newRows[$coverIndex])) {
                    // Clear cover on all siblings, set on the chosen new row.
                    PropertyPhoto::where('property_id', $property->id)->update(['is_cover' => false]);
                    $newRows[$coverIndex]->update(['is_cover' => true]);
                } else {
                    // Guarantee at least one cover exists if any photos remain.
                    $hasCover = PropertyPhoto::where('property_id', $property->id)
                        ->where('is_cover', true)
                        ->exists();
                    if (! $hasCover) {
                        $first = PropertyPhoto::where('property_id', $property->id)
                            ->orderBy('sort_order')
                            ->first();
                        if ($first) {
                            $first->update(['is_cover' => true]);
                        }
                    }
                }
            });
        } catch (Throwable $e) {
            // Roll back written files if the DB transaction failed.
            $disk = Storage::disk('public');
            foreach ($writtenPaths as $p) {
                if ($disk->exists($p)) {
                    $disk->delete($p);
                }
            }
            throw $e;
        }
    }
}
