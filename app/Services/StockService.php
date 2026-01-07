<?php

namespace App\Services;

use App\Models\Stock;
use Illuminate\Support\Facades\Auth;

class StockService
{
    /**
     * Add stock (Purchase or Return In)
     *
     * @param int $medicineId
     * @param int $qty
     * @param string $date
     * @param string $expireDate
     * @param string $invNo
     * @param int $refId
     * @param string $type ('purchase', 'sales_return', etc.)
     * @return Stock
     */
    public function addStock($medicineId, $qty, $date, $expireDate, $invNo, $refId, $type = 'purchase')
    {
        return $this->createStockEntry($medicineId, abs($qty), $date, $expireDate, $invNo, $refId, $type);
    }

    /**
     * Reduce stock (Sale or Purchase Return)
     *
     * @param int $medicineId
     * @param int $qty
     * @param string $date
     * @param string $expireDate
     * @param string $invNo
     * @param int $refId
     * @param string $type ('sales', 'purchase_return', etc.)
     * @return Stock
     */
    public function reduceStock($medicineId, $qty, $date, $expireDate, $invNo, $refId, $type = 'sales')
    {
        // For reduction, we store negative quantity
        return $this->createStockEntry($medicineId, -abs($qty), $date, $expireDate, $invNo, $refId, $type);
    }

    /**
     * Create raw stock entry
     */
    private function createStockEntry($medicineId, $qty, $date, $expireDate, $invNo, $refId, $type)
    {
        // Enforce lowercase type for consistency
        $normalizedType = strtolower($type);

        return Stock::create([
            'medicineId'  => $medicineId,
            'qty'         => $qty,
            'date'        => $date,
            'expire_date' => $expireDate,
            'inv_no'      => $invNo,
            'ref_id'      => $refId,
            'type'        => $normalizedType,
            'updated_by'  => Auth::id() ?? 1, // Fallback to 1 if no auth (e.g. seeder)
        ]);
    }
}
