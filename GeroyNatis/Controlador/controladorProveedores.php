<?php
require_once "../Modelo/Proveedor.php";

$proveedor = new Proveedor();
$proveedores = $proveedor->consultarProveedor();
$resultadoo = $proveedores;
$elegirAcciones = isset($_POST['Acciones']) ? $_POST['Acciones'] : "Cargar";

if ($elegirAcciones == 'Crear Proveedor') {
    $idProveedor = $proveedor->añadirProveedor(
        $_POST['nombreproveedor'],
        $_POST['Telefono'],
        $_POST['productos']
    );
    // Redireccionar a la página de éxito o mostrar un mensaje.
    header("Location: ../Controlador/controladorProveedores.php?success=1");
    exit();
}elseif ($elegirAcciones == 'Actualizar Proveedor') {
    $idProveedor = $_POST['idProveedor'] ?? null;

    if ($idProveedor) {
        $nombreproveedor = $_POST['nombreproveedor'];
        $Telefono = $_POST['Telefono'];
        $producto = $_POST['productos'];

        $proveedor->actualizarProveedor($idProveedor,$nombreproveedor,$Telefono,$producto);
        header("Location: ../Controlador/controladorProveedores.php?success=1");
        exit();
    }
}
include "../Principal/Proveedores.php";
