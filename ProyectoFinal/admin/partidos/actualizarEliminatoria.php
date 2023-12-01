<?php
    session_start();
    $nombre = $_SESSION['nombre'];


    include("../../db.php");


    if($nombre === null || $nombre = '' || $nombre !== 'Admin'){
        echo "Usted no tiene autorización para entrar a esta pagina, inicie sesión";
        die();
    }
    date_default_timezone_set('America/Guatemala');    
    $DateAndTime = date("Y-m-d H:i:00",time()); 
    $num=$_POST["num_p"];
    $fecha=$_POST["fecha"];
    $estadio=$_POST["estadio"];
    $nombre_h=$_POST["nombre_h"];
    $nombre_a=$_POST["nombre_a"];
    $goles_h= intval( $_POST["goles_h"]);
    $goles_a= intval( $_POST["goles_a"] );
    $penales_a=intval($_POST["penales_a"]);
    $penales_h=intval($_POST["penales_h"]);
    $grupo=$_POST["grupo"];

    if ($fecha==null or $estadio==null ) {
        echo "Error: Por favor ingrese el valor de todos los campos Fecha y Estadio";
        echo "   <br> <a href='editarEliminatoria.php?id=$num&grupo=$grupo' class='link'>REGRESAR A EDITAR</a>";
        die();
    }elseif (($goles_h==null and $goles_a!=null)or ($goles_h!=null and $goles_a==null)) {
        echo "Error: Por favor ingrese el puntaje de goles para ambos equipos";
        echo "   <br> <a href='editarEliminatoria.php?id=$num&grupo=$grupo' class='link'>REGRESAR A EDITAR</a>";
        die();
    }elseif (($penales_h==null and $penales_a!=null)or ($penales_h!=null and $penales_a==null)) {
        echo "Error: Por favor ingrese el puntaje de penales para ambos equipos";
        echo "   <br> <a href='editarEliminatoria.php?id=$num&grupo=$grupo' class='link'>REGRESAR A EDITAR</a>";
        die();
    };

    $newDate = date("Y-m-d", strtotime($fecha));

    $query="Select estadio, fecha_hora, nombre_h, nombre_a FROM partido WHERE TO_CHAR(fecha_hora, 'YYYY-MM-DD')='$newDate';";
    $result = pg_query($dbconn,$query);
    $filas = pg_num_rows($result);
    if($filas > 0){
        while($arr = pg_fetch_array($result, NULL, PGSQL_ASSOC)){
            if($arr['estadio'] == $estadio){
                echo "Error: Ya existe un partido jugandose el día $newDate estadio $estadio";
                echo "   <br> <a href='editar.php?id=$num&grupo=$grupo' class='link'>REGRESAR A EDITAR</a>";
                die();
            }elseif ($equipo_h==$arr['nombre_h'] or $equipo_h==$arr['nombre_a'] or $equipo_a==$arr['nombre_h'] or $equipo_a==$arr['nombre_a'] ) {
                echo "Error: $equipo_h o $equipo_a ya tienen programado un partido en la fecha $newDate";
                echo "   <br> <a href='editar.php?id=$num&grupo=$grupo' class='link'>REGRESAR A EDITAR</a>";
                die();
            }
        }
        
    }

    if($goles_h==null){
        $goles_h='null';
        $goles_a='null';
    }
    if($penales_h==null){
        $penales_h='null';
        $penales_a='null';
    }

    if($fecha > $DateAndTime){
        $goles_h='null';
        $goles_a='null';
        $penales_h='null';
        $penales_a='null';
    }

    $query="UPDATE partido SET fecha_hora='$fecha', estadio='$estadio', resultado_h=$goles_h, resultado_a=$goles_a,penales_h=$penales_h,penales_a=$penales_a WHERE num_partido=$num;";
    $result = pg_query($dbconn,$query) or die(foo("No se pudo actualizar el partido $num: ",true,$dbconn));
    foo("Se actualizó exitosamente el partido $num",false,$dbconn);

    if($goles_h!=null and $goles_a!=null){
        echo "aquí?";
        $query = "SELECT nickname FROM apuesta WHERE num_partido=$num";
        $result = pg_query($dbconn,$query) or die(foo("No se pudo consultar $num: ",true,$dbconn));
        while($arr = pg_fetch_array($result, NULL, PGSQL_ASSOC)){
            calcular($arr['nickname'],$dbconn);
        }
        if($penales_a!='null'){
            if($grupo=='SM'){
                if($goles_h+$penales_h > $goles_a+$penales_a){
                    finales($nombre_h,$nombre_a,$num,$dbconn);
                }else{
                    finales($nombre_a,$nombre_h,$num,$dbconn);
                }
            }else{
                if($goles_h+$penales_h > $goles_a+$penales_a){
                    clasificar($num,$nombre_h,$dbconn);
                }else{
                    clasificar($num,$nombre_a,$dbconn);
                }
            }
        }else{
            if($grupo=='SM '){
                if($goles_h > $goles_a){
                    finales($nombre_h,$nombre_a,$num,$dbconn);
                }else{
                    finales($nombre_a,$nombre_h,$num,$dbconn);
                }
            }else{
                if($goles_h > $goles_a){
                    clasificar($num,$nombre_h,$dbconn);
                }else{
                    clasificar($num,$nombre_a,$dbconn);
                }
            }
        }

        
    };


    pg_close($dbconn);

    function foo($mensaje, $error, $link){
        echo "<div class='aviso'>";
        if($error){
            echo "hola soy un error";
        }else{
            echo "    <div class='texto'>  $mensaje </div>";
        }
        echo "    <a href='../inicio.php' class='link'>REGRESAR A INICIO</a>";
        echo "</div>";
    }

    function calcular($nickname,$dbconn){
        $query = "SELECT U.nickname
                FROM usuario as U,apuesta as A,partido as P
                WHERE U.nickname = A.nickname 
                        and A.num_partido=P.num_partido 
                        and U.nickname='$nickname'
                        and ((A.prediccion_h>A.prediccion_a and P.resultado_h>P.resultado_a)
                            or(A.prediccion_h<A.prediccion_a and P.resultado_h<P.resultado_a))";
        $result = pg_query($dbconn,$query) or die(foo("No se pudo consultar: ",true,$dbconn));
        $filas = pg_num_rows($result);
        $punteo = 3*$filas;

        $query = "SELECT U.nickname
                FROM usuario as U,apuesta as A,partido as P
                WHERE U.nickname = A.nickname 
                        and A.num_partido=P.num_partido 
                        and U.nickname='$nickname'
                        and ( A.prediccion_h=P.resultado_h and A.prediccion_a=P.resultado_a)";
        $result = pg_query($dbconn,$query) or die(foo("No se pudo consultar: ",true,$dbconn));
        $filas = pg_num_rows($result);
        $extras = 3*$filas;

        $total=$punteo+$extras;

        $query="UPDATE usuario SET puntos = $total WHERE nickname='$nickname';";
        $result = pg_query($dbconn,$query) or die(foo("No se pudo actualizar el punteo de $nickname: ",true,$dbconn));
    }


    function clasificar($partido,$ganador,$dbconn){
        $lugar="";
        $siguiente="";
        switch ($partido) {
            case '53':
                $siguiente="58";
                $lugar="nombre_h";
                $query="UPDATE partido SET $lugar='$ganador'  WHERE num_partido=$siguiente;";
                $result = pg_query($dbconn,$query) or die(foo("No se pudo actualizar el partido: ",true,$dbconn));
                foo("Se actualizó exitosamente el partido",false,$dbconn);
                break;
            case '54':
                $siguiente="58";
                $lugar="nombre_a";
                $query="UPDATE partido SET $lugar='$ganador'  WHERE num_partido=$siguiente;";
                $result = pg_query($dbconn,$query) or die(foo("No se pudo actualizar el partido: ",true,$dbconn));
                foo("Se actualizó exitosamente el partido",false,$dbconn);
                break;
            case '49':
                $siguiente="57";
                $lugar="nombre_h";
                $query="UPDATE partido SET $lugar='$ganador'  WHERE num_partido=$siguiente;";
                $result = pg_query($dbconn,$query) or die(foo("No se pudo actualizar el partido: ",true,$dbconn));
                foo("Se actualizó exitosamente el partido",false,$dbconn);
                break;
            case '50':
                $siguiente="57";
                $lugar="nombre_a";
                $query="UPDATE partido SET $lugar='$ganador'  WHERE num_partido=$siguiente;";
                $result = pg_query($dbconn,$query) or die(foo("No se pudo actualizar el partido: ",true,$dbconn));
                foo("Se actualizó exitosamente el partido",false,$dbconn);
                break;
            case '55':
                $siguiente="60";
                $lugar="nombre_h";
                $query="UPDATE partido SET $lugar='$ganador'  WHERE num_partido=$siguiente;";
                $result = pg_query($dbconn,$query) or die(foo("No se pudo actualizar el partido: ",true,$dbconn));
                foo("Se actualizó exitosamente el partido",false,$dbconn);
                break;
            case '56':
                $siguiente="60";
                $lugar="nombre_a";
                $query="UPDATE partido SET $lugar='$ganador'  WHERE num_partido=$siguiente;";
                $result = pg_query($dbconn,$query) or die(foo("No se pudo actualizar el partido: ",true,$dbconn));
                foo("Se actualizó exitosamente el partido",false,$dbconn);
                break;
            case '51':
                $siguiente="59";
                $lugar="nombre_h";
                $query="UPDATE partido SET $lugar='$ganador'  WHERE num_partido=$siguiente;";
                $result = pg_query($dbconn,$query) or die(foo("No se pudo actualizar el partido: ",true,$dbconn));
                foo("Se actualizó exitosamente el partido",false,$dbconn);
                break;
            case '52':
                $siguiente="59";
                $lugar="nombre_a";
                $query="UPDATE partido SET $lugar='$ganador'  WHERE num_partido=$siguiente;";
                $result = pg_query($dbconn,$query) or die(foo("No se pudo actualizar el partido: ",true,$dbconn));
                foo("Se actualizó exitosamente el partido",false,$dbconn);
                break;

            case '57':
                $siguiente="61";
                $lugar="nombre_h";
                $query="UPDATE partido SET $lugar='$ganador'  WHERE num_partido=$siguiente;";
                $result = pg_query($dbconn,$query) or die(foo("No se pudo actualizar el partido: ",true,$dbconn));
                foo("Se actualizó exitosamente el partido",false,$dbconn);
                break;
            case '58':
                $siguiente="61";
                $lugar="nombre_a";
                $query="UPDATE partido SET $lugar='$ganador'  WHERE num_partido=$siguiente;";
                $result = pg_query($dbconn,$query) or die(foo("No se pudo actualizar el partido: ",true,$dbconn));
                foo("Se actualizó exitosamente el partido",false,$dbconn);
                break;
            case '59':
                $siguiente="62";
                $lugar="nombre_h";
                $query="UPDATE partido SET $lugar='$ganador'  WHERE num_partido=$siguiente;";
                $result = pg_query($dbconn,$query) or die(foo("No se pudo actualizar el partido: ",true,$dbconn));
                foo("Se actualizó exitosamente el partido",false,$dbconn);
                break;
            case '60':
                $siguiente="62";
                $lugar="nombre_a";
                $query="UPDATE partido SET $lugar='$ganador'  WHERE num_partido=$siguiente;";
                $result = pg_query($dbconn,$query) or die(foo("No se pudo actualizar el partido: ",true,$dbconn));
                foo("Se actualizó exitosamente el partido",false,$dbconn);
                break;            
            default:
                $siguiente="0";
                $lugar="";
                break;
        }
    }

    function finales($ganador,$perdedor,$partido,$dbconn){
        echo "LLEGAMSOS A LAS FINALES";
        if($partido=='61'){
            $query="UPDATE partido SET nombre_h='$ganador'  WHERE num_partido=64;";
            $result = pg_query($dbconn,$query) or die(foo("No se pudo actualizar el partido: ",true,$dbconn));
            foo("Se actualizó exitosamente el partido",false,$dbconn);

            $query="UPDATE partido SET nombre_h='$perdedor'  WHERE num_partido=63;";
            $result = pg_query($dbconn,$query) or die(foo("No se pudo actualizar el partido: ",true,$dbconn));
            foo("Se actualizó exitosamente el partido",false,$dbconn);
        }elseif ($partido=='62') {
            $query="UPDATE partido SET nombre_a='$ganador'  WHERE num_partido=64;";
            $result = pg_query($dbconn,$query) or die(foo("No se pudo actualizar el partido: ",true,$dbconn));
            foo("Se actualizó exitosamente el partido",false,$dbconn);

            $query="UPDATE partido SET nombre_a='$perdedor'  WHERE num_partido=63;";
            $result = pg_query($dbconn,$query) or die(foo("No se pudo actualizar el partido: ",true,$dbconn));
            foo("Se actualizó exitosamente el partido",false,$dbconn);
        }
    }
?>