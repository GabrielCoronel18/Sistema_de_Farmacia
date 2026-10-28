<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ventas | Farmacia</title>
    <link rel="stylesheet" href="public/bootstrap-5.3.8-dist/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<main class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Ventas</h1>
        <a href="index.php?url=Dashboard" class="btn btn-outline-secondary">Volver al menú</a>
    </div>

    <?php if ($error_message !== null): ?>
        <div class="alert alert-danger" role="alert"><?= htmlspecialchars($error_message, ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8") ?></div>
    <?php endif; ?>
    <button class="btn btn-primary mb-3" type="button" data-bs-toggle="modal" data-bs-target="#ventaModal">
        Registrar venta
    </button>

    <div class="table-responsive">
        <table class="table bg-white">
            <thead >
                <tr><th class="bg-primary text-white">ID</th><th class="bg-primary text-white">Fecha</th><th class="bg-primary text-white">Cliente</th><th class="bg-primary text-white">Empleado</th><th class="bg-primary text-white">Total</th><th class="bg-primary text-white">Estado</th><th class="bg-primary text-white">Acciones</th></tr>
            </thead>
            <tbody>
                <?php foreach ($ventas as $venta): ?>
                    <tr>
                        <td><?= (int) $venta["id_venta"] ?></td>
                        <td><?= htmlspecialchars($venta["fecha_venta"], ENT_QUOTES, "UTF-8") ?></td>
                        <td><?= htmlspecialchars($venta["nombre_cliente"] . " " . $venta["apellido_cliente"], ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8") ?></td>
                        <td><?= htmlspecialchars($venta["nombre_empleado"] . " " . $venta["apellido_empleado"], ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8") ?></td>
                        <td><?= htmlspecialchars((string) $venta["total_venta"], ENT_QUOTES, "UTF-8") ?></td>
                        <td><?= (int) $venta["estado_venta"] === 1 ? "Activa" : "Anulada" ?></td>
                        <td>
                            <div class="d-flex gap-2">
                                <a class="btn btn-sm btn-outline-primary" href="index.php?url=Ventas&detalle=<?= (int) $venta["id_venta"] ?>">Detalle</a>
                                <?php if ((int) $venta["estado_venta"] === 1): ?>
                                    <form method="post" onsubmit="return confirm('¿Anular esta venta y devolver las cantidades al stock?');">
                                        <input type="hidden" name="id_venta" value="<?= (int) $venta["id_venta"] ?>">
                                        <button class="btn btn-sm btn-danger" type="submit" name="anularVenta" value="1">Anular</button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</main>

<div class="modal fade" id="ventaModal" tabindex="-1" aria-labelledby="ventaModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title fs-5" id="ventaModalTitle">Registrar venta</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <form method="post">
                <div class="modal-body">
                    <?php if ($showSaleModal && $error_message !== null): ?>
                        <div class="alert alert-danger" role="alert"><?= htmlspecialchars($error_message, ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8") ?></div>
                    <?php endif; ?>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label" for="cedula_cliente">Cliente</label>
                            <select class="form-select" id="cedula_cliente" name="cedula_cliente" required>
                                <option value="">Seleccionar</option>
                                <?php foreach ($opciones["clientes"] as $cliente): ?>
                                    <option value="<?= htmlspecialchars((string) $cliente["id"], ENT_QUOTES, "UTF-8") ?>"><?= htmlspecialchars($cliente["nombre"], ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8") ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="cedula_empleado">Empleado</label>
                            <select class="form-select" id="cedula_empleado" name="cedula_empleado" required>
                                <option value="">Seleccionar</option>
                                <?php foreach ($opciones["empleados"] as $empleado): ?>
                                    <option value="<?= htmlspecialchars((string) $empleado["id"], ENT_QUOTES, "UTF-8") ?>"><?= htmlspecialchars($empleado["nombre"], ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8") ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="mt-3" id="articulosVenta">
                        <div class="row g-2 align-items-end articulo-venta mb-2">
                            <div class="col-md-8">
                                <label class="form-label">Medicamento</label>
                                <select class="form-select" name="medicamento[]" required>
                                    <option value="">Seleccionar</option>
                                    <?php foreach ($opciones["medicamentos"] as $medicamento): ?>
                                        <option value="<?= (int) $medicamento["id"] ?>">
                                            <?= htmlspecialchars($medicamento["nombre"] . " (stock: " . $medicamento["stock"] . ")", ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8") ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Cantidad</label>
                                <input class="form-control" type="number" name="cantidad[]" min="1" step="1" required>
                            </div>
                            <div class="col-md-1">
                                <button class="btn btn-outline-danger quitarArticulo" type="button" aria-label="Quitar artículo">X</button>
                            </div>
                        </div>
                    </div>
                    <button class="btn btn-outline-primary btn-sm" type="button" id="agregarArticulo">Agregar medicamento</button>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button class="btn btn-primary" type="submit" name="crearVenta" value="1">Guardar venta</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php if ($detalleId): ?>
    <div class="modal fade" id="detalleModal" tabindex="-1" aria-labelledby="detalleModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title fs-5" id="detalleModalTitle">Detalle de la venta #<?= (int) $detalleId ?></h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <?php if ($detalles === []): ?>
                        <p class="mb-0">Esta venta no tiene artículos registrados.</p>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead><tr><th>Medicamento</th><th>Código</th><th>Laboratorio</th><th>Precio</th><th>Cantidad</th></tr></thead>
                                <tbody>
                                    <?php foreach ($detalles as $detalle): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($detalle["nombre"], ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8") ?></td>
                                            <td><?= htmlspecialchars((string) $detalle["codigo"], ENT_QUOTES, "UTF-8") ?></td>
                                            <td><?= htmlspecialchars($detalle["nombre_laboratorio"], ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8") ?></td>
                                            <td><?= htmlspecialchars((string) $detalle["precio"], ENT_QUOTES, "UTF-8") ?></td>
                                            <td><?= (int) $detalle["cantidad"] ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="modal-footer">
                    <a href="index.php?url=Ventas" class="btn btn-secondary">Cerrar</a>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const articulosVenta = document.getElementById("articulosVenta");
    const articuloBase = articulosVenta.querySelector(".articulo-venta");
    document.getElementById("agregarArticulo").addEventListener("click", () => {
        const articulo = articuloBase.cloneNode(true);
        articulo.querySelector("select").selectedIndex = 0;
        articulo.querySelector("input").value = "";
        articulosVenta.appendChild(articulo);
    });
    articulosVenta.addEventListener("click", (event) => {
        if (event.target.classList.contains("quitarArticulo") && articulosVenta.querySelectorAll(".articulo-venta").length > 1) {
            event.target.closest(".articulo-venta").remove();
        }
    });
    <?php if ($showSaleModal): ?>
        new bootstrap.Modal(document.getElementById("ventaModal")).show();
    <?php elseif ($detalleId): ?>
        new bootstrap.Modal(document.getElementById("detalleModal")).show();
    <?php endif; ?>
</script>
</body>
<script src="public/bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js"></script>
</html>
