<?php
    $mssCobrarCreditos = "";
    $idUsuario = "";
    $saldoCredito = "";
    $correoAsociado = "";
    $mesesActual = 0;
    $tasa_interes = 0.0;
    $cobroMesActual = 0.0;
    $sql = "SELECT * from Credito";
    //Recorrer todos los creditos
    if ($result = $con->query($sql)) {
        while ($row = $result->fetch_assoc()) 
        {
            $idUsuario = $row["ID_USUARIO"];
            $saldoCredito = $row["Saldo"];
            $correoAsociado = $row["Correo_notificaciones"];
            $mesesActual = $row["Meses"];
            $tasa_interes = $row["Tasa_interes"];
            
            $cobroMesActual = ($saldoCredito/$mesesActual)*$tasa_interes;
            if ($idUsuario == NULL)
            {
                cobrarVisitante($correoAsociado, $cobroMesActual);
            }
            else
            {
                cobrarCliente($idUsuario, $cobroMesActual);
            }
        }
    }
    else{
        $mssCobrarCreditos.= "Error al consultar cuentas: ".mysqli_error($con);
    }

    function cobrarVisitante($correoAsociadoP, $cobroMesActualP)
    {
        echo "<br> Credito de visitante, saldo actual: ". $cobroMesActualP." Correo asociado: ". $correoAsociadoP;  
    }

    function cobrarCliente($idUsuarioP, $cobroMesActualP)
    {
        
    }
    

?>
