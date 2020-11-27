<html>

<head>
    <meta charset="UTF-8">
    <title>Editar Tarjeta</title>
</head>

<body>
    <h1>Editar Tarjeta</h1>
    <input type='button' value='Regresar al index' onclick="document.location.href='index.php';" />
    <form action="<?= $_SERVER["PHP_SELF"]; ?>" method="post">
        <label for="ID">ID(Numero de la Tarjeta)</label><br>
        <input type="text" name="ID"><br>

        <label for="Cupo">Cupo</label><br>
        <input type="number" step="0.01" name="Cupo"><br>

        <label for="Sobre_cupo">Sobrecupo</label><br>
        <input type="number" step="0.01" name="Sobre_cupo"><br>

        <label for="Cuota_manejo">Cuota de manejo</label><br>
        <input type="number" step="0.01" name="Cuota_manejo"><br>

        <label for="Tasa_interes">Tasa de interes</label><br>
        <input type="number" step="0.01" name="Tasa_interes"><br>

        <label for="Estado">Estado</label><br>
        <input type="text" name="Estado"><br>

        <label for="ID_CUENTA">ID de la Cuenta asociada</label><br>
        <input type="number" name="ID_CUENTA"><br>

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

            $sql = "SELECT * FROM `tarjeta_credito` WHERE `PID`={$_POST["ID"]}";
            $resultado = mysqli_query($con, $sql);
            $aux = mysqli_num_rows($resultado);
            if ($aux == 1) {
                echo "Se ha encontrado la tarjeta de numero {$_POST["ID"]}";
                echo "<br>";
                update_cuenta();
            } else {
                echo "NO se ha encontrado una tarjeta de numero {$_POST["ID"]}";
                echo "<br>";
            }
        } else {
            echo "RECUERDE: Es necesario que ingrese un numero de tarjeta para poder editar.";
        }
    }
} else {
    echo "Este funcion es exclusiva para Administradores.";
}

function update_cuenta()
{
    include_once dirname(__FILE__) . '/config.php';
    $con = mysqli_connect(HOST_DB, USUARIO_DB, USUARIO_PASS, NOMBRE_DB);
    if (mysqli_connect_errno()) {
        echo "Error en la conexión: " . mysqli_connect_error();
    }
    //Creacion de la cadena de sql de acuerdo a los datos que se modificaron:
    $str_datos = "UPDATE `tarjeta_credito` SET";
    $contador = 0; //Contador para colocar las comas. 
    if (!empty($_POST["Cupo"])) {
        $str_datos .= " `Cupo` = '{$_POST["Cupo"]}'";
        $contador++;
    }
    if (!empty($_POST["Sobre_cupo"])) {
        if ($contador > 0) {
            $str_datos .= ",";
        }
        $str_datos .= " `Sobre_cupo` = '{$_POST["Sobre_cupo"]}'";
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
    if (!empty($_POST["ID_CUENTA"])) {
        if ($contador > 0) {
            $str_datos .= ",";
        }
        $str_datos .= " `ID_CUENTA` = '{$_POST["ID_CUENTA"]}'";
        $contador++;
    }
    if ($contador > 0) { //Asegurarse de que modifique al menos un campo
        $str_datos .= " WHERE `tarjeta_credito`.`PID` = 1;"; //CAMBIAR AQUI A ID DE LA TARJETA.
        echo $str_datos; //UPDATE `tarjeta_credito` SET `Cupo` = '12000.00', `Sobre_cupo` = '220.00', `Cuota_manejo` = '12.00', `Tasa_interes` = '0.12', `Estado` = 'o' WHERE `tarjeta_credito`.`PID` = 1;
        echo "<br>";
        $sql = $str_datos;
        if (mysqli_query($con, $sql)) {
            echo "Se ha ACTUALIZADO la tarjeta de numero {$_POST["ID"]}";
            echo "<br>";
        } else {
            echo "NO se ha ACTUALIZADO la tarjeta de numero {$_POST["ID"]}, revisa que los datos ingresados sean correctos";
            echo "<br>";
        }
    } else {
        echo "Debes modificar al menos un campo.";
    }
}
?>