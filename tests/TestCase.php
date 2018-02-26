<?php

namespace Irisit\Filestash\Tests;

use Illuminate\Support\Facades\File;
use Irisit\Filestash\FilestashServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{

    public function setUp()
    {
        parent::setUp();

        $this->setUpTempTestFiles();
    }

    protected function tearDown()
    {
        parent::tearDown();

        File::deleteDirectory($this->getTempDirectory());
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            FilestashServiceProvider::class
        ];
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('irisit_filestash', [
            'base_path' => 'files',
            'api_path' => 'api',
            'admin_path' => 'admin',
            'mounts' => [
                'group' => [
                    'root' => __DIR__ . DIRECTORY_SEPARATOR . 'temp',
                ],
                'user' => [
                    'root' => __DIR__ . DIRECTORY_SEPARATOR . 'temp'
                ]
            ],
            'admin_allowed_roles' => 'admin',
        ]);
    }

    protected function setUpTempTestFiles()
    {
        $this->initializeDirectory($this->getTempDirectory());

        File::copyDirectory(__DIR__ . '/test_structure', $this->getTempDirectory());
    }

    protected function initializeDirectory($directory)
    {
        if (File::isDirectory($directory)) {
            File::deleteDirectory($directory);
        }

        File::makeDirectory($directory);
    }

    public function getTempDirectory($suffix = '')
    {
        return __DIR__ . '/temp' . ($suffix == '' ? '' : '/' . $suffix);
    }

    public function getTestStructureDirectory($suffix = '')
    {
        return $this->getTempDirectory() . '/test_structure' . ($suffix == '' ? '' : '/' . $suffix);
    }

    /*
     *  UTILS
     */
    public function recursive_unset(&$array, $unwanted_key)
    {
        unset($array[$unwanted_key]);
        foreach ($array as &$value) {
            if (is_array($value)) {
                $this->recursive_unset($value, $unwanted_key);
            }
        }
    }


}