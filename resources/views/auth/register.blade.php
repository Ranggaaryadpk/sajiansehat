@extends('layouts.app')

@section('content')
<div class="container mx-auto px-6 flex justify-center py-4">
    <div class="w-full max-w-lg bg-white rounded-[2.5rem] shadow-xl shadow-gray-100 border border-gray-100 p-8 md:p-8">
        <div class="text-center mb-6">
            <h2 class="text-2xl font-black text-gray-800 mb-2">Buat Akun Baru</h2>
            <p class="text-sm text-gray-400 font-medium">Lengkapi data untuk mulai hidup sehat</p>
        </div>

        <form action="{{ route('register') }}" method="POST" class="space-y-4">
            @csrf
            
            <div>
                <label class="block text-[11px] font-black text-gray-400 uppercase tracking-widest ml-1 mb-1">Nama Lengkap</label>
                <input type="text" name="name" value="{{ old('name') }}" 
                    class="w-full border-2 {{ $errors->has('name') ? 'border-red-400 bg-red-50/30' : 'border-gray-50 bg-gray-50/50' }} rounded-2xl px-5 py-3 text-sm font-bold outline-none focus:border-green-500 focus:bg-white transition-all" 
                    placeholder="Nama lengkap Anda" required>
                @error('name')
                    <p class="text-[10px] text-red-500 font-bold mt-2 ml-2 italic">⚠️ {{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-[11px] font-black text-gray-400 uppercase tracking-widest ml-1 mb-1">Asal Negara</label>
                    <input type="text" name="asal_negara" value="{{ old('asal_negara') }}" 
                        class="w-full border-2 {{ $errors->has('asal_negara') ? 'border-red-400 bg-red-50/30' : 'border-gray-50 bg-gray-50/50' }} rounded-2xl px-5 py-3 text-sm font-bold outline-none focus:border-green-500 focus:bg-white transition-all" 
                        placeholder="Indonesia" required>
                    @error('asal_negara')
                        <p class="text-[10px] text-red-500 font-bold mt-2 ml-2 italic">⚠️ {{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-[11px] font-black text-gray-400 uppercase tracking-widest ml-1 mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" 
                        class="w-full border-2 {{ $errors->has('email') ? 'border-red-400 bg-red-50/30' : 'border-gray-50 bg-gray-50/50' }} rounded-2xl px-5 py-3 text-sm font-bold outline-none focus:border-green-500 focus:bg-white transition-all" 
                        placeholder="Email aktif" required>
                    @error('email')
                        <p class="text-[10px] text-red-500 font-bold mt-2 ml-2 italic">⚠️ {{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-[11px] font-black text-gray-400 uppercase tracking-widest ml-1 mb-1">Password</label>
                    <div class="relative">
                        <input type="password" id="passReg" name="password" 
                            class="w-full border-2 {{ $errors->has('password') ? 'border-red-400 bg-red-50/30' : 'border-gray-50 bg-gray-50/50' }} rounded-2xl px-5 py-3 text-sm font-bold outline-none focus:border-green-500 focus:bg-white transition-all" 
                            placeholder="Min. 8 karakter" required>
                        <button type="button" onclick="toggleRegPassword('passReg', 'lockIcon1')" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-green-600 transition-colors">
                            <svg id="lockIcon1" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </button>
                    </div>
                    @error('password')
                        <p class="text-[10px] text-red-500 font-bold mt-2 ml-2 italic">⚠️ {{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-[11px] font-black text-gray-400 uppercase tracking-widest ml-1 mb-1">Konfirmasi</label>
                    <div class="relative">
                        <input type="password" id="passConfirm" name="password_confirmation" 
                            class="w-full border-2 border-gray-50 bg-gray-50/50 rounded-2xl px-5 py-3 text-sm font-bold outline-none focus:border-green-500 focus:bg-white transition-all" 
                            placeholder="Ulangi password" required>
                        <button type="button" onclick="toggleRegPassword('passConfirm', 'lockIcon2')" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-green-600 transition-colors">
                            <svg id="lockIcon2" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <button type="submit" class="w-full bg-green-600 text-white py-3.5 rounded-2xl font-black text-base hover:bg-green-700 transition shadow-lg shadow-green-100 mt-2">
                Daftar Akun
            </button>
        </form>

        <div class="mt-6">
            <div class="relative flex items-center justify-center mb-5">
                <div class="border-t border-gray-100 w-full"></div>
                <span class="bg-white px-4 text-[11px] font-bold text-gray-300 uppercase absolute">Atau</span>
            </div>

            <a href="{{ route('google.login') }}" class="flex items-center justify-center gap-4 w-full border-2 border-gray-50 py-3 rounded-2xl hover:bg-gray-50 transition-all group">
                <img src="https://www.svgrepo.com/show/355037/google.svg" class="w-5 h-5 group-hover:scale-110 transition-transform">
                <span class="font-black text-gray-600 text-sm">Daftar Cepat Menggunakan Google</span>
            </a>
        </div>
        
        <p class="text-center mt-6 text-sm font-bold text-gray-400">
            Sudah memiliki akun? <a href="{{ route('login') }}" class="text-green-600 hover:underline">login di sini</a>
        </p>
    </div>
</div>

<script>
    function toggleRegPassword(inputId, iconId) {
        const input = document.getElementById(inputId);
        const icon = document.getElementById(iconId);
        
        if (input.type === "password") {
            input.type = "text";
            icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z" />';
            icon.classList.add('text-green-600');
        } else {
            input.type = "password";
            icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />';
            icon.classList.remove('text-green-600');
        }
    }
</script>
@endsection