<?php
// Conexión a la base de datos
$mysqli = new mysqli("localhost", "root", "", "geroynatis");
if ($mysqli->connect_errno) {
    die("Error al conectar: " . $mysqli->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $documento = $_POST['documento'];
    $nueva_contrasena = $_POST['nueva_contrasena'];
    $llave_cifrado = 'empanadaslomejor4'; // Asegúrate de usar una clave de cifrado segura y consistente

    // Actualizar la contraseña en la tabla `sesion` usando AES_ENCRYPT
    $sql = "UPDATE sesion 
            SET contrasena = AES_ENCRYPT('$nueva_contrasena', '$llave_cifrado'), 
                token = NULL, 
                token_expiry = NULL 
            WHERE documento = '$documento'";

    if ($mysqli->query($sql)) {
        header("Location: ../Sesiones/IniciarSesion.php?message=okay");
        } else {
        echo "Error al actualizar la contraseña: " . $mysqli->error;
    }
}
?>
