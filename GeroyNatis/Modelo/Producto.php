<?php
include '../Modelo/Conexion.php';
require_once __DIR__ . '/../vendor/autoload.php';
use Cloudinary\Cloudinary;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../'); // Ajusta la ruta si es necesario
$dotenv->load();



class Producto
{
    private $idProducto;
    private $nombreProducto;
    private $cantidadp;
    private $precio;
    private $color;
    private $iva;
    private $estado;
    private $talla;
    private $foto;
    private $categoria;
    private $Conexion;

    public function __construct($idProducto = null, $nombreProducto = null, $cantidadp = null, $precio = null, $color = null, $iva = null, $estado = null, $talla = null, $Conexion = null, $foto = null, $categoria = null)
    {
        $this->idProducto = $idProducto;
        $this->nombreProducto = $nombreProducto;
        $this->cantidadp = $cantidadp;
        $this->precio = $precio;
        $this->color = $color;
        $this->iva = $iva;
        $this->estado = $estado;
        $this->talla = $talla;
        $this->foto = $foto;
        $this->categoria = $categoria;
        $this->Conexion = Conectarse();
    }

    public function obtenerProductos()
    {
        $sql =
            "SELECT P.idProducto, P.nombreproducto, P.precio, P.iva, P.imagen, P.CategoriaidCategoria, C.categoria, P.id_estado, S.tiposestados, GROUP_CONCAT(CONCAT(T.talla, ': ', DP.cantidad, ': ', DP.color) SEPARATOR ', ') AS Detalle_Producto, SUM(DP.cantidad) AS total_unidades FROM producto P JOIN estados S ON P.id_estado = S.idestado JOIN producto_talla DP ON P.idProducto = DP.id_producto JOIN talla T ON T.idtalla = DP.id_talla JOIN categoria C ON C.idCategoria = P.CategoriaidCategoria WHERE P.id_estado = 3 GROUP BY P.idProducto;";
        $resultado = $this->Conexion->query($sql);
        $this->Conexion->close();
        return $resultado;
    }
    
public function añadirProducto($foto, $nombreProducto, $precio, $precioproveedor, $color, $categoria, $estado, $tallas, $cantidades, $colores, $fechaEntrada, $proveedorId)
{
    $this->Conexion->begin_transaction();

    try {
        // 🔁 Subir imagen a Cloudinary
        $cloudinary = new Cloudinary([
            'cloud' => [
                'cloud_name' => 'dmcwfn5kq',
                'api_key'    => $_ENV['CLAUDINARY_API_KEY'],
                'api_secret' => $_ENV['CLAUDINARY_API_SECRET'],
            ]
        ]);

        $upload = $cloudinary->uploadApi()->upload($foto);
        $urlImagen = $upload['secure_url']; // URL de la imagen subida

        $iva = 19; // IVA fijo

        // 📝 Insertar el producto en la tabla `producto` con la URL de Cloudinary
        $sql = "INSERT INTO producto(nombreproducto, precio, iva, imagen, CategoriaidCategoria, id_estado) 
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->Conexion->prepare($sql);
        $stmt->bind_param("siisii", $nombreProducto, $precio, $iva, $urlImagen, $categoria, $estado);
        $stmt->execute();

        $idProducto = $this->Conexion->insert_id;
        $stmt->close();

        for ($i = 0; $i < count($tallas); $i++) {
    $talla = $tallas[$i];
    $cantidad = $cantidades[$i];
    $color = $colores[$i];

    // Validar que no estén vacíos y sean coherentes
    if ($talla !== null && $cantidad !== null && $color !== null) {
        $sql = "INSERT INTO producto_talla (id_producto, id_talla, cantidad, color) VALUES (?, ?, ?, ?)";
        $stmt = $this->Conexion->prepare($sql);
        $stmt->bind_param("iiis", $idProducto, $talla, $cantidad, $color);
        $stmt->execute();
        $stmt->close();
    }
}



        // 💰 Calcular el total e insertar en proceso
       $cantidadTotal = array_sum($cantidades);
$total = $precioproveedor * $cantidadTotal;

        $anadido = 5;

        $sql = "INSERT INTO proceso (entradaProducto, fecha_entrada, productoidProducto, proveedoridproveedor, anadido, total, precioproveedor) 
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->Conexion->prepare($sql);
        $stmt->bind_param("isiiiii", $cantidadTotal, $fechaEntrada, $idProducto, $proveedorId, $anadido, $total, $precioproveedor);
        $stmt->execute();
        $stmt->close();

        $this->Conexion->commit();
        header("Location: ../Controlador/controladorInventario2.php?success=1");
        exit();

    } catch (Exception $e) {
        $this->Conexion->rollback();
        throw $e;
    }
}

 public function actualizarProducto($idProducto, $nombreProducto, $precio, $foto, $categoria, $estado)
    {
        // Asegúrate de mantener la conexión abierta
        $sql = 'UPDATE `producto` SET nombreproducto=?, precio=?, CategoriaidCategoria=?, id_estado=? WHERE idProducto=?';
        $stmt = $this->Conexion->prepare($sql);
        $stmt->bind_param("isisii", $idProducto, $nombreProducto, $precio, $categoria, $estado);
        $resultado = $stmt->execute();
        $stmt->close();
        return $resultado;
    }

    public function borrarProducto($idProducto, $nombreProducto, $cantidadp, $precio, $color, $iva, $categoria, $estado, $talla)
    {
        $this->Conexion = Conectarse();

        $sql = "UPDATE `producto` SET `id_estado`= '4'WHERE idProducto=?";
        $stmt = $this->Conexion->prepare($sql);
        $stmt->bind_param("i", $idProducto);
        $resultado = $stmt->execute();
        $stmt->close();
        $this->Conexion->close();
        if ($resultado) {
            header("Location: ../Controlador/controladorInventario2.php?success=1");
            exit();
        }

        return $resultado;
    }
}
