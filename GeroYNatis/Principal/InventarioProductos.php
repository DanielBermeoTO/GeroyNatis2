<?php
include_once('../Modelo/Conexion.php');  // Si el archivo está en un directorio superior

// Realiza la conexión
$Conexion = Conectarse();

// Consulta SQL para obtener las tallas
$sqlt = "SELECT idtalla, talla FROM talla";
$resultadot = $Conexion->query($sqlt);

$sqlCategorias = "SELECT idCategoria, categoria FROM categoria";
$resultadoCategorias = $Conexion->query($sqlCategorias);
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
  <link rel="icon" href="./Imagenes/Gero_y_Natis Logo.png" type="image/png">

</head>

<body>
  <header>
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
          <form action="" method="get" class="d-flex ms-lg-4">
            <input class="form-control me-2" type="search" placeholder="Buscar" aria-label="Search" name="busqueda">
            <button style="color: white; background: rgb(49, 44, 44); border: black; border-radius: 50px;" class="btn btn-outline-success" type="submit" name="enviar" value="buscar"><i class="bi bi-search"></i></button>
          </form>
        </div>
      </div>
    </nav>
    <nav class="acciones">
      <a class="aña" href="../Principal/InventarioAnadir.php"><span><i class="bi bi-file-earmark-plus-fill"></i> Añadir Producto</span></a>
    </nav>
  </header>

<br>



  <div class="container" style="padding: 0 0 50px 0;  font-family: Oswald, sans-serif;">
    <div class="row">
    <div class="Ordenado" style="display: flex; justify-content:baseline; gap: 10px; ">
    <div class="ordenar" style="padding: 0 0 0 20px;">
      <select id="options" name="options" style="padding: 5px; border-radius: 5px; border: 1px solid #ccc;">
        <option value="" disabled selected>Ordenar por:</option>
        <option value="opcion1">A-Z</option>
        <option value="opcion2">Z-A</option>
        <option value="opcion3">Mayor precio</option>
        <option value="opcion4">Menor precio</option>
        <option value="opcion5">Inactivo</option>
      </select>
    </div>

    <div class="ordenar">
      <select id="filtrar" name="filtro" style="padding: 5px; border-radius: 5px; border: 1px solid #ccc;">
        <option value="onnn" disabled selected>Filtrar por:</option>
        <option value="opcion1">Lista</option>
        <option value="opcion2">Cajón</option>
      </select>
    </div>
  </div>
      <!--Inicio Portafolio-->
      <div class="col-md-8">
        <h2 style="font-family: Bebas Neue, sans-serif; padding: 20px 0 0 0; font-size: 60px;">Tus Productos <i class="bi bi-file-earmark-diff-fill"></i></h2>
        <p style="font-family: Oswald, sans-serif; font-size: 22px;">En este apartado puedes visualizar tus productos, actualizarlos, eliminarlos o inhabilitarlos.</p>
        <hr>

        <div class="col-md-12">
          <table class="edit table table-responsive">
            <thead>
              <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Cantidad</th>
                <th>Precio</th>
                <th>Color</th>
                <th>Talla</th>
                <th>Categoria</th>
                <th>Estado</th>
                <th>IVA</th>
                <th>Foto</th>
                <th>Editar</th>
              </tr>
            </thead>
            <tbody>
            <?php
// Verificar si se realizó una búsqueda
if (isset($_GET['enviar']) && !empty($_GET['busqueda'])) {
    $busqueda = strtolower(trim($_GET['busqueda'])); // Convierte a minúsculas para hacer la búsqueda insensible a mayúsculas/minúsculas
    $productosFiltrados = array_filter(iterator_to_array($resultado), function($producto) use ($busqueda) {
        return strpos(strtolower($producto['nombreproducto']), $busqueda) !== false;
    });
} else {
    // Si no hay búsqueda, mostrar todos los productos
    $productosFiltrados = iterator_to_array($resultado);
}

