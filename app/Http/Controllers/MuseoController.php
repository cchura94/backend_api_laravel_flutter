<?php

namespace App\Http\Controllers;

use App\Models\Museo;
use Illuminate\Http\Request;
use PhpParser\Node\Stmt\Return_;

class MuseoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $limit = isset($request->limit)?$request->limit:10;

        $museos = Museo::with(['categoria', 'comentarios', 'favoritos'])
                        ->where("nombre", "like", "%$request->q%")
                        ->orderBy('id', 'desc')
                        ->paginate($limit);

        return response()->json($museos, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            "nombre" => "required",
            "categoria_id" => "required"
        ]);

        $museo = new Museo();
        $museo->nombre = $request->nombre;
        $museo->descripcion = $request->descripcion;
        $museo->horario_atencion = $request->horario_atencion;
        $museo->direccion = $request->direccion;
        $museo->latitud = $request->latitud;
        $museo->longitud = $request->longitud;
        if(isset($request->precio)){
            $museo->precio = $request->precio;
        }
        $museo->categoria_id = $request->categoria_id;
        $museo->user_id = $request->user()->id;
        $museo->save();

        return response()->json(["mensaje" => "Museo Registrado correctamente"], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function funComentar($id, Request $request){

        $user_id = $request->user()->id;

        $museo = Museo::find($id);
        $museo->comentarios()->attach([$user_id => [ 
                                                    "comentario" => $request->comentario,
                                                    "calificacion" => $request->calificacion,
                                                    "fecha" => date("Y-m-d H:i:s") 
                                                    ]]);
        return response()->json(["mensaje" => "Comentario Registrado"], 201);
    }
}
