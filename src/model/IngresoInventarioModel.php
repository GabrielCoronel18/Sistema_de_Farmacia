<?php
namespace Usuario\SistemaDeFarmacia\model;

use Usuario\SistemaDeFarmacia\config\ConnectBD;

class IngresoInventarioModel extends ConnectBD
{
    public function obtenerOpciones(): array
    {
        $connection = $this->getConnection();
        return [
            "medicamentos" => $connection->query(
                "SELECT id_medicamento, nombre, codigo, stock FROM medicamento WHERE estado = 1 ORDER BY nombre"
            )->fetchAll(\PDO::FETCH_ASSOC),
            "proveedores" => $connection->query(
                "SELECT id_proveedor, nombre_proveedor FROM proveedores ORDER BY nombre_proveedor"
            )->fetchAll(\PDO::FETCH_ASSOC),
            "ingresos" => $connection->query(
                "SELECT mp.*, m.nombre AS medicamento, p.nombre_proveedor
                 FROM medicamento_proveedores mp
                 INNER JOIN medicamento m ON m.id_medicamento = mp.id_med_lab
                 INNER JOIN proveedores p ON p.id_proveedor = mp.id_proveedor
                 ORDER BY mp.id_med_prov DESC"
            )->fetchAll(\PDO::FETCH_ASSOC),
        ];
    }

    public function registrarIngreso(int $medicamentoId, int $proveedorId, int $precio, int $cantidad, string $fecha): void
    {
        $connection = $this->getConnection();
        $connection->beginTransaction();

        try {
            $medicineQuery = $connection->prepare(
                "SELECT id_medicamento FROM medicamento WHERE id_medicamento = :id AND estado = 1 FOR UPDATE"
            );
            $medicineQuery->execute(["id" => $medicamentoId]);
            if (!$medicineQuery->fetchColumn()) {
                throw new \RuntimeException("El medicamento seleccionado no está disponible.");
            }

            $insert = $connection->prepare(
                "INSERT INTO medicamento_proveedores (id_med_lab, id_proveedor, precio_entrega, cantidad_entrega, fecha_entrega)
                 VALUES (:medicamento, :proveedor, :precio, :cantidad, :fecha)"
            );
            $insert->execute([
                "medicamento" => $medicamentoId,
                "proveedor" => $proveedorId,
                "precio" => $precio,
                "cantidad" => $cantidad,
                "fecha" => $fecha,
            ]);

            $updateStock = $connection->prepare(
                "UPDATE medicamento SET stock = stock + :cantidad WHERE id_medicamento = :id"
            );
            $updateStock->execute(["id" => $medicamentoId, "cantidad" => $cantidad]);
            $connection->commit();
        } catch (\Throwable $exception) {
            if ($connection->inTransaction()) {
                $connection->rollBack();
            }
            throw $exception;
        }
    }
}
