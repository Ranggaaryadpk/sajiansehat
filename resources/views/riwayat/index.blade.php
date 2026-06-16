@extends('layouts.app')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="container mx-auto px-6 py-6 h-[calc(100vh-80px)] flex flex-col">
    
    <div class="flex flex-col md:flex-row justify-between items-end mb-6 gap-6 shrink-0">
        <div>
            <h2 class="text-3xl md:text-4xl font-black text-gray-900 mb-1 tracking-tight">
                Riwayat <span class="text-green-600">Rekomendasi</span>
            </h2>
            <p class="text-gray-500 text-sm font-medium">Daftar rencana makan sehat yang telah kamu simpan.</p>
        </div>
        
        <div class="relative w-full md:w-80">
            <input type="text" id="searchInput" placeholder="Cari riwayat..." 
                class="w-full bg-white border border-gray-200 rounded-2xl px-5 py-3 pl-11 text-sm font-medium focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all shadow-sm">
            <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
        </div>

        <div class="bg-green-50 px-4 py-2 rounded-xl text-green-700 text-xs font-bold border border-green-100 shadow-sm shrink-0">
            Total: {{ $dataRiwayat->count() }}
        </div>
    </div>

    @guest
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: 'AKSES TERBATAS!',
                text: 'Silakan login terlebih dahulu untuk melihat riwayat.',
                icon: 'info',
                showCancelButton: true,
                confirmButtonText: 'LOGIN',
                cancelButtonText: 'KEMBALI',
                reverseButtons: true,
                customClass: {
                    popup: 'rounded-[2.5rem] p-10 shadow-2xl',
                    confirmButton: 'bg-green-600 hover:bg-green-700 text-white rounded-2xl px-8 py-4 font-black text-xs tracking-widest',
                    cancelButton: 'bg-gray-100 hover:bg-gray-200 text-gray-500 rounded-2xl px-8 py-4 font-black text-xs tracking-widest',
                }
            }).then((result) => {
                if (result.isConfirmed) window.location.href = "{{ route('login') }}";
                else window.location.href = "{{ route('rekomendasi.index') }}";
            });
        });
    </script>
    @endguest

    <div class="bg-white rounded-[2.5rem] shadow-xl border border-gray-100 overflow-hidden flex flex-col shrink">
        <div class="max-h-[480px] overflow-y-auto custom-scrollbar">
            <table class="w-full text-left border-collapse" id="riwayatTable">
                <thead class="bg-gray-50/80 border-b border-gray-100 sticky top-0 z-10 backdrop-blur-md">
                    <tr>
                        <th class="px-8 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Tanggal Simpan</th>
                        <th class="px-8 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Program</th>
                        <th class="px-8 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Catatan Analisis</th>
                        <th class="px-8 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($dataRiwayat as $item)
                    <tr class="hover:bg-green-50/30 transition-all group riwayat-row">
                        <td class="px-8 py-5 text-sm font-bold text-gray-600 whitespace-nowrap">
                            {{ $item->created_at->translatedFormat('d F Y') }}
                        </td>
                        <td class="px-8 py-5">
                            <span class="px-3 py-1 rounded-full text-[9px] font-black uppercase bg-blue-50 text-blue-600 border border-blue-100">
                                {{ str_replace('_', ' ', $item->durasi) }}
                            </span>
                        </td>
                        <td class="px-8 py-5 text-sm text-gray-500 font-medium italic">
                            "{{ Str::limit($item->analisis['analisis_kesehatan'] ?? 'Analisis Gizi', 55) }}"
                        </td>
                        <td class="px-8 py-5 text-right flex justify-end gap-2 items-center">
                            <a href="{{ route('riwayat.show', $item->id) }}" 
                                class="bg-white border border-gray-200 px-4 py-2 rounded-xl text-[9px] font-black hover:bg-black hover:text-white transition shadow-sm uppercase tracking-wider">
                                LIHAT
                            </a>
                            
                            <form action="{{ route('riwayat.destroy', $item->id) }}" method="POST" id="delete-form-{{ $item->id }}">
                                @csrf @method('DELETE')
                                <button type="button" onclick="confirmDelete('{{ $item->id }}')"
                                    class="bg-rose-50 border border-rose-100 p-2 rounded-xl text-rose-600 hover:bg-rose-600 hover:text-white transition shadow-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr id="emptyRow">
                        <td colspan="4" class="px-8 py-24 text-center">
                            <p class="text-gray-400 font-bold italic text-sm">Belum ada rekomendasi yang disimpan.</p>
                            <a href="{{ route('rekomendasi.index') }}" class="mt-2 text-green-600 text-[10px] font-black uppercase underline tracking-widest block">Buat Sekarang</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    // FUNGSI SEARCH REAL-TIME
    document.getElementById('searchInput').addEventListener('keyup', function() {
        let filter = this.value.toLowerCase();
        let rows = document.querySelectorAll('.riwayat-row');
        let hasVisibleRows = false;

        rows.forEach(row => {
            let text = row.innerText.toLowerCase();
            if (text.includes(filter)) {
                row.style.display = "";
                hasVisibleRows = true;
            } else {
                row.style.display = "none";
            }
        });

        let existingNoResult = document.getElementById('noResults');
        if (!hasVisibleRows && rows.length > 0) {
            if (!existingNoResult) {
                let noResultTr = document.createElement('tr');
                noResultTr.id = 'noResults';
                noResultTr.innerHTML = `<td colspan="4" class="px-8 py-10 text-center text-gray-400 text-sm italic">Hasil tidak ditemukan.</td>`;
                document.querySelector('tbody').appendChild(noResultTr);
            }
        } else if (existingNoResult) {
            existingNoResult.remove();
        }
    });

    function confirmDelete(id) {
        Swal.fire({
            title: 'Hapus Data?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e11d48',
            confirmButtonText: 'YA, HAPUS',
            cancelButtonText: 'BATAL',
            reverseButtons: true,
            customClass: { popup: 'rounded-[2rem] p-8 shadow-2xl', confirmButton: 'rounded-xl font-bold', cancelButton: 'rounded-xl font-bold' }
        }).then((result) => {
            if (result.isConfirmed) document.getElementById('delete-form-' + id).submit();
        });
    }
</script>

<style>
    /* CUSTOM SCROLLBAR */
    .custom-scrollbar::-webkit-scrollbar { width: 5px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: #f9fafb; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #e5e7eb; border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #10b981; }
    
    /* Mencegah scrollbar browser */
    html, body { overflow: hidden; }
</style>
@endsection