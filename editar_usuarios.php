<html>
<head>
    <meta charset="UTF-8">
    <title>Editar Usuario</title>
</head>
<body>
    <h1>Editar Usuario</h1>
    <input type='button' value='Regresar al index' onclick="document.location.href='index.php';" />
    <form action="<?= $_SERVER["PHP_SELF"]; ?>" method="post">
        <label for="ID">ID del usuario</label><br>
        <input type="text" name="ID"><br>

        <label for="Nombre">Nombre</label><br>
        <input type="text" name="Nombre"><br>

        <label for="Contrasena">Contraseña</label><br>
        <input type="password" name="Contrasena"><br>

        <label for="Rol">Rol</label><br>
        <input type="text" name="Rol"><br>

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

            $sql = "SELECT * FROM `usuario` WHERE `PID`={$_POST["ID"]}";
            $resultado = mysqli_query($con, $sql);
            $aux = mysqli_num_rows($resultado);
            if ($aux == 1) {
                echo "Se ha encontrado la ID del usuario {$_POST["ID"]}";
                echo "<br>";
                update_usuario();
            } else {
                echo "NO se ha encontrado la ID del usuario {$_POST["ID"]}";
                echo "<br>";
            }
        } else {
            echo "RECUERDE: Es necesario que ingrese un numero de ID de usuario para poder editar.";
        }
    }
} else {
    echo "Este funcion es exclusiva para Administradores.";
}

function update_usuario()
{
    include_once dirname(__FILE__) . '/config.php';
    $con = mysqli_connect(HOST_DB, USUARIO_DB, USUARIO_PASS, NOMBRE_DB);
    if (mysqli_connect_errno()) {
        echo "Error en la conexión: " . mysqli_connect_error();
    }
    //Creacion de la cadena de sql de acuerdo a los datos que se modificaron:
    $str_datos = "UPDATE `usuario` SET";
    $contador = 0; //Contador para colocar las comas. 
    if (!empty($_POST["Nombre"])) {
        $str_datos .= " `Nombre` = '{$_POST["Nombre"]}'";
        $contador++;
    }
    if (!empty($_POST["Contrasena"])) {
        if ($contador > 0) {
            $str_datos .= ",";
        }
        $str_datos .= " `Contrasena` = '{$_POST["Contrasena"]}'";
        $contador++;
    }
    if (!empty($_POST["Rol"])) {
        if ($contador > 0) {
            $str_datos .= ",";
        }
        $str_datos .= " `Rol` = '{$_POST["Rol"]}'";
        $contador++;
    }
    if ($contador > 0) { //Asegurarse de que modifique al menos un campo
        $str_datos .= " WHERE `usuario`.`PID` = 1;"; //CAMBIAR AQUI A ID DEL USUARIO.
        echo $str_datos; //UPDATE `usuario` SET `Nombre` = 'Danielaa', `Contrasena` = '12345', `Rol` = 'u' WHERE `usuario`.`PID` = 2;
        echo "<br>";
        $sql = $str_datos; 
        if (mysqli_query($con, $sql)) {
            echo "Se ha ACTUALIZADO a la persona de ID {$_POST["ID"]}";
            echo "<br>";
        } else {
            echo "NO se ha ACTUALIZADO a la persona de ID {$_POST["ID"]}, revisa que los datos ingresados sean correctos";
            echo "<br>";
        }
    }else{
        echo "Debes modificar al menos un campo.";
    }
}
?>