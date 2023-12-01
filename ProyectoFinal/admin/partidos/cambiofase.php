<?php
    session_start();
    $nombre = $_SESSION['nombre'];

    if($nombre === null || $nombre = '' || $nombre !== 'Admin'){
        echo "Usted no tiene autorización para entrar a esta pagina, inicie sesión";
        die();
    }

    $query="SELECT num_partido
    FROM partido
    WHERE resultado_h is null or resultado_a is null";
    $result = pg_query($dbconn,$query) or die(foo("No se pudo consultar: ",true,$dbconn));
    $filas = pg_num_rows($result);

    if($filas == 0){
        echo "<div class='texto'> Error: Todavía no se han ingresado los resultados de todos lo partidos de grupo</div>";
        echo "    <a href='nuevo_usuario.php' class='link'>REGRESAR A FORMULARIO</a>";
        die();
    } 


    include('../../db.php');

    $query="SELECT num_partido
    FROM partido
    WHERE resultado_h is null or resultado_a is null";
    $result = pg_query($dbconn,$query) or die(foo("No se pudo consultar: ",true,$dbconn));
    $filas = pg_num_rows($result);


    $marca=0;
    $primer="";
    $segundo="";

    /*----------------------------GRUPO A--------------------------------------*/


    $query= "SELECT nombre,partidos_ganados,partidos_perdidos,partidos_empatados,goles_favor,goles_contra FROM equipos WHERE grupo='A'";
    $result = pg_query($dbconn,$query);
    if(!$result){
        echo 'ocurrio un error';
        die();
    }else{
        while( $arr = pg_fetch_array($result, NULL, PGSQL_ASSOC)){
            $nombre = $arr['nombre'];
            $pg = $arr['partidos_ganados'];
            $pp = $arr['partidos_perdidos'];
            $pe = $arr['partidos_empatados'];
            $gf = $arr['goles_favor'];
            $gc = $arr['goles_contra'];
            $pj = $pg+$pp+$pe;
            $diff= $gf-$gc;
            $pts = 3*$pg + $pe;

            if($pts > $marca){
                $segundo=$primer;
                $primer=$nombre;
            }
        }
    }


    $query="UPDATE partido SET nombre_h='$primer'  WHERE num_partido=49;";
    $result = pg_query($dbconn,$query) or die(foo("No se pudo actualizar el partido 49: ",true,$dbconn));
    foo("Se actualizó exitosamente el partido 49",false,$dbconn);

    $query="UPDATE partido SET nombre_a='$segundo'  WHERE num_partido=51;";
    $result = pg_query($dbconn,$query) or die(foo("No se pudo actualizar el partido 51: ",true,$dbconn));
    foo("Se actualizó exitosamente el partido 51",false,$dbconn);

    /*----------------------------GRUPO B--------------------------------------*/
    $marca=0;
    $primer="";
    $segundo="";

    $query= "SELECT nombre,partidos_ganados,partidos_perdidos,partidos_empatados,goles_favor,goles_contra FROM equipos WHERE grupo='B'";
    $result = pg_query($dbconn,$query);
    if(!$result){
        echo 'ocurrio un error';
        die();
    }else{
        while( $arr = pg_fetch_array($result, NULL, PGSQL_ASSOC)){
            $nombre = $arr['nombre'];
            $pg = $arr['partidos_ganados'];
            $pp = $arr['partidos_perdidos'];
            $pe = $arr['partidos_empatados'];
            $gf = $arr['goles_favor'];
            $gc = $arr['goles_contra'];
            $pj = $pg+$pp+$pe;
            $diff= $gf-$gc;
            $pts = 3*$pg + $pe;

            if($pts > $marca){
                $segundo=$primer;
                $primer=$nombre;
            }
        }
    }


    $query="UPDATE partido SET nombre_h='$primer'  WHERE num_partido=51;";
    $result = pg_query($dbconn,$query) or die(foo("No se pudo actualizar el partido 51: ",true,$dbconn));
    foo("Se actualizó exitosamente el partido 51",false,$dbconn);

    $query="UPDATE partido SET nombre_a='$segundo'  WHERE num_partido=49;";
    $result = pg_query($dbconn,$query) or die(foo("No se pudo actualizar el partido 49: ",true,$dbconn));
    foo("Se actualizó exitosamente el partido 49",false,$dbconn);


    /*----------------------------GRUPO C--------------------------------------*/
    $marca=0;
    $primer="";
    $segundo="";

     $query= "SELECT nombre,partidos_ganados,partidos_perdidos,partidos_empatados,goles_favor,goles_contra FROM equipos WHERE grupo='C'";
     $result = pg_query($dbconn,$query);
     if(!$result){
         echo 'ocurrio un error';
         die();
     }else{
         while( $arr = pg_fetch_array($result, NULL, PGSQL_ASSOC)){
             $nombre = $arr['nombre'];
             $pg = $arr['partidos_ganados'];
             $pp = $arr['partidos_perdidos'];
             $pe = $arr['partidos_empatados'];
             $gf = $arr['goles_favor'];
             $gc = $arr['goles_contra'];
             $pj = $pg+$pp+$pe;
             $diff= $gf-$gc;
             $pts = 3*$pg + $pe;
 
             if($pts > $marca){
                 $segundo=$primer;
                 $primer=$nombre;
             }
         }
     }
 
     $query="UPDATE partido SET nombre_h='$primer'  WHERE num_partido=50;";
     $result = pg_query($dbconn,$query) or die(foo("No se pudo actualizar el partido 50: ",true,$dbconn));
     foo("Se actualizó exitosamente el partido 50",false,$dbconn);
 
     $query="UPDATE partido SET nombre_a='$segundo'  WHERE num_partido=52;";
     $result = pg_query($dbconn,$query) or die(foo("No se pudo actualizar el partido 52: ",true,$dbconn));
     foo("Se actualizó exitosamente el partido 52",false,$dbconn);


      /*----------------------------GRUPO D--------------------------------------*/
      $marca=0;
    $primer="";
    $segundo="";

      $query= "SELECT nombre,partidos_ganados,partidos_perdidos,partidos_empatados,goles_favor,goles_contra FROM equipos WHERE grupo='D'";
      $result = pg_query($dbconn,$query);
      if(!$result){
          echo 'ocurrio un error';
          die();
      }else{
          while( $arr = pg_fetch_array($result, NULL, PGSQL_ASSOC)){
              $nombre = $arr['nombre'];
              $pg = $arr['partidos_ganados'];
              $pp = $arr['partidos_perdidos'];
              $pe = $arr['partidos_empatados'];
              $gf = $arr['goles_favor'];
              $gc = $arr['goles_contra'];
              $pj = $pg+$pp+$pe;
              $diff= $gf-$gc;
              $pts = 3*$pg + $pe;
  
              if($pts > $marca){
                  $segundo=$primer;
                  $primer=$nombre;
              }
          }
      }
  
      $query="UPDATE partido SET nombre_h='$primer'  WHERE num_partido=52;";
      $result = pg_query($dbconn,$query) or die(foo("No se pudo actualizar el partido 52: ",true,$dbconn));
      foo("Se actualizó exitosamente el partido 52",false,$dbconn);
  
      $query="UPDATE partido SET nombre_a='$segundo'  WHERE num_partido=50;";
      $result = pg_query($dbconn,$query) or die(foo("No se pudo actualizar el partido 50: ",true,$dbconn));
      foo("Se actualizó exitosamente el partido 50",false,$dbconn);

       /*----------------------------GRUPO E--------------------------------------*/
       $marca=0;
    $primer="";
    $segundo="";

     $query= "SELECT nombre,partidos_ganados,partidos_perdidos,partidos_empatados,goles_favor,goles_contra FROM equipos WHERE grupo='E'";
     $result = pg_query($dbconn,$query);
     if(!$result){
         echo 'ocurrio un error';
         die();
     }else{
         while( $arr = pg_fetch_array($result, NULL, PGSQL_ASSOC)){
             $nombre = $arr['nombre'];
             $pg = $arr['partidos_ganados'];
             $pp = $arr['partidos_perdidos'];
             $pe = $arr['partidos_empatados'];
             $gf = $arr['goles_favor'];
             $gc = $arr['goles_contra'];
             $pj = $pg+$pp+$pe;
             $diff= $gf-$gc;
             $pts = 3*$pg + $pe;
 
             if($pts > $marca){
                 $segundo=$primer;
                 $primer=$nombre;
             }
         }
     }
 
     $query="UPDATE partido SET nombre_h='$primer'  WHERE num_partido=53;";
     $result = pg_query($dbconn,$query) or die(foo("No se pudo actualizar el partido 53: ",true,$dbconn));
     foo("Se actualizó exitosamente el partido 53",false,$dbconn);
 
     $query="UPDATE partido SET nombre_a='$segundo'  WHERE num_partido=55;";
     $result = pg_query($dbconn,$query) or die(foo("No se pudo actualizar el partido 55: ",true,$dbconn));
     foo("Se actualizó exitosamente el partido 55",false,$dbconn);


      /*----------------------------GRUPO F--------------------------------------*/
      $marca=0;
    $primer="";
    $segundo="";

      $query= "SELECT nombre,partidos_ganados,partidos_perdidos,partidos_empatados,goles_favor,goles_contra FROM equipos WHERE grupo='F'";
      $result = pg_query($dbconn,$query);
      if(!$result){
          echo 'ocurrio un error';
          die();
      }else{
          while( $arr = pg_fetch_array($result, NULL, PGSQL_ASSOC)){
              $nombre = $arr['nombre'];
              $pg = $arr['partidos_ganados'];
              $pp = $arr['partidos_perdidos'];
              $pe = $arr['partidos_empatados'];
              $gf = $arr['goles_favor'];
              $gc = $arr['goles_contra'];
              $pj = $pg+$pp+$pe;
              $diff= $gf-$gc;
              $pts = 3*$pg + $pe;
  
              if($pts > $marca){
                  $segundo=$primer;
                  $primer=$nombre;
              }
          }
      }
  
      $query="UPDATE partido SET nombre_h='$primer'  WHERE num_partido=55;";
      $result = pg_query($dbconn,$query) or die(foo("No se pudo actualizar el partido 55: ",true,$dbconn));
      foo("Se actualizó exitosamente el partido 55",false,$dbconn);
  
      $query="UPDATE partido SET nombre_a='$segundo'  WHERE num_partido=53;";
      $result = pg_query($dbconn,$query) or die(foo("No se pudo actualizar el partido 53: ",true,$dbconn));
      foo("Se actualizó exitosamente el partido 53",false,$dbconn);
      
      /*----------------------------GRUPO G--------------------------------------*/
      $marca=0;
    $primer="";
    $segundo="";

      $query= "SELECT nombre,partidos_ganados,partidos_perdidos,partidos_empatados,goles_favor,goles_contra FROM equipos WHERE grupo='G'";
      $result = pg_query($dbconn,$query);
      if(!$result){
          echo 'ocurrio un error';
          die();
      }else{
          while( $arr = pg_fetch_array($result, NULL, PGSQL_ASSOC)){
              $nombre = $arr['nombre'];
              $pg = $arr['partidos_ganados'];
              $pp = $arr['partidos_perdidos'];
              $pe = $arr['partidos_empatados'];
              $gf = $arr['goles_favor'];
              $gc = $arr['goles_contra'];
              $pj = $pg+$pp+$pe;
              $diff= $gf-$gc;
              $pts = 3*$pg + $pe;
  
              if($pts > $marca){
                  $segundo=$primer;
                  $primer=$nombre;
              }
          }
      }
  
      $query="UPDATE partido SET nombre_h='$primer'  WHERE num_partido=54;";
      $result = pg_query($dbconn,$query) or die(foo("No se pudo actualizar el partido 54: ",true,$dbconn));
      foo("Se actualizó exitosamente el partido 54",false,$dbconn);
  
      $query="UPDATE partido SET nombre_a='$segundo'  WHERE num_partido=56;";
      $result = pg_query($dbconn,$query) or die(foo("No se pudo actualizar el partido 56: ",true,$dbconn));
      foo("Se actualizó exitosamente el partido 56",false,$dbconn);


       /*----------------------------GRUPO H--------------------------------------*/
       $marca=0;
    $primer="";
    $segundo="";

       $query= "SELECT nombre,partidos_ganados,partidos_perdidos,partidos_empatados,goles_favor,goles_contra FROM equipos WHERE grupo='H'";
       $result = pg_query($dbconn,$query);
       if(!$result){
           echo 'ocurrio un error';
           die();
       }else{
           while( $arr = pg_fetch_array($result, NULL, PGSQL_ASSOC)){
               $nombre = $arr['nombre'];
               $pg = $arr['partidos_ganados'];
               $pp = $arr['partidos_perdidos'];
               $pe = $arr['partidos_empatados'];
               $gf = $arr['goles_favor'];
               $gc = $arr['goles_contra'];
               $pj = $pg+$pp+$pe;
               $diff= $gf-$gc;
               $pts = 3*$pg + $pe;
   
               if($pts > $marca){
                   $segundo=$primer;
                   $primer=$nombre;
               }
           }
       }
   
       $query="UPDATE partido SET nombre_h='$primer'  WHERE num_partido=56;";
       $result = pg_query($dbconn,$query) or die(foo("No se pudo actualizar el partido 56: ",true,$dbconn));
       foo("Se actualizó exitosamente el partido 56",false,$dbconn);
   
       $query="UPDATE partido SET nombre_a='$segundo'  WHERE num_partido=54;";
       $result = pg_query($dbconn,$query) or die(foo("No se pudo actualizar el partido 54: ",true,$dbconn));
       foo("Se actualizó exitosamente el partido 54",false,$dbconn);



    function foo($mensaje, $error, $link){
        echo "<div class='aviso'>";
        if($error){
            echo "hola soy un error";
        }else{
            echo "    <div class='texto'>  $mensaje </div>";
        }
    }

    echo "    <a href='../inicio.php' class='link'>REGRESAR A INICIO</a>";
    echo "</div>";
    
    pg_close($dbconn);

?>