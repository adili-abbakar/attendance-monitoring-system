<!-- Loader Overlay -->
<div id="loaderOverlay" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 hidden">
    <div class="text-center">
        <div class="w-16 h-16 border-4 border-dashed rounded-full animate-spin border-teal-900 mx-auto"></div>
        <h2 class="text-slate-400 mt-4">Loading...</h2>
    </div>
</div>

<script>
    function showLoader() {
        document.getElementById('loaderOverlay').classList.remove('hidden');
    }

    function hideLoader() {
        document.getElementById('loaderOverlay').classList.add('hidden');
    }
</script>
