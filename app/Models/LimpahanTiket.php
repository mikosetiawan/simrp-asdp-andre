<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LimpahanTiket extends Model {
    protected $table = 'limpahan_tiket';
    protected $fillable = ['shift_id','jenis_tiket','terjual','tertagih_regu1','tertagih_regu2','tertagih_regu3','dilimpahkan','dilimpahkan_ke_regu_id','keterangan'];
    public function shift(): BelongsTo { return $this->belongsTo(ShiftOperasional::class,'shift_id'); }
    public function dilimpahkanKeRegu(): BelongsTo { return $this->belongsTo(Regu::class,'dilimpahkan_ke_regu_id'); }
}
