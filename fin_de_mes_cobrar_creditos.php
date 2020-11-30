<?php
    $mssCobrarCreditos = "";
    $idUsuario = "";
    $saldoCredito = "";
    $correoAsociado = "";
    $sql = "SELECT * from Credito";
    //Recorrer todos los creditos
    if ($result = $con->query($sql)) {
        while ($row = $result->fetch_assoc()) 
        {
            $idUsuario = $row["ID_USUARIO"];
            $saldoCredito = $row["Saldo"];
            $correoAsociado = $row["Correo_notificaciones"];
            if ($idUsuario == NULL)
            {
                cobrarVisitante($saldoCredito, $correoAsociado);
            }
            else
            {
                cobrarCliente($idUsuario, $saldoCredito);
            }
        }
    }
    else{
        $mssCobrarCreditos.= "Error al consultar cuentas: ".mysqli_error($con);
    }

    function cobrarVisitante($saldoCreditoP, $correoAsociadoP)
    {
        echo "<br> Credito de visitante, saldo actual: ". $saldoCreditoP." Correo asociado: ". $correoAsociadoP;  
    }

    function cobrarCliente($idUsuarioP, $saldoCreditoP)
    {
        
    }
    

?>
