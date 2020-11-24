
<html>
    <head>
        <meta charset="UTF-8">
        <title>Retirar</title>
    </head>
    <body>
        <h1>Retirar</h1>
        <h3>Ingrese la cantidad a retirar<h3>
        
        <form action="retirar_post.php"method="post">
        	<input type="text"name="cantidad">
        	<input type="submit" value="Confirmar" name="SubmitButton">
        </form>
        <input type='button'value='Cancelar' onclick="document.location.href='index.php';"/>
    </body>
</html>