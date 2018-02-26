<?php

namespace Irisit\AuthzLdap\Http\Controllers\Admin;

use App\User;
use Exception;
use Illuminate\Support\Facades\Artisan;
use Irisit\AuthzLdap\Http\Requests\Admin\AdminUserRoleRequest;
use Irisit\AuthzLdap\Models\Role;

use Irisit\AuthzLdap\Notifications\NewAccount;
use Irisit\AuthzLdap\Services\PasswordGenService;
use Laracasts\Flash\Flash;
use Irisit\AuthzLdap\Http\Controllers\Controller;

class UsersController extends Controller
{

    public function index()
    {
        $users = User::paginate(5);

        return view('authz::admin.users.index')->with(compact('users'));
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);

        $roles = Role::pluck('name', 'id');

        return view('authz::admin.users.edit')->with(compact('user', 'roles'));
    }

    public function update(AdminUserRequest $request, $id)
    {
        $user = User::findOrFail($id);

        $data = $request->all();

        if ($user->update($data)) {
            Flash::success(__('Update user success'));
        } else {
            Flash::error(__('Update user failed'));
        }

        return redirect(route('authz.admin_index_users', $id));
    }

    public function delete($id)
    {
        $user = User::findOrFail($id);

        return view('authz::admin.users.delete')->with(compact('user'));

    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if ($user->delete()) {
            Flash::success(__('Delete user success'));
        } else {
            Flash::error(__('Delete user failed'));
        }

        return redirect(route('authz.admin_index_users'));

    }

    public function syncRoles(AdminUserRoleRequest $request, $id)
    {
        $user = User::findOrFail($id);

        $data = $request->all();

        if (!$request->has("roles")) {
            $data["roles"] = [];
        }

        if ($user->roles()->sync($data["roles"])) {
            Flash::success(__('Update user success'));
        } else {
            Flash::error(__('Update user failed'));
        }

        return redirect(route('authz.admin_index_users'));

    }

    public function triggerLdapSync()
    {
        try {
            define('STDOUT', fopen('php://stdout', 'w'));
            Artisan::call('adldap:import', ['--no-interaction']);
        } catch (Exception $e) {
            Flash::error(__('Ldap sync failed'));
        }
        Flash::success(__('Ldap sync success'));

        return redirect(route('authz.admin_index_users'));
    }


}
