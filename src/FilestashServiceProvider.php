<?php

namespace Irisit\Filestash;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class FilestashServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        /*
         * Package Loading Stuff
         */
        $this->publishes([__DIR__ . '/../config/filestash.php' => config_path('irisit_filestash.php')], 'irisit-filestash-config');

//        $this->publishes([__DIR__ . '/../database/seeds/RoleTableSeeder.php' => database_path('seeds/RoleTableSeeder.php')], 'irisit-authz-seeder');
//
//        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
//
//        $this->loadViewsFrom(__DIR__ . '/../resources/views/' . config('irisit_authz.base_theme'), 'authz');

        $this->loadRoutesFrom(__DIR__ . '/routes.php');

    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/filestash.php', 'irisit_filestash');
//
//        $this->app->register('Irisit\AuthzLdap\Providers\PermissionsServiceProvider');
//
//        $this->app->register('Collective\Html\HtmlServiceProvider');
//
//        $this->app->alias('Form', 'Collective\Html\FormFacade');
//
//        $this->app->alias('Html', 'Collective\Html\HtmlFacade');
//
//        $this->commands([ParsePermissions::class, ImportAndMapLdapGroups::class, SetUserAsAdmin::class, ReplaceAllPasswords::class]);
    }

}