<?php
namespace Usuario\SistemaDeFarmacia\model;

use Usuario\SistemaDeFarmacia\config\ConnectBD;

class ProveedorModel extends ConnectBD
{
    public function obtenerTodos(): array
    {
        return $this->getConnection()->query("SELECT * FROM proveedores ORDER BY id_proveedor DESC")->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function obtenerPorId(int $id): array|false
    {
        $query = $this->getConnection()->prepare("SELECT * FROM proveedores WHERE id_proveedor = :id");
        $query->execute(["id" => $id]);
        return $query->fetch(\PDO::FETCH_ASSOC);
    }

    public function agregar(array $datos): void
    {
        $query = $this->getConnection()->prepare(
            "INSERT INTO proveedores (nombre_proveedor, telefono_proveedor, email_proveedor)
             VALUES (:nombre, :telefono, :correo)"
        );
        $query->execute([
            "nombre" => $datos["nombre_proveedor"],
            "telefono" => $datos["telefono_proveedor"],
            "correo" => $datos["email_proveedor"],
        ]);
    }

    public function actualizar(int $id, array $datos): void
    {
        $query = $this->getConnection()->prepare(
            "UPDATE proveedores SET nombre_proveedor = :nombre, telefono_proveedor = :telefono,
             email_proveedor = :correo WHERE id_proveedor = :id"
        );
        $query->execute([
            "id" => $id,
            "nombre" => $datos["nombre_proveedor"],
            "telefono" => $datos["telefono_proveedor"],
            "correo" => $datos["email_proveedor"],
        ]);
    }

    public function eliminar(int $id): void
    {
        $query = $this->getConnection()->prepare("DELETE FROM proveedores WHERE id_proveedor = :id");
        $query->execute(["id" => $id]);
    }
}
