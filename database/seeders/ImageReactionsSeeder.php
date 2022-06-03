<?php

namespace Database\Seeders;

use App\Models\ImageReactions;
use Illuminate\Database\Seeder;

class ImageReactionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ImageReactions::query()->delete();

        $imageReaction = new ImageReactions();
        $imageReaction->label = 'Bravo !';
        $imageReaction->image = 'https://margxt.fr/wp-content/uploads/2020/04/Animal-Crossing-New-Horizons-mimiques-30.png';
        $imageReaction->save();

        $imageReaction = new ImageReactions();
        $imageReaction->label = 'J\'adore !';
        $imageReaction->image = 'https://margxt.fr/wp-content/uploads/2020/04/Animal-Crossing-New-Horizons-mimiques-24.png';
        $imageReaction->save();

        $imageReaction = new ImageReactions();
        $imageReaction->label = 'Smirk !';
        $imageReaction->image = 'https://margxt.fr/wp-content/uploads/2020/04/Animal-Crossing-New-Horizons-mimiques-07.png';
        $imageReaction->save();
    }
}
