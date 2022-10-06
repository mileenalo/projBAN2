<?php

declare(strict_types=1);

include("./db.php");

class Product {

    /** @var number */
    public number $id;

    /** @var String */
    public String $description;

    /** @var number */
    public number $price;

    /** @var String */
    public String $details;

    /**
     * @param  $id
     */
    public function getProduct($id){
        $db = new Database();

        $prod = $db->_query("SELECT * FROM tb_products WHERE pr_productId = {$id}");
        
        return $prod;
        
    }

    /**
     * @param  $name
     */
    public function createProduct($description, $price, $detail){
        $db = new Database();
        
        $prodct = $db->_exec("INSERT INTO tb_products (pr_description, pr_price, pr_detail)
                                VALUES ('{$description}', {$price}, '{$detail}') ");
        
        if($prodct == true){
            return "OK";
        }else{
            return "ERRO: " . $prodct;
        }
    }

     /**
     * @param  $name
     */
    public function editProduct($id, $description, $price, $detail){
        
        $db = new Database();
    
        $upProd = $db->_exec("UPDATE tb_products SET pr_description = '{$description}', pr_price = {$price}, pr_detail = '{$detail}' WHERE pr_productId = {$id} ");
        if($upProd == true){
            return "OK";
        }else{
            return "ERRO: " . $upProd;
        }
    }

    /**
     * @param  $name
     */
    public function deleteProduct($id){
        
        $db = new Database();

        $delProd = $db->_exec("DELETE FROM tb_products WHERE pr_productId = {$id} ");
            
        if($delProd == true){
            return "OK";
        }else{
            return "ERRO: " . $delProd;
        }
    }

    /**
    * @param 
    */
    public function listaProduct(){

        $db = new Database(); 

        $pro = $db->_query("SELECT * FROM tb_products ORDER BY pr_productId");
        
        return $pro;
    }

}
