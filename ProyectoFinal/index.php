<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="estilos/login.css">
    <title>PROYECTO FINAL</title>
</head>
<body>
    <div id="encabezado">
        <h1>PROYECTO FINAL</h1>
    </div>
    <div class="contenido">
        <div id="logo">
            <img src="img/logo.png" alt="logo" alt>
        </div>
        <div id="datos">
            <div id="caja--inputs">
                <form action="login/validar.php" method="POST">

                    <?php
                        if(isset($_GET["reg"])){
                            if($_GET["reg"]=="false"){
                                echo "<div id='mensaje_error'>USUARIO O CONTRASEÑA INCORRECTOS</div>";
                            }
                        }
                    ?>
                    
                    <div id="usuario">
                        <label for="user">Usuario</label>
                        <input type="text" name="user">
                    </div>  
                    <div id="password">
                        <label for="password">Contraseña</label>
                        <input type="password" name="password">
                    </div>
                    <br>
                    <div class="enviar">
                        <button type="submit">INICIAR SESION</button>
                    </div>
                </form>

                <form action="login/nuevo_usuario.php" method="post">
                    <div class="enviar">
                        <button type="nuevo"> CREAR USUARIO </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>