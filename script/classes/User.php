<?php

declare(strict_types=1);

include("./db.php");

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
        
        $db = new Database(); 

        $pass = md5($password);

        $user = $db->_exec("INSERT INTO tb_users (usu_name, usu_email, usu_password) VALUES ('{$name}', '{$email}', '{$pass}') ");
        
        if($user == true){
            return "OK";
        }else{
            return "ERRO";
        }
    }

    /**
     * @param  $id, $name
     */
    public function updateUser($user_id, $name, $email, $password){
        
        $db = new Database(); 
        $pass = md5($password);

        $upUsu = $db->_exec("UPDATE tb_users SET usu_name = '{$name}', usu_email = '{$email}', usu_password = '{$pass}' WHERE usu_userId = {$user_id} ");
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
        
        $db = new Database(); 

        $delUsu = $db->_exec("DELETE FROM tb_users WHERE usu_userId = {$user_id}");
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
        
        $db = new Database(); 

        $getUsu = $db->_query("SELECT * FROM tb_users WHERE usu_userId = {$user_id}");
        
        return $getUsu;

    }

    /**
     * @param  $id, $name
     */
    public function listaUser(){

        $db = new Database(); 

        $usu = $db->_query("SELECT usu_userId, usu_name, usu_email, usu_password FROM tb_users ORDER BY usu_userId");
        
        return $usu;

    }

}
