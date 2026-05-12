{{-- resources/views/laporan/pdf/rekap-bulanan.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<style>
  * { box-sizing: border-box; margin: 0; padding: 0; }
  body { font-family: 'DejaVu Sans', sans-serif; font-size: 10px; color: #1a1a1a; }
  .header { background: #003087; color: white; padding: 14px 20px; }
  .header h1 { font-size: 13px; font-weight: bold; }
  .header p  { font-size: 9px; opacity: 0.8; margin-top: 2px; }
  .meta { padding: 10px 20px; background: #f0f4ff; border-bottom: 2px solid #003087; display: flex; gap: 30px; }
  .meta-item label { font-size: 8px; color: #666; text-transform: uppercase; display: block; }
  .meta-item span  { font-size: 11px; font-weight: bold; color: #003087; }
  .content { padding: 14px 20px; }
  table { width: 100%; border-collapse: collapse; font-size: 9px; }
  thead tr { background: #003087; color: white; }
  thead th { padding: 7px 8px; text-align: left; font-weight: 600; }
  tbody tr:nth-child(even) { background: #f8f9fa; }
  tbody td { padding: 6px 8px; border-bottom: 1px solid #f0f0f0; }
  tfoot tr { background: #003087; color: white; font-weight: bold; }
  tfoot td { padding: 7px 8px; }
  .td-right { text-align: right; }
</style>
</head>
<body>
<div class="header">
  <h1>PT. ASDP INDONESIA FERRY (PERSERO) — CABANG UTAMA MERAK</h1>
  <p>REKAPITULASI PENDAPATAN BULANAN — LINTAS MERAK–BAKAUHENI</p>
</div>
<div class="meta">
  @php $namaBulan = ['','Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember']; @endphp
  <div class="meta-item"><label>Periode</label><span>{{ $namaBulan[$bulan] }} {{ $tahun }}</span></div>
  <div class="meta-item"><label>Total Pendapatan</label><span>Rp {{ number_format($total_pendapatan, 0, ',', '.') }}</span></div>
  <div class="meta-item"><label>Total Trip</label><span>{{ $total_trip }} Trip</span></div>
  <div class="meta-item"><label>Total Penumpang</label><span>{{ number_format($total_penumpang) }} Org</span></div>
  <div class="meta-item"><label>Dicetak</label><span>{{ now()->format('d/m/Y H:i') }}</span></div>
</div>
<div class="content">
  <table>
    <thead><tr><th>Tanggal</th><th class="td-right">Total Trip</th><th class="td-right">Total Penumpang</th><th class="td-right">Total Pendapatan (Rp)</th></tr></thead>
    <tbody>
      @foreach($per_hari as $tgl => $hari)
      <tr>
        <td>{{ \Carbon\Carbon::parse($tgl)->isoFormat('dddd, D MMMM Y') }}</td>
        <td class="td-right">{{ number_format($hari['trip']) }}</td>
        <td class="td-right">{{ number_format($hari['penumpang']) }}</td>
        <td class="td-right"><strong>Rp {{ number_format($hari['pendapatan'], 0, ',', '.') }}</strong></td>
      </tr>
      @endforeach
    </tbody>
    <tfoot>
      <tr>
        <td><strong>TOTAL {{ strtoupper($namaBulan[$bulan]) }} {{ $tahun }}</strong></td>
        <td class="td-right">{{ number_format($total_trip) }}</td>
        <td class="td-right">{{ number_format($total_penumpang) }}</td>
        <td class="td-right">Rp {{ number_format($total_pendapatan, 0, ',', '.') }}</td>
      </tr>
    </tfoot>
  </table>
</div>
</body>
</html>
