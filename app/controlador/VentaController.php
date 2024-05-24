<?php
use \vista\Vista;
use \App\modelo\Venta;
use \App\modelo\Detalleventa;
use \App\modelo\Detalleimpositivoventa;
use \App\modelo\Producto;
use \App\modelo\Detallepagoventa;
use \App\modelo\Devolucion;
use \App\modelo\Flujodecaja;
use \App\modelo\Cliente;
use \App\modelo\Parametro;
 
class VentaController {
	//ruta principal retorna el listado de ventas
    public function index(){
    	$data = Venta::all();
        return Vista::crear("ventas.index",array(
        	"ventas" => $data
        ));
    }
	
    public function crear(){
       $data = Venta::all();
        return Vista::crear("ventas.crear",array(
        	"ventas" => $data
        ));
     }
    
    public function crearventa(){
		//creamos el objeto Parametro para sacar datos de la empresa
		//Lo hacemos porque estamos dentro de una clase
		$parametro = new Parametro();
		$parametro = Parametro::find("1");
		
    	//Guardo en tabla VENTAS
        $venta = new Venta();
        
        $venta->idUsuario = input("txtIdUsuario");				
        $venta->idCliente = input("txtIdCliente");	
        $venta->nroVenta = input("txtNroVentaOk");		
    	$venta->idComprobante = input("txtIdComprobante");						
        $venta->descGlobal = input("txtDesc"); 							
		
		$venta->totalImpuesto = input("txtImpuestoOk"); //total de impuesto				
		$venta->subTotalNeto = input("txtSubTotalOk"); //subtotal Neto sin impuesto		
        $venta->totalVenta = input("txtTotalVentaOk");		
    	$venta->fecha = input("txtFechaActualOk");		
		
		$venta->estado = "Emitida";
		
        $venta->guardar();		
		
		//Guardo en tabla DETALLEIMPOSITIVOVENTAS
		$detalleimpositivoventa = new Detalleimpositivoventa();
		
		$detalleimpositivoventa->idVenta = $venta->id;
		$detalleimpositivoventa->pais = $parametro->pais;
		$detalleimpositivoventa->moneda = $parametro->moneda;
		$detalleimpositivoventa->idenTributaria = $parametro->idenTributaria;		
		$detalleimpositivoventa->nom_imp = $parametro->nombre_impuesto;
		$detalleimpositivoventa->porc_imp = $parametro->porcentaje_impuesto;
		$detalleimpositivoventa->condicionIVA = $parametro->condicionIVA;
		$detalleimpositivoventa->puntoVenta = $parametro->puntoVenta;			
		//creamos el objeto cliente para sacar datos del cliente
		$cliente = new Cliente();
		$cliente = Cliente::find($venta->idCliente);
		//guardo cond de iva de la empresa y el cliente
		$detalleimpositivoventa->emisor_receptor = $parametro->condicionIVA."-".$cliente->condTributaria;		
		$detalleimpositivoventa->guardar();

        //Guardo en tabla DETALLEVENTAS
        $detalleventa = new Detalleventa();
        		
        //recibo los array en formato JSON y los decodifico a arrays comunes
        $cantidad = $_POST['txtRowCount']; //cantidad de productos distintos		
        $arrayIdProd = json_decode($_POST['txtArrayIdProd'], true); //ID de c/producto		
        $arrayCodxProd = json_decode($_POST['txtArrayCodxProd'], true); 	
        $arrayCantxProd = json_decode($_POST['txtArrayCantxProd'], true); 	
        $arrayDescxProd = json_decode($_POST['txtArrayDescxProd'], true); 	
        $arrayNomxProd = json_decode($_POST['txtArrayNomxProd'], true); 			
		$arrayPrecioVentaNetoxProd = json_decode($_POST['txtArrayPrecioVentaNetoxProd'], true); 	
        $arrayPrecioVentaxProd = json_decode($_POST['txtArrayPrecioVentaxProd'], true); 	
        $arraysubTotalxProd = json_decode($_POST['txtArraysubTotalxProd'], true);			
        //recorro todos los Arrays con el mismo indice (ya que todos son del mismo tamaño). 
        //De esta forma guardo cada producto en detalle de venta
        //Uso cualquier array, ejemplo arrayIdProd
		
		if (is_array($arrayIdProd) || is_object($arrayIdProd)) {			
			foreach($arrayIdProd as $indice=>$valor){			  			
				//echo $indice." => ".$valor."<br>";
				$detalleventa->idVenta = $venta->id;//El ID de venta es el mismo siempre				
				$detalleventa->idProducto = $arrayIdProd[$indice];                      
				$detalleventa->codProd = $arrayCodxProd[$indice];                      
				$detalleventa->nomProd = $arrayNomxProd[$indice];   
				$detalleventa->cantidad = $arrayCantxProd[$indice];                      				
				$detalleventa->precioVentaNeto = $arrayPrecioVentaNetoxProd[$indice];
				$detalleventa->precioVenta = $arrayPrecioVentaxProd[$indice];                      
				$detalleventa->descuento = $arrayDescxProd[$indice];
				$detalleventa->total = $arraysubTotalxProd[$indice];                    				
				$detalleventa->guardar(); 
			}
		}
        //actualizamos el stock de la tabla Productos (ya que realizamos una venta)
		//por lo tanto decrece el stock
        $producto = new Producto();    
        		
		if (is_array($arrayIdProd) || is_object($arrayIdProd)) {	
			foreach($arrayIdProd as $indice=>$valor){
				//echo "<br>ID: ".$arrayIdProd[$indice];
				$lista_productos = Producto::where("id", $arrayIdProd[$indice]);
				//echo "Cantidad a descontar: ".$arrayCantxProd[$indice]."<br>";
				foreach($lista_productos as $lista_producto){
					//echo "Stock ACTUAL (sin descontar): ".$lista_producto->stock;
					$lista_producto->stock = $lista_producto->stock - $arrayCantxProd[$indice];
					//echo "<br>Stock ACTUAL (con el DESCUENTO): ".$lista_producto->stock;
					$producto->id = $lista_producto->id;
					$producto->stock = $lista_producto->stock;
					//echo "<br>ID : ".$producto->id. " su stock nuevo es: ".$producto->stock."<br>";
					$producto->guardar();                     
				}    
			}
		}
		
		//Guardo en tabla DETALLEPAGOSVENTAS
        $detallepagoventa = new Detallepagoventa();
         
        $detallepagoventa->idVenta = $venta->id;
        $detallepagoventa->idFormaPago = input("txtIdFormaPago");
        $detallepagoventa->cuotas = input("txtCuotas");
        $detallepagoventa->pagoEfectivo = input("txtPagoEfectivo");
        $detallepagoventa->pagoDebito = input("txtPagoDebito");
        $detallepagoventa->pagoCredito = input("txtPagoCredito");
		
		//nuevo para OFP Otras Formas de Pago
		if(input("txtIdFormaPago") == 8){
			//si es 8 es OFP, y se guarda el nombre (ej nota de credito y combinaciones)
			$detallepagoventa->nombreOFP = input("txtNombreOFP");
			$detallepagoventa->pagoOFP = input("txtPagoOFP");	
		}else{
			//no guarda el nombre de OFP ya que en tabla formaspagos estan los nombres del 1 al 7
			//$detallepagoventa->nombreOFP = null;
			//$detallepagoventa->pagoOFP = null;	
			$detallepagoventa->nombreOFP = "Ninguna";
			$detallepagoventa->pagoOFP = "Ninguna";	
		}

        $detallepagoventa->totalVenta = $venta->totalVenta;		
        $detallepagoventa->tarjDebito = input("txtTarjetaDebito");		
        $detallepagoventa->tarjCredito = input("txtTarjetaCredito");		
        $detallepagoventa->fechaVenta = $venta->fecha; 		
		$detallepagoventa->guardar();		
		
		//Guardo en tabla FLUJODECAJAS cualquier pago que implique EFECTIVO
		if(input("txtIdFormaPago") == 1){
			//Guardo en tabla flujo de cajas pagos pago Efectivo
			$flujodecaja = new Flujodecaja();
			$flujodecajas = Flujodecaja::all();

			$flujodecaja->fecha = date('Y/m/d H:i:s'); //toma fecha y hora actual
			$flujodecaja->descripcion = "Venta";
			$flujodecaja->entrada = $venta->totalVenta;
			$flujodecaja->salida = "0.00";			
			//debo sumar al ultimo valor del saldo
			$ventas = Venta::all();
			$last = [];

			if (empty($flujodecajas)){	
				$flujodecaja->saldoActual = $venta->totalVenta;
			 }else{
				$last = array_pop($flujodecajas);
				$last->saldoActual = $last->saldoActual + $venta->totalVenta;    
				$flujodecaja->saldoActual =  $last->saldoActual;
			}
			$flujodecaja->guardar()	;	
			
		}else 
		//en forma de pago igual a 4, 5 y 7	tamabien hubo pago en efectivo
		if(input("txtIdFormaPago") == 4 || input("txtIdFormaPago") == 5 || input("txtIdFormaPago") == 7){
			//Guardo en tabla flujo de cajas
			$flujodecaja = new Flujodecaja();
			$flujodecajas = Flujodecaja::all();

			$flujodecaja->fecha = date('Y/m/d H:i:s'); //toma fecha y hora actual
			$flujodecaja->descripcion = "Venta";
			$flujodecaja->entrada = $detallepagoventa->pagoEfectivo;
			$flujodecaja->salida = "0.00";

			//debo sumar al ultimo valor del saldo
			$ventas = Venta::all();
			$last = [];

			if (empty($flujodecajas)){	
				$flujodecaja->saldoActual = $detallepagoventa->pagoEfectivo;
			 }else{
				$last = array_pop($flujodecajas);
				$last->saldoActual = $last->saldoActual + $detallepagoventa->pagoEfectivo;    
				$flujodecaja->saldoActual =  $last->saldoActual;
			}
			$flujodecaja->guardar()	;	
		}
		
		//codigo para el caso que se pague con nota de credito
		else
		if(input("txtIdFormaPago") == 8){						
				//Guardo en tabla flujo de cajas
				$flujodecaja = new Flujodecaja();
				$flujodecajas = Flujodecaja::all();

				$flujodecaja->fecha = date('Y/m/d H:i:s'); //toma fecha y hora actual
				$flujodecaja->descripcion = "Venta"; //aqui va la parte que se cancela con efectivo				
				//$flujodecaja->entrada = $detallepagoventa->pagoOFP;
			    $flujodecaja->entrada = $detallepagoventa->pagoEfectivo;
				$flujodecaja->salida = "0.00";

				//debo sumar al ultimo valor del saldo
				$ventas = Venta::all();
				$last = [];

				if (empty($flujodecajas)){	
					$flujodecaja->saldoActual = $detallepagoventa->pagoEfectivo;
				 }else{
					$last = array_pop($flujodecajas);
					$last->saldoActual = $last->saldoActual + $detallepagoventa->pagoEfectivo;    
					$flujodecaja->saldoActual =  $last->saldoActual;
				}
				$flujodecaja->guardar()	;						
				//Guardo en tabla DEVOLUCIONES
				$nroNC = input("txtNroNC"); //recibo el nro de NC				
				$devolucion = new Devolucion();
				//$devoluciones = Devolucion::where("id", $nroNC);
				$devoluciones = Devolucion::where("nroNotaCredito", $nroNC);				
				foreach($devoluciones as $devolucion){
					//$devolucion->id = $nroNC; //guardo el nro de NC
					$devolucion->estado = "Cancelada"; //Pasa de "Pendiente" a "Cancelada"					
					$devolucion->guardar();						
				}
		}		
		
		//controlar el PAIS
		/*if($parametro->pais == "AR"){			
			//Control para imprimir factura A, B o C		
			if($parametro->condicionIVA == "RI" && $cliente->condTributaria == "RI"){
				redireccionar("/ventas/imprimirA?id=$venta->id");   			
			}
			if($parametro->condicionIVA == "RI" && $cliente->condTributaria == "CF"){
				redireccionar("/ventas/imprimirB?id=$venta->id");
			}
			if($parametro->condicionIVA == "MT"){
				redireccionar("/ventas/imprimirC?id=$venta->id");
			}	
		}
		else{
			redireccionar("/ventas/imprimir?id=$venta->id");	
		}*/
		
		//nuevo para tickets
		if($parametro->pais == "AR"){			
			//Control para imprimir factura A, B o C		
			if($parametro->condicionIVA == "RI" && $cliente->condTributaria == "RI"){
				switch($parametro->modo_impresion){				
					case "F":
						$comp = "ventas/imprimirA?id=";
						break;
					case "T":
						$comp = "ventas/ticketA?id=";
						break;	
				}
				redireccionar($comp.$venta->id);   			
			}
			if($parametro->condicionIVA == "RI" && $cliente->condTributaria == "CF"){
				switch($parametro->modo_impresion){				
					case "F":
						$comp = "ventas/imprimirB?id=";
						break;
					case "T":
						$comp = "ventas/ticketB?id=";
						break;	
				}
				redireccionar($comp.$venta->id); 				
			}
			if($parametro->condicionIVA == "MT"){
				switch($parametro->modo_impresion){				
					case "F":
						$comp = "ventas/imprimirC?id=";
						break;
					case "T":
						$comp = "ventas/ticketC?id=";
						break;	
				}
				redireccionar($comp.$venta->id);
			}	
		} //si Pais es distinto de Argentina
		else{
			switch($parametro->modo_impresion){				
					case "F":
						$comp = "ventas/imprimir?id=";
						break;
					case "T":
						$comp = "ventas/ticket?id=";
						break;	
				}
			redireccionar($comp.$venta->id);
		}
		
    }
    
