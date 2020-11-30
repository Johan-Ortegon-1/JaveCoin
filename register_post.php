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
    $nombreUsuario = "";
    $password = "";
    $correo = "";
    $rol = "";
    $password_cryp = "";
    
    include_once dirname(__FILE__) . '/config.php';
    $con = mysqli_connect(HOST_DB, USUARIO_DB, USUARIO_PASS, NOMBRE_DB);

    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        if (empty($_POST["nombre_u"]) ){
            $mssCreacion = "Error de Registro: El nombre es necesario para el registro";
            $flagError = true;
        }
        else if (empty($_POST["correo"]) ){
            $mssCreacion = "Error de Registro: el correo es necesario";
            $flagError = true;
        }
        else if (empty($_POST["rol"]) ){
            $mssCreacion = "Error de Registro: El rol es necesaria para el registro";
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
            $nombreUsuario = $_POST["nombre_u"];
            $password = $_POST["password"];
            $correo = $_POST["correo"];
            $rol = $_POST["rol"];
            //Encriptacion del password
            if (CRYPT_SHA512 ==1)
            {
                $password_cryp = crypt($password, EASY_CRYPT);
            }
            else
            {
                $mssCreacion = "SHA-512 no esta soportado.";
            }

            $sql = "INSERT INTO Usuario (Nombre, Contrasena, Correo, Rol) VALUES (\"{$nombreUsuario}\", \"{$password_cryp}\", \"$correo\", \"{$rol}\")";
            if(mysqli_query($con, $sql))
            {
                $mssCreacion = "Se ha registrado al: $rol con nombre de usuario: $nombreUsuario";
            }
            else{
                $mssCreacion = "Problemas en la conexión ".mysqli_error($con);
            }

            //Obtener informacion para la SESSION
            $currentUserID = mysqli_query($con, "SELECT PID as id from usuario WHERE Nombre = \"{$nombreUsuario}\" AND Contrasena = \"{$password_cryp}\"");
            $dataCurrentUser = mysqli_fetch_assoc($currentUserID);
            $_SESSION['currentUserID'] = $dataCurrentUser['id'];

            $currentUserRol = mysqli_query($con, "SELECT Rol as rol from usuario WHERE Nombre = \"{$nombreUsuario}\" AND Contrasena = \"{$password_cryp}\"");
            $dataCurrentUser = mysqli_fetch_assoc($currentUserRol);
            $_SESSION['currentUserRol'] = $dataCurrentUser['rol'];
            
            $currentUserCorreo = mysqli_query($con, "SELECT Correo as correo from usuario WHERE Nombre = \"{$nombreUsuario}\" AND Contrasena = \"{$password_cryp}\"");
            $dataCurrentUser = mysqli_fetch_assoc($currentUserCorreo);
            $_SESSION['currentUserCorreo'] = $dataCurrentUser['correo'];

            $_SESSION['currentUserNombre'] = $nombreUsuario;
        }
    }
    ?>
    <!-- Alerta con el resultado de la transaccion -->
    <?php 
        echo "<script>
                    alert(\"$mssCreacion\");
            </script>";
        if($flagError) {echo "<script> window.location.href = \"register.php\"; </script>";}
        else
        {
            echo "<script>
                    window.location.href = \"index.php\";
            </script>";
        }
    ?>
</body>
</html>