@extends('layouts.admin')
@section('title', 'Gestion des Réclamations')
@section('page-header', 'Réclamations')
@section('content')
<div class="card shadow-sm border-0">
    <div class="card-body">
        <form method="GET" class="row mb-3">
            <div class="col-md-3">
                <select name="status" class="form-select" onchange="this.form.submit()">
                    <option value="">Tous les statuts</option>
                    <option value="open" {{ request('status')=='open'?'selected':''}}>Ouverte</option>
                    <option value="in_review" {{ request('status')=='in_review'?'selected':''}}>En cours</option>
                    <option value="resolved" {{ request('status')=='resolved'?'selected':''}}>Résolue</option>
                    <option value="rejected" {{ request('status')=='rejected'?'selected':''}}>Rejetée</option>
                </select>
            </div>
        </form>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light"><tr><th>Étudiant</th><th>Titre</th><th>Absence</th><th>Statut</th><th>Date</th><th class="text-end">Actions</th></tr></thead>
                <tbody>
                    @forelse($reclamations as $r)
                    <tr>
                        <td><a href="{{ route('admin.students.show', $r->student) }}">{{ $r->student->full_name }}</a></td>
                        <td>{{ $r->title }}</td>
                        <td>{{ $r->absence_id ? '#' . $r->absence_id : '—' }}</td>
                        <td><span class="badge bg-{{ $r->status=='resolved'?'success':($r->status=='rejected'?'danger':($r->status=='in_review'?'info':'warning')) }}">{{ $r->status }}</span></td>
                        <td>{{ $r->created_at->format('d/m/Y') }}</td>
                        <td class="text-end"><a href="{{ route('admin.reclamations.show', $r) }}" class="btn btn-sm btn-outline-info"><i class="fa fa-eye"></i></a></td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center py-4">Aucune réclamation trouvée.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $reclamations->links() }}
    </div>
</div>
@endsection
