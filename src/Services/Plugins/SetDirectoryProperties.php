<?php

namespace Irisit\Filestash\Services\Plugins;

use Irisit\Filestash\Helpers\DirectoryHelper;
use Irisit\Filestash\Helpers\PropertiesHelper;
use League\Flysystem\FilesystemInterface;
use League\Flysystem\PluginInterface;
use League\Flysystem\Util;

class SetDirectoryProperties implements PluginInterface
{
    protected $filesystem;

    public function setFilesystem(FilesystemInterface $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    public function getMethod()
    {
        return 'setDirectoryProperties';
    }

    public function handle($directory_path = DIRECTORY_SEPARATOR, $key = null, $value = null)
    {

        if ($key === null || $value === null) {
            return false;
        }

        $properties_file_name = '.directory_properties';

        $directory_path = Util::normalizePath($directory_path);

        $full_path = DirectoryHelper::combinePaths($directory_path, $properties_file_name);

        if (!$this->filesystem->has($full_path)) {

            $stubs = PropertiesHelper::getStubs();

            $exploded = explode('/', $directory_path);

            $stubs['display_name'] = end($exploded);

            if (!PropertiesHelper::validate($stubs)) {
                return false;
            }

            $this->filesystem->write($full_path, json_encode($stubs));

        }

        $contents = $this->filesystem->read($full_path);

        $properties = json_decode($contents, true);

        $properties[$key] = $value;

        if (!PropertiesHelper::validate($properties)) {
            return false;
        }

        $this->filesystem->write($full_path, json_encode($properties));

        return true;

    }


}