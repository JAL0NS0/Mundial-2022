<?php
    $nombre = $_POST["nombre"];
    $direccion = $_POST["direccion"];

    include('../../db.php');

    $query= "INSERT INTO estadio(nombre,direccion) VALUES('$nombre','$direccion');";
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
        echo "    <a href='estadios.php' class='link'>REGRESAR A ESTADIOS</a>";
        echo "</div>";
    }
?>