<?php
include "../Modelo/Conexion.php";

class Venta
{
    private $idFactura;
    private $fechaventa;
    private $subtotal;
    private $total;
    private $id_estadof;
    private $ProductoidProducto;
    private $valorunitario;
    private $cantidad;
    private $cliente;
    private $Conexion;

    public function __construct($idFactura = null, $fechaventa = null, $subtotal = null, $total = null, $id_estadof = null, $ProductoidProducto = null, $valorunitario = null, $cantidad = null, $cliente = null)
    {
        $this->idFactura = $idFactura;
        $this->fechaventa = $fechaventa;
        $this->subtotal = $subtotal;
        $this->total = $total;
        $this->id_estadof = $id_estadof;
        $this->ProductoidProducto = $ProductoidProducto;
        $this->valorunitario = $valorunitario;
        $this->cantidad = $cantidad;
        $this->cliente = $cliente;
        $this->Conexion = Conectarse();
    }

    public function obtenerVentas()
    {
        $sqlv = "SELECT FV.idFactura, FV.fechaventa, FV.subtotal, FV.total,FV.usuario,(SELECT nombre FROM usuario WHERE FV.usuario = usuario.documento) AS nombre,(SELECT apellido FROM usuario WHERE FV.usuario = usuario.documento) AS apellido, (SELECT estados.tiposestados FROM estados WHERE FV.id_estadof = estados.idestado) AS estadi, GROUP_CONCAT(CONCAT(p.idProducto, ': ', p.nombreproducto, ' : ', df.cantidad, ': ', df.valorunitario, ': ', p.iva, ': ', df.cliente) SEPARATOR ', ') AS productos, SUM(df.cantidad) AS total_cantidad FROM detalle_factura df INNER JOIN factura AS FV ON df.FacturaidFactura = FV.idFactura INNER JOIN producto p ON df.ProductoidProducto = p.idProducto INNER JOIN estados AS E ON FV.id_estadof = E.idestado GROUP BY FV.idFactura, FV.fechaventa, FV.subtotal, FV.total, FV.id_estadof, E.tiposestados;";

        $resultado = $this->Conexion->query($sqlv);

        // Verificar si hubo un error en la consulta
        if (!$resultado) {
            die('Error en la consulta: ' . $this->Conexion->error);
        }

        return $resultado; // No cerrar la conexión aquí
    }

