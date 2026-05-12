<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TripKapal extends Model
{
    protected $table = 'trip_kapal';

    protected $fillable = [
        'shift_id','kapal_id','kapal_pengganti_id','dermaga_id',
        'jumlah_trip','trip_ke','jam_berangkat','jam_tiba','keterangan',
    ];

    public function shift(): BelongsTo { return $this->belongsTo(ShiftOperasional::class, 'shift_id'); }
    public function kapal(): BelongsTo { return $this->belongsTo(Kapal::class); }
    public function kapalPengganti(): BelongsTo { return $this->belongsTo(Kapal::class, 'kapal_pengganti_id'); }
    public function dermaga(): BelongsTo { return $this->belongsTo(Dermaga::class); }
    public function tagihPelayaran(): HasOne { return $this->hasOne(TagihPelayaran::class, 'trip_id'); }
    public function manifest(): HasOne { return $this->hasOne(ManifestPenumpang::class, 'trip_id'); }
}
