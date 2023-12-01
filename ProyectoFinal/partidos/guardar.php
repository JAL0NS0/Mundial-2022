<?php
    session_start();
    $nombre = $_SESSION['nombre'];

    if($nombre === null || $nombre == ''){
        echo "Usted no tiene autorización para entrar a esta pagina, inicie sesión";
        die();
    }

    include("../db.php");
    $a=1;
    $valores = array();
    while ($a <= 48) {
        $preh = $_POST['prediccion'.$a.'_h'];
        $prea = $_POST['prediccion'.$a.'_a'];

        if($preh!=null and $prea!=null){
            $query="SELECT nickname,num_partido,prediccion_h,prediccion_a FROM apuesta WHERE nickname='$nombre' and num_partido=$a";
            $result = pg_query($dbconn,$query);
            if(!$result){
                echo 'ocurrio un error';
                die();
            }else{
                $filas = pg_num_rows($result);
                if($filas == 0){
                    $query2 = "INSERT INTO apuesta(nickname,num_partido,prediccion_h,prediccion_a) VALUES ('$nombre',$a,$preh,$prea)";
                    $result = pg_query($dbconn,$query2);
                    if(!$result){
                        echo 'ocurrio un error';
                        die();
                    }
                }else{
                    $query2 = "UPDATE apuesta SET prediccion_h=$preh, prediccion_a=$prea WHERE nickname='$nombre' and num_partido='$a'";
                    $result = pg_query($dbconn,$query2);
                    if(!$result){
                        echo 'ocurrio un error';
                        die();
                    }
                };
            }
        }else if(($preh!=null and $prea==null) or ($preh==null and $prea!=null)){
            $valores[] = $a;
        }  
        $a=$a+1;
    }

    echo "Predicciones guardadas exitosamente";

    if( count($valores) > 0){
        echo "Los partidos ".json_encode($valores)." no fueron guardados ya que no se ingresó la predicción para alguno de los dos equipos";
    }

    echo "    <a href='grupos.php' class='link'>REGRESAR A GRUPOS</a>";

    pg_close($dbconn);
?>