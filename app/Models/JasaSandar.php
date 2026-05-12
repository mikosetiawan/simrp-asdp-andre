<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JasaSandar extends Model {
    protected $table = 'jasa_sandar';
    protected $fillable = ['shift_id','dermaga_id','call_sandar','jumlah_trip','tarif_jsn_per_trip','tarif_engker_per_trip','pendapatan_jsn','pendapatan_engker','total_jasa_dermaga','keterangan'];
    protected $casts = ['tarif_jsn_per_trip'=>'decimal:2','tarif_engker_per_trip'=>'decimal:2'];
    public function shift(): BelongsTo { return $this->belongsTo(ShiftOperasional::class,'shift_id'); }
    public function dermaga(): BelongsTo { return $this->belongsTo(Dermaga::class); }
}
