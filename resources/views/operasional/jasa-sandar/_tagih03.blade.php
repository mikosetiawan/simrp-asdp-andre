@php
    $exMap = $shift->jasaSandar->keyBy('dermaga_id');
    $rupiah = fn ($v) => ((float) $v) > 0 ? 'Rp. ' . number_format((float) $v, 0, ',', '.') : 'Rp. -';
@endphp

<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="px-5 py-4 border-b border-gray-100">
        <h3 class="font-semibold text-gray-800">⚓ Ringkasan Jasa Sandar (TAGIH03)</h3>
        <p class="text-xs text-gray-500 mt-1">Pengisian pada form <strong>Tagih Pelayaran (Tagih01)</strong> — layout per dermaga (bagian 5 engker &amp; 6 masa tambat).</p>
    </div>
    <div class="p-5 space-y-6">
        <div>
            <h4 class="text-sm font-bold text-gray-900 mb-2">5. Jasa Sandar dari engker</h4>
            <div class="overflow-x-auto rounded-xl border border-gray-100">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-gray-600">
                        <tr>
                            <th class="px-4 py-2.5 text-left">Sandar di dermaga</th>
                            <th class="px-4 py-2.5 text-center w-24">Call</th>
                            <th class="px-4 py-2.5 text-right w-36"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($dermaga as $d)
                            @php $ex = $exMap[$d->id] ?? null; @endphp
                            <tr>
                                <td class="px-4 py-2">- {{ strtoupper($d->nama_dermaga) }}</td>
                                <td class="px-4 py-2 text-center">{{ $ex?->call_sandar ?? 0 }}</td>
                                <td class="px-4 py-2 text-right tabular-nums">{{ $rupiah($ex?->pendapatan_engker ?? 0) }}</td>
                            </tr>
                        @endforeach
                        <tr class="bg-gray-50 font-semibold">
                            <td colspan="2" class="px-4 py-2">Total sandar engker</td>
                            <td class="px-4 py-2 text-right tabular-nums">{{ $rupiah($shift->jasaSandar->sum('pendapatan_engker')) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div>
            <h4 class="text-sm font-bold text-gray-900 mb-2">6. Jasa Sandar masa tambat</h4>
            <div class="overflow-x-auto rounded-xl border border-gray-100">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-gray-600">
                        <tr>
                            <th class="px-4 py-2.5 text-left">Dermaga</th>
                            <th class="px-4 py-2.5 text-center w-24">Call</th>
                            <th class="px-4 py-2.5 text-right w-36"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($dermaga as $d)
                            @php $ex = $exMap[$d->id] ?? null; @endphp
                            <tr>
                                <td class="px-4 py-2">- {{ strtoupper($d->nama_dermaga) }}</td>
                                <td class="px-4 py-2 text-center">{{ $ex?->jumlah_trip ?? 0 }}</td>
                                <td class="px-4 py-2 text-right tabular-nums">{{ $rupiah($ex?->pendapatan_jsn ?? 0) }}</td>
                            </tr>
                        @endforeach
                        <tr class="bg-gray-50 font-semibold">
                            <td colspan="2" class="px-4 py-2">Total sandar masa tambat</td>
                            <td class="px-4 py-2 text-right tabular-nums">{{ $rupiah($shift->jasaSandar->sum('pendapatan_jsn')) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
