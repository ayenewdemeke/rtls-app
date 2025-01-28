<?php

namespace App\Policies;

use App\Models\Role;
use App\Models\User;
use App\Models\WorkZone;
use Illuminate\Auth\Access\Response;

class WorkZonePolicy
{
    public function create(User $user): Response
    {
        $roles = $user->roles->pluck('id')->toArray();
        return in_array(Role::ADMIN, $roles)
            ? Response::allow()
            : Response::deny('You must be an admin to add a work zone.');
    }

    public function update(User $user, WorkZone $workZone): Response
    {
        return $user->id === $workZone->user_id
            ? Response::allow()
            : Response::deny('You do not have permission to update this work zone.');
    }

    public function delete(User $user, WorkZone $workZone): Response
    {
        return $user->id === $workZone->user_id
            ? Response::allow()
            : Response::deny('You do not have permission to delete this work zone.');
    }
}
