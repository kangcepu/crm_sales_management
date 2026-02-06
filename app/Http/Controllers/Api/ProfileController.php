<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function show(Request $request)
    {
        return $request->user()->load('roles');
    }

    public function update(Request $request)
    {
        $user = $request->user();
        $data = $request->validate([
            'full_name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:50',
            'password' => 'nullable|string|min:6',
            'photo' => 'nullable|file|mimes:jpg,jpeg,png,webp|max:5120'
        ]);

        $payload = [];
        if (array_key_exists('full_name', $data)) {
            $payload['full_name'] = $data['full_name'];
        }
        if (array_key_exists('phone', $data)) {
            $payload['phone'] = $data['phone'];
        }
        if (!empty($data['password'])) {
            $payload['password_hash'] = Hash::make($data['password']);
        }

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('profile-photos', 'media');
            $payload['profile_photo_url'] = url('media/'.$path);
        }

        if (!empty($payload)) {
            $user->update($payload);
        }

        return $user->load('roles');
    }
}
