<?php
namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
trait FileUpload
{
    /**
     * Upload a file with type and size validation
     *
     * @param UploadedFile $file
     * @param string $type 'image', 'pdf', 'video', 'zip', 'other'
     * @param string|null $folder optional custom folder
     * @param int|null $maxSize in KB (optional)
     * @return string|false Returns file path or false if validation fails
     */
    public function uploadFile(UploadedFile $file, string $type, string $folder = null, int $maxSize = null)
    {
        // Define allowed mime types
        $mimeTypes = [
            'image' => ['image/jpeg','image/png','image/gif','image/webp','image/jpg'],
            'pdf'   => ['application/pdf'],
            'video' => ['video/mp4','video/avi','video/mpeg','video/quicktime','video/x-ms-wmv'],
            'zip'   => ['application/zip','application/x-zip-compressed','multipart/x-zip'],
            'other' => ['*/*'],
        ];

        // Define default max sizes (KB)
        $defaultMaxSizes = [
            'image' => 2048, // 2 MB
            'pdf'   => 5120, // 5 MB
            'video' => 51200, // 50 MB
            'zip'   => 10240, // 10 MB
            'other' => 10240, // 10 MB
        ];

        $allowedMimes = $mimeTypes[$type] ?? $mimeTypes['other'];
        $maxSize = $maxSize ?? $defaultMaxSizes[$type] ?? 10240;

        // Validate mime type
        if (!in_array($file->getMimeType(), $allowedMimes) && $allowedMimes[0] != '*/*') {
            return false;
        }

        // Validate file size
        if ($file->getSize()/1024 > $maxSize) {
            return false;
        }

        // Set folder
        $folder = $folder ?? $type;

        // Generate unique file name
        $filename = Str::random(20) . '.' . $file->getClientOriginalExtension();

        // Store file in storage/app/public/$folder
        $path = $file->storeAs("public/$folder", $filename);

        return $path ? Storage::url($path) : false;
    }

    /**
     * Delete file
     */
    public function deleteFile(?string $filePath): bool
    {
        if ($filePath && Storage::disk('public')->exists($filePath)) {
            Storage::disk('public')->delete($filePath);
            return true;
        }
        return false;
    }



    /**
     * Get file public URL
     */
    public function fileUrl(?string $filePath): ?string
    {
        if (!$filePath) return null;
        return asset('storage/' . $filePath);
    }
}