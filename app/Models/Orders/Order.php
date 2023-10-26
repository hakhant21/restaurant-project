<?php

namespace App\Models\Orders;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $fillable = ['order_number','total_amount','order_date', 'tax', 'promotion', 'grand_total', 'service_charges', 'discount', 'payment_type'];
    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class, 'order_id');
    }

    public function generateInvoiceNumber()
    {
        $currentDate = now();

        // Get the year, month, and day from the current date
        $year = $currentDate->format('Y');
        $month = $currentDate->format('m');
        $day = $currentDate->format('d');
        $maxInvoiceNumber = $this
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->get()->count()+1;

        // Generate the invoice number with the format I#year-month-day-serial_number
        $invoiceNumber = 'INVOICE#' . $year . '-' . $month . '-' . $day . '#' . str_pad($maxInvoiceNumber, 6, '0', STR_PAD_LEFT);

        return $invoiceNumber;
    }
}
