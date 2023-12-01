<?php
    session_start();
    $nombre = $_SESSION['nombre'];

    if($nombre === null || $nombre = '' ){
        echo "Usted no tiene autorización para entrar a esta pagina, inicie sesión";
        die();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../estilos/encabezado.css">
    <link rel="stylesheet" href="../estilos/partidos.css">
    <title>Eliminatoria</title>
</head>
<body>
    <div id="encabezado">  
        <div id="logo">
            <img src="../img/logo.png" alt="logo" alt>
        </div>
        <div id="inf_usuario">
            <div id="usuario">
                <?php
                    include('../db.php');
                    $nombre = $_SESSION['nombre'];
                    echo "$_SESSION[nombre]";
                ?>
            </div>
        </div>
        <div id="cerrar">
            <a href="../cerrar_session.php" class="botones">CERRAR SESION</a>
        </div>
    </div>
    <?php
        $eliminatoria= $_GET["etapa"];
        $titulo="";
        switch ($eliminatoria) {
            case 'OC':
                $titulo="OCTAVOS";
                break;
            case 'CU':
                $titulo="CUARTOS";
                break;
            case 'SM':
                $titulo="SEMI FINAL";
                break;
            case 'FN':
                $titulo="FINAL";
                break;
        
            default:
                $titulo="ERROR";
                break;
        }
    ?>
    <div id="menu">
        <ul>
            <li><a class="active" href="../inicio.php">Inicio</a></li>
            <li><a class="active" href="#"><?php echo $titulo ?></a></li>
        </ul>
    </div>

    <div id="contenido">
        <div id="nombre_listado">
            <h1><?php echo $titulo ?></h1>
        </div>
        <form action="guardarEliminatoria.php" method="post">
            <input type="hidden" name="etapa" value="<?php echo $eliminatoria?>" >
        <?php
            date_default_timezone_set('America/Guatemala');    
            $DateAndTime = date("Y-m-d H:i:00",time()); 
            echo "<div class='grupo'>";
            echo "    <div class='titulo--grupos'> $titulo </div>";

            $query= "SELECT num_partido,nombre_h,nombre_a,TO_CHAR(fecha_hora, 'YYYY-MM-DD') as fecha, TO_CHAR(fecha_hora, 'HH:MI:SS')as hora,resultado_h,resultado_a,penales_h,penales_a,etapa,estadio FROM partido WHERE etapa='$eliminatoria' ORDER BY num_partido;";
                $result = pg_query($dbconn,$query);
                if(!$result){
                    echo 'ocurrio un error';
                die();
            }else{
                while ($arr = pg_fetch_array($result, NULL, PGSQL_ASSOC)) {
                        $num =      $arr['num_partido'];
                        $nombre_h = $arr['nombre_h'];
                        $nombre_a = $arr['nombre_a'];
                        $YYMMDD =    $arr['fecha'];
                        $HH = $arr['hora'];
                        $fecha = $YYMMDD."T".$HH;
                        $resultado_h=$arr['resultado_h'];
                        $resultado_a=$arr['resultado_a'];
                        $penales_h=$arr['penales_h'];
                        $penales_a=$arr['penales_a'];
                        $etapa =    $arr['etapa'];
                        $estadio = $arr['estadio'];

                        $query2="SELECT prediccion_h,prediccion_a FROM apuesta WHERE nickname='$nombre' and num_partido=$num";
                        $result2 = pg_query($dbconn,$query2);
                        if(!$result2){
                            echo 'ocurrio un error';
                            die();
                        }else{
                            $filas = pg_num_rows($result2);
                            if($filas == 0){
                                $apuesta_h="";
                                $apuesta_a="";
                            }else{
                                $datos = pg_fetch_array($result2, NULL, PGSQL_ASSOC);
                                $apuesta_h=$datos['prediccion_h'];
                                $apuesta_a=$datos['prediccion_a'];
                            };
                        }

                        $query3="SELECT bandera FROM equipos WHERE nombre='$nombre_h'";
                        $result3 = pg_query($dbconn,$query3);
                        $dato = pg_fetch_array($result3, NULL, PGSQL_ASSOC);
                        $bandera_h = $dato['bandera'];

                        $query3="SELECT bandera FROM equipos WHERE nombre='$nombre_a'";
                        $result2 = pg_query($dbconn,$query3);
                        $dato = pg_fetch_array($result3, NULL, PGSQL_ASSOC);
                        $bandera_a = $dato['bandera'];

                    echo "    <div class='partido'>
                                <div class='detalles'>
                                    <div class='fecha'>$fecha</div>
                                    <div class='dato'>$estadio</div>
                                    <div class='dato'>Partido $num</div>
                                </div>
                                <div class='dato--equipo dato--I'>
                                    <div class='bandera'><img src='../img/Equipos/$bandera_h.png' alt=''></div>
                                    <div class='Equipo'>$nombre_h</div>
                                </div>
                                <div class='resultados resultados--user'>
                                    <div class='goles punteo'>
                                            <div class='nombre_punteo'>Resultados</div>
                                            <div class='real valor'>";
                                        if($penales_h!=null){
                                            echo "<div class='res'> $resultado_h ($penales_h)</div>-<div class='res'>$resultado_a ($penales_a)</div>";
                                        }else{
                                            echo "<div class='res'> $resultado_h </div>-<div class='res'>$resultado_a </div>";
                                        }
                    echo "                  </div>
                                            <div class='nombre_punteo'>Predicción</div>
                                        <div class='prediccion valor'>";
                                        if(($fecha!=null) and ($DateAndTime < $fecha) ){
                                            echo "<div class='res'> <input type='number' name='prediccion".$num."_h' min='0' value='$apuesta_h' ></div>-<div class='res'><input type='number' name='prediccion".$num."_a' min='0' value='$apuesta_a'></div>";
                                        }else{
                                            echo "<div class='res'> <input type='hidden' name='prediccion".$num."_h' value='$apuesta_h' >$apuesta_h</div>-<div class='res'><input type='hidden' name='prediccion".$num."_a' value='$apuesta_a'> $apuesta_a</div>";
                                        }
echo "
                                        </div>
                                    </div>

                                </div>
                                <div class='dato--equipo dato--D'>
                                    <div class='bandera'><img src='../img/Equipos/$bandera_a.png' alt=''></div>
                                    <div class='Equipo'>$nombre_a</div>
                                </div>
                            </div>";     
                }
            echo "</div>";
            } 
            pg_close($dbconn);
        ?>
        <button type="submit">Enviar</button>
        </form>
    </div>
</body>
</html>