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
        if(!is_null($usuario)){
            if($usuario['baja'] == 0){
                $baja = 1;
            }else{
                $baja = 0;
            }
            $modelo->darBaja($id, $baja);
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
            if(!is_null($usuario)){
                if(password_verify($_POST['pass'], $usuario['pass'])){
                    unset($usuario['pass']);
                    $permisos = $this->procesarPermisos($usuario['id_rol']);
                    $_SESSION['usuario'] = $usuario;
                    $_SESSION['permisos'] = $permisos;
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
}
