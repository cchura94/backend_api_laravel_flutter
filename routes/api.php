<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\MuseoController;
use App\Http\Controllers\PermisoController;
use App\Http\Controllers\RoleController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


// auth

Route::prefix('v1/auth')->group(function(){

    Route::post('/login', [AuthController::class, 'funLogin']);
    Route::post('/register', [AuthController::class, 'funRegister']);
    
    Route::middleware('auth:sanctum')->group(function(){
    
        Route::get('/profile', [AuthController::class, 'funProfile']);
        Route::post('/logout', [AuthController::class, 'funLogout']);
    
    });

});

Route::middleware('auth:sanctum')->group(function(){
    // comentar museo
    Route::post("museo/{id}/comentario", [MuseoController::class, "funComentar"]);
    // CRUD CATEGORIAS
    Route::apiResource("categoria", CategoriaController::class);
    Route::apiResource("museo", MuseoController::class);
    Route::apiResource("role", RoleController::class);
    Route::apiResource("permiso", PermisoController::class);
});

Route::get("/no-autorizado", function(){
    return response()->json(["mensaje" => "No autorizado, Necesita un Token de acceso"], 401);
})->name('login');


