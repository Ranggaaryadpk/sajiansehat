@extends('layouts.app')

@section('content')
<div class="container mx-auto px-6 max-w-4xl py-6 h-[calc(100vh-80px)] flex flex-col justify-center animate-fade-in">
    
    <div class="bg-white border border-gray-100 rounded-[2.5rem] p-6 md:p-8 shadow-sm mb-6 shrink-0">
        <div class="flex flex-col md:flex-row items-center gap-6 md:gap-10">
            <div class="relative">
                <div class="w-28 h-28 md:w-32 md:h-32 rounded-full border-4 border-green-50 p-1 overflow-hidden bg-gray-50 flex items-center justify-center shadow-inner">
                    @php
                        $avatarUrl = Auth::user()->avatar;
                        if ($avatarUrl && !filter_var($avatarUrl, FILTER_VALIDATE_URL)) {
                            $avatarUrl = asset($avatarUrl);
                        }
                    @endphp
                    
                    @if($avatarUrl)
                        <img src="{{ $avatarUrl }}" 
                             class="w-full h-full rounded-full object-cover" 
                             onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=16a34a&color=fff'" 
                             alt="Profile">
                    @else
                        <div class="w-full h-full rounded-full bg-green-600 flex items-center justify-center text-white text-4xl font-black">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                    @endif
                </div>
                <div class="absolute bottom-1 right-1 w-6 h-6 bg-green-500 border-2 border-white rounded-full"></div>
            </div>

            <div class="text-center md:text-left flex-1">
                <div class="flex flex-wrap items-center justify-center md:justify-start gap-3 mb-1">
                    <h2 class="text-2xl md:text-3xl font-black text-gray-800 tracking-tight">{{ Auth::user()->name }}</h2>
                    <span class="bg-green-100 text-green-600 text-[9px] font-black px-3 py-1 rounded-full uppercase tracking-widest">Member</span>
                </div>
                <p class="text-gray-500 text-sm font-medium mb-4">{{ Auth::user()->email }}</p>
                
                <div class="flex flex-wrap justify-center md:justify-start gap-3">
                    <div class="bg-gray-50 px-4 py-2 rounded-2xl border border-gray-100">
                        <p class="text-[9px] font-black text-gray-400 uppercase tracking-tighter">Bergabung</p>
                        <p class="text-xs font-bold text-gray-700">{{ Auth::user()->created_at->format('d M Y') }}</p>
                    </div>
                    <div class="bg-gray-50 px-4 py-2 rounded-2xl border border-gray-100">
                        <p class="text-[9px] font-black text-gray-400 uppercase tracking-tighter">Total Riwayat</p>
                        <p class="text-xs font-bold text-gray-700">{{ $totalRiwayat ?? 0 }} Resep</p>
                    </div>
                </div>
            </div>

            <div class="w-full md:w-auto">
                <a href="{{ route('profile.edit') }}" class="block text-center bg-gray-900 text-white px-8 py-3 rounded-2xl font-bold text-sm hover:bg-black transition-all shadow-lg hover:shadow-gray-200">
                    Edit Profil
                </a>
            </div>
        </div>
    </div>

    <div class="bg-white border border-gray-100 rounded-[2.5rem] p-8 md:p-10 shadow-sm shrink">
        <h4 class="text-base font-black text-gray-800 mb-6 flex items-center gap-2">
            <span class="p-2 bg-orange-50 rounded-xl text-orange-500 text-xs">📋</span>
            Detail Informasi Akun
        </h4>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-5">
            <div class="group">
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1 mb-2 block">Nama Lengkap</label>
                <div class="w-full border-2 border-gray-50 rounded-2xl p-4 bg-gray-50/50 text-sm font-bold text-gray-700 flex items-center gap-3 group-hover:bg-white group-hover:border-green-100 transition-all">
                    <span class="text-lg">👤</span> {{ Auth::user()->name }}
                </div>
            </div>

            <div class="group">
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1 mb-2 block">Alamat Email</label>
                <div class="w-full border-2 border-gray-50 rounded-2xl p-4 bg-gray-50/50 text-sm font-bold text-gray-700 flex items-center gap-3 group-hover:bg-white group-hover:border-green-100 transition-all">
                    <span class="text-lg">📧</span> {{ Auth::user()->email }}
                </div>
            </div>

            <div class="group">
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1 mb-2 block">Negara Asal</label>
                <div class="w-full border-2 border-gray-50 rounded-2xl p-4 bg-gray-50/50 text-sm font-bold text-gray-700 flex items-center gap-3 group-hover:bg-white group-hover:border-green-100 transition-all">
                    <span class="text-lg">🇮🇩</span> {{ Auth::user()->asal_negara ?? 'Indonesia' }}
                </div>
            </div>

            <div class="group">
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1 mb-2 block">Status Keamanan</label>
                <div class="w-full border-2 border-gray-50 rounded-2xl p-4 bg-gray-50/50 text-sm font-bold text-green-600 flex items-center gap-3 group-hover:bg-white group-hover:border-green-100 transition-all">
                    <span class="text-lg">🛡️</span> {{ Auth::user()->google_id ? 'Google Connected' : 'Standard Account' }}
                </div>
            </div>
        </div>

        <div class="mt-8 pt-6 border-t border-gray-50 flex flex-col md:flex-row justify-between items-center gap-4">
            <p class="text-[11px] text-gray-400 font-medium italic">
                Data ini digunakan untuk personalisasi rekomendasi makanan Anda secara cerdas.
            </p>
            <div class="flex gap-3">
                <span class="text-[9px] font-black text-green-500 uppercase bg-green-50 px-3 py-1.5 rounded-lg border border-green-100">Verified</span>
                <span class="text-[9px] font-black text-orange-500 uppercase bg-orange-50 px-3 py-1.5 rounded-lg border border-orange-100">Active</span>
            </div>
        </div>
    </div>
</div>

<style>
    /* Haluskan transisi hover pada box detail */
    .group:hover div {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px -10px rgba(0,0,0,0.05);
    }
</style>
@endsection