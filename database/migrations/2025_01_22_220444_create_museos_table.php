<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('museos', function (Blueprint $table) {
            $table->id();

            $table->string("nombre", 200);
            $table->string("imagen")->nullable();
            $table->text("descripcion")->nullable();
            $table->string("horario_atencion")->default("De Lunes a Viernes de 8:00 a 18:00")->nullable();
            $table->string("direccion")->nullable();
            $table->string("latitud")->nullable();
            $table->string("longitud")->nullable();
            $table->decimal("precio", 12, 2)->default(0);
            
            $table->bigInteger("categoria_id")->unsigned();
            $table->bigInteger("user_id")->unsigned();

            $table->foreign("categoria_id")->references("id")->on("categorias");
            $table->foreign("user_id")->references("id")->on("users");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('museos');
    }
};
