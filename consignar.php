<html>
    <head>
        <meta charset="UTF-8">
        <title>Consignar</title>
    </head>
    <body>
        <h1>Consignar</h1>
        <form action="consignar_post.php" method="post">
            <label for="accion">Transferir a</label>
            <select name ="accion" id="accion">
                <option
                    value="Cuenta"
                >Cuenta
                </option>
                <option
                        value="Credito"
                >Credito
                </option>
            </select>
            <br>
            <label for="IDP">ID</label><br>
            <input type="text" name="IDP"><br>
            <label for="cantidad">Cantidad</label><br>
            <input type="text" name="cantidad">
            <select name ="tipoMoneda" id="tipoMoneda">
                <option
                        value="JaveCoin"
                >JaveCoin
                </option>
                <option
                        value="COP"
                >COP
                </option>
            </select><br>

            <?php
                session_start();
                if(isset($_SESSION['IDUsuario'])){
                }
                else{
                    echo "<label for=\"cedula\">Cedula</label><br>";
                    echo "<input type=\"text\" name=\"cedula\"><br>";
                }
            ?>

            <input type="submit" value="Consignar" name="SubmitButton">

        </form>

        <input type='button'value='Regresar al index' onclick="document.location.href='index.php';"/>
    </body>
</html>