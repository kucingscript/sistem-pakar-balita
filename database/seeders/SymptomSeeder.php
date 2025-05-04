<?php

namespace Database\Seeders;

use App\Models\Disease;
use App\Models\Symptom;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SymptomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $symptomData = [
            'ISPA' => [
                ['code' => 'G1', 'description' => 'batuk disertai pilek', 'weight' => 0.7],
                ['code' => 'G2', 'description' => 'sesak nafas', 'weight' => 0.7],
                ['code' => 'G3', 'description' => 'nyeri telan', 'weight' => 0.4],
                ['code' => 'G4', 'description' => 'hidung buntu', 'weight' => 0.1],
            ],
            'DBD' => [
                ['code' => 'G5', 'description' => 'penurunan trombosit dan peningkatan hematokrit', 'weight' => 0.7],
                ['code' => 'G6', 'description' => 'petechiae', 'weight' => 0.6],
                ['code' => 'G7', 'description' => 'nyeri otot dan sendi', 'weight' => 0.3],
                ['code' => 'G8', 'description' => 'nyeri kepala', 'weight' => 0.1],
            ],
            'Diare' => [
                ['code' => 'G9', 'description' => 'bab lendir', 'weight' => 0.7],
                ['code' => 'G10', 'description' => 'bab cair', 'weight' => 0.7],
                ['code' => 'G11', 'description' => 'nyeri perut', 'weight' => 0.4],
                ['code' => 'G12', 'description' => 'mual dan muntah', 'weight' => 0.3],
            ],
            'Kejang Demam' => [
                ['code' => 'G13', 'description' => 'kejang', 'weight' => 0.7],
                ['code' => 'G14', 'description' => 'demam tinggi', 'weight' => 0.7],
                ['code' => 'G15', 'description' => 'mata melirik ke atas', 'weight' => 0.6],
                ['code' => 'G16', 'description' => 'dehidrasi', 'weight' => 0.3],
            ],
            'Kulit' => [
                ['code' => 'G17', 'description' => 'diaperrash', 'weight' => 0.7],
                ['code' => 'G18', 'description' => 'gatal merah merah', 'weight' => 0.6],
                ['code' => 'G19', 'description' => 'kulit kering', 'weight' => 0.4],
                ['code' => 'G20', 'description' => 'bayi menangis tidak normal', 'weight' => 0.1],
            ],
        ];

        foreach ($symptomData as $diseaseCode => $symptoms) {
            $disease = Disease::where('code', $diseaseCode)->first();
            foreach ($symptoms as $symptom) {
                Symptom::create([
                    'code' => $symptom['code'],
                    'description' => $symptom['description'],
                    'weight' => $symptom['weight'],
                    'disease_id' => $disease->id,
                ]);
            }
        }
    }
}
