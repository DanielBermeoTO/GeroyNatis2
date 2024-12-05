<?php
header('Content-Type: application/json');
include '../Modelo/Conexion.php';

$conexion = Conectarse();

if (!$conexion) {
    die(json_encode([
        'error' => 'Error de conexión: ' . mysqli_connect_error()
    ]));
}

// Obtener el tipo de período (día, semana, mes, año)
$period = $_GET['period'] ?? 'day';
$date = $_GET['date'] ?? date('Y-m-d');

$sales = 0;
$invoices = [];

switch ($period) {
    case 'day':
        // Consulta existente para ventas diarias
        $query_sales = "
            SELECT SUM(total) as total_sales 
            FROM factura
            WHERE DATE(fechaventa) = '$date'
        ";
        
        $query_invoices = "
            SELECT idFactura, total 
            FROM factura
            WHERE DATE(fechaventa) = '$date'
            ORDER BY idFactura DESC
        ";
        break;

    case 'week':
        // Consulta para ventas semanales
        $query_sales = "
            SELECT SUM(total) as total_sales 
            FROM factura
            WHERE 
                fechaventa >= DATE_SUB('$date', INTERVAL WEEKDAY('$date') DAY)
                AND fechaventa < DATE_ADD(DATE_SUB('$date', INTERVAL WEEKDAY('$date') DAY), INTERVAL 7 DAY)
        ";
        
        $query_invoices = "
            SELECT idFactura, total, DATE(fechaventa) as fecha
            FROM factura
            WHERE 
                fechaventa >= DATE_SUB('$date', INTERVAL WEEKDAY('$date') DAY)
                AND fechaventa < DATE_ADD(DATE_SUB('$date', INTERVAL WEEKDAY('$date') DAY), INTERVAL 7 DAY)
            ORDER BY fechaventa DESC, idFactura DESC
        ";
        break;

    case 'month':
        // Consulta para ventas mensuales
        $query_sales = "
            SELECT SUM(total) as total_sales 
            FROM factura
            WHERE 
                YEAR(fechaventa) = YEAR('$date')
                AND MONTH(fechaventa) = MONTH('$date')
        ";
        
        $query_invoices = "
            SELECT idFactura, total, DATE(fechaventa) as fecha
            FROM factura
            WHERE 
                YEAR(fechaventa) = YEAR('$date')
                AND MONTH(fechaventa) = MONTH('$date')
            ORDER BY fechaventa DESC, idFactura DESC
        ";
        break;

    case 'year':
        // Consulta para ventas anuales
        $query_sales = "
            SELECT SUM(total) as total_sales 
            FROM factura
            WHERE YEAR(fechaventa) = YEAR('$date')
        ";
        
        $query_invoices = "
            SELECT idFactura, total, DATE(fechaventa) as fecha
            FROM factura
            WHERE YEAR(fechaventa) = YEAR('$date')
            ORDER BY fechaventa DESC, idFactura DESC
        ";
        break;
}

// Ejecutar consulta de ventas
$result_sales = mysqli_query($conexion, $query_sales);
$sales_data = mysqli_fetch_assoc($result_sales);
$total_sales = $sales_data['total_sales'] ?? 0;

// Ejecutar consulta de facturas
$result_invoices = mysqli_query($conexion, $query_invoices);
while ($row = mysqli_fetch_assoc($result_invoices)) {
    $invoices[] = $row;
}

// Preparar respuesta
$response = [
    'sales' => $total_sales,
    'invoices' => $invoices,
    'period' => $period,
    'date' => $date
];

echo json_encode($response);

mysqli_close($conexion);
?>