    public function eliminar(){
    	$venta = Venta::find(input("id"));
    	$venta->eliminar();

    	redireccionar("/ventas");
    }
        
    public function historial(){
    	$data = Venta::all();
        return Vista::crear("ventas.historial",array(
        	"ventas" => $data
        ));    
    }
    
    //muestra el detalle de una venta en el formulario para devolucion
    public function devolucion(){
        $venta = Venta::find(input("id"));
    	return Vista::crear("ventas.devolucion",array(
    		"venta" => $venta
    	));    
    } 
	
	public function detalle(){
        $venta = Venta::find(input("id"));
    	return Vista::crear("ventas.detalle",array(
    		"venta" => $venta
    	));
    }	
	
	//Formato FACTURAS
	public function imprimir(){
        $venta = Venta::find(input("id"));
    	return Vista::crear("ventas.imprimir",array(
    		"venta" => $venta
    	));
    }
	
	//para comprabantes de RI a RI
	public function imprimirA(){
        $venta = Venta::find(input("id"));
    	return Vista::crear("ventas.imprimirA",array(
    		"venta" => $venta
    	));
    }
		
	//para comprabantes de RI a cons final
	public function imprimirB(){
        $venta = Venta::find(input("id"));
    	return Vista::crear("ventas.imprimirB",array(
    		"venta" => $venta
    	));
    }
	
	//para comprabantes de MT a cons final
	public function imprimirC(){
        $venta = Venta::find(input("id"));
    	return Vista::crear("ventas.imprimirC",array(
    		"venta" => $venta
    	));
    }
	
	//Formato TICKETS
	public function ticket(){
        $venta = Venta::find(input("id"));
    	return Vista::crear("ventas.ticket",array(
    		"venta" => $venta
    	));
    }
	
	public function ticketA(){
        $venta = Venta::find(input("id"));
    	return Vista::crear("ventas.ticketA",array(
    		"venta" => $venta
    	));
    }
	
	public function ticketB(){
        $venta = Venta::find(input("id"));
    	return Vista::crear("ventas.ticketB",array(
    		"venta" => $venta
    	));
    }
	
	public function ticketC(){
        $venta = Venta::find(input("id"));
    	return Vista::crear("ventas.ticketC",array(
    		"venta" => $venta
    	));
    }
        
    public function cantidad(){
        $venta = Venta::all();
        $cantidad = count($venta);        
        echo $cantidad;    
    }   
}