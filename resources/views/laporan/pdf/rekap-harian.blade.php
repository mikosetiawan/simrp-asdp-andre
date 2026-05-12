<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<style>
  * { box-sizing: border-box; margin: 0; padding: 0; }
  body { font-family: 'DejaVu Sans', sans-serif; font-size: 10px; color: #1a1a1a; }
  .header { background: #003087; color: white; padding: 14px 20px; }
  .header h1 { font-size: 14px; font-weight: bold; }
  .header p  { font-size: 9px; opacity: 0.8; margin-top: 2px; }
  .meta { display: flex; gap: 30px; padding: 10px 20px; background: #f0f4ff; border-bottom: 2px solid #003087; }
  .meta-item label { font-size: 8px; color: #666; text-transform: uppercase; letter-spacing: 0.5px; display: block; }
  .meta-item span  { font-size: 11px; font-weight: bold; color: #003087; }
  .kpi { display: flex; gap: 0; padding: 10px 20px; background: #fff; border-bottom: 1px solid #e0e0e0; }
  .kpi-box { flex: 1; padding: 8px 12px; border-right: 1px solid #e0e0e0; }
  .kpi-box:last-child { border-right: none; }
  .kpi-box .label { font-size: 8px; color: #888; text-transform: uppercase; }
  .kpi-box .value { font-size: 13px; font-weight: bold; color: #003087; margin-top: 2px; }
  .content { padding: 14px 20px; }
  table { width: 100%; border-collapse: collapse; font-size: 9px; }
  thead tr { background: #003087; color: white; }
  thead th { padding: 7px 8px; text-align: left; font-weight: 600; }
  tbody tr { border-bottom: 1px solid #f0f0f0; }
  tbody tr:nth-child(even) { background: #f8f9fa; }
  tbody td { padding: 6px 8px; }
  tfoot tr { background: #003087; color: white; font-weight: bold; }
  tfoot td { padding: 7px 8px; }
  .td-right { text-align: right; }
  .footer { position: fixed; bottom: 0; left: 0; right: 0; border-top: 1px solid #003087; padding: 6px 20px; background: white; font-size: 8px; color: #888; display: flex; justify-content: space-between; }
  .badge { display: inline-block; padding: 2px 6px; border-radius: 3px; font-size: 8px; font-weight: bold; }
  .badge-approved { background: #d4edda; color: #155724; }
  .badge-submitted { background: #cce5ff; color: #004085; }
  .badge-draft { background: #fff3cd; color: #856404; }
</style>
</head>
<body>
<div class="header">
  <h1>PT. ASDP INDONESIA FERRY (PERSERO) — CABANG UTAMA MERAK</h1>
  <p>REKAPITULASI PENDAPATAN HARIAN — LINTAS MERAK–BAKAUHENI</p>
</div>
<div class="meta">
  <div class="meta-item"><label>Tanggal</label><span>{{ \Carbon\Carbon::parse($tanggal)->isoFormat('dddd, D MMMM Y') }}</span></div>
  <div class="meta-item"><label>Dicetak</label><span>{{ now()->format('d/m/Y H:i') }}</span></div>
</div>
<div class="kpi">
  <div class="kpi-box"><div class="label">Total Pendapatan</div><div class="value">Rp {{ number_format($total_pendapatan, 0, ',', '.') }}</div></div>
  <div class="kpi-box"><div class="label">Total Trip</div><div class="value">{{ $total_trip }} Trip</div></div>
  <div class="kpi-box"><div class="label">Total Penumpang</div><div class="value">{{ number_format($total_penumpang) }} Org</div></div>
  <div class="kpi-box"><div class="label">Total Kendaraan</div><div class="value">{{ number_format($total_kendaraan) }} Unit</div></div>
</div>
<div class="content">
  <table>
    <thead>
      <tr>
        <th>Regu</th><th>Shift</th><th>Jam</th><th>Supervisi</th>
        <th class="td-right">Trip</th><th class="td-right">Penumpang</th><th class="td-right">Kendaraan</th>
        <th class="td-right">Pend. Penumpang</th><th class="td-right">Pend. Kendaraan</th><th class="td-right">Total Pendapatan</th>
        <th>Status</th>
      </tr>
    </thead>
    <tbody>
      @foreach($per_regu as $item)
      @php $shift = $item['shift']; @endphp
      <tr>
        <td><strong>{{ $shift->regu->nama_regu ?? '-' }}</strong></td>
        <td>{{ $shift->nama_shift }}</td>
        <td>{{ substr($shift->jam_mulai,0,5) }}–{{ substr($shift->jam_selesai,0,5) }}</td>
        <td>{{ $shift->supervisi->name ?? '-' }}</td>
        <td class="td-right">{{ $item['trip'] }}</td>
        <td class="td-right">{{ number_format($item['penumpang']) }}</td>
        <td class="td-right">{{ number_format($item['kendaraan']) }}</td>
        <td class="td-right">Rp {{ number_format($shift->tripKapal->sum(fn($t) => $t->tagihPelayaran?->pendapatan_penumpang ?? 0), 0, ',', '.') }}</td>
        <td class="td-right">Rp {{ number_format($shift->tripKapal->sum(fn($t) => $t->tagihPelayaran?->pendapatan_kendaraan ?? 0), 0, ',', '.') }}</td>
        <td class="td-right"><strong>Rp {{ number_format($item['pendapatan'], 0, ',', '.') }}</strong></td>
        <td><span class="badge badge-{{ $shift->status }}">{{ strtoupper($shift->status) }}</span></td>
      </tr>
      @endforeach
    </tbody>
    <tfoot>
      <tr>
        <td colspan="4">TOTAL</td>
        <td class="td-right">{{ $total_trip }}</td>
        <td class="td-right">{{ number_format($total_penumpang) }}</td>
        <td class="td-right">{{ number_format($total_kendaraan) }}</td>
        <td colspan="2"></td>
        <td class="td-right">Rp {{ number_format($total_pendapatan, 0, ',', '.') }}</td>
        <td></td>
      </tr>
    </tfoot>
  </table>
</div>
<div class="footer">
  <span>SIMRP ASDP Merak — Dokumen ini dicetak otomatis oleh sistem</span>
  <span>Hal. 1</span>
</div>
</body>
</html>
