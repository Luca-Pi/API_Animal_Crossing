<?php

namespace App\Http\Controllers\Api;

use App\Models\Category;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Repositories\CategoryRepository;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    private CategoryRepository $categoryRepository;

    function __construct(CategoryRepository $categoryRepository){
        $this->categoryRepository = $categoryRepository;
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAll(){
        return response()->json($this->categoryRepository->getAll());
    }

     /**
     * Remove the specified resource from storage.
     *  @param int id
     *  @return \Illuminate\Http\Response
     */
    public function getById($id){
        return response()->json($this->categoryRepository->getById($id));
    }

    // /**
    //  * Remove the specified resource from storage.
    //  *  @param int id
    //  *  @return \Illuminate\Http\Response
    //  */
    // public function updateById($id,Request $request){
    //     $validated = $request->validate([
    //         'title'=>"sometimes|max:15",
    //         "content"=>"sometimes|min:10",
    //         "category_id"=>"sometimes",
    //         "user_id"=>"sometimes",
    //         "image"=>"sometimes|mimes|max:3000"
    //     ]);
    //     return response()->json($this->publicationRepository->updateById($id,$validated));
    // }

    /**
     * Remove the specified resource from storage.
     *  @param int id
     *  @return \Illuminate\Http\Response
     */
    public function deleteById($id){
        return response()->json($this->categoryRepository->deleteById($id));
    }
}
