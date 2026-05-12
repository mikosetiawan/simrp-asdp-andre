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
  thead th { padding: 5px 4px; text-align: left; font-weight: 600; border: 1px solid #002060; }
  thead th.td-right { text-align: right; }
  tbody td { padding: 4px 4px; border: 1px solid #ddd; }
  tbody tr:nth-child(even) { background: #f8f9fa; }
  tr.section td { background: #dce6f7; font-weight: bold; font-size: 7px; color: #003087; padding: 4px 6px; border: 1px solid #b8c9e6; }
  tr.ringkasan td { background: #fff9e6; font-weight: bold; border: 1px solid #e6d9a8; }
  tr.total-shift td { background: #003087; color: white; font-weight: bold; border: 1px solid #002060; }
  .td-right { text-align: right; }
  .footer { font-size: 7px; color: #666; margin-top: 8px; padding-top: 6px; border-top: 1px solid #ccc; }
</style>
</head>
<body>
<div class="header">
  <h1>PT. ASDP INDONESIA FERRY (PERSERO) — CABANG UTAMA MERAK</h1>
  <p>LAPORAN PENJUALAN TIKET (JUAL01) — REKAP PER GOLONGAN / JENIS TIKET</p>
</div>
<div class="meta">
  <div class="meta-item"><label>Tanggal</label><span>{{ \Carbon\Carbon::parse($tanggal)->locale('id')->isoFormat('dddd, D MMMM Y') }}</span></div>
  <div class="meta-item"><label>Regu</label><span>{{ $reguKeterangan }}</span></div>
  <div class="meta-item"><label>Dicetak</label><span>{{ now()->format('d/m/Y H:i') }}</span></div>
</div>
<div class="content">
@php
    $golonganBaris = [
        ['field' => 'pnp_ekb_d', 'label' => 'EKB-D (Dewasa)', 'bagian' => 'penumpang'],
        ['field' => 'pnp_ekb_l', 'label' => 'EKB-L (Lansia)', 'bagian' => 'penumpang'],
        ['field' => 'pnp_ekb_a', 'label' => 'EKB-A (Anak)', 'bagian' => 'penumpang'],
        ['field' => 'knd_gol_i', 'label' => 'Kend. Golongan I', 'bagian' => 'kendaraan'],
        ['field' => 'knd_gol_ii', 'label' => 'Kend. Golongan II', 'bagian' => 'kendaraan'],
        ['field' => 'knd_gol_iii', 'label' => 'Kend. Golongan III', 'bagian' => 'kendaraan'],
        ['field' => 'knd_gol_iv_a', 'label' => 'Kend. Golongan IV-A', 'bagian' => 'kendaraan'],
        ['field' => 'knd_gol_iv_b', 'label' => 'Kend. Golongan IV-B', 'bagian' => 'kendaraan'],
        ['field' => 'knd_gol_v_a', 'label' => 'Kend. Golongan V-A', 'bagian' => 'kendaraan'],
        ['field' => 'knd_gol_v_b', 'label' => 'Kend. Golongan V-B', 'bagian' => 'kendaraan'],
        ['field' => 'knd_gol_vi_a', 'label' => 'Kend. Golongan VI-A', 'bagian' => 'kendaraan'],
        ['field' => 'knd_gol_vi_b', 'label' => 'Kend. Golongan VI-B', 'bagian' => 'kendaraan'],
        ['field' => 'knd_gol_vii', 'label' => 'Kend. Golongan VII', 'bagian' => 'kendaraan'],
        ['field' => 'knd_gol_viii', 'label' => 'Kend. Golongan VIII', 'bagian' => 'kendaraan'],
        ['field' => 'knd_gol_ix', 'label' => 'Kend. Golongan IX', 'bagian' => 'kendaraan'],
    ];
    $kndFields = array_column(array_filter($golonganBaris, fn ($r) => $r['bagian'] === 'kendaraan'), 'field');
@endphp

@forelse($shifts as $shift)
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
        <th style="min-width: 11%;">Golongan / jenis</th>
        @foreach($posList as $pos)
        <th class="td-right" style="max-width: 6%;">{{ \Illuminate\Support\Str::limit($pos, 14) }}</th>
        @endforeach
        <th class="td-right">Jumlah</th>
      </tr>
    </thead>
    <tbody>
      @php $prevBagian = null; @endphp
      @foreach($golonganBaris as $baris)
      @if($baris['bagian'] !== $prevBagian)
      <tr class="section">
        <td colspan="{{ count($posList) + 2 }}">
          {{ $baris['bagian'] === 'penumpang' ? 'A. PENUMPANG (EKB)' : 'B. KENDARAAN (PER GOLONGAN)' }}
        </td>
      </tr>
      @php $prevBagian = $baris['bagian']; @endphp
      @endif
      <tr>
        <td>{{ $baris['label'] }}</td>
        @php $barisJumlah = 0; @endphp
        @foreach($posList as $pos)
        @php
          $p = $shift->penjualanTiket->firstWhere('pos_penjualan', $pos);
          $v = (int) ($p?->{$baris['field']} ?? 0);
          $barisJumlah += $v;
        @endphp
        <td class="td-right">{{ number_format($v) }}</td>
        @endforeach
        <td class="td-right"><strong>{{ number_format($barisJumlah) }}</strong></td>
      </tr>
      @endforeach
      @php
        $pendapatanPos = [];
        $totalPnpPos = [];
        $totalKndPos = [];
        foreach ($posList as $pos) {
            $p = $shift->penjualanTiket->firstWhere('pos_penjualan', $pos);
            $pendapatanPos[$pos] = $p ? (int) ($p->total_pendapatan_penjualan ?? 0) : 0;
            $totalPnpPos[$pos] = $p ? $p->total_pnp : 0;
            $totalKndPos[$pos] = $p ? collect($kndFields)->sum(fn ($f) => (int) ($p->$f ?? 0)) : 0;
        }
        $sumPend = array_sum($pendapatanPos);
        $sumPnp = array_sum($totalPnpPos);
        $sumKnd = array_sum($totalKndPos);
      @endphp
      <tr class="ringkasan">
        <td>Jumlah penumpang (org) per pos</td>
        @foreach($posList as $pos)
        <td class="td-right">{{ number_format($totalPnpPos[$pos] ?? 0) }}</td>
        @endforeach
        <td class="td-right">{{ number_format($sumPnp) }}</td>
      </tr>
      <tr class="ringkasan">
        <td>Jumlah kendaraan (unit) per pos</td>
        @foreach($posList as $pos)
        <td class="td-right">{{ number_format($totalKndPos[$pos] ?? 0) }}</td>
        @endforeach
        <td class="td-right">{{ number_format($sumKnd) }}</td>
      </tr>
      <tr class="total-shift">
        <td>Pendapatan penjualan (Rp)</td>
        @foreach($posList as $pos)
        <td class="td-right">Rp {{ number_format($pendapatanPos[$pos] ?? 0, 0, ',', '.') }}</td>
        @endforeach
        <td class="td-right">Rp {{ number_format($sumPend, 0, ',', '.') }}</td>
      </tr>
    </tbody>
  </table>
</div>
@empty
<p style="padding:16px; color:#666;">Tidak ada data shift untuk filter ini.</p>
@endforelse
</div>
<p class="footer">Sumber data: input JUAL01 per pos penjualan. Kolom golongan adalah jumlah lembar/penumpang/unit terjual per pos.</p>
</body>
</html>
