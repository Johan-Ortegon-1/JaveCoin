<?php
    session_start();
    $idUsuario = $_SESSION['currentUserID']
?>
<!DOCTYPE html>
<html>
<head>
    <title></title>
</head>
<body>
    <!-- Imprimir resultado de la transaccion -->
    <?php

    $errRetiro = "";
    $cantidad = $_POST["cantidad"];
    $cuenta =  $_POST["IDCuenta"];
    $cuentaDestino = $_POST["cuentaDestino"];

    $transferir = "UPDATE `cuenta` 
                SET Saldo = CASE
                     WHEN (Saldo-$cantidad)>=0 THEN Saldo-$cantidad
                     ELSE Saldo
                     END
                WHERE `PID` = $cuenta";

    $transferir2 = "UPDATE `cuenta` 
                SET Saldo = Saldo + $cantidad
                WHERE `PID` = $cuentaDestino";

    $buscar = "SELECT * FROM `cuenta` WHERE `PID` = $cuentaDestino";

    include_once dirname(__FILE__) . '/config.php';
    $con = mysqli_connect(HOST_DB, USUARIO_DB, USUARIO_PASS, NOMBRE_DB);

    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        if (empty($_POST["cantidad"]) ){
            $errRetiro = "Transaccion declinada: Ingrese una cantidad valida";
        }

        else if (!preg_match("/^[0-9]*$/",$cantidad))
        {
            $errRetiro = "Transaccion declinada: Ingrese una cantidad valida";
        }
        else if (mysqli_connect_errno()) {
            $errRetiro = "Error en la conexión: ";
        }else {

            $existeCuenta =  mysqli_query($con,$buscar);
            $row = mysqli_fetch_row($existeCuenta);
            if(implode(null,$row) == null){ $errRetiro = "La cuenta a transferir no existe";}

            else{
                $resultado = mysqli_query($con,$transferir );
                if(mysqli_affected_rows($con) !== 0){
                    mysqli_query($con,$transferir2 );
                    $errRetiro = "Transaccion exitosa";

                    $date = date('Y-m-d H:i:s');


                    $sql = "INSERT INTO `notificaciones` (`PID`, `Fecha`, `Mensaje`, `ID_USUARIO`) 
                    VALUES (NULL, '$date', 'Has trasnferido $cantidad a la cuenta #$cuentaDestino.',$idUsuario)";

                    mysqli_query($con,$sql);

                    //busco la cuenta a la cual consigné
                    $sql = "SELECT * FROM `cuenta` WHERE `PID` = $cuentaDestino";
                    $cuentaEnvio = mysqli_query($con,$sql);
                    $row = mysqli_fetch_row($cuentaEnvio);

                    //busco el usuario asociado a esa cuenta
                    $sql = "SELECT * FROM `usuario` WHERE `PID` = $row[3]";
                    $usuarioEnvio = mysqli_query($con,$sql);
                    $row = mysqli_fetch_row($usuarioEnvio);

                    if(implode(null,$row) == null){
                        //el usuario probablemente sea un visitante
                    }else{
                        $sql = "INSERT INTO `notificaciones` (`PID`, `Fecha`, `Mensaje`, `ID_USUARIO`) 
                        VALUES (NULL, '$date', 'Se te han consignado $cantidad a tu cuenta #$cuentaDestino', $row[0])";

                        mysqli_query($con,$sql);
                    }
                }else{
                    $errRetiro = "Saldo insuficiente";
                }
            }
        }
    }
    ?>

    <!-- Alerta con el resultado de la transaccion -->
    <?php echo $errRetiro;
    echo "<script>
                alert(\"$errRetiro\");
                window.location.href = \"index.php\";
            </script>"
    ?>
</body>
</html>