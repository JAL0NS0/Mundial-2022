<?php

    $host= "localhost";
    $dbname="QM2022";
    $user="postgres";
    $password="1234";

    $conn_string = "host=$host dbname=$dbname user=$user password=$password";
    
    $dbconn = pg_connect($conn_string);
    
    // Revisamos el estado de la conexion en caso de errores.
    if(!$dbconn) {
        echo "Error: No se ha podido conectar a la base de datos\n";
        die();
    }
?>