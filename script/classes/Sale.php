<?php

declare(strict_types=1);

include("./db_mongo.php");

class Sale {

    /** @var number */
    public number $id;

    /** @var number */
    public number $customer_id;

    /** @var number */
    public number $seller_id;

    /** @var date */
    public date $date;

    /** @var number */
    public number $final_price;

    /** @var number */
    public number $invoice_id;
    
    /** @var number */
    public number $product_id;

    /** @var number */
    public number $quantity;

    /** @var number */
    public number $type;

    /**
     * @param  $id
     */
    public function listSaleInfo(){
        $db = new Database();
        $cod = $db->_query("SELECT * FROM tb_sales 
                            INNER JOIN tb_customer ON cs_customerId = sl_customerId 
                            INNER JOIN tb_users ON usu_userId = sl_sellerId
                            LEFT JOIN tb_invoices ON in_invoiceId = sl_invoiceId
                            LEFT JOIN tb_sale_items ON sl_saleId = sli_saleId
                            LEFT JOIN tb_products ON sli_productId = pr_productId");
        return $cod;
    }

    /**
     * @param  $id
     */
    public function closeSale($id, $type, $card){
        include_once("Invoice.php");
        include_once("Transactions.php");
        include_once("AdjustInventory.php");

        $Transaction = new Transactions();
        $Invoice = new Invoice();
        $Inventory = new AdjustInventory();

        if($type == 1){
            $message = "Pagamento com cartao de credito final ". substr($card, -4);
        }else if($type == 2){
            $message = "Pagamento com cartao de debito final " . substr($card, -4);
        }else{
            $message = "Pagamento com boleto bancario";
        }

        $db = new DBMongo(); 
        
        $table = "tb_sales";
       
        $cod = $db->search($id, $table);

        foreach($cod as $c){
            $date = $c->sl_date;
            $finalPrice = $c->sl_finalPrice;
            $customerId = $c->sl_customerId;
        }

        $numInvoice = $Invoice->createInvoice($date, $finalPrice);
        $numTransaction = $Transaction->createTransaction($customerId, $type, $numInvoice, $message, $date);
       
        $doc = [ "sl_invoiceId" => new MongoDB\BSON\ObjectId($numInvoice), "sl_statusPayment" => 1 ];

        $upInvIt = $db->update($id, $doc, $table);
        if($upInvIt == true){
            return "OK";
        }else{
            return "ERRO: " . $upInvIt;
        }
       
        /*$ivt = $db->_query("SELECT ivt_inventoryItensId, ivt_quantity, sli_quantity FROM tb_inventory_itens
                            INNER JOIN tb_products ON pr_productId = ivt_productId
                            INNER JOIN tb_sale_items ON sli_productId = pr_productId
                            WHERE sli_saleId = {$id} ");
    
        if(count($ivt) > 0 ){
            $quantity = 0;
            foreach($ivt as $i){
                $quantity = $i["ivt_quantity"] - $i["sli_quantity"];
                $invent = $Inventory->ajustInventItem($i["ivt_inventoryItensId"], $quantity);
            }
        }
        if($addInv == true){
            return "OK";
        }else{
            return "ERRO: ". $addInv;
        }*/

    }

    /**
     * @param  $name
     */
    public function createSale($customer_id, $seller_id, $date){
        $db = new DBMongo(); 
    
        $doc = [ "sl_customerId" => new MongoDB\BSON\ObjectId($customer_id), "sl_sellerId" => new MongoDB\BSON\ObjectId($seller_id), "sl_date" => $date, "sl_finalPrice" => 0.0, "sl_quantity" => 0, "sl_invoiceId" => null, "sl_statusPayment" => 2 ];
        $table = "tb_sales";

        $sale = $db->insert($doc, $table);
    
        return $sale;
    }

     /**
     * @param  $name
     */
    public function editItens($id, $product_id, $quantity, $type){
        $db = new DBMongo(); 

        //insere ou altera 
        if($type == 1){
            $table = "tb_products";
       
            $tot = $db->search($product_id, $table);
            $totPrice = 0;
            foreach($tot as $t){
                $totPrice = $t->pr_price * $quantity;
            }
    
            $doc = [ "sli_saleId" => new MongoDB\BSON\ObjectId($id), "sli_productId" => new MongoDB\BSON\ObjectId($product_id), "sli_quantity" => $quantity, "sli_totalPrice" => $totPrice ];
            $table = "tb_sale_items";

            $item = $db->insert($doc, $table);

            //Busca o preço para tualizar pedido
            $field = "sli_saleId";
            $getPrice = $db->search2($field, $id, $table);

            $totPricePed = 0;
            $totQtde = 0;

            foreach($getPrice as $g){
                $totPricePed += $g->sli_totalPrice;
                $totQtde += $g->sli_quantity;
            }
           
            $table = "tb_sales";
            $doc = [ "sl_finalPrice" => $totPricePed, "sl_quantity" => $totQtde ];
    
            $updPrice = $db->update($id, $doc, $table);
            
            if($item != ""){
                return "OK";
            }else{
                return "ERRO: " . $item;
            }
        }else{
            $totPrice = 0;
            $totPricePed = 0;
            $totQtde = 0;

            $table = "tb_products";
            $tot = $db->search($product_id, $table);

            foreach($tot as $t){
                $totPrice = $t->pr_price * $quantity;
            }

            $table = "tb_sale_items";
            $doc = [ "sli_quantity" => $totPricePed, "sli_totalPrice" => $totQtde ];
    
            $updItem = $db->update2($id, $product_id, "sli_saleItemId", $doc, $table);
         
            //Busca o preço para tualizar pedido
            $field = "sli_saleId";
            $getPrice = $db->search2($field, $id, $table);
           
            foreach($getPrice as $g){
                $totPricePed = $totPricePed + $g->sli_quantity * $g->sli_totalPrice;
                $totQtde = $totQtde + $g->sli_quantity;
            }
            
            $table = "tb_sales";
            $doc = [ "sl_finalPrice" => $totPricePed, "sl_quantity" => $totQtde ];
    
            $updPrice = $db->update($id, $doc, $table);
            
            if($updItem == true){
                return "OK";
            }else{
                return "ERRO: " . $updItem;
            }
        }
    }

    /**
     * @param  $name
     */
    public function deleteItens($product_id){
        $db = new DBMongo();  

        $table = "tb_sale_items";
        $delItem = $db->delete($product_id, $table);

        if($delItem == true){
            return "OK";
        }else{
            return "ERRO: " . $delItem;
        }
    }

    /**
     * @param  $name
     */
    public function deleteSale($id){
        $db = new DBMongo();  

        $table = "tb_sale_items";
        $delItem = $db->delete2($id, "sli_saleId", $table);

        $table = "tb_sales";
        $delSale = $db->delete($id, $table);
        
        if($delSale == true){
            return "OK";
        }else{
            return "ERRO: ". $delSale;
        }
    }

    /**
     * @param  $name
     */
    public function graphSale(){
        $db = new Database();

        $graphSale = $db->_query("SELECT sl_customerId, cs_name, COUNT(sl_saleId) AS qtdPed, SUM(sl_finalPrice) AS totVal, sum(sl_quantity) AS qtd 
                                    FROM tb_sales
                                INNER JOIN tb_customer on cs_customerId = sl_customerId
                                WHERE sl_invoiceId <> ''
                                GROUP BY sl_customerId;");

        return $graphSale;
      
    }
    
     /**
     * @param  $name
     */
    public function graphItensSale(){
        $db = new Database();

        $itensSale = $db->_query("SELECT pr_description, sum(sli_quantity) AS qtdVend, sum(ivt_quantity) qtdEst, iv_location 
                                FROM tb_inventory_itens
                                INNER JOIN tb_products ON pr_productId = ivt_productId
                                INNER JOIN tb_inventory ON iv_inventoryId = ivt_inventoryId
                                INNER JOIN tb_sale_items ON sli_productId = pr_productId
                                INNER JOIN tb_sales ON sl_saleId = sli_saleId AND sl_invoiceId <> ''
                                GROUP BY pr_description");

        return $itensSale;
    }
}
