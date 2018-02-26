<?php

use Irisit\AuthzLdap\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the users seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->delete();
        $statement = "ALTER TABLE roles AUTO_INCREMENT = 1;";
        DB::unprepared($statement);


        $roles = [[
            'id' => '1',
            'name' => 'admin',
            'label' => 'Local - Administrateur',
            'description' => 'Administrateur local'
        ]];

        foreach ($roles as $role) {
            Role::create($role);
        }


    }
}