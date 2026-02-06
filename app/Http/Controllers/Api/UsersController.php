<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UsersController extends Controller
{
    public function index()
    {
        return User::with('roles')->orderByDesc('created_at')->get();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'phone' => 'nullable|string|max:50',
            'profile_photo_url' => 'nullable|string|max:255',
            'password_hash' => 'required|string|min:6',
            'is_active' => 'boolean',
            'role_ids' => 'array',
            'role_ids.*' => 'exists:roles,id'
        ]);

        $roleIds = $data['role_ids'] ?? [];
        unset($data['role_ids']);

        $user = User::create($data);
        $user->roles()->sync($roleIds);

        return response()->json($user->load('roles'), 201);
    }

    public function show(User $user)
    {
        return $user->load('roles');
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'phone' => 'nullable|string|max:50',
            'profile_photo_url' => 'nullable|string|max:255',
            'password_hash' => 'nullable|string|min:6',
            'is_active' => 'boolean',
            'role_ids' => 'array',
            'role_ids.*' => 'exists:roles,id'
        ]);

        $roleIds = $data['role_ids'] ?? null;
        unset($data['role_ids']);

        if (empty($data['password_hash'])) {
            unset($data['password_hash']);
        }

        $user->update($data);

        if ($roleIds !== null) {
            $user->roles()->sync($roleIds);
        }

        return $user->load('roles');
    }

    public function destroy(User $user)
    {
        $user->roles()->detach();
        $user->delete();
        return response()->json(['message' => 'Deleted']);
    }
}
