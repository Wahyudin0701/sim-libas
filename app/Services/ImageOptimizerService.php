<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use RuntimeException;
use Throwable;

class ImageOptimizerService
{
    /**
     * Optimasi dan simpan gambar ke format WebP dengan dimensi dan kompresi yang ideal.
     *
     * @param UploadedFile|TemporaryUploadedFile|string $file File unggahan atau path lokal
     * @param string $directory Direktori penyimpanan di dalam disk public (default: 'umkm')
     * @param int $maxWidth Lebar maksimum gambar (default: 1200px)
     * @param int $maxHeight Tinggi maksimum gambar (default: 800px)
     * @param int $quality Kualitas kompresi WebP (0-100, default: 82)
     * @return string Path relatif file yang tersimpan di disk public
     * @throws RuntimeException Jika file tidak valid atau gagal diproses
     */
    public function optimizeAndStore(
        mixed $file,
        string $directory = 'umkm',
        int $maxWidth = 1200,
        int $maxHeight = 800,
        int $quality = 82
    ): string {
        $realPath = $this->resolveFilePath($file);

        if (! file_exists($realPath) || ! is_readable($realPath)) {
            throw new RuntimeException('File gambar tidak ditemukan atau tidak dapat dibaca.');
        }

        $imageInfo = @getimagesize($realPath);
        if ($imageInfo === false) {
            throw new RuntimeException('Format file bukan merupakan citra gambar yang valid.');
        }

        $mime = $imageInfo['mime'] ?? '';
        $allowedMimes = ['image/jpeg', 'image/png', 'image/webp', 'image/jpg'];
        if (! in_array($mime, $allowedMimes, true)) {
            throw new RuntimeException("Format file {$mime} tidak didukung. Harap unggah file JPG, PNG, atau WebP.");
        }

        $sourceData = file_get_contents($realPath);
        if ($sourceData === false) {
            throw new RuntimeException('Gagal membaca data gambar.');
        }

        $srcImage = @imagecreatefromstring($sourceData);
        if ($srcImage === false) {
            throw new RuntimeException('Sistem gagal memproses struktur gambar yang diunggah.');
        }

        // Auto-orientasi EXIF (terutama untuk foto dari kamera smartphone)
        $srcImage = $this->autoRotateExif($srcImage, $realPath, $mime);

        $origWidth = imagesx($srcImage);
        $origHeight = imagesy($srcImage);

        // Hitung dimensi proporsional
        [$targetWidth, $targetHeight] = $this->calculateTargetDimensions(
            $origWidth,
            $origHeight,
            $maxWidth,
            $maxHeight
        );

        // Buat canvas gambar baru
        $dstImage = imagecreatetruecolor($targetWidth, $targetHeight);

        // Tangani transparansi jika ada (PNG/WebP)
        imagealphablending($dstImage, false);
        imagesavealpha($dstImage, true);
        $transparent = imagecolorallocatealpha($dstImage, 255, 255, 255, 127);
        imagefilledrectangle($dstImage, 0, 0, $targetWidth, $targetHeight, $transparent);

        // Resampling berkualitas tinggi
        imagecopyresampled(
            $dstImage,
            $srcImage,
            0, 0, 0, 0,
            $targetWidth,
            $targetHeight,
            $origWidth,
            $origHeight
        );

        // Simpan sementara sebagai WebP (atau JPEG jika WebP tidak tersedia)
        $tempFile = tempnam(sys_get_temp_dir(), 'umkm_opt_');
        $extension = 'webp';

        try {
            if (function_exists('imagewebp')) {
                imagewebp($dstImage, $tempFile, $quality);
                $extension = 'webp';
            } else {
                imagejpeg($dstImage, $tempFile, $quality);
                $extension = 'jpg';
            }
        } finally {
            if (is_resource($srcImage) || (is_object($srcImage) && $srcImage instanceof \GdImage)) {
                imagedestroy($srcImage);
            }
            if (is_resource($dstImage) || (is_object($dstImage) && $dstImage instanceof \GdImage)) {
                imagedestroy($dstImage);
            }
        }

        // Generate nama file unik & aman
        $filename = 'umkm_' . date('Ymd_His') . '_' . bin2hex(random_bytes(6)) . '.' . $extension;
        $targetPath = trim($directory, '/') . '/' . $filename;

        // Simpan ke storage disk public
        $stream = fopen($tempFile, 'r');
        try {
            Storage::disk('public')->put($targetPath, $stream);
        } finally {
            if (is_resource($stream)) {
                fclose($stream);
            }
            if (file_exists($tempFile)) {
                @unlink($tempFile);
            }
        }

        return $targetPath;
    }

    /**
     * Hapus file foto lama dari storage disk jika ada.
     */
    public function deleteOldPhoto(?string $path, string $disk = 'public'): bool
    {
        if (empty($path)) {
            return false;
        }

        try {
            if (Storage::disk($disk)->exists($path)) {
                return Storage::disk($disk)->delete($path);
            }
        } catch (Throwable) {
            // Ignored - log can be recorded if needed
        }

        return false;
    }

    /**
     * Mendapatkan path absolut file dari berbagai tipe input.
     */
    private function resolveFilePath(mixed $file): string
    {
        if ($file instanceof UploadedFile || $file instanceof TemporaryUploadedFile) {
            return $file->getRealPath() ?: $file->path();
        }

        if (is_string($file)) {
            return $file;
        }

        throw new RuntimeException('Tipe file gambar tidak dikenali.');
    }

    /**
     * Memperbaiki orientasi foto berdasarkan EXIF data kamera HP.
     */
    private function autoRotateExif(\GdImage $image, string $realPath, string $mime): \GdImage
    {
        if (! function_exists('exif_read_data') || ! in_array($mime, ['image/jpeg', 'image/jpg'], true)) {
            return $image;
        }

        try {
            $exif = @exif_read_data($realPath);
            if (! empty($exif['Orientation'])) {
                switch ($exif['Orientation']) {
                    case 3:
                        $rotated = imagerotate($image, 180, 0);
                        if ($rotated !== false) {
                            imagedestroy($image);
                            return $rotated;
                        }
                        break;
                    case 6:
                        $rotated = imagerotate($image, -90, 0);
                        if ($rotated !== false) {
                            imagedestroy($image);
                            return $rotated;
                        }
                        break;
                    case 8:
                        $rotated = imagerotate($image, 90, 0);
                        if ($rotated !== false) {
                            imagedestroy($image);
                            return $rotated;
                        }
                        break;
                }
            }
        } catch (Throwable) {
            // Abaikan kesalahan pembacaan EXIF jika metadata rusak
        }

        return $image;
    }

    /**
     * Hitung lebar & tinggi target secara proporsional.
     *
     * @return array{0: int, 1: int}
     */
    private function calculateTargetDimensions(int $width, int $height, int $maxWidth, int $maxHeight): array
    {
        if ($width <= $maxWidth && $height <= $maxHeight) {
            return [$width, $height];
        }

        $ratio = min($maxWidth / $width, $maxHeight / $height);
        $newWidth = max(1, (int) round($width * $ratio));
        $newHeight = max(1, (int) round($height * $ratio));

        return [$newWidth, $newHeight];
    }
}
