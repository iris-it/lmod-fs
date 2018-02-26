<?php
/**
 * Created by PhpStorm.
 * User: alexa
 * Date: 12/02/2018
 * Time: 13:07
 */

namespace Irisit\Filestash\Services\Mounts;


use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Irisit\Filestash\Helpers\DirectoryHelper;
use Irisit\Filestash\Helpers\FileHelper;
use Irisit\Filestash\Helpers\FilterHelper;
use Irisit\Filestash\Helpers\TreeHelper;
use Irisit\Filestash\Services\Mounts\Interfaces\MountInterface;
use Irisit\Filestash\Services\Plugins\GetDirectoryProperties;
use Irisit\Filestash\Transformers\FileTransformer;
use Irisit\Filestash\Transformers\RawTransformer;
use League\Flysystem\AdapterInterface;
use League\Flysystem\Filesystem;
use League\Flysystem\Plugin\ListPaths;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use Symfony\Component\HttpKernel\Exception\HttpException;

class GroupFS implements MountInterface
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

        $this->filesystem->addPlugin(new ListPaths());
        $this->filesystem->addPlugin(new GetDirectoryProperties());
    }

    /**
     * List all folders, enumerate files in a folder,
     * and get detailed file information.
     *
     * required param string $identifier ( user identifier )
     * required param array $groups ( user groups list )
     * optional param bool $is_admin
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
    public function list($data)
    {

        $validator = Validator::make($data, [
            'identifier' => 'required|string',
            'groups' => 'required|array',
            'is_admin' => 'nullable|boolean',
            'path' => 'nullable|string',
            'type' => ['nullable', Rule::in(['file', 'dir', 'all'])],
            'recursive' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            throw new HttpException(400, $validator->errors());
        }

        $path = $data['path'] ?? DIRECTORY_SEPARATOR;
        $type = $data['type'] ?? 'all';
        $recursive = $data['recursive'] ?? false;

        $path = DirectoryHelper::preventTraversal($path);

        $items = $this->filesystem->listContents($path, $recursive);

        $items = FilterHelper::itemNotDotFile($items);

        $items = FilterHelper::itemType($items, $type);

        $items = FileHelper::addMetadata($items, $this->filesystem);

        $items = FilterHelper::itemAuthorized($items, $data['identifier'], $data['groups'], $data['is_admin']);

        $items = TreeHelper::build($items, $path);


        return new Collection($items, new FileTransformer());
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
     * Check if the user has a permission on a file/folder or not.
     *
     * required param string $identifier ( user identifier )
     * required param array $groups ( user groups list )
     * optional param bool $is_admin
     * required param string $action [visible, display, read, write, delete, share]
     * required param string $path
     *
     * @param $data
     * @return Item
     */
    public function check_permission($data)
    {

        $validator = Validator::make($data, [
            'identifier' => 'required|string',
            'groups' => 'required|array',
            'is_admin' => 'nullable|boolean',
            'action' => ['required', Rule::in(['visible', 'display', 'read', 'write', 'delete', 'share'])],
            'path' => 'required|string',
        ]);

        if ($validator->fails()) {
            throw new HttpException(400, $validator->errors());
        }

        if ($data['is_admin'] === true) {
            return new Item('OK', new RawTransformer());
        }

        $properties = $this->filesystem->getDirectoryProperties($data['path']);

        if ($data['identifier'] === $properties['owner']) {
            return new Item('OK', new RawTransformer());
        }

        if (sizeof(array_intersect($data['groups'], $properties[$data['action']])) > 0) {
            return new Item('OK', new RawTransformer());
        }

        throw new HttpException(403, 'Unauthorized');

    }

    /**
     * Get the permissions for a given path
     *
     * required param $path
     *
     * @param $data
     * @return Item
     */
    public function get_properties($data)
    {
        $validator = Validator::make($data, [
            'path' => 'required|string',
        ]);

        if ($validator->fails()) {
            throw new HttpException(400, $validator->errors());
        }

        $data = $this->filesystem->getDirectoryProperties($data['path']);

        return new Item($data, new RawTransformer());
    }

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
    public function set_properties($data)
    {
        $validator = Validator::make($data, [
            'key' => 'required|string',
            'value' => 'required',
            'path' => 'required',
        ]);

        if ($validator->fails()) {
            throw new HttpException(400, $validator->errors());
        }

        if ($this->filesystem->setDirectoryProperties($data['path'], $data['key'], $data['value'])) {
            return new Item('OK', new RawTransformer());
        }

        throw new HttpException(400, 'Schema not valid');
    }

    /**
     * Upload a file.
     *
     * required param string $destination
     *
     * @param $data
     * @return Item
     */
    public function upload($data)
    {
        $this->abortIfNotMethod($data->method(), ['POST', 'PUT']);

        $path = '';

        $files = $data->file();

        if ($data->has('to')) {
            $path = $this->preventTraversal($data->get('to'));
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

        return new Item('OK', new RawTransformer());
    }

    /**
     * Download files, zip is made if more than one file
     *
     * required param array $paths
     *
     * @param $data
     * @return BinaryFileResponse
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
     * @return BinaryFileResponse
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
     * @return Item
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
     * @return Item
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
     * @return Item
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
     * @return Item
     */
    public function delete($data)
    {
        // TODO: Implement delete() method.
    }

}