<nav class="bg-white border-b border-gray-100 sticky top-0 z-40">
    <div class="container mx-auto px-6 py-4 flex justify-between items-center">
        <div class="flex items-center gap-8">
            <a href="/" class="text-2xl font-black text-orange-500 tracking-tighter">Sajian<span class="text-green-600">Sehat</span></a>
            
            <div class="hidden md:flex items-center gap-6 text-sm font-bold text-gray-500">
                <a href="/" class="{{ Request::is('/') ? 'text-green-600' : 'hover:text-green-600 transition-colors' }}">Home</a>
                <a href="/rekomendasi" class="{{ Request::is('rekomendasi*') ? 'text-green-600' : 'hover:text-green-600 transition-colors' }}">Rekomendasi Makanan</a>
                <a href="/riwayat" class="{{ Request::is('riwayat*') ? 'text-green-600' : 'hover:text-green-600 transition-colors' }}">Riwayat Rekomendasi</a>
            </div>
        </div>

        <div class="flex items-center gap-4 relative">
            @auth
                <div class="relative" id="profileDropdownContainer">
                    <button onclick="toggleDropdown()" class="flex items-center gap-3 focus:outline-none group">
                        <div class="text-right hidden sm:block">
                            <p class="text-xs font-black text-gray-800 line-clamp-1 uppercase tracking-tighter">{{ Auth::user()->name }}</p>
                            <p class="text-[10px] text-gray-400 font-bold">Member</p>
                        </div>
                        
                        <div class="w-10 h-10 rounded-full border-2 border-green-100 p-0.5 group-hover:border-green-500 transition-all">
                            @if(Auth::user()->avatar)
                                <img src="{{ Auth::user()->avatar }}" class="w-full h-full rounded-full object-cover" alt="Profile">
                            @else
                                <div class="w-full h-full rounded-full bg-green-600 flex items-center justify-center text-white text-sm font-black">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </div>
                            @endif
                        </div>
                    </button>

                    <div id="dropdownMenu" class="hidden absolute right-0 mt-3 w-48 bg-white border border-gray-100 rounded-2xl shadow-xl py-2 z-50 animate-fade-in">
                        <div class="px-4 py-2 border-b border-gray-50 mb-1">
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Akun Saya</p>
                        </div>
                        
                        <a href="/profile" class="flex items-center gap-3 px-4 py-2 text-sm font-bold text-gray-600 hover:bg-green-50 hover:text-green-600 transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            Profil
                        </a>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full flex items-center gap-3 px-4 py-2 text-sm font-bold text-red-500 hover:bg-red-50 transition-all">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                                Keluar
                            </button>
                        </form>
                    </div>
                </div>
            @else
                <a href="/login" class="text-sm font-bold text-gray-500 hover:text-green-600 transition-colors">Login</a>
                <a href="/register" class="bg-green-600 text-white px-6 py-2.5 rounded-xl font-bold text-sm hover:bg-green-700 transition-all shadow-lg shadow-green-100">Daftar</a>
            @endauth
        </div>
    </div>
</nav>

<script>
    function toggleDropdown() {
        const menu = document.getElementById('dropdownMenu');
        menu.classList.toggle('hidden');
    }

    // Menutup dropdown jika user mengklik di luar menu
    window.onclick = function(event) {
        if (!event.target.closest('#profileDropdownContainer')) {
            const menu = document.getElementById('dropdownMenu');
            if (!menu.classList.contains('hidden')) {
                menu.classList.add('hidden');
            }
        }
    }
</script>

<style>
    .animate-fade-in { animation: fadeIn 0.2s ease-out; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }
</style>