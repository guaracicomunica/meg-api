<?php

namespace Database\Seeders;

use App\Models\Unit;
use Illuminate\Database\Seeder;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $units = ['1ª Unidade', '2ª Unidade', '3ª Unidade', '4ª Unidade'];

        foreach($units as $unit) {
            Unit::create(['name' => $unit]);
        }
    }
}
