<?php
session_start();
if (isset($_SESSION['currentUserID']) or isset($_SESSION['currentUserRol'])) {
    if ($_SESSION['currentUserRol'] != "Administrador") {
        echo "<script>
                    alert('Usted no tiene acceso a esta funcion. Volviendo al menu principal');
            </script>";
        if ($flagError) {
            echo "<script> window.location.href = \"administrar.php\"; </script>";
        } else {
            echo "<script> window.location.href = \"index.php\"; </script>";
        }
    } else {
        // UPDATE `cuenta` SET `Saldo` = '10196.01', `Cuota_manejo` = '11.02', `ID_USUARIO` = '2' WHERE `cuenta`.`PID` = 1;
        include_once dirname(__FILE__) . '/config.php';
        $con = mysqli_connect(HOST_DB, USUARIO_DB, USUARIO_PASS, NOMBRE_DB);
        if (mysqli_connect_errno()) {
            echo "Error en la conexión: " . mysqli_connect_error();
        }
        /*$sql = "SELECT * FROM `cuenta` ";
        $str_datos="";
        if (mysqli_query($con, $sql)) {
            while ($fila = mysqli_fetch_array($resultado)) {
                $nuevo = $fila['PID']
                $str_datos .= "UPDATE `cuenta` SET `Saldo` = '10196.01'"
                $fila['PID'];
            }
        }*/

        $str_datos = "UPDATE `cuenta` SET `Saldo` = `Saldo` * 1.01";
        $sql =$str_datos;
        
        if (mysqli_query($con, $sql)) {
            $message = "Se han incrementado correctamente los saldos de las cuentas de ahorro";
            $flagError = false;
        } else {
            $message = "Ha ocurrido un error. ";
            $flagError =true;
        }
        mysqli_close($con);
        echo "<script>
                    alert(\"$message\");
            </script>";
            if($flagError) {echo "<script> window.location.href = \"index.php\"; </script>";}
            else
            {
                echo "<script> window.location.href = \"administrar.php\"; </script>";
            }
        
    }
} else {
    echo "No se ha podido identificar al usuario registrado.";
}
