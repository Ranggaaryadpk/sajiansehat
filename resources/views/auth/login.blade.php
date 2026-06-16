@extends('layouts.app')

@section('content')
<div class="container mx-auto px-6 flex justify-center py-6 h-[calc(100vh-80px)] items-center">
    <div class="w-full max-w-sm bg-white rounded-[2.5rem] shadow-sm border border-gray-100 p-8">
        <h2 class="text-2xl font-black text-center text-gray-800 mb-1">Selamat Datang!</h2>
        <p class="text-gray-400 text-center mb-6 text-xs font-medium">Silakan login untuk akses fitur lengkap.</p>

        @if($errors->any())
            <div class="bg-red-50 text-red-500 p-3 rounded-xl mb-4 text-[10px] font-bold text-center border border-red-100">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('login') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5 ml-1">Email</label>
                <input type="email" name="email" value="{{ old('email') }}"
                    class="w-full border-2 {{ $errors->has('email') ? 'border-red-400' : 'border-gray-50' }} bg-gray-50/50 rounded-2xl p-3.5 text-sm font-bold outline-none focus:border-green-500 transition-all" 
                    placeholder="nama@gmail.com" required>
                @error('email')
                    <p class="text-[9px] text-red-500 font-bold mt-1 ml-1 italic">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5 ml-1">Password</label>
                <div class="relative">
                    <input type="password" id="passwordInput" name="password" 
                        class="w-full border-2 {{ $errors->has('password') ? 'border-red-400' : 'border-gray-50' }} bg-gray-50/50 rounded-2xl p-3.5 text-sm font-bold outline-none focus:border-green-500 transition-all" 
                        placeholder="••••••••" required>
                </div>
                @error('password')
                    <p class="text-[9px] text-red-500 font-bold mt-1 ml-1 italic">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="flex justify-end pr-2">
                <a href="{{ route('password.request') }}" class="text-[10px] font-bold text-gray-400 hover:text-green-600 transition-colors">
                    Lupa Password?
                </a>
            </div>
            
            <button type="submit" class="w-full bg-green-600 text-white py-3.5 rounded-2xl font-black text-sm hover:bg-green-700 transition-all shadow-lg shadow-green-100 mt-2">
                Login
            </button>
        </form>

        <div class="relative flex py-6 items-center">
            <div class="flex-grow border-t border-gray-100"></div>
            <span class="flex-shrink mx-4 text-[10px] font-bold text-gray-300 uppercase tracking-tighter">Atau</span>
            <div class="flex-grow border-t border-gray-100"></div>
        </div>

        <div class="text-center">
            <a href="{{ route('google.login') }}" class="inline-flex items-center justify-center w-full border-2 border-gray-50 py-3 rounded-2xl hover:bg-gray-50 transition-all group">
                <img src="https://www.svgrepo.com/show/355037/google.svg" class="w-4 h-4 mr-3 opacity-80 group-hover:opacity-100">
                <span class="font-bold text-gray-600 text-xs">Login Menggunakan Google</span>
            </a>

            <p class="mt-6 text-xs text-gray-400 font-medium">
                Belum punya akun? <a href="{{ route('register') }}" class="text-green-600 font-bold hover:underline">Daftar</a>
            </p>
        </div>
    </div>
</div>

<script>
    // Script tetap sama sesuai permintaan struktur tidak berubah
    function togglePassword() {
        const input = document.getElementById('passwordInput');
        const icon = document.getElementById('lockIcon');
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

<style>
    /* Tambahan agar scrollbar browser hilang */
    html, body { overflow: hidden; }
</style>
@endsection