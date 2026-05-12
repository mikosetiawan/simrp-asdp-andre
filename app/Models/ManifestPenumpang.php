<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ManifestPenumpang extends Model {
    protected $table = 'manifest_penumpang';
    protected $fillable = ['trip_id','pnp_dalam_gol_iv_a','pnp_dalam_gol_iv_b','pnp_dalam_gol_v_a','pnp_dalam_gol_v_b','pnp_dalam_gol_vi_a','pnp_dalam_gol_vi_b','pnp_dalam_gol_vii','pnp_dalam_gol_viii','pnp_dalam_gol_ix','total_pnp_manifest','keterangan'];
    public function trip(): BelongsTo { return $this->belongsTo(TripKapal::class,'trip_id'); }
    public function getTotalAttribute(): int {
        return $this->pnp_dalam_gol_iv_a + $this->pnp_dalam_gol_iv_b + $this->pnp_dalam_gol_v_a + $this->pnp_dalam_gol_v_b + $this->pnp_dalam_gol_vi_a + $this->pnp_dalam_gol_vi_b + $this->pnp_dalam_gol_vii + $this->pnp_dalam_gol_viii + $this->pnp_dalam_gol_ix;
    }
}
