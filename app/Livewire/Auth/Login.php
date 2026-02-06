<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Login extends Component
{
    public $email = '';
    public $password = '';

    protected function rules()
    {
        return [
            'email' => 'required|email',
            'password' => 'required|string'
        ];
    }

    public function login()
    {
        $data = $this->validate();
        if (Auth::attempt(['email' => $data['email'], 'password' => $data['password'], 'is_active' => true])) {
            session()->regenerate();
            return redirect()->route('dashboard');
        }
        $this->addError('email', 'Invalid credentials');
    }

    public function render()
    {
        return view('livewire.auth.login')->layout('layouts.auth');
    }
}
