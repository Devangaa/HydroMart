<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    use HasFactory, Notifiable;

    protected $table = 'akun';
    protected $primaryKey = 'username';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'username', 
        'password', 
        'role', 
        'nama_lengkap', 
        'email', 
        'no_hp', 
        'alamat', 
        'tanggal_bergabung'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed', 
            'tanggal_bergabung' => 'datetime',
        ];
    }
}
