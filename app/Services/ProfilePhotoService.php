<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;

/**
 * Kompresi & resize foto profil (GD). Output JPEG agar ukuran kecil.
 */
class ProfilePhotoService
{
    public const MAX_EDGE_PX = 1920;

    public const JPEG_QUALITY = 82;

    /**
     * Simpan ke disk public, kembalikan path relatif (mis. people/uuid.jpg).
     */
    public function storeCompressed(UploadedFile $file, string $directory = 'people'): string
    {
        $realPath = $file->getRealPath();
        if ($realPath === false) {
            throw new RuntimeException('Berkas upload tidak dapat dibaca.');
        }

        $mime = (string) $file->getMimeType();
        $src = $this->createImageResource($realPath, $mime);

        if ($src === false) {
            return $file->store($directory, 'public');
        }

        $src = $this->normalizeOrientation($src, $realPath, $mime);

        $w = imagesx($src);
        $h = imagesy($src);
        if ($w < 1 || $h < 1) {
            imagedestroy($src);

            return $file->store($directory, 'public');
        }

        [$nw, $nh] = $this->scaledDimensions($w, $h, self::MAX_EDGE_PX);

        $dst = imagecreatetruecolor($nw, $nh);
        if ($dst === false) {
            imagedestroy($src);

            return $file->store($directory, 'public');
        }

        imagealphablending($dst, false);
        $white = imagecolorallocate($dst, 255, 255, 255);
        imagefill($dst, 0, 0, $white);
        imagealphablending($dst, true);
        imagecopyresampled($dst, $src, 0, 0, 0, 0, $nw, $nh, $w, $h);

        imagedestroy($src);

        $temp = tempnam(sys_get_temp_dir(), 'pfp');
        if ($temp === false) {
            imagedestroy($dst);

            return $file->store($directory, 'public');
        }

        imagejpeg($dst, $temp, self::JPEG_QUALITY);
        imagedestroy($dst);

        $name = $directory.'/'.Str::uuid()->toString().'.jpg';
        Storage::disk('public')->put($name, (string) file_get_contents($temp));
        @unlink($temp);

        return $name;
    }

    /**
     * @return array{0: int, 1: int}
     */
    protected function scaledDimensions(int $w, int $h, int $maxEdge): array
    {
        if ($w <= $maxEdge && $h <= $maxEdge) {
            return [$w, $h];
        }

        if ($w >= $h) {
            $nw = $maxEdge;
            $nh = (int) max(1, round($h * ($maxEdge / $w)));
        } else {
            $nh = $maxEdge;
            $nw = (int) max(1, round($w * ($maxEdge / $h)));
        }

        return [$nw, $nh];
    }

    /**
     * @return \GdImage|resource|false
     */
    protected function createImageResource(string $path, string $mime)
    {
        $mime = strtolower($mime);

        if (str_contains($mime, 'jpeg') || $mime === 'image/jpg') {
            return @imagecreatefromjpeg($path);
        }
        if (str_contains($mime, 'png')) {
            return @imagecreatefrompng($path);
        }
        if (str_contains($mime, 'gif')) {
            return @imagecreatefromgif($path);
        }
        if (str_contains($mime, 'webp') && function_exists('imagecreatefromwebp')) {
            return @imagecreatefromwebp($path);
        }

        return false;
    }

    /**
     * Normalisasi orientasi berdasarkan EXIF (umum pada foto kamera HP).
     *
     * @param  \GdImage|resource  $src
     * @return \GdImage|resource
     */
    protected function normalizeOrientation($src, string $path, string $mime)
    {
        $mime = strtolower($mime);
        if (! (str_contains($mime, 'jpeg') || $mime === 'image/jpg')) {
            return $src;
        }

        if (! function_exists('exif_read_data')) {
            return $src;
        }

        $exif = @exif_read_data($path);
        $orientation = (int) ($exif['Orientation'] ?? 1);

        if ($orientation === 1) {
            return $src;
        }

        if (function_exists('imageflip')) {
            if (in_array($orientation, [2, 4, 5, 7], true)) {
                @imageflip($src, IMG_FLIP_HORIZONTAL);
            }
            if (in_array($orientation, [4, 5, 7], true)) {
                @imageflip($src, IMG_FLIP_VERTICAL);
            }
        }

        $angle = match ($orientation) {
            3 => 180,
            6, 5 => -90,
            8, 7 => 90,
            default => 0,
        };

        if ($angle === 0) {
            return $src;
        }

        $rotated = @imagerotate($src, $angle, 0);
        if ($rotated === false) {
            return $src;
        }

        imagedestroy($src);

        return $rotated;
    }
}
