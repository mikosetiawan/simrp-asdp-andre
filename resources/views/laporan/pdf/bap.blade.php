<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<style>
  * { box-sizing: border-box; margin: 0; padding: 0; }
  body { font-family: 'DejaVu Sans', sans-serif; font-size: 10px; color: #1a1a1a; padding: 20px; }
  .kop { text-align: center; border-bottom: 3px double #003087; padding-bottom: 10px; margin-bottom: 16px; }
  .kop h1 { font-size: 13px; font-weight: bold; text-transform: uppercase; }
  .kop p  { font-size: 9px; color: #444; margin-top: 3px; }
  .judul { text-align: center; font-size: 13px; font-weight: bold; text-transform: uppercase; margin: 12px 0 6px; letter-spacing: 1px; }
  .no-bap { text-align: center; font-size: 9px; color: #666; margin-bottom: 16px; }
  .info-table { width: 100%; margin-bottom: 16px; font-size: 10px; }
  .info-table td { padding: 3px 4px; }
  .info-table .label { font-weight: bold; width: 180px; }
  .info-table .sep { width: 10px; text-align: center; }
  table { width: 100%; border-collapse: collapse; font-size: 9px; margin-bottom: 16px; }
  th { background: #003087; color: white; padding: 6px 7px; }
  td { padding: 5px 7px; border: 1px solid #ddd; }
  .td-right { text-align: right; }
  .td-center { text-align: center; }
  tfoot td { background: #e8edf5; font-weight: bold; }
  .ttd { margin-top: 30px; display: flex; justify-content: space-between; }
  .ttd-box { text-align: center; width: 45%; font-size: 10px; }
  .ttd-line { border-top: 1px solid #333; margin-top: 45px; padding-top: 4px; }
</style>
</head>
<body>
<div class="kop">
  <h1>PT. ASDP Indonesia Ferry (Persero) — Cabang Utama Merak</h1>
  <p>Jl. Pelabuhan Merak, Kota Cilegon, Banten</p>
</div>

<div class="judul">BERITA ACARA PENYERAHAN (BAP)</div>
<div class="judul" style="font-size:11px;">KARTU / TIKET ELEKTRONIK TERKLAIM</div>
<div class="no-bap">No. BAP: BAP-{{ str_pad($shift->id, 5, '0', STR_PAD_LEFT) }}/ASDP/MRK/{{ $shift->tanggal->format('m/Y') }}</div>

<table class="info-table">
  <tr><td class="label">Regu</td><td class="sep">:</td><td>{{ $shift->regu->nama_regu ?? '-' }}</td></tr>
  <tr><td class="label">Tanggal Shift</td><td class="sep">:</td><td>{{ $shift->tanggal->isoFormat('D MMMM Y') }}</td></tr>
  <tr><td class="label">Shift</td><td class="sep">:</td><td>{{ $shift->nama_shift }} ({{ substr($shift->jam_mulai,0,5) }}–{{ substr($shift->jam_selesai,0,5) }})</td></tr>
  <tr><td class="label">Tgl Awal Dinas</td><td class="sep">:</td><td>{{ $shift->tanggal_awal_dinas?->isoFormat('D MMMM Y') ?? '-' }}</td></tr>
  <tr><td class="label">Tgl Akhir Dinas</td><td class="sep">:</td><td>{{ $shift->tanggal_akhir_dinas?->isoFormat('D MMMM Y') ?? '-' }}</td></tr>
  <tr><td class="label">Supervisi Usaha</td><td class="sep">:</td><td>{{ $shift->supervisi->name ?? '-' }}</td></tr>
  <tr><td class="label">Kolektor Tiket</td><td class="sep">:</td><td>{{ $shift->kolektor->name ?? '-' }}</td></tr>
  <tr><td class="label">Jam Penyerahan</td><td class="sep">:</td><td>{{ now()->format('H:i') }} WIB</td></tr>
</table>

<p style="margin-bottom:10px; font-size:10px;">Dengan ini kami menyerahkan tiket/kartu elektronik terklaim kepada bagian Keuangan Gedung Centra (GS) dengan rincian sebagai berikut:</p>

<table>
  <thead>
    <tr>
      <th class="td-center">No</th>
      <th>Kapal</th>
      <th class="td-right">EKB-D</th>
      <th class="td-right">EKB-L</th>
      <th class="td-right">EKB-A</th>
      <th class="td-right">Gol I-III</th>
      <th class="td-right">Gol IV-VI</th>
      <th class="td-right">Gol VII-IX</th>
      <th class="td-right">Total Pnp</th>
      <th class="td-right">Total Knd</th>
      <th class="td-right">Total Pendapatan</th>
    </tr>
  </thead>
  <tbody>
    @php $totPnd = 0; $totPnp = 0; $totKnd = 0; @endphp
    @foreach($shift->tripKapal as $i => $trip)
    @php
      $tp = $trip->tagihPelayaran;
      $totPnd += $tp?->total_pendapatan ?? 0;
      $totPnp += $tp?->total_penumpang ?? 0;
      $totKnd += $tp?->total_kendaraan ?? 0;
      $golKecil = ($tp?->gol_i??0)+($tp?->gol_ii??0)+($tp?->gol_iii??0);
      $golMenengah = ($tp?->gol_iv_a??0)+($tp?->gol_iv_b??0)+($tp?->gol_v_a??0)+($tp?->gol_v_b??0)+($tp?->gol_vi_a??0)+($tp?->gol_vi_b??0);
      $golBesar = ($tp?->gol_vii??0)+($tp?->gol_viii??0)+($tp?->gol_ix??0);
    @endphp
    <tr>
      <td class="td-center">{{ $i+1 }}</td>
      <td>{{ $trip->kapal->nama_kapal ?? '-' }}</td>
      <td class="td-right">{{ $tp?->jml_pnp_ekb_d ?? 0 }}</td>
      <td class="td-right">{{ $tp?->jml_pnp_ekb_l ?? 0 }}</td>
      <td class="td-right">{{ $tp?->jml_pnp_ekb_a ?? 0 }}</td>
      <td class="td-right">{{ $golKecil }}</td>
      <td class="td-right">{{ $golMenengah }}</td>
      <td class="td-right">{{ $golBesar }}</td>
      <td class="td-right">{{ number_format($tp?->total_penumpang ?? 0) }}</td>
      <td class="td-right">{{ number_format($tp?->total_kendaraan ?? 0) }}</td>
      <td class="td-right"><strong>Rp {{ number_format($tp?->total_pendapatan ?? 0, 0, ',', '.') }}</strong></td>
    </tr>
    @endforeach
  </tbody>
  <tfoot>
    <tr>
      <td colspan="8" class="td-right"><strong>TOTAL</strong></td>
      <td class="td-right"><strong>{{ number_format($totPnp) }}</strong></td>
      <td class="td-right"><strong>{{ number_format($totKnd) }}</strong></td>
      <td class="td-right"><strong>Rp {{ number_format($totPnd, 0, ',', '.') }}</strong></td>
    </tr>
  </tfoot>
</table>

<div class="ttd">
  <div class="ttd-box">
    <div>Yang Menyerahkan,</div>
    <div>Supervisi Usaha</div>
    <div class="ttd-line">( {{ $shift->supervisi->name ?? '____________________' }} )</div>
    <div style="font-size:9px; color:#666;">NIK: {{ $shift->supervisi->nik ?? '-' }}</div>
  </div>
  <div class="ttd-box">
    <div>Yang Menerima,</div>
    <div>Bagian Keuangan GS</div>
    <div class="ttd-line">( _________________________ )</div>
    <div style="font-size:9px; color:#666;">Tanggal: {{ now()->isoFormat('D MMMM Y') }}</div>
  </div>
</div>
</body>
</html>
