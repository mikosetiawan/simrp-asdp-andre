{{-- Form TAGIH03 (layout awal): bagian 5 engker + bagian 6 masa tambat per dermaga — disisipkan di form Tagih01 --}}
@php
    $exMap = $jasaExisting ?? collect();
    $rupiah = fn ($v) => ((float) $v) > 0 ? 'Rp. ' . number_format((float) $v, 0, ',', '.') : 'Rp. -';
@endphp

<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden" id="jasa-sandar-embed-tagih">
    <div class="px-5 py-3 bg-violet-50 border-b border-violet-100">
        <h3 class="font-semibold text-violet-900">⚓ Jasa Sandar (TAGIH03)</h3>
        <p class="text-xs text-violet-800/80 mt-0.5">Data ini disimpan per <strong>shift &amp; dermaga</strong> (satu baris per dermaga). Rumus: Rp engker = Call × tarif engker; Rp tambat = Call masa tambat × tarif JSN.</p>
    </div>

    <div class="p-5 space-y-6">
        {{-- 5. Engker --}}
        <div>
            <h4 class="text-sm font-bold text-gray-900 mb-2">5.&nbsp;&nbsp;&nbsp;Jasa Sandar dari engker :</h4>
            <div class="overflow-x-auto rounded-xl border border-gray-100">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-gray-600">
                        <tr>
                            <th class="px-4 py-2.5 text-left font-semibold">&nbsp;&nbsp;&nbsp;&nbsp;Sandar di dermaga</th>
                            <th class="px-4 py-2.5 text-center font-semibold w-24">Call</th>
                            <th class="px-4 py-2.5 text-right font-semibold w-36"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($dermaga as $d)
                            @php $ex = $exMap[$d->id] ?? null; @endphp
                            <tr class="hover:bg-gray-50/80 jse-engker-row" data-tarif-engker="{{ (float) $d->tarif_engker_per_trip }}">
                                <td class="px-4 py-2.5 font-medium text-gray-800">
                                    &nbsp;&nbsp;&nbsp;&nbsp;- {{ strtoupper($d->nama_dermaga) }}
                                </td>
                                <td class="px-4 py-2.5 text-center">
                                    <input type="number" name="data[{{ $d->id }}][call_sandar]" min="0"
                                        value="{{ old("data.{$d->id}.call_sandar", $ex?->call_sandar ?? 0) }}"
                                        class="jse-input-call w-20 border border-gray-300 rounded-lg px-2 py-1.5 text-sm text-center focus:ring-2 focus:ring-asdp-500">
                                </td>
                                <td class="px-4 py-2.5 text-right font-medium text-gray-800 tabular-nums jse-rp-engker">{{ $rupiah(0) }}</td>
                            </tr>
                        @endforeach
                        <tr class="bg-gray-50/90 font-semibold border-t border-gray-200">
                            <td colspan="2" class="px-4 py-2.5">&nbsp;&nbsp;&nbsp;&nbsp;Total sandar engker</td>
                            <td class="px-4 py-2.5 text-right tabular-nums jse-total-engker">{{ $rupiah(0) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        {{-- 6. Masa tambat — call & keterangan per dermaga --}}
        <div>
            <h4 class="text-sm font-bold text-gray-900 mb-2">6.&nbsp;&nbsp;&nbsp;Jasa Sandar masa tambat :</h4>
            <div class="overflow-x-auto rounded-xl border border-gray-100">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-gray-600">
                        <tr>
                            <th class="px-4 py-2.5 text-left font-semibold">&nbsp;&nbsp;&nbsp;&nbsp;Dermaga</th>
                            <th class="px-4 py-2.5 text-center font-semibold w-24">Call</th>
                            <th class="px-4 py-2.5 text-right font-semibold w-36"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($dermaga as $d)
                            @php $ex = $exMap[$d->id] ?? null; @endphp
                            <tr class="hover:bg-gray-50/80 jse-tambat-row" data-tarif-jsn="{{ (float) $d->tarif_jsn_per_trip }}">
                                <td class="px-4 py-2.5 align-top">
                                    <div class="font-medium text-gray-800">&nbsp;&nbsp;&nbsp;&nbsp;- {{ strtoupper($d->nama_dermaga) }}</div>
                                    <input type="text" name="data[{{ $d->id }}][keterangan]"
                                        value="{{ old("data.{$d->id}.keterangan", $ex?->keterangan) }}"
                                        placeholder="Keterangan (opsional)"
                                        class="mt-1 ml-4 w-[min(100%,20rem)] border border-gray-200 rounded-lg px-2 py-1 text-xs text-gray-600 focus:ring-2 focus:ring-asdp-500">
                                </td>
                                <td class="px-4 py-2.5 text-center align-top">
                                    <input type="number" name="data[{{ $d->id }}][jumlah_trip]" min="0"
                                        value="{{ old("data.{$d->id}.jumlah_trip", $ex?->jumlah_trip ?? 0) }}"
                                        class="jse-input-trip w-20 border border-gray-300 rounded-lg px-2 py-1.5 text-sm text-center focus:ring-2 focus:ring-asdp-500">
                                </td>
                                <td class="px-4 py-2.5 text-right font-medium text-gray-800 tabular-nums align-top jse-rp-tambat">{{ $rupiah(0) }}</td>
                            </tr>
                        @endforeach
                        <tr class="bg-gray-50/90 font-semibold border-t border-gray-200">
                            <td colspan="2" class="px-4 py-2.5">&nbsp;&nbsp;&nbsp;&nbsp;Total sandar masa tambat</td>
                            <td class="px-4 py-2.5 text-right tabular-nums jse-total-tambat">{{ $rupiah(0) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
(function () {
    var root = document.getElementById('jasa-sandar-embed-tagih');
    if (!root) return;
    function fmt(n) { return n > 0 ? 'Rp. ' + Math.round(n).toLocaleString('id-ID') : 'Rp. -'; }
    function recalc() {
        var te = 0, tj = 0;
        root.querySelectorAll('.jse-engker-row').forEach(function (row) {
            var tarif = parseFloat(row.getAttribute('data-tarif-engker')) || 0;
            var inp = row.querySelector('.jse-input-call');
            var c = inp ? (parseInt(inp.value, 10) || 0) : 0;
            te += c * tarif;
            var rpCell = row.querySelector('.jse-rp-engker');
            if (rpCell) rpCell.textContent = fmt(c * tarif);
        });
        root.querySelectorAll('.jse-tambat-row').forEach(function (row) {
            var tarif = parseFloat(row.getAttribute('data-tarif-jsn')) || 0;
            var inp = row.querySelector('.jse-input-trip');
            var t = inp ? (parseInt(inp.value, 10) || 0) : 0;
            tj += t * tarif;
            var rpCell = row.querySelector('.jse-rp-tambat');
            if (rpCell) rpCell.textContent = fmt(t * tarif);
        });
        var tE = root.querySelector('.jse-total-engker');
        var tJ = root.querySelector('.jse-total-tambat');
        if (tE) tE.textContent = fmt(te);
        if (tJ) tJ.textContent = fmt(tj);
    }
    root.addEventListener('input', recalc);
    root.addEventListener('change', recalc);
    recalc();
})();
</script>
@endpush
