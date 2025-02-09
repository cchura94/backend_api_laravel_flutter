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
        Schema::create('favorito', function (Blueprint $table) {
            $table->id();

            $table->bigInteger("museo_id")->unsigned();
            $table->bigInteger("user_id")->unsigned();
            $table->foreign("museo_id")->references("id")->on("museos");
            $table->foreign("user_id")->references("id")->on("users");

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('favorito');
    }
};
