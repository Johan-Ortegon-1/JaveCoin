<?php
    session_start();
?>
<?php
    $vistaHTMLGeneral = "";
    $vistaHTMLCurrentUserRol = "";

    if(!isset($_SESSION['currentUserID']) or !isset($_SESSION['currentUserRol']))
    {
        $vistaHTMLGeneral .= "<h3>Al ser visitante requerimos un correo</h3>
        <label for=\"email\">Correo: </label><br>
        <input type=\"email\" name=\"email\" id=\"email\"><br>";
    }
    else if(isset($_SESSION['currentUserID']) and $_SESSION['currentUserRol'] == "Cliente")
    {
        $vistaHTMLGeneral .= "<h3>Al ser cliente puede proponer una tasa</h3>
        <label for=\"tasa\">Proponga una tasa:  </label><br>
        <input type=\"number\" min=\"0.01\" step=.01 name=\"tasa\" id=\"tasa\">%<br>";
    }  
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Sacar un credito</title>
    </head>
    <body>
        <h1>Sacar un credito</h1>
        <form action="sacar_credito_post.php" method="POST">
            <label for="tasa">valor del credito:  </label><br>
            <input type="number" min="1000" step="100" name="saldo" id="saldo">$$$<br>
            <?php
                echo $vistaHTMLGeneral;
            ?>
            <br>
            <input type="submit" value="Solicitar credito" name="solicitar">
        </form>
        <input type='button'value='Cancelar' onclick="document.location.href='index.php';"/>
    </body>
</html>