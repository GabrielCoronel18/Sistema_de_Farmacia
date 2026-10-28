<?php
namespace Usuario\SistemaDeFarmacia\model;

use Usuario\SistemaDeFarmacia\config\ConnectBD;

class RolModel extends ConnectBD
{
    public function obtenerTodos(): array
    {
        return $this->getConnection()->query("SELECT * FROM rol ORDER BY id_rol DESC")->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function obtenerPorId(int $id): array|false
    {
        $query = $this->getConnection()->prepare("SELECT * FROM rol WHERE id_rol = :id");
        $query->execute(["id" => $id]);
        return $query->fetch(\PDO::FETCH_ASSOC);
    }

    public function agregar(string $nombre): void
    {
        $query = $this->getConnection()->prepare("INSERT INTO rol (nombre_rol) VALUES (:nombre)");
        $query->execute(["nombre" => $nombre]);
    }

    public function actualizar(int $id, string $nombre): void
    {
        $query = $this->getConnection()->prepare("UPDATE rol SET nombre_rol = :nombre WHERE id_rol = :id");
        $query->execute(["id" => $id, "nombre" => $nombre]);
    }

    public function eliminar(int $id): void
    {
        $query = $this->getConnection()->prepare("DELETE FROM rol WHERE id_rol = :id");
        $query->execute(["id" => $id]);
    }
}
