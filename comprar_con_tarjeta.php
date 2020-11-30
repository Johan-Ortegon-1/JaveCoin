<?php
    session_start();
    $idUsuario = $_SESSION['currentUserID']
?>

<html>
    <head>
        <meta charset="UTF-8">
        <title>Comprar con tarjeta</title>
    </head>
    <body>
        <h1>Comprar con tarjeta</h1>

        <?php
        $mostrarBuscar = false;
        include_once dirname(__FILE__) . '/config.php';
        $con = mysqli_connect(HOST_DB, USUARIO_DB, USUARIO_PASS, NOMBRE_DB);

        $error = "";
        if (mysqli_connect_errno()) {
            $error = "Error en la conexión: ";
        }else {
            //busco las cuentas del usuario
            $sql = "SELECT * FROM `cuenta` WHERE `ID_USUARIO` = $idUsuario";

            $resultado = mysqli_query($con,$sql );
            $tarjetas = "";
            while($fila = mysqli_fetch_array($resultado)) {

                $sql = "SELECT * FROM `tarjeta_credito` WHERE `ID_CUENTA` = $fila[PID]";
                $resultado2 = mysqli_query($con,$sql );
                while($fila2 = mysqli_fetch_array($resultado2)) {
                    $tarjetas .= "
                        <option value=\"$fila2[PID]\">$fila2[PID]</option>
                    ";
                }

            }


        }
        ?>

        <form action="comprar_post.php"method="post">
            <label for="nombreProducto">Nombre Producto</label><br>
            <input type="text" name="nombreProducto"><br>

            <label for="number">Precio</label><br>
            <input type="number" min="0" step=1 name="precio" id="precio">
            <select name ="tipoMoneda" id="tipoMoneda">
                <option
                        value="JaveCoin"
                >JaveCoin
                </option>
                <option
                        value="COP"
                >COP
                </option>
            </select><br>
            <label for="numTarjeta">Tarjeta para pagar:</label>
            <select name ="numTarjeta" id="numTarjeta">
                <?php
                    echo $tarjetas;
                ?>
            </select><br>
            <label for="numCuotas">Cuotas:</label>
            <select name ="numCuotas" id="numCuotas">
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
                <option value="6">6</option>
            </select><br>
            <input type="submit" value="Comprar" name="SubmitButton">
        </form>
        <input type='button'value='Regresar al index' onclick="document.location.href='index.php';"/>
    </body>
</html>