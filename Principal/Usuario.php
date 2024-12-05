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
    .modal-body-custom h1 {
    font-size: 40px !important;
}

.modal-body-custom p {
    font-size: 20px !important;
    font-family: 'Oswald', sans-serif !important;
    color: black;
}

  .card {
            width: 300px;
            height: 300px;
            border-radius: 50%;
            overflow: hidden;
            font-family: Oswald, sans-serif;
            background: linear-gradient(70deg, #86d392, #d4d3d2);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: black;
            text-align: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            position: relative;
            border: 2px black solid;
        }
        .avatar-container {
            position: relative;
            margin-bottom: 20px;
        }
        .avatar {
            width: 80px;
            height: 80px;
            border: 2px black solid;
            border-radius: 50%;
            background-color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 40px;
            color: #3db5b9;
        }
        .role {
            position: absolute;
            bottom: -20px;
            left: 50%;
            transform: translateX(-50%);
            font-size: 12px;
            color: white;
            background-color: rgba(0, 0, 0, 0.5);
            padding: 2px 8px;
            border-radius: 10px;
        }
        .user-info {
            margin-bottom: 15px;
        }
        h2 {
            margin: 0;
            font-size: 18px;
        }
        p {
            margin: 5px 0;
            font-size: 14px;
            opacity: 0.8;
        }
        .buttons {
            display: flex;
            gap: 10px;
        }
        .btn {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: none;
            background-color: rgba(255, 255, 255, 0.3);
            color: black;
            font-size: 18px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .btn:hover {
            background-color: rgba(255, 255, 255, 0.5);
        }

        .text-success-custom {
    color: green !important;
}

.text-danger-custom {
    color: red !important;
}

    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="nav-link" style="color: black;" href="../Controlador/controladorInventario.php"><i class="bi bi-box-arrow-left"></i></a>
            <img src="../Imagenes/Gero_y_Natis Logo.png" alt="" width="150" height="150">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-center" id="navbarSupportedContent">
                <ul class="navbar-nav flex-column mb-2 mb-lg-0">
                    <h1>Gestión de Usuarios</h1>
                </ul>

            </div>
        </div>
    </nav>
        <nav class="acciones">
      <a class="aña" href="../Sesiones/Registrarse.php"><i class="bi bi-person-fill-add"></i> Crear Usuario</a>
    </nav>
    <div class="container">
    <h2 style="font-family: Bebas Neue, sans-serif; padding: 20px 0 0 0; font-size: 60px;">
        Usuarios <i class="bi bi-person-arms-up"></i>
    </h2>
    <p style="font-family: Oswald, sans-serif; font-size: 22px;">
        Explora y administra tu base de usuarios de manera eficiente 
    </p>
    <hr>
    <div style="padding: 30px 0 50px 0;" class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-4">
        <?php while ($row = mysqli_fetch_assoc($resultado)) { ?>
            <div class="card">
                <div class="avatar-container">
                    <div class="avatar"><i class="bi bi-person-circle"></i></div>
                    <div class="role"><?php echo $row['idrol']; ?></div>
                </div>
                <div class="user-info">
                    <h2><?php echo $row['nombre'] . ' ' . $row['apellido']; ?></h2>
                    <p>DNI: <?php echo $row['documento']; ?></p>
                    <p><?php echo $row['correo']; ?></p>
                </div>
                <div class="buttons">
                <button  class="btn"  title="Actualizar Usuario" data-bs-toggle="modal" data-bs-target="#updateModal<?php echo $row['documento']; ?>" type="button">
                <i class="bi bi-pen"></i></button>
                    <form action="../Controlador/controladorUsuario.php" method="post">
                        <input type="hidden" name="documento" value="<?php echo $row['documento']; ?>">
                        <button class="btn" title="Inhabilitar Usuario" name="Acciones" value="Borrar Usuario" type="submit">
                            <i class="bi bi-slash-circle"></i>
                        </button>
                    </form>
                    <button type="button" title="Visualizar usuario" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal<?php echo $row['documento']; ?>">
                        <i class="bi bi-eye"></i>
                    </button>
                </div>
            </div>

            <!-- Modal -->
            <div class="modal fade" id="exampleModal<?php echo $row['documento']; ?>" tabindex="-1" aria-labelledby="exampleModalLabel<?php echo $row['documento']; ?>" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 style="font-family: Bebas Neue, sans-serif;font-size: 100px;" class="modal-title fs-5" id="exampleModalLabel<?php echo $row['documento']; ?>"><i class="bi bi-person-arms-up"></i> Información</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body modal-body-custom">
                            <h1><?php echo $row['nombre'] . ' ' . $row['apellido']; ?></h1>
                            <p><strong>Documento de Identidad:</strong> <?php echo $row['tipoDocumento'] .' '. $row['documento']; ?></p>
                            <p><strong>Dirección:</strong> <?php echo $row['direccion']; ?></p>
                            <p><strong>Localidad:</strong> <?php echo $row['localidad']; ?></p>
                            <p><strong>Teléfono:</strong> <?php echo $row['telefono']; ?></p>
                            <p><strong>Correo:</strong> <?php echo $row['correo']; ?></p>
                            <p><strong>Estado:</strong> <?php echo $row['idestado']; ?></p>
                            <p><strong>Rol:</strong> <?php echo $row['idrol']; ?></p>
                        </div>
                    </div>
                </div>
            </div>

 <!-- Modal para actualizar producto -->
<div class="modal fade" id="updateModal<?php echo $row['documento']; ?>" tabindex="-1" aria-labelledby="updateModalLabel" aria-hidden="true">
  <div class="modal-dialog" style="border: 2px solid black; border-radius: 10px;">
    <div class="modal-content" style="padding: 20px; background: linear-gradient(70deg, #c24a46, #c2a8a1);">
      <div class="modal-header">
        <h5 class="modal-title" id="updateModalLabel">Actualizar Usuario - DNI: <?php echo $row['documento']; ?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="../Controlador/controladorUsuario.php" method="post" enctype="multipart/form-data">
          <div class="mb-3">
            <label class="form-label">Número de documento</label>
            <input class="form-control" name="documento" type="number" value="<?php echo $row['documento']; ?>" readonly>
          </div>
          <div class="mb-3">
            <label class="form-label">Nombre</label>
            <input class="form-control" name="nombre" type="text" value="<?php echo $row['nombre']; ?>">
          </div>
          <div class="mb-3">
            <label class="form-label">Apellido</label>
            <input class="form-control" name="apellido" type="text" value="<?php echo $row['apellido']; ?>">
          </div>
          <div class="mb-3">
            <label class="form-label">Dirección</label>
            <input class="form-control" name="direccion" type="text" value="<?php echo $row['direccion']; ?>">
          </div>

          <div class="mb-3">
            <label class="form-label">Localidad</label>
            <select class="form-select" name="localidad" required>
                <option value="" disabled <?php echo empty($row['localidad']) ? 'selected' : ''; ?>>Seleccione una localidad</option>
                <?php
                $localidades = [
                    'La Candelaria',
                    'Los Mártires',
                    'Santa Fe',
                    'San Cristóbal',
                    'Usaquén',
                    'Chapinero',
                    'Teusaquillo',
                    'Fontibón',
                    'Engativá',
                    'Suba',
                    'Barrios Unidos',
                    'Tunjuelito',
                    'Sumapaz',
                    'Bosa',
                    'Kennedy',
                    'Antonio Nariño',
                    'Rafael Uribe Uribe',
                    'Ciudad Bolívar',
                    'San Andrés',
                ];

                foreach ($localidades as $localidad) {
                    $selected = ($row['localidad'] == $localidad) ? 'selected' : '';
                    echo "<option value=\"{$localidad}\" $selected>{$localidad}</option>";
                }
                ?>
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label">Teléfono</label>
            <input class="form-control" name="telefono" type="number" value="<?php echo $row['telefono']; ?>">
          </div>

          <div class="mb-3">
            <label class="form-label">Correo</label>
            <input class="form-control" name="correo" type="email" value="<?php echo $row['correo']; ?>">
          </div>

          <div class="mb-3">
            <label class="form-label">Estado</label>
            <select name="estado" id="estado">
                <option value="3" <?php echo ($row['idestado'] == 'ACTIVO') ? 'selected' : ''; ?>>ACTIVO</option>
                <option value="4" <?php echo ($row['idestado'] == 'INACTIVO') ? 'selected' : ''; ?>>INACTIVO</option>
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label">Rol</label>
            <select name="idrol" id="idrol">
                <option value="1" <?php echo ($row['idrol'] == 'Administrador') ? 'selected' : ''; ?>>Administrador</option>
                <option value="2" <?php echo ($row['idrol'] == 'Vendedor') ? 'selected' : ''; ?>>Vendedor</option>
            </select>
          </div>

          <button class="btn btn-warning" type="submit" name="Acciones" value="Actualizar Usuario"><i class="bi bi-upload"></i> Actualizar</button>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-arrow-bar-left"></i></button>
      </div>
    </div>
  </div>
</div>
<?php } ?>

        <!-- Tarjeta roja que trae otra consulta -->
        <?php 
        while ($rowi = mysqli_fetch_assoc($usuariosi)) { ?>
            <div class="card" style="  background: linear-gradient(70deg, #d4d3d2, #b93f39) !important; color: black;">
                <div class="avatar-container">
                    <div class="avatar"><i class="bi bi-ban"></i></div>
                    <div class="role"><?php echo $rowi['idrol']; ?></div>
                </div>
                <div class="user-info">
                    <h2><?php echo $rowi['nombre'].' '.$rowi['apellido']; ?></h2> <!-- Reemplaza campo1 con el nombre real del campo -->
                    <p>DNI: <?php echo $rowi['documento']; ?></p> <!-- Reemplaza campo2 con el nombre real del campo -->
                    <p><?php echo $rowi['correo']; ?></p> <!-- Reemplaza campo3 con el nombre real del campo -->
                </div>
                <div class="buttons">
                <form action="../Controlador/controladorUsuario.php" method="post">
                        <input type="hidden" name="documento" value="<?php echo $rowi['documento']; ?>">
                        <button style="color: green;" class="btn" title="Activar Usuario" name="Acciones" value="Activar Usuario" type="submit">
                        <i class="bi bi-check-circle"></i>                        </button>
                    </form>
                    <button type="button" title="Visualizar usuario" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal<?php echo $rowi['documento']; ?>">
                        <i class="bi bi-eye"></i>
                    </button>
                </div>
            </div>
              <!-- Modal -->
              <div class="modal fade" id="exampleModal<?php echo $rowi['documento']; ?>" tabindex="-1" aria-labelledby="exampleModalLabel<?php echo $rowi['documento']; ?>" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 style="font-family: Bebas Neue, sans-serif;font-size: 100px;" class="modal-title fs-5" id="exampleModalLabel<?php echo $rowi['documento']; ?>"><i class="bi bi-person-arms-up"></i> Información</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body modal-body-custom">
                            <h1><?php echo $rowi['nombre'] . ' ' . $rowi['apellido']; ?></h1>
                            <p><strong>Documento de Identidad:</strong> <?php echo $rowi['tipoDocumento'] .' '. $rowi['documento']; ?></p>
                            <p><strong>Dirección:</strong> <?php echo $rowi['direccion']; ?></p>
                            <p><strong>Localidad:</strong> <?php echo $rowi['localidad']; ?></p>
                            <p><strong>Teléfono:</strong> <?php echo $rowi['telefono']; ?></p>
                            <p><strong>Correo:</strong> <?php echo $rowi['correo']; ?></p>
                            <p><strong>Estado:</strong> <?php echo $rowi['idestado']; ?></p>
                            <p><strong>Rol:</strong> <?php echo $rowi['idrol']; ?></p>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</div>



  <!--Fin Inventario-->


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



