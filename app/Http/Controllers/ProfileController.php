<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use App\Models\Riwayat; 

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Hitung total riwayat (Ganti 'Rekomendasi' dengan nama Model Anda)
        // Jika model belum dibuat, sementara kita set 0
        $totalRiwayat = 0; 
        if (class_exists('App\Models\Rekomendasi')) {
            $totalRiwayat = \App\Models\Riwayat::where('user_id', $user->id)->count();
        }

        return view('profile.index', compact('user', 'totalRiwayat'));
    }

    public function edit()
    {
        return view('profile.edit');
    }

    public function update(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'password' => 'nullable|min:8|confirmed',
        ]);

        $user->name = $request->name;

        if ($request->hasFile('avatar')) {
            // Logika hapus foto lama Anda sudah benar
            if ($user->avatar && !filter_var($user->avatar, FILTER_VALIDATE_URL)) {
                $oldPath = str_replace('/storage/', '', $user->avatar);
                Storage::disk('public')->delete($oldPath);
            }

            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = '/storage/' . $path;
        }

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('profile')->with('success', 'Profil berhasil diperbarui!');
    }
}