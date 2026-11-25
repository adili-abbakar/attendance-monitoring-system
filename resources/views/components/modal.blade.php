@props([
    'id' => null,
    'title' => null,
    'size' => 'md',
    'closeable' => true,
    'class' => '',
])

<?php
$modalId = $id ?? 'modal-' . uniqid();
$sizes = [
    'sm' => 'max-w-md',
    'md' => 'max-w-lg',
    'lg' => 'max-w-2xl',
    'xl' => 'max-w-4xl',
    'full' => 'max-w-full mx-4',
];
$sizeClass = $sizes[$size] ?? $sizes['md'];
?>

<div id="{{ $modalId }}" class="fixed inset-0 z-50 overflow-y-auto hidden" role="dialog" aria-modal="true" aria-labelledby="{{ $modalId }}-title" {{ $attributes }}>
    <div class="fixed inset-0 bg-black/50 transition-opacity" @click="$dispatch('close-modal', { id: '{{ $modalId }}' })"></div>

    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="w-full {{ $sizeClass }} bg-white rounded-xl shadow-xl transform transition-all {{ $class }}">
            @if ($title || $closeable)
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
                    @if ($title)
                        <h3 id="{{ $modalId }}-title" class="text-lg font-semibold text-gray-900">{{ $title }}</h3>
                    @endif
                    @if ($closeable)
                        <button type="button" class="text-gray-400 hover:text-gray-600 transition-colors p-1" @click="$dispatch('close-modal', { id: '{{ $modalId }}' })" aria-label="Close modal">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    @endif
                </div>
            @endif

            <div class="p-6">
                {{ $slot }}
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        window.addEventListener('close-modal', (e) => {
            const modal = document.getElementById(e.detail.id);
            if (modal) {
                modal.classList.add('hidden');
            }
        });

        window.openModal = (id) => {
            const modal = document.getElementById(id);
            if (modal) {
                modal.classList.remove('hidden');
            }
        };

        window.closeModal = (id) => {
            const modal = document.getElementById(id);
            if (modal) {
                modal.classList.add('hidden');
            }
        };
    });
</script>
@endpush