<?php
    session_start();
    $nombre = $_SESSION['nombre'];

    if($nombre === null || $nombre = '' || $nombre !== 'Admin'){
        echo "Usted no tiene autorización para entrar a esta pagina, inicie sesión";
        die();
    }

    $id =$_GET['id'];
    $nombre =$_POST['nombre'];
    $dir = $_POST['direccion'];

    
    include('../../db.php');

    $query="UPDATE estadio SET direccion='$dir' WHERE nombre='$nombre';";

    $result = pg_query($dbconn,$query) or die(foo("No se pudo actualizar el estadio $nombre: ",true,$dbconn));

    foo("Se actualizó exitosamente el estadio $nombre",false,$dbconn);
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