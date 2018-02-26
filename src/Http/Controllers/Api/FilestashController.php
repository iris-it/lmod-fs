<?php

namespace Irisit\Filestash\Http\Controllers\Api;

use Illuminate\Http\Request;

use Irisit\Filestash\Http\Controllers\Controller;
use Irisit\Filestash\Services\FilestashService;
use Irisit\Filestash\Services\Mounts\GroupFS;
use Irisit\Filestash\Services\Mounts\ShareFS;
use Irisit\Filestash\Services\Mounts\UserFS;
use League\Flysystem\Adapter\Local;


class FilestashController extends Controller
{

    public function info()
    {
        return [
            'api' => '0.1.0',
            'provider' => 'filestash'
        ];
    }

    /**
     * This method is a commander for all the specific vfs methods
     * there is a differentiation between mounts ( home / groups / shared )
     * because they has not the same behavior
     *
     * if an array is provided as response, json will be return
     *
     *
     * @param Request $request
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function handleRequests(Request $request)
    {
        $data = $request->all();

        $method = $request->method();

        $mount = $request->get('mount');

        $function = $request->get('function');

        $user = [
            'identifier' => 'alex',
            'groups' => ['group_1', 'group_2'],
            'is_admin' => true
        ];

        switch ($mount) {
            case 'group':
                $filestashService = new FilestashService(new GroupFS(new Local(config('irisit_filestash.mounts.group.root'))));
                return $filestashService->call($user, $method, $function, $data);

            case 'user' :
                $filestashService = new FilestashService(new UserFS(new Local(config('irisit_filestash.mounts.user.root'))));
                return $filestashService->call($user, $method, $function, $data);

            case 'share':
                $filestashService = new FilestashService(new ShareFS());
                return $filestashService->call($user, $method, $function, $data);
        }

        return abort(404);
    }


}
