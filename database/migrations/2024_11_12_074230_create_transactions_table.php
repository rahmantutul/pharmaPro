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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('medicineId')->nullable();
            $table->unsignedBigInteger('customerId')->nullable();
            $table->unsignedBigInteger('supplierId')->nullable();
            $table->tinyInteger('is_walking_customer')->default(0);
            $table->float('amount')->default('0');
            $table->date('date');
            $table->integer('refId')->nullable();
            $table->string('type')->comment('demurrage,purchase_return,sales_return,customer_due,supplier_due');
            $table->foreign('customerId')->references('id')->on('customers')->onDelete('cascade');
            $table->foreign('supplierId')->references('id')->on('suppliers')->onDelete('cascade');
            $table->foreign('medicineId')->references('id')->on('medicines')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
