<?php

namespace Database\Seeders;

use App\Models\Disease;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DiseaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $diseases = [
            [
                'code' => 'ISPA',
                'name' => 'Infeksi Saluran Pernapasan Akut',
                'treatment' => 'Berikan antipiretik, edukasi hidrasi, evaluasi infeksi bakteri, rujuk jika ada takipnea atau sesak.'
            ],
            [
                'code' => 'DBD',
                'name' => 'Demam Berdarah Dengue',
                'treatment' => 'Pantau hematokrit & trombosit, berikan cairan sesuai fase, hindari AINS, rujuk bila syok.'
            ],
            [
                'code' => 'Diare',
                'name' => 'Diare',
                'treatment' => 'Berikan oralit, lanjutkan ASI/makan, tambahkan zinc 10–20 mg/hari, rujuk bila dehidrasi berat.'
            ],
            [
                'code' => 'Kejang Demam',
                'name' => 'Kejang Demam',
                'treatment' => 'Berikan diazepam rektal bila >5 menit, antipiretik, evaluasi infeksi sistemik, edukasi kejang berulang.'
            ],
            [
                'code' => 'Kulit',
                'name' => 'Penyakit Kulit',
                'treatment' => 'Topikal sesuai diagnosis, jaga kebersihan kulit, evaluasi alergi/infeksi, rujuk bila tidak membaik.'
            ]
        ];


        foreach ($diseases as $disease) {
            Disease::create($disease);
        }
    }
}
