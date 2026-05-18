<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, HasUuids, Notifiable;

    protected $table = 'akun';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'username',
        'password',
        'role',
        'nama_lengkap',
        'email',
        'no_hp',
        'alamat',
        'kecamatan_id',
        'poin_reward',
        'tanggal_bergabung',
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

    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class);
    }

    public function ulasans()
    {
        return $this->hasMany(Ulasan::class, 'id_akun');
    }

    public function penukaranRewards()
    {
        return $this->hasMany(PenukaranReward::class, 'id_akun');
    }

    public function riwayatPoins()
    {
        return $this->hasMany(RiwayatPoin::class, 'id_akun');
    }
}
