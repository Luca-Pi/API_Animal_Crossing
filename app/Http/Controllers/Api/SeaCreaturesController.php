<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\HasSeaCreature;
use App\Models\SeaCreatures;
use App\Services\PeriodService;
use Illuminate\Http\Request;
use App\Models\User;

class SeaCreaturesController extends Controller
{
    /** @var PeriodService $periodService */
    private $periodService;

    public function __construct(PeriodService $periodService)
    {
        $this->periodService = $periodService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $seaCreatures = SeaCreatures::all();
        $user = User::where('api_token', $request->get('api_token'))->first();

        foreach ($seaCreatures as $seaCreature) {
            $seaCreature->hasSeaCreature = count($user->seaCreatures()->where('sea_creature_id', $seaCreature->id)->get()) > 0 ? true : false;
        }

        return response()->json($seaCreatures);
    }

    public function getSeaCreaturesUser(Request $request)
    {
        $user = User::where('api_token', $request->get('api_token'))->first();
        $userSeaCreatures = $user->seaCreatures;

        return response()->json($userSeaCreatures);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $seaCreature = SeaCreatures::find($id);

        return response()->json($seaCreature);
    }

    public function searchSeaCreatures(Request $request)
    {
        $seaCreatures = SeaCreatures::query();
        $requestSearch = $request->all();
        $user = User::where('api_token', $request->get('api_token'))->first();

        $filters['name'] = $requestSearch['name'] !== '' ? $requestSearch['name'] : null;
        $filters['hasSeaCreature'] = $requestSearch['hasSeaCreature'] !== '' ? $requestSearch['hasSeaCreature'] : null;
        $filters['period'] = $requestSearch['period'] !== '' ? $requestSearch['period'] : null;

        $seaCreatures = $seaCreatures
            ->select('sea_creatures.*');

        if ($filters['name'] !== null) {
            $seaCreatures->where('sea_creatures.name', '=', $filters['name']);
        }
        if ($filters['hasSeaCreature'] === "true") {
            $seaCreatures
                ->leftJoin('has_sea_creatures', 'has_sea_creatures.sea_creature_id', '=', 'sea_creatures.id')
                ->where('has_sea_creatures.user_id', '=', $user->id)
            ;
        } else if($filters['hasSeaCreature'] === "false") {
            $seaCreaturesAcquired = HasSeaCreature::query();
            $seaCreaturesAcquired = $seaCreaturesAcquired
                ->select('has_sea_creatures.sea_creature_id')
                ->where('has_sea_creatures.user_id', '=', $user->id);

            $seaCreatures->whereNotIn('id', $seaCreaturesAcquired);
        }
        if ($filters['period'] !== null) {
            $allSeaCreatures = SeaCreatures::all();
            $idSeaCreatures = [];
            foreach ($allSeaCreatures as $seaCreature) {
                if ($this->periodService->isInPeriod($seaCreature->period, $filters['period'])) {
                    $idSeaCreatures[] = $seaCreature->id;
                }
            }
            $seaCreatures->whereIn('sea_creatures.id', $idSeaCreatures);
        }

        $seaCreatures = $seaCreatures->get();

        foreach ($seaCreatures as $seaCreature) {
            $seaCreature->hasSeaCreature = count($user->seaCreatures()->where('sea_creature_id', $seaCreature->id)->get()) > 0 ? true : false;
        }

        return response()->json($seaCreatures);
    }
}
