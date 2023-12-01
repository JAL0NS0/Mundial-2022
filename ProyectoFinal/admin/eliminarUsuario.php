<?php
    session_start();
    $nombre = $_SESSION['nombre'];

    if($nombre === null || $nombre = '' || $nombre !== 'Admin'){
        echo "Usted no tiene autorización para entrar a esta pagina, inicie sesión";
        die();
    }
    
    $nickname = $_GET["id"];

    include('../db.php');

    $query="DELETE FROM apuesta WHERE nickname = '$nickname';
            DELETE FROM usuario WHERE nickname = '$nickname';";

    $result = pg_query($dbconn,$query) or die(foo("No se pudo eliminar el usuario $nickname: ",true,$dbconn));

    foo("Se elimino exitosamente el usuario $nickname",false,$dbconn);

    function foo($mensaje, $error, $link){
        echo "<div class='aviso'>";
        if($error){
            echo "hola soy un error";
        }else{
            echo "    <div class='texto'>  $mensaje </div>";
        }
        echo "    <a href='inicio.php' class='link'>REGRESAR A INICIO</a>";
        echo "</div>";
    }
    pg_close($dbconn);

?>