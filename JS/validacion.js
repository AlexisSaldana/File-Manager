function mostrarContrasena() {
    let contrasena = document.getElementById("password");
    let eyeicon = document.getElementById("verContrasena");

    eyeicon.onclick = function(){
        if (contrasena.type === "password") {
            contrasena.type = "text";
            eyeicon.src = "iconos/eye-open.png"
        } else {
            contrasena.type = "password";
            eyeicon.src = "iconos/eye-close.png"
        }
    }
}

function validarInicioSesion() {
    var usuario = document.getElementById("usuario").value;
    var contrasena = document.getElementById("password").value;

    if (usuario === "" || contrasena === "") {
        alert("Nombre de usuario y contraseña son obligatorios. Por favor, complete ambos campos.");
        return false;
    }

    return true;
}