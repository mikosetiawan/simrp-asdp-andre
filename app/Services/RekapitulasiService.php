<?php
namespace App\Services;

use App\Models\{ShiftOperasional, Tarif, TagihPelayaran};
use Illuminate\Support\Collection;

class RekapitulasiService
{
    /**
     * Hitung pendapatan tagih pelayaran berdasarkan tarif aktif
     */
    public function hitungPendapatanTagih(array $data, Tarif $tarif): array
    {
        $pnp = ($data['jml_pnp_ekb_d'] * $tarif->ekb_dewasa)
             + ($data['jml_pnp_ekb_l'] * $tarif->ekb_lansia)
             + ($data['jml_pnp_ekb_a'] * $tarif->ekb_anak);

        $knd = ($data['gol_i']    * $tarif->gol_i)
             + ($data['gol_ii']   * $tarif->gol_ii)
             + ($data['gol_iii']  * $tarif->gol_iii)
             + ($data['gol_iv_a'] * $tarif->gol_iv_a)
             + ($data['gol_iv_b'] * $tarif->gol_iv_b)
             + ($data['gol_v_a']  * $tarif->gol_v_a)
             + ($data['gol_v_b']  * $tarif->gol_v_b)
             + ($data['gol_vi_a'] * $tarif->gol_vi_a)
             + ($data['gol_vi_b'] * $tarif->gol_vi_b)
             + ($data['gol_vii']  * $tarif->gol_vii)
             + ($data['gol_viii'] * $tarif->gol_viii)
             + ($data['gol_ix']   * $tarif->gol_ix);

        return [
            'pendapatan_penumpang' => $pnp,
            'pendapatan_kendaraan' => $knd,
            'total_pendapatan'     => $pnp + $knd,
        ];
    }

    /**
     * Hitung pendapatan jasa sandar
     */
    public function hitungJasaSandar(int $jumlahTrip, float $tarifJsn, float $tarifEngker): array
    {
        $jsn    = $jumlahTrip * $tarifJsn;
        $engker = $jumlahTrip * $tarifEngker;
        return [
            'pendapatan_jsn'      => $jsn,
            'pendapatan_engker'   => $engker,
            'total_jasa_dermaga'  => $jsn + $engker,
        ];
    }

    /**
     * Rekap harian semua regu untuk tanggal tertentu
     */
    public function rekapHarian(string $tanggal, ?int $regu_id = null): array
    {
        $query = ShiftOperasional::with([
            'regu',
            'supervisi',
            'tripKapal.tagihPelayaran',
            'tripKapal.kapal',
            'tripKapal.dermaga',
            'jasaSandar.dermaga',
        ])->whereDate('tanggal', $tanggal);

        if ($regu_id) {
            $query->where('regu_id', $regu_id);
        }
        $shifts = $query->get();

        $totalPendapatan = 0;
        $totalTrip = 0;
        $totalPenumpang = 0;
        $totalKendaraan = 0;
        $perRegu = [];

        foreach ($shifts as $shift) {
            $pendShift = 0;
            $tripShift = 0;
            $pnpShift  = 0;
            $kndShift  = 0;

            foreach ($shift->tripKapal as $trip) {
                $tp = $trip->tagihPelayaran;
                if ($tp) {
                    $pendShift += $tp->total_pendapatan;
                    $pnpShift  += $tp->total_penumpang;
                    $kndShift  += $tp->total_kendaraan;
                }
                $tripShift += $trip->jumlah_trip;
            }

            $totalPendapatan += $pendShift;
            $totalTrip       += $tripShift;
            $totalPenumpang  += $pnpShift;
            $totalKendaraan  += $kndShift;

            $perRegu[] = [
                'shift'       => $shift,
                'pendapatan'  => $pendShift,
                'trip'        => $tripShift,
                'penumpang'   => $pnpShift,
                'kendaraan'   => $kndShift,
            ];
        }

        return [
            'tanggal'         => $tanggal,
            'total_pendapatan'=> $totalPendapatan,
            'total_trip'      => $totalTrip,
            'total_penumpang' => $totalPenumpang,
            'total_kendaraan' => $totalKendaraan,
            'per_regu'        => $perRegu,
            'shifts'          => $shifts,
            'regu_id'         => $regu_id,
        ];
    }

