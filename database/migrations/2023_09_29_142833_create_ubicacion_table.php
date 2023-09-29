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
        Schema::create('ubicacion', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100);
            $table->string('referencia')->nullable();
            $table->string('link')->nullable();
            $table->decimal('latitud', 7,8);
            $table->decimal('longitud', 7,8);

            $table->unsignedBigInteger("cliente_id");
            $table->foreign('cliente_id')->on('users')->references('id')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ubicacion');
    }
};
