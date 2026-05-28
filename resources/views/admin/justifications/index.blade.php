@extends('layouts.admin')
@section('title', 'Gestion des Justifications')
@section('page-header', 'Justifications')
@section('content')
<div class="card shadow-sm border-0">
    <div class="card-body">
        <form method="GET" class="row mb-3">
            <div class="col-md-3">
                <select name="status" class="form-select" onchange="this.form.submit()">
                    <option value="">Tous les statuts</option>
                    <option value="pending" {{ request('status')=='pending'?'selected':''}}>En attente</option>
                    <option value="accepted" {{ request('status')=='accepted'?'selected':''}}>Acceptée</option>
                    <option value="rejected" {{ request('status')=='rejected'?'selected':''}}>Rejetée</option>
                </select>
            </div>
        </form>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light"><tr><th>Étudiant</th><th>Titre</th><th>Document</th><th>Statut</th><th>Soumis le</th><th class="text-end">Actions</th></tr></thead>
                <tbody>
                    @forelse($justifications as $j)
                    <tr>
                        <td><a href="{{ route('admin.students.show', $j->student) }}">{{ $j->student->full_name }}</a></td>
                        <td>{{ $j->title }}</td>
                        <td>
                            @if($j->document_path)
                                @php $ext = strtolower(pathinfo($j->document_path, PATHINFO_EXTENSION)); @endphp
                                @if(in_array($ext, ['jpg','jpeg','png']))
                                    <a href="{{ asset('storage/'.$j->document_path) }}" target="_blank">
                                        <img src="{{ asset('storage/'.$j->document_path) }}"
                                             alt="Document" class="rounded border" style="max-height: 60px; max-width: 80px; object-fit: cover;">
                                    </a>
                                @else
                                    <a href="{{ asset('storage/'.$j->document_path) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                        <i class="fa fa-file-pdf-o"></i> PDF
                                    </a>
                                @endif
                            @else
                                —
                            @endif
                        </td>
                        <td><span class="badge bg-{{ $j->status=='accepted'?'success':($j->status=='rejected'?'danger':'warning') }}">{{ $j->status }}</span></td>
                        <td>{{ $j->created_at->format('d/m/Y H:i') }}</td>
                        <td class="text-end">
                            @if($j->status=='pending')
                            <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#approveModal{{ $j->id }}"><i class="fa fa-check"></i> Approuver</button>
                            <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $j->id }}"><i class="fa fa-times"></i> Rejeter</button>
                            @else
                            <span class="text-muted">Traité par {{ $j->reviewer?->name ?? '—' }}</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center py-4">Aucune justification trouvée.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $justifications->links() }}
    </div>
</div>

@foreach($justifications as $j)
@if($j->status=='pending')
<div class="modal fade" id="approveModal{{ $j->id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.absences.justifications.approve', $j) }}" method="POST">
                @csrf
                <div class="modal-header"><h5 class="modal-title">Approuver la justification</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <p>Approuver la justification de <strong>{{ $j->student->full_name }}</strong> ?</p>
                    <div class="mb-3"><label class="form-label">Notes (optionnel)</label><textarea name="review_notes" class="form-control" rows="2"></textarea></div>
                </div>
                <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button><button type="submit" class="btn btn-success">Approuver</button></div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="rejectModal{{ $j->id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.absences.justifications.reject', $j) }}" method="POST">
                @csrf
                <div class="modal-header"><h5 class="modal-title">Rejeter la justification</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <p>Rejeter la justification de <strong>{{ $j->student->full_name }}</strong> ?</p>
                    <div class="mb-3"><label class="form-label">Motif du rejet</label><textarea name="review_notes" class="form-control" rows="2" required></textarea></div>
                </div>
                <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button><button type="submit" class="btn btn-danger">Rejeter</button></div>
            </form>
        </div>
    </div>
</div>
@endif
@endforeach
@endsection
