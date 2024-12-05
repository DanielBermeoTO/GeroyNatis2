<?php
include "../Modelo/Conexion.php";

class Proveedor
{
    private $idProveedor;
    private $nombreproveedor;
    private $Telefono;
    private $producto;
    private $Conexion;

    public function __construct($idProveedor = null, $nombreproveedor = null, $Telefono = null, $Conexion = null, $producto = null)
    {
        $this->idProveedor = $idProveedor;
        $this->nombreproveedor = $nombreproveedor;
        $this->Telefono = $Telefono;
        $this->producto = $producto;
        $this->Conexion = Conectarse();
    }

    public function consultarProveedor()
    {
        // No cierres la conexión aquí
        $sql = "SELECT `idProveedor`, `nombreproveedor`, `Telefono`, `productos`, 
                       (SELECT `nombreproducto` FROM producto WHERE producto.idProducto = proveedor.productos) AS producto 
                FROM proveedor;";
        $resultado = $this->Conexion->query($sql);
        return $resultado; // Devuelve el resultado, pero no cierras la conexión aquí
    }

    public function añadirProveedor($nombreproveedor, $Telefono, $producto)
    {
        // Asegúrate de no haber cerrado la conexión previamente
        $sql = "INSERT INTO `proveedor`(`nombreproveedor`, Telefono, productos) VALUES (?,?,?)";
        $stmt = $this->Conexion->prepare($sql);
        $stmt->bind_param("sii", $nombreproveedor, $Telefono, $producto);
        $stmt->execute();
        $idProveedor = $this->Conexion->insert_id;
        $stmt->close();
        return $idProveedor;
    }

    public function actualizarProveedor($idProveedor, $nombreproveedor, $Telefono, $producto)
    {
        // Asegúrate de mantener la conexión abierta
        $sql = 'UPDATE `proveedor` SET `nombreproveedor`= ?,`Telefono`= ?,`productos`= ? WHERE `idProveedor`=?';
        $stmt = $this->Conexion->prepare($sql);
        $stmt->bind_param("siii", $nombreproveedor, $Telefono, $producto, $idProveedor);
        $resultado = $stmt->execute();
        $stmt->close();
        return $resultado;
    }

    // Método para cerrar la conexión cuando todo haya terminado
    public function cerrarConexion()
    {
        $this->Conexion->close();
    }
}
