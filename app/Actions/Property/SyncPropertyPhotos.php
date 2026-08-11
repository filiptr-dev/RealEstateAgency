<?php

namespace App\Actions\Property;

use App\Models\Property;
use App\Models\PropertyPhoto;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Throwable;

class SyncPropertyPhotos
{
    /**
     * Upload new photos (files + URL fetches), optionally choose a cover, delete requested existing rows.
     *
     * Ordering rule: uploaded files first, then URL fetches. The cover index applies across the
     * combined list — index 0 is the first file, index N is the (N-count(files))th URL.
     *
     * @param  array<int, UploadedFile>  $uploadedFiles
     * @param  int|null  $coverIndex  Index in the combined new-rows list that becomes the cover.
     *                                If null, does not change the current cover unless there is none.
     * @param  array<int, int>  $deletePhotoIds  IDs of existing PropertyPhoto rows to delete.
     * @param  array<int, string>  $urls  Image URLs to fetch + store alongside the uploads.
     * @return array<int, string> URLs that were skipped (bad content-type, timeout, non-2xx). Empty on full success.
     */
    public function handle(
        Property $property,
        array $uploadedFiles = [],
        ?int $coverIndex = null,
        array $deletePhotoIds = [],
        array $urls = [],
    ): array {
        $writtenPaths = [];
        $skipped = [];

        try {
            DB::transaction(function () use ($property, $uploadedFiles, $coverIndex, $deletePhotoIds, $urls, &$writtenPaths, &$skipped) {
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

                // Fetch + store URL-supplied photos. Failures are non-fatal — skipped, not thrown.
                foreach ($urls as $url) {
                    try {
                        $response = Http::timeout(10)->get($url);
                        $contentType = (string) $response->header('Content-Type');
                        if (! $response->ok() || ! str_starts_with($contentType, 'image/')) {
                            $skipped[] = $url;

                            continue;
                        }
                        $path = "properties/{$property->id}/".Str::uuid().'.jpg';
                        $disk->put($path, $response->body());
                        $writtenPaths[] = $path;
                        $newRows[] = PropertyPhoto::create([
                            'property_id' => $property->id,
                            'path' => $path,
                            'is_cover' => false,
                            'sort_order' => 0,
                        ]);
                    } catch (Throwable $e) {
                        Log::warning("Photo URL fetch failed: {$url}", ['error' => $e->getMessage()]);
                        $skipped[] = $url;
                    }
                }

                // Cover selection — applies to the combined new-rows list (files then URLs).
                if ($coverIndex !== null && isset($newRows[$coverIndex])) {
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

        return $skipped;
    }
}
