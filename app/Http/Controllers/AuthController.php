<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    // Menampilkan Halaman Login
    public function halamanLogin()
    {
        return view('auth.login');
    }

    // Menampilkan Halaman Register
    public function halamanRegister()
    {
        return view('auth.register');
    }

    // Proses Register Manual
    public function prosesRegister(Request $request)
    {
        try {
            $data = $request->validate([
                'name' => 'required|string|max:255',
                'asal_negara' => 'required|string|max:100',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:8|confirmed',
            ], [
                // Pesan Kustom Bahasa Indonesia
                'email.unique' => 'Email ini sudah terdaftar. Gunakan email lain.',
                'password.confirmed' => 'Konfirmasi password tidak cocok.',
                'password.min' => 'Password minimal harus 8 karakter.',
            ]);

            $user = User::create([
                'name' => $data['name'],
                'asal_negara' => $data['asal_negara'],
                'email' => $data['email'],
                'password' => bcrypt($data['password']),
            ]);

            Auth::login($user);
            
            // Mengirim session success ke app.blade.php
            return redirect()->route('home')->with('success', 'Selamat! Akun Anda berhasil dibuat.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Mengirim session error ke app.blade.php jika validasi gagal
            return back()->withErrors($e->validator)->withInput()->with('error', 'Pendaftaran gagal. Mohon periksa kembali formulir Anda.');
        }
    }

    // Proses Login Manual
    public function prosesLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // 1. Cek apakah email ada di database
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Email tidak terdaftar dalam sistem kami.'])->withInput();
        }

        // 2. Cek apakah password benar
        if (!Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            return back()->withErrors(['password' => 'Password yang Anda masukkan salah.'])->withInput();
        }

        $request->session()->regenerate();
        return redirect()->intended('/')->with('success', 'Selamat datang kembali!');
    }

    // Google Login Redirect
    public function googleRedirect()
    {
        return Socialite::driver('google')->redirect();
    }

    // Google Login Callback
    public function googleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            $user = User::updateOrCreate([
                'email' => $googleUser->email,
            ], [
                'name' => $googleUser->name,
                'google_id' => $googleUser->id,
                'asal_negara' => 'Indonesia', // Default jika tidak ada
                'password' => null, // Login via Google tidak butuh password manual
            ]);

            Auth::login($user);
            return redirect()->route('home');
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Gagal login dengan Google.');
        }
    }

    // Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    // Menampilkan halaman minta link
    public function halamanLupaPassword() {
        return view('auth.forgot-password');
    }

    // Proses kirim email
    public function kirimLinkReset(Request $request) {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink($request->only('email'));

        return $status === Password::RESET_LINK_SENT
            ? back()->with('success', 'Link reset password telah dikirim ke email Anda!')
            : back()->withErrors(['email' => __($status)])->with('error', 'Gagal mengirim email.');
    }

    // Menampilkan halaman form password baru
    public function halamanResetPassword($token) {
        return view('auth.reset-password', ['token' => $token]);
    }

    // Proses update password baru
    public function prosesResetPassword(Request $request) {
        // 1. Validasi Input
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        // 2. Jalankan Reset
        $status = Password::broker()->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                // Jika masuk ke sini, berarti token & email COCOK di database
                if ($user) {
                    $user->password = Hash::make($password);
                    $user->setRememberToken(Str::random(60));
                    $user->save();
                    
                    event(new PasswordReset($user));
                } else {
                    // Ini untuk mencegah error "save() on null"
                    throw new \Exception("User tidak ditemukan di sistem.");
                }
            }
        );

        // 3. Respon ke User
        if ($status === Password::PASSWORD_RESET) {
            return redirect()->route('login')->with('success', 'Password berhasil diperbarui! Silakan login.');
        }

        // Jika gagal (token expired/email salah), tampilkan pesan di halaman
        return back()
            ->withInput($request->only('email'))
            ->withErrors(['email' => __($status)]);
    }
}