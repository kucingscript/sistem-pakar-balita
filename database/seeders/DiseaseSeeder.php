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
            ['code' => 'ISPA', 'name' => 'Infeksi Saluran Pernapasan Akut'],
            ['code' => 'DBD', 'name' => 'Demam Berdarah Dengue'],
            ['code' => 'Diare', 'name' => 'Diare'],
            ['code' => 'Kejang Demam', 'name' => 'Kejang Demam'],
            ['code' => 'Kulit', 'name' => 'Penyakit Kulit'],
        ];

        foreach ($diseases as $disease) {
            Disease::create($disease);
        }
    }
}
