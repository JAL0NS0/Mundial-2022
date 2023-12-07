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
            <li><a class="active" href="estadios.php">Estadios</a></li>
            <li><a class="active" href="#">Editar</a></li>
        </ul>
    </div>
    <div id="contenido" class="modificar">
        <?php
            $estadio="";
            $direccion="";
            $pagina="agregar.php";
            $titulo="AGREGAR ESTADIO";
            if(isset($_GET["id"])){

                $query= "SELECT nombre,direccion FROM estadio WHERE nombre='$_GET[id]'";
                $result = pg_query($dbconn,$query);
                $arr = pg_fetch_array($result, NULL, PGSQL_ASSOC);
                $estadio=$arr["nombre"];
                $direccion=$arr["direccion"];
                $pagina="actualizar.php?$estadio";
                $titulo="ACTUALIZAR ESTADIO";

            }
        ?>

       <div id="titulo">
            <h2><?php echo $titulo?></h2>
       </div>
       <div id="datos">

            <form action="<?php echo $pagina?>" method="post">
                <div class="dato">
                    <label for="nombre">
                        Nombre
                        <?php
                            if(isset($_GET["id"])){
                                echo "<input type='text' value='$estadio' name='nombre' disabled>";
                            }else{
                                echo "<input type='text' value='$estadio' name='nombre'>";
                            }
                        ?>
                        
                    </label>
                </div>
                <div class="dato">
                    <label for="direccion">
                        Direccion
                        <input type="text" value="<?php echo $direccion?>" name="direccion">
                    </label>
                </div>
                <button type="submit">ENVIAR</button>
           </form>
           
       </div>

    </div>
    
</body>
</html>