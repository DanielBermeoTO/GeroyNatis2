<?php
require_once "../Modelo/Movimientos.php";

$movimiento = new Movimientos();
$movimientos = $movimiento->obtenerMovimiento();
$elegirAcciones = isset($_POST['Acciones']) ? $_POST['Acciones'] : "Cargar";

$resultado = $movimientos; // Assign the result to $resultado

// Include the HTML part, not included directly in PHP script
// Include the HTML part, not included directly in PHP script

include "../Principal/ProveedoresMovimientos.php";
