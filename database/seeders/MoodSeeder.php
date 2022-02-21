<?php

namespace Database\Seeders;

use App\Models\Mood;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MoodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $payloadNature = [
            'name' => 'Nature',
            'description' => 'Nature is the natural world, the natural world is the nature.',
            'emoji' => '🌳',
        ];

        $payloadRelax = [
            'name' => 'Relax',
            'description' => 'Relax is the time to relax, the time to unwind.',
            'emoji' => '💤',
        ];

        $payloadHistory = [
            'name' => 'History',
            'description' => 'History is the past, the past is the history.',
            'emoji' => '📚',
        ];

        $payloadCulture = [
            'name' => 'Culture',
            'description' => 'Culture is the art, the art is the culture.',
            'emoji' => '🎨',
        ];

        $payloadParty = [
            'name' => 'Party',
            'description' => 'Party is the fun, the fun is the party.',
            'emoji' => '🎉',
        ];

        $payloadAdventure = [
            'name' => 'Adventure',
            'description' => 'Adventure is the journey, the journey is the adventure.',
            'emoji' => '🗺️',
        ];

        $payloadFood = [
            'name' => 'Food',
            'description' => 'Food is the food, the food is the food.',
            'emoji' => '🍽️',
        ];

        Mood::create($payloadNature);
        Mood::create($payloadRelax);
        Mood::create($payloadHistory);
        Mood::create($payloadCulture);
        Mood::create($payloadParty);
        Mood::create($payloadAdventure);
        Mood::create($payloadFood);
    }
}
