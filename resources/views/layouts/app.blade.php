<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SajianSehat - Analisis AI</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            margin: 0;
            padding: 0;
            /* Mencegah scrollbar horizontal jika ada animasi */
            overflow-x: hidden; 
        }
        .animate-fade-in { animation: fadeIn 0.4s ease-out; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        [x-cloak] { display: none !important; }
    </style>
    @stack('styles')
</head>
<body class="bg-gray-50 text-gray-900">

    @include('layouts.navigation')

    <main>
        @yield('content')
    </main>

    {{-- Notifikasi Info Penting --}}
    @if(!Auth::check() && Request::is('/') && !session('has_seen_info'))
        <div id="info-penting" class="fixed bottom-10 right-10 z-50 animate-fade-in">
            <div class="bg-white border border-gray-100 rounded-[2.5rem] p-6 shadow-2xl max-w-sm relative border-t-4 border-t-green-500">
                <button onclick="closeInfo()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
                <span class="bg-orange-100 text-orange-600 text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-wider">Informasi Penting</span>
                <h4 class="text-lg font-black text-gray-800 mt-4 mb-2">Simpan Riwayatmu! 🍲</h4>
                <p class="text-sm text-gray-500 leading-relaxed mb-6">
                    Ingin melihat kembali resep sehat? <strong>Login sekarang</strong> untuk membuka menu Riwayat.
                </p>
                <a href="{{ route('login') }}" class="block w-full bg-green-600 text-white text-center py-3 rounded-xl font-bold text-sm hover:bg-green-700 transition-all mb-3">
                    Login Sekarang
                </a>
                <button onclick="closeInfo()" class="block w-full text-center text-gray-400 text-xs font-bold hover:text-gray-600">
                    Nanti aja
                </button>
            </div>
        </div>
        @php session(['has_seen_info' => true]); @endphp
    @endif

    <script>
        function closeInfo() {
            const infoBox = document.getElementById('info-penting');
            if (infoBox) {
                infoBox.classList.add('opacity-0', 'translate-y-10');
                setTimeout(() => infoBox.remove(), 300);
            }
        }
    </script>
    @stack('scripts')
</body>
</html>