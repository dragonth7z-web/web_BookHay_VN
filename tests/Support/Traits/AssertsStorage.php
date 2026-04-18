<?php

namespace Tests\Support\Traits;

use Illuminate\Support\Facades\Storage;

trait AssertsStorage
{
    /**
     * Assert file tồn tại trong disk public.
     */
    protected function assertFileExistsInStorage(string $path): void
    {
        Storage::disk('public')->assertExists($path);
    }

    /**
     * Assert file không tồn tại trong disk public.
     */
    protected function assertFileAbsentFromStorage(string $path): void
    {
        Storage::disk('public')->assertMissing($path);
    }

    /**
     * Assert không có orphan file: tất cả files trong folder đều có record tương ứng.
     */
    protected function assertNoOrphanFiles(string $folder, array $validPaths): void
    {
        $files = Storage::disk('public')->files($folder);
        foreach ($files as $file) {
            $this->assertContains($file, $validPaths, "Orphan file tìm thấy: {$file}");
        }
    }

    /**
     * Assert file có extension .webp.
     */
    protected function assertIsWebp(string $path): void
    {
        $this->assertStringEndsWith('.webp', $path, "File phải được convert sang WebP: {$path}");
    }
}
