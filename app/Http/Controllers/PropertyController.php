<?php

namespace App\Http\Controllers;

use App\Http\Resources\PropertyResource;
use App\Models\Property;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    public function index(Request $request){
        //Get args from URL if exists
        $nameFilter = $request->get("name") ?? null;
        $locationFilter = $request->get("location") ?? null;
        $nBedroomsFilter = $request->get("bedrooms") ?? null;
        $nBathroomsFilter = $request->get("bedrooms") ?? null;
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

    public function show(int $id){
        return new PropertyResource(Property::where("id", "=", $id)->first());
    }
}
