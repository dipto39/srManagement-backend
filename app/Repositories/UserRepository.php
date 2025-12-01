<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository
{
    public function activeUsers()
    {
        return User::where('status', 'active')
                    ->orderBy('name')
                    ->get();
    }
    public function getAllUsers()
    {
        return User::all(); 
    }
}
