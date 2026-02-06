<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;

class ProfilePage extends Component
{
    use WithFileUploads;

    public $full_name = '';
    public $phone = '';
    public $password = '';
    public $password_confirmation = '';
    public $photo;

    protected function rules()
    {
        return [
            'full_name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:50',
            'password' => 'nullable|string|min:6|confirmed',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120'
        ];
    }

    public function mount()
    {
        $user = Auth::user();
        $this->full_name = $user?->full_name ?? '';
        $this->phone = $user?->phone ?? '';
    }

    public function save()
    {
        $data = $this->validate();
        $user = Auth::user();
        if (!$user) {
            return;
        }
        $payload = [
            'full_name' => $data['full_name'],
            'phone' => $data['phone']
        ];
        if (!empty($data['password'])) {
            $payload['password_hash'] = $data['password'];
        }
        if (!empty($data['photo'])) {
            $path = $data['photo']->store('profile-photos', 'media');
            $payload['profile_photo_url'] = url('media/'.$path);
        }
        $user->update($payload);
        $this->password = '';
        $this->password_confirmation = '';
        $this->photo = null;
        session()->flash('message', 'Profile updated');
    }

    public function render()
    {
        return view('livewire.profile-page')->layout('layouts.app', [
            'title' => 'Profile',
            'subtitle' => 'Kelola data akun anda.'
        ]);
    }
}
