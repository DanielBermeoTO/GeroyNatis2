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

        $sql = "INSERT INTO proceso (entradaProducto, fecha_entrada, productoidProducto, proveedoridproveedor, anadido, total) 
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->Conexion->prepare($sql);
        $stmt->bind_param("isiiii", $cantidadTotal, $fechaEntrada, $idProducto, $proveedorId, $anadido, $total);
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


    public function actualizarProducto($idProducto, $nombreProducto, $cantidadp, $precio, $color, $iva, $categoria, $estado, $talla, $nuevaImagen = null)
    {
        $this->Conexion = Conectarse();

        try {
            // Iniciar la transacción
            $this->Conexion->begin_transaction();

            // Si se cargó una nueva imagen, actualizamos la ruta en la base de datos
            if ($nuevaImagen) {
                // Eliminar la imagen anterior (opcional, si quieres sobreescribirla)
                $sql = "SELECT imagen FROM producto WHERE idProducto = ?";
                $stmt = $this->Conexion->prepare($sql);
                $stmt->bind_param("i", $idProducto);
                $stmt->execute();
                $resultado = $stmt->get_result();
                $producto = $resultado->fetch_assoc();
                $imagenAnterior = $producto['imagen'];

                // Verificar si existe una imagen anterior y eliminarla
                if (file_exists($imagenAnterior)) {
                    unlink($imagenAnterior); // Elimina la imagen anterior
                }

                // Actualizar el producto con la nueva imagen
                $sql = "UPDATE `producto` SET `nombreproducto`=?, `cantidadp`=?, `precio`=?, `color`=?, `iva`=?, `CategoriaidCategoria`=?, `id_estado`=?, `talla`=?, `imagen`=? WHERE `idProducto`=?";
                $stmt = $this->Conexion->prepare($sql);
                $stmt->bind_param("siisiiissi", $nombreProducto, $cantidadp, $precio, $color, $iva, $categoria, $estado, $talla, $nuevaImagen, $idProducto);
            } else {
                // Si no se cargó una nueva imagen, dejamos la imagen actual intacta
                $sql = "UPDATE `producto` SET `nombreproducto`=?, `cantidadp`=?, `precio`=?, `color`=?, `iva`=?, `CategoriaidCategoria`=?, `id_estado`=?, `talla`=? WHERE `idProducto`=?";
                $stmt = $this->Conexion->prepare($sql);
                $stmt->bind_param("siisiiiii", $nombreProducto, $cantidadp, $precio, $color, $iva, $categoria, $estado, $talla, $idProducto);
            }

            // Ejecutar la actualización
            $resultado = $stmt->execute();
            $stmt->close();

            // Confirmar la transacción
            $this->Conexion->commit();

            // Cerrar la conexión
            $this->Conexion->close();

            return $resultado;
        } catch (Exception $e) {
            // En caso de error, hacer rollback de la transacción
            $this->Conexion->rollback();
            $this->Conexion->close();
            throw $e;
        }
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
