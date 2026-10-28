<?php
namespace Usuario\SistemaDeFarmacia\model;

use Usuario\SistemaDeFarmacia\config\ConnectBD;

class EmpleadoModel extends ConnectBD
{
    public function obtenerTodos(): array
    {
        return $this->getConnection()->query("SELECT * FROM empleado ORDER BY cedula_empleado DESC")->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function obtenerPorId(int $id): array|false
    {
        $query = $this->getConnection()->prepare("SELECT * FROM empleado WHERE cedula_empleado = :id");
        $query->execute(["id" => $id]);
        return $query->fetch(\PDO::FETCH_ASSOC);
    }

    public function agregar(array $datos): void
    {
        $query = $this->getConnection()->prepare(
            "INSERT INTO empleado (cedula_empleado, nombre_empleado, apellido_empleado, telefono_empleado, correo_empleado, direccion_empleado)
             VALUES (:cedula, :nombre, :apellido, :telefono, :correo, :direccion)"
        );
        $query->execute([
            "cedula" => $datos["cedula_empleado"],
            "nombre" => $datos["nombre_empleado"],
            "apellido" => $datos["apellido_empleado"],
            "telefono" => $datos["telefono_empleado"],
            "correo" => $datos["correo_empleado"],
            "direccion" => $datos["direccion_empleado"],
        ]);
    }

    public function actualizar(int $id, array $datos): void
    {
        $query = $this->getConnection()->prepare(
            "UPDATE empleado SET cedula_empleado = :cedula, nombre_empleado = :nombre, apellido_empleado = :apellido,
             telefono_empleado = :telefono, correo_empleado = :correo, direccion_empleado = :direccion
             WHERE cedula_empleado = :id"
        );
        $query->execute([
            "id" => $id,
            "cedula" => $datos["cedula_empleado"],
            "nombre" => $datos["nombre_empleado"],
            "apellido" => $datos["apellido_empleado"],
            "telefono" => $datos["telefono_empleado"],
            "correo" => $datos["correo_empleado"],
            "direccion" => $datos["direccion_empleado"],
        ]);
    }

    public function eliminar(int $id): void
    {
        $query = $this->getConnection()->prepare("DELETE FROM empleado WHERE cedula_empleado = :id");
        $query->execute(["id" => $id]);
    }
}
