@props([
    'id' => null,
    'title' => 'Are you sure?',
    'message' => 'This action cannot be undone.',
    'confirmText' => 'Confirm',
    'cancelText' => 'Cancel',
    'variant' => 'danger',
    'class' => '',
])

<x-modal :id="$id" :title="$title" :size="'sm'" :class="$class">
    <div class="text-center">
        <div class="inline-flex items-center justify-center w-12 h-12
            @if ($variant === 'danger') bg-red-100 text-red-600
            @elseif ($variant === 'warning') bg-yellow-100 text-yellow-600
            @elseif ($variant === 'info') bg-blue-100 text-blue-600
            @else bg-gray-100 text-gray-600
            @endif
            rounded-full mb-4">
            @if ($variant === 'danger')
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            @elseif ($variant === 'warning')
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            @elseif ($variant === 'info')
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            @else
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            @endif
        </div>
        <h3 class="text-lg font-medium text-gray-900 mb-2">{{ $title }}</h3>
        <p class="text-gray-500 mb-6">{{ $message }}</p>
        <div class="flex justify-center gap-3">
            <x-button variant="ghost" @click="$dispatch('close-modal', { id: '{{ $id }}' })">{{ $cancelText }}</x-button>
            <x-button variant="{{ $variant }}" @click="$dispatch('confirm-{{ $id }}')">{{ $confirmText }}</x-button>
        </div>
    </div>
</x-modal>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        window.addEventListener('confirm-{{ $id }}', () => {
            const event = new CustomEvent('confirmed-{{ $id }}');
            window.dispatchEvent(event);
            window.closeModal('{{ $id }}');
        });
    });
</script>
@endpush