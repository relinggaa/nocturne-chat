<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enter Username</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: transparent;
        }
    </style>
</head>

<body>
    <!-- Video Background -->
    <video autoplay muted loop class="absolute top-0 left-0 w-full h-full object-cover -z-10">
        <source src="{{ asset('videos/background.mp4') }}" type="video/mp4">
        Your browser does not support the video tag.
    </video>

    <!-- Main Content -->
    <section class="form-user bg-white/20 shadow-xl rounded-lg p-8">
        <!-- Header -->
        <header class="flex flex-col items-center mb-6">
            <div class="w-full flex justify-center">
                <img class="img-user" src="{{ asset('img/logo.png') }}" alt="Logo">
            </div>
            <h1 class="text-2xl font-semibold text-gray-800 text-center mt-2">Chat App</h1>
        </header>

        <!-- Form -->
        <form action="{{ route('set.username') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label for="username" class="block text-gray-700 font-medium">Enter your username:</label>
                <input type="text" name="username" id="username" required 
                    class="w-full mt-2 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
            </div>
            <button type="submit" 
                class="w-full bg-black text-white py-3 px-4 rounded-lg font-semibold hover:bg-gray-800 transition ease-in-out duration-200">
                Join Chat
            </button>
        </form>
    </section>
   
</body>

</html>
