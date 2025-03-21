@push('styles')
<style>
.tox-tinymce {
    min-height: 300px;
}
.tox-editor-container {
    min-height: 300px;
}
/* Fix pour les boîtes de dialogue modales */
.tox-dialog-wrap {
    z-index: 9999 !important;
}
.tox-dialog__backdrop {
    z-index: 9998 !important;
}
.tox-tinymce {
    position: relative;
    z-index: 1;
}
</style>
@endpush

@push('scripts')
<!-- Utilisation de TinyMCE en local -->
<script src="{{ asset('js/tinymce/tinymce/js/tinymce/tinymce.min.js') }}" referrerpolicy="origin"></script>
<script>
    $(document).ready(function() {
        // Sélectionne à la fois les éléments avec la classe .summernote et les éléments avec l'ID #content ou #description
        tinymce.init({
            selector: '.summernote, #content, #description',
            language: 'fr_FR',
            height: 300,
            plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
            toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
            images_upload_handler: function (blobInfo, progress) {
                return new Promise((resolve, reject) => {
                    let formData = new FormData();
                    formData.append('file', blobInfo.blob(), blobInfo.filename());
                    
                    $.ajax({
                        url: '{{ route("admin.upload.image") }}',
                        method: 'POST',
                        data: formData,
                        contentType: false,
                        processData: false,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(data) {
                            resolve(data.location);
                        },
                        error: function(xhr, status, error) {
                            console.error('Upload failed:', error);
                            reject('Erreur lors de l\'upload de l\'image: ' + error);
                        }
                    });
                });
            }
        });
    });
</script>
@endpush