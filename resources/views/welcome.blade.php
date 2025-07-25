<!-- resources/views/welcome.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Posyandu Admin Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
</head>
<body class="bg-gray-100 font-sans text-gray-900">

    <!-- Main container -->
    <div class="flex justify-center items-center min-h-screen bg-teal-500">
        <div class="text-center text-white p-6 bg-opacity-75 rounded-lg shadow-lg">
            <h1 class="text-4xl font-bold mb-4">Welcome to Posyandu Admin Dashboard</h1>
            <p class="text-xl mb-6">Manage all aspects of your Posyandu activities with ease. Keep track of patients, schedules, articles, and more.</p>

            <div class="flex justify-center space-x-4">
                <a href="{{ route('login') }}" class="bg-teal-600 text-white px-6 py-2 rounded-lg hover:bg-teal-700 transition duration-300">Login</a>
                <a href="{{ route('register') }}" class="bg-teal-600 text-white px-6 py-2 rounded-lg hover:bg-teal-700 transition duration-300">Register</a>
            </div>
        </div>
    </div>

    <script src="{{ mix('js/app.js') }}"></script>
</body>
</html>
