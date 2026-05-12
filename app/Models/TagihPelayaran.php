<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TagihPelayaran extends Model
{
    protected $table = 'tagih_pelayaran';

    protected $fillable = [
        'trip_id','tarif_id',
        'jml_pnp_ekb_d','jml_pnp_ekb_l','jml_pnp_ekb_a',
        'gol_i','gol_ii','gol_iii',
        'gol_iv_a','gol_iv_b','gol_v_a','gol_v_b',
        'gol_vi_a','gol_vi_b','gol_vii','gol_viii','gol_ix',
        'pendapatan_penumpang','pendapatan_kendaraan','total_pendapatan',
    ];

    protected $casts = [
        'pendapatan_penumpang' => 'integer',
        'pendapatan_kendaraan' => 'integer',
        'total_pendapatan' => 'integer',
    ];

    public function trip(): BelongsTo { return $this->belongsTo(TripKapal::class); }
    public function tarif(): BelongsTo { return $this->belongsTo(Tarif::class); }

    // Accessor total penumpang (backup jika DB generated column tidak dipakai)
    public function getTotalPenumpangAttribute(): int
    {
        return $this->jml_pnp_ekb_d + $this->jml_pnp_ekb_l + $this->jml_pnp_ekb_a;
    }

    public function getTotalKendaraanAttribute(): int
    {
        return $this->gol_i + $this->gol_ii + $this->gol_iii
            + $this->gol_iv_a + $this->gol_iv_b
            + $this->gol_v_a + $this->gol_v_b
            + $this->gol_vi_a + $this->gol_vi_b
            + $this->gol_vii + $this->gol_viii + $this->gol_ix;
    }

    public function getPendapatanFormattedAttribute(): string
    {
        return 'Rp ' . number_format($this->total_pendapatan, 0, ',', '.');
    }
}
