<?php

namespace Database\Seeders;

use App\Models\Bimester;
use Illuminate\Database\Seeder;

class BimesterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $bimesters = ['1º Bimestre', '2º Bimestre', '3º Bimester', '4º Bimestre'];

        foreach($bimesters as $bimester) {
            Bimester::create(['name' => $bimester]);
        }
    }
}
