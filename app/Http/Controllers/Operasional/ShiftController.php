<?php
namespace App\Http\Controllers\Operasional;

use App\Http\Controllers\Controller;
use App\Models\{ShiftOperasional, Regu, User};
use Illuminate\Http\Request;

class ShiftController extends Controller
{
    public function index(Request $request) {
        $tanggal = $request->get('tanggal', today()->format('Y-m-d'));
        $status  = $request->get('status');

        $shifts = ShiftOperasional::with(['regu','supervisi','kolektor'])
            ->when($tanggal, fn($q) => $q->whereDate('tanggal', $tanggal))
            ->when($status,  fn($q) => $q->where('status', $status))
            ->orderBy('tanggal','desc')
            ->orderBy('jam_mulai')
            ->paginate(20);

        return view('operasional.shift.index', compact('shifts','tanggal','status'));
    }

    public function create() {
        $regu      = Regu::where('aktif', true)->orderBy('kode_regu')->get();
        $supervisi = $this->getUsersByRole('supervisi');
        $kolektor  = $this->getUsersByRole('kolektor');
        return view('operasional.shift.form', [
            'shift'     => new ShiftOperasional(),
            'regu'      => $regu,
            'supervisi' => $supervisi,
            'kolektor'  => $kolektor,
            'mode'      => 'create',
        ]);
    }

    public function store(Request $request) {
        $v = $request->validate([
            'tanggal'             => 'required|date',
            'regu_id'             => 'required|exists:regu,id',
            'nama_shift'          => 'required|string|max:30',
            'jam_mulai'           => 'required|date_format:H:i',
            'jam_selesai'         => 'required|date_format:H:i',
            'supervisi_id'        => 'required|exists:users,id',
            'kolektor_id'         => 'nullable|exists:users,id',
            'tanggal_awal_dinas'  => 'nullable|date',
            'tanggal_akhir_dinas' => 'nullable|date|after_or_equal:tanggal_awal_dinas',
            'catatan'             => 'nullable|string',
        ]);
        $v['status'] = 'draft';
        $shift = ShiftOperasional::create($v);
        return redirect()->route('operasional.shift.show', $shift)->with('success', 'Shift berhasil dibuat.');
    }

    public function show(ShiftOperasional $shift) {
        $shift->load([
            'regu','supervisi','kolektor',
            'tripKapal.kapal','tripKapal.dermaga','tripKapal.tagihPelayaran',
            'jasaSandar.dermaga',
            'penjualanTiket',
            'asuransiShift',
        ]);
        return view('operasional.shift.show', compact('shift'));
    }

    public function edit(ShiftOperasional $shift) {
        if ($shift->isApproved()) return back()->with('error', 'Shift yang sudah disetujui tidak dapat diubah.');
        $regu      = Regu::where('aktif', true)->orderBy('kode_regu')->get();
        $supervisi = $this->getUsersByRole('supervisi');
        $kolektor  = $this->getUsersByRole('kolektor');
        return view('operasional.shift.form', compact('shift','regu','supervisi','kolektor') + ['mode'=>'edit']);
    }

    public function update(Request $request, ShiftOperasional $shift) {
        if ($shift->isApproved()) return back()->with('error', 'Shift yang sudah disetujui tidak dapat diubah.');
        $v = $request->validate([
            'tanggal'             => 'required|date',
            'regu_id'             => 'required|exists:regu,id',
            'nama_shift'          => 'required|string|max:30',
            'jam_mulai'           => 'required|date_format:H:i',
            'jam_selesai'         => 'required|date_format:H:i',
            'supervisi_id'        => 'required|exists:users,id',
            'kolektor_id'         => 'nullable|exists:users,id',
            'tanggal_awal_dinas'  => 'nullable|date',
            'tanggal_akhir_dinas' => 'nullable|date|after_or_equal:tanggal_awal_dinas',
            'catatan'             => 'nullable|string',
        ]);
        $shift->update($v);
        return redirect()->route('operasional.shift.show', $shift)->with('success', 'Shift berhasil diperbarui.');
    }

    public function destroy(ShiftOperasional $shift) {
        if (!$shift->isDraft()) return back()->with('error', 'Hanya shift berstatus draft yang dapat dihapus.');
        $shift->delete();
        return redirect()->route('operasional.shift.index')->with('success', 'Shift berhasil dihapus.');
    }

    public function submit(ShiftOperasional $shift) {
        if (!$shift->isDraft()) return back()->with('error', 'Shift sudah disubmit atau disetujui.');
        $shift->update(['status' => 'submitted']);
        return back()->with('success', 'Shift berhasil disubmit untuk persetujuan.');
    }

    public function approve(ShiftOperasional $shift) {
        if (!$shift->isSubmitted()) return back()->with('error', 'Shift belum disubmit.');
        $shift->update(['status' => 'approved', 'approved_by' => auth()->id(), 'approved_at' => now()]);
        return back()->with('success', 'Shift berhasil disetujui.');
    }

    /**
     * Query users berdasarkan nama role — aman tanpa guard issue.
     */
    private function getUsersByRole(string $roleName)
    {
        return User::whereHas('roles', fn($q) => $q->where('name', $roleName))
            ->where('aktif', true)
            ->orderBy('name')
            ->get();
    }
}
