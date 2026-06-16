@extends('layouts.app')

@section('content')
<div class="container mx-auto px-6 max-w-3xl py-6 h-[calc(100vh-80px)] flex flex-col justify-center animate-fade-in">
    
    <div class="bg-white border border-gray-100 rounded-[2.5rem] p-8 md:p-10 shadow-sm shrink overflow-hidden">
        <div class="mb-8 flex items-center justify-between border-b border-gray-50 pb-6">
            <div>
                <h2 class="text-2xl font-black text-gray-800">Edit Profil <span class="text-green-600">Anda</span></h2>
                <p class="text-[11px] font-medium text-gray-400 uppercase tracking-widest mt-1">Perbarui data diri & keamanan akun</p>
            </div>
            <div class="hidden md:block">
                <span class="text-[10px] font-black text-orange-500 bg-orange-50 px-3 py-1 rounded-full uppercase">Update Session</span>
            </div>
        </div>

        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PATCH')

            {{-- Foto Profil --}}
            <div class="flex items-center gap-6 bg-gray-50/50 p-4 rounded-[2rem] border border-gray-50">
                <div class="relative shrink-0">
                    <div class="w-16 h-16 rounded-full bg-green-600 flex items-center justify-center text-white font-black text-xl border-4 border-white shadow-md">
                        @if(Auth::user()->avatar)
                            <img src="{{ asset(Auth::user()->avatar) }}" class="w-full h-full rounded-full object-cover">
                        @else
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        @endif
                    </div>
                </div>
                <div class="flex flex-col gap-1">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Unggah Foto Baru</label>
                    <input type="file" name="avatar" class="text-[10px] text-gray-500 file:mr-4 file:py-1.5 file:px-4 file:rounded-full file:border-0 file:text-[10px] file:font-black file:bg-green-600 file:text-white hover:file:bg-green-700 transition-all cursor-pointer">
                </div>
            </div>

            {{-- Input Data Diri --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ Auth::user()->name }}" class="w-full border-2 border-gray-50 rounded-2xl p-3.5 text-sm font-bold bg-gray-50/30 focus:border-green-500 focus:bg-white outline-none transition-all" placeholder="Nama Anda">
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Email (Locked)</label>
                    <div class="relative">
                        <input type="email" value="{{ Auth::user()->email }}" disabled class="w-full border-2 border-gray-50 rounded-2xl p-3.5 text-sm font-bold bg-gray-100 text-gray-400 cursor-not-allowed shadow-inner">
                        <span class="absolute right-4 top-1/2 -translate-y-1/2 opacity-30 text-xs">🔒</span>
                    </div>
                </div>
            </div>

            {{-- Bagian Password --}}
            <div class="bg-orange-50/30 rounded-[2rem] p-6 border border-orange-50">
                <h3 class="text-[10px] font-black text-orange-600 uppercase tracking-widest mb-4 flex items-center gap-2">
                    <span class="text-xs">🔑</span> Ganti Password (Opsional)
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="space-y-2">
                        <input type="password" name="password" placeholder="Password Baru" class="w-full border-2 border-white rounded-2xl p-3.5 text-sm font-bold bg-white focus:border-green-500 outline-none transition-all shadow-sm">
                    </div>
                    <div class="space-y-2">
                        <input type="password" name="password_confirmation" placeholder="Ulangi Password" class="w-full border-2 border-white rounded-2xl p-3.5 text-sm font-bold bg-white focus:border-green-500 outline-none transition-all shadow-sm">
                    </div>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="flex flex-col md:flex-row gap-4 pt-2">
                <a href="{{ route('profile') }}" class="flex-1 bg-white text-gray-400 py-4 rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-gray-50 hover:text-gray-600 transition-all text-center border-2 border-gray-50">
                    Batal
                </a>

                <button type="submit" class="flex-[2] bg-green-600 text-white py-4 rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-green-700 transition-all shadow-xl shadow-green-100 active:scale-[0.98]">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<style>
    /* Hilangkan scrollbar browser */
    html, body { overflow: hidden; }

    /* Custom Input Focus */
    input:focus::placeholder {
        color: transparent;
    }
</style>
@endsection