<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function funLogin(Request $request){
        // validar
        $credenciales = $request->validate([
            "email" => "required|email",
            "password" => "required"
        ]);
        // verificar y autenticar credenciales
        if(!Auth::attempt($credenciales)){
            return response()->json(["mensaje" => "Credenciales Incorrectas"], 401);
        }
        // generar token
        $token = $request->user()->createToken("Token Auth")->plainTextToken;

        // respuesta
        return response()->json([
            "access_token" => $token,
            "usuario" => $request->user()
        ], 201);
    }

    public function funRegister(Request $request){
        // validamos
        $request->validate([
           "name" => "required|max:100|min:2",
           "email" => "required|email|unique:users",
           "password" => "required|same:cpassword"
        ]);
        // registramos
        $usuario = new User();
        $usuario->name = $request->name;
        $usuario->email = $request->email;
        $usuario->password = Hash::make($request->password);
        $usuario->save();
        // respuesta
        return response()->json(["mensaje" => "Usuario registrado", "user" => $usuario], 201);
    }

    public function funProfile(Request $request){
        // retornamos el perfil

        return response()->json($request->user(), 200);
    }

    public function funLogout(Request $request){
        // salir
        $request->user()->tokens()->delete();
        return response()->json(["mensaje" => "Logout"], 200);

    }
}
