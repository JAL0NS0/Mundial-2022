<?php
    session_start();
    $nombre = $_SESSION['nombre'];

    if($nombre === null || $nombre = ''){
        echo "Usted no tiene autorización para entrar a esta pagina, inicie sesión";
        die();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="estilos/encabezado.css">
    <link rel="stylesheet" href="estilos/listado.css">
    <link href="https://ges2.galileo.edu/resources/theme-ges-forall/images/favicon.ico" rel="shortcut icon" type="image/x-icon">
    <title>Estadios</title>
</head>
<body>
    <div id="encabezado">  
        <div id="logo">
            <img src="img/logo.png" alt="logo" alt>
        </div>
        <div id="inf_usuario">
            <div id="usuario">
                <?php
                    include('db.php');
                    $nombre = $_SESSION['nombre'];
                    echo "$_SESSION[nombre]";
                ?>
            </div>
        </div>
        <div id="cerrar">
            <a href="cerrar_session.php" class="botones">CERRAR SESION</a>
        </div>
    </div>
    <div id="menu">
        <ul>
            <li><a class="active" href="inicio.php">Inicio</a></li>
            <li><a class="active" href="#">Estadios</a></li>
        </ul>
    </div>
    <div id="contenido"  class="listado listado--estadio">
        <div id="nombre_listado">
            <h1>ESTADIOS</h1>
        </div>
        <div class="dato dato--titulos " >
            <div id="nombre"> NOMBRE </div>
            <div id="ciudad"> CIUDAD </div>
        </div>
        <div id="lista">
            <?php
                $query= "SELECT nombre,direccion FROM estadio";
                $result = pg_query($dbconn,$query);
                if(!$result){
                    echo 'ocurrio un error';
                    die();
                }else{
                    while( $arr = pg_fetch_array($result, NULL, PGSQL_ASSOC)){
                        echo    "<div class='dato'>";
                        echo        "<div id='nombre'>";
                        echo            $arr['nombre'];
                        echo        "</div>";
                        echo        "<div id='ciudad'>";
                        echo            $arr['direccion'];
                        echo        "</div>";
                        echo    "</div>";
                    }  
                }
            ?>
        </div>
        

    </div>
    
</body>
</html>