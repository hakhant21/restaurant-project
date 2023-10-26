<?php

namespace App\Http\Controllers\Carts;

use App\Http\Controllers\Controller;
use charlieuki\ReceiptPrinter\Item;

use charlieuki\ReceiptPrinter\ReceiptPrinter;
use charlieuki\ReceiptPrinter\Store;
use Exception;
use Illuminate\Http\Request;
use Mike42\Escpos\Printer;

use Mike42\Escpos\CapabilityProfile;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\CupsPrintConnector;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;

class ReceiptController extends ReceiptPrinter
{ 
    public $promotion_discount;
    public $total_amount;
    public $order_number;
    public $changed;
    public $paid_by;
    public $printer;
    public $total_discount;
    public $total_sub_amount;
    public $service_charge;
    private $logo;
    private $store;
    private $items;
    private $currency = 'MMK';
    private $subtotal = 0;
    private $tax_percentage = 10;
    private $tax = 0;
    private $grandtotal = 0;
    private $request_amount = 0;
    private $qr_code = [];
    private $transaction_id = '';
    function __construct() {
        $this->printer = null;
        $this->items = [];
    }
    public function init($connector_type, $connector_descriptor, $connector_port = 9100) {
        switch (strtolower($connector_type)) {
            case 'cups':
                $connector = new CupsPrintConnector($connector_descriptor);
                break;
            case 'windows':
                $connector = new WindowsPrintConnector($connector_descriptor);
                break;
            case 'network':
                $connector = new NetworkPrintConnector($connector_descriptor);
                break;
            default:
                $connector = new FilePrintConnector("php://stdout");
                break;
        }

        if ($connector) {
            // Load simple printer profile
            $profile = CapabilityProfile::load("default");
            // Connect to printer
            $this->printer = new Printer($connector, $profile);
        } else {
            throw new Exception('Invalid printer connector type. Accepted values are: cups');
        }
    }
    public function close() {
        if ($this->printer) {
            $this->printer->close();
        }
    }

    public function setStore($mid, $name, $address, $phone, $email, $website) {
        $this->store = new Store($mid, $name, $address, $phone, $email, $website);
    }

    public function setLogo($logo) {
        $this->logo = $logo;
    }
    public function addItem($name, $qty, $price) {
        $item = new ItemPrint($name, $qty, $price);
        $item->setCurrency($this->currency);
        
        $this->items[] = $item;
    }

    public function setTax($tax) {
        $this->tax_percentage = $tax;
        
        if ($this->subtotal == 0) {
            $this->calculateSubtotal();
        }

        $this->tax = (int) $this->tax_percentage / 100 * (int) $this->subtotal;
    }
    public function setQRcode($content) {
        $this->qr_code = $content;
    }

    public function setTextSize($width = 1, $height = 1) {
        if ($this->printer) {
            $width = ($width >= 1 && $width <= 8) ? (int) $width : 1;
            $height = ($height >= 1 && $height <= 8) ? (int) $height : 1;
            $this->printer->setTextSize($width, $height);
        }
    }


    public function setPromotionDiscount($discount)
    {
        $this->promotion_discount = $discount;
    }
    public function setCalculateTotal($total_amount)
    {
        $this->total_amount = $total_amount;
    }
    public function setOrderNumber($order_number)
    {
        $this->order_number = $order_number;
    }
    public function setServiceCharge($service_charge)
    {
        $this->service_charge = $service_charge;
    }
    public function setTotalDiscount($total_discount)
    {
        $this->total_discount = $total_discount;
    }
    public function setPaidBy($paid_by)
    {
        $this->paid_by = $paid_by;
    }
    public function setChanged($changed)
    {
        $this->changed = $changed;
    }
    public function setCalculateTax($tax)
    {
        $this->tax = $tax;
    }
    public function setCalculateSubTotal($total_sub_amount)
    {   
       $this->total_sub_amount = $total_sub_amount; 
    }
    public function getPrintableQRcode() {
        return json_encode($this->qr_code);
    }

    public function getPrintableHeader($left_text, $right_text, $is_double_width = false) {
        $cols_width = $is_double_width ? 8 : 16;

        return str_pad($left_text, $cols_width) . str_pad($right_text, $cols_width, ' ', STR_PAD_LEFT);
    }

