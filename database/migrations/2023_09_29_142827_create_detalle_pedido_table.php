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
        Schema::create('detalle_pedido', function (Blueprint $table) {
            $table->id();
            $table->integer('cantidad');
            $table->decimal('monto', 10,2);

            $table->unsignedBigInteger("pedido_id");
            $table->unsignedBigInteger("producto_id");

            $table->foreign('pedido_id')->on('pedido')->references('id')->onDelete('cascade');
            $table->foreign('producto_id')->on('producto')->references('id')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detalle_pedido');
    }
};
