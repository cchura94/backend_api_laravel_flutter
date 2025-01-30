<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    // categorias
    // protected $table = 'wp_categoria';
    // // id
    // protected $primaryKey = 'idcat';
    // public $incrementing = false;
    // protected $keyType = 'string';

    // public $timestamps = false;

    public function museos(){
        return $this->hasMany(Museo::class);
    }
}
