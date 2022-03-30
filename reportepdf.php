<?php
include_once('fpdf/pdf.php');
require_once("class/class.php");
//ob_end_clean();
ob_start();

$casos = array (

                  'PROVINCIAS' => array(

                                    'medidas' => array('P', 'mm', 'A4'),

                                    'func' => 'TablaListarProvincias',

                                    'output' => array('Listado de Provincias.pdf', 'I')

                                  ),

                  'DEPARTAMENTOS' => array(

                                    'medidas' => array('P', 'mm', 'A4'),

                                    'func' => 'TablaListarDepartamentos',

                                    'output' => array('Listado de Departamentos.pdf', 'I')

                                  ),

                  'DOCUMENTOS' => array(

                                    'medidas' => array('P', 'mm', 'A4'),

                                    'func' => 'TablaListarDocumentos',

                                    'output' => array('Listado de Tipos de Documentos.pdf', 'I')

                                  ),

                  'TIPOMONEDA' => array(

                                    'medidas' => array('P', 'mm', 'A4'),

                                    'func' => 'TablaListarTiposMonedas',

                                    'output' => array('Listado de Tipos de Moneda.pdf', 'I')

                                  ),

                'TIPOCAMBIO' => array(

                                    'medidas' => array('P', 'mm', 'A4'),

                                    'func' => 'TablaListarTiposCambio',

                                    'output' => array('Listado de Tipos de Cambio.pdf', 'I')

                                  ),
                  
                  'MEDIOSPAGOS' => array(

                                    'medidas' => array('P', 'mm', 'A4'),

                                    'func' => 'TablaListarMediosPagos',

                                    'output' => array('Listado de Medios de Pago.pdf', 'I')

                                  ),
                  
                  'IMPUESTOS' => array(

                                    'medidas' => array('P', 'mm', 'A4'),

                                    'func' => 'TablaListarImpuestos',

                                    'output' => array('Listado de Impuestos.pdf', 'I')

                                  ),

                  'SALAS' => array(

                                    'medidas' => array('P', 'mm', 'A4'),

                                    'func' => 'TablaListarSalas',

                                    'output' => array('Listado de Salas.pdf', 'I')

                                  ),

                  'MESAS' => array(

                                    'medidas' => array('P', 'mm', 'A4'),

                                    'func' => 'TablaListarMesas',

                                    'output' => array('Listado de Mesas.pdf', 'I')

                                  ),

                  'CATEGORIAS' => array(

                                    'medidas' => array('P', 'mm', 'A4'),

                                    'func' => 'TablaListarCategorias',

                                    'output' => array('Listado de Categorias.pdf', 'I')

                                  ),

                  'MEDIDAS' => array(

                                    'medidas' => array('P', 'mm', 'A4'),

                                    'func' => 'TablaListarMedidas',

                                    'output' => array('Listado de Medidas.pdf', 'I')

                                  ),

                  'USUARIOS' => array(

                                    'medidas' => array('L', 'mm', 'LEGAL'),

                                    'func' => 'TablaListarUsuarios',

                                    'output' => array('Listado de Usuarios.pdf', 'I')

                                  ),

                  'LOGS' => array(

                                    'medidas' => array('L', 'mm', 'LEGAL'),

                                    'func' => 'TablaListarLogs',

                                    'output' => array('Listado Logs de Acceso.pdf', 'I')

                                  ),

                  'CLIENTES' => array(

                                    'medidas' => array('L', 'mm', 'LEGAL'),

                                    'func' => 'TablaListarClientes',

                                    'output' => array('Listado de Clientes.pdf', 'I')

                                  ),

                  'PROVEDORES' => array(

                                    'medidas' => array('L', 'mm', 'LEGAL'),

                                    'func' => 'TablaListarProveedores',

                                    'output' => array('Listado de Proveedores.pdf', 'I')

                                  ),

                 'INGREDIENTES' => array(

                                    'medidas' => array('L', 'mm', 'LEGAL'),

                                    'func' => 'TablaListarIngredientes',

                                    'output' => array('Listado de Ingredientes.pdf', 'I')

                                  ),

                 'INGREDIENTESMINIMO' => array(

                                    'medidas' => array('L', 'mm', 'LEGAL'),

                                    'func' => 'TablaListarIngredientesMinimo',

                                    'output' => array('Listado de Ingredientes en Stock Minimo.pdf', 'I')

                                  ),

                 'INGREDIENTESMAXIMO' => array(

                                    'medidas' => array('L', 'mm', 'LEGAL'),

                                    'func' => 'TablaListarIngredientesMaximo',

                                    'output' => array('Listado de Ingredientes en Stock Maximo.pdf', 'I')

                                  ),

                   'KARDEXINGREDIENTES' => array(

                                    'medidas' => array('L', 'mm', 'LEGAL'),

                                    'func' => 'TablaListarKardexIngredientes',

                                    'output' => array('Listado de Kardex de Ingrediente.pdf', 'I')

                                  ),

                  'INGREDIENTESVENDIDOS' => array(

                                    'medidas' => array('L', 'mm', 'LEGAL'),

                                    'func' => 'TablaListarIngredientesVendidos',

                                    'output' => array('Listado de Ingredientes Vendidos.pdf', 'I')

                                  ),

                 'PRODUCTOS' => array(

                                    'medidas' => array('L', 'mm', 'LEGAL'),

                                    'func' => 'TablaListarProductos',

                                    'output' => array('Listado de Productos.pdf', 'I')

                                  ),

                 'PRODUCTOSMINIMO' => array(

                                    'medidas' => array('L', 'mm', 'LEGAL'),

                                    'func' => 'TablaListarProductosMinimo',

                                    'output' => array('Listado de Productos en Stock Minimo.pdf', 'I')

                                  ),

                 'PRODUCTOSMAXIMO' => array(

                                    'medidas' => array('L', 'mm', 'LEGAL'),

                                    'func' => 'TablaListarProductosMaximo',

                                    'output' => array('Listado de Productos en Stock Maximo.pdf', 'I')

                                  ),

                   'KARDEXPRODUCTOS' => array(

                                    'medidas' => array('L', 'mm', 'LEGAL'),

                                    'func' => 'TablaListarKardexProductos',

                                    'output' => array('Listado de Kardex de Producto.pdf', 'I')

                                  ),

                  'PRODUCTOSVENDIDOS' => array(

                                    'medidas' => array('L', 'mm', 'LEGAL'),

                                    'func' => 'TablaListarProductosVendidos',

                                    'output' => array('Listado de Productos Vendidos.pdf', 'I')

                                  ),

                  'PRODUCTOSXMONEDA' => array(

                                    'medidas' => array('L', 'mm', 'LEGAL'),

                                    'func' => 'TablaListarProductosxMoneda',

                                    'output' => array('Listado de Productos por Moneda.pdf', 'I')

                                  ),

                 'FACTURACOMPRA' => array(

                                    'medidas' => array('L', 'mm', 'LEGAL'),

                                    'func' => 'FacturaCompra',

                                    'output' => array('Factura de Compra.pdf', 'I')

                                  ),

                 'COMPRAS' => array(

                                    'medidas' => array('L', 'mm', 'LEGAL'),

                                    'func' => 'TablaListarCompras',

                                    'output' => array('Listado de Compras.pdf', 'I')

                                  ),

                 'CUENTASXPAGAR' => array(

                                    'medidas' => array('L', 'mm', 'LEGAL'),

                                    'func' => 'TablaListarCuentasxPagar',

                                    'output' => array('Listado de Cuentas por Pagar.pdf', 'I')

                                  ),

              'COMPRASXPROVEEDOR' => array(

                                    'medidas' => array('L', 'mm', 'LEGAL'),

                                    'func' => 'TablaListarComprasxProveedor',

                                    'output' => array('Listado de Compras por Proveedor.pdf', 'I')

                                  ),

              'COMPRASXFECHAS' => array(

                                    'medidas' => array('L', 'mm', 'LEGAL'),

                                    'func' => 'TablaListarComprasxFechas',

                                    'output' => array('Listado de Compras por Fechas.pdf', 'I')

                                  ),
                'CAJAS' => array(

                                    'medidas' => array('P', 'mm', 'A4'),

                                    'func' => 'TablaListarCajas',

                                    'output' => array('Listado de Cajas.pdf', 'I')

                                  ),

               'ARQUEOS' => array(

                                    'medidas' => array('L', 'mm', 'LEGAL'),

                                    'func' => 'TablaListarArqueos',

                                    'output' => array('Listado de Arqueos de Cajas.pdf', 'I')

                                  ),

                'MOVIMIENTOS' => array(

                                    'medidas' => array('P', 'mm', 'A4'),

                                    'func' => 'TablaListarMovimientos',

                                    'output' => array('Listado de Movimientos en Caja.pdf', 'I')

                                  ),

                   'ARQUEOSXFECHAS' => array(

                                    'medidas' => array('L', 'mm', 'LEGAL'),

                                    'func' => 'TablaListarArqueosxFechas',

                                    'output' => array('Listado de Arqueos por Fechas.pdf', 'I')

                                  ),

                  'MOVIMIENTOSXFECHAS' => array(

                                    'medidas' => array('P', 'mm', 'A4'),

                                    'func' => 'TablaListarMovimientosxFechas',

                                    'output' => array('Listado de Movimientos por Fechas.pdf', 'I')

                                  ),
        
                  'COMANDA' => array(

                                    'medidas' => array('P','mm','ticket'),

                                    'func' => 'TicketComanda',

                                    'setPrintFooter' => 'true',

                                    'output' => array('Ticket de Comanda.pdf', 'I')

                                  ),
        
                  'PRECUENTA' => array(

                                    'medidas' => array('P','mm','ticket'),

                                    'func' => 'TicketPrecuenta',

                                    'setPrintFooter' => 'true',

                                    'output' => array('Ticket de Precuenta.pdf', 'I')

                                  ),
        
                  'TICKET' => array(

                                    'medidas' => array('P','mm','ticket'),

                                    'func' => 'TicketVenta',

                                    'setPrintFooter' => 'true',

                                    'output' => array('Ticket de Venta.pdf', 'I')

                                  ),

                  'FACTURA' => array(

                                    'medidas' => array('P', 'mm', 'A4'),

                                    'func' => 'FacturaVenta',

                                    'output' => array('Factura de Ventas.pdf', 'I')

                                  ),

                  'VENTAS' => array(

                                    'medidas' => array('L', 'mm', 'LEGAL'),

                                    'func' => 'TablaListarVentas',

                                    'output' => array('Listado de Ventas.pdf', 'I')

                                  ),

                  'VENTASDIARIAS' => array(

                                    'medidas' => array('L', 'mm', 'LEGAL'),

                                    'func' => 'TablaListarVentasDiarias',

                                    'output' => array('Listado de Ventas del Dia.pdf', 'I')

                                  ),

                  'VENTASXCAJAS' => array(

                                    'medidas' => array('L', 'mm', 'LEGAL'),

                                    'func' => 'TablaListarVentasxCajas',

                                    'output' => array('Listado de Ventas por Cajas.pdf', 'I')

                                  ),

                  'VENTASXFECHAS' => array(

                                    'medidas' => array('L', 'mm', 'LEGAL'),

                                    'func' => 'TablaListarVentasxFechas',

                                    'output' => array('Listado de Ventas por Fechas.pdf', 'I')

                                  ),
        
                  'TICKETCREDITO' => array(

                                    'medidas' => array('P','mm','ticket'),

                                    'func' => 'TicketCredito',

                                    'output' => array('Ticket de Abonos.pdf', 'I')

                                  ),

                  'CREDITOS' => array(

                                    'medidas' => array('L', 'mm', 'LEGAL'),

                                    'func' => 'TablaListarCreditos',

                                    'output' => array('Listado de Creditos.pdf', 'I')

                                  ),

                  'CREDITOSXCLIENTES' => array(

                                    'medidas' => array('L', 'mm', 'LEGAL'),

                                    'func' => 'TablaListarCreditosxClientes',

                                    'output' => array('Listado de Creditos por Clientes.pdf', 'I')

                                  ),

                  'CREDITOSXFECHAS' => array(

                                    'medidas' => array('L', 'mm', 'LEGAL'),

                                    'func' => 'TablaListarCreditosxFechas',

                                    'output' => array('Listado de Creditos por Fechas.pdf', 'I')

                                  ),

                );

 
  $tipo = decrypt($_GET['tipo']);

  $caso_data = $casos[$tipo];
  $pdf = new PDF($caso_data['medidas'][0], $caso_data['medidas'][1], $caso_data['medidas'][2]);
  $pdf->AddPage();
  $pdf->SetAuthor("Allcode");
  $pdf->SetCreator("FPDF Y PHP");
  $pdf->{$caso_data['func']}();
  $pdf->Output($caso_data['output'][0], $caso_data['output'][1]);
  ob_end_flush();

?>