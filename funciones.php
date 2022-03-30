<?php
require_once("class/class.php");
?>
<script type="text/javascript" src="assets/script/jsventas.js"></script>
<script src="assets/script/autocompleto.js"></script> 

<?php
$imp = new Login();
$imp = $imp->ImpuestosPorId();
$impuesto = $imp[0]['nomimpuesto'];
$valor = $imp[0]['valorimpuesto'];

$con = new Login();
$con = $con->ConfiguracionPorId();
$simbolo = "<strong>".$con[0]['simbolo']."</strong>";

$new = new Login();
?>


<?php 
######################## BUSCA DEPARTAMENTOS POR PROVINCIAS ########################
if (isset($_GET['BuscaDepartamentos']) && isset($_GET['id_provincia'])) {
  
   $dep = $new->ListarDepartamentoXProvincias();

$id_provincia = limpiar($_GET['id_provincia']);

 if($id_provincia=="") { ?>

  <option value=""> -- SIN RESULTADOS -- </option>
  <?php } else { ?>

    <option value=""> -- SELECCIONE -- </option>
  <?php
   for($i=0;$i<sizeof($dep);$i++){
    ?>
<option value="<?php echo $dep[$i]['id_departamento']; ?>" ><?php echo $dep[$i]['departamento']; ?></option>
    <?php 
    }
  }
}
######################## BUSCA DEPARTAMENTOS POR PROVINCIAS ########################
?>

<?php 
######################## SELECCIONE DEPARTAMENTOS POR PROVINCIAS ########################
if (isset($_GET['SeleccionaDepartamento']) && isset($_GET['id_provincia']) && isset($_GET['id_departamento'])) {
  
   $dep = $new->SeleccionaDepartamento();
  ?>
    </div>
  </div>
       <option value="">SELECCIONE</option>
  <?php for($i=0;$i<sizeof($dep);$i++){ ?>
<option value="<?php echo $dep[$i]['id_departamento']; ?>"<?php if (!(strcmp($_GET['id_departamento'], htmlentities($dep[$i]['id_departamento'])))) {echo "selected=\"selected\"";} ?>><?php echo $dep[$i]['departamento']; ?></option>
<?php
   } 
}
######################## SELECCIONE LOCALIDAD POR CIUDADES ########################
?>


<?php
######################## MOSTRAR USUARIO EN VENTANA MODAL ############################
if (isset($_GET['BuscaUsuarioModal']) && isset($_GET['codigo'])) { 
$reg = $new->UsuariosPorId();
?>

  <table class="table-responsive" border="0" align="center">
  <tr>
    <td><strong>Nº de Documento:</strong> <?php echo $reg[0]['dni']; ?></td>
  </tr>
  <tr>
    <td><strong>Nombres y Apellidos:</strong> <?php echo $reg[0]['nombres']; ?></td>
  </tr>
  <tr>
    <td><strong>Sexo:</strong> <?php echo $reg[0]['sexo']; ?></td>
  </tr>
  <tr>
    <td><strong>Dirección Domiciliaria: </strong> <?php echo $reg[0]['direccion']; ?></td>
  </tr>
  <tr>
    <td><strong>Nº de Teléfono: </strong> <?php echo $reg[0]['telefono']; ?></td>
  </tr>
  <tr>
    <td><strong>Correo Electrónico: </strong> <?php echo $reg[0]['email']; ?></td>
  </tr>
  <tr>
    <td><strong>Usuario de Acceso: </strong> <?php echo $reg[0]['usuario']; ?></td>
  </tr>
  <tr>
    <td><strong>Nivel de Acceso: </strong> <?php echo $reg[0]['nivel']; ?></td>
  </tr>
  <tr>
    <td><strong>Comisión por Ventas: </strong> <?php echo $reg[0]['comision']; ?>%</td>
  </tr>
  <tr>
  <td><strong>Status de Acceso: </strong> <?php echo $status = ( $reg[0]['status'] == 1 ? "<span class='badge badge-pill badge-success'><i class='fa fa-check'></i> ACTIVO</span>" : "<span class='badge badge-pill badge-warning'><i class='fa fa-times'></i> INACTIVO</span>"); ?></td>
  </tr>
</table>  

  <?php
   } 
######################## MOSTRAR USUARIO EN VENTANA MODAL ############################
?>



<?php 
######################## SELECCIONE DEPARTAMENTOS POR PROVINCIAS ########################
if (isset($_GET['MuestraUsuario']) && isset($_GET['codigo']) && isset($_GET['codsucursal'])) {
  
$usuario = $new->BuscarUsuariosxSucursal();
?>
<option value=""> -- SELECCIONE -- </option>
  <?php
   for($i=0;$i<sizeof($usuario);$i++){
    ?>
<option value="<?php echo $usuario[$i]['codigo'] ?>"<?php if (!(strcmp($_GET['codigo'], htmlentities($usuario[$i]['codigo'])))) { echo "selected=\"selected\"";} ?>><?php echo $usuario[$i]['nombres'].": ".$usuario[$i]['nivel']; ?></option>
<?php
   } 
}
######################## SELECCIONE LOCALIDAD POR CIUDADES ########################
?>





<?php 
######################## MUESTRA DIV CLIENTE ########################
if (isset($_GET['BuscaDivCliente'])) {
  
  ?>
<div class="row">
      <div class="col-md-12">
<font color="red"><strong> Para poder realizar la Carga Masiva de Clientes, el archivo Excel, debe estar estructurado de 11 columnas, la cuales tendrán las siguientes especificaciones:</strong></font><br>

  1. Código de Cliente. (Ejemplo: C1, C2, C3, C4, C5......)<br>
  2. Tipo de Documento. (Debera de Ingresar el Codigo de Documento a la que corresponde)<br>
  3. Nº de Documento.<br>
  4. Nombre de Cliente (Ingresar Nombre completo con Apellidos).<br>
  5. Nº de Teléfono. (Formato: (9999) 9999999).<br>
  6. Provincia. (Debera de Ingresar el Codigo de Provincia a la que corresponde)<br>
  7. Departamento. (Debera de Ingresar el Codigo de Departamento a la que corresponde)<br>
  8. Dirección Domiciliaria.<br>
  9. Correo Electronico.<br>
  10. Tipo de Cliente.<br>
  11. Monto de Crédito en Ventas.<br><br>

  <font color="red"><strong> NOTA:</strong></font><br>
  a) El Archivo no debe de tener cabecera, solo deben estar los registros a grabar.<br>
  b) Se debe de guardar como archivo .CSV  (delimitado por comas)(*.csv).<br>
  c) Todos los datos deberán escribirse en mayúscula para mejor orden y visibilidad en los reportes.<br>
  d) Deben de tener en cuenta que la carga masiva de Clientes, deben de ser cargados como se explica, para evitar problemas de datos del cliente dentro del Sistema.<br><br>
   </div>
</div>                               
<?php 
  }
######################## MUESTRA DIV CLIENTE ########################
?>

<?php
######################## MOSTRAR CLIENTE EN VENTANA MODAL ########################
if (isset($_GET['BuscaClienteModal']) && isset($_GET['codcliente'])) { 

$reg = $new->ClientesPorId();
?>
 <table class="table-responsive" border="0" align="center">
  <tr>
    <td><strong>Código:</strong> <?php echo $reg[0]['codcliente']; ?></td>
  </tr>
  <tr>
    <td><strong>Nº de <?php echo $reg[0]['documcliente'] == '0' ? "Documento" : $reg[0]['documento'] ?>:</strong> <?php echo $reg[0]['dnicliente']; ?></td>
  </tr>
  <tr>
    <td><strong>Nombres y Apellidos:</strong> <?php echo $reg[0]['nomcliente']; ?></td>
  </tr>
  <tr>
    <td><strong>Nº de Teléfono: </strong> <?php echo $reg[0]['tlfcliente'] == '' ? "*********" : $reg[0]['tlfcliente'] ?></td>
  </tr>
  <tr>
    <td><strong>Provincia: </strong> <?php echo $reg[0]['provincia'] == '' ? "*********" : $reg[0]['provincia'] ?></td>
  </tr>
  <tr>
    <td><strong>Departamento: </strong> <?php echo $reg[0]['departamento'] == '' ? "*********" : $reg[0]['departamento'] ?></td>
  </tr>
  <tr>
    <td><strong>Dirección Domiciliaria: </strong> <?php echo $reg[0]['direccliente']; ?></td>
  </tr>
  <tr>
    <td><strong>Correo Electrónico: </strong> <?php echo $reg[0]['emailcliente'] == '' ? "*********" : $reg[0]['emailcliente'] ?></td>
  </tr> 
  <tr>
    <td><strong>Tipo de Cliente: </strong> <?php echo $reg[0]['tipocliente']; ?></td>
  </tr> 
  <tr>
    <td><strong>Limite de Crédito: </strong> <?php echo $reg[0]['limitecredito']; ?></td>
  </tr> 
  <tr>
    <td><strong>Fecha de Ingreso: </strong> <?php echo date("d-m-Y",strtotime($reg[0]['fechaingreso'])); ?></td>
  </tr>
</table>
<?php 
} 
######################## MOSTRAR CLIENTE EN VENTANA MODAL ########################
?>












<?php 
######################## MUESTRA DIV PROVEEDOR ########################
if (isset($_GET['BuscaDivProveedor'])) {
  
  ?>
<div class="row">
      <div class="col-md-12">
<font color="red"><strong> Para poder realizar la Carga Masiva de Proveedores, el archivo Excel, debe estar estructurado de 11 columnas, la cuales tendrán las siguientes especificaciones:</strong></font><br>

  1. Código de Proveedor. (Ejemplo: P1, P2, P3, P4, P5......)<br>
  2. Tipo de Documento. (Debera de Ingresar el Codigo de Documento a la que corresponde)<br>
  3. Nº de Documento.<br>
  4. Nombre de Proveedor (Ingresar Nombre de Proveedor).<br>
  5. Nº de Teléfono. (Formato: (9999) 9999999).<br>
  6. Provincia. (Debera de Ingresar el Codigo de Provincia a la que corresponde)<br>
  7. Departamento. (Debera de Ingresar el Codigo de Departamento a la que corresponde)<br>
  8. Dirección de Proveedor.<br>
  9. Correo Electronico.<br>
  10. Nombre de Vendedor.<br>
  11. Nº de Teléfono de Vendedor. (Formato: (9999) 9999999).<br><br>

  <font color="red"><strong> NOTA:</strong></font><br>
  a) El Archivo no debe de tener cabecera, solo deben estar los registros a grabar.<br>
  b) Se debe de guardar como archivo .CSV  (delimitado por comas)(*.csv).<br>
  c) Todos los datos deberán escribirse en mayúscula para mejor orden y visibilidad en los reportes.<br>
  d) Deben de tener en cuenta que la carga masiva de Proveedores, deben de ser cargados como se explica, para evitar problemas de datos del proveedor dentro del Sistema.<br><br>
   </div>
</div>
<?php 
  }
######################## MUESTRA DIV PROVEEDOR ########################
?>

<?php
######################## MOSTRAR PROVEEDOR EN VENTANA MODAL ########################
if (isset($_GET['BuscaProveedorModal']) && isset($_GET['codproveedor'])) { 

$reg = $new->ProveedoresPorId();
?>
  
  <table class="table-responsive" border="0" align="center">
  <tr>
    <td><strong>Código:</strong> <?php echo $reg[0]['codproveedor']; ?></td>
  </tr>
  <tr>
    <td><strong>Nº de <?php echo $reg[0]['documproveedor'] == '0' ? "Documento" : $reg[0]['documento'] ?>:</strong> <?php echo $reg[0]['cuitproveedor']; ?>:</td>
  </tr>
  <tr>
    <td><strong>Nombres de Proveedor:</strong> <?php echo $reg[0]['nomproveedor']; ?></td>
  </tr>
  <tr>
    <td><strong>Nº de Teléfono: </strong> <?php echo $reg[0]['tlfproveedor']; ?></td>
  </tr>
  <tr>
    <td><strong>Provincia: </strong> <?php echo $reg[0]['provincia'] == '' ? "*********" : $reg[0]['provincia'] ?></td>
  </tr>
  <tr>
    <td><strong>Departamento: </strong> <?php echo $reg[0]['departamento'] == '' ? "*********" : $reg[0]['departamento'] ?></td>
  </tr>
  <tr>
    <td><strong>Dirección de Proveedor: </strong> <?php echo $reg[0]['direcproveedor']; ?></td>
  </tr>
  <tr>
    <td><strong>Correo Electrónico: </strong> <?php echo $reg[0]['emailproveedor']; ?></td>
  </tr> 
  <tr>
    <td><strong>Vendedor: </strong> <?php echo $reg[0]['vendedor']; ?></td>
  </tr> 
  <tr>
    <td><strong>Nº de Teléfono: </strong> <?php echo $reg[0]['tlfvendedor']; ?></td>
  </tr>
  <tr>
    <td><strong>Fecha de Ingreso: </strong> <?php echo date("d-m-Y",strtotime($reg[0]['fechaingreso'])); ?></td>
  </tr>
</table>
<?php 
} 
######################## MOSTRAR PROVEEDOR EN VENTANA MODAL ########################
?>





























<?php 
######################## MUESTRA DIV INGREDIENTE ########################
if (isset($_GET['BuscaDivIngrediente'])) {
  
  ?>
<div class="row">
      <div class="col-md-12">
<font color="red"><strong> Para poder realizar la Carga Masiva de Ingredientes, el archivo Excel, debe estar estructurado de 13 columnas, la cuales tendrán las siguientes especificaciones:</strong></font><br><br>

  1. Código de Ingrediente (Ejem. 0001).<br>
  2. Nombre de Ingrediente.<br>
  3. Código de Medida. (Deberá ingresar el Nº de Unidad de Medida a la que corresponde).<br>
  4. Precio Compra. (Numeros con 2 decimales).<br>
  5. Precio Venta. (Numeros con 2 decimales).<br>
  6. Cantidad. (Debe de ser con 2 decimales).<br>
  7. Stock Minimo. (Debe de ser con 2 decimales).<br>
  8. Stock Máximo. (Debe de ser con 2 decimales).<br>
  9. <?php echo $impuesto == '' ? "Impuesto" : $imp[0]['nomimpuesto']; ?> de Producto. (Ejem. SI o NO).<br>
  10. Descuento de Producto. (Numeros con 2 decimales).<br>
  11. Lote de Producto (En caso de no tener colocar Cero (0)).<br>
  12. Fecha de Expiración. (Formato: 0000-00-00).<br>
  13. Proveedor. (Debe de verificar a que codigo pertenece el Proveedor existente).<br><br>

  <font color="red"><strong> NOTA:</strong></font><br>
  a) El Archivo no debe de tener cabecera, solo deben estar los registros a grabar.<br>
  b) Se debe de guardar como archivo .CSV  (delimitado por comas)(*.csv).<br>
  c) Todos los datos deberán escribirse en mayúscula para mejor orden y visibilidad en los reportes.<br>
  d) Deben de tener en cuenta que la carga masiva de Productos, deben de ser cargados como se explica, para evitar problemas de datos del ingrediente dentro del Sistema.<br><br>
    </div>
</div>                                 
<?php 
  }
######################## MUESTRA DIV INGREDIENTE ########################
?>

<?php
######################## MOSTRAR INGREDIENTES EN VENTANA MODAL ########################
if (isset($_GET['BuscaIngredienteModal']) && isset($_GET['codingrediente'])) { 

$reg = $new->IngredientesPorId(); 

$monedap = new Login();
$cambio = $monedap->MonedaProductoId(); 
?>
  
  <table class="table-responsive" border="0" align="center">
  <tr>
    <td><strong>Código:</strong> <?php echo $reg[0]['codingrediente']; ?></td>
  </tr>
  <tr>
    <td><strong>Ingrediente:</strong> <?php echo $reg[0]['nomingrediente']; ?></td>
  </tr> 
  <tr>
  <td><strong>Proveedor: </strong><?php echo $reg[0]['codproveedor'] == '0' ? "*********" : $reg[0]['cuitproveedor'].": ".$reg[0]['nomproveedor']; ?></td>
  </tr> 
  <tr>
    <td><strong>Unidad Medida:</strong> <?php echo $reg[0]['nommedida']; ?></td>
  </tr>
  <tr>
    <td><strong>Precio de Compra: </strong> <?php echo $simbolo.number_format($reg[0]['preciocompra'], 2, '.', ','); ?></td>
  </tr> 
  <tr>
    <td><strong>Precio de Venta: </strong> <?php echo $simbolo.number_format($reg[0]['precioventa'], 2, '.', ','); ?></td>
  </tr>
<?php if($cambio[0]['codmoneda2']!=""){ ?>
  <tr>
    <td><strong><?php echo $cambio[0]['codmoneda2'] == '' ? "*****" : "Precio ".$cambio[0]['siglas']; ?>: </strong> 
      <?php echo $cambio[0]['codmoneda2'] == '' ? "*****" : "<strong>".$cambio[0]['simbolo']."</strong> ".number_format($reg[0]['precioventa']/$cambio[0]['montocambio'], 2, '.', ','); ?></td>
  </tr> 
<?php } ?>
  <tr>
    <td><strong>Existencia: </strong> <?php echo $reg[0]['cantingrediente']; ?></td>
  </tr> 
  <tr>
    <td><strong>Stock Minimo: </strong> <?php echo $reg[0]['stockminimo'] == '0' ? "*********" : $reg[0]['stockminimo']; ?></td>
  </tr> 
  <tr>
    <td><strong>Stock Máximo: </strong> <?php echo $reg[0]['stockmaximo'] == '0' ? "*********" : $reg[0]['stockmaximo']; ?></td>
  </tr> 
  <tr>
    <td><strong><?php echo $impuesto == '' ? "Impuesto" : $imp[0]['nomimpuesto']; ?>: </strong> <?php echo $reg[0]['ivaingrediente'] == 'SI' ? $imp[0]["valorimpuesto"]."%" : "(E)"; ?></td>
  </tr> 
  <tr>
    <td><strong>Descuento: </strong> <?php echo $reg[0]['descingrediente']."%"; ?></td>
  </tr>  
  <tr>
    <td><strong>Nº de Lote: </strong> <?php echo $reg[0]['lote'] == '0' ? "*********" : $reg[0]['lote']; ?></td>
  </tr>
  <tr>
    <td><strong>Fecha de Expiración: </strong> <?php echo $reg[0]['fechaexpiracion'] == '0000-00-00' ? "*********" : date("d-m-Y",strtotime($reg[0]['fechaexpiracion'])); ?></td>
  </tr>
  <tr>
    <td><strong>Status: </strong> <?php echo $status = ( $reg[0]['cantingrediente'] != 0.00 ? "<span class='badge badge-pill badge-success'><i class='fa fa-check'></i> ACTIVO</span>" : "<span class='badge badge-pill badge-warning'><i class='fa fa-times'></i> INACTIVO</span>"); ?></td>
  </tr>
</table>
<?php 
} 
######################## MOSTRAR INGREDIENTES EN VENTANA MODAL ########################
?>


<?php 
######################## BUSQUEDA DE KARDEX POR INGREDIENTES ########################
if (isset($_GET['BuscaKardexIngrediente']) && isset($_GET['codingrediente'])) { 

$codingrediente = limpiar($_GET['codingrediente']); 

  if($codingrediente=="") {

  echo "<div class='alert alert-danger'>";
  echo "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>";
  echo "<center><span class='fa fa-info-circle'></span> POR FAVOR REALICE LA BÚSQUEDA DEL INGREDIENTE CORRECTAMENTE</center>";
  echo "</div>";
  exit;
   
   } else {
  
$kardex = new Login();
$kardex = $kardex->BuscarKardexIngrediente();  
?>
 
 <!-- Row -->
 <div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header bg-warning">
        <h4 class="card-title text-white"><i class="fa fa-tasks"></i> Movimientos del Ingrediente <?php echo $kardex[0]['codingrediente'].": ".$kardex[0]['nomingrediente']; ?></h4>
      </div>

      <div class="form-body">
        <div class="card-body">

          <div class="row">
            <div class="col-md-7">
              <div class="btn-group m-b-20">
              <a class="btn waves-effect waves-light btn-light" href="reportepdf?codingrediente=<?php echo $codingrediente; ?>&tipo=<?php echo encrypt("KARDEXINGREDIENTES") ?>" target="_blank" rel="noopener noreferrer"  data-toggle="tooltip" data-placement="bottom" title="Exportar Pdf"><span class="fa fa-file-pdf-o text-dark"></span> Pdf</a>

              <a class="btn waves-effect waves-light btn-light" href="reporteexcel?codingrediente=<?php echo $codingrediente; ?>&documento=<?php echo encrypt("EXCEL") ?>&tipo=<?php echo encrypt("KARDEXINGREDIENTES") ?>" data-toggle="tooltip" data-placement="bottom" title="Exportar Excel"><span class="fa fa-file-excel-o text-dark"></span> Excel</a>

              <a class="btn waves-effect waves-light btn-light" href="reporteexcel?codingrediente=<?php echo $codingrediente; ?>&documento=<?php echo encrypt("WORD") ?>&tipo=<?php echo encrypt("KARDEXINGREDIENTES") ?>" data-toggle="tooltip" data-placement="bottom" title="Exportar Word"><span class="fa fa-file-word-o text-dark"></span> Word</a>

              </div>
            </div>
          </div>

          <div id="div3"><table id="datatable-scroller" class="table table-hover table-striped table-bordered nowrap" cellspacing="0" width="100%">
                              <thead>
                              <tr class="text-center">
                                  <th>Nº</th>
                                  <th>Movimiento</th>
                                  <th>Entradas</th>
                                  <th>Salidas</th>
                                  <th>Devolución</th>
                                  <th>Precio Costo</th>
                                  <th>Costo Movimiento</th>
                                  <th>Stock Actual</th>
                                  <th>Documento</th>
                                  <th>Fecha de Kardex</th>
                              </tr>
                              </thead>
                              <tbody>
<?php
$TotalEntradas=0;
$TotalSalidas=0;
$TotalDevolucion=0;
$a=1;
for($i=0;$i<sizeof($kardex);$i++){ 
$TotalEntradas+=$kardex[$i]['entradas'];
$TotalSalidas+=$kardex[$i]['salidas'];
$TotalDevolucion+=$kardex[$i]['devolucion'];
?>
                              <tr class="text-center">
                                  <td><?php echo $a++; ?></td>
                                  <td><?php echo $kardex[$i]['movimiento']; ?></td>
                                  <td><?php echo $kardex[$i]['entradas']; ?></td>
                                  <td><?php echo $kardex[$i]['salidas']; ?></td>
                                  <td><?php echo $kardex[$i]['devolucion']; ?></td>
                                  <td><?php echo $simbolo.number_format($kardex[$i]['precio'], 2, '.', ','); ?></td>
                          <?php if($kardex[$i]["movimiento"]=="ENTRADAS"){ ?>
        <td><?php echo number_format($kardex[$i]['precio']*$kardex[$i]['entradas'], 2, '.', ','); ?></td>
                          <?php } elseif($kardex[$i]["movimiento"]=="SALIDAS"){ ?>
        <td><?php echo number_format($kardex[$i]['precio']*$kardex[$i]['salidas'], 2, '.', ','); ?></td>
                          <?php } else { ?>
        <td><?php echo number_format($kardex[$i]['precio']*$kardex[$i]['devolucion'], 2, '.', ','); ?></td>
                          <?php } ?>
                                  <td><?php echo $kardex[$i]['stockactual']; ?></td>
                                  <td><?php echo $kardex[$i]['documento']." ".$num = ($kardex[$i]['documento'] == 'VENTA' || $kardex[$i]['documento'] == 'DEVOLUCION' ? $kardex[$i]['codproceso'] : ""); ?></td>
                                  <td><?php echo date("d-m-Y",strtotime($kardex[$i]['fechakardex'])); ?></td>
                              </tr>
                        <?php  }  ?>
                              </tbody>
                          </table>
                        
          <strong>Detalles de Ingrediente</strong><br>
          <strong>Código:</strong> <?php echo $kardex[0]['codingrediente']; ?><br>
          <strong>Descripción:</strong> <?php echo $kardex[0]['nomingrediente']; ?><br>
          <strong>Categoria:</strong> <?php echo $kardex[0]['nommedida']; ?><br>
          <strong>Total Entradas:</strong> <?php echo $TotalEntradas; ?><br>
          <strong>Total Salidas:</strong> <?php echo $TotalSalidas; ?><br>
          <strong>Total Devolución:</strong> <?php echo $TotalDevolucion; ?><br>
          <strong>Existencia:</strong> <?php echo $kardex[0]['cantingrediente']; ?><br>
          <strong>Precio Compra:</strong> <?php echo $simbolo." ".$kardex[0]['preciocompra']; ?><br>
          <strong>Precio Venta:</strong> <?php echo $simbolo." ".$kardex[0]['precioventa']; ?>
            </div>

        </div>
      </div>
    </div>
  </div>
</div>
<!-- End Row -->
    <?php
    } 
  }
######################## BUSQUEDA DE KARDEX POR INGREDIENTES ########################
?>

