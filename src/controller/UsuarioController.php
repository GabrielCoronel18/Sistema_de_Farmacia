<?php
namespace Usuario\SistemaDeFarmacia\controller;

use Usuario\SistemaDeFarmacia\model\UsuarioModel;

require_once 'src/helpers/auth.php';

verificarRol([1]);


$model = new UsuarioModel();
$fields = [
    "cedula_empleado" => ["label" => "Empleado", "type" => "select", "display" => "empleado"],
    "nombre" => ["label" => "Usuario", "type" => "text"],
    "password" => ["label" => "Contraseña", "type" => "password"],
    "id_rol" => ["label" => "Rol", "type" => "select", "display" => "nombre_rol"],
    "estado" => ["label" => "Estado", "type" => "select"],
];
$options = [
    "cedula_empleado" => $model->empleados(),
    "id_rol" => $model->roles(),
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
    header("Location: index.php?url=Usuario");
    exit();
}
if (isset($_POST["delete"])) {
    $model->eliminar((int) $_POST["id"]);
    header("Location: index.php?url=Usuario");
    exit();
}
if (isset($_GET["editar"])) {
    $formData = $model->obtenerPorId((int) $_GET["editar"]) ?: [];
    $editing = $formData !== [];
}

$records = $model->obtenerTodos();
require "src/view/UsuarioView.php";
