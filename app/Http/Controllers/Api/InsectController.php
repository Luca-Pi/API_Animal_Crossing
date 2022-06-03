<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\HasInsect;
use App\Models\Insect;
use App\Models\LanguageData;
use App\Models\User;
use App\Services\PeriodService;
use Illuminate\Http\Request;

class InsectController extends Controller
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
        $insects = Insect::query()
            ->select('bugs.*', 'languages_data.name AS LangDataName')
            ->leftJoin('languages_data', 'languages_data.id', '=', 'bugs.lang_id')
            ->where('languages_data.name', LanguageData::getEn()->name)->get();

        $user = User::where('api_token', $request->get('api_token'))->first();

        foreach ($insects as $insect) {
            $insect->hasInsect = count($user->insects()->where('insect_id', $insect->id)->get()) > 0 ? true : false;
        }
        return response()->json($insects);
    }

    public function getInsectsUser(Request $request)
    {
        $user = User::where('api_token', $request->get('api_token'))->first();
        $userInsects = $user->insects;

        return response()->json($userInsects);
    }

    public function show($id)
    {
        $insect = Insect::find($id);

        return response()->json($insect);
    }

    public function searchInsects(Request $request)
    {
        $insects = Insect::query();
        $requestSearch = $request->all();
        $user = User::where('api_token', $request->get('api_token'))->first();

        $filters['name'] = $requestSearch['name'] !== '' ? $requestSearch['name'] : null;
        $filters['hasInsect'] = $requestSearch['hasInsect'] !== '' ? $requestSearch['hasInsect'] : null;
        $filters['period'] = $requestSearch['period'] !== '' ? $requestSearch['period'] : null;

        $insects = $insects
            ->select('bugs.*');

        if ($filters['name'] !== null) {
            $insects->where('bugs.name', '=', $filters['name']);
        }
        if ($filters['hasInsect'] === "true") {
            $insects
                ->leftJoin('has_insects', 'has_insects.insect_id', '=', 'bugs.id')
                ->where('has_insects.user_id', '=', $user->id)
            ;
        } else if($filters['hasInsect'] === "false") {
            $insectsAcquired = HasInsect::query();
            $insectsAcquired = $insectsAcquired
                ->select('has_insects.insect_id')
                ->where('has_insects.user_id', '=', $user->id);

            $insects->whereNotIn('id', $insectsAcquired);
        }
        if ($filters['period'] !== null) {
            $allInsects = Insect::all();
            $idInsects = [];
            foreach ($allInsects as $insect) {
                if ($this->periodService->isInPeriod($insect->n_availability, $filters['period'])) {
                    $idInsects[] = $insect->id;
                }
            }
            $insects->whereIn('bugs.id', $idInsects);
        }

        $insects = $insects->get();

        foreach ($insects as $insect) {
            $insect->hasInsect = count($user->insects()->where('insect_id', $insect->id)->get()) > 0 ? true : false;
        }

        return response()->json($insects);
    }
}
