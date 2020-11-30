<html>
    <head>
        <meta charset="UTF-8">
        <title>Login</title>
    </head>
    <body>
        <h1>Login</h1>
        <form action="login_post.php" method="POST">
            <label for="nombre_u">Nombre de usuario: </label><br>
            <input type="text" name="nombre_u" id="nombre_u"><br>
            <label for="password">Contraseña: </label><br>
            <input type="password" name="password" id="password"><br>
            <br>
            <input type="submit" value="login" name="login">
        </form>
        <input type='button'value='Cancelar' onclick="document.location.href='index.php';"/>
    </body>
</html>