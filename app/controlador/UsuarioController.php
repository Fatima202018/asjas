<?php
use \vista\Vista;
use \App\modelo\Usuario;
 
class UsuarioController {
    public function index(){
        $data = Usuario::all();
        return Vista::crear("usuarios.index",array(
        	"usuarios" => $data
        ));
    }
    
     public function crear(){
        return Vista::crear("usuarios.crear");
    }
    
    public function crearusuario() {
        $usuario = new Usuario();
        $usuario->user = input("txtUser");
		$usuario->nomyape = input("txtNomyApe");
        $usuario->pass = crypt(input("txtPassword"));
        $usuario->direccion = input("txtDireccion");
        $usuario->telefono = input("txtTelefono");
		$usuario->email = input("txtEmail");
        $usuario->privilegio = input("txtPrivilegio") ;       
        $usuario->guardar();
        
		session_start();
        session_destroy();
        redireccionar("/login");		
        //redireccionar("/usuarios");
    }
    
    public function editar(){
        $usuario = Usuario::find(input("id"));
    	return Vista::crear("usuarios.editar",array(
    		"usuario" => $usuario
    	));
        
    }
    
    public function editarusuario(){
    	$usuario = Usuario::find(input("id"));    	
        $usuario->nomyape = input("txtNomyApe");		
        $usuario->user = input("txtUser");		
        //$usuario->pass = crypt(input("txtPassword"));		
        $usuario->direccion = input("txtDireccion");
        $usuario->telefono = input("txtTelefono");
		$usuario->email = input("txtEmail");
        $usuario->privilegio = input("txtPrivilegio") ;
    	 
        $usuario->guardar();
        
        redireccionar("/usuarios");
    }
    
    //solo para que cada usuario pueda cambiar sus datos y pass
     public function editar_perfil(){
        $usuario = Usuario::find(input("id"));
    	return Vista::crear("usuarios.editar_perfil",array(
    		"usuario" => $usuario
    	));    
    }
    
    //solo para que CADA usuario pueda cambiar sus datos y pass
    public function editarperfilusuario(){
    	$usuario = Usuario::find(input("id"));    	
        //$usuario->user = input("txtUser");		
		$usuario->user = $usuario->user;		
        $usuario->nomyape = input("txtNomyApe");
        $usuario->pass = crypt(input("txtPassword"));
        $usuario->direccion = input("txtDireccion");
        $usuario->telefono = input("txtTelefono");
		$usuario->email = input("txtEmail");
        //$usuario->privilegio = input("txtPrivilegio") ;
		$usuario->privilegio = $usuario->privilegio;
    	 
        $usuario->guardar();
        session_start();
        session_destroy();
        redireccionar("/login");
    }
    
	public function eliminar(){
		//es para el caso que un usuario logueado se autoelimina, el sistema sale al login
		if (input("id") == $_SESSION["id"]){
			$usuario = Usuario::find(input("id"));
			$usuario->eliminar();
			redireccionar("/login");
		}else{
			$usuario = Usuario::find(input("id"));
			$usuario->eliminar();

			redireccionar("/usuarios");
		}
    }
	
    public function cantidad(){
        $usuario = Usuario::all();
        $cantidad = count($usuario);        
        echo $cantidad;    
    }    
}