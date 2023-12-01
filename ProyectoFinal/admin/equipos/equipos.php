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
    <script src="../../js/borrar.js"></script>
    <link rel="stylesheet" href="../../estilos/encabezado.css">
    <link rel="stylesheet" href="../../estilos/listado.css">
    <title>Equipos</title>
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
            <a href="../../cerrar_session.php" class="botones">CERRAR SESION</a>
        </div>
    </div>
    <div id="menu">
        <ul>
            <li><a class="active" href="../inicio.php">Inicio</a></li>
            <li><a class="active" href="#">Equipos</a></li>
        </ul>
    </div>
    <div id="contenido"  class="listado listado--modif listado--modif--equipo listado--equipos">
        <div id="nombre_listado">
            <h1>EQUIPOS</h1>
        </div>
        <div id="agregar">
            <a href="editar.php">Agregar Equipo</a>
        </div>

        <?php           
            $grupos=array('A','B','C','D','E','F','G','H');
            foreach ($grupos as $key => $value) {
                echo "<div class='dato dato--titulos '>";
                echo     "<div id='nombre'>GRUPO $value</div>";
                echo     "<div class='punteo'>PJ</div>";
                echo     "<div class='punteo'>PG</div>";
                echo     "<div class='punteo'>PE</div>";
                echo     "<div class='punteo'>PP</div>";
                echo     "<div class='punteo'>GF</div>";
                echo     "<div class='punteo'>GC</div>";
                echo     "<div class='punteo'>+/-</div>";
                echo     "<div class='punteo'>PTS</div>";
                echo     "<div id='edicion'> EDICION </div>";
                echo     "<div id='edicion'> BORRAR </div>";
                echo "</div>";
                echo "<div id='lista'>";                
                $query= "SELECT nombre,bandera,partidos_ganados,partidos_perdidos,partidos_empatados,goles_favor,goles_contra FROM equipos WHERE grupo='$value'";
                $result = pg_query($dbconn,$query);
                if(!$result){
                    echo 'ocurrio un error';
                    die();
                }else{
                    while( $arr = pg_fetch_array($result, NULL, PGSQL_ASSOC)){
                        $nombre = $arr['nombre'];
                        $bandera = $arr['bandera'];
                        $pg = $arr['partidos_ganados'];
                        $pp = $arr['partidos_perdidos'];
                        $pe = $arr['partidos_empatados'];
                        $gf = $arr['goles_favor'];
                        $gc = $arr['goles_contra'];
                        $pj = $pg+$pp+$pe;
                        $diff= $gf-$gc;
                        $pts = 3*$pg + $pe;

                        echo    "<div class='dato dato--admin '>";
                        echo        "<div id='bandera'></div>";
                        echo        "<div id='nombre'>  $nombre </div>";
                        echo        "<div class='punteo'> $pj </div>";                        
                        echo        "<div class='punteo'> $pg  </div>";
                        echo        "<div class='punteo'> $pe  </div>";
                        echo        "<div class='punteo'> $pp  </div>";
                        echo        "<div class='punteo'> $gf  </div>";
                        echo        "<div class='punteo'> $gc  </div>";
                        echo        "<div class='punteo'> $diff </div>";
                        echo        "<div class='punteo'> $pts </div>";
                        echo        "<div id='edicion'>";
                        echo            "<a href='editar.php?id=$nombre'><img src='../../img/lapiz.png' ></a>";
                        echo        "</div>";
                        echo        "<div id='edicion'>";
                        echo            "<a href=\"#\" onclick='preguntaEliminar(\"equipo\",\"$nombre\",\"\");'><img src='../../img/borrar.png' ></a>";
                        echo        "</div>";
                        echo    "</div>";
                    }  
                }
                echo "</div>"; 

            }
            unset($valor);
            pg_close($dbconn);   
        ?>       

    </div>
    
</body>
</html>