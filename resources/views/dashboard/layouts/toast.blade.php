<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
    body.swal2-toast-shown .swal2-container.swal2-top-end,
    body.swal2-toast-shown .swal2-container.swal2-top-right {
        z-index: 2000 !important
    }

    div:where(.swal2-container).swal2-top-end > .swal2-popup,
    div:where(.swal2-container).swal2-top-right > .swal2-popup {
        padding: 0 16px !important;
    }

    /*.swal2-toast h2:where(.swal2-title) {*/
    /*    line-height: 2.2 !important;*/
    /*}*/
</style>
<script>
    window.toast = function (type = 'success', message = '', opts = {}) {
        const Toast = Swal.mixin({
            toast: true,
            position: opts.position || 'top-right',
            showConfirmButton: false,
            timer: opts.timer || 3000,
            timerProgressBar: false,
            didOpen: (toast) => {
                toast.style.cursor = 'pointer';
                toast.addEventListener('mouseenter', Swal.stopTimer);
                toast.addEventListener('mouseleave', Swal.resumeTimer);
                toast.addEventListener('click', () => Swal.close());
            }
        });
        return Toast.fire({
            icon: type,
            title: message
        });
    };

    document.addEventListener('livewire:init', () => {
        Livewire.on('toast', (data) => {
            window.toast(data.type ?? 'success', data.message);
        });
    });
    // window.toast('success', 'test toaster');
    @if (session('success'))
    window.toast('success', @json(session('success')));
    @endif

    @if (session('error'))
    window.toast('error', @json(session('error')));
    @endif

    @if (session('warning'))
    window.toast('warning', @json(session('warning')));
    @endif

    @if (session('info'))
    window.toast('info', @json(session('info')));
    @endif
</script>
