<?php
session_start();
require_once 'login_helper.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['usuario'] ?? '';
    $password = $_POST['contrasena'] ?? '';
    $user = autentificar($username, $password);

    if ($user) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['esAdmin'] = $user['esAdmin'];

        if ($_SESSION['esAdmin']) {
            header('Location: index.php');
            exit;
        } else {
            header('Location: index.php'); 
            exit;
        }
    } else {
        $error = 'Usuario o contraseña incorrectos.';
    }
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CSS/iniciar-sesion.css">
    <link rel="stylesheet" href="CSS/index.css">
    <script src="JS/validacion.js"></script>
    <title>Inicio de Sesión</title>
</head>
<body>
    <header class="header">
        <a href="#" class="logo">Bienvenido al Manejador de Archivos</a>
    </header>

    <div class="inicio-sesion">
        <h1>Iniciar Sesión</h1>
        <?php if ($error): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
        <form method="post" action="login.php">
            <input type="text" id="usuario" name="usuario" placeholder="Usuario">
            <div class="password">
                <input type="password" id="password" name="contrasena" placeholder="Contraseña">
                <img src="iconos/eye-close.png" id="verContrasena" onclick="mostrarContrasena()">
            </div>
            <button class="boton-enviar" type="submit">Acceder</button>
        </form>
    </div>
    <script src="JS/validacion.js"></script>
</body>
</html>