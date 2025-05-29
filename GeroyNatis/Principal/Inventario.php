<!doctype html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="../Principal/Geroyn.css">
      <link rel="stylesheet" href="../Principal/pie.css">

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">


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
  <div class="header-wrapper">
        <div class="header-background"></div>
        
        <!-- Puntos decorativos -->
        <div class="decorative-dot dot-1"></div>
        <div class="decorative-dot dot-2"></div>
        <div class="decorative-dot dot-3"></div>
        <div class="decorative-dot dot-4"></div>
        
        <div class="container-fluid">
            <!-- Header Top -->
            <div class="header-top d-flex justify-content-end align-items-center">
                <button onclick="window.location.href='../Controlador/controladorUsuario.php'" class="btn-icon position-relative">
                    <i class="fa-solid fa-user"></i>
                </button>
                <button onclick="window.location.href='../Sesiones/Cerrar Sesion.php'" class="btn-icon">
                    <i class="fa-solid fa-door-open"></i>
                </button>
                <div class="ms-2">
                    <img src="../Imagenes/Gero_y_Natis Logo.png?height=40&width=40" alt="Avatar" class="avatar">
                </div>
            </div>
            
            <!-- Main Heading -->
            <h1 class="main-heading">Gero y Natis</h1>
            
            <!-- Navigation Pills -->
            <div class="d-flex justify-content-center mb-4" style="font-family: Oswald, sans-serif; font-size: 1.1em;">
                <ul class="nav nav-pills">
                    <li class="nav-item">
                        <a class="nav-link active" href="../Controlador/controladorInventario.php"><i class="far fa-copy me-2"></i>Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../Controlador/controladorInventario2.php"><i class="fa-regular fa-clipboard"></i> Inventario</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../Controlador/controladorProveedores.php"><i class="bi bi-file-person"></i> Proveedores</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../Controlador/controladorMovimiento.php"> <i class="bi bi-book-half"></i> Movimientos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../Controlador/controladorVentas.php"><i class="fa-regular fa-credit-card"></i> Ventas</a>
                    </li>
                </ul>
            </div>
            
            <!-- Search Bar -->
            <div class="search-container">
                <div class="input-group search-input">
                    <span class="input-group-text bg-transparent border-0">
                        <i class="fas fa-search search-icon"></i>
                    </span>
                    <input type="search" class="form-control border-0 shadow-none" aria-label="Search" name="busqueda" placeholder="Buscar">
                    <span class="input-group-text bg-transparent border-0">
                        <i class="fas fa-arrow-right search-icon"></i>
                    </span>
                </div>
            </div>
        </div>
    </div>

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
