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
        Schema::create('purchase_invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_no');
            $table->date('invoice_date');
            $table->unsignedBigInteger('supplierId');
            $table->tinyInteger('discount_type')->comment('1=Fixed Type, 2=Percentage Type')->nullable();
            $table->float('total_discount')->default(0.00);
            $table->float('total_amount');
            $table->float('paid_amount');
            $table->float('due_amount');
            $table->foreign('supplierId')->references('id')->on('suppliers')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_invoices');
    }
};
