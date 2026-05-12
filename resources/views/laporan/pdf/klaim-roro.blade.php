<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<style>
  @media print {
    body { font-size: 9px; }
    table { page-break-inside: avoid; }
    .no-print { display: none; }
    @page { margin: 5mm 10mm; }
  }
  * { box-sizing: border-box; margin: 0; padding: 0; }
  body { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000; padding: 10px; max-width: 800px; margin: auto; }
  
  /* Helper Classes */
  .text-center { text-align: center; }
  .text-right { text-align: right; }
  .text-left { text-align: left; }
  .font-bold { font-weight: bold; }
  .uppercase { text-transform: uppercase; }
  .border-b { border-bottom: 1px solid #000; }
  .border-t { border-top: 1px solid #000; }
  .mb-1 { margin-bottom: 3px; }
  .mb-2 { margin-bottom: 5px; }
  .mb-3 { margin-bottom: 10px; }
  .mt-4 { margin-top: 15px; }
  
  /* Header Table */
  .header-table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
  .header-table td { border: 1px solid #000; padding: 3px; vertical-align: middle; }
  .header-logo { width: 150px; text-align: center; font-weight: bold; font-size: 13px; color: #003087; letter-spacing: 1px; }
  .header-title { font-size: 11px; font-weight: bold; text-align: center; text-transform: uppercase; letter-spacing: 1px; }
  .header-meta { font-size: 8px; line-height: 1.3; width: 220px; }
  .meta-row { display: flex; justify-content: space-between; }
  
  /* Main Content Styles */
  .intro-text { line-height: 1.3; margin-bottom: 10px; text-align: justify; font-size: 8px; }
  
  /* Data Table */
  .data-table { width: 100%; border-collapse: collapse; font-size: 8px; }
  .data-table th { border-bottom: 1px solid #000; border-top: 1px solid #000; padding: 3px 2px; text-align: left; }
  .data-table td { padding: 2px; }
  .col-jenis { width: 45%; }
  .col-tarif { width: 15%; text-align: right; }
  .col-qty { width: 15%; text-align: center; }
  .col-rp { width: 15%; text-align: right; }
  .col-ket { width: 10%; text-align: center; }
  
  .row-title { font-weight: bold; padding-top: 4px; }
  .row-item td { padding-left: 15px; }
  .row-subtotal { font-weight: bold; }
  .row-subtotal td { border-top: 1px solid #000; border-bottom: 1px solid #000; padding-top: 3px; padding-bottom: 3px; }
  
  .row-total-pendapatan td { font-weight: bold; font-size: 10px; padding: 4px 2px; }
  
  /* Operasional Realisasi */
  .realisasi-table { width: 100%; border-collapse: collapse; font-size: 8px; margin: 5px 0; text-align: center; }
  .realisasi-table th { font-weight: normal; padding: 2px; }
  .realisasi-table td { padding: 2px; border-bottom: 1px solid #ccc; font-weight: bold; }
  
  /* Nested Sandar */
  .sandar-table { width: 100%; font-size: 8px; border-collapse: collapse; margin-bottom: 2px; }
  .sandar-table td { padding: 1px 2px; }
  
  /* Signature */
  .ttd-container { width: 100%; margin-top: 20px; font-size: 9px; text-align: center; border-collapse: collapse; border: none; }
  .ttd-container td { border: none; vertical-align: top; width: 50%; padding: 0; }
  .ttd-box { width: 250px; margin: 0 auto; }
  .ttd-name { font-weight: bold; border-bottom: 1px solid #000; display: inline-block; padding-bottom: 2px; margin-bottom: 2px; margin-top: 35px; min-width: 200px; }
  
  .footnote { font-style: italic; font-size: 7px; text-align: center; margin-top: 15px; }
  .highlight-bg { background-color: #d1d5db !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
</style>
</head>
<body>

@php
    function rupiah($value) {
        return $value > 0 ? 'Rp. ' . number_format($value, 0, ',', '.') : 'Rp. -';
    }

    $trip = $shift->tripKapal->first();
    $tarif = $trip?->tagihPelayaran?->tarif;

    // Aggregate Penumpang
    $qty_dws = $shift->tripKapal->sum(fn($t) => $t->tagihPelayaran?->jml_pnp_ekb_d ?? 0);
    $qty_lansia = $shift->tripKapal->sum(fn($t) => $t->tagihPelayaran?->jml_pnp_ekb_l ?? 0);
    $qty_bayi = $shift->tripKapal->sum(fn($t) => $t->tagihPelayaran?->jml_pnp_ekb_a ?? 0);
    $tot_qty_pnp = $qty_dws + $qty_lansia + $qty_bayi;

    $rp_dws = $qty_dws * ($tarif->ekb_dewasa ?? 0);
    $rp_lansia = $qty_lansia * ($tarif->ekb_lansia ?? 0);
    $rp_bayi = $qty_bayi * ($tarif->ekb_anak ?? 0); // Assuming bayi maps to anak tariff or 0
    $tot_rp_pnp = $rp_dws + $rp_lansia + $rp_bayi;

    // Aggregate Kendaraan
    $qty_g1 = $shift->tripKapal->sum(fn($t) => $t->tagihPelayaran?->gol_i ?? 0);
    $qty_g2 = $shift->tripKapal->sum(fn($t) => $t->tagihPelayaran?->gol_ii ?? 0);
    $qty_g3 = $shift->tripKapal->sum(fn($t) => $t->tagihPelayaran?->gol_iii ?? 0);
    $qty_g4a = $shift->tripKapal->sum(fn($t) => $t->tagihPelayaran?->gol_iv_a ?? 0);
    $qty_g4b = $shift->tripKapal->sum(fn($t) => $t->tagihPelayaran?->gol_iv_b ?? 0);
    $qty_g5a = $shift->tripKapal->sum(fn($t) => $t->tagihPelayaran?->gol_v_a ?? 0);
    $qty_g5b = $shift->tripKapal->sum(fn($t) => $t->tagihPelayaran?->gol_v_b ?? 0);
    $qty_g6a = $shift->tripKapal->sum(fn($t) => $t->tagihPelayaran?->gol_vi_a ?? 0);
    $qty_g6b = $shift->tripKapal->sum(fn($t) => $t->tagihPelayaran?->gol_vi_b ?? 0);
    $qty_g7 = $shift->tripKapal->sum(fn($t) => $t->tagihPelayaran?->gol_vii ?? 0);
    $qty_g8 = $shift->tripKapal->sum(fn($t) => $t->tagihPelayaran?->gol_viii ?? 0);
    $qty_g9 = $shift->tripKapal->sum(fn($t) => $t->tagihPelayaran?->gol_ix ?? 0);
    
    $tot_qty_knd = $qty_g1+$qty_g2+$qty_g3+$qty_g4a+$qty_g4b+$qty_g5a+$qty_g5b+$qty_g6a+$qty_g6b+$qty_g7+$qty_g8+$qty_g9;

    $rp_g1 = $qty_g1 * ($tarif->gol_i ?? 0);
    $rp_g2 = $qty_g2 * ($tarif->gol_ii ?? 0);
    $rp_g3 = $qty_g3 * ($tarif->gol_iii ?? 0);
    $rp_g4a = $qty_g4a * ($tarif->gol_iv_a ?? 0);
    $rp_g4b = $qty_g4b * ($tarif->gol_iv_b ?? 0);
    $rp_g5a = $qty_g5a * ($tarif->gol_v_a ?? 0);
    $rp_g5b = $qty_g5b * ($tarif->gol_v_b ?? 0);
    $rp_g6a = $qty_g6a * ($tarif->gol_vi_a ?? 0);
    $rp_g6b = $qty_g6b * ($tarif->gol_vi_b ?? 0);
    $rp_g7 = $qty_g7 * ($tarif->gol_vii ?? 0);
    $rp_g8 = $qty_g8 * ($tarif->gol_viii ?? 0);
    $rp_g9 = $qty_g9 * ($tarif->gol_ix ?? 0);

    $tot_rp_knd = $rp_g1+$rp_g2+$rp_g3+$rp_g4a+$rp_g4b+$rp_g5a+$rp_g5b+$rp_g6a+$rp_g6b+$rp_g7+$rp_g8+$rp_g9;

    $jumlah_pendapatan = $tot_rp_pnp + $tot_rp_knd;
    $jasa_administrasi = 0; // Defaulting to 0 as per instructions "null value dianggap 0"
    $pendapatan_sebelum_sandar = $jumlah_pendapatan - $jasa_administrasi;

    // Jasa Sandar
    $engker_drgs = \App\Models\Dermaga::all(); // DRG I - VII ideally
    
    $total_engker = $shift->jasaSandar->sum('pendapatan_engker');
    $total_masa_tambat = $shift->jasaSandar->sum('pendapatan_jsn');
    $total_jasa_sandar = $total_engker + $total_masa_tambat;

    // Tambahan Biaya
    $tambat_kepil = 0; // assuming 0 if not tracked
    $cetak_manifest_lembar = 2;
    $cetak_manifest_tarif = 300;
    $cetak_manifest = $cetak_manifest_lembar * $cetak_manifest_tarif;
    $layanan_contact_center = 0;

    $subtotal = $total_jasa_sandar + $tambat_kepil + $cetak_manifest;
    $ppn = $subtotal * 0.11;
    $total_bayar = $subtotal + $ppn;

    $nama_kapal = $trip ? ($trip->kapal->nama_kapal ?? '-') : '-';
    $jam_b = $trip ? substr($trip->jam_berangkat, 0, 5) : '00:00';
    $nama_dermaga = $trip ? ($trip->dermaga->nama_dermaga ?? '-') : '-';
@endphp

<!-- HEADER -->
<table class="header-table">
    <tr>
        <td class="header-logo" style="width:20%">
            <img src="{{ public_path('images/asdp-ferry.png') }}" 
                 alt="ASDP" style="max-height: 40px; display: block; margin: 0 auto; object-fit: contain;">
        </td>
        <td class="header-title" style="width:50%">
            BERITA ACARA<br>
            <span style="font-weight: normal; display:inline-block; margin-top:5px;">PENDAPATAN KAPAL RO-RO</span>
        </td>
        <td class="header-meta" style="width:30%">
            <div class="meta-row"><span>No. Dokumen</span><span>: OPS-202.00.05B</span></div>
            <div class="meta-row"><span>Edisi</span><span>: 01</span></div>
            <div class="meta-row"><span>Revisi</span><span>: 00</span></div>
            <div class="meta-row"><span>Berlaku Efektif</span><span>: 01-11-2005</span></div>
            <div class="meta-row"><span>Halaman</span><span>: 1 dari 1</span></div>
        </td>
    </tr>
</table>

<!-- INTRO TEXT -->
<div class="intro-text">
    Pada hari ini 
    <strong>{{ \Carbon\Carbon::parse($shift->tanggal)->locale('id')->isoFormat('dddd') }}</strong> 
    Tanggal 
    <strong>{{ \Carbon\Carbon::parse($shift->tanggal)->locale('id')->isoFormat('D MMMM Y') }}</strong> 
    telah dilaksanakan penjualan Tiket Terpadu penumpang dan kendaraan untuk 
    <strong>{{ $nama_kapal }}</strong> 
    pada pukul: <strong>{{ $jam_b }} WIB</strong> 
    yang beroperasi di 
    <strong>{{ $nama_dermaga }} Merak</strong> 
    dengan rincian sebagai berikut:
</div>

<!-- DATA TABLE -->
<table class="data-table">
    <thead>
        <tr>
            <th class="col-jenis">JENIS KARCIS</th>
            <th class="col-tarif">TARIF<br>(Rp)</th>
            <th class="col-qty text-center">JUMLAH<br>(Lembar)</th>
            <th class="col-rp">JUMLAH<br>(Rp)</th>
            <th class="col-ket">KET</th>
        </tr>
    </thead>
    <tbody>
        <!-- 1. PENUMPANG -->
        <tr>
            <td class="row-title" colspan="5">1.&nbsp;&nbsp;&nbsp;&nbsp;PENUMPANG</td>
        </tr>
        <tr class="row-item">
            <td>f. Ekonomi B Dewasa</td>
            <td class="text-right">{{ number_format($tarif->ekb_dewasa ?? 0, 0, ',', '.') }}</td>
            <td class="text-center">{{ $qty_dws }}</td>
            <td class="text-right">{{ rupiah($rp_dws) }}</td>
            <td></td>
        </tr>
        <tr class="row-item">
            <td>g. Ekonomi B Lansia</td>
            <td class="text-right">{{ number_format($tarif->ekb_lansia ?? 0, 0, ',', '.') }}</td>
            <td class="text-center">{{ $qty_lansia }}</td>
            <td class="text-right">{{ rupiah($rp_lansia) }}</td>
            <td></td>
        </tr>
        <tr class="row-item">
            <td>h. Ekonomi B Bayi</td>
            <td class="text-right">{{ number_format($tarif->ekb_anak ?? 0, 0, ',', '.') }}</td>
            <td class="text-center">{{ $qty_bayi }}</td>
            <td class="text-right">{{ rupiah($rp_bayi) }}</td>
            <td></td>
        </tr>
        <tr class="row-subtotal">
            <td colspan="2">sub jumlah</td>
            <td class="text-center">{{ $tot_qty_pnp }}</td>
            <td class="text-right">{{ rupiah($tot_rp_pnp) }}</td>
            <td></td>
        </tr>

        <!-- 2. KENDARAAN -->
        <tr>
            <td class="row-title" colspan="5">2.&nbsp;&nbsp;&nbsp;&nbsp;KENDARAAN</td>
        </tr>
        <tr class="row-item">
            <td>a. Gol I</td>
            <td class="text-right">{{ number_format($tarif->gol_i ?? 0, 0, ',', '.') }}</td>
            <td class="text-center">{{ $qty_g1 }}</td>
            <td class="text-right">{{ rupiah($rp_g1) }}</td>
            <td></td>
        </tr>
        <tr class="row-item">
            <td>b. Gol II</td>
            <td class="text-right">{{ number_format($tarif->gol_ii ?? 0, 0, ',', '.') }}</td>
            <td class="text-center">{{ $qty_g2 }}</td>
            <td class="text-right">{{ rupiah($rp_g2) }}</td>
            <td></td>
        </tr>
        <tr class="row-item">
            <td>c. Gol III</td>
            <td class="text-right">{{ number_format($tarif->gol_iii ?? 0, 0, ',', '.') }}</td>
            <td class="text-center">{{ $qty_g3 }}</td>
            <td class="text-right">{{ rupiah($rp_g3) }}</td>
            <td></td>
        </tr>
        <tr class="row-item">
            <td>d. Gol IV A</td>
            <td class="text-right">{{ number_format($tarif->gol_iv_a ?? 0, 0, ',', '.') }}</td>
            <td class="text-center">{{ $qty_g4a }}</td>
            <td class="text-right">{{ rupiah($rp_g4a) }}</td>
            <td></td>
        </tr>
        <tr class="row-item">
            <td>e. Gol IV B</td>
            <td class="text-right">{{ number_format($tarif->gol_iv_b ?? 0, 0, ',', '.') }}</td>
            <td class="text-center">{{ $qty_g4b }}</td>
            <td class="text-right">{{ rupiah($rp_g4b) }}</td>
            <td></td>
        </tr>
        <tr class="row-item">
            <td>f. Gol V A</td>
            <td class="text-right">{{ number_format($tarif->gol_v_a ?? 0, 0, ',', '.') }}</td>
            <td class="text-center">{{ $qty_g5a }}</td>
            <td class="text-right">{{ rupiah($rp_g5a) }}</td>
            <td></td>
        </tr>
        <tr class="row-item">
            <td>g. Gol V B</td>
            <td class="text-right">{{ number_format($tarif->gol_v_b ?? 0, 0, ',', '.') }}</td>
            <td class="text-center">{{ $qty_g5b }}</td>
            <td class="text-right">{{ rupiah($rp_g5b) }}</td>
            <td></td>
        </tr>
        <tr class="row-item">
            <td>h. Gol VI A</td>
            <td class="text-right">{{ number_format($tarif->gol_vi_a ?? 0, 0, ',', '.') }}</td>
            <td class="text-center">{{ $qty_g6a }}</td>
            <td class="text-right">{{ rupiah($rp_g6a) }}</td>
            <td></td>
        </tr>
        <tr class="row-item">
            <td>i. Gol VI B</td>
            <td class="text-right">{{ number_format($tarif->gol_vi_b ?? 0, 0, ',', '.') }}</td>
            <td class="text-center">{{ $qty_g6b }}</td>
            <td class="text-right">{{ rupiah($rp_g6b) }}</td>
            <td></td>
        </tr>
        <tr class="row-item">
            <td>j. Gol VII</td>
            <td class="text-right">{{ number_format($tarif->gol_vii ?? 0, 0, ',', '.') }}</td>
            <td class="text-center">{{ $qty_g7 }}</td>
            <td class="text-right">{{ rupiah($rp_g7) }}</td>
            <td></td>
        </tr>
        <tr class="row-item">
            <td>k. Gol VIII</td>
            <td class="text-right">{{ number_format($tarif->gol_viii ?? 0, 0, ',', '.') }}</td>
            <td class="text-center">{{ $qty_g8 }}</td>
            <td class="text-right">{{ rupiah($rp_g8) }}</td>
            <td></td>
        </tr>
        <tr class="row-item">
            <td>l. Gol IX</td>
            <td class="text-right">{{ number_format($tarif->gol_ix ?? 0, 0, ',', '.') }}</td>
            <td class="text-center">{{ $qty_g9 }}</td>
            <td class="text-right">{{ rupiah($rp_g9) }}</td>
            <td></td>
        </tr>
        <tr class="row-subtotal">
            <td colspan="2">sub jumlah</td>
            <td class="text-center">{{ $tot_qty_knd }}</td>
            <td class="text-right">{{ rupiah($tot_rp_knd) }}</td>
            <td></td>
        </tr>

        <!-- JUMLAH PENDAPATAN -->
        <tr class="row-total-pendapatan">
            <td colspan="3">Jumlah Pendapatan</td>
            <td class="text-right">{{ rupiah($jumlah_pendapatan) }}</td>
            <td></td>
        </tr>

        <!-- 3. BIAYA OPERASIONAL -->
        <tr>
            <td class="row-title" colspan="5">3.&nbsp;&nbsp;&nbsp;&nbsp;Jasa Administrasi Operasional</td>
        </tr>
        <tr>
            <td colspan="3">&nbsp;&nbsp;&nbsp;&nbsp;Pendapatan KSO PT. ASDP (Persero) 10%</td>
            <td class="text-right border-b">{{ rupiah($jasa_administrasi) }}</td>
            <td></td>
        </tr>

        <!-- INFO OPERASIONAL -->
        <tr>
            <td class="row-title" colspan="5">4.</td>
        </tr>
        <tr>
            <td colspan="5">
                <table class="realisasi-table">
                    <tr>
                        <th width="33%">Realisasi tiba</th>
                        <th width="33%">Realisasi berangkat</th>
                        <th width="33%">Waktu sandar</th>
                    </tr>
                    <tr>
                        <th>WIB</th>
                        <th>WIB</th>
                        <th>WIB</th>
                    </tr>
                    <tr>
                        <td>{{ $trip ? substr($trip->jam_tiba, 0, 5) : '-' }}</td>
                        <td>{{ $jam_b }}</td>
                        <td>-</td>
                    </tr>
                </table>
                <table class="realisasi-table">
                    <tr>
                        <th width="33%">Jadwal tiba</th>
                        <th width="33%">Jadwal keberangkatan</th>
                        <th width="33%">Kelebihan dari jadwal berangkat</th>
                    </tr>
                    <tr>
                        <th>WIB</th>
                        <th>WIB</th>
                        <th>WIB</th>
                    </tr>
                    <tr>
                        <td>00:00</td>
                        <td>01:20</td>
                        <td>-</td>
                    </tr>
                </table>
            </td>
        </tr>

        <!-- 5. SANDAR ENGKER -->
        <tr>
            <td class="row-title" colspan="5">5.&nbsp;&nbsp;&nbsp;&nbsp;Jasa Sandar dari engker :</td>
        </tr>
        <tr>
            <td colspan="5">
                <table class="sandar-table">
                    <tr>
                        <td width="30%">&nbsp;&nbsp;&nbsp;&nbsp;Sandar di dermaga</td>
                        <td width="10%">Call</td>
                        <td></td>
                    </tr>
                    @foreach($engker_drgs as $drg)
                    @php
                        $js = $shift->jasaSandar->firstWhere('dermaga_id', $drg->id);
                        $callEnk = $js ? $js->call_sandar : 0;
                        $rpEnk = $js ? $js->pendapatan_engker : 0;
                    @endphp
                    <tr>
                        <td>&nbsp;&nbsp;&nbsp;&nbsp;- {{ strtoupper($drg->nama_dermaga) }}</td>
                        <td class="text-center">{{ $callEnk }}</td>
                        <td class="text-right pr-5">{{ rupiah($rpEnk) }}</td>
                    </tr>
                    @endforeach
                    <tr>
                        <td colspan="2" class="font-bold border-t border-b">&nbsp;&nbsp;&nbsp;&nbsp;Total sandar engker</td>
                        <td class="text-right font-bold border-t border-b">{{ rupiah($total_engker) }}</td>
                    </tr>
                </table>
            </td>
        </tr>

        <!-- 6. SANDAR TAMBAT -->
        <tr>
            <td class="row-title" colspan="5">6.&nbsp;&nbsp;&nbsp;&nbsp;Jasa Sandar masa tambat :</td>
        </tr>
        <tr>
            <td colspan="5">
                <table class="sandar-table">
                    <tr>
                        <td width="30%">&nbsp;&nbsp;&nbsp;&nbsp;Dermaga</td>
                        <td width="10%">Call</td>
                        <td></td>
                    </tr>
                    @foreach($engker_drgs as $drg)
                    @php
                        $js = $shift->jasaSandar->firstWhere('dermaga_id', $drg->id);
                        $callTambat = $js ? $js->jumlah_trip : 0;
                        $rpTambat = $js ? $js->pendapatan_jsn : 0;
                    @endphp
                    <tr>
                        <td>&nbsp;&nbsp;&nbsp;&nbsp;- {{ strtoupper($drg->nama_dermaga) }}</td>
                        <td class="text-center">{{ $callTambat }}</td>
                        <td class="text-right pr-5">{{ rupiah($rpTambat) }}</td>
                    </tr>
                    @endforeach
                    <tr>
                        <td colspan="2" class="font-bold border-t border-b">&nbsp;&nbsp;&nbsp;&nbsp;Total sandar masa tambat</td>
                        <td class="text-right font-bold border-t border-b">{{ rupiah($total_masa_tambat) }}</td>
                    </tr>
                </table>
            </td>
        </tr>

        <!-- TOTAL JASA SANDAR -->
        <tr class="row-subtotal">
            <td colspan="3">&nbsp;&nbsp;&nbsp;&nbsp;Total jasa sandar (5 + 6)</td>
            <td class="text-right">{{ rupiah($total_jasa_sandar) }}</td>
            <td></td>
        </tr>

        <!-- 7. TAMBAT KEPIL -->
        <tr>
            <td class="row-title" colspan="5">7.&nbsp;&nbsp;&nbsp;&nbsp;Jasa Tambat Kepil</td>
        </tr>
        <tr>
            <td colspan="5">
                <table class="sandar-table" style="margin-bottom:0">
                    <tr>
                        <td width="30%">&nbsp;&nbsp;&nbsp;&nbsp;Dermaga</td>
                        <td width="10%">TRIP</td>
                        <td width="20%">TARIF</td>
                        <td></td>
                    </tr>
                    @foreach($engker_drgs as $drg)
                    <tr>
                        <td>&nbsp;&nbsp;&nbsp;&nbsp;- {{ strtoupper($drg->nama_dermaga) }}</td>
                        <td class="text-center">0</td>
                        <td>Rp.</td>
                        <td class="text-right pr-5">Rp. -</td>
                    </tr>
                    @endforeach
                    <tr>
                        <td colspan="3" class="font-bold border-t border-b">&nbsp;&nbsp;&nbsp;&nbsp;Total Tambat Kepil</td>
                        <td class="text-right font-bold border-t border-b">{{ rupiah($tambat_kepil) }}</td>
                    </tr>
                </table>
            </td>
        </tr>

        <!-- 8. CETAK MANIFEST -->
        <tr>
            <td class="row-title" colspan="5">8.&nbsp;&nbsp;&nbsp;&nbsp;Cetak Adm. Tiket Manifest</td>
        </tr>
        <tr>
            <td colspan="5">
                <table class="sandar-table border-b" style="margin-bottom:0; padding-bottom:5px;">
                    <tr>
                        <td width="30%"></td>
                        <td width="20%" class="text-center"><span style="border-bottom:1px solid #000">LEMBAR (JML)</span></td>
                        <td width="20%">TARIF</td>
                        <td class="text-right font-bold pr-5">{{ rupiah($cetak_manifest) }}</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td class="text-center">{{ $cetak_manifest_lembar }}</td>
                        <td>Rp{{ $cetak_manifest_tarif }}</td>
                        <td></td>
                    </tr>
                </table>
            </td>
        </tr>

        <!-- 9. CONTACT CENTER -->
        <tr>
            <td class="row-title" colspan="5">9.&nbsp;&nbsp;&nbsp;&nbsp;Jasa Layanan Contact Center</td>
        </tr>
        <tr>
            <td colspan="5">
                <table class="sandar-table">
                    <tr>
                        <td width="30%">&nbsp;&nbsp;&nbsp;&nbsp;Dermaga</td>
                        <td width="10%">TRIP</td>
                        <td width="20%">TARIF</td>
                        <td></td>
                    </tr>
                    @foreach($engker_drgs as $drg)
                    <tr>
                        <td>&nbsp;&nbsp;&nbsp;&nbsp;- {{ strtoupper($drg->nama_dermaga) }}</td>
                        <td class="text-center">0</td>
                        <td>Rp80.000</td>
                        <td class="text-right pr-5">Rp. -</td>
                    </tr>
                    @endforeach
                    <tr>
                        <td colspan="3" class="font-bold border-t border-b">&nbsp;&nbsp;&nbsp;&nbsp;Total Layanan Contact Center</td>
                        <td class="text-right font-bold border-t border-b">{{ rupiah($layanan_contact_center) }}</td>
                    </tr>
                </table>
            </td>
        </tr>
        
        <!-- PENDAPATAN SEBELUM JASA SANDAR -->
        <tr>
            <td class="font-bold border-b border-t" style="padding-top:6px; padding-bottom:6px" colspan="3">10.&nbsp;&nbsp;&nbsp;&nbsp;Pendapatan Sebelum Jasa Sandar ( (1 + 2) - 3)</td>
            <td class="font-bold border-b border-t text-right">{{ rupiah($pendapatan_sebelum_sandar) }}</td>
            <td></td>
        </tr>
        
        <!-- FINAL HIGHLIGHT -->
        <tr class="highlight-bg">
            <td class="font-bold border-b" style="padding-top:6px; padding-bottom:6px" colspan="3">11.&nbsp;&nbsp;&nbsp;&nbsp;<i>Jumlah Jasa Sandar yang harus dibayarkan (6 + 7 + 8) + PPN 11%</i></td>
            <td class="font-bold border-b text-right">{{ rupiah($total_bayar) }}</td>
            <td></td>
        </tr>

    </tbody>
</table>

<!-- FOOTER AND SIGNATURES -->
<table class="ttd-container">
    <tr>
        <td>
            <div class="ttd-box">
                PETUGAS PELAYARAN :
                <div class="ttd-name">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
            </div>
        </td>
        <td>
            <div class="ttd-box">
                Merak, {{ \Carbon\Carbon::parse($shift->tanggal)->locale('id')->isoFormat('D MMMM Y') }}<br>
                SUPERVISI USAHA {{ strtoupper(($shift->regu->kode_regu ?? '').' — '.($shift->regu->nama_regu ?? '-')) }}
                
                <div class="ttd-name">{{ strtoupper($shift->supervisi->name ?? '________________________') }}</div>
                <div>NIK. {{ $shift->supervisi->nik ?? '________________' }}</div>
            </div>
        </td>
    </tr>
</table>

<div class="footnote">
    *Bukti klaim ini harap di periksa kembali sebelum meninggalkan GS*
</div>

</body>
</html>
