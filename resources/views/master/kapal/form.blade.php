@extends('layouts.app')
@section('title', ($mode === 'create' ? 'Tambah' : 'Edit') . ' Kapal')
@section('breadcrumb', 'Master Data → Kapal → ' . ($mode === 'create' ? 'Tambah' : 'Edit'))
@section('content')
<div class="max-w-lg mx-auto">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="bg-gradient-to-r from-asdp-900 to-asdp-700 px-6 py-4">
            <h2 class="text-white font-semibold">{{ $mode === 'create' ? '➕ Tambah Kapal Baru' : '✏️ Edit Data Kapal' }}</h2>
        </div>
        <form method="POST"
              action="{{ $mode === 'create' ? route('master.kapal.store') : route('master.kapal.update', $kapal) }}"
              class="p-6 space-y-4">
            @csrf
            @if($mode === 'edit') @method('PUT') @endif

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Kapal <span class="text-red-500">*</span></label>
                <input type="text" name="nama_kapal" value="{{ old('nama_kapal', $kapal->nama_kapal) }}"
                    placeholder="Contoh: JATRA III"
                    class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-asdp-500 uppercase @error('nama_kapal') border-red-400 @enderror">
                @error('nama_kapal')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kode Kapal</label>
                    <input type="text" name="kode_kapal" value="{{ old('kode_kapal', $kapal->kode_kapal) }}"
                        placeholder="Contoh: JT3"
                        class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-asdp-500 uppercase">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">GRT <span class="text-red-500">*</span></label>
                    <input type="number" name="grt" value="{{ old('grt', $kapal->grt) }}"
                        placeholder="Contoh: 5050" min="1"
                        class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-asdp-500 @error('grt') border-red-400 @enderror">
                    @error('grt')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Kapal <span class="text-red-500">*</span></label>
                    <select name="jenis" class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-asdp-500">
                        <option value="roro" {{ old('jenis', $kapal->jenis) === 'roro' ? 'selected' : '' }}>RoRo</option>
                        <option value="lct"  {{ old('jenis', $kapal->jenis) === 'lct'  ? 'selected' : '' }}>LCT</option>
                    </select>
                </div>
                <div class="flex items-end pb-1">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="aktif" value="1"
                            {{ old('aktif', $kapal->aktif ?? true) ? 'checked' : '' }}
                            class="w-4 h-4 text-asdp-600 rounded focus:ring-asdp-500">
                        <span class="text-sm font-medium text-gray-700">Kapal Aktif</span>
                    </label>
                </div>
            </div>

            <div class="flex gap-3 pt-2 border-t border-gray-100">
                <a href="{{ route('master.kapal.index') }}"
                   class="flex-1 text-center px-4 py-2.5 border border-gray-300 text-gray-700 rounded-xl text-sm font-medium hover:bg-gray-50 transition">
                    Batal
                </a>
                <button type="submit"
                    class="flex-1 bg-asdp-800 text-white px-4 py-2.5 rounded-xl text-sm font-semibold hover:bg-asdp-700 transition">
                    {{ $mode === 'create' ? '✅ Simpan' : '💾 Perbarui' }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