// Mostrar los productos
if (count($productosFiltrados) > 0) {
    foreach ($productosFiltrados as $row) {
?>
                <tr>
                  <td><?php echo $row['idProducto']; ?></td>
                  <td><?php echo $row['nombreproducto']; ?></td>
                  <td><?php echo $row['cantidadp']; ?></td>
                  <td>$<?php echo number_format($row['precio']); ?></td>
                  <td><?php echo $row['color']; ?></td>
                  <td><?php echo $row['talla']; ?></td>
                  <td><?php echo $row['categorias']; ?></td>
                  <td><?php echo $row['estado']; ?></td>
                  <td><?php echo $row['iva']; ?>%</td>
                  <td><img src="../Imagenes/<?php echo $row['imagen']; ?> " alt=""></td>
                  <td>
                    <button data-bs-toggle="modal" data-bs-target="#updateModal<?php echo $row['idProducto']; ?>" style="border: none; background: white; color: green;" type="button">
                      <i class="bi bi-cloud-upload"></i> Actualizar
                    </button>
                    <form action="../Controlador/controladorInventario3.php" method="post">
                      <input type="hidden" name="idProducto" value="<?php echo $row['idProducto']; ?>">
                      <button name="Acciones" value="Borrar Producto" style="border: none; background: white; color: red;" type="submit">
                        <i class="bi bi-ban"></i> Inhabilitar
                      </button>
                    </form>
                  </td>
                </tr>

                <!-- Modal para actualizar producto -->
                <!-- Modal para actualizar producto -->
                <div class="modal fade" id="updateModal<?php echo $row['idProducto']; ?>" tabindex="-1" aria-labelledby="updateModalLabel" aria-hidden="true">
                  <div class="modal-dialog" style="border: 2px solid black; border-radius: 10px;">
                    <div class="modal-content" style="padding: 20px; background: linear-gradient(70deg, #c24a46, #c2a8a1);">
                      <div class="modal-header">
                        <h5 class="modal-title" id="updateModalLabel">Actualizar Producto - ID: <?php echo $row['idProducto']; ?></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body">
                        <form action="../Controlador/controladorInventario3.php" method="post" enctype="multipart/form-data">
                          <!-- ID Producto -->
                          <div class="mb-3">
                            <label class="form-label">ID producto</label>
                            <input class="form-control" name="idProducto" type="text" value="<?php echo $row['idProducto']; ?>" readonly>
                          </div>

                          <!-- Nombre -->
                          <div class="mb-3">
                            <label class="form-label">Nombre</label>
                            <input class="form-control" name="nombreproducto" type="text" value="<?php echo $row['nombreproducto']; ?>">
                          </div>

                          <!-- Cantidad -->
                          <div class="mb-3">
                            <label class="form-label">Cantidad</label>
                            <input class="form-control" name="cantidadp" type="number" value="<?php echo $row['cantidadp']; ?>">
                          </div>

                          <!-- Precio -->
                          <div class="mb-3">
                            <label class="form-label">Precio</label>
                            <input class="form-control" name="precio" type="number" value="<?php echo $row['precio']; ?>">
                          </div>

                          <!-- Color -->
                          <div class="mb-3">
                            <label class="form-label">Color</label>
                            <input class="form-control" name="color" type="text" value="<?php echo $row['color']; ?>">
                          </div>

                          <!-- Talla -->
                          <div class="mb-3">
                            <label class="form-label">Talla</label>
                            <select class="form-select" name="talla" required>
                              <option value="1" <?php echo ($row['talla'] == 'XS') ? 'selected' : ''; ?>>XS</option>
                              <option value="2" <?php echo ($row['talla'] == 'S') ? 'selected' : ''; ?>>S</option>
                              <option value="3" <?php echo ($row['talla'] == 'M') ? 'selected' : ''; ?>>M</option>
                              <option value="4" <?php echo ($row['talla'] == 'L') ? 'selected' : ''; ?>>L</option>
                              <option value="5" <?php echo ($row['talla'] == 'XL') ? 'selected' : ''; ?>>XL</option>
                            </select>
                          </div>

                          <!-- Categoría -->
                          <div class="mb-3">
                            <label class="form-label">Categoría</label>
                            <select class="form-select" name="categoria" required>
                              <?php
                              // Reestablecer el puntero de datos para las categorías
                              mysqli_data_seek($resultadoCategorias, 0);

                              // Iterar a través de todas las categorías disponibles
                              while ($jeje = mysqli_fetch_assoc($resultadoCategorias)) {
                                // Verificar si la categoría actual es la que corresponde
                                $selected = ($row['categorias'] == $jeje['categoria']) ? 'selected' : '';
                                echo "<option value=\"{$jeje['idCategoria']}\" $selected>{$jeje['categoria']}</option>";
                              }
                              ?>
                            </select>
                          </div>

                          <!-- Estado -->
                          <div class="mb-3">
                            <label class="form-label">Estado</label>
                            <select class="form-select" name="estado" required>
                              <option value="3" <?php echo ($row['estado'] == 'ACTIVO') ? 'selected' : ''; ?>>ACTIVO</option>
                              <option value="4" <?php echo ($row['estado'] == 'INACTIVO') ? 'selected' : ''; ?>>INACTIVO</option>
                            </select>
                          </div>

                          <!-- IVA -->
                          <div class="mb-3">
                            <label class="form-label">IVA</label>
                            <input class="form-control" name="iva" type="number" value="<?php echo $row['iva']; ?>">
                          </div>

                          <!-- Imagen -->
                          <div class="mb-3">
                            <label class="form-label">Imagen actual</label><br>
                            <img src="<?php echo $row['imagen']; ?>" alt="Imagen actual" style="max-width: 100px;">
                          </div>

                          <div class="mb-3">
                            <label class="form-label">Subir nueva imagen</label>
                            <input class="form-control" name="foto" type="file">
                          </div>

                          <button class="btn btn-warning" type="submit" name="Acciones" value="Actualizar Producto">Actualizar Producto</button>
                        </form>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                      </div>
                    </div>
                  </div>
                </div>


                <?php
    }
} else {
    echo '<p class="text-center">No se encontraron productos que coincidan con tu búsqueda.</p>';
}
?>
            </tbody>
          </table>
        </div>

      </div>
    </div>
  </div>


  <div class="cajon" id="cajonProductos">
    <div class="container" style="font-family: Oswald, sans-serif;">
      <div style="padding: 0 0 50px 0;" class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-4">
        <?php
        mysqli_data_seek($resultado, 0); // Reiniciar el puntero de resultados
        if ($resultado) {
          while ($row = mysqli_fetch_assoc($resultado)) {
            echo '<div class="col">
          <div class="card" style="border: 2px solid black; ">
            <div class="card-header" style="text-align: center; background: linear-gradient(70deg, #c24a46, #c2a8a1); color: black;">' . ($row['idProducto']) . '</div>
            <img src="../Imagenes/' . ($row['imagen']) . '" class="card-img-top mx-auto d-block img-fluid" alt="..." style="margin: 15px 10px 0 10px; width: 200px; height: 220px;">
            <hr>
            <div style="text-align: center; padding: 0 0 20px 0;" class="card-body">
              <p class="card-text-inline"><strong>Nombre: </strong>' . ($row['nombreproducto']) . '</p>
              <p class="card-text-inline"><strong>Cantidad: </strong>' . ($row['cantidadp']) . '</p>
              <p class="card-text-inline"><strong>Precio: </strong>$' . ($row['precio']) . '</p>
              <p class="card-text-inline"><strong>Color: </strong>' . ($row['color']) . '</p>
              <p class="card-text-inline"><strong>Talla: </strong>' . ($row['talla']) . '</p>
              <p class="card-text-inline"><strong>Estado: </strong>' . ($row['estado']) . '</p>
              <p class="card-text-inline"><strong>IVA: </strong>' . ($row['iva']) . '</p>
              <p class="card-text-inline"><strong>Categoria: </strong>' . ($row['categorias']) . '</p>
              <div class="btn-group" role="group" aria-label="Basic outlined example">
                <button data-bs-toggle="modal" data-bs-target="#updateModal' . $row['idProducto'] . '" style="border: none; background: white; color: green;" type="submit">
                <i class="bi bi-cloud-upload"></i> Actualizar
              </button>
 <form action="../Controlador/controladorInventario3.php" method="post">  
               <input type="hidden" name="idProducto" value="' . $row['idProducto'] . '">     
              <button name="Acciones" value="Borrar Producto" style="border: none; background: white; color: red;" type="submit">
                <i class="bi bi-ban"></i> Inhabilitar
              </button>
              </form>       
              </div>
            </div> 
          </div>
        </div>';
          }
        }
        ?>
      </div>

    </div>
  </div>
  </div>


  <script>
    // Obtener elementos del DOM
    const selectFiltro = document.getElementById('filtrar');
    const cajonProductos = document.getElementById('cajonProductos');
    const tablaProductos = document.querySelector('table.edit');

    tablaProductos.style.display = 'table';
    cajonProductos.style.display = 'none';

    // Evento cuando el usuario selecciona una opción del select
    selectFiltro.addEventListener('change', function() {
      if (selectFiltro.value === 'opcion2') {
        // Mostrar el cajón y ocultar la tabla
        cajonProductos.style.display = 'block';
        tablaProductos.style.display = 'none';
      } else {
        // Mostrar la tabla y ocultar el cajón
        cajonProductos.style.display = 'none';
        tablaProductos.style.display = 'table';
      }
    });
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
      /*Esta es una función que se ejecuta cuando se selecciona un archivo en el <input type="file">. El event es el objeto del evento que contiene información sobre el evento, incluyendo el archivo seleccionado.*/
      var reader = new FileReader(); /*Se crea una instancia del objeto FileReader. Este objeto se usa para leer archivos de manera asíncrona. En este caso, lo utilizaremos para leer la imagen seleccionada.*/
      reader.onload = function() {
        /*Aquí se define una función que se ejecutará cuando el FileReader haya terminado de leer el archivo. Esta función es un "event handler" que se activa cuando la lectura del archivo se completa con éxito.*/
        var output = document.getElementById('image-preview'); /*Obtiene una referencia al elemento con el id image-preview. Este es el <img> en el que se mostrará la imagen seleccionada.*/
        output.src = reader.result; /*Establece la propiedad src del elemento <img> con el resultado de la lectura del archivo. reader.result contiene la imagen en formato base64, que puede ser usado directamente como fuente para el <img>.*/
        output.style.display = 'block'; /*Cambia el estilo del elemento <img> a block para hacerlo visible. Este estilo se establece solo después de que la imagen ha sido cargada. Antes de la carga, el <img> tiene display: none;, por lo que no es visible.*/
      };
      reader.readAsDataURL(event.target.files[0]); /*nicia la lectura del archivo seleccionado. event.target.files[0] accede al primer archivo seleccionado por el usuario (en caso de que se permita seleccionar más de un archivo, files sería una lista de archivos). readAsDataURL lee el archivo como una URL de datos base64, que es adecuada para mostrar imágenes en la web.*/
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