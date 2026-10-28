<?php
namespace Usuario\SistemaDeFarmacia\model;

use Usuario\SistemaDeFarmacia\config\ConnectBD;

class VentasModel extends ConnectBD
{
    public function obtenerVentas(): array
    {
        $sql = "SELECT v.*, c.nombre_cliente, c.apellido_cliente, e.nombre_empleado, e.apellido_empleado
                FROM venta v
                INNER JOIN cliente c ON c.cedula_cliente = v.cedula_cliente
                INNER JOIN empleado e ON e.cedula_empleado = v.cedula_empleado
                ORDER BY v.id_venta DESC";
        return $this->getConnection()->query($sql)->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function obtenerDetalleVenta(int $id): array
    {
        $sql = "SELECT dv.cantidad, m.nombre, m.codigo, m.precio, l.nombre_laboratorio
                FROM detalle_venta dv
                INNER JOIN medicamento m ON m.id_medicamento = dv.id_medicamento
                INNER JOIN laboratorio l ON l.id_laboratorio = m.id_laboratorio
                WHERE dv.id_venta = :id_venta";
        $statement = $this->getConnection()->prepare($sql);
        $statement->execute(["id_venta" => $id]);
        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function obtenerOpciones(): array
    {
        $connection = $this->getConnection();
        return [
            "clientes" => $connection->query(
                "SELECT cedula_cliente AS id, CONCAT(nombre_cliente, ' ', apellido_cliente) AS nombre FROM cliente ORDER BY nombre_cliente"
            )->fetchAll(\PDO::FETCH_ASSOC),
            "empleados" => $connection->query(
                "SELECT cedula_empleado AS id, CONCAT(nombre_empleado, ' ', apellido_empleado) AS nombre FROM empleado ORDER BY nombre_empleado"
            )->fetchAll(\PDO::FETCH_ASSOC),
            "medicamentos" => $connection->query(
                "SELECT id_medicamento AS id, nombre, codigo, precio, stock FROM medicamento WHERE estado = 1 ORDER BY nombre"
            )->fetchAll(\PDO::FETCH_ASSOC),
        ];
    }

    public function crearVenta(int $clienteId, int $empleadoId, array $items): int
    {
        $connection = $this->getConnection();
        $connection->beginTransaction();

        try {
            $items = $this->consolidarItems($items);
            $precioQuery = $connection->prepare(
                "SELECT precio FROM medicamento WHERE id_medicamento = :id AND estado = 1 FOR UPDATE"
            );
            $stockUpdate = $connection->prepare(
                "UPDATE medicamento SET stock = stock - :cantidad
                 WHERE id_medicamento = :id AND estado = 1 AND stock >= :stock_requerido"
            );
            $details = [];
            $total = 0;

            foreach ($items as $item) {
                $precioQuery->execute(["id" => $item["medicamento"]]);
                $medicamento = $precioQuery->fetch(\PDO::FETCH_ASSOC);
                if (!$medicamento) {
                    throw new \RuntimeException("Uno de los medicamentos seleccionados ya no está disponible.");
                }

                $stockUpdate->execute([
                    "id" => $item["medicamento"],
                    "cantidad" => $item["cantidad"],
                    "stock_requerido" => $item["cantidad"],
                ]);
                if ($stockUpdate->rowCount() !== 1) {
                    throw new \RuntimeException("No hay stock suficiente para completar la venta.");
                }

                $total += (int) $medicamento["precio"] * $item["cantidad"];
                $details[] = [
                    "id" => $item["medicamento"],
                    "cantidad" => $item["cantidad"],
                ];
            }

            $sale = $connection->prepare(
                "INSERT INTO venta (cedula_empleado, cedula_cliente, fecha_venta, total_venta, estado_venta)
                 VALUES (:empleado, :cliente, :fecha, :total, 1)"
            );
            $sale->execute([
                "empleado" => $empleadoId,
                "cliente" => $clienteId,
                "fecha" => date("Y-m-d"),
                "total" => $total,
            ]);
            $saleId = (int) $connection->lastInsertId();

            $detailInsert = $connection->prepare(
                "INSERT INTO detalle_venta (id_venta, id_medicamento, cantidad)
                 VALUES (:venta, :medicamento, :cantidad)"
            );
            foreach ($details as $detail) {
                $detailInsert->execute([
                    "venta" => $saleId,
                    "medicamento" => $detail["id"],
                    "cantidad" => $detail["cantidad"],
                ]);
            }

            $connection->commit();
            return $saleId;
        } catch (\Throwable $exception) {
            if ($connection->inTransaction()) {
                $connection->rollBack();
            }
            throw $exception;
        }
    }

    public function anularVenta(int $id): void
    {
        $connection = $this->getConnection();
        $connection->beginTransaction();

        try {
            $sale = $connection->prepare("SELECT estado_venta FROM venta WHERE id_venta = :id FOR UPDATE");
            $sale->execute(["id" => $id]);
            $status = $sale->fetchColumn();
            if ($status === false || (int) $status !== 1) {
                throw new \RuntimeException("La venta no existe o ya fue anulada.");
            }

            $detailQuery = $connection->prepare(
                "SELECT id_medicamento, cantidad FROM detalle_venta WHERE id_venta = :id"
            );
            $detailQuery->execute(["id" => $id]);
            $restoreStock = $connection->prepare(
                "UPDATE medicamento SET stock = stock + :cantidad WHERE id_medicamento = :id"
            );
            foreach ($detailQuery->fetchAll(\PDO::FETCH_ASSOC) as $detail) {
                $restoreStock->execute([
                    "id" => $detail["id_medicamento"],
                    "cantidad" => $detail["cantidad"],
                ]);
            }

            $cancel = $connection->prepare("UPDATE venta SET estado_venta = 0 WHERE id_venta = :id");
            $cancel->execute(["id" => $id]);
            $connection->commit();
        } catch (\Throwable $exception) {
            if ($connection->inTransaction()) {
                $connection->rollBack();
            }
            throw $exception;
        }
    }

    private function consolidarItems(array $items): array
    {
        $consolidated = [];
        foreach ($items as $item) {
            $medicamentoId = filter_var($item["medicamento"] ?? null, FILTER_VALIDATE_INT);
            $cantidad = filter_var($item["cantidad"] ?? null, FILTER_VALIDATE_INT);
            if ($medicamentoId === false || $medicamentoId < 1 || $cantidad === false || $cantidad < 1) {
                throw new \InvalidArgumentException("Selecciona medicamentos y cantidades válidos.");
            }

            $consolidated[$medicamentoId] = ($consolidated[$medicamentoId] ?? 0) + $cantidad;
        }
        if ($consolidated === []) {
            throw new \InvalidArgumentException("Agrega al menos un medicamento a la venta.");
        }

        $normalized = [];
        foreach ($consolidated as $medicamentoId => $cantidad) {
            $normalized[] = ["medicamento" => (int) $medicamentoId, "cantidad" => $cantidad];
        }
        return $normalized;
    }
}
