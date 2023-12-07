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
    <link rel="stylesheet" href="../../estilos/listado.css?v=<?php echo(rand()); ?>">
    <link href="https://ges2.galileo.edu/resources/theme-ges-forall/images/favicon.ico" rel="shortcut icon" type="image/x-icon">
    <title>Estadios</title>
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
            <li><a class="active" href="#">Estadios</a></li>
        </ul>
    </div>
    <div id="contenido"  class="listado listado--modif listado--estadio listado--modif--estadio">
        <div id="nombre_listado">
            <h1>ESTADIOS</h1>
        </div>
        <div id="agregar">
            <a href="editar.php">Agregar Estadio</a>
        </div>
        <table>
            <thead>
                <tr class="dato--titulos">
                    <th>NOMBRE</th>
                    <th>CIUDAD</th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php
                $query= "SELECT nombre,direccion FROM estadio";
                $result = pg_query($dbconn,$query);
                if(!$result){
                    echo 'ocurrio un error';
                    die();
                }else{
                    while( $arr = pg_fetch_array($result, NULL, PGSQL_ASSOC)){
                ?>
                    <tr>
                        <td><?=$arr['nombre']?></td>
                        <td><?=$arr['direccion']?></td>
                        <td><?="<a href='editar.php?id=$arr[nombre]'><img src='../../img/lapiz.png' ></a>"?></td>
                        <td><?="<a href=\"#\" onclick='preguntaEliminar(\"estadio\",\"$arr[nombre]\",\" $arr[nombre] \");'><img src='../../img/borrar.png' ></a>"?></td>
                    </tr>
                <?php
                        }  
                    }
                ?>
            </tbody>
        </table>
    </div>
    
</body>
</html>