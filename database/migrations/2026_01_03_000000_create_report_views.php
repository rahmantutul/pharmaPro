<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("
            CREATE OR REPLACE VIEW view_customer_sales_summary AS
            SELECT 
                c.id as custId,
                c.name,
                c.phone,
                COUNT(s.id) as total_invoice,
                SUM(s.grand_total) as grand_total,
                SUM(s.paid_amount) as paid_amount,
                SUM(s.due_amount) as total_due
            FROM customers c
            JOIN sales s ON c.id = s.customerId
            GROUP BY c.id, c.name, c.phone
        ");

        DB::statement("
            CREATE OR REPLACE VIEW view_supplier_purchases_summary AS
            SELECT 
                s.id,
                s.name,
                s.phone,
                COUNT(pi.id) as total_invoice,
                SUM(pi.total_amount) as grand_total,
                SUM(pi.paid_amount) as paid_amount,
                SUM(pi.due_amount) as total_due
            FROM suppliers s
            JOIN purchase_invoices pi ON s.id = pi.supplierId
            GROUP BY s.id, s.name, s.phone
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("DROP VIEW IF EXISTS view_customer_sales_summary");
        DB::statement("DROP VIEW IF EXISTS view_supplier_purchases_summary");
    }
};
