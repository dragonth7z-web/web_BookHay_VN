<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

trait UploadsFile
{
    /**
     * Lưu file upload vào disk public, tối ưu hóa và chuyển sang WebP.
     */
    protected function uploadFile(UploadedFile $file, string $folder = 'uploads'): string
    {
        $mime = $file->getMimeType();
        // Skip SVG or non-image files, format them safely with Intervention Image
        if (str_starts_with($mime, 'image/') && !in_array($mime, ['image/svg+xml', 'image/gif'])) {
            $manager = new ImageManager(new Driver());
            $image = $manager->read($file->getRealPath());

            // Limit maximum width to 1200px for web optimization
            if ($image->width() > 1200) {
                $image->scaleDown(width: 1200);
            }

            $encoded = $image->toWebp(75);
            $filename = uniqid('img_') . '_' . time() . '.webp';
            $path = trim($folder, '/') . '/' . $filename;
            
            Storage::disk('public')->put($path, $encoded->toString());
            
            return $path;
        }

        return $file->store($folder, 'public');
    }

    /**
     * Xóa file cũ nếu tồn tại.
     */
    protected function deleteFile(?string $path): void
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }

    /**
     * Thay thế file cũ bằng file mới.
     */
    protected function replaceFile(UploadedFile $newFile, ?string $oldPath, string $folder = 'uploads'): string
    {
        $this->deleteFile($oldPath);
        return $this->uploadFile($newFile, $folder);
    }
}
