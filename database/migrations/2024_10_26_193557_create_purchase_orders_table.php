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
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->date('order_date');  // Stores date in 'YYYY-MM-DD' format
            $table->time('order_time');  // Stores time in 'HH:MM:SS' format
            $table->unsignedBigInteger('supplierId');  // Stores time in 'HH:MM:SS' format
            $table->float('grand_total');
            $table->tinyInteger('is_invoiced')->default(0)->comment('1=Invoiced, 0=Not invoiced');
            $table->string('order_by');
            $table->foreign('supplierId')->references('id')->on('suppliers')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_orders');
    }
};
