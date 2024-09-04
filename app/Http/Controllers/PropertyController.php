<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUpdatePropertyRequest;
use App\Http\Resources\PropertyResource;
use App\Models\Image;
use App\Models\Property;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth:sanctum")->only(["store", "update", "destroy"]);
    }

    public function index(Request $request){
        //Get args from URL if exists
        $nameFilter = $request->get("name") ?? null;
        $locationFilter = $request->get("location") ?? null;
        $nBedroomsFilter = $request->get("bedrooms") ?? null;
        $nBathroomsFilter = $request->get("bathrooms") ?? null;
        $maxPriceFilter = $request->get("max_price") ?? null;
        $forRentFilter = $request->get("for_rent") ?? null;
        $acceptAnimalsFilter = $request->get("accept_animals") ?? null;

        $data = new Property();

        if($nameFilter !== null){
            $data = $data->where("name", "LIKE", "%{$nameFilter}%");
        }  
        if($locationFilter !== null){
            $data = $data->where("location", "LIKE", "%{$locationFilter}%");
        } 
        if($nBedroomsFilter !== null){
            $data = $data->where("bedrooms", ">=", $nBedroomsFilter);
        } 
        if($nBathroomsFilter !== null){
            $data = $data->where("bathrooms", ">=", $nBathroomsFilter);
        } 
        if($maxPriceFilter !== null){
            $data = $data->where("price", "<=", $maxPriceFilter);
        } 
        if($forRentFilter !== null){
            $data = $data->where("for_rent", "=", $forRentFilter);
            if($forRentFilter === "1" && $acceptAnimalsFilter !== null){
                $data = $data->where("accept_animals", "=", $acceptAnimalsFilter);
            }
        }       
        return PropertyResource::collection($data->paginate(15));
    }

    public function show(string $id){
        $data = Property::find($id);
        if(! $data){
            return response()->json(["message" => "No query results"], 404);
        }
        return new PropertyResource($data);
    }

    public function destroy(string $id){
        $property = Property::find($id);
        if(! $property){
            return response()->json(["message" => "Target with id={$id} not found"], 404);
        }
        foreach($property->images as $image){
            $link = str_replace("storage/images/", "", $image->link);
            unlink(storage_path("app/public/images/".$link));
        }
        $property->delete();
        return response()->noContent(204);
    }

    public function store(StoreUpdatePropertyRequest $request){
        $data = $request->validated();
        $property = Property::create($data);
        if(isset($data["files"])){
            foreach($data["files"] as $file){
                $link = $file->store("images", "public");
                Image::create(["property_id" => $property->id, "link" => "storage/".$link, "original_name" => $file->getClientOriginalName()]);
            };
        }
        return response()->noContent(201);
    }

    public function update(StoreUpdatePropertyRequest $request, string $id){
        $data = $request->validated();
        $property = Property::find($id);
        if(! $property){
            return response()->json(["message" => "Target with id={$id} not found"], 404);
        }
        $property->update($data);
        return response()->noContent(204);
    }
}
