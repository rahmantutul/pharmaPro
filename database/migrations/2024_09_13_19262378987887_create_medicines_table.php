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
        Schema::create('medicines', function (Blueprint $table) {
            $table->id();
            $table->string('qr_code')->nullable();
            $table->string('hns_code')->nullable();
            $table->string('name');
            $table->string('strength')->nullable();
            $table->float('sell_price')->nullable();
            $table->float('purchase_price')->nullable();
            $table->string('generic_name')->nullable();
            $table->string('desc')->nullable();
            $table->string('image');
            $table->unsignedBigInteger('leafId');
            $table->unsignedBigInteger('categoryId');
            $table->unsignedBigInteger('vendorId');
            $table->unsignedBigInteger('supplierId');
            $table->unsignedBigInteger('typeId');
            $table->foreign('leafId')->references('id')->on('leaves')->onDelete('cascade');
            $table->foreign('categoryId')->references('id')->on('categories')->onDelete('cascade');
            $table->foreign('vendorId')->references('id')->on('vendors')->onDelete('cascade');
            $table->foreign('supplierId')->references('id')->on('suppliers')->onDelete('cascade');
            $table->foreign('typeId')->references('id')->on('types')->onDelete('cascade');
            $table->timestamps();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('medicines');
    }
};
