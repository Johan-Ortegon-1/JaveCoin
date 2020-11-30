<?php
    session_start();
?>
<?php
    $vistaHTMLGeneral = "";
    $vistaHTMLCurrentUserRol = "";
    $loginFlag = true;
    if(!isset($_SESSION['currentUserID']) or !isset($_SESSION['currentUserRol']))
    {
        $loginFlag = false;
        $vistaHTMLCurrentUserRol.="Visitante";
        $vistaHTMLGeneral .= "<h3>Funciones del Visitante</h3>
        <input type='button'value='Consignar' onclick=\"document.location.href='consignar.php';\"/>
        <input type='button'value='Sacar un credito' onclick=\"document.location.href='sacar_credito.php';\"/>";
    }
    else if(isset($_SESSION['currentUserID']) and $_SESSION['currentUserRol'] == "Cliente")
    {
        $vistaHTMLCurrentUserRol.="Cliente";
        $vistaHTMLGeneral .= "<h3>Funciones del Cliente</h3>
        <input type='button'value='Crear una cuenta de ahorros' onclick=\"document.location.href='crear_cuenta_ahorros.php';\"/>
        <input type='button'value='Retirar' onclick=\"document.location.href='retirar.php';\"/>
        <input type='button'value='Consignar' onclick=\"document.location.href='consignar.php';\"/>
        <input type='button'value='Sacar una credito' onclick=\"document.location.href='sacar_credito.php';\"/>
        <input type='button'value='Sacar una tarjeta' onclick=\"document.location.href='sacar_tarjeta.php';\"/>
        <input type='button'value='Comprar con tarjeta' onclick=\"document.location.href='comprar_con_tarjeta.php';\"/>
        <input type='button'value='Ver mis productos' onclick=\"document.location.href='ver_mis_productos.php';\"/>
        <input type='button'value='Transferir' onclick=\"document.location.href='transferir.php';\"/>";
    }
    else if(isset($_SESSION['currentUserID']) and $_SESSION['currentUserRol'] == "Administrador")
    {
        $vistaHTMLCurrentUserRol.="Administrador";
        $vistaHTMLGeneral .= "<h3>Funciones del Administrador</h3>
        <input type='button'value='Realizar acciones de administración' onclick=\"document.location.href='administrar.php';\"/>";
    }   
?>
<html>
    <title>BANCO XXX</title>
        <body>
            <div>
                <?php
                    if(!$loginFlag)
                    {
                        echo "<a href=\"login.php\">Login</a>
                        <a href=\"register.php\">Resgistrarse</a>";
                    }
                    else
                    {
                        echo "<a href=\"logout.php\">Logout</a>";
                    }
                ?>
            </div>
            <div>
                <?php
                    if($loginFlag and isset($_SESSION['currentUserNombre']))
                    {
                        echo "<h3>Bienvenido: ".$_SESSION['currentUserNombre']."</h3>";
                    }
                    echo "<h3>Rol del usuario actual: ".$vistaHTMLCurrentUserRol."</h3>";
                ?>
            </div>
            <h3>Configuracion inicial del sistema</h3>
            <input type='button'value='Crear Base de datos' onclick="document.location.href='crear_db.php';"/>
            <input type='button'value='Crear Tabla' onclick="document.location.href='crear_tabla.php';"/>
            <?php
                echo $vistaHTMLGeneral;
            ?>
        </body>
    </html>
