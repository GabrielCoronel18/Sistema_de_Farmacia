<?php
$escape = static fn($value): string => htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proveedores | Farmacia</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<main class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3"><h1 class="mb-0">Proveedores</h1><a href="index.php?url=Dashboard" class="btn btn-outline-secondary">Volver</a></div>
    <button class="btn btn-primary mb-3" type="button" id="agregarProveedor" data-bs-toggle="modal" data-bs-target="#proveedorModal">Agregar nuevo proveedor</button>
    <div class="table-responsive"><table class="table shadow-sm">
        <thead><tr><th class="bg-primary text-light">Nombre</th><th class="bg-primary text-light">Teléfono</th><th class="bg-primary text-light">Correo</th><th class="bg-primary text-light">Acciones</th></tr></thead>
        <tbody><?php foreach ($records as $record): ?><tr>
            <td><?= $escape($record["nombre_proveedor"]) ?></td><td><?= $escape($record["telefono_proveedor"]) ?></td><td><?= $escape($record["email_proveedor"]) ?></td>
            <td><div class="d-flex gap-2">
                <button class="btn btn-primary btn-sm editarProveedor" type="button" data-bs-toggle="modal" data-bs-target="#proveedorModal"
                    data-id="<?= $escape($record["id_proveedor"]) ?>" data-nombre_proveedor="<?= $escape($record["nombre_proveedor"]) ?>"
                    data-telefono_proveedor="<?= $escape($record["telefono_proveedor"]) ?>" data-email_proveedor="<?= $escape($record["email_proveedor"]) ?>">Editar</button>
                <form method="post"><input type="hidden" name="id" value="<?= $escape($record["id_proveedor"]) ?>"><button class="btn btn-danger btn-sm" type="submit" name="delete" value="1">Eliminar</button></form>
            </div></td>
        </tr><?php endforeach; ?></tbody>
    </table></div>
</main>
<div class="modal fade" id="proveedorModal" tabindex="-1" aria-labelledby="proveedorModalTitle" aria-hidden="true"><div class="modal-dialog"><div class="modal-content">
    <div class="modal-header"><h2 class="modal-title fs-5" id="proveedorModalTitle">Agregar proveedor</h2><button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Cerrar"></button></div>
    <form method="post"><div class="modal-body">
        <input type="hidden" name="id" id="proveedorId" value="<?= $escape($formData["id_proveedor"] ?? "") ?>">
        <div class="mb-3"><label class="form-label" for="nombre_proveedor">Nombre</label><input class="form-control" type="text" name="nombre_proveedor" id="nombre_proveedor" value="<?= $escape($formData["nombre_proveedor"] ?? "") ?>" required></div>
        <div class="mb-3"><label class="form-label" for="telefono_proveedor">Teléfono</label><input class="form-control" type="number" name="telefono_proveedor" id="telefono_proveedor" value="<?= $escape($formData["telefono_proveedor"] ?? "") ?>" required></div>
        <div class="mb-3"><label class="form-label" for="email_proveedor">Correo</label><input class="form-control" type="email" name="email_proveedor" id="email_proveedor" value="<?= $escape($formData["email_proveedor"] ?? "") ?>" required></div>
    </div><div class="modal-footer"><button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Cerrar</button><button class="btn btn-primary" type="submit" name="save" value="1" id="guardarProveedor"><?= $editing ? "Guardar cambios" : "Guardar" ?></button></div></form>
</div></div></div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const proveedorForm = document.querySelector("#proveedorModal form");
    const proveedorFields = ["nombre_proveedor", "telefono_proveedor", "email_proveedor"];
    document.getElementById("agregarProveedor").addEventListener("click", () => {
        proveedorForm.reset(); document.getElementById("proveedorId").value = "";
        document.getElementById("proveedorModalTitle").textContent = "Agregar proveedor";
        document.getElementById("guardarProveedor").textContent = "Guardar";
    });
    document.querySelectorAll(".editarProveedor").forEach((button) => {
        button.addEventListener("click", () => {
            proveedorForm.reset(); document.getElementById("proveedorId").value = button.dataset.id;
            proveedorFields.forEach((name) => { proveedorForm.elements[name].value = button.getAttribute("data-" + name) ?? ""; });
            document.getElementById("proveedorModalTitle").textContent = "Actualizar proveedor";
            document.getElementById("guardarProveedor").textContent = "Actualizar";
        });
    });
    <?php if ($editing): ?>new bootstrap.Modal(document.getElementById("proveedorModal")).show();<?php endif; ?>
</script>
</body>
</html>
