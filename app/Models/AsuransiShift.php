<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AsuransiShift extends Model {
    protected $table = 'asuransi_shift';
    protected $fillable = ['shift_id','jr_pnp_dewasa','jr_pnp_lansia','jr_pnp_anak','jr_knd_gol_i','jr_knd_gol_ii','jr_knd_gol_iii','jr_knd_gol_iv','jr_knd_gol_v','jr_knd_gol_vi','jr_knd_gol_vii','jr_knd_gol_viii','jr_knd_gol_ix','total_jr','jp_pnp_dewasa','jp_pnp_lansia','total_jp','total_asuransi'];
    public function shift(): BelongsTo { return $this->belongsTo(ShiftOperasional::class,'shift_id'); }
}
