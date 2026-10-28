<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medicamentos | Farmacia</title>
    <link rel="stylesheet" href="public/bootstrap-5.3.8-dist/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
</head>
<body class="bg-light">
    <div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h1 class="mb-0">Medicamentos</h1>
      <a href="index.php?url=Dashboard" class="btn btn-outline-secondary">Volver</a>
    </div>
     
    <button type="button" id="agregarMedicamento" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#exampleModal">
    Agregar nuevo medicamento
     </button>
    
     <div class="table-responsive">
     <table class="table shadow-sm">
      <thead >
          <tr>
            <th class="bg-primary text-light">ID</th>
            <th class="bg-primary text-light">Código</th>
            <th class="bg-primary text-light">Nombre</th>
            <th class="bg-primary text-light">Laboratorio</th>
            <th class="bg-primary text-light">Vencimiento</th>
            <th class="bg-primary text-light">Precio</th>
            <th class="bg-primary text-light">Stock</th>
            <th class="bg-primary text-light">Acciones</th>
            
          </tr>
      </thead>
      <tbody>
             
             <?php 
             $escape = static fn($value): string => htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8");
             $resultado = $medicamentoModel->obtenerMedicamentos();
             foreach($resultado as $e){
                echo "<tr>"
                  . "<td>" . $escape($e["id_medicamento"]) . "</td>"
                  . "<td>" . $escape($e["codigo"]) . "</td>"
                  . "<td>" . $escape($e["nombre"]) . "</td>"
                  . "<td>" . $escape($e["nombre_laboratorio"]) . "</td>"
                  . "<td>" . $escape($e["fecha_vencimiento"]) . "</td>"
                  . "<td>" . $escape($e["precio"]) . "<span><small>$</small></span></td>"
                  . "<td>" . $escape($e["stock"]) . "</td>"
                  . "<td><div class='d-flex gap-2'>"
                  . "<button type='button' class='btn btn-primary btn-sm editarMedicamento' "
                  . "data-bs-toggle='modal' data-bs-target='#exampleModal' "
                  . "data-id='" . $escape($e["id_medicamento"]) . "' "
                  . "data-codigo='" . $escape($e["codigo"]) . "' "
                  . "data-nombre='" . $escape($e["nombre"]) . "' "
                  . "data-laboratorio='" . $escape($e["id_laboratorio"]) . "' "
                  . "data-fecha='" . $escape($e["fecha_vencimiento"]) . "' "
                  . "data-precio='" . $escape($e["precio"]) . "' "
                  . "data-stock='" . $escape($e["stock"]) . "'>Actualizar</button>"
                  . "<form method='post' class='d-flex gap-2'>"
                  . "<input type='hidden' name='id_medicamento' value='" . $escape($e["id_medicamento"]) . "'>"
                  . "<button type='submit' class='btn btn-danger btn-sm' name='Eliminar'>Eliminar</button>"
                  . "</form></div></td></tr>";
             }
                
                ?>
      </tbody>
    </table>
    </div>
    </div>
  <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Agregar nuevo medicamento</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form method="post" id="medicamentoForm" novalidate>
      <div class="modal-body">
        
        <input type="hidden" name="id_medicamento" id="id_medicamento">
        <div class="mb-3">
          <label for="codigo" class="form-label">Codigo</label>
          <input type="text" class="form-control" id="codigo" name="codigo" required>
          <div class="invalid-feedback" id="error-codigo"></div>
        </div>
        <div class="mb-3">
          <label for="nombre" class="form-label">Nombre</label>
          <input type="text" class="form-control" id="nombre" name="nombre" required>
          <div class="invalid-feedback" id="error-nombre"></div>
        </div>
        <div class="mb-3">
          <label for="laboratorio" class="form-label">Laboratorio</label>
          <select class="form-select" id="laboratorio" aria-label="Default select example" name="laboratorio" required>
              <option selected value="">Seleccionar</option>
              <?php
              $laboratorios = $medicamentoModel->listarLaboratorios();
              foreach($laboratorios as $laboratorio){
                  echo "<option value='".$laboratorio['id_laboratorio']."'>".$laboratorio['nombre_laboratorio']."</option>";
              }
              ?>
            </select>
          <div class="invalid-feedback" id="error-laboratorio"></div>
        </div>
        <div class="mb-3">
          <label for="fecha_vencimiento" class="form-label">Fecha de vencimiento</label>
          <input type="date" class="form-control" id="fecha_vencimiento" name="fecha_vencimiento" required>
          <div class="invalid-feedback" id="error-fecha_vencimiento"></div>
        </div>
        <div class="mb-3">
          <label for="precio" class="form-label">Precio</label>
          <input type="number" step="0.01" min="0" class="form-control" id="precio" name="precio" required>
          <div class="invalid-feedback" id="error-precio"></div>
        </div>
        <div class="mb-3">
          <label for="stock" class="form-label">Stock</label>
          <input type="number" min="0" step="1" class="form-control" id="stock" name="stock" required>
          <div class="invalid-feedback" id="error-stock"></div>
        </div>
      
         
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        <button type="submit" id="guardarMedicamento" class="btn btn-primary" value="Agregar" name="Agregar">Guardar</button>
      </div>
      </form>
    </div>
      
  </div>
</div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <script src="public/js/Medicamento.js"></script>
    <script src="public/bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
