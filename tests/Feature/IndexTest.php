<?php

namespace Irisit\Filestash\Tests\Feature;

use Irisit\Filestash\Tests\TestCase;

class IndexTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {
        $url = route('filestash.index');

        $response = $this->json('GET', $url, [], []);

        $response->assertStatus(200);

    }
}
