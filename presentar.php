<!DOCTYPE html>
<!--
Antes de mostar esta página se debió ejecutar lo siguiente 
1. crear_db.php
2. crear_tabla.php
3. insertar_personas.php 
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>Pesentar personas</title>
    </head>
    <body>
        <?php
            echo "Test: ".$_GET["orden"];

            include_once dirname(__FILE__) . '/config.php';
            $str_datos = "";
            $con = mysqli_connect(HOST_DB, USUARIO_DB, USUARIO_PASS, NOMBRE_DB);
                if (mysqli_connect_errno()) {
                $str_datos.= "Error en la conexión: " . mysqli_connect_error();
            }
            if(isset($_GET['Nombre']))
            {
                $sql = "SELECT * FROM Personas ORDER BY Nombre";
            }
            elseif(isset($_GET['Cedula']))
            {
                $sql = "SELECT * FROM Personas ORDER BY Cedula";
            }
            if($_GET['orden'] == "Ascendente")
            {
                $sql.=" ASC";
            }
            elseif($_GET['orden'] == "Descendente")
            {
                $sql.=" DESC";
            }
            $str_datos.='<table border="1" style="width:100%">';
            $str_datos.='<tr>';
            $str_datos.='<th>ID</th>';
            $str_datos.='<th>Cedula</th>';
            $str_datos.='<th>Nombre</th>';
            $str_datos.='<th>Apellido</th>';
            $str_datos.='<th>Correo</th>';
            $str_datos.='<th>Edad</th>';
            $str_datos.='</tr>';
            
            $resultado = mysqli_query($con,$sql);
            if (mysqli_query($con, $sql)) {
                echo "Tabla Personas seleccionada correctamente";
                while($fila = mysqli_fetch_array($resultado)) {
                    $str_datos.='<tr>';
                    $str_datos.= 
                    "<td>".$fila['PID']."</td>
                    <td>".$fila['Cedula']."</td> 
                    <td>".$fila['Nombre']."</td> 
                    <td>".$fila['Apellido']."</td>
                    <td>".$fila['Correo']."</td>
                    <td>".$fila['Edad']."</td> ";
                    $str_datos.= "</tr>";
                  }
                  $str_datos.= "</table>";
                  echo $str_datos;
                  mysqli_close($con);
            } else {
                echo "Error en la seleccion " . mysqli_error($con);
            }
        ?>
        <input type='button'value='Regresar al index' onclick="document.location.href='index.php';"/>
    </body>
</html>
