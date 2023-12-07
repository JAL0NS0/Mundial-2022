<?php
    session_start();
    $nombre = $_SESSION['nombre'];
    $is_admin = FALSE;

    if($nombre === null || $nombre == ''){
        echo "Usted no tiene autorización para entrar a esta pagina, inicie sesión";
        die();
    }
    if($nombre == 'Admin'){
        $is_admin = TRUE;
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="./js/borrar.js"></script>
    <link rel="stylesheet" href="./estilos/encabezado.css">
    <link rel="stylesheet" href="./estilos/listado.css?v=<?php echo(rand()); ?>">
    <link href="https://ges2.galileo.edu/resources/theme-ges-forall/images/favicon.ico" rel="shortcut icon" type="image/x-icon">
    <title>Equipos</title>
</head>
<body>
    <div id="encabezado">  
        <div id="logo">
            <img src="./img/logo.png" alt="logo" alt>
        </div>
        <div id="inf_usuario">
            <div id="usuario">
                <?php
                    include('./db.php');
                    $nombre = $_SESSION['nombre'];
                    echo "$_SESSION[nombre]";
                ?>
            </div>
        </div>
        <div id="cerrar">
            <a href="./cerrar_session.php" class="botones">CERRAR SESION</a>
        </div>
    </div>
    <div id="menu">
        <ul>
            <li><a class="active" href="./inicio.php">Inicio</a></li>
            <li><a class="active" href="#">Equipos</a></li>
        </ul>
    </div>



    <div id="contenido"  class="listado listado--modif listado--modif--equipo listado--equipos">
        <div id="nombre_listado">
            <h1>EQUIPOS</h1>
        </div>
        <?php
        if($is_admin){
            ?>
            <div id="agregar">
                <a href="editar.php">Agregar Equipo</a>
            </div>
            <?php
        }
        ?>

        <?php 
        $grupos=array('A','B','C','D','E','F','G','H');
        foreach ($grupos as $key => $nombre_grupo) {
        ?>
        <table>
            <thead>
                <tr class="dato--titulos">
                    <th class="nombre_grupo">GRUPO <?= $nombre_grupo ?></th>
                    <th class="punteo" >PJ</th>
                    <th class="punteo" >PG</th>
                    <th class="punteo" >PE</th>
                    <th class="punteo" >PP</th>
                    <th class="punteo" >GF</th>
                    <th class="punteo" >GC</th>
                    <th class="punteo" >+/-</th>
                    <th class="punteo" >PTS</th>
                    <?php
                    if($is_admin){
                        ?>
                        <th class="punteo" ></th>
                        <th class="punteo" ></th>
                        <?php
                    }
                    ?>
                </tr>
            </thead>

            <?php
            $query= "SELECT nombre,bandera,partidos_ganados,partidos_perdidos,partidos_empatados,goles_favor,goles_contra FROM equipos WHERE grupo='$nombre_grupo'";
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
                    ?>
                    <tr class="">
                        <td class="nombre_grupo"><?= $nombre ?></td>
                        <td class="punteo"><?= $pj ?></td>
                        <td class="punteo"><?= $pg ?></td>
                        <td class="punteo"><?= $pe ?></td>
                        <td class="punteo"><?= $pp ?></td>
                        <td class="punteo"><?= $gf ?></td>
                        <td class="punteo"><?= $gc ?></td>
                        <td class="punteo"><?= $diff ?></td>
                        <td class="punteo"><?= $pts ?></td>
                        <?php
                        if($is_admin){
                            ?>
                            <td class="punteo"><a href='editar.php?id=<?=$nombre?>'><img src='./img/lapiz.png' ></a></td>
                            <td class="punteo"><a href=\"#\" onclick='preguntaEliminar(\"equipo\",\"<?=$nombre?>\",\"\");'><img src='./img/borrar.png' ></a></td>
                            <?php
                        }
                    
                    echo "</tr>";
                    
                }  
            }
            ?>
        </table>
        <?php
        }
        unset($valor);
        pg_close($dbconn);
        ?>       
    </div>
</body>
</html>