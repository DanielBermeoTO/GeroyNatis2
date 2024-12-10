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
        .card {
            background-color: #fff;
            border-radius: 6px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            overflow: hidden;
            margin: 10px auto;
            border: 1px solid black;
        }
        .card-header {
            background-color: #f8f9fa;
            padding: 8px 10px;
            border-bottom: 1px solid #e9ecef;
        }
        .card-title {
            margin: 0;
            font-size: 1em;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .badge {
            font-size: 0.75em;
            padding: 2px 6px;
            border-radius: 10px;
            color: white;
            background-color: #28a745;
        }
        .card-body {
            padding: 10px;
        }
        .card-info {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 5px;
            margin-bottom: 10px;
            font-size: 0.9em;
        }
        .card-total {
            font-size: 1.1em;
            font-weight: bold;
            margin-bottom: 10px;
            text-align: right;
        }
        .product-list {
            border-top: 1px solid #e9ecef;
            padding-top: 10px;
            font-size: 0.9em;
        }
        .product-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }
        .notes {
            font-style: italic;
            margin-top: 10px;
            border-top: 1px solid #e9ecef;
            padding-top: 10px;
            font-size: 0.9em;
        }
        
        /* Responsive design */
        @media (min-width: 768px) {
            .card {
                width: calc(50% - 20px);
                display: inline-block;
                vertical-align: top;
            }
        }
        
        @media (min-width: 1024px) {
            .card {
                width: calc(33.333% - 20px);
            }
        }

  th, td {
    text-align: center;
    vertical-align: middle;
    padding: 10px !important; /* Reduce el espacio entre las celdas */
  }

  .inha {
    font-size: 18px; /* Botones más pequeños */
  }
</style>

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
            <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">Registro de ventas</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="../Controlador/controladorInventario.php"><i class="bi bi-file-medical-fill"></i><span>Inicio</span></a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="../Controlador/controladorInventario2.php"><i class="bi bi-clipboard2-minus-fill"></i><span>Inventario</span></a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="../Controlador/controladorProveedores.php"><i class="bi bi-file-person"></i><span>Proveedores</span></a>
          </li>
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
    <a class="aña" href="../Principal/RegistroVentasAñadir.php"><span><i class="bi bi-bag-plus-fill"></i> Añadir Venta</span></a>
    <a class="aña" href="../Calendario/Calendario.html"><span><i class="bi bi-calendar-week"></i> Calendario de Ventas</span></a>
  </nav>
<br>
  <div class="container" style="padding: 0 0 50px 0;  font-family: Oswald, sans-serif;">
    <div class="row">
  
    <div class="ordenar">
      <select id="filtrar" name="filtro" style="padding: 5px; border-radius: 5px; border: 1px solid #ccc;">
        <option value="onnn" disabled selected>Filtrar por:</option>
        <option value="opcion1">Lista</option>
        <option value="opcion2">Cajón</option>
      </select>
    </div>
  </div>
      <!--Inicio Portafolio-->
      <div class="col-md-10">
        <h2 style="font-family: Bebas Neue, sans-serif; padding: 20px 0 0 0; font-size: 60px;">Tus Ventas <i class="bi bi-credit-card"></i></h2>
        <p style="font-family: Oswald, sans-serif; font-size: 22px;">En este apartado puedes visualizar las ventas realizadas.</p>
        <hr>

        <div class="col-md-12">
          <table class="edit table table-responsive">
            <thead>
              <tr>
                <th>ID</th>
                <th>Fecha</th>
                <th>Productos Vendidos</th>
                <th>LLeva</th>
                <th>Subtotal</th>
                <th>Total</th>
                <th>Estado</th>
                <th>Cliente</th>
                <th>Usuario</th>
              </tr>
            </thead>
            <tbody>
            <?php
// Verificar si se realizó una búsqueda
if (isset($_GET['enviar']) && !empty($_GET['busqueda'])) {
    $busqueda = strtolower(trim($_GET['busqueda'])); // Convierte a minúsculas para hacer la búsqueda insensible a mayúsculas/minúsculas
    $ventasFiltradas = array_filter(iterator_to_array($resultado), function($ventas) use ($busqueda) {
        return strpos(strtolower($ventas['idFactura']), $busqueda) !== false ||
        strpos(strtolower($ventas['fechaventa']), $busqueda) !== false;
    });
} else {
    // Si no hay búsqueda, mostrar todos los productos
    $ventasFiltradas = iterator_to_array($resultado);
}

