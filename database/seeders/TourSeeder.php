<?php

namespace Database\Seeders;

use App\Models\Tour;
use App\Models\Travel;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TourSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $travels = Travel::all();

        foreach ($travels as $k => $v) {
            $payload = [
                'travelId' => $travels[$k]->id,
                'name' => 'Tour ' . $k,
                'description' => 'Tour: ' . $travels[$k]->name . ' #' . $k,
                'startingDate' => now(),
                'endingDate' => now()->addDays(rand(1, 10)),
                'price' => rand(1000, 10000)
            ];

            Tour::create($payload);
        }
    }
}
