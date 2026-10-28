<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ingresos de inventario | Farmacia</title>
    <link rel="stylesheet" href="public/bootstrap-5.3.8-dist/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<main class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Ingresos de inventario</h1>
        <a href="index.php?url=Dashboard" class="btn btn-outline-secondary">Volver al menú</a>
    </div>
    <p class="text-muted">Registrar una entrega aumenta el stock del medicamento.</p>
    <?php if ($error_message !== null): ?>
        <div class="alert alert-danger" role="alert"><?= htmlspecialchars($error_message, ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8") ?></div>
    <?php endif; ?>

    <button class="btn btn-primary mb-3" type="button" data-bs-toggle="modal" data-bs-target="#ingresoModal">
        Registrar ingreso
    </button>

    <div class="table-responsive">
        <table class="table table-striped bg-white">
            <thead><tr><th class="bg-primary text-white">ID</th><th class="bg-primary text-white">Medicamento</th><th class="bg-primary text-white">Proveedor</th><th class="bg-primary text-white">Precio</th><th class="bg-primary text-white">Cantidad</th><th class="bg-primary text-white">Fecha</th></tr></thead>
            <tbody>
                <?php foreach ($data["ingresos"] as $ingreso): ?>
                    <tr>
                        <td><?= (int) $ingreso["id_med_prov"] ?></td>
                        <td><?= htmlspecialchars($ingreso["medicamento"], ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8") ?></td>
                        <td><?= htmlspecialchars($ingreso["nombre_proveedor"], ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8") ?></td>
                        <td><?= (int) $ingreso["precio_entrega"] ?></td>
                        <td><?= (int) $ingreso["cantidad_entrega"] ?></td>
                        <td><?= htmlspecialchars($ingreso["fecha_entrega"], ENT_QUOTES, "UTF-8") ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</main>

<div class="modal fade" id="ingresoModal" tabindex="-1" aria-labelledby="ingresoModalTitle" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title fs-5" id="ingresoModalTitle">Registrar ingreso</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <form method="post">
                <div class="modal-body">
                    <?php if ($showIngresoModal && $error_message !== null): ?>
                        <div class="alert alert-danger" role="alert"><?= htmlspecialchars($error_message, ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8") ?></div>
                    <?php endif; ?>
                    <div class="mb-3">
                        <label class="form-label" for="id_medicamento">Medicamento</label>
                        <select class="form-select" name="id_medicamento" id="id_medicamento" required>
                            <option value="">Seleccionar</option>
                            <?php foreach ($data["medicamentos"] as $medicamento): ?>
                                <option value="<?= (int) $medicamento["id_medicamento"] ?>">
                                    <?= htmlspecialchars($medicamento["nombre"] . " (stock actual: " . $medicamento["stock"] . ")", ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8") ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="id_proveedor">Proveedor</label>
                        <select class="form-select" name="id_proveedor" id="id_proveedor" required>
                            <option value="">Seleccionar</option>
                            <?php foreach ($data["proveedores"] as $proveedor): ?>
                                <option value="<?= (int) $proveedor["id_proveedor"] ?>"><?= htmlspecialchars($proveedor["nombre_proveedor"], ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8") ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="precio_entrega">Precio de entrega</label>
                        <input class="form-control" type="number" min="0" step="1" name="precio_entrega" id="precio_entrega" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="cantidad_entrega">Cantidad recibida</label>
                        <input class="form-control" type="number" min="1" step="1" name="cantidad_entrega" id="cantidad_entrega" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="fecha_entrega">Fecha de entrega</label>
                        <input class="form-control" type="date" name="fecha_entrega" id="fecha_entrega" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button class="btn btn-primary" type="submit" name="registrarIngreso" value="1">Guardar ingreso</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
<script src="public/bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js"></script>
<?php if ($showIngresoModal): ?>
    <script>new bootstrap.Modal(document.getElementById("ingresoModal")).show();</script>
<?php endif; ?>
</body>
</html>
