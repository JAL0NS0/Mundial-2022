<?php
    session_start();
    $nombre = $_SESSION['nombre'];

    if($nombre === null || $nombre = ''){
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
    <link rel="stylesheet" href="estilos/encabezado.css">
    <link rel="stylesheet" href="estilos/listado.css">
    <title>Equipos</title>
</head>
<body>
    <div id="encabezado">  
        <div id="logo">
            <img src="img/logo.png" alt="logo" alt>
        </div>
        <div id="inf_usuario">
            <div id="usuario">
                <?php
                    include('db.php');
                    $nombre = $_SESSION['nombre'];
                    echo "$_SESSION[nombre]";
                ?>
            </div>
        </div>
        <div id="cerrar">
            <a href="cerrar_session.php" class="botones">CERRAR SESION</a>
        </div>
    </div>
    <div id="menu">
        <ul>
            <li><a class="active" href="inicio.php">Inicio</a></li>
            <li><a class="active" href="#">Equipos</a></li>
        </ul>
    </div>
    <div id="contenido"  class="listado listado--equipos">
        <div id="nombre_listado">
            <h1>EQUIPOS</h1>
        </div>
        <?php           
            $grupos=array('A','B','C','D','E','F','G','H');
            foreach ($grupos as $key => $value) {
                echo "<div class='dato dato--titulos'>";
                echo     "<div id='nombre'>GRUPO $value</div>";
                echo     "<div class='punteo'>PJ</div>";
                echo     "<div class='punteo'>PG</div>";
                echo     "<div class='punteo'>PE</div>";
                echo     "<div class='punteo'>PP</div>";
                echo     "<div class='punteo'>GF</div>";
                echo     "<div class='punteo'>GC</div>";
                echo     "<div class='punteo'>+/-</div>";
                echo     "<div class='punteo'>PTS</div>";
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
                        $pg = intval( $arr['partidos_ganados']);
                        $pp = intval( $arr['partidos_perdidos']);
                        $pe = intval($arr['partidos_empatados']);
                        $gf = intval($arr['goles_favor']);
                        $gc = intval($arr['goles_contra']);
                        $pj = $pg+$pp+$pe;
                        $diff= $gf-$gc;
                        $pts = 3*$pg + $pe;

                        echo    "<div class='dato '>";
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
                        echo    "</div>";
                    }  
                }

            }
            unset($valor);
               
        ?>       

    </div>
    
</body>
</html>