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
        Schema::create('purchase_invoice_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('invoice_id');
            $table->unsignedBigInteger('medicineId');
            $table->unsignedBigInteger('supplier_id');
            $table->date('expire_date'); 
            $table->integer('qty'); 
            $table->float('price'); 
            $table->float('total');
            $table->foreign('invoice_id')->references('id')->on('purchase_invoices')->onDelete('cascade');
            $table->foreign('medicineId')->references('id')->on('medicines')->onDelete('cascade');
            $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_invoice_details');
    }
};
