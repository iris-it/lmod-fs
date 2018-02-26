<?php

namespace Irisit\Filestash\Services\Mounts\Interfaces;

use League\Flysystem\AdapterInterface;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

interface MountInterface
{

    /**
     * Initialize the Mount with a concrete adapter
     * In order to work properly You have to bind the
     * $adapter with the filesystem and make it usable
     * in the class like this
     *
     * $this->adapter = $adapter;
     * $this->filesystem = new Filesystem($this->adapter);
     *
     * required param AdapterInterface $adapter
     *
     * @param AdapterInterface $adapter
     */
    public function __construct(AdapterInterface $adapter);


    /**
     * List all folders, enumerate files in a folder,
     * and get detailed file information.
     *
     * optional param string $path = DIRECTORY_SEPARATOR
     * optional param string $type = 'all' | ['file', 'dir', 'all']
     * optional param bool $recursive = true
     *
     * returns list of File with or without
     *
     * @param $data
     * @return Collection
     */
    public function list($data);

    /**
     * Get the file contents
     *
     * required param string $path
     * optional param bool $base64 = false
     *
     * returns file content as string or base64
     *
     * @param $data
     * @return Item
     */
    public function read($data);

    /**
     * Get the total size of files/folders within a given folder.
     *
     * optional param string $path = DIRECTORY_SEPARATOR
     * optional param bool $recursive = true
     *
     * return directory size in octet
     *
     * @param $data
     * @return Item
     */
    public function directory_size($data);

    /**
     * Check if the user has a permission on a file/folder or not.
     *
     * required param string $identifier ( user identifier )
     * required param array $groups ( user groups list )
     * required param bool $is_admin
     * required param string $action [visible, display, read, write, delete, share]
     * required param string $path
     *
     * @param $data
     * @return Item
     */
    public function check_permission($data);

    /**
     * Get the permissions for a given path
     *
     * required param $path
     *
     * @param $data
     * @return Item
     */
    public function get_properties($data);

    /**
     * Set the permissions for a given path
     *
     * required param $key
     * required param $value
     * required param $path
     *
     * @param $data
     * @return Item
     */
    public function set_properties($data);

    /**
     * Upload a file.
     *
     * required param string $destination
     *
     * @param $data
     * @return Item
     */
    public function upload($data);

    /**
     * Download files, zip is made if more than one file
     *
     * required param array $paths
     *
     * @param $data
     * @return BinaryFileResponse
     */
    public function download_files($data);

    /**
     * Download folders as zip
     *
     * required param string $path
     * optional param bool $recursive = false
     *
     * @param $data
     * @return BinaryFileResponse
     */
    public function download_directory($data);

    /**
     * Generate a sharing link to share files/folders with other
     * people and perform operations on sharing links.
     *
     * required param string $path
     * required param string $type [users,groups]
     * required param array $identifiers
     *
     * @param $data
     * @return Item
     */
    public function share($data);

    /**
     * Create folder.
     *
     * required param string $path
     *
     * @param $data
     * @return Item
     */
    public function make_directory($data);

    /**
     * Rename a file/folder.
     *
     * required param string $origin
     * required param string $name
     *
     * @param $data
     * @return Item
     */
    public function rename($data);

    /**
     * Copy files/folders.
     *
     * required param string $origin
     * required param string $destination
     *
     * @param $data
     * @return Item
     */
    public function copy($data);

    /**
     * Move files/folders
     *
     * required param string $origin
     * required param string $destination
     *
     * @param $data
     * @return Item
     */
    public function move($data);

    /**
     * Delete files/folders.
     *
     * required param string $path
     *
     * @param $data
     * @return Item
     */
    public function delete($data);


}