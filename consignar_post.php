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

    $accion = $_POST["accion"];
    $IDP =  $_POST["IDP"];
    $cantidad = $_POST["cantidad"];
    $cedula = $_POST["cedula"];
    $tipoMoneda = $_POST["tipoMoneda"];

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

        else if (!preg_match("/^[0-9]*$/",$cantidad))
        {
            $errRetiro = "Transaccion declinada: Ingrese una cantidad valida";
        }
        else if (!preg_match("/^[0-9]*$/",$IDP))
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
                }else{
                    $errRetiro = "Cuenta inexistente";
                }
            }else{

                $sql = "SELECT * FROM `credito` WHERE `PID` = $IDP";
                $existeCuenta = mysqli_query($con,$sql);
                $row = mysqli_fetch_row($existeCuenta);
                if(implode(null,$row) == null){ $errRetiro = "El credito a transferir no existe";}
                else if( ( $row[2] - $cantidad ) < 0 ) {
                    $errRetiro = "Maxima cantidad a consignar: $row[2] JaveCoins";
                }else{
                    $sql = "UPDATE `credito` 
                     SET Saldo = CASE
                        WHEN (Saldo-$cantidad)>=0 THEN Saldo-$cantidad
                        ELSE Saldo
                     END  WHERE `PID` = $IDP;";
                    $resultado = mysqli_query($con,$sql);
                    $resta = $row[2] - $cantidad;
                    $errRetiro = "Transaccion exitosa. Credito restante:$resta";
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