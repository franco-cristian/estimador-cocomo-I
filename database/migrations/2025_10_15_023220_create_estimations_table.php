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
        Schema::create('estimations', function (Blueprint $table) {
            $table->id();
            $table->string('project_name');

            // Entradas del cálculo
            $table->float('kloc');
            $table->float('salario_mensual');
            $table->string('modo');
            $table->json('factores_costo');

            // Resultados del cálculo
            $table->float('eaf', 8, 4); // 8 dígitos en total, 4 decimales
            $table->float('pm', 12, 2); // 12 dígitos en total, 2 decimales
            $table->float('duracion', 12, 2);
            $table->float('personal', 12, 2);
            $table->float('costo_total', 16, 2);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estimations');
    }
};