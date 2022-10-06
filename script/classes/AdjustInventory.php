<?php

declare(strict_types=1);

class AdjustInventory {

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
     * @param  $name
     */
    public function ajustInventItem($itemId, $quantity){
        
        $db = new Database();

        $upInvIt = $db->_exec("UPDATE tb_inventory_itens SET ivt_quantity = {$quantity} WHERE ivt_inventoryItensId = {$itemId}");
        if($upInvIt == true){
            return "OK";
        }else{
            return "ERRO: " . $upInvIt;
        }
    }
 
}
