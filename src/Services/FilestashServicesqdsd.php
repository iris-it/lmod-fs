<?php

namespace Irisit\Filestash\Services;

use Illuminate\Support\Facades\Storage;

class FilestashServicesqdsd extends Service
{

    private $disk_instance;

    private $disk_path;

    public function init($mount)
    {
        $this->disk_instance = $this->getMount($mount);

        $this->disk_path = $this->disk_instance->getDriver()->getAdapter()->getPathPrefix();

        return $this;
    }


    private function upload($request)
    {
        $this->abortIfNotMethod($request->method(), ['POST', 'PUT']);

        $path = '';

        $files = $request->file();

        if ($request->has('to')) {
            $path = $this->preventTraversal($request->get('to'));
        }

        foreach ($files as $file) {

            if ($file->isValid()) {

                $stream = fopen($file->getRealPath(), 'r+');

                $filename_info = pathinfo($file->getClientOriginalName());

                $name = $filename_info['filename'];

                $extension = $filename_info['extension'];

                $filename = $name . '.' . $extension;

                if (realpath($this->disk_path . $path . DIRECTORY_SEPARATOR . $filename) !== false) {
                    $filename = $name . '.' . 'duplicate-' . rand(1000, 9999) . '.' . $extension;
                }

                $this->disk_instance->writeStream($path . DIRECTORY_SEPARATOR . $filename, $stream);

                fclose($stream);
            }
        }

        return response('ok', 200);
    }

    private function gettree($request)
    {
        $files = [];

        $this->abortIfNotMethod($request->method(), ['GET']);

        $path = $this->preventTraversal($request->input('path', DIRECTORY_SEPARATOR));

        $contents = $this->disk_instance->listContents($path, true);

        $contents = array_map(function ($item) {
            if ($item['type'] == 'dir') {
                return $item;
            }
            return null;
        }, $contents);

        $contents = array_filter($contents);

        $contents = $this->buildFileTree($contents);

        return $contents;
    }

    private function listdirectory($request)
    {
        $files = [];

        $this->abortIfNotMethod($request->method(), ['GET']);

        $path = $this->preventTraversal($request->input('path', DIRECTORY_SEPARATOR));

        $contents = $this->disk_instance->listContents($path, false);

        $contents = array_values($contents);

        return $contents;
    }

    /*
     * Mounts
     */
    private function getMount($mount_name)
    {
        $mounts_configuration = config('irisit_filestash.mounts');

        if (in_array($mount_name, array_keys($mounts_configuration))) {

            $disk = null;

            $mount_configuration = $mounts_configuration[$mount_name];

            $disk = Storage::disk($mount_name);

            foreach ($mount_configuration['plugins'] as $plugin) {
                $disk->addPlugin(new $plugin);
            }

            return $disk;

        }

        return false;
    }

    /*
     * Utils
     */
    private function abortIfNotMethod($method, array $allowedMethods)
    {
        if (!in_array($method, $allowedMethods)) {
            return abort(403);
        }

        return true;
    }

    function preventTraversal($path)
    {
        //$root = ($path[0] === '/') ? '/' : '';

        $root = '/';

        $segments = explode('/', trim($path, '/'));

        $ret = array();

        foreach ($segments as $segment) {
            if (($segment == '.') || strlen($segment) === 0) {
                continue;
            }
            if ($segment == '..') {
                array_pop($ret);
            } else {
                array_push($ret, $segment);
            }
        }
        return $root . implode('/', $ret);
    }


    function buildFileTree($flat_structure)
    {
        $tree = array();

        $split_regex = '/' . preg_quote('/', '/') . '/';

        foreach ($flat_structure AS $item) {

            $pathIds = preg_split($split_regex, $item['path'], -1);

            $current = &$tree;

            foreach ($pathIds AS $id) {

                if (!isset($current["childs"][$id])) {
                    $current["childs"][$id] = array();
                }

                $current = &$current["childs"][$id];

                if ($id == $item["basename"]) {
                    $current = $item;
                }
            }
        }

        return $tree["childs"];
    }

}