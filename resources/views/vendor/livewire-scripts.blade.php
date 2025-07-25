<!-- resources/views/vendor/livewire-scripts.blade.php -->
@livewireStyles

<script src="https://unpkg.com/livewire@2.0.0/dist/livewire.js"></script>

@livewireScripts

<!-- Optional: Include additional JS for Livewire interactivity -->
<script>
    document.addEventListener('livewire:load', function () {
        // Optional: Any custom JS you want to execute when Livewire is fully loaded
        console.log("Livewire is ready!");
    });
</script>
