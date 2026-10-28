<?php
namespace Usuario\SistemaDeFarmacia\model;

use Usuario\SistemaDeFarmacia\config\ConnectBD;

class LaboratorioModel extends ConnectBD
{
    public function obtenerTodos(): array
    {
        return $this->getConnection()->query("SELECT * FROM laboratorio ORDER BY id_laboratorio DESC")->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function obtenerPorId(int $id): array|false
    {
        $query = $this->getConnection()->prepare("SELECT * FROM laboratorio WHERE id_laboratorio = :id");
        $query->execute(["id" => $id]);
        return $query->fetch(\PDO::FETCH_ASSOC);
    }

    public function agregar(string $nombre): void
    {
        $query = $this->getConnection()->prepare("INSERT INTO laboratorio (nombre_laboratorio) VALUES (:nombre)");
        $query->execute(["nombre" => $nombre]);
    }

    public function actualizar(int $id, string $nombre): void
    {
        $query = $this->getConnection()->prepare("UPDATE laboratorio SET nombre_laboratorio = :nombre WHERE id_laboratorio = :id");
        $query->execute(["id" => $id, "nombre" => $nombre]);
    }

    public function eliminar(int $id): void
    {
        $query = $this->getConnection()->prepare("DELETE FROM laboratorio WHERE id_laboratorio = :id");
        $query->execute(["id" => $id]);
    }
}
