function preguntaEliminar(tipo,codigo,nombre){ 
  if(tipo=='estadio'){
      if(confirm("¿Está seguro que desea eliminar la informacion del estadio "+nombre+"?")){
        document.location.href="eliminarEstadio.php?id="+codigo;
      }
  }else if(tipo=='equipo'){
      if(confirm("¿Está seguro que desea eliminar la informacion del equipo "+codigo+"?")){
        document.location.href="eliminarEquipo.php?id="+codigo;
      }
  }else if(tipo == 'usuario'){
      if(confirm("¿Está seguro que desea eliminar la informacion del usuario "+codigo+"?, se eliminaran todos las quinielas que esten relacionados con él")){
        document.location.href="eliminarUsuario.php?id="+codigo;
      }
  }
}