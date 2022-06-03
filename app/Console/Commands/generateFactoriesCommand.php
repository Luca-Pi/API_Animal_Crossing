<?php

namespace App\Console\Commands;

use App\Models\Commentaries;
use App\Models\HasFish;
use App\Models\HasFossils;
use App\Models\HasInsect;
use App\Models\HasPlatforms;
use App\Models\HasSeaCreature;
use App\Models\Publications;
use App\Models\Reactions;
use App\Models\User;
use Database\Seeders\HasPlatformsSeeder;
use Illuminate\Console\Command;

class generateFactoriesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'island_crossing:factories';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Commande permettant de générer des factories';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            User::factory()->count(50)->create();

            /**
             * BLOG
             */
            Publications::factory()->count(15)->create();
            Commentaries::factory()->count(40)->create();
            Reactions::factory()->count(20)->create();

            HasFish::factory()->count(84)->create();
            HasInsect::factory()->count(151)->create();
            HasSeaCreature::factory()->count(37)->create();
            HasFossils::factory()->count(92)->create();

            HasPlatforms::factory()->count(25)->create();
        } catch (\Exception $exception) {
            $this->error($exception);
        }
    }
}
