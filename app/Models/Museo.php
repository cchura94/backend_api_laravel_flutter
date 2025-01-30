<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Museo extends Model
{
    public function categoria(){
        return $this->belongsTo(Categoria::class);
    }

    public function comentarios(){
        return $this->belongsToMany(User::class, 'museo_user')
                    ->withPivot(["comentario", "calificacion", "fecha"])
                    ->withTimestamps();
    }

    public function favoritos(){
        return $this->belongsToMany(User::class, "favorito")
                    ->withTimestamps();
    }
}
