<?php
$escape = static fn($value): string => htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laboratorios | Farmacia</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<main class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3"><h1 class="mb-0">Laboratorios</h1><a href="index.php?url=Dashboard" class="btn btn-outline-secondary">Volver</a></div>
    <button class="btn btn-primary mb-3" type="button" id="agregarLaboratorio" data-bs-toggle="modal" data-bs-target="#laboratorioModal">Agregar nuevo laboratorio</button>
    <div class="table-responsive"><table class="table shadow-sm">
        <thead><tr><th class="bg-primary text-light">Nombre del laboratorio</th><th class="bg-primary text-light">Acciones</th></tr></thead>
        <tbody><?php foreach ($records as $record): ?><tr>
            <td><?= $escape($record["nombre_laboratorio"]) ?></td>
            <td><div class="d-flex gap-2">
                <button class="btn btn-primary btn-sm editarLaboratorio" type="button" data-bs-toggle="modal" data-bs-target="#laboratorioModal" data-id="<?= $escape($record["id_laboratorio"]) ?>" data-nombre="<?= $escape($record["nombre_laboratorio"]) ?>">Editar</button>
                <form method="post"><input type="hidden" name="id" value="<?= $escape($record["id_laboratorio"]) ?>"><button class="btn btn-danger btn-sm" type="submit" name="delete" value="1">Eliminar</button></form>
            </div></td>
        </tr><?php endforeach; ?></tbody>
    </table></div>
</main>
<div class="modal fade" id="laboratorioModal" tabindex="-1" aria-labelledby="laboratorioModalTitle" aria-hidden="true"><div class="modal-dialog"><div class="modal-content">
    <div class="modal-header"><h2 class="modal-title fs-5" id="laboratorioModalTitle">Agregar laboratorio</h2><button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Cerrar"></button></div>
    <form method="post"><div class="modal-body">
        <input type="hidden" name="id" id="laboratorioId" value="<?= $escape($formData["id_laboratorio"] ?? "") ?>">
        <div class="mb-3"><label class="form-label" for="nombre_laboratorio">Nombre del laboratorio</label><input class="form-control" type="text" name="nombre_laboratorio" id="nombre_laboratorio" value="<?= $escape($formData["nombre_laboratorio"] ?? "") ?>" required></div>
    </div><div class="modal-footer"><button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Cerrar</button><button class="btn btn-primary" type="submit" name="save" value="1" id="guardarLaboratorio"><?= $editing ? "Guardar cambios" : "Guardar" ?></button></div></form>
</div></div></div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const laboratorioForm = document.querySelector("#laboratorioModal form");
    document.getElementById("agregarLaboratorio").addEventListener("click", () => {
        laboratorioForm.reset(); document.getElementById("laboratorioId").value = "";
        document.getElementById("laboratorioModalTitle").textContent = "Agregar laboratorio";
        document.getElementById("guardarLaboratorio").textContent = "Guardar";
    });
    document.querySelectorAll(".editarLaboratorio").forEach((button) => {
        button.addEventListener("click", () => {
            laboratorioForm.reset(); document.getElementById("laboratorioId").value = button.dataset.id;
            laboratorioForm.elements.nombre_laboratorio.value = button.dataset.nombre ?? "";
            document.getElementById("laboratorioModalTitle").textContent = "Actualizar laboratorio";
            document.getElementById("guardarLaboratorio").textContent = "Actualizar";
        });
    });
    <?php if ($editing): ?>new bootstrap.Modal(document.getElementById("laboratorioModal")).show();<?php endif; ?>
</script>
</body>
</html>