    public function agregarVenta($fechaventa, $id_estadof, $productos, $documento) {
        // Verifica que la conexión esté activa
        if (!$this->Conexion->ping()) {
            throw new Exception("La conexión a la base de datos se ha cerrado.");
        }
    
        // Validar el inventario antes de proceder
        foreach ($productos as $producto) {
            $ProductoidProducto = $producto['idProducto'];
            $cantidad = $producto['cantidad'];
    
            $sqlVerificarInventario = "SELECT cantidadp FROM `producto` WHERE idProducto = ?";
            $stmtVerificar = $this->Conexion->prepare($sqlVerificarInventario);
            if ($stmtVerificar === false) {
                throw new Exception("Error en la preparación de la consulta de verificación de inventario: " . $this->Conexion->error);
            }
            $stmtVerificar->bind_param("i", $ProductoidProducto);
            $stmtVerificar->execute();
            $stmtVerificar->bind_result($cantidadp);
            $stmtVerificar->fetch();
            $stmtVerificar->close();
    
            // Si no hay suficiente inventario, detener el proceso
            if ($cantidad > $cantidadp) {
                header("Location: ../GeroYNatis/Principal/RegistroVentasAñadir.php?error=pocosproductos");
                exit;
            }
        }
    
        // Calcular el subtotal y el IVA total
        $subtotal = 0;
        $ivaTotal = 0;
    
        foreach ($productos as $producto) {
            $ProductoidProducto = $producto['idProducto'];
            $sqlIva = "SELECT iva FROM `producto` WHERE idProducto = ?";
            $stmtIva = $this->Conexion->prepare($sqlIva);
            if ($stmtIva === false) {
                throw new Exception("Error en la preparación de la consulta de IVA: " . $this->Conexion->error);
            }
            $stmtIva->bind_param("i", $ProductoidProducto);
            $stmtIva->execute();
            $stmtIva->bind_result($iva);
            $stmtIva->fetch();
            $stmtIva->close();
    
            $valorunitario = $producto['valorunitario'];
            $cantidad = $producto['cantidad'];
    
            $subtotal += $valorunitario * $cantidad;
            $ivaTotal += ($valorunitario * $cantidad) * ($iva / 100);
        }
    
        $total = $subtotal + $ivaTotal;
    
        // Insertar la factura
        $sql = "INSERT INTO `factura`(`fechaventa`, `id_estadof`, `subtotal`, `total`, usuario) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->Conexion->prepare($sql);
        if ($stmt === false) {
            throw new Exception("Error en la preparación de la consulta: " . $this->Conexion->error);
        }
        $stmt->bind_param("ssidi", $fechaventa, $id_estadof, $subtotal, $total, $documento);
        $stmt->execute();
        $idFactura = $this->Conexion->insert_id;
        $stmt->close();
    
        // Insertar los detalles de la factura y actualizar el inventario
        $sqlDetalle = "INSERT INTO `detalle_factura`(`FacturaidFactura`, `ProductoidProducto`, `valorunitario`, `cantidad`, `cliente`) VALUES (?, ?, ?, ?, ?)";
        $stmtDetalle = $this->Conexion->prepare($sqlDetalle);
        if ($stmtDetalle === false) {
            throw new Exception("Error en la preparación de la consulta de detalles: " . $this->Conexion->error);
        }
    
        foreach ($productos as $producto) {
            $ProductoidProducto = $producto['idProducto'];
            $valorunitario = $producto['valorunitario'];
            $cantidad = $producto['cantidad'];
            $cliente = $producto['cliente'];
    
            $stmtDetalle->bind_param("iiidi", $idFactura, $ProductoidProducto, $valorunitario, $cantidad, $cliente);
            if (!$stmtDetalle->execute()) {
                throw new Exception("Error al ejecutar la consulta de detalles: " . $stmtDetalle->error);
            }
    
            // Actualizar el inventario
            $sqlActualizarInventario = "UPDATE `producto` SET `cantidadp` = `cantidadp` - ? WHERE idProducto = ?";
            $stmtActualizarInventario = $this->Conexion->prepare($sqlActualizarInventario);
            if ($stmtActualizarInventario === false) {
                throw new Exception("Error en la preparación de la consulta de actualización de inventario: " . $this->Conexion->error);
            }
            $stmtActualizarInventario->bind_param("ii", $cantidad, $ProductoidProducto);
            $stmtActualizarInventario->execute();
            $stmtActualizarInventario->close();
    
            // Inhabilitar el producto si la cantidad llega a 0
            $sqlInhabilitarProducto = "UPDATE `producto` SET `id_estado` = 4 WHERE idProducto = ? AND `cantidadp` <= 0";
            $stmtInhabilitar = $this->Conexion->prepare($sqlInhabilitarProducto);
            if ($stmtInhabilitar === false) {
                throw new Exception("Error en la preparación de la consulta para inhabilitar producto: " . $this->Conexion->error);
            }
            $stmtInhabilitar->bind_param("i", $ProductoidProducto);
            $stmtInhabilitar->execute();
            $stmtInhabilitar->close();
        }
    
        $stmtDetalle->close();
        return $idFactura;
    }
    
    
    
    public function pagarVenta($idFactura, $id_estadof, $fechaventa) {
        // Verifica que el ID de la factura existe
        $sqlCheck = "SELECT COUNT(*) FROM `factura` WHERE `idFactura` = ?";
        $stmtCheck = $this->Conexion->prepare($sqlCheck);
        $stmtCheck->bind_param("i", $idFactura);
        $stmtCheck->execute();
        $stmtCheck->bind_result($count);
        $stmtCheck->fetch();
        $stmtCheck->close();
    
        if ($count === 0) {
            echo "No se encontró la factura con ID: " . $idFactura;
            return false;
        }
        
        $sql = "UPDATE `factura` SET `id_estadof` = '1' WHERE `factura`.`idFactura` = ?";
        $stmt = $this->Conexion->prepare($sql);
        $stmt->bind_param("i", $idFactura);
        
        $resultado = $stmt->execute();
        
        if (!$resultado) {
            echo "Error: " . $stmt->error;
        }
        
        $stmt->close();
        
        
        return $resultado;
    }

    public function nopagarVenta($idFactura, $id_estadof, $fechaventa) {
        // Verifica que el ID de la factura existe
        $sqlCheck = "SELECT COUNT(*) FROM `factura` WHERE `idFactura` = ?";
        $stmtCheck = $this->Conexion->prepare($sqlCheck);
        $stmtCheck->bind_param("i", $idFactura);
        $stmtCheck->execute();
        $stmtCheck->bind_result($count);
        $stmtCheck->fetch();
        $stmtCheck->close();
    
        if ($count === 0) {
            echo "No se encontró la factura con ID: " . $idFactura;
            return false;
        }
        
        $sql = "UPDATE `factura` SET `id_estadof` = '2' WHERE `factura`.`idFactura` = ?";
        $stmt = $this->Conexion->prepare($sql);
        $stmt->bind_param("i", $idFactura);
        
        $resultado = $stmt->execute();
        
        if (!$resultado) {
            echo "Error: " . $stmt->error;
        }
        
        $stmt->close();
        

        
        return $resultado;
    }
    
    
}
