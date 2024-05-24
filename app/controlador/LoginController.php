<?php
use \vista\Vista;
use App\modelo\Usuario;
use libreria\ORM\DpORM;

class LoginController {

    public function index() {
        return Vista::crear("login.login");
    }

    public function ingresar() {
        if(val_csrf()){
                $user = input("user");
                //$password = input("password");                 
                $passwords = Usuario::all();
                foreach($passwords as $usuario){
                    if($user == $usuario->user){
                    $passEnBD = $usuario->pass;
                    }    
                }    
                //la fcion encriptar ahora tiene 2 parametros (pass que ingresa el usuario)
                // y la pass encriptada en la base de datos
                $password = encriptar(input("password"), $passEnBD);        
                $objOrm = new DpORM();
				//Aqui ejecutamos el procedure login que esta en la base de datos
                $data = $objOrm->Ejecutar("login", array($user, $password));           
                if(count($data) > 0) {
                   //si es correcto, vamos a una ruta interna para administrador                   
                   //creamos las variables de session    
                   $_SESSION["id"] = $data[0]["id"]; 
                   $_SESSION["nomyape"] = $data[0]["nomyape"];
                   $_SESSION["user"] = $data[0]["user"];
                   $_SESSION["privilegio"] = $data[0]["privilegio"];                                               
                   $_SESSION["valor"] = "true";//valor para enviar un alertify a admin\index.php                           
                   redireccionar("/admin");
                } else {
                    //si el ingreso falla vuelve al formulario de login
                    $_SESSION["valor"] = "false";
                    redireccionar("/login");                    
                }
        }else{
			redireccionar("/login");
                //echo "No valida el CSRF";
        }
    }

   public function cerrarSesion() {
        session_start();
        session_destroy();
        redireccionar("/login");
    }
	
	 
    
}
