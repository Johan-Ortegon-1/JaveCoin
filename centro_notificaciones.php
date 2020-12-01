<?php
session_start();
?>
<html>

<head>
    <meta charset="UTF-8">
    <title>Centro Notificaciones</title>
</head>

<body>
    <h1>Centro de Notificaciones</h1>
    <h3>Bienvenido, aqui puedes ver las ultimas noticias sobre tus productos en nuestro Banco</h3>
    <input type='button' value='Regresar al index' onclick="document.location.href='index.php';" />
</body>

</html>

<?php

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
    $str_datos .= '<th>Fecha</th>';
    $str_datos .= '<th>Mensaje</th>';
    $str_datos .= '</tr>';

    $sql = "SELECT * FROM `notificaciones` WHERE `ID_USUARIO` = $id"; //WHERE `ID_USUARIO` = $_SESSION['user']";
}

$resultado = mysqli_query($con, $sql);
if (mysqli_query($con, $sql)) {
    while ($fila = mysqli_fetch_array($resultado)) {
        $str_datos .= '<tr>';
        $str_datos .=
            "<td>{$fila['PID']}</td>
                      <td>{$fila['Fecha']}</td> 
                      <td>{$fila['Mensaje']}</td>";
        $str_datos .= "</tr>";
    }
    $str_datos .= "</table>";
    echo $str_datos;
    mysqli_close($con);
} else {
    echo "Error en la seleccion " . mysqli_error($con);
}



?>