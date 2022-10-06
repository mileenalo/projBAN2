<!doctype html>
<html>

<head>
<meta charset="utf-8">
<title>Sistema de Vendas - Login</title>
<link href="enc/estilo.css" rel="stylesheet" type="text/css">
</head>

<body>

<div id="content">

    <div id="calculadora">
        <form name="calc" method="POST" enctype="multipart/form-data" class="form" action="auth.php">
            <label>
                <span class="span">Usuário</span>
                <br/>
                <input type="text" id="user" name="user" class="in" placeholder="mail@exemple.com"/>
            </label>
            
            <br/>
            
            <label>
                <span class="span">Senha</span>
                <br/>
                <input type="password" id="password" name="password" class="in" placeholder="******"/>
            </label>
            
            </br>
            <input type="submit" name="login" value="LOGIN" class="btn">
        </form>
    </div>
</div> 
</body>
</html>