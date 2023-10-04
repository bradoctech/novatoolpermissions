<?php

namespace Bradoctech\NovaToolPermissions\Providers;

use Bradoctech\Brandenburg\Role;
use Illuminate\Support\Facades\Gate;
use Bradoctech\NovaToolPermissions\Policies\RolePolicy;
use Bradoctech\NovaToolPermissions\Policies\UserPolicy;
use Bradoctech\Brandenburg\Traits\ValidatesPermissions;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    use ValidatesPermissions;

    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Role::class => RolePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->policies[config('brandenburg.userModel')] = UserPolicy::class;

        $this->registerPolicies();
        $this->defineGates();
    }

    private function defineGates()
    {
        collect([
            'assignRoles',
            'manageRoles',
            'manageUsers',
            'viewRoles',
            'viewUsers',
            'viewNova',
            'canBeGivenAccess',
        ])->each(function ($permission) {
            Gate::define($permission, function ($user) use ($permission) {
                if ($this->nobodyHasAccess($permission)) {
                    return true;
                }

                return $user->hasRoleWithPermission($permission);
            });
        });
    }
}
