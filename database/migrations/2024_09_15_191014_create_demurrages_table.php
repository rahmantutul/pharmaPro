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
        Schema::create('demurrages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('medicineId');
            $table->date('demurrage_date');
            $table->float('price');
            $table->integer('qty');
            $table->float('total');
            $table->foreign('medicineId')->references('id')->on('medicines')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('demurrages');
    }
};
