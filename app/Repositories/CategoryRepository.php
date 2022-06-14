<?php
namespace App\Repositories;
use App\Models\Category;


class CategoryRepository{

    function getAll(){
        return Category::all();
    }

    function getById(int $id){
        return Category::where("id", $id)->first();
    }

    function updateById(int $id,array $data){
        return Category::where("id", $id)->first()->update($data);
    }

    function deleteById(int $id){
        return Category::where("id", $id)->first()->delete();
    }
}
