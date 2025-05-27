@extends('admin.layout')

@section('title', 'Créer un Article')

@section('page_title', 'Créer un Article')

@section('content_body')
<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h3 class="card-title">Nouvel article</h3>
            <a href="{{ route('admin.articles.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Retour à la liste
            </a>
        </div>
    </div>
    <div class="card-body">
        @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('admin.articles.store') }}" method="POST" enctype="multipart/form-data" id="articleForm">
            @csrf

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="title">Titre <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="title" name="title" value="{{ old('title') }}" required>
                    </div>

                    <div class="form-group">
                        <label for="content">Contenu <span class="text-danger">*</span></label>
                        <textarea class="form-control tinymce-editor" id="content" name="content" rows="10">{{ old('content') }}</textarea>
                    </div>

                    <div class="form-group">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="is_published" name="is_published" value="1" {{ old('is_published') ? 'checked' : '' }}>
                            <label class="custom-control-label" for="is_published">Publier immédiatement</label>
                        </div>
                        <small class="form-text text-muted">Cochez cette case pour rendre l'article visible sur le site public.</small>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="image">Image</label>
                        <div class="input-group">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="image" name="image" accept="image/*">
                                <label class="custom-file-label" for="image">Choisir une image</label>
                            </div>
                        </div>
                        <small class="form-text text-muted">Formats acceptés: JPEG, PNG, GIF. Taille max: 2MB.</small>
                    </div>

                    <div class="form-group mt-4">
                        <div id="preview-container" class="text-center d-none">
                            <h5>Aperçu de l'image</h5>
                            <div id="image-preview" class="d-none">
                                <img src="" alt="Aperçu" class="img-fluid img-thumbnail" style="max-height: 200px;">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-12 text-right">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Enregistrer
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('custom_js')
<x-head.tinymce-config />
<script>
    // Afficher le nom du fichier sélectionné
    document.getElementById('image').addEventListener('change', function() {
        var fileName = this.value.split('\\').pop();
        this.nextElementSibling.textContent = fileName || 'Choisir une image';

        // Prévisualisation de l'image
        if (this.files && this.files[0]) {
            var reader = new FileReader();
            var previewContainer = document.getElementById('preview-container');
            var imagePreview = document.getElementById('image-preview');
            var previewImg = imagePreview.querySelector('img');

            reader.onload = function(e) {
                previewContainer.classList.remove('d-none');
                imagePreview.classList.remove('d-none');
                previewImg.src = e.target.result;
            }

            reader.readAsDataURL(this.files[0]);
        }
    });

    // Validation du formulaire
    document.getElementById('articleForm').addEventListener('submit', function(e) {
        e.preventDefault();

        // Récupérer le contenu de TinyMCE
        var content = tinymce.get('content').getContent();

        // Vérifier si le contenu est vide
        if (!content.trim()) {
            alert('Le contenu est requis');
            return;
        }

        // Si tout est valide, soumettre le formulaire
        this.submit();
    });
</script>
@endsection