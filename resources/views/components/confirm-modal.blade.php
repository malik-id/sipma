{{-- Universal Modern Confirmation Modal Component --}}
<div id="global-confirm-modal"
     class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-zinc-950/65 backdrop-blur-xs transition-opacity duration-200 hidden opacity-0"
     role="dialog"
     aria-modal="true"
     aria-labelledby="confirm-modal-title">

    <div id="global-confirm-card"
         class="relative w-full max-w-md bg-white rounded-2xl shadow-2xl border border-zinc-200 overflow-hidden transform scale-95 transition-all duration-200">

        <div class="p-6 sm:p-7">
            {{-- Modal Header with Icon --}}
            <div class="flex items-start gap-4">
                <div id="confirm-modal-icon-container"
                     class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 border border-amber-200/60 flex items-center justify-center shrink-0 shadow-2xs">
                    <span id="confirm-modal-icon" class="material-symbols-outlined text-[26px]">help</span>
                </div>

                <div class="flex-1 min-w-0">
                    <h3 id="confirm-modal-title" class="text-base sm:text-lg font-bold text-zinc-900 leading-tight">
                        Konfirmasi Tindakan
                    </h3>
                    <p id="confirm-modal-message" class="text-xs sm:text-sm text-zinc-600 mt-2 leading-relaxed">
                        Apakah Anda yakin ingin melanjutkan tindakan ini?
                    </p>
                </div>
            </div>
        </div>

        {{-- Modal Actions / Buttons Footer --}}
        <div class="px-6 py-4 bg-zinc-50 border-t border-zinc-100 flex items-center justify-end gap-3">
            <button type="button"
                    id="confirm-modal-cancel-btn"
                    class="px-4 py-2.5 rounded-xl border border-zinc-300 text-zinc-700 bg-white hover:bg-zinc-100 text-xs font-bold transition">
                Batal
            </button>
            <button type="button"
                    id="confirm-modal-submit-btn"
                    class="px-5 py-2.5 rounded-xl bg-zinc-950 hover:bg-zinc-800 text-amber-400 text-xs font-bold transition shadow-xs">
                Ya, Lanjutkan
            </button>
        </div>
    </div>
</div>

<script>
    (function () {
        const modal = document.getElementById('global-confirm-modal');
        const card = document.getElementById('global-confirm-card');
        const titleEl = document.getElementById('confirm-modal-title');
        const messageEl = document.getElementById('confirm-modal-message');
        const iconContainer = document.getElementById('confirm-modal-icon-container');
        const iconEl = document.getElementById('confirm-modal-icon');
        const cancelBtn = document.getElementById('confirm-modal-cancel-btn');
        const submitBtn = document.getElementById('confirm-modal-submit-btn');

        let currentCallback = null;

        window.showConfirmModal = function (options) {
            const {
                title = 'Konfirmasi Tindakan',
                message = 'Apakah Anda yakin ingin melanjutkan tindakan ini?',
                confirmText = 'Ya, Lanjutkan',
                cancelText = 'Batal',
                type = 'warning', // 'warning', 'danger', 'info', 'success'
                onConfirm = null
            } = options || {};

            titleEl.textContent = title;
            messageEl.textContent = message;
            submitBtn.textContent = confirmText;
            cancelBtn.textContent = cancelText;
            currentCallback = onConfirm;

            // Styling based on type
            if (type === 'danger' || /hapus|delete|tolak|reset|diskualifikasi/i.test(title + ' ' + message + ' ' + confirmText)) {
                iconContainer.className = 'w-12 h-12 rounded-2xl bg-rose-50 text-rose-600 border border-rose-200/60 flex items-center justify-center shrink-0 shadow-2xs';
                iconEl.textContent = 'warning';
                submitBtn.className = 'px-5 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold transition shadow-xs';
            } else if (type === 'success' || /setujui|verifikasi|tetapkan|publikasi/i.test(title + ' ' + message + ' ' + confirmText)) {
                iconContainer.className = 'w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 border border-emerald-200/60 flex items-center justify-center shrink-0 shadow-2xs';
                iconEl.textContent = 'verified';
                submitBtn.className = 'px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold transition shadow-xs';
            } else {
                iconContainer.className = 'w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 border border-amber-200/60 flex items-center justify-center shrink-0 shadow-2xs';
                iconEl.textContent = 'help';
                submitBtn.className = 'px-5 py-2.5 rounded-xl bg-zinc-950 hover:bg-zinc-800 text-amber-400 text-xs font-bold transition shadow-xs';
            }

            modal.classList.remove('hidden');
            requestAnimationFrame(() => {
                modal.classList.remove('opacity-0');
                card.classList.remove('scale-95');
                card.classList.add('scale-100');
            });
        };

        function closeModal() {
            modal.classList.add('opacity-0');
            card.classList.remove('scale-100');
            card.classList.add('scale-95');
            setTimeout(() => {
                modal.classList.add('hidden');
                currentCallback = null;
            }, 200);
        }

        cancelBtn.addEventListener('click', closeModal);
        modal.addEventListener('click', function (e) {
            if (e.target === modal) closeModal();
        });

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
                closeModal();
            }
        });

        submitBtn.addEventListener('click', function () {
            const cb = currentCallback;
            closeModal();
            if (typeof cb === 'function') {
                cb();
            }
        });

        // Global automatic interceptor for forms with data-confirm or standard onsubmit confirm
        document.addEventListener('submit', function (e) {
            const form = e.target;
            if (!form || form._confirmedByCustomModal) return;

            const confirmMsg = form.getAttribute('data-confirm');
            if (confirmMsg) {
                e.preventDefault();
                const isDelete = form.querySelector('input[name="_method"][value="DELETE"]') || /hapus|delete|reset/i.test(confirmMsg);
                window.showConfirmModal({
                    title: isDelete ? 'Konfirmasi Penghapusan Data' : 'Konfirmasi Tindakan',
                    message: confirmMsg,
                    confirmText: isDelete ? 'Ya, Hapus Sekarang' : 'Ya, Lanjutkan',
                    cancelText: 'Batal',
                    type: isDelete ? 'danger' : 'warning',
                    onConfirm: () => {
                        form._confirmedByCustomModal = true;
                        form.submit();
                    }
                });
            }
        }, true);

        // Convert existing onsubmit="return confirm(...)" dynamically to modern modal
        document.querySelectorAll('form[onsubmit*="confirm("]').forEach(function (form) {
            const onsubmitAttr = form.getAttribute('onsubmit');
            const match = onsubmitAttr.match(/confirm\(['"](.+?)['"]\)/);
            if (match && match[1]) {
                form.removeAttribute('onsubmit');
                form.setAttribute('data-confirm', match[1]);
            }
        });
    })();
</script>
