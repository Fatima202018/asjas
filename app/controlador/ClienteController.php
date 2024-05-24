<?php
use \vista\Vista;
use \App\modelo\Cliente;

class ClienteController {
    public function index(){
    	$data = Cliente::all();
        return Vista::crear("clientes.index",array(
        	"clientes" => $data
        ));
    }

    public function crear(){
        return Vista::crear("clientes.crear");
    }

    public function crearcliente(){		
		$cliente = new Cliente();
		$cliente->nomyape = input("txtNomyape");
		$cliente->direccion = input("txtDireccion");
		$cliente->telefono = input("txtTelefono");
		$cliente->email = input("txtEmail");
		$cliente->cuit = input("txtCuit");
		$cliente->condTributaria = strtoupper(input("txtCondTributaria")); //guarda en Mayus
		
		$cliente->guardar();
		redireccionar("/clientes");
    }

    public function eliminar(){
    	$cliente = Cliente::find(input("id"));
    	$cliente->eliminar();

    	redireccionar("/clientes");
    }

    public function editar(){
    	$cliente = Cliente::find(input("id"));    	
    	return Vista::crear("clientes.editar",array(
    		"cliente" => $cliente
    	));
    }

    public function editarcliente(){
		$cliente = new Cliente(); //incluida para que no genere el error		
    	$cliente = Cliente::find(input("id"));	
    	$cliente->nomyape = input("txtNomyape");
    	$cliente->direccion = input("txtDireccion");
		$cliente->telefono = input("txtTelefono");
        $cliente->email = input("txtEmail");
        $cliente->cuit = input("txtCuit");
		$cliente->condTributaria = strtoupper(input("txtCondTributaria")); //guarda en Mayus
    	
        $cliente->guardar();
		
    	redireccionar("/clientes");
    }

     public function cantidad(){
        $cliente = Cliente::all();
        $cantidad = count($cliente);        
        echo $cantidad;    
    }
}