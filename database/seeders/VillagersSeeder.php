<?php

namespace Database\Seeders;

use App\Models\Gender;
use App\Models\LanguageData;
use App\Models\Personality;
use App\Models\Sign;
use App\Models\Species;
use App\Models\Villager;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

class VillagersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Villager::query()->delete();

        $nameVillagers = file_get_contents('https://raw.githubusercontent.com/alexislours/translation-sheet-data/master/villagers.json');
        $nameVillagers2 = file_get_contents('https://nookipedia.com/w/index.php?title=Special:CargoExport&tables=nh_villager%2C+nh_language_name%2C+villager&join+on=nh_villager.name%3Dnh_language_name.en_name%2C+nh_villager.name%3Dvillager.name&fields=nh_villager.name%2C+nh_language_name.fr_name%2C+nh_villager.name%2C+villager.image_url%2C+nh_villager.photo_url%2C+nh_villager.icon_url%2C+nh_villager.quote%2C+nh_villager.species%2C+nh_villager.gender%2C+nh_villager.personality%2C+nh_villager.birthday%2C+nh_villager.sign%2C+nh_villager.catchphrase%2C+villager.id%2C&where=nh_language_name.type%3D%27Villager%27&order+by=%60mw_cargo__nh_villager%60.%60name%60%2C%60mw_cargo__nh_language_name%60.%60fr_name%60%2C%60mw_cargo__villager%60.%60image_url%60%2C%60mw_cargo__nh_villager%60.%60photo_url%60%2C%60mw_cargo__nh_villager%60.%60icon_url%60&limit=500&format=json');
        $nameVillagers = json_decode($nameVillagers);
        $nameVillagers2 = json_decode($nameVillagers2);
        $nameVillagersTrad = [];
        $nameVillagersTrad2 = [];

        foreach ($nameVillagers as $nameVillager) {
            $nameVillagersTrad[$nameVillager->id] = $nameVillager->locale;
        }
        foreach ($nameVillagers2 as $nameVillager2) {
            $nameVillagersTrad2[$nameVillager2->id] = [
                'en' => $nameVillager2->name,
                'fr' => $nameVillager2->fr_name,
                'icon_url' => $nameVillager2->icon_url,
            ];
        }

        $request = Http::withHeaders([
            "X-API-KEY" => env('API_KEY_NOOKI'),
            "Accept-Version" => "1.0.0",
        ])
            ->withOptions([
                'verify' => false,
            ])
            ->get('https://api.nookipedia.com/villagers');

        $villagers = json_decode($request->body(), true);
        $villagers = new Collection($villagers);
        foreach ($villagers as $villager) {
            if (!$villager['id'] == null && !$villager['id'] == '') {
                $villagerBDD['en'][$villager['id']]['url'] = $villager['url'];
                $villagerBDD['en'][$villager['id']]['code'] = $villager['id'];
                $villagerBDD['en'][$villager['id']]['name'] = isset($nameVillagersTrad[$villager['id']]) ? $nameVillagersTrad[$villager['id']]->EUen : isset($nameVillagersTrad2[$villager['id']]) ? $nameVillagersTrad2[$villager['id']]['en'] : $villager['name'];
                $villagerBDD['en'][$villager['id']]['image_url'] = $villager['image_url'];
                $villagerBDD['en'][$villager['id']]['gender_id'] = Gender::getGender('en', substr($villager['gender'], 0, 1))->id;
                $villagerBDD['en'][$villager['id']]['species_id'] = Species::getSpece('en', substr($villager['species'], 0, 3))->id;
                $villagerBDD['en'][$villager['id']]['phrase'] = $villager['quote'];
                $villagerBDD['en'][$villager['id']]['catchphrase'] = $villager['phrase'];
                $villagerBDD['en'][$villager['id']]['clothing'] = $villager['clothing'];
                $villagerBDD['en'][$villager['id']]['birthday_month'] = $villager['birthday_month'];
                $villagerBDD['en'][$villager['id']]['birthday_day'] = $villager['birthday_day'] !== '' ? $villager['birthday_day'] : null;
                $villagerBDD['en'][$villager['id']]['personality_id'] = Personality::getPersonality('en', substr($villager['personality'], 0, 3))->id;
                $villagerBDD['en'][$villager['id']]['lang_id'] = LanguageData::getEn()->id;
                $villagerBDD['en'][$villager['id']]['sign_id'] = Sign::getSign('en', substr($villager['sign'], 0, 3))->id;

                $villagerBDD['fr'][$villager['id']]['url'] = $villager['url'];
                $villagerBDD['fr'][$villager['id']]['code'] = $villager['id'];
                $villagerBDD['fr'][$villager['id']]['name'] = isset($nameVillagersTrad[$villager['id']]) ? $nameVillagersTrad[$villager['id']]->EUfr : isset($nameVillagersTrad2[$villager['id']]) ? $nameVillagersTrad2[$villager['id']]['fr'] : $villager['name'];;
                $villagerBDD['fr'][$villager['id']]['image_url'] = $villager['image_url'];
                $villagerBDD['fr'][$villager['id']]['gender_id'] = Gender::getGender('fr', substr($villager['gender'], 0, 1))->id;
                $villagerBDD['fr'][$villager['id']]['species_id'] = Species::getSpece('en', substr($villager['species'], 0, 3))->id;
                $villagerBDD['fr'][$villager['id']]['phrase'] = $villager['quote'];
                $villagerBDD['fr'][$villager['id']]['catchphrase'] = $villager['phrase'];
                $villagerBDD['fr'][$villager['id']]['clothing'] = $villager['clothing'];
                $villagerBDD['fr'][$villager['id']]['birthday_month'] = $villager['birthday_month'];
                $villagerBDD['fr'][$villager['id']]['birthday_day'] = $villager['birthday_day'] !== '' ? $villager['birthday_day'] : null;
                $villagerBDD['fr'][$villager['id']]['personality_id'] = Personality::getPersonality('fr', substr($villager['personality'], 0, 3))->id;
                $villagerBDD['fr'][$villager['id']]['lang_id'] = LanguageData::getFr()->id;
                $villagerBDD['fr'][$villager['id']]['sign_id'] = Sign::getSign('fr', substr($villager['sign'], 0, 3))->id;
            }
        }

        foreach ($villagerBDD['en'] as $villager) {
            Villager::create($villager);
        }

        foreach ($villagerBDD['fr'] as $villager) {
            Villager::create($villager);
        }
    }
}
