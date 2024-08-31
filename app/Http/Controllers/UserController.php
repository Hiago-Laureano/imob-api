<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth:sanctum")->only(["destroy", "update", "store", "index", "show"]);
    }

    public function index(){
        return UserResource::collection(User::where("active", "=", "1")->paginate(15));
    }

    public function show(string $id){
        $user = User::find($id);
        if(! $user || ! $user->active){
            return response()->json(["message" => "No query results"], 404);
        }
        return new UserResource($user);
    }

    public function destroy(Request $request, string $id){
        if($request->user()->superuser){
            $user = User::find($id);
            if(! $user || ! $user->active){
                return response()->json(["message" => "Target with id={$id} not found"], 404);
            }
            $user->active = false;
            $user->save();
            return response()->noContent(204);
        }
        return response()->json(["message" => "Only superusers are authorized"], 403);
    }
    
    public function store(StoreUpdateUserRequest $request){
        if($request->user()->superuser){
            $data = $request->validated();
            User::create($data);
            return response()->noContent(201);
        }
        return response()->json(["message" => "Only superusers are authorized"], 403);
    }

    public function update(StoreUpdateUserRequest $request, string $id){
        if($request->user()->id === $id || $request->user()->superuser){
            $data = $request->validated();
            $user = User::find($id);
            if(! $user || ! $user->active){
                return response()->json(["message" => "Target with id={$id} not found"], 404);
            }
            $user->update($data);
            return response()->noContent(204);
        }
        return response()->json(["message" => "Only superusers are authorized"], 403);

    }
}
