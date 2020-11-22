<?php
    include_once dirname(__FILE__) . '/config.php';
    $con = mysqli_connect(HOST_DB, USUARIO_DB, USUARIO_PASS, NOMBRE_DB);
    if (mysqli_connect_errno()) {
        echo "Error en la conexión: " . mysqli_connect_error();
    }
    if(isset($_POST['Crear/Actualizar']))
    {
        $result=mysqli_query($con, "SELECT count(*) as total from personas WHERE Cedula = {$_POST["cedula"]}");
        $data=mysqli_fetch_assoc($result);
        if($data['total'] > 0){ //Realizar un update
            $sql = "UPDATE personas SET Nombre = \"{$_POST["nombre"]}\", Apellido = \"{$_POST["apellido"]}\", Correo =\"{$_POST["correo"]}\", Edad = {$_POST["edad"]} WHERE Cedula = {$_POST["cedula"]}";
            if(mysqli_query($con,$sql)){
                echo "Se ha actualizado a la persona con la cedula: {$_POST["cedula"]}";
            }
            else{
                echo "<br>"."Error en la actualizacion";
            }
        }
        else{ //Realizar un insert
            $sql = "INSERT INTO personas (Cedula, Nombre, Apellido, Correo, Edad) VALUES ({$_POST["cedula"]}, \"{$_POST["nombre"]}\", \"{$_POST["apellido"]}\", \"{$_POST["correo"]}\", {$_POST["edad"]})";
            if(mysqli_query($con,$sql)){
                echo "Se ha insertado a la persona con la cedula: {$_POST["cedula"]}";
            }
            else{
                echo "<br>"."Error en la insercion";
            }
        }
    }
    elseif(isset($_POST['Eliminar']))
    {
        $sql = "DELETE FROM personas WHERE Cedula = {$_POST["cedula"]}";
        if(mysqli_query($con,$sql)){
            echo "Se ha eliminado a la persona con la cedula: {$_POST["cedula"]}";
        }
        else{
            echo "<br>"."Error en la eliminacion";
        }
    }
    mysqli_close($con);
?>
<br>
<input type='button'value='Regresar al index' onclick="document.location.href='index.php';"/>