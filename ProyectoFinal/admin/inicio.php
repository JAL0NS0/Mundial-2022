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
    <link rel="stylesheet" href="../estilos/encabezado.css">
    <link rel="stylesheet" href="../estilos/inicio.css">
    <script src="../js/borrar.js"></script>
    <title>Inicio</title>
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
    <div id="menu">
        <ul>
            <li><a class="active" href="#">Inicio</a></li>
        </ul>
    </div>
    <div id="contenido">
        <div id="division">
            <div id="ranking">
                <div id="titulo">
                    <h2>RANKING</h2>
                </div>
                <?php
                $query= "SELECT nickname,puntos FROM usuario ORDER BY puntos DESC";
                    $result = pg_query($dbconn,$query);
                    if(!$result){
                        echo 'ocurrio un error';
                        die();
                    }else{
                        while( $arr = pg_fetch_array($result, NULL, PGSQL_ASSOC)){
                            if($arr['nickname']!== 'Admin'){
                                echo "<div class='participante'>";
                                echo     "<div id='nombre'>". $arr["nickname"]."</div>"; 
                                echo     "<div id='punteo'>";
                                echo       $arr["puntos"];   
                                echo        "<progress id='file' max='384' value='".$arr['puntos']."'></progress>";
                                echo     "</div>";
                                echo     "<div id='edit_usuario'><a href='#' onclick='preguntaEliminar(\"usuario\",\"$arr[nickname]\",\"\");'>BORRAR</a></div>";
                                echo "</div>";
                            }
                        }
                        
                    }
                pg_close($dbconn);    
                ?>
            </div>
            <div id="paginas">
                <div id="datos">
                    
                    <div id="grupos" class="llevar equipos">
                        <div>
                            <h2>Equipos</h2>
                        </div>                        
                        <div>
                            <a href="equipos/equipos.php" class="botones">Equipos</a>
                        </div>
                    </div>

                    <div id="grupos" class="llevar grupos">
                        <div>
                            <h2>Estadios</h2>
                        </div>
                        <div>
                            <a href="estadios/estadios.php" class="botones">Estadios</a>
                        </div>
                    </div>
                </div>
                <div id="apuestas">                
                    <h2>PARTIDOS</h2>
                    <div id="grupos" class="llevar">
                        <a href="partidos/grupos.php" class="botones">GRUPOS</a>
                    </div>
                    <div id="octavos" class="llevar">
                        <a href="partidos/eliminatoria.php?etapa=OC" class="botones">OCTAVOS</a>
                    </div>
                    <div id="Cuartos" class="llevar">
                        <a href="partidos/eliminatoria.php?etapa=CU" class="botones">CUARTOS</a>
                    </div>
                    <div id="semifinales" class="llevar">
                        <a href="partidos/eliminatoria.php?etapa=SM" class="botones">SEMIFINALES</a>
                    </div>
                    <div id="finales" class="llevar">
                        <a href="partidos/eliminatoria.php?etapa=FN" class="botones">FINALES</a>
                    </div>
                </div>

            </div>
            
            
        </div>
    </div>
    
</body>
</html>