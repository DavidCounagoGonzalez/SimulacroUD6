<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace Com\Daw2\Models;


class UsuariosSistemaModel extends \Com\Daw2\Core\BaseModel{
    
    private const SELECT_FROM = "SELECT us.*, ar.nombre_rol FROM usuario_sistema us LEFT JOIN aux_rol ar ON ar.id_rol = us.id_rol ORDER BY us.username";

    public function getAll() {
        $query = self::SELECT_FROM;
        $stmt = $this->pdo->query($query);
        return $stmt->fetchAll();
    }   
    
    public function loadByEmail($email) {
        $query = "SELECT * FROM usuario_sistema WHERE email = ?";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$email]);
        if($row = $stmt->fetch()){
            return $row;
        }else{
            return null;
        }
        
    }
    
    public function loadByUser($id_usuario) {
        $query = "SELECT * FROM usuario_sistema WHERE id_usuario = ?";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$id_usuario]);
        if($row = $stmt->fetch()){
            return $row;
        }else{
            return null;
        }
    }
    
    public function loadByUsername($username) {
        $query = "SELECT * FROM usuario_sistema WHERE username = ?";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$username]);
        if($row = $stmt->fetch()){
            return $row;
        }else{
            return null;
        }
    }
    
    public function darBaja($id_usuario, $baja) {
        $query = 'UPDATE usuario_sistema SET baja = ? WHERE id_usuario = ?';
        $stmt = $this->pdo->prepare($query);
        if($stmt->execute([$baja, $id_usuario]) && $stmt->rowCount() > 0){
            return true;
        }else{
            return false;
        }
    }
    
    public function ultimoLogin($id_usuario) {
        $query = "UPDATE usuario_sistema SET last_date=NOW() WHERE id_usuario=?";
        $stmt = $this->pdo->prepare($query);
        if($stmt->execute([$id_usuario]) && $stmt->rowCount() > 0){
            return true;
        }else{
            return false;
        }
    }
    
    

}
