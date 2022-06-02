<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\HasSeaCreature;
use App\Models\SeaCreatures;
use Illuminate\Http\Request;
use App\Models\User;

class SeaCreaturesController extends Controller
{
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
            ->select('bugs.*');

        if ($filters['name'] !== null) {
            $seaCreatures->where('bugs.name', '=', $filters['name']);
        }
        if ($filters['hasSeaCreature'] === "true") {
            $seaCreatures
                ->leftJoin('has_sea_creatures', 'has_sea_creatures.sea_creature_id', '=', 'bugs.id')
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
            $seaCreatures->where('bugs.n_availability', 'LIKE', '%' . $filters['period'] .'%');
        }

        $seaCreatures = $seaCreatures->get();

        foreach ($seaCreatures as $seaCreature) {
            $seaCreature->hasSeaCreature = count($user->seaCreatures()->where('sea_creature_id', $seaCreature->id)->get()) > 0 ? true : false;
        }

        return response()->json($seaCreatures);
    }
}
