<?php
namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Kapal;
use Illuminate\Http\Request;

class KapalController extends Controller
{
    public function index()
    {
        $kapal = Kapal::orderBy('nama_kapal')->paginate(20);
        return view('master.kapal.index', compact('kapal'));
    }

    public function create()
    {
        return view('master.kapal.form', ['kapal' => new Kapal(), 'mode' => 'create']);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_kapal' => 'required|string|max:100|unique:kapal',
            'grt'        => 'required|integer|min:1',
            'jenis'      => 'required|in:roro,lct',
            'kode_kapal' => 'nullable|string|max:20',
            'aktif'      => 'boolean',
        ]);
        Kapal::create($validated);
        return redirect()->route('master.kapal.index')->with('success', 'Data kapal berhasil ditambahkan.');
    }

    public function edit(Kapal $kapal)
    {
        return view('master.kapal.form', ['kapal' => $kapal, 'mode' => 'edit']);
    }

    public function update(Request $request, Kapal $kapal)
    {
        $validated = $request->validate([
            'nama_kapal' => 'required|string|max:100|unique:kapal,nama_kapal,' . $kapal->id,
            'grt'        => 'required|integer|min:1',
            'jenis'      => 'required|in:roro,lct',
            'kode_kapal' => 'nullable|string|max:20',
            'aktif'      => 'boolean',
        ]);
        $kapal->update($validated);
        return redirect()->route('master.kapal.index')->with('success', 'Data kapal berhasil diperbarui.');
    }

    public function destroy(Kapal $kapal)
    {
        if ($kapal->tripKapal()->exists()) {
            return back()->with('error', 'Kapal tidak dapat dihapus karena sudah memiliki data trip.');
        }
        $kapal->delete();
        return redirect()->route('master.kapal.index')->with('success', 'Data kapal berhasil dihapus.');
    }
}
