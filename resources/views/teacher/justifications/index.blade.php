@extends('layouts.admin')
@section('title', 'Justifications')
@section('page-header', 'Justifications des étudiants')
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
            <div class="col-md-3">
                <select name="module_id" class="form-select" onchange="this.form.submit()">
                    <option value="">Tous les modules</option>
                    @foreach($modules as $id => $name)
                        <option value="{{ $id }}" {{ request('module_id')==$id?'selected':''}}>{{ $name }}</option>
                    @endforeach
                </select>
            </div>
        </form>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Étudiant</th>
                        <th>Absence</th>
                        <th>Titre</th>
                        <th>Document</th>
                        <th>Statut</th>
                        <th>Soumis le</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($justifications as $j)
                    <tr>
                        <td>
                            <a href="{{ route('admin.students.show', $j->student) }}">{{ $j->student->full_name }}</a>
                            <br><small class="text-muted">{{ $j->student->classe?->name ?? '—' }}</small>
                        </td>
                        <td>
                            @if($j->absence)
                                <small>{{ $j->absence->seance?->module->name ?? '—' }}<br>
                                {{ $j->absence->seance?->date?->format('d/m/Y') ?? '—' }}</small>
                            @else
                                <small class="text-muted">—</small>
                            @endif
                        </td>
                        <td>{{ $j->title }}</td>
                        <td>
                            @if($j->document_path)
                                <a href="{{ asset('storage/'.$j->document_path) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                    <i class="fa fa-file"></i> Voir
                                </a>
                            @else
                                —
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-{{ $j->status=='accepted'?'success':($j->status=='rejected'?'danger':'warning') }}">
                                {{ $j->status=='accepted'?'Acceptée':($j->status=='rejected'?'Rejetée':'En attente') }}
                            </span>
                        </td>
                        <td>{{ $j->created_at->format('d/m/Y H:i') }}</td>
                        <td class="text-end">
                            <a href="{{ route('teacher.justifications.show', $j) }}" class="btn btn-sm btn-outline-info">
                                <i class="fa fa-eye"></i> Détails
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center py-4">Aucune justification trouvée.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $justifications->links() }}
    </div>
</div>
@endsection
