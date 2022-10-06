<?php

declare(strict_types=1);

include("./db.php");

class Customer {

    /** @var number */
    public number $customer_id;

    /** @var String */
    public String $name;

    /**
     * @param  $name
     */
    public function createCustomer($name){
        
        $db = new Database(); 

        $customer = $db->_exec("INSERT INTO tb_customer (cs_name) VALUES ('{$name}') ");
        
        if($customer == true){
            return "OK";
        }else{
            return "ERRO";
        }
    }

    /**
     * @param  $id, $name
     */
    public function updateCustomer($customer_id, $name){
        
        $db = new Database(); 

        $upCus = $db->_exec("UPDATE tb_customer SET cs_name = '{$name}' WHERE cs_customerId = {$customer_id}");
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
        
        $db = new Database(); 

        $delCus = $db->_exec("DELETE FROM tb_customer WHERE cs_customerId = {$customer_id}");
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
        
        $db = new Database(); 

        $selCus = $db->_query("SELECT * FROM tb_customer WHERE cs_customerId = {$customer_id}");
       
        return $selCus;

    }

    /**
     * @param  $id, $name
     */
    public function listaCustomer(){

        $db = new Database(); 

        $cus = $db->_query("SELECT * FROM tb_customer ORDER BY cs_customerId");
        
        return $cus;

    }

}
