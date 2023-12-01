<?php
    $nombre =   $_POST["nombre"];
    $nickname = $_POST["nickname"];
    $pass=      $_POST["contrasena"];

    include('../db.php');


    $query="SELECT nickname FROM usuario WHERE nickname='$nickname'";
    $result = pg_query($dbconn,$query) or die(foo("No se pudo consultar: ",true,$dbconn));
    $filas = pg_num_rows($result);

    if($filas != 0){
        echo "<div class='texto'> El nickname $nickname ya esta en uso, por favor escoga uno diferente</div>";
        echo "    <a href='nuevo_usuario.php' class='link'>REGRESAR A FORMULARIO</a>";
        die();
    }

    $query= "INSERT INTO usuario(nombre,nickname,puntos,contrasena) VALUES('$nombre','$nickname',0,'$pass')";
    $result = pg_query($dbconn,$query) or die(foo("No se pudo insertar: ",true,$dbconn));

    foo("Se insertó exitosamente",false,$dbconn);
    pg_close($dbconn);

    function foo($mensaje, $error, $link){
        echo "<div class='aviso'>";
        if($error){
            echo "    <div class='texto'>  $mensaje <br>". pg_last_error($link)."</div>";
        }else{
            echo "    <div class='texto'>  $mensaje </div>";
        }
        echo "    <a href='../index.php' class='link'>REGRESAR A LOGIN</a>";
        echo "</div>";
    }
?>