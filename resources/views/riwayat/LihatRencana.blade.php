@extends('layouts.app')

@section('content')
@php
    use Carbon\Carbon;
    Carbon::setLocale('id');
    
    // Asumsi: $tanggal adalah instance Carbon dari 'created_at' di database
    $startDate = $tanggal->copy(); 
    
    // Chunk resep menjadi 3 per hari
    $resepPerHari = array_chunk($resep, 3);
    $totalHari = count($resepPerHari);
    $totalMinggu = ceil($totalHari / 7);
@endphp

{{-- Tambahkan Alpine.js --}}
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

<div class="container mx-auto px-4 py-10 max-w-6xl" 
     x-data="{ 
        currentDay: 1, 
        currentWeek: 1,
        durasi: '{{ $durasi }}'
     }">
    
    {{-- HEADER RIWAYAT --}}
    <div class="mb-10 flex flex-col md:flex-row justify-between items-center gap-6">
        <div class="flex items-center gap-4">
            <div>
                <h2 class="text-2xl font-black text-gray-900 uppercase">Detail Rencana Tersimpan</h2>
                <p class="text-xs text-gray-500 font-bold uppercase tracking-widest">
                    Disimpan: {{ $tanggal->translatedFormat('d F Y H:i') }}
                </p>
            </div>
        </div>
        <div class="bg-black text-white px-6 py-3 rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-lg">
            Program {{ str_replace('_', ' ', $durasi) }}
        </div>
    </div>

    {{-- ANALISIS BOX (VERSI FINAL - SUPER CLEAN) --}}
    <div class="bg-green-500 rounded-[2rem] p-6 md:px-10 md:py-7 text-white shadow-sm mb-10 relative overflow-hidden border-b-4 border-green-600">
        <div class="relative z-10 flex flex-col md:flex-row md:items-center gap-6">
            <div class="flex-1">
                <span class="text-[8px] font-black uppercase tracking-[0.4em] opacity-75">Analisis Kesehatan</span>
                <h3 class="text-lg md:text-xl font-black uppercase leading-tight mt-0.5">
                    {{ $analisis['analisis_kesehatan'] ?? 'Analisis Nutrisi' }}
                </h3>
                <p class="text-[11px] opacity-90 leading-relaxed font-medium mt-1 max-w-4xl">
                    {{ $analisis['saran_nutrisi'] ?? 'Rencana makan ini telah dioptimalkan sesuai kondisi tubuh Anda.' }}
                </p>
            </div>
        </div>
    </div>

    {{-- NAVIGATION MINGGU (Hanya jika 1 Bulan) --}}
    @if($durasi == '1_bulan')
    <div class="mb-8 flex flex-wrap justify-center gap-2">
        @for($w = 1; $w <= $totalMinggu; $w++)
        <button @click="currentWeek = {{ $w }}; currentDay = (({{ $w }}-1) * 7) + 1"
            :class="currentWeek === {{ $w }} ? 'bg-black text-white shadow-lg scale-105' : 'bg-gray-100 text-gray-400'"
            class="px-6 py-3 rounded-2xl font-black uppercase text-[10px] transition-all">
            MINGGU {{ $w }}
        </button>
        @endfor
    </div>
    @endif

    {{-- NAVIGATION HARI --}}
    <div class="mb-12 flex overflow-x-auto pb-6 space-x-3 no-scrollbar justify-start md:justify-center">
        @foreach($resepPerHari as $index => $r)
            @php 
                $hariKe = $index + 1; 
                $tglSpesifik = $startDate->copy()->addDays($index);
            @endphp
            <button 
                x-show="durasi !== '1_bulan' || ({{ $hariKe }} >= (currentWeek - 1) * 7 + 1 && {{ $hariKe }} <= currentWeek * 7)"
                @click="currentDay = {{ $hariKe }}" 
                :class="currentDay === {{ $hariKe }} ? 'bg-green-600 text-white shadow-xl scale-110' : 'bg-white text-gray-400 border-gray-100'"
                class="flex-none w-28 py-4 rounded-[2.5rem] border-2 flex flex-col items-center transition-all duration-300">
                <span class="font-black uppercase text-[8px] opacity-70">{{ $tglSpesifik->translatedFormat('l') }}</span>
                <span class="text-2xl font-black leading-none my-1">{{ $tglSpesifik->translatedFormat('d') }}</span>
                <span class="font-bold uppercase text-[9px] tracking-tighter">{{ $tglSpesifik->translatedFormat('M Y') }}</span>
            </button>
        @endforeach
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
                
                {{-- Foto Resep --}}
                <div class="relative h-56 overflow-hidden flex-shrink-0">
                    <img src="{{ $item['image'] }}" class="w-full h-full object-cover group-hover:scale-105 transition-all duration-700">
                    <div class="absolute top-5 left-5">
                        <span class="bg-white/95 backdrop-blur px-5 py-2 rounded-full text-[10px] font-black text-gray-800 uppercase italic">
                            @if($idx == 0) 🌅 BREAKFAST @elseif($idx == 1) ☀️ LUNCH @else 🌙 DINNER @endif
                        </span>
                    </div>
                </div>

                {{-- Body Card --}}
                <div class="p-8">
                    <h4 class="font-black text-gray-800 text-sm mb-6 uppercase line-clamp-2 h-10">
                        {{ $item['title'] }}
                    </h4>

                    <div class="mb-6">
                        <a href="https://www.youtube.com/results?search_query=cara+masak+{{ urlencode($item['title']) }}" target="_blank" class="flex items-center justify-center gap-3 w-full bg-rose-50 hover:bg-rose-100 text-rose-600 py-4 rounded-2xl text-[11px] font-black border border-rose-100 transition-all">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M19.615 3.184c-3.604-.246-11.631-.245-15.23 0-3.897.266-4.356 2.62-4.385 8.816.029 6.185.484 8.549 4.385 8.816 3.6.245 11.626.246 15.23 0 3.897-.266 4.356-2.62 4.385-8.816-.029-6.185-.484-8.549-4.385-8.816zm-10.615 12.816v-8l8 3.993-8 4.007z"/></svg>
                            TUTORIAL VIDEO
                        </a>
                    </div>

                    {{-- Ubah x-data agar memiliki variabel masing-masing --}}
                    <div class="space-y-3" x-data="{ showGizi: false, showMasak: false }">
                        
                        {{-- BAGIAN GIZI --}}
                        <div class="w-full">
                            {{-- Tombol Gizi: Ubah @click dan :class --}}
                            <button @click="showGizi = !showGizi" class="w-full flex items-center justify-between bg-green-50 text-green-700 px-6 py-4 rounded-2xl text-[10px] font-black hover:bg-green-100">
                                <span>📊 DETAIL GIZI</span>
                                <svg :class="showGizi ? 'rotate-180' : ''" class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"></path></svg>
                            </button>
                            
                            {{-- Konten Gizi: Ubah x-show --}}
                            <div x-show="showGizi" x-cloak x-transition class="mt-3 space-y-2 px-2">
                                @foreach($item['nutrisi'] as $nut)
                                    <div class="flex justify-between text-[10px] font-bold text-gray-500 uppercase border-b border-gray-50 pb-2">
                                        {{ $nut }}
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- BAGIAN CARA MASAK --}}
                        <div class="w-full">
                            {{-- Tombol Masak: Ubah @click dan :class --}}
                            <button @click="showMasak = !showMasak" class="w-full flex items-center justify-between bg-orange-50 text-orange-700 px-6 py-4 rounded-2xl text-[10px] font-black hover:bg-orange-100">
                                <span>🍳 CARA MASAK</span>
                                <svg :class="showMasak ? 'rotate-180' : ''" class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"></path></svg>
                            </button>
                            
                            {{-- Konten Masak: Ubah x-show --}}
                            <div x-show="showMasak" x-cloak x-transition class="mt-4 bg-orange-50/50 rounded-[2.5rem] p-6 border border-orange-100 shadow-inner max-h-80 overflow-y-auto custom-scrollbar">
                                {{-- Isi konten bahan dan langkah tetap sama seperti sebelumnya --}}
                                <p class="text-[10px] font-black text-orange-800 uppercase mb-3 border-l-4 border-orange-400 pl-2">Bahan:</p>
                                <ul class="text-[11px] text-gray-600 space-y-2 mb-6">
                                    @foreach($item['bahan'] as $bahan) 
                                        <li>• {{ $bahan }}</li> 
                                    @endforeach
                                </ul>
                                <p class="text-[10px] font-black text-orange-800 uppercase mb-3 border-l-4 border-orange-400 pl-2">Langkah:</p>
                                <ol class="text-[11px] text-gray-600 space-y-4 font-medium">
                                    @foreach($item['langkah'] as $step) 
                                        <li class="flex gap-2">
                                            <span class="text-orange-600 font-bold">{{ $loop->iteration }}.</span>
                                            <span>{{ $step }}</span>
                                        </li> 
                                    @endforeach
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endforeach
    </div>

    {{-- TOMBOL KEMBALI --}}
    <div class="flex justify-center pb-20 mt-16">
        <a href="{{ route('riwayat.index') }}" 
           class="group flex items-center gap-4 bg-black text-white px-12 py-5 rounded-[2.5rem] text-[12px] font-black uppercase tracking-widest shadow-2xl hover:scale-105 transition-all">
            <svg class="w-5 h-5 transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M11 15l-3-3m0 0l3-3m-3 3h8M3 12a9 9 0 1118 0 9 9 0 01-18 0z"></path></svg>
            KEMBALI KE RIWAYAT
        </a>
    </div>
</div>

<style>
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #fdba74; border-radius: 10px; }
    [x-cloak] { display: none !important; }
</style>
@endsection