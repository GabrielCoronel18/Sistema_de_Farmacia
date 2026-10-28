<?php
namespace Usuario\SistemaDeFarmacia\controller;

use Usuario\SistemaDeFarmacia\model\RolModel;

require_once 'src/helpers/auth.php';

verificarRol([1]);

$model = new RolModel();
$formData = [];
$editing = false;

if (isset($_POST["save"])) {
    $nombre = trim($_POST["nombre_rol"] ?? "");
    if (!empty($_POST["id"])) {
        $model->actualizar((int) $_POST["id"], $nombre);
    } else {
        $model->agregar($nombre);
    }
    header("Location: index.php?url=Rol");
    exit();
}
if (isset($_POST["delete"])) {
    $model->eliminar((int) $_POST["id"]);
    header("Location: index.php?url=Rol");
    exit();
}
if (isset($_GET["editar"])) {
    $formData = $model->obtenerPorId((int) $_GET["editar"]) ?: [];
    $editing = $formData !== [];
}

$records = $model->obtenerTodos();
require "src/view/RolView.php";
