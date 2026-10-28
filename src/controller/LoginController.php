<?php
namespace Usuario\SistemaDeFarmacia\controller;


use Usuario\SistemaDeFarmacia\model\LoginModel;

$loginModel = new LoginModel();

$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["login"])) {
    $username = trim($_POST["username"] ?? "");
    $password = trim($_POST["password"] ?? "");
        

        if(!$loginModel->validateRequired(["username" => $username,"password" => $password],["username","password"])){
           $errors = $loginModel->getErrors();
           
        }
        if(!$loginModel->validateMinLength($username,"username",8)){
            $errors = $loginModel->getErrors();
           
        }
        if(!$loginModel->validateMinLength($password,"password",8)){
            $errors = $loginModel->getErrors();
           
        }
        
        if(!$loginModel->validateMaxLength($username,"username",30)){
            $errors = $loginModel->getErrors();
           
        }
        if(!$loginModel->validateMaxLength($password,"password",30)){
            $errors = $loginModel->getErrors();
           
        }
    
    $usuario = $loginModel->validarCredenciales($username, $password);
    
    if ($usuario){
        session_regenerate_id(true);
        $_SESSION['usuario'] = $usuario;
        
        header("Location: index.php?url=Dashboard");
        exit();
    } else {
        
        echo "<script>alert('Credenciales inválidas. Por favor, inténtelo de nuevo.');</script>";
    }
}


require_once "src/view/LoginView.php";
?>