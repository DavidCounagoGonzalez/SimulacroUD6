<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace Com\Daw2\Controllers;

class UsuarioSistemaController extends \Com\Daw2\Core\BaseController{
    
    
    public function mostrarTodo() {
        $modelo = new \Com\Daw2\Models\UsuariosSistemaModel();
        $usuarios = $modelo->getAll();
        
        $data = [
            'seccion' => '/usuarios-sistema',
            'titulo' => 'Usuarios sistema',
            'breadcrumb' => ['Inicio', 'Usuarios sistema'],
            'usuarios' => $usuarios
            ];
        $this->view->showViews(array('templates/header.view.php', 'listadoUsuarioSistema.view.php', 'templates/footer.view.php'), $data);
    }
    
    public function procesarBaja(int $id) {
        $modelo = new \Com\Daw2\Models\UsuariosSistemaModel();
        $usuario = $modelo->loadByUser($id);
        if(!is_null($usuario) && $id != $_SESSION['usuario']['id_usuario']){
            if($usuario['baja'] == 0){
                $baja = 1;
            }else{
                $baja = 0;
            }
            if($modelo->darBaja($id, $baja)){
                $_SESSION['mensaje'] = [
                'class' => "success",
                'texto' => "Se ha dado de baja al usuario"
            ];
            }else{
               $_SESSION['mensaje'] = [
                'class' => "danger",
                'texto' => "No se ha podido dar de baja al usuario"
            ]; 
            }
        }else{
            $_SESSION['mensaje'] = [
                'class' => "danger",
                'texto' => "No se ha podido dar de baja al usuario"
            ];
        }
        header('location: /usuarios-sistema');
    }
    
     public function mostrarLogin() {
        if(isset($_SESSION['usuario'])){
            header('location: /');
        }else{
            $this->view->show('login.view.php');
        }
    }
    
    private function procesarPermisos($id_rol) {
        $modelRol = new \Com\Daw2\Models\AuxRolModel();
        $rol = $modelRol->loadByRol($id_rol);
        
        $permisos = [
            'usuarios' => '',
            'productos' => '',
            'categorias' => '',
            'proveedores' => ''
            ];
        switch ($rol['nombre_rol']) {
            case 'Administrador':
                foreach ($permisos as $key => $value) {
                    $permisos[$key] = 'rwd';
                }
                break;
            case 'Auditor':
                foreach ($permisos as $key => $value) {
                    $permisos[$key] = 'r';
                }
                break;
            case 'Facturación':
                $permisos['proveedores'] = 'rw';
                $permisos['productos'] = 'rw';
                break;
        }
        return $permisos;
    }


    public function procesarLogin() {
        $errores = $this->checkLogin($_POST);
        if(count($errores) <= 0){
            $modelo = new \Com\Daw2\Models\UsuariosSistemaModel();
            $usuario = $modelo->loadByEmail($_POST['email']);
            if(!is_null($usuario) && $usuario['baja']==0){
                if(password_verify($_POST['pass'], $usuario['pass'])){
                    unset($usuario['pass']);
                    $permisos = $this->procesarPermisos($usuario['id_rol']);
                    $_SESSION['usuario'] = $usuario;
                    $_SESSION['permisos'] = $permisos;
                    $modelo->ultimoLogin($usuario['id_usuario']);
                    header('location: /');
                }else{
                    $errores['error'] = 'Datos invalidos';
                }
            }else{
                $errores['error'] = 'Datos invalidos';
            }
        }
        $data = [
            'errores' => $errores,
            'email' => filter_input(INPUT_POST, 'email', FILTER_SANITIZE_SPECIAL_CHARS)
        ];
        $this->view->show('login.view.php', $data);
    }
    
    public function logout() {
        if(isset($_SESSION['usuario'])){
            session_destroy();
            header('location: /login');
        }
    }
    
    private function checkLogin($datos) {
        $errores = [];
        if(empty($datos['email'])){
            $errores['email'] = "Debe indicar un email válido";
        }
        if(empty($datos['pass'])){
            $errores['pass'] = "Indica una contraseña";
        }
        
        return $errores;
    }
    
    public function mostrarAdd() {
        $rolModel = new \Com\Daw2\Models\AuxRolModel();
        $roles = $rolModel->getAll();
        $idiomas = ['es'=>'Español', 'gl'=>'Gallego', 'en'=>'Inglés'];
        $data = [
            'seccion' => '/usuarios-sistema/add',
            'titulo' => 'Añadir Usuario',
            'breadcrumb' => ['Inicio', 'Usuarios sistema', 'Add'],
            'roles' => $roles,
            'idiomas' => $idiomas    
            ];
        $this->view->showViews(array('templates/header.view.php', 'add.usuario.view.php', 'templates/footer.view.php'), $data);
    }
    
    public function add() {
        
    }
    
    
    private function checkForm($datos): ?array {
        $errores = [];
        $modelo = new \Com\Daw2\Models\UsuariosSistemaModel;
        if(empty($datos['username'])){
            $errores['username'] = "Debes indicar un nombre";
        }else if(!preg_match ($datos['username'], '/^[1-9a-zA-Z_]{5,20}$/')){
            $errores['username'] = "El nombre no cumple las condiciones";
        }else if(!is_null($modelo->loadByUsername($datos['username']))){
            $errores['username'] = "Este nombre ya existe";
        }
        
        if(empty($datos['pass'])){
            $errores['pass'] = "Debe indicar una contraseña";
        }else if(!preg_match($datos['pass'], '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{6,}$/')){
            $errores['pass'] = "La contraseña no cumple las condiciones";
        }else if($datos['pass'] != $datos['pass2']){
            $errores['pass'] = "Las contraseñas no coinciden";
        }
        
        if(empty($datos['email'])){
            $errores['email'] = "Debe indicar un email";
        }else if(filter_var($datos['email'], FILTER_VALIDATE_EMAIL)){
            $errores['email'] = "El email no contiene un formato válido";
        }else if(!is_null($modelo->loadByEmail($datos['email']))){
            $errores['email'] = "El email ya existe";
        }
        
        if(empty($datos['id_rol'])){
            $errores['id_rol'] = "Debe indicar un rol";
        }else{
            $rolModel = new \Com\Daw2\Models\AuxRolModel();
            if(!is_null($rolModel->loadByRol($datos['id_rol'])) && !filter_var($datos['id_rol'], FILTER_VALIDATE_INT)){
                $errores['id_rol'] = "El rol no es válido";
            }
        }
    }
}
