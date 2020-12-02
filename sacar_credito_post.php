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
    $diaPag = 0;
    include_once dirname(__FILE__) . '/config.php';
    $con = mysqli_connect(HOST_DB, USUARIO_DB, USUARIO_PASS, NOMBRE_DB);
    $estado_inicial = ESTADO_INICIAL;
    $tasa_inicial = TASA_INTERES_GENERAL;
    $meses_general = MESES_GENERAL;

    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        if($_POST['diaPago'] == null or !isset($_POST['diaPago'])){
            $mssSacarCredito = "El dia de pago es obligatorio";
            $flagError = true;
        }
        else if($_POST['saldo'] == null or !isset($_POST['saldo']))
        {
            $mssSacarCredito = "El valor del credito es obligatorio, debe ser positivo y mayor a 1";
            $flagError = true;
        }
        else if (!isset($_SESSION['currentUserID']) or !isset($_SESSION['currentUserRol'])) {
            if($_POST['email'] == ' ' or !isset($_POST['email']))
            {
                $mssSacarCredito = "El correo es requerido al se un visitante";
                $flagError = true;
            }
            else
            {
                $saldo = $_POST['saldo'];
                $email = $_POST['email'];
                $diaPag = $_POST['diaPago'];
                $sql = "INSERT INTO Credito (Tasa_interes, Saldo, Estado, Fecha_pago, Meses, Correo_notificaciones) VALUES ($tasa_inicial, $saldo, \"$estado_inicial\", $diaPag, $meses_general, \"$email\")";
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
            if($_POST['tasa'] == null or !isset($_POST['tasa']))
            {
                $mssSacarCredito = "La tasa es requerida para el Cliente";
                $flagError = true;
            }
            else if($_POST['saldo'] == null or !isset($_POST['saldo']))
            {
                $mssSacarCredito = "El saldo es requerido";
                $flagError = true;
            }
            else{
                $saldo = $_POST['saldo'];
                $tasa = $_POST['tasa'];
                $diaPag = $_POST['diaPago'];
                $sql = "INSERT INTO Credito (Tasa_interes, Saldo, Estado, Fecha_pago, Meses, ID_USUARIO) VALUES ($tasa, $saldo, \"$estado_inicial\", $diaPag, $meses_general, $IDUsuario)";
                if(mysqli_query($con,$sql)){
                    $mssSacarCredito = "Se ha creado el credito, esperando respuesta del Administrador ";
                    $date = date('Y-m-d H:i:s');

                    $sql = "INSERT INTO `notificaciones` (`PID`, `Fecha`, `Mensaje`, `ID_USUARIO`) 
                    VALUES (NULL, '$date', 'Ha creado un credito, esperando respuesta del Administrador', $_SESSION[currentUserID])";

                    mysqli_query($con,$sql);
                }
                else{
                    $mssSacarCredito .= "Problemas en la conexión ".mysqli_error($con);
                    $flagError = true;
                }
            }
        }
    }

    echo "<script>
        alert(\"$mssSacarCredito\");
    </script>";
    if(!$flagError){
        echo "<script>
            alert(\"$mssSacarCredito\");
            window.location.href = \"index.php\";
        </script>";
        //echo "<script> window.location.href = \"index.php\"; </script>";
    }
    else{
        echo "<script>
            alert(\"$mssSacarCredito\");
            window.location.href = \"sacar_credito.php\";
        </script>";
        //echo "<script> window.location.href = \"sacar_credito.php\"; </script>";
    }
?>