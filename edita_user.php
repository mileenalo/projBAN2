<?php
    include("./scripts/classes/User.php");

    $User = new User();

    $id = $_POST["id"];
    $nome = $_POST["nome"];
    $email = $_POST["email"];
    $senha = $_POST["senha"];

    $res = $User->updateUser($id, $nome, $email, $senha);

    echo $res;
?>