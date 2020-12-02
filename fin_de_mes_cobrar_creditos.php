<?php
    session_start();
    $GLOBALS['mssCobrarCreditos'] = "";
    $GLOBALS['flagError'] = false;
    $GLOBALS['saldoCredito'] = 0.0;
    $GLOBALS['cobroMensual'] = 0.0;
    $mssCobrarCreditos = "";
    $idUsuario = "";
    $saldoCredito = "";
    $correoAsociado = "";
    $mesesActual = 0;
    $tasa_interes = 0.0;
    $cobroMesActual = 0.0;
    $sql = "SELECT * from Credito";

    include_once dirname(__FILE__) . '/config.php';
    $con = mysqli_connect(HOST_DB, USUARIO_DB, USUARIO_PASS, NOMBRE_DB);
    //Recorrer todos los creditos
    if ($result = $con->query($sql)) {
        while ($row = $result->fetch_assoc()) 
        {
            $idUsuario = $row["ID_USUARIO"];
            $idCredito = $row["PID"];
            $GLOBALS['saldoCredito'] = $row["Saldo"];
            $GLOBALS['intereses'] = $row["Tasa_interes"];
            $correoAsociado = $row["Correo_notificaciones"];
            $mesesActual = $row["Meses"];
            $fechaPago = $row["Fecha_pago"];
            $estadoActual = $row["Estado"];
            
            $cobroMesActual = ($GLOBALS['saldoCredito']/$mesesActual)+($GLOBALS['saldoCredito']*$GLOBALS['intereses']);
            $GLOBALS['cobroMensual'] = ($GLOBALS['saldoCredito']/$mesesActual)+($GLOBALS['saldoCredito']*$GLOBALS['intereses']);
            if($estadoActual == "Aprobado")
            {
                if ($idUsuario == NULL)
                {
                    cobrarVisitante($idCredito, $mesesActual, $fechaPago, $correoAsociado, $cobroMesActual, $con);
                }
                else
                {
                    cobrarCliente($idCredito, $idUsuario, $cobroMesActual, $mesesActual, $con);
                }
            }
            else
            {
                $GLOBALS['mssCobrarCreditos'] .= "El credito: ".$idCredito." aún no ha sido aprovado <br>";
            }
        }
        echo "<br>********RESUMEN: <br>".$GLOBALS['mssCobrarCreditos'];
    }
    else{
        $GLOBALS['mssCobrarCreditos'].= "Error al consultar cuentas: ".mysqli_error($con)."<br>";
        $GLOBALS['flagError'] = true;
    }

    function cobrarVisitante($idCreditoP, $mesesActualP, $fechaPagoP, $correoAsociadoP, $cobroMesActualP, $con)
    {
        $idCredito = "";
        $tipo = "";
        $mesActual = 0;
        $monto = 0.0;
        $diaUltimoPago = 0;
        $currentYear = 0;
        echo "*********************************************";
        echo "<br> <h3>Credito de visitante</h3> <br> Pago para este mes: ". $cobroMesActualP." Correo asociado: ". $correoAsociadoP."<br>";
        $sql = "SELECT * from Transacciones ORDER BY Fecha_transaccion ASC";
        if ($result = $con->query($sql)) {
            while ($row = $result->fetch_assoc()) 
            {
                $idCredito = $row['ID_CREDITO'];
                $tipo = $row['Tipo'];
                $fecha = $row['Fecha_transaccion'];
                $mesTransaccion = date("n", strtotime($fecha));
                $mesActual = date("n", strtotime($fecha));
                $currentYear = date("Y", strtotime($fecha));
                if($idCreditoP == $idCredito and $tipo == CONSIGNAR and $mesActual == $mesTransaccion)
                {
                    echo "<br>Fecha transaccion: ".$fecha." por un total de: ".$row['Monto'];
                    $monto = $monto + $row['Monto'];
                    if($monto >= $cobroMesActualP)
                    {
                        $diaUltimoPago = date('j', strtotime($fecha));
                        break;
                    }
                }
            }
            if($monto >= $cobroMesActualP and $diaUltimoPago <= $fechaPagoP)
            {
                echo "<br> El visitante ha pagado su credito este mes a tiempo <br>";
            }
            else if($monto >= $cobroMesActualP and $diaUltimoPago > $fechaPagoP)
            {
                $diasMora = $diaUltimoPago - $fechaPagoP;
                echo "<br> El visitante ha pagado su credito este mes con algunos días de retraso";
                echo "<br> Se le aplica una sancion en su credito de: ";
                aplicarMora($idCreditoP, $diasMora, $mesesActualP, $con);
            }
            else
            {
                $fechaActual = date("Y/m/d");
                $mesActual = date("n", strtotime($fechaActual));
                $currentYear = date("Y", strtotime($fechaActual));
                echo "<br>Estado: El visitante no ha pagado su credito este mes";
                $diasMora =  cal_days_in_month(CAL_GREGORIAN,$mesActual,$currentYear);
                echo "<br>Efecto: Efectuando sación por ".$diasMora." días, ". "un total de:";
                aplicarMora($idCreditoP, $diasMora, $mesesActualP, $con);
                echo "<br> Enviando correo de notificación<br>";
                $contenido = "Su credito con ID: ".$idCreditoP." debia ser cancelado este mes por un valor de: ".$cobroMesActualP."No se ha recibido este pago.";
                //enviar_correo($correoAsociadoP, "Notificacion de falta de pago", $contenido);
            }
        }
    }

    function aplicarMora($idCreditoP, $diasMoraP, $mesesActual, $con)
    {
        $saldoNuevo = 0;
        $saldoNuevo = $GLOBALS['saldoCredito'] + $GLOBALS['saldoCredito']*INTERES_MORA_VISITANTES*$diasMoraP;
        $sql = "UPDATE Credito SET Saldo = $saldoNuevo WHERE PID = $idCreditoP";
        if(mysqli_query($con, $sql))
        {
            $incremento = $GLOBALS['saldoCredito']*INTERES_MORA_VISITANTES*$diasMoraP;
            echo $incremento." $<br>";
            $GLOBALS['mssCobrarCreditos'] .= "El credito: $idCreditoP Se incremento en:".$incremento."<br>";
            registrarMora($idCreditoP, $incremento, $con);
        }
        else{
            $GLOBALS['mssCobrarCreditos'] .= "Problemas en la conexión ".mysqli_error($con)."<br>";
            $GLOBALS['flagError'] = true;
        }
    }

    function registrarMora($idCredito, $consignacion, $con)
    {
        $fecha_actual = date("Y-m-d");
        $tipo = MORA;
        $sql = "INSERT INTO Transacciones (Fecha_transaccion, Monto, Tipo, ID_CREDITO) VALUES (\"$fecha_actual\", $consignacion, \"$tipo\", $idCredito)";
        if(mysqli_query($con, $sql))
        {
            $GLOBALS['mssCobrarCreditos'] .= "La transaccion por mora se registro"."<br>";
        }
        else{
            $GLOBALS['mssCobrarCreditos'] .= "Problemas en la conexión ".mysqli_error($con)."<br>";
            $GLOBALS['flagError'] = true;
        }
    }

    function cobrarCliente($idCreditoP, $idUsuarioP, $cobroMesActualP, $mesesActual, $con)
    {
        $monto = 0.0;
        $dineroTotalCliente = 0.0;
        $montoCuentaActual = 0.0;
        $idCuentaActual = 0;
        $sql = "SELECT SUM(Saldo) as monto from Cuenta WHERE ID_USUARIO = $idUsuarioP";
        if ($result = $con->query($sql)) 
        {
            $monto = $result->fetch_assoc();
            $dineroTotalCliente = $monto['monto'];
            echo "*********************************************";
            echo "<br> <h3>Credito de Cliente</h3> <br> Pago para este mes: ". $cobroMesActualP." ID del cliente asociado: ". $idUsuarioP."<br>";
            if($cobroMesActualP <= $dineroTotalCliente)
            {
                $sql = "SELECT * from Cuenta WHERE ID_USUARIO = $idUsuarioP ORDER BY Saldo DESC";
                $auxCobroMensual = $cobroMesActualP;
                if ($result = $con->query($sql)) {
                    while ($row = $result->fetch_assoc() and $auxCobroMensual > 0)
                    {
                        $montoCuentaActual = $row['Saldo'];
                        $idCuentaActual = $row['PID'];
                        $auxCobroMensual = $auxCobroMensual - $montoCuentaActual;
                        if ($auxCobroMensual < 0)//SOBRE PAGO
                        {
                            descontarDeCuenta($idCuentaActual, $auxCobroMensual*-1, $mesesActual, $con);
                            saldarCredito($idCreditoP, $mesesActual, $con);
                        }
                        else if($auxCobroMensual == 0)//PAGO EXACTO
                        {
                            descontarDeCuenta($idCuentaActual, 0, $mesesActual, $con);
                            saldarCredito($idCreditoP, $mesesActual, $con);
                        }
                        else
                        {
                            descontarDeCuenta($idCuentaActual, 0, $mesesActual, $con);
                        }
                                                
                    }
                }
                else
                {
                    $GLOBALS['mssCobrarCreditos'].="<br>Error ORDER BYProblemas en la conexión ".mysqli_error($con)."<br>";
                }
            }
            else{
                echo "<br>SALDO INSUFICIENTE PARA COBRARLE AL CLIENTE: ".$idUsuarioP;

                $currentUserCorreo = mysqli_query($con, "SELECT Correo as correo from usuario WHERE PID = \"$idUsuarioP\"");
                $dataCurrentUser = mysqli_fetch_assoc($currentUserCorreo);
                $correo = $dataCurrentUser['correo'];

                echo "<br>SE HA ENVIADO UN CORREO A: ".$correo;
                enviar_correo($correo,"Paga sucio Muggle","Saldo en sus cuentas insuficiente para pagar un credito");
            }
        }
        else{
            $GLOBALS['mssCobrarCreditos'] .= "Error al consultar cuentas: ".mysqli_error($con)."<br>";
            $GLOBALS['flagError'] = true;
        }
    }

    function descontarDeCuenta($idCuenta, $montoActualizar, $mesesActual, $con){
        $sql = "UPDATE Cuenta SET Saldo = $montoActualizar WHERE PID = $idCuenta";
        if(mysqli_query($con, $sql))
        {
            echo "Se le desconto a la cuenta con ID: ".$idCuenta."<br>";
            $GLOBALS['mssCobrarCreditos'] .= "La cuenta: $idCuenta quedo con un total de: $montoActualizar"."<br>";
            registrarRetiro($idCuenta, $con);
        }
        else{
            $GLOBALS['mssCobrarCreditos'] .= "Problemas en la conexión ".mysqli_error($con)."<br>";
            $GLOBALS['flagError'] = true;
        }
    }

    function saldarCredito($idCredito, $mesesActual, $con){
        $saldoNuevo = $GLOBALS['saldoCredito'] - ($GLOBALS['saldoCredito']/$mesesActual);
        $mesesActual = $mesesActual - 1; 
        $sql = "";
        
        if($mesesActual == 0 and $saldoNuevo <= 0){
            $sql = "DELETE FROM Credito WHERE PID = $idCredito";
            if(mysqli_query($con, $sql))
            {
                echo "El credito con ID: ".$idCredito." Fue saldado <br>";
                $GLOBALS['mssCobrarCreditos'] .= "El credito: $idCredito quedo con un total de: $saldoNuevo y sera eliminado de la BD"."<br>";
            }
            else{
                $GLOBALS['mssCobrarCreditos'] .= "Problemas en la conexión ".mysqli_error($con)."<br>";
                $GLOBALS['flagError'] = true;
            }
        }
        else
        {
            $sql = "UPDATE Credito SET Saldo = $saldoNuevo, Meses = $mesesActual WHERE PID = $idCredito";
            if(mysqli_query($con, $sql))
            {
                $GLOBALS['mssCobrarCreditos'] .= "El credito: $idCredito quedo con un total de: $saldoNuevo"."<br>";
                registrarConsignacion($idCredito, $mesesActual + 1, $con);
            }
            else{
                $GLOBALS['mssCobrarCreditos'] .= "Problemas en la conexión ".mysqli_error($con)."<br>";
                $GLOBALS['flagError'] = true;
            }
        }
    }

    function registrarConsignacion($idCredito, $mesesActual, $con){
        $fecha_actual = date("Y-m-d");
        $consignacion = ($GLOBALS['saldoCredito']/$mesesActual);
        $tipo = CONSIGNAR;
        $sql = "INSERT INTO Transacciones (Fecha_transaccion, Monto, Tipo, ID_CREDITO) VALUES (\"$fecha_actual\", $consignacion, \"$tipo\", $idCredito)";
        if(mysqli_query($con, $sql))
        {
            $GLOBALS['mssCobrarCreditos'] .= "La transaccion se registro"."<br>";
        }
        else{
            $GLOBALS['mssCobrarCreditos'] .= "Problemas en la conexión ".mysqli_error($con)."<br>";
            $GLOBALS['flagError'] = true;
        }
    }
    
    function registrarRetiro($idCuenta, $con)
    {
        $fecha_actual = date("Y-m-d");
        $retiro = $GLOBALS['cobroMensual'];
        $tipo = RETIRAR;
        $sql = "INSERT INTO Transacciones (Fecha_transaccion, Monto, Tipo, ID_CUENTA) VALUES (\"$fecha_actual\", $retiro, \"$tipo\", $idCuenta)";
        if(mysqli_query($con, $sql))
        {
            $GLOBALS['mssCobrarCreditos'] .= "La transaccion se registro"."<br>";
        }
        else{
            $GLOBALS['mssCobrarCreditos'] .= "Problemas en la conexión ".mysqli_error($con)."<br>";
            $GLOBALS['flagError'] = true;
        }
    }

    function enviar_correo($destino, $titulo, $contenido)
    {
        require_once('PHPMailer/PHPMailerAutoload.php');
        $mail = new PHPMailer;

        // $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //SI TIENEN PROBLEMAS, DESCOMENTAR ESTE LINEA PARA SABER QUE FALLA
        $mail->isSMTP();                                            // Send using SMTP
        $mail->Host       = 'smtp.gmail.com';                    // Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
        $mail->Username   = 'mibancoproweb@gmail.com';                     // SMTP username
        $mail->Password   = 'Proweb2020';                               // SMTP password
        $mail->SMTPSecure = 'tsl';         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
        $mail->Port       = 25;                 //SI NECESARIO, CAMBIAR EL PUERTO AQUI. 


        $mail->setFrom('mibancoproweb@gmail.com', 'Mailer');
        $mail->addAddress($destino);
        $mail->isHTML();/*
        $mail->addAddress('joe@example.net', 'Joe User');     // Add a recipient
        $mail->addAddress('ellen@example.com');               // Name is optional
        $mail->addReplyTo('info@example.com', 'Information');
        $mail->addCC('cc@example.com');
        $mail->addBCC('bcc@example.com');*/

        $mail->Subject = $titulo;
        $mail->Body = $contenido;

        /*// Attachments
        $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
        $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name*/

        if (!$mail->Send()) {
            echo "No se enviado correctamente. ERROR:  ";
            echo $mail->ErrorInfo;
        } else {
            echo "Se ha enviado correctamente";
        }
    }
    echo "<input type='button'value='Terminar' onclick=\"document.location.href='index.php';\"/>";
?>
