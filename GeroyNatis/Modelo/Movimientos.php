<?php
include '../Modelo/Conexion.php';

class Movimientos
{
    private $idproceso;
    private $entradaproducto;
    private $fechaentrada;
    private $ProductoidProducto;
    private $ProveedoridProveedor;
    private $precioproveedor;
    private $total;
    private $anadido;
    private $Conexion;

    public function __construct($idproceso = null, $entradaproducto = null, $fechaentrada = null, $ProductoidProducto = null, $ProveedoridProveedor = null, $total = null, $anadido = null, $precioproveedor = null, $Conexion = null)
    {
        $this->idproceso = $idproceso;
        $this->entradaproducto = $entradaproducto;
        $this->fechaentrada = $fechaentrada;
        $this->ProductoidProducto = $ProductoidProducto;
        $this->ProveedoridProveedor = $ProveedoridProveedor;
        $this->precioproveedor= $precioproveedor;
        $this->anadido = $anadido;
        $this->total = $total;
        $this->Conexion = Conectarse();
    }
    public function obtenerMovimiento()
    {
        $sql = "SELECT `idProceso`, `entradaproducto`, `fecha_entrada`,`ProductoidProducto`, precioproveedor, total, (SELECT nombreproducto FROM producto WHERE proceso.ProductoidProducto = producto.idProducto) AS producto,(SELECT tiposestados FROM estados WHERE estados.idestado = anadido) AS anadido, (SELECT precio FROM producto WHERE proceso.ProductoidProducto = producto.idProducto) AS precio, (SELECT `nombreproveedor` FROM proveedor WHERE proceso.ProveedoridProveedor = proveedor.idProveedor) AS proveedor FROM `proceso`;
";
        $resultado = $this->Conexion->query($sql);
        $this->Conexion->close();
        return $resultado;
    }

    public function añadirmovimiento($entradaproducto, $fechaentrada, $ProductoidProducto, $ProveedoridProveedor, $precioproveedor, $anadido, $total)
    {
        // Iniciar transacción
        $this->Conexion->begin_transaction();
    
        try {
            // Obtener la cantidad actual y el precio del producto
            $sql = "SELECT cantidadp FROM producto WHERE idProducto = ?";
            $stmt = $this->Conexion->prepare($sql);
            $stmt->bind_param("i", $ProductoidProducto);
            $stmt->execute();
            $resultado = $stmt->get_result();
            $row = $resultado->fetch_assoc();
            $cantidadActual = $row['cantidadp'];
    
            // Calcular la nueva cantidad
            $nuevaCantidad = $cantidadActual + $entradaproducto;
    
            // Calcular el total (precio del producto por la cantidad ingresada)
            $total = $precioproveedor * $entradaproducto;
    
            // Guardar el valor 6 en la variable anadido
            $anadido = 6;
    
            // Actualizar la cantidad del producto
            $sql = "UPDATE producto SET cantidadp = ? WHERE idProducto = ?";
            $stmt = $this->Conexion->prepare($sql);
            $stmt->bind_param("ii", $nuevaCantidad, $ProductoidProducto);
            $stmt->execute();
    
            // Insertar el nuevo movimiento en la tabla proceso
            $sql = "INSERT INTO `proceso`(`entradaProducto`, `fecha_entrada`, `productoidProducto`, `proveedoridproveedor`, precioproveedor, `anadido`, `total`) 
                    VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->Conexion->prepare($sql);
            $stmt->bind_param("isiiiii", $entradaproducto, $fechaentrada, $ProductoidProducto, $ProveedoridProveedor, $precioproveedor, $anadido, $total);
            $stmt->execute();
    
            // Obtener el id del proceso insertado
            $idproceso = $this->Conexion->insert_id;
    
            // Confirmar la transacción
            $this->Conexion->commit();
            return $idproceso;
        } catch (Exception $e) {
            // En caso de error, hacer rollback
            $this->Conexion->rollback();
            throw $e;
        }    
    }
}
