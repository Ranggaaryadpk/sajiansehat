@extends('layouts.app')

@section('content')
<div class="container mx-auto px-6 flex justify-center py-20">
    <div class="w-full max-w-md bg-white rounded-[2.5rem] shadow-sm border border-gray-100 p-10">
        <h2 class="text-3xl font-black text-center text-gray-800 mb-2">Password Baru</h2>
        <p class="text-gray-400 text-center mb-10 text-sm font-medium">Buat password yang kuat dan mudah diingat.</p>

        <form action="{{ route('password.update') }}" method="POST" class="space-y-6">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            
            <div>
                <label class="block text-[11px] font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">Konfirmasi Email</label>
                <input type="email" name="email" value="{{ request()->email }}" class="w-full border-2 border-gray-50 bg-gray-50/50 rounded-2xl p-4 text-sm font-bold outline-none focus:border-green-500 transition-all cursor-not-allowed" readonly required>
            </div>

            <div>
                <label class="block text-[11px] font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">Password Baru</label>
                <div class="relative">
                    <input type="password" id="passReset" name="password" 
                        class="w-full border-2 {{ $errors->has('password') ? 'border-red-400' : 'border-gray-50' }} bg-gray-50/50 rounded-2xl px-5 py-4 text-sm font-bold outline-none focus:border-green-500 transition-all" 
                        placeholder="Minimal 8 karakter" required>
                    <button type="button" onclick="togglePassword('passReset', 'lockIcon1')" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-green-600 transition-colors">
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
                <label class="block text-[11px] font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">Ulangi Password Baru</label>
                <div class="relative">
                    <input type="password" id="passConfirm" name="password_confirmation" 
                        class="w-full border-2 border-gray-50 bg-gray-50/50 rounded-2xl px-5 py-4 text-sm font-bold outline-none focus:border-green-500 transition-all" 
                        placeholder="Ulangi password baru" required>
                    <button type="button" onclick="togglePassword('passConfirm', 'lockIcon2')" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-green-600 transition-colors">
                        <svg id="lockIcon2" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </button>
                </div>
            </div>

            <button type="submit" class="w-full bg-green-600 text-white py-4 rounded-2xl font-black text-base hover:bg-green-700 transition-all shadow-lg shadow-green-100 mt-4">
                Update Password
            </button>
        </form>
    </div>
</div>

<script>
    function togglePassword(inputId, iconId) {
        const input = document.getElementById(inputId);
        const icon = document.getElementById(iconId);
        
        if (input.type === "password") {
            input.type = "text";
            // Ganti ke ikon gembok terbuka
            icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z" />';
            icon.classList.add('text-green-600');
        } else {
            input.type = "password";
            // Ganti kembali ke ikon gembok tertutup
            icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />';
            icon.classList.remove('text-green-600');
        }
    }
</script>
@endsection