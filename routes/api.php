<?php

use App\Http\Controllers\PropertyController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get("/get-all", [PropertyController::class, "index"]);
Route::get("/get/{id}", [PropertyController::class, "show"]);