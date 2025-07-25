<!-- resources/views/vendor/volt.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Volt External Component</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    <!-- External Volt CSS -->
    <link href="https://cdn.jsdelivr.net/npm/volt.css@1.1.1/dist/volt.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 font-sans text-gray-900">

    <!-- Page Content -->
    <div class="container mx-auto p-6">
        @yield('content')
    </div>

    <!-- Footer -->
    <footer class="bg-teal-500 text-white text-center py-4 mt-6">
        &copy; 2025 Posyandu Kesehatan. All Rights Reserved.
    </footer>

    <!-- External Volt JS -->
    <script src="https://cdn.jsdelivr.net/npm/volt.js@1.1.1/dist/volt.min.js"></script>

    <!-- Application JS -->
    <script src="{{ mix('js/app.js') }}"></script>
</body>
</html>
