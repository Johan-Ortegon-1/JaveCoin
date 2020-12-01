<html>

<head>
    <meta charset="UTF-8">
    <title>Editar Tarjeta</title>
</head>

<body>
    <h1>Editar Tarjeta</h1>
    <input type='button' value='Regresar a Administrar' onclick="document.location.href='administrar.php';" />
    <form action="<?= $_SERVER["PHP_SELF"]; ?>" method="post">
        <label for="ID">ID(Numero de la Tarjeta)</label><br>
        <input type="number" name="ID"><br>

        <label for="Cupo">Cupo</label><br>
        <input type="number" min="10" step="0.01" name="Cupo"><br>

        <label for="Sobre_cupo">Sobrecupo</label><br>
        <input type="number"  min="10" step="0.01" name="Sobre_cupo"><br>

        <label for="Cuota_manejo">Cuota de manejo</label><br>
        <input type="number" min="1" step="0.01" name="Cuota_manejo"><br>

        <label for="Tasa_interes">Tasa de interes</label><br>
        <input type="number" min="0.01" step="0.01" name="Tasa_interes"><br>

        <label for="Estado">Estado   </label>
            <select name="Estado" >
                <option value="Espera">Espera</option>
                <option value="Aprobado">Aprobado</option>
            </select>
        <br>

        <label for="ID_CUENTA">ID de la Cuenta asociada</label><br>
        <input type="number" min="0" name="ID_CUENTA"><br>

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
read_tarjetas();

function read_tarjetas(){
    include_once dirname(__FILE__) . '/config.php';
    /*$id = 0;
    if(isset($_SESSION['currentUserID']) or isset($_SESSION['currentUserRol']))
    {
        $id = $_SESSION['currentUserID'];
    }else{
        echo "No se ha podido identificar al usuario registrado.";
    }*/
    $str_datos = "";
    $con = mysqli_connect(HOST_DB, USUARIO_DB, USUARIO_PASS, NOMBRE_DB);
    if (mysqli_connect_errno()) {
        $str_datos .= "Error en la conexión: " . mysqli_connect_error();
    } else {
        $str_datos .= '<table border="1" style="width:100%">';
        $str_datos .= '<tr>';
        $str_datos .= '<th>PID</th>';
        $str_datos .= '<th>Cupo</th>';
        $str_datos .= '<th>Sobrecupo</th>';
        $str_datos .= '<th>Cuota de manejo</th>';
        $str_datos .= '<th>Tasa de interes</th>';
        $str_datos .= '<th>Estado</th>';
        $str_datos .= '</tr>';

        $sql = "SELECT * FROM `tarjeta_credito`"; //WHERE `ID_CUENTA` = $_SESSION['numero de la cuenta jaja']";
    }

    $resultado = mysqli_query($con, $sql);
    if (mysqli_query($con, $sql)) {
        while ($fila = mysqli_fetch_array($resultado)) {
            $str_datos .= '<tr>';
            $str_datos .=
                "<td>{$fila['PID']}</td>
                      <td>{$fila['Cupo']}</td> 
                      <td>{$fila['Sobre_cupo']}</td>
                      <td>{$fila['Cuota_manejo']}</td>
                      <td>{$fila['Tasa_interes']}</td>
                      <td>{$fila['Estado']}</td>";
            $str_datos .= "</tr>";
        }
        $str_datos .= "</table>";
        echo "<h3>Estas son todas las tarjetas existentes en nuestro banco. Recuerda que puedes editar uno o varios campos </h3>";
        echo $str_datos;
        mysqli_close($con);
    } else {
        echo "Error en la seleccion " . mysqli_error($con);
    }
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
        $str_datos .= " WHERE `tarjeta_credito`.`PID` = {$_POST["ID"]};"; //CAMBIAR AQUI A ID DE LA TARJETA.
        //echo $str_datos; //UPDATE `tarjeta_credito` SET `Cupo` = '12000.00', `Sobre_cupo` = '220.00', `Cuota_manejo` = '12.00', `Tasa_interes` = '0.12', `Estado` = 'o' WHERE `tarjeta_credito`.`PID` = 1;
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