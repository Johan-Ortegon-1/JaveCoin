<html>
    <head>
        <meta charset="UTF-8">
        <title>Registarse</title>
    </head>
    <body>
        <h1>Registar</h1>
        <h3>Para clientes o administradones nuevos</h3>
        <form action="register_post.php" method="POST">
            <label for="nombre_u">Nombre de usuario: </label><br>
            <input type="text" name="nombre_u" id="nombre_u"><br>
            <label for="password">Contraseña: </label><br>
            <input type="password" name="password" id="password"><br>
            <label for="rol">Rol asignado: </label>
            <select name="rol">
                <option value="Administrador">Administrador</option>
                <option value="Cliente">Cliente</option>
            </select>
            <br>
            <input type="submit" value="Crear" name="Crear">
        </form>
        <input type='button'value='Cancelar' onclick="document.location.href='index.php';"/>
    </body>
</html>