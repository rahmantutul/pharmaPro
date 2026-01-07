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
        Schema::create('sale_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('medicineId');
            $table->unsignedBigInteger('salesId');
            $table->date('expiry_date');
            $table->float('sell_price');
            $table->integer('qty');
            $table->float('subtotal');
            $table->float('discount');
            $table->float('total');
            $table->foreign('medicineId')->references('id')->on('medicines')->onDelete('cascade');
            $table->foreign('salesId')->references('id')->on('sales')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sale_details');
    }
};
