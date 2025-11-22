<?php

namespace App\Actions;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class UpdateProfileAction
{
    public function execute(User $user, array $data): User
    {
        if (isset($data['password']) && $data['password']) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        // Only update columns that exist and are fillable to avoid SQL errors
        $fillable = $user->getFillable();
        $updates = [];
        foreach ($data as $key => $value) {
            if (in_array($key, $fillable, true) && Schema::hasColumn('users', $key)) {
                $updates[$key] = $value;
            }
        }

        if (!empty($updates)) {
            $user->update($updates);
        }

        return $user;
    }
}
