<?php

namespace Irisit\Filestash\Tests\Feature;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Irisit\Filestash\Tests\TestCase;

class UploadTests extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testFileUpload()
    {
        Storage::fake('test');

        $destination = '/user/test/upload';

        $url = route('filestash.handle_requests');

        $data = [
            'mount' => 'test',
            'method' => 'upload',
            'to' => $destination,
            UploadedFile::fake()->image('file.jpg')
        ];

        $response = $this->postJson($url, $data);

        $response->assertStatus(200);

        // Assert the file was stored...
        Storage::disk('test')->assertExists($destination . DIRECTORY_SEPARATOR . 'file.jpg');

    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testFileUploadWithoutPath()
    {
        Storage::fake('test');

        $url = route('filestash.handle_requests');

        $data = [
            'mount' => 'test',
            'method' => 'upload',
            UploadedFile::fake()->image('file.jpg')
        ];

        $response = $this->postJson($url, $data);

        $response->assertStatus(200);

        // Assert the file was stored...
        Storage::disk('test')->assertExists('file.jpg');

    }


    /**
     * A basic test example.
     *
     * @return void
     */
    public function testFileUploadSameFile()
    {
        Storage::fake('test');

        $destination = '/user/test/upload';

        $url = route('filestash.handle_requests');

        $data = [
            'mount' => 'test',
            'method' => 'upload',
            'to' => $destination,
            UploadedFile::fake()->image('file.jpg')
        ];

        $this->postJson($url, $data);

        $this->postJson($url, $data);

        $this->postJson($url, $data);

        $response = $this->postJson($url, $data);

        $response->assertStatus(200);

        // Assert the file was stored...
        Storage::disk('test')->assertExists($destination . DIRECTORY_SEPARATOR . 'file.jpg');

    }
}