<?php 
######################## BUSQUEDA DE INGREDIENTES VENDIDOS ########################
if (isset($_GET['BuscaIngredientesVendidos']) && isset($_GET['desde']) && isset($_GET['hasta'])) { 

$desde = limpiar($_GET['desde']); 
$hasta = limpiar($_GET['hasta']);
   
 if($desde=="") {

  echo "<div class='alert alert-danger'>";
  echo "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>";
  echo "<center><span class='fa fa-info-circle'></span> POR FAVOR INGRESE FECHA DE INICIO PARA TU BÚSQUEDA</center>";
  echo "</div>"; 
  exit;

} else if($hasta=="") {

  echo "<div class='alert alert-danger'>";
  echo "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>";
  echo "<center><span class='fa fa-info-circle'></span> POR FAVOR INGRESE FECHA FINAL PARA TU BÚSQUEDA</center>";
  echo "</div>"; 
  exit;

} elseif (strtotime($desde) > strtotime($hasta)) {

  echo "<div class='alert alert-danger'>";
  echo "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>";
  echo "<center><span class='fa fa-info-circle'></span> LA FECHA DE INICIO NO PUEDE SER MAYOR QUE LA FECHA DE FIN</center>";
  echo "</div>"; 
  exit;

} else {
  
$vendidos = new Login();
$reg = $vendidos->BuscarIngredientesVendidos();  
 ?>
 
 <!-- Row -->
 <div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header bg-warning">
        <h4 class="card-title text-white"><i class="fa fa-tasks"></i> Ingredientes Vendidos por Fecha Desde <?php echo date("d-m-Y", strtotime($desde)); ?> Hasta <?php echo date("d-m-Y", strtotime($hasta)); ?></h4>
      </div>

      <div class="form-body">
        <div class="card-body">

          <div class="row">
            <div class="col-md-7">
              <div class="btn-group m-b-20">
              <a class="btn waves-effect waves-light btn-light" href="reportepdf?desde=<?php echo $desde; ?>&hasta=<?php echo $hasta; ?>&tipo=<?php echo encrypt("INGREDIENTESVENDIDOS") ?>" target="_blank" rel="noopener noreferrer"  data-toggle="tooltip" data-placement="bottom" title="Exportar Pdf"><span class="fa fa-file-pdf-o text-dark"></span> Pdf</a>

              <a class="btn waves-effect waves-light btn-light" href="reporteexcel?desde=<?php echo $desde; ?>&hasta=<?php echo $hasta; ?>&documento=<?php echo encrypt("EXCEL") ?>&tipo=<?php echo encrypt("INGREDIENTESVENDIDOS") ?>" data-toggle="tooltip" data-placement="bottom" title="Exportar Excel"><span class="fa fa-file-excel-o text-dark"></span> Excel</a>

              <a class="btn waves-effect waves-light btn-light" href="reporteexcel?desde=<?php echo $desde; ?>&hasta=<?php echo $hasta; ?>&documento=<?php echo encrypt("WORD") ?>&tipo=<?php echo encrypt("INGREDIENTESVENDIDOS") ?>" data-toggle="tooltip" data-placement="bottom" title="Exportar Word"><span class="fa fa-file-word-o text-dark"></span> Word</a>
              </div>
            </div>
          </div>

          <div id="div2"><table id="datatable-scroller" class="table table-hover table-striped table-bordered nowrap" cellspacing="0" width="100%">
                              <thead>
                                <tr class="text-center">
                                  <th>Nº</th>
                                  <th>Código</th>
                                  <th>Descripción de Ingrediente</th>
                                  <th>Unidad Medida</th>
                                  <th>Desc</th>
                                  <th>Precio de Venta</th>
                                  <th>Existencia</th>
                                  <th>Vendido</th>
                                  <th>Monto Total</th>
                                </tr>
                              </thead>
                              <tbody>
<?php
$precioTotal=0;
$existeTotal=0;
$vendidosTotal=0;
$pagoTotal=0;
$a=1;
for($i=0;$i<sizeof($reg);$i++){
$precioTotal+=$reg[$i]['precioventa'];
$existeTotal+=$reg[$i]['cantingrediente'];
$vendidosTotal+=$reg[$i]['cantidad']; 
$pagoTotal+=$reg[$i]['precioventa']*$reg[$i]['cantingrediente']-$reg[$i]['descingrediente']/100; 
?>
                                <tr class="text-center">
                      <td><?php echo $a++; ?></div></td>
                      <td><?php echo $reg[$i]['codingrediente']; ?></td>
                      <td><?php echo $reg[$i]['nomingrediente']; ?></td>
                      <td><?php echo $reg[$i]['nommedida']; ?></td>
                      <td><?php echo $reg[$i]['descingrediente']; ?>%</td>
                      <td><?php echo $simbolo.number_format($reg[$i]["precioventa"], 2, '.', ','); ?></td>
                      <td><?php echo $reg[$i]['cantingrediente']; ?></td>
                      <td><?php echo number_format($reg[$i]['cantidad'], 2, '.', ','); ?></td>
                      <td><?php echo $simbolo.number_format($reg[$i]['precioventa']*$reg[$i]['cantidad'], 2, '.', ','); ?></td>
                                </tr>
                        <?php  }  ?>
                      <tr align="center">
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td><strong><?php echo $simbolo.number_format($precioTotal, 2, '.', ','); ?></strong></td>
                        <td><strong><?php echo number_format($existeTotal, 2, '.', ','); ?></strong></td>
                        <td><strong><?php echo number_format($vendidosTotal, 2, '.', ','); ?></strong></td>
                        <td><strong><?php echo $simbolo.number_format($pagoTotal, 2, '.', ','); ?></strong></td>
                      </tr>
                              </tbody>
                          </table>
                      </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- End Row -->
    <?php
    } 
  }
######################## BUSQUEDA DE INGREDIENTES VENDIDOS ########################
?>

<?php 
######################## MUESTRA INGREDIENTES AGREGADOS A PRODUCTOS ########################
if (isset($_GET['BuscaIngredienteNuevo']) && isset($_GET['codproducto'])) { 
?>

<table id="default_order" class="table table-striped table-bordered border display">
              <thead>
              <tr role="row">
              <th colspan="6"><center><h4>Ingredientes Agregados</h4></center></th>
              </tr>
                                <tr>
                                <th>Nº</th>
                                <th>Ingrediente</th>
                                <th>Cant. Ración</th>
                                <th>Existencia</th>
                                <th>Precio Venta</th>
                                <th class="text-center">Eliminar</th>
                                </tr>
                            </thead>
                                <tbody>
<?php 
$tru = new Login();
$a=1;
$busq = $tru->VerDetallesIngredientes();

if($busq==""){

echo "";      

} else {

for($i=0;$i<sizeof($busq);$i++){
?>
                <tr>
<td><?php echo $a++; ?></td>
<td><input type="hidden" name="codingrediente[]" id="codingrediente" value="<?php echo $busq[$i]["codingrediente"]; ?>"><?php echo $busq[$i]["nomingrediente"]; ?></td>

<td><input type="text" class="form-control" name="cantidad[]" id="cantidad" onKeyUp="this.value=this.value.toUpperCase();" autocomplete="off" placeholder="Cantidad Porción" value="<?php echo $busq[$i]["cantracion"]; ?>" title="Ingrese Cantidad" required="" aria-required="true"></td>

<td><?php echo $busq[$i]["cantingrediente"]." ".$busq[$i]["nommedida"]; ?></td>
<td><?php echo number_format($busq[$i]["precioventa"], 2, '.', ','); ?></td>
<td><button type="button" class="btn btn-dark btn-rounded" onClick="EliminaDetalleIngredienteNuevo('<?php echo encrypt($busq[$i]['codproducto']) ?>','<?php echo encrypt($busq[$i]['codingrediente']) ?>','<?php echo encrypt("ELIMINADETALLEINGREDIENTE") ?>')" title="Eliminar" ><i class="fa fa-trash-o"></i></button></td>
                </tr><?php } } ?>
              </tbody>
        </table>

<?php 
}
######################## MUESTRA INGREDIENTES AGREGADOS A PRODUCTOS ########################
?>


<?php 
######################## MUESTRA INGREDIENTES AGREGADOS A PRODUCTOS ########################
if (isset($_GET['BuscaIngredienteAgregar']) && isset($_GET['codproducto'])) { 
?>

<table id="default_order" class="table table-striped table-bordered border display">
              <thead>
              <tr role="row">
              <th colspan="6"><center><h4>Ingredientes Agregados</h4></center></th>
              </tr>
                                <tr>
                                <th>Nº</th>
                                <th>Ingrediente</th>
                                <th>Cant. Ración</th>
                                <th>Existencia</th>
                                <th>Precio Venta</th>
                                <th class="text-center">Eliminar</th>
                                </tr>
                            </thead>
                                <tbody>
<?php 
$tru = new Login();
$a=1;
$busq = $tru->VerDetallesIngredientes();

if($busq==""){

echo "";      

} else {

for($i=0;$i<sizeof($busq);$i++){
?>
                <tr>
<td><?php echo $a++; ?></td>
<td><?php echo $busq[$i]["nomingrediente"]; ?></td>
<td><?php echo $busq[$i]["cantracion"]; ?></td>
<td><?php echo $busq[$i]["cantingrediente"]." ".$busq[$i]["nommedida"]; ?></td>
<td><?php echo number_format($busq[$i]["precioventa"], 2, '.', ','); ?></td>
<td><button type="button" class="btn btn-dark btn-rounded" onClick="EliminaDetalleIngredienteAgrega('<?php echo encrypt($busq[$i]['codproducto']) ?>','<?php echo encrypt($busq[$i]['codingrediente']) ?>','<?php echo encrypt("ELIMINADETALLEINGREDIENTE") ?>')" title="Eliminar" ><i class="fa fa-trash-o"></i></button></td>
                </tr><?php } } ?>
              </tbody>
        </table>

<?php 
}
######################## MUESTRA INGREDIENTES AGREGADOS A PRODUCTOS ########################
?>




















<?php 
######################## MUESTRA DIV PRODUCTO ########################
if (isset($_GET['BuscaDivProducto'])) {
  
  ?>
<div class="row">
      <div class="col-md-12">
<font color="red"><strong> Para poder realizar la Carga Masiva de Productos, el archivo Excel, debe estar estructurado de 16 columnas, la cuales tendrán las siguientes especificaciones:</strong></font><br><br>

  1. Código de Producto (Ejem. 0001).<br>
  2. Nombre de Producto.<br>
  3. Código de Categoria. (Deberá ingresar el Nº de Categoria a la que corresponde o colocar Cero (0)).<br>
  4. Precio Compra. (Numeros con 2 decimales).<br>
  5. Precio Venta. (Numeros con 2 decimales).<br>
  6. Existencia. (Debe de ser solo enteros).<br>
  7. Stock Minimo. (Debe de ser solo enteros).<br>
  8. Stock Máximo. (Debe de ser solo enteros).<br>
  9. <?php echo $impuesto == '' ? "Impuesto" : $imp[0]['nomimpuesto']; ?> de Producto. (Ejem. SI o NO).<br>
  10. Descuento de Producto. (Numeros con 2 decimales).<br>
  11. Código de Barra. (En caso de no tener colocar Cero (0)).<br>
  12. Lote de Producto (En caso de no tener colocar Cero (0)).<br>
  13. Fecha de Elaboración. (Formato: 0000-00-00).<br>
  14. Fecha de Expiración. (Formato: 0000-00-00).<br>
  15. Proveedor. (Debe de verificar a que codigo pertenece el Proveedor existente).<br>
  16. Favorito.<br><br>

  <font color="red"><strong> NOTA:</strong></font><br>
  a) El Archivo no debe de tener cabecera, solo deben estar los registros a grabar.<br>
  b) Se debe de guardar como archivo .CSV  (delimitado por comas)(*.csv).<br>
  c) Todos los datos deberán escribirse en mayúscula para mejor orden y visibilidad en los reportes.<br>
  d) Deben de tener en cuenta que la carga masiva de Productos, deben de ser cargados como se explica, para evitar problemas de datos del productos dentro del Sistema.<br><br>
    </div>
</div>                                 
<?php 
  }
######################## MUESTRA DIV PRODUCTO ########################
?>

<?php
######################## MOSTRAR PRODUCTOS EN VENTANA MODAL ########################
if (isset($_GET['BuscaProductoModal']) && isset($_GET['codproducto'])) { 

$reg = $new->ProductosPorId(); 

$monedap = new Login();
$cambio = $monedap->MonedaProductoId(); 
?>
  
  <table class="table-responsive" border="0" align="center">
  <tr>
    <td><strong>Código:</strong> <?php echo $reg[0]['codproducto']; ?></td>
  </tr>
  <tr>
    <td><strong>Producto:</strong> <?php echo $reg[0]['producto']; ?></td>
  </tr> 
  <tr>
  <td><strong>Proveedor: </strong><?php echo $reg[0]['codproveedor'] == '0' ? "*********" : $reg[0]['cuitproveedor'].": ".$reg[0]['nomproveedor']; ?></td>
  </tr> 
  <tr>
    <td><strong>Categoria:</strong> <?php echo $reg[0]['nomcategoria']; ?></td>
  </tr>
  <tr>
    <td><strong>Precio de Compra: </strong> <?php echo $simbolo.number_format($reg[0]['preciocompra'], 2, '.', ','); ?></td>
  </tr> 
  <tr>
    <td><strong>Precio de Venta: </strong> <?php echo $simbolo.number_format($reg[0]['precioventa'], 2, '.', ','); ?></td>
  </tr>
<?php if($cambio[0]['codmoneda2']!=""){ ?>
  <tr>
    <td><strong><?php echo $cambio[0]['codmoneda2'] == '' ? "*****" : "Precio ".$cambio[0]['siglas']; ?>: </strong> 
      <?php echo $cambio[0]['codmoneda2'] == '' ? "*****" : "<strong>".$cambio[0]['simbolo']."</strong> ".number_format($reg[0]['precioventa']/$cambio[0]['montocambio'], 2, '.', ','); ?></td>
  </tr> 
<?php } ?>
  <tr>
    <td><strong>Existencia: </strong> <?php echo $reg[0]['existencia']; ?></td>
  </tr> 
  <tr>
    <td><strong>Stock Minimo: </strong> <?php echo $reg[0]['stockminimo'] == '0' ? "*********" : $reg[0]['stockminimo']; ?></td>
  </tr> 
  <tr>
    <td><strong>Stock Máximo: </strong> <?php echo $reg[0]['stockmaximo'] == '0' ? "*********" : $reg[0]['stockmaximo']; ?></td>
  </tr> 
  <tr>
    <td><strong><?php echo $impuesto == '' ? "Impuesto" : $imp[0]['nomimpuesto']; ?>: </strong> <?php echo $reg[0]['ivaproducto'] == 'SI' ? $imp[0]["valorimpuesto"]."%" : "(E)"; ?></td>
  </tr> 
  <tr>
    <td><strong>Descuento: </strong> <?php echo $reg[0]['descproducto']."%"; ?></td>
  </tr> 
  <tr>
  <td><strong>Código de Barra: </strong> <?php echo $reg[0]['codigobarra'] == '0' ? "*********" : $reg[0]['codigobarra']; ?></td>
  </tr> 
  <tr>
    <td><strong>Nº de Lote: </strong> <?php echo $reg[0]['lote'] == '0' ? "*********" : $reg[0]['lote']; ?></td>
  </tr> 
  <tr>
    <td><strong>Fecha de Elaboración: </strong> <?php echo $reg[0]['fechaelaboracion'] == '0000-00-00' ? "*********" : date("d-m-Y",strtotime($reg[0]['fechaelaboracion'])); ?></td>
  </tr> 
  <tr>
    <td><strong>Fecha de Expiración: </strong> <?php echo $reg[0]['fechaexpiracion'] == '0000-00-00' ? "*********" : date("d-m-Y",strtotime($reg[0]['fechaexpiracion'])); ?></td>
  </tr>
  <tr>
    <td><strong>Status: </strong> <?php echo $status = ( $reg[0]['existencia'] != 0 ? "<span class='badge badge-pill badge-success'><i class='fa fa-check'></i> ACTIVO</span>" : "<span class='badge badge-pill badge-warning'><i class='fa fa-times'></i> INACTIVO</span>"); ?></td>
  </tr>
    <tr>
    <td><strong>Favorito: </strong> <?php echo $status = ( $reg[0]['favorito'] == 1 ? "<span class='badge badge-pill badge-success'><i class='fa fa-check'></i> SI</span>" : "<span class='badge badge-pill badge-warning'><i class='fa fa-times'></i> NO</span>"); ?></td>  
    </tr>
</table>

<?php 
$tru = new Login();
$a=1;
$busq = $tru->VerDetallesIngredientes(); 

if($busq==""){

    echo "";      
    
} else {

?>
<div id="div1"><table id="default_order" class="table table-striped table-bordered border display">
                                                <thead>
                                                    <tr>
        <th colspan="4" data-priority="1"><center>Ingredientes Agregados</center></th>
                                                    </tr>
                                                    <tr>
            <th>Nº</th>
            <th>Ingrediente</th>
            <th>Cant. Ración</th>
            <th>Existencia</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
<?php 
for($i=0;$i<sizeof($busq);$i++){
?>
                                                    <tr>
                <th><?php echo $a++; ?></th>
<td><?php echo $busq[$i]["nomingrediente"]; ?></td>
<td><?php echo $busq[$i]["cantracion"]." ".$busq[$i]["nommedida"]; ?></td>
<td><?php echo $busq[$i]["cantingrediente"]." ".$busq[$i]["nommedida"]; ?></td>
                                                    </tr> 
                              <?php } ?>     
                                                </tbody>
                                            </table>
                                        </div>
<?php  
    }
} 
######################## MOSTRAR PRODUCTOS EN VENTANA MODAL ########################
?>


<?php 
######################## BUSQUEDA DE KARDEX POR PRODUCTOS ########################
if (isset($_GET['BuscaKardexProducto']) && isset($_GET['codproducto'])) { 

$codproducto = limpiar($_GET['codproducto']); 

  if($codproducto=="") {

  echo "<div class='alert alert-danger'>";
  echo "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>";
  echo "<center><span class='fa fa-info-circle'></span> POR FAVOR REALICE LA BÚSQUEDA DEL PRODUCTO CORRECTAMENTE</center>";
  echo "</div>";
  exit;
   
   } else {
  
$kardex = new Login();
$kardex = $kardex->BuscarKardexProducto();  
?>
 
 <!-- Row -->
 <div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header bg-warning">
        <h4 class="card-title text-white"><i class="fa fa-tasks"></i> Movimientos del Producto <?php echo $kardex[0]['codproducto'].": ".$kardex[0]['producto']; ?></h4>
      </div>

      <div class="form-body">
        <div class="card-body">

          <div class="row">
            <div class="col-md-7">
              <div class="btn-group m-b-20">
              <a class="btn waves-effect waves-light btn-light" href="reportepdf?codproducto=<?php echo $codproducto; ?>&tipo=<?php echo encrypt("KARDEXPRODUCTOS") ?>" target="_blank" rel="noopener noreferrer"  data-toggle="tooltip" data-placement="bottom" title="Exportar Pdf"><span class="fa fa-file-pdf-o text-dark"></span> Pdf</a>

              <a class="btn waves-effect waves-light btn-light" href="reporteexcel?codproducto=<?php echo $codproducto; ?>&documento=<?php echo encrypt("EXCEL") ?>&tipo=<?php echo encrypt("KARDEXPRODUCTOS") ?>" data-toggle="tooltip" data-placement="bottom" title="Exportar Excel"><span class="fa fa-file-excel-o text-dark"></span> Excel</a>

              <a class="btn waves-effect waves-light btn-light" href="reporteexcel?codproducto=<?php echo $codproducto; ?>&documento=<?php echo encrypt("WORD") ?>&tipo=<?php echo encrypt("KARDEXPRODUCTOS") ?>" data-toggle="tooltip" data-placement="bottom" title="Exportar Word"><span class="fa fa-file-word-o text-dark"></span> Word</a>

              </div>
            </div>
          </div>

          <div id="div3"><table id="datatable-scroller" class="table table-hover table-striped table-bordered nowrap" cellspacing="0" width="100%">
                              <thead>
                              <tr class="text-center">
                                  <th>Nº</th>
                                  <th>Movimiento</th>
                                  <th>Entradas</th>
                                  <th>Salidas</th>
                                  <th>Devolución</th>
                                  <th>Precio Costo</th>
                                  <th>Costo Movimiento</th>
                                  <th>Stock Actual</th>
                                  <th>Documento</th>
                                  <th>Fecha de Kardex</th>
                              </tr>
                              </thead>
                              <tbody>
<?php
$TotalEntradas=0;
$TotalSalidas=0;
$TotalDevolucion=0;
$a=1;
for($i=0;$i<sizeof($kardex);$i++){ 
$TotalEntradas+=$kardex[$i]['entradas'];
$TotalSalidas+=$kardex[$i]['salidas'];
$TotalDevolucion+=$kardex[$i]['devolucion'];
?>
                              <tr class="text-center">
                                  <td><?php echo $a++; ?></td>
                                  <td><?php echo $kardex[$i]['movimiento']; ?></td>
                                  <td><?php echo $kardex[$i]['entradas']; ?></td>
                                  <td><?php echo $kardex[$i]['salidas']; ?></td>
                                  <td><?php echo $kardex[$i]['devolucion']; ?></td>
                                  <td><?php echo $simbolo.number_format($kardex[$i]['precio'], 2, '.', ','); ?></td>
                          <?php if($kardex[$i]["movimiento"]=="ENTRADAS"){ ?>
        <td><?php echo number_format($kardex[$i]['precio']*$kardex[$i]['entradas'], 2, '.', ','); ?></td>
                          <?php } elseif($kardex[$i]["movimiento"]=="SALIDAS"){ ?>
        <td><?php echo number_format($kardex[$i]['precio']*$kardex[$i]['salidas'], 2, '.', ','); ?></td>
                          <?php } else { ?>
        <td><?php echo number_format($kardex[$i]['precio']*$kardex[$i]['devolucion'], 2, '.', ','); ?></td>
                          <?php } ?>
                                  <td><?php echo $kardex[$i]['stockactual']; ?></td>
                                  <td><?php echo $kardex[$i]['documento']." ".$num = ($kardex[$i]['documento'] == 'VENTA' || $kardex[$i]['documento'] == 'DEVOLUCION' ? $kardex[$i]['codproceso'] : ""); ?></td>
                                  <td><?php echo date("d-m-Y",strtotime($kardex[$i]['fechakardex'])); ?></td>
                              </tr>
                        <?php  }  ?>
                              </tbody>
                          </table>
                        
          <strong>Detalles de Producto</strong><br>
          <strong>Código:</strong> <?php echo $kardex[0]['codproducto']; ?><br>
          <strong>Descripción:</strong> <?php echo $kardex[0]['producto']; ?><br>
          <strong>Categoria:</strong> <?php echo $kardex[0]['nomcategoria']; ?><br>
          <strong>Total Entradas:</strong> <?php echo $TotalEntradas; ?><br>
          <strong>Total Salidas:</strong> <?php echo $TotalSalidas; ?><br>
          <strong>Total Devolución:</strong> <?php echo $TotalDevolucion; ?><br>
          <strong>Existencia:</strong> <?php echo $kardex[0]['existencia']; ?><br>
          <strong>Precio Compra:</strong> <?php echo $simbolo." ".$kardex[0]['preciocompra']; ?><br>
          <strong>Precio Venta:</strong> <?php echo $simbolo." ".$kardex[0]['precioventa']; ?>
            </div>

        </div>
      </div>
    </div>
  </div>
</div>
<!-- End Row -->
    <?php
    } 
  }
######################## BUSQUEDA DE KARDEX POR PRODUCTOS ########################
?>


<?php 
######################## BUSQUEDA DE PRODUCTOS VENDIDOS ########################
if (isset($_GET['BuscaProductosVendidos']) && isset($_GET['desde']) && isset($_GET['hasta'])) { 

$desde = limpiar($_GET['desde']); 
$hasta = limpiar($_GET['hasta']);
   
 if($desde=="") {

  echo "<div class='alert alert-danger'>";
  echo "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>";
  echo "<center><span class='fa fa-info-circle'></span> POR FAVOR INGRESE FECHA DE INICIO PARA TU BÚSQUEDA</center>";
  echo "</div>"; 
  exit;

} else if($hasta=="") {

  echo "<div class='alert alert-danger'>";
  echo "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>";
  echo "<center><span class='fa fa-info-circle'></span> POR FAVOR INGRESE FECHA FINAL PARA TU BÚSQUEDA</center>";
  echo "</div>"; 
  exit;

} elseif (strtotime($desde) > strtotime($hasta)) {

  echo "<div class='alert alert-danger'>";
  echo "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>";
  echo "<center><span class='fa fa-info-circle'></span> LA FECHA DE INICIO NO PUEDE SER MAYOR QUE LA FECHA DE FIN</center>";
  echo "</div>"; 
  exit;

} else {
  
$vendidos = new Login();
$reg = $vendidos->BuscarProductosVendidos();  
 ?>
 
 <!-- Row -->
 <div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header bg-warning">
        <h4 class="card-title text-white"><i class="fa fa-tasks"></i> Productos Vendidos por Fecha Desde <?php echo date("d-m-Y", strtotime($desde)); ?> Hasta <?php echo date("d-m-Y", strtotime($hasta)); ?></h4>
      </div>

      <div class="form-body">
        <div class="card-body">

          <div class="row">
            <div class="col-md-7">
              <div class="btn-group m-b-20">
              <a class="btn waves-effect waves-light btn-light" href="reportepdf?desde=<?php echo $desde; ?>&hasta=<?php echo $hasta; ?>&tipo=<?php echo encrypt("PRODUCTOSVENDIDOS") ?>" target="_blank" rel="noopener noreferrer"  data-toggle="tooltip" data-placement="bottom" title="Exportar Pdf"><span class="fa fa-file-pdf-o text-dark"></span> Pdf</a>

              <a class="btn waves-effect waves-light btn-light" href="reporteexcel?desde=<?php echo $desde; ?>&hasta=<?php echo $hasta; ?>&documento=<?php echo encrypt("EXCEL") ?>&tipo=<?php echo encrypt("PRODUCTOSVENDIDOS") ?>" data-toggle="tooltip" data-placement="bottom" title="Exportar Excel"><span class="fa fa-file-excel-o text-dark"></span> Excel</a>

              <a class="btn waves-effect waves-light btn-light" href="reporteexcel?desde=<?php echo $desde; ?>&hasta=<?php echo $hasta; ?>&documento=<?php echo encrypt("WORD") ?>&tipo=<?php echo encrypt("PRODUCTOSVENDIDOS") ?>" data-toggle="tooltip" data-placement="bottom" title="Exportar Word"><span class="fa fa-file-word-o text-dark"></span> Word</a>
              </div>
            </div>
          </div>

          <div id="div2"><table id="datatable-scroller" class="table table-hover table-striped table-bordered nowrap" cellspacing="0" width="100%">
                              <thead>
                                <tr class="text-center">
                                  <th>Nº</th>
                                  <th>Código</th>
                                  <th>Descripción de Producto</th>
                                  <th>Categoria</th>
                                  <th>Desc</th>
                                  <th>Precio de Venta</th>
                                  <th>Existencia</th>
                                  <th>Vendido</th>
                                  <th>Monto Total</th>
                                </tr>
                              </thead>
                              <tbody>
<?php
$precioTotal=0;
$existeTotal=0;
$vendidosTotal=0;
$pagoTotal=0;
$a=1;
for($i=0;$i<sizeof($reg);$i++){
$precioTotal+=$reg[$i]['precioventa'];
$existeTotal+=$reg[$i]['existencia'];
$vendidosTotal+=$reg[$i]['cantidad']; 
$pagoTotal+=$reg[$i]['precioventa']*$reg[$i]['cantidad']-$reg[$i]['descproducto']/100; 
?>
                                <tr class="text-center">
                      <td><?php echo $a++; ?></div></td>
                      <td><?php echo $reg[$i]['codproducto']; ?></td>
                      <td><?php echo $reg[$i]['producto']; ?></td>
                      <td><?php echo $reg[$i]['nomcategoria']; ?></td>
                      <td><?php echo $reg[$i]['descproducto']; ?>%</td>
                      <td><?php echo $simbolo.number_format($reg[$i]["precioventa"], 2, '.', ','); ?></td>
                      <td><?php echo number_format($reg[$i]['existencia'], 2, '.', ','); ?></td>
                      <td><?php echo number_format($reg[$i]['cantidad'], 2, '.', ','); ?></td>
                      <td><?php echo $simbolo.number_format($reg[$i]['precioventa']*$reg[$i]['cantidad'], 2, '.', ','); ?></td>
                                </tr>
                        <?php  }  ?>
                      <tr align="center">
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td><strong><?php echo $simbolo.number_format($precioTotal, 2, '.', ','); ?></strong></td>
                        <td><strong><?php echo number_format($existeTotal, 2, '.', ','); ?></strong></td>
                        <td><strong><?php echo number_format($vendidosTotal, 2, '.', ','); ?></strong></td>
                        <td><strong><?php echo $simbolo.number_format($pagoTotal, 2, '.', ','); ?></strong></td>
                      </tr>
                              </tbody>
                          </table>
                      </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- End Row -->
    <?php
    } 
  }
