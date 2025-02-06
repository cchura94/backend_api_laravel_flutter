<?php

namespace App\Http\Controllers;

use App\Models\Museo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MuseoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $limit = isset($request->limit) ? $request->limit : 10;

            $museos = Museo::with(['categoria', 'comentarios', 'favoritos'])
                ->where("nombre", "like", "%$request->q%")
                ->orderBy('id', 'desc')
                ->paginate($limit);

            return response()->json($museos, 200);
        } catch (\Exception $e) {
            //throw $th;
            return response()->json(["mensaje" => "Error al realizar la petición", "error" => $e->getMessage()], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                "nombre" => "required|unique:museos",
                "categoria_id" => "required"
            ]);

            DB::beginTransaction();

            // subida de imagenes
            $direccion_imagen = "";
            if($file = $request->file("imagen")){
                $url_imagen = time() ."-". $file->getClientOriginalName();
                $file->move("imagenes/", $url_imagen);
                $direccion_imagen = "imagenes/". $url_imagen;
            }

            $museo = new Museo();
            $museo->nombre = $request->nombre;
            $museo->descripcion = $request->descripcion;
            $museo->horario_atencion = $request->horario_atencion;
            $museo->direccion = $request->direccion;
            $museo->latitud = $request->latitud;
            $museo->longitud = $request->longitud;
            if (isset($request->precio)) {
                $museo->precio = $request->precio;
            }
            $museo->categoria_id = $request->categoria_id;
            $museo->user_id = $request->user()->id;
            $museo->imagen = $direccion_imagen;
            $museo->save();

            DB::commit();

            return response()->json(["mensaje" => "Museo Registrado correctamente"], 201);
        } catch (\Exception $e) {
            //throw $th;
            DB::rollback();
            return response()->json(["mensaje" => "Error al realizar la petición", "error" => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $museo = Museo::with(['categoria', 'comentarios', 'favoritos'])->find($id);

            return response()->json($museo, 200);
        } catch (\Exception $e) {
            //throw $th;
            return response()->json(["mensaje" => "Error al realizar la petición", "error" => $e->getMessage()], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {

                $request->validate([
                    "nombre" => "required|unique:museos,nombre,$id",
                    "categoria_id" => "required"
                ]);
    
                DB::beginTransaction();
    
                // subida de imagenes
                $direccion_imagen = "";
                
    
                $museo = Museo::find($id);
                $museo->nombre = $request->nombre;
                $museo->descripcion = $request->descripcion;
                $museo->horario_atencion = $request->horario_atencion;
                $museo->direccion = $request->direccion;
                $museo->latitud = $request->latitud;
                $museo->longitud = $request->longitud;
                if (isset($request->precio)) {
                    $museo->precio = $request->precio;
                }
                $museo->categoria_id = $request->categoria_id;
                $museo->user_id = $request->user()->id;

                if($file = $request->file("imagen")){
                    $url_imagen = time() ."-". $file->getClientOriginalName();
                    $file->move("imagenes/", $url_imagen);
                    $direccion_imagen = "imagenes/". $url_imagen;
                    $museo->imagen = $direccion_imagen;
                }

                $museo->update();
    
                DB::commit();
    
                return response()->json(["mensaje" => "Museo Actualizado correctamente"], 201);
    
            
        } catch (\Exception $e) {
            //throw $th;
            return response()->json(["mensaje" => "Error al realizar la petición", "error" => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $museo = Museo::find($id);
            // $museo->delete();
            return response()->json(["mensaje" => "Museo Eliminado SIMULADO"], 200);

        } catch (\Exception $e) {
            //throw $th;
            return response()->json(["mensaje" => "Error al realizar la petición", "error" => $e->getMessage()], 500);
        }
    }

    public function funComentar($id, Request $request)
    {
        try {

            $user_id = $request->user()->id;

            $museo = Museo::find($id);
            $museo->comentarios()->attach([$user_id => [
                "comentario" => $request->comentario,
                "calificacion" => $request->calificacion,
                "fecha" => date("Y-m-d H:i:s")
            ]]);
            return response()->json(["mensaje" => "Comentario Registrado"], 201);
        } catch (\Exception $e) {
            //throw $th;
            return response()->json(["mensaje" => "Error al realizar la petición", "error" => $e->getMessage()], 500);
        }
    }
}
