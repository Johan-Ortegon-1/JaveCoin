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
        <input type=\"email\" name=\"email\" id=\"email\" require><br>";
    }
    else if(isset($_SESSION['currentUserID']) and $_SESSION['currentUserRol'] == "Cliente")
    {
        $vistaHTMLGeneral .= "<h3>Al ser cliente puede proponer una tasa</h3>
        <label for=\"tasa\">Proponga una tasa:  </label><br>
        <input type=\"number\" min=\"0.01\" step=.01 name=\"tasa\" id=\"tasa\" require>%<br>";
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
            <input type="number" require min="100" require step="1" name="saldo" id="saldo">JAVE COINS<br>
            <?php
                echo $vistaHTMLGeneral;
            ?>
            <label for="diaPago">Día del mes / Fecha limite de pago:  </label>
            <select name="diaPago" require>
                <option value="1">Día 1</option>
                <option value="2">Día 2</option>
                <option value="3">Día 3</option>
                <option value="4">Día 4</option>
                <option value="5">Día 5</option>
                <option value="6">Día 6</option>
                <option value="7">Día 7</option>
                <option value="8">Día 8</option>
                <option value="9">Día 9</option>
                <option value="10">Día 10</option>
                <option value="11">Día 11</option>
                <option value="12">Día 12</option>
                <option value="13">Día 13</option>
                <option value="14">Día 14</option>
                <option value="15">Día 15</option>
                <option value="16">Día 16</option>
                <option value="17">Día 17</option>
                <option value="18">Día 18</option>
                <option value="19">Día 19</option>
                <option value="20">Día 20</option>
                <option value="21">Día 21</option>
                <option value="22">Día 22</option>
                <option value="23">Día 23</option>
                <option value="24">Día 24</option>
                <option value="25">Día 25</option>
                <option value="26">Día 26</option>
                <option value="27">Día 27</option>
                <option value="28">Día 28</option>
                <option value="29">Día 29</option>
                <option value="30">Día 30</option>
                <option value="30">Día 31</option>
            </select>
            <br>
            <input type="submit" value="Solicitar credito" name="solicitar">
        </form>
        <input type='button'value='Cancelar' onclick="document.location.href='index.php';"/>
    </body>
</html>