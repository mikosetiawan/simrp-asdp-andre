<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Tarif extends Model
{
    protected $table = 'tarif';

    protected $fillable = [
        'nama_tarif','berlaku_mulai','berlaku_sampai',
        'ekb_dewasa','ekb_lansia','ekb_anak',
        'gol_i','gol_ii','gol_iii',
        'gol_iv_a','gol_iv_b','gol_v_a','gol_v_b',
        'gol_vi_a','gol_vi_b','gol_vii','gol_viii','gol_ix',
        'asuransi_jr_pnp','asuransi_jp_pnp','aktif',
    ];

    protected $casts = [
        'berlaku_mulai' => 'date',
        'berlaku_sampai' => 'date',
        'aktif' => 'boolean',
    ];

    public function scopeAktif(Builder $query): Builder
    {
        return $query->where('aktif', true);
    }

    public static function aktifPadaTanggal(string $tanggal): ?self
    {
        return static::where('aktif', true)
            ->where('berlaku_mulai', '<=', $tanggal)
            ->where(fn($q) => $q->whereNull('berlaku_sampai')->orWhere('berlaku_sampai', '>=', $tanggal))
            ->orderByDesc('berlaku_mulai')
            ->first();
    }
}
