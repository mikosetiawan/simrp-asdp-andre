<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PenjualanTiket extends Model {
    protected $table = 'penjualan_tiket';
    protected $fillable = ['shift_id','pos_penjualan','pnp_ekb_d','pnp_ekb_l','pnp_ekb_a','knd_gol_i','knd_gol_ii','knd_gol_iii','knd_gol_iv_a','knd_gol_iv_b','knd_gol_v_a','knd_gol_v_b','knd_gol_vi_a','knd_gol_vi_b','knd_gol_vii','knd_gol_viii','knd_gol_ix','total_pendapatan_penjualan','keterangan'];
    public function shift(): BelongsTo { return $this->belongsTo(ShiftOperasional::class,'shift_id'); }
    public function getTotalPnpAttribute(): int { return $this->pnp_ekb_d + $this->pnp_ekb_l + $this->pnp_ekb_a; }
}
