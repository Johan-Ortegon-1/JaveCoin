<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>



	<!-- Imprimir resultado de la transaccion -->
	<?php
	$errRetiro = "";
	$cantidad = $_POST["cantidad"];
    $cuenta =  $_POST["IDCuenta"];
	include_once dirname(__FILE__) . '/config.php';
	$con = mysqli_connect(HOST_DB, USUARIO_DB, USUARIO_PASS, NOMBRE_DB);

	if ($_SERVER["REQUEST_METHOD"] == "POST")
	{
		if (empty($_POST["cantidad"]) ){
			$errRetiro = "Transaccion declinada: Ingrese una cantidad valida";
		}

		else if (!preg_match("/^[0-9]*(.[0-9])?$/",$cantidad))
		{
			$errRetiro = "Transaccion declinada: Ingrese una cantidad valida";
		}
	    else if (mysqli_connect_errno()) {
	        $errRetiro = "Error en la conexión: ";
	    }else {
		    //busco la cuenta relacionada con el ID del usuario
		    $sql = "UPDATE `cuenta` 
		    SET Saldo = CASE
	  			 WHEN (Saldo-$cantidad)>=0 THEN Saldo-$cantidad
	  			 ELSE Saldo
	  			 END
		    WHERE `PID` = $cuenta";

			$resultado = mysqli_query($con,$sql );
		   	if(mysqli_affected_rows($con) !== 0){
		   		$errRetiro = "Transaccion exitosa";
		   	}else{
		   		$errRetiro = "Saldo insuficiente";
		   	}	
	    }
	}
	?>

	<!-- Alerta con el resultado de la transaccion -->
	<?php echo $errRetiro;
		echo "<script>
			alert(\"$errRetiro\");
			window.location.href = \"index.php\";
		</script>"
	?>
</body>
</html>