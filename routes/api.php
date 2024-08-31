<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get("/get-all", [PropertyController::class, "index"]);
Route::get("/get/{id}", [PropertyController::class, "show"]);
Route::delete("/delete/{id}", [PropertyController::class, "destroy"]);
Route::post("/post", [PropertyController::class, "store"]);
Route::put("/update/{id}", [PropertyController::class, "update"]);

Route::delete("/image-delete/{id}", [ImageController::class, "destroy"]);
Route::post("/image-add", [ImageController::class, "store"]);

Route::post("/login", [AuthController::class, "login"]);
Route::post("/logout", [AuthController::class, "logout"]);