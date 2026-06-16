@extends('layouts.app')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="container mx-auto px-6 max-w-5xl py-10">
    <div class="mb-8 text-center">
        <h2 class="text-2xl font-black text-gray-800 tracking-tight">Analisis Nutrisi AI</h2>
        <p class="text-sm text-gray-500 mt-1">Saran menu sehat yang dipersonalisasi untuk Anda.</p>
    </div>

    <div class="mb-6 flex justify-center">
        <select id="metode_pilihan" onchange="gantiMetode()" class="bg-gray-50 border border-gray-200 rounded-xl px-4 py-2 text-xs font-bold text-gray-600 shadow-sm focus:ring-2 focus:ring-green-500 cursor-pointer outline-none transition-all">
            <option value="deskripsi">✍️ Mode Deskripsi (Simpel)</option>
            <option value="lengkap">📋 Mode Data Lengkap (Terstruktur)</option>
        </select>
    </div>

    <form id="formAnalisis" action="{{ route('rekomendasi.proses') }}" method="POST">
        @csrf
        
        <div id="section_deskripsi" class="animate-fade-in">
            <div class="bg-white border border-gray-100 rounded-[2.5rem] p-8 shadow-sm">
                <label class="block text-sm font-bold text-gray-700 mb-3 ml-1">Apa keluhan atau kondisi tubuhmu?</label>
                <textarea id="input_deskripsi" name="kondisi_deskripsi_saja" rows="6" 
                    class="w-full border-2 border-gray-50 rounded-2xl p-5 text-sm focus:border-green-500 focus:ring-4 focus:ring-green-50 outline-none transition-all bg-gray-50/30 text-gray-700" 
                    placeholder="Contoh: Saya sedang flu dan maag, butuh makanan hangat..."></textarea>
            </div>
        </div>

        <div id="section_lengkap" class="hidden animate-fade-in">
            <div class="bg-white border border-gray-100 rounded-[2.5rem] p-8 shadow-sm">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-6">
                    <div class="space-y-4">
                        <h4 class="text-[10px] font-black text-green-600 uppercase tracking-widest flex items-center">
                            <span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span> Profil Fisik (Wajib)
                        </h4>
                        <div class="grid grid-cols-2 gap-3">
                            <select name="gender" class="input-terstruktur w-full border border-gray-100 rounded-xl p-2.5 bg-gray-50/50 text-xs focus:border-green-400 outline-none">
                                <option value="pria">Pria</option>
                                <option value="wanita">Wanita</option>
                            </select>
                            <input type="number" id="input_umur" name="umur" class="input-terstruktur w-full border border-gray-100 rounded-xl p-2.5 bg-gray-50/50 text-xs focus:border-green-400 outline-none" placeholder="Umur (Thn)">
                            <input type="number" id="input_berat" name="berat" class="input-terstruktur w-full border border-gray-100 rounded-xl p-2.5 bg-gray-50/50 text-xs focus:border-green-400 outline-none" placeholder="Berat (kg)">
                            <input type="number" id="input_tinggi" name="tinggi" class="input-terstruktur w-full border border-gray-100 rounded-xl p-2.5 bg-gray-50/50 text-xs focus:border-green-400 outline-none" placeholder="Tinggi (cm)">
                        </div>
                    </div>

                    <div class="space-y-4">
                        <h4 class="text-[10px] font-black text-orange-600 uppercase tracking-widest flex items-center">
                            <span class="w-2 h-2 bg-orange-500 rounded-full mr-2"></span> Diet & Alergi (Opsional)
                        </h4>
                        <select name="preferensi_diet" class="w-full border border-gray-100 rounded-xl p-2.5 bg-gray-50/50 text-xs focus:border-orange-400 outline-none">
                            <option value="normal">Normal</option>
                            <option value="vegetarian">Vegetarian</option>
                            <option value="vegan">Vegan</option>
                        </select>
                        <input type="text" name="alergi" class="w-full border border-gray-100 rounded-xl p-2.5 bg-gray-50/50 text-xs focus:border-orange-400 outline-none" placeholder="Alergi (Seafood, dll)">
                    </div>
                </div>
                <textarea name="kondisi_lengkap" rows="3" class="w-full border border-gray-100 rounded-xl p-4 bg-gray-50/50 text-xs outline-none focus:border-green-400" placeholder="Catatan kondisi lainnya..."></textarea>
            </div>
        </div>

        <div class="mt-6 grid grid-cols-1 lg:grid-cols-4 gap-4">
            <div class="lg:col-span-1 bg-emerald-50 border border-emerald-100 rounded-[2rem] p-5 flex flex-col items-center justify-center text-center shadow-sm">
                <div class="bg-white p-3 rounded-full mb-3 shadow-sm text-2xl">☪️</div>
                <h5 class="text-[11px] font-black text-emerald-700 uppercase tracking-widest">Jaminan Halal</h5>
            </div>

            <div class="lg:col-span-3 bg-white border border-gray-100 rounded-[2rem] p-5 shadow-sm flex flex-col md:flex-row gap-4 items-center">
                <div class="w-full md:w-1/3">
                    <label class="text-[9px] font-black text-gray-400 ml-1 uppercase mb-1 block">Rencana</label>
                    <select name="durasi" class="w-full border-none rounded-xl px-3 py-2 text-xs font-bold bg-gray-50 text-gray-700 outline-none focus:ring-2 focus:ring-green-100 cursor-pointer">
                        <option value="1_hari">1 Hari</option>
                        <option value="1_minggu">1 Minggu</option>
                        <option value="1_bulan">1 Bulan</option>
                    </select>
                </div>

                <div class="w-full md:w-1/3">
                    <label class="text-[9px] font-black text-gray-400 ml-1 uppercase mb-1 block">Jenis Resep</label>
                    <select name="tipe_masakan" class="w-full border-none rounded-xl px-3 py-2 text-xs font-bold bg-gray-50 text-gray-700 outline-none focus:ring-2 focus:ring-green-100 cursor-pointer">
                        <option value="indonesia">🇮🇩 Indonesia</option>
                        <option value="internasional">🌎 Internasional</option>
                        <option value="mix">✨ Mix</option>
                    </select>
                </div>

                <div class="w-full md:w-1/3 flex items-end pt-4 md:pt-0">
                    <button type="submit" class="w-full bg-green-600 text-white py-3 rounded-xl font-bold text-xs hover:bg-green-700 transition-all flex items-center justify-center shadow-md shadow-green-100 active:scale-95">
                        <span>Dapatkan Hasil</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    // Konfigurasi SweetAlert Modern (Glassmorphism Style)
    const Toast = Swal.mixin({
        toast: false,
        position: 'center',
        showConfirmButton: true,
        confirmButtonColor: '#059669',
        background: '#ffffff',
        customClass: {
            popup: 'rounded-[2rem] shadow-2xl border border-gray-100',
            title: 'text-lg font-black text-gray-800',
            htmlContainer: 'text-xs text-gray-500 font-medium'
        }
    });

    function gantiMetode() {
        const metode = document.getElementById('metode_pilihan').value;
        const deskripsi = document.getElementById('section_deskripsi');
        const lengkap = document.getElementById('section_lengkap');

        if (metode === 'deskripsi') {
            deskripsi.classList.remove('hidden');
            lengkap.classList.add('hidden');
        } else {
            deskripsi.classList.add('hidden');
            lengkap.classList.remove('hidden');
        }
    }

    document.getElementById('formAnalisis').addEventListener('submit', function(e) {
        const metode = document.getElementById('metode_pilihan').value;
        
        if (metode === 'deskripsi') {
            const input = document.getElementById('input_deskripsi').value.trim();
            if (input === "") {
                e.preventDefault();
                Toast.fire({
                    icon: 'warning',
                    title: 'Deskripsi Kosong',
                    text: 'Mohon ceritakan sedikit tentang kondisi tubuh Anda agar AI bisa menganalisis.'
                });
                return;
            }
        } else {
            // Ambil Value
            const umur = parseInt(document.getElementById('input_umur').value);
            const berat = parseInt(document.getElementById('input_berat').value);
            const tinggi = parseInt(document.getElementById('input_tinggi').value);

            // 1. Validasi Empty
            if (!umur || !berat || !tinggi) {
                e.preventDefault();
                Toast.fire({
                    icon: 'error',
                    title: 'Data Belum Lengkap',
                    text: 'Umur, Berat, dan Tinggi badan wajib diisi untuk analisis akurat.'
                });
                return;
            }

            // 2. Validasi Range (Logika Medis Dasar)
            let errorMsg = "";
            if (umur < 1 || umur > 150) errorMsg = "Usia harus antara 5 hingga 150 tahun.";
            else if (berat < 20 || berat > 250) errorMsg = "Berat badan harus antara 20kg hingga 250kg.";
            else if (tinggi < 40 || tinggi > 250) errorMsg = "Tinggi badan harus antara 50cm hingga 250cm.";

            if (errorMsg !== "") {
                e.preventDefault();
                Toast.fire({
                    icon: 'info',
                    title: 'Input Tidak Wajar',
                    text: errorMsg
                });
                return;
            }
        }

        // Tampilkan Loading Modern
        Swal.fire({
            title: 'Menganalisis Nutrisi...',
            html: '<div class="mt-4 flex flex-col items-center"><div class="w-12 h-12 border-4 border-green-100 border-t-green-600 rounded-full animate-spin"></div><p class="mt-4 text-xs font-medium text-gray-400">AI sedang merancang menu terbaik untuk Anda</p></div>',
            allowOutsideClick: false,
            showConfirmButton: false,
            background: '#ffffff',
            customClass: {
                popup: 'rounded-[3rem] p-10 shadow-2xl',
                title: 'text-xl font-black text-gray-800 uppercase'
            }
        });
    });

    @if(session('error'))
        Toast.fire({ icon: 'error', title: 'Gagal!', text: "{{ session('error') }}" });
    @endif
</script>

<style>
    .animate-fade-in { animation: fadeIn 0.4s cubic-bezier(0.4, 0, 0.2, 1); }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    
    /* Menghapus spinner default input number agar tetap rapi */
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
</style>
@endsection