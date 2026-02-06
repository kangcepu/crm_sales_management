<?php

namespace App\Livewire;

use App\Models\ActivityLog;
use App\Models\Store;
use App\Models\StoreMedia;
use App\Models\StoreVisitReport;
use App\Models\StoreVisitReportMedia;
use Livewire\Component;

class ReportTrackingPage extends Component
{
    public $storeId = '';

    protected $queryString = ['storeId'];

    public function mount()
    {
        if (!$this->storeId) {
            $first = Store::orderBy('store_name')->first();
            $this->storeId = $first?->id;
        }
    }

    public function render()
    {
        $stores = Store::orderBy('store_name')->get();
        $summary = (object) [
            'reports' => 0,
            'consignment_qty' => 0,
            'consignment_value' => 0,
            'sales_qty' => 0,
            'sales_value' => 0
        ];
        $reports = collect();
        $storeMedia = collect();
        $reportMedia = collect();
        $logs = collect();

        if ($this->storeId) {
            $summaryRow = StoreVisitReport::query()
                ->join('store_visits', 'store_visits.id', '=', 'store_visit_reports.visit_id')
                ->where('store_visits.store_id', $this->storeId)
                ->selectRaw('count(*) as reports, coalesce(sum(consignment_qty),0) as consignment_qty, coalesce(sum(consignment_value),0) as consignment_value, coalesce(sum(sales_qty),0) as sales_qty, coalesce(sum(sales_value),0) as sales_value')
                ->first();

            if ($summaryRow) {
                $summary = $summaryRow;
            }

            $reports = StoreVisitReport::with(['visit.user', 'media'])
                ->whereHas('visit', fn ($query) => $query->where('store_id', $this->storeId))
                ->orderByDesc('id')
                ->take(10)
                ->get();

            $storeMedia = StoreMedia::with('visit')
                ->whereHas('visit', fn ($query) => $query->where('store_id', $this->storeId))
                ->orderByDesc('id')
                ->take(12)
                ->get();

            $reportMedia = StoreVisitReportMedia::with('report.visit')
                ->whereHas('report.visit', fn ($query) => $query->where('store_id', $this->storeId))
                ->orderByDesc('id')
                ->take(12)
                ->get();

            $reportIds = StoreVisitReport::whereHas('visit', fn ($query) => $query->where('store_id', $this->storeId))
                ->pluck('id');

            $storeMediaIds = StoreMedia::whereHas('visit', fn ($query) => $query->where('store_id', $this->storeId))
                ->pluck('id');

            if ($reportIds->isNotEmpty() || $storeMediaIds->isNotEmpty()) {
                $logsQuery = ActivityLog::with('user')->orderByDesc('created_at');
                $logsQuery->where(function ($query) use ($reportIds, $storeMediaIds) {
                    if ($reportIds->isNotEmpty()) {
                        $query->orWhere(function ($sub) use ($reportIds) {
                            $sub->where('entity_type', 'store_visit_report')
                                ->whereIn('entity_id', $reportIds);
                        });
                    }
                    if ($storeMediaIds->isNotEmpty()) {
                        $query->orWhere(function ($sub) use ($storeMediaIds) {
                            $sub->where('entity_type', 'store_media')
                                ->whereIn('entity_id', $storeMediaIds);
                        });
                    }
                });
                $logs = $logsQuery->take(40)->get();
            }
        }

        return view('livewire.report-tracking-page', [
            'stores' => $stores,
            'summary' => $summary,
            'reports' => $reports,
            'storeMedia' => $storeMedia,
            'reportMedia' => $reportMedia,
            'logs' => $logs
        ])->layout('layouts.app', [
            'title' => 'Report Tracking',
            'subtitle' => 'Summary, media, and updates per store'
        ]);
    }
}
