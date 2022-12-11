<?php

declare(strict_types=1);

//include("./db.php");

class Invoice {

    /** @var number */
    public number $invoice_id;

    /** @var number */
    public number $number;

    /** @var number */
    public number $serie;

    /** @var String */
    public String $key;

    /** @var String */
    public String $date;

    /** @var number */
    public number $price;

    /**
     * @param  $name
     */
    public function createInvoice($date, $price){
        $number = "";
        $key = "";

        for($i = 0; $i < 4; $i++){
            $number .= strval(rand(1,9));
        }

        for($i = 0; $i < 9; $i++){
            $key .= strval(rand(1,9));
        }

        $number = intval($number);
        $serie = 4;
        $key = intval($key);

        $db = new DBMongo(); 
    
        $doc = [ "in_number" => $number, "in_serie" => $serie, "in_key" => $key, "in_date" => $date, "in_price" => $price ];
        $table = "tb_invoices";

        $invoice = $db->insert($doc, $table);
    
        return $invoice;

    }

}
