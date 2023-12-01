<?php
    session_start();
    $nombre = $_SESSION['nombre'];

    if($nombre === null || $nombre = '' || $nombre !== 'Admin'){
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
    <link rel="stylesheet" href="../../estilos/encabezado.css">
    <link rel="stylesheet" href="../../estilos/partidos.css">
    <title>Octavos Admin</title>
</head>
<body>
    <div id="encabezado">  
        <div id="logo">
            <img src="../../img/logo.png" alt="logo" alt>
        </div>
        <div id="inf_usuario">
            <div id="usuario">
                <?php
                    include('../../db.php');
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
        <?php

            echo "<div class='grupo'>";
            echo "    <div class='titulo--grupos'> $titulo </div>";

            $query= "SELECT num_partido,nombre_h,nombre_a,fecha_hora,resultado_h,resultado_a,penales_h,penales_a,etapa,estadio FROM partido WHERE etapa='$eliminatoria' ORDER BY num_partido;";
            $result = pg_query($dbconn,$query);
            if(!$result){
                echo 'ocurrio un error';
                die();
            }else{
                while ($arr = pg_fetch_array($result, NULL, PGSQL_ASSOC)) {
                    $num =      $arr['num_partido'];
                    $nombre_h = $arr['nombre_h'];
                    $nombre_a = $arr['nombre_a'];
                    $fecha =    $arr['fecha_hora'];
                    $resultado_h=$arr['resultado_h'];
                    $resultado_a=$arr['resultado_a'];
                    $penales_h=$arr['penales_h'];
                    $penales_a=$arr['penales_a'];
                    $etapa =    $arr['etapa'];
                    $estadio = $arr['estadio'];

                    $query2="SELECT bandera FROM equipos WHERE nombre='$nombre_h'";
                        $result2 = pg_query($dbconn,$query2);
                        $dato = pg_fetch_array($result2, NULL, PGSQL_ASSOC);
                        $bandera_h = $dato['bandera'];

                        $query2="SELECT bandera FROM equipos WHERE nombre='$nombre_a'";
                        $result2 = pg_query($dbconn,$query2);
                        $dato = pg_fetch_array($result2, NULL, PGSQL_ASSOC);
                        $bandera_a = $dato['bandera'];

                    echo "    <div class='partido'>";
                    echo "        <div class='detalles'>";
                    echo "            <div class='fecha'>$fecha</div>";
                    echo "            <div class='dato'>$estadio</div>";
                    echo "            <div class='dato'>Partido $num</div>";
                    echo "        </div>";
                    echo "        <div class='dato--equipo dato--I'>";
                    echo "            <div class='bandera'><img src='../../img/Equipos/$bandera_h.png' alt=''></div>";
                    echo "            <div class='Equipo'>$nombre_h</div>";
                    echo "        </div>";
                    echo "        <div class='resultados'>";
                    echo "            <div class='goles punteo'>";
                    echo "                <div class='res'> $resultado_h</div>-<div class='res'>$resultado_a</div>";
                    echo "            </div>";
                    echo "            <div class='penales punteo'>";
                    echo "                <div class='res'> $penales_h </div>-<div class='res'>$penales_a</div>";
                    echo "            </div>";
                    echo "        </div>";
                    echo "        <div class='dato--equipo dato--D'>";
                    echo "            <div class='bandera'><img src='../../img/Equipos/$bandera_a.png' alt=''></div>";
                    echo "            <div class='Equipo'>$nombre_a</div>";
                    echo "        </div>";
                    echo "        <div class='editar'>";
                    echo "            <a href='editarEliminatoria.php?id=$num'>Editar</a>";
                    echo "        </div>";
                    echo "    </div>";     
                }
            echo "</div>";
            } 
            pg_close($dbconn);
        ?>
    </div>
</body>
</html>