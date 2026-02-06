<div class="page">
    <div class="stats-grid">
        <div class="card stat-card">
            <div class="stat-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path d="M16 11a4 4 0 1 0-8 0 4 4 0 0 0 8 0z"/>
                    <path d="M6 20a6 6 0 0 1 12 0"/>
                </svg>
            </div>
            <div class="stat-value">{{ $stats['users'] }}</div>
            <div class="stat-label">Total Users</div>
            <div class="badge success">All active</div>
        </div>
        <div class="card stat-card">
            <div class="stat-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path d="M4 21V8l8-5 8 5v13"/>
                    <path d="M9 21v-6h6v6"/>
                </svg>
            </div>
            <div class="stat-value">{{ $stats['stores'] }}</div>
            <div class="stat-label">Active Stores</div>
            <div class="badge info">{{ $stats['stores'] }} locations</div>
        </div>
        <div class="card stat-card">
            <div class="stat-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path d="M12 21s7-6 7-11a7 7 0 0 0-14 0c0 5 7 11 7 11z"/>
                    <circle cx="12" cy="10" r="2.5"/>
                </svg>
            </div>
            <div class="stat-value">{{ $stats['visits'] }}</div>
            <div class="stat-label">Total Visits</div>
            <div class="badge info">This week</div>
        </div>
        <div class="card stat-card">
            <div class="stat-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <circle cx="12" cy="12" r="9"/>
                    <path d="M9 12h6M12 7v10"/>
                </svg>
            </div>
            <div class="stat-value">{{ $stats['deals'] }}</div>
            <div class="stat-label">Active Deals</div>
            <div class="badge warn">In pipeline</div>
        </div>
    </div>

    <div class="card">
        <div class="table-header">
            <div>
                <div class="section-title">Recent Visits</div>
                <div class="section-sub">Latest store visit activities</div>
            </div>
            <a class="link" href="{{ route('visits') }}" wire:navigate>View All →</a>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>Store Name</th>
                    <th>Visitor</th>
                    <th>Visit Time</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($recentVisits as $visit)
                    @php
                        $isOnTime = $visit->visit_status === 'ON_TIME';
                    @endphp
                    <tr>
                        <td>{{ $visit->store?->store_name }}</td>
                        <td>{{ $visit->user?->full_name }}</td>
                        <td>{{ $visit->visit_at }}</td>
                        <td>
                            <span class="status-pill {{ $isOnTime ? 'ok' : 'warn' }}">
                                <span class="status-dot"></span>
                                {{ $isOnTime ? 'On Time' : 'Out of Range' }}
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="card">
        <div class="table-header">
            <div>
                <div class="section-title">Recent Deals</div>
                <div class="section-sub">Latest deals in pipeline</div>
            </div>
            <a class="link" href="{{ route('deals') }}" wire:navigate>View All →</a>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>Deal</th>
                    <th>Store</th>
                    <th>Owner</th>
                    <th>Stage</th>
                </tr>
            </thead>
            <tbody>
                @foreach($recentDeals as $deal)
                    <tr>
                        <td>{{ $deal->deal_name }}</td>
                        <td>{{ $deal->store?->store_name }}</td>
                        <td>{{ $deal->owner?->full_name }}</td>
                        <td>{{ $deal->stage }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
