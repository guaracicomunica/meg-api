<?php

namespace Database\Seeders;

use App\Models\PostType;
use Illuminate\Database\Seeder;

class PostTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PostType::create(
         [ 'description' => 'New' ],

        );

        PostType::create(
            [ 'description' => 'Activity' ],
        );
    }
}
