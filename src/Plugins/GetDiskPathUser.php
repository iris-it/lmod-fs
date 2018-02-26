<?php

namespace Irisit\Filestash\Plugins;

use App\User;
use League\Flysystem\FilesystemInterface;
use League\Flysystem\PluginInterface;

/**
 * Created by PhpStorm.
 * User: alexa
 * Date: 11/01/2018
 * Time: 16:05
 */
class GetDiskPathUser implements PluginInterface
{
    protected $filesystem;

    public function setFilesystem(FilesystemInterface $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    public function getMethod()
    {
        return 'getDiskPath';
    }

    public function handle()
    {

        dd($this->filesystem);

        return $this->filesystem->getAdapter()->getPathPrefix() . sha1(1) . DIRECTORY_SEPARATOR;
    }

}