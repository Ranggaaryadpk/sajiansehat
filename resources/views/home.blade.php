@extends('layouts.app')

@section('content')
<div class="container mx-auto px-6 h-[calc(100vh-80px)] flex flex-col justify-center space-y-6 overflow-hidden">
    
    <div class="bg-white rounded-[2.5rem] p-8 md:p-10 shadow-sm border border-gray-100 flex flex-col md:flex-row items-center justify-between shrink-0">
        <div class="md:w-3/5 text-center md:text-left">
            <h1 class="text-3xl md:text-5xl font-extrabold text-gray-900 leading-tight mb-4">
                Makan Sehat, <br><span class="text-green-600">Sesuai Kondisi Tubuhmu.</span>
            </h1>
            <p class="text-gray-600 text-base md:text-lg mb-8 max-w-xl">
                Dapatkan rekomendasi resep Indonesia dan Internasional yang dimodifikasi khusus oleh AI Gemini berdasarkan kondisi kesehatanmu saat ini secara cerdas.
            </p>
            <div class="flex justify-center md:justify-start">
                <a href="{{ route('rekomendasi.index') }}" class="bg-green-600 text-white px-10 py-4 rounded-2xl font-bold shadow-lg shadow-green-200 hover:bg-green-700 hover:-translate-y-1 transition-all duration-300">
                    Mulai Rekomendasi
                </a>
            </div>
        </div>

        <div class="md:w-1/3 flex justify-center md:justify-end shrink-0">
            <div class="bg-green-50 rounded-full p-6 w-44 h-44 md:w-60 md:h-60 flex items-center justify-center">
                <img src="https://cdn-icons-png.flaticon.com/512/2276/2276931.png" alt="Healthy Food AI" class="w-28 md:w-44">
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 shrink">
        <div class="bg-white p-6 rounded-[2rem] border border-gray-100 shadow-sm hover:shadow-md transition group">
            <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center mb-4">
                <span class="text-xl font-bold text-red-600">ID</span>
            </div>
            <h3 class="text-lg font-bold mb-2">Resep Indonesia</h3>
            <p class="text-gray-500 text-sm leading-snug">
                Eksplorasi ribuan resep Nusantara yang dicari AI untuk nutrisi lokal yang sehat.
            </p>
        </div>

        <div class="bg-white p-6 rounded-[2rem] border border-gray-100 shadow-sm hover:shadow-md transition group">
            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center mb-4">
                <span class="text-xl">🌎</span>
            </div>
            <h3 class="text-lg font-bold mb-2">Resep Internasional</h3>
            <p class="text-gray-500 text-sm leading-snug">
                Akses database global Spoonacular dengan penyesuaian bahan pengganti yang sehat.
            </p>
        </div>

        <div class="bg-white p-6 rounded-[2rem] border border-gray-100 shadow-sm hover:shadow-md transition group">
            <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center mb-4">
                <span class="text-xl">🤖</span>
            </div>
            <h3 class="text-lg font-bold mb-2">AI Personalization</h3>
            <p class="text-gray-500 text-sm leading-snug">
                Teknologi analisis instan yang memahami kondisi tubuhmu untuk menyaring bahan makanan.
            </p>
        </div>
    </div>
</div>
@endsection