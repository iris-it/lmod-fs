<?php

namespace Irisit\Filestash\Services\Plugins;

use Irisit\Filestash\Helpers\DirectoryHelper;
use Irisit\Filestash\Helpers\PropertiesHelper;
use League\Flysystem\FilesystemInterface;
use League\Flysystem\PluginInterface;
use League\Flysystem\Util;

class GetDirectoryProperties implements PluginInterface
{
    protected $filesystem;

    public function setFilesystem(FilesystemInterface $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    public function getMethod()
    {
        return 'getDirectoryProperties';
    }

    public function handle($directory_path = DIRECTORY_SEPARATOR)
    {
        $properties_file_name = '.directory_properties';

        $directory_path = Util::normalizePath($directory_path);

        $full_path = DirectoryHelper::combinePaths($directory_path, $properties_file_name);


        if ($this->filesystem->has($full_path)) {

            $contents = $this->filesystem->read($full_path);

            return json_decode($contents, true);

        }

        $stubs = PropertiesHelper::getStubs();

        $exploded = explode('/', $directory_path);

        $stubs['display_name'] = end($exploded);

        $this->filesystem->write($full_path, json_encode($stubs));

        return $stubs;

    }


}