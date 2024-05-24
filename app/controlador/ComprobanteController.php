<?php
use \vista\Vista;
use \App\modelo\Comprobante;

class ComprobanteController {
    public function index(){
    	$data = Comprobante::all();
        return Vista::crear("comprobantes.index",array(
        	"comprobantes" => $data
        ));
    }

    public function crear(){
        return Vista::crear("comprobantes.crear");
    }

    public function crearcomprobante(){
    	$comprobante = new Comprobante();
    	$comprobante->descripcion = input("txtDescripcion");
    	$comprobante->guardar();

    	redireccionar("/comprobantes");
    }

    public function eliminar(){
    	$comprobante = Comprobante::find(input("id"));
    	$comprobante->eliminar();

    	redireccionar("/comprobantes");
    }

    public function editar(){
    	$comprobante = Comprobante::find(input("id"));
    	
    	return Vista::crear("comprobantes.editar",array(
    		"comprobante" => $comprobante
    	));
    }

    public function editarcomprobante(){
    	$comprobante = Comprobante::find(input("id"));
    	$comprobante->descripcion = input("txtDescripcion");
    	$comprobante->guardar();

    	redireccionar("/comprobantes");
    }
        
    //genera un listado de la tabla comprobantes y muestra la descripcion de c/u    
     public function listado() {
        $comprobantes = Comprobante::all();
            foreach($comprobantes as $comprobante) {
                echo $comprobante->descripcion."<br>";
            }
    }
    
    //metodo que busca la descripcion en la tabla comprobantes
     public function busqueda(){
       $idcat = $_REQUEST["id"];
       $comprobante = Comprobante::find($idcat);
       echo $comprobante->descripcion;
   }
    
     public function cantidad(){
        $comprobante = Comprobante::all();
        $cantidad = count($comprobante);        
        echo $cantidad;    
     }     
}