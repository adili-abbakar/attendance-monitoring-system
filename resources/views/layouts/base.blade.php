<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>

@if (session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            showSuccess("{{ session('success') }}");
        });
    </script>
@endif
@yield('content')
<script src="{{ asset('js/alert.js') }}"></script>
</html>
