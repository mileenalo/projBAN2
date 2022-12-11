<?php

declare(strict_types=1);

include("./db_mongo.php");

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
        $db = new DBMongo(); 
        
        $table = "tb_products";
       
        $prod = $db->search($id, $table);

        return $prod;
    }

    /**
     * @param  $name
     */
    public function createProduct($description, $price, $detail){
        
        $db = new DBMongo(); 
    
        $doc = [ "pr_description" => $description, "pr_price" => $price, "pr_detail" => $detail ];
        $table = "tb_products";

        $prodct = $db->insert($doc, $table);
       
        if($prodct != ""){
            return "OK";
        }else{
            return "ERRO";
        }

    }

     /**
     * @param  $name
     */
    public function editProduct($id, $description, $price, $detail){
        
        $db = new DBMongo();  
        
        $table = "tb_products";
        $doc = [ "pr_description" => $description, "pr_price" => $price, "pr_detail" => $detail ];

        $upProd = $db->update($id, $doc, $table);
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
        
        $db = new DBMongo();  

        $table = "tb_products";
        $delProd = $db->delete($id, $table);

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

        $db = new DBMongo(); 
    
        $field = "pr_description";
        $table = "tb_products";
        $usu = $db->searchAll($field, $table);

        return $usu;

    }

}
