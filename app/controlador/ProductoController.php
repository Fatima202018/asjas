<?php
use \vista\Vista;
use \App\modelo\Producto;

class ProductoController {

	//ruta principal retorna el listado de productos
    public function index(){
    	$data = Producto::all();
        return Vista::crear("productos.index",array(
        	"productos" => $data
        ));
    }

    public function crear(){
        return Vista::crear("productos.crear");
    }
   	
	public function crearproducto() {
	try	{
		$producto = new Producto();
    	$producto->codigo = strtoupper(input("txtCodigo"));   
        $producto->nombre = input("txtNombre");
        $producto->precioCompra = input("txtPrecioCompra");
        $producto->precioVenta = input("txtPrecioVenta");			
        $producto->stock = input("txtStock");		
        $producto->stockMin = input("txtStockMin");		
        $producto->idCategoria = input("txtIdCategoria");		
        $producto->idMarca = input("txtIdMarca");
        $producto->idUnidad = input("txtIdUnidad");
		
		$producto->formato_codigo = input("formatoCodeBar"); //nuevo codigo de barras        
    	$producto->guardar();   
        redireccionar("/productos");
	}catch (Exception $e) {
            echo $e->getMessage();
        }	
    }
	    
    public function eliminar(){
    	$producto = Producto::find(input("id"));
    	$producto->eliminar();
        
        redireccionar("/productos");
    }

    public function editar(){
    	$producto = Producto::find(input("id"));
        
    	return Vista::crear("productos.editar",array(
    		"producto" => $producto
    	));
        
    }
 
    public function editarproducto(){
		try{			
			//$producto = new Producto(); //incluida para que no genere el error		
			$producto = Producto::find(input("id"));
			
			$producto->codigo = strtoupper(input("txtCodigo"));			
			$producto->nombre = input("txtNombre");						
			$producto->precioCompra = input("txtPrecioCompra");						
			$producto->precioVenta = input("txtPrecioVenta");
			$producto->stock = input("txtStock");			
			$producto->stockMin = input("txtStockMin");
			$producto->idCategoria = input("txtCategoria");
			//$producto->idMarca = input("txtIdMarca");			
			//$producto->idUnidad = input("txtIdUnidad");
			$producto->idMarca = input("txtMarca");			
			$producto->idUnidad = input("txtUnidad");			
			$producto->formato_codigo = input("formatoCodeBar"); //nuevo codigo de barras
			
			$producto->guardar();
			redireccionar("/productos");
		}catch (Exception $e) {
            echo $e->getMessage();
        }
    }
  
    public function cantidad(){
        $producto = Producto::all();
        $cantidad = count($producto);        
        echo $cantidad;    
    }
    
    //genera un listado de la tabla categorias y muestra la descripcion de c/u    
     public function listado() {
        $productos = Producto::all();
            foreach($productos as $productos) {
                echo $productos->nombre;
                echo $productos->stock."<br>";
            }
    }
    
}