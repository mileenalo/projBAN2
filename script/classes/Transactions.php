<?php

declare(strict_types=1);

class Transactions {

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
    public function createTransaction($customerId, $type, $invoiceId, $message, $date){
       
        $db = new DBMongo(); 
    
        $doc = [ "tr_customerId" => new MongoDB\BSON\ObjectId($customerId), "tr_paymentId" => $type, "tr_invoiceId" => new MongoDB\BSON\ObjectId($invoiceId), "tr_date" => $date, "tr_messagem" => $message ];
        $table = "tb_transactions";

        $transaction = $db->insert($doc, $table);
       
        if($transaction == true){
            return "OK";
        }else{
            return "ERRO";
        }
    }

    /**
     * @param  $name
     */
    public function listTransaction(){
        include("./db.php");
        $db = new Database();
        
        $listTrasn = $db->_query("SELECT * FROM tb_transactions 
                            INNER JOIN tb_customer ON cs_customerId = tr_customerId
                            INNER JOIN tb_invoices ON tr_invoiceId = in_invoiceId 
                            INNER JOIN tb_sales ON sl_invoiceId = in_invoiceId
                            INNER JOIN tb_payments ON pa_paymentId = sl_paymentId 
                            ORDER BY tr_transactionId");
        
        return $listTrasn;
    }

}
