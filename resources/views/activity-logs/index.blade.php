@extends('layouts.admin')
@section('title', 'Activity Logs')
@section('page-header', 'Activity Logs')
@section('content')
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label small">Action</label>
                    <select name="action" class="form-select form-select-sm">
                        <option value="">All Actions</option>
                        @foreach($actions as $a)
                            <option value="{{ $a }}" {{ request('action') == $a ? 'selected' : '' }}>{{ $a }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small">Date From</label>
                    <input type="date" name="date_from" class="form-control form-control-sm" value="{{ request('date_from') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label small">Date To</label>
                    <input type="date" name="date_to" class="form-control form-control-sm" value="{{ request('date_to') }}">
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary btn-sm me-2"><i class="fa fa-filter"></i> Filter</button>
                    <a href="{{ route('admin.activity-logs') }}" class="btn btn-outline-secondary btn-sm"><i class="fa fa-undo"></i> Reset</a>
                </div>
            </form>
        </div>
    </div>
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr><th>User</th><th>Action</th><th>Description</th><th>IP Address</th><th>Date/Time</th></tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $log)
                            <tr>
                                <td>
                                    @if($log->user)
                                        <span class="fw-medium">{{ $log->user->name }}</span><br><small class="text-muted">{{ $log->user->email }}</small>
                                    @else <span class="text-muted">System</span> @endif
                                </td>
                                <td><code>{{ $log->action }}</code></td>
                                <td>{{ Str::limit($log->description, 60) }}</td>
                                <td><small>{{ $log->ip_address ?? '-' }}</small></td>
                                <td><small>{{ $log->created_at->format('Y-m-d H:i:s') }}</small><br><small class="text-muted">{{ $log->created_at->diffForHumans() }}</small></td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center py-4 text-muted">No activity logs found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($logs->hasPages())<div class="card-footer bg-white">{{ $logs->links() }}</div>@endif
    </div>
@endsection
