<html>
<head>
    <meta charset="UTF-8">
    <title>Editar Cuenta</title>
</head>
<body>
    <h1>Editar Cuenta</h1>
    <input type='button' value='Regresar al index' onclick="document.location.href='index.php';" />
    <form action="<?= $_SERVER["PHP_SELF"]; ?>" method="post">
        <label for="ID">ID(Numero de la cuenta)</label><br>
        <input type="text" name="ID"><br>

        <label for="saldo">Saldo</label><br>
        <input type="number" step="0.01" name="saldo"><br>

        <label for="Cuota_manejo">Cuota de manejo</label><br>
        <input type="number" step="0.01" name="Cuota_manejo"><br>

        <label for="ID_USUARIO">ID del usuario</label><br>
        <input type="number" name="ID_USUARIO"><br>

        <select name="tipoMoneda" id="tipoMoneda">
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

            $sql = "SELECT * FROM `cuenta` WHERE `PID`={$_POST["ID"]}";
            $resultado = mysqli_query($con, $sql);
            $aux = mysqli_num_rows($resultado);
            if ($aux == 1) {
                echo "Se ha encontrado la cuenta de numero {$_POST["ID"]}";
                echo "<br>";
                update_cuenta();
            } else {
                echo "NO se ha encontrado una cuenta de numero {$_POST["ID"]}";
                echo "<br>";
            }
        } else {
            echo "RECUERDE: Es necesario que ingrese un numero de cuenta para poder editar.";
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
    $str_datos = "UPDATE `cuenta` SET";
    $contador = 0; //Contador para colocar las comas. 
    if (!empty($_POST["saldo"])) {
        $str_datos .= " `Saldo` = '{$_POST["saldo"]}'";
        $contador++;
    }
    if (!empty($_POST["Cuota_manejo"])) {
        if ($contador > 0) {
            $str_datos .= ",";
        }
        $str_datos .= " `Cuota_manejo` = '{$_POST["Cuota_manejo"]}'";
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
        $str_datos .= " WHERE `cuenta`.`PID` = 1;"; //CAMBIAR AQUI A ID DEL USUARIO.
        echo $str_datos; //UPDATE `cuenta` SET `Saldo` = '10196.01', `Cuota_manejo` = '11.02', `ID_USUARIO` = '2' WHERE `cuenta`.`PID` = 1;
        echo "<br>";
        $sql = $str_datos; 
        if (mysqli_query($con, $sql)) {
            echo "Se ha ACTUALIZADO la cuenta de numero {$_POST["ID"]}";
            echo "<br>";
        } else {
            echo "NO se ha ACTUALIZADO la cuenta de numero {$_POST["ID"]}, revisa que los datos ingresados sean correctos";
            echo "<br>";
        }
    }else{
        echo "Debes modificar al menos un campo.";
    }
}
?>