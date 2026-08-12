<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
(() => {
  if (typeof Swal === 'undefined') return;

  const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3200,
    timerProgressBar: true,
  });

  window.ChanSwal = {
    toast(icon, title) {
      return Toast.fire({ icon, title });
    },
    success(title, text = '') {
      return Swal.fire({
        icon: 'success',
        title,
        text,
        confirmButtonText: 'Parfait',
        confirmButtonColor: '#12a38c',
      });
    },
    error(title, text = '') {
      return Swal.fire({
        icon: 'error',
        title,
        text,
        confirmButtonText: 'Fermer',
        confirmButtonColor: '#dc2626',
      });
    },
    info(title, text = '') {
      return Swal.fire({
        icon: 'info',
        title,
        text,
        confirmButtonText: 'OK',
        confirmButtonColor: '#0ea5e9',
      });
    },
    async confirm(title, text = '', confirmText = 'Confirmer') {
      const r = await Swal.fire({
        icon: 'question',
        title,
        text,
        showCancelButton: true,
        confirmButtonText: confirmText,
        cancelButtonText: 'Annuler',
        confirmButtonColor: '#12a38c',
        cancelButtonColor: '#64748b',
        reverseButtons: true,
      });
      return r.isConfirmed;
    },
  };

  window.alert = function (message) {
    Swal.fire({
      icon: 'info',
      title: String(message || ''),
      confirmButtonText: 'OK',
      confirmButtonColor: '#0ea5e9',
    });
  };

  document.addEventListener('click', async (e) => {
    const submitter = e.target.closest('button[type="submit"], input[type="submit"]');
    if (!submitter) return;
    const form = submitter.closest('form');
    if (!form) return;
    if (form.dataset.swalConfirmed === '1') return;
    const msg = submitter.getAttribute('data-confirm') || form.getAttribute('data-confirm');
    if (!msg) return;
    e.preventDefault();
    e.stopPropagation();
    const ok = await window.ChanSwal.confirm('Confirmation', msg);
    if (!ok) return;
    form.dataset.swalConfirmed = '1';
    if (typeof form.requestSubmit === 'function') {
      form.requestSubmit(submitter);
    } else {
      form.submit();
    }
  }, true);

  @if(session('success'))
    window.ChanSwal.toast('success', @json(session('success')));
  @endif
  @if(session('error'))
    window.ChanSwal.toast('error', @json(session('error')));
  @endif
  @if(session('warning'))
    window.ChanSwal.toast('warning', @json(session('warning')));
  @endif
  @if(session('info'))
    window.ChanSwal.toast('info', @json(session('info')));
  @endif
})();
</script>
