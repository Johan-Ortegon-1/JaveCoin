<?php
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
            $tasa_interes = $row["Tasa_interes"];
            
            $cobroMesActual = ($GLOBALS['saldoCredito']/$mesesActual)+($GLOBALS['saldoCredito']*$tasa_interes);
            $GLOBALS['cobroMensual'] = ($GLOBALS['saldoCredito']/$mesesActual)+($GLOBALS['saldoCredito']*$tasa_interes);
            if ($idUsuario == NULL)
            {
                cobrarVisitante($correoAsociado, $cobroMesActual, $con);
            }
            else
            {
                cobrarCliente($idCredito, $idUsuario, $cobroMesActual, $mesesActual, $con);
            }
        }
        echo "<br>********RESUMEN: ".$GLOBALS['mssCobrarCreditos'];
    }
    else{
        $GLOBALS['mssCobrarCreditos'].= "Error al consultar cuentas: ".mysqli_error($con);
        $GLOBALS['flagError'] = true;
    }

    function cobrarVisitante($correoAsociadoP, $cobroMesActualP, $con)
    {
        echo "<br> Credito de visitante, saldo actual: ". $cobroMesActualP." Correo asociado: ". $correoAsociadoP;  
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
            echo "<br> La cantidad de dinero del cliente: ".$idUsuarioP." Es: ".$monto['monto']." Para pagar: ".$cobroMesActualP;
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
                    echo "Error ORDER BYProblemas en la conexión ".mysqli_error($con);
                }
            }
            else{
                echo "SALDO INSUFICIENTE PARA COBRARLE AL CLIENTE: ".$idUsuarioP;
                //Envio de correo al cliente
            }
        }
        else{
            $GLOBALS['mssCobrarCreditos'] .= "Error al consultar cuentas: ".mysqli_error($con);
            $GLOBALS['flagError'] = true;
        }

    }

    function descontarDeCuenta($idCuenta, $montoActualizar, $mesesActual, $con){
        $sql = "UPDATE Cuenta SET Saldo = $montoActualizar WHERE PID = $idCuenta";
        if(mysqli_query($con, $sql))
        {
            $GLOBALS['mssCobrarCreditos'] .= "La cuenta: $idCuenta quedo con un total de: $montoActualizar";
            registrarRetiro($idCuenta, $con);
        }
        else{
            $GLOBALS['mssCobrarCreditos'] .= "Problemas en la conexión ".mysqli_error($con);
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
                $GLOBALS['mssCobrarCreditos'] .= "El credito: $idCredito quedo con un total de: $saldoNuevo";
                registrarConsignacion($idCredito, $mesesActual + 1, $con);
            }
            else{
                $GLOBALS['mssCobrarCreditos'] .= "Problemas en la conexión ".mysqli_error($con);
                $GLOBALS['flagError'] = true;
            }
        }
        else
        {
            $sql = "UPDATE Credito SET Saldo = $saldoNuevo, Meses = $mesesActual WHERE PID = $idCredito";
            if(mysqli_query($con, $sql))
            {
                $GLOBALS['mssCobrarCreditos'] .= "El credito: $idCredito quedo con un total de: $saldoNuevo";
                registrarConsignacion($idCredito, $mesesActual + 1, $con);
            }
            else{
                $GLOBALS['mssCobrarCreditos'] .= "Problemas en la conexión ".mysqli_error($con);
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
            $GLOBALS['mssCobrarCreditos'] .= "La transaccion se registro";
        }
        else{
            $GLOBALS['mssCobrarCreditos'] .= "Problemas en la conexión ".mysqli_error($con);
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
            $GLOBALS['mssCobrarCreditos'] .= "La transaccion se registro";
        }
        else{
            $GLOBALS['mssCobrarCreditos'] .= "Problemas en la conexión ".mysqli_error($con);
            $GLOBALS['flagError'] = true;
        }
    }

?>
