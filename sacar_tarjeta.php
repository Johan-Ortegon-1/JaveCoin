<?php
session_start();
?>
<?php
    $IDUsuario = "";
    $cuentasID = "";
    $mssSacarTarjeta = "";
    include_once dirname(__FILE__) . '/config.php';
    $con = mysqli_connect(HOST_DB, USUARIO_DB, USUARIO_PASS, NOMBRE_DB);
    if (!isset($_SESSION['currentUserID']) or !isset($_SESSION['currentUserRol'])) {
        $mssCrearCuenta = "No se encuentra logeado, por favor inicie sesión para esta acción.";
    }
    else{
        $IDUsuario = $_SESSION['currentUserID'];
        $sql = "SELECT * from Cuenta WHERE ID_USUARIO = {$IDUsuario}";
        if ($result = $con->query($sql)) {
            while ($row = $result->fetch_assoc()) 
            {
                $field0 = $row["PID"];
                $field1 = $row["Saldo"];
                $field2 = $row["Cuota_manejo"];
                $cuentasID .= "<option value=\"$field0\">Cuenta con ID: $field0 Con saldo: $field1 Con cuota de manejo: $field2%</option>";
                $field3 = $row["ID_USUARIO"];
            }
        }
        else{
            $mssSacarTarjeta.= "Error al consultar cuentas: ".mysqli_error($con);
        }
    }
?>
<html>

    <head>
        <meta charset="UTF-8">
        <title>Sacar tarjeta</title>
    </head>

    <body>
        <h1>Sacar tarjeta</h1>
        <h3>Para sacar una tarjeta es necesario asociarla a una cuenta: </h3>
        <form action="sacar_tarjeta_post.php" method="POST">
            <select name="cuenta">
                <?php
                    echo $cuentasID;
                ?>
            </select>
            <br>
            <input type="submit" value="Sacar tarjeta" name="sacarTarjeta">
        </form>
        <input type='button' value='Cancelar' onclick="document.location.href='index.php';" />
    </body>

</html>