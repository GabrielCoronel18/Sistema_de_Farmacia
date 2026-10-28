<?php
namespace Usuario\SistemaDeFarmacia\config;
use PDO;
use PDOException;
abstract class ConnectBD{
    
    private $conex;
    public function __construct()
    {
        $this->getConnection();
        
    }
    public function getConnection(){
       $dsn = "mysql:host=localhost;dbname=farmacia";
       $user = "root";
       $password = "";
       try{
            $conex = new PDO($dsn,$user,$password);
            
            $conex->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
            $this->conex = $conex;
            }
            catch(PDOException $e){
                die("error en la base de datos".$e->getMessage());
            }
            
            return $this->conex;
    }
}