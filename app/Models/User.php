<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
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

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    // Пользователь может иметь один профиль гостя
    public function guest()
    {
        return $this->hasOne(Guest::class);
    }

    // Проверка: является ли пользователь администратором (или выше)
    public function isAdmin()
    {
        return in_array($this->role, ['admin', 'superadmin']);
    }

    // Проверка: является ли пользователь супер-админом
    public function isSuperAdmin()
    {
        return $this->role === 'superadmin';
    }

    // Проверка: является ли пользователь обычным гостем
    public function isGuest()
    {
        return $this->role === 'guest';
    }
}
