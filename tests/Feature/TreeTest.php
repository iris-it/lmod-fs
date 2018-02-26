<?php

namespace Irisit\Filestash\Tests\Feature;

use Irisit\Filestash\Tests\TestCase;

class TreeTest extends TestCase
{

    public function testRecursiveDirectoryTree()
    {
        $url = route('filestash.handle_requests');

        $data = [
            'mount' => 'group',
            'function' => 'list',
            'path' => '/',
            'type' => 'dir',
            'recursive' => true,
        ];

        $response = $this->json('GET', $url, $data, []);

        $content = $response->content();

        // response testing file
        // file_put_contents(__DIR__.'/json_response_files/recursive_directory_tree.json', $content);

        $response->assertStatus(200);

        $content = json_decode($content, true);
        $expected_content = json_decode(file_get_contents(__DIR__ . '/json_response_files/recursive_directory_tree.json'), true);

        $this->recursive_unset($content, 'timestamp');
        $this->recursive_unset($expected_content, 'timestamp');

        $this->assertArraySubset($content, $expected_content);

    }

    public function testRecursiveFileAndDirectoriesTree()
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

        // response testing file
        // file_put_contents(__DIR__.'/json_response_files/recursive_file_and_directories_tree.json', $content);

        $response->assertStatus(200);

        $content = json_decode($content, true);
        $expected_content = json_decode(file_get_contents(__DIR__ . '/json_response_files/recursive_file_and_directories_tree.json'), true);

        $this->recursive_unset($content, 'timestamp');
        $this->recursive_unset($expected_content, 'timestamp');

        $this->assertArraySubset($content, $expected_content);

    }

}
