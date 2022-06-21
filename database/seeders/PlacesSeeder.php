<?php

namespace Database\Seeders;
use App\Models\Places;

use Illuminate\Database\Seeder;

class PlacesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {


        if (($open = fopen('C:\laragon\www\API_Animal_Crossing\nookiepediaPlace.csv', "r")) !== FALSE) {

            while (($data = fgetcsv($open, 1000, ",")) !== FALSE) {
                $PlacesToDatabase = new Places();
                $PlacesToDatabase['label']=$data[4];
                $PlacesToDatabase['description']=$data[5];
                $PlacesToDatabase['image']=$data[6];
                $PlacesToDatabase->save();

            }
            fclose($open);
        }
    }
}
