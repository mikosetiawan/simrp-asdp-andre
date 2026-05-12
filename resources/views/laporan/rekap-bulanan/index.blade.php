@extends('layouts.app')
@section('title', 'Rekap Bulanan')
@section('breadcrumb', 'Laporan → Rekap Bulanan')
@section('content')
<div class="space-y-5">
    {{-- Filter --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
        <form method="GET" class="flex flex-wrap items-end gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Bulan</label>
                <select name="bulan" class="border border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-asdp-500">
                    @foreach(['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'] as $i => $nm)
                    <option value="{{ $i+1 }}" {{ $bulan == $i+1 ? 'selected' : '' }}>{{ $nm }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tahun</label>
                <select name="tahun" class="border border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-asdp-500">
                    @foreach(range(2023, now()->year + 1) as $y)
                    <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Regu</label>
                <select name="regu_id" class="border border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-asdp-500">
                    <option value="">Semua Regu</option>
                    @foreach($regus as $rg)
                        <option value="{{ $rg->id }}" {{ $regu_id == $rg->id ? 'selected' : '' }}>{{ $rg->nama_regu }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="bg-asdp-800 text-white px-5 py-2.5 rounded-xl text-sm font-semibold hover:bg-asdp-700 transition">🔍 Tampilkan</button>
            @can('laporan.export')
            <a href="{{ route('laporan.rekap-bulanan.pdf', ['bulan' => $bulan, 'tahun' => $tahun, 'regu_id' => $regu_id]) }}"
               class="bg-red-600 text-white px-5 py-2.5 rounded-xl text-sm font-semibold hover:bg-red-700 transition">📄 PDF</a>
            <a href="{{ route('laporan.rekap-bulanan.excel', ['bulan' => $bulan, 'tahun' => $tahun, 'regu_id' => $regu_id]) }}"
               class="bg-green-600 text-white px-5 py-2.5 rounded-xl text-sm font-semibold hover:bg-green-700 transition">📊 Excel</a>
            <button type="button" onclick="window.print()" class="bg-gray-600 text-white px-5 py-2.5 rounded-xl text-sm font-semibold hover:bg-gray-700 transition">🖨️ Print</button>
            @endcan
        </form>
    </div>

    {{-- Summary --}}
    <div class="grid grid-cols-3 gap-4">
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm text-center">
            <div class="text-xs text-gray-500 uppercase font-medium mb-1">Total Pendapatan</div>
            <div class="text-xl font-bold text-gray-800">Rp {{ number_format($data['total_pendapatan'], 0, ',', '.') }}</div>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm text-center">
            <div class="text-xs text-gray-500 uppercase font-medium mb-1">Total Trip</div>
            <div class="text-xl font-bold text-gray-800">{{ number_format($data['total_trip']) }}</div>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm text-center">
            <div class="text-xs text-gray-500 uppercase font-medium mb-1">Total Penumpang</div>
            <div class="text-xl font-bold text-gray-800">{{ number_format($data['total_penumpang']) }}</div>
        </div>
    </div>

    {{-- Chart --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
        <h3 class="font-semibold text-gray-800 mb-4">📈 Tren Pendapatan Harian</h3>
        <canvas id="chartBulanan" height="80"></canvas>
    </div>

    {{-- Table per hari --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100">
            <h3 class="font-semibold text-gray-800">
                📋 Detail Per Hari — {{ ['','Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'][$bulan] }} {{ $tahun }}
            </h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-asdp-900 text-white">
                    <tr>
                        <th class="px-4 py-3 text-left">Tanggal</th>
                        <th class="px-4 py-3 text-right">Total Trip</th>
                        <th class="px-4 py-3 text-right">Total Penumpang</th>
                        <th class="px-4 py-3 text-right">Total Pendapatan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($data['per_hari'] as $tgl => $hari)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 font-medium">{{ \Carbon\Carbon::parse($tgl)->isoFormat('dddd, D MMMM Y') }}</td>
                        <td class="px-4 py-3 text-right">{{ number_format($hari['trip']) }}</td>
                        <td class="px-4 py-3 text-right">{{ number_format($hari['penumpang']) }}</td>
                        <td class="px-4 py-3 text-right font-semibold">Rp {{ number_format($hari['pendapatan'], 0, ',', '.') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="px-4 py-10 text-center text-gray-400">Tidak ada data untuk periode ini</td></tr>
                    @endforelse
                </tbody>
                <tfoot class="bg-asdp-900 text-white">
                    <tr>
                        <td class="px-4 py-3 font-bold">TOTAL</td>
                        <td class="px-4 py-3 text-right font-bold">{{ number_format($data['total_trip']) }}</td>
                        <td class="px-4 py-3 text-right font-bold">{{ number_format($data['total_penumpang']) }}</td>
                        <td class="px-4 py-3 text-right font-bold">Rp {{ number_format($data['total_pendapatan'], 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const perHari = @json($data['per_hari']);
const labels = Object.keys(perHari).map(d => { const dd = new Date(d); return dd.getDate() + '/' + (dd.getMonth()+1); });
const pendapatan = Object.values(perHari).map(h => h.pendapatan);
new Chart(document.getElementById('chartBulanan').getContext('2d'), {
    type: 'line',
    data: {
        labels,
        datasets: [{
            label: 'Pendapatan (Rp)',
            data: pendapatan,
            borderColor: '#003087',
            backgroundColor: 'rgba(0,48,135,0.08)',
            pointBackgroundColor: '#003087',
            pointRadius: 3,
            tension: 0.4,
            fill: true,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            y: { ticks: { callback: v => 'Rp ' + (v/1000000).toFixed(1) + 'M', font: { size: 10 } }, grid: { color: '#f5f5f5' } },
            x: { ticks: { font: { size: 10 } }, grid: { display: false } }
        }
    }
});
</script>
@endpush
