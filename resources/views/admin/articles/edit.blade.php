@extends('admin.layout')

@section('title', 'Modifier un Article')

@section('page_title', 'Modifier un Article')

@section('content_body')
<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h3 class="card-title">Modification: {{ $article->title }}</h3>
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

        <form action="{{ route('admin.articles.update', $article) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="title">Titre <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="title" name="title"
                            value="{{ old('title', $article->title) }}" required>
                    </div>

                    <div class="form-group">
                        <label for="content">Contenu <span class="text-danger">*</span></label>
                        <textarea class="form-control tinymce-editor" id="content" name="content" rows="10" required>{{ old('content', $article->content) }}</textarea>
                    </div>

                    <div class="form-group">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="is_published"
                                name="is_published" value="1" {{ old('is_published', $article->is_published) ? 'checked' : '' }}>
                            <label class="custom-control-label" for="is_published">Publier l'article</label>
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
                                <label class="custom-file-label" for="image">Choisir une nouvelle image</label>
                            </div>
                        </div>
                        <small class="form-text text-muted">Formats acceptés: JPEG, PNG, GIF. Taille max: 2MB. Laissez vide pour conserver l'image existante.</small>
                    </div>

                    @if($article->image)
                    <div class="form-group mt-4">
                        <h5>Image actuelle</h5>
                        <div class="position-relative d-inline-block">
                            <img src="{{ Storage::url($article->image) }}" alt="{{ $article->title }}"
                                class="img-fluid img-thumbnail" style="max-height: 200px;">
                            <div class="mt-2">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="remove_image" name="remove_image">
                                    <label class="custom-control-label text-danger" for="remove_image">
                                        <i class="fas fa-trash"></i> Supprimer l'image
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <div class="form-group mt-3">
                        <div id="preview-container" class="text-center d-none">
                            <h5>Aperçu de la nouvelle image</h5>
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
                        <i class="fas fa-save"></i> Enregistrer les modifications
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
    $(function() {
        // Afficher le nom du fichier sélectionné
        $('#image').on('change', function() {
            let fileName = $(this).val().split('\\').pop();
            $(this).next('.custom-file-label').html(fileName || 'Choisir une nouvelle image');

            // Prévisualisation de l'image
            previewImage(this);
        });

        // Fonction pour prévisualiser l'image
        function previewImage(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    $('#preview-container').removeClass('d-none');
                    $('#image-preview').removeClass('d-none');
                    $('#image-preview img').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        // Gestion de la suppression de l'image
        $('#remove_image').on('change', function() {
            const currentImage = $(this).closest('.form-group').find('img');
            if (this.checked) {
                currentImage.css('opacity', '0.5');
            } else {
                currentImage.css('opacity', '1');
            }
        });
    });
</script>
@endsection