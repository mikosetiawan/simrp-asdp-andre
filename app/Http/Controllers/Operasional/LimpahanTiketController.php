<?php
namespace App\Http\Controllers\Operasional;

use App\Http\Controllers\Controller;
use App\Models\{LimpahanTiket, ShiftOperasional, Regu};
use App\Services\RekapitulasiService;
use Illuminate\Http\Request;

class LimpahanTiketController extends Controller
{
    public function __construct(private RekapitulasiService $rekap) {}

    public function create(ShiftOperasional $shift) {
        $jenisTiket = ['EKB-D','EKB-L','EKB-A','GOL-I','GOL-II','GOL-III','GOL-IVA','GOL-IVB','GOL-VA','GOL-VB','GOL-VIA','GOL-VIB','GOL-VII','GOL-VIII','GOL-IX'];
        $regu       = Regu::where('aktif', true)->get();
        $existing   = $shift->limpahanTiket->keyBy('jenis_tiket');
        return view('operasional.limpahan-tiket.form', compact('shift','jenisTiket','regu','existing'));
    }

    public function store(Request $request, ShiftOperasional $shift) {
        $request->validate(['data' => 'required|array']);
        foreach ($request->data as $jenis => $row) {
            if (!isset($row['terjual'])) continue;
            $r1  = (int)($row['tertagih_regu1'] ?? 0);
            $r2  = (int)($row['tertagih_regu2'] ?? 0);
            $r3  = (int)($row['tertagih_regu3'] ?? 0);
            $dilimpahkan = $this->rekap->hitungLimpahan((int)$row['terjual'], $r1, $r2, $r3);
            LimpahanTiket::updateOrCreate(
                ['shift_id' => $shift->id, 'jenis_tiket' => $jenis],
                ['terjual' => $row['terjual'], 'tertagih_regu1' => $r1, 'tertagih_regu2' => $r2, 'tertagih_regu3' => $r3, 'dilimpahkan' => $dilimpahkan, 'dilimpahkan_ke_regu_id' => $row['dilimpahkan_ke_regu_id'] ?? null, 'keterangan' => $row['keterangan'] ?? null]
            );
        }
        return redirect()->route('operasional.shift.show', $shift)->with('success', 'Data limpahan tiket berhasil disimpan.');
    }
}
