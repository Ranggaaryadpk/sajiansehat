# Sajian Sehat

Sajian Sehat adalah aplikasi berbasis web yang dirancang untuk memberikan rekomendasi makanan sehat berdasarkan profil dan inputan data dari pengguna. Aplikasi ini dibangun menggunakan **Laravel** dan menggunakan **MySQL** sebagai sistem basis datanya.

## Kategori Makanan
Aplikasi ini menyediakan rekomendasi untuk 3 jenis makanan:
1. **Makanan Indonesia**  
   Rekomendasi masakan Nusantara diambil melalui integrasi dengan **Gemini AI API** untuk mengatasi keterbatasan pada penyedia API resep lokal.
2. **Makanan Internasional**  
   Rekomendasi masakan mancanegara diambil menggunakan data dari **Spoonacular API**.
3. **Makanan Mix (Campuran)**  
   Menyajikan perpaduan rekomendasi antara hidangan lokal dan internasional.

## Teknologi yang Digunakan
* **Backend:** Laravel Framework
* **Database:** MySQL
* **Third-Party API:**
  * [Spoonacular API](https://spoonacular.com/food-api)
  * [Google Gemini AI API](https://ai.google.dev/)
