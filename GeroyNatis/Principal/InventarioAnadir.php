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

$sqlest = "SELECT idestado, tiposestados FROM estados WHERE idestado = 3 OR idestado = 4;";
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

   <style>
        .image-preview {
            width: 100%;
            height: 150px;
            border: 2px dashed #ddd;
            border-radius: 5px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 15px;
            overflow: hidden;
            position: relative;
            Cursor: pointer;
        }
        
        .image-preview img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }
        
        .image-preview-text {
            position: absolute;
            z-index: 1;
        }
        
        .image-preview.has-image .image-preview-text {
            display: none;
        }
        
        .form-label.required:after {
            content: " *";
            color: red;
        }
        
        #imageInput {
            display: none;
        }
    </style>
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
          <div class="col-md-12" style="border: 2px solid black; border-radius: 10px; ">
<form action="../Controlador/controladorInventario3.php" method="post" enctype="multipart/form-data" style="padding: 20px;" id="productForm" class="needs-validation" novalidate>
            <!-- Imagen del producto -->
            <div class="mb-3">
                <label for="imageInput" class="form-label required">Imagen del Producto</label>
                <div class="image-preview" id="imagePreview">
                    <span class="image-preview-text">Haga clic para seleccionar una imagen</span>
                    <img src="/placeholder.svg" alt="Vista previa de imagen" id="previewImg" style="display: none;">
                </div>
                <input type="file" class="form-control" name="foto" id="imageInput" accept="image/*" required>
                <div class="invalid-feedback">
                    Por favor seleccione una imagen para el producto.
                </div>
            </div>
            
            <div class="row">
                <!-- Nombre del producto -->
                <div class="col-md-6 mb-2">
                  <div class="correo">
                <input type="text" name="nombreproducto" id="nombreproducto" required>
                <label for="nombreproducto">Nombre Producto</label>
              
                    <div class="invalid-feedback">
                        Por favor ingrese el nombre del producto.
                    </div>
                </div>
                </div>
                
                <!-- Precio unitario -->
                <div class="col-md-3 mb-2">
                  <div class="correo">
                <input type="number" name="precio" id="precio" required step="1"
                  title="Solo se permiten números enteros." oninput="validarLongitud(this)" maxlength="7"
                  inputmode="numeric">
                <script>
                  function validarLongitud(input) {
                    input.value = input.value.replace(/\D/g, '');
                    // Limitar a 11 dígitos
                    if (input.value.length > 11) {
                      input.value = input.value.slice(0, 11);
                    }
                  }
                </script> <label for="precio">Precio Unitario</label>
                <div class="invalid-feedback">
                            Ingrese el precio unitario.
                        </div>
              </div>
                </div>
                
                <!-- Precio proveedor -->
                <div class="col-md-3 mb-2">
                     <div class="correo">
              <input type="number" name="precioproveedor" id="precioproveedor" required step="1"
                  title="Solo se permiten números enteros." oninput="validarLongitud(this)" maxlength="11"
                  inputmode="numeric">
                  <label for="nombreproducto">Precio Proveedor</label>
              
                        <div class="invalid-feedback">
                            Ingrese el precio del proveedor.
                        </div>
                        
                    </div>
                </div>
            </div>
            
            <div class="row">
                <!-- Categoría -->
                <div class="col-md-4 mb-2">
                    <label for="category" class="form-label required">Categoría</label>
                    <select class="form-select" name="categoria" id="category" required>
                        <option value="" selected disabled>Seleccione una categoría</option>
                         <?php while ($row = mysqli_fetch_assoc($resultadoCategorias)) { ?>
                    <option value="<?php echo $row['idCategoria']; ?>"><?php echo $row['categoria']; ?></option><?php } ?>
                    </select>
                    <div class="invalid-feedback">
                        Por favor seleccione una categoría.
                    </div>
                </div>
                
                <!-- Estado -->
                <div class="col-md-4 mb-2">
                    <label for="status" class="form-label required">Estado</label>
                    <select class="form-select" name="estado" id="estado" required>
                        <option value="" selected disabled>Seleccione un estado</option>
                        <?php while ($row = mysqli_fetch_assoc($resultadoest)) { ?>
                    <option value="<?php echo $row['idestado']; ?>"><?php echo $row['tiposestados']; ?></option><?php } ?>
                    </select>
                    <div class="invalid-feedback">
                        Por favor seleccione un estado.
                    </div>
                </div>
                
                <!-- Proveedor -->
                <div class="col-md-4 mb-2">
                    <label for="supplier" class="form-label required">Proveedor</label>
                    <select class="form-select" name="ProveedoridProveedor" id="ProveedoridProveedor" required>
                        <option value="" selected disabled>Seleccione un proveedor</option>
                        <?php while ($row = mysqli_fetch_assoc($resultadopro)) { ?>
                    <option value="<?php echo $row['idProveedor']; ?>"><?php echo $row['nombreproveedor']; ?></option>
                  <?php } ?>
                    </select>
                    <div class="invalid-feedback">
                        Por favor seleccione un proveedor.
                    </div>
                </div>
            </div>
            
            <!-- Fecha -->
            <div class="mb-3">
              <div class="correo">
                <input type="date" name="fecha_entrada" id="fecha_entrada" required>
                <label for="fecha_entrada">Fecha</label>
                <div class="invalid-feedback">
                    Por favor seleccione una fecha.
                </div>
                              </div>
            </div>
            
            <!-- Sección de tallas -->
            <h5 class="mt-3 mb-2">Inventario por Tallas</h5>

            <div class="row row-cols-2 row-cols-md-3 g-2 mb-3">
                <!-- XS -->
                <div class="col">
                    <div class="card h-100">
                        <div class="card-header py-1 bg-light">
                            <h6 class="card-title mb-0">Talla XS</h6>
                        </div>
                        <div class="card-body p-2">
                            <input type="hidden" name="talla[]" value="1">
                            <div class="mb-2">
                                <label for="quantityXS" class="form-label required">Cantidad</label>
                                <input type="number" class="form-control" id="quantityXS" name="cantidad[]" min="0" required>                                
                                <div class="invalid-feedback">
                                    Por favor ingrese la cantidad.
                                </div>
                            </div>
                            <div class="mb-0">
                                <label for="colorXS" class="form-label required">Color</label>
                                  <input type="text" class="form-control" id="colorXS" name="color[]" required>  
                                <div class="invalid-feedback">
                                    Por favor ingrese el color.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- S -->
                <div class="col">
                    <div class="card h-100">
                        <div class="card-header py-1 bg-light">
                            <h6 class="card-title mb-0">Talla S</h6>
                        </div>
                        <div class="card-body p-2">
                            <input type="hidden" name="talla[]" value="2">
                            <div class="mb-2">
                                <label for="quantityS" class="form-label required">Cantidad</label>
                                <input type="number" class="form-control" id="quantityS" name="cantidad[]" min="0" required>
                                <div class="invalid-feedback">
                                    Por favor ingrese la cantidad.
                                </div>
                            </div>
                            <div class="mb-0">
                                <label for="colorS" class="form-label required">Color</label>
                                  <input type="text" class="form-control" id="colorS" name="color[]" required>  
                                <div class="invalid-feedback">
                                    Por favor ingrese el color.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- M -->
                <div class="col">
                    <div class="card h-100">
                        <div class="card-header py-1 bg-light">
                            <h6 class="card-title mb-0">Talla M</h6>
                        </div>
                        <div class="card-body p-2">
                       <input type="hidden" name="talla[]" value="3">
                            <div class="mb-2">
                                <label for="quantityM" class="form-label required">Cantidad</label>
                                <input type="number" class="form-control" id="quantityM" name="cantidad[]" min="0" required>
                                <div class="invalid-feedback">
                                    Por favor ingrese la cantidad.
                                </div>
                            </div>
                            <div class="mb-0">
                                <label for="colorM" class="form-label required">Color</label>
                                  <input type="text" class="form-control" id="colorM" name="color[]" required>  
                                <div class="invalid-feedback">
                                    Por favor ingrese el color.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- L -->
                <div class="col">
                    <div class="card h-100">
                        <div class="card-header py-1 bg-light">
                            <h6 class="card-title mb-0">Talla L</h6>
                        </div>
                        <div class="card-body p-2">
                            <input type="hidden" name="talla[]" value="4">
                            <div class="mb-2">
                                <label for="quantityL" class="form-label required">Cantidad</label>
                                <input type="number" class="form-control" id="quantityL" name="cantidad[]" min="0" required> 
                                <div class="invalid-feedback">
                                    Por favor ingrese la cantidad.
                                </div>
                            </div>
                            <div class="mb-0">
                                <label for="colorL" class="form-label required">Color</label>
                                  <input type="text" class="form-control" id="colorL" name="color[]" required>  
                                <div class="invalid-feedback">
                                    Por favor ingrese el color.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- XL -->
                <div class="col">
                    <div class="card h-100">
                        <div class="card-header py-1 bg-light">
                            <h6 class="card-title mb-0">Talla XL</h6>
                        </div>
                        <div class="card-body p-2">
                            <input type="hidden" name="talla[]" value="5">

                            <div class="mb-2">
                                <label for="quantityXL" class="form-label required">Cantidad</label>
                                <input type="number" class="form-control" id="quantityXL" name="cantidad[]" min="0" required>                                
                                <div class="invalid-feedback">
                                    Por favor ingrese la cantidad.
                                </div>
                            </div>
                            <div class="mb-0">
                                <label for="colorXL" class="form-label required">Color</label>
                                  <input type="text" class="form-control" id="colorXL" name="color[]" required>  
                                <div class="invalid-feedback">
                                    Por favor ingrese el color.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="d-grid gap-2 mt-3">
<button style="background: linear-gradient(70deg, #c24a46, #c2a8a1); padding: 10px; border-radius: 20px;" name="Acciones" value="Crear Producto" type="submit" class="anadirr">
                <i class="bi bi-file-earmark-plus"></i> Añadir
              </button>            </div>
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

   <script>
        // Manejar la vista previa de la imagen
        const imagePreview = document.getElementById('imagePreview');
        const imageInput = document.getElementById('imageInput');
        const previewImg = document.getElementById('previewImg');
        
        // Cuando se hace clic en el área de vista previa, activar el input de archivo
        imagePreview.addEventListener('click', function() {
            imageInput.click();
        });
        
        // Cuando se selecciona un archivo, mostrar la vista previa
        imageInput.addEventListener('change', function() {
            const file = this.files[0];
            
            if (file) {
                const reader = new FileReader();
                
                reader.addEventListener('load', function() {
                    previewImg.src = reader.result;
                    previewImg.style.display = 'block';
                    imagePreview.classList.add('has-image');
                });
                
                reader.readAsDataURL(file);
            } else {
                previewImg.src = '';
                previewImg.style.display = 'none';
                imagePreview.classList.remove('has-image');
            }
        });
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