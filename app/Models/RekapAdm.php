<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RekapAdm extends Model {
    protected $table = 'rekap_adm';
    protected $fillable = ['shift_id','kapal_id','setoran_penumpang','setoran_kendaraan','total_setoran','no_berita_acara','keterangan'];
    public function shift(): BelongsTo { return $this->belongsTo(ShiftOperasional::class,'shift_id'); }
    public function kapal(): BelongsTo { return $this->belongsTo(Kapal::class); }
}
