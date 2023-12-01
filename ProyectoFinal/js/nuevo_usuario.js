document.addEventListener("DOMContentLoaded", function() {
    document.getElementById("formulario").addEventListener('submit', validarFormulario); 
  });


function validarFormulario(evento){
    evento.preventDefault();
    var pass1 = document.getElementById("contrasena").value
    var pass2 = document.getElementById("verif_contrasena").value
    if(!(pass1===pass2)){
        alert("Las contraseñas no coinciden");
        return;
    }
    this.submit();
}