// Mostrar los productos
if (count($ventasFiltradas) > 0) {
    foreach ($ventasFiltradas as $row) {
?>
<?php
echo 
              '<tr>
                  <td>' . ($row['idFactura']) . '</td>
                  <td>' . ($row['fechaventa']) . '</td>
                  <td>
                      <table class="productos">
                          <thead>
                              <tr>
                                  <th>ID Producto</th>
                                  <th>Producto</th>
                                  <th>Cantidad</th>
                                  <th>Precio</th>
                                  <th>IVA</th>
                              </tr>
                          </thead>
                          <tbody>';
                echo '<tr>';
                $productos = explode(', ', $row['productos']);
                foreach ($productos as $producto) {
                  // Separar los datos de cada producto
                  list($idProducto, $nombreProducto, $cantidad, $precio, $iva, $cliente) = explode(': ', $producto);
                  // Limpiamos los datos
                  $precio = trim($precio); // Para quitar espacios extra, si los hay

                  // Si el precio contiene " $" lo eliminamos
                  if (strpos($precio, '$') !== false) {
                    $precio = str_replace('$', '', $precio);
                  }

                  echo '<td>' . htmlspecialchars($idProducto) . '</td>
          <td>' . htmlspecialchars($nombreProducto) . '</td>
          <td>' . htmlspecialchars($cantidad) . '</td>
          <td>$' . number_format($precio) . '</td>
          <td>' . htmlspecialchars($iva) . '%</td>
          </tr>';
                }


                echo ' </tbody>
                      </table>
                  </td>
                  <td>' . ($row['total_cantidad']) . '</td>
                  <td>$' . number_format($row['subtotal']) . '</td>
                  <td>$' . number_format($row['total']) . '</td>
                  <td>' . ($row['estadi']) . '</td>
          <td>' . htmlspecialchars($cliente) . '</td>

                  <td>' . ($row['usuario']) . ' - '. ($row['nombre']) . ' '. ($row['apellido']) . '</td>
              </tr>';
              ?>
              <?php
              }}
              ?>
          </table>
        </div>
      </div>
    </div>
  </div>

<div class="cajon" id="cajonProductos">
<div class="container" style="font-family: Oswald, sans-serif;">
<div style="padding: 0 0 50px 0;" class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-4">
<?php
// Reiniciar el puntero de resultados para reutilizarlo
mysqli_data_seek($resultado, 0);

if ($resultado) {
    while ($row = mysqli_fetch_assoc($resultado)) {
        // Desglosar productos vendidos
        $productosHtml = '';
        $cliente = ''; // Variable para almacenar al cliente
        $productos = explode(', ', $row['productos']); // Dividimos los productos
        
        foreach ($productos as $index => $producto) {
            // Separar los datos de cada producto
            list($idProducto, $nombreProducto, $cantidad, $precio, $iva, $clienteDato) = explode(': ', $producto);

            // Almacenar el cliente solo una vez (del primer producto, por ejemplo)
            if ($index === 0) {
                $cliente = htmlspecialchars(trim($clienteDato)); // Asignar el cliente
            }

            // Construir HTML para el producto
            $productosHtml .= '
                <div class="product-item">
                    <span>' . htmlspecialchars($idProducto) . ' - ' . htmlspecialchars($nombreProducto) . ' (' . htmlspecialchars($cantidad) . ')</span>
                    <span>$' . number_format(trim(str_replace('$', '', $precio))) . '</span>
                </div>';
        }

        // Generar tarjeta
        echo '
        <div class="card">
            <div class="card-header" style="   background: linear-gradient(70deg, #bd5c57, #c2a8a1); ">
                <h2 class="card-title">
                    Factura ' . htmlspecialchars($row['idFactura']) . '
                    <span class="badge">' . htmlspecialchars($row['estadi']) . '</span>
                </h2>
            </div>
            
            <div class="card-body">
                <div class="card-info">
                    <div>📅 Fecha: ' . htmlspecialchars($row['fechaventa']) . '</div>
                    <div>👤 Cliente: ' . $cliente . '</div> <!-- Mostrar cliente correctamente -->
                    <div>👨‍💼 Vendedor: ' . htmlspecialchars($row['nombre']) . ' ' . htmlspecialchars($row['apellido']) . '</div>
                </div>
                <div class="card-total">Subtotal: $' . number_format($row['subtotal']) . '</div>
                <div class="card-total">Total: $' . number_format($row['total']) . '</div>
                <div class="product-list">
                    ' . $productosHtml . '
                </div>
                <div class="notes" style="  display: flex;  justify-content: space-between;  text-align: center;">
 <form action="../Controlador/controladorVentas.php" method="post">
<input type="hidden" name="idFactura" value="' . $row['idFactura'] . '">
    <button name="Acciones" value="Pago" style="border: none; background: white; color: green;" type="submit" class="inha">
        <i class="bi bi-cash"></i> PAGO
    </button>
</form>
<form action="../Controlador/controladorVentas.php" method="post">
<input type="hidden" name="idFactura" value="' . $row['idFactura'] . '">
    <button name="Acciones" value="No Pago" style="border: none; background: white; color: orange;" type="submit" class="inha">
        <i class="bi bi-ban"></i> NO PAGO
    </button>
</form>                </div>
            </div>
        </div>';
    }
}
?>

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