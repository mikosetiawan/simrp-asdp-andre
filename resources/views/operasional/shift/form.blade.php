@extends('layouts.app')
@section('title', ($mode === 'create' ? 'Buat' : 'Edit') . ' Shift Operasional')
@section('breadcrumb', 'Operasional → Shift → ' . ($mode === 'create' ? 'Buat Baru' : 'Edit'))

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="bg-gradient-to-r from-asdp-900 to-asdp-700 px-6 py-4">
            <h2 class="text-white font-semibold text-lg">
                {{ $mode === 'create' ? '➕ Buat Shift Operasional Baru' : '✏️ Edit Shift Operasional' }}
            </h2>
        </div>
        <form method="POST"
              action="{{ $mode === 'create' ? route('operasional.shift.store') : route('operasional.shift.update', $shift) }}"
              class="p-6 space-y-5">
            @csrf
            @if($mode === 'edit') @method('PUT') @endif

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal <span class="text-red-500">*</span></label>
                    <input type="date" name="tanggal" value="{{ old('tanggal', $shift->tanggal?->format('Y-m-d') ?? today()->format('Y-m-d')) }}"
                        class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-asdp-500 focus:border-asdp-500 @error('tanggal') border-red-400 @enderror">
                    @error('tanggal')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Regu <span class="text-red-500">*</span></label>
                    <select name="regu_id" class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-asdp-500 @error('regu_id') border-red-400 @enderror">
                        <option value="">-- Pilih Regu --</option>
                        @foreach($regu as $r)
                        <option value="{{ $r->id }}" {{ old('regu_id', $shift->regu_id) == $r->id ? 'selected' : '' }}>
                            {{ $r->nama_regu }}
                        </option>
                        @endforeach
                    </select>
                    @error('regu_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Shift <span class="text-red-500">*</span></label>
                    <select name="nama_shift" class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-asdp-500">
                        @foreach(['Pagi','Siang','Malam','Full Day'] as $s)
                        <option value="{{ $s }}" {{ old('nama_shift', $shift->nama_shift) === $s ? 'selected' : '' }}>{{ $s }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Jam Mulai <span class="text-red-500">*</span></label>
                    <input type="time" name="jam_mulai" value="{{ old('jam_mulai', substr($shift->jam_mulai ?? '07:00', 0, 5)) }}"
                        class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-asdp-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Jam Selesai <span class="text-red-500">*</span></label>
                    <input type="time" name="jam_selesai" value="{{ old('jam_selesai', substr($shift->jam_selesai ?? '15:00', 0, 5)) }}"
                        class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-asdp-500">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Supervisi Usaha <span class="text-red-500">*</span></label>
                    <select name="supervisi_id" class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-asdp-500 @error('supervisi_id') border-red-400 @enderror">
                        <option value="">-- Pilih Supervisi --</option>
                        @foreach($supervisi as $sv)
                        <option value="{{ $sv->id }}" {{ old('supervisi_id', $shift->supervisi_id) == $sv->id ? 'selected' : '' }}>
                            {{ $sv->name }} ({{ $sv->regu->nama_regu ?? '-' }})
                        </option>
                        @endforeach
                    </select>
                    @error('supervisi_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kolektor Tiket</label>
                    <select name="kolektor_id" class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-asdp-500">
                        <option value="">-- Pilih Kolektor (Opsional) --</option>
                        @foreach($kolektor as $kl)
                        <option value="{{ $kl->id }}" {{ old('kolektor_id', $shift->kolektor_id) == $kl->id ? 'selected' : '' }}>
                            {{ $kl->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tgl Awal Dinas</label>
                    <input type="date" name="tanggal_awal_dinas" value="{{ old('tanggal_awal_dinas', $shift->tanggal_awal_dinas?->format('Y-m-d')) }}"
                        class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-asdp-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tgl Akhir Dinas</label>
                    <input type="date" name="tanggal_akhir_dinas" value="{{ old('tanggal_akhir_dinas', $shift->tanggal_akhir_dinas?->format('Y-m-d')) }}"
                        class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-asdp-500">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Catatan</label>
                <textarea name="catatan" rows="3"
                    class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-asdp-500 resize-none"
                    placeholder="Catatan tambahan (opsional)...">{{ old('catatan', $shift->catatan) }}</textarea>
            </div>

            <div class="flex items-center justify-end gap-3 pt-2 border-t border-gray-100">
                <a href="{{ route('operasional.shift.index') }}"
                   class="px-5 py-2.5 border border-gray-300 text-gray-700 rounded-xl text-sm font-medium hover:bg-gray-50 transition">
                    Batal
                </a>
                <button type="submit"
                    class="px-6 py-2.5 bg-asdp-800 text-white rounded-xl text-sm font-semibold hover:bg-asdp-700 transition">
                    {{ $mode === 'create' ? '✅ Simpan Shift' : '💾 Perbarui Shift' }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const namaShiftSelect = document.querySelector('select[name="nama_shift"]');
    const jamMulaiInput = document.querySelector('input[name="jam_mulai"]');
    const jamSelesaiInput = document.querySelector('input[name="jam_selesai"]');
    const tanggalInput = document.querySelector('input[name="tanggal"]');
    const tglAwalInput = document.querySelector('input[name="tanggal_awal_dinas"]');
    const tglAkhirInput = document.querySelector('input[name="tanggal_akhir_dinas"]');

    function updateTimesAndDates() {
        if (!namaShiftSelect || !tanggalInput) return;
        const shiftVal = namaShiftSelect.value;
        const baseDateVal = tanggalInput.value;

        if (!baseDateVal) return;

        if (tglAwalInput) tglAwalInput.value = baseDateVal;

        const baseDate = new Date(baseDateVal + 'T00:00:00');

        if (shiftVal === 'Pagi') {
            if (jamMulaiInput) jamMulaiInput.value = '08:00';
            if (jamSelesaiInput) jamSelesaiInput.value = '20:00';
            if (tglAkhirInput) tglAkhirInput.value = baseDateVal;
        } else if (shiftVal === 'Malam') {
            if (jamMulaiInput) jamMulaiInput.value = '20:00';
            if (jamSelesaiInput) jamSelesaiInput.value = '08:00';
            const nextDate = new Date(baseDate);
            nextDate.setDate(nextDate.getDate() + 1);
            const yyyy = nextDate.getFullYear();
            const mm = String(nextDate.getMonth() + 1).padStart(2, '0');
            const dd = String(nextDate.getDate()).padStart(2, '0');
            if (tglAkhirInput) tglAkhirInput.value = `${yyyy}-${mm}-${dd}`;
        } else if (shiftVal === 'Siang') {
            if (jamMulaiInput) jamMulaiInput.value = '15:00';
            if (jamSelesaiInput) jamSelesaiInput.value = '23:00';
            if (tglAkhirInput) tglAkhirInput.value = baseDateVal;
        } else if (shiftVal === 'Full Day') {
            if (jamMulaiInput) jamMulaiInput.value = '08:00';
            if (jamSelesaiInput) jamSelesaiInput.value = '08:00';
            const nextDate = new Date(baseDate);
            nextDate.setDate(nextDate.getDate() + 1);
            const yyyy = nextDate.getFullYear();
            const mm = String(nextDate.getMonth() + 1).padStart(2, '0');
            const dd = String(nextDate.getDate()).padStart(2, '0');
            if (tglAkhirInput) tglAkhirInput.value = `${yyyy}-${mm}-${dd}`;
        }
    }

    if (namaShiftSelect && tanggalInput) {
        namaShiftSelect.addEventListener('change', updateTimesAndDates);
        tanggalInput.addEventListener('change', updateTimesAndDates);

        @if($mode === 'create')
            updateTimesAndDates();
        @endif
    }
});
</script>
@endpush
