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

    session_start();

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
                $sql = "INSERT INTO `compras` 
                (`PID`, `Fecha_compra`, `totalPagar`, `cuotas`, `cuotas_pagadas`, `ID_TARJETA`) 
                VALUES (NULL, date(DATE_RFC2822), $precio, $numCuotas, '0', $numTarjeta);";

                $resultado = mysqli_query($con,$sql );
                if(mysqli_affected_rows($con) !== 0){
                    $errRetiro = "Transaccion exitosa";
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