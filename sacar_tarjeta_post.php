<?php
session_start();
?>
<?php
    $mssSacarTarjeta = "";
    $flagError = false;
    $IDCuenta = "";
    include_once dirname(__FILE__) . '/config.php';
    $con = mysqli_connect(HOST_DB, USUARIO_DB, USUARIO_PASS, NOMBRE_DB);
    
    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        if(!isset($_POST['cuenta']))
        {
            $mssSacarTarjeta = "No se encontró una cuenta pasa asociar a esta tarjeta";
            $flagError = true;
        }
        else if(!isset($_SESSION['currentUserID']) or !isset($_SESSION['currentUserRol']))
        {
            $mssSacarTarjeta = "No se encuentra logeado, por favor inicie sesión para esta acción.";
            $flagError = true;
        }
        else
        {
            $IDCuenta = $_POST['cuenta'];
            $sql = "INSERT INTO Tarjeta_Credito (Cupo, Sobre_cupo, Cuota_manejo, Tasa_interes, Estado, ID_CUENTA) VALUES (0.0, 0.0, 0.0, 0.0, \"No aprovado\", $IDCuenta)";
            if(mysqli_query($con,$sql)){
                $mssSacarTarjeta = "Se ha creado la tarjeta de credito :) a nombre de: ".$_SESSION['currentUserNombre'];
            }
            else{
                $mssSacarTarjeta = "Problemas en la conexión ".mysqli_error($con);
                $flagError = true;
            }
        }
        
    }
?>
<?php
    echo "<script>
        alert(\"$mssSacarTarjeta\");
            window.location.href = \"index.php\";
        </script>";
    if($flagError) {echo "<script> window.location.href = \"sacar_tarjeta_post.php\"; </script>";}
    else
    {
        echo "<script> window.location.href = \"index.php\"; </script>";
    }
?>