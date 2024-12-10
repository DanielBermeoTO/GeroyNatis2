<?php
// Mostrar todos los errores
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once "../Modelo/Movimientos.php";

$movimiento = new Movimientos();
$elegirAcciones = isset($_POST['Acciones']) ? $_POST['Acciones'] : "Cargar";

if ($elegirAcciones == 'Crear Movimiento') {
    // Llamar a la función añadirmovimiento() pasando todos los parámetros necesarios
    $idproceso = $movimiento->añadirmovimiento(
        $_POST['entradaproducto'],          // Cantidad de productos entrados
        $_POST['fecha_entrada'],            // Fecha de entrada
        $_POST['ProductoidProducto'],       // ID del producto
        $_POST['ProveedoridProveedor'],     // ID del proveedor
        $_POST['precioproveedor'],
        6,                                  // Valor de 'anadido', que será siempre 6
        0                                   // Valor temporal de 'total', se calcula en la función
    );
    
    // Redireccionar a la página controladora con un mensaje de éxito
    header("Location: ../Controlador/controladorMovimiento.php?success=1");
    exit();
}
?>
