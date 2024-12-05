<!doctype html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="../Principal/Geroyn.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

  <title>Gero y Natis</title>
  <link rel="icon" href="Imagenes/Gero_y_Natis Logo.png" type="image/png">
  <style>
    .navbar {
      background: linear-gradient(70deg, #c24a46, #a686ca, #3db5b9);
      box-shadow: 0 2px 4px rgba(0, 0, 0, 1);
      font-family: "Bebas Neue", sans-serif;
      font-size: 20px;
    }

    .navbar-brand img {
      transition: transform 0.3s ease;
    }

    .navbar-brand img:hover {
      transform: scale(1.05);
    }

    .nav-item {
      margin-bottom: 0px;
      transition: background-color 0.3s ease;
    }

    .nav-link {
      padding: 10px 15px !important;
      border-radius: 20px;
      transition: all 0.3s ease;
    }

    .nav-link:hover {
      background-color: rgba(0, 0, 0, 0.05);
    }

    .nav-link i {
      margin-right: 5px;
    }

    @media (min-width: 992px) {
      .nav-item {
        margin-right: 20px;
      }

      .navbar-nav {
        align-items: center;
      }
    }
  </style>
  <style>
    body {
      font-family: Oswald, sans-serif;
      margin: 0;
      padding: 0;
      background-image: url('../Imagenes/sesion.jpg');
      /* Ruta relativa a la carpeta de imagen */
      background-size: 180% auto;
      /* Duplicamos el tamaño en ancho para mostrar solo la mitad */
      background-position: center left bottom;
      /* Colocamos la imagen en la parte inferior */
      background-repeat: no-repeat;
      /* Evita que la imagen se repita */
    }

    .container {
      padding: 0 0 50px 0;
      font-family: Oswald, sans-serif;
      position: relative;
      z-index: 2;
      /* Aseguramos que el contenido esté encima de la imagen */
    }

    /* Opcional: si quieres añadir un fondo semitransparente al formulario */
    .form-background {
      background-color: rgba(255, 255, 255, 0.8);
      border: 2px solid black;
      border-radius: 10px;
      padding: 20px;
    }

    /* Media query para pantallas pequeñas (menor a 768px) */
    @media (max-width: 768px) {
      body {
        background-image: none;
        /* Oculta la imagen en pantallas pequeñas */
      }
    }

    @media (max-width: 1px) {
      body {
        background-image: none;
        /* Oculta la imagen en pantallas pequeñas */
      }
    }
  </style>
</head>

<body>
  <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="nav-link" style="color: black;" href="../Principal/inicio.html"><i class="bi bi-box-arrow-left"></i></a>
    <img src="../Imagenes/Gero_y_Natis Logo.png" alt="" width="150" height="150">
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
      aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
  </nav>

  <div class="container" style="padding: 0 0 50px 0;  font-family: Oswald, sans-serif;">
    <div class="row">
      <!--Inicio Portafolio-->
      <div class="col-md-8">
        <h2 style="font-family: Bebas Neue, sans-serif; padding: 20px 0 0 0; font-size: 60px;">Iniciar Sesión</h2>
        <p style="font-family: Oswald, sans-serif; font-size: 22px;">Inicia sesión con tus datos para disrutar de
          nuestros servicios.</p>
        <hr>

        <div class="row">
          <div class="col-md-6" style="border: 2px solid black; border-radius: 10px; ">
            <form action="../Sesiones/IniciarSsesion.php" class="anadir" method="post" style="padding: 20px;">
              <div class="correo">
                <input type="number" name="documento" id="documento" required step="1"
                  title="Solo se permiten números enteros." oninput="validarLongitud(this)" maxlength="11"
                  inputmode="numeric">

                <script>
                  function validarLongitud(input) {
                    input.value = input.value.replace(/\D/g, '');
                    // Limitar a 11 dígitos
                    if (input.value.length > 11) {
                      input.value = input.value.slice(0, 11);
                    }
                  }
                </script>

                <label for="">Documento</label>
              </div>
              <div class="correo">
                <input type="password" name="contrasena" id="contrasena"
                  required><label>Contraseña</label></div>
              <center>
                <button name="Acciones" value="Iniciar Sesión"
                  style="background: linear-gradient(70deg, #c24a46, #c2a8a1); padding: 10px; border-radius: 20px;"
                  type="submit" class="anadirr"><i class="bi bi-file-earmark-plus"></i> Iniciar</button>
              </center>
              <br>

              <p>¿Olvido su contraseña? <a style="color: #c24a46; text-decoration: none;"
                  href="./Recuperar contraseña.php">Recuperar
                  Contraseña</a></p>

              <?php
              if (isset($_GET['message'])) {
                // Definir clases y mensajes según el tipo
                $alertClass = 'alert-danger'; // Por defecto, alerta de error
                $icon = '<i class="bi bi-exclamation-triangle-fill"></i>'; // Ícono de error
                $messageText = 'Algo salió mal, intenta de nuevo'; // Mensaje por defecto

                // Evaluar el mensaje recibido
                switch ($_GET['message']) {
                  case 'ok':
                    $alertClass = 'alert-success'; // Cambiar a éxito
                    $icon = '<i class="bi bi-check-circle-fill"></i>'; // Ícono de éxito
                    $messageText = 'Su correo fue enviado satisfactoriamente, revise su correo';
                    break;

                  case 'Usuario no encontrado':
                    $messageText = 'Usuario no encontrado, Usuario inexistente o correo mal proporcionado.';
                    break;

                  case 'okay':
                    $alertClass = 'alert-success'; // Cambiar a éxito
                    $icon = '<i class="bi bi-check-circle-fill"></i>'; // Ícono de éxito
                    $messageText = 'Contraseña actualizada con exito.';
                    break;

                  default:
                    // Mantener valores por defecto
                    break;
                }
              ?>

                <!-- Mostrar la alerta -->
                <div class="alert <?= $alertClass ?> d-flex align-items-center" role="alert">
                  <?= $icon ?>
                  <div style="margin-left: 10px;">
                    <?= $messageText ?>
                  </div>
                </div>

              <?php
              }
              ?>


            </form>
          </div>
        </div>

      </div>
    </div>
  </div>

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
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
    crossorigin="anonymous"></script>

  <!-- Option 2: Separate Popper and Bootstrap JS -->
  <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
    -->
</body>

</html>