<?php

declare(strict_types=1);

include("./db.php");

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

        $db = new Database();
        $cod = $db->_query("SELECT * FROM tb_sales WHERE sl_saleId = {$id}");
        
        if(count($cod) > 0 ){

            foreach($cod as $c){
                $date = $c["sl_date"];
                $finalPrice = $c["sl_finalPrice"];
                $customerId = $c["sl_customerId"];
            }

            $numInvoice = $Invoice->createInvoice($date, $finalPrice);

            $numTransaction = $Transaction->createTransaction($customerId, $type, $numInvoice, $message, $date);

            $addInv = $db->_exec("UPDATE tb_sales SET sl_invoiceId = {$numInvoice}, sl_paymentId = {$type}, sl_statusPayment = 1 WHERE sl_saleId = {$id}");
            
            $ivt = $db->_query("SELECT ivt_inventoryItensId, ivt_quantity, sli_quantity FROM tb_inventory_itens
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
            }
            
        }else{
            return "PEDIDO NAO ENCONTRADO!";
        }
    }

    /**
     * @param  $name
     */
    public function createSale($customer_id, $seller_id, $date){
        $db = new Database();
        
        $sale = $db->_exec("INSERT INTO tb_sales (sl_customerId, sl_sellerId, sl_date, sl_finalPrice, sl_quantity, sl_invoiceId, sl_statusPayment)
                            VALUES({$customer_id}, {$seller_id}, '{$date}', 0.0, 0, NULL, 2) ");
        $numSale = $db->_query("SELECT sl_saleId 
                                FROM tb_sales 
                                WHERE sl_customerId = {$customer_id} 
                                AND sl_sellerId = {$seller_id} 
                                AND sl_date = '{$date}' 
                                AND sl_quantity = 0 
                                ORDER BY sl_saleId DESC LIMIT 1");
        foreach($numSale as $n){
            $codSale = $n["sl_saleId"];
        }

        if($sale == true){
            return $codSale;
        }else{
            return 0;
        }
    }

     /**
     * @param  $name
     */
    public function editItens($id, $product_id, $quantity, $type){
        $db = new Database();

        //insere ou altera 
        if($type == 1){

            $tot = $db->_query("SELECT pr_price FROM tb_products WHERE pr_productId = {$product_id}");
            if(count($tot) > 0 ){
                foreach($tot as $t){
                    $totPrice = $t["pr_price"] * $quantity;
                }
            }else{
                $totPrice = 0;
            } 

            $item = $db->_exec("INSERT INTO tb_sale_items (sli_saleId, sli_productId, sli_quantity, sli_totalPrice)
                                 VALUES ({$id}, {$product_id}, {$quantity}, {$totPrice}) ");
            
            $getPrice = $db->_query("SELECT sli_quantity, sli_totalPrice FROM tb_sale_items WHERE sli_saleId = {$id} ");
            
            $totPricePed = 0;
            $totQtde = 0;

            if(count($getPrice) > 0 ){
                foreach($getPrice as $g){
                    $totPricePed += $g["sli_totalPrice"];
                    $totQtde += $g["sli_quantity"];
                }
            }else{
                $totPricePed = 0;
            }

            $updPrice = $db->_exec("UPDATE tb_sales SET sl_finalPrice = {$totPricePed}, sl_quantity = {$totQtde} WHERE sl_saleId = {$id} ");
            
            if($item == true){
                return "OK";
            }else{
                return "ERRO: " . $item;
            }
        }else{
            $totPrice = 0;
            $totPricePed = 0;
            $totQtde = 0;

            $tot = $db->_query("SELECT pr_price FROM tb_products WHERE pr_productId = {$product_id}");
            if(count($tot) > 0 ){
                foreach($tot as $t){
                    $totPrice = $t["pr_price"] * $quantity;
                }
            }else{
                $totPrice = 0;
            }

            $upItem = $db->_exec("UPDATE tb_sale_items SET sli_quantity = {$quantity}, sli_totalPrice = {$totPrice} 
                                    WHERE sli_saleItemId = {$product_id} AND sli_saleId = {$id} ");
            
            $getPrice = $db->_query("SELECT sli_quantity, sli_totalPrice FROM tb_sale_items WHERE sli_saleId = {$id} ");
            
            if(count($getPrice) > 0 ){
                foreach($getPrice as $g){
                    $totPricePed = $totPricePed + $g["sli_quantity"] * $g["sli_totalPrice"];
                    $totQtde = $totQtde + $g["sli_quantity"];
                }
            }else{
                $totPricePed = 0;
            }
                                    
            $updPrice = $db->_exec("UPDATE tb_sales SET sl_finalPrice = {$totPricePed}, sl_quantity = {$totQtde} WHERE sl_saleId = {$id} ");
                        
            if($upItem == true){
                return "OK";
            }else{
                return "ERRO: " . $upItem;
            }
        }
    }

    /**
     * @param  $name
     */
    public function deleteItens($product_id){
        $db = new Database();

        $delItem = $db->_exec("DELETE FROM tb_sale_items WHERE sli_saleItemId = {$product_id} ");
            
        if($delItem == true){
            return "OK";
        }else{
            return "ERRO: ". $delItem;
        }
    }

    /**
     * @param  $name
     */
    public function deleteSale($id){
        $db = new Database();

        $delItensSale = $db->_exec("DELETE FROM tb_sale_items WHERE sli_saleId = {$id}");
        $delSale = $db->_exec("DELETE FROM tb_sales WHERE sl_saleId = {$id}");
            
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
