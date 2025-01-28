<?php

namespace App\Policies;

use App\Models\Device;
use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class DevicePolicy
{
    public function manage(User $user): Response
    {
        $roles = $user->roles->pluck('id')->toArray();
        return in_array(Role::ADMIN, $roles)
            ? Response::allow()
            : Response::deny('You must be an admin to do this.');
    }
}
