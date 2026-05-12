@extends('layouts.app')
@section('title', ($mode === 'create' ? 'Tambah' : 'Edit') . ' Dermaga')
@section('breadcrumb', 'Master Data → Dermaga → ' . ($mode === 'create' ? 'Tambah' : 'Edit'))

@section('content')
<div class="max-w-xl mx-auto">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

        {{-- Header --}}
        <div class="bg-gradient-to-r from-asdp-800 to-asdp-600 px-6 py-4">
            <h2 class="text-white font-semibold text-base">
                {{ $mode === 'create' ? '⚓ Tambah Dermaga Baru' : '✏️ Edit Dermaga' }}
            </h2>
            <p class="text-white/60 text-xs mt-0.5">Data dermaga operasional Pelabuhan Merak</p>
        </div>

        <form method="POST"
              action="{{ $mode === 'create' ? route('master.dermaga.store') : route('master.dermaga.update', $dermaga) }}"
              class="p-6 space-y-5">
            @csrf
            @if($mode === 'edit') @method('PUT') @endif

            {{-- Nama & Kode --}}
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5 uppercase tracking-wide">
                        Nama Dermaga <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="nama_dermaga"
                           value="{{ old('nama_dermaga', $dermaga->nama_dermaga) }}"
                           placeholder="Contoh: Dermaga I"
                           class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-asdp-500 focus:border-asdp-500 transition @error('nama_dermaga') border-red-400 @enderror">
                    @error('nama_dermaga')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5 uppercase tracking-wide">
                        Kode Dermaga <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="kode_dermaga"
                           value="{{ old('kode_dermaga', $dermaga->kode_dermaga) }}"
                           placeholder="Contoh: D1"
                           class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-asdp-500 focus:border-asdp-500 transition uppercase @error('kode_dermaga') border-red-400 @enderror">
                    @error('kode_dermaga')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Tarif --}}
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5 uppercase tracking-wide">
                        Tarif JSN / Trip (Rp) <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">Rp</span>
                        <input type="number" name="tarif_jsn_per_trip"
                               value="{{ old('tarif_jsn_per_trip', $dermaga->tarif_jsn_per_trip) }}"
                               min="0" step="1000"
                               class="w-full border border-gray-300 rounded-xl pl-9 pr-3 py-2.5 text-sm focus:ring-2 focus:ring-asdp-500 focus:border-asdp-500 transition @error('tarif_jsn_per_trip') border-red-400 @enderror">
                    </div>
                    @error('tarif_jsn_per_trip')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5 uppercase tracking-wide">
                        Tarif Engker / Trip (Rp) <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">Rp</span>
                        <input type="number" name="tarif_engker_per_trip"
                               value="{{ old('tarif_engker_per_trip', $dermaga->tarif_engker_per_trip) }}"
                               min="0" step="1000"
                               class="w-full border border-gray-300 rounded-xl pl-9 pr-3 py-2.5 text-sm focus:ring-2 focus:ring-asdp-500 focus:border-asdp-500 transition @error('tarif_engker_per_trip') border-red-400 @enderror">
                    </div>
                    @error('tarif_engker_per_trip')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Kapasitas & Status --}}
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5 uppercase tracking-wide">
                        Kapasitas Trip / Hari
                    </label>
                    <input type="number" name="kapasitas_trip_per_hari"
                           value="{{ old('kapasitas_trip_per_hari', $dermaga->kapasitas_trip_per_hari) }}"
                           min="0"
                           class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-asdp-500 focus:border-asdp-500 transition">
                </div>
                <div class="flex items-end pb-1">
                    <label class="flex items-center gap-2.5 cursor-pointer">
                        <div class="relative">
                            <input type="checkbox" name="aktif" value="1"
                                   {{ old('aktif', $dermaga->aktif ?? true) ? 'checked' : '' }}
                                   class="sr-only peer">
                            <div class="w-10 h-5 bg-gray-200 peer-checked:bg-asdp-600 rounded-full transition-colors"></div>
                            <div class="absolute top-0.5 left-0.5 w-4 h-4 bg-white rounded-full shadow transition-transform peer-checked:translate-x-5"></div>
                        </div>
                        <span class="text-sm font-medium text-gray-700">Dermaga Aktif</span>
                    </label>
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex gap-3 pt-3 border-t border-gray-100">
                <a href="{{ route('master.dermaga.index') }}"
                   class="flex-1 text-center px-4 py-2.5 border border-gray-300 text-gray-700 rounded-xl text-sm font-medium hover:bg-gray-50 transition">
                    Batal
                </a>
                <button type="submit"
                        class="flex-1 bg-asdp-700 text-white px-4 py-2.5 rounded-xl text-sm font-semibold hover:bg-asdp-800 transition">
                    {{ $mode === 'create' ? '✅ Simpan Dermaga' : '💾 Perbarui Dermaga' }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
