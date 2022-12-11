<?php

declare(strict_types=1);

include("./db_mongo.php");

class User {

    /** @var number */
    public number $user_id;

    /** @var String */
    public String $name;

    /** @var String */
    public String $email;

    /** @var String */
    public String $senha;

    /**
     * @param  $name
     */
    public function createUser($name, $email, $password){
        
        $db = new DBMongo(); 
    
        $doc = [ "usu_nome" => $name, "usu_email" => $email, "usu_password" => md5($password) ];
        $table = "tb_users";

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
    public function updateUser($user_id, $name, $email, $password){
        $db = new DBMongo();  
        $table = "tb_users";
        $doc = [ "usu_nome" => $name, "usu_email" => $email, "usu_password" => md5($password) ];

        $upUsu = $db->update($user_id, $doc, $table);
        if($upUsu == true){
            return "OK";
        }else{
            return "ERRO: " . $upUsu;
        }
    }
    
    /**
     * @param  $id, $name
     */
    public function deleteUser($user_id){
        
        $db = new DBMongo();  

        $table = "tb_users";
        $delUsu = $db->delete($user_id, $table);

        if($delUsu == true){
            return "OK";
        }else{
            return "ERRO: " . $delUsu;
        }

    }

    /**
     * @param  $id, $name
     */
    public function getUser($user_id){
        
        $db = new DBMongo(); 
        
        $table = "tb_users";
       
        $getUsu = $db->search($user_id, $table);

        return $getUsu;

    }

    /**
     * @param  $id, $name
     */
    public function listaUser(){

        $db = new DBMongo(); 
    
        $field = "usu_nome";
        $table = "tb_users";
        $usu = $db->searchAll($field, $table);

        return $usu;

    }

}
