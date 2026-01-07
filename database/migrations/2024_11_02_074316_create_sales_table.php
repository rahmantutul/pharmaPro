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
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->date('invoice_date')->index();
            $table->string('invoice_no', 50)->unique();
            $table->unsignedBigInteger('customerId')->nullable();
            $table->boolean('is_walking_customer')->default(0)->comment('1 = Walk-in, 0 = Regular');
            $table->unsignedBigInteger('paymentId');
            $table->decimal('grand_total', 12, 2)->default(0);
            $table->decimal('invoice_discount', 10, 2)->default(0);
            $table->tinyInteger('discount_type')->default(0)->comment('0 = None, 1 = Percentage, 2 = Fixed');
            $table->decimal('payable_total', 12, 2)->default(0);
            $table->decimal('paid_amount', 12, 2)->default(0);
            $table->decimal('due_amount', 12, 2)->default(0);
            $table->enum('status', ['paid', 'partial', 'due', 'cancelled'])->default('paid');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();

            // Foreign keys
            $table->foreign('customerId')->references('id')->on('customers')->onDelete('set null');
            $table->foreign('paymentId')->references('id')->on('payment_methods')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('admins')->onDelete('set null');

            // Indexes for performance
            $table->index('status');
            $table->index('customerId');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};