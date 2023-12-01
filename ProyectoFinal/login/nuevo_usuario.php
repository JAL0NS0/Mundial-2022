<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../estilos/login.css">
    <script src="../js/nuevo_usuario.js"></script>
    
    <title>PROYECTO FINAL</title>
</head>
<body>
    <div id="encabezado">
        <h1>PROYECTO FINAL</h1>
    </div>
    <div class="contenido">
        <div id="logo">
            <img src="../img/logo.png" alt="logo" alt>
        </div>
        <div id="datos">
            <div id="caja--inputs">
            <form action="ingresar_usuario.php" method="POST" id="formulario">
                <div class="dato">
                    <label for="nombre"> Nombre <input type="text" id="nombre" name="nombre"></label>
                </div>
                <div class="dato">
                    <label for="nickname"> Nickname <input type="text" id="nickname" name="nickname"></label>
                </div>
                <div class="dato">
                    <label for="contrasena"> Contraseña <input type="password" id="contrasena" name="contrasena"></label>
                </div>
                <div class="dato">
                    <label for="verif_contrasena"> Verifique contraseña <input type="password" id="verif_contrasena"></label>
                </div>
                <button type="submit">Crear usuario</button>
            </form>
            <form action="../index.php">
                <button type="submit">Cancelar</button>
            </form>
            </div>
        </div>
    </div>
</body>
</html>