<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

use Laravel\Passport\Passport;
use Carbon\Carbon;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        Passport::routes();
        Passport::tokensExpireIn(Carbon::now()->addseconds(10));
        Passport::refreshTokensExpireIn(Carbon::now()->addDays(1));

        /* define a SuperAdmin and admin a role */
        Gate::define('SuperAdminAndAdmin', function($user) {
            $role_name = ['SuperAdmin','Admin'];
           if(in_array($user->role->name, $role_name))
           {
               return $user->role->name;
           }
        });

        /* define a Developer and SuperAdmin role */
        Gate::define('SuperadminAndDeveloper', function($user) {
            $role_name = ['Developer','SuperAdmin'];
           if(in_array($user->role->name, $role_name))
           {
               return $user->role->name;
           }
        });

        /* define a Developer and SuperAdmin role */
        Gate::define('Superadmin', function($user) {
            $role_name = ['SuperAdmin'];
           if(in_array($user->role->name, $role_name))
           {
               return $user->role->name;
           }
        });

       
    }
}
