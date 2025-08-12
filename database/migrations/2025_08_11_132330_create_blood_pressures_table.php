<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('blood_pressures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->date('date');
            $table->integer('sistole');
            $table->integer('diastole');
            $table->json('medications')->nullable(); // untuk menyimpan array obat
            $table->enum('status', ['Fit To Work', 'Fit With Medical Therapy', 'Unfit', 'Observasi']);
            $table->text('notes')->nullable();
            $table->timestamps();

            // Index untuk performa
            $table->index(['employee_id', 'date']);
            $table->unique(['employee_id', 'date']); // Satu karyawan satu data per hari
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blood_pressures');
    }
};