    /**
     * Rekap bulanan
     */
    public function rekapBulanan(int $bulan, int $tahun, ?int $regu_id = null): array
    {
        $query = ShiftOperasional::with([
            'regu',
            'tripKapal.tagihPelayaran',
            'jasaSandar',
        ])->whereMonth('tanggal', $bulan)
          ->whereYear('tanggal', $tahun);

        if ($regu_id) {
            $query->where('regu_id', $regu_id);
        }
        $shifts = $query->get();

        $perHari = [];
        foreach ($shifts as $shift) {
            $tgl = $shift->tanggal->format('Y-m-d');
            if (!isset($perHari[$tgl])) {
                $perHari[$tgl] = ['pendapatan' => 0, 'trip' => 0, 'penumpang' => 0];
            }
            foreach ($shift->tripKapal as $trip) {
                $tp = $trip->tagihPelayaran;
                if ($tp) {
                    $perHari[$tgl]['pendapatan'] += $tp->total_pendapatan;
                    $perHari[$tgl]['penumpang']  += $tp->total_penumpang;
                }
                $perHari[$tgl]['trip'] += $trip->jumlah_trip;
            }
        }

        ksort($perHari);

        return [
            'bulan'           => $bulan,
            'tahun'           => $tahun,
            'total_pendapatan'=> array_sum(array_column($perHari, 'pendapatan')),
            'total_trip'      => array_sum(array_column($perHari, 'trip')),
            'total_penumpang' => array_sum(array_column($perHari, 'penumpang')),
            'per_hari'        => $perHari,
            'regu_id'         => $regu_id,
        ];
    }

    /**
     * Rekap tahunan
     */
    public function rekapTahunan(int $tahun, ?int $regu_id = null): array
    {
        $query = ShiftOperasional::with([
            'regu',
            'tripKapal.tagihPelayaran',
            'jasaSandar',
        ])->whereYear('tanggal', $tahun);

        if ($regu_id) {
            $query->where('regu_id', $regu_id);
        }
        $shifts = $query->get();

        $perBulan = [];
        for ($i = 1; $i <= 12; $i++) {
            $perBulan[$i] = ['pendapatan' => 0, 'trip' => 0, 'penumpang' => 0];
        }

        foreach ($shifts as $shift) {
            $bln = (int)$shift->tanggal->format('m');
            foreach ($shift->tripKapal as $trip) {
                $tp = $trip->tagihPelayaran;
                if ($tp) {
                    $perBulan[$bln]['pendapatan'] += $tp->total_pendapatan;
                    $perBulan[$bln]['penumpang']  += $tp->total_penumpang;
                }
                $perBulan[$bln]['trip'] += $trip->jumlah_trip;
            }
        }

        return [
            'tahun'           => $tahun,
            'regu_id'         => $regu_id,
            'total_pendapatan'=> array_sum(array_column($perBulan, 'pendapatan')),
            'total_trip'      => array_sum(array_column($perBulan, 'trip')),
            'total_penumpang' => array_sum(array_column($perBulan, 'penumpang')),
            'per_bulan'       => $perBulan,
        ];
    }

    /**
     * Data per kapal dalam periode tertentu
     */
    public function rekapPerKapal(string $dari, string $sampai): Collection
    {
        return \App\Models\TripKapal::with(['kapal', 'tagihPelayaran', 'shift'])
            ->whereHas('shift', fn($q) => $q->whereBetween('tanggal', [$dari, $sampai]))
            ->get()
            ->groupBy('kapal_id')
            ->map(function ($trips) {
                $kapal = $trips->first()->kapal;
                return [
                    'kapal'      => $kapal,
                    'total_trip' => $trips->sum('jumlah_trip'),
                    'total_pnp'  => $trips->sum(fn($t) => $t->tagihPelayaran?->total_penumpang ?? 0),
                    'total_knd'  => $trips->sum(fn($t) => $t->tagihPelayaran?->total_kendaraan ?? 0),
                    'total_pend' => $trips->sum(fn($t) => $t->tagihPelayaran?->total_pendapatan ?? 0),
                ];
            });
    }

    /**
     * Hitung limpahan tiket
     */
    public function hitungLimpahan(int $terjual, int $r1, int $r2, int $r3): int
    {
        return max(0, $terjual - $r1 - $r2 - $r3);
    }
}
