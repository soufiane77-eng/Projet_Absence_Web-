@extends('layouts.admin')
@section('title', 'Mes Justifications')
@section('page-header', 'Mes Justifications')
@section('page-actions')
    <a href="{{ route('student.justifications.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i> Nouvelle Justification</a>
@endsection
@section('content')
<div class="card shadow-sm border-0">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light"><tr><th>Titre</th><th>Absence</th><th>Document</th><th>Statut</th><th>Date</th><th class="text-end">Actions</th></tr></thead>
                <tbody>
                    @forelse($justifications as $j)
                    <tr>
                        <td>{{ $j->title }}</td>
                        <td>{{ $j->absence_id ? '#'.$j->absence_id : '—' }}</td>
                        <td>@if($j->document_path)<a href="{{ asset('storage/'.$j->document_path) }}" target="_blank"><i class="fa fa-file"></i></a>@else — @endif</td>
                        <td><span class="badge bg-{{ $j->status=='accepted'?'success':($j->status=='rejected'?'danger':'warning') }}">{{ $j->status }}</span></td>
                        <td>{{ $j->created_at->format('d/m/Y') }}</td>
                        <td class="text-end"><a href="{{ route('student.justifications.show', $j) }}" class="btn btn-sm btn-outline-info"><i class="fa fa-eye"></i></a></td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center py-4">Aucune justification.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $justifications->links() }}
    </div>
</div>
@endsection
