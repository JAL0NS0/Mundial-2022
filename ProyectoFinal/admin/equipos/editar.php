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
    <link rel="stylesheet" href="../../estilos/listado.css">
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
            <li><a class="active" href="equipos.php">Equipos</a></li>
            <li><a class="active" href="#">Editar</a></li>
        </ul>
    </div>
    <div id="contenido" class="modificar">
        <?php
            $id="";
            $bandera="";
            $grupo="";
            $pagina="agregar.php";
            $titulo="AGREGAR EQUIPO";
            if(isset($_GET["id"])){
                $query= "SELECT nombre,bandera,grupo FROM equipos WHERE nombre='$_GET[id]'";
                $result = pg_query($dbconn,$query);
                $arr = pg_fetch_array($result, NULL, PGSQL_ASSOC);
                $id=$arr["nombre"];
                $bandera=$arr["bandera"];
                $grupo=$arr["grupo"];
                $pagina="actualizar.php";
                $titulo="ACTUALIZAR EQUIPO";
            }

            pg_close($dbconn);
        ?>

       <div id="titulo">
            <h2><?php echo $titulo?></h2>
       </div>
       <div id="datos">
            <form action="<?php echo $pagina?>" method="post">
                <div class="dato">
                    <label for="nombre">Nombre
                    <?php if(isset($_GET["id"])){
                        echo "<input type='hidden' value='$id' name='nombre'>";
                        echo $id;
                    }else{
                        echo "<input type='text' value='' name='nombre'>";
                    }
                    ?>
                    </label>
                </div>
                <div class="dato">
                    <label for="bandera">Bandera
                        <input type="text" value="<?php echo $bandera?>" name="bandera">
                    </label>
                </div>
                <div class="dato">
                    <label for="grupo">Grupo
                        <select name="grupo" >
                        <?php
                            $grupos=array('A','B','C','D','E','F','G','H');
                            foreach ($grupos as $key => $value) {
                                echo " <option value='$value'>$value</option>";
                            }
                        ?>
                        </select>
                    </label>
                </div>
                <button type="submit">ENVIAR</button>
           </form>
           
       </div>

    </div>
    
</body>
</html>