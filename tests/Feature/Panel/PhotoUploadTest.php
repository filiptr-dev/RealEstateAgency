<?php

namespace Tests\Feature\Panel;

use App\Actions\Property\SyncPropertyPhotos;
use App\Models\Property;
use App\Models\PropertyPhoto;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PhotoUploadTest extends TestCase
{
    use RefreshDatabase;

    public function test_upload_stores_files_and_marks_cover(): void
    {
        Storage::fake('public');
        $property = Property::factory()->create();

        app(SyncPropertyPhotos::class)->handle(
            $property,
            [UploadedFile::fake()->image('a.jpg'), UploadedFile::fake()->image('b.jpg')],
            coverIndex: 1,
        );

        $this->assertSame(2, $property->photos()->count());
        $this->assertSame(1, $property->photos()->where('is_cover', true)->count());
        foreach ($property->photos()->get() as $photo) {
            Storage::disk('public')->assertExists($photo->path);
        }
    }

    public function test_delete_removes_row_and_file(): void
    {
        Storage::fake('public');
        $property = Property::factory()->create();

        app(SyncPropertyPhotos::class)->handle(
            $property,
            [UploadedFile::fake()->image('a.jpg')],
            coverIndex: 0,
        );

        $photo = $property->photos()->firstOrFail();
        Storage::disk('public')->assertExists($photo->path);

        app(SyncPropertyPhotos::class)->handle(
            $property,
            deletePhotoIds: [$photo->id],
        );

        $this->assertSame(0, $property->photos()->count());
        Storage::disk('public')->assertMissing($photo->path);
    }

    public function test_rollback_leaves_no_orphan_files_when_transaction_fails(): void
    {
        Storage::fake('public');
        $property = Property::factory()->create();

        // Force the transaction to fail after files were written.
        DB::listen(function ($query) {
            if (str_contains(strtolower($query->sql), 'update "property_photos"')
                || str_contains(strtolower($query->sql), 'update `property_photos`')) {
                throw new \RuntimeException('boom');
            }
        });

        try {
            app(SyncPropertyPhotos::class)->handle(
                $property,
                [UploadedFile::fake()->image('a.jpg')],
                coverIndex: 0,
            );
            $this->fail('expected exception');
        } catch (\RuntimeException $e) {
            // expected
        }

        $this->assertSame(0, PropertyPhoto::count(), 'no rows should remain after rollback');
        $this->assertEmpty(
            Storage::disk('public')->allFiles("properties/{$property->id}"),
            'no files should remain after rollback',
        );
    }
}
