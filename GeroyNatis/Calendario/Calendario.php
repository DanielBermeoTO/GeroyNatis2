<?php
header('Content-Type: application/json');
include '../Modelo/Conexion.php';

$conexion = Conectarse();

if (!$conexion) {
    die(json_encode([
        'error' => 'Error de conexión: ' . mysqli_connect_error()
    ]));
}

// Si se proporciona una fecha específica
if (isset($_GET['date'])) {
    $date = mysqli_real_escape_string($conexion, $_GET['date']);
    
    // Obtener ventas del día
    $query_sales = "
        SELECT SUM(total) as daily_sales 
        FROM factura
        WHERE DATE(fechaventa) = '$date'
    ";
    
    $result_sales = mysqli_query($conexion, $query_sales);
    $sales = mysqli_fetch_assoc($result_sales);
    
    // Obtener facturas del día
    $query_invoices = "
        SELECT idFactura, total 
        FROM factura
        WHERE DATE(fechaventa) = '$date'
        ORDER BY idFactura DESC
    ";
    
    $result_invoices = mysqli_query($conexion, $query_invoices);
    $invoices = [];
    
    while ($row = mysqli_fetch_assoc($result_invoices)) {
        $invoices[] = $row;
    }
    
    echo json_encode([
        'sales' => $sales['daily_sales'] ?? 0,
        'invoices' => $invoices
    ]);
    
} else {
    // Obtener resumen de ventas por día del mes actual
    $query = "
        SELECT DATE(fechaventa) as date, 
               SUM(total) as daily_sales,
               COUNT(*) as invoice_count
        FROM factura
        WHERE MONTH(fechaventa) = MONTH(CURRENT_DATE())
        GROUP BY DATE(fechaventa)
        ORDER BY date
    ";
    
    $result = mysqli_query($conexion, $query);
    $monthData = [];
    
    while ($row = mysqli_fetch_assoc($result)) {
        $monthData[] = $row;
    }
    
    echo json_encode($monthData);
}

mysqli_close($conexion);
?>