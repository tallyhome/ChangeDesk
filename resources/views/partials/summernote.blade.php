@section('head')
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
<style>
.note-editor {
    min-height: 300px;
}
.note-editable {
    min-height: 300px;
}
</style>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
<script>
    $(document).ready(function() {
        $('.summernote').summernote({
            height: 300,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture', 'video']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ],
            callbacks: {
                onImageUpload: function(files) {
                    for(let i = 0; i < files.length; i++) {
                        uploadImage(files[i], $(this));
                    }
                }
            }
        });
        
        function uploadImage(file, editor) {
            let formData = new FormData();
            formData.append('file', file);
            formData.append('_token', '{{ csrf_token() }}');
            
            $.ajax({
                url: '{{ route("admin.upload.image") }}',
                method: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(data) {
                    editor.summernote('insertImage', data.location, function($image) {
                        $image.css('max-width', '100%');
                    });
                },
                error: function(xhr, status, error) {
                    console.error('Upload failed:', error);
                    alert('Échec du téléchargement de l\'image');
                }
            });
        }
    });
</script>
@endsection