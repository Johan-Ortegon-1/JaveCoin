<!DOCTYPE html>
<html>
<head>
    <title></title>
</head>
<body>
    <?php
    $errCreacion = "";
    $nombreUsuario = $_POST["nombre_u"];
    $password = $_POST["password"];
    $rol = $_POST["rol"];

    $sql = "INSERT INTO Usuario (Nombre, Contrasena, Rol) VALUES (\"{$nombreUsuario}\", \"{$password}\", \"{$rol}\")";
    
    include_once dirname(__FILE__) . '/config.php';
    $con = mysqli_connect(HOST_DB, USUARIO_DB, USUARIO_PASS, NOMBRE_DB);

    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        if (empty($_POST["nombre_u"]) ){
            $errCreacion = "Error de Registro: El nombre es necesario para el registro";
        }
        else if (empty($_POST["password"]) ){
            $errCreacion = "Error de Registro: La contraseña es necesaria para el registro";
        }
        else if (mysqli_connect_errno()) {
            $errCreacion = "Error en la conexión: ";
        }
        else 
        {
            if(mysqli_query($con,$sql)){
                $errCreacion = "Se ha registrado al: $rol con nombre de usuario: $nombreUsuario";
                echo "Se ha registrado al: $rol con nombre de usuario: $nombreUsuario";
            }
            else{
                echo "<br>"."Error en la insercion";
                $errCreacion = "Probleas en la conexión".mysqli_error($con);
            }
        }
    }
    ?>
    <!-- Alerta con el resultado de la transaccion -->
    <?php echo $errCreacion;
    echo "<script>
                alert(\"$errCreacion\");
                window.location.href = \"index.php\";
            </script>"
    ?>
</body>
</html>