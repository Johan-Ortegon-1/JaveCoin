<html>

<head>
    <meta charset="UTF-8">
    <title>Editar Credito</title>
</head>

<body>
    <h1>Editar Credito</h1>
    <input type='button' value='Regresar a Administrar' onclick="document.location.href='administrar.php';" />
    <form action='<?= $_SERVER["PHP_SELF"]; ?>' method='post'>
        <label for="ID">ID(Numero del Credito)</label><br>
        <input type="number" min="0" name="ID"><br>

        <label for="Saldo">Saldo</label><br>
        <input type="number" min="100" step="0.01" name="Saldo"><br>

        <label for="Fecha_pago">Día del mes / Fecha limite de pago: </label>
        <select name="Fecha_pago">
            <option value="" selected> </option>
            <option value="1">Día 1</option>
            <option value="2">Día 2</option>
            <option value="3">Día 3</option>
            <option value="4">Día 4</option>
            <option value="5">Día 5</option>
            <option value="6">Día 6</option>
            <option value="7">Día 7</option>
            <option value="8">Día 8</option>
            <option value="9">Día 9</option>
            <option value="10">Día 10</option>
            <option value="11">Día 11</option>
            <option value="12">Día 12</option>
            <option value="13">Día 13</option>
            <option value="14">Día 14</option>
            <option value="15">Día 15</option>
            <option value="16">Día 16</option>
            <option value="17">Día 17</option>
            <option value="18">Día 18</option>
            <option value="19">Día 19</option>
            <option value="20">Día 20</option>
            <option value="21">Día 21</option>
            <option value="22">Día 22</option>
            <option value="23">Día 23</option>
            <option value="24">Día 24</option>
            <option value="25">Día 25</option>
            <option value="26">Día 26</option>
            <option value="27">Día 27</option>
            <option value="28">Día 28</option>
            <option value="29">Día 29</option>
            <option value="30">Día 30</option>
            <option value="30">Día 31</option>
        </select>
        <br>

        <label for="Tasa_interes">Tasa de interes</label><br>
        <input type="number" min="0.01" step="0.01" name="Tasa_interes"><br>

        <label for="Estado">Estado </label>
        <select name="Estado">
            <option value="Espera">Espera</option>
            <option value="Aprobado">Aprobado</option>
        </select>
        <br>

        <label for="Correo_notificaciones">Correo electronico</label><br>
        <input type="email" name="Correo_notificaciones"><br>

        <label for="Meses">Meses</label><br>
        <input type="number" min="0" max="60" name="Meses"><br>

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
read_creditos();
function read_creditos()
{
    include_once dirname(__FILE__) . '/config.php';
    $id = 0;
    //echo $_SESSION['currentUserID'];
    if (isset($_SESSION['currentUserID']) or isset($_SESSION['currentUserRol'])) {
        $id = $_SESSION['currentUserID'];
    } else {
        echo "No se ha podido identificar al usuario registrado.";
    }
    $str_datos = "";
    $con = mysqli_connect(HOST_DB, USUARIO_DB, USUARIO_PASS, NOMBRE_DB);
    if (mysqli_connect_errno()) {
        $str_datos .= "Error en la conexión: " . mysqli_connect_error();
    } else {
        $str_datos .= '<table border="1" style="width:100%">';
        $str_datos .= '<tr>';
        $str_datos .= '<th>PID</th>';
        $str_datos .= '<th>Tasa de interes</th>';
        $str_datos .= '<th>Saldo</th>';
        $str_datos .= '<th>Estado</th>';
        $str_datos .= '<th>Fecha de pago</th>';
        $str_datos .= '<th>Meses</th>';
        $str_datos .= '<th>Correo Notificaciones</th>';
        $str_datos .= '</tr>';

        $sql = "SELECT * FROM `credito`"; //WHERE `ID_USUARIO` = $_SESSION['user']";
    }

    $resultado = mysqli_query($con, $sql);
    if (mysqli_query($con, $sql)) {
        while ($fila = mysqli_fetch_array($resultado)) {
            $str_datos .= '<tr>';
            $str_datos .=
                "<td>{$fila['PID']}</td>
                      <td>{$fila['Tasa_interes']}</td> 
                      <td>{$fila['Saldo']}</td>
                      <td>{$fila['Estado']}</td>
                      <td>{$fila['Fecha_pago']}</td>
                      <td>{$fila['Meses']}</td>
                      <td>{$fila['Correo_notificaciones']}</td>";
            $str_datos .= "</tr>";
        }
        $str_datos .= "</table>";
        echo "<h3>Estos son todos los creditos existentes en nuestro banco. Recuerda que puedes editar uno o varios campos </h3>";
        echo $str_datos;
        mysqli_close($con);
    } else {
        echo "Error en la seleccion " . mysqli_error($con);
    }
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
    if (!empty($_POST["Meses"])) {
        if ($contador > 0) {
            $str_datos .= ",";
        }
        $str_datos .= " `Meses` = '{$_POST["Meses"]}'";
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
        $str_datos .= " WHERE `credito`.`PID` = {$_POST["ID"]};"; //CAMBIAR AQUI A ID DEl Credito.
        echo $str_datos; //UPDATE `cuenta` SET `Saldo` = '10196.01', `Cuota_manejo` = '11.02', `ID_USUARIO` = '2' WHERE `cuenta`.`PID` = 1;
        echo "<br>";
        $sql = $str_datos;
        if (mysqli_query($con, $sql)) {
            echo "Se ha ACTUALIZADO el Credito de numero {$_POST["ID"]}";
            echo "<br>";

            $sql = "SELECT * FROM `credito` WHERE `PID` ={$_POST["ID"]}";
            $idusuario = 0;
            $auxresultado = mysqli_query($con, $sql);
            if (mysqli_query($con, $sql)) {
                while ($auxfila = mysqli_fetch_array($auxresultado)) {
                    $idusuario = $auxfila['ID_USUARIO'];
                }
            }

            if ($idusuario != null) {
                $fecha = date('Y-m-d');
                $sql = "INSERT INTO `notificaciones` (`PID`, `Fecha`, `Mensaje`, `ID_USUARIO`)
             VALUES (NULL, '{$fecha}', 'Se han modificado los valores de tu credito de numero {$_POST['ID']}', $idusuario);";
                //echo $sql;
                //echo "<br>";
                if (mysqli_query($con, $sql)) {
                    // echo "Se pudo insertar en tabla de notificaciones";
                } else {
                    //echo "NO se pudo insertar en tabla de notificaciones";
                }
            }
        } else {
            echo "NO se ha ACTUALIZADO el Credito de numero {$_POST["ID"]}, revisa que los datos ingresados sean correctos";
            echo "<br>";
        }
    } else {
        echo "Debes modificar al menos un campo.";
    }
}
?>