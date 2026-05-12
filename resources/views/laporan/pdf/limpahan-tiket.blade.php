<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<style>
  * { box-sizing: border-box; margin: 0; padding: 0; }
  body { font-family: 'DejaVu Sans', sans-serif; font-size: 8px; color: #1a1a1a; }
  .header { background: #003087; color: white; padding: 12px 16px; }
  .header h1 { font-size: 12px; font-weight: bold; }
  .header p  { font-size: 8px; opacity: 0.85; margin-top: 2px; }
  .meta { display: flex; gap: 24px; padding: 8px 16px; background: #f0f4ff; border-bottom: 2px solid #003087; flex-wrap: wrap; }
  .meta-item label { font-size: 7px; color: #666; text-transform: uppercase; letter-spacing: 0.5px; display: block; }
  .meta-item span  { font-size: 10px; font-weight: bold; color: #003087; }
  .content { padding: 10px 16px 24px; }
  .shift-block { page-break-inside: avoid; margin-bottom: 14px; }
  .shift-title { font-size: 9px; font-weight: bold; color: #003087; padding: 6px 8px; background: #e8eef9; border: 1px solid #c5d4eb; margin-bottom: 4px; }
  table { width: 100%; border-collapse: collapse; font-size: 7.5px; }
  thead tr { background: #003087; color: white; }
  thead th { padding: 5px 6px; text-align: left; font-weight: 600; border: 1px solid #002060; }
  thead th.td-right { text-align: right; }
  tbody td { padding: 4px 6px; border: 1px solid #ddd; vertical-align: top; }
  tbody tr:nth-child(even) { background: #f8f9fa; }
  tr.section td { background: #dce6f7; font-weight: bold; font-size: 7px; color: #003087; padding: 4px 8px; border: 1px solid #b8c9e6; }
  tr.subtotal td { background: #fff9e6; font-weight: bold; border: 1px solid #e6d9a8; }
  tr.grandtotal td { background: #003087; color: white; font-weight: bold; border: 1px solid #002060; }
  .td-right { text-align: right; }
  .jenis { font-weight: 600; color: #003087; }
  .footer { font-size: 7px; color: #666; margin-top: 8px; padding-top: 6px; border-top: 1px solid #ccc; }
</style>
</head>
<body>
<div class="header">
  <h1>PT. ASDP INDONESIA FERRY (PERSERO) — CABANG UTAMA MERAK</h1>
  <p>LAPORAN LIMPAHAN TIKET — PER GOLONGAN / JENIS TIKET</p>
</div>
<div class="meta">
  <div class="meta-item"><label>Tanggal</label><span>{{ \Carbon\Carbon::parse($tanggal)->locale('id')->isoFormat('dddd, D MMMM Y') }}</span></div>
  <div class="meta-item"><label>Regu</label><span>{{ $reguKeterangan }}</span></div>
  <div class="meta-item"><label>Dicetak</label><span>{{ now()->format('d/m/Y H:i') }}</span></div>
</div>
<div class="content">
@forelse($shifts as $shift)
@php
    $jenisPnp = array_values(array_filter($jenisTiket, fn ($j) => str_starts_with($j, 'EKB')));
    $jenisKnd = array_values(array_filter($jenisTiket, fn ($j) => str_starts_with($j, 'GOL')));
@endphp
<div class="shift-block">
  <div class="shift-title">
    {{ $shift->regu->kode_regu ?? '' }} — {{ $shift->regu->nama_regu ?? '—' }} |
    {{ $shift->nama_shift }} |
    {{ substr($shift->jam_mulai,0,5) }}–{{ substr($shift->jam_selesai,0,5) }} |
    Status: {{ strtoupper($shift->status) }}
  </div>
  <table>
    <thead>
      <tr>
        <th style="width:12%;">Jenis tiket</th>
        <th class="td-right">Terjual</th>
        <th class="td-right">Tertagih R1</th>
        <th class="td-right">Tertagih R2</th>
        <th class="td-right">Tertagih R3</th>
        <th class="td-right">Dilimpahkan</th>
        <th style="width:14%;">Limpah ke regu</th>
        <th style="width:22%;">Keterangan</th>
      </tr>
    </thead>
    <tbody>
      <tr class="section">
        <td colspan="8">A. PENUMPANG (EKB)</td>
      </tr>
      @php
        $sPnp = ['t'=>0,'r1'=>0,'r2'=>0,'r3'=>0,'d'=>0];
      @endphp
      @foreach($jenisPnp as $jenis)
      @php $row = $shift->limpahanTiket->firstWhere('jenis_tiket', $jenis); @endphp
      @php
        $t = (int)($row?->terjual ?? 0); $r1 = (int)($row?->tertagih_regu1 ?? 0);
        $r2 = (int)($row?->tertagih_regu2 ?? 0); $r3 = (int)($row?->tertagih_regu3 ?? 0); $d = (int)($row?->dilimpahkan ?? 0);
        $sPnp['t']+=$t; $sPnp['r1']+=$r1; $sPnp['r2']+=$r2; $sPnp['r3']+=$r3; $sPnp['d']+=$d;
      @endphp
      <tr>
        <td><span class="jenis">{{ $jenis }}</span></td>
        <td class="td-right">{{ number_format($t) }}</td>
        <td class="td-right">{{ number_format($r1) }}</td>
        <td class="td-right">{{ number_format($r2) }}</td>
        <td class="td-right">{{ number_format($r3) }}</td>
        <td class="td-right">{{ number_format($d) }}</td>
        <td>{{ $row?->dilimpahkanKeRegu?->nama_regu ?? '—' }}</td>
        <td>{{ $row?->keterangan ? \Illuminate\Support\Str::limit($row->keterangan, 80) : '—' }}</td>
      </tr>
      @endforeach
      <tr class="subtotal">
        <td>Subtotal penumpang</td>
        <td class="td-right">{{ number_format($sPnp['t']) }}</td>
        <td class="td-right">{{ number_format($sPnp['r1']) }}</td>
        <td class="td-right">{{ number_format($sPnp['r2']) }}</td>
        <td class="td-right">{{ number_format($sPnp['r3']) }}</td>
        <td class="td-right">{{ number_format($sPnp['d']) }}</td>
        <td colspan="2"></td>
      </tr>
      <tr class="section">
        <td colspan="8">B. KENDARAAN (PER GOLONGAN)</td>
      </tr>
      @php
        $sKnd = ['t'=>0,'r1'=>0,'r2'=>0,'r3'=>0,'d'=>0];
      @endphp
      @foreach($jenisKnd as $jenis)
      @php $row = $shift->limpahanTiket->firstWhere('jenis_tiket', $jenis); @endphp
      @php
        $t = (int)($row?->terjual ?? 0); $r1 = (int)($row?->tertagih_regu1 ?? 0);
        $r2 = (int)($row?->tertagih_regu2 ?? 0); $r3 = (int)($row?->tertagih_regu3 ?? 0); $d = (int)($row?->dilimpahkan ?? 0);
        $sKnd['t']+=$t; $sKnd['r1']+=$r1; $sKnd['r2']+=$r2; $sKnd['r3']+=$r3; $sKnd['d']+=$d;
      @endphp
      <tr>
        <td><span class="jenis">{{ $jenis }}</span></td>
        <td class="td-right">{{ number_format($t) }}</td>
        <td class="td-right">{{ number_format($r1) }}</td>
        <td class="td-right">{{ number_format($r2) }}</td>
        <td class="td-right">{{ number_format($r3) }}</td>
        <td class="td-right">{{ number_format($d) }}</td>
        <td>{{ $row?->dilimpahkanKeRegu?->nama_regu ?? '—' }}</td>
        <td>{{ $row?->keterangan ? \Illuminate\Support\Str::limit($row->keterangan, 80) : '—' }}</td>
      </tr>
      @endforeach
      <tr class="subtotal">
        <td>Subtotal kendaraan</td>
        <td class="td-right">{{ number_format($sKnd['t']) }}</td>
        <td class="td-right">{{ number_format($sKnd['r1']) }}</td>
        <td class="td-right">{{ number_format($sKnd['r2']) }}</td>
        <td class="td-right">{{ number_format($sKnd['r3']) }}</td>
        <td class="td-right">{{ number_format($sKnd['d']) }}</td>
        <td colspan="2"></td>
      </tr>
      <tr class="grandtotal">
        <td>TOTAL (penumpang + kendaraan)</td>
        <td class="td-right">{{ number_format($sPnp['t'] + $sKnd['t']) }}</td>
        <td class="td-right">{{ number_format($sPnp['r1'] + $sKnd['r1']) }}</td>
        <td class="td-right">{{ number_format($sPnp['r2'] + $sKnd['r2']) }}</td>
        <td class="td-right">{{ number_format($sPnp['r3'] + $sKnd['r3']) }}</td>
        <td class="td-right">{{ number_format($sPnp['d'] + $sKnd['d']) }}</td>
        <td colspan="2">Limpahan = Terjual − (R1+R2+R3)</td>
      </tr>
    </tbody>
  </table>
</div>
@empty
<p style="padding:16px; color:#666;">Tidak ada data shift untuk filter ini.</p>
@endforelse
</div>
<p class="footer">Sumber data: input limpahan tiket per shift. Golongan penumpang (EKB) dan kendaraan (GOL) dipisahkan sesuai jenis tiket.</p>
</body>
</html>
