@extends('layouts.app')
@section('title', ($mode === 'create' ? 'Tambah' : 'Edit') . ' Regu')
@section('breadcrumb', 'Master Data → Regu → ' . ($mode === 'create' ? 'Tambah' : 'Edit'))

@section('content')
<div class="max-w-md mx-auto">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

        <div class="bg-gradient-to-r from-asdp-800 to-asdp-600 px-6 py-4">
            <h2 class="text-white font-semibold text-base">
                {{ $mode === 'create' ? '👥 Tambah Regu Baru' : '✏️ Edit Regu' }}
            </h2>
        </div>

        <form method="POST"
              action="{{ $mode === 'create' ? route('master.regu.store') : route('master.regu.update', $regu) }}"
              class="p-6 space-y-4">
            @csrf
            @if($mode === 'edit') @method('PUT') @endif

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5 uppercase tracking-wide">
                        Nama Regu <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="nama_regu"
                           value="{{ old('nama_regu', $regu->nama_regu) }}"
                           placeholder="Contoh: Regu I"
                           class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-asdp-500 focus:border-asdp-500 transition @error('nama_regu') border-red-400 @enderror">
                    @error('nama_regu')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5 uppercase tracking-wide">
                        Kode Regu <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="kode_regu"
                           value="{{ old('kode_regu', $regu->kode_regu) }}"
                           placeholder="Contoh: R1"
                           class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-asdp-500 focus:border-asdp-500 transition uppercase @error('kode_regu') border-red-400 @enderror">
                    @error('kode_regu')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1.5 uppercase tracking-wide">Keterangan</label>
                <textarea name="keterangan" rows="3"
                          placeholder="Keterangan tambahan (opsional)..."
                          class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-asdp-500 focus:border-asdp-500 transition resize-none">{{ old('keterangan', $regu->keterangan) }}</textarea>
            </div>

            <div>
                <label class="flex items-center gap-2.5 cursor-pointer">
                    <div class="relative">
                        <input type="checkbox" name="aktif" value="1"
                               {{ old('aktif', $regu->aktif ?? true) ? 'checked' : '' }}
                               class="sr-only peer">
                        <div class="w-10 h-5 bg-gray-200 peer-checked:bg-asdp-600 rounded-full transition-colors"></div>
                        <div class="absolute top-0.5 left-0.5 w-4 h-4 bg-white rounded-full shadow transition-transform peer-checked:translate-x-5"></div>
                    </div>
                    <span class="text-sm font-medium text-gray-700">Regu Aktif</span>
                </label>
            </div>

            <div class="flex gap-3 pt-3 border-t border-gray-100">
                <a href="{{ route('master.regu.index') }}"
                   class="flex-1 text-center px-4 py-2.5 border border-gray-300 text-gray-700 rounded-xl text-sm font-medium hover:bg-gray-50 transition">
                    Batal
                </a>
                <button type="submit"
                        class="flex-1 bg-asdp-700 text-white px-4 py-2.5 rounded-xl text-sm font-semibold hover:bg-asdp-800 transition">
                    {{ $mode === 'create' ? '✅ Simpan' : '💾 Perbarui' }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
