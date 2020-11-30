<html>
<head>
    <meta charset="UTF-8">
    <title>Editar Credito</title>
</head>
<body>
    <h1>Editar Credito</h1>
    <input type='button' value='Regresar al index' onclick="document.location.href='index.php';" />
    <form action='<?= $_SERVER["PHP_SELF"]; ?>' method='post'>
        <label for="ID">ID(Numero del Credito)</label><br>
        <input type="text" name="ID"><br>

        <label for="Saldo">Saldo</label><br>
        <input type="number" step="0.01" name="Saldo"><br>

        <label for="Fecha_pago">Fecha de Pago</label><br>
        <input type="date" name="Fecha_pago"><br>

        <label for="Tasa_interes">Tasa de interes</label><br>
        <input type="number" step="0.01" name="Tasa_interes"><br>

        <label for="Estado">Estado</label><br>
        <input type="text" name="Estado"><br>

        <label for="Correo_notificaciones">Estado</label><br>
        <input type="email" name="Correo_notificaciones"><br>

        <label for="ID_USUARIO">ID del usuario</label><br>
        <input type="number" name="ID_USUARIO"><br>

            <input type="submit" value="Editar" name="SubmitButton">
    </form>
    <br>
</body>
</html>

<?php
session_start();
//Asegurarse que el user esta logeado y aun falta verificar si es admin
$_SESSION['logeado'] = true;
if (isset($_SESSION['logeado'])) {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (!empty($_POST["ID"])) {
            //Primero aseguramos que la cedula exista.
            include_once dirname(__FILE__) . '/config.php';
            $con = mysqli_connect(HOST_DB, USUARIO_DB, USUARIO_PASS, NOMBRE_DB);
            if (mysqli_connect_errno()) {
                echo "Error en la conexión: " . mysqli_connect_error();
            }

            $sql = "SELECT * FROM `credito` WHERE `PID`={$_POST["ID"]}";
            $resultado = mysqli_query($con, $sql);
            $aux = mysqli_num_rows($resultado);
            if ($aux == 1) {
                echo "Se ha encontrado el Credito de numero {$_POST["ID"]}";
                echo "<br>";
                update_credito();
            } else {
                echo "NO se ha encontrado un Credito de numero {$_POST["ID"]}";
                echo "<br>";
            }
        } else {
            echo "RECUERDE: Es necesario que ingrese un numero de Credito para poder editar.";
        }
    }
} else {
    echo "Este funcion es exclusiva para Administradores.";
}

function update_credito()
{
    include_once dirname(__FILE__) . '/config.php';
    $con = mysqli_connect(HOST_DB, USUARIO_DB, USUARIO_PASS, NOMBRE_DB);
    if (mysqli_connect_errno()) {
        echo "Error en la conexión: " . mysqli_connect_error();
    }
    //Creacion de la cadena de sql de acuerdo a los datos que se modificaron:
    $str_datos = "UPDATE `credito` SET";
    $contador = 0; //Contador para colocar las comas. 
    if (!empty($_POST["Saldo"])) {
        if ($contador > 0) {
            $str_datos .= ",";
        }
        $str_datos .= " `Saldo` = '{$_POST["Saldo"]}'";
        $contador++;
    }
    if (!empty($_POST["Fecha_pago"])) {
        if ($contador > 0) {
            $str_datos .= ",";
        }
        $str_datos .= " `Fecha_pago` = '{$_POST["Fecha_pago"]}'";
        $contador++;
    }
    if (!empty($_POST["Tasa_interes"])) {
        if ($contador > 0) {
            $str_datos .= ",";
        }
        $str_datos .= " `Tasa_interes` = '{$_POST["Tasa_interes"]}'";
        $contador++;
    }
    if (!empty($_POST["Estado"])) {
        if ($contador > 0) {
            $str_datos .= ",";
        }
        $str_datos .= " `Estado` = '{$_POST["Estado"]}'";
        $contador++;
    }
    if (!empty($_POST["Correo_notificaciones"])) {
        if ($contador > 0) {
            $str_datos .= ",";
        }
        $str_datos .= " `Correo_notificaciones` = '{$_POST["Correo_notificaciones"]}'";
        $contador++;
    }
    if (!empty($_POST["ID_USUARIO"])) {
        if ($contador > 0) {
            $str_datos .= ",";
        }
        $str_datos .= " `ID_USUARIO` = '{$_POST["ID_USUARIO"]}'";
        $contador++;
    }
    if ($contador > 0) { //Asegurarse de que modifique al menos un campo
        $str_datos .= " WHERE `credito`.`PID` = 1;"; //CAMBIAR AQUI A ID DEl Credito.
        echo $str_datos; //UPDATE `cuenta` SET `Saldo` = '10196.01', `Cuota_manejo` = '11.02', `ID_USUARIO` = '2' WHERE `cuenta`.`PID` = 1;
        echo "<br>";
        $sql = $str_datos; 
        if (mysqli_query($con, $sql)) {
            echo "Se ha ACTUALIZADO el Credito de numero {$_POST["ID"]}";
            echo "<br>";
        } else {
            echo "NO se ha ACTUALIZADO el Credito de numero {$_POST["ID"]}, revisa que los datos ingresados sean correctos";
            echo "<br>";
        }
    }else{
        echo "Debes modificar al menos un campo.";
    }
}

function update_creditos()
{
    //var_dump($_POST)[1]; Solucion a que el iterador vaya hasta la id mas alta esta aqui, de alguna manera
    if (isset($_POST['contador'])) {
        echo "<br>";
        include_once dirname(__FILE__) . '/config.php';
        $con = mysqli_connect(HOST_DB, USUARIO_DB, USUARIO_PASS, NOMBRE_DB);
        if (mysqli_connect_errno()) {
            echo "Error en la conexión: " . mysqli_connect_error();
        }
        // Arreglar aqui    
        $sql = "SELECT * FROM `credito` WHERE `ESTADO` = 'o'"; //MODIFICAR AQUI
        //$resultado = mysqli_query($con, $sql);
        if (mysqli_query($con, $sql)) {
            while ($fila = mysqli_fetch_array($resultado)) {
                $cadena = "cbox" . $fila['PID'];
                if (isset($_POST[$cadena])) { //Creacion de la cadena de sql de acuerdo a los datos que se modificaron:
                    $str_datos = "UPDATE `credito` SET  `Estado`= 'a'  WHERE `credito`.`PID` = " . $_POST[$cadena] . "; ";
                } else {
                    $str_datos = "UPDATE `credito` SET  `Estado`= 'o', `Tasa_interes`= '0.69'   WHERE `credito`.`PID` = " . $_POST[$cadena] . "; ";
                }
                $sql = $str_datos;

                if (mysqli_query($con, $sql)) {
                    echo "El credito " . $_POST[$cadena] . " ha sido aprobado";
                    echo "<br>";
                } else {
                    echo "El credito " . $_POST[$cadena] . "  NO ha sido aprobado, es probable que haya ocurrio un error";
                    echo "<br>";
                }
            }
        }
    }
}
?>