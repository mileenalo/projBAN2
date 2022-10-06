<?php

    include("./db.php");
    include("./script/classes/Log.php");
    
    $user = $_POST['user'];
    $password = md5($_POST['password']);
    
    $db = new Database();
    $Logs = new Logs();
    
    $log = $db->_query("SELECT * FROM tb_users WHERE usu_email = '{$user}' AND usu_password = '{$password}'");

    if(count($log) > 0){
        foreach($log as $l){
            setcookie('u_id', $l["usu_userId"]);

            $Logs->createLog($l["usu_userId"], "Usuário Logado!");
        }
        header('location: ./_pedidos.php');
    }else{
        header('location: ./login.php');
    }

?>
<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Sistema de vendas</title>
        <link href="enc/estilo.css" rel="stylesheet" type="text/css">
    </head>
</html>

