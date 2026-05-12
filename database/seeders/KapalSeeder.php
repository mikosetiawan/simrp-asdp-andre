<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kapal;

class KapalSeeder extends Seeder
{
    public function run(): void
    {
        $kapal = [
            ['nama_kapal' => 'JATRA III',         'grt' => 5050,  'jenis' => 'roro', 'kode_kapal' => 'JT3'],
            ['nama_kapal' => 'PORT LINK',         'grt' => 12517, 'jenis' => 'roro', 'kode_kapal' => 'PL'],
            ['nama_kapal' => 'PORT LINK III',     'grt' => 15341, 'jenis' => 'roro', 'kode_kapal' => 'PL3'],
            ['nama_kapal' => 'SEBUKU',            'grt' => 5553,  'jenis' => 'roro', 'kode_kapal' => 'SBK'],
            ['nama_kapal' => 'BATU MANDI',        'grt' => 5553,  'jenis' => 'roro', 'kode_kapal' => 'BTM'],
            ['nama_kapal' => 'LEGUNDI',           'grt' => 5556,  'jenis' => 'roro', 'kode_kapal' => 'LGD'],
            ['nama_kapal' => 'SAFIRA NUSANTARA',  'grt' => 6345,  'jenis' => 'roro', 'kode_kapal' => 'SFN'],
            ['nama_kapal' => 'KIRANA - IX',       'grt' => 9168,  'jenis' => 'roro', 'kode_kapal' => 'KR9'],
            ['nama_kapal' => 'SUKI 2',            'grt' => 5008,  'jenis' => 'roro', 'kode_kapal' => 'SK2'],
            ['nama_kapal' => 'ELYSIA',            'grt' => 5094,  'jenis' => 'roro', 'kode_kapal' => 'ELS'],
            ['nama_kapal' => 'TRIMAS KANAYA',     'grt' => 6410,  'jenis' => 'roro', 'kode_kapal' => 'TKY'],
            ['nama_kapal' => 'SMS SAGITA',        'grt' => 8968,  'jenis' => 'roro', 'kode_kapal' => 'SGT'],
            ['nama_kapal' => 'WINDU KARSA P',     'grt' => 5071,  'jenis' => 'roro', 'kode_kapal' => 'WKP'],
            ['nama_kapal' => 'VIRGO 18',          'grt' => 9989,  'jenis' => 'roro', 'kode_kapal' => 'VG18'],
            ['nama_kapal' => 'EIRENE',            'grt' => 8663,  'jenis' => 'roro', 'kode_kapal' => 'ERN'],
            ['nama_kapal' => 'CALISHA',           'grt' => 9553,  'jenis' => 'roro', 'kode_kapal' => 'CLS'],
            ['nama_kapal' => 'ATHAYA',            'grt' => 13413, 'jenis' => 'roro', 'kode_kapal' => 'ATH'],
        ];
        foreach ($kapal as $k) Kapal::create($k);
    }
}
