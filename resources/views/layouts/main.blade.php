<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Website Rekomendasi Makanan</title>
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <nav class="bg-white border-b p-4 flex justify-between items-center px-10">
        <div class="flex gap-8 font-semibold text-gray-700">
            <a href="{{ route('home') }}" class="hover:text-green-600">Home</a>
            <a href="{{ route('rekomendasi.index') }}" class="hover:text-green-600">Rekomendasi Makanan</a>
            <a href="{{ route('riwayat') }}" class="hover:text-green-600">Riwayat Rekomendasi</a>
        </div>
        <div class="flex gap-4 items-center">
            @auth
                <span class="text-sm">Halo, <b>{{ Auth::user()->name }}</b></span>
                <form action="{{ route('logout') }}" method="POST">@csrf <button class="text-red-500">Logout</button></form>
            @else
                <a href="{{ route('login') }}" class="bg-green-600 text-white px-6 py-2 rounded-full hover:bg-green-700">Login</a>
            @endauth
        </div>
    </nav>

    @yield('content')

    @guest
        @if(!request()->is('login') && !request()->is('register'))
        <div id="modal-notif" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white p-8 rounded-xl max-w-sm text-center shadow-2xl">
                <h3 class="text-xl font-bold mb-2">Halo Pengunjung!</h3>
                <p class="text-gray-600 mb-6">Login sekarang untuk menyimpan riwayat rekomendasi makanan sehatmu secara gratis.</p>
                <div class="flex flex-col gap-3">
                    <a href="{{ route('login') }}" class="bg-green-600 text-white py-2 rounded-lg font-bold">Login</a>
                    <button onclick="document.getElementById('modal-notif').remove()" class="text-gray-400 text-sm">Nanti aja</button>
                </div>
            </div>
        </div>
        @endif
    @endguest
</body>
</html>