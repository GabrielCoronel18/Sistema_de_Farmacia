<?php
namespace Usuario\SistemaDeFarmacia\model;

use Usuario\SistemaDeFarmacia\config\ConnectBD;

class ClienteModel extends ConnectBD
{
    public function obtenerTodos(): array
    {
        return $this->getConnection()->query("SELECT * FROM cliente ORDER BY cedula_cliente DESC")->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function obtenerPorId(int $id): array|false
    {
        $query = $this->getConnection()->prepare("SELECT * FROM cliente WHERE cedula_cliente = :id");
        $query->execute(["id" => $id]);
        return $query->fetch(\PDO::FETCH_ASSOC);
    }

    public function agregar(array $datos): void
    {
        $query = $this->getConnection()->prepare(
            "INSERT INTO cliente (cedula_cliente, nombre_cliente, apellido_cliente, telefono_cliente)
             VALUES (:cedula, :nombre, :apellido, :telefono)"
        );
        $query->execute([
            "cedula" => $datos["cedula_cliente"],
            "nombre" => $datos["nombre_cliente"],
            "apellido" => $datos["apellido_cliente"],
            "telefono" => $datos["telefono_cliente"],
        ]);
    }

    public function actualizar(int $id, array $datos): void
    {
        $query = $this->getConnection()->prepare(
            "UPDATE cliente SET cedula_cliente = :cedula, nombre_cliente = :nombre,
             apellido_cliente = :apellido, telefono_cliente = :telefono WHERE cedula_cliente = :id"
        );
        $query->execute([
            "id" => $id,
            "cedula" => $datos["cedula_cliente"],
            "nombre" => $datos["nombre_cliente"],
            "apellido" => $datos["apellido_cliente"],
            "telefono" => $datos["telefono_cliente"],
        ]);
    }

    public function eliminar(int $id): void
    {
        $query = $this->getConnection()->prepare("DELETE FROM cliente WHERE cedula_cliente = :id");
        $query->execute(["id" => $id]);
    }
}
