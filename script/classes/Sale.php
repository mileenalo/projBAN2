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
        $aggregate = [
            'aggregate' => 'tb_sales',
            'cursor' => new stdClass,
            'pipeline' => [
                ['$lookup' => ["from" => "tb_customer","localField" => "sl_customerId","foreignField" => "_id","as" => "customer_sale"]],
                ['$lookup' => ["from" => "tb_products","localField" => "orderItems","foreignField" => "_id","as" => "product_itens"]],
                ['$lookup' => ["from" => "tb_users","localField" => "sl_sellerId","foreignField" => "_id","as" => "seller_sale"]],
            ], 
        ];
        
        $db = new DBMongo(); 

        $pro = $db->innerSelect($aggregate);
        
        return $pro;

    }

    /**
     * @param  $name
     */
    public function createSale($customer_id, $seller_id, $date, $product_id, $quantity){

        $db = new DBMongo(); 

        $doc = [ 
            "sl_customerId" => new MongoDB\BSON\ObjectId($customer_id), 
            "sl_sellerId" => new MongoDB\BSON\ObjectId($seller_id), 
            "sl_date" => $date, 
            "sl_finalPrice" => 0.0, 
            "sl_quantity" => $quantity, 
            "sl_statusPayment" => 2,
            "orderItems" => [new MongoDB\BSON\ObjectId($product_id)] 
        ];
        $table = "tb_sales";

        return true;
    }

    /**
     * @param  $name
     */
    public function graphSale(){
        $aggregate = [
            'aggregate' => 'tb_sales',
            'cursor' => new stdClass,
            'pipeline' => [
                ['$lookup' => ["from" => "tb_customer","localField" => "sl_customerId","foreignField" => "_id","as" => "customer_sale"]],
                ['$lookup' => ["from" => "tb_products","localField" => "orderItems","foreignField" => "_id","as" => "product_itens"]],
                ['$lookup' => ["from" => "tb_users","localField" => "sl_sellerId","foreignField" => "_id","as" => "seller_sale"]],
            ], 
        ];
        
        $db = new DBMongo(); 

        $graphSale = $db->innerSelect($aggregate);
        
        return $graphSale;
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
