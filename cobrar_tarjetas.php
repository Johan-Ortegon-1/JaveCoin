<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <title>Cobrar tarjetas</title>
</head>
<body>
    <h1>
        Cobrando Tarjetas
    </h1>

    <?php

        $date = date('Y-m-d');

        $finCorte = date('2021-m-15');
        $inicioCorte = date('Y-m-15', strtotime($finCorte. ' - 1 month'));

    echo "
        <h3>
            Fecha Hoy: $date<br>
            Fecha Corte: $inicioCorte - $finCorte<br>
        </h3>
        ";

        //OBTENER TARJETAS
        $sql_tarjetas = "SELECT * FROM `tarjeta_credito`";

        include_once dirname(__FILE__) . '/config.php';
        $con = mysqli_connect(HOST_DB, USUARIO_DB, USUARIO_PASS, NOMBRE_DB);

        if (mysqli_connect_errno()) {
            $errRetiro = "Error en la conexión: ";
        }else {
            $tarjetas = mysqli_query($con,$sql_tarjetas);

            while($fila = mysqli_fetch_array($tarjetas)) {

                $total = 0;

                echo "------------------------------------------------------------<br>";
                $sql_cuenta = "SELECT * FROM `cuenta` WHERE `PID` = $fila[ID_CUENTA]";
                $cuenta = mysqli_query($con,$sql_cuenta);

                $row = $cuenta->fetch_assoc();
                $saldo = $row['Saldo'];
                $id_cuenta = $row['PID'];

                $sql_Usuario = "SELECT * FROM `usuario` WHERE `PID` = $row[ID_USUARIO]";
                $usuario = mysqli_query($con,$sql_Usuario);

                $row = $usuario->fetch_assoc();


                echo "Nombre Usuario: $row[Nombre]<br>";
                echo "ID Usuario: $row[PID]<br>";
                echo "Correo Usuario: $row[Correo]<br>";


                echo "ID Tarjeta: $fila[PID]<br>
                Cupo: $fila[Cupo]<br>
                Sobre-cupo: $fila[Sobre_cupo]<br>
                Tasa interes: $fila[Tasa_interes]<br>
                Cuota manejo: $fila[Cuota_manejo]<br>
                ";

                $sql_compras = "SELECT * FROM `compras` WHERE DATE(Fecha_compra) <= '$finCorte' AND `ID_TARJETA` = $fila[PID]";
                $compras = mysqli_query($con,$sql_compras);
                echo "<table>
                <tr>
                    <th>ID</th>
                    <th>FECHA COMPRA</th>
                    <th>Total Pagar</th>
                    <th>Cuotas</th>
                    <th>Cuotas Pagadas</th>
                </tr>
                ";

                $numCompras = $compras->num_rows;
                //if($numCompras > 0){
                    while($fila2 = mysqli_fetch_array($compras)) {
                        $pago_mes = $fila2['totalPagar']/$fila2['cuotas'];
                        if($fila2['cuotas_pagadas'] == 0){
                            $total = $total+ $pago_mes;
                        }else{
                            $faltaPagar = $fila2['totalPagar'] - $pago_mes * $fila2['cuotas_pagadas'];
                            $total = $total + $pago_mes + ($faltaPagar * $fila['Tasa_interes']);
                        }
                        echo "AYUDA $fila2[cuotas_pagadas]";
                        echo "<tr>
                     <td>$fila2[PID]</td>
                     <td>$fila2[Fecha_compra]</td>
                     <td>$fila2[totalPagar]</td>
                     <td>$fila2[cuotas]</td>
                     <td>$fila2[cuotas_pagadas]</td>
                 
                    </tr>";
                    }
                //}

                $superTotal = $total+$fila['Cuota_manejo'];
                echo "</table>";
                echo "
                    <br>Total Productos: $total<br>
                    Cuota de Manejo: $fila[Cuota_manejo]<br>
                    Total: $superTotal<br>
                    Saldo disponible: $saldo<br>
                ";
                echo "------------------------------------------------------------<br>";

                $accion_realizada = "";


                //si puede pagar el saldo lo paga
                if($saldo >=  $superTotal){
                    $update_saldo = "UPDATE `cuenta` SET `Saldo` = $saldo - $superTotal WHERE `cuenta`.`PID` = $id_cuenta";
                    mysqli_query($con,$update_saldo);

                    //se hace el update de las cuotas
                    $update_cuotas = "UPDATE `compras` SET `cuotas_pagadas` = (`cuotas_pagadas` + 1) WHERE ID_TARJETA = $fila[PID]";
                    mysqli_query($con,$update_cuotas);


                    //se borran las compras pagadas en su totalidad
                    $delete_compras = "DELETE FROM `compras` WHERE `cuotas_pagadas` >= cuotas AND ID_TARJETA = $fila[PID]";
                    mysqli_query($con,$delete_compras);

                    $nuevoSaldo = $saldo-$superTotal;
                    $accion_realizada = "Pago realizado satisfactoriamente";
                    echo "Nuevo Saldo: $nuevoSaldo<br>";
                //en caso contrario se envia el correo
                }else{
                    $accion_realizada = "Correo enviado";
                }

                echo "$accion_realizada<br>";


            }

        }
    ?>
</body>
</html>