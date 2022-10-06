<?php

declare(strict_types=1);

include("./db.php");

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
        
        $db = new Database();

        $loc = $db->_query("SELECT iv_location FROM tb_inventory WHERE iv_inventoryId = {$id}");
        
        return $loc;
    }

    /**
     * @param  $location
     */
    public function createInventory($location){
        $db = new Database();
        
        $invent = $db->_exec("INSERT INTO tb_inventory (iv_location) VALUES ('{$location}') ");
        
        if($invent == true){
            return "OK";
        }else{
            return "ERRO: " . $invent;
        }
    }

     /**
     * @param  $name
     */
    public function editInvent($id, $location){
        $db = new Database();

        $upInv = $db->_exec("UPDATE tb_inventory SET iv_location = '{$location}' WHERE iv_inventoryId = {$id}");
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
        $db = new Database();

        $delInv = $db->_exec("DELETE FROM tb_inventory WHERE iv_inventoryId = {$id} ");
        
        if($delInv == true){
            return "OK";
        }else{
            return "ERRO: " . $upInv;
        }   
    }

    /************************************************************************************************** 
     * Funções para inclusão de produtos no inventario                                                *
    /**************************************************************************************************/
    /**
     * @param  $id
     */
    public function getItem($id){
        $db = new Database();

        $itn = $db->_query("SELECT * FROM tb_inventory_itens WHERE ivt_inventoryItensId = {$id}");
        
        return $itn;
    }

    /**
     * @param  $location
     */
    public function createInventoryItems($id, $productId, $quantity ){
        $db = new Database();

        $inventIte = $db->_exec("INSERT INTO tb_inventory_itens (ivt_inventoryId, ivt_productId, ivt_quantity) 
                                    VALUES({$id}, {$productId}, {$quantity}) ");
        
        if($inventIte == true){
            return "OK";
        }else{
            return "ERRO: " . $inventIte;
        }
    }

     /**
     * @param  $name
     */
    public function editInventItem($itemId, $quantity){
        $db = new Database();

        $upInvIt = $db->_exec("UPDATE tb_inventory_itens SET ivt_quantity = {$quantity} WHERE ivt_inventoryItensId = {$itemId}");
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
        $db = new Database();

        $delInvIt = $db->_exec("DELETE FROM tb_inventory_itens WHERE ivt_inventoryItensId = {$itemId} ");
            
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

        $db = new Database(); 

        $pro = $db->_query("SELECT * FROM tb_inventory ORDER BY iv_inventoryId");
        
        return $pro;
    }

    /**
    * @param 
    */
    public function listaInventoryItens(){

        $db = new Database(); 

        $pro = $db->_query("SELECT * FROM tb_inventory_itens 
                            INNER JOIN tb_products ON pr_productId = ivt_productId 
                            INNER JOIN tb_inventory ON iv_inventoryId = ivt_inventoryId 
                            ORDER BY ivt_inventoryId, ivt_productId");
        
        return $pro;
    }
}
