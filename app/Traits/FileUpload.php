<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

trait FileUpload
{
    /**
     * Upload a file with validation and return a usable URL or path
     *
     * @param UploadedFile $file
     * @param string $type 'image', 'pdf', 'video', 'zip', 'other'
     * @param string|null $folder optional custom folder
     * @param int|null $maxSize in KB
     * @param string $disk 'public', 'private', 's3'
     * @return string|false Returns file URL for S3 or public/local, or relative path for private local
     */
    protected function uploadFile(
        UploadedFile $file,
        string $type,
        ?string $folder = null,
        ?int $maxSize = null,
        string $disk = 'public'
    ): string|false {

        // Allowed MIME types
        $mimeTypes = [
            'image' => ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/jpg'],
            'pdf'   => ['application/pdf'],
            'video' => ['video/mp4', 'video/avi', 'video/mpeg', 'video/quicktime', 'video/x-ms-wmv'],
            'zip'   => ['application/zip', 'application/x-zip-compressed', 'multipart/x-zip'],
            'other' => ['*/*'],
        ];

        // Default max sizes (KB)
        $defaultMaxSizes = [
            'image' => 2048,   // 2 MB
            'pdf'   => 5120,   // 5 MB
            'video' => 51200,  // 50 MB
            'zip'   => 10240,  // 10 MB
            'other' => 10240,  // 10 MB
        ];

        $allowedMimes = $mimeTypes[$type] ?? $mimeTypes['other'];
        $maxSize = $maxSize ?? $defaultMaxSizes[$type] ?? 10240;

        // Validate MIME type
        if ($allowedMimes[0] !== '*/*' && !in_array($file->getMimeType(), $allowedMimes)) {
            return false;
        }

        // Validate file size
        if ($file->getSize() / 1024 > $maxSize) {
            return false;
        }

        $folder = $folder ?? $type;
        $filename = Str::random(20) . '.' . $file->getClientOriginalExtension();

        switch ($disk) {
            case 's3':
                $path = Storage::disk('s3')->putFileAs($folder, $file, $filename, 'public');
                Log::info($path);
                if (!$path) return false;

                return $path;

            case 'private':
                // Store in local private disk (storage/app/private)
                $path = Storage::disk('local')->putFileAs($folder, $file, $filename);

                if (!$path) return false;

                // Return relative path; frontend can use a route/controller to serve
                return $path;

            case 'public':
            default:
                $path = Storage::disk('public')->putFileAs($folder, $file, $filename);

                if (!$path) return false;

                return $path; // full public URL
        }
    }

    /**
     * Delete a file from a disk
     *
     * @param string|null $filePath
     * @param string $disk
     * @return bool
     */
    public function deleteFile(?string $filePath, string $disk = 'public'): bool
    {
        if (!$filePath) return false;

        if (Storage::disk($disk)->exists($filePath)) {
            Storage::disk($disk)->delete($filePath);
            return true;
        }

        return false;
    }

    /**
     * Get usable file URL dynamically based on disk
     *
     * @param string|null $filePath
     * @param string $disk
     * @param bool $temporary for private S3 files
     * @param int $expiresMinutes for S3 temporary URL
     * @return string|null
     */
    public function fileUrl(?string $filePath, string $disk = 'public', bool $temporary = false, int $expiresMinutes = 5): ?string
    {
        if (!$filePath) return null;

        switch ($disk) {
            case 's3':
                if ($temporary) {
                    return Storage::disk('s3')->temporaryUrl($filePath, now()->addMinutes($expiresMinutes), ['ResponseContentDisposition' => 'attachment']);
                }
                return Storage::disk('s3')->url($filePath);

            case 'private':
                // Private local files must be served via route/controller
                // Example: /file/{path} route
                return url('/file/' . $filePath);

            case 'public':
            default:
                return Storage::disk('public')->url($filePath);
        }
    }
}
