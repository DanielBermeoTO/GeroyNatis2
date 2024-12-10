<?php
// Mostrar todos los errores
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$mysqli = new mysqli("localhost", "root", "", "geroynatis");
if ($mysqli->connect_errno) {
    die("Error al conectar: " . $mysqli->connect_error); // Mostrar error si falla la conexión
}

session_start(); // Iniciar sesión

// Verifica que el formulario haya sido enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Capturar los datos del formulario
    $documento = $_POST['documento'];
    $Password = $_POST['contrasena'];
    echo "<br>";
    echo "<br>";

    // Consulta para obtener la contraseña encriptada y el rol del usuario
    $query = "SELECT AES_DECRYPT(s.contrasena, 'empanadaslomejor4') AS contrasenna, u.idrol 
              FROM sesion s 
              JOIN usuario u ON u.documento = s.documento 
              WHERE s.documento = ?";
    $stmt = $mysqli->prepare($query);
    
    if (!$stmt) {
        die("Error en la consulta: " . $mysqli->error); // Mostrar error de la consulta si falla
    }

    $stmt->bind_param("i", $documento); // 'i' porque es un entero
    $stmt->execute();
    $stmt->store_result();

    // Si el número de documento existe
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($contrasenna, $idrol);
        $stmt->fetch();

        // Verificar si la contraseña desencriptada coincide
        if ($Password === $contrasenna) {
            // Credenciales correctas, iniciar sesión
            $_SESSION['sesion'] = $documento;
            $_SESSION['rol'] = $idrol; // Almacenar el rol en la sesión

            if ($idrol == 1) {
                // Si es administrador, redirigir al panel de administración
                ?>
                <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                <script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Sesión iniciada',
                        text: 'Bienvenido administrador',
                        showConfirmButton: true,
                        confirmButtonText: "Aceptar"
                    }).then(function () {
                        window.location = "../Controlador/controladorInventario.php"; // Redirigir al panel de administración
                    });
                </script>
                <?php
            } elseif ($idrol == 2) {
                // Si es vendedor, redirigir al panel de vendedor
                ?>
                <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                <script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Sesión iniciada',
                        text: 'Bienvenido vendedor',
                        showConfirmButton: true,
                        confirmButtonText: "Aceptar"
                    }).then(function () {
                        window.location = "../UsuarioControlador/UsuarioInventario.php"; // Redirigir al panel de vendedor
                    });
                </script>
                <?php
            }
        } else {
            // Contraseña incorrecta
            ?>
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
            <script>
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Contraseña incorrecta',
                    showConfirmButton: true,
                    confirmButtonText: "Devolver"
                }).then(function () {
                    window.location = "../Sesiones/IniciarSesion.php";
                });
            </script>
            <?php
        }
    } else {
        // Documento no encontrado
        ?>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            Swal.fire({
                icon: 'warning',
                title: 'Usuario no encontrado',
                text: 'El documento no está registrado',
                showConfirmButton: true,
                confirmButtonText: "Devolver"
            }).then(function () {
                window.location = "../Sesiones/IniciarSesion.php";
            });
        </script>
        <?php
    }

    $stmt->close();
}

$mysqli->close();

?>
