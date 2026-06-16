# Sajian Sehat

Sajian Sehat adalah aplikasi berbasis web yang dirancang untuk memberikan rekomendasi makanan sehat berdasarkan profil dan inputan data dari pengguna. Aplikasi ini dibangun menggunakan **Laravel** dan menggunakan **MySQL** sebagai sistem basis datanya.

## 🍽️ Kategori Makanan
Aplikasi ini menyediakan rekomendasi untuk 3 jenis makanan:
1. **Makanan Indonesia**  
   Rekomendasi masakan Nusantara diambil melalui integrasi dengan **Gemini AI API** untuk mengatasi keterbatasan pada penyedia API resep lokal.
2. **Makanan Internasional**  
   Rekomendasi masakan mancanegara diambil menggunakan data dari **Spoonacular API**.
3. **Makanan Mix (Campuran)**  
   Menyajikan perpaduan rekomendasi antara hidangan lokal dan internasional.

## 💻 Teknologi yang Digunakan
* **Backend:** Laravel Framework
* **Database:** MySQL
* **Third-Party API:**
  * [Spoonacular API](https://spoonacular.com/food-api)
  * [Google Gemini AI API](https://ai.google.dev/)

## 🚀 Cara Instalasi

1. Clone repository ini:
   ```bash
   git clone https://github.com/Ranggaaryadpk/sajiansehat.git
   ```
2. Masuk ke direktori project:
   ```bash
   cd sajiansehat
   ```
3. Install dependensi composer:
   ```bash
   composer install
   ```
4. Copy file `.env.example` menjadi `.env` dan sesuaikan konfigurasi database beserta API Key (Spoonacular & Gemini AI):
   ```bash
   cp .env.example .env
   ```
5. Generate application key:
   ```bash
   php artisan key:generate
   ```
6. Jalankan migrasi database:
   ```bash
   php artisan migrate
   ```
7. Jalankan server lokal:
   ```bash
   php artisan serve
   ```
Aplikasi dapat diakses melalui `http://localhost:8000`.
