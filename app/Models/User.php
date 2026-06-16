<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'asal_negara', 
        'google_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    /**
     * Override pengiriman notifikasi reset password.
     */
    public function sendPasswordResetNotification($token)
    {
        // Kita panggil class ResetPassword secara statis untuk mendefinisikan tampilan email
        \Illuminate\Auth\Notifications\ResetPassword::toMailUsing(function ($notifiable, $token) {
            return (new \Illuminate\Notifications\Messages\MailMessage)
                ->subject('🔒 Atur Ulang Kata Sandi - Sajian Sehat')
                ->greeting('Halo, ' . $notifiable->name . '!')
                ->line('Kami menerima permintaan untuk mengatur ulang kata sandi akun Anda.')
                ->action('Atur Ulang Password', url(route('password.reset', [
                    'token' => $token,
                    'email' => $notifiable->getEmailForPasswordReset(),
                ], false)))
                ->line('Link ini akan kedaluwarsa dalam 60 menit.')
                ->line('Jika Anda tidak merasa meminta ini, abaikan saja email ini.')
                ->salutation('Salam sehat, Tim Sajian Sehat');
        });

        // Jalankan pengiriman notifikasi standar (yang sekarang sudah menggunakan settingan di atas)
        $this->notify(new \Illuminate\Auth\Notifications\ResetPassword($token));
    }
}

