@extends('layouts.app')

@section('content')
<div class="container mx-auto px-6 flex justify-center py-20">
    <div class="w-full max-w-md bg-white rounded-[2.5rem] shadow-sm border border-gray-100 p-10">
        <h2 class="text-3xl font-black text-center text-gray-800 mb-2">Lupa Password?</h2>
        <p class="text-gray-400 text-center mb-10 text-sm font-medium">Masukkan email Anda untuk menerima link reset.</p>

        <form action="{{ route('password.email') }}" method="POST" class="space-y-6">
            @csrf
            <div>
                <label class="block text-[11px] font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">Email Terdaftar</label>
                <input type="email" name="email" value="{{ old('email') }}"
                    class="w-full border-2 {{ $errors->has('email') ? 'border-red-400 bg-red-50/30' : 'border-gray-50 bg-gray-50/50' }} rounded-2xl p-4 text-sm font-bold outline-none focus:border-green-500 transition-all" 
                    placeholder="nama@gmail.com" required>
                @error('email')
                    <p class="text-[10px] text-red-500 font-bold mt-2 ml-2 italic">⚠️ {{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="w-full bg-green-600 text-white py-4 rounded-2xl font-black text-base hover:bg-green-700 transition-all shadow-lg shadow-green-100 mt-4">
                Kirim Link Reset
            </button>
        </form>

        <div class="text-center mt-8">
            <a href="{{ route('login') }}" class="text-sm font-bold text-gray-400 hover:text-green-600"> Kembali ke Login</a>
        </div>
    </div>
</div>
@endsection