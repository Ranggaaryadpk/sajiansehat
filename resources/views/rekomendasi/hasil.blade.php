@extends('layouts.app')

@section('content')
@php
    use Carbon\Carbon;
    Carbon::setLocale('id');
    $today = Carbon::now();
    
    $resepPerHari = array_chunk($resep, 3);
    $totalHari = count($resepPerHari);
    $totalMinggu = ceil($totalHari / 7);
@endphp

<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

<div class="container mx-auto px-4 py-10 max-w-6xl" 
     x-data="{ 
        currentDay: 1, 
        currentWeek: 1,
        durasi: '{{ $durasi }}',
        showLoginModal: false 
     }">
    
    {{-- ANALISIS BOX --}}
    <div class="mb-10 grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-green-500 rounded-[3rem] p-10 text-white flex flex-col justify-center shadow-xl border-b-8 border-green-700">
            <span class="text-xs font-black uppercase tracking-[0.4em] opacity-90 mb-2">Program Rekomendasi</span>
            <h3 class="text-4xl lg:text-5xl font-black uppercase leading-none mb-4">{{ str_replace('_', ' ', $durasi) }}</h3>
            <div class="bg-green-600/50 py-2 px-4 rounded-xl inline-block w-fit text-[11px] font-bold uppercase tracking-widest">
                {{ $today->translatedFormat('d M Y') }} - {{ $today->copy()->addDays(count($resepPerHari)-1)->translatedFormat('d M Y') }}
            </div>
        </div>

        <div class="md:col-span-2 bg-white rounded-[3rem] p-10 shadow-sm border border-gray-100 flex items-center gap-8">
            <div class="h-20 w-20 bg-green-50 rounded-full flex-shrink-0 flex items-center justify-center text-4xl shadow-inner">✨</div>
            <div>
                <h2 class="text-xl font-black text-gray-800 uppercase">Analisis Nutrisi AI</h2>
                <p class="text-[11px] font-black text-green-600 mt-2 uppercase tracking-[0.2em]">{{ $analisis['analisis_kesehatan'] ?? 'Profil Optimal' }}</p>
                <p class="text-xs text-gray-400 mt-2 font-medium leading-relaxed">{{ $analisis['saran_nutrisi'] ?? 'Penuhi asupan gizi harian Anda.' }}</p>
            </div>
        </div>
    </div>

    {{-- NAVIGATION --}}
    <div class="mb-12 space-y-6 text-center">
        @if($durasi == '1_bulan')
        <div class="flex flex-wrap justify-center gap-2">
            @for($w = 1; $w <= $totalMinggu; $w++)
            <button @click="currentWeek = {{ $w }}; currentDay = (({{ $w }}-1) * 7) + 1"
                :class="currentWeek === {{ $w }} ? 'bg-black text-white shadow-lg' : 'bg-gray-100 text-gray-400'"
                class="px-6 py-3 rounded-2xl font-black uppercase text-[10px] transition-all transform hover:scale-105">
                MINGGU {{ $w }}
            </button>
            @endfor
        </div>
        @endif

        <div class="flex overflow-x-auto pb-6 space-x-3 no-scrollbar justify-start md:justify-center">
            @foreach($resepPerHari as $index => $r)
                @php 
                    $hariKe = $index + 1; 
                    $tanggalHariIni = $today->copy()->addDays($index);
                @endphp
                <button 
                    x-show="durasi !== '1_bulan' || ({{ $hariKe }} >= (currentWeek - 1) * 7 + 1 && {{ $hariKe }} <= currentWeek * 7)"
                    @click="currentDay = {{ $hariKe }}" 
                    :class="currentDay === {{ $hariKe }} ? 'bg-green-600 text-white shadow-2xl scale-110' : 'bg-white text-gray-400 border-gray-100'"
                    class="flex-none w-28 py-4 rounded-[2.5rem] border-2 flex flex-col items-center transition-all duration-300">
                    <span class="font-black uppercase text-[8px] opacity-70">{{ $tanggalHariIni->translatedFormat('l') }}</span>
                    <span class="text-2xl font-black leading-none my-1">{{ $tanggalHariIni->translatedFormat('d') }}</span>
                    <span class="font-bold uppercase text-[9px] tracking-tighter">{{ $tanggalHariIni->translatedFormat('M Y') }}</span>
                </button>
            @endforeach
        </div>
    </div>

    {{-- RECIPE GRID --}}
    <div class="space-y-12">
        @foreach($resepPerHari as $dayIndex => $tigaResep)
        <div x-show="currentDay === {{ $dayIndex + 1 }}" 
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform translate-y-8"
            x-transition:enter-end="opacity-100 transform translate-y-0"
            class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
            
            @foreach($tigaResep as $idx => $item)
            <div class="bg-white rounded-[3rem] border border-gray-100 overflow-hidden shadow-sm flex flex-col hover:shadow-xl transition-all border-b-4 hover:border-green-500 group">
                <div class="relative h-56 overflow-hidden flex-shrink-0">
                    <img src="{{ $item['image'] }}" class="w-full h-full object-cover group-hover:scale-105 transition-all duration-700">
                    <div class="absolute top-5 left-5">
                        <span class="bg-white/95 backdrop-blur px-5 py-2 rounded-full text-[10px] font-black text-gray-800 uppercase italic">
                            @if($idx == 0) 🌅 BREAKFAST @elseif($idx == 1) ☀️ LUNCH @else 🌙 DINNER @endif
                        </span>
                    </div>
                </div>

                <div class="p-8">
                    <h4 class="font-black text-gray-800 text-sm mb-6 uppercase line-clamp-2 h-10 text-left">{{ $item['title'] }}</h4>
                    <div class="mb-6">
                        <a href="https://www.youtube.com/results?search_query=cara+memasak+{{ urlencode($item['title']) }}" target="_blank" class="flex items-center justify-center gap-3 w-full bg-rose-50 hover:bg-rose-100 text-rose-600 py-4 rounded-2xl text-[11px] font-black border border-rose-100 transition-all">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M19.615 3.184c-3.604-.246-11.631-.245-15.23 0-3.897.266-4.356 2.62-4.385 8.816.029 6.185.484 8.549 4.385 8.816 3.6.245 11.626.246 15.23 0 3.897-.266 4.356-2.62 4.385-8.816-.029-6.185-.484-8.549-4.385-8.816zm-10.615 12.816v-8l8 3.993-8 4.007z"/></svg>
                            TUTORIAL VIDEO
                        </a>
                    </div>

                    <div class="space-y-3" x-data="{ showGizi: false, showMasak: false }">
                        <div class="w-full">
                            <button @click="showGizi = !showGizi" class="w-full flex items-center justify-between bg-green-50 text-green-700 px-6 py-4 rounded-2xl text-[10px] font-black hover:bg-green-100 transition-colors">
                                <span>📊 DETAIL GIZI</span>
                                <svg :class="showGizi ? 'rotate-180' : ''" class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"></path></svg>
                            </button>
                            <div x-show="showGizi" x-cloak x-transition class="mt-3 space-y-2 px-2 text-left">
                                @foreach($item['nutrisi'] as $nut)
                                    <div class="text-[10px] font-bold text-gray-500 uppercase border-b border-gray-50 pb-2">{{ $nut }}</div>
                                @endforeach
                            </div>
                        </div>

                        <div class="w-full">
                            <button @click="showMasak = !showMasak" class="w-full flex items-center justify-between bg-orange-50 text-orange-700 px-6 py-4 rounded-2xl text-[10px] font-black hover:bg-orange-100 transition-colors">
                                <span>🍳 CARA MASAK</span>
                                <svg :class="showMasak ? 'rotate-180' : ''" class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"></path></svg>
                            </button>
                            <div x-show="showMasak" x-cloak x-transition class="mt-4 bg-orange-50/50 rounded-[2.5rem] p-6 max-h-80 overflow-y-auto custom-scrollbar border border-orange-100 shadow-inner text-left">
                                <p class="text-[10px] font-black text-orange-800 uppercase mb-3 border-l-4 border-orange-400 pl-2">Bahan:</p>
                                <ul class="text-[11px] text-gray-600 space-y-2 mb-6">
                                    @foreach($item['bahan'] as $bahan) <li>• {{ $bahan }}</li> @endforeach
                                </ul>
                                <div class="mt-4 border-t border-orange-100 pt-4">
                                    <p class="font-black text-orange-800 mb-3 uppercase text-[10px]">Langkah Memasak:</p>
                                    @forelse($item['langkah'] as $l)
                                        <p class="mb-3 border-l-2 border-orange-200 pl-3 text-[11px] text-gray-600 leading-relaxed">{{ $loop->iteration }}. {{ $l }}</p>
                                    @empty
                                        <p class="text-gray-400 text-[11px]">Langkah tidak tersedia.</p>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endforeach
    </div>

    {{-- BUTTONS DENGAN VALIDASI LOGIN --}}
    <div class="mt-16 flex flex-col md:flex-row justify-between items-center gap-6 pb-10">
        <a href="{{ route('rekomendasi.index') }}" class="group flex items-center gap-3 bg-white border-2 border-gray-100 px-8 py-4 rounded-3xl text-[11px] font-black text-gray-400 hover:border-green-500 hover:text-green-600 transition-all">
            <svg class="w-5 h-5 transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            KEMBALI KE FORM
        </a>

        <form action="{{ route('rekomendasi.simpan') }}" method="POST" @submit.prevent="
            @auth
                $el.submit();
            @else
                showLoginModal = true;
            @endauth
        ">
            @csrf
            <input type="hidden" name="data_resep" value="{{ json_encode($resep) }}">
            <input type="hidden" name="data_analisis" value="{{ json_encode($analisis) }}">
            <input type="hidden" name="durasi" value="{{ $durasi }}">
            <button type="submit" class="bg-black text-white px-10 py-5 rounded-[2rem] text-[11px] font-black uppercase tracking-widest shadow-2xl hover:scale-105 transition-all">
                💾 SIMPAN RENCANA INI
            </button>
        </form>
    </div>

    {{-- MODAL LOGIN NOTIFICATION --}}
    <div x-show="showLoginModal" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-[99] flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm" x-cloak>
        
        <div @click.away="showLoginModal = false" 
             class="bg-white rounded-[3rem] p-10 max-w-sm w-full text-center shadow-2xl transform transition-all">
            <div class="w-20 h-20 bg-orange-50 text-orange-500 rounded-full flex items-center justify-center text-3xl mx-auto mb-6 shadow-inner text-center">🔒</div>
            <h3 class="text-xl font-black text-gray-800 uppercase leading-tight mb-2 text-center">Simpan Rencana?</h3>
            <p class="text-xs text-gray-400 font-medium leading-relaxed mb-8 text-center">
                Anda perlu masuk ke akun Sajian Sehat untuk menyimpan hasil analisis AI dan melihatnya kembali nanti.
            </p>
            <div class="space-y-3">
                <a href="{{ route('login') }}" class="block w-full bg-green-600 text-white py-4 rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-green-700 transition-all shadow-lg shadow-green-100">MASUK SEKARANG</a>
                <button @click="showLoginModal = false" class="block w-full bg-gray-50 text-gray-400 py-4 rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-gray-100 transition-all">NANTI SAJA</button>
            </div>
            <p class="mt-6 text-[10px] text-gray-300 font-bold uppercase text-center">Belum punya akun? <a href="{{ route('register') }}" class="text-green-500 underline">Daftar Disini</a></p>
        </div>
    </div>
</div>

<style>
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #fdba74; border-radius: 10px; }
    [x-cloak] { display: none !important; }
</style>
@endsection