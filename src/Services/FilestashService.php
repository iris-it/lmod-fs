<?php

namespace Irisit\Filestash\Services;

use Irisit\Filestash\Services\Mounts\Interfaces\MountInterface;
use League\Fractal\Manager;
use League\Fractal\Resource\ResourceAbstract;
use League\Fractal\Serializer\DataArraySerializer;
use Symfony\Component\HttpKernel\Exception\HttpException;


class FilestashService
{

    /**
     * @var MountInterface
     */
    private $mount;

    /**
     * @var Manager
     */
    private $output_manager;

    /**
     * @var array
     */
    private $allowed_methods = [
        'list' => ['GET']
    ];

    /**
     * @var array
     */
    private $allowed_function = [
        'list' => ['*'],
        'check_permission' => ['*'],
        'get_permission' => ['admin'],
        'set_permission' => ['admin'],
    ];

    /**
     * The mount interface must be an implementation of the MountInterface contract
     *
     * @param MountInterface $mount
     */
    public function __construct(MountInterface $mount)
    {
        $this->mount = $mount;

        $this->output_manager = new Manager();

        $this->output_manager->setSerializer(new DataArraySerializer());
    }

    /**
     * This method is used to call specific VFS methods
     * There is a need to dynamically call the function
     * because we can apply many middleware like :
     * - create file objects if they not exist and retrieve 'em
     * - check if the user has rights to make an action like read or write
     *
     * @param $user ( with string identifier / array of roles names )
     * @param $method
     * @param $function
     * @param array $data
     * @return array
     */
    public function call($user, $method, $function, array $data = [])
    {

        if (!array_key_exists($function, $this->allowed_methods)) {
            throw new HttpException(501, 'Remote function does not exists');
        }

        if (empty($this->allowed_methods[$function])) {
            throw new HttpException(501, 'Remote allowed HTTP methods are not defined');
        }

        if (!in_array($method, $this->allowed_methods[$function])) {
            throw new HttpException(403, 'HTTP Method not allowed');
        }

        if (!in_array('*', $this->allowed_function[$function])) {
            if (sizeof(array_intersect($user['roles'], $this->allowed_function[$function])) === 0) {
                throw new HttpException(403, 'Unauthorized');
            }
        }

        $data = array_merge($user, $data);

        $output = $this->mount->{$function}($data);

        if ($output instanceof ResourceAbstract) {
            return $this->output_manager->createData($output)->toArray();
        }

        return $output;

    }

    /**
     * @return array
     */
    public function getAllowedMethods(): array
    {
        return $this->allowed_methods;
    }

    /**
     * @param array $allowed_methods
     */
    public function setAllowedMethods(array $allowed_methods)
    {
        $this->allowed_methods = $allowed_methods;
    }

    /**
     * @return array
     */
    public function getAllowedFunction(): array
    {
        return $this->allowed_function;
    }

    /**
     * @param array $allowed_function
     */
    public function setAllowedFunction(array $allowed_function)
    {
        $this->allowed_function = $allowed_function;
    }

}