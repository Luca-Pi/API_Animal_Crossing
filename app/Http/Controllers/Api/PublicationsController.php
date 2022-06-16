<?php

namespace App\Http\Controllers\Api;

use App\Models\Publications;
use App\Http\Controllers\Controller;
use App\Http\Requests\StorePublicationsRequest;
use App\Http\Requests\UpdatePublicationsRequest;
use App\Repositories\PublicationRepository;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class PublicationsController extends Controller
{
    private PublicationRepository $publicationRepository;

    function __construct(PublicationRepository $publicationRepository){
        $this->publicationRepository = $publicationRepository;
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        //var_dump($request);
        $validated = $request->validate([
            "title"=>"sometimes|max:50",
            "content"=>"sometimes",
            "category_id"=>"required",
            "user_id"=>"required",
            "image"=>"sometimes|image|max:3000"
        ]);
        $publication = new Publications($request->all());

        if(isset($validated["image"])){
            $newImageName = Storage::disk('public')->put('publications', $validated["image"]);
            $publication->image_url = Storage::url($newImageName);
            //$publication->image_url = 'oui';
        }
        //$publication->image_url = "TEST";
        if(isset($validated["title"])){
            $publication->title = $validated["title"];
        }
        if(isset($validated["content"])){
            $publication->content = $validated["content"];
        }
        $publication->category_id = $validated["category_id"];
        $publication->save();
        return response()->json($publication);
    }

    

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAll(){
        return response()->json($this->publicationRepository->getAll());
    }

     /**
     * Remove the specified resource from storage.
     *  @param int id
     *  @return \Illuminate\Http\Response
     */
    public function getById($id){
        return response()->json($this->publicationRepository->getById($id));
    }

    /**
     * Remove the specified resource from storage.
     *  @param int id
     *  @return \Illuminate\Http\Response
     */
    public function updateById($id,Request $request){
        $validated = $request->validate([
            'title'=>"sometimes|max:15",
            "content"=>"sometimes|min:10",
            "category_id"=>"sometimes",
            "user_id"=>"sometimes",
            "image"=>"sometimes|mimes|max:3000"
        ]);
        return response()->json($this->publicationRepository->updateById($id,$validated));
    }

    /**
     * Remove the specified resource from storage.
     *  @param int id
     *  @return \Illuminate\Http\Response
     */
    public function deleteById($id){
       
        return response()->json($this->publicationRepository->deleteById($id));
    }

}
