@extends('layouts.admin')
@section('title', 'Mes Réclamations')
@section('page-header', 'Mes Réclamations')
@section('page-actions')
    <a href="{{ route('student.reclamations.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i> Nouvelle Réclamation</a>
@endsection
@section('content')
<div class="card shadow-sm border-0">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light"><tr><th>Titre</th><th>Absence</th><th>Statut</th><th>Date</th><th class="text-end">Actions</th></tr></thead>
                <tbody>
                    @forelse($reclamations as $r)
                    <tr>
                        <td>{{ $r->title }}</td>
                        <td>{{ $r->absence_id ? '#'.$r->absence_id : '—' }}</td>
                        <td><span class="badge bg-{{ $r->status=='resolved'?'success':($r->status=='rejected'?'danger':($r->status=='in_review'?'info':'warning')) }}">{{ $r->status }}</span></td>
                        <td>{{ $r->created_at->format('d/m/Y') }}</td>
                        <td class="text-end"><a href="{{ route('student.reclamations.show', $r) }}" class="btn btn-sm btn-outline-info"><i class="fa fa-eye"></i></a></td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center py-4">Aucune réclamation.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $reclamations->links() }}
    </div>
</div>
@endsection
