<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Regu extends Model
{
    protected $table = 'regu';
    protected $fillable = ['nama_regu', 'kode_regu', 'keterangan', 'aktif'];
    protected $casts = ['aktif' => 'boolean'];

    public function shifts(): HasMany { return $this->hasMany(ShiftOperasional::class); }
    public function users(): HasMany { return $this->hasMany(User::class); }
}
