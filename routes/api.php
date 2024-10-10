<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::apiResource("/properties", PropertyController::class);

Route::delete("/image-delete/{id}", [ImageController::class, "destroy"]);
Route::post("/image-add", [ImageController::class, "store"]);

Route::post("/login", [AuthController::class, "login"]);
Route::post("/logout", [AuthController::class, "logout"]);

Route::apiResource("/user", UserController::class);