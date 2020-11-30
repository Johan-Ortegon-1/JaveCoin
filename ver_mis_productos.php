<?php
    session_start();
    
?>
<html>

<head>
    <meta charset="UTF-8">
    <title>Ver mis productos</title>
</head>

<body>
    <h1>Ver mis productos</h1>
    <input type='button' value='Regresar al index' onclick="document.location.href='index.php';" />
</body>

</html>
<?php
//Asegurarse que el user esta logeado
$_SESSION['logeado'] = true;
if (isset($_SESSION['logeado'])) {
    echo "<br>";
    echo "<br>";
    //read_usuarios();
    echo "<h2> Cuentas de ahorro</h2>";
    read_cuentas();
    echo "<h2> Tarjetas de Credito</h2>";
    read_tarjetas();
    echo "<h2> Creditos</h2>";
    read_creditos();    
} else {
    echo "Este funcion es exclusiva para clientes.";
}

//FUNCIONES
function read_creditos(){
    include_once dirname(__FILE__) . '/config.php';
    $id = 0;
    echo $_SESSION['currentUserID'];
    if(!isset($_SESSION['currentUserID']) or !isset($_SESSION['currentUserRol']))
    {
        $id = $_SESSION['currentUserID'];
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
        $str_datos .= '</tr>';

        $sql = "SELECT * FROM `credito` WHERE `ID_USUARIO` = $id"; //WHERE `ID_USUARIO` = $_SESSION['user']";
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
                      <td>{$fila['Fecha_pago']}</td>";
            $str_datos .= "</tr>";
        }
        $str_datos .= "</table>";
        echo $str_datos;
        mysqli_close($con);
    } else {
        echo "Error en la seleccion " . mysqli_error($con);
    }

}

function read_tarjetas(){
    include_once dirname(__FILE__) . '/config.php';
    $id = 0;
    if(!isset($_SESSION['currentUserID']) or !isset($_SESSION['currentUserRol']))
    {
        $id = $_SESSION['currentUserID'];
    }
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

        $sql = "SELECT * FROM `tarjeta_credito` WHERE `ID_CUENTA` = $id"; //WHERE `ID_CUENTA` = $_SESSION['numero de la cuenta jaja']";
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
        echo $str_datos;
        mysqli_close($con);
    } else {
        echo "Error en la seleccion " . mysqli_error($con);
    }
}

function read_cuentas(){
    include_once dirname(__FILE__) . '/config.php';
    $id = 0;
    if(!isset($_SESSION['currentUserID']) or !isset($_SESSION['currentUserRol']))
    {
        $id = $_SESSION['currentUserID'];
    }
    $str_datos = "";
    $con = mysqli_connect(HOST_DB, USUARIO_DB, USUARIO_PASS, NOMBRE_DB);
    if (mysqli_connect_errno()) {
        $str_datos .= "Error en la conexión: " . mysqli_connect_error();
    } else {
        $str_datos .= '<table border="1" style="width:100%">';
        $str_datos .= '<tr>';
        $str_datos .= '<th>PID</th>';
        $str_datos .= '<th>Saldo</th>';
        $str_datos .= '<th>Cuota manejo</th>';
        $str_datos .= '</tr>';

        echo $id;
        $sql = "SELECT * FROM `cuenta` WHERE `ID_USUARIO` = $id"; //WHERE `ID_USUARIO` = $_SESSION['user']";
    }

    $resultado = mysqli_query($con, $sql);
    if (mysqli_query($con, $sql)) {
        while ($fila = mysqli_fetch_array($resultado)) {
            $str_datos .= '<tr>';
            $str_datos .=
                "<td>{$fila['PID']}</td>
                      <td>{$fila['Saldo']}</td> 
                      <td>{$fila['Cuota_manejo']}</td>";
            $str_datos .= "</tr>";
        }
        $str_datos .= "</table>";
        echo $str_datos;
        mysqli_close($con);
    } else {
        echo "Error en la seleccion " . mysqli_error($con);
    }
}

function read_usuarios(){
    include_once dirname(__FILE__) . '/config.php';
    $str_datos = "";
    $con = mysqli_connect(HOST_DB, USUARIO_DB, USUARIO_PASS, NOMBRE_DB);
    if (mysqli_connect_errno()) {
        $str_datos .= "Error en la conexión: " . mysqli_connect_error();
    } else {
        $str_datos .= '<table border="1" style="width:100%">';
        $str_datos .= '<tr>';
        $str_datos .= '<th>PID</th>';
        $str_datos .= '<th>Nombre</th>';
        $str_datos .= '<th>Contraseña</th>';
        $str_datos .= '<th>Rol</th>';
        $str_datos .= '</tr>';

        $sql = "SELECT * FROM `usuario`";
    }

    $resultado = mysqli_query($con, $sql);
    if (mysqli_query($con, $sql)) {
        while ($fila = mysqli_fetch_array($resultado)) {
            $str_datos .= '<tr>';
            $str_datos .=
                "<td>{$fila['PID']}</td>
                      <td>{$fila['Nombre']}</td> 
                      <td>{$fila['Contrasena']}</td>
                      <td>{$fila['Rol']}</td>";
            $str_datos .= "</tr>";
        }
        $str_datos .= "</table>";
        echo $str_datos;
        mysqli_close($con);
    } else {
        echo "Error en la seleccion " . mysqli_error($con);
    }
}
?>
