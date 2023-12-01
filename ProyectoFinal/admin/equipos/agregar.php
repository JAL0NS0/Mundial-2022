<?php
    session_start();
    $nombre = $_SESSION['nombre'];

    if($nombre === null || $nombre = '' || $nombre !== 'Admin'){
        echo "Usted no tiene autorización para entrar a esta pagina, inicie sesión";
        die();
    }

    
    
    $nombre = $_POST["nombre"];
    $bandera = $_POST["bandera"];
    $grupo = $_POST["grupo"];
    echo "Se quiere ingresar $nombre con bandera $bandera para el grupo $grupo";
    include('../../db.php');


    $query= "SELECT nombre FROM equipos WHERE grupo='$grupo'";
    $result = pg_query($dbconn,$query);
    $filas = pg_num_rows($result);
    if($filas >=4){
        echo "<div>Error, los grupos solo pueden tener 4 integrantes</div>";
        echo "    <a href='equipos.php' class='link'>REGRESAR A EQUIPOS</a>";
        pg_close($dbconn);
        die();
    }
    
    

    $query= "INSERT INTO equipos(nombre,bandera,partidos_ganados,partidos_perdidos,partidos_empatados,goles_favor,goles_contra,grupo) VALUES('$nombre','$bandera',0,0,0,0,0,'$grupo');";
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
        echo "    <a href='equipos.php' class='link'>REGRESAR A EQUIPOS</a>";
        echo "</div>";
    }
?>