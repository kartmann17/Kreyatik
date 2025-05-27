@extends('admin.layout')

@section('title', 'Gestion des Articles')

@section('page_title', 'Gestion des Articles')

@section('content_body')
<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h3 class="card-title">Liste des Articles</h3>
            <a href="{{ route('admin.articles.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Nouvel Article
            </a>
        </div>
    </div>
    <div class="card-body">
        @if(session('success'))
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            {{ session('success') }}
        </div>
        @endif

        @if(count($articles) > 0)
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th style="width: 10px">#</th>
                        <th style="width: 100px">Image</th>
                        <th>Titre</th>
                        <th>Statut</th>
                        <th>Date de publication</th>
                        <th style="width: 150px">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($articles as $article)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            @if($article->image)
                            <img src="{{ asset('storage/' . $article->image) }}" alt="{{ $article->title }}" class="img-thumbnail" style="max-height: 60px;">
                            @else
                            <div class="bg-secondary p-2 text-white text-center" style="height: 60px;">
                                <small>Pas d'image</small>
                            </div>
                            @endif
                        </td>
                        <td>{{ $article->title }}</td>
                        <td>
                            <form action="{{ route('admin.articles.toggle-publish', $article) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm {{ $article->is_published ? 'btn-success' : 'btn-secondary' }}">
                                    {{ $article->is_published ? 'Publié' : 'Brouillon' }}
                                </button>
                            </form>
                        </td>
                        <td>{{ $article->published_at ? $article->published_at->format('d/m/Y') : 'Non publié' }}</td>
                        <td>
                            <div class="btn-group">
                                <a href="{{ route('admin.articles.edit', $article) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#delete-modal-{{ $article->id }}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>

                            <!-- Modal de confirmation de suppression -->
                            <div class="modal fade" id="delete-modal-{{ $article->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="deleteModalLabel">Confirmer la suppression</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            Êtes-vous sûr de vouloir supprimer cet article : <strong>{{ $article->title }}</strong> ?
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                                            <form action="{{ route('admin.articles.destroy', $article) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger">Supprimer</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="alert alert-info">
            Aucun article disponible. <a href="{{ route('admin.articles.create') }}">Créez-en un</a> pour commencer.
        </div>
        @endif
    </div>
</div>

<div class="mt-4">
    {{ $articles->links() }}
</div>
@endsection