<?php

namespace Irisit\AuthzLdap\Console\Commands;

use App\User;
use Irisit\AuthzLdap\Models\Role;
use Illuminate\Console\Command;

use League\CLImate\CLImate;

class SetUserAsAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lmod_authz:promote_user_admin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import Ldap groups and map them to the users';


    /**
     * Instance of command line utilities tool
     *
     * @var CLImate
     */
    private $climate;

    /**
     * Create a new command instance.
     *
     * @param CLImate $climate
     */
    public function __construct(CLImate $climate)
    {
        parent::__construct();

        $this->climate = $climate;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $username = $this->ask('Sam Account Name of the user');

        $user = User::where('username', $username)->first();

        if ($user) {

            $this->climate->green('User found')->br();

            if ($user->hasRole('admin')) {

                $this->climate->red($user->firstname . ' ' . $user->lastname . ' already an Admin');

            } else {

                $role = Role::findOrFail(1);

                $user->roles()->save($role);

                $this->climate->green($user->firstname . ' ' . $user->lastname . ' is promoted as Admin');
            }

        } else {

            $this->climate->red('No user found');

        }

    }


}