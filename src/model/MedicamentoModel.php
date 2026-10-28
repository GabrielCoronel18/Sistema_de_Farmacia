<?php
namespace Usuario\SistemaDeFarmacia\model;
use Usuario\SistemaDeFarmacia\config\ConnectBD;
use Usuario\SistemaDeFarmacia\traits\Validation;

class MedicamentoModel extends ConnectBD{
    private int $id_medicamento;
    private int $codigo;
    private string $nombre;
    private int $id_laboratorio;
    private string $fecha_vencimiento;
    private float $precio;
    private int $stock;
    private string $estado;

    use Validation;
    
    public function obtenerMedicamentos(){
        $sql = "SELECT medicamento.*, laboratorio.nombre_laboratorio FROM medicamento INNER JOIN laboratorio ON medicamento.id_laboratorio = laboratorio.id_laboratorio WHERE medicamento.estado = 1;";
        return $this->ExeQuery($sql, [], true);
    }

    public function agregarMedicamento($codigo, $nombre, $id_laboratorio, $fecha_vencimiento, $precio, $stock){
      $this->codigo = $codigo;
      $this->nombre = $nombre;
      $this->id_laboratorio = $id_laboratorio;
      $this->fecha_vencimiento = $fecha_vencimiento;
      $this->precio = $precio;
      $this->stock = $stock;
      
      $sql = "INSERT INTO medicamento (id_medicamento,nombre,id_laboratorio,codigo,precio,fecha_vencimiento,stock,estado) VALUES (0,:nombre,:id_laboratorio,:codigo,:precio,:fecha_vencimiento,:stock,1)";
      return $this->ExeQuery($sql, ["nombre","id_laboratorio","codigo","precio","fecha_vencimiento","stock"]);
    }

    public function actualizarMedicamento($id_medicamento, $codigo, $nombre, $id_laboratorio, $fecha_vencimiento, $precio, $stock){
        $this->id_medicamento = $id_medicamento;
        $this->codigo = $codigo;
        $this->nombre = $nombre;
        $this->id_laboratorio = $id_laboratorio;
        $this->fecha_vencimiento = $fecha_vencimiento;
        $this->precio = $precio;
        $this->stock = $stock;

        $sql = "UPDATE medicamento SET nombre = :nombre, id_laboratorio = :id_laboratorio, codigo = :codigo, precio = :precio, fecha_vencimiento = :fecha_vencimiento, stock = :stock WHERE id_medicamento = :id_medicamento";
        return $this->ExeQuery($sql, ["nombre", "id_laboratorio", "codigo", "precio", "fecha_vencimiento", "stock", "id_medicamento"]);
    }

    public function eliminarMedicamento($id_medicamento){
        $this->id_medicamento = $id_medicamento;
        $sql = "UPDATE medicamento SET estado = 0 WHERE id_medicamento = :id_medicamento";
        return $this->ExeQuery($sql, ["id_medicamento"]);
    }

    public function listarLaboratorios(){
        $sql = "SELECT nombre_laboratorio, id_laboratorio FROM laboratorio";
        return $this->ExeQuery($sql, [], true);
    }
    
    public function obtenerMedicamentoPorId($id){
        $this->id_medicamento = $id;
        $sql = "SELECT * FROM medicamento WHERE id_medicamento = :id_medicamento AND estado = 1";
        return $this->ExeQuery($sql, ["id_medicamento"], true);
    }

    private function ExeQuery(string $sql, array $param, bool $fetch = false){
        $conex = $this->GetConnection();
        $prep = $conex->prepare($sql);
        
        if(!empty($param)){ 
        foreach($param as $e){
            $prep->bindParam(":".$e,$this->$e);
          }
            
        }
        if($fetch){
               $prep->execute(); 
               return $prep->fetchAll(\PDO::FETCH_ASSOC);
            }else{

            return $prep->execute();
        }

    }

}
?>