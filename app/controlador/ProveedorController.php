<?php

use \vista\Vista;
use \App\modelo\Proveedor;

class ProveedorController {

	//ruta principal retorna el listado de proveedores
    public function index(){
    	$data = Proveedor::all();
        return Vista::crear("proveedores.index",array(
        	"proveedores" => $data
        ));
    }

    public function crear(){
        return Vista::crear("proveedores.crear");
    }

    public function crearproveedor(){
    	$proveedor = new Proveedor();
        
    	$proveedor->razon_social = input("txtRazonSocial");
    	$proveedor->direccion = input("txtDireccion");
		$proveedor->telefono = input("txtTelefono");
        $proveedor->email = input("txtEmail");
		$proveedor->cuit = input("txtCuit");
		$proveedor->condTributaria = strtoupper(input("txtCondTributaria"));
    	$proveedor->guardar();

    	redireccionar("/proveedores");
    }

    public function eliminar(){
    	$proveedor = Proveedor::find(input("id"));
    	$proveedor->eliminar();

    	redireccionar("/proveedores");
    }

    public function editar(){		
    	$proveedor = Proveedor::find(input("id"));
    	return Vista::crear("proveedores.editar",array(
    		"proveedor" => $proveedor
    	));
    }

    public function editarproveedor(){
    	$proveedor = Proveedor::find(input("id"));
        
    	$proveedor->razon_social = input("txtRazonSocial");
    	$proveedor->direccion = input("txtDireccion");
		$proveedor->telefono = input("txtTelefono");
        $proveedor->email = input("txtEmail");
		$proveedor->cuit = input("txtCuit");
		$proveedor->condTributaria = strtoupper(input("txtCondTributaria"));
        $proveedor->guardar();

    	redireccionar("/proveedores");
    }

     public function cantidad(){
        $proveedor = Proveedor::all();
        $cantidad = count($proveedor);        
        echo $cantidad;    
    }
}