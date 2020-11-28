<?php
    session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <title></title>
</head>
<body>
    <?php
    $mssCreacion = "";
    $flagError = false;
    $nombreUsuario = $_POST["nombre_u"];
    $password = $_POST["password"];
    $rol = $_POST["rol"];

    $sql = "INSERT INTO Usuario (Nombre, Contrasena, Rol) VALUES (\"{$nombreUsuario}\", \"{$password}\", \"{$rol}\")";
    
    include_once dirname(__FILE__) . '/config.php';
    $con = mysqli_connect(HOST_DB, USUARIO_DB, USUARIO_PASS, NOMBRE_DB);

    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        if (empty($_POST["nombre_u"]) ){
            $mssCreacion = "Error de Registro: El nombre es necesario para el registro";
            $flagError = true;
        }
        else if (empty($_POST["password"]) ){
            $mssCreacion = "Error de Registro: La contraseña es necesaria para el registro";
            $flagError = true;
        }
        else if (mysqli_connect_errno()) {
            $mssCreacion = "Error en la conexión: ".mysqli_error($con);
            $flagError = true;
        }
        else 
        {
            if(mysqli_query($con,$sql)){
                $mssCreacion = "Se ha registrado al: $rol con nombre de usuario: $nombreUsuario";
            }
            else{
                $mssCreacion = "Problemas en la conexión ".mysqli_error($con);
            }
        }
    }
    ?>
    <!-- Alerta con el resultado de la transaccion -->
    <?php 
        echo "<script>
                    alert(\"$mssCreacion\");
                    window.location.href = \"index.php\";
            </script>";
        if($flagError) {echo "<script> window.location.href = \"register.php\"; </script>";}
        else
        {
            if(!isset($_SESSION['currentUserID']))
            {
                $currentUserID = mysqli_query($con, "SELECT PID as id from usuario WHERE Nombre = \"{$nombreUsuario}\" AND Contrasena = \"{$password}\"");
                $dataCurrentUser = mysqli_fetch_assoc($currentUserID);
                $_SESSION['currentUserID'] = $dataCurrentUser['id'];

                $currentUserRol = mysqli_query($con, "SELECT Rol as rol from usuario WHERE Nombre = \"{$nombreUsuario}\" AND Contrasena = \"{$password}\"");
                $dataCurrentUser = mysqli_fetch_assoc($currentUserRol);
                $_SESSION['currentUserRol'] = $dataCurrentUser['rol'];

                $_SESSION['currentUserNombre'] = $nombreUsuario;
            }
            echo "Usuario con ID: ".$_SESSION['currentUserID']." y Rol: ".$_SESSION['currentUserRol'];
            echo "<script> window.location.href = \"index.php\"; </script>";
        }
    ?>
</body>
</html>