######################## BUSQUEDA DE PRODUCTOS VENDIDOS ########################
?>

<?php 
######################## BUSQUEDA DE PRODUCTOS POR MONEDA ########################
if (isset($_GET['BuscaProductosxMoneda']) && isset($_GET['codmoneda'])) { 

  $codmoneda = limpiar($_GET['codmoneda']);

  if($codmoneda=="") { 

  echo "<div class='alert alert-danger'>";
  echo "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>";
  echo "<center><span class='fa fa-info-circle'></span> POR FAVOR SELECCIONE TIPO DE MONEDA PARA TU BÚSQUEDA</center>";
  echo "</div>";
  exit;
   
   } else {

$cambio = new Login();
$cambio = $cambio->BuscarTiposCambios();
  
$reg = $new->ListarProductos();  
 ?>
 
 <!-- Row -->
 <div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header bg-warning">
        <h4 class="card-title text-white"><i class="fa fa-tasks"></i> Productos al Cambio de <?php echo $cambio[0]['moneda']." (".$cambio[0]['siglas'].")"; ?></h4>
      </div>

      <div class="form-body">
        <div class="card-body">

          <div class="row">
            <div class="col-md-7">
              <div class="btn-group m-b-20">
              <a class="btn waves-effect waves-light btn-light" href="reportepdf?codmoneda=<?php echo $codmoneda; ?>&tipo=<?php echo encrypt("PRODUCTOSXMONEDA") ?>" target="_blank" rel="noopener noreferrer"  data-toggle="tooltip" data-placement="bottom" title="Exportar Pdf"><span class="fa fa-file-pdf-o text-dark"></span> Pdf</a>

              <a class="btn waves-effect waves-light btn-light" href="reporteexcel?codmoneda=<?php echo $codmoneda; ?>&documento=<?php echo encrypt("EXCEL") ?>&tipo=<?php echo encrypt("PRODUCTOSXMONEDA") ?>" data-toggle="tooltip" data-placement="bottom" title="Exportar Excel"><span class="fa fa-file-excel-o text-dark"></span> Excel</a>

              <a class="btn waves-effect waves-light btn-light" href="reporteexcel?codmoneda=<?php echo $codmoneda; ?>&documento=<?php echo encrypt("WORD") ?>&tipo=<?php echo encrypt("PRODUCTOSXMONEDA") ?>" data-toggle="tooltip" data-placement="bottom" title="Exportar Word"><span class="fa fa-file-word-o text-dark"></span> Word</a>

              </div>
            </div>
          </div>

          <div id="div3"><table id="datatable-responsive" class="table table-hover table-nomargin table-bordered dataTable table-striped" cellspacing="0" width="100%">
                                                 <thead>
                                                 <tr role="row">
                                                    <th>N°</th>
                                                    <th>Img</th>
                                                    <th>Código</th>
                                                    <th>Nombre de Producto</th>
                                                    <th>Categoria</th>
                                                    <th>Precio Venta</th>
                                                    <th><?php echo $cambio[0]['siglas']; ?></th>
                                                    <th>Existencia</th>
      <th><?php echo $impuesto == '' ? "Impuesto" : $imp[0]['nomimpuesto']; ?></th>
                                                    <th>Descuento</th>
                                                 </tr>
                                                 </thead>
                                                 <tbody class="BusquedaRapida">

<?php 

if($reg==""){ 

} else {
 
$a=1;
for($i=0;$i<sizeof($reg);$i++){  
?>
                                               <tr role="row" class="odd">
                                               <td><?php echo $a++; ?></td>
<td><a href="#" data-placement="left" title="Ver Imagen" data-original-title="" data-href="#" data-toggle="modal" data-target=".bs-example-modal-sm" data-backdrop="static" data-keyboard="false" onClick="VerImagen('<?php echo encrypt($reg[$i]["codproducto"]); ?>','<?php echo encrypt($reg[$i]['codsucursal']) ?>')"><?php if (file_exists("fotos/productos/".$reg[$i]["codproducto"].".jpg")){
    echo "<img src='fotos/productos/".$reg[$i]["codproducto"].".jpg?' class='img-rounded' style='margin:0px;' width='50' height='45'>"; 
}else{
   echo "<img src='fotos/producto.png' class='img-rounded' style='margin:0px;' width='50' height='45'>";  
} 
     ?></a></td>
                                               <td><?php echo $reg[$i]['codproducto']; ?></td>
                                               <td><?php echo $reg[$i]['producto']; ?></td>
                                               <td><?php echo $reg[$i]['nomcategoria']; ?></td>
                                              <td><?php echo $simbolo.number_format($reg[$i]['precioventa'], 2, '.', ','); ?></td>
                                              <td><?php echo "<strong>".$cambio[0]['simbolo']."</strong> ".number_format($reg[$i]['precioventa']/$cambio[0]['montocambio'], 2, '.', ','); ?></td>
                                               <td><?php echo $reg[$i]['existencia']; ?></td>
                                               <td><?php echo $reg[$i]['ivaproducto'] == 'SI' ? $imp[0]["valorimpuesto"]."%" : "(E)"; ?></td>
                                               <td><?php echo $reg[$i]['descproducto']; ?></td>
                                               </tr>
                                                <?php } } ?>
                                            </tbody>
                                     </table>
                         </div>

        </div>
      </div>
    </div>
  </div>
</div>
<!-- End Row -->
    <?php
    } 
  }
######################## BUSQUEDA DE PRODUCTOS POR MONEDA ##########################
?>




















