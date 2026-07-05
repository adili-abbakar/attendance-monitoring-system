@extends('layouts.base')

@section('content')

    <body class="bg-gray-50">
        @if (session('success'))
            <script>
                document.addEventListener('DOMContentLoaded', () => {
                    showSuccess("{{ session('success') }}");
                });
            </script>
        @endif
        @yield('page-content')
        <script src="{{ asset('js/alert.js') }}"></script>

    </body>
@endsection
