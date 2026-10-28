<?php
namespace Usuario\SistemaDeFarmacia\controller;
use Usuario\SistemaDeFarmacia\model\ClienteModel;
require_once 'src/helpers/auth.php';

verificarRol([1,2]);

$model = new ClienteModel();
$fields = [
    "cedula_cliente" => ["label" => "Cédula", "type" => "number"],
    "nombre_cliente" => ["label" => "Nombre", "type" => "text"],
    "apellido_cliente" => ["label" => "Apellido", "type" => "text"],
    "telefono_cliente" => ["label" => "Teléfono", "type" => "text"],
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
    header("Location: index.php?url=Cliente");
    exit();
}
if (isset($_POST["delete"])) {
    $model->eliminar((int) $_POST["id"]);
    header("Location: index.php?url=Cliente");
    exit();
}
if (isset($_GET["editar"])) {
    $formData = $model->obtenerPorId((int) $_GET["editar"]) ?: [];
    $editing = $formData !== [];
}

$records = $model->obtenerTodos();
require "src/view/ClienteView.php";
