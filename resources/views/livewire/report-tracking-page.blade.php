<div class="page">
    <div class="card">
        <div class="form-grid">
            <div class="form-group">
                <label class="label">Store</label>
                <select class="select" wire:model="storeId">
                    <option value="">Select store</option>
                    @foreach($stores as $store)
                        <option value="{{ $store->id }}">{{ $store->store_name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <div class="stats-grid">
        <div class="card stat-card">
            <div class="stat-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path d="M16 11a4 4 0 1 0-8 0 4 4 0 0 0 8 0z"/>
                    <path d="M6 20a6 6 0 0 1 12 0"/>
                </svg>
            </div>
            <div class="stat-value">{{ $summary->reports ?? 0 }}</div>
            <div class="stat-label">Total Reports</div>
            <div class="badge info">Store summary</div>
        </div>
        <div class="card stat-card">
            <div class="stat-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path d="M4 7h16M4 12h16M4 17h16"/>
                </svg>
            </div>
            <div class="stat-value">{{ number_format($summary->consignment_qty ?? 0) }}</div>
            <div class="stat-label">Consignment Qty</div>
            <div class="badge info">Total</div>
        </div>
        <div class="card stat-card">
            <div class="stat-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <circle cx="12" cy="12" r="9"/>
                    <path d="M9 12h6M12 7v10"/>
                </svg>
            </div>
            <div class="stat-value">{{ number_format($summary->consignment_value ?? 0, 2) }}</div>
            <div class="stat-label">Consignment Value</div>
            <div class="badge info">Total</div>
        </div>
        <div class="card stat-card">
            <div class="stat-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path d="M4 7h16M4 12h16M4 17h16"/>
                    <path d="M9 7v10"/>
                </svg>
            </div>
            <div class="stat-value">{{ number_format($summary->sales_qty ?? 0) }}</div>
            <div class="stat-label">Sales Qty</div>
            <div class="badge info">Total</div>
        </div>
        <div class="card stat-card">
            <div class="stat-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <circle cx="12" cy="12" r="9"/>
                    <path d="M9 12h6M12 7v10"/>
                </svg>
            </div>
            <div class="stat-value">{{ number_format($summary->sales_value ?? 0, 2) }}</div>
            <div class="stat-label">Sales Value</div>
            <div class="badge info">Total</div>
        </div>
    </div>

    <div class="card">
        <div class="table-header">
            <div>
                <div class="section-title">Recent Reports</div>
                <div class="section-sub">Latest visit reports for this store</div>
            </div>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>Visit Time</th>
                    <th>Visitor</th>
                    <th>Sales</th>
                    <th>Consignment</th>
                    <th>Payment</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reports as $report)
                    <tr>
                        <td>{{ $report->visit?->visit_at }}</td>
                        <td>{{ $report->visit?->user?->full_name }}</td>
                        <td>{{ number_format($report->sales_qty) }} ({{ number_format($report->sales_value, 2) }})</td>
                        <td>{{ number_format($report->consignment_qty) }} ({{ number_format($report->consignment_value, 2) }})</td>
                        <td>{{ $report->payment_status }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">No reports yet</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="card">
        <div class="table-header">
            <div>
                <div class="section-title">Report Media</div>
                <div class="section-sub">Photos and videos attached to visit reports</div>
            </div>
        </div>
        <div class="media-grid">
            @forelse($reportMedia as $media)
                <div class="media-card">
                    @if($media->media_type === 'VIDEO')
                        <video src="{{ $media->media_url }}" controls></video>
                    @else
                        <img src="{{ $media->media_url }}" alt="Media">
                    @endif
                    <div class="media-meta">{{ $media->report?->visit?->visit_at }}</div>
                </div>
            @empty
                <div class="notice">No report media uploaded</div>
            @endforelse
        </div>
    </div>

    <div class="card">
        <div class="table-header">
            <div>
                <div class="section-title">Store Media</div>
                <div class="section-sub">Photos and videos captured during visits</div>
            </div>
        </div>
        <div class="media-grid">
            @forelse($storeMedia as $media)
                <div class="media-card">
                    @if($media->media_type === 'VIDEO')
                        <video src="{{ $media->media_url }}" controls></video>
                    @else
                        <img src="{{ $media->media_url }}" alt="Media">
                    @endif
                    <div class="media-meta">{{ $media->visit?->visit_at }}</div>
                </div>
            @empty
                <div class="notice">No store media uploaded</div>
            @endforelse
        </div>
    </div>

    <div class="card">
        <div class="table-header">
            <div>
                <div class="section-title">Update Timeline</div>
                <div class="section-sub">Track all report and media updates</div>
            </div>
        </div>
        <div class="timeline">
            @forelse($logs as $log)
                <div class="timeline-item">
                    <div class="timeline-title">
                        <span class="tag">{{ str_replace('_', ' ', $log->action) }}</span>
                        <span>{{ $log->user?->full_name }}</span>
                        <span class="timeline-time">{{ $log->created_at }}</span>
                    </div>
                    @if(is_array($log->changes) && count($log->changes))
                        @foreach($log->changes as $field => $change)
                            <div class="change-line">
                                <span class="change-field">{{ $field }}</span>
                                @if(is_array($change) && (array_key_exists('from', $change) || array_key_exists('to', $change)))
                                    <span>{{ $change['from'] ?? '-' }} -> {{ $change['to'] ?? '-' }}</span>
                                @elseif(is_array($change))
                                    <span>{{ json_encode($change) }}</span>
                                @else
                                    <span>{{ $change }}</span>
                                @endif
                            </div>
                        @endforeach
                    @endif
                </div>
            @empty
                <div class="notice">No updates recorded</div>
            @endforelse
        </div>
    </div>
</div>
