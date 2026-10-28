<?php
namespace Usuario\SistemaDeFarmacia\controller;

use Usuario\SistemaDeFarmacia\model\EmpleadoModel;
require_once 'src/helpers/auth.php';

verificarRol([1,2]);
$model = new EmpleadoModel();
$fields = [
    "cedula_empleado" => ["label" => "Cédula", "type" => "number"],
    "nombre_empleado" => ["label" => "Nombre", "type" => "text"],
    "apellido_empleado" => ["label" => "Apellido", "type" => "text"],
    "telefono_empleado" => ["label" => "Teléfono", "type" => "number"],
    "correo_empleado" => ["label" => "Correo", "type" => "email"],
    "direccion_empleado" => ["label" => "Dirección", "type" => "text"],
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
    header("Location: index.php?url=Empleado");
    exit();
}
if (isset($_POST["delete"])) {
    $model->eliminar((int) $_POST["id"]);
    header("Location: index.php?url=Empleado");
    exit();
}
if (isset($_GET["editar"])) {
    $formData = $model->obtenerPorId((int) $_GET["editar"]) ?: [];
    $editing = $formData !== [];
}

$records = $model->obtenerTodos();
require "src/view/EmpleadoView.php";
