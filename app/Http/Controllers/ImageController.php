<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUpdateImageRequest;
use App\Models\Image;
use App\Models\Property;

class ImageController extends Controller
{
    public function destroy(string $id){
        $data = Image::findOrFail($id);
        $link = str_replace("storage/images/", "", $data->link);
        unlink(storage_path("app/public/images/".$link));
        $data->delete();
        return response()->json(null, 204);
    }

    public function store(StoreUpdateImageRequest $request){
        $data = $request->validated();
        $property = Property::findOrFail($data["property_id"]);
        foreach($data["files"] as $file){
            $link = $file->store("images", "public");
            Image::create(["property_id" => $property->id, "link" => "storage/".$link, "original_name" => $file->getClientOriginalName()]);
        }
        return response()->json(null, 201);
    }
}
