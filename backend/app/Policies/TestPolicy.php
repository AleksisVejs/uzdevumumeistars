<?php

namespace App\Policies;

use App\Models\Test;
use App\Models\User;

class TestPolicy
{
    public function view(User $user, Test $test): bool
    {
        return (int)$test->user_id === (int)$user->id;
    }

    public function update(User $user, Test $test): bool
    {
        return (int)$test->user_id === (int)$user->id;
    }
}