    public function getPrintableSummary($label, $value, $is_double_width = false) {
        $left_cols = $is_double_width ? 6 : 12;
        $right_cols = $is_double_width ? 10 : 20;

        $formatted_value = number_format($value, 0, ',', ',').$this->currency ;

        return str_pad($label, $left_cols) . str_pad($formatted_value, $right_cols, ' ', STR_PAD_LEFT);
    }

    public function feed($feed = NULL) {
        $this->printer->feed($feed);
    }

    public function cut() {
        $this->printer->cut();
    }

    public function printDashedLine() {
        $line = '';

        for ($i = 0; $i < 32; $i++) {
            $line .= '-';
        }

        $this->printer->text($line);
    }

    public function printLogo() {
        if ($this->logo) {
            $image = EscposImage::load($this->logo, false);

            //$this->printer->feed();
            //$this->printer->bitImage($image);
            //$this->printer->feed();
        }
    }

    public function printQRcode() {
        if (!empty($this->qr_code)) {
            $this->printer->qrCode($this->getPrintableQRcode(), Printer::QR_ECLEVEL_L, 8);
        }
    }
    public function printReceipt($with_items = true) {
        if ($this->printer) {
            $grand_total_amount = $this->getPrintableSummary('Grand Total', $this->total_amount);
            $tax = $this->getPrintableSummary('Tax', $this->tax);
            $paid_by = $this->getPrintableSummary('Paid By', $this->paid_by);
            $changed = $this->getPrintableSummary('Changed', $this->changed);
            $total_discount = $this->getPrintableSummary('Discount(-)', $this->total_discount);
            $promotion_discount = $this->getPrintableSummary('Promotion(-)', $this->promotion_discount);
            $service_charge = $this->getPrintableSummary('Service(+)', $this->service_charge);
            $sub_total = $this->getPrintableSummary('TOTAL', $this->total_sub_amount);
            $header = $this->getPrintableHeader(
                'TID:' . $this->order_number,
                " Ph: ". $this->store->getPhone()
            );
          
            $footer = "Thank you for your order!\n";
            // Init printer settings
            $this->printer->initialize();
            $this->printer->selectPrintMode();
            // Set margins
            $this->printer->setPrintLeftMargin(2);
            // Print receipt headers
            $this->printer->setJustification(Printer::JUSTIFY_CENTER);
            // Print logo
            $this->printLogo();
            $this->printer->selectPrintMode();
            $this->printer->feed(2);
            $this->printer->text("{$this->store->getName()}\n");
            $this->printer->text("{$this->store->getEmail()}\n");
            $this->printer->selectPrintMode();
            $this->printer->text("{$this->store->getAddress()}\n");
            $this->printer->feed();
        
          
            $this->printer->text($header . "\n");
            $this->printer->feed();
            // Print receipt title
            $this->printer->setEmphasis(true);
            $this->printer->text("RECEIPT\n");
            $this->printer->setEmphasis(false);
            $this->printer->feed();
            // Print items
            if ($with_items) {
                $this->printer->setJustification(Printer::JUSTIFY_LEFT);
                foreach ($this->items as $item) {
                    $this->printer->text($item);
                }
                $this->printer->feed();
            }
            // Print subtotal
            $this->printer->setEmphasis(true);
            $this->printer->text($grand_total_amount);
            $this->printer->setEmphasis(false);
            $this->printer->feed();
            // Print tax
            
            $this->printer->text($tax);
            $this->printer->feed();
            $this->printer->text($total_discount);
            $this->printer->feed();
            $this->printer->text($promotion_discount);
            $this->printer->feed();
            $this->printer->text($service_charge);
            $this->printer->feed(2);
            // Print grand total
            $this->printer->text($paid_by);
            $this->printer->feed();
            $this->printer->text($sub_total);
            $this->printer->feed();
            $this->printer->selectPrintMode();
            $this->printer->text($changed);
            $this->printer->feed();
            $this->printer->selectPrintMode();
            $this->printer->feed();
            $this->printer->setJustification(Printer::JUSTIFY_CENTER);
            $this->printer->feed();
            $this->printer->text($footer);
            $this->printer->feed();
            // Print receipt date
            $this->printer->text(date('j F Y H:i:s'));
            $this->printer->feed(2);
            // Cut the receipt
            $this->printer->cut();
            $this->printer->close();
        } else {
            throw new Exception('Printer has not been initialized.');
        }
    }
    
}
