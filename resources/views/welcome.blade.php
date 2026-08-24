<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>GLE Academy</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-50">

    <nav class="bg-white shadow">
        <div class="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center">
            <span class="text-xl font-bold text-blue-600">GLE Academy</span>
            <div class="space-x-4">
                @auth
                    <a href="{{ route('dashboard') }}" class="text-gray-700">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="text-gray-700">Login</a>
                    <a href="{{ route('register') }}" class="bg-blue-600 text-white px-4 py-2 rounded">Daftar</a>
                @endauth
            </div>
        </div>
    </nav>

    <section class="max-w-7xl mx-auto px-4 py-24 text-center">
        <h1 class="text-4xl font-bold text-gray-900 mb-4">Belajar Kapan Saja, di Mana Saja</h1>
        <p class="text-gray-600 mb-8 max-w-xl mx-auto">
            GLE Academy adalah platform belajar online dengan kelas dari berbagai instruktur, lengkap dengan materi, video, dan quiz interaktif.
        </p>
        <a href="{{ route('register') }}" class="bg-blue-600 text-white px-6 py-3 rounded font-semibold">
            Mulai Belajar Sekarang
        </a>
    </section>

</body>
</html>