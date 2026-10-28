<?php
$escape = static fn($value): string => htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuarios | Farmacia</title>
     <link rel="stylesheet" href="public/bootstrap-5.3.8-dist/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<main class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3"><h1 class="mb-0">Usuarios</h1><a href="index.php?url=Dashboard" class="btn btn-outline-secondary">Volver</a></div>
    <button class="btn btn-primary mb-3" type="button" id="agregarUsuario" data-bs-toggle="modal" data-bs-target="#usuarioModal">Agregar nuevo usuario</button>
    <div class="table-responsive"><table class="table shadow-sm">
        <thead><tr><th class="bg-primary text-light">Empleado</th><th class="bg-primary text-light">Usuario</th><th class="bg-primary text-light">Contraseña</th><th class="bg-primary text-light">Rol</th><th class="bg-primary text-light">Estado</th><th class="bg-primary text-light">Acciones</th></tr></thead>
        <tbody><?php foreach ($records as $record): ?><tr>
            <td><?= $escape($record["empleado"]) ?></td><td><?= $escape($record["nombre"]) ?></td><td>••••••</td><td><?= $escape($record["nombre_rol"]) ?></td><td><?= (int) $record["estado"] === 1 ? "Activo" : "Inactivo" ?></td>
            <td><div class="d-flex gap-2">
                <button class="btn btn-primary btn-sm editarUsuario" type="button" data-bs-toggle="modal" data-bs-target="#usuarioModal"
                    data-id="<?= $escape($record["id"]) ?>" data-cedula_empleado="<?= $escape($record["cedula_empleado"]) ?>"
                    data-nombre="<?= $escape($record["nombre"]) ?>" data-id_rol="<?= $escape($record["id_rol"]) ?>"
                    data-estado="<?= $escape($record["estado"]) ?>">Editar</button>
                <form method="post"><input type="hidden" name="id" value="<?= $escape($record["id"]) ?>"><button class="btn btn-danger btn-sm" type="submit" name="delete" value="1">Eliminar</button></form>
            </div></td>
        </tr><?php endforeach; ?></tbody>
    </table></div>
</main>
<div class="modal fade" id="usuarioModal" tabindex="-1" aria-labelledby="usuarioModalTitle" aria-hidden="true"><div class="modal-dialog"><div class="modal-content">
    <div class="modal-header"><h2 class="modal-title fs-5" id="usuarioModalTitle">Agregar usuario</h2><button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Cerrar"></button></div>
    <form method="post"><div class="modal-body">
        <input type="hidden" name="id" id="usuarioId" value="<?= $escape($formData["id"] ?? "") ?>">
        <div class="mb-3"><label class="form-label" for="cedula_empleado">Empleado</label><select class="form-select" name="cedula_empleado" id="cedula_empleado" required><option value="">Seleccionar</option>
            <?php foreach ($options["cedula_empleado"] as $empleado): $empleadoId = $empleado["cedula_empleado"]; ?>
                <option value="<?= $escape($empleadoId) ?>" <?= (string) ($formData["cedula_empleado"] ?? "") === (string) $empleadoId ? "selected" : "" ?>><?= $escape(trim($empleado["nombre_empleado"] . " " . $empleado["apellido_empleado"])) ?></option>
            <?php endforeach; ?>
        </select></div>
        <div class="mb-3"><label class="form-label" for="nombre">Usuario</label><input class="form-control" type="text" name="nombre" id="nombre" value="<?= $escape($formData["nombre"] ?? "") ?>" required></div>
        <div class="mb-3"><label class="form-label" for="password">Contraseña</label><input class="form-control" type="password" name="password" id="password" value="" <?= $editing ? "" : "required" ?>><small class="form-text">Deja vacío al editar para conservar la contraseña.</small></div>
        <div class="mb-3"><label class="form-label" for="id_rol">Rol</label><select class="form-select" name="id_rol" id="id_rol" required><option value="">Seleccionar</option>
            <?php foreach ($options["id_rol"] as $rol): $rolId = $rol["id_rol"]; ?>
                <option value="<?= $escape($rolId) ?>" <?= (string) ($formData["id_rol"] ?? "") === (string) $rolId ? "selected" : "" ?>><?= $escape($rol["nombre_rol"]) ?></option>
            <?php endforeach; ?>
        </select></div>
        <div class="mb-3"><label class="form-label" for="estado">Estado</label><select class="form-select" name="estado" id="estado" required>
            <option value="1" <?= (string) ($formData["estado"] ?? "1") === "1" ? "selected" : "" ?>>Activo</option>
            <option value="0" <?= (string) ($formData["estado"] ?? "") === "0" ? "selected" : "" ?>>Inactivo</option>
        </select></div>
    </div><div class="modal-footer"><button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Cerrar</button><button class="btn btn-primary" type="submit" name="save" value="1" id="guardarUsuario"><?= $editing ? "Guardar cambios" : "Guardar" ?></button></div></form>
</div></div></div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const usuarioForm = document.querySelector("#usuarioModal form");
    const usuarioPassword = usuarioForm.elements.password;
    document.getElementById("agregarUsuario").addEventListener("click", () => {
        usuarioForm.reset(); document.getElementById("usuarioId").value = ""; usuarioPassword.required = true;
        document.getElementById("usuarioModalTitle").textContent = "Agregar usuario";
        document.getElementById("guardarUsuario").textContent = "Guardar";
    });
    document.querySelectorAll(".editarUsuario").forEach((button) => {
        button.addEventListener("click", () => {
            usuarioForm.reset(); document.getElementById("usuarioId").value = button.dataset.id;
            ["cedula_empleado", "nombre", "id_rol", "estado"].forEach((name) => { usuarioForm.elements[name].value = button.getAttribute("data-" + name) ?? ""; });
            usuarioPassword.required = false;
            document.getElementById("usuarioModalTitle").textContent = "Actualizar usuario";
            document.getElementById("guardarUsuario").textContent = "Actualizar";
        });
    });
    <?php if ($editing): ?>new bootstrap.Modal(document.getElementById("usuarioModal")).show();<?php endif; ?>
</script>
</body>
</html>
