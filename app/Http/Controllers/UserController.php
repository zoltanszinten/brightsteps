<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function user(Request $request)
    {
        $user = $request->user();

        $roles = $user->getRoleNames();
        $permissions = $user->getAllPermissions()
            ->pluck('name')
            ->values();

        return response()->json([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'type' => $user->type,
            ],
            'roles' => $roles,
            'permissions' => $permissions,
        ]);
    }
}
