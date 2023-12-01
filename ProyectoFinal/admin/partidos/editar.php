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
    <link rel="stylesheet" href="../../estilos/editar.css">
    <title>Editar Equipos</title>
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
            <li><a class="active" href="#">Editar</a></li>
        </ul>
    </div>
    <div id="contenido" class="modificar">
        <?php
            date_default_timezone_set('America/Guatemala');    
            $DateAndTime = date("Y-m-d H:i:00",time()); 
            
            if(isset($_GET["id"])){
                $id=$_GET["id"];
                $grupo=$_GET["grupo"];
                $habilitar="";
                $query= "SELECT num_partido,nombre_h,nombre_a,TO_CHAR(fecha_hora, 'YYYY-MM-DD') as fecha, TO_CHAR(fecha_hora, 'HH:MI:SS')as hora,resultado_h,resultado_a,etapa,estadio FROM partido WHERE num_partido=$id;";
                echo "<br>";
                $result = pg_query($dbconn,$query);
                $arr = pg_fetch_array($result, NULL, PGSQL_ASSOC);
                $num =      $arr['num_partido'];
                $nombre_h = $arr['nombre_h'];
                $nombre_a = $arr['nombre_a'];
                $YYMMDD =    $arr['fecha'];
                $HH = $arr['hora'];
                $fecha = $YYMMDD."T".$HH;
                $resultado_h=$arr['resultado_h'];
                $resultado_a=$arr['resultado_a'];
                $etapa =    $arr['etapa'];
                $estadio =  $arr['estadio'];
                $actualizar="F";

                if($resultado_h!=null and $resultado_a!=null){
                    $actualizar = "T";
                }
            }
        ?>

       <div id="titulo">
            <h2> EDITAR PARTIDO</h2>
       </div>
       <div id="datos">
            <form action="actualizar.php" method="post">
                <div class='partido'>
                    <input type="hidden" name='num_p' value='<?php echo $num ?>' >
                    <input type="hidden" name="grupo" value="<?php echo $grupo?> ">

                    <div class='llaves dato'>
                        <div class="num">Num. Partido: <?php echo $num ?></div>
                        <div class="grupo">Grupo: <?php echo $etapa ?></div>
                    </div>
                    <div class='fecha_lugar dato'>
                        <div class="fecha_hora">
                                <label for="fecha">Fecha y hora</label>
                                <input type="datetime-local" name="fecha" id="fecha" value="<?php echo $fecha?>">
                        </div>
                        <div class="lugar">
                                <label for="estadio">Estadio</label>
                                <select name="estadio" id="estadio">
                                    <?php
                                        $query="SELECT nombre FROM estadio";
                                        $result = pg_query($dbconn,$query);
                                        while ($arr = pg_fetch_array($result, NULL, PGSQL_ASSOC)) {
                                            if($estadio == $arr['nombre']){
                                                echo "<option value='$arr[nombre]' selected>$arr[nombre]</option>";
                                            }else{
                                                echo "<option value='$arr[nombre]'>$arr[nombre]</option>";
                                            }
                                        }
                                    ?>
                                </select>
                        </div>
                    </div>
                    <div class='equipos dato'>
                        <div class='dato--equipo dato--I'>
                            <div class='Equipo'>
                                <label for="equipo_h">Equipo 1</label>
                                <select name="equipo_h" id="equipo_h">
                                    <?php
                                        if(isset($grupo)){
                                            $query="SELECT nombre FROM equipos WHERE grupo='$grupo'";
                                        }else{
                                            $query="SELECT nombre FROM equipos";
                                        };
                                        
                                        $result = pg_query($dbconn,$query);
                                        while ($arr = pg_fetch_array($result, NULL, PGSQL_ASSOC)) {
                                            $equipo=$arr['nombre'];
                                            echo $equipo;
                                            if($nombre_h == $equipo){
                                                echo "<option value='$equipo' selected>$equipo</option>";
                                            }else{
                                                echo "<option value='$equipo'>$equipo</option>";
                                            }
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class='resultados'>
                            <label for="">goles</label>
                            <div class='goles punteo'>
                                <?php
                                    if(($fecha!=null) and ($DateAndTime > $fecha) ){
                                        echo "<div class='res'> <input type='number' name='goles_h' min='0' value='$resultado_h'></div>-<div class='res'><input type='number' name='goles_a' min='0' value='$resultado_a'></div>";
                                    }else{
                                        echo "<div class='res'> <input type='hidden' name='goles_h' value='$resultado_h' >$resultado_h</div>-<div class='res'><input type='hidden' name='goles_a' value='$resultado_a'> $resultado_a</div>";
                                    }
                                ?>
                            </div>
                        </div>
                        <div class='dato--equipo dato--D'>
                            <div class='Equipo'>
                                <select name="equipo_a" id="equipo_a">
                                    <?php
                                        if(isset($_GET["grupo"])){
                                            $grupo=$_GET["grupo"];
                                            $query="SELECT nombre FROM equipos WHERE grupo='$grupo'";
                                        }else{
                                            $query="SELECT nombre FROM equipos";
                                        };
                                        
                                        $result = pg_query($dbconn,$query);
                                        while ($arr = pg_fetch_array($result, NULL, PGSQL_ASSOC)) {
                                            $equipo=$arr['nombre'];
                                            echo $equipo;
                                            if($nombre_a == $equipo){
                                                echo "<option value='$equipo' selected>$equipo</option>";
                                            }else{
                                                echo "<option value='$equipo'>$equipo</option>";
                                            }
                                        }
                                    ?>
                                </select>
                                <label for="equipo_a">Equipo 2</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="enviar">
                    <button type="submit">Enviar</button>
                </div>
                
           </form>   
       </div>

    </div>
    
</body>
</html>