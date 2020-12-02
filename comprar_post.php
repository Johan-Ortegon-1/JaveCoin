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

    $nombreProducto = $_POST["nombreProducto"];
    $precio =  $_POST["precio"];
    $numCuotas = $_POST["numCuotas"];
    $tipoMoneda = $_POST["tipoMoneda"];
    $numTarjeta = $_POST["numTarjeta"];

    if($tipoMoneda == "COP"){
        $precio = $precio/1000;
    }


    include_once dirname(__FILE__) . '/config.php';
    $con = mysqli_connect(HOST_DB, USUARIO_DB, USUARIO_PASS, NOMBRE_DB);

    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        if (empty($_POST["nombreProducto"]) or empty($_POST["precio"])){
            $errRetiro = "Transaccion declinada: Llene todos los campos";
        }
        else if (mysqli_connect_errno()) {
            $errRetiro = "Error en la conexión: ";
        }else {
            $date = date('Y-m-d H:i:s');

                $sql = "INSERT 
                INTO `compras` (`PID`,`Nombre_producto`, `Fecha_compra`, `totalPagar`, `cuotas`, `cuotas_pagadas`, `ID_TARJETA`) 
                VALUES (NULL, '$nombreProducto', '$date', $precio, $numCuotas, '0', $numTarjeta);";

                $resultado = mysqli_query($con,$sql );
                if(mysqli_affected_rows($con) !== 0){
                    $errRetiro = "Transaccion existosa";

                    $date = date('Y-m-d H:i:s');

                    $sql = "INSERT INTO `notificaciones` (`PID`, `Fecha`, `Mensaje`, `ID_USUARIO`) 
                    VALUES (NULL, '$date', 'Has comprado $nombreProducto por $precio $tipoMoneda con la tarjeta # $numTarjeta',$idUsuario)";

                    mysqli_query($con,$sql);
                }else{
                    $errRetiro = "Cuenta inexistente";
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