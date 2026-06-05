{{--
    Toast Flash Message — global frontend.

    Render kalau ada session('success') atau session('error').
    Auto-dismiss 5 detik. Position fixed top-right desktop, top-center mobile.
    Pakai Bootstrap 5 Toast component native (0 dependency tambahan).

    a11y: role="status" + aria-live="polite" agar screen reader umumkan
    tanpa interrupt user. Tombol dismiss punya aria-label Bahasa Indonesia.
--}}
@if (session('success') || session('error'))
    @php
        $isSuccess = session('success') !== null;
        $type = $isSuccess ? 'success' : 'danger';
        $icon = $isSuccess ? 'bi-check-circle-fill' : 'bi-exclamation-triangle-fill';
        $message = $isSuccess ? session('success') : session('error');
    @endphp

    <div class="toast-container position-fixed p-3 toast-flash-container" aria-live="polite" aria-atomic="true">
        <div id="flashToast"
             class="toast align-items-center border-0 shadow-lg"
             role="status"
             data-bs-autohide="true"
             data-bs-delay="5000">
            <div class="d-flex">
                <div class="toast-body d-flex align-items-center gap-2 flex-grow-1">
                    <i class="bi {{ $icon }} fs-5 text-{{ $type }} flex-shrink-0" aria-hidden="true"></i>
                    <span class="text-dark">{{ $message }}</span>
                </div>
                <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Tutup notifikasi"></button>
            </div>
        </div>
    </div>

    {{-- Push ke @stack('scripts') agar render setelah Bootstrap defer loaded --}}
    @push('scripts')
        <script>
            (function() {
                'use strict';

                function showFlashToast() {
                    var toastEl = document.getElementById('flashToast');
                    if (!toastEl) return;

                    if (typeof bootstrap === 'undefined' || !bootstrap.Toast) {
                        // Fallback kalau Bootstrap belum loaded — retry max 5x dengan interval
                        if ((window.__flashRetry || 0) < 5) {
                            window.__flashRetry = (window.__flashRetry || 0) + 1;
                            setTimeout(showFlashToast, 100);
                        }
                        return;
                    }

                    new bootstrap.Toast(toastEl).show();
                }

                if (document.readyState === 'loading') {
                    document.addEventListener('DOMContentLoaded', showFlashToast);
                } else {
                    showFlashToast();
                }
            })();
        </script>
    @endpush
@endif
