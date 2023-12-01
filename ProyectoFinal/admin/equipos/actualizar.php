<?php
    session_start();
    $nombre = $_SESSION['nombre'];

    if($nombre === null || $nombre = '' || $nombre !== 'Admin'){
        echo "Usted no tiene autorización para entrar a esta pagina, inicie sesión";
        die();
    }

    $nombre =$_POST['nombre'];
    $bandera =$_POST['bandera'];
    $grupo = $_POST['grupo'];
    echo "Quiere actualizar el equipo $nombre con bandera $bandera y del grupo $grupo";

    
    include('../../db.php');

    $query="UPDATE equipos SET nombre ='$nombre', bandera='$bandera', grupo='$grupo' WHERE nombre='$nombre';";

    $result = pg_query($dbconn,$query) or die(foo("No se pudo actualizar el equipo $nombre: ",true,$dbconn));

    foo("Se actualizó exitosamente el equipo $nombre",false,$dbconn);
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