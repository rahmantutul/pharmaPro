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
        Schema::create('purchase_returns', function (Blueprint $table) {
            $table->id();
            $table->string('inv_no', 50)->unique()->index();
            $table->unsignedBigInteger('medicineId');
            $table->unsignedBigInteger('supplierId');
            $table->date('return_date');
            $table->decimal('qty', 10, 2);
            $table->decimal('price', 10, 2);
            $table->decimal('total', 12, 2);
            $table->timestamps();

            // Foreign keys
            $table->foreign('medicineId')->references('id')->on('medicines')->onDelete('cascade');
            $table->foreign('supplierId')->references('id')->on('suppliers')->onDelete('cascade');

            // Indexes for better performance
            $table->index('return_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_returns');
    }
};