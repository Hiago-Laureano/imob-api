<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUpdateImageRequest;
use App\Models\Image;
use App\Models\Property;

class ImageController extends Controller
{
    public function __construct(){
        $this->middleware("auth:sanctum")->only(["store", "destroy"]);
    }

    public function destroy(string $id){
        $data = Image::find($id);
        if(! $data){
            return response()->json(["message" => "Target with id={$id} not found"], 404);
        }
        $link = str_replace("storage/images/", "", $data->link);
        unlink(storage_path("app/public/images/".$link));
        $data->delete();
        return response()->noContent(204);
    }

    public function store(StoreUpdateImageRequest $request){
        $data = $request->validated();
        $property = Property::find($data["property_id"]);
        if(! $property){
            return response()->json(["message" => "Target with id={$data['property_id']} not found"], 404);
        }
        foreach($data["files"] as $file){
            $link = $file->store("images", "public");
            Image::create(["property_id" => $property->id, "link" => "storage/".$link, "original_name" => $file->getClientOriginalName()]);
        }
        return response()->noContent(201);
    }
}
