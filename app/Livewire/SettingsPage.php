<?php

namespace App\Livewire;

use App\Models\Setting;
use Livewire\Component;
use Livewire\WithFileUploads;

class SettingsPage extends Component
{
    use WithFileUploads;

    public $site_title = '';
    public $site_description = '';
    public $logo_upload;
    public $favicon_upload;

    protected function rules()
    {
        return [
            'site_title' => 'required|string|max:100',
            'site_description' => 'nullable|string|max:160',
            'logo_upload' => 'nullable|file|mimes:png,jpg,jpeg,webp,svg|max:5120',
            'favicon_upload' => 'nullable|file|mimes:png,ico|max:1024'
        ];
    }

    public function mount()
    {
        $this->site_title = Setting::getValue('site_title', 'CR Sales');
        $this->site_description = Setting::getValue('site_description', 'Enterprise CRM System');
    }

    public function save()
    {
        $data = $this->validate();

        Setting::setValue('site_title', $data['site_title']);
        Setting::setValue('site_description', $data['site_description'] ?? '');

        if ($this->logo_upload) {
            $path = $this->logo_upload->store('settings', 'media');
            Setting::setValue('site_logo', url('media/'.$path));
        }

        if ($this->favicon_upload) {
            $path = $this->favicon_upload->store('settings', 'media');
            Setting::setValue('site_favicon', url('media/'.$path));
        }

        session()->flash('message', 'Settings updated');
    }

    public function render()
    {
        return view('livewire.settings-page', [
            'currentLogo' => Setting::getValue('site_logo'),
            'currentFavicon' => Setting::getValue('site_favicon')
        ])->layout('layouts.app', [
            'title' => 'Settings',
            'subtitle' => 'Kelola identitas aplikasi.'
        ]);
    }
}
