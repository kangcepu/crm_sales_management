<?php

namespace App\Livewire;

use App\Models\Deal;
use App\Models\Store;
use App\Models\StoreVisit;
use App\Models\User;
use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        $start = now()->startOfWeek();
        $end = now()->endOfWeek();

        return view('livewire.dashboard', [
            'stats' => [
                'users' => User::count(),
                'stores' => Store::where('is_active', true)->count(),
                'visits' => StoreVisit::whereBetween('visit_at', [$start, $end])->count(),
                'deals' => Deal::whereIn('stage', ['PROSPECT', 'NEGOTIATION', 'WON'])->count(),
            ],
            'recentVisits' => StoreVisit::with(['store', 'user'])->orderByDesc('visit_at')->take(5)->get(),
            'recentDeals' => Deal::with(['store', 'owner'])->orderByDesc('created_at')->take(5)->get(),
        ])->layout('layouts.app', [
            'title' => 'Dashboard',
            'subtitle' => 'Selamat datang kembali! Berikut ringkasan performa hari ini.'
        ]);
    }
}
