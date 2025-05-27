@extends('admin.layout')

@section('title', 'Tableau de bord administrateur')

@section('page_title', 'Tableau de bord administrateur')

@section('content_body')
<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h3 class="card-title">Vue d'ensemble</h3>
            <div class="btn-group">
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addModal">
                    <i class="fas fa-plus"></i> Ajouter
                </button>
            </div>
        </div>
    </div>
    <div class="card-body">
        @if(session('success'))
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            {{ session('success') }}
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            {{ session('error') }}
        </div>
        @endif

        @if(count($items) > 0)
        <div class="table-responsive">
            <table class="table table-hover" id="main-table">
                <thead>
                    <tr>
                        <th style="width: 10px">#</th>
                        <th>Titre</th>
                        <th>Description</th>
                        <th>Statut</th>
                        <th>Date de création</th>
                        <th style="width: 150px">Actions</th>
                    </tr>
                </thead>
                <tbody id="sortable-items">
                    @foreach($items as $item)
                    <tr data-id="{{ $item->id }}">
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->title }}</td>
                        <td>{{ Str::limit($item->description, 50) }}</td>
                        <td>
                            <span class="badge badge-{{ $item->status ? 'success' : 'warning' }}">
                                {{ $item->status ? 'Actif' : 'Inactif' }}
                            </span>
                        </td>
                        <td>{{ $item->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            <div class="btn-group">
                                <button type="button" class="btn btn-sm btn-info view-btn" data-id="{{ $item->id }}">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-warning edit-btn" data-id="{{ $item->id }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#deleteModal" data-id="{{ $item->id }}" data-name="{{ $item->title }}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="alert alert-info">
            Aucun élément trouvé. <a href="#" data-toggle="modal" data-target="#addModal">Ajoutez-en un</a> pour commencer.
        </div>
        @endif
    </div>
</div>

<!-- Modal d'ajout -->
<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addModalLabel">Ajouter un élément</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="modal_title">Titre</label>
                        <input type="text" class="form-control" id="modal_title" name="title" required>
                    </div>
                    <div class="form-group">
                        <label for="modal_description">Description</label>
                        <textarea class="form-control" id="modal_description" name="description" rows="3"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="modal_status">Statut</label>
                        <select class="form-control" id="modal_status" name="status">
                            <option value="1">Actif</option>
                            <option value="0">Inactif</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal de confirmation de suppression -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirmer la suppression</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Êtes-vous sûr de vouloir supprimer <span id="clientName"></span> ?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                <form id="deleteForm" method="POST" style="display: inline-block;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Supprimer</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('custom_js')
<script>
    $(function() {
        // Initialisation de DataTables
        $('#main-table').DataTable({
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/French.json"
            }
        });

        // Configuration du modal de suppression
        $('#deleteModal').on('show.bs.modal', function(event) {
            const button = $(event.relatedTarget);
            const id = button.data('id');
            const name = button.data('name');

            const modal = $(this);
            modal.find('#clientName').text(name);
            modal.find('#deleteForm').attr('action', `/admin/projects/${id}`);
        });

        // Gestion de la soumission du formulaire de suppression
        $('#deleteForm').on('submit', function(e) {
            e.preventDefault();
            const form = $(this);
            const url = form.attr('action');

            $.ajax({
                url: url,
                type: 'DELETE',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    $('#deleteModal').modal('hide');
                    Swal.fire({
                        title: 'Succès!',
                        text: 'Le projet a été supprimé avec succès.',
                        type: 'success',
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        if (result.value) {
                            window.location.reload();
                        }
                    });
                },
                error: function(xhr) {
                    $('#deleteModal').modal('hide');
                    Swal.fire({
                        title: 'Erreur!',
                        text: xhr.responseJSON?.message || 'Une erreur est survenue lors de la suppression.',
                        type: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            });
        });

        // Gestion de l'édition
        $('.edit-btn').on('click', function() {
            const id = $(this).data('id');
            window.location.href = `/admin/projects/${id}/edit`;
        });

        // Gestion de la visualisation
        $('.view-btn').on('click', function() {
            const id = $(this).data('id');
            window.location.href = `/admin/projects/${id}`;
        });
    });
</script>
@endsection