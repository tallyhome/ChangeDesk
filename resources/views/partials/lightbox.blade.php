@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.css" />
<style>
    .content-area img {
        cursor: zoom-in;
        max-width: 100%;
        height: auto;
        margin: 10px 0;
        border: 1px solid #ddd;
        padding: 3px;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialisation Fancybox avec délai
    setTimeout(() => {
        document.querySelectorAll('.content-area img').forEach(img => {
            if(!img.closest('a')) {
                const wrapper = document.createElement('a');
                wrapper.href = img.src;
                wrapper.setAttribute('data-fancybox', 'gallery');
                wrapper.setAttribute('data-caption', img.alt || '');
                img.parentNode.insertBefore(wrapper, img);
                wrapper.appendChild(img);
            }
        });
        
        Fancybox.bind('[data-fancybox="gallery"]', {
            Thumbs: false,
            Toolbar: true,
            Image: {
                zoom: true,
                click: 'close'
            }
        });
    }, 500);
});
</script>
@endpush