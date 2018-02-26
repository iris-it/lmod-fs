<?php
/**
 * Created by PhpStorm.
 * User: alexa
 * Date: 12/02/2018
 * Time: 13:07
 */

namespace Irisit\Filestash\Services\Mounts;

use Irisit\Filestash\Services\Mounts\Interfaces\MountInterface;
use League\Flysystem\AdapterInterface;
use League\Flysystem\Filesystem;

class UserFS implements MountInterface
{
    private $filesystem;
    private $adapter;

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
    public function __construct(AdapterInterface $adapter)
    {
        $this->adapter = $adapter;
        $this->filesystem = new Filesystem($this->adapter);
    }

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
     */
    public function list($data)
    {
        // TODO: Implement list() method.
    }

    /**
     * Get the file contents
     *
     * required param string $path
     * optional param bool $base64 = false
     *
     * returns file content as string or base64
     *
     * @param $data
     */
    public function read($data)
    {
        // TODO: Implement read() method.
    }

    /**
     * Get the total size of files/folders within a given folder.
     *
     * optional param string $path = DIRECTORY_SEPARATOR
     * optional param bool $recursive = true
     *
     * return directory size in octet
     *
     * @param $data
     */
    public function directory_size($data)
    {
        // TODO: Implement directory_size() method.
    }

    /**
     * Check if the file/folder has a permission of a file/folder or not.
     *
     * required param $identifier ( user identifier )
     * required param $groups ( user groups list )
     * required param $action [visible, display, read, write, delete, share]
     * required param $path
     *
     * @param $data
     */
    public function check_permission($data)
    {
        // TODO: Implement check_permission() method.
    }

    /**
     * Get the permissions for a given path
     *
     * required param $identifier ( user identifier )
     * required param $groups ( user groups list )
     * required param $path
     *
     * @param $data
     */
    public function get_permission($data)
    {
        // TODO: Implement get_permission() method.
    }

    /**
     * Upload a file.
     *
     * required param string $destination
     *
     * @param $data
     */
    public function upload($data)
    {
        // TODO: Implement upload() method.
    }

    /**
     * Download files, zip is made if more than one file
     *
     * required param array $paths
     *
     * @param $data
     */
    public function download_files($data)
    {
        // TODO: Implement download_files() method.
    }

    /**
     * Download folders as zip
     *
     * required param string $path
     * optional param bool $recursive = false
     *
     * @param $data
     */
    public function download_directory($data)
    {
        // TODO: Implement download_directory() method.
    }

    /**
     * Generate a sharing link to share files/folders with other
     * people and perform operations on sharing links.
     *
     * required param string $path
     * required param string $type [users,groups]
     * required param array $identifiers
     *
     * @param $data
     */
    public function share($data)
    {
        // TODO: Implement share() method.
    }

    /**
     * Create folder.
     *
     * required param string $path
     *
     * @param $data
     */
    public function make_directory($data)
    {
        // TODO: Implement make_directory() method.
    }

    /**
     * Rename a file/folder.
     *
     * required param string $origin
     * required param string $name
     *
     * @param $data
     */
    public function rename($data)
    {
        // TODO: Implement rename() method.
    }

    /**
     * Copy files/folders.
     *
     * required param string $origin
     * required param string $destination
     *
     * @param $data
     */
    public function copy($data)
    {
        // TODO: Implement copy() method.
    }

    /**
     * Move files/folders
     *
     * required param string $origin
     * required param string $destination
     *
     * @param $data
     */
    public function move($data)
    {
        // TODO: Implement move() method.
    }

    /**
     * Delete files/folders.
     *
     * required param string $path
     *
     * @param $data
     */
    public function delete($data)
    {
        // TODO: Implement delete() method.
    }
}