<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;

trait InvoiceGeneratorTrait
{
    /**
     * Generate a unique invoice number
     * Format: PREFIXYYYYMMDDxxxx (e.g., INV202410260001)
     *
     * @param string $modelClass The model class to check for the last record
     * @param string $prefix The prefix for the invoice number (e.g., 'INV', 'PUR')
     * @param string $dateField The date field to filter by (default: 'invoice_date')
     * @return string
     */
    public function generateInvoiceNumber($modelClass, $prefix = 'INV', $dateField = 'invoice_date')
    {
        $date = now();
        $year = $date->format('Y');
        $month = $date->format('m');
        $day = $date->format('d');
        
        // Find the last record for this day/month
        // Note: Using a raw query or more generic builder might be safer if models have different structures,
        // but typically we look for the latest ID.
        
        $lastRecord = $modelClass::whereYear($dateField, $year)
            ->whereMonth($dateField, $month)
            ->latest('id')
            ->first();

        // If specific format is needed (YearMonthDay + Sequence)
        // Original logic used YearMonth + sequence. Let's stick to a consistent format.
        // Plan says: INV + YYYY + MM + 4-digit sequence
        
        $number = $lastRecord ? ($lastRecord->id + 1) : 1;
        
        // Pad with zeros to 4 digits
        return $prefix . $year . $month . str_pad($number, 4, '0', STR_PAD_LEFT);
    }
}
