@extends('layouts.app')
@section('title', 'Berita Acara Penyerahan (BAP)')
@section('breadcrumb', 'Laporan → BAP')
@section('content')
<div class="space-y-5">
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
        <form method="GET" class="flex items-end gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
                <input type="date" name="tanggal" value="{{ $tanggal }}"
                    class="border border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-asdp-500">
            </div>
            <button type="submit" class="bg-asdp-800 text-white px-5 py-2.5 rounded-xl text-sm font-semibold hover:bg-asdp-700 transition">
                🔍 Tampilkan
            </button>
        </form>
    </div>

    @forelse($shifts as $shift)
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="flex items-center justify-between px-5 py-4 bg-gray-50 border-b border-gray-100">
            <div>
                <span class="font-semibold text-gray-800">BAP — {{ $shift->regu->nama_regu ?? '-' }}</span>
                <span class="text-sm text-gray-500 ml-2">{{ $shift->nama_shift }} | Supervisi: {{ $shift->supervisi->name ?? '-' }}</span>
            </div>
            <a href="{{ route('laporan.bap.pdf', $shift) }}"
               class="bg-red-600 text-white px-4 py-2 rounded-xl text-xs font-semibold hover:bg-red-700 transition">
                📄 Cetak BAP
            </a>
        </div>
        <div class="p-5 grid grid-cols-2 sm:grid-cols-4 gap-4 text-sm">
            <div><span class="text-gray-500 text-xs block">Total Penumpang</span>
                <span class="font-bold text-gray-800">{{ number_format($shift->tripKapal->sum(fn($t) => $t->tagihPelayaran?->total_penumpang ?? 0)) }} org</span></div>
            <div><span class="text-gray-500 text-xs block">Total Kendaraan</span>
                <span class="font-bold text-gray-800">{{ number_format($shift->tripKapal->sum(fn($t) => $t->tagihPelayaran?->total_kendaraan ?? 0)) }} unit</span></div>
            <div><span class="text-gray-500 text-xs block">Total Trip</span>
                <span class="font-bold text-gray-800">{{ $shift->tripKapal->sum('jumlah_trip') }} trip</span></div>
            <div><span class="text-gray-500 text-xs block">Total Pendapatan</span>
                <span class="font-bold text-asdp-800">Rp {{ number_format($shift->tripKapal->sum(fn($t) => $t->tagihPelayaran?->total_pendapatan ?? 0), 0, ',', '.') }}</span></div>
        </div>
    </div>
    @empty
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm py-16 text-center">
        <div class="text-5xl mb-3">📭</div>
        <p class="text-gray-500">Tidak ada data untuk tanggal yang dipilih</p>
    </div>
    @endforelse
</div>
@endsection
