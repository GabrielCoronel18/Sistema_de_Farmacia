<?php
$escape = static fn($value): string => htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Roles | Farmacia</title>
     <link rel="stylesheet" href="public/bootstrap-5.3.8-dist/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<main class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3"><h1 class="mb-0">Roles</h1><a href="index.php?url=Dashboard" class="btn btn-outline-secondary">Volver</a></div>
    <button class="btn btn-primary mb-3" type="button" id="agregarRol" data-bs-toggle="modal" data-bs-target="#rolModal">Agregar nuevo rol</button>
    <div class="table-responsive"><table class="table shadow-sm">
        <thead><tr><th class="bg-primary text-light">Nombre del rol</th><th class="bg-primary text-light">Acciones</th></tr></thead>
        <tbody><?php foreach ($records as $record): ?><tr>
            <td><?= $escape($record["nombre_rol"]) ?></td>
            <td><div class="d-flex gap-2">
                <button class="btn btn-primary btn-sm editarRol" type="button" data-bs-toggle="modal" data-bs-target="#rolModal" data-id="<?= $escape($record["id_rol"]) ?>" data-nombre="<?= $escape($record["nombre_rol"]) ?>">Editar</button>
                <form method="post"><input type="hidden" name="id" value="<?= $escape($record["id_rol"]) ?>"><button class="btn btn-danger btn-sm" type="submit" name="delete" value="1">Eliminar</button></form>
            </div></td>
        </tr><?php endforeach; ?></tbody>
    </table></div>
</main>
<div class="modal fade" id="rolModal" tabindex="-1" aria-labelledby="rolModalTitle" aria-hidden="true"><div class="modal-dialog"><div class="modal-content">
    <div class="modal-header"><h2 class="modal-title fs-5" id="rolModalTitle">Agregar rol</h2><button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Cerrar"></button></div>
    <form method="post"><div class="modal-body">
        <input type="hidden" name="id" id="rolId" value="<?= $escape($formData["id_rol"] ?? "") ?>">
        <div class="mb-3"><label class="form-label" for="nombre_rol">Nombre del rol</label><input class="form-control" type="text" name="nombre_rol" id="nombre_rol" value="<?= $escape($formData["nombre_rol"] ?? "") ?>" required></div>
    </div><div class="modal-footer"><button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Cerrar</button><button class="btn btn-primary" type="submit" name="save" value="1" id="guardarRol"><?= $editing ? "Guardar cambios" : "Guardar" ?></button></div></form>
</div></div></div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const rolForm = document.querySelector("#rolModal form");
    document.getElementById("agregarRol").addEventListener("click", () => {
        rolForm.reset(); document.getElementById("rolId").value = "";
        document.getElementById("rolModalTitle").textContent = "Agregar rol";
        document.getElementById("guardarRol").textContent = "Guardar";
    });
    document.querySelectorAll(".editarRol").forEach((button) => {
        button.addEventListener("click", () => {
            rolForm.reset(); document.getElementById("rolId").value = button.dataset.id;
            rolForm.elements.nombre_rol.value = button.dataset.nombre ?? "";
            document.getElementById("rolModalTitle").textContent = "Actualizar rol";
            document.getElementById("guardarRol").textContent = "Actualizar";
        });
    });
    <?php if ($editing): ?>new bootstrap.Modal(document.getElementById("rolModal")).show();<?php endif; ?>
</script>
</body>
<script src="public/bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js"></script>
</html>
