<?php
namespace Usuario\SistemaDeFarmacia\controller;

use Usuario\SistemaDeFarmacia\model\IngresoInventarioModel;
require_once 'src/helpers/auth.php';

verificarRol([1,2]);
$ingresoModel = new IngresoInventarioModel();
$error_message = null;
$showIngresoModal = false;

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["registrarIngreso"])) {
    $showIngresoModal = true;
    try {
        $ingresoModel->registrarIngreso(
            (int) ($_POST["id_medicamento"] ?? 0),
            (int) ($_POST["id_proveedor"] ?? 0),
            (int) ($_POST["precio_entrega"] ?? 0),
            (int) ($_POST["cantidad_entrega"] ?? 0),
            (string) ($_POST["fecha_entrega"] ?? "")
        );
        header("Location: index.php?url=Ingreso");
        exit();
    } catch (\RuntimeException $exception) {
        $error_message = $exception->getMessage();
    }
}

$data = $ingresoModel->obtenerOpciones();
require "src/view/IngresoView.php";
