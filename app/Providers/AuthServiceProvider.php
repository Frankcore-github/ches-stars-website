<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('isAdmin', function($user){
            return $user->usertype == 'admin';
        });

        Gate::define('isStaff', function($user){
            return $user->usertype == 'staff';
        });

        Gate::define('isStudent', function($user){
            return $user->usertype == 'student';
        });

        Gate::define('allowedExam', function($user){
            return $user->student->examstatus == 'allowed';
        });

        Gate::define('all', function($user){
            if($user->usertype == 'admin' || $user->usertype == 'staff' || $user->usertype == 'student'){
                return true;
            }else {
                return false;
            }
        });
        Gate::define('notAdmin', function($user){
            if($user->usertype == 'staff' || $user->usertype == 'student'){
                return true;
            }else {
                return false;
            }
        });


        Passport::routes();
    }
}
