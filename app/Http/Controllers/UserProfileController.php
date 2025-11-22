<?php

namespace App\Http\Controllers;

use App\Actions\UpdateProfileAction;
use App\Http\Requests\UpdateProfileRequest;

class UserProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function update(UpdateProfileRequest $request, UpdateProfileAction $action)
    {
        $user = $request->user();
        $data = $request->validated();

        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('avatars', 'public');
            $data['avatar'] = $path;
        }

        $user = $action->execute($user, $data);

        return response()->json($user);
    }
}
