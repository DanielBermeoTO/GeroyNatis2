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
  <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div style="            background: linear-gradient(70deg, #3db5b9, #3d575e);
" class="container-fluid">
      <a class="nav-link" style="color: black;" href="../UsuarioControlador/UsuarioInventario.php"><i class="bi bi-box-arrow-left"></i></a>
      <a class="navbar-brand" href="#">
        <img src="../Imagenes/Gero_y_Natis Logo.png" alt="" width="150" height="150">
      </a>

      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse justify-content-center" id="navbarSupportedContent">
        <ul class="navbar-nav  mb-2 mb-lg-0">
          <li class="nav-item">
            <a class="nav-link" href="../UsuarioControlador/UsuarioInventario.php"><i class="bi bi-file-medical-fill"></i><span>Inicio</span></a>
          </li>
        </ul>
        <form class="d-flex ms-lg-4">
          <input class="form-control me-2" type="search" placeholder="Buscar Venta" aria-label="Search">
          <button style="color: white; background: rgb(49, 44, 44); border: black; border-radius: 50px;" class="btn btn-outline-success" type="submit"><i class="bi bi-search"></i></button>
        </form>
      </div>
    </div>
  </nav>
  <nav class="acciones">
    <a class="aña" href="../Usuario/UsuarioAñadirVenta.php"><span><i class="bi bi-bag-plus-fill"></i> Añadir Venta</span></a>
  </nav>

  <div class="container" style="padding: 0 0 50px 0;  font-family: Oswald, sans-serif;">
    <div class="row">
      <!--Inicio Portafolio-->
      <div class="col-md-10">
        <h2 style="font-family: Bebas Neue, sans-serif; padding: 20px 0 0 0; font-size: 60px;">Tus Ventas <i class="bi bi-credit-card"></i></h2>
        <p style="font-family: Oswald, sans-serif; font-size: 22px;">En este apartado puedes visualizar las ventas realizadas.</p>
        <hr>
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
    exit(); // Asegúrate de salir después de mostrar el mensaje
}
?>
<div  class="col-md-12">
    <table  class="edit table table-responsive">
        <thead >
            <tr>
                <th style="            background: #3db5b9;
">ID</th>
                <th style="            background: #3db5b9;
">Fecha</th>
                <th style="            background: #3db5b9;
">Productos Vendidos</th>
                <th style="            background: #3db5b9;
">LLeva</th>
                <th style="            background: #3db5b9;
">Subtotal</th>
                <th style="            background: #3db5b9;
">Total</th>
                <th style="            background: #3db5b9;
">Estado</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Asegúrate de que $resultado esté definido y contenga datos
            while ($row = mysqli_fetch_assoc($resultado)) {
                echo '
                <tr>
                    <td>' . htmlspecialchars($row['idFactura']) . '</td>
                    <td>' . htmlspecialchars($row['fechaventa']) . '</td>
                    <td>
                        <table class="productos">
                            <thead>
                                <tr>
                                    <th style="            background: #3db5b9;
">ID Producto</th>
                                    <th style="            background: #3db5b9;
">Producto</th>
                                    <th style="            background: #3db5b9;
">Cantidad</th>
                                    <th style="            background: #3db5b9;
">Precio</th>
                                    <th style="            background: #3db5b9;
">IVA</th>
                                </tr>
                            </thead>
                            <tbody>';
                $productos = explode(', ', $row['productos']);
                foreach ($productos as $producto) {
                    list($idProducto, $nombreProducto, $cantidad, $precio, $iva) = explode(': ', $producto);
                    $precio = trim(str_replace('$', '', $precio));
                    echo '<tr>
                        <td>' . htmlspecialchars($idProducto) . '</td>
                        <td>' . htmlspecialchars($nombreProducto) . '</td>
                        <td>' . htmlspecialchars($cantidad) . '</td>
                        <td>$' . number_format($precio) . '</td>
                        <td>' . htmlspecialchars($iva) . '%</td>
                    </tr>';
                }
                echo '</tbody></table></td>
                    <td>' . htmlspecialchars($row['total_cantidad']) . '</td>
                    <td>$' . number_format($row['subtotal']) . '</td>
                    <td>$' . number_format($row['total']) . '</td>
                    <td>' . htmlspecialchars($row['estadi']) . '</td>
                </tr>';
            }
            ?>
        </tbody>
    </table>
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
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

  <!-- Option 2: Separate Popper and Bootstrap JS -->
  <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
    -->
</body>

</html>