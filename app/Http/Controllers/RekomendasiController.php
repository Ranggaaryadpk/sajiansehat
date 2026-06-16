<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\RekomendasiSimpan;

class RekomendasiController extends Controller
{
    public function index() 
    { 
        return view('rekomendasi.index'); 
    }

    public function proses(Request $request)
    {
        set_time_limit(600);
        ini_set('memory_limit', '512M');

        $request->validate([
            'durasi' => 'required', 
            'tipe_masakan' => 'required'
        ]);

        try {
            $durasi = $request->input('durasi');
            $tipe = $request->input('tipe_masakan');
            
            $jumlahResep = match($durasi) {
                '1_minggu' => 21,
                '1_bulan'  => 90,
                default    => 3,
            };

            // 1. Analisis Profil Kesehatan & Keyword
            $analisisAI = $this->panggilGeminiAnalisis($request->all());

            // 2. Logika Pengambilan Resep
            if ($tipe == 'indonesia') {
                $resepFinal = $this->generateResepIndonesia($analisisAI, $jumlahResep);
            } 
            elseif ($tipe == 'internasional') {
                $resepSpoonacular = $this->panggilSpoonacular($analisisAI, $jumlahResep);
                $resepFinal = $this->translateBulkWithGemini($resepSpoonacular);
            } 
            elseif ($tipe == 'mix') {
                // Logika Mix: Ambil separuh Indonesia, separuh Internasional
                $jumlahIndo = ceil($jumlahResep / 2); // Misal 21 resep, Indo dapat 11
                $jumlahInter = floor($jumlahResep / 2); // Inter dapat 10

                $resepIndo = $this->generateResepIndonesia($analisisAI, $jumlahIndo);
                
                $rawSpoonacular = $this->panggilSpoonacular($analisisAI, $jumlahInter);
                $resepInter = $this->translateBulkWithGemini($rawSpoonacular);

                // Gabungkan dengan pola selang-seling (Interleaving)
                $resepFinal = [];
                $max = max(count($resepIndo), count($resepInter));
                
                for ($i = 0; $i < $max; $i++) {
                    if (isset($resepIndo[$i])) $resepFinal[] = $resepIndo[$i];
                    if (isset($resepInter[$i])) $resepFinal[] = $resepInter[$i];
                }
                
                // Pastikan jumlahnya pas (karena kadang AI memberikan jumlah berbeda)
                $resepFinal = array_slice($resepFinal, 0, $jumlahResep);
            }

            if (empty($resepFinal)) {
                return back()->with('error', 'Gagal memuat resep. Coba deskripsi lain.');
            }

            return view('rekomendasi.hasil', [
                'analisis' => $analisisAI,
                'resep'    => $resepFinal,
                'durasi'   => $durasi,
                'tipe'     => $tipe
            ]);

        } catch (\Exception $e) {
            Log::error("Error Utama: " . $e->getMessage());
            return back()->with('error', 'Terjadi gangguan sistem.');
        }
    }

