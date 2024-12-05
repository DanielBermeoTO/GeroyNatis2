<!doctype html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="../Principal/Geroyn.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

  <title>Gero y Natis</title>
  <link rel="icon" href="../Imagenes/Gero_y_Natis Logo.png" type="image/png">

</head>

<body>
  <header>
    <nav  class="navbar navbar-expand-lg navbar-light bg-light">
      <div style="            background: linear-gradient(70deg, #3db5b9, #3d575e);
" class="container-fluid">
        <a class="navbar-brand" href="#">
          <img src="../Imagenes/Gero_y_Natis Logo.png" alt="" width="150" height="150">
        </a>
        <img src="../Imagenes/inicio.png" alt="Imagen adicional" width="650" height="350" class="d-none d-lg-block">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
          aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-center" id="navbarSupportedContent">
          <ul class="navbar-nav flex-column mb-2 mb-lg-0">
            <li class="nav-item">
              <a class="nav-link" href="../UsuarioControlador/VentasControlador.php"><i class="bi bi-clipboard2-pulse-fill"></i><span>Registro
                  de ventas</span></a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="../Sesiones/Cerrar Sesion.php"><i class="bi bi-door-closed-fill"></i><span>Cerrar Sesión</span></a>
            </li>
          </ul>
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
      // Iniciar la sesión
      session_start();

      // Verificar si la sesión está iniciada y si el usuario tiene el rol adecuado (rol 2 para vendedor)
      if (!isset($_SESSION['sesion']) || $_SESSION['sesion'] == "" || $_SESSION['rol'] != 2) {
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
          exit(); // Detener la ejecución del script si no se cumple la condición
      }
      // Iterar sobre los productos y mostrarlos
      while ($row = mysqli_fetch_assoc($productos)) {
          echo '<div class="col">
              <div class="card" style=" border: 2px solid black;">
                  <img src="../Imagenes/' . ($row['imagen']) . '" class="card-img-top mx-auto d-block img-fluid" alt="..." style="margin: 15px 10px 0 10px; width: 200px; height: 220px;">
                  <hr>
                  <div style="text-align: center; padding: 0 0 20px 0;" class="card-body">
                      <h5 class="card-title">' . ($row['nombreproducto']) . '</h5>
                      <br>
                      <p class="card-text"><strong>Precio: </strong>$' . number_format($row['precio']) . '</p>
                      <p class="card-text"><strong>Color: </strong>' . ($row['color']) . '</p>
                      <p class="card-text"><strong>Cantidad: </strong>' . ($row['cantidadp']) . '</p>
                      <p class="card-text"><strong>Talla: </strong>' . ($row['talla']) . '</p>
                  </div>
              </div>
          </div>';
      }
      ?>
    </div>
  </div>

  <!--Fin Inventario-->

  <div class="pie">
    <div class="row">
      <div class="col-md-8 col-lg-2 colForm1">
        <ul>
          <dd>
            <h2>Contáctanos</h2>
          </dd>
          <br>
          <dd><strong>Correo: </strong>felipedanieltorres32@gmail.com</dd>
          <dd><strong>Dirección: </strong> Av. Cra 30 Nro.17</dd>
          <dd><strong>Ciudad: </strong>Bogotá, Colombia</dd>
          <dd><strong>Teléfono: </strong>3011480544</dd>
        </ul>
      </div>

      <div class="col-md-8 col-lg-2  social-links">
        <h2>Síguenos</h2>
        <br>
        <ul>
          <li><a href="https://www.facebook.com" target="_blank">Facebook</a></li>
          <li><a href="https://www.instagram.com" target="_blank">Instagram</a></li>
          <li><a href="https://www.linkedin.com" target="_blank">LinkedIn</a></li>
        </ul>
      </div>
      <hr style="color: white;">
      <div class="derechos">
        <p><strong>@2024</strong> <strong>Advertencia: </strong>Todos los derechos reservados.</p>
      </div>
    </div>
  </div>

  <!-- Optional JavaScript; choose one of the two! -->

  <!-- Option 1: Bootstrap Bundle with Popper -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

</body>

</html>
