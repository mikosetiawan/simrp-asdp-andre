<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Builder;

class ShiftOperasional extends Model
{
    protected $table = 'shift_operasional';

    protected $fillable = [
        'tanggal','regu_id','nama_shift','jam_mulai','jam_selesai',
        'supervisi_id','kolektor_id','tanggal_awal_dinas','tanggal_akhir_dinas',
        'status','catatan','approved_by','approved_at',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'tanggal_awal_dinas' => 'date',
        'tanggal_akhir_dinas' => 'date',
        'approved_at' => 'datetime',
    ];

    public function regu(): BelongsTo { return $this->belongsTo(Regu::class); }
    public function supervisi(): BelongsTo { return $this->belongsTo(User::class, 'supervisi_id'); }
    public function kolektor(): BelongsTo { return $this->belongsTo(User::class, 'kolektor_id'); }
    public function approvedBy(): BelongsTo { return $this->belongsTo(User::class, 'approved_by'); }

    public function tripKapal(): HasMany { return $this->hasMany(TripKapal::class, 'shift_id'); }

    public function jasaSandar(): HasMany
    {
        return $this->hasMany(JasaSandar::class, 'shift_id');
    }
    public function penjualanTiket(): HasMany { return $this->hasMany(PenjualanTiket::class, 'shift_id'); }
    public function limpahanTiket(): HasMany { return $this->hasMany(LimpahanTiket::class, 'shift_id'); }
    public function asuransiShift(): HasOne { return $this->hasOne(AsuransiShift::class, 'shift_id'); }
    public function rekapAdm(): HasMany { return $this->hasMany(RekapAdm::class, 'shift_id'); }

    public function scopeDraft(Builder $q): Builder { return $q->where('status','draft'); }
    public function scopeSubmitted(Builder $q): Builder { return $q->where('status','submitted'); }
    public function scopeApproved(Builder $q): Builder { return $q->where('status','approved'); }
    public function scopeTanggal(Builder $q, string $tgl): Builder { return $q->whereDate('tanggal', $tgl); }

    public function isDraft(): bool { return $this->status === 'draft'; }
    public function isSubmitted(): bool { return $this->status === 'submitted'; }
    public function isApproved(): bool { return $this->status === 'approved'; }

    public function getTotalPendapatanAttribute(): int
    {
        return $this->tripKapal->sum(fn($t) => $t->tagihPelayaran?->total_pendapatan ?? 0);
    }

    public function getTotalTripAttribute(): int
    {
        return $this->tripKapal->sum('jumlah_trip');
    }

    public function getTotalPenumpangAttribute(): int
    {
        return $this->tripKapal->sum(fn($t) => $t->tagihPelayaran?->total_penumpang ?? 0);
    }
}
