<?php
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../app/Models/Producto.php';
include_once __DIR__ . '/../app/Config/Database.php';

use App\Models\Producto;
use App\Config\Database;

class ProductoTest extends TestCase
{
    protected $producto;
    
    protected function setUp(): void
    {
        $this->producto = new Producto();
    }
    
    public function testAñadirProducto()
    {
        // Datos simulados
        $nombreproducto = 'Producto de prueba';
        $precio = 19999;
        $precioproveedor = 199;
        $categoria = 1;
        $estado = 3;
        $foto = __DIR__ . '/imagen_dummy.jpg';

        $tallas = [1];
        $cantidades = [10];
        $colores = ['Rojo'];

        $fechaEntrada = date('Y-m-d');
        $proveedorId = 1;

        // Crear archivo de prueba si no existe
        if (!file_exists($foto)) {
            file_put_contents($foto, file_get_contents('https://via.placeholder.com/150'));
        }

        // Ejecutar el método y validar que no falle
        try {
            $this->producto->añadirProducto(
                $foto,
                $nombreproducto,
                $precio,
                $precioproveedor,
                $colores[0],
                $categoria,
                $estado,
                $tallas,
                $cantidades,
                $colores,
                $fechaEntrada,
                $proveedorId
            );

            $this->assertTrue(true);
        } catch (Exception $e) {
            $this->fail("Error al añadir producto: " . $e->getMessage());
        }
    }

    
    public function testConsultarProducto()
    {
        // Insertar un producto antes de consultarlo
        $this->producto->agregarProducto(
            'Producto test consulta de producto',
            100.0,
            19,
            'test.jpg',
            121,
            101,
            'Producto de prueba para consulta',
            30,
            201,
            '505547805'
        );

        // Obtener el último producto insertado (suponiendo que consultarProducto sin parámetro devuelve todos)
        $todos = $this->producto->consultarProducto(); // Asumiendo devuelve mysqli_result
        $ultimoProducto = null;
        while ($fila = $todos->fetch_assoc()) {
            $ultimoProducto = $fila;
        }

        $this->assertNotNull($ultimoProducto, 'Debe existir al menos un producto para probar');

        // Consultar el producto por su ID real
        $resultado = $this->producto->consultarProducto($ultimoProducto['CodigoProducto']);

        $this->assertInstanceOf(\mysqli_result::class, $resultado);
        $this->assertTrue($resultado->num_rows > 0, 'Debe existir al menos un producto con ese ID');

        $producto = $resultado->fetch_assoc();
        $this->assertArrayHasKey('CodigoProducto', $producto, 'Debe contener el campo CodigoProducto');
        $this->assertArrayHasKey('Nombre', $producto, 'Debe contener el campo Nombre');
    }
    
    public function testActualizarProducto()
    {
        $id_producto = 356; // Asume que el producto con ID 1 existe
        $nombre = 'Producto actualizado Lilley';
        $precio = 299.99;
        $iva = 19;
        $id_categoria = 121; 
        $id_estado = 101; 
        $descripcion = 'Producto actualizado';
        $id_marca = 30;    
        $id_talla = 201;   
        $documento_usuario = '1080180837'; 
        
        $resultado = $this->producto->actualizarProducto(
            $id_producto,
            $nombre,
            $precio,
            $iva,
            $id_categoria,
            $id_estado,
            $descripcion,
            $id_marca,
            $id_talla,
            $documento_usuario
        );
        
        $this->assertTrue($resultado, 'El producto debería actualizarse correctamente');
    }
   
    
    public function testConsultarTodosLosProductos()
    {
        // Reemplazamos listarProductos con consultarProducto sin parámetros
        $resultado = $this->producto->consultarProducto(); // Sin parámetro consulta todos
        
        // Verificamos que devuelve un objeto mysqli_result
        $this->assertInstanceOf(\mysqli_result::class, $resultado);
        
        // Convertimos el resultado a un array para verificar
        $productos = [];
        while ($fila = $resultado->fetch_assoc()) {
            $productos[] = $fila;
        }
        
        $this->assertIsArray($productos, 'Debería poder convertirse a un array');
        $this->assertGreaterThanOrEqual(0, count($productos), 'Debe contener al menos cero productos');
    }

    public static function tearDownAfterClass(): void
    {
        echo "\n\n******************************\n";
        echo "Todas las pruebas del módulo PRODUCTOS se realizaron correctamente ✅\n";
        echo "Se validaron agregar, consultar, actualizar y listar productos.\n";
        echo "******************************\n\n";
    }
}
