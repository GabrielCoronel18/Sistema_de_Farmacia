<?php
$escape = static fn($value): string => htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clientes | Farmacia</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<main class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="mb-0">Clientes</h1>
        <a href="index.php?url=Dashboard" class="btn btn-outline-secondary">Volver</a>
    </div>
    <button class="btn btn-primary mb-3" type="button" id="agregarCliente" data-bs-toggle="modal" data-bs-target="#clienteModal">Agregar nuevo cliente</button>
    <div class="table-responsive">
        <table class="table shadow-sm">
            <thead>
                <tr>
                    <th class="bg-primary text-light">Cédula</th>
                    <th class="bg-primary text-light">Nombre</th>
                    <th class="bg-primary text-light">Apellido</th>
                    <th class="bg-primary text-light">Teléfono</th>
                    <th class="bg-primary text-light">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($records as $record): ?>
                    <tr>
                        <td><?= $escape($record["cedula_cliente"]) ?></td>
                        <td><?= $escape($record["nombre_cliente"]) ?></td>
                        <td><?= $escape($record["apellido_cliente"]) ?></td>
                        <td><?= $escape($record["telefono_cliente"]) ?></td>
                        <td>
                            <div class="d-flex gap-2">
                                <button class="btn btn-primary btn-sm editarCliente" type="button" data-bs-toggle="modal" data-bs-target="#clienteModal"
                                    data-id="<?= $escape($record["cedula_cliente"]) ?>"
                                    data-cedula_cliente="<?= $escape($record["cedula_cliente"]) ?>"
                                    data-nombre_cliente="<?= $escape($record["nombre_cliente"]) ?>"
                                    data-apellido_cliente="<?= $escape($record["apellido_cliente"]) ?>"
                                    data-telefono_cliente="<?= $escape($record["telefono_cliente"]) ?>">Editar</button>
                                <form method="post">
                                    <input type="hidden" name="id" value="<?= $escape($record["cedula_cliente"]) ?>">
                                    <button class="btn btn-danger btn-sm" type="submit" name="delete" value="1">Eliminar</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</main>
<div class="modal fade" id="clienteModal" tabindex="-1" aria-labelledby="clienteModalTitle" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title fs-5" id="clienteModalTitle">Agregar cliente</h2>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <form method="post">
                <div class="modal-body">
                    <input type="hidden" name="id" id="clienteId" value="<?= $escape($formData["cedula_cliente"] ?? "") ?>">
                    <div class="mb-3"><label class="form-label" for="cedula_cliente">Cédula</label><input class="form-control" type="number" name="cedula_cliente" id="cedula_cliente" value="<?= $escape($formData["cedula_cliente"] ?? "") ?>" required></div>
                    <div class="mb-3"><label class="form-label" for="nombre_cliente">Nombre</label><input class="form-control" type="text" name="nombre_cliente" id="nombre_cliente" value="<?= $escape($formData["nombre_cliente"] ?? "") ?>" required></div>
                    <div class="mb-3"><label class="form-label" for="apellido_cliente">Apellido</label><input class="form-control" type="text" name="apellido_cliente" id="apellido_cliente" value="<?= $escape($formData["apellido_cliente"] ?? "") ?>" required></div>
                    <div class="mb-3"><label class="form-label" for="telefono_cliente">Teléfono</label><input class="form-control" type="text" name="telefono_cliente" id="telefono_cliente" value="<?= $escape($formData["telefono_cliente"] ?? "") ?>" required></div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Cerrar</button>
                    <button class="btn btn-primary" type="submit" name="save" value="1" id="guardarCliente"><?= $editing ? "Guardar cambios" : "Guardar" ?></button>
                </div>
            </form>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const clienteForm = document.querySelector("#clienteModal form");
    const clienteFields = ["cedula_cliente", "nombre_cliente", "apellido_cliente", "telefono_cliente"];
    document.getElementById("agregarCliente").addEventListener("click", () => {
        clienteForm.reset();
        document.getElementById("clienteId").value = "";
        document.getElementById("clienteModalTitle").textContent = "Agregar cliente";
        document.getElementById("guardarCliente").textContent = "Guardar";
    });
    document.querySelectorAll(".editarCliente").forEach((button) => {
        button.addEventListener("click", () => {
            clienteForm.reset();
            document.getElementById("clienteId").value = button.dataset.id;
            clienteFields.forEach((name) => { clienteForm.elements[name].value = button.getAttribute("data-" + name) ?? ""; });
            document.getElementById("clienteModalTitle").textContent = "Actualizar cliente";
            document.getElementById("guardarCliente").textContent = "Actualizar";
        });
    });
    <?php if ($editing): ?>new bootstrap.Modal(document.getElementById("clienteModal")).show();<?php endif; ?>
</script>
</body>
</html>
