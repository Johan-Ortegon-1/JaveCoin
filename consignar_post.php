<?php
    session_start();
    $idUsuario = "";
    if(isset($_SESSION['currentUserID'])) {
        $idUsuario = $_SESSION['currentUserID'];
    }else{
        $idUsuario = "";
    }
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

    $accion = $_POST["accion"];
    $IDP =  $_POST["IDP"];
    $cantidad = $_POST["cantidad"];
    $tipoMoneda = $_POST["tipoMoneda"];

    if(!isset($_SESSION['currentUserID']))
    {
        $cedula = $_POST["cedula"];
    }

    if($tipoMoneda == "COP"){
        $cantidad = $cantidad/1000;
    }

    include_once dirname(__FILE__) . '/config.php';
    $con = mysqli_connect(HOST_DB, USUARIO_DB, USUARIO_PASS, NOMBRE_DB);

    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        if (empty($_POST["cantidad"]) or empty($_POST["IDP"]) or empty($_POST["cantidad"])){
            $errRetiro = "Transaccion declinada: Llene todos los campos";
        }
        else if(empty($_POST["cedula"]) and !isset($_SESSION['currentUserID'])){
            $errRetiro = "Transaccion declinada: Es necesario la cedula";
        }

        else if (!preg_match("/^[0-9]*(.[0-9]*)?$/",$cantidad))
        {
            $errRetiro = "Transaccion declinada: Ingrese una cantidad valida";
        }
        else if (!preg_match("/^[0-9]*(.[0-9]*)?$/",$IDP))
        {
            $errRetiro = "Transaccion declinada: Ingrese una cuenta valida";
        }
        else if (mysqli_connect_errno()) {
            $errRetiro = "Error en la conexión: ";
        }else {

            if($accion == "Cuenta"){

                $sql = "UPDATE `cuenta` 
                SET Saldo = Saldo + $cantidad
                WHERE `PID` = $IDP";

                $resultado = mysqli_query($con,$sql );
                if(mysqli_affected_rows($con) !== 0){
                    $errRetiro = "Transaccion exitosa";

                    $date = date('Y-m-d H:i:s');

                    if($idUsuario != ""){
                        $sql = "INSERT INTO `notificaciones` (`PID`, `Fecha`, `Mensaje`, `ID_USUARIO`) 
                        VALUES (NULL, '$date', 'Has consignado $cantidad a la cuenta $IDP.', $idUsuario)";

                        mysqli_query($con,$sql);
                    }

                    //busco la cuenta a la cual consigné
                    $sql = "SELECT * FROM `cuenta` WHERE `PID` = $IDP";
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
                        VALUES (NULL, '$date', 'Se te han consignado $cantidad a tu cuenta $IDP', $row[0])";

                        mysqli_query($con,$sql);
                    }
                }else{
                    $errRetiro = "Cuenta inexistente";
                }
            }
            else
            {
                $sql = "SELECT * FROM `credito` WHERE `PID` = $IDP";
                $existeCuenta = mysqli_query($con,$sql);
                $row = mysqli_fetch_row($existeCuenta);
                if(implode(null,$row) == null){ $errRetiro = "El credito a transferir no existe";}
                else if( ( $row[2] - $cantidad ) < 0 ) {
                    $errRetiro = "Maxima cantidad a consignar: $row[2] JaveCoins";
                }
                else
                {
                    $sql = "UPDATE `credito` 
                     SET Saldo = CASE
                        WHEN (Saldo-$cantidad)>=0 THEN Saldo-$cantidad
                        ELSE Saldo
                     END  WHERE `PID` = $IDP;";
                    $resultado = mysqli_query($con,$sql);
                    $resta = $row[2] - $cantidad;
                    $errRetiro = "Transaccion exitosa. Credito restante:$resta";

                    $date = date('Y-m-d H:i:s');

                    if($idUsuario != "") {
                        $sql = "INSERT INTO `notificaciones` (`PID`, `Fecha`, `Mensaje`, `ID_USUARIO`) 
                        VALUES (NULL, '$date', 'Has consignado $cantidad al credito $IDP.', $idUsuario)";
                        mysqli_query($con,$sql);
                    }

                    //busco la cuenta a la cual consigné
                    $sql = "SELECT * FROM `credito` WHERE `PID` = $IDP";
                    $creditoEnvio = mysqli_query($con,$sql);
                    $row = mysqli_fetch_row($creditoEnvio);

                    if($row[7] !=NULL){
                        //busco el usuario asociado a esa cuenta
                        $sql = "SELECT * FROM `usuario` WHERE `PID` = $row[7]";
                        $usuarioEnvio = mysqli_query($con,$sql);
                        $row = mysqli_fetch_row($usuarioEnvio);

                        if(implode(null,$row) == null){
                            //el usuario probablemente sea un visitante
                        }else{
                            $sql = "INSERT INTO `notificaciones` (`PID`, `Fecha`, `Mensaje`, `ID_USUARIO`) 
                        VALUES (NULL, '$date', 'Se te han consignado $cantidad al credito #$IDP', $row[0])";

                            mysqli_query($con,$sql);
                        }
                    }


                    registrarConsignacion($IDP, $cantidad, $con);
                }
            }
        }
    }

    function registrarConsignacion($idCredito, $consignacion, $con)
    {
        $fecha_actual = date("Y-m-d");
        $tipo = CONSIGNAR;
        $sql = "INSERT INTO Transacciones (Fecha_transaccion, Monto, Tipo, ID_CREDITO) VALUES (\"$fecha_actual\", $consignacion, \"$tipo\", $idCredito)";
        if(mysqli_query($con, $sql))
        {
            return "  Transaccion registrada en la BD";
        }
        else{
            return "  Error al almacenar la transaccion en la BD";
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