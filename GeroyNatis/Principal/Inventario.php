<!doctype html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="../Principal/Geroyn.css">
      <link rel="stylesheet" href="../Principal/pie.css">

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

  <title>Gero y Natis</title>
  <link rel="icon" href="../Imagenes/Gero_y_Natis Logo.png" type="image/png">
</head>

<body>
<?php
// Iniciar la sesión
session_start();

// Verificar si la sesión está iniciada y si el usuario tiene el rol adecuado (rol 2 para vendedor)
if (!isset($_SESSION['sesion']) || $_SESSION['sesion'] == "" || $_SESSION['rol'] != 1) {
    // Si no está logueado o no tiene el rol de vendedor, mostrar alerta y redirigir
    ?>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Acceso denegado',
            text: 'Debe iniciar sesión para acceder a esta página',
            showConfirmButton: true,
            confirmButtonText: "Aceptar",
        }).then(function() {
            window.location = "../Principal/inicio.html"; // Redirigir a la página de inicio de sesión
        });
    </script>
    <?php
    exit(); // Asegúrate de salir después de mostrar el mensaje
}
?>
  <header>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
      <div class="container-fluid">
        <a class="navbar-brand" href="#">
          <img src="../Imagenes/Gero_y_Natis Logo.png" alt="" width="150" height="150">
        </a>
        <img src="../Imagenes/inicio.png" alt="Imagen adicional" width="650" height="350" class="d-none d-lg-block">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-center" id="navbarSupportedContent">
          <ul class="navbar-nav flex-column mb-2 mb-lg-0">
            <li class="nav-item">
            </li>
            <li class="nav-item">
              <a class="nav-link" href="../Controlador/controladorUsuario.php"><i class="bi bi-person-circle"></i><span>Usuarios</span></a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="../Controlador/controladorInventario2.php"><i class="bi bi-clipboard2-minus-fill"></i><span>Inventario</span></a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="http://127.0.0.1:8000/proveedor"><i class="bi bi-file-person"></i><span>Proveedores</span></a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="../Controlador/controladorVentas.php"><i class="bi bi-clipboard2-pulse-fill"></i><span>Registro de ventas</span></a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="../Sesiones/Cerrar Sesion.php"><i class="bi bi-door-closed-fill"></i><span>Cerrar Sesión</span></a>
            </li>
          </ul>
          <form action="" method="get" class="d-flex ms-lg-4">
            <input class="form-control me-2" type="search" placeholder="Buscar" aria-label="Search" name="busqueda">
            <button style="color: white; background: rgb(49, 44, 44); border: black; border-radius: 50px;" class="btn btn-outline-success" type="submit" name="enviar" value="buscar"><i class="bi bi-search"></i></button>
          </form>
        </div>
      </div>
    </nav>
    <nav class="acciones" style="color: white">
      <h5>Bienvenido a Gero y Natis</h5>
    </nav>
  </header>

  <!--Inicio Inventario-->
  <div class="container">
    <h2 style="font-family: Bebas Neue, sans-serif; padding: 20px 0 0 0; font-size: 60px;">Inicio <i class="bi bi-postcard-heart-fill"></i></h2>
    <p style="font-family: Oswald, sans-serif; font-size: 22px;">¡Estos son tus productos en pantalla! </p>
    <hr>
    <div style="padding: 30px 0 50px 0;" class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-4">
      <?php
      // Verificar si se realizó una búsqueda
      if (isset($_GET['enviar']) && !empty($_GET['busqueda'])) {
          $busqueda = $_GET['busqueda'];
          $consulta = Conectarse()->query("SELECT * FROM producto WHERE nombreproducto LIKE '%$busqueda%'");
      } else {
          // Si no hay búsqueda, mostrar todos los productos
          $consulta = Conectarse()->query("SELECT * FROM producto");
      }

      // Mostrar los resultados
      if ($consulta->num_rows > 0) {
          while ($row = $consulta->fetch_array()) {
            $urlImagen = $row['imagen']; // La URL de Cloudinary

              echo '<div class="col">
                  <div class="card" style="border: 2px solid black;">
<img src="' . htmlspecialchars($row['imagen']) . '" class="card-img-top mx-auto d-block img-fluid" alt="Producto" style="margin: 15px 10px 0 10px; width: 200px; height: 220px;">
                      <hr>
                      <div style="text-align: center; padding: 0 0 20px 0;" class="card-body">
                          <h5 class="card-title">' . htmlspecialchars($row['nombreproducto']) . '</h5>
                          <p class="card-text"><strong>Precio: </strong>$' . number_format($row['precio']) . '</p>
                      </div>
                  </div>
              </div>';
          }
      } else {
          echo '<p class="text-center">No se han registrado productos.</p>';
      }
      ?>
    </div>
  </div>
  <!--Fin Inventario-->

  

   <!-- Footer -->
    <footer class="footer">
        <div class="footer-decorative">
            <i class="fas fa-tools"></i>
        </div>
        
        <div class="container">
            <!-- Marca Principal -->
            <div class="footer-brand">
                <h2 class="footer-logo">GERO Y NATIS</h2>
                <p class="footer-tagline">Sistema de Gestión de Inventario</p>
                <p class="footer-version">Versión 1.0.0 | Instalación Local</p>
            </div>

            <!-- Sección de Soporte Técnico -->
            <div class="support-section">
                <h5 class="support-title">
                    <i class="fas fa-headset"></i>
                    Soporte Técnico
                </h5>
                
                <div class="contact-grid">
                    <div class="contact-card">
                        <div class="contact-icon">
                            <i class="fas fa-phone"></i>
                        </div>
                        <div class="contact-label">Teléfono</div>
                        <div class="contact-value">
                            <a href="tel:+525512345678">+57 301 143 0988</a>
                            <br>
                            <a href="tel:+525512345678">+57 302 133 4678</a>
                            <br>
                            <a href="tel:+525512345678">+57 301 460 0998</a>

                        </div>
                    </div>

                    <div class="contact-card">
                        <div class="contact-icon">
                            <i class="fab fa-whatsapp"></i>
                        </div>
                        <div class="contact-label">WhatsApp</div>
                        <div class="contact-value">
                            <a href="https://wa.me/573011480544">+57 301 444 9971</a>
                            <br>
                            <a href="https://wa.me/573005467787">+57 300 546 7787</a>
                            <br>
                            <a href="https://wa.me/573014568799">+57 301 456 8799</a>
                        </div>
                    </div>

                    <div class="contact-card">
                        <div class="contact-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div class="contact-label">Email</div>
                        <div class="contact-value">
                            <a href="mailto:soporte@tuempresa.com">daniel_bermeo@soy.sena.edu.co</a>
                            <br>
                            <a href="mailto:soporte@tuempresa.com">deisy_gonzalez@soy.sena.edu.co</a>
                            <br>
                            <a href="mailto:soporte@tuempresa.com">vanessa_mateus@soy.sena.edu.co</a>
                        </div>
                    </div>

                    <div class="contact-card">
                        <div class="contact-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="contact-label">Horario</div>
                        <div class="contact-value">
                            Lun - Vie: 9:00 - 18:00
                        </div>
                    </div>
                </div>

                <!-- Contacto de Emergencia -->
                <div class="emergency-contact">
                    <div class="contact-icon">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <div class="contact-label">Emergencias 24/7</div>
                    <div class="contact-value">
                        <a href="tel:+525587654321">+52 304 555 7689</a>
                    </div>
                </div>
            </div>

            <!-- Información del Sistema -->
            <div class="system-info">
                <div class="info-item">
                    <i class="fas fa-server"></i>
                    <span>Sistema Local</span>
                </div>
                <div class="info-item">
                    <i class="fas fa-shield-alt"></i>
                    <span>Datos Seguros</span>
                </div>
                <div class="info-item">
                    <i class="fas fa-tools"></i>
                    <span>Mantenimiento Incluido</span>
                </div>
                <div class="info-item">
                    <i class="fas fa-graduation-cap"></i>
                    <span>Capacitación Disponible</span>
                </div>
            </div>

            <!-- Divisor -->
            <div class="footer-divider"></div>

            <!-- Copyright -->
            <div class="footer-bottom">
                <p class="footer-copyright">
                    <strong>@2024</strong> <strong>Advertencia: </strong>Todos los derechos reservados. 
                    | Sistema desarrollado para <strong>Gero y Natis</strong> | ¿Problemas? Contáctanos arriba
                </p>
            </div>
        </div>
    </footer>

  <!-- Optional JavaScript -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>

</html>
