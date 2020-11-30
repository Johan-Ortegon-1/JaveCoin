<?php
    session_start();
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Crear cuenta de ahorros</title>
    </head>
    <body>
        <?php
            $mssCrearCuenta = "";
            $flagError = false;
            $IDUsuario = "";
            $rol = "";
            include_once dirname(__FILE__) . '/config.php';
            $con = mysqli_connect(HOST_DB, USUARIO_DB, USUARIO_PASS, NOMBRE_DB);
            $cuata_general = CUOTA_GENERAL;
            if(!isset($_SESSION['currentUserID']) or !isset($_SESSION['currentUserRol']))
            {
                $mssCrearCuenta = "No se encuentra logeado, por favor inicie sesión para esta acción.";
            }
            else
            {
                $IDUsuario = $_SESSION['currentUserID'];
                $sql = "INSERT INTO Cuenta (Saldo, Cuota_manejo, ID_USUARIO) VALUES (0.0, $cuata_general, $IDUsuario)";
                if(mysqli_query($con,$sql)){
                    $mssCrearCuenta = "Se ha creado la Cuenta de ahorros a nombre de: ".$_SESSION['currentUserNombre'];
                }
                else{
                    $mssCrearCuenta = "Problemas en la conexión ".mysqli_error($con);
                }
            }
        ?>
        <h1>Crear cuenta de ahorros</h1>
        <?php 
            echo "<script>
                        alert(\"$mssCrearCuenta\");
                        window.location.href = \"index.php\";
                </script>";
        ?>
        <input type='button'value='Regresar al index' onclick="document.location.href='index.php';"/>
    </body>
</html>