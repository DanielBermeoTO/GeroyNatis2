<?php
if (isset($_GET['error']) && $_GET['error'] === 'usuario_existente') {
  echo "<script>alert('Error: El usuario ya existe.');</script>";
}
?>

<!doctype html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="../Principal/Geroyn.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

  <title>Gero y Natis</title>
  <link rel="icon" href="../Imagenes/Gero_y_Natis Logo.png" type="image/png">
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
</head>

<body>
  <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="nav-link" style="color: black;" href="../Controlador/controladorUsuario.php"><i class="bi bi-box-arrow-left"></i></a>
    <div class="container">
      <img src="../Imagenes/Gero_y_Natis Logo.png" alt="" width="150" height="150">
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
    </div>
  </nav>

  <div class="container" style="padding: 0 0 50px 0;  font-family: Oswald, sans-serif;">
    <div class="row">
      <!--Inicio Portafolio-->
      <div class="col-md-8">
        <h2 style="font-family: Bebas Neue, sans-serif; padding: 20px 0 0 0; font-size: 60px;">Crear Usuario</h2>
        <p style="font-family: Oswald, sans-serif; font-size: 22px;">Crea usuarios a tus empleados con roles diferentes.</p>
        <hr>

        <div class="row">
          <div class="col-md-6" style="border: 2px solid black; border-radius: 10px; ">
            <form action="../Controlador/controladorUsuario.php" class="anadir" method="post" style="padding: 20px;">
              <label for="tipoDocumento">Tipo de Documento</label>
              <br>
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="tipoDocumento" id="tipoDocumentoCC" value="CC" required>
                <label class="form-check-label" for="tipoDocumentoCC">CC</label>
              </div>
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="tipoDocumento" id="tipoDocumentoTI" value="TI">
                <label class="form-check-label" for="tipoDocumentoTI">TI</label>
              </div>
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="tipoDocumento" id="tipoDocumentoCE" value="CE">
                <label class="form-check-label" for="tipoDocumentoCE">CE</label>
              </div>

              <div class="correo">
                <input type="number" name="documento" id="documento" required step="1" title="Solo se permiten números enteros." oninput="validarLongitud(this)" maxlength="11" inputmode="numeric">
                <script>
                  function validarLongitud(input) {
                    input.value = input.value.replace(/\D/g, '');
                    // Limitar a 11 dígitos
                    if (input.value.length > 11) {
                      input.value = input.value.slice(0, 11);
                    }
                  }
                </script>
                <label for="documento"><i class="bi bi-person"></i> Documento:</label>
              </div>

              <div class="correo">
                <input type="text" name="nombre" id="nombre" required oninput="validartext(this)" maxlength="15">
                <label for="nombre"><i class="bi bi-person-fill"></i> Nombres:</label>
              </div>
              <div class="correo">
                <input type="text" name="apellido" id="apellido" required oninput="validartext(this)" maxlength="15">
                <label for="apellido"><i class="bi bi-person-lines-fill"></i> Apellidos:</label>
              </div>

              <script>
                function validartext(input) {
                  // Eliminar caracteres que no sean letras o espacios
                  input.value = input.value.replace(/[^A-Za-z\s]/g, "");

                  // Limitar a 15 caracteres
                  if (input.value.length > 15) {
                    input.value = input.value.slice(0, 15);
                  }
                }
              </script>

              <div class="correo">
                <input type="text" name="direccion" id="direccion" required>
                <label for="direccion"><i class="bi bi-geo-alt"></i> Dirección:</label>
              </div>

              <div class="correo">
                <select name="localidad" id="localidad" required>
                  <option value="">Seleccione una localidad</option>
                  <option value="Usaquen">Usaquén</option>
                  <option value="Chapinero">Chapinero</option>
                  <option value="SantaFe">Santa Fe</option>
                  <option value="SanCristobal">San Cristóbal</option>
                  <option value="Usme">Usme</option>
                  <option value="Tunjuelito">Tunjuelito</option>
                  <option value="Bosa">Bosa</option>
                  <option value="Kennedy">Kennedy</option>
                  <option value="Fontibon">Fontibón</option>
                  <option value="Engativa">Engativá</option>
                  <option value="Suba">Suba</option>
                  <option value="BarriosUnidos">Barrios Unidos</option>
                  <option value="Teusaquillo">Teusaquillo</option>
                  <option value="LosMartires">Los Mártires</option>
                  <option value="AntonioNariño">Antonio Nariño</option>
                  <option value="PuenteAranda">Puente Aranda</option>
                  <option value="LaCandelaria">La Candelaria</option>
                  <option value="RafaelUribeUribe">Rafael Uribe Uribe</option>
                  <option value="CiudadBolivar">Ciudad Bolívar</option>
                  <option value="Sumapaz">Sumapaz</option>
                </select>
              </div>

              <div class="correo">
                <input type="number" name="telefono" id="telefono" step="1" title="Solo se permiten números enteros." oninput="validarLongitud(this)" maxlength="11" inputmode="numeric">
                <label for="telefono"><i class="bi bi-telephone"></i> Telefono:</label>
              </div>

              <div class="correo">
                <input type="email" name="correo" id="correo" required>
                <label for="correo"> <i class="bi bi-envelope"></i> Correo Electronico</label>
              </div>

              <div class="correo">
                <input type="password" name="contrasena" id="contrasena" required>
                <label for="contrasena"><i class="bi bi-lock"></i> Contraseña</label>
              </div>

              <div class="correo">
                <select name="estado" id="estado" required>
                  <option value="">Estado</option>
                  <option value="3">Activo</option>
                  <option value="4">Inactivo</option>
                </select>
              </div>
              <div class="correo">
                <select name="idrol" id="idrol" required>
                  <option value="">Estado</option>
                  <option value="1">Administrador</option>
                  <option value="2">Vendedor</option>
                </select>
              </div>



              <button style="background: linear-gradient(70deg, #c24a46, #c2a8a1); padding: 10px; border-radius: 20px;" type="submit" name="Acciones" value="Crear Usuario" class="anadirr">
                <i class="bi bi-file-earmark-person-fill"></i> Registrate
              </button>
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