<?php
/**
 * Created by PhpStorm.
 * User: alexa
 * Date: 13/02/2018
 * Time: 11:20
 */

namespace Irisit\Filestash\Helpers;


use League\Flysystem\FilesystemInterface;

class FileHelper
{

    /**
     * Add more metadata for file
     *
     * @param array $items
     * @param FilesystemInterface $filesystem
     * @return array
     */
    public static function addMetadata(array $items, FilesystemInterface $filesystem)
    {

        foreach ($items as $key => $item) {

            if ($item['type'] !== 'dir') {
                $items[$key]['mimetype'] = $filesystem->getMimetype($item['path']);
            }

            if ($item['type'] === 'dir') {
                $item['directory'] = $item['path'];
            }

            if ($item['type'] === 'file') {
                $item['directory'] = $item['dirname'];
            }

            $items[$key]['properties'] = $filesystem->getDirectoryProperties($item['directory']);
        }

        return $items;
    }

}