<html>
    <title>BANCO XXX</title>
    <body>
        <div>
            <a href="login.php">Login</a>
            <a href="register.php">Resgistrarse</a>
        </div>
        <h3>Configuracion inicial del sistema</h3>
        <input type='button'value='Crear Base de datos' onclick="document.location.href='crear_db.php';"/>
        <input type='button'value='Crear Tabla' onclick="document.location.href='crear_tabla.php';"/>
        <h3>Funciones del Visitante</h3>
        <input type='button'value='Consignar' onclick="document.location.href='consignar.php';"/>
        <input type='button'value='Sacar un credito' onclick="document.location.href='sacar_credito.php';"/>
        <h3>Funciones del Cliente</h3>
        <input type='button'value='Crear una cuenta de ahorros' onclick="document.location.href='crear_cuenta_ahorros.php';"/>
        <input type='button'value='Retirar' onclick="document.location.href='retirar.php';"/>
        <input type='button'value='Sacar una tarjeta' onclick="document.location.href='sacar_tarjeta.php';"/>
        <input type='button'value='Comprar con tarjeta' onclick="document.location.href='comprar_con_tarjeta.php';"/>
        <input type='button'value='Ver mis productos' onclick="document.location.href='ver_mis_productos.php';"/>
        <input type='button'value='Transferir' onclick="document.location.href='transferir.php';"/>
        <h3>Funciones del Administrador</h3>
        <input type='button'value='Realizar acciones de administración' onclick="document.location.href='administrar.php';"/>
    </body>
</html>