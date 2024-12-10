<?php
include_once("../Modelo/Conexion.php");
$conexion = Conectarse();
$sql = "SELECT idProducto, nombreproducto FROM producto";
$resultadosql = $conexion->query($sql);
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
        <a class="nav-link" style="color: black;" href="../Controlador/controladorProveedores.php"><i class="bi bi-box-arrow-left"></i></a>
        <a class="navbar-brand" href="#">
          <img src="../Imagenes/Gero_y_Natis Logo.png" alt="" width="150" height="150">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-center" id="navbarSupportedContent">
          <ul class="navbar-nav  mb-2 mb-lg-0">
            <li class="nav-item">
              <a class="nav-link disabled" href="" tabindex="-1" aria-disabled="true">Proveedores</a>
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
            <input class="form-control me-2" type="search" placeholder="Buscar" aria-label="Search">
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
        <h2 style="font-family: Bebas Neue, sans-serif; padding: 20px 0 0 0; font-size: 60px;">Añadir Proveedor</h2>
        <p style="font-family: Oswald, sans-serif; font-size: 22px;">En este apartado puedes añadir tu proveedor de confianza.</p>
        <hr>

        <div class="row">
          <div class="col-md-6" style="border: 2px solid black; border-radius: 10px; ">
            <form action="../Controlador/controladorProveedores.php" class="anadir" method="post" style="padding: 20px;">
              <div class="correo"><input type="text" name="nombreproveedor" required><label for="">Nombre Proveedor</label></div>
              <div class="correo"><input type="text" name="Telefono" required><label>Teléfono</label></div>
              <div class="correo">
                <select name="productos" required>
                  <option value="">Producto que provee</option>
                  <?php while ($row = mysqli_fetch_assoc($resultadosql)) { ?>
                    <option value="<?php echo $row['idProducto']; ?>"><?php echo $row['idProducto'];
                                                                      echo '-';
                                                                      echo $row['nombreproducto']; ?></option><?php } ?>
                </select>
              </div>
              <button name="Acciones" value="Crear Proveedor" style="background: linear-gradient(70deg, #c24a46, #c2a8a1); padding: 10px; border-radius: 20px;" type="submit" class="anadirr"><i class="bi bi-file-earmark-plus"></i> Añadir</button>
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