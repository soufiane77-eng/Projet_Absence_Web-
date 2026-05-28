@extends('layouts.admin')
@section('title', 'Login History')
@section('page-header', 'Login History')
@section('content')
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
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
                    <a href="{{ route('admin.login-history') }}" class="btn btn-outline-secondary btn-sm"><i class="fa fa-undo"></i> Reset</a>
                </div>
            </form>
        </div>
    </div>
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr><th>User</th><th>IP Address</th><th>Device / Browser</th><th>Status</th><th>Date/Time</th></tr>
                    </thead>
                    <tbody>
                        @forelse($histories as $history)
                            <tr>
                                <td>
                                    @if($history->user)
                                        <span class="fw-medium">{{ $history->user->name }}</span><br><small class="text-muted">{{ $history->user->email }}</small>
                                    @else <span class="text-muted">Deleted User</span> @endif
                                </td>
                                <td><code>{{ $history->ip_address }}</code></td>
                                <td><small class="text-muted" title="{{ $history->user_agent }}">{{ Str::limit($history->user_agent, 40) }}</small></td>
                                <td>
                                    @if($history->login_successful) <span class="badge bg-success">Success</span>
                                    @else <span class="badge bg-danger">Failed</span> @endif
                                </td>
                                <td><small>{{ $history->login_at->format('Y-m-d H:i:s') }}</small><br><small class="text-muted">{{ $history->login_at->diffForHumans() }}</small></td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center py-4 text-muted">No login history found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($histories->hasPages())<div class="card-footer bg-white">{{ $histories->links() }}</div>@endif
    </div>
@endsection
