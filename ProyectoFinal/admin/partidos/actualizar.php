<?php
    session_start();
    $nombre = $_SESSION['nombre'];

    if($nombre === null || $nombre = '' || $nombre !== 'Admin'){
        echo "Usted no tiene autorización para entrar a esta pagina, inicie sesión";
        die();
    }

    include("../../db.php");

    date_default_timezone_set('America/Guatemala');    
    $DateAndTime = date("Y-m-d H:i:00",time()); 
    $grupo=$_POST["grupo"];
    $num=$_POST["num_p"];
    $fecha=$_POST["fecha"];
    $estadio=$_POST["estadio"];
    $equipo_h=$_POST["equipo_h"];
    $equipo_a=$_POST["equipo_a"];
    $goles_h=$_POST["goles_h"];
    $goles_a=$_POST["goles_a"];

    if($equipo_h == $equipo_a){
        echo "Error: Debe seleccionar dos equipos diferentes para ingresar un partido";
        echo "   <br> <a href='editar.php?id=$num&grupo=$grupo' class='link'>REGRESAR A EDITAR</a>";
        die();
    }elseif ($fecha==null or $estadio==null ) {
        echo "Error: Por favor ingrese el valor de todos los campos Fecha y Estadio";
        echo "   <br> <a href='editar.php?id=$num&grupo=$grupo' class='link'>REGRESAR A EDITAR</a>";
        die();
    }elseif (($goles_h==null and $goles_a!=null)or ($goles_h!=null and $goles_a==null)) {
        echo "Error: Por favor ingrese el puntaje de goles para ambos equipos";
        echo "   <br> <a href='editar.php?id=$num&grupo=$grupo' class='link'>REGRESAR A EDITAR</a>";
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


    if($fecha > $DateAndTime or $goles_h==null){
        $goles_h='null';
        $goles_a='null';
    }

    $query="UPDATE partido SET nombre_h='$equipo_h',nombre_a='$equipo_a', fecha_hora='$fecha', resultado_h=$goles_h,resultado_a=$goles_a,estadio='$estadio'  WHERE num_partido=$num;";
    $result = pg_query($dbconn,$query) or die(foo("No se pudo actualizar el partido $num: ",true,$dbconn));
    foo("Se actualizó exitosamente el partido $num",false,$dbconn);

    if($goles_h!='null'){
        $query = "SELECT nickname FROM apuesta WHERE num_partido=$num";
        $result = pg_query($dbconn,$query) or die(foo("No se pudo consultar $num: ",true,$dbconn));
        while($arr = pg_fetch_array($result, NULL, PGSQL_ASSOC)){
            calcular($arr['nickname'],$dbconn);
        }    
    }
    puntear($equipo_h,$dbconn);
    puntear($equipo_a,$dbconn);
    
    pg_close($dbconn);

    function foo($mensaje, $error, $link){
        echo "<div class='aviso'>";
        if($error){
            echo "hola soy un error";
        }else{
            echo "    <div class='texto'>  $mensaje </div>";
        }
        echo "    <a href='grupos.php' class='link'>REGRESAR A GRUPOS</a>";
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

    function puntear($equipo,$dbconn){
        $query="SELECT E.nombre
                FROM equipos as E, partido as P
                WHERE ((E.nombre=P.nombre_h
                        and ( (P.resultado_h<P.resultado_a)
                            or (P.penales_h< P.penales_a)))
                        or
                        (E.nombre=P.nombre_a
                        and ( (P.resultado_h>P.resultado_a)
                            or (P.penales_h>P.penales_a)))
                    )		
                    and E.nombre = '$equipo'
                    and P.etapa like 'G_'";
        $result = pg_query($dbconn,$query) or die(foo("No se pudo consultar: ",true,$dbconn));
        $filas = pg_num_rows($result);
        $perdidos = $filas;

        $query="SELECT E.nombre
                FROM equipos as E, partido as P
                WHERE ((E.nombre=P.nombre_h
                        and ( (P.resultado_h>P.resultado_a)
                            or (P.penales_h> P.penales_a)))
                        or
                        (E.nombre=P.nombre_a
                        and ( (P.resultado_h<P.resultado_a)
                            or (P.penales_h<P.penales_a)))
                    )		
                    and E.nombre = '$equipo'
                    and P.etapa like 'G_'";
        $result = pg_query($dbconn,$query) or die(foo("No se pudo consultar: ",true,$dbconn));
        $filas = pg_num_rows($result);
        $ganados = $filas;

        $query="SELECT *
                FROM equipos as E, partido as P
                WHERE (E.nombre=P.nombre_h or E.nombre=P.nombre_a)
                    and P.resultado_h=P.resultado_a
                    and E.nombre = '$equipo'
                    and P.etapa like 'G_'";
        $result = pg_query($dbconn,$query) or die(foo("No se pudo consultar: ",true,$dbconn));
        $filas = pg_num_rows($result);
        $empatados = $filas;

        $favor=0;
        $contra=0;

        $query="SELECT P.resultado_h, P.resultado_a
                FROM equipos as E, partido as P
                WHERE E.nombre=P.nombre_h
                    and E.nombre = '$equipo'
                    and P.etapa like 'G_'";
        $result = pg_query($dbconn,$query) or die(foo("No se pudo consultar: ",true,$dbconn));
        while( $arr = pg_fetch_array($result, NULL, PGSQL_ASSOC)){
            $favor= $favor + intval($arr['resultado_h']);
            $contra= $contra + intval($arr['resultado_a']);
        }
        $query="SELECT P.resultado_h, P.resultado_a
                FROM equipos as E, partido as P
                WHERE E.nombre=P.nombre_a
                    and E.nombre = '$equipo'
                    and P.etapa like 'G_'";
        $result = pg_query($dbconn,$query) or die(foo("No se pudo consultar: ",true,$dbconn));
        while( $arr = pg_fetch_array($result, NULL, PGSQL_ASSOC)){
            $favor= $favor + intval($arr['resultado_a']);
            $contra= $contra + intval($arr['resultado_h']);
        }

        $query="UPDATE equipos SET partidos_ganados=$ganados,partidos_perdidos=$perdidos,partidos_empatados=$empatados, goles_favor=$favor, goles_contra=$contra WHERE nombre='$equipo';";
        $result = pg_query($dbconn,$query) or die(foo("No se pudo consultar: ",true,$dbconn));
    }


    
?>