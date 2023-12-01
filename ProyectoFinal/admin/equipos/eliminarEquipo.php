<?php
    session_start();
    $nombre = $_SESSION['nombre'];

    if($nombre === null || $nombre = '' || $nombre !== 'Admin'){
        echo "Usted no tiene autorización para entrar a esta pagina, inicie sesión";
        die();
    }
    
    $id=$_GET["id"];

    include('../../db.php');

    $query="SELECT num_partido FROM partido WHERE nombre_h='$id' or nombre_a='$id';";
    $result = pg_query($dbconn,$query) or die(foo("No se pudo consultar: ",true,$dbconn));
    $filas = pg_num_rows($result);

    if($filas != 0){
        echo "<div class='texto'> El equipo $id tiene partidos programados, no se puede eliminar</div>";
        echo "    <a href='equipos.php' class='link'>REGRESAR A EQUIPOS</a>";
        die();
    }

    $query="DELETE FROM equipos WHERE nombre = '$id'";

    $result = pg_query($dbconn,$query) or die(foo("No se pudo eliminar el equipo $id: ",true,$dbconn));

    foo("Se elimino exitosamente el equipo $id",false,$dbconn);
    pg_close($dbconn);

    function foo($mensaje, $error, $link){
        echo "<div class='aviso'>";
        if($error){
            echo "hola soy un error";
        }else{
            echo "    <div class='texto'>  $mensaje </div>";
        }
        echo "    <a href='equipos.php' class='link'>REGRESAR A EQUIPOS</a>";
        echo "</div>";
    }
?>