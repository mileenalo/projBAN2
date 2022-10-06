<?php
    
    include("./db.php");
    include("./script/classes/Log.php");
    
    $db = new Database();
    $Logs = new Logs();

    $user = $_COOKIE['u_id'];

    $Logs->createLog($user, "Sessão finalizada!");
   
    header('location: ./login.php');

?>
<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Sistema de vendas</title>
        <link href="enc/estilo.css" rel="stylesheet" type="text/css">
    </head>
</html>

