<?php
namespace Usuario\SistemaDeFarmacia\controller;

use Usuario\SistemaDeFarmacia\model\VentasModel;
require_once 'src/helpers/auth.php';

verificarRol([1,2]);

$ventaModel = new VentasModel();
$error_message = null;
$showSaleModal = false;
$detalleId = isset($_GET["detalle"]) ? (int) $_GET["detalle"] : null;

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["crearVenta"])) {
    $showSaleModal = true;
    $medicamentos = (array) ($_POST["medicamento"] ?? []);
    $cantidades = (array) ($_POST["cantidad"] ?? []);
    $items = [];
    foreach ($medicamentos as $index => $medicamento) {
        $items[] = [
            "medicamento" => $medicamento,
            "cantidad" => $cantidades[$index] ?? 0,
        ];
    }

    try {
        $ventaModel->crearVenta(
            (int) ($_POST["cedula_cliente"] ?? 0),
            (int) ($_POST["cedula_empleado"] ?? 0),
            $items
        );
        header("Location: index.php?url=Ventas");
        exit();
    } catch (\RuntimeException | \InvalidArgumentException $exception) {
        $error_message = $exception->getMessage();
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["anularVenta"])) {
    try {
        $ventaModel->anularVenta((int) ($_POST["id_venta"] ?? 0));
        header("Location: index.php?url=Ventas");
        exit();
    } catch (\RuntimeException $exception) {
        $error_message = $exception->getMessage();
    }
}

$opciones = $ventaModel->obtenerOpciones();
$ventas = $ventaModel->obtenerVentas();
$detalles = $detalleId ? $ventaModel->obtenerDetalleVenta($detalleId) : [];
require "src/view/VentasView.php";
