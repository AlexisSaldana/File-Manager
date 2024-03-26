<?php
session_start();

require_once 'config.php';
require_once 'login_helper.php';

$username = $_SESSION['username'] ?? "Usuario Desconocido";
$esAdmin = $_SESSION['esAdmin'] ?? false;

function listarArchivos() {
    $archivos = scandir(DIR_UPLOAD);
    return array_filter($archivos, function($archivo) {
        return !in_array($archivo, ['.', '..']);
    });
}

if ($esAdmin && isset($_POST['borrar']) && isset($_POST['nombreArchivo'])) {
    $archivoABorrar = DIR_UPLOAD . $_POST['nombreArchivo'];
    if (file_exists($archivoABorrar)) {
        unlink($archivoABorrar);
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    }
}

// Procesa la solicitud de subida de archivo
$errorSubida = '';
if (isset($_FILES['archivo']) && isset($_POST['subirArchivo'])) {
    $archivoTemp = $_FILES['archivo']['tmp_name'];
    $extensionArchivo = strtolower(pathinfo($_FILES['archivo']['name'], PATHINFO_EXTENSION));
    $nombrePersonalizado = $_POST['nombreArchivo'] ? trim($_POST['nombreArchivo']) . '.' . $extensionArchivo : $_FILES['archivo']['name'];
    $rutaDestino = DIR_UPLOAD . $nombrePersonalizado;

    // Validar tipo de archivo
    if (!array_key_exists($extensionArchivo, $CONTENT_TYPES_EXT)) {
        $errorSubida = 'Tipo de archivo no permitido.';
    } elseif (move_uploaded_file($archivoTemp, $rutaDestino)) {
        // Archivo subido exitosamente
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    } else {
        $errorSubida = 'Error al subir el archivo.';
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CSS/index.css">
    <link rel="stylesheet" href="CSS/archivos.css">
    <title>Pagina Principal</title>
    <script>
        function confirmarBorrado(nombreArchivo) {
            return confirm(`¿Está seguro que desea borrar ${nombreArchivo}?`);
        }
    </script>
</head>
<body>
    <header class="header">
        <a href="#" class="logo">Bienvenido, <?php echo htmlspecialchars($username); ?></a>
        <nav class="navbar">
            <a href="logout.php">Cerrar Sesión</a>
        </nav>
    </header>
    
    <div class="contenedor-archivos">
        <h1>Listar Archivos</h1>
        <table>
            <thead>
                <tr>
                    <th>Nombre del archivo</th>
                    <th>Tamaño (KB)</th>
                    <?php if ($esAdmin): ?>
                        <th>Eliminar Archivo</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach (listarArchivos() as $archivo): ?>
                    <tr>
                        <td><a href="archivo.php?nombre=<?php echo urlencode($archivo); ?>" target="_blank"><?php echo htmlspecialchars($archivo); ?></a></td>
                        <td><?php echo round(filesize(DIR_UPLOAD . $archivo) / 1024, 2); ?></td>
                        <?php if ($esAdmin): ?>
                            <td>
                                <form method="post" onsubmit="return confirmarBorrado('<?php echo htmlspecialchars($archivo); ?>');">
                                    <input type="hidden" name="nombreArchivo" value="<?php echo htmlspecialchars($archivo); ?>">
                                    <button type="submit" name="borrar"><img src="iconos/eliminar.png" alt=""></button>
                                </form>
                            </td>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <?php if ($esAdmin): ?>
            <h2>Subir Archivo</h2>
            <?php if ($errorSubida): ?>
                <p><?php echo $errorSubida; ?></p>
            <?php endif; ?>
            <form class="form-subir" method="post" enctype="multipart/form-data">
                <label for="nombreArchivo">Nombre del archivo (opcional):</label>
                <input type="text" id="nombreArchivo" name="nombreArchivo" placeholder="Nombre personalizado">
                <input class="archivofile" type="file" name="archivo" required>
                <button class="subir-btn" type="submit" name="subirArchivo"><img src="iconos/upload.png" alt=""></button>
            </form>

        <?php endif; ?>
    </div>
</body>
</html>
