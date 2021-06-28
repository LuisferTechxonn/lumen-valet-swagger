<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cars;

class CarsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        Cars::factory()->count(34)->create();

    }
}
