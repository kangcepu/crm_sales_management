<?php

use App\Livewire\Auth\Login;
use App\Livewire\Dashboard;
use App\Livewire\DealsPage;
use App\Livewire\ProfilePage;
use App\Livewire\SettingsPage;
use App\Livewire\ReportTrackingPage;
use App\Livewire\RolesPage;
use App\Livewire\AreasPage;
use App\Livewire\AreaMappingPage;
use App\Livewire\StoreAssignmentsPage;
use App\Livewire\StoreConditionsPage;
use App\Livewire\StoreMediaPage;
use App\Livewire\StoreStatusHistoryPage;
use App\Livewire\StoreVisitReportsPage;
use App\Livewire\StoreVisitsPage;
use App\Livewire\StoresPage;
use App\Livewire\UsersPage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/login', Login::class)->name('login')->middleware('guest');

Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect()->route('login');
})->name('logout')->middleware('auth');

Route::middleware(['auth'])->group(function () {
    Route::get('/', Dashboard::class)->name('dashboard');

    Route::get('/users', UsersPage::class)->name('users')->middleware('permission:users.manage');
    Route::get('/roles', RolesPage::class)->name('roles')->middleware('permission:roles.manage');
    Route::get('/settings', SettingsPage::class)->name('settings')->middleware('permission:settings.manage');

    Route::get('/stores', StoresPage::class)->name('stores')->middleware('permission:stores.manage');
    Route::get('/assignments', StoreAssignmentsPage::class)->name('assignments')->middleware('permission:assignments.manage');
    Route::get('/areas', AreasPage::class)->name('areas')->middleware('permission:areas.manage');
    Route::get('/area-mapping', AreaMappingPage::class)->name('area-mapping')->middleware('permission:area_mapping.view');
    Route::get('/status-history', StoreStatusHistoryPage::class)->name('status-history')->middleware('permission:status_history.manage');

    Route::get('/visits', StoreVisitsPage::class)->name('visits')->middleware('permission:visits.manage');
    Route::get('/visit-reports', StoreVisitReportsPage::class)->name('visit-reports')->middleware('permission:visit_reports.manage');
    Route::get('/report-tracking', ReportTrackingPage::class)->name('report-tracking')->middleware('permission:report_tracking.view');
    Route::get('/conditions', StoreConditionsPage::class)->name('conditions')->middleware('permission:conditions.manage');
    Route::get('/store-media', StoreMediaPage::class)->name('media')->middleware('permission:media.manage');
    Route::get('/deals', DealsPage::class)->name('deals')->middleware('permission:deals.manage');

    Route::get('/profile', ProfilePage::class)->name('profile');
});
