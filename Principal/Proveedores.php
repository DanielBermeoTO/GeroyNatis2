<?php
include_once '../Modelo/Conexion.php'; // Incluye la conexión a la base de datos

// Realiza la conexión
$Conexion = Conectarse();

// Consulta SQL para obtener las tallas
$sqlt = "SELECT `idProducto`, `nombreproducto` FROM `producto`;";
$resultadot = $Conexion->query($sqlt);

?>

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
    <div class="container-fluid">
      <a class="nav-link" style="color: black;" href="../Controlador/controladorInventario.php"><i class="bi bi-box-arrow-left"></i></a>
      <a class="navbar-brand" href="#">
        <img src="../Imagenes/Gero_y_Natis Logo.png" alt="" width="150" height="150">
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse justify-content-center" id="navbarSupportedContent">
        <ul class="navbar-nav  mb-2 mb-lg-0">
          <li class="nav-item">
            <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">Proveedores</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="../Controlador/controladorInventario.php"><i class="bi bi-file-medical-fill"></i><span>Inicio</span></a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="../Controlador/controladorInventario2.php"><i class="bi bi-clipboard2-minus-fill"></i><span>Inventario</span></a>
          </li>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="../Controlador/controladorVentas.php"><i class="bi bi-clipboard2-pulse-fill"></i><span>Registro de ventas</span></a>
          </li>
        </ul>
        <form class="d-flex ms-lg-4">
          <input class="form-control me-2" type="search" placeholder="Buscar Proveedor" aria-label="Search">
          <button style="color: white; background: rgb(49, 44, 44); border: black; border-radius: 50px;" class="btn btn-outline-success" type="submit"><i class="bi bi-search"></i></button>
        </form>
      </div>
    </div>
  </nav>
  <nav class="acciones">
    <a class="aña" href="../Principal/ProveedoresAñadir.php"><span><i class="bi bi-emoji-grin"></i> Añadir Proveedor</span></a>
    <a class="aña" href="../Controlador/controladorMovimiento.php"><span><i class="bi bi-bus-front-fill"></i> Movimientos</span></a>

  </nav>

  <div class="container" style="padding: 0 0 50px 0; font-family: Oswald, sans-serif;">
    <div class="row">
      <!--Inicio Portafolio-->
      <div class="col-md-8">
        <h2 style="font-family: Bebas Neue, sans-serif; padding: 20px 0 0 0; font-size: 60px;">Tus Proveedores <i class="bi bi-file-person"></i></h2>
        <p style="font-family: Oswald, sans-serif; font-size: 22px;">En este apartado puedes visualizar tus proveedores, actualizarlos, eliminarlos</p>
        <hr>

        <div class="col-md-12">
          <table class="edit table table-responsive">
            <thead>
              <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Teléfono</th>
                <th>Producto que provee</th>
                <th>Editar</th>
              </tr>
            </thead>
            <tbody>
              <?php while ($row = mysqli_fetch_assoc($resultadoo)): ?>
                <tr>
                  <td><?php echo $row['idProveedor']; ?></td>
                  <td><?php echo $row['nombreproveedor']; ?></td>
                  <td><?php echo $row['Telefono']; ?></td>
                  <td>
                    <table class="productos">
                      <thead>
                        <tr>
                          <th>Producto</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td><?php echo $row['producto']; ?></td>
                        </tr>
                      </tbody>
                    </table>
                  </td>
                  <td>
                    <button data-bs-toggle="modal" data-bs-target="#updateModal<?php echo $row['idProveedor']; ?>" style="border: none; background: white; color: green;" type="button">
                      <i class="bi bi-cloud-upload"></i> Actualizar
                    </button>
                  </td>
                </tr>

                <!-- Modal para actualizar proveedor -->
                <div class="modal fade" id="updateModal<?php echo $row['idProveedor']; ?>" tabindex="-1" aria-labelledby="updateModalLabel" aria-hidden="true">
                  <div class="modal-dialog" style="border: 2px solid black; border-radius: 10px;">
                    <div class="modal-content" style="padding: 20px; background: linear-gradient(70deg, #c24a46, #c2a8a1);">
                      <div class="modal-header">
                        <h5 class="modal-title" id="updateModalLabel">Actualizar Proveedor - ID: <?php echo $row['idProveedor']; ?></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body">
                        <form action="../Controlador/controladorProveedores.php" method="post">
                          <div class="mb-3">
                            <label class="form-label">ID proveedor</label>
                            <input class="form-control" name="idProveedor" type="text" value="<?php echo $row['idProveedor']; ?>">
                          </div>
                          <div class="mb-3">
                            <label class="form-label">Nombre Proveedor</label>
                            <input class="form-control" name="nombreproveedor" type="text" value="<?php echo $row['nombreproveedor']; ?>">
                          </div>
                          <div class="mb-3">
                            <label class="form-label">Teléfono</label>
                            <input class="form-control" name="Telefono" type="text" value="<?php echo $row['Telefono']; ?>">
                          </div>
                          <div class="mb-3">
                            <label class="form-label">Producto</label>
                            <select class="form-select" name="productos" required>
                              <?php
                              mysqli_data_seek($resultadot, 0);
                              while ($jeje = mysqli_fetch_assoc($resultadot)) {
                                $selected = ($row['producto'] == $jeje['nombreproducto']) ? 'selected' : '';
                                echo "<option value=\"{$jeje['idProducto']}\" $selected>{$jeje['nombreproducto']}</option>";
                              }
                              ?>
                            </select>
                          </div>
                          <button class="btn btn-warning" type="submit" name="Acciones" value="Actualizar Proveedor">Actualizar Proveedor</button>
                        </form>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                      </div>
                    </div>
                  </div>
                </div>
              <?php endwhile; ?>
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