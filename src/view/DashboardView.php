<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Farmacia</title>
    <link rel="stylesheet" href="public/bootstrap-5.3.8-dist/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .card-menu { transition: transform 0.3s ease, box-shadow 0.3s ease; }
        .card-menu:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.1); }
    </style>
</head>
<body class="bg-light">
<main class="container py-5">
    <header class="text-center mb-5">
        <div class="row">
            <div class="col-6 text-start">
                <h1 class="display-4 fw-bold text-primary">Farmacia</h1>
                <p class="text-muted">Gestión de inventario y facturación</p>
            </div>
            <div class="col-6 text-end">
              <div class="d-flex justify-content-end">
             <div class="mb-2 d-flex flex-column">            
                <span>Usuario: <?php echo htmlspecialchars($_SESSION['usuario']['nombre'] ?? 'Usuario')?></span>
                <span class="badge bg-secondary text-end"><?php echo htmlspecialchars($_SESSION['usuario']['rol'] ?? 'Rol')?></span>
            </div>
            </div>
            <form method="post">
                
                <button type="submit" class='btn btn-sm btn-secondary' name="logout">Cerrar Session</button>
            </form>
            </div>
        </div>
    </header>
    <?php
    $modules = [
        "Medicamento" => "Medicamentos",
        "Ventas" => "Ventas",
        "Cliente" => "Clientes",
        "Empleado" => "Empleados",
        "Laboratorio" => "Laboratorios",
        "Proveedor" => "Proveedores",
        "Rol" => "Roles",
        "Usuario" => "Usuarios",
        "Ingreso" => "Ingresos de inventario",
    ];
    ?>
    <div class="row g-4">
        <?php foreach ($modules as $route => $label): ?>
           <div class="col-12 col-md-4">
                <?php if($route == "Usuario" || $route == "Rol"): ?>
                  <?php if($_SESSION["usuario"]["id_rol"] == 1): ?>
                    <div class="card h-100 border-primary card-menu text-center">
                    <div class="card-body d-flex flex-column justify-content-center">
                        <h2 class="h5 card-title"><?= htmlspecialchars($label, ENT_QUOTES, "UTF-8") ?></h2>
                        
                        <a href="index.php?url=<?= htmlspecialchars($route, ENT_QUOTES, "UTF-8") ?>" class="btn btn-primary mt-auto stretched-link">Ir</a>
                    </div>
                    <?php else:?>
                        </div><div>
                   <?php endif;?>
                 </div>
                <?php else:?>
                    
                <div class="card h-100 border-primary card-menu text-center">
                    <div class="card-body d-flex flex-column justify-content-center">
                        <h2 class="h5 card-title"><?= htmlspecialchars($label, ENT_QUOTES, "UTF-8") ?></h2>
                        
                        <a href="index.php?url=<?= htmlspecialchars($route, ENT_QUOTES, "UTF-8") ?>" class="btn btn-primary mt-auto stretched-link">Ir</a>
                    </div>
                </div>
                <?php endif;?>
           
         </div>    
                

        <?php endforeach; ?>
       
    </div>
</main>
</body>
   <script src="public/bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js"></script>
</html>
