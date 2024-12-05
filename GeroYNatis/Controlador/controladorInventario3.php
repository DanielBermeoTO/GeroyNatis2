<?php
require_once "../Modelo/Producto.php";

$producto = new Producto();
$elegirAcciones = isset($_POST['Acciones']) ? $_POST['Acciones'] : "Cargar";


if ($elegirAcciones == 'Crear Producto') {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Validamos si se subió una imagen
        if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
            $imagen = $_FILES['foto'];

            // Verificamos si el archivo es una imagen válida
            $tipoImagen = mime_content_type($imagen['tmp_name']);
            if (strpos($tipoImagen, 'image') === false) {
                // Si no es una imagen, mostramos un error y no procesamos
                echo "El archivo no es una imagen válida.";
                exit();
            }

            // Comprobamos el tamaño máximo del archivo (por ejemplo 5MB)
            $maxSize = 5 * 1024 * 1024; // 5MB en bytes
            if ($imagen['size'] > $maxSize) {
                // Si el archivo excede el tamaño máximo
                echo "La imagen es demasiado grande. El tamaño máximo permitido es 5MB.";
                exit();
            }

            // Creamos un nombre único para la imagen para evitar sobrescribir archivos existentes
            $nombreImagen = uniqid() . "_" . basename($imagen['name']);
            $carpetaDestino = '../Imagenes/';
            $rutaImagen = $carpetaDestino . $nombreImagen;

            // Movemos el archivo subido a la carpeta destino
            if (move_uploaded_file($imagen['tmp_name'], $rutaImagen)) {
                // Si la imagen se sube correctamente, procedemos a insertar el producto
                $idProducto = $producto->añadirProducto(
                    $rutaImagen, // Ruta de la imagen
                    $_POST['nombreproducto'],
                    $_POST['cantidadp'],
                    $_POST['precio'],
                    $_POST['color'],
                    $_POST['iva'],
                    $_POST['categoria'],
                    $_POST['estado'],
                    $_POST['talla'],
                    $_POST['fecha_entrada'],          // Fecha de entrada
                    $_POST['ProveedoridProveedor']    // ID del proveedor
                );

                // Redirigir a la página de inventario con éxito
                header("Location: ../Principal/InventarioProductos?success=1");
                exit();
            } else {
                echo "Error al subir la imagen.";
                exit();
            }
        } else {
            // Si no se subió una imagen, podemos asignar un valor por defecto o manejarlo de otro modo
            $rutaImagen = null; // O puedes asignar una ruta predeterminada si no es obligatorio

            // Procedemos con la inserción del producto sin imagen
            $idProducto = $producto->añadirProducto(
                $rutaImagen, // Puede ser null si no se sube imagen
                $_POST['nombreproducto'],
                $_POST['cantidadp'],
                $_POST['precio'],
                $_POST['color'],
                $_POST['iva'],
                $_POST['categoria'],
                $_POST['estado'],
                $_POST['talla'],
                $_POST['fecha_entrada'],
                $_POST['ProveedoridProveedor']
            );

            // Redirigir a la página de inventario con éxito
            header("Location: ../Principal/InventarioProductos?success=1");
            exit();
        }
    }
} elseif ($elegirAcciones == 'Actualizar Producto') {
    // Asegúrate de que el ID del producto esté presente
    $idProducto = $_POST['idProducto'] ?? null;

    if ($idProducto) {
        $nombreProducto = $_POST['nombreproducto'] ?? null;
        $cantidadp = $_POST['cantidadp'];
        $precio = $_POST['precio'];
        $color = $_POST['color'];
        $iva = $_POST['iva'];
        $categoria = $_POST['categoria'];
        $estado = $_POST['estado'];
        $talla = $_POST['talla'];

        // Verificar si se ha subido una nueva imagen
        if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
            // Nueva imagen subida
            $nuevaImagen = $_FILES['foto']['name'];
            $tmp = $_FILES['foto']['tmp_name'];
            $carpeta = '../Imagenes';
            $rutaImagen = $carpeta . "/" . $nuevaImagen;

            // Mover la imagen al directorio
            if (move_uploaded_file($tmp, $rutaImagen)) {
                // Actualizar el producto con la nueva imagen
                $producto->actualizarProducto($idProducto, $nombreProducto, $cantidadp, $precio, $color, $iva, $categoria, $estado, $talla, $rutaImagen);
            } else {
                echo "Error al mover la imagen.";
            }
        } else {
            // No se subió una nueva imagen, actualizar sin cambiar la imagen actual
            $producto->actualizarProducto($idProducto, $nombreProducto, $cantidadp, $precio, $color, $iva, $categoria, $estado, $talla);
        }

        header("Location: ../Controlador/controladorInventario2.php?success=1");
        exit();
    }
} elseif ($elegirAcciones == 'Borrar Producto') {
    $producto->borrarProducto($_POST['idProducto'], null, null, null, null, null, null, '4', null);
}
