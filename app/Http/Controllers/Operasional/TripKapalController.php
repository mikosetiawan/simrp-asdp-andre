<?php
namespace App\Http\Controllers\Operasional;

use App\Http\Controllers\Controller;
use App\Models\{TripKapal, ShiftOperasional, Kapal, Dermaga};
use Illuminate\Http\Request;

class TripKapalController extends Controller
{
    public function create(ShiftOperasional $shift) {
        $kapal   = Kapal::aktif()->orderBy('nama_kapal')->get();
        $dermaga = Dermaga::where('aktif', true)->orderBy('kode_dermaga')->get();
        return view('operasional.trip-kapal.form', compact('shift','kapal','dermaga'));
    }

    public function store(Request $request, ShiftOperasional $shift) {
        $v = $request->validate([
            'kapal_id'            => 'required|exists:kapal,id',
            'kapal_pengganti_id'  => 'nullable|exists:kapal,id|different:kapal_id',
            'dermaga_id'          => 'required|exists:dermaga,id',
            'jumlah_trip'         => 'required|integer|min:1',
            'trip_ke'             => 'required|integer|min:1',
            'jam_berangkat'       => 'nullable|date_format:H:i',
            'jam_tiba'            => 'nullable|date_format:H:i',
            'keterangan'          => 'nullable|string',
        ]);
        $v['shift_id'] = $shift->id;
        $trip = TripKapal::create($v);
        return redirect()->route('operasional.tagih-pelayaran.create', $trip)
            ->with('success', 'Trip kapal berhasil disimpan. Silakan input data tagih pelayaran.');
    }

    public function edit(TripKapal $tripKapal) {
        $kapal   = Kapal::aktif()->orderBy('nama_kapal')->get();
        $dermaga = Dermaga::where('aktif', true)->orderBy('kode_dermaga')->get();
        $shift   = $tripKapal->shift;
        return view('operasional.trip-kapal.form', compact('tripKapal','kapal','dermaga','shift'));
    }

    public function update(Request $request, TripKapal $tripKapal) {
        $v = $request->validate([
            'kapal_id'            => 'required|exists:kapal,id',
            'kapal_pengganti_id'  => 'nullable|exists:kapal,id|different:kapal_id',
            'dermaga_id'          => 'required|exists:dermaga,id',
            'jumlah_trip'         => 'required|integer|min:1',
            'trip_ke'             => 'required|integer|min:1',
            'jam_berangkat'       => 'nullable|date_format:H:i',
            'jam_tiba'            => 'nullable|date_format:H:i',
            'keterangan'          => 'nullable|string',
        ]);
        $tripKapal->update($v);
        return redirect()->route('operasional.shift.show', $tripKapal->shift_id)->with('success', 'Trip kapal berhasil diperbarui.');
    }

    public function destroy(TripKapal $tripKapal) {
        $shiftId = $tripKapal->shift_id;
        $tripKapal->delete();
        return redirect()->route('operasional.shift.show', $shiftId)->with('success', 'Trip kapal berhasil dihapus.');
    }
}
