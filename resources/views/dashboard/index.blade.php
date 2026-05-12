@extends('layouts.app')
@section('title', 'Dashboard Eksekutif')

@section('content')
<div class="space-y-6">

    {{-- Date Filter --}}
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <p class="text-sm text-gray-500">Menampilkan data operasional untuk tanggal:</p>
        </div>
        <form method="GET" class="flex items-center gap-2">
            <input type="date" name="tanggal" value="{{ $tanggal }}"
                class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-asdp-500 focus:border-asdp-500">
            <button type="submit" class="bg-asdp-800 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-asdp-700 transition">
                Tampilkan
            </button>
        </form>
    </div>

    {{-- KPI Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Pendapatan Hari Ini</span>
                <span class="w-9 h-9 bg-blue-50 rounded-xl flex items-center justify-center text-lg">💰</span>
            </div>
            <div class="text-2xl font-bold text-gray-800">
                Rp {{ number_format($rekap['total_pendapatan'], 0, ',', '.') }}
            </div>
            <div class="text-xs text-gray-400 mt-1">{{ count($rekap['per_regu']) }} shift aktif</div>
            <div class="mt-3 h-1.5 bg-gray-100 rounded-full">
                <div class="h-1.5 bg-blue-500 rounded-full" style="width: 72%"></div>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Total Trip</span>
                <span class="w-9 h-9 bg-green-50 rounded-xl flex items-center justify-center text-lg">🚢</span>
            </div>
            <div class="text-2xl font-bold text-gray-800">{{ number_format($rekap['total_trip']) }} Trip</div>
            <div class="text-xs text-gray-400 mt-1">{{ $totalDermaga }} dermaga aktif</div>
            <div class="mt-3 h-1.5 bg-gray-100 rounded-full">
                <div class="h-1.5 bg-green-500 rounded-full" style="width: 60%"></div>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Total Penumpang</span>
                <span class="w-9 h-9 bg-yellow-50 rounded-xl flex items-center justify-center text-lg">👥</span>
            </div>
            <div class="text-2xl font-bold text-gray-800">{{ number_format($rekap['total_penumpang']) }} Org</div>
            <div class="text-xs text-gray-400 mt-1">EKB-D / L / A</div>
            <div class="mt-3 h-1.5 bg-gray-100 rounded-full">
                <div class="h-1.5 bg-yellow-400 rounded-full" style="width: 55%"></div>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Total Kendaraan</span>
                <span class="w-9 h-9 bg-orange-50 rounded-xl flex items-center justify-center text-lg">🚗</span>
            </div>
            <div class="text-2xl font-bold text-gray-800">{{ number_format($rekap['total_kendaraan']) }} Unit</div>
            <div class="text-xs text-gray-400 mt-1">Gol I – IX</div>
            <div class="mt-3 h-1.5 bg-gray-100 rounded-full">
                <div class="h-1.5 bg-orange-400 rounded-full" style="width: 45%"></div>
            </div>
        </div>
    </div>

    {{-- Charts + Top Kapal --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

        {{-- Pendapatan 7 Hari --}}
        <div class="lg:col-span-2 bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
            <div class="flex items-center justify-between mb-5">
                <h3 class="font-semibold text-gray-800">📈 Pendapatan 7 Hari Terakhir</h3>
            </div>
            <canvas id="chartPendapatan" height="90"></canvas>
        </div>

        {{-- Top Kapal --}}
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
            <h3 class="font-semibold text-gray-800 mb-4">🚢 Top Kapal Hari Ini</h3>
            @forelse($topKapal as $i => $tk)
            <div class="flex items-center gap-3 py-2 {{ !$loop->last ? 'border-b border-gray-50' : '' }}">
                <span class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold
                    {{ $i === 0 ? 'bg-yellow-100 text-yellow-700' : ($i === 1 ? 'bg-gray-100 text-gray-600' : 'bg-orange-50 text-orange-600') }}">
                    {{ $i + 1 }}
                </span>
                <div class="flex-1 min-w-0">
                    <div class="text-sm font-medium text-gray-800 truncate">{{ $tk->kapal->nama_kapal ?? '-' }}</div>
                    <div class="text-xs text-gray-400">{{ $tk->total_trip }} trip</div>
                </div>
            </div>
            @empty
            <p class="text-sm text-gray-400 text-center py-4">Belum ada data trip hari ini</p>
            @endforelse
        </div>
    </div>

    {{-- Shift Status + Per Regu --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

        {{-- Status Shift --}}
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
            <h3 class="font-semibold text-gray-800 mb-4">📋 Status Shift Hari Ini</h3>
            <div class="space-y-3">
                <div class="flex items-center justify-between p-3 bg-yellow-50 rounded-xl">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-yellow-400"></span>
                        <span class="text-sm font-medium text-yellow-800">Draft</span>
                    </div>
                    <span class="text-lg font-bold text-yellow-700">{{ $shiftDraft }}</span>
                </div>
                <div class="flex items-center justify-between p-3 bg-blue-50 rounded-xl">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-blue-400"></span>
                        <span class="text-sm font-medium text-blue-800">Submitted</span>
                    </div>
                    <span class="text-lg font-bold text-blue-700">{{ $shiftSubmitted }}</span>
                </div>
                <div class="flex items-center justify-between p-3 bg-green-50 rounded-xl">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-green-400"></span>
                        <span class="text-sm font-medium text-green-800">Approved</span>
                    </div>
                    <span class="text-lg font-bold text-green-700">{{ count($shiftsHariIni) - $shiftDraft - $shiftSubmitted }}</span>
                </div>
                <div class="pt-2 border-t border-gray-100">
                    <a href="{{ route('operasional.shift.index', ['tanggal' => $tanggal]) }}"
                       class="w-full block text-center text-sm text-asdp-700 font-medium hover:underline">
                        Lihat Semua Shift →
                    </a>
                </div>
            </div>
        </div>

        {{-- Rekap Per Regu --}}
        <div class="lg:col-span-2 bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
            <h3 class="font-semibold text-gray-800 mb-4">📊 Rekap Per Regu — {{ \Carbon\Carbon::parse($tanggal)->isoFormat('D MMMM Y') }}</h3>
            @if(count($rekap['per_regu']) > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-100">
                            <th class="text-left py-2 text-xs font-semibold text-gray-500 uppercase">Regu</th>
                            <th class="text-left py-2 text-xs font-semibold text-gray-500 uppercase">Shift</th>
                            <th class="text-right py-2 text-xs font-semibold text-gray-500 uppercase">Trip</th>
                            <th class="text-right py-2 text-xs font-semibold text-gray-500 uppercase">Penumpang</th>
                            <th class="text-right py-2 text-xs font-semibold text-gray-500 uppercase">Pendapatan</th>
                            <th class="text-center py-2 text-xs font-semibold text-gray-500 uppercase">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($rekap['per_regu'] as $item)
                        <tr class="hover:bg-gray-50">
                            <td class="py-3 font-medium text-gray-800">{{ $item['shift']->regu->nama_regu ?? '-' }}</td>
                            <td class="py-3 text-gray-600">{{ $item['shift']->nama_shift }}</td>
                            <td class="py-3 text-right font-medium">{{ $item['trip'] }}</td>
                            <td class="py-3 text-right">{{ number_format($item['penumpang']) }}</td>
                            <td class="py-3 text-right font-semibold text-gray-800">
                                Rp {{ number_format($item['pendapatan'], 0, ',', '.') }}
                            </td>
                            <td class="py-3 text-center">
                                @php $st = $item['shift']->status @endphp
                                <span class="px-2 py-1 rounded-full text-xs font-medium
                                    {{ $st === 'approved' ? 'bg-green-100 text-green-700' :
                                       ($st === 'submitted' ? 'bg-blue-100 text-blue-700' : 'bg-yellow-100 text-yellow-700') }}">
                                    {{ ucfirst($st) }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="border-t-2 border-gray-200 bg-gray-50">
                            <td colspan="2" class="py-3 font-bold text-gray-800">Total</td>
                            <td class="py-3 text-right font-bold">{{ $rekap['total_trip'] }}</td>
                            <td class="py-3 text-right font-bold">{{ number_format($rekap['total_penumpang']) }}</td>
                            <td class="py-3 text-right font-bold text-asdp-800">
                                Rp {{ number_format($rekap['total_pendapatan'], 0, ',', '.') }}
                            </td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            @else
            <div class="text-center py-10">
                <div class="text-4xl mb-3">📭</div>
                <p class="text-gray-500 text-sm">Belum ada data shift untuk tanggal ini</p>
                @can('shift.create')
                <a href="{{ route('operasional.shift.create') }}"
                   class="mt-3 inline-block bg-asdp-800 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-asdp-700">
                    + Buat Shift Baru
                </a>
                @endcan
            </div>
            @endif
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
const ctx = document.getElementById('chartPendapatan').getContext('2d');
const chartData = @json($chartData);
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: chartData.map(d => d.tanggal),
        datasets: [{
            label: 'Pendapatan (Rp)',
            data: chartData.map(d => d.pendapatan),
            backgroundColor: 'rgba(0, 48, 135, 0.15)',
            borderColor: '#003087',
            borderWidth: 2,
            borderRadius: 6,
            borderSkipped: false,
        },{
            label: 'Trip',
            data: chartData.map(d => d.trip),
            type: 'line',
            borderColor: '#00a3e0',
            backgroundColor: 'rgba(0,163,224,0.1)',
            pointBackgroundColor: '#00a3e0',
            pointRadius: 4,
            tension: 0.4,
            yAxisID: 'y2',
        }]
    },
    options: {
        responsive: true,
        interaction: { mode: 'index', intersect: false },
        plugins: { legend: { position: 'top', labels: { font: { size: 12 } } } },
        scales: {
            y:  { position: 'left',  ticks: { callback: v => 'Rp ' + (v/1000000).toFixed(1) + 'M', font: { size: 11 } }, grid: { color: '#f0f0f0' } },
            y2: { position: 'right', ticks: { font: { size: 11 } }, grid: { drawOnChartArea: false } },
            x:  { ticks: { font: { size: 11 } }, grid: { display: false } }
        }
    }
});
</script>
@endpush