<?php
######################### MOSTRAR COMPRA PAGADA EN VENTANA MODAL ########################
if (isset($_GET['BuscaCompraPagadaModal']) && isset($_GET['codcompra'])) { 
 
$reg = $new->ComprasPorId();

  if($reg==""){
    
    echo "<div class='alert alert-danger'>";
    echo "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>";
    echo "<center><span class='fa fa-info-circle'></span> NO SE ENCONTRARON COMPRAS Y DETALLES ACTUALMENTE </center>";
    echo "</div>";    

} else {
?>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="pull-left">
                                        <address>
  <h3><b class="text-danger">RAZÓN SOCIAL</b></h3>
  <p class="text-muted m-l-5"><?php echo $con[0]['nomsucursal']; ?>,
  <br/> Nº <?php echo $con[0]['documsucursal'] == '0' ? "DOCUMENTO" : $con[0]['documento'] ?>: <?php echo $con[0]['cuit']; ?> - TLF: <?php echo $con[0]['tlfsucursal']; ?></p>

  <h3><b class="text-danger">Nº COMPRA <?php echo $reg[0]['codcompra']; ?></b></h3>
  <p class="text-muted m-l-5">STATUS: 
  <?php if($reg[0]['fechavencecredito']== '0000-00-00') { echo "<span class='badge badge-pill badge-info'><i class='fa fa-check'></i> ".$reg[0]["statuscompra"]."</span>"; } 
        elseif($reg[0]['fechavencecredito'] >= date("Y-m-d") && $reg[0]['fechapagado']== "0000-00-00") { echo "<span class='badge badge-pill badge-success'><i class='fa fa-exclamation-circle'></i> ".$reg[0]["statuscompra"]."</span>"; } 
        elseif($reg[0]['fechavencecredito'] < date("Y-m-d") && $reg[0]['fechapagado']== "0000-00-00") { echo "<span class='badge badge-pill badge-danger'><i class='fa fa-times'></i> VENCIDA</span>"; }
        elseif($reg[0]['fechavencecredito'] <= date("Y-m-d") && $reg[0]['fechapagado']!= "0000-00-00") { echo "<span class='badge badge-pill badge-info'><i class='fa fa-check'></i> ".$reg[0]["statuscompra"]."</span>"; } ?>

  <?php if($reg[0]['fechavencecredito']!= "0000-00-00") { ?>
  <br>DIAS VENCIDOS: 
  <?php if($reg[0]['fechavencecredito']== '0000-00-00') { echo "0"; } 
        elseif($reg[0]['fechavencecredito'] >= date("Y-m-d") && $reg[0]['fechapagado']== "0000-00-00") { echo "0"; } 
        elseif($reg[0]['fechavencecredito'] < date("Y-m-d") && $reg[0]['fechapagado']== "0000-00-00") { echo Dias_Transcurridos(date("Y-m-d"),$reg[0]['fechavencecredito']); }
        elseif($reg[0]['fechavencecredito'] < date("Y-m-d") && $reg[0]['fechapagado']!= "0000-00-00") { echo Dias_Transcurridos($reg[0]['fechapagado'],$reg[0]['fechavencecredito']); } ?>
  <?php } ?>
  
  <?php if($reg[0]['fechapagado']!= "0000-00-00") { ?>
  <br>FECHA PAGADA: <?php echo date("d-m-Y",strtotime($reg[0]['fechapagado'])); ?>
  <?php } ?>

  <br>FECHA DE EMISIÓN: <?php echo date("d-m-Y",strtotime($reg[0]['fechaemision'])); ?>
  <br/> FECHA DE RECEPCIÓN: <?php echo date("d-m-Y",strtotime($reg[0]['fecharecepcion'])); ?></p>
                                        </address>
                                    </div>
                                    <div class="pull-right text-right">
                                        <address>
  <h3><b class="text-danger">PROVEEDOR</b></h3>
  <p class="text-muted m-l-30"><?php echo $reg[0]['nomproveedor'] == '' ? "**********************" : $reg[0]['nomproveedor']; ?>,
  <br/>DIREC: <?php echo $reg[0]['direcproveedor'] == '' ? "*********" : $reg[0]['direcproveedor']; ?> <?php echo $reg[0]['provincia'] == '' ? "*********" : strtoupper($reg[0]['provincia']); ?> <?php echo $reg[0]['departamento'] == '' ? "*********" : strtoupper($reg[0]['departamento']); ?>
  <br/> EMAIL: <?php echo $reg[0]['emailproveedor'] == '' ? "**********************" : $reg[0]['emailproveedor']; ?>
  <br/> Nº <?php echo $reg[0]['documproveedor'] == '0' ? "DOCUMENTO" : $reg[0]['documento'] ?>: <?php echo $reg[0]['cuitproveedor'] == '' ? "**********************" : $reg[0]['cuitproveedor']; ?> - TLF: <?php echo $reg[0]['tlfproveedor'] == '' ? "**********************" : $reg[0]['tlfproveedor']; ?>
  <br/> VENDEDOR: <?php echo $reg[0]['vendedor']; ?> - TLF: <?php echo $reg[0]['tlfvendedor'] == '' ? "**********************" : $reg[0]['tlfvendedor']; ?></p>
                                            
                                        </address>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="table-responsive m-t-10" style="clear: both;">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr class="text-center">
                        <th>#</th>
                        <th>Descripción de Producto</th>
                        <th>Cantidad</th>
                        <th>Precio Unit.</th>
                        <th>Valor Total</th>
                        <th>Desc %</th>
                        <th><?php echo $impuesto == '' ? "Impuesto" : $imp[0]['nomimpuesto']; ?></th>
                        <th>Valor Neto</th>
<?php if ($_SESSION['acceso'] == "administrador") { ?><th>Acción</th><?php } ?>
                                                </tr>
                                            </thead>
                                            <tbody>
<?php 
$tra = new Login();
$detalle = $tra->VerDetallesCompras();

$SubTotal = 0;
$a=1;
for($i=0;$i<sizeof($detalle);$i++){  
$SubTotal += $detalle[$i]['valorneto']; 
?>
                                                <tr class="text-center">
      <td><?php echo $a++; ?></td>
      <td><h5><?php echo $detalle[$i]['producto']; ?></h5>
      <small>(<?php echo $detalle[$i]['tipoentrada'] == 'PRODUCTO' ? $detalle[$i]['nomcategoria'] : $detalle[$i]['nommedida']; ?>)</small></td>
      <td><?php echo $detalle[$i]['cantcompra']; ?></td>
      <td><?php echo $simbolo.number_format($detalle[$i]['preciocomprac'], 2, '.', ','); ?></td>
      <td><?php echo $simbolo.number_format($detalle[$i]['valortotal'], 2, '.', ','); ?></td>
      <td><?php echo $simbolo.$detalle[$i]['totaldescuentoc']; ?><sup><?php echo $detalle[$i]['descfactura']; ?>%</sup></td>
      <td><?php echo $detalle[$i]['ivaproductoc'] == 'SI' ? $reg[0]['ivac']."%" : "(E)"; ?></td>
      <td><?php echo $simbolo.number_format($detalle[$i]['valorneto'], 2, '.', ','); ?></td>
 <?php if ($_SESSION['acceso'] == "administrador") { ?><td>
<button type="button" class="btn btn-rounded btn-dark" onClick="EliminarDetallesComprasPagadasModal('<?php echo encrypt($detalle[$i]["coddetallecompra"]); ?>','<?php echo encrypt($detalle[$i]["codcompra"]); ?>','<?php echo encrypt($reg[0]["codproveedor"]); ?>','<?php echo encrypt("DETALLESCOMPRAS") ?>')" title="Eliminar" ><i class="fa fa-trash-o"></i></button></td><?php } ?>
                                                </tr>
                                      <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>


                                <div class="col-md-12">

                                    <div class="pull-right text-right">
<p><b>Subtotal:</b> <?php echo $simbolo.number_format($SubTotal, 2, '.', ','); ?></p>
<p><b>Total Grabado <?php echo $reg[0]['ivac'] ?>%:</b> <?php echo $simbolo.number_format($reg[0]['subtotalivasic'], 2, '.', ','); ?></p>
<p><b>Total Exento 0%:</b> <?php echo $simbolo.number_format($reg[0]['subtotalivanoc'], 2, '.', ','); ?></p>
<p><b>Total <?php echo $impuesto == '' ? "Impuesto" : $imp[0]['nomimpuesto']; ?> (<?php echo $reg[0]['ivac']; ?>%):</b> <?php echo $simbolo.number_format($reg[0]['totalivac'], 2, '.', ','); ?> </p>
<p><b>Desc. Global (<?php echo $reg[0]['descuentoc']; ?>%):</b> <?php echo $simbolo.number_format($reg[0]['totaldescuentoc'], 2, '.', ','); ?> </p>
                                        <hr>
<h3><b>Importe Total :</b> <?php echo $simbolo.number_format($reg[0]['totalpagoc'], 2, '.', ','); ?></h3></div>
                                    <div class="clearfix"></div>
                                    <hr>
                                <div class="col-md-12">
                                    <div class="text-right">
 <a href="reportepdf?codcompra=<?php echo encrypt($reg[0]['codcompra']); ?>&tipo=<?php echo encrypt("FACTURACOMPRA") ?>" target="_blank" rel="noopener noreferrer"><button id="print" class="btn waves-light btn-light" type="button"><span><i class="fa fa-print"></i> Imprimir</span> </button></a>
 <button type="button" class="btn btn-dark" data-dismiss="modal"><span class="fa fa-times-circle"></span> Cerrar</button>
                                    </div>
                                </div>
                            </div>
                <!-- .row -->

  <?php
       }
   } 
######################### MOSTRAR COMPRA PAGADA EN VENTANA MODAL ########################
?>

<?php
####################### MOSTRAR COMPRA PENDIENTE EN VENTANA MODAL #######################
if (isset($_GET['BuscaCompraPendienteModal']) && isset($_GET['codcompra'])) { 
 
$reg = $new->ComprasPorId();

  if($reg==""){
    
    echo "<div class='alert alert-danger'>";
    echo "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>";
    echo "<center><span class='fa fa-info-circle'></span> NO SE ENCONTRARON COMPRAS Y DETALLES ACTUALMENTE </center>";
    echo "</div>";    

} else {
?>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="pull-left">
                                        <address>
  <h3><b class="text-danger">RAZÓN SOCIAL</b></h3>
  <p class="text-muted m-l-5"><?php echo $con[0]['nomsucursal']; ?>,
  <br/> Nº <?php echo $con[0]['documsucursal'] == '0' ? "DOCUMENTO" : $con[0]['documento'] ?>: <?php echo $con[0]['cuit']; ?> - TLF: <?php echo $con[0]['tlfsucursal']; ?></p>

  <h3><b class="text-danger">Nº COMPRA <?php echo $reg[0]['codcompra']; ?></b></h3>
  <p class="text-muted m-l-5">STATUS: 
  <?php if($reg[0]['fechavencecredito']== '0000-00-00') { echo "<span class='badge badge-pill badge-info'><i class='fa fa-check'></i> ".$reg[0]["statuscompra"]."</span>"; } 
        elseif($reg[0]['fechavencecredito'] >= date("Y-m-d") && $reg[0]['fechapagado']== "0000-00-00") { echo "<span class='badge badge-pill badge-success'><i class='fa fa-exclamation-circle'></i> ".$reg[0]["statuscompra"]."</span>"; } 
        elseif($reg[0]['fechavencecredito'] < date("Y-m-d") && $reg[0]['fechapagado']== "0000-00-00") { echo "<span class='badge badge-pill badge-danger'><i class='fa fa-times'></i> VENCIDA</span>"; }
        elseif($reg[0]['fechavencecredito'] <= date("Y-m-d") && $reg[0]['fechapagado']!= "0000-00-00") { echo "<span class='badge badge-pill badge-info'><i class='fa fa-check'></i> ".$reg[0]["statuscompra"]."</span>"; } ?>

  <?php if($reg[0]['fechavencecredito']!= "0000-00-00") { ?>
  <br>DIAS VENCIDOS: 
  <?php if($reg[0]['fechavencecredito']== '0000-00-00') { echo "0"; } 
        elseif($reg[0]['fechavencecredito'] >= date("Y-m-d") && $reg[0]['fechapagado']== "0000-00-00") { echo "0"; } 
        elseif($reg[0]['fechavencecredito'] < date("Y-m-d") && $reg[0]['fechapagado']== "0000-00-00") { echo Dias_Transcurridos(date("Y-m-d"),$reg[0]['fechavencecredito']); }
        elseif($reg[0]['fechavencecredito'] < date("Y-m-d") && $reg[0]['fechapagado']!= "0000-00-00") { echo Dias_Transcurridos($reg[0]['fechapagado'],$reg[0]['fechavencecredito']); } ?>
  <?php } ?>
  
  <?php if($reg[0]['fechapagado']!= "0000-00-00") { ?>
  <br>FECHA PAGADA: <?php echo date("d-m-Y",strtotime($reg[0]['fechapagado'])); ?>
  <?php } ?>

  <br>FECHA DE EMISIÓN: <?php echo date("d-m-Y",strtotime($reg[0]['fechaemision'])); ?>
  <br/> FECHA DE RECEPCIÓN: <?php echo date("d-m-Y",strtotime($reg[0]['fecharecepcion'])); ?></p>
                                        </address>
                                    </div>
                                    <div class="pull-right text-right">
                                        <address>
  <h3><b class="text-danger">PROVEEDOR</b></h3>
  <p class="text-muted m-l-30"><?php echo $reg[0]['nomproveedor'] == '' ? "**********************" : $reg[0]['nomproveedor']; ?>,
  <br/>DIREC: <?php echo $reg[0]['direcproveedor'] == '' ? "*********" : $reg[0]['direcproveedor']; ?> <?php echo $reg[0]['provincia'] == '' ? "*********" : strtoupper($reg[0]['provincia']); ?> <?php echo $reg[0]['departamento'] == '' ? "*********" : strtoupper($reg[0]['departamento']); ?>
  <br/> EMAIL: <?php echo $reg[0]['emailproveedor'] == '' ? "**********************" : $reg[0]['emailproveedor']; ?>
  <br/> Nº <?php echo $reg[0]['documproveedor'] == '0' ? "DOCUMENTO" : $reg[0]['documento'] ?>: <?php echo $reg[0]['cuitproveedor'] == '' ? "**********************" : $reg[0]['cuitproveedor']; ?> - TLF: <?php echo $reg[0]['tlfproveedor'] == '' ? "**********************" : $reg[0]['tlfproveedor']; ?>
  <br/> VENDEDOR: <?php echo $reg[0]['vendedor']; ?> - TLF: <?php echo $reg[0]['tlfvendedor'] == '' ? "**********************" : $reg[0]['tlfvendedor']; ?></p>
                                            
                                        </address>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="table-responsive m-t-10" style="clear: both;">
                                        <table class="table table-hover">
                                            <thead>
                                              <tr class="text-center">
                        <th>#</th>
                        <th>Descripción de Producto</th>
                        <th>Cantidad</th>
                        <th>Precio Unit.</th>
                        <th>Valor Total</th>
                        <th>Desc %</th>
                        <th><?php echo $impuesto == '' ? "Impuesto" : $imp[0]['nomimpuesto']; ?></th>
                        <th>Valor Neto</th>
<?php if ($_SESSION['acceso'] == "administrador") { ?><th>Acción</th><?php } ?>
                                                </tr>
                                            </thead>
                                            <tbody>
<?php 
$tra = new Login();
$detalle = $tra->VerDetallesCompras();

$SubTotal = 0;
$a=1;
for($i=0;$i<sizeof($detalle);$i++){  
$SubTotal += $detalle[$i]['valorneto'];
?>
                                                <tr class="text-center">
      <td><?php echo $a++; ?></td>
      <td><h5><?php echo $detalle[$i]['producto']; ?></h5>
      <small>(<?php echo $detalle[$i]['nomcategoria']; ?>)</small></td>
      <td><?php echo $detalle[$i]['cantcompra']; ?></td>
      <td><?php echo $simbolo.number_format($detalle[$i]['preciocomprac'], 2, '.', ','); ?></td>
      <td><?php echo $simbolo.number_format($detalle[$i]['valortotal'], 2, '.', ','); ?></td>
      <td><?php echo $simbolo.$detalle[$i]['totaldescuentoc']; ?><sup><?php echo $detalle[$i]['descfactura']; ?>%</sup></td>
      <td><?php echo $detalle[$i]['ivaproductoc'] == 'SI' ? $reg[0]['ivac']."%" : "(E)"; ?></td>
      <td><?php echo $simbolo.number_format($detalle[$i]['valorneto'], 2, '.', ','); ?></td>
 <?php if ($_SESSION['acceso'] == "administrador") { ?><td>
<button type="button" class="btn btn-rounded btn-dark" onClick="EliminarDetallesComprasPendientesModal('<?php echo encrypt($detalle[$i]["coddetallecompra"]); ?>','<?php echo encrypt($detalle[$i]["codcompra"]); ?>','<?php echo encrypt($reg[0]["codproveedor"]); ?>','<?php echo encrypt("DETALLESCOMPRAS") ?>')" title="Eliminar" ><i class="fa fa-trash-o"></i></button></td><?php } ?>
                                                </tr>
                                      <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>


                                <div class="col-md-12">

                                    <div class="pull-right text-right">
<p><b>Subtotal:</b> <?php echo $simbolo.number_format($SubTotal, 2, '.', ','); ?></p>
<p><b>Total Grabado <?php echo $reg[0]['ivac'] ?>%:</b> <?php echo $simbolo.number_format($reg[0]['subtotalivasic'], 2, '.', ','); ?></p>
<p><b>Total Exento 0%:</b> <?php echo $simbolo.number_format($reg[0]['subtotalivanoc'], 2, '.', ','); ?></p>
<p><b>Total <?php echo $impuesto == '' ? "Impuesto" : $imp[0]['nomimpuesto']; ?> (<?php echo $reg[0]['ivac']; ?>%):</b> <?php echo $simbolo.number_format($reg[0]['totalivac'], 2, '.', ','); ?> </p>
<p><b>Desc. Global (<?php echo $reg[0]['descuentoc']; ?>%):</b> <?php echo $simbolo.number_format($reg[0]['totaldescuentoc'], 2, '.', ','); ?> </p>
                                        <hr>
<h3><b>Importe Total :</b> <?php echo $simbolo.number_format($reg[0]['totalpagoc'], 2, '.', ','); ?></h3></div>
                                    <div class="clearfix"></div>
                                    <hr>

                                <div class="col-md-12">
                                    <div class="text-right">
 <a href="reportepdf?codcompra=<?php echo encrypt($reg[0]['codcompra']); ?>&tipo=<?php echo encrypt("FACTURACOMPRA") ?>" target="_blank" rel="noopener noreferrer"><button id="print" class="btn waves-light btn-light" type="button"><span><i class="fa fa-print"></i> Imprimir</span></button></a>
 <button type="button" class="btn btn-dark" data-dismiss="modal"><span class="fa fa-times-circle"></span> Cerrar</button>
                                    </div>
                                </div>
                            </div>
                <!-- .row -->

  <?php
       }
   } 
###################### MOSTRAR COMPRA PENDIENTE EN VENTANA MODAL #######################
?>


<?php
######################## MOSTRAR DETALLES DE COMPRAS UPDATE ############################
if (isset($_GET['MuestraDetallesComprasUpdate']) && isset($_GET['codcompra'])) { 
 
$reg = $new->ComprasPorId();

?>

<div class="table-responsive m-t-20">
            <table class="table table-hover">
                <thead>
                    <tr class="text-center">
                        <th>Cantidad</th>
                        <th>Código</th>
                        <th>Descripción de Producto</th>
                        <th>Precio Unit.</th>
                        <th>Valor Total</th>
                        <th>Desc %</th>
                        <th><?php echo $impuesto == '' ? "Impuesto" : $imp[0]['nomimpuesto']; ?></th>
                        <th>Valor Neto</th>
<?php if ($_SESSION['acceso'] == "administrador") { ?><th>Acción</th><?php } ?>
                    </tr>
                </thead>
                <tbody>
<?php 
$tra = new Login();
$detalle = $tra->VerDetallesCompras();
$a=1;
for($i=0;$i<sizeof($detalle);$i++){  
    ?>
                                 <tr class="text-center">
      <td>
      <input type="text" class="form-control" name="cantcompra[]" id="cantcompra<?php echo $a; ?>" onKeyUp="this.value=this.value.toUpperCase();" autocomplete="off" placeholder="Cantidad" value="<?php echo $detalle[$i]["cantcompra"]; ?>" style="width: 80px;" onfocus="this.style.background=('#B7F0FF')" onBlur="this.style.background=('#e4e7ea')" title="Ingrese Cantidad" onKeyPress="EvaluateText('%f', this);" onBlur="this.value = NumberFormat(this.value, '2', '.', '')" required="" aria-required="true">
      <input type="hidden" name="cantidadcomprabd[]" id="cantidadcomprabd" value="<?php echo $detalle[$i]["cantcompra"]; ?>">
      </td>
      
      <td>
      <input type="hidden" name="coddetallecompra[]" id="coddetallecompra" value="<?php echo $detalle[$i]["coddetallecompra"]; ?>">
      <input type="hidden" name="tipoentrada[]" id="tipoentrada" value="<?php echo $detalle[$i]["tipoentrada"]; ?>">
      <input type="hidden" name="codproducto[]" id="codproducto" value="<?php echo $detalle[$i]["codproducto"]; ?>">
      <?php echo $detalle[$i]['codproducto']; ?>
      </td>
      
      <td><input type="hidden" name="precioventa[]" id="precioventa" value="<?php echo $detalle[$i]["precioventac"]; ?>"><h5><?php echo $detalle[$i]['producto']; ?></h5><small>(<?php echo $detalle[$i]['nomcategoria']; ?>)</small></td>
      
      <td><input type="hidden" name="preciocompra[]" id="preciocompra" value="<?php echo $detalle[$i]["preciocomprac"]; ?>"><?php echo $simbolo.$detalle[$i]['preciocomprac']; ?></td>

      <td><input type="hidden" name="valortotal[]" id="valortotal" value="<?php echo $detalle[$i]["valortotal"]; ?>"><?php echo $simbolo.$detalle[$i]['valortotal']; ?></td>
      
      <td><input type="hidden" name="descfactura[]" id="descfactura" value="<?php echo $detalle[$i]["descfactura"]; ?>"><?php echo $simbolo.$detalle[$i]['totaldescuentoc']; ?><sup><?php echo $detalle[$i]['descfactura']; ?>%</sup></td>

      <td><input type="hidden" name="ivaproducto[]" id="ivaproducto" value="<?php echo $detalle[$i]["ivaproductoc"]; ?>"><?php echo $detalle[$i]['ivaproductoc'] == 'SI' ? $reg[0]['ivac']."%" : "(E)"; ?></td>

      <td><?php echo $simbolo.$detalle[$i]['valorneto']; ?></td>

 <?php if ($_SESSION['acceso'] == "administrador") { ?><td>
<button type="button" class="btn btn-rounded btn-dark" onClick="EliminarDetallesComprasUpdate('<?php echo encrypt($detalle[$i]["coddetallecompra"]); ?>','<?php echo encrypt($detalle[$i]["codcompra"]); ?>','<?php echo encrypt($reg[0]["codproveedor"]); ?>','<?php echo encrypt("DETALLESCOMPRAS") ?>')" title="Eliminar" ><i class="fa fa-trash-o"></i></button></td><?php } ?>
                                 </tr>
                     <?php } ?>
                </tbody>
            </table><hr>

             <table id="carritototal" class="table-responsive">
                <tr>
    <td width="50">&nbsp;</td>
    <td width="250">
    <h5><label>Total Gravado <?php echo $reg[0]['ivac'] ?>%:</label></h5>
    </td>
                  
    <td width="250">
    <h5><?php echo $simbolo; ?><label id="lblsubtotal" name="lblsubtotal"><?php echo $reg[0]['subtotalivasic'] ?></label></h5>
    <input type="hidden" name="txtsubtotal" id="txtsubtotal" value="<?php echo $reg[0]['subtotalivasic'] ?>"/>
    </td>

    <td width="250">
    <h5><label>Total Exento 0%:</label></h5>
    </td>
    
    <td width="250">
    <h5><?php echo $simbolo; ?><label id="lblsubtotal2" name="lblsubtotal2"><?php echo $reg[0]['subtotalivanoc'] ?></label></h5>
    <input type="hidden" name="txtsubtotal2" id="txtsubtotal2" value="<?php echo $reg[0]['subtotalivanoc'] ?>"/>
    </td>

    <td class="text-center" width="250">
    <h2><b>Importe Total</b></h2>
    </td>
                </tr>
                <tr>
    <td>&nbsp;</td>
    <td>
    <h5><label><?php echo $impuesto == '' ? "Impuesto" : $imp[0]['nomimpuesto']; ?> <?php echo $reg[0]['ivac'] ?>%:<input type="hidden" name="iva" id="iva" autocomplete="off" value="<?php echo $reg[0]['ivac'] ?>"></label></h5>
    </td>
    
    <td>
    <h5><?php echo $simbolo; ?><label id="lbliva" name="lbliva"><?php echo $reg[0]['totalivac'] ?></label></h5>
    <input type="hidden" name="txtIva" id="txtIva" value="<?php echo $reg[0]['totalivac'] ?>"/>
    </td>

    <td>
    <h5><label>Desc. Global <input class="number" type="text" name="descuento" id="descuento" onKeyPress="EvaluateText('%f', this);" style="border-radius:4px;height:30px;width:70px;" onBlur="this.value = NumberFormat(this.value, '2', '.', '')" onKeyUp="this.value=this.value.toUpperCase();" autocomplete="off" value="<?php echo $reg[0]['descuentoc'] ?>">%:</label></h5>
    </td>

    <td>
    <h5><?php echo $simbolo; ?><label id="lbldescuento" name="lbldescuento"><?php echo $reg[0]['totaldescuentoc'] ?></label></h5>
    <input type="hidden" name="txtDescuento" id="txtDescuento" value="<?php echo $reg[0]['totaldescuentoc'] ?>"/>
    </td>

    <td class="text-center">
    <h2><?php echo $simbolo; ?><label id="lbltotal" name="lbltotal"><?php echo $reg[0]['totalpagoc'] ?></label></h2>
    <input type="hidden" name="txtTotal" id="txtTotal" value="<?php echo $reg[0]['totalpagoc'] ?>"/>
    </td>
                    </tr>
                  </table>
        </div>
<?php
  } 
######################## MOSTRAR DETALLES DE COMPRAS UPDATE ########################
?>


<?php
######################## BUSQUEDA COMPRAS POR PROVEEDORES ########################
if (isset($_GET['BuscaComprasxProvedores']) && isset($_GET['codproveedor'])) {
  
  $codproveedor = limpiar($_GET['codproveedor']);

 if($codproveedor=="") {

   echo "<div class='alert alert-danger'>";
   echo "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>";
   echo "<center><span class='fa fa-info-circle'></span> POR FAVOR SELECCIONE PROVEEDOR PARA TU BÚSQUEDA</center>";
   echo "</div>";   
   exit;

} else {

$pre = new Login();
$reg = $pre->BuscarComprasxProveedor();
  ?>

<!-- Row -->
 <div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header bg-warning">
        <h4 class="card-title text-white"><i class="fa fa-tasks"></i> Compras al Proveedor <?php echo $reg[0]['cuitproveedor'].": ".$reg[0]['nomproveedor']; ?></h4>
      </div>

      <div class="form-body">
        <div class="card-body">

          <div class="row">
            <div class="col-md-7">
              <div class="btn-group m-b-20">
              <a class="btn waves-effect waves-light btn-light" href="reportepdf?codproveedor=<?php echo $codproveedor; ?>&tipo=<?php echo encrypt("COMPRASXPROVEEDOR") ?>" target="_blank" rel="noopener noreferrer"  data-toggle="tooltip" data-placement="bottom" title="Exportar Pdf"><span class="fa fa-file-pdf-o text-dark"></span> Pdf</a>

              <a class="btn waves-effect waves-light btn-light" href="reporteexcel?codproveedor=<?php echo $codproveedor; ?>&documento=<?php echo encrypt("EXCEL") ?>&tipo=<?php echo encrypt("COMPRASXPROVEEDOR") ?>" data-toggle="tooltip" data-placement="bottom" title="Exportar Excel"><span class="fa fa-file-excel-o text-dark"></span> Excel</a>

              <a class="btn waves-effect waves-light btn-light" href="reporteexcel?codproveedor=<?php echo $codproveedor; ?>&documento=<?php echo encrypt("WORD") ?>&tipo=<?php echo encrypt("COMPRASXPROVEEDOR") ?>" data-toggle="tooltip" data-placement="bottom" title="Exportar Word"><span class="fa fa-file-word-o text-dark"></span> Word</a>
              </div>
            </div>
          </div>

  <div id="div2"><table id="datatable-scroller" class="table table-hover table-striped table-bordered nowrap" cellspacing="0" width="100%">
                              <thead>
                              <tr class="text-center">
                              <th>Nº</th>
                              <th>N° de Compra</th>
                              <th>Descripción de Proveedor</th>
                              <th>Nº de Articulos</th>
                              <th>Imp. Total</th>
                              <th>Status</th>
                              <th>Dias Venc.</th>
                              <th>Fecha de Emisión</th>
                              <th>Fecha de Recepción</th>
                              <th>Reporte</th>
                                </tr>
                              </thead>
                              <tbody>
<?php
$a=1;
for($i=0;$i<sizeof($reg);$i++){
?>
                                <tr class="text-center">
                    <td><?php echo $a++; ?></td>
                    <td><?php echo $reg[$i]['codcompra']; ?></td>
 <td><abbr title="<?php echo "Nº ".$documento = ($reg[$i]['documproveedor'] == '0' ? "DOCUMENTO" : $reg[$i]['documento']).": ".$reg[$i]['cuitproveedor']; ?>"><?php echo $reg[$i]['nomproveedor']; ?></abbr></td>
                    <td><?php echo $reg[$i]['articulos']; ?></td>
                    <td><?php echo $simbolo.number_format($reg[$i]['totalpagoc'], 2, '.', ','); ?></td>
                    <td><?php if($reg[$i]['fechavencecredito']== '0000-00-00') { echo "<span class='badge badge-pill badge-info'><i class='fa fa-check'></i> ".$reg[$i]["statuscompra"]."</span>"; } 
      elseif($reg[$i]['fechavencecredito'] >= date("Y-m-d") && $reg[$i]['fechapagado']== "0000-00-00") { echo "<span class='badge badge-pill badge-success'><i class='fa fa-exclamation-circle'></i> ".$reg[$i]["statuscompra"]."</span>"; } 
      elseif($reg[$i]['fechavencecredito'] < date("Y-m-d") && $reg[$i]['fechapagado']== "0000-00-00") { echo "<span class='badge badge-pill badge-danger'><i class='fa fa-times'></i> VENCIDA</span>"; }
      elseif($reg[$i]['fechavencecredito'] <= date("Y-m-d") && $reg[$i]['fechapagado']!= "0000-00-00") { echo "<span class='badge badge-pill badge-info'><i class='fa fa-check'></i> ".$reg[$i]["statuscompra"]."</span>"; } ?></td>
<td><?php if($reg[$i]['fechavencecredito']== '0000-00-00') { echo "0"; } 
        elseif($reg[$i]['fechavencecredito'] >= date("Y-m-d") && $reg[$i]['fechapagado']== "0000-00-00") { echo "0"; } 
        elseif($reg[$i]['fechavencecredito'] < date("Y-m-d") && $reg[$i]['fechapagado']== "0000-00-00") { echo Dias_Transcurridos(date("Y-m-d"),$reg[$i]['fechavencecredito']); }
        elseif($reg[$i]['fechavencecredito'] < date("Y-m-d") && $reg[$i]['fechapagado']!= "0000-00-00") { echo Dias_Transcurridos($reg[$i]['fechapagado'],$reg[$i]['fechavencecredito']); } ?></td>
                    <td><?php echo date("d-m-Y",strtotime($reg[$i]['fechaemision'])); ?></td>
                    <td><?php echo date("d-m-Y",strtotime($reg[$i]['fecharecepcion'])); ?></td>
                    <td>
<a href="reportepdf?codcompra=<?php echo encrypt($reg[$i]['codcompra']); ?>&tipo=<?php echo encrypt("FACTURACOMPRA") ?>" target="_blank" rel="noopener noreferrer"><button type="button" class="btn btn-rounded btn-secondary" title="Imprimir Pdf"><i class="fa fa-print"></i></button></a></td>
                                </tr>
                        <?php  }  ?>
                              </tbody>
                          </table>
                      </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- End Row -->

<?php
  
   }
 } 
########################## BUSQUEDA COMPRAS POR PROVEEDORES ##########################
?>


<?php
########################## BUSQUEDA COMPRAS POR FECHAS ##########################
if (isset($_GET['BuscaComprasxFechas']) && isset($_GET['desde']) && isset($_GET['hasta'])) {
  
  $desde = limpiar($_GET['desde']);
  $hasta = limpiar($_GET['hasta']);

 if($desde=="") {

  echo "<div class='alert alert-danger'>";
  echo "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>";
  echo "<center><span class='fa fa-info-circle'></span> POR FAVOR INGRESE FECHA DE INICIO PARA TU BÚSQUEDA</center>";
  echo "</div>"; 
  exit;


} else if($hasta=="") {

  echo "<div class='alert alert-danger'>";
  echo "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>";
  echo "<center><span class='fa fa-info-circle'></span> POR FAVOR INGRESE FECHA FINAL PARA TU BÚSQUEDA</center>";
  echo "</div>"; 
  exit;

} elseif (strtotime($desde) > strtotime($hasta)) {

  echo "<div class='alert alert-danger'>";
  echo "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>";
  echo "<center><span class='fa fa-info-circle'></span> LA FECHA DE INICIO NO PUEDE SER MAYOR QUE LA FECHA DE FIN</center>";
  echo "</div>"; 
  exit;

} else {

$pre = new Login();
$reg = $pre->BuscarComprasxFechas();
  ?>

<!-- Row -->
 <div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header bg-warning">
        <h4 class="card-title text-white"><i class="fa fa-tasks"></i> Compras de Productos por Fechas Desde <?php echo date("d-m-Y", strtotime($desde)); ?> Hasta <?php echo date("d-m-Y", strtotime($hasta)); ?></h4>
      </div>

      <div class="form-body">
        <div class="card-body">

          <div class="row">
            <div class="col-md-7">
              <div class="btn-group m-b-20">
              <a class="btn waves-effect waves-light btn-light" href="reportepdf?desde=<?php echo $desde; ?>&hasta=<?php echo $hasta; ?>&tipo=<?php echo encrypt("COMPRASXFECHAS") ?>" target="_blank" rel="noopener noreferrer" data-toggle="tooltip" data-placement="bottom" title="Exportar Pdf"><span class="fa fa-file-pdf-o text-dark"></span> Pdf</a>

              <a class="btn waves-effect waves-light btn-light" href="reporteexcel?desde=<?php echo $desde; ?>&hasta=<?php echo $hasta; ?>&documento=<?php echo encrypt("EXCEL") ?>&tipo=<?php echo encrypt("COMPRASXFECHAS") ?>" data-toggle="tooltip" data-placement="bottom" title="Exportar Excel"><span class="fa fa-file-excel-o text-dark"></span> Excel</a>

              <a class="btn waves-effect waves-light btn-light" href="reporteexcel?desde=<?php echo $desde; ?>&hasta=<?php echo $hasta; ?>&documento=<?php echo encrypt("WORD") ?>&tipo=<?php echo encrypt("COMPRASXFECHAS") ?>" data-toggle="tooltip" data-placement="bottom" title="Exportar Word"><span class="fa fa-file-word-o text-dark"></span> Word</a>
              </div>
            </div>
          </div>

  <div id="div2"><table id="datatable-scroller" class="table table-hover table-striped table-bordered nowrap" cellspacing="0" width="100%">
                              <thead>
                                <tr class="text-center">
                              <th>Nº</th>
                              <th>N° de Compra</th>
                              <th>Descripción de Proveedor</th>
                              <th>Nº de Articulos</th>
                              <th>Imp. Total</th>
                              <th>Status</th>
                              <th>Fecha de Emisión</th>
                              <th>Fecha de Recepción</th>
                              <th>Reporte</th>
                                </tr>
                              </thead>
                              <tbody>
<?php
$a=1;
for($i=0;$i<sizeof($reg);$i++){
?>
                                <tr class="text-center">
                    <td><?php echo $a++; ?></td>
                    <td><?php echo $reg[$i]['codcompra']; ?></td>
<td><abbr title="<?php echo "Nº ".$documento = ($reg[$i]['documproveedor'] == '0' ? "DOCUMENTO" : $reg[$i]['documento']).": ".$reg[$i]['cuitproveedor']; ?>"><?php echo $reg[$i]['nomproveedor']; ?></abbr></td>
                    <td><?php echo $reg[$i]['articulos']; ?></td>
                    <td><?php echo $simbolo.number_format($reg[$i]['totalpagoc'], 2, '.', ','); ?></td>
                    <td><?php if($reg[$i]['fechavencecredito']== '0000-00-00') { echo "<span class='badge badge-pill badge-info'><i class='fa fa-check'></i> ".$reg[$i]["statuscompra"]."</span>"; } 
      elseif($reg[$i]['fechavencecredito'] >= date("Y-m-d") && $reg[$i]['fechapagado']== "0000-00-00") { echo "<span class='badge badge-pill badge-success'><i class='fa fa-exclamation-circle'></i> ".$reg[$i]["statuscompra"]."</span>"; } 
      elseif($reg[$i]['fechavencecredito'] < date("Y-m-d") && $reg[$i]['fechapagado']== "0000-00-00") { echo "<span class='badge badge-pill badge-danger'><i class='fa fa-times'></i> VENCIDA</span>"; }
      elseif($reg[$i]['fechavencecredito'] <= date("Y-m-d") && $reg[$i]['fechapagado']!= "0000-00-00") { echo "<span class='badge badge-pill badge-info'><i class='fa fa-check'></i> ".$reg[$i]["statuscompra"]."</span>"; } ?></td>
                    <td><?php echo date("d-m-Y",strtotime($reg[$i]['fechaemision'])); ?></td>
                    <td><?php echo date("d-m-Y",strtotime($reg[$i]['fecharecepcion'])); ?></td>
                    <td>
<a href="reportepdf?codcompra=<?php echo encrypt($reg[$i]['codcompra']); ?>&tipo=<?php echo encrypt("FACTURACOMPRA") ?>" target="_blank" rel="noopener noreferrer"><button type="button" class="btn btn-rounded btn-secondary" title="Imprimir Pdf"><i class="fa fa-print"></i></button></a></td>
                                </tr>
                        <?php  }  ?>
                              </tbody>
                          </table>
                      </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- End Row -->

<?php
  
   }
 } 
########################## BUSQUEDA COMPRAS POR FECHAS ########################
?>


























<?php
######################## MOSTRAR CAJA DE VENTA EN VENTANA MODAL ########################
if (isset($_GET['BuscaCajaModal']) && isset($_GET['codcaja'])) { 

$reg = $new->CajasPorId();
?>
  
  <table class="table-responsive" border="0" align="center"> 
  <tr>
    <td><strong>Nº de Caja:</strong> <?php echo $reg[0]['nrocaja']; ?></td>
  </tr>
  <tr>
    <td><strong>Nombre de Caja:</strong> <?php echo $reg[0]['nomcaja']; ?></td>
  </tr>
  <tr>
    <td><strong>Responsable de Caja: </strong> <?php echo $reg[0]['nombres']; ?></td>
  </tr>
</table>
<?php 
} 
######################## MOSTRAR CAJA DE VENTA EN VENTANA MODAL ########################
?>

<?php
######################## MOSTRAR ARQUEO EN CAJA EN VENTANA MODAL ########################
if (isset($_GET['BuscaArqueoModal']) && isset($_GET['codarqueo'])) { 

$reg = $new->ArqueoCajaPorId();

  ?>
  
  <table class="table-responsive" border="0" align="center">
  <tr>
    <td><strong>Nombre de Caja:</strong> <?php echo $reg[0]['nrocaja'].": ".$reg[0]['nomcaja']; ?></td>
  </tr>
  <tr>
    <td><strong>Monto Inicial:</strong> <?php echo $simbolo.number_format($reg[0]['montoinicial'], 2, '.', ','); ?></td>
  </tr>
  <tr>
    <td><strong>Ingresos:</strong> <?php echo $simbolo.number_format($reg[0]['ingresos'], 2, '.', ','); ?></td>
  </tr>
  <tr>
    <td><strong>Egresos:</strong> <?php echo $simbolo.number_format($reg[0]['egresos'], 2, '.', ','); ?></td>
    </tr>
  <tr>
    <td><strong>Créditos:</strong> <?php echo $simbolo.number_format($reg[0]['creditos'], 2, '.', ','); ?></td>
  </tr>
  <tr>
    <td><strong>Abonos de Créditos:</strong> <?php echo $simbolo.number_format($reg[0]['abonos'], 2, '.', ','); ?></td>
  </tr>
  <tr>
    <td><strong>Ingresos de Propinas:</strong> <?php echo $simbolo.number_format($reg[0]['propinas'], 2, '.', ','); ?></td>
  </tr>
  <tr>
    <td><strong>Total Ventas:</strong> <?php echo $simbolo.number_format($reg[0]['ingresos']+$reg[0]['creditos'], 2, '.', ','); ?></td>
  </tr>
  <tr>
    <td><strong>Total Ingresos:</strong> <?php echo $simbolo.number_format($reg[0]['ingresos']+$reg[0]['abonos']+$reg[0]['propinas'], 2, '.', ','); ?></td>
  </tr>
  <tr>
    <td><strong>Dinero en Efectivo:</strong> <?php echo $simbolo.number_format($reg[0]['dineroefectivo'], 2, '.', ','); ?></td>
  </tr>
  <tr>
    <td><strong>Diferencia:</strong> <?php echo $simbolo.number_format($reg[0]['diferencia'], 2, '.', ','); ?></td>
  </tr>
  <tr>
    <td><strong>Observaciones:</strong> <?php echo $reg[0]['comentarios']; ?></td>
  </tr>
  <tr>
    <td><strong>Hora Apertura:</strong> <?php echo date("d-m-Y h:i:s",strtotime($reg[0]['fechaapertura'])); ?></td>
  </tr>
  <tr>
    <td><strong>Hora Cierre:</strong> <?php echo $cierre = ( $reg[0]['statusarqueo'] == '1' ? $reg[0]['fechacierre'] : date("d-m-Y h:i:s",strtotime($reg[0]['fechacierre']))); ?></td>
  </tr>
  <tr>
    <td><strong>Responsable:</strong> <?php echo $reg[0]['dni'].": ".$reg[0]['nombres']; ?></td>
  </tr>
</table>
  
  <?php
   } 
######################## MOSTRAR ARQUEO EN CAJA EN VENTANA MODAL ########################
?>


<?php
######################## BUSQUEDA ARQUEOS DE CAJA POR FECHAS ########################
if (isset($_GET['BuscaArqueosxFechas']) && isset($_GET['codcaja']) && isset($_GET['desde']) && isset($_GET['hasta'])) {
  
  $codcaja = limpiar($_GET['codcaja']);
  $desde = limpiar($_GET['desde']);
  $hasta = limpiar($_GET['hasta']);

 if($codcaja=="") {

   echo "<div class='alert alert-danger'>";
   echo "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>";
   echo "<center><span class='fa fa-info-circle'></span> POR FAVOR SELECCIONE CAJA PARA TU BÚSQUEDA</center>";
   echo "</div>";   
   exit;

} else if($desde=="") {

  echo "<div class='alert alert-danger'>";
  echo "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>";
  echo "<center><span class='fa fa-info-circle'></span> POR FAVOR INGRESE FECHA DE INICIO PARA TU BÚSQUEDA</center>";
  echo "</div>"; 
  exit;


} else if($hasta=="") {

  echo "<div class='alert alert-danger'>";
  echo "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>";
  echo "<center><span class='fa fa-info-circle'></span> POR FAVOR INGRESE FECHA FINAL PARA TU BÚSQUEDA</center>";
  echo "</div>"; 
  exit;

} elseif (strtotime($desde) > strtotime($hasta)) {

  echo "<div class='alert alert-danger'>";
  echo "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>";
  echo "<center><span class='fa fa-info-circle'></span> LA FECHA DE INICIO NO PUEDE SER MAYOR QUE LA FECHA DE FIN</center>";
  echo "</div>"; 
  exit;

} else {

$pre = new Login();
$reg = $pre->BuscarArqueosxFechas();
  ?>

<!-- Row -->
 <div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header bg-warning">
        <h4 class="card-title text-white"><i class="fa fa-tasks"></i> Arqueos de Cajas por Fechas Desde <?php echo date("d-m-Y", strtotime($desde)); ?> Hasta <?php echo date("d-m-Y", strtotime($hasta)); ?></h4>
      </div>

      <div class="form-body">
        <div class="card-body">

          <div class="row">
            <div class="col-md-7">
              <div class="btn-group m-b-20">
              <a class="btn waves-effect waves-light btn-light" href="reportepdf?codcaja=<?php echo $codcaja; ?>&desde=<?php echo $desde; ?>&hasta=<?php echo $hasta; ?>&tipo=<?php echo encrypt("ARQUEOSXFECHAS") ?>" target="_blank" rel="noopener noreferrer" data-toggle="tooltip" data-placement="bottom" title="Exportar Pdf"><span class="fa fa-file-pdf-o text-dark"></span> Pdf</a>

              <a class="btn waves-effect waves-light btn-light" href="reporteexcel?codcaja=<?php echo $codcaja; ?>&desde=<?php echo $desde; ?>&hasta=<?php echo $hasta; ?>&documento=<?php echo encrypt("EXCEL") ?>&tipo=<?php echo encrypt("ARQUEOSXFECHAS") ?>" data-toggle="tooltip" data-placement="bottom" title="Exportar Excel"><span class="fa fa-file-excel-o text-dark"></span> Excel</a>

              <a class="btn waves-effect waves-light btn-light" href="reporteexcel?codcaja=<?php echo $codcaja; ?>&desde=<?php echo $desde; ?>&hasta=<?php echo $hasta; ?>&documento=<?php echo encrypt("WORD") ?>&tipo=<?php echo encrypt("ARQUEOSXFECHAS") ?>" data-toggle="tooltip" data-placement="bottom" title="Exportar Word"><span class="fa fa-file-word-o text-dark"></span> Word</a>
              </div>
            </div>
          </div>

  <div id="div2"><table id="datatable-scroller" class="table table-hover table-striped table-bordered nowrap" cellspacing="0" width="100%">
                              <thead>
                                <tr class="text-center">
                                  <th>Nº</th>
                                  <th>Caja</th>
                                  <th>Hora de Apertura</th>
                                  <th>Hora de Cierre</th>
                                  <th>Inicial</th>
                                  <th>Ingresos</th>
                                  <th>Egresos</th>
                                  <th>Créditos</th>
                                  <th>Abonos</th>
                                  <th>Propinas</th>
                                  <th>Total Ventas</th>
                                  <th>Total Ingresos</th>
                                  <th>Dinero Efectivo</th>
                                </tr>
                              </thead>
                              <tbody>
<?php
$a=1;
for($i=0;$i<sizeof($reg);$i++){
?>
                                <tr class="text-center">
                    <td><?php echo $a++; ?></td>
<td><abbr title="<?php echo "Responsable: ".$reg[$i]['nombres'] ?>"><?php echo $reg[$i]['nrocaja'].": ".$reg[$i]['nomcaja']; ?></abbr></td>
              <td><?php echo date("d-m-Y",strtotime($reg[$i]['fechaapertura'])); ?></td>
<td><?php echo $reg[$i]['fechacierre'] == '0000-00-00 00:00:00' ? "*********" : date("d-m-Y",strtotime($reg[$i]['fechacierre'])); ?></td>
<td><?php echo $simbolo.number_format($reg[$i]['montoinicial'], 2, '.', ','); ?></td>
<td><?php echo $simbolo.number_format($reg[$i]['ingresos'], 2, '.', ','); ?></td>
<td><?php echo $simbolo.number_format($reg[$i]['egresos'], 2, '.', ','); ?></td>
<td><?php echo $simbolo.number_format($reg[$i]['creditos'], 2, '.', ','); ?></td>
<td><?php echo $simbolo.number_format($reg[$i]['abonos'], 2, '.', ','); ?></td>
<td><?php echo $simbolo.number_format($reg[$i]['propinas'], 2, '.', ','); ?></td>
<td><?php echo $simbolo.number_format($reg[$i]['ingresos']+$reg[$i]['creditos'], 2, '.', ','); ?></td>
<td><?php echo $simbolo.number_format($reg[$i]['montoinicial']+$reg[$i]['ingresos']+$reg[$i]['propinas']+$reg[$i]['abonos']-$reg[$i]['egresos'], 2, '.', ','); ?></td>
<td><?php echo $simbolo.number_format($reg[$i]['dineroefectivo'], 2, '.', ','); ?></td>
                                </tr>
                        <?php  }  ?>
                              </tbody>
                          </table>
                      </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- End Row -->

<?php
  
   }
 } 
######################## BUSQUEDA ARQUEOS DE CAJAS POR FECHAS ########################
?>









<?php
###################### MOSTRAR MOVIMIENTO EN CAJA EN VENTANA MODAL #######################
if (isset($_GET['BuscaMovimientoModal']) && isset($_GET['codmovimiento'])) { 

$reg = $new->MovimientosPorId();

  ?>
  
  <table class="table-responsive" border="0" align="center">
  <tr>
    <td><strong>Nombre de Caja:</strong> <?php echo $reg[0]['nrocaja'].": ".$reg[0]['nomcaja']; ?></td>
  </tr>
  <tr>
    <td><strong>Tipo de Movimiento:</strong> <?php echo $reg[0]['tipomovimiento']; ?></td>
  </tr>
  <tr>
    <td><strong>Descripción de Movimiento:</strong> <?php echo $reg[0]['descripcionmovimiento']; ?></td>
  </tr>
  <tr>
    <td><strong>Monto de Movimiento:</strong> <?php echo $simbolo.number_format($reg[0]['montomovimiento'], 2, '.', ','); ?></td>
    </tr>
  <tr>
    <td><strong>Tipo de Pago:</strong> <?php echo $reg[0]['mediopago']; ?></td>
  </tr>
  <tr>
    <td><strong>Hora Cierre:</strong> <?php echo date("d-m-Y h:i:s",strtotime($reg[0]['fechamovimiento'])); ?></td>
  </tr>
  <tr>
    <td><strong>Responsable:</strong> <?php echo $reg[0]['dni'].": ".$reg[0]['nombres']; ?></td>
  </tr>
</table>
  
  <?php
   } 
###################### MOSTRAR MOVIMIENTO EN CAJA EN VENTANA MODAL ######################
?>




<?php
######################## BUSQUEDA MOVIMIENTOS DE CAJA POR FECHAS ########################
if (isset($_GET['BuscaMovimientosxFechas']) && isset($_GET['codcaja']) && isset($_GET['desde']) && isset($_GET['hasta'])) {
  
  $codcaja = limpiar($_GET['codcaja']);
  $desde = limpiar($_GET['desde']);
  $hasta = limpiar($_GET['hasta']);

 if($codcaja=="") {

   echo "<div class='alert alert-danger'>";
   echo "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>";
   echo "<center><span class='fa fa-info-circle'></span> POR FAVOR SELECCIONE CAJA PARA TU BÚSQUEDA</center>";
   echo "</div>";   
   exit;

} else if($desde=="") {

  echo "<div class='alert alert-danger'>";
  echo "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>";
  echo "<center><span class='fa fa-info-circle'></span> POR FAVOR INGRESE FECHA DE INICIO PARA TU BÚSQUEDA</center>";
  echo "</div>"; 
  exit;


} else if($hasta=="") {

  echo "<div class='alert alert-danger'>";
  echo "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>";
  echo "<center><span class='fa fa-info-circle'></span> POR FAVOR INGRESE FECHA FINAL PARA TU BÚSQUEDA</center>";
  echo "</div>"; 
  exit;

} elseif (strtotime($desde) > strtotime($hasta)) {

  echo "<div class='alert alert-danger'>";
  echo "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>";
  echo "<center><span class='fa fa-info-circle'></span> LA FECHA DE INICIO NO PUEDE SER MAYOR QUE LA FECHA DE FIN</center>";
  echo "</div>"; 
  exit;

} else {

$pre = new Login();
$reg = $pre->BuscarMovimientosxFechas();
  ?>

<!-- Row -->
 <div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header bg-warning">
        <h4 class="card-title text-white"><i class="fa fa-tasks"></i> Movimientos en Cajas por Fechas Desde <?php echo date("d-m-Y", strtotime($desde)); ?> Hasta <?php echo date("d-m-Y", strtotime($hasta)); ?></h4>
      </div>

      <div class="form-body">
        <div class="card-body">

          <div class="row">
            <div class="col-md-7">
              <div class="btn-group m-b-20">
              <a class="btn waves-effect waves-light btn-light" href="reportepdf?codcaja=<?php echo $codcaja; ?>&desde=<?php echo $desde; ?>&hasta=<?php echo $hasta; ?>&tipo=<?php echo encrypt("MOVIMIENTOSXFECHAS") ?>" target="_blank" rel="noopener noreferrer" data-toggle="tooltip" data-placement="bottom" title="Exportar Pdf"><span class="fa fa-file-pdf-o text-dark"></span> Pdf</a>

              <a class="btn waves-effect waves-light btn-light" href="reporteexcel?codcaja=<?php echo $codcaja; ?>&desde=<?php echo $desde; ?>&hasta=<?php echo $hasta; ?>&documento=<?php echo encrypt("EXCEL") ?>&tipo=<?php echo encrypt("MOVIMIENTOSXFECHAS") ?>" data-toggle="tooltip" data-placement="bottom" title="Exportar Excel"><span class="fa fa-file-excel-o text-dark"></span> Excel</a>

              <a class="btn waves-effect waves-light btn-light" href="reporteexcel?codcaja=<?php echo $codcaja; ?>&desde=<?php echo $desde; ?>&hasta=<?php echo $hasta; ?>&documento=<?php echo encrypt("WORD") ?>&tipo=<?php echo encrypt("MOVIMIENTOSXFECHAS") ?>" data-toggle="tooltip" data-placement="bottom" title="Exportar Word"><span class="fa fa-file-word-o text-dark"></span> Word</a>
              </div>
            </div>
          </div>

  <div id="div2"><table id="datatable-scroller" class="table table-hover table-striped table-bordered nowrap" cellspacing="0" width="100%">
                              <thead>
                                <tr class="text-center">
                                  <th>Nº</th>
                                  <th>Nº de Caja</th>
                                  <th>Responsable</th>
                                  <th>Tipo Movimiento</th>
                                  <th>Descripción</th>
                                  <th>Monto</th>
                                  <th>Forma de Movimiento</th>
                                  <th>Fecha Movimiento</th>
                                </tr>
                              </thead>
                              <tbody>
<?php
$a=1;
for($i=0;$i<sizeof($reg);$i++){
?>
                                <tr class="text-center">
                    <td><?php echo $a++; ?></td>
              <td><?php echo $reg[$i]['nrocaja'].": ".$reg[$i]['nomcaja']; ?></td>
              <td><?php echo $reg[$i]['nombres']; ?></td>
<td><?php echo $status = ( $reg[$i]['tipomovimiento'] == 'INGRESO' ? "<span class='badge badge-pill badge-info'><i class='fa fa-check'></i> ".$reg[$i]['tipomovimiento']."</span>" : "<span class='badge badge-pill badge-danger'><i class='fa fa-times'></i> ".$reg[$i]['tipomovimiento']."</span>"); ?></td>
<td><?php echo $reg[$i]['descripcionmovimiento']; ?></td>
<td><?php echo $simbolo.number_format($reg[$i]['montomovimiento'], 2, '.', ','); ?></td>
              <td><?php echo $reg[$i]['mediopago']; ?></td>
              <td><?php echo date("d-m-Y",strtotime($reg[$i]['fechamovimiento'])); ?></td>
                                </tr>
                        <?php  }  ?>
                              </tbody>
                          </table>
                      </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- End Row -->

<?php
  
   }
 } 
######################## BUSQUEDA MOVIMIENTOS DE CAJAS POR FECHAS ########################
?>






































<?php 
######################## MOSTRAR PEDIDOS POR MESAS PARA VENTAS ########################
if (isset($_GET['BuscaMesaReservada']) && isset($_GET['codmesa'])) {

$detalle = new Login();
$detalle = $detalle->VerificaMesa(); 

$arqueo = new Login();
$arqueo = $arqueo->ArqueoCajaPorUsuario();
?>

        <h4 class="text-danger"><strong><?php echo $detalle[0]['nomsala']; ?></strong></h4> 
        <h4 class="text-danger"><strong><?php echo $detalle[0]['nommesa']; ?></strong></h4>
        <input type="hidden" name="mesa" id="mesa" value="<?php echo encrypt($detalle[0]['codmesa']); ?>">
        <input type="hidden" name="codmesa" id="codmesa" value="<?php echo $detalle[0]['codmesa']; ?>">
        <input type="hidden" name="nombremesa" id="nombremesa" value="<?php echo $detalle[0]['nommesa']; ?>">
        <input type="hidden" name="codpedido" id="codpedido" value="<?php echo $detalle[0]['codpedido']; ?>">
        <hr>
        <input type="hidden" name="codproducto" id="codproducto">
        <input type="hidden" name="producto" id="producto">
        <input type="hidden" name="codcategoria" id="codcategoria">
        <input type="hidden" name="categorias" id="categorias">
        <input type="hidden" name="precioventa" id="precioventa">
        <input type="hidden" name="preciocompra" id="preciocompra"> 
        <input type="hidden" name="precioconiva" id="precioconiva">
        <input type="hidden" name="ivaproducto" id="ivaproducto">
        <input type="hidden" name="descproducto" id="descproducto">
        <input type="hidden" name="cantidad" id="cantidad" value="1">
        <input type="hidden" name="existencia" id="existencia">

        <div class="row">
            <div class="col-md-10">
                <div class="form-group has-feedback">
                    <label class="control-label">Búsqueda de Clientes: </label>
                    <input type="hidden" name="codcliente" id="codcliente" <?php if (isset($detalle[0]['codpedido'])) { ?> value="<?php echo $detalle[0]['codcliente']; ?>" <?php } else { ?> value="0" <?php } ?>>
                    <input type="text" class="form-control" name="busqueda" id="busqueda" onKeyUp="this.value=this.value.toUpperCase();" placeholder="Ingrese Criterio para la Búsqueda del Cliente" <?php if (isset($detalle[0]['codpedido'])) { ?> 
                        value="<?php echo $detalle[0]['codcliente'] == '0' ? "CONSUMIDOR FINAL" : $detalle[0]['documento'].": ".$detalle[0]['dnicliente'].": ".$detalle[0]['nomcliente']; ?>" <?php } ?> autocomplete="off"/>
                  <i class="fa fa-pencil form-control-feedback"></i>
                </div>
            </div>  

            <div class="col-md-2"> 
                <div class="form-group has-feedback"> 
                  <label class="control-label"></label><br>
                  <button type="button" class="btn btn-info waves-effect waves-light" data-placement="left" title="Nuevo Cliente" data-original-title="" data-href="#" data-toggle="modal" data-target="#myModalCliente" data-backdrop="static" data-keyboard="false"><i class="fa fa-user-plus"></i></button> 
                </div> 
            </div>    
        </div>

        <div class="table-responsive m-t-10">
            <table id="carrito" class="table table-hover">
                <thead>
                    <tr class="text-center">
                        <th>Cantidad</th>
                        <th>Descripción</th>
                        <th>Precio</th>
                        <th>Importe</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="text-center" colspan=5><h4>NO HAY DETALLES AGREGADOS</h4></td>
                    </tr>
                </tbody>
            </table> 
        </div>

        <hr>

        <div class="table-responsive">
          <table id="carritototal" width="100%">
            <tr>
              <td><h5 class="text-left"><label>TOTAL A CONFIRMAR:</label></h5></td>
              <td><h5 class="text-right"> <?php echo $simbolo; ?><label id="lbltotal" name="lbltotal">0.00</label></h5></td>
              <input type="hidden" name="txtsubtotal" id="txtsubtotal" value="0.00"/>
              <input type="hidden" name="txtsubtotal2" id="txtsubtotal2" value="0.00"/>
              <input type="hidden" name="iva" id="iva" value="<?php echo $valor == '' ? "0.00" : $imp[0]['valorimpuesto']; ?>">
              <input type="hidden" name="txtIva" id="txtIva" value="0.00"/>
              <input type="hidden" name="descuento" id="descuento" value="<?php echo $con[0]['descuentoglobal'] ?>">
              <input type="hidden" name="txtDescuento" id="txtDescuento" value="0.00"/>
              <input type="hidden" name="txtTotal" id="txtTotal" value="0.00"/>
              <input type="hidden" name="txtTotalCompra" id="txtTotalCompra" value="0.00"/>
            </tr>
          </table>
        </div>

        <div class="row">
          <div class="col-md-12"> 
            <div class="form-group has-feedback2"> 
              <label class="control-label">OBSERVACIONES DE PEDIDO: </label> 
              <textarea class="form-control" type="text" name="observacionespedido" id="observacionespedido" onKeyUp="this.value=this.value.toUpperCase();" autocomplete="off" placeholder="Ingrese Observaciones de Pedido"></textarea>
              <i class="fa fa-comment-o form-control-feedback2"></i> 
            </div> 
          </div>
        </div>


      <div class="pull-left">
<input id="boton" onClick="mostrar();" style="cursor: pointer;" class="btn btn-info waves-effect waves-light" value="Ver Mesas" type="button"><div id="remision" style="display: none;"></div>
      </div>

      <div class="text-right">
<button type="submit" name="btn-agregar" id="btn-agregar" class="btn btn-warning"><span class="fa fa-plus"></span> Agregar</button>
<button type="button" id="vaciar" class="btn btn-dark"><span class="fa fa-trash-o"></span> Limpiar</button>
      </div>

    

  <div id="muestradetalles">

  	<!-- sample modal content -->
<div id="myModalPago" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning">
       <?php if($arqueo[0]["codcaja"]==""){ ?>
             <h4 class="modal-title text-center text-white" id="myModalLabel">POR FAVOR DEBE DE REALIZAR EL ARQUEO DE CAJA ASIGNADA PARA COBRO DE VENTAS</h4>
        <?php } else { ?>
               <h4 class="modal-title text-white" id="myModalLabel"><i class="mdi mdi-desktop-mac"></i> Caja Nº: <?php echo $arqueo[0]["nrocaja"].":".$arqueo[0]["nomcaja"]; ?></h4>
                <input type="hidden" name="codcaja" id="codcaja" value="<?php echo $arqueo[0]["codcaja"]; ?>">
        <?php } ?>
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><img src="assets/images/close.png"/></button>
            </div>

            <div class="modal-body">

            <div id="cierremesa"></div>
    
                <div class="row">
                    <div class="col-md-4">
                       <h4 class="mb-0 font-light">Total a Pagar</h4>
                       <h3 class="mb-0 font-medium"><?php echo $simbolo; ?><label id="TextImporte" name="TextImporte"><?php echo $detalle[0]['totalpago'] ?></label></h3>
                    </div>

                    <div class="col-md-4">
                       <h4 class="mb-0 font-light">Total Recibido</h4>
                       <h3 class="mb-0 font-medium"><?php echo $simbolo; ?><label id="TextPagado" name="TextPagado">0.00</label></h3>
                    </div>

                    <div class="col-md-4">
                       <h4 class="mb-0 font-light">Total Cambio</h4>
                       <h3 class="mb-0 font-medium"><?php echo $simbolo; ?><label id="TextCambio" name="TextCambio">0.00</label></h3>
                    </div>
                </div>
               
                <div class="row">
                    <div class="col-md-8">
                       <h4 class="mb-0 font-light">Nombre del Cliente</h4>
                       <h4 class="mb-0 font-medium"> <label id="TextCliente" name="TextCliente"><?php echo $detalle[0]['codcliente'] == '0' ? "CONSUMIDOR FINAL" : $detalle[0]['nomcliente']; ?></label></h4>
                    </div>

                    <div class="col-md-4">
                       <h4 class="mb-0 font-light">Limite de Crédito</h4>
                       <h4 class="mb-0 font-medium"><?php echo $simbolo; ?><label id="TextCredito" name="TextCredito">0.00</label></h4>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label">Tipo de Documento: <span class="symbol required"></span></label><br>
                                
                              <div class="custom-control custom-radio">
                                <input type="radio" class="custom-control-input" id="ticket" name="tipodocumento" value="TICKET"  checked="checked">
                                <label class="custom-control-label" for="ticket">TICKET</label>
                              </div>

                              <div class="custom-control custom-radio">
                                <input type="radio" class="custom-control-input" id="factura" name="tipodocumento" value="FACTURA">
                                <label class="custom-control-label" for="factura">FACTURA</label>
                              </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label">Condición de Pago: <span class="symbol required"></span></label>
                            <div class="custom-control custom-radio">
                            <input type="radio" class="custom-control-input" id="contado" name="tipopago" value="CONTADO" onClick="CargaCondicionesPagosVentas()" value="CONTADO" checked="checked">
                            <label class="custom-control-label" for="contado">CONTADO</label>
                            </div>

                            <div class="custom-control custom-radio">
                            <input type="radio" class="custom-control-input" id="credito" name="tipopago" value="CREDITO" onClick="CargaCondicionesPagosVentas()">
                            <label class="custom-control-label" for="credito">CRÉDITO</label>
                            </div>
                        </div>
                    </div>
                </div>

            <div id="condiciones">

                <div class="row">
                    <div class="col-md-6"> 
                        <div class="form-group has-feedback"> 
                            <label class="control-label">Forma de Pago: <span class="symbol required"></span></label>
                            <i class="fa fa-bars form-control-feedback"></i>
                            <select name="codmediopago" id="codmediopago" class="form-control" required="" aria-required="true">
                            <option value=""> -- SELECCIONE -- </option>
                            <?php
                            $pago = new Login();
                            $pago = $pago->ListarMediosPagos();
                            for($i=0;$i<sizeof($pago);$i++){ ?>
                            <option value="<?php echo encrypt($pago[$i]['codmediopago']); ?>"<?php if (!(strcmp('1', $pago[$i]['codmediopago']))) {echo "selected=\"selected\"";} ?>><?php echo $pago[$i]['mediopago'] ?></option>       
                            <?php } ?>
                            </select>
                        </div> 
                    </div>

                    <div class="col-md-6"> 
                        <div class="form-group has-feedback"> 
                           <label class="control-label">Monto Recibido: <span class="symbol required"></span></label>
                           <input type="hidden" name="montodevuelto" id="montodevuelto" value="0.00">
                           <input class="form-control" type="text" name="montopagado" id="montopagado" autocomplete="off" placeholder="Monto Recibido" value="<?php echo $detalle[0]['totalpago'] ?>" onKeyUp="DevolucionVenta();" required="" aria-required="true"> 
                           <i class="fa fa-tint form-control-feedback"></i>
                        </div> 
                    </div>
                </div>
            </div>

                <div class="row">
                    <div class="col-md-6"> 
                        <div class="form-group has-feedback"> 
                           <label class="control-label">Propina Recibida: <span class="symbol required"></span></label>
                           <input class="form-control" type="text" name="montopropina" id="montopropina" autocomplete="off" placeholder="Propina Recibido" value="0.00" onKeyUp="DevolucionVenta();" required="" aria-required="true"> 
                           <i class="fa fa-tint form-control-feedback"></i>
                        </div> 
                    </div>

                    <div class="col-md-6">
                        <div class="form-group has-feedback">
                          <label class="control-label">Observaciones: </label>
                          <input type="text" class="form-control" name="observaciones" id="observaciones" onKeyUp="this.value=this.value.toUpperCase();" placeholder="Ingrese Observaciones en Venta" autocomplete="off"/> <i class="fa fa-comments form-control-feedback"></i>
                        </div>
                    </div>
                </div> 
            </div>

            <div class="modal-footer">
                <button type="submit" name="btn-cerrar" id="btn-cerrar" class="btn btn-primary"><span class="fa fa-print"></span> Facturar e Imprimir</button>
                <button class="btn btn-dark" type="reset" class="close" data-dismiss="modal" aria-hidden="true"><span class="fa fa-trash-o"></span> Cancelar</button>
            </div>

        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal --> 

    <hr>

  <div class="table-responsive">
    <div id="div">
        <table class="table table-striped display">
            <thead>
            <tr>
            <th style="background:#f0ad4e;" colspan=4><div class="text-center text-white">PRODUCTOS AGREGADOS</div></th>
            </tr>
            </thead>
            <tbody>
 <?php 
for($i=0;$i<sizeof($detalle);$i++){
?>
            <tr>
            <td><?php echo $detalle[$i]['cantventa'] ?></td>
            <td><?php echo $detalle[$i]['producto'] ?></td>
            <td><?php echo $simbolo.$detalle[$i]['valorneto'] ?></td>
<td><button type="button" class="btn btn-dark btn-sm" style="cursor:pointer;" data-toggle="tooltip" data-placement="left" title="" data-original-title="Eliminar Detalle de Venta" onClick="EliminaDetallePedido('<?php echo encrypt($detalle[0]["codmesa"]) ?>','<?php echo encrypt($detalle[$i]["codpedido"]) ?>','<?php echo encrypt($detalle[$i]["pedido"]) ?>','<?php echo encrypt($detalle[0]["codcliente"]) ?>','<?php echo encrypt($detalle[$i]["codproducto"]) ?>','<?php echo encrypt($detalle[$i]["cantventa"]) ?>','<?php echo encrypt("ELIMINADETALLEPEDIDOMESA") ?>')"><i class="fa fa-trash-o"></i></button></td>
            </tr>
          <?php } ?>
           </tbody>
        </table>  
      </div></br>  

      <table id="carritototal" class="table-responsive">
                <tr>
    <td width="250">
    <h6 class="text-right"><label>Gravado <?php echo $detalle[0]['iva'] ?>%:</label></h6>
    </td>
                  
    <td width="250">
    <h6 class="text-right"><?php echo $simbolo; ?><label><?php echo $detalle[0]['subtotalivasi'] ?></label></h6>
    </td>

    <td width="250">
    <h6 class="text-right"><label>Exento 0%:</label></h6>
    </td>
    
    <td width="250">
    <h6 class="text-right"><?php echo $simbolo; ?><label><?php echo $detalle[0]['subtotalivano'] ?></label></h6>
    </td>
                </tr>
                <tr>
    <td>
    <h6 class="text-right"><label><?php echo $impuesto == '' ? "Impuesto" : $imp[0]['nomimpuesto']; ?> <?php echo $detalle[0]['iva'] ?>%:</label></h6>
    </td>
    
    <td>
    <h6 class="text-right"><?php echo $simbolo; ?><label><?php echo $detalle[0]['totaliva'] ?></label></h6>
    </td>

    <td>
    <h6 class="text-right"><label>Desc. <?php echo $detalle[0]['descuento'] ?>%:</label></h6>
    </td>

    <td>
    <h6 class="text-right"><?php echo $simbolo; ?><label><?php echo $detalle[0]['totaldescuento'] ?></label></h6>
    </td>      </tr>
               <tr>
    <td colspan="2">
    <h5><label class="text-right">TOTAL A PAGAR:</label></h5>
    </td>
    <td colspan="2">
    <h5 class="text-right"> <?php echo $simbolo; ?><label> <?php echo $detalle[0]['totalpago'] ?></label></h5>
    <input type="hidden" name="PagoGeneral" id="PagoGeneral" value="<?php echo $detalle[0]['totalpago'] ?>"/>
    </td>
                </tr>
            </table>
        </div> 

      <div class="text-right">
<a href="reportepdf?codpedido=<?php echo encrypt($detalle[0]['codpedido']); ?>&tipo=<?php echo encrypt("PRECUENTA") ?>" target="_blank" rel="noopener noreferrer"><button type="button" class="btn btn-info waves-effect waves-light" title="Imprimir Precuenta"><i class="fa fa-print"></i> Precuenta</button></a>

<?php if ($_SESSION['acceso'] == "administrador" || $_SESSION["acceso"]=="cajero") { ?>

<button type="button" class="btn btn-warning waves-effect waves-light" data-placement="left" title="Cobrar Venta" data-original-title="" data-href="#" data-toggle="modal" data-target="#myModalPago"><span class="fa fa-calculator"></span> Pagar</button>

<?php } ?>

<button type="button" class="btn btn-dark waves-effect waves-light" onClick="CancelarPedido('<?php echo encrypt($detalle[0]["codpedido"]) ?>','<?php echo encrypt($detalle[0]["codmesa"]) ?>','<?php echo encrypt("CANCELARPEDIDOMESA") ?>')" title="Cancelar Pedido" ><i class="fa fa-user-times"></i> Cancelar</button>

      </div> 

      </div>     
<?php  
  }
######################## MOSTRAR PEDIDOS POR MESAS PARA VENTAS ########################
?>

<?php
################### MUESTRA DETALLES DE PEDIDOS EN MESA ########################
if (isset($_GET['CargaDetallesPedido']) && isset($_GET['codmesa'])) {

$detalle = new Login();
$detalle = $detalle->DetallesPedidoMesa(); 

$arqueo = new Login();
$arqueo = $arqueo->ArqueoCajaPorUsuario();

if($detalle==""){
    
    echo "";    

} else {
?>

<!-- sample modal content -->
<div id="myModalPago" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning">
       <?php if($arqueo[0]["codcaja"]==""){ ?>
              <h4 class="modal-title text-center text-white" id="myModalLabel">POR FAVOR DEBE DE REALIZAR EL ARQUEO DE CAJA ASIGNADA PARA COBRO DE VENTAS</h4>
        <?php } else { ?>
               <h4 class="modal-title text-white" id="myModalLabel"><i class="mdi mdi-desktop-mac"></i> Caja Nº: <?php echo $arqueo[0]["nrocaja"].":".$arqueo[0]["nomcaja"]; ?></h4>
                <input type="hidden" name="codcaja" id="codcaja" value="<?php echo $arqueo[0]["codcaja"]; ?>">
        <?php } ?>
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><img src="assets/images/close.png"/></button>
            </div>

            <div class="modal-body">

            <div id="cierremesa"></div>
    
                <div class="row">
                    <div class="col-md-4">
                       <h4 class="mb-0 font-light">Total a Pagar</h4>
                       <h3 class="mb-0 font-medium"><?php echo $simbolo; ?><label id="TextImporte" name="TextImporte"><?php echo $detalle[0]['totalpago'] ?></label></h3>
                    </div>

                    <div class="col-md-4">
                       <h4 class="mb-0 font-light">Total Recibido</h4>
                       <h3 class="mb-0 font-medium"><?php echo $simbolo; ?><label id="TextPagado" name="TextPagado">0.00</label></h3>
                    </div>

                    <div class="col-md-4">
                       <h4 class="mb-0 font-light">Total Cambio</h4>
                       <h3 class="mb-0 font-medium"><?php echo $simbolo; ?><label id="TextCambio" name="TextCambio">0.00</label></h3>
                    </div>
                </div>
               
                <div class="row">
                    <div class="col-md-8">
                       <h4 class="mb-0 font-light">Nombre del Cliente</h4>
                       <h4 class="mb-0 font-medium"> <label id="TextCliente" name="TextCliente"><?php echo $detalle[0]['codcliente'] == '0' ? "CONSUMIDOR FINAL" : $detalle[0]['nomcliente']; ?></label></h4>
                    </div>

                    <div class="col-md-4">
                       <h4 class="mb-0 font-light">Limite de Crédito</h4>
                       <h4 class="mb-0 font-medium"><?php echo $simbolo; ?><label id="TextCredito" name="TextCredito">0.00</label></h4>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label">Tipo de Documento: <span class="symbol required"></span></label><br>
                                
                              <div class="custom-control custom-radio">
                                <input type="radio" class="custom-control-input" id="ticket" name="tipodocumento" value="TICKET"  checked="checked">
                                <label class="custom-control-label" for="ticket">TICKET</label>
                              </div>

                              <div class="custom-control custom-radio">
                                <input type="radio" class="custom-control-input" id="factura" name="tipodocumento" value="FACTURA">
                                <label class="custom-control-label" for="factura">FACTURA</label>
                              </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label">Condición de Pago: <span class="symbol required"></span></label>
                            <div class="custom-control custom-radio">
                            <input type="radio" class="custom-control-input" id="contado" name="tipopago" value="CONTADO" onClick="CargaCondicionesPagosVentas()" value="CONTADO" checked="checked">
                            <label class="custom-control-label" for="contado">CONTADO</label>
                            </div>

                            <div class="custom-control custom-radio">
                            <input type="radio" class="custom-control-input" id="credito" name="tipopago" value="CREDITO" onClick="CargaCondicionesPagosVentas()">
                            <label class="custom-control-label" for="credito">CRÉDITO</label>
                            </div>
                        </div>
                    </div>
                </div>

            <div id="condiciones">

                <div class="row">
                    <div class="col-md-6"> 
                        <div class="form-group has-feedback"> 
                            <label class="control-label">Forma de Pago: <span class="symbol required"></span></label>
                            <i class="fa fa-bars form-control-feedback"></i>
                            <select name="codmediopago" id="codmediopago" class="form-control" required="" aria-required="true">
                            <option value=""> -- SELECCIONE -- </option>
                            <?php
                            $pago = new Login();
                            $pago = $pago->ListarMediosPagos();
                            for($i=0;$i<sizeof($pago);$i++){ ?>
                            <option value="<?php echo encrypt($pago[$i]['codmediopago']); ?>"<?php if (!(strcmp('1', $pago[$i]['codmediopago']))) {echo "selected=\"selected\"";} ?>><?php echo $pago[$i]['mediopago'] ?></option>       
                            <?php } ?>
                            </select>
                        </div> 
                    </div>

                    <div class="col-md-6"> 
                        <div class="form-group has-feedback"> 
                           <label class="control-label">Monto Recibido: <span class="symbol required"></span></label>
                           <input type="hidden" name="montodevuelto" id="montodevuelto" value="0.00">
                           <input class="form-control" type="text" name="montopagado" id="montopagado" autocomplete="off" placeholder="Monto Recibido" value="<?php echo $detalle[0]['totalpago'] ?>" onKeyUp="DevolucionVenta();" required="" aria-required="true"> 
                           <i class="fa fa-tint form-control-feedback"></i>
                        </div> 
                    </div>
                </div>
            </div>

                <div class="row">
                    <div class="col-md-6"> 
                        <div class="form-group has-feedback"> 
                           <label class="control-label">Propina Recibida: <span class="symbol required"></span></label>
                           <input class="form-control" type="text" name="montopropina" id="montopropina" autocomplete="off" placeholder="Propina Recibido" value="0.00" onKeyUp="DevolucionVenta();" required="" aria-required="true"> 
                           <i class="fa fa-tint form-control-feedback"></i>
                        </div> 
                    </div>

                    <div class="col-md-6">
                        <div class="form-group has-feedback">
                          <label class="control-label">Observaciones: </label>
                          <input type="text" class="form-control" name="observaciones" id="observaciones" onKeyUp="this.value=this.value.toUpperCase();" placeholder="Ingrese Observaciones en Venta" autocomplete="off"/> <i class="fa fa-comments form-control-feedback"></i>
                        </div>
                    </div>
                </div> 
            </div>

            <div class="modal-footer">
                <button type="submit" name="btn-cerrar" id="btn-cerrar" class="btn btn-primary"><span class="fa fa-print"></span> Facturar e Imprimir</button>
                <button class="btn btn-dark" type="reset" class="close" data-dismiss="modal" aria-hidden="true"><span class="fa fa-trash-o"></span> Cancelar</button>
            </div>

        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal --> 

<hr>

    <div class="table-responsive">
      <div id="div">
        <table class="table table-striped display">
            <thead>
            <tr>
            <th style="background:#f0ad4e;" colspan=4><div class="text-center text-white">Productos Agregados</div></th>
            </tr>
            </thead>
            <tbody>
 <?php 
for($i=0;$i<sizeof($detalle);$i++){
?>
            <tr>
            <td><?php echo $detalle[$i]['cantventa'] ?></td>
            <td><?php echo $detalle[$i]['producto'] ?></td>
            <td><?php echo $simbolo.$detalle[$i]['valorneto'] ?></td>
<td><button type="button" class="btn btn-dark btn-sm" style="cursor:pointer;" data-toggle="tooltip" data-placement="left" title="" data-original-title="Eliminar Detalle de Venta" onClick="EliminaDetallePedido('<?php echo encrypt($detalle[0]["codmesa"]) ?>','<?php echo encrypt($detalle[$i]["codpedido"]) ?>','<?php echo encrypt($detalle[$i]["pedido"]) ?>','<?php echo encrypt($detalle[0]["codcliente"]) ?>','<?php echo encrypt($detalle[$i]["codproducto"]) ?>','<?php echo encrypt($detalle[$i]["cantventa"]) ?>','<?php echo encrypt("ELIMINADETALLEPEDIDOMESA") ?>')"><i class="fa fa-trash-o"></i></button></td>
            </tr>
          <?php } ?>
           </tbody>
        </table> 
    </div></br>

      <table id="carritototal" class="table-responsive">
                <tr>
    <td width="250">
    <h6 class="text-right"><label>Gravado <?php echo $detalle[0]['iva'] ?>%:</label></h6>
    </td>
                  
    <td width="250">
    <h6 class="text-right"><?php echo $simbolo; ?><label><?php echo $detalle[0]['subtotalivasi'] ?></label></h6>
    </td>

    <td width="250">
    <h6 class="text-right"><label>Exento 0%:</label></h6>
    </td>
    
    <td width="250">
    <h6 class="text-right"><?php echo $simbolo; ?><label><?php echo $detalle[0]['subtotalivano'] ?></label></h6>
    </td>
                </tr>
                <tr>
    <td>
    <h6 class="text-right"><label><?php echo $impuesto == '' ? "Impuesto" : $imp[0]['nomimpuesto']; ?> <?php echo $detalle[0]['iva'] ?>%:</label></h6>
    </td>
    
    <td>
    <h6 class="text-right"><?php echo $simbolo; ?><label><?php echo $detalle[0]['totaliva'] ?></label></h6>
    </td>

    <td>
    <h6 class="text-right"><label>Desc. <?php echo $detalle[0]['descuento'] ?>%:</label></h6>
    </td>

    <td>
    <h6 class="text-right"><?php echo $simbolo; ?><label><?php echo $detalle[0]['totaldescuento'] ?></label></h6>
    </td>      </tr>
               <tr>
    <td colspan="2">
    <h5><label class="text-right">TOTAL A PAGAR:</label></h5>
    </td>
    <td colspan="2">
    <h5 class="text-right"> <?php echo $simbolo; ?><label> <?php echo $detalle[0]['totalpago'] ?></label></h5>
    <input type="hidden" name="PagoGeneral" id="PagoGeneral" value="<?php echo $detalle[0]['totalpago'] ?>"/>
    </td>
                </tr>
            </table>
        </div>   

      <div class="text-right">
<a href="reportepdf?codpedido=<?php echo encrypt($detalle[0]['codpedido']); ?>&tipo=<?php echo encrypt("PRECUENTA") ?>" target="_blank" rel="noopener noreferrer"><button type="button" class="btn btn-info waves-effect waves-light" title="Imprimir Precuenta"><i class="fa fa-print"></i> Precuenta</button></a>

<?php if ($_SESSION['acceso'] == "administrador" || $_SESSION["acceso"]=="cajero") { ?>

<button type="button" class="btn btn-warning waves-effect waves-light" data-placement="left" title="Cobrar Venta" data-original-title="" data-href="#" data-toggle="modal" data-target="#myModalPago"><span class="fa fa-calculator"></span> Pagar</button>

<?php } ?>

<button type="button" class="btn btn-dark waves-effect waves-light" onClick="CancelarPedido('<?php echo encrypt($detalle[0]["codpedido"]) ?>','<?php echo encrypt($detalle[0]["codmesa"]) ?>','<?php echo encrypt("CANCELARPEDIDOMESA") ?>')" title="Cancelar Pedido" ><i class="fa fa-user-times"></i> Cancelar</button>

      </div>

<?php
   }
}
######################## MUESTRA DETALLES DE PEDIDOS EN MESA ########################
?>

<?php 
######################## MUESTRA CONDICIONES DE PAGO PARA VENTAS ########################
if (isset($_GET['BuscaCondicionesPagosVentas']) && isset($_GET['tipopago'])) { 
  
$tra = new Login();

 if(limpiar($_GET['tipopago'])==""){ echo ""; 

 } elseif(limpiar($_GET['tipopago'])=="CONTADO"){  ?>

                <div class="row">
                    <div class="col-md-6"> 
                        <div class="form-group has-feedback"> 
                            <label class="control-label">Forma de Pago: <span class="symbol required"></span></label>
                            <i class="fa fa-bars form-control-feedback"></i>
                            <select name="codmediopago" id="codmediopago" class="form-control" required="" aria-required="true">
                            <option value=""> -- SELECCIONE -- </option>
                            <?php
                            $pago = new Login();
                            $pago = $pago->ListarMediosPagos();
                            for($i=0;$i<sizeof($pago);$i++){ ?>
                            <option value="<?php echo encrypt($pago[$i]['codmediopago']); ?>"<?php if (!(strcmp('1', $pago[$i]['codmediopago']))) {echo "selected=\"selected\"";} ?>><?php echo $pago[$i]['mediopago'] ?></option>       
                            <?php } ?>
                            </select>
                        </div> 
                    </div>

                    <div class="col-md-6"> 
                        <div class="form-group has-feedback"> 
                           <label class="control-label">Monto Recibido: </label>
                           <input type="hidden" name="montodevuelto" id="montodevuelto" value="0.00">
                           <input class="form-control" type="text" name="montopagado" id="montopagado" autocomplete="off" placeholder="Monto Recibido" onKeyUp="DevolucionVenta();" required="" aria-required="true">
                           <i class="fa fa-tint form-control-feedback"></i>
                        </div> 
                    </div>
                </div>
          
 <?php   } else if(limpiar($_GET['tipopago'])=="CREDITO"){  ?>

                <div class="row">
                    <div class="col-md-6"> 
                         <div class="form-group has-feedback"> 
                            <label class="control-label">Fecha Vence Crédito: <span class="symbol required"></span></label> 
                            <input type="text" class="form-control expira" name="fechavencecredito" id="fechavencecredito" onKeyUp="this.value=this.value.toUpperCase();" autocomplete="off" placeholder="Ingrese Fecha Vence Crédito" aria-required="true">
                            <i class="fa fa-calendar form-control-feedback"></i>  
                       </div> 
                    </div> 

                    <div class="col-md-6"> 
                        <div class="form-group has-feedback"> 
                           <label class="control-label">Abono Crédito: <span class="symbol required"></span></label>
                           <input class="form-control number" type="text" name="montoabono" id="montoabono" onKeyUp="this.value=this.value.toUpperCase();" onKeyPress="EvaluateText('%f', this);" onBlur="this.value = NumberFormat(this.value, '2', '.', '')" autocomplete="off" placeholder="Ingrese Monto de Abono" value="0.00" required="" aria-required="true"> 
                           <i class="fa fa-tint form-control-feedback"></i>
                        </div> 
                    </div>
                </div>
 
<?php  }
  }
######################## MUESTRA CONDICIONES DE PAGO PARA VENTAS ########################
?>


<?php 
######################## MUESTRA CONDICIONES DE PAGO PARA DELIVERY ########################
if (isset($_GET['BuscaCondicionesPagosDelivery']) && isset($_GET['tipopago'])) { 
  
$tra = new Login();

 if(limpiar($_GET['tipopago'])==""){ echo ""; 

 } elseif(limpiar($_GET['tipopago'])=="CONTADO"){  ?>

                <div class="row">
                    <div class="col-md-6"> 
                        <div class="form-group has-feedback"> 
                            <label class="control-label">Forma de Pago: <span class="symbol required"></span></label>
                            <i class="fa fa-bars form-control-feedback"></i>
                            <select name="codmediopago" id="codmediopago" class="form-control" required="" aria-required="true">
                            <option value=""> -- SELECCIONE -- </option>
                            <?php
                            $pago = new Login();
                            $pago = $pago->ListarMediosPagos();
                            for($i=0;$i<sizeof($pago);$i++){ ?>
                            <option value="<?php echo encrypt($pago[$i]['codmediopago']); ?>"<?php if (!(strcmp('1', $pago[$i]['codmediopago']))) {echo "selected=\"selected\"";} ?>><?php echo $pago[$i]['mediopago'] ?></option>       
                            <?php } ?>
                            </select>
                        </div> 
                    </div>

                    <div class="col-md-6"> 
                        <div class="form-group has-feedback"> 
                           <label class="control-label">Monto Recibido: </label>
                           <input type="hidden" name="montodevuelto" id="montodevuelto" value="0.00">
                           <input class="form-control" type="text" name="montopagado" id="montopagado" autocomplete="off" placeholder="Monto Recibido" onKeyUp="DevolucionDelivery();" required="" aria-required="true">
                           <i class="fa fa-tint form-control-feedback"></i>
                        </div> 
                    </div>
                </div>
          
 <?php   } else if(limpiar($_GET['tipopago'])=="CREDITO"){  ?>

                <div class="row">
                    <div class="col-md-6"> 
                         <div class="form-group has-feedback"> 
                            <label class="control-label">Fecha Vence Crédito: <span class="symbol required"></span></label> 
                            <input type="text" class="form-control expira" name="fechavencecredito" id="fechavencecredito" onKeyUp="this.value=this.value.toUpperCase();" autocomplete="off" placeholder="Ingrese Fecha Vence Crédito" aria-required="true">
                            <i class="fa fa-calendar form-control-feedback"></i>  
                       </div> 
                    </div> 

                    <div class="col-md-6"> 
                        <div class="form-group has-feedback"> 
                           <label class="control-label">Abono Crédito: <span class="symbol required"></span></label>
                           <input class="form-control number" type="text" name="montoabono" id="montoabono" onKeyUp="this.value=this.value.toUpperCase();" onKeyPress="EvaluateText('%f', this);" onBlur="this.value = NumberFormat(this.value, '2', '.', '')" autocomplete="off" placeholder="Ingrese Monto de Abono" value="0.00" required="" aria-required="true"> 
                           <i class="fa fa-tint form-control-feedback"></i>
                        </div> 
                    </div>
                </div>
 
<?php  }
  }
######################## MUESTRA CONDICIONES DE PAGO PARA DELIVERY ########################
?>

<?php
######################## MOSTRAR VENTAS EN VENTANA MODAL ########################
if (isset($_GET['BuscaVentaModal']) && isset($_GET['codventa'])) { 
 
$reg = $new->VentasPorId();

  if($reg==""){
    
    echo "<div class='alert alert-danger'>";
    echo "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>";
    echo "<center><span class='fa fa-info-circle'></span> NO SE ENCONTRARON VENTAS Y DETALLES ACTUALMENTE </center>";
    echo "</div>";    

} else {
?>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="pull-left">
                                        <address>
  <h3><b class="text-danger">RAZÓN SOCIAL</b></h3>
  <p class="text-muted m-l-5"><?php echo $con[0]['nomsucursal']; ?>,
  <br/> Nº <?php echo $con[0]['documsucursal'] == '0' ? "DOCUMENTO" : $con[0]['documento'] ?>: <?php echo $con[0]['cuit']; ?> - TLF: <?php echo $con[0]['tlfsucursal']; ?></p>

  <h3><b class="text-danger">Nº VENTA <?php echo $reg[0]['codventa']; ?></b></h3>
  <p class="text-muted m-l-5">Nº SERIE: <?php echo $reg[0]['codserie']; ?>

  <?php if($reg[0]['codmesa']!= '0') { ?>
  <br><?php echo $reg[0]['nomsala'].": ".$reg[0]['nommesa']; ?>
  <?php } ?>

  <br>Nº DE CAJA: <?php echo $reg[0]['nrocaja'].": ".$reg[0]['nomcaja']; ?>
  
  <?php if($reg[0]['fechavencecredito']!= "0000-00-00") { ?>
  <br>DIAS VENCIDOS: 
  <?php if($reg[0]['fechavencecredito']== '0000-00-00') { echo "0"; } 
        elseif($reg[0]['fechavencecredito'] >= date("Y-m-d") && $reg[0]['fechapagado']== "0000-00-00") { echo "0"; } 
        elseif($reg[0]['fechavencecredito'] < date("Y-m-d") && $reg[0]['fechapagado']== "0000-00-00") { echo Dias_Transcurridos(date("Y-m-d"),$reg[0]['fechavencecredito']); }
        elseif($reg[0]['fechavencecredito'] < date("Y-m-d") && $reg[0]['fechapagado']!= "0000-00-00") { echo Dias_Transcurridos($reg[0]['fechapagado'],$reg[0]['fechavencecredito']); } ?>
  <?php } ?>

  <br>STATUS: 
  <?php if($reg[0]['fechavencecredito']== '0000-00-00') { echo "<span class='badge badge-pill badge-info'><i class='fa fa-check'></i> ".$reg[0]["statusventa"]."</span>"; } 
        elseif($reg[0]['fechavencecredito'] >= date("Y-m-d") && $reg[0]['fechapagado']== "0000-00-00") { echo "<span class='badge badge-pill badge-success'><i class='fa fa-exclamation-circle'></i> ".$reg[0]["statusventa"]."</span>"; } 
        elseif($reg[0]['fechavencecredito'] < date("Y-m-d") && $reg[0]['fechapagado']== "0000-00-00") { echo "<span class='badge badge-pill badge-danger'><i class='fa fa-times'></i> VENCIDA</span>"; }
        elseif($reg[0]['fechavencecredito'] <= date("Y-m-d") && $reg[0]['fechapagado']!= "0000-00-00") { echo "<span class='badge badge-pill badge-info'><i class='fa fa-check'></i> ".$reg[0]["statusventa"]."</span>"; } ?>
  
  <?php if($reg[0]['fechapagado']!= "0000-00-00") { ?>
  <br>FECHA PAGADA: <?php echo date("d-m-Y",strtotime($reg[0]['fechapagado'])); ?>
  <?php } ?>

  <br>FECHA DE EMISIÓN: <?php echo date("d-m-Y h:i:s",strtotime($reg[0]['fechaventa'])); ?></p>
                                        </address>
                                    </div>
                                    <div class="pull-right text-right">
                                        <address>
  <h3><b class="text-danger">CLIENTE</b></h3>
  <p class="text-muted m-l-30"><?php echo $reg[0]['nomcliente'] == '' ? "CONSUMIDOR FINAL" : $reg[0]['nomcliente']; ?>,
  <br/>DIREC: <?php echo $reg[0]['direccliente'] == '' ? "*********" : $reg[0]['direccliente']; ?> <?php echo $reg[0]['provincia'] == '' ? "*********" : strtoupper($reg[0]['provincia']); ?> <?php echo $reg[0]['departamento'] == '' ? "*********" : strtoupper($reg[0]['departamento']); ?>
  <br/> EMAIL: <?php echo $reg[0]['emailcliente'] == '' ? "**********************" : $reg[0]['emailcliente']; ?>
  <br/> Nº <?php echo $reg[0]['documcliente'] == '0' ? "DOCUMENTO" : $reg[0]['documento'] ?>: <?php echo $reg[0]['dnicliente'] == '' ? "**********************" : $reg[0]['dnicliente']; ?> - TLF: <?php echo $reg[0]['tlfcliente'] == '' ? "**********************" : $reg[0]['tlfcliente']; ?></p>
                                            
                                        </address>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="table-responsive m-t-10" style="clear: both;">
                                        <table class="table table-hover">
                               <thead>
                        <tr class="text-center">
                        <th>#</th>
                        <th>Descripción de Producto</th>
                        <th>Cantidad</th>
                        <th>Precio Unit.</th>
                        <th>Valor Total</th>
                        <th>Desc %</th>
                        <th><?php echo $impuesto == '' ? "Impuesto" : $imp[0]['nomimpuesto']; ?></th>
                        <th>Valor Neto</th>
<?php if ($_SESSION['acceso'] == "administrador") { ?><th>Acción</th><?php } ?>
                                                </tr>
                                            </thead>
                                            <tbody>
<?php 
$tra = new Login();
$detalle = $tra->VerDetallesVentas();

$SubTotal = 0;
$a=1;
for($i=0;$i<sizeof($detalle);$i++){  
$SubTotal += $detalle[$i]['valorneto'];
?>
                                                <tr class="text-center">
      <td><?php echo $a++; ?></td>
      <td><h5><?php echo $detalle[$i]['producto']; ?></h5>
      <small>(<?php echo $detalle[$i]['nomcategoria']; ?>)</small></td>
      <td><?php echo $detalle[$i]['cantventa']; ?></td>
      <td><?php echo $simbolo.number_format($detalle[$i]['precioventa'], 2, '.', ','); ?></td>
      <td><?php echo $simbolo.number_format($detalle[$i]['valortotal'], 2, '.', ','); ?></td>
      <td><?php echo $simbolo.$detalle[$i]['totaldescuentov']; ?><sup><?php echo $detalle[$i]['descproducto']; ?>%</sup></td>
      <td><?php echo $detalle[$i]['ivaproducto'] == 'SI' ? $reg[0]['iva']."%" : "(E)"; ?></td>
      <td><?php echo $simbolo.number_format($detalle[$i]['valorneto'], 2, '.', ','); ?></td>
 <?php if ($_SESSION['acceso'] == "administrador") { ?><td>
<button type="button" class="btn btn-rounded btn-dark" onClick="EliminarDetallesVentaModal('<?php echo encrypt($detalle[$i]["coddetalleventa"]); ?>','<?php echo encrypt($detalle[$i]["codventa"]); ?>','<?php echo encrypt($reg[0]["codcliente"]); ?>','<?php echo encrypt("DETALLESVENTAS") ?>')" title="Eliminar" ><i class="fa fa-trash-o"></i></button></td><?php } ?>
                                                </tr>
                                      <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>


                                <div class="col-md-12">

                                    <div class="pull-right text-right">
<p><b>Subtotal:</b> <?php echo $simbolo.number_format($SubTotal, 2, '.', ','); ?></p>
<p><b>Total Grabado <?php echo $reg[0]['iva'] ?>%:</b> <?php echo $simbolo.number_format($reg[0]['subtotalivasi'], 2, '.', ','); ?><p>
<p><b>Total Exento 0%:</b> <?php echo $simbolo.number_format($reg[0]['subtotalivano'], 2, '.', ','); ?></p>
<p><b>Total <?php echo $impuesto == '' ? "Impuesto" : $imp[0]['nomimpuesto']; ?> (<?php echo $reg[0]['iva']; ?>%):</b> <?php echo $simbolo.number_format($reg[0]['totaliva'], 2, '.', ','); ?> </p>
<p><b>Desc. Global (<?php echo $reg[0]['descuento']; ?>%):</b> <?php echo $simbolo.number_format($reg[0]['totaldescuento'], 2, '.', ','); ?> </p>
                                        <hr>
<h3><b>Importe Total:</b> <?php echo $simbolo.number_format($reg[0]['totalpago'], 2, '.', ','); ?></h3></div>
                                    <div class="clearfix"></div>
                                    <hr>

                                <div class="col-md-12">
                                    <div class="text-right">
 <a href="reportepdf?codventa=<?php echo encrypt($reg[0]['codventa']); ?>&tipo=<?php echo encrypt($reg[0]['tipodocumento']) ?>" target="_blank" rel="noopener noreferrer"><button id="print" class="btn waves-light btn-light" type="button"><span><i class="fa fa-print"></i> Imprimir</span></button></a>
 <button type="button" class="btn btn-dark" data-dismiss="modal"><span class="fa fa-times-circle"></span> Cerrar</button>
                                    </div>
                                </div>
                            </div>
                <!-- .row -->
  <?php
       }
   } 
######################## MOSTRAR VENTAS EN VENTANA MODAL ########################
?>


<?php
######################## MOSTRAR DETALLES DE VENTAS UPDATE ########################
if (isset($_GET['MuestraDetallesVentasUpdate']) && isset($_GET['codventa'])) { 
 
$reg = $new->VentasPorId();

?>

<div class="table-responsive m-t-20">
            <table class="table table-hover">
                <thead>
                    <tr class="text-center">
                        <th>Cantidad</th>
                        <th>Código</th>
                        <th>Descripción de Producto</th>
                        <th>Precio Unit.</th>
                        <th>Valor Total</th>
                        <th>Desc %</th>
                        <th><?php echo $impuesto == '' ? "Impuesto" : $imp[0]['nomimpuesto']; ?></th>
                        <th>Valor Neto</th>
<?php if ($_SESSION['acceso'] == "administrador") { ?><th>Acción</th><?php } ?>
                    </tr>
                </thead>
                <tbody>
<?php 
$tra = new Login();
$detalle = $tra->VerDetallesVentas();
$a=1;
for($i=0;$i<sizeof($detalle);$i++){  
    ?>
                                 <tr class="text-center">
      <td>
      <input type="text" class="form-control" name="cantventa[]" id="cantventa_<?php echo $a; ?>" onKeyUp="this.value=this.value.toUpperCase();" autocomplete="off" placeholder="Cantidad" value="<?php echo $detalle[$i]["cantventa"]; ?>" style="width: 80px;" onfocus="this.style.background=('#B7F0FF')" onBlur="this.style.background=('#e4e7ea')" title="Ingrese Cantidad" required="" aria-required="true">
      <input type="hidden" name="cantidadventabd[]" id="cantidadventabd" value="<?php echo $detalle[$i]["cantventa"]; ?>">
      </td>
      
      <td>
      <input type="hidden" name="coddetalleventa[]" id="coddetalleventa" value="<?php echo $detalle[$i]["coddetalleventa"]; ?>">
      <input type="hidden" name="codproducto[]" id="codproducto" value="<?php echo $detalle[$i]["codproducto"]; ?>">
      <?php echo $detalle[$i]['codproducto']; ?>
      </td>

      <td><input type="hidden" name="preciocompra[]" id="preciocompra" value="<?php echo $detalle[$i]["preciocompra"]; ?>"><h5><?php echo $detalle[$i]['producto']; ?></h5><small>(<?php echo $detalle[$i]['nomcategoria'] ; ?>)</small></td>
      
      <td><input type="hidden" name="precioventa[]" id="precioventa" value="<?php echo $detalle[$i]["precioventa"]; ?>"><?php echo $simbolo.$detalle[$i]['precioventa']; ?></td>

       <td><input type="hidden" name="valortotal[]" id="valortotal" value="<?php echo $detalle[$i]["valortotal"]; ?>"><?php echo $simbolo.$detalle[$i]['valortotal']; ?></td>
      
      <td><input type="hidden" name="descproducto[]" id="descproducto" value="<?php echo $detalle[$i]["descproducto"]; ?>"><?php echo $simbolo.$detalle[$i]['totaldescuentov']; ?><sup><?php echo $detalle[$i]['descproducto']; ?>%</sup></td>

      <td><input type="hidden" name="ivaproducto[]" id="ivaproducto" value="<?php echo $detalle[$i]["ivaproducto"]; ?>"><?php echo $detalle[$i]['ivaproducto'] == 'SI' ? $reg[0]['iva']."%" : "(E)"; ?></td>

      <td><?php echo $simbolo.$detalle[$i]['valorneto']; ?></td>

 <?php if ($_SESSION['acceso'] == "administrador") { ?><td>
<button type="button" class="btn btn-rounded btn-dark" onClick="EliminarDetallesVentaUpdate('<?php echo encrypt($detalle[$i]["coddetalleventa"]); ?>','<?php echo encrypt($detalle[$i]["codventa"]); ?>','<?php echo encrypt($reg[0]["codcliente"]); ?>','<?php echo encrypt("DETALLESVENTAS") ?>')" title="Eliminar" ><i class="fa fa-trash-o"></i></button></td><?php } ?>
                                 </tr>
                     <?php } ?>
                </tbody>
            </table><hr>

             <table id="carritototal" class="table-responsive">
                <tr>
    <td width="50">&nbsp;</td>
    <td width="250">
    <h5><label>Total Gravado <?php echo $reg[0]['iva'] ?>%:</label></h5>
    </td>
                  
    <td width="250">
    <h5><?php echo $simbolo; ?><label id="lblsubtotal" name="lblsubtotal"><?php echo $reg[0]['subtotalivasi'] ?></label></h5>
    <input type="hidden" name="txtsubtotal" id="txtsubtotal" value="<?php echo $reg[0]['subtotalivasi'] ?>"/>
    </td>

    <td width="250">
    <h5><label>Total Exento 0%:</label></h5>
    </td>
    
    <td width="250">
    <h5><?php echo $simbolo; ?><label id="lblsubtotal2" name="lblsubtotal2"><?php echo $reg[0]['subtotalivano'] ?></label></h5>
    <input type="hidden" name="txtsubtotal2" id="txtsubtotal2" value="<?php echo $reg[0]['subtotalivano'] ?>"/>
    </td>

    <td class="text-center" width="250">
    <h2><b>Importe Total</b></h2>
    </td>
                </tr>
                <tr>
    <td>&nbsp;</td>
    <td>
    <h5><label><?php echo $impuesto == '' ? "Impuesto" : $imp[0]['nomimpuesto']; ?> <?php echo $reg[0]['iva'] ?>%:<input type="hidden" name="iva" id="iva" autocomplete="off" value="<?php echo $reg[0]['iva'] ?>"></label></h5>
    </td>
    
    <td>
    <h5><?php echo $simbolo; ?><label id="lbliva" name="lbliva"><?php echo $reg[0]['totaliva'] ?></label></h5>
    <input type="hidden" name="txtIva" id="txtIva" value="<?php echo $reg[0]['totaliva'] ?>"/>
    </td>

    <td>
    <h5><label>Desc. Global <input class="number" type="text" name="descuento" id="descuento" onKeyPress="EvaluateText('%f', this);" style="border-radius:4px;height:30px;width:70px;" onBlur="this.value = NumberFormat(this.value, '2', '.', '')" onKeyUp="this.value=this.value.toUpperCase();" autocomplete="off" value="<?php echo $reg[0]['descuento'] ?>">%:</label></h5>
    </td>

    <td>
    <h5><?php echo $simbolo; ?><label id="lbldescuento" name="lbldescuento"><?php echo $reg[0]['totaldescuento'] ?></label></h5>
    <input type="hidden" name="txtDescuento" id="txtDescuento" value="<?php echo $reg[0]['totaldescuento'] ?>"/>
    </td>

    <td class="text-center">
    <h2><?php echo $simbolo; ?><label id="lbltotal" name="lbltotal"><?php echo $reg[0]['totalpago'] ?></label></h2>
    <input type="hidden" name="txtTotal" id="txtTotal" value="<?php echo $reg[0]['totalpago'] ?>"/>
    <input type="hidden" name="txtTotalCompra" id="txtTotalCompra" value="0.00"/>
    </td>
                    </tr>
                  </table>
        </div>
<?php
  } 
######################## MOSTRAR DETALLES DE VENTAS UPDATE ########################
?>

<?php
######################## MOSTRAR DETALLES DE VENTAS AGREGAR ########################
if (isset($_GET['MuestraDetallesVentasAgregar']) && isset($_GET['codventa'])) { 
 
$reg = $new->VentasPorId();

?>

<div class="table-responsive m-t-20">
            <table class="table table-hover">
                <thead>
                    <tr class="text-center">
                        <th>Nº</th>
                        <th>Código</th>
                        <th>Descripción de Producto</th>
                        <th>Cantidad</th>
                        <th>Precio Unit.</th>
                        <th>Valor Total</th>
                        <th>Desc %</th>
                        <th><?php echo $impuesto == '' ? "Impuesto" : $imp[0]['nomimpuesto']; ?></th>
                        <th>Valor Neto</th>
<?php if ($_SESSION['acceso'] == "administrador") { ?><th>Acción</th><?php } ?>
                    </tr>
                </thead>
                <tbody>
<?php 
$tra = new Login();
$detalle = $tra->VerDetallesVentas();
$a=1;
for($i=0;$i<sizeof($detalle);$i++){  
    ?>
                                 <tr class="text-center">
      <td><?php echo $a++; ?></td>
      
      <td><?php echo $detalle[$i]['codproducto']; ?></td>
      
      <td><h5><?php echo $detalle[$i]['producto']; ?></h5><small>(<?php echo $detalle[$i]['nomcategoria'] ; ?>)</small></td>

      <td><?php echo $detalle[$i]['cantventa']; ?></td>
      
      <td><?php echo $simbolo.$detalle[$i]['precioventa']; ?></td>

       <td><?php echo $simbolo.$detalle[$i]['valortotal']; ?></td>
      
      <td><?php echo $simbolo.$detalle[$i]['totaldescuentov']; ?><sup><?php echo $detalle[$i]['descproducto']; ?>%</sup></td>

      <td><?php echo $detalle[$i]['ivaproducto'] == 'SI' ? $reg[0]['iva']."%" : "(E)"; ?></td>

      <td><?php echo $simbolo.$detalle[$i]['valorneto']; ?></td>

 <?php if ($_SESSION['acceso'] == "administrador") { ?><td>
<button type="button" class="btn btn-rounded btn-dark" onClick="EliminarDetallesVentaAgregar('<?php echo encrypt($detalle[$i]["coddetalleventa"]); ?>','<?php echo encrypt($detalle[$i]["codventa"]); ?>','<?php echo encrypt($reg[0]["codcliente"]); ?>','<?php echo encrypt("DETALLESVENTAS") ?>')" title="Eliminar" ><i class="fa fa-trash-o"></i></button></td><?php } ?>
                                 </tr>
                     <?php } ?>
                </tbody>
            </table>

            <table class="table-responsive">
                <tr>
    <td width="50">&nbsp;</td>
    <td width="250">
    <h5><label>Total Gravado <?php echo $reg[0]['iva'] ?>%:</label></h5>
    </td>
                  
    <td width="250">
    <h5><?php echo $simbolo; ?><label><?php echo number_format($reg[0]['subtotalivasi'], 2, '.', ','); ?></label></h5>
    </td>

    <td width="250">
    <h5><label>Total Exento 0%:</label></h5>
    </td>
    
    <td width="250">
    <h5><?php echo $simbolo; ?><label><?php echo number_format($reg[0]['subtotalivano'], 2, '.', ','); ?></label></h5>
    </td>

    <td class="text-center" width="250">
    <h2><b>Importe Total</b></h2>
    </td>
                </tr>
                <tr>
    <td>&nbsp;</td>
    <td>
    <h5><label><?php echo $impuesto == '' ? "Impuesto" : $imp[0]['nomimpuesto']; ?> <?php echo $reg[0]['iva']; ?>%:</label></h5>
    </td>
    
    <td>
    <h5><?php echo $simbolo; ?><label><?php echo number_format($reg[0]['totaliva'], 2, '.', ','); ?></label></h5>
    </td>

    <td>
    <h5><label>Desc. Global (<?php echo $reg[0]['descuento']; ?>%):</label></h5>
    </td>

    <td>
    <h5><?php echo $simbolo; ?><label><?php echo number_format($reg[0]['totaldescuento'], 2, '.', ','); ?></label></h5>
    </td>

    <td class="text-center">
    <h2><b><?php echo $simbolo; ?><label><?php echo number_format($reg[0]['totalpago'], 2, '.', ','); ?></label></b></h2>
    </td>
                    </tr>
                  </table>
           </div>
<?php
  } 
######################## MOSTRAR DETALLES DE VENTAS AGREGRAR ########################
?>


<?php
######################## BUSQUEDA VENTAS POR CAJAS ########################
if (isset($_GET['BuscaVentasxCajas']) && isset($_GET['codcaja']) && isset($_GET['desde']) && isset($_GET['hasta'])) {
  
  $codcaja = limpiar($_GET['codcaja']);
  $desde = limpiar($_GET['desde']);
  $hasta = limpiar($_GET['hasta']);

 if($codcaja=="") {

   echo "<div class='alert alert-danger'>";
   echo "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>";
   echo "<center><span class='fa fa-info-circle'></span> POR FAVOR SELECCIONE CAJA PARA TU BÚSQUEDA</center>";
   echo "</div>";   
   exit;

} else if($desde=="") {

  echo "<div class='alert alert-danger'>";
  echo "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>";
  echo "<center><span class='fa fa-info-circle'></span> POR FAVOR INGRESE FECHA DE INICIO PARA TU BÚSQUEDA</center>";
  echo "</div>"; 
  exit;


} else if($hasta=="") {

  echo "<div class='alert alert-danger'>";
  echo "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>";
  echo "<center><span class='fa fa-info-circle'></span> POR FAVOR INGRESE FECHA FINAL PARA TU BÚSQUEDA</center>";
  echo "</div>"; 
  exit;

} elseif (strtotime($desde) > strtotime($hasta)) {

  echo "<div class='alert alert-danger'>";
  echo "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>";
  echo "<center><span class='fa fa-info-circle'></span> LA FECHA DE INICIO NO PUEDE SER MAYOR QUE LA FECHA DE FIN</center>";
  echo "</div>"; 
  exit;

} else {

$pre = new Login();
$reg = $pre->BuscarVentasxCajas();
  ?>

<!-- Row -->
 <div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header bg-warning">
        <h4 class="card-title text-white"><i class="fa fa-tasks"></i> Ventas en Caja <?php echo $reg[0]['nrocaja'].": ".$reg[0]['nomcaja']; ?></h4>
      </div>

      <div class="form-body">
        <div class="card-body">

          <div class="row">
            <div class="col-md-7">
              <div class="btn-group m-b-20">
              <a class="btn waves-effect waves-light btn-light" href="reportepdf?codcaja=<?php echo $codcaja; ?>&desde=<?php echo $desde; ?>&hasta=<?php echo $hasta; ?>&tipo=<?php echo encrypt("VENTASXCAJAS") ?>" target="_blank" rel="noopener noreferrer" data-toggle="tooltip" data-placement="bottom" title="Exportar Pdf"><span class="fa fa-file-pdf-o text-dark"></span> Pdf</a>

              <a class="btn waves-effect waves-light btn-light" href="reporteexcel?codcaja=<?php echo $codcaja; ?>&desde=<?php echo $desde; ?>&hasta=<?php echo $hasta; ?>&documento=<?php echo encrypt("EXCEL") ?>&tipo=<?php echo encrypt("VENTASXCAJAS") ?>" data-toggle="tooltip" data-placement="bottom" title="Exportar Excel"><span class="fa fa-file-excel-o text-dark"></span> Excel</a>

              <a class="btn waves-effect waves-light btn-light" href="reporteexcel?codcaja=<?php echo $codcaja; ?>&desde=<?php echo $desde; ?>&hasta=<?php echo $hasta; ?>&documento=<?php echo encrypt("WORD") ?>&tipo=<?php echo encrypt("VENTASXCAJAS") ?>" data-toggle="tooltip" data-placement="bottom" title="Exportar Word"><span class="fa fa-file-word-o text-dark"></span> Word</a>
              </div>
            </div>
          </div>

          <div id="div2"><table id="datatable-scroller" class="table table-hover table-striped table-bordered nowrap" cellspacing="0" width="100%">
                              <thead>
                                <tr class="text-center">
                                  <th>Nº</th>
                                  <th>N° de Venta</th>
                                  <th>Descripción de Cliente</th>
                                  <th>Nº de Articulos</th>
                                  <th>Grab</th>
                                  <th>Exen</th>
                                  <th><?php echo $impuesto == '' ? "Impuesto" : $imp[0]['nomimpuesto']; ?></th>
                                  <th>Imp. Total</th>
                                  <th>Status</th>
                                  <th>Fecha Emisión</th>
                                  <th>Reporte</th>
                                </tr>
                              </thead>
                              <tbody>
<?php
$a=1;
for($i=0;$i<sizeof($reg);$i++){
?>
                                <tr class="text-center">
                                  <td><?php echo $a++; ?></td>
                                  <td><?php echo $reg[$i]['codventa']; ?></td>
  <td><abbr title="<?php echo $reg[$i]['codcliente'] == '0' ? "CONSUMIDOR FINAL" : "Nº ".$documento = ($reg[$i]['documcliente'] == '0' ? "DOCUMENTO" : $reg[$i]['documento']).": ".$reg[$i]['dnicliente']; ?>"><?php echo $reg[$i]['codcliente'] == '0' ? "CONSUMIDOR FINAL" : $reg[$i]['nomcliente']; ?></abbr></td>
  <td><?php echo $reg[$i]['articulos']; ?></td>
  <td><?php echo $simbolo.number_format($reg[$i]['subtotalivasi'], 2, '.', ','); ?></td>
  <td><?php echo $simbolo.number_format($reg[$i]['subtotalivano'], 2, '.', ','); ?></td>
  <td><?php echo $simbolo.number_format($reg[$i]['totaliva'], 2, '.', ','); ?><sup><?php echo $reg[$i]['iva']; ?>%</sup></td>
  <td><?php echo $simbolo.number_format($reg[$i]['totalpago'], 2, '.', ','); ?></td>
  <td><?php if($reg[$i]['fechavencecredito']== '0000-00-00') { echo "<span class='badge badge-pill badge-info'><i class='fa fa-check'></i> ".$reg[$i]["statusventa"]."</span>"; } 
      elseif($reg[$i]['fechavencecredito'] >= date("Y-m-d") && $reg[$i]['fechapagado']== "0000-00-00") { echo "<span class='badge badge-pill badge-success'><i class='fa fa-exclamation-circle'></i> ".$reg[$i]["statusventa"]."</span>"; } 
      elseif($reg[$i]['fechavencecredito'] < date("Y-m-d") && $reg[$i]['fechapagado']== "0000-00-00") { echo "<span class='badge badge-pill badge-danger'><i class='fa fa-times'></i> VENCIDA</span>"; }
      elseif($reg[$i]['fechavencecredito'] <= date("Y-m-d") && $reg[$i]['fechapagado']!= "0000-00-00") { echo "<span class='badge badge-pill badge-info'><i class='fa fa-check'></i> ".$reg[$i]["statusventa"]."</span>"; } ?></td>
  <td><?php echo date("d-m-Y h:i:s",strtotime($reg[$i]['fechaventa'])); ?></td>
  <td> <a href="reportepdf?codventa=<?php echo encrypt($reg[$i]['codventa']); ?>&tipo=<?php echo encrypt($reg[$i]['tipodocumento']) ?>" target="_blank" rel="noopener noreferrer"><button type="button" class="btn btn-rounded btn-secondary" title="Imprimir Pdf"><i class="fa fa-print"></i></button></a></td>
                                  </tr>
                        <?php  }  ?>
                              </tbody>
                          </table>
                      </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- End Row -->

<?php
  
   }
 } 
######################## BUSQUEDA VENTAS POR CAJAS ########################
?>


<?php
######################## BUSQUEDA VENTAS POR FECHAS ########################
if (isset($_GET['BuscaVentasxFechas']) && isset($_GET['desde']) && isset($_GET['hasta'])) {
  
  $desde = limpiar($_GET['desde']);
  $hasta = limpiar($_GET['hasta']);

 if($desde=="") {

  echo "<div class='alert alert-danger'>";
  echo "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>";
  echo "<center><span class='fa fa-info-circle'></span> POR FAVOR INGRESE FECHA DE INICIO PARA TU BÚSQUEDA</center>";
  echo "</div>"; 
  exit;


} else if($hasta=="") {

  echo "<div class='alert alert-danger'>";
  echo "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>";
  echo "<center><span class='fa fa-info-circle'></span> POR FAVOR INGRESE FECHA FINAL PARA TU BÚSQUEDA</center>";
  echo "</div>"; 
  exit;

} elseif (strtotime($desde) > strtotime($hasta)) {

  echo "<div class='alert alert-danger'>";
  echo "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>";
  echo "<center><span class='fa fa-info-circle'></span> LA FECHA DE INICIO NO PUEDE SER MAYOR QUE LA FECHA DE FIN</center>";
  echo "</div>"; 
  exit;

} else {

$pre = new Login();
$reg = $pre->BuscarVentasxFechas();
  ?>

<!-- Row -->
 <div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header bg-warning">
        <h4 class="card-title text-white"><i class="fa fa-tasks"></i> Ventas por Fechas Desde <?php echo date("d-m-Y", strtotime($desde)); ?> Hasta <?php echo date("d-m-Y", strtotime($hasta)); ?></h4>
      </div>

      <div class="form-body">
        <div class="card-body">

          <div class="row">
            <div class="col-md-7">
              <div class="btn-group m-b-20">
              <a class="btn waves-effect waves-light btn-light" href="reportepdf?desde=<?php echo $desde; ?>&hasta=<?php echo $hasta; ?>&tipo=<?php echo encrypt("VENTASXFECHAS") ?>" target="_blank" rel="noopener noreferrer" data-toggle="tooltip" data-placement="bottom" title="Exportar Pdf"><span class="fa fa-file-pdf-o text-dark"></span> Pdf</a>

              <a class="btn waves-effect waves-light btn-light" href="reporteexcel?desde=<?php echo $desde; ?>&hasta=<?php echo $hasta; ?>&documento=<?php echo encrypt("EXCEL") ?>&tipo=<?php echo encrypt("VENTASXFECHAS") ?>" data-toggle="tooltip" data-placement="bottom" title="Exportar Excel"><span class="fa fa-file-excel-o text-dark"></span> Excel</a>

              <a class="btn waves-effect waves-light btn-light" href="reporteexcel?desde=<?php echo $desde; ?>&hasta=<?php echo $hasta; ?>&documento=<?php echo encrypt("WORD") ?>&tipo=<?php echo encrypt("VENTASXFECHAS") ?>" data-toggle="tooltip" data-placement="bottom" title="Exportar Word"><span class="fa fa-file-word-o text-dark"></span> Word</a>
              </div>
            </div>
          </div>

          <div id="div2"><table id="datatable-scroller" class="table table-hover table-striped table-bordered nowrap" cellspacing="0" width="100%">
                              <thead>
                                <tr class="text-center">
                                  <th>Nº</th>
                                  <th>N° de Venta</th>
                                  <th>Descripción de Cliente</th>
                                  <th>Nº de Articulos</th>
                                  <th>Grab</th>
                                  <th>Exen</th>
                                  <th><?php echo $impuesto == '' ? "Impuesto" : $imp[0]['nomimpuesto']; ?></th>
                                  <th>Imp. Total</th>
                                  <th>Status</th>
                                  <th>Fecha Emisión</th>
                                  <th>Reporte</th>
                                </tr>
                              </thead>
                              <tbody>
<?php
$a=1;
for($i=0;$i<sizeof($reg);$i++){
?>
                                <tr class="text-center">
                                  <td><?php echo $a++; ?></td>
                                  <td><?php echo $reg[$i]['codventa']; ?></td>
  <td><abbr title="<?php echo $reg[$i]['codcliente'] == '0' ? "CONSUMIDOR FINAL" : "Nº ".$documento = ($reg[$i]['documcliente'] == '0' ? "DOCUMENTO" : $reg[$i]['documento']).": ".$reg[$i]['dnicliente']; ?>"><?php echo $reg[$i]['codcliente'] == '0' ? "CONSUMIDOR FINAL" : $reg[$i]['nomcliente']; ?></abbr></td>
  <td><?php echo $reg[$i]['articulos']; ?></td>
  <td><?php echo $simbolo.number_format($reg[$i]['subtotalivasi'], 2, '.', ','); ?></td>
  <td><?php echo $simbolo.number_format($reg[$i]['subtotalivano'], 2, '.', ','); ?></td>
  <td><?php echo $simbolo.number_format($reg[$i]['totaliva'], 2, '.', ','); ?><sup><?php echo $reg[$i]['iva']; ?>%</sup></td>
  <td><?php echo $simbolo.number_format($reg[$i]['totalpago'], 2, '.', ','); ?></td>
  <td><?php if($reg[$i]['fechavencecredito']== '0000-00-00') { echo "<span class='badge badge-pill badge-info'><i class='fa fa-check'></i> ".$reg[$i]["statusventa"]."</span>"; } 
      elseif($reg[$i]['fechavencecredito'] >= date("Y-m-d") && $reg[$i]['fechapagado']== "0000-00-00") { echo "<span class='badge badge-pill badge-success'><i class='fa fa-exclamation-circle'></i> ".$reg[$i]["statusventa"]."</span>"; } 
      elseif($reg[$i]['fechavencecredito'] < date("Y-m-d") && $reg[$i]['fechapagado']== "0000-00-00") { echo "<span class='badge badge-pill badge-danger'><i class='fa fa-times'></i> VENCIDA</span>"; }
      elseif($reg[$i]['fechavencecredito'] <= date("Y-m-d") && $reg[$i]['fechapagado']!= "0000-00-00") { echo "<span class='badge badge-pill badge-info'><i class='fa fa-check'></i> ".$reg[$i]["statusventa"]."</span>"; } ?></td>
  <td><?php echo date("d-m-Y h:i:s",strtotime($reg[$i]['fechaventa'])); ?></td>
  <td> <a href="reportepdf?codventa=<?php echo encrypt($reg[$i]['codventa']); ?>&tipo=<?php echo encrypt($reg[$i]['tipodocumento']) ?>" target="_blank" rel="noopener noreferrer"><button type="button" class="btn btn-rounded btn-secondary" title="Imprimir Pdf"><i class="fa fa-print"></i></button></a></td>
                                  </tr>
                        <?php  }  ?>
                              </tbody>
                          </table>
                      </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- End Row -->

<?php
  
   }
 } 
######################## BUSQUEDA VENTAS POR FECHAS ########################
?>















































<?php
######################## MOSTRAR VENTA DE CREDITO EN VENTANA MODAL #######################
if (isset($_GET['BuscaCreditoModal']) && isset($_GET['codventa'])) { 
 
$reg = $new->CreditosPorId();

?>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="pull-left">
                                        <address>
  <h3><b class="text-danger">RAZÓN SOCIAL</b></h3>
  <p class="text-muted m-l-5"><?php echo $con[0]['nomsucursal']; ?>,
  <br/> Nº <?php echo $con[0]['documsucursal'] == '0' ? "DOCUMENTO" : $con[0]['documento'] ?>: <?php echo $con[0]['cuit']; ?> - TLF: <?php echo $con[0]['tlfsucursal']; ?></p>

  <h3><b class="text-danger">Nº VENTA <?php echo $reg[0]['codventa']; ?></b></h3>
  <p class="text-muted m-l-5">Nº DE CAJA: <?php echo $reg[0]['nrocaja'].": ".$reg[0]['nomcaja']; ?>
  <br>TOTAL FACTURA: <?php echo $simbolo.number_format($reg[0]['totalpago'], 2, '.', ','); ?>
  <br>TOTAL ABONO: <?php echo $simbolo.number_format($reg[0]['abonototal'], 2, '.', ','); ?>
  <br>TOTAL DEBE: <?php echo $simbolo.number_format($reg[0]['totalpago']-$reg[0]['abonototal'], 2, '.', ','); ?>
  <br>DIAS VENCIDOS: 
  <?php if($reg[0]['fechavencecredito']== '0000-00-00') { echo "0"; } 
        elseif($reg[0]['fechavencecredito'] >= date("Y-m-d") && $reg[0]['fechapagado']== "0000-00-00") { echo "0"; } 
        elseif($reg[0]['fechavencecredito'] < date("Y-m-d") && $reg[0]['fechapagado']== "0000-00-00") { echo Dias_Transcurridos(date("Y-m-d"),$reg[0]['fechavencecredito']); }
        elseif($reg[0]['fechavencecredito'] < date("Y-m-d") && $reg[0]['fechapagado']!= "0000-00-00") { echo Dias_Transcurridos($reg[0]['fechapagado'],$reg[0]['fechavencecredito']); } ?>
  <br>STATUS: 
  <?php if($reg[0]['fechavencecredito']== '0000-00-00') { echo "<span class='badge badge-pill badge-info'><i class='fa fa-check'></i> ".$reg[0]["statusventa"]."</span>"; } 
        elseif($reg[0]['fechavencecredito'] >= date("Y-m-d") && $reg[0]['fechapagado']== "0000-00-00") { echo "<span class='badge badge-pill badge-success'><i class='fa fa-exclamation-circle'></i> ".$reg[0]["statusventa"]."</span>"; } 
        elseif($reg[0]['fechavencecredito'] < date("Y-m-d") && $reg[0]['fechapagado']== "0000-00-00") { echo "<span class='badge badge-pill badge-danger'><i class='fa fa-times'></i> VENCIDA</span>"; }
        elseif($reg[0]['fechavencecredito'] <= date("Y-m-d") && $reg[0]['fechapagado']!= "0000-00-00") { echo "<span class='badge badge-pill badge-info'><i class='fa fa-check'></i> ".$reg[0]["statusventa"]."</span>"; } ?>
  <?php if($reg[0]['fechapagado']!= "0000-00-00") { ?>
  <br>FECHA PAGADA: <?php echo date("d-m-Y",strtotime($reg[0]['fechapagado'])); ?>
  <?php } ?>
  <br>FECHA DE EMISIÓN: <?php echo date("d-m-Y h:i:s",strtotime($reg[0]['fechaventa'])); ?></p>

  <h3><b class="text-danger">CLIENTE </b></h3>
  <p class="text-muted m-l-5"><?php echo $reg[0]['nomcliente'] == '' ? "CONSUMIDOR FINAL" : $reg[0]['nomcliente']; ?>,
  <br/>DIREC: <?php echo $reg[0]['direccliente'] == '' ? "*********" : $reg[0]['direccliente']; ?> <?php echo $reg[0]['provincia'] == '' ? "*********" : strtoupper($reg[0]['provincia']); ?> <?php echo $reg[0]['departamento'] == '' ? "*********" : strtoupper($reg[0]['departamento']); ?>
  <br/> EMAIL: <?php echo $reg[0]['emailcliente'] == '' ? "**********************" : $reg[0]['emailcliente']; ?>
  <br/> Nº <?php echo $reg[0]['documcliente'] == '0' ? "DOCUMENTO" : $reg[0]['documento'] ?>: <?php echo $reg[0]['dnicliente'] == '' ? "**********************" : $reg[0]['dnicliente']; ?> - TLF: <?php echo $reg[0]['tlfcliente'] == '' ? "**********************" : $reg[0]['tlfcliente']; ?></p>


                                        </address>
                                    </div>
                                </div>
                                
                                <div class="col-md-12">
                                    <div class="table-responsive m-t-10" style="clear: both;">
                                        <table class="table table-hover">
                               <thead>
                        <tr class="text-center"><th colspan="4">Detalles de Abonos</th></tr>
                        <tr class="text-center">
                        <th>#</th>
                        <th>Nº de Caja</th>
                        <th>Monto de Abono</th>
                        <th>Fecha de Abono</th>
                        </tr>
                                            </thead>
                                            <tbody>
<?php 
$tra = new Login();
$detalle = $tra->VerDetallesAbonos();

if($detalle==""){
    
    echo "<div class='alert alert-danger'>";
    echo "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>";
    echo "<center><span class='fa fa-info-circle'></span> NO SE ENCONTRARON ABONOS ACTUALMENTE </center>";
    echo "</div>";    

} else {

$a=1;
for($i=0;$i<sizeof($detalle);$i++){  

?>
                                                <tr class="text-center">
      <td><?php echo $a++; ?></td>
      <td><?php echo $detalle[$i]['nrocaja'].": ".$detalle[$i]['nomcaja']; ?></td>
      <td><?php echo $simbolo.number_format($detalle[$i]['montoabono'], 2, '.', ','); ?></td>
      <td><?php echo date("d-m-Y h:i:s",strtotime($detalle[$i]['fechaabono'])); ?></td>
                                                </tr>
                                      <?php } } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                <hr>

                                <div class="col-md-12">
                                    <div class="text-right">
 <a href="reportepdf?codventa=<?php echo encrypt($reg[0]['codventa']); ?>&tipo=<?php echo encrypt("TICKETCREDITO") ?>" target="_blank" rel="noopener noreferrer"><button id="print" class="btn waves-light btn-light" type="button"><span><i class="fa fa-print"></i> Imprimir</span></button></a>
 <button type="button" class="btn btn-dark" data-dismiss="modal"><span class="fa fa-times-circle"></span> Cerrar</button>
                                    </div>
                                </div>
                              </div>
                <!-- .row -->
  <?php
   } 
######################## MOSTRAR VENTA DE CREDITO EN VENTANA MODAL #######################?>


<?php
######################## BUSQUEDA CREDITOS POR CLIENTES ########################
if (isset($_GET['BuscaCreditosxClientes']) && isset($_GET['codcliente'])) {
  
  $codcliente = limpiar($_GET['codcliente']);

 if($codcliente=="") {

   echo "<div class='alert alert-danger'>";
   echo "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>";
   echo "<center><span class='fa fa-info-circle'></span> POR FAVOR REALICE LA BÚSQUEDA DEL CLIENTE CORRECTAMENTE</center>";
   echo "</div>";   
   exit;

} else {

$pre = new Login();
$reg = $pre->BuscarCreditosxClientes();
  ?>

<!-- Row -->
 <div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header bg-warning">
        <h4 class="card-title text-white"><i class="fa fa-tasks"></i> Créditos del Cliente <?php echo $reg[0]['dnicliente'].": ".$reg[0]['nomcliente']; ?></h4>
      </div>

      <div class="form-body">
        <div class="card-body">

          <div class="row">
            <div class="col-md-7">
              <div class="btn-group m-b-20">
              <a class="btn waves-effect waves-light btn-light" href="reportepdf?codcliente=<?php echo $codcliente; ?>&tipo=<?php echo encrypt("CREDITOSXCLIENTES") ?>" target="_blank" rel="noopener noreferrer" data-toggle="tooltip" data-placement="bottom" title="Exportar Pdf"><span class="fa fa-file-pdf-o text-dark"></span> Pdf</a>

              <a class="btn waves-effect waves-light btn-light" href="reporteexcel?codcliente=<?php echo $codcliente; ?>&documento=<?php echo encrypt("EXCEL") ?>&tipo=<?php echo encrypt("CREDITOSXCLIENTES") ?>" data-toggle="tooltip" data-placement="bottom" title="Exportar Excel"><span class="fa fa-file-excel-o text-dark"></span> Excel</a>

              <a class="btn waves-effect waves-light btn-light" href="reporteexcel?codcliente=<?php echo $codcliente; ?>&documento=<?php echo encrypt("WORD") ?>&tipo=<?php echo encrypt("CREDITOSXCLIENTES") ?>" data-toggle="tooltip" data-placement="bottom" title="Exportar Word"><span class="fa fa-file-word-o text-dark"></span> Word</a>
              </div>
            </div>
          </div>

          <div id="div2"><table id="datatable-scroller" class="table table-hover table-striped table-bordered nowrap" cellspacing="0" width="100%">
                              <thead>
                                <tr class="text-center">
                                  <th>Nº</th>
                                  <th>N° de Venta</th>
                                  <th>Descripción de Cliente</th>
                                  <th>Imp. Total</th>
                                  <th>Total Abono</th>
                                  <th>Total Debe</th>
                                  <th>Status</th>
                                  <th>Dias Venc</th>
                                  <th>Fecha Emisión</th>
                                  <th>Reporte</th>
                                </tr>
                              </thead>
                              <tbody>
<?php
$a=1;
for($i=0;$i<sizeof($reg);$i++){
?>
                                <tr class="text-center">
                                  <td><?php echo $a++; ?></td>
                                  <td><?php echo $reg[$i]['codventa']; ?></td>
  <td><abbr title="<?php echo $reg[$i]['codcliente'] == '0' ? "CONSUMIDOR FINAL" : "Nº ".$documento = ($reg[$i]['documcliente'] == '0' ? "DOCUMENTO" : $reg[$i]['documento']).": ".$reg[$i]['dnicliente']; ?>"><?php echo $reg[$i]['codcliente'] == '0' ? "CONSUMIDOR FINAL" : $reg[$i]['nomcliente']; ?></abbr></td>
           <td><?php echo $simbolo.number_format($reg[$i]['totalpago'], 2, '.', ','); ?></td>
           <td><?php echo $simbolo.number_format($reg[$i]['abonototal'], 2, '.', ','); ?></td>
           <td><?php echo $simbolo.number_format($reg[$i]['totalpago']-$reg[$i]['abonototal'], 2, '.', ','); ?></td>
      <td><?php if($reg[$i]['fechavencecredito']== '0000-00-00') { echo "<span class='badge badge-pill badge-info'><i class='fa fa-check'></i> ".$reg[$i]["statusventa"]."</span>"; } 
      elseif($reg[$i]['fechavencecredito'] >= date("Y-m-d") && $reg[$i]['fechapagado']== "0000-00-00") { echo "<span class='badge badge-pill badge-success'><i class='fa fa-exclamation-circle'></i> ".$reg[$i]["statusventa"]."</span>"; } 
      elseif($reg[$i]['fechavencecredito'] < date("Y-m-d") && $reg[$i]['fechapagado']== "0000-00-00") { echo "<span class='badge badge-pill badge-danger'><i class='fa fa-times'></i> VENCIDA</span>"; }
      elseif($reg[$i]['fechavencecredito'] <= date("Y-m-d") && $reg[$i]['fechapagado']!= "0000-00-00") { echo "<span class='badge badge-pill badge-info'><i class='fa fa-check'></i> ".$reg[$i]["statusventa"]."</span>"; } ?></td>

<td><?php if($reg[$i]['fechavencecredito']== '0000-00-00') { echo "0"; } 
        elseif($reg[$i]['fechavencecredito'] >= date("Y-m-d") && $reg[$i]['fechapagado']== "0000-00-00") { echo "0"; } 
        elseif($reg[$i]['fechavencecredito'] < date("Y-m-d") && $reg[$i]['fechapagado']== "0000-00-00") { echo Dias_Transcurridos(date("Y-m-d"),$reg[$i]['fechavencecredito']); }
        elseif($reg[$i]['fechavencecredito'] < date("Y-m-d") && $reg[$i]['fechapagado']!= "0000-00-00") { echo Dias_Transcurridos($reg[$i]['fechapagado'],$reg[$i]['fechavencecredito']); } ?></td>
  <td><?php echo date("d-m-Y h:i:s",strtotime($reg[$i]['fechaventa'])); ?></td>
  <td> <a href="reportepdf?codventa=<?php echo encrypt($reg[$i]['codventa']); ?>&tipo=<?php echo encrypt("TICKETCREDITO"); ?>" target="_blank" rel="noopener noreferrer"><button type="button" class="btn btn-rounded btn-secondary" title="Imprimir Pdf"><i class="fa fa-print"></i></button></a></td>
                                  </tr>
                        <?php  }  ?>
                              </tbody>
                          </table>
                      </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- End Row -->

<?php
  
   }
 } 
######################## BUSQUEDA CREDITOS POR CLIENTES ########################
?>


<?php
######################## BUSQUEDA CREDITOS POR FECHAS ########################
if (isset($_GET['BuscaCreditosxFechas']) && isset($_GET['desde']) && isset($_GET['hasta'])) {
  
  $desde = limpiar($_GET['desde']);
  $hasta = limpiar($_GET['hasta']);

 if($desde=="") {

  echo "<div class='alert alert-danger'>";
  echo "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>";
  echo "<center><span class='fa fa-info-circle'></span> POR FAVOR INGRESE FECHA DE INICIO PARA TU BÚSQUEDA</center>";
  echo "</div>"; 
  exit;


} else if($hasta=="") {

  echo "<div class='alert alert-danger'>";
  echo "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>";
  echo "<center><span class='fa fa-info-circle'></span> POR FAVOR INGRESE FECHA FINAL PARA TU BÚSQUEDA</center>";
  echo "</div>"; 
  exit;

} elseif (strtotime($desde) > strtotime($hasta)) {

  echo "<div class='alert alert-danger'>";
  echo "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>";
  echo "<center><span class='fa fa-info-circle'></span> LA FECHA DE INICIO NO PUEDE SER MAYOR QUE LA FECHA DE FIN</center>";
  echo "</div>"; 
  exit;

} else {

$pre = new Login();
$reg = $pre->BuscarCreditosxFechas();
  ?>

<!-- Row -->
 <div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header bg-warning">
        <h4 class="card-title text-white"><i class="fa fa-tasks"></i> Créditos por Fechas Desde <?php echo date("d-m-Y", strtotime($desde)); ?> Hasta <?php echo date("d-m-Y", strtotime($hasta)); ?></h4>
      </div>

      <div class="form-body">
        <div class="card-body">

          <div class="row">
            <div class="col-md-7">
              <div class="btn-group m-b-20">
              <a class="btn waves-effect waves-light btn-light" href="reportepdf?desde=<?php echo $desde; ?>&hasta=<?php echo $hasta; ?>&tipo=<?php echo encrypt("CREDITOSXFECHAS") ?>" target="_blank" rel="noopener noreferrer" data-toggle="tooltip" data-placement="bottom" title="Exportar Pdf"><span class="fa fa-file-pdf-o text-dark"></span> Pdf</a>

              <a class="btn waves-effect waves-light btn-light" href="reporteexcel?desde=<?php echo $desde; ?>&hasta=<?php echo $hasta; ?>&documento=<?php echo encrypt("EXCEL") ?>&tipo=<?php echo encrypt("CREDITOSXFECHAS") ?>" data-toggle="tooltip" data-placement="bottom" title="Exportar Excel"><span class="fa fa-file-excel-o text-dark"></span> Excel</a>

              <a class="btn waves-effect waves-light btn-light" href="reporteexcel?desde=<?php echo $desde; ?>&hasta=<?php echo $hasta; ?>&documento=<?php echo encrypt("WORD") ?>&tipo=<?php echo encrypt("CREDITOSXFECHAS") ?>" data-toggle="tooltip" data-placement="bottom" title="Exportar Word"><span class="fa fa-file-word-o text-dark"></span> Word</a>
              </div>
            </div>
          </div>

          <div id="div2"><table id="datatable-scroller" class="table table-hover table-striped table-bordered nowrap" cellspacing="0" width="100%">
                              <thead>
                                <tr class="text-center">
                                  <th>Nº</th>
                                  <th>N° de Venta</th>
                                  <th>Descripción de Cliente</th>
                                  <th>Imp. Total</th>
                                  <th>Total Abono</th>
                                  <th>Total Debe</th>
                                  <th>Status</th>
                                  <th>Dias Venc</th>
                                  <th>Fecha Emisión</th>
                                  <th>Reporte</th>
                                </tr>
                              </thead>
                              <tbody>
<?php
$a=1;
for($i=0;$i<sizeof($reg);$i++){
?>
                                <tr class="text-center">
                                  <td><?php echo $a++; ?></td>
                                  <td><?php echo $reg[$i]['codventa']; ?></td>
  <td><abbr title="<?php echo $reg[$i]['codcliente'] == '0' ? "CONSUMIDOR FINAL" : "Nº ".$documento = ($reg[$i]['documcliente'] == '0' ? "DOCUMENTO" : $reg[$i]['documento']).": ".$reg[$i]['dnicliente']; ?>"><?php echo $reg[$i]['codcliente'] == '0' ? "CONSUMIDOR FINAL" : $reg[$i]['nomcliente']; ?></abbr></td>
           <td><?php echo $simbolo.number_format($reg[$i]['totalpago'], 2, '.', ','); ?></td>
           <td><?php echo $simbolo.number_format($reg[$i]['abonototal'], 2, '.', ','); ?></td>
           <td><?php echo $simbolo.number_format($reg[$i]['totalpago']-$reg[$i]['abonototal'], 2, '.', ','); ?></td>
      <td><?php if($reg[$i]['fechavencecredito']== '0000-00-00') { echo "<span class='badge badge-pill badge-info'><i class='fa fa-check'></i> ".$reg[$i]["statusventa"]."</span>"; } 
      elseif($reg[$i]['fechavencecredito'] >= date("Y-m-d") && $reg[$i]['fechapagado']== "0000-00-00") { echo "<span class='badge badge-pill badge-success'><i class='fa fa-exclamation-circle'></i> ".$reg[$i]["statusventa"]."</span>"; } 
      elseif($reg[$i]['fechavencecredito'] < date("Y-m-d") && $reg[$i]['fechapagado']== "0000-00-00") { echo "<span class='badge badge-pill badge-danger'><i class='fa fa-times'></i> VENCIDA</span>"; }
      elseif($reg[$i]['fechavencecredito'] <= date("Y-m-d") && $reg[$i]['fechapagado']!= "0000-00-00") { echo "<span class='badge badge-pill badge-info'><i class='fa fa-check'></i> ".$reg[$i]["statusventa"]."</span>"; } ?></td>

<td><?php if($reg[$i]['fechavencecredito']== '0000-00-00') { echo "0"; } 
        elseif($reg[$i]['fechavencecredito'] >= date("Y-m-d") && $reg[$i]['fechapagado']== "0000-00-00") { echo "0"; } 
        elseif($reg[$i]['fechavencecredito'] < date("Y-m-d") && $reg[$i]['fechapagado']== "0000-00-00") { echo Dias_Transcurridos(date("Y-m-d"),$reg[$i]['fechavencecredito']); }
        elseif($reg[$i]['fechavencecredito'] < date("Y-m-d") && $reg[$i]['fechapagado']!= "0000-00-00") { echo Dias_Transcurridos($reg[$i]['fechapagado'],$reg[$i]['fechavencecredito']); } ?></td>
  <td><?php echo date("d-m-Y h:i:s",strtotime($reg[$i]['fechaventa'])); ?></td>
  <td> <a href="reportepdf?codventa=<?php echo encrypt($reg[$i]['codventa']); ?>&tipo=<?php echo encrypt("TICKETCREDITO"); ?>" target="_blank" rel="noopener noreferrer"><button type="button" class="btn btn-rounded btn-secondary" title="Imprimir Pdf"><i class="fa fa-print"></i></button></a></td>
                                  </tr>
                        <?php  }  ?>
                              </tbody>
                          </table>
                      </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- End Row -->

<?php
  
   }
 } 
######################## BUSQUEDA CREDITOS POR FECHAS ########################
?>
