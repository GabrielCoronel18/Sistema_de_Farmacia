<?php
namespace Usuario\SistemaDeFarmacia\controller;

use Usuario\SistemaDeFarmacia\model\ProveedorModel;

require_once 'src/helpers/auth.php';

verificarRol([1,2]);

$model = new ProveedorModel();
$fields = [
    "nombre_proveedor" => ["label" => "Nombre", "type" => "text"],
    "telefono_proveedor" => ["label" => "Teléfono", "type" => "number"],
    "email_proveedor" => ["label" => "Correo", "type" => "email"],
];
$formData = [];
$editing = false;

if (isset($_POST["save"])) {
    $formData = array_intersect_key($_POST, $fields);
    if (!empty($_POST["id"])) {
        $model->actualizar((int) $_POST["id"], $formData);
    } else {
        $model->agregar($formData);
    }
    header("Location: index.php?url=Proveedor");
    exit();
}
if (isset($_POST["delete"])) {
    $model->eliminar((int) $_POST["id"]);
    header("Location: index.php?url=Proveedor");
    exit();
}
if (isset($_GET["editar"])) {
    $formData = $model->obtenerPorId((int) $_GET["editar"]) ?: [];
    $editing = $formData !== [];
}

$records = $model->obtenerTodos();
require "src/view/ProveedorView.php";
