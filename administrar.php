<html>
    <head>
        <meta charset="UTF-8">
        <title>Administrar</title>
    </head>
    <body>
        <h1>Administrar</h1>
        <input type='button'value='Regresar al index' onclick="document.location.href='index.php';"/>
        <br>
        <input type='button'value='Editar un Cliente' onclick="document.location.href='editar_usuarios.php';"/>
        <input type='button'value='Editar una cuenta' onclick="document.location.href='editar_cuenta.php';"/>
        <input type='button'value='Editar una tarjeta' onclick="document.location.href='editar_tarjeta.php';"/>
        <input type='button'value='Editar un credito' onclick="document.location.href='editar_credito.php';"/>

    </body>
        
</html>

<?php
session_start();
if (isset($_SESSION['mensaje'])){
    echo $_SESSION['mensaje'];
}
//Asegurarse que el user esta logeado
$_SESSION['logeado'] = true;
if (isset($_SESSION['logeado'])) { 
    echo "<br>";
    echo "<br>";   
} else {
    echo "Este funcion es exclusiva para clientes.";
}

?>