<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Dermaga extends Model
{
    protected $table = 'dermaga';
    protected $fillable = ['nama_dermaga', 'kode_dermaga', 'tarif_jsn_per_trip', 'tarif_engker_per_trip', 'kapasitas_trip_per_hari', 'aktif'];
    protected $casts = ['aktif' => 'boolean', 'tarif_jsn_per_trip' => 'decimal:2', 'tarif_engker_per_trip' => 'decimal:2'];

    public function tripKapal(): HasMany { return $this->hasMany(TripKapal::class); }

    public function jasaSandar(): HasMany { return $this->hasMany(JasaSandar::class); }
}