    private function generateResepIndonesia($analisis, $jumlah)
    {
        $prompt = "Hasilkan $jumlah resep Indonesia SEHAT & HALAL sesuai kondisi: " . ($analisis['analisis_kesehatan'] ?? 'Umum') . ".
        Format JSON ARRAY. Kunci: id, title, nutrisi, bahan, langkah.
        PENTING: 'nutrisi' WAJIB array format: [\"Kalori: 350 kcal\", \"Protein: 15g\", \"Lemak: 18g\", \"Karbohidrat: 35g\"]";
        
        $results = $this->kontakGemini($prompt, true);
        $final = [];

        if (is_array($results)) {
            foreach ($results as $r) { 
                $final[] = [
                    'id'      => $r['id'] ?? uniqid(),
                    'title'   => $r['title'] ?? ($r['judul'] ?? 'Menu Nusantara'),
                    'nutrisi' => is_array($r['nutrisi'] ?? null) ? $r['nutrisi'] : [],
                    'bahan'   => is_array($r['bahan'] ?? null) ? $r['bahan'] : [],
                    'langkah' => is_array($r['langkah'] ?? null) ? $r['langkah'] : [],
                    'image'   => "https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=800",
                    'sumber'  => 'Nusantara (Halal)'
                ];
            }
        }
        return $final;
    }

    private function translateBulkWithGemini($resepList)
    {
        if (empty($resepList)) return [];

        $dataToTranslate = collect($resepList)->map(function($item) {
            return [
                'id'          => $item['id'],
                'title_orig'  => $item['title'], 
                'image_orig'  => $item['image'],
                'ingredients' => collect($item['extendedIngredients'] ?? [])->take(8)->map(fn($i) => $i['original'])->all(),
                'steps'       => collect($item['analyzedInstructions'][0]['steps'] ?? [])->map(fn($s) => $s['step'])->all(),
                'nutrition'   => collect($item['nutrition']['nutrients'] ?? [])
                                    ->whereIn('name', ['Calories', 'Protein', 'Fat', 'Carbohydrates'])
                                    ->map(fn($n) => $n['name'] . ": " . $n['amount'] . $n['unit'])->all(),
            ];
        })->all();

        $batches = array_chunk($dataToTranslate, 45);
        $finalResults = [];

        foreach ($batches as $batch) {
            $prompt = "Terjemahkan data JSON resep internasional ke Bahasa Indonesia:
            1. 'ingredients' dan 'steps' WAJIB Bahasa Indonesia.
            2. 'nutrition' WAJIB diterjemahkan (contoh: 'Calories' jadi 'Kalori').
            3. 'title_orig' dan 'image_orig' JANGAN DIUBAH.
            4. Jika 'steps' kosong, buatkan langkah masak logis berdasarkan bahan.
            5. PASTIKAN HALAL (Substitusi bahan babi/alkohol).
            Kembalikan JSON ARRAY: " . json_encode($batch);

            $translated = $this->kontakGemini($prompt, true);
            if (is_array($translated)) {
                foreach ($translated as $res) {
                    $finalResults[] = [
                        'id'      => $res['id'] ?? uniqid(),
                        'title'   => $res['title_orig'], 
                        'image'   => $res['image_orig'], 
                        'nutrisi' => is_array($res['nutrition'] ?? null) ? $res['nutrition'] : [],
                        'bahan'   => is_array($res['ingredients'] ?? null) ? $res['ingredients'] : [],
                        'langkah' => is_array($res['steps'] ?? null) ? $res['steps'] : [],
                        'sumber'  => 'Spoonacular'
                    ];
                }
            }
        }
        return $finalResults;
    }

    private function panggilSpoonacular($analisis, $limit)
    {
        try {
            // Kita gunakan keyword spesifik hasil ekstraksi AI
            $searchQuery = $analisis['keyword'] ?? 'healthy';

            return Http::timeout(60)->get("https://api.spoonacular.com/recipes/complexSearch", [
                'apiKey'               => env('SPOONACULAR_API_KEY'),
                'query'                => $searchQuery, 
                'number'               => $limit, 
                'diet'                 => 'whole30', // Memastikan hasil sehat secara otomatis
                'excludeIngredients'   => 'pork, lard, alcohol, wine',
                'addRecipeInformation' => 'true', 
                'fillIngredients'      => 'true', 
                'addRecipeNutrition'   => 'true'
            ])->json()['results'] ?? [];
        } catch (\Exception $e) { return []; }
    }

    /**
     * PERBAIKAN TOTAL PADA PROMPT KEYWORD:
     * Gemini diperintahkan mencari kata kunci "MAKANAN" bukan kata kunci "PENYAKIT".
     */
    private function panggilGeminiAnalisis($data)
    {
        $inputUser = $data['kondisi_deskripsi_saja'] ?? 'Normal';

        $prompt = "Analisis deskripsi ini: '$inputUser'. 
        Tugas Anda adalah menghasilkan JSON untuk pencarian resep:
        1. 'analisis_kesehatan': Ringkasan kondisi medis user.
        2. 'saran_nutrisi': Apa yang harus dimakan/dihindari.
        3. 'keyword': WAJIB berupa 1-2 kata benda makanan dalam Bahasa Inggris yang AMAN untuk kondisi tersebut.
           - Jika Diabetes: 'Oatmeal' atau 'Salad'.
           - Jika Hipertensi: 'Fish' atau 'Vegetables'.
           - Jika Diet/Sehat: 'Chicken Breast' atau 'Quinoa'.
           - Jika bingung: 'Healthy' atau 'Clean eating'.
        
        Wajib JSON:
        {
            \"analisis_kesehatan\": \"...\",
            \"saran_nutrisi\": \"...\",
            \"keyword\": \"English Food Keyword\",
            \"minProtein\": 10,
            \"maxCalories\": 800
        }";

        $res = $this->kontakGemini($prompt, true);

        if ($res) {
            // Normalisasi
            if (isset($res['analisis_kesehatan']) && is_array($res['analisis_kesehatan'])) {
                $res['analisis_kesehatan'] = implode(', ', $res['analisis_kesehatan']);
            }
            if (isset($res['saran_nutrisi']) && is_array($res['saran_nutrisi'])) {
                $res['saran_nutrisi'] = implode('. ', $res['saran_nutrisi']);
            }
            // Tambahkan fallback keyword jika kosong
            if (empty($res['keyword'])) { $res['keyword'] = 'healthy food'; }
            
            return $res;
        }

        return [
            'analisis_kesehatan' => 'Normal', 
            'saran_nutrisi' => 'Seimbang', 
            'keyword' => 'clean eating'
        ];
    }

    public function simpan(Request $request)
    {
        if (!Auth::check()) return redirect()->route('login');
        try {
            RekomendasiSimpan::create([
                'user_id'  => Auth::id(),
                'durasi'   => $request->durasi,
                'analisis' => json_decode($request->data_analisis, true),
                'resep'    => json_decode($request->data_resep, true),
            ]);
            return redirect()->route('riwayat.index')->with('success', 'Tersimpan!');
        } catch (\Exception $e) { return back()->with('error', 'Gagal.'); }
    }

    public function destroy($id)
    {
        RekomendasiSimpan::where('id', $id)->where('user_id', Auth::id())->delete();
        return redirect()->route('riwayat.index')->with('success', 'Dihapus.');
    }

    private function kontakGemini($prompt, $isJson = false)
    {
        try {
            $payload = ['contents' => [['parts' => [['text' => $prompt]]]]];
            if ($isJson) { $payload['generationConfig'] = ['response_mime_type' => 'application/json']; }

            $response = Http::timeout(240)->post(
                "https://generativelanguage.googleapis.com/v1beta/models/gemini-3-flash-preview:generateContent?key=" . env('GEMINI_API_KEY'), 
                $payload
            );

            if (!$response->successful()) return null;
            $text = $response->json()['candidates'][0]['content']['parts'][0]['text'] ?? '';
            $clean = preg_replace('/^```(?:json)?\n?|```$/', '', trim($text));
            return $isJson ? json_decode($clean, true) : $text;
        } catch (\Exception $e) { return null; }
    }
}