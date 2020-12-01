<html>

<head>
    <meta charset="UTF-8">
    <title>Administrar</title>
</head>

<body>
    <h1>Administrar</h1>
    <input type='button' value='Regresar al index' onclick="document.location.href='index.php';" />
    <br><br>
    <input type='button' value='Editar un Cliente' onclick="document.location.href='editar_usuarios.php';" />
    <input type='button' value='Editar una cuenta' onclick="document.location.href='editar_cuenta.php';" />
    <input type='button' value='Editar una tarjeta' onclick="document.location.href='editar_tarjeta.php';" />
    <input type='button' value='Editar un credito' onclick="document.location.href='editar_credito.php';" />
    <input type='button' value='Incrementar saldos' onclick="document.location.href='incrementar_saldos_post.php';" />
    <input type='button' value='Cobrar cuotas de manejo' onclick="document.location.href='cuotas_manejo_post.php';" />
    <input type='button' value='Cobrar tarjetas' onclick="document.location.href='cobrar_tarjetas.php';" />

    <br>

    
</body>

</html>

<?php
session_start();
//Asegurarse que el user esta logeado
$_SESSION['logeado'] = true;
if (isset($_SESSION['logeado'])) {
    echo "<br>";
    read_creditos();
    echo "<br>";
    read_tarjetas();
    
} else {
    echo "Este funcion es exclusiva para Administradores.";
}

function read_tarjetas() //LAMAR DE PRIMERO A LA TABLA PARA EVITAR REFRESCAR. 
{   echo "<h2>Tarjetas a aprobar</h2>";
    echo "<p>Seleccione las tarjetas que desea aprobar y luego de click en el boton de enviar, aquellas que no sean aprobados
    seran rechazados y asignados con el valor estandar.</p>";
    update_tarjetas();
    include_once dirname(__FILE__) . '/config.php';
    $str_datos = "";
    $con = mysqli_connect(HOST_DB, USUARIO_DB, USUARIO_PASS, NOMBRE_DB);
    $inicial =ESTADO_INICIAL ;
    if (mysqli_connect_errno()) {
        $str_datos .= "Error en la conexión: " . mysqli_connect_error();
    } else {
        $str_datos .= "<form action=\"administrar.php\" method=\"POST\">";
        $str_datos .= '<table border="1" style="width:100%">';
        $str_datos .= '<tr>';
        $str_datos .= '<th>PID</th>';
        $str_datos .= '<th>Cupo</th>';
        $str_datos .= '<th>Sobrecupo</th>';
        $str_datos .= '<th>Cuota de manejo </th>';
        $str_datos .= '<th>Tasa de interes</th>';
        $str_datos .= '<th>Estado</th>';
        $str_datos .= '<th>Cuenta asociada</th>';
        $str_datos .= '<th>Acciones</th>';
        $str_datos .= '</tr>';

        $sql = "SELECT * FROM `tarjeta_credito` WHERE `ESTADO` = '$inicial' "; //WHERE `ID_USUARIO` = $_SESSION['user']"; MODIFICAR AQUI
    }
    $contador = 0;
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
                      <td>{$fila['Estado']}</td>
                      <td>{$fila['ID_CUENTA']}</td>";
            $str_datos .= '<td>';
            $str_datos .= '<label><input type="checkbox" name="Tcbox';
            $str_datos .= $fila['PID'];
            $str_datos .= '" value="';
            $str_datos .= $fila['PID'];
            $str_datos .= '">Aprobar</label><br>';
            $str_datos .= '<label><input type="checkbox" name="Tdbox';
            $str_datos .= $fila['PID'];
            $str_datos .= '" value="';
            $str_datos .= $fila['PID'];
            $str_datos .= '">Por Defecto</label><br>';
            $str_datos .= '</td> </tr>';
            $str_datos .= '<input type="hidden" name="Tcontador" value="' . $fila['PID'] . '" />';
            $contador++;
        }
        $str_datos .= "</table>";

        $str_datos .= '<input type="submit" value="Enviar" name="SubmitButton"> </form>';
        echo $str_datos;
        mysqli_close($con);
       // update_tarjetas();
    } else {
        echo "Error en la seleccion " . mysqli_error($con);
    }
}

function read_creditos() //LAMAR DE PRIMERO A LA TABLA PARA EVITAR REFRESCAR. 
{   echo "<h2>Creditos a aprobar</h2>";
    echo "<p>Seleccione los creditos que desea aprobar y luego de click en el boton de enviar, aquellos que no sean aprobados
    seran rechazados y asignados con el valor estandar. Sera necesario actualizar la pagina par ver los cambios.</p>";
    update_creditos();
    include_once dirname(__FILE__) . '/config.php';
    $str_datos = "";
    $con = mysqli_connect(HOST_DB, USUARIO_DB, USUARIO_PASS, NOMBRE_DB);
    $inicial =ESTADO_INICIAL ;
    if (mysqli_connect_errno()) {
        $str_datos .= "Error en la conexión: " . mysqli_connect_error();
    } else {
        $str_datos .= "<form action=\"administrar.php\" method=\"POST\">";
        $str_datos .= '<table border="1" style="width:100%">';
        $str_datos .= '<tr>';
        $str_datos .= '<th>PID</th>';
        $str_datos .= '<th>Tasa de interes</th>';
        $str_datos .= '<th>Saldo</th>';
        $str_datos .= '<th>Estado</th>';
        $str_datos .= '<th>Fecha de pago</th>';
        $str_datos .= '<th>Meses</th>';
        $str_datos .= '<th>Correo Notificaciones</th>';
        $str_datos .= '<th>Acciones</th>';
        $str_datos .= '</tr>';

        $sql = "SELECT * FROM `credito` WHERE `ESTADO` = '$inicial' "; //WHERE `ID_USUARIO` = $_SESSION['user']"; MODIFICAR AQUI
    }
    $contador = 0;
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
            $str_datos .= '<td>';
            $str_datos .= '<label><input type="checkbox" name="cbox';
            $str_datos .= $fila['PID'];
            $str_datos .= '" value="';
            $str_datos .= $fila['PID'];
            $str_datos .= '">Aprobar</label><br>';
            $str_datos .= '<label><input type="checkbox" name="dbox';
            $str_datos .= $fila['PID'];
            $str_datos .= '" value="';
            $str_datos .= $fila['PID'];
            $str_datos .= '">Por Defecto</label><br>';
            $str_datos .= '</td> </tr>';
            $str_datos .= '<input type="hidden" name="contador" value="' . $fila['PID'] . '" />';
            $contador++;
        }
        $str_datos .= "</table>";

        $str_datos .= '<input type="submit" value="Enviar" name="SubmitButton"> </form>';
        echo $str_datos;
        mysqli_close($con);
        //update_creditos();
    } else {
        echo "Error en la seleccion " . mysqli_error($con);
    }
}
function update_tarjetas()
{
    if (isset($_POST['Tcontador'])) {
        echo "<br>";
        include_once dirname(__FILE__) . '/config.php';
        $con = mysqli_connect(HOST_DB, USUARIO_DB, USUARIO_PASS, NOMBRE_DB);
        $inicial = " '".ESTADO_INICIAL."'" ;
        $tasa = " '".TASA_INTERES_GENERAL."'";
        $aprobado =  " '".ESTADO_APROBADO."'";
        //echo $inicial;
        //echo $tasa;
        echo "<br>";
        if (mysqli_connect_errno()) {
            echo "Error en la conexión: " . mysqli_connect_error();
        }
        // Arreglar aqui    

        $sql = "SELECT * FROM `tarjeta_credito` WHERE `Estado` = $inicial "; //MODIFICAR AQUI
        //echo $sql;
        echo "<br>";
        $resultado = mysqli_query($con, $sql);
        if (mysqli_query($con, $sql)) {
            while ($fila = mysqli_fetch_array($resultado)) {
                $cadena = "Tcbox" . $fila['PID'];
                if (isset($_POST[$cadena])) { //Creacion de la cadena de sql de acuerdo a los datos que se modificaron:
                    $str_datos = "UPDATE `tarjeta_credito` SET  `Estado`= $aprobado WHERE `tarjeta_credito`.`PID` = " . $fila['PID'] . "; ";
                   // echo $str_datos;
                    echo "La tarjeta " . $fila['PID'] . " ha sido aprobada";
                    //echo "<br>";
                    $sql = $str_datos;
                } else {
                    $cadena = "Tdbox" . $fila['PID'];
                    if (isset($_POST[$cadena])) { //Por defecto
                        $str_datos = "UPDATE `tarjeta_credito` SET  `Estado`= 'Rechazada', `Tasa_interes`= $tasa  WHERE `tarjeta_credito`.`PID` = " . $fila['PID'] . "; ";
                       // echo $str_datos;
                        echo "La tarjeta " . $fila['PID'] . " ha sido aprobada con los valores estandar";
                        //echo "<br>";
                        $sql = $str_datos;
                    }
                    /*$str_datos = "UPDATE `tarjeta_credito` SET  `Estado`= $aprobado, `Tasa_interes`= $tasa  WHERE `credito`.`PID` = " . $fila['PID'] . "; ";
                    echo $str_datos;
                    echo "El credito " . $fila['PID'] . "  NO ha sido aprobado y se le ha asignado el valor estandar";
                    echo "<br>";*/
                }
               // $sql = $str_datos;

                if (mysqli_query($con, $sql)) {
                    //echo "El credito " . $fila['PID'] . " ha sido aprobado";
                   // echo "<br>";
                } else {
                    echo "Es probable que haya ocurrio un error";
                    echo "<br>";
                }
            }
        }
    }
}

function update_creditos()
{
    if (isset($_POST['contador'])) {
        echo "<br>";
        include_once dirname(__FILE__) . '/config.php';
        $con = mysqli_connect(HOST_DB, USUARIO_DB, USUARIO_PASS, NOMBRE_DB);
        $inicial = " '".ESTADO_INICIAL."'" ;
        $tasa = " '".TASA_INTERES_GENERAL."'";
        $aprobado =  " '".ESTADO_APROBADO."'";
        //echo $inicial;
        //echo $tasa;
        echo "<br>";
        if (mysqli_connect_errno()) {
            echo "Error en la conexión: " . mysqli_connect_error();
        }
        // Arreglar aqui    

        $sql = "SELECT * FROM `credito` WHERE `Estado` = $inicial "; //MODIFICAR AQUI
        //echo $sql;
        echo "<br>";
        $resultado = mysqli_query($con, $sql);
        if (mysqli_query($con, $sql)) {
            while ($fila = mysqli_fetch_array($resultado)) {
                $cadena = "cbox" . $fila['PID'];
                if (isset($_POST[$cadena])) { //Creacion de la cadena de sql de acuerdo a los datos que se modificaron:
                    $str_datos = "UPDATE `credito` SET  `Estado`= $aprobado WHERE `credito`.`PID` = " . $fila['PID'] . "; ";
                   // echo $str_datos;
                    echo "El credito " . $fila['PID'] . " ha sido aprobado";
                    //echo "<br>";
                    $sql = $str_datos;
                } else {
                    $cadena = "dbox" . $fila['PID'];
                    if (isset($_POST[$cadena])) { //Por defecto
                        $str_datos = "UPDATE `credito` SET  `Estado`= $aprobado, `Tasa_interes`= $tasa  WHERE `credito`.`PID` = " . $fila['PID'] . "; ";
                       // echo $str_datos;
                        echo "El credito " . $fila['PID'] . " ha sido aprobado con los valores estandar";
                        //echo "<br>";
                        $sql = $str_datos;
                    }
                    /*$str_datos = "UPDATE `credito` SET  `Estado`= $aprobado, `Tasa_interes`= $tasa  WHERE `credito`.`PID` = " . $fila['PID'] . "; ";
                    echo $str_datos;
                    echo "El credito " . $fila['PID'] . "  NO ha sido aprobado y se le ha asignado el valor estandar";
                    echo "<br>";*/
                }
                //$sql = $str_datos;

                if (mysqli_query($con, $sql)) {
                    //echo "El credito " . $fila['PID'] . " ha sido aprobado";
                    echo "<br>";
                } else {
                    echo "Es probable que haya ocurrio un error";
                    echo "<br>";
                }
            }
        }
    }
}
?>