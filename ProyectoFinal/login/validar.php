<?php
    session_start();
    $nombre = $_POST["user"];
    $pass = $_POST["password"];

    include('../db.php');
    $query= "SELECT nombre,puntos,contrasena,nickname FROM usuario WHERE nickname='$nombre' AND contrasena='$pass'";

    $result = pg_query($dbconn,$query);

    $filas = pg_num_rows($result);

    if($filas > 0){
        $_SESSION["nombre"]= $nombre;

        if($nombre == 'Admin'){
            header("Location: ../admin/inicio.php");
        }else{
            header("Location:../inicio.php");
        }
    }else{
        header("Location: ../?reg=false");
    }
    
?>