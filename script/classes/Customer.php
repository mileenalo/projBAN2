<?php

declare(strict_types=1);

include("./db_mongo.php");

class Customer {

    /** @var number */
    public number $customer_id;

    /** @var String */
    public String $name;

    /**
     * @param  $name
     */
    public function createCustomer($name){
        
        $db = new DBMongo(); 
    
        $doc = [ "cs_name" => $name ];
        $table = "tb_customer";

        $customer = $db->insert($doc, $table);
       
        if($customer != ""){
            return "OK";
        }else{
            return "ERRO";
        }
    }

    /**
     * @param  $id, $name
     */
    public function updateCustomer($customer_id, $name){
        
        $db = new DBMongo();  
        $table = "tb_customer";
        $doc = [ "cs_name" => $name ];

        $upCus = $db->update($customer_id, $doc, $table);
        if($upCus == true){
            return "OK";
        }else{
            return "ERRO: " . $upCus;
        }
    }
    
    /**
     * @param  $id, $name
     */
    public function deleteCustomer($customer_id){
        
        $db = new DBMongo();  
        $table = "tb_customer";
        $delCus = $db->delete($customer_id, $table);
        if($delCus == true){
            return "OK";
        }else{
            return "ERRO: " . $delCus;
        }
    }

    /**
     * @param  $id, $name 
     */
    public function getCustomer($customer_id){
        
        $db = new DBMongo(); 
        
        $table = "tb_customer";
       
        $customer = $db->search($customer_id, $table);

        return $customer;

    }

    /**
     * @param  $id, $name
     */
    public function listaCustomer(){

        $db = new DBMongo(); 
    
        $field = "cs_name";
        $table = "tb_customer";
        $customer = $db->searchAll($field, $table);

        return $customer;

    }

}
