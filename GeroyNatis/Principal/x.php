<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Producto</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .image-preview {
            width: 100%;
            height: 150px;
            border: 2px dashed #ddd;
            border-radius: 5px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 15px;
            overflow: hidden;
            position: relative;
        }
        
        .image-preview img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }
        
        .image-preview-text {
            position: absolute;
            z-index: 1;
        }
        
        .image-preview.has-image .image-preview-text {
            display: none;
        }
        
        .form-label.required:after {
            content: " *";
            color: red;
        }
        
        #imageInput {
            display: none;
        }
    </style>
</head>
<body>
    <div class="container-sm my-4" style="max-width: 650px;">
        <h4 class="mb-3">Registro de Producto</h4>
        
        <form id="productForm" class="needs-validation" novalidate>
            <!-- Imagen del producto -->
            <div class="mb-3">
                <label for="imageInput" class="form-label required">Imagen del Producto</label>
                <div class="image-preview" id="imagePreview">
                    <span class="image-preview-text">Haga clic para seleccionar una imagen</span>
                    <img src="/placeholder.svg" alt="Vista previa de imagen" id="previewImg" style="display: none;">
                </div>
                <input type="file" class="form-control" id="imageInput" accept="image/*" required>
                <div class="invalid-feedback">
                    Por favor seleccione una imagen para el producto.
                </div>
            </div>
            
            <div class="row">
                <!-- Nombre del producto -->
                <div class="col-md-6 mb-2">
                    <label for="productName" class="form-label required">Nombre del Producto</label>
                    <input type="text" class="form-control" id="productName" required>
                    <div class="invalid-feedback">
                        Por favor ingrese el nombre del producto.
                    </div>
                </div>
                
                <!-- Precio unitario -->
                <div class="col-md-3 mb-2">
                    <label for="unitPrice" class="form-label required">Precio Unitario</label>
                    <div class="input-group">
                        <span class="input-group-text">$</span>
                        <input type="number" class="form-control" id="unitPrice" min="0" step="0.01" required>
                        <div class="invalid-feedback">
                            Por favor ingrese el precio unitario.
                        </div>
                    </div>
                </div>
                
                <!-- Precio proveedor -->
                <div class="col-md-3 mb-2">
                    <label for="supplierPrice" class="form-label required">Precio Proveedor</label>
                    <div class="input-group">
                        <span class="input-group-text">$</span>
                        <input type="number" class="form-control" id="supplierPrice" min="0" step="0.01" required>
                        <div class="invalid-feedback">
                            Por favor ingrese el precio del proveedor.
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <!-- Categoría -->
                <div class="col-md-4 mb-2">
                    <label for="category" class="form-label required">Categoría</label>
                    <select class="form-select" id="category" required>
                        <option value="" selected disabled>Seleccione una categoría</option>
                        <option value="camisetas">Camisetas</option>
                        <option value="pantalones">Pantalones</option>
                        <option value="vestidos">Vestidos</option>
                        <option value="chaquetas">Chaquetas</option>
                        <option value="accesorios">Accesorios</option>
                    </select>
                    <div class="invalid-feedback">
                        Por favor seleccione una categoría.
                    </div>
                </div>
                
                <!-- Estado -->
                <div class="col-md-4 mb-2">
                    <label for="status" class="form-label required">Estado</label>
                    <select class="form-select" id="status" required>
                        <option value="" selected disabled>Seleccione un estado</option>
                        <option value="disponible">Disponible</option>
                        <option value="agotado">Agotado</option>
                        <option value="descontinuado">Descontinuado</option>
                        <option value="proximamente">Próximamente</option>
                    </select>
                    <div class="invalid-feedback">
                        Por favor seleccione un estado.
                    </div>
                </div>
                
                <!-- Proveedor -->
                <div class="col-md-4 mb-2">
                    <label for="supplier" class="form-label required">Proveedor</label>
                    <select class="form-select" id="supplier" required>
                        <option value="" selected disabled>Seleccione un proveedor</option>
                        <option value="proveedor1">Proveedor 1</option>
                        <option value="proveedor2">Proveedor 2</option>
                        <option value="proveedor3">Proveedor 3</option>
                        <option value="proveedor4">Proveedor 4</option>
                    </select>
                    <div class="invalid-feedback">
                        Por favor seleccione un proveedor.
                    </div>
                </div>
            </div>
            
            <!-- Fecha -->
            <div class="mb-3">
                <label for="date" class="form-label required">Fecha</label>
                <input type="date" class="form-control" id="date" required>
                <div class="invalid-feedback">
                    Por favor seleccione una fecha.
                </div>
            </div>
            
            <!-- Sección de tallas -->
            <h5 class="mt-3 mb-2">Inventario por Tallas</h5>

            <div class="row row-cols-2 row-cols-md-3 g-2 mb-3">
                <!-- XS -->
                <div class="col">
                    <div class="card h-100">
                        <div class="card-header py-1 bg-light">
                            <h6 class="card-title mb-0">Talla XS</h6>
                        </div>
                        <div class="card-body p-2">
                            <div class="mb-2">
                                <label for="quantityXS" class="form-label required">Cantidad</label>
                                <input type="number" class="form-control" id="quantityXS" min="0" required>
                                <div class="invalid-feedback">
                                    Por favor ingrese la cantidad.
                                </div>
                            </div>
                            <div class="mb-0">
                                <label for="colorXS" class="form-label required">Color</label>
                                <input type="text" class="form-control" id="colorXS" required>
                                <div class="invalid-feedback">
                                    Por favor ingrese el color.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- S -->
                <div class="col">
                    <div class="card h-100">
                        <div class="card-header py-1 bg-light">
                            <h6 class="card-title mb-0">Talla S</h6>
                        </div>
                        <div class="card-body p-2">
                            <div class="mb-2">
                                <label for="quantityS" class="form-label required">Cantidad</label>
                                <input type="number" class="form-control" id="quantityS" min="0" required>
                                <div class="invalid-feedback">
                                    Por favor ingrese la cantidad.
                                </div>
                            </div>
                            <div class="mb-0">
                                <label for="colorS" class="form-label required">Color</label>
                                <input type="text" class="form-control" id="colorS" required>
                                <div class="invalid-feedback">
                                    Por favor ingrese el color.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- M -->
                <div class="col">
                    <div class="card h-100">
                        <div class="card-header py-1 bg-light">
                            <h6 class="card-title mb-0">Talla M</h6>
                        </div>
                        <div class="card-body p-2">
                            <div class="mb-2">
                                <label for="quantityM" class="form-label required">Cantidad</label>
                                <input type="number" class="form-control" id="quantityM" min="0" required>
                                <div class="invalid-feedback">
                                    Por favor ingrese la cantidad.
                                </div>
                            </div>
                            <div class="mb-0">
                                <label for="colorM" class="form-label required">Color</label>
                                <input type="text" class="form-control" id="colorM" required>
                                <div class="invalid-feedback">
                                    Por favor ingrese el color.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- L -->
                <div class="col">
                    <div class="card h-100">
                        <div class="card-header py-1 bg-light">
                            <h6 class="card-title mb-0">Talla L</h6>
                        </div>
                        <div class="card-body p-2">
                            <div class="mb-2">
                                <label for="quantityL" class="form-label required">Cantidad</label>
                                <input type="number" class="form-control" id="quantityL" min="0" required>
                                <div class="invalid-feedback">
                                    Por favor ingrese la cantidad.
                                </div>
                            </div>
                            <div class="mb-0">
                                <label for="colorL" class="form-label required">Color</label>
                                <input type="text" class="form-control" id="colorL" required>
                                <div class="invalid-feedback">
                                    Por favor ingrese el color.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- XL -->
                <div class="col">
                    <div class="card h-100">
                        <div class="card-header py-1 bg-light">
                            <h6 class="card-title mb-0">Talla XL</h6>
                        </div>
                        <div class="card-body p-2">
                            <div class="mb-2">
                                <label for="quantityXL" class="form-label required">Cantidad</label>
                                <input type="number" class="form-control" id="quantityXL" min="0" required>
                                <div class="invalid-feedback">
                                    Por favor ingrese la cantidad.
                                </div>
                            </div>
                            <div class="mb-0">
                                <label for="colorXL" class="form-label required">Color</label>
                                <input type="text" class="form-control" id="colorXL" required>
                                <div class="invalid-feedback">
                                    Por favor ingrese el color.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="d-grid gap-2 mt-3">
                <button class="btn btn-primary" type="submit">Guardar Producto</button>
            </div>
        </form>
    </div>
    
    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Manejar la vista previa de la imagen
        const imagePreview = document.getElementById('imagePreview');
        const imageInput = document.getElementById('imageInput');
        const previewImg = document.getElementById('previewImg');
        
        // Cuando se hace clic en el área de vista previa, activar el input de archivo
        imagePreview.addEventListener('click', function() {
            imageInput.click();
        });
        
        // Cuando se selecciona un archivo, mostrar la vista previa
        imageInput.addEventListener('change', function() {
            const file = this.files[0];
            
            if (file) {
                const reader = new FileReader();
                
                reader.addEventListener('load', function() {
                    previewImg.src = reader.result;
                    previewImg.style.display = 'block';
                    imagePreview.classList.add('has-image');
                });
                
                reader.readAsDataURL(file);
            } else {
                previewImg.src = '';
                previewImg.style.display = 'none';
                imagePreview.classList.remove('has-image');
            }
        });
        
        // Validación del formulario
        (function () {
            'use strict'
            
            // Obtener todos los formularios a los que queremos aplicar estilos de validación de Bootstrap personalizados
            const forms = document.querySelectorAll('.needs-validation');
            
            // Bucle sobre ellos y evitar el envío
            Array.from(forms).forEach(form => {
                form.addEventListener('submit', event => {
                    if (!form.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();
                    } else {
                        event.preventDefault();
                        alert('Formulario enviado correctamente!');
                        // Aquí puedes agregar el código para enviar los datos a tu servidor
                    }
                    
                    form.classList.add('was-validated');
                }, false);
            });
        })();
    </script>
</body>
</html>
