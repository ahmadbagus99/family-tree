<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PublicStorageFileTest extends TestCase
{
    public function test_it_serves_existing_public_disk_files(): void
    {
        Storage::disk('public')->put('people/test-image.txt', 'hello');

        $response = $this->get('/media/people/test-image.txt');

        $response->assertOk();
        $this->assertTrue(Storage::disk('public')->exists('people/test-image.txt'));

        Storage::disk('public')->delete('people/test-image.txt');
    }

    public function test_it_returns_404_for_path_traversal(): void
    {
        $this->get('/media/../.env')->assertNotFound();
    }

    public function test_it_returns_404_for_missing_file(): void
    {
        $this->get('/media/people/does-not-exist-'.uniqid().'.jpg')->assertNotFound();
    }
}
