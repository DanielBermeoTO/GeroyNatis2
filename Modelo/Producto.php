<?php
include '../Modelo/Conexion.php';
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
            "SELECT 
    idProducto, 
    nombreproducto, 
    cantidadp, 
    precio,
    `color`, 
    `iva`,
    imagen,
    (SELECT categoria FROM categoria WHERE categoria.idCategoria = producto.CategoriaidCategoria) AS categorias, 
    (SELECT talla FROM talla WHERE talla.idtalla = producto.talla) AS talla,
    (SELECT tiposestados FROM estados WHERE estados.idestado = producto.id_estado) AS estado 
FROM 
    producto 
ORDER BY 
    CASE 
        WHEN (SELECT idestado FROM estados WHERE estados.idestado = producto.id_estado) = 3 THEN 0
        ELSE 1 
    END, 
    idProducto;
;

";
        $resultado = $this->Conexion->query($sql);
        $this->Conexion->close();
        return $resultado;
    }
    public function añadirProducto($imagen, $nombreProducto, $cantidadp, $precio, $color, $iva, $categoria, $estado, $talla, $fechaEntrada, $proveedorId)
    {
        // Iniciar transacción
        $this->Conexion->begin_transaction();

        try {
            // Insertar el producto en la tabla `producto`
            $sql = "INSERT INTO producto (nombreproducto, cantidadp, precio, color, iva, imagen, CategoriaidCategoria, id_estado, talla) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->Conexion->prepare($sql);
            $stmt->bind_param("siisisiii", $nombreProducto, $cantidadp, $precio, $color, $iva, $imagen, $categoria, $estado, $talla);
            $stmt->execute();

            // Obtener el ID del producto insertado
            $idProducto = $this->Conexion->insert_id;
            $stmt->close();

            // Calcular el total (precio del producto * cantidad de productos ingresados)
            $total = $precio * $cantidadp;

            // Definir el valor de 'anadido' como 5
            $anadido = 5;

            // Insertar el movimiento en la tabla `proceso`
            $sql = "INSERT INTO proceso (entradaProducto, fecha_entrada, productoidProducto, proveedoridproveedor, anadido, total) 
                    VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $this->Conexion->prepare($sql);
            $stmt->bind_param("isiiii", $cantidadp, $fechaEntrada, $idProducto, $proveedorId, $anadido, $total);
            $stmt->execute();
            $stmt->close();

            // Confirmar la transacción
            $this->Conexion->commit();
            header("Location: ../Controlador/controladorInventario2.php?success=1");
            exit();

            return $idProducto;
        } catch (Exception $e) {
            // En caso de error, hacer rollback de la transacción
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
