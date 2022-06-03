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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $validated = $request->validate([
            'title'=>"sometimes|max:15",
            "content"=>"sometimes|min:10",
            "category_id"=>"required",
            "user_id"=>"required",
            "image"=>"sometimes|mimes|max:3000"

        ]);
        $publication = new Publications();

        if(isset($validated["image"])){
            $newImageName = time()."_".$request->title.'.'.$request->image->extension();
            Storage::disk('local')->put($newImageName, $request->image);
            $publication->image_url = $newImageName;
        }
        $publication->title = $validated["title"];
        $publication->content = $validated["content"];
        $publication->category_id = $validated["category_id"];
        $publication->save();
        return response()->json($publication);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StorePublicationsRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePublicationsRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Publications  $publications
     * @return \Illuminate\Http\Response
     */
    public function show(Publications $publications)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Publications  $publications
     * @return \Illuminate\Http\Response
     */
    public function edit(Publications $publications)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatePublicationsRequest  $request
     * @param  \App\Models\Publications  $publications
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePublicationsRequest $request, Publications $publications)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Publications  $publications
     * @return \Illuminate\Http\Response
     */
    public function destroy(Publications $publications)
    {
        //
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

    





}
