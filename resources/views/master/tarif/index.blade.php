{{-- resources/views/master/tarif/index.blade.php --}}
@extends('layouts.app')
@section('title', 'Master Tarif')
@section('breadcrumb', 'Master Data → Tarif')
@section('content')
<div class="space-y-5">
    <div class="flex justify-between items-center">
        <p class="text-sm text-gray-500">Tarif tiket kapal feri Merak–Bakauheni</p>
        <a href="{{ route('master.tarif.create') }}"
           class="bg-asdp-800 text-white px-4 py-2 rounded-xl text-sm font-semibold hover:bg-asdp-700 transition">
            + Tambah Tarif Baru
        </a>
    </div>
    @forelse($tarif as $t)
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
            <div class="flex items-center gap-3">
                <span class="text-lg">💰</span>
                <div>
                    <h3 class="font-semibold text-gray-800">{{ $t->nama_tarif }}</h3>
                    <p class="text-xs text-gray-500">
                        Berlaku: {{ $t->berlaku_mulai->format('d/m/Y') }}
                        @if($t->berlaku_sampai) s/d {{ $t->berlaku_sampai->format('d/m/Y') }} @else — sekarang @endif
                    </p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <span class="px-3 py-1 rounded-full text-xs font-bold {{ $t->aktif ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                    {{ $t->aktif ? '✅ Aktif' : 'Nonaktif' }}
                </span>
                <a href="{{ route('master.tarif.edit', $t) }}"
                   class="px-3 py-1.5 bg-blue-50 text-blue-700 rounded-lg text-xs font-medium hover:bg-blue-100">Edit</a>
            </div>
        </div>
        <div class="p-5 grid grid-cols-3 sm:grid-cols-6 gap-4 text-xs">
            <div class="bg-blue-50 rounded-xl p-3">
                <div class="text-blue-600 font-medium mb-1">EKB-D (Dewasa)</div>
                <div class="font-bold text-blue-900">Rp {{ number_format($t->ekb_dewasa, 0, ',', '.') }}</div>
            </div>
            <div class="bg-blue-50 rounded-xl p-3">
                <div class="text-blue-600 font-medium mb-1">EKB-L (Lansia)</div>
                <div class="font-bold text-blue-900">Rp {{ number_format($t->ekb_lansia, 0, ',', '.') }}</div>
            </div>
            <div class="bg-orange-50 rounded-xl p-3">
                <div class="text-orange-600 font-medium mb-1">Gol I (Sepeda)</div>
                <div class="font-bold text-orange-900">Rp {{ number_format($t->gol_i, 0, ',', '.') }}</div>
            </div>
            <div class="bg-orange-50 rounded-xl p-3">
                <div class="text-orange-600 font-medium mb-1">Gol IV-A</div>
                <div class="font-bold text-orange-900">Rp {{ number_format($t->gol_iv_a, 0, ',', '.') }}</div>
            </div>
            <div class="bg-orange-50 rounded-xl p-3">
                <div class="text-orange-600 font-medium mb-1">Gol VII</div>
                <div class="font-bold text-orange-900">Rp {{ number_format($t->gol_vii, 0, ',', '.') }}</div>
            </div>
            <div class="bg-orange-50 rounded-xl p-3">
                <div class="text-orange-600 font-medium mb-1">Gol IX</div>
                <div class="font-bold text-orange-900">Rp {{ number_format($t->gol_ix, 0, ',', '.') }}</div>
            </div>
        </div>
    </div>
    @empty
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm py-10 text-center text-gray-400">
        Belum ada data tarif
    </div>
    @endforelse
</div>
@endsection
