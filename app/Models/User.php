<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    /**
     * Guard name eksplisit untuk Spatie Permission.
     */
    protected $guard_name = 'web';

    protected $fillable = ['name','nik','email','password','regu_id','jabatan','aktif'];

    protected $hidden = ['password','remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'aktif' => 'boolean',
    ];

    public function regu()
    {
        return $this->belongsTo(Regu::class);
    }

    public function shiftsSupervisi()
    {
        return $this->hasMany(ShiftOperasional::class, 'supervisi_id');
    }

    public function shiftsKolektor()
    {
        return $this->hasMany(ShiftOperasional::class, 'kolektor_id');
    }

    public function isAdmin(): bool { return $this->hasRole('admin'); }
    public function isSupervisi(): bool { return $this->hasRole('supervisi'); }
    public function isKolektor(): bool { return $this->hasRole('kolektor'); }
    public function isEksekutif(): bool { return $this->hasRole('eksekutif'); }
}
