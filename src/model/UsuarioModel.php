<?php
namespace Usuario\SistemaDeFarmacia\model;

use Usuario\SistemaDeFarmacia\config\ConnectBD;

class UsuarioModel extends ConnectBD
{
    public function obtenerTodos(): array
    {
        $sql = "SELECT u.id, u.cedula_empleado, u.nombre, u.id_rol, u.estado,
                       CONCAT(e.nombre_empleado, ' ', e.apellido_empleado) AS empleado,
                       r.nombre_rol
                FROM usuarios u
                INNER JOIN empleado e ON e.cedula_empleado = u.cedula_empleado
                INNER JOIN rol r ON r.id_rol = u.id_rol
                ORDER BY u.id DESC";
        return $this->getConnection()->query($sql)->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function obtenerPorId(int $id): array|false
    {
        $query = $this->getConnection()->prepare(
            "SELECT id, cedula_empleado, nombre, id_rol, estado FROM usuarios WHERE id = :id"
        );
        $query->execute(["id" => $id]);
        return $query->fetch(\PDO::FETCH_ASSOC);
    }

    public function empleados(): array
    {
        return $this->getConnection()->query(
            "SELECT cedula_empleado, nombre_empleado, apellido_empleado FROM empleado ORDER BY nombre_empleado"
        )->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function roles(): array
    {
        return $this->getConnection()->query("SELECT id_rol, nombre_rol FROM rol ORDER BY nombre_rol")->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function agregar(array $datos): void
    {
        $query = $this->getConnection()->prepare(
            "INSERT INTO usuarios (cedula_empleado, nombre, password, id_rol, estado)
             VALUES (:empleado, :nombre, :password, :rol, :estado)"
        );
        $query->execute([
            "empleado" => $datos["cedula_empleado"],
            "nombre" => $datos["nombre"],
            "password" => password_hash($datos["password"], PASSWORD_DEFAULT),
            "rol" => $datos["id_rol"],
            "estado" => $datos["estado"],
        ]);
    }

    public function actualizar(int $id, array $datos): void
    {
        $sql = "UPDATE usuarios SET cedula_empleado = :empleado, nombre = :nombre, id_rol = :rol, estado = :estado";
        $params = [
            "id" => $id,
            "empleado" => $datos["cedula_empleado"],
            "nombre" => $datos["nombre"],
            "rol" => $datos["id_rol"],
            "estado" => $datos["estado"],
        ];
        if ($datos["password"] !== "") {
            $sql .= ", password = :password";
            $params["password"] = password_hash($datos["password"], PASSWORD_DEFAULT);
        }
        $sql .= " WHERE id = :id";
        $query = $this->getConnection()->prepare($sql);
        $query->execute($params);
    }

    public function eliminar(int $id): void
    {
        $query = $this->getConnection()->prepare("DELETE FROM usuarios WHERE id = :id");
        $query->execute(["id" => $id]);
    }
}
