<?php
include "../Modelo/Conexion.php";
$Conexion = Conectarse();
$sqlest = "SELECT idestado, tiposestados FROM estados";
$resultadoest = $Conexion->query($sqlest);

$sqlp = "SELECT `idProducto`,`nombreproducto`, precio FROM producto WHERE id_estado=3;";
$resultap = $Conexion->query($sqlp);
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
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

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
        <a class="nav-link" style="color: black;" href="../Controlador/controladorVentas.php"><i
            class="bi bi-box-arrow-left"></i></a>
        <a class="navbar-brand" href="#">
          <img src="../Imagenes/Gero_y_Natis Logo.png" alt="" width="150" height="150">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
          aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-center" id="navbarSupportedContent">
          <ul class="navbar-nav  mb-2 mb-lg-0">
            <li class="nav-item">
              <a class="nav-link disabled" href="../Controlador/controladorVentas.php" tabindex="-1" aria-disabled="true">Registro de
                ventas</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="../Controlador/controladorInventario.php"><i class="bi bi-file-medical-fill"></i><span>Inicio</span></a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="../Controlador/controladorInventario2.php"><i
                  class="bi bi-clipboard2-minus-fill"></i><span>Inventario</span></a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="../Controlador/controladorProveedores.php"><i
                  class="bi bi-file-person"></i><span>Proveedores</span></a>
            </li>
          </ul>
          <form action="" method="get" class="d-flex ms-lg-4">
            <input class="form-control me-2" type="search" placeholder="Buscar" aria-label="Search" name="busqueda">
            <button style="color: white; background: rgb(49, 44, 44); border: black; border-radius: 50px;" class="btn btn-outline-success" type="submit" name="enviar" value="buscar"><i class="bi bi-search"></i></button>
          </form>
        </div>
      </div>
    </nav>
  </header>

  <?php
              if (isset($_GET['message'])) {
                // Definir clases y mensajes según el tipo
                $alertClass = 'alert-danger'; // Por defecto, alerta de error
                $icon = '<i class="bi bi-exclamation-triangle-fill"></i>'; // Ícono de error
                $messageText = 'Algo salió mal, intenta de nuevo'; // Mensaje por defecto

                // Evaluar el mensaje recibido
                switch ($_GET['message']) {
                  case 'pocosproductos':
                    $messageText = 'No hay suficientes productos en el inventario para hacer la venta';
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

  <div class="container" style="padding: 0 0 50px 0;  font-family: Oswald, sans-serif;">
    <div class="row">
      <!--Inicio Portafolio-->
      <div class="col-md-8">
        <h2 style="font-family: Bebas Neue, sans-serif; padding: 20px 0 0 0; font-size: 60px;">Añadir Venta</h2>
        <p style="font-family: Oswald, sans-serif; font-size: 22px;">En este apartado puedes añadir una venta.</p>
        <hr>
        <div class="row">
          <div class="col-md-6" style="border: 2px solid black; border-radius: 10px; ">
            <form action="../Controlador/controladorVentas.php" class="anadir" method="post" id="product-form">
              <div id="product-list" style="padding: 20px;">
                <div class="correo">
                  <input type="date" name="fechaventa" id="fechaventa" required>
                  <label for="fechaventa">Fecha</label>
                </div>
                <div class="product-item" style="display: flex; gap: 20px;">
                  <div class="correo">
                    <select name="idProducto[]" class="idProducto" required>
                      <option value="">Producto</option>
                      <?php while ($rowe = mysqli_fetch_assoc($resultap)) { ?>
                        <option value="<?php echo $rowe['idProducto']; ?>" data-precio="<?php echo $rowe['precio']; ?>">
                          <?php echo $rowe['idProducto'] . ' ' . $rowe['nombreproducto'] . ' | $' . number_format($rowe['precio']); ?>
                        </option>
                      <?php } ?>
                    </select>
                  </div>
                  <div class="correo" style="flex: 1;">
                    <input type="number" style="width: 100%;" name="cantidad[]" required step="1"
                      title="Solo se permiten números enteros." oninput="validarLongitudt(this)" maxlength="11"
                      inputmode="numeric">
                    <script>
                    function validarLongitudt(input) {
                      input.value = input.value.replace(/\D/g, ''); // Elimina caracteres no numéricos
                      if (input.value.length < 0) {
                      input.setCustomValidity('El número de cantidad debe tener al menos 1 dígito.');
                      } else if (input.value.length > 3) {
                      input.value = input.value.slice(0, 3); // Limita a 11 dígitos
                      input.setCustomValidity('El número de cantidad no debe tener más de 3 dígitos.');
                      } else {
                      input.setCustomValidity(''); // La validación es exitosa
                      }
                      }              
                    </script>
                    <label for="">Cantidad</label>
                  </div>
                  <input type="hidden" name="valorunitario[]" class="valorunitario">
                </div>
              </div>

              <div class="jepo">
                <button type="button" id="add-product">Agregar Otro Producto</button>
              </div>

              <div class="correo">
                <input type="number" name="cliente" required step="1"
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
                <label for="">Documento Cliente</label>
              </div>

              <div class="correo">
                <select name="id_estadof" id="id_estadof" required>
                  <option value="">Estado</option>
                  <?php while ($row = mysqli_fetch_assoc($resultadoest)) { ?>
                    <option value="<?php echo $row['idestado']; ?>"><?php echo $row['tiposestados']; ?></option>
                  <?php } ?>
                </select>
              </div>

              <div class="botones" style="padding: 0 0 30px 0;">
                <button type="submit" name="Acciones" value="Crear Venta">Añadir</button>
                <button type="reset">Borrar</button>
              </div>
            </form>

            <script>
              // Cuando se seleccione un producto, actualizar el valor unitario correspondiente
              document.addEventListener('change', function(event) {
                if (event.target.classList.contains('idProducto')) {
                  const selectedOption = event.target.options[event.target.selectedIndex];
                  const precio = selectedOption.dataset.precio;
                  const valorUnitarioInput = event.target.closest('.product-item').querySelector('.valorunitario');
                  valorUnitarioInput.value = precio;
                }
              });

              document.getElementById('add-product').addEventListener('click', function() {
                // Crear un nuevo elemento para el producto
                const productItem = document.createElement('div');
                productItem.classList.add('product-item');
                productItem.style.display = 'flex';
                productItem.style.gap = '20px';

                // Contenido del nuevo producto, incluyendo el select para elegir el producto
                productItem.innerHTML = `
            <div class="correo">
                <select name="idProducto[]" class="idProducto" required>
                    <option value="">Producto</option>
                    <?php
                    // Asegúrate de que $resultap esté definido antes de usarlo aquí
                    $resultap = $Conexion->query($sqlp);
                    while ($rowe = mysqli_fetch_assoc($resultap)) { ?>
                            <option value="<?php echo $rowe['idProducto']; ?>" data-precio="<?php echo $rowe['precio']; ?>">
                                <?php echo $rowe['idProducto'] . ' ' . $rowe['nombreproducto'] . ' | $' . number_format($rowe['precio']); ?>
                            </option>
                    <?php } ?>
                </select>
            </div>
            <div class="correo" style="flex: 1;">
                <input type="number" style="width: 100%;" name="cantidad[]" required>
                <label for="">Cantidad</label>
            </div>
            <input type="hidden" name="valorunitario[]" class="valorunitario">
        `;

                // Añadir el nuevo producto a la lista
                document.getElementById('product-list').appendChild(productItem);
              });
            </script>

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
          document.getElementById('fechaventa').value = formattedDate;
        </script>


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