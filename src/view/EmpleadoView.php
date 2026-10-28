<?php
$escape = static fn($value): string => htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Empleados | Farmacia</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<main class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="mb-0">Empleados</h1>
        <a href="index.php?url=Dashboard" class="btn btn-outline-secondary">Volver</a>
    </div>
    <button class="btn btn-primary mb-3" type="button" id="agregarEmpleado" data-bs-toggle="modal" data-bs-target="#empleadoModal">Agregar nuevo empleado</button>
    <div class="table-responsive">
        <table class="table shadow-sm">
            <thead><tr>
                <th class="bg-primary text-light">Cédula</th><th class="bg-primary text-light">Nombre</th><th class="bg-primary text-light">Apellido</th>
                <th class="bg-primary text-light">Teléfono</th><th class="bg-primary text-light">Correo</th><th class="bg-primary text-light">Dirección</th><th class="bg-primary text-light">Acciones</th>
            </tr></thead>
            <tbody>
                <?php foreach ($records as $record): ?>
                    <tr>
                        <td><?= $escape($record["cedula_empleado"]) ?></td><td><?= $escape($record["nombre_empleado"]) ?></td><td><?= $escape($record["apellido_empleado"]) ?></td>
                        <td><?= $escape($record["telefono_empleado"]) ?></td><td><?= $escape($record["correo_empleado"]) ?></td><td><?= $escape($record["direccion_empleado"]) ?></td>
                        <td><div class="d-flex gap-2">
                            <button class="btn btn-primary btn-sm editarEmpleado" type="button" data-bs-toggle="modal" data-bs-target="#empleadoModal"
                                data-id="<?= $escape($record["cedula_empleado"]) ?>"
                                data-cedula_empleado="<?= $escape($record["cedula_empleado"]) ?>"
                                data-nombre_empleado="<?= $escape($record["nombre_empleado"]) ?>"
                                data-apellido_empleado="<?= $escape($record["apellido_empleado"]) ?>"
                                data-telefono_empleado="<?= $escape($record["telefono_empleado"]) ?>"
                                data-correo_empleado="<?= $escape($record["correo_empleado"]) ?>"
                                data-direccion_empleado="<?= $escape($record["direccion_empleado"]) ?>">Editar</button>
                            <form method="post"><input type="hidden" name="id" value="<?= $escape($record["cedula_empleado"]) ?>"><button class="btn btn-danger btn-sm" type="submit" name="delete" value="1">Eliminar</button></form>
                        </div></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</main>
<div class="modal fade" id="empleadoModal" tabindex="-1" aria-labelledby="empleadoModalTitle" aria-hidden="true"><div class="modal-dialog"><div class="modal-content">
    <div class="modal-header"><h2 class="modal-title fs-5" id="empleadoModalTitle">Agregar empleado</h2><button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Cerrar"></button></div>
    <form method="post"><div class="modal-body">
        <input type="hidden" name="id" id="empleadoId" value="<?= $escape($formData["cedula_empleado"] ?? "") ?>">
        <div class="mb-3"><label class="form-label" for="cedula_empleado">Cédula</label><input class="form-control" type="number" name="cedula_empleado" id="cedula_empleado" value="<?= $escape($formData["cedula_empleado"] ?? "") ?>" required></div>
        <div class="mb-3"><label class="form-label" for="nombre_empleado">Nombre</label><input class="form-control" type="text" name="nombre_empleado" id="nombre_empleado" value="<?= $escape($formData["nombre_empleado"] ?? "") ?>" required></div>
        <div class="mb-3"><label class="form-label" for="apellido_empleado">Apellido</label><input class="form-control" type="text" name="apellido_empleado" id="apellido_empleado" value="<?= $escape($formData["apellido_empleado"] ?? "") ?>" required></div>
        <div class="mb-3"><label class="form-label" for="telefono_empleado">Teléfono</label><input class="form-control" type="number" name="telefono_empleado" id="telefono_empleado" value="<?= $escape($formData["telefono_empleado"] ?? "") ?>" required></div>
        <div class="mb-3"><label class="form-label" for="correo_empleado">Correo</label><input class="form-control" type="email" name="correo_empleado" id="correo_empleado" value="<?= $escape($formData["correo_empleado"] ?? "") ?>" required></div>
        <div class="mb-3"><label class="form-label" for="direccion_empleado">Dirección</label><input class="form-control" type="text" name="direccion_empleado" id="direccion_empleado" value="<?= $escape($formData["direccion_empleado"] ?? "") ?>" required></div>
    </div><div class="modal-footer"><button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Cerrar</button><button class="btn btn-primary" type="submit" name="save" value="1" id="guardarEmpleado"><?= $editing ? "Guardar cambios" : "Guardar" ?></button></div></form>
</div></div></div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const empleadoForm = document.querySelector("#empleadoModal form");
    const empleadoFields = ["cedula_empleado", "nombre_empleado", "apellido_empleado", "telefono_empleado", "correo_empleado", "direccion_empleado"];
    document.getElementById("agregarEmpleado").addEventListener("click", () => {
        empleadoForm.reset(); document.getElementById("empleadoId").value = "";
        document.getElementById("empleadoModalTitle").textContent = "Agregar empleado";
        document.getElementById("guardarEmpleado").textContent = "Guardar";
    });
    document.querySelectorAll(".editarEmpleado").forEach((button) => {
        button.addEventListener("click", () => {
            empleadoForm.reset(); document.getElementById("empleadoId").value = button.dataset.id;
            empleadoFields.forEach((name) => { empleadoForm.elements[name].value = button.getAttribute("data-" + name) ?? ""; });
            document.getElementById("empleadoModalTitle").textContent = "Actualizar empleado";
            document.getElementById("guardarEmpleado").textContent = "Actualizar";
        });
    });
    <?php if ($editing): ?>new bootstrap.Modal(document.getElementById("empleadoModal")).show();<?php endif; ?>
</script>
</body>
</html>
