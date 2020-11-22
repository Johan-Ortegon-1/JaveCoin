<?php
    include_once dirname(__FILE__) . '/config.php';
    $con=mysqli_connect(HOST_DB,USUARIO_DB,USUARIO_PASS);
    $sql="CREATE DATABASE miBD";
    if (mysqli_query($con,$sql)) {
		echo "Base de datos miBD creada";
    }else {
		echo "Error en la creacion " . mysqli_error($con);
    }
?>
<input type='button'value='Regresar al index' onclick="document.location.href='index.php';"/>
