<?php
$ruta = new Ruta();

$ruta->controladores(array(
    "/"=>"WelcomeController",
    "/login"=>"LoginController",
    "/admin"=>"AdminController",
    "/ventas"=>"VentaController",
    "/devoluciones"=>"DevolucionController",    
    "/clientes"=>"ClienteController",
    "/compras"=>"CompraController",
    "/proveedores"=>"ProveedorController",
    "/categorias"=>"CategoriaController",
    "/marcas"=>"MarcaController",
    "/unidades"=>"UnidadController",
    "/productos"=>"ProductoController",
    "/reportes"=>"ReporteController",
    "/usuarios"=>"UsuarioController",
    "/parametros"=>"ParametroController",
    "/pagos"=>"PagoController",
    "/flujodecajas"=>"FlujodeCajaController",
    "/comprobantes"=>"ComprobanteController",
	"/tarjetas"=>"TarjetaController",
));