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
        $db = new Database();
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

        $invoice = $db->_exec("INSERT INTO tb_invoices (in_number, in_serie, in_key, in_date, in_price)
                                 VALUES({$number}, {$serie}, {$key}, '{$date}', {$price}) ");
        $numInv = $db->_query("SELECT in_invoiceId 
                                FROM tb_invoices 
                                WHERE in_number = {$number} 
                                AND in_serie = {$serie} 
                                AND in_key = {$key}
                                AND in_date = '{$date}'
                                AND in_price = {$price}
                                ORDER BY in_invoiceId DESC LIMIT 1");
        foreach($numInv as $n){
            $codInv = $n["in_invoiceId"];
        }

        if($invoice == true){
            return $codInv;
        }else{
            return 0;
        }
    }

}
