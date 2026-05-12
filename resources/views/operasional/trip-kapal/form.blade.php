@extends('layouts.app')
@section('title', 'Tambah Trip Kapal')
@section('breadcrumb', 'Operasional → Shift → Trip Kapal')
@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="bg-gradient-to-r from-asdp-900 to-asdp-700 px-6 py-4">
            <h2 class="text-white font-semibold">🚢 Tambah Data Trip Kapal</h2>
            <p class="text-white/70 text-sm mt-1">
                Shift: {{ $shift->regu->nama_regu }} — {{ $shift->nama_shift }} — {{ $shift->tanggal->isoFormat('D MMM Y') }}
            </p>
        </div>
        <form method="POST" action="{{ route('operasional.trip-kapal.store', $shift) }}" class="p-6 space-y-4">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kapal <span class="text-red-500">*</span></label>
                    <select name="kapal_id" class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-asdp-500 @error('kapal_id') border-red-400 @enderror">
                        <option value="">-- Pilih Kapal --</option>
                        @foreach($kapal as $k)
                        <option value="{{ $k->id }}" {{ old('kapal_id') == $k->id ? 'selected' : '' }}>
                            {{ $k->nama_kapal }} ({{ number_format($k->grt) }} GRT)
                        </option>
                        @endforeach
                    </select>
                    @error('kapal_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kapal Pengganti</label>
                    <select name="kapal_pengganti_id" class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-asdp-500">
                        <option value="">-- Tidak Ada Pengganti --</option>
                        @foreach($kapal as $k)
                        <option value="{{ $k->id }}" {{ old('kapal_pengganti_id') == $k->id ? 'selected' : '' }}>
                            {{ $k->nama_kapal }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Dermaga <span class="text-red-500">*</span></label>
                    <select name="dermaga_id" class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-asdp-500 @error('dermaga_id') border-red-400 @enderror">
                        <option value="">-- Pilih Dermaga --</option>
                        @foreach($dermaga as $d)
                        <option value="{{ $d->id }}" {{ old('dermaga_id') == $d->id ? 'selected' : '' }}>
                            {{ $d->nama_dermaga }} ({{ $d->kode_dermaga }})
                        </option>
                        @endforeach
                    </select>
                    @error('dermaga_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah Trip <span class="text-red-500">*</span></label>
                    <input type="number" name="jumlah_trip" value="{{ old('jumlah_trip', 1) }}" min="1"
                        class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-asdp-500">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Trip Ke-</label>
                    <input type="number" name="trip_ke" value="{{ old('trip_ke', 1) }}" min="1"
                        class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-asdp-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Jam Berangkat</label>
                    <input type="time" name="jam_berangkat" value="{{ old('jam_berangkat') }}"
                        class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-asdp-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Jam Tiba</label>
                    <input type="time" name="jam_tiba" value="{{ old('jam_tiba') }}"
                        class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-asdp-500">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Keterangan</label>
                <textarea name="keterangan" rows="2" placeholder="Opsional..."
                    class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-asdp-500 resize-none">{{ old('keterangan') }}</textarea>
            </div>

            <div class="flex gap-3 pt-2 border-t border-gray-100">
                <a href="{{ route('operasional.shift.show', $shift) }}"
                   class="flex-1 text-center px-4 py-2.5 border border-gray-300 text-gray-700 rounded-xl text-sm font-medium hover:bg-gray-50 transition">
                    Batal
                </a>
                <button type="submit"
                    class="flex-1 bg-asdp-800 text-white px-4 py-2.5 rounded-xl text-sm font-semibold hover:bg-asdp-700 transition">
                    ✅ Simpan & Lanjut Input Tagih
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
