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
        $mensaje = cuotas_cuentas();
        $mensaje .="   ";
        $mensaje .= cuotas_tarjetas();
        echo $mensaje;
        $word ="error";
        if(strpos($mensaje, $word) !== false){
            $flagError= true;
        } else{
            $flagError= false;
            //echo "Word Not Found!";
        }
        echo "<script>
                    alert(\"$mensaje\");
            </script>";
            if($flagError) {echo "<script> window.location.href = \"index.php\"; </script>";}
            else
            {
                echo "<script> window.location.href = \"administrar.php\"; </script>";
            }
    }
}

function cuotas_cuentas(){
    include_once dirname(__FILE__) . '/config.php';
    $con = mysqli_connect(HOST_DB, USUARIO_DB, USUARIO_PASS, NOMBRE_DB);
    if (mysqli_connect_errno()) {
        echo "Error en la conexión: " . mysqli_connect_error();
    }
    $sql = "SELECT * FROM `cuenta` ";
    $str_datos="";
    $resultado = mysqli_query($con, $sql);
    if (mysqli_query($con, $sql)) {
        while ($fila = mysqli_fetch_array($resultado)) {
            $str_datos="";
            $nuevo = $fila['Saldo']-$fila ['Cuota_manejo'];
            if($nuevo<0){
                $nuevo = 0;
            }
            $str_datos .= " UPDATE `cuenta` SET `Saldo` = '$nuevo' WHERE `cuenta`.`PID` = ".$fila['PID']."; ";
            echo $str_datos;
            echo "<br>";
            if (mysqli_query($con, $str_datos)) {
                $message = "Se han incrementado correctamente los saldos de las cuentas de ahorro";
                //$flagError = false;
            } else {
                $message = "Ha ocurrido un error con las cuentas de ahorro. ";
                //$flagError =true;
            }
        }
    }
    mysqli_close($con);
return $message;
}
function cuotas_tarjetas(){
    include_once dirname(__FILE__) . '/config.php';
    $con = mysqli_connect(HOST_DB, USUARIO_DB, USUARIO_PASS, NOMBRE_DB);
    if (mysqli_connect_errno()) {
        echo "Error en la conexión: " . mysqli_connect_error();
    }
    $sql = "SELECT * FROM `tarjeta_credito` ";
    $str_datos="";
    $resultado = mysqli_query($con, $sql);
    if (mysqli_query($con, $sql)) {
        while ($fila = mysqli_fetch_array($resultado)) {
            $str_datos="";
            $nuevo = $fila ['Cuota_manejo'];
            if($nuevo<0){
                $nuevo = 0;
            }
            $str_datos .= " UPDATE `cuenta` SET `Saldo` = `Saldo`- $nuevo WHERE `cuenta`.`PID` = ".$fila['PID']."; ";
            echo $str_datos;
            echo "<br>";
            if (mysqli_query($con, $str_datos)) {
                $message = "Se han incrementado correctamente los saldos de las tarjetas de credito";
                //$flagError = false;
            } else {
                $message = "Ha ocurrido un error con las tarjetas de credito. ";
                //$flagError =true;
            }
        }
    }
    mysqli_close($con);
return $message;
}

?>