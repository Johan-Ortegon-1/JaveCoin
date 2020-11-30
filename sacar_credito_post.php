<?php
session_start();
?>
<?php
    $mssSacarCredito = "";
    $flagError = false;
    $IDUsuario = "";
    $sql = "";
    $email = "";
    $tasa = "";
    $saldo = "";
    include_once dirname(__FILE__) . '/config.php';
    $con = mysqli_connect(HOST_DB, USUARIO_DB, USUARIO_PASS, NOMBRE_DB);
    $estado_inicial = ESTADO_INICIAL;
    $tasa_inicial = TASA_INTERES_GENERAL;
    echo $tasa_inicial;

    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        if(!isset($_POST['saldo']))
        {
            $mssSacarCredito = "El monto es requerido, positivo y mayor a 1";
            $flagError = true;
        }
        else if (!isset($_SESSION['currentUserID']) or !isset($_SESSION['currentUserRol'])) {
            if(!isset($_POST['email']))
            {
                $mssSacarCredito = "El correo es requerido al se un visitante";
                $flagError = true;
            }
            else
            {
                $saldo = $_POST['saldo'];
                $email = $_POST['email'];
                $sql = "INSERT INTO Credito (Tasa_interes, Saldo, Estado, Fecha_pago, Correo_notificaciones) VALUES ($tasa_inicial, $saldo, \"$estado_inicial\", \"2008-11-11\", \"$email\")";
                if(mysqli_query($con,$sql)){
                    $mssSacarCredito = "Se ha creado el crediro para el visitante ";
                }
                else{
                    $mssSacarCredito = "Problemas en la conexión ".mysqli_error($con);
                    $flagError = true;
                }
            }
        }
        else {
            $IDUsuario = $_SESSION['currentUserID'];
            if(!isset($_POST['tasa']))
            {
                $mssSacarCredito = "La tasa es requerida para el Cliente";
                $flagError = true;
            }
            else{
                $saldo = $_POST['saldo'];
                $tasa = $_POST['tasa'];
                $sql = "INSERT INTO Credito (Tasa_interes, Saldo, Estado, Fecha_pago, Correo_notificaciones, ID_USUARIO) VALUES ($tasa, $saldo, \"ESTADO_INICIAL\", \"2008-11-11\", \"NULL\", $IDUsuario)";
                if(mysqli_query($con,$sql)){
                    $mssSacarCredito = "Se ha creado el crediro, esperando respuesta del Administrador ";
                }
                else{
                    $mssSacarCredito .= "Problemas en la conexión ".mysqli_error($con);
                    $flagError = true;
                }
            }
        }
    }
    else{
        echo "ERROR6";
    }
?>
<?php 
    echo "<script>
        alert(\"$mssSacarCredito\");
    </script>";
    if(!$flagError){
        echo "<script> window.location.href = \"index.php\"; </script>";
    }
    else{
        echo "<script> window.location.href = \"sacar_credito.php\"; </script>";
    }
?>