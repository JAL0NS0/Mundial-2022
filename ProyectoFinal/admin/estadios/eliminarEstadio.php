<?php
    session_start();
    $nombre = $_SESSION['nombre'];

    if($nombre === null || $nombre = '' || $nombre !== 'Admin'){
    echo "Usted no tiene autorización para entrar a esta pagina, inicie sesión";
    die();
    }
    $id=$_GET["id"];
    echo "Quere eliminar el estadio $id";

    include('../../db.php');


    $query="SELECT num_partido FROM partido WHERE estadio='$id';";
    $result = pg_query($dbconn,$query) or die(foo("No se pudo consultar: ",true,$dbconn));
    $filas = pg_num_rows($result);

    if($filas != 0){
        echo "<div class='texto'> El estadio $id tiene partidos programados, no se puede eliminar</div>";
        echo "    <a href='estadios.php' class='link'>REGRESAR A ESTADIOS</a>";
        die();
    }

    $query="DELETE FROM estadio WHERE nombre='$id'";

    $result = pg_query($dbconn,$query) or die(foo("No se pudo eliminar el estadio $id: ",true,$dbconn));

    foo("Se elimino exitosamente el estadio $id",false,$dbconn);
    pg_close($dbconn);

    function foo($mensaje, $error, $link){
        echo "<div class='aviso'>";
        if($error){
            echo "hola soy un error";
        }else{
            echo "    <div class='texto'>  $mensaje </div>";
        }
        echo "    <a href='estadios.php' class='link'>REGRESAR A ESTADIOS</a>";
        echo "</div>";
    }
?>