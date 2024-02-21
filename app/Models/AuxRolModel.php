<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace Com\Daw2\Models;

/**
 * Description of AuxRolModel
 *
 * @author david.counagogonzalez
 */
class AuxRolModel extends \Com\Daw2\Core\BaseModel{
    
    public function getAll() {
        $stmt = $this->pdo->query('SELECT * FROM aux_rol');
        return $stmt->fetchAll();
    }
    
    public function loadByRol($id_rol) {
        $query = "SELECT * FROM aux_rol WHERE id_rol = ?";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$id_rol]);
        if($row = $stmt->fetch()){
            return $row;
        }else{
            return false;
        }
    }
}
