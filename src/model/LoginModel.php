<?php
namespace Usuario\SistemaDeFarmacia\model;
use Usuario\SistemaDeFarmacia\config\ConnectBD;
use Usuario\SistemaDeFarmacia\traits\Validation;

class LoginModel extends ConnectBD
{
    use Validation;
    
    public function validarCredenciales(string $username, string $password): array|false
    {
        $sql = "SELECT usuarios.*, rol.nombre_rol AS rol FROM usuarios INNER JOIN rol ON rol.id_rol = usuarios.id_rol WHERE nombre = :nombre AND estado = 1 LIMIT 1";
        $statement = $this->getConnection()->prepare($sql);
        $statement->execute(["nombre" => $username]);
        $usuario = $statement->fetch(\PDO::FETCH_ASSOC);
        
        
        if (!$usuario) {
            return false;
        }

        $storedPassword = (string) $usuario["password"];
        $isHashed = password_get_info($storedPassword)["algo"] !== null;
        $isValid = $isHashed
            ? password_verify($password, $storedPassword)
            : hash_equals($storedPassword, $password);

        return $isValid ? $usuario : false;
    }
}
