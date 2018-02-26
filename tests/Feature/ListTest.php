<?php

namespace Irisit\Filestash\Tests\Feature;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Irisit\Filestash\Tests\TestCase;

class ListTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testListFilesOnly()
    {
        $url = route('filestash.handle_requests');

        $data = [
            'mount' => 'group',
            'function' => 'list',
            'path' => '/directory_1',
            'type' => 'file',
            'recursive' => false,
        ];

        $response = $this->json('GET', $url, $data, []);

        $content = $response->content();

        // response testing file
        // file_put_contents(__DIR__ . '/json_response_files/list_files_only.json', $content);

        $response->assertStatus(200);

        $content = json_decode($content, true);
        $expected_content = json_decode(file_get_contents(__DIR__ . '/json_response_files/list_files_only.json'), true);

        $this->recursive_unset($content, 'timestamp');
        $this->recursive_unset($expected_content, 'timestamp');

        $this->assertArraySubset($content, $expected_content);
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testListDirectoriesOnly()
    {
        $url = route('filestash.handle_requests');

        $data = [
            'mount' => 'group',
            'function' => 'list',
            'path' => '/directory_1',
            'type' => 'dir',
            'recursive' => false,
        ];

        $response = $this->json('GET', $url, $data, []);

        $content = $response->content();

        // response testing file
        // file_put_contents(__DIR__ . '/json_response_files/list_directories_only.json', $content);

        $response->assertStatus(200);

        $content = json_decode($content, true);
        $expected_content = json_decode(file_get_contents(__DIR__ . '/json_response_files/list_directories_only.json'), true);

        $this->recursive_unset($content, 'timestamp');
        $this->recursive_unset($expected_content, 'timestamp');

        $this->assertArraySubset($content, $expected_content);
    }

    /**
     * A basic test example.
     *
     * @group testme
     *
     * @return void
     */
    public function testListAll()
    {
        $url = route('filestash.handle_requests');

        $data = [
            'mount' => 'group',
            'function' => 'list',
            'path' => '/',
            'type' => 'all',
            'recursive' => true,
        ];

        $response = $this->json('GET', $url, $data, []);

        $content = $response->content();

        $content = json_decode($content, true);
        unset($content['trace']);
        dd($content);

        // response testing file
        // file_put_contents(__DIR__ . '/json_response_files/list_all.json', $content);

        $response->assertStatus(200);

        $content = json_decode($content, true);
        $expected_content = json_decode(file_get_contents(__DIR__ . '/json_response_files/list_all.json'), true);


        $this->recursive_unset($content, 'timestamp');
        $this->recursive_unset($expected_content, 'timestamp');

        $this->assertArraySubset($content, $expected_content);
    }

}
