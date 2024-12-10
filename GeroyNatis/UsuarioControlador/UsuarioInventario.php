<?php
require_once "../Modelo/Producto.php";

$producto = new Producto();
$productos = $producto->obtenerProductos();
$elegirAcciones = isset($_POST['Acciones']) ? $_POST['Acciones'] : "Cargar";

$resultado = $productos; // Assign the result to $resultado

// Include the HTML part, not included directly in PHP script
// Include the HTML part, not included directly in PHP script
include "../Usuario/InventaroUsuario.php";
?>
