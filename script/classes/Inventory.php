<?php

declare(strict_types=1);

include("./db_mongo.php");

class Inventory {

    /** @var number */
    public number $id;

    /** @var String */
    public String $location;

    /** @var number */
    public number $itemId;

    /** @var number */
    public number $quantity;

    /** @var number */
    public number $productId;

    //Funções para estoque
    /**
     * @param  $id
     */
    public function getLocation($id){
        $db = new DBMongo(); 
        
        $table = "tb_inventory";
       
        $loc = $db->search($id, $table);

        return $loc;
    }

    /**
     * @param  $location
     */
    public function createInventory($location){
        $db = new DBMongo(); 
    
        $doc = [ "iv_location" => $location ];
        $table = "tb_inventory";

        $invent = $db->insert($doc, $table);
       
        if($invent != ""){
            return "OK";
        }else{
            return "ERRO";
        }

    }

     /**
     * @param  $name
     */
    public function editInvent($id, $location){
        $db = new DBMongo();  
        
        $table = "tb_inventory";
        $doc = [ "iv_location" => $location ];

        $upInv = $db->update($id, $doc, $table);
        if($upInv == true){
            return "OK";
        }else{
            return "ERRO: " . $upInv;
        }
    }

    /**
     * @param  $name
     */
    public function deleteInvent($id){
        $db = new DBMongo();  

        $table = "tb_inventory";
        $delInv = $db->delete($id, $table);

        if($delInv == true){
            return "OK";
        }else{
            return "ERRO: " . $delInv;
        }
    }

    /************************************************************************************************** 
     * Funções para inclusão de produtos no inventario                                                *
    /**************************************************************************************************/
    /**
     * @param  $id
     */
    public function getItem($id){
        $db = new DBMongo(); 
        
        $table = "tb_inventory_items";
       
        $itn = $db->search($id, $table);

        return $itn;
    }

    /**
     * @param  $location
     */
    public function createInventoryItems($id, $productId, $quantity ){
        $db = new DBMongo(); 
    
        $doc = [ "ivt_inventoryId" => new MongoDB\BSON\ObjectId($id), "ivt_productId" => new MongoDB\BSON\ObjectId($productId), "ivt_quantity" => $quantity ];
        $table = "tb_inventory_items";

        $inventIte = $db->insert($doc, $table);
       
        if($inventIte != ""){
            return "OK";
        }else{
            return "ERRO";
        }
    }

     /**
     * @param  $name
     */
    public function editInventItem($itemId, $quantity){
        $db = new DBMongo();  
        
        $table = "tb_inventory_items";
        $doc = [ "ivt_quantity" => $quantity ];

        $upInvIt = $db->update($itemId, $doc, $table);
        if($upInvIt == true){
            return "OK";
        }else{
            return "ERRO: " . $upInvIt;
        }
    }

    /**
     * @param  $name
     */
    public function deleteInventItem($itemId){
        $db = new DBMongo();  

        $table = "tb_inventory_items";
        $delInvIt = $db->delete($itemId, $table);

        if($delInvIt == true){
            return "OK";
        }else{
            return "ERRO: " . $delInvIt;
        }
    }

    /**
    * @param 
    */
    public function listaInventory(){

        $db = new DBMongo(); 
    
        $field = "iv_location";
        $table = "tb_inventory";
        $lct = $db->searchAll($field, $table);

        return $lct;
    }

    /**
    * @param 
    */
    public function listaInventoryItens(){
        
        $aggregate = [
            'aggregate' => 'tb_inventory_items',
            'cursor' => new stdClass,
            'pipeline' => [
                ['$lookup' => ["from" => "tb_inventory","localField" => "ivt_inventoryId","foreignField" => "_id","as" => "inventory_itens"]],
                ['$lookup' => ["from" => "tb_products","localField" => "ivt_productId","foreignField" => "_id","as" => "product_itens"]],
            ], 
        ];
        
        $db = new DBMongo(); 

        $pro = $db->innerSelect($aggregate);
        
        return $pro;
    }
}
