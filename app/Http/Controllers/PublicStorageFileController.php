<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PublicStorageFileController extends Controller
{
    /**
     * Layani file dari disk public lewat PHP (hindari 403 karena symlink / permission di server).
     */
    public function show(string $path): StreamedResponse|Response
    {
        $path = str_replace('\\', '/', $path);
        $path = ltrim($path, '/');

        if ($path === '' || str_contains($path, '..')) {
            abort(404);
        }

        // Hanya izinkan upload profil (bukan seluruh isi disk public).
        if (! str_starts_with($path, 'people/')) {
            abort(404);
        }

        $disk = Storage::disk('public');

        if (! $disk->exists($path)) {
            abort(404);
        }

        $full = realpath($disk->path($path));
        $root = realpath(storage_path('app/public'));

        if ($full === false || $root === false || ! str_starts_with($full, $root)) {
            abort(404);
        }

        return $disk->response($path);
    }
}
