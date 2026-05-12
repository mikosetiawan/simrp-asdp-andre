<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

class Kapal extends Model
{
    protected $table = 'kapal';

    protected $fillable = [
        'nama_kapal', 'grt', 'jenis', 'kode_kapal', 'aktif',
    ];

    protected $casts = [
        'aktif' => 'boolean',
        'grt' => 'integer',
    ];

    public function tripKapal(): HasMany
    {
        return $this->hasMany(TripKapal::class, 'kapal_id');
    }

    public function scopeAktif(Builder $query): Builder
    {
        return $query->where('aktif', true);
    }

    public function getGrtFormattedAttribute(): string
    {
        return number_format($this->grt, 0, ',', '.') . ' GRT';
    }
}
