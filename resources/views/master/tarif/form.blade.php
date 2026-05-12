@extends('layouts.app')
@section('title', ($mode === 'create' ? 'Tambah' : 'Edit') . ' Tarif')
@section('breadcrumb', 'Master Data → Tarif → ' . ($mode === 'create' ? 'Tambah' : 'Edit'))
@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="bg-gradient-to-r from-asdp-900 to-asdp-700 px-6 py-4">
            <h2 class="text-white font-semibold">{{ $mode === 'create' ? '➕ Tambah Tarif Baru' : '✏️ Edit Tarif' }}</h2>
        </div>
        <form method="POST"
              action="{{ $mode === 'create' ? route('master.tarif.store') : route('master.tarif.update', $tarif) }}"
              class="p-6 space-y-6">
            @csrf
            @if($mode === 'edit') @method('PUT') @endif

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="sm:col-span-3">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Tarif <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_tarif" value="{{ old('nama_tarif', $tarif->nama_tarif) }}"
                        placeholder="Contoh: Tarif Permenhub 2024"
                        class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-asdp-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Berlaku Mulai <span class="text-red-500">*</span></label>
                    <input type="date" name="berlaku_mulai" value="{{ old('berlaku_mulai', $tarif->berlaku_mulai?->format('Y-m-d')) }}"
                        class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-asdp-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Berlaku Sampai</label>
                    <input type="date" name="berlaku_sampai" value="{{ old('berlaku_sampai', $tarif->berlaku_sampai?->format('Y-m-d')) }}"
                        class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-asdp-500">
                </div>
                <div class="flex items-end pb-1">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="aktif" value="1"
                            {{ old('aktif', $tarif->aktif ?? false) ? 'checked' : '' }}
                            class="w-4 h-4 text-asdp-600 rounded">
                        <span class="text-sm font-medium text-gray-700">Tarif Aktif</span>
                    </label>
                </div>
            </div>

            {{-- Tarif Penumpang --}}
            <div>
                <h3 class="font-semibold text-gray-700 mb-3 pb-2 border-b border-gray-100">👥 Tarif Penumpang (Rp/orang)</h3>
                <div class="grid grid-cols-3 gap-4">
                    @foreach([['ekb_dewasa','EKB-D (Dewasa)'],['ekb_lansia','EKB-L (Lansia)'],['ekb_anak','EKB-A (Anak < 5th)']] as [$f,$l])
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ $l }}</label>
                        <input type="number" name="{{ $f }}" value="{{ old($f, $tarif->$f ?? 0) }}" min="0"
                            class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-asdp-500">
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Tarif Kendaraan --}}
            <div>
                <h3 class="font-semibold text-gray-700 mb-3 pb-2 border-b border-gray-100">🚗 Tarif Kendaraan (Rp/unit)</h3>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                    @foreach([
                        ['gol_i','Gol I (Sepeda)'],['gol_ii','Gol II (Motor <500cc)'],
                        ['gol_iii','Gol III (Motor ≥500cc)'],['gol_iv_a','Gol IV-A (Pnp)'],
                        ['gol_iv_b','Gol IV-B (Brg)'],['gol_v_a','Gol V-A (Pnp)'],
                        ['gol_v_b','Gol V-B (Brg)'],['gol_vi_a','Gol VI-A (Pnp)'],
                        ['gol_vi_b','Gol VI-B (Brg)'],['gol_vii','Gol VII'],
                        ['gol_viii','Gol VIII'],['gol_ix','Gol IX'],
                    ] as [$f,$l])
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">{{ $l }}</label>
                        <input type="number" name="{{ $f }}" value="{{ old($f, $tarif->$f ?? 0) }}" min="0"
                            class="w-full border border-gray-300 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-asdp-500">
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Asuransi --}}
            <div>
                <h3 class="font-semibold text-gray-700 mb-3 pb-2 border-b border-gray-100">🛡️ Tarif Asuransi (Rp/orang)</h3>
                <div class="grid grid-cols-2 gap-4">
                    @foreach([['asuransi_jr_pnp','Jasa Raharja (JR)'],['asuransi_jp_pnp','Jasa Penumpang (JP)']] as [$f,$l])
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ $l }}</label>
                        <input type="number" name="{{ $f }}" value="{{ old($f, $tarif->$f ?? 0) }}" min="0"
                            class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-asdp-500">
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="flex gap-3 pt-2 border-t border-gray-100">
                <a href="{{ route('master.tarif.index') }}"
                   class="flex-1 text-center px-4 py-2.5 border border-gray-300 text-gray-700 rounded-xl text-sm font-medium hover:bg-gray-50 transition">
                    Batal
                </a>
                <button type="submit"
                    class="flex-1 bg-asdp-800 text-white px-4 py-2.5 rounded-xl text-sm font-semibold hover:bg-asdp-700 transition">
                    {{ $mode === 'create' ? '✅ Simpan Tarif' : '💾 Perbarui Tarif' }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
