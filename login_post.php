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
    $mssLogin = "";
    $flagError = false;
    $nombreUsuario = $_POST["nombre_u"];
    $password = $_POST["password"];

    include_once dirname(__FILE__) . '/config.php';
    $sql = "SELECT count(*) as total from usuario WHERE Nombre = \"{$nombreUsuario}\" AND Contrasena = \"{$password}\"";
    $con = mysqli_connect(HOST_DB, USUARIO_DB, USUARIO_PASS, NOMBRE_DB);

    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        if (empty($_POST["nombre_u"]) ){
            $mssLogin = "Es necesario el nombre";
            $flagError = true;
        }
        else if(empty($_POST["password"]) )
        {
            $mssLogin = "Es necesaria la contraseña";
            $flagError = true;
        }
        else
        {
            $result=mysqli_query($con, $sql);
            $data=mysqli_fetch_assoc($result);
            if($data['total'] > 0)
            {
                $mssLogin = "Bienvenido $nombreUsuario";
            }
            else
            {
                $mssLogin = "Error en el login, nombre de usuario o contraseña incorrectos". mysqli_error($con);
                $flagError = true;
            }
        }
    }
    ?>
    <!-- Alerta con el resultado de la transaccion -->
    <?php
        echo "<script>
                    alert(\"$mssLogin\");
            </script>";
        if($flagError) {echo "<script> window.location.href = \"login.php\"; </script>";}
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