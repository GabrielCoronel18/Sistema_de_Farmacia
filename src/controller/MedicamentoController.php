<?php

namespace Usuario\SistemaDeFarmacia\controller;

use Usuario\SistemaDeFarmacia\model\MedicamentoModel;
require_once 'src/helpers/auth.php';

verificarRol([1,2]);

$medicamentoModel = new MedicamentoModel();

$errors = [];
$isAjax = strtolower($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '') === 'xmlhttprequest';
$respondJson = static function (array $payload, int $status = 200) use ($isAjax): void {
   if (!$isAjax) {
      return;
   }

   http_response_code($status);
   header('Content-Type: application/json; charset=utf-8');
   echo json_encode($payload, JSON_UNESCAPED_UNICODE);
   exit;
};

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["Agregar"])) {
 $codigo=$_POST["codigo"] ?? "";
 $nombre=$_POST["nombre"] ?? "";
 $laboratorio=$_POST["laboratorio"] ?? 0;
 $fecha_vencimiento=$_POST["fecha_vencimiento"] ?? "";
 $precio=$_POST["precio"] ?? 0;
 $stock=$_POST["stock"] ?? 0;

 if(!$medicamentoModel->validateRequired(["codigo" => $codigo,"nombre" => $nombre,"laboratorio" => $laboratorio,"fecha_vencimiento" => $fecha_vencimiento,"precio" => $precio,"stock" => $stock],["codigo","nombre","laboratorio","fecha_vencimiento","precio","stock"])){
    $errors = $medicamentoModel->getErrors();
    
 }
 if(!$medicamentoModel->validateMinLength($codigo,"codigo",3)){
    $errors = $medicamentoModel->getErrors();
    
 }
 if(!$medicamentoModel->validateMinLength($nombre,"nombre",3)){
    $errors = $medicamentoModel->getErrors();
    
 }
 if(!$medicamentoModel->validateMaxLength($codigo,"codigo",10)){
    $errors = $medicamentoModel->getErrors();
    
 }
 if(!$medicamentoModel->validateMaxLength($nombre,"nombre",50)){
    $errors = $medicamentoModel->getErrors();
    
 }
 if(!$medicamentoModel->validateInteger($laboratorio,"laboratorio")){
    $errors = $medicamentoModel->getErrors();
    
 } 
 if(!$medicamentoModel->validateNumeric($precio,"precio")){
    $errors = $medicamentoModel->getErrors();
    
 }
 if(!$medicamentoModel->validateInteger($stock,"stock")){
    $errors = $medicamentoModel->getErrors();
    
 }

 if(!empty($errors)){
    $respondJson(['ok' => false, 'errors' => $errors], 422);
 } else {
    $resultado = $medicamentoModel->agregarMedicamento($codigo,$nombre,$laboratorio,$fecha_vencimiento,$precio,$stock);
    $respondJson(['ok' => $resultado, 'message' => 'Medicamento guardado correctamente.']);
    exit;
 }



}
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["Actualizar"])) {
    $id_medicamento = $_POST["id_medicamento"] ?? 0;
    $codigo = $_POST["codigo"] ?? "";
    $nombre = $_POST["nombre"] ?? "";
    $laboratorio = $_POST["laboratorio"] ?? 0;
    $fecha_vencimiento = $_POST["fecha_vencimiento"] ?? "";
    $precio = $_POST["precio"] ?? 0;
    $stock = $_POST["stock"] ?? 0;

   if(!$medicamentoModel->validateRequired(["codigo" => $codigo,"nombre" => $nombre,"laboratorio" => $laboratorio,"fecha_vencimiento" => $fecha_vencimiento,"precio" => $precio,"stock" => $stock],["codigo","nombre","laboratorio","fecha_vencimiento","precio","stock"])){
      $errors = $medicamentoModel->getErrors();
   }
   if(!$medicamentoModel->validateMinLength($codigo,"codigo",3)){
      $errors = $medicamentoModel->getErrors();
   }
   if(!$medicamentoModel->validateMinLength($nombre,"nombre",3)){
      $errors = $medicamentoModel->getErrors();
   }
   if(!$medicamentoModel->validateMaxLength($codigo,"codigo",10)){
      $errors = $medicamentoModel->getErrors();
   }
   if(!$medicamentoModel->validateMaxLength($nombre,"nombre",50)){
      $errors = $medicamentoModel->getErrors();
   }
   if(!$medicamentoModel->validateInteger($laboratorio,"laboratorio")){
      $errors = $medicamentoModel->getErrors();
   }
   if(!$medicamentoModel->validateNumeric($precio,"precio")){
      $errors = $medicamentoModel->getErrors();
   }
   if(!$medicamentoModel->validateInteger($stock,"stock")){
      $errors = $medicamentoModel->getErrors();
   }

   if(!empty($errors)){
      $respondJson(['ok' => false, 'errors' => $errors], 422);
   } else {
      $resultado = $medicamentoModel->actualizarMedicamento($id_medicamento, $codigo, $nombre, $laboratorio, $fecha_vencimiento, $precio, $stock);
      $respondJson(['ok' => $resultado, 'message' => 'Medicamento actualizado correctamente.']);
   }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["Eliminar"])) {
    $id_medicamento = $_POST["id_medicamento"] ?? 0;
    $medicamentoModel->eliminarMedicamento($id_medicamento);
}


require_once "src/view/MedicamentoView.php";
