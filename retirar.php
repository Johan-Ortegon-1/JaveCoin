
<html>
    <head>
        <meta charset="UTF-8">
        <title>Retirar</title>
        <style>
            table, th, td {
                border: 1px solid black;
            }
        </style>
    </head>
    <body>
        <h1>Cuentas disponibles</h1>


        <!--Query para buscar los datos de las cuentas del usuario
         Los resultados se guardan para mostrarse en una tabla-->
        <?php
            include_once dirname(__FILE__) . '/config.php';
            $con = mysqli_connect(HOST_DB, USUARIO_DB, USUARIO_PASS, NOMBRE_DB);

            $error = "";
            if (mysqli_connect_errno()) {
                $error = "Error en la conexión: ";
            }else {
                //busco las cuentas del usuario
                $sql = "SELECT * FROM `cuenta` WHERE `ID_USUARIO` = 1";

                $resultado = mysqli_query($con,$sql );

                $str_datos = "";
                $opciones = "";
                while($fila = mysqli_fetch_array($resultado)) {
                    $str_datos.= "
                    <tr>
                     <td>$fila[PID]</td>
                     <td>$fila[Saldo]</td>
                    </tr>";
                    $opciones .= "
                        <option value=\"$fila[PID]\">$fila[PID]</option>
                    ";
                }
        }
        ?>

        <table>
            <tr>
                <th>Numero</th>
                <th>Saldo</th>
            </tr>
            <?php
                echo $str_datos;
            ?>
        </table>

        <h2>Retirar</h2>
        <form action="retirar_post.php"method="post">
            <label for="IDCuenta">ID:</label><br>
            <select name="IDCuenta" id="IDCuenta">
                <?php
                    echo $opciones;
                ?>
            </select><br>
            <label for="cantidad">Cantidad:</label><br>
            <input type="text" name="cantidad"><br>
            <input type="submit" value="Retirar" name="SubmitButton">
        </form>

        <input type='button'value='Cancelar' onclick="document.location.href='index.php';"/>
    </body>
</html>