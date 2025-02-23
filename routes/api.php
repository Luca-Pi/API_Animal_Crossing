<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::middleware('auth:api')->get('villagers/search', 'VillagerController@searchVillagers');
Route::middleware('auth:api')->get('villagers/getnames', 'VillagerController@getVillagersName');
Route::middleware('auth:api')->get('species/getnames', 'SpeciesController@getSpeciesName');
Route::middleware('auth:api')->get('personalities/getnames', 'PersonnalityController@getPersonalitiesName');
Route::middleware('auth:api')->get('villagers-anniversary-today', 'VillagerController@getVillagersAnniversary');

Route::middleware('auth:api')->get('fishes/search', 'FishesController@searchFishes');
Route::middleware('auth:api')->get('user-fishes', 'FishesController@getFishesUser');
Route::middleware('auth:api')->post('has-fish-user', 'HasFishController@attachFishUser');
Route::middleware('auth:api')->delete('has-fish-user-remove', 'HasFishController@detachFishUser');

Route::middleware('auth:api')->get('insects/search', 'InsectController@searchInsects');
Route::middleware('auth:api')->get('user-insects', 'InsectController@getInsectsUser');
Route::middleware('auth:api')->post('has-insect-user', 'HasInsectController@attachInsectUser');
Route::middleware('auth:api')->delete('has-insects-user-remove', 'HasInsectController@detachInsectUser');

Route::middleware('auth:api')->get('sea-creatures/search', 'SeaCreaturesController@searchSeaCreatures');
Route::middleware('auth:api')->get('user-sea-creatures', 'SeaCreaturesController@getSeaCreaturesUser');
Route::middleware('auth:api')->post('has-sea-creature-user', 'HasSeaCreatureController@attachSeaCreatureUser');
Route::middleware('auth:api')->delete('has-sea-creature-user-remove', 'HasSeaCreatureController@detachSeaCreatureUser');

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::middleware("auth:api")->put("update","UserController@updateUser");

Route::middleware('auth:api')->apiResource("villagers", "VillagerController")->except(['store', 'destroy', 'update']);
Route::middleware('auth:api')->apiResource("characters", "CharacterController")->except(['store', 'destroy', 'update']);
Route::middleware('auth:api')->apiResource("musics", "MusicController")->except(['store', 'destroy', 'update']);
Route::middleware('auth:api')->apiResource("fishes", "FishesController")->except(['store', 'destroy', 'update']);
Route::middleware('auth:api')->apiResource("insects", "InsectController")->except(['store', 'destroy', 'update']);
Route::middleware('auth:api')->apiResource("sea_creatures", "SeaCreaturesController")->except(['store', 'destroy', 'update']);
Route::middleware('auth:api')->apiResource("has_fish", "HasFishController");
Route::middleware('auth:api')->apiResource("set_furnitures", "SetFurnitureController");

/*Route::middleware('auth:api')->apiResource("users", "UserController")->except(['store', 'destroy', 'update']);*/

Route::middleware('auth:api')->get('me', 'UserController@show')->name('api.user.show');
