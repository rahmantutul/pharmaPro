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
        Schema::create('stocks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('medicineId');
            $table->string('inv_no');
            $table->integer('qty');
            $table->integer('ref_id');
            $table->date('date');
            $table->date('expire_date');
            $table->string('type')->comment('Sales','Purchase','Return','Demurrage','Others');
            $table->unsignedBigInteger('updated_by');
            $table->foreign('medicineId')->references('id')->on('medicines')->onDelete('cascade');
            $table->foreign('updated_by')->references('id')->on('admins')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stocks');
    }
};
