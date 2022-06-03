<?php
namespace App\Repositories;
use App\Models\Publications;

class PublicationRepository{

    function getAll(){
        return Publications::all();
    }

    function getById(int $id){
        return Publications::where("id", $id)->first();
    }
}
