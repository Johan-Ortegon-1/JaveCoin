<html>
    <title>GESTOR DE PERSONAS :)</title>
    <body>
        <h3>Configuracion inicial</h3>
        <input type='button'value='Crear Base de datos' onclick="document.location.href='crear_db.php';"/>
        <input type='button'value='Crear Tabla' onclick="document.location.href='crear_tabla.php';"/>
        <h3>Gestionar personas</h3>
        <form action="insertar_personas.php" method="POST">
            Cedula: <input type="number" name="cedula"><br>
            Nombre: <input type="text" name="nombre"><br>
            Apellido: <input type="text" name="apellido"><br>
            Correo: <input type="email" name="correo"><br>
            Edad: <input type="number" name="edad"><br>
            <input type="submit" value="Crear/Actualizar" name="Crear/Actualizar">
            <input type="submit" value="Eliminar" name="Eliminar">
        </form>
        <h3>Visualizar personas</h3>
        <form action="presentar.php" method="GET">
            <label for="order">Forma de visualizacion</label>
            <select name="orden">
                <option value="Ascendente">Ascendente</option>
                <option value="Descendente">Descendente</option>
            </select>
            <br>
            <label for="Nombre">Ordenar por</label>
            <input type="submit" value="Nombre" name="Nombre">
            <input type="submit" value="Cedula" name="Cedula">
        </form>
        <h3>Subir archivos</h3>
        <form action="archivo_subir.php" method="post"
        enctype="multipart/form-data">
        <label for="arch">Nombre:</label>
        <input type="file" name="arch" id="arch"><br>
        <input type="submit" name="submit" value="Enviar">
    </form>
    <?php
        crear_imagen();
        echo "<img src=imagen.png?".date("U").">";
        function  crear_imagen(){
                $ancho = rand(200,400);
                $alto = rand(200,400);
                $im = imagecreate($ancho, $alto) or die("Error en la creacion de imagenes");
                $color_fondo = imagecolorallocate($im, rand(0,255), rand(0,255), rand(0,255));   // Random color

                $colo1 = imagecolorallocate($im, rand(0,255), rand(0,255), rand(0,255));                  // Random color
                $color2 = imagecolorallocate($im, rand(0,255), rand(0,255), rand(0,255));                 // Random color
                $color3 = imagecolorallocate($im, rand(0,255), rand(0,255), rand(0,255));                 // Random color
                imagerectangle ($im,   rand(0,$ancho),  rand(0,$alto), rand(0,$ancho), rand(0,$alto), $colo1); //rectangulo (borde) inicio X, inicio Y, Fin X, Fin Y
                imagefilledrectangle ($im, rand(0,$ancho),  rand(0,$alto), rand(0,$ancho), rand(0,$alto), $color2); //rectangulo (lleno)
                imageellipse($im, rand(0,$ancho), rand(0,$alto), rand(0,$alto), rand(0,$alto), $color3); //elipse
                imagepng($im,"imagen.png");
                imagedestroy($im);
        }
        date_default_timezone_set('America/Bogota');
        echo "<br> Fecha y hora:" . date("Y/m/d") . "  " . date('H:i') . "<br>";
        ?>
    </body>
</html>