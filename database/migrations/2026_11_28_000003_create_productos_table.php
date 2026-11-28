<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 150)->index();
            $table->string('slug', 180)->unique();
            $table->foreignId('marca_id')->constrained('marcas')->restrictOnDelete();
            $table->foreignId('sistema_id')->constrained('sistemas')->restrictOnDelete();
            $table->string('codigo', 50)->unique();
            $table->string('oem', 50)->nullable()->index();
            $table->decimal('precio', 10, 2)->nullable();
            $table->unsignedInteger('stock')->default(0);
            $table->text('descripcion')->nullable();
            $table->string('aplicacion')->nullable();
            $table->string('imagen_path')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};
