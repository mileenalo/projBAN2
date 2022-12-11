<?php

    include("./db_mongo.php");
    include("./script/classes/Log.php");
    
    $user = $_POST['user'];
    $password = md5($_POST['password']);
    
    $db = new DBMongo();
    $Logs = new Logs();
    $table = "tb_users";
       
    $log = $db->search3("usu_email", $user, "usu_password", $password, $table);

    foreach($log as $l){
        setcookie('u_id', $l->_id);
        $Logs->createLog($l->_id, "Usuário Logado!");
    }
    header('location: ./_pedidos.php');

?>
<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Sistema de vendas</title>
        <link href="enc/estilo.css" rel="stylesheet" type="text/css">
    </head>
</html>

