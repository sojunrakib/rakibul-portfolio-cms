<?php

declare(strict_types=1);

namespace App\Services;

use RuntimeException;

final class UploadService
{
    private const MAX_BYTES = 5242880;
    private const ALLOWED = ['image/jpeg', 'image/png', 'image/webp', 'image/gif', 'application/pdf'];

    public function store(?array $file, string $directory = 'media'): ?string
    {
        if (!$file || ($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
            return null;
        }

        if (($file['error'] ?? UPLOAD_ERR_OK) !== UPLOAD_ERR_OK) {
            throw new RuntimeException('Upload failed.');
        }

        if (($file['size'] ?? 0) > self::MAX_BYTES) {
            throw new RuntimeException('File is larger than the 5MB upload limit.');
        }

        $mime = \mime_content_type($file['tmp_name']);
        if (!in_array($mime, self::ALLOWED, true)) {
            throw new RuntimeException('Only images and PDF files are allowed.');
        }

        $basePath = dirname(__DIR__, 2) . '/public/uploads/' . trim($directory, '/');
        if (!is_dir($basePath)) {
            mkdir($basePath, 0775, true);
        }

        if ($mime === 'application/pdf') {
            $name = \bin2hex(\random_bytes(12)) . '.pdf';
            $this->moveUploadedFile($file['tmp_name'], $basePath . '/' . $name);
            return trim($directory, '/') . '/' . $name;
        }

        if (\extension_loaded('imagick') && \class_exists(\Imagick::class)) {
            return $this->storeWithImagick($file['tmp_name'], $basePath, $directory);
        }

        if (!\extension_loaded('gd') || !\function_exists('imagewebp')) {
            return $this->storeVerifiedOriginal($file['tmp_name'], $basePath, $directory, $mime);
        }

        $image = match ($mime) {
            'image/jpeg' => \imagecreatefromjpeg($file['tmp_name']),
            'image/png' => \imagecreatefrompng($file['tmp_name']),
            'image/webp' => \imagecreatefromwebp($file['tmp_name']),
            'image/gif' => \imagecreatefromgif($file['tmp_name']),
            default => null,
        };

        if (!$image) {
            throw new RuntimeException('Image could not be processed.');
        }

        $width = \imagesx($image);
        $height = \imagesy($image);
        $maxWidth = 1800;
        if ($width > $maxWidth) {
            $ratio = $maxWidth / $width;
            $target = \imagecreatetruecolor($maxWidth, (int) \round($height * $ratio));
            \imagealphablending($target, false);
            \imagesavealpha($target, true);
            \imagecopyresampled($target, $image, 0, 0, 0, 0, \imagesx($target), \imagesy($target), $width, $height);
            \imagedestroy($image);
            $image = $target;
        }

        $name = \bin2hex(\random_bytes(12)) . '.webp';
        \imagewebp($image, $basePath . '/' . $name, 82);
        \imagedestroy($image);

        return trim($directory, '/') . '/' . $name;
    }

    private function storeWithImagick(string $tmpPath, string $basePath, string $directory): string
    {
        $image = new \Imagick($tmpPath);
        $image->setImageFormat('webp');
        $image->setImageCompressionQuality(82);
        if ($image->getImageWidth() > 1800) {
            $image->thumbnailImage(1800, 0);
        }

        $name = \bin2hex(\random_bytes(12)) . '.webp';
        $image->writeImage($basePath . '/' . $name);
        $image->clear();
        $image->destroy();

        return trim($directory, '/') . '/' . $name;
    }

    private function storeVerifiedOriginal(string $tmpPath, string $basePath, string $directory, string $mime): string
    {
        // Professional fallback for hosts where GD/Imagick is disabled: keep uploads working after MIME validation.
        // Enable gd or imagick in php.ini to restore resize/optimization and WebP conversion.
        $extension = match ($mime) {
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp',
            'image/gif' => 'gif',
            default => throw new RuntimeException('Unsupported image type.'),
        };

        $name = \bin2hex(\random_bytes(12)) . '.' . $extension;
        $this->moveUploadedFile($tmpPath, $basePath . '/' . $name);

        return trim($directory, '/') . '/' . $name;
    }

    private function moveUploadedFile(string $from, string $to): void
    {
        $moved = \is_uploaded_file($from)
            ? \move_uploaded_file($from, $to)
            : \rename($from, $to);

        if (!$moved) {
            throw new RuntimeException('Could not save uploaded file.');
        }
    }
}
