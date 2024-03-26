<?php
session_start(); // Inicia la sesión

// Elimina todas las variables de sesión
$_SESSION = [];

// Si se desea destruir completamente la sesión, borra también la cookie de sesión.
// Nota: ¡Esto destruirá la sesión, y no solo los datos de la sesión!
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Finalmente, destruye la sesión.
session_destroy();

// Redirige al usuario a la página de inicio de sesión
header('Location: login.php');
exit;
?>
