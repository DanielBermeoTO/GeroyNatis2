<?php
include_once('../Modelo/Conexion.php');  // Si el archivo está en un directorio superior

// Realiza la conexión
$conexion = Conectarse();

// Consulta SQL para obtener las tallas
$sql = "SELECT idtalla, talla FROM talla";
$resultado = $conexion->query($sql);

$sqlCategorias = "SELECT idCategoria, categoria FROM categoria";
$resultadoCategorias = $conexion->query($sqlCategorias);

$sqlpro = "SELECT `idProveedor`, `nombreproveedor`, `Telefono`, `productos` FROM `proveedor`";
$resultadopro = $conexion->query($sqlpro);

$sqlest = "SELECT idestado, tiposestados FROM estados";
$resultadoest = $conexion->query($sqlest);


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
  <header>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
      <div class="container-fluid">
        <a class="nav-link" style="color: black;" href="../Controlador/controladorInventario2.php"><i class="bi bi-box-arrow-left"></i></a>
        <a class="navbar-brand" href="#">
          <img src="../Imagenes/Gero_y_Natis Logo.png" alt="" width="150" height="150">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-center" id="navbarSupportedContent">
          <ul class="navbar-nav  mb-2 mb-lg-0">
            <li class="nav-item">
              <a class="nav-link" href="../Controlador/controladorInventario2.php" tabindex="-1" aria-disabled="true">Inventario</a>
            </li>
            <li class="nav-item">
              <a class="nav-link " href="../Controlador/controladorInventario.php"><i class="bi bi-file-medical-fill"></i><span>Inicio</span></a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="../Controlador/controladorProveedores.php"><i class="bi bi-file-person"></i><span>Proveedores</span></a>
            </li>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="../Controlador/controladorVentas.php"><i class="bi bi-clipboard2-pulse-fill"></i><span>Registro de ventas</span></a>
            </li>
          </ul>
          <form class="d-flex ms-lg-4">
            <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
            <button style="color: white; background: rgb(49, 44, 44); border: black; border-radius: 50px;" class="btn btn-outline-success" type="submit"><i class="bi bi-search"></i></button>
          </form>
        </div>
      </div>
    </nav>
  </header>


  <div class="container" style="padding: 0 0 50px 0;  font-family: Oswald, sans-serif;">
    <div class="row">
      <!--Inicio Portafolio-->
      <div class="col-md-8">
        <h2 style="font-family: Bebas Neue, sans-serif; padding: 20px 0 0 0; font-size: 60px;">Añadir Producto</h2>
        <p style="font-family: Oswald, sans-serif; font-size: 22px;">En este apartado puedes añadir tu producto para que quede en tu inventario de productos.</p>
        <hr>

        <div class="row">
          <div class="col-md-6" style="border: 2px solid black; border-radius: 10px; ">
            <form action="../Controlador/controladorInventario3.php" method="post" enctype="multipart/form-data" style="padding: 20px;">
              <div class="mb-3">
                <label for="image-preview" class="form-label">Seleccione Imagen:</label>
                <input id="image-preview" type="file" name="foto" accept="image/*" require class="form-control" onchange="previewImage(event)">
              </div>

              <!-- Contenedor para la vista previa de la imagen -->
              <div class="image-preview-wrapper">
                <img id="image-preview-display" class="image-preview" src="" alt="Imagen previa">
              </div>
              <br>
              <div class="correo">
                <input type="text" name="nombreproducto" id="nombreproducto" required>
                <label for="nombreproducto">Nombre Producto</label>
              </div>
              <div class="correo">
                <input type="number" name="precio" id="precio" required step="1"
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
                </script> <label for="precio">Precio</label>
              </div>

              <div class="correo">
                <input type="text" name="color" id="color" required>
                <label for="color">Color</label>
              </div>
              <div class="correo">
                <input type="number" name="cantidadp" id="cantidadp" required step="1"
                  title="Solo se permiten números enteros." oninput="validarLongitud(this)" maxlength="11"
                  inputmode="numeric">
                <label for="cantidadp">Cantidad</label>
              </div>
              <div class="correo">
                <select name="talla" id="talla" required>
                  <option value="" disable>Talla</option>
                  <?php while ($row = mysqli_fetch_assoc($resultado)) { ?>
                    <option value="<?php echo $row['idtalla']; ?>"><?php echo $row['talla']; ?></option>
                  <?php } ?>
                </select>
              </div>

              <div class="correo">
                <input type="number" name="iva" id="iva" required step="1"
                  title="Solo se permiten números enteros." oninput="validarLongitud(this)" maxlength="11"
                  inputmode="numeric">
                <label for="iva">IVA</label>
              </div>
              <div class="correo">
                <select name="categoria" id="categoria" required>
                  <option>Categoría</option>
                  <?php while ($row = mysqli_fetch_assoc($resultadoCategorias)) { ?>
                    <option value="<?php echo $row['idCategoria']; ?>"><?php echo $row['categoria']; ?></option><?php } ?>
                </select>
              </div>
              <div class="correo">
                <select name="estado" id="estado" required>
                  <option>Estado</option>
                  <?php while ($row = mysqli_fetch_assoc($resultadoest)) { ?>
                    <option value="<?php echo $row['idestado']; ?>"><?php echo $row['tiposestados']; ?></option><?php } ?>
                </select>
              </div>

              <div class="correo">
                <input type="date" name="fecha_entrada" id="fecha_entrada" required>
                <label for="fecha_entrada">Fecha</label>
              </div>

              <div class="correo">
                <select name="ProveedoridProveedor" id="ProveedoridProveedor" required>
                  <option value="">Proveedor</option>
                  <?php while ($row = mysqli_fetch_assoc($resultadopro)) { ?>
                    <option value="<?php echo $row['idProveedor']; ?>"><?php echo $row['nombreproveedor']; ?></option>
                  <?php } ?>
                </select>
              </div>

              <button style="background: linear-gradient(70deg, #c24a46, #c2a8a1); padding: 10px; border-radius: 20px;" name="Acciones" value="Crear Producto" type="submit" class="anadirr">
                <i class="bi bi-file-earmark-plus"></i> Añadir
              </button>
            </form>

          </div>
        </div>

      </div>
    </div>
  </div>

  <script>
    // Obtener la fecha actual
    const today = new Date();
    const year = today.getFullYear();
    const month = String(today.getMonth() + 1).padStart(2, '0'); // Mes entre 01 y 12
    const day = String(today.getDate()).padStart(2, '0'); // Día entre 01 y 31

    // Formatear la fecha en el formato YYYY-MM-DD
    const formattedDate = `${year}-${month}-${day}`;

    // Asignar la fecha al input
    document.getElementById('fecha_entrada').value = formattedDate;
  </script>

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

  <script>
    function previewImage(event) {
      var reader = new FileReader();
      reader.onload = function() {
        var output = document.getElementById('image-preview-display');
        output.src = reader.result;
        output.style.display = 'block'; // Muestra la imagen previa
      };
      reader.readAsDataURL(event.target.files[0]);
    }
  </script>



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