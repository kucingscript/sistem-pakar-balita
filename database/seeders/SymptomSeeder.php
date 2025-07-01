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
                ['code' => 'G1', 'description' => 'batuk disertai pilek', 'mb' => 0.8, 'md' => 0.1],
                ['code' => 'G2', 'description' => 'nyeri telan', 'mb' => 0.8, 'md' => 0.2],
                ['code' => 'G3', 'description' => 'demam lebih dari 3 hari', 'mb' => 0.6, 'md' => 0.2],
                ['code' => 'G4', 'description' => 'hidung buntu', 'mb' => 0.6, 'md' => 0.3],
            ],
            'DBD' => [
                ['code' => 'G5', 'description' => 'demam tinggi 1 sampai 7 hari', 'mb' => 0.8, 'md' => 0.1],
                ['code' => 'G6', 'description' => 'petechiae', 'mb' => 0.8, 'md' => 0.1],
                ['code' => 'G7', 'description' => 'nyeri kepala', 'mb' => 0.6, 'md' => 0.2],
                ['code' => 'G8', 'description' => 'muntah', 'mb' => 0.6, 'md' => 0.3],
            ],
            'Diare' => [
                ['code' => 'G9', 'description' => 'bab lendir', 'mb' => 1, 'md' => 0.1],
                ['code' => 'G10', 'description' => 'bab cair', 'mb' => 1, 'md' => 0.1],
                ['code' => 'G11', 'description' => 'nyeri perut', 'mb' => 0.8, 'md' => 0.2],
                ['code' => 'G12', 'description' => 'mual dan muntah', 'mb' => 0.6, 'md' => 0.3],
            ],
            'Kejang Demam' => [
                ['code' => 'G13', 'description' => 'kejang', 'mb' => 1, 'md' => 0.1],
                ['code' => 'G14', 'description' => 'demam tinggi', 'mb' => 0.8, 'md' => 0.1],
                ['code' => 'G15', 'description' => 'mata melirik ke atas', 'mb' => 0.8, 'md' => 0.2],
            ],
            'Kulit' => [
                ['code' => 'G16', 'description' => 'dehidrasi', 'mb' => 0.6, 'md' => 0.2],
                ['code' => 'G17', 'description' => 'diaperrash', 'mb' => 1, 'md' => 0.1],
                ['code' => 'G18', 'description' => 'gatal merah merah', 'mb' => 0.8, 'md' => 0.1],
                ['code' => 'G19', 'description' => 'kemerahan di lipatan paha', 'mb' => 0.8, 'md' => 0.1],
                ['code' => 'G20', 'description' => 'bayi menangis tidak normal', 'mb' => 0.6, 'md' => 0.3],
            ],
        ];

        foreach ($symptomData as $diseaseCode => $symptoms) {
            $disease = Disease::where('code', $diseaseCode)->first();

            if (!$disease) {
                $this->command->warn("Disease with code '{$diseaseCode}' not found. Skipping symptoms for this disease.");
                continue;
            }

            foreach ($symptoms as $symptom) {
                // Hitung 'weight' = mb - md
                $calculatedWeight = $symptom['mb'] - $symptom['md'];

                Symptom::create([
                    'code' => $symptom['code'],
                    'description' => $symptom['description'],
                    'mb' => $symptom['mb'],
                    'md' => $symptom['md'],
                    'weight' => $calculatedWeight,
                    'disease_id' => $disease->id,
                ]);
            }
        }
    }
}
