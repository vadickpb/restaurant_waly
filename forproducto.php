<?php
require_once("class/class.php"); 
if(isset($_SESSION['acceso'])) { 
     if ($_SESSION['acceso'] == "administrador" || $_SESSION["acceso"]=="secretaria") {

$tra = new Login();
$ses = $tra->ExpiraSession(); 

$con = new Login();
$con = $con->ConfiguracionPorId();

$imp = new Login();
$imp = $imp->ImpuestosPorId();
$impuesto = $imp[0]['nomimpuesto'];

if(isset($_POST["proceso"]) and $_POST["proceso"]=="save")
{
$reg = $tra->RegistrarProductos();
exit;
} 
elseif(isset($_POST["proceso"]) and $_POST["proceso"]=="update")
{
$reg = $tra->ActualizarProductos();
exit;
}        
?>
<!DOCTYPE html>
<html dir="ltr" lang="en">
<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Allcode">
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="assets/images/favicon.png">
    <title></title>

    <!-- Menu CSS -->
    <link href="assets/plugins/bower_components/sidebar-nav/dist/sidebar-nav.min.css" rel="stylesheet">
    <!-- toast CSS -->
    <link href="assets/plugins/bower_components/toast-master/css/jquery.toast.css" rel="stylesheet">
    <!-- Sweet-Alert -->
    <link rel="stylesheet" href="assets/css/sweetalert.css">
    <!-- animation CSS -->
    <link href="assets/css/animate.css" rel="stylesheet">
    <!-- needed css -->
    <link href="assets/css/style.css" rel="stylesheet">
    <!-- color CSS -->
    <link href="assets/css/default.css" id="theme" rel="stylesheet">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->

</head>

<body onLoad="muestraReloj()" class="fix-header">
    
   <!-- ============================================================== -->
    <!-- Preloader - style you can find in spinners.css -->
    <!-- ============================================================== -->
    <div class="preloader">
        <svg class="circular" viewBox="25 25 50 50">
        <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" />
        </svg>
    </div>

    <!-- ============================================================== -->
    <!-- Main wrapper - style you can find in pages.scss -->
    <!-- ============================================================== -->
    <div id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full" data-boxed-layout="full" data-boxed-layout="boxed" data-header-position="fixed" data-sidebar-position="fixed" class="mini-sidebar">               
                    
    
        <!-- INICIO DE MENU -->
        <?php include('menu.php'); ?>
        <!-- FIN DE MENU -->
   

        <!-- ============================================================== -->
        <!-- Page wrapper  -->
        <!-- ============================================================== -->
        <div class="page-wrapper">
            <!-- ============================================================== -->
            <!-- Bread crumb and right sidebar toggle -->
            <!-- ============================================================== -->
            <div class="page-breadcrumb border-bottom">
                <div class="row">
                    <div class="col-lg-3 col-md-4 col-xs-12 align-self-center">
        <h5 class="font-medium text-uppercase mb-0"><i class="fa fa-tasks"></i> Gestión de Productos</h5>
                    </div>
                    <div class="col-lg-9 col-md-8 col-xs-12 align-self-center">
                        <nav aria-label="breadcrumb" class="mt-2 float-md-right float-left">
                            <ol class="breadcrumb mb-0 justify-content-end p-0">
                                <li class="breadcrumb-item">Mantenimiento</li>
                                <li class="breadcrumb-item active" aria-current="page">Productos</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
            <!-- ============================================================== -->
            <!-- End Bread crumb and right sidebar toggle -->
            <!-- ============================================================== -->
            <!-- ============================================================== -->
            <!-- Container fluid  -->
            <!-- ============================================================== -->
            <div class="page-content container-fluid">
                <!-- ============================================================== -->
                <!-- Start Page Content -->
                <!-- ============================================================== -->
               
<!-- Row -->
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header bg-warning">
                <h4 class="card-title text-white"><i class="fa fa-pencil"></i> Gestión de Productos</h4>
            </div>

<?php  if (isset($_GET['codproducto'])) {
      
      $reg = $tra->ProductosPorId(); ?>
      
<form class="form form-material" method="post" action="#" name="updateproductos" id="updateproductos" data-id="<?php echo $reg[0]["codproducto"] ?>" enctype="multipart/form-data">
        
    <?php } else { ?>
        
<form class="form form-material" method="post" action="#" name="saveproductos" id="saveproductos" enctype="multipart/form-data">
      
    <?php } ?>

                <div id="save">
                   <!-- error will be shown here ! -->
               </div>

               <div class="form-body">

            <div class="card-body">

                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group has-feedback">
                            <label class="control-label">Código de Producto: <span class="symbol required"></span></label>
                            <input type="hidden" name="idproducto" id="idproducto" <?php if (isset($reg[0]['idproducto'])) { ?> value="<?php echo $reg[0]['idproducto']; ?>"<?php } ?>>
                            <input type="hidden" name="proceso" id="proceso" <?php if (isset($reg[0]['idproducto'])) { ?> value="update" <?php } else { ?> value="save" <?php } ?>/>
                            <input type="text" class="form-control" name="codproducto" id="codproducto" onKeyUp="this.value=this.value.toUpperCase();" placeholder="Ingrese Código de Producto" autocomplete="off" <?php if (isset($reg[0]['codproducto'])) { ?> value="<?php echo $reg[0]['codproducto']; ?>" readonly="readonly" <?php } else { ?><?php } ?> required="" aria-required="true"/> 
                            <i class="fa fa-bolt form-control-feedback"></i> 
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group has-feedback">
                            <label class="control-label">Nombre de Producto: <span class="symbol required"></span></label>
                            <input type="text" class="form-control" name="producto" id="producto" onKeyUp="this.value=this.value.toUpperCase();" placeholder="Ingrese Nombre de Producto" autocomplete="off" <?php if (isset($reg[0]['producto'])) { ?> value="<?php echo $reg[0]['producto']; ?>" <?php } ?> required="" aria-required="true"/>  
                            <i class="fa fa-pencil form-control-feedback"></i> 
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group has-feedback">
                            <label class="control-label">Categoria de Producto: <span class="symbol required"></span></label>
                            <i class="fa fa-bars form-control-feedback"></i>
                            <?php if (isset($reg[0]['codcategoria'])) { ?>
                            <select name="codcategoria" id="codcategoria" class='form-control' required="" aria-required="true">
                                <option value=""> -- SELECCIONE -- </option>
                                <?php
                                $categoria = new Login();
                                $categoria = $categoria->ListarCategorias();
                                for($i=0;$i<sizeof($categoria);$i++){ ?>
                                <option value="<?php echo $categoria[$i]['codcategoria'] ?>"<?php if (!(strcmp($reg[0]['codcategoria'], htmlentities($categoria[$i]['codcategoria'])))) { echo "selected=\"selected\"";} ?>><?php echo $categoria[$i]['nomcategoria'] ?></option>        
                                  <?php } ?>
                          </select>  
                             <?php } else { ?>
                          <select name="codcategoria" id="codcategoria" class='form-control' required="" aria-required="true">
                                <option value=""> -- SELECCIONE -- </option>
                                <?php
                                $categoria = new Login();
                                $categoria = $categoria->ListarCategorias();
                                for($i=0;$i<sizeof($categoria);$i++){ ?>
                                <option value="<?php echo $categoria[$i]['codcategoria'] ?>"><?php echo $categoria[$i]['nomcategoria'] ?></option>        
                                  <?php } ?>
                          </select>
                             <?php } ?>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group has-feedback">
                            <label class="control-label">Precio de Compra: <span class="symbol required"></span></label>
                            <input type="hidden" name="porcentaje" id="porcentaje" value="<?php echo $con[0]['porcentaje']; ?>">
                            <input type="text" class="form-control calculoprecio" name="preciocompra" id="preciocompra" onKeyUp="this.value=this.value.toUpperCase();" onKeyPress="EvaluateText('%f', this);" onBlur="this.value = NumberFormat(this.value, '2', '.', '')" placeholder="Ingrese Precio de Compra" autocomplete="off" <?php if (isset($reg[0]['preciocompra'])) { ?> value="<?php echo $reg[0]['preciocompra']; ?>" <?php } ?> required="" aria-required="true"/>  
                            <i class="fa fa-tint form-control-feedback"></i>
                        </div>
                    </div>
                </div>


                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group has-feedback">
                            <label class="control-label">Precio de Venta (P.V.P): <span class="symbol required"></span></label>
                            <input type="text" class="form-control" name="precioventa" id="precioventa" onKeyUp="this.value=this.value.toUpperCase();" onKeyPress="EvaluateText('%f', this);" onBlur="this.value = NumberFormat(this.value, '2', '.', '')" placeholder="Ingrese Precio de Venta" autocomplete="off" <?php if (isset($reg[0]['precioventa'])) { ?> value="<?php echo $reg[0]['precioventa']; ?>" <?php } ?>  required="" aria-required="true"/>  
                            <i class="fa fa-tint form-control-feedback"></i>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group has-feedback">
                            <label class="control-label">Existencia de Producto: <span class="symbol required"></span></label>
                            <input type="text" class="form-control" name="existencia" id="existencia" onKeyUp="this.value=this.value.toUpperCase();" onKeyPress="EvaluateText('%f', this);" onBlur="this.value = NumberFormat(this.value, '2', '.', '')" placeholder="Ingrese Existencia de Producto" autocomplete="off" <?php if (isset($reg[0]['existencia'])) { ?> value="<?php echo $reg[0]['existencia']; ?>" <?php } ?> required="" aria-required="true"/>  
                            <i class="fa fa-bolt form-control-feedback"></i>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group has-feedback">
                            <label class="control-label">Stock Minimo: <span class="symbol required"></span></label>
                             <input type="text" class="form-control" name="stockminimo" id="stockminimo" onKeyUp="this.value=this.value.toUpperCase();" onKeyPress="EvaluateText('%f', this);" onBlur="this.value = NumberFormat(this.value, '2', '.', '')" placeholder="Ingrese Stock Minimo" autocomplete="off" <?php if (isset($reg[0]['stockminimo'])) { ?> value="<?php echo $reg[0]['stockminimo']; ?>" <?php } ?> required="" aria-required="true"/>  
                            <i class="fa fa-bolt form-control-feedback"></i> 
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group has-feedback">
                            <label class="control-label">Stock Máximo: <span class="symbol required"></span></label>
                            <input type="text" class="form-control" name="stockmaximo" id="stockmaximo" onKeyUp="this.value=this.value.toUpperCase();" onKeyPress="EvaluateText('%f', this);" onBlur="this.value = NumberFormat(this.value, '2', '.', '')" placeholder="Ingrese Stock Máximo" autocomplete="off" <?php if (isset($reg[0]['stockmaximo'])) { ?> value="<?php echo $reg[0]['stockmaximo']; ?>" <?php } ?> required="" aria-required="true"/>  
                            <i class="fa fa-bolt form-control-feedback"></i>
                        </div>
                    </div>
                </div>


                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group has-feedback">
                            <label class="control-label"><?php echo $impuesto == '' ? "Impuesto" : $imp[0]['nomimpuesto']; ?> de Producto: <span class="symbol required"></span></label>
                            <i class="fa fa-bars form-control-feedback"></i>
                                <?php if (isset($reg[0]['ivaproducto'])) { ?>
                            <select name="ivaproducto" id="ivaproducto" class="form-control" required="" aria-required="true">
                                <option value=""> -- SELECCIONE -- </option>
<option value="SI"<?php if (!(strcmp('SI', $reg[0]['ivaproducto']))) {echo "selected=\"selected\"";} ?>>SI</option>
<option value="NO"<?php if (!(strcmp('NO', $reg[0]['ivaproducto']))) {echo "selected=\"selected\"";} ?>>NO</option>
                            </select>
                               <?php } else { ?>
                            <select name="ivaproducto" id="ivaproducto" class="form-control" required="" aria-required="true">
                                <option value=""> -- SELECCIONE -- </option>
                                <option value="SI">SI</option>
                                <option value="NO">NO</option>
                            </select>
                               <?php } ?>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group has-feedback">
                            <label class="control-label">Descuento de Producto: <span class="symbol required"></span></label>
                            <input type="text" class="form-control" name="descproducto" id="descproducto" onKeyUp="this.value=this.value.toUpperCase();" onKeyPress="EvaluateText('%f', this);" onBlur="this.value = NumberFormat(this.value, '2', '.', '')" placeholder="Ingrese Descuento de Producto" autocomplete="off" <?php if (isset($reg[0]['descproducto'])) { ?> value="<?php echo $reg[0]['descproducto']; ?>" <?php } ?> required="" aria-required="true"/>  
                            <i class="fa fa-tint form-control-feedback"></i>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group has-feedback">
                            <label class="control-label">Código de Barra: </label>
                             <input type="text" class="form-control" name="codigobarra" id="codigobarra" onKeyUp="this.value=this.value.toUpperCase();" placeholder="Ingrese Código de Barra" autocomplete="off" <?php if (isset($reg[0]['codigobarra'])) { ?> value="<?php echo $reg[0]['codigobarra']; ?>" <?php } ?> required="" aria-required="true"/>  
                            <i class="fa fa-barcode form-control-feedback"></i> 
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group has-feedback">
                            <label class="control-label">Nº de Lote: </label>
                            <input type="text" class="form-control" name="lote" id="lote" onKeyUp="this.value=this.value.toUpperCase();" placeholder="Ingrese Nº de Lote" autocomplete="off" <?php if (isset($reg[0]['lote'])) { ?> value="<?php echo $reg[0]['lote']; ?>" <?php } ?> required="" aria-required="true"/>  
                            <i class="fa fa-pencil form-control-feedback"></i> 
                        </div>
                    </div>
                </div>


                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group has-feedback">
                        <label class="control-label">Fecha de Elaboración: </label>
                        <input type="text" class="form-control calendario" name="fechaelaboracion" id="fechaelaboracion" onKeyUp="this.value=this.value.toUpperCase();" placeholder="Ingrese Fecha de Elaboración" autocomplete="off" <?php if (isset($reg[0]['fechaelaboracion'])) { ?> value="<?php echo $reg[0]['fechaelaboracion'] == '0000-00-00' ? "" : date("d-m-Y",strtotime($reg[0]['fechaelaboracion'])); ?>"<?php } ?> required="" aria-required="true"/>
                            <i class="fa fa-calendar form-control-feedback"></i>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group has-feedback">
                            <label class="control-label">Fecha de Expiración: </label>
                        <input type="text" class="form-control expira" name="fechaexpiracion" id="fechaexpiracion" onKeyUp="this.value=this.value.toUpperCase();" placeholder="Ingrese Fecha de Expiración" autocomplete="off" <?php if (isset($reg[0]['fechaexpiracion'])) { ?> value="<?php echo $reg[0]['fechaexpiracion'] == '0000-00-00' ? "" : date("d-m-Y",strtotime($reg[0]['fechaexpiracion'])); ?>"<?php } ?> required="" aria-required="true"/>  
                            <i class="fa fa-calendar form-control-feedback"></i>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group has-feedback">
                            <label class="control-label">Seleccione Proveedor: <span class="symbol required"></span></label>
                            <i class="fa fa-bars form-control-feedback"></i>
                                 <?php if (isset($reg[0]['codproveedor'])) { ?>
                            <select name="codproveedor" id="codproveedor" class='form-control' required="" aria-required="true">
                                <option value=""> -- SELECCIONE -- </option>
                                <?php
                                $proveedor = new Login();
                                $proveedor = $proveedor->ListarProveedores();
                                for($i=0;$i<sizeof($proveedor);$i++){ ?>
                                <option value="<?php echo $proveedor[$i]['codproveedor'] ?>"<?php if (!(strcmp($reg[0]['codproveedor'], htmlentities($proveedor[$i]['codproveedor'])))) {echo "selected=\"selected\""; } ?>><?php echo $proveedor[$i]['nomproveedor'] ?></option>        
                                  <?php } ?>
                            </select>
                              <?php } else { ?>
                            <select name="codproveedor" id="codproveedor" class='form-control' required="" aria-required="true">
                                <option value=""> -- SELECCIONE -- </option>
                                <?php
                                $proveedor = new Login();
                                $proveedor = $proveedor->ListarProveedores();
                                for($i=0;$i<sizeof($proveedor);$i++){ ?>
                                <option value="<?php echo $proveedor[$i]['codproveedor'] ?>"><?php echo $proveedor[$i]['nomproveedor'] ?></option>        
                                  <?php } ?>
                            </select>

                              <?php } ?> 
                        </div>
                    </div>


                    <div class="col-md-3">
                      <div class="fileinput fileinput-new" data-provides="fileinput">
                          <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="width: 60px; height: 60px;"><?php if (isset($reg[0]['codproducto'])) {
                            if (file_exists("fotos/productos/".$reg[0]['codproducto'].".jpg")){
                                echo "<img src='fotos/productos/".$reg[0]['codproducto'].".jpg?".date('h:i:s')."' class='img-rounded' border='1' width='60' height='60' title='Foto del Producto' data-rel='tooltip'>"; 
                            }else{
                                echo "<img src='fotos/ninguna.png' class='img-rounded' border='1' width='60' height='60' title='SIN FOTO' data-rel='tooltip'>"; 
                            } } else {
                              echo "<img src='fotos/ninguna.png' class='img-rounded' border='1' width='60' height='60' title='SIN FOTO' data-rel='tooltip'>"; 
                          }
                          ?>
                      </div>
                      <div>
                          <span class="btn btn-success btn-file">
                              <span class="fileinput-new"><i class="fa fa-file-image-o"></i> Imagen</span>
                              <span class="fileinput-exists"><i class="fa fa-paint-brush"></i> Imagen</span>
                              <input type="file" size="10" data-original-title="Subir Fotografia" data-rel="tooltip" placeholder="Suba su Fotografia" name="imagen" id="imagen"  />
                          </span>
                          <a href="#" class="btn btn-dark fileinput-exists" data-dismiss="fileinput"><i class="fa fa-times-circle"></i> Remover</a><small><p>Para Subir la Imagen debe tener en cuenta:<br> * La Imagen debe ser extension.jpg<br> * La imagen no debe ser mayor de 50 KB</p></small>                             
                      </div>
                      </div>
                  </div>
                </div>

        <?php  if (!isset($_GET['codproducto'])) { ?>

            <div class="row">

                <div class="col-md-12"> 
                    <div class="form-group has-feedback">
                    <table width="100%" id="tabla"><tr> 

    <a class="btn btn-info btn-rounded" onClick="Add()"><font color="white"><i class="fa fa-plus-circle"></i></font></a>&nbsp;
    <a class="btn btn-dark btn-rounded" onClick="Delete()"><font color="white"><i class="fa fa-trash-o"></i></font></a><br></br>

            <td>
            <div class="col-md-12">  
                <div class="form-group has-feedback"> 
                    <label class="control-label">Nombre de Ingrediente: </label>
                    <input type="hidden" name="codingrediente[]" id="codingrediente">
                    <input type="text" class="form-control" name="agregaingrediente[]" id="agregaingrediente" onKeyUp="this.value=this.value.toUpperCase(); autocompletar(this.name);" placeholder="Realice la Búsqueda de Ingrediente" autocomplete="off"/>  
                    <i class="fa fa-pencil form-control-feedback"></i>                                           
                </div>
            </div>
            </td>

            <td>
            <div class="col-md-12">  
                <div class="form-group has-feedback"> 
                    <label class="control-label">Cantidad de Porción: </label>
                    <input type="text" class="form-control" name="cantidad[]" id="cantidad1" onKeyUp="this.value=this.value.toUpperCase();" placeholder="Ingrese Cantidad de Porción" title="Ingrese Cantidad de Porción" autocomplete="off"/>  
                    <i class="fa fa-pencil form-control-feedback"></i>                                           
                </div>
            </div>
            </td>

            <td>
            <div class="col-md-12">  
                <div class="form-group has-feedback"> 
                    <label class="control-label">Unidad de Medida: </label>
                    <input type="text" class="form-control" name="medida[]" id="medida" onKeyUp="this.value=this.value.toUpperCase();" placeholder="Ingrese Unidad de Medida" autocomplete="off" readonly=""/>  
                    <i class="fa fa-pencil form-control-feedback"></i>                                           
                </div>
            </div>

            </td></tr><input type="hidden" name="var_cont">
                 </table>
               </div> 
             </div>
           </div> 

        <?php } else { ?>

         <div id="cargaingredientes"><table id="default_order" class="table table-striped table-bordered border display">
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
                                <th><center>Eliminar</center></th>
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
        </table></div>

        <?php } ?>



             <div class="text-right">
    <?php  if (isset($_GET['codproducto'])) { ?>
<button type="submit" name="btn-update" id="btn-update" class="btn btn-warning"><span class="fa fa-edit"></span> Actualizar</button>
<button class="btn btn-dark" type="reset"><span class="fa fa-trash-o"></span> Cancelar</button> 
    <?php } else { ?>
<button type="submit" name="btn-submit" id="btn-submit" class="btn btn-warning"><span class="fa fa-save"></span> Guardar</button>
<button class="btn btn-dark" type="reset"><span class="fa fa-trash-o"></span> Limpiar</button>
    <?php } ?>  
             </div>
          </div>
      </div>
    </form>
   </div>
  </div>
</div>
<!-- End Row -->


                <!-- ============================================================== -->
                <!-- End PAge Content -->
                <!-- ============================================================== -->
                <!-- ============================================================== -->
                <!-- Right sidebar -->
                <!-- ============================================================== -->
                <!-- .right-sidebar -->
                <!-- ============================================================== -->
                <!-- End Right sidebar -->
                <!-- ============================================================== -->
            </div>
            <!-- ============================================================== -->
            <!-- End Container fluid  -->
            <!-- ============================================================== -->
            <!-- ============================================================== -->
            <!-- footer -->
            <!-- ============================================================== -->
            <footer class="footer text-center">
                <i class="fa fa-copyright"></i> <span class="current-year"></span>.
            </footer>
            <!-- ============================================================== -->
            <!-- End footer -->
            <!-- ============================================================== -->
        </div>
        <!-- ============================================================== -->
        <!-- End Page wrapper  -->
        <!-- ============================================================== -->
    </div>
    <!-- ============================================================== -->
    <!-- End Wrapper -->
    <!-- ============================================================== -->
   

    <!-- ============================================================== -->
    <!-- All Jquery -->
    <!-- ============================================================== -->
    <script src="assets/script/jquery.min.js"></script> 
    <script src="assets/js/bootstrap.js"></script>
    <!-- apps -->
    <script src="assets/js/app.min.js"></script>
    <script src="assets/js/app.init.horizontal-fullwidth.js"></script>
    <script src="assets/js/app-style-switcher.js"></script>
    <!-- slimscrollbar scrollbar JavaScript -->
    <script src="assets/js/perfect-scrollbar.js"></script>
    <script src="assets/js/sparkline.js"></script>
    <!--Wave Effects -->
    <script src="assets/js/waves.js"></script>
    <!-- Sweet-Alert -->
    <script src="assets/js/sweetalert-dev.js"></script>
    <!--Menu sidebar -->
    <script src="assets/js/sidebarmenu.js"></script>
    <!--Custom JavaScript -->
    <script src="assets/js/custom.js"></script>

    <!-- Custom file upload -->
    <script src="assets/plugins/fileupload/bootstrap-fileupload.min.js"></script>

    <!-- script jquery -->
    <script type="text/javascript" src="assets/script/titulos.js"></script>
    <script type="text/javascript" src="assets/script/script2.js"></script>
    <script type="text/javascript" src="assets/script/validation.min.js"></script>
    <script type="text/javascript" src="assets/script/script.js"></script>
    <!-- script jquery -->

    <!-- Calendario -->
    <link rel="stylesheet" href="assets/calendario/jquery-ui.css" />
    <script src="assets/calendario/jquery-ui.js"></script>
    <script src="assets/script/jscalendario.js"></script>
    <script src="assets/script/autocompleto.js"></script>
    <!-- Calendario -->

    <!-- jQuery -->
    <script src="assets/plugins/noty/packaged/jquery.noty.packaged.min.js"></script>
    <!-- jQuery -->
   
   <script language='JavaScript'>
   var cont=1;

  function Add()  //Esta la funcion que agrega las filas segunda parte :
    {
    cont++;
    //autocompletar
    var indiceFila=1;
    myNewRow = document.getElementById('tabla').insertRow(-1);
    myNewRow.id=indiceFila;
    myNewCell=myNewRow.insertCell(-1);
    myNewCell.innerHTML='<div class="col-md-12"><div class="form-group has-feedback"><label class="control-label">Nombre de Ingrediente: <span class="symbol required"></span></label><input type="hidden" name="codingrediente[]'+cont+'" id="codingrediente'+cont+'"><input type="text" class="form-control" name="agregaingrediente[]'+cont+'" id="agregaingrediente'+cont+'" onKeyUp="this.value=this.value.toUpperCase(); autocompletar(this.name);" placeholder="Ingrese Nombre de Ingrediente" title="Ingrese Nombre de Ingrediente" autocomplete="off" required="" aria-required="true"><i class="fa fa-pencil form-control-feedback"></i></div></div>';
    myNewCell=myNewRow.insertCell(-1);
    myNewCell.innerHTML='<div class="col-md-12"><div class="form-group has-feedback"><label class="control-label">Cantidad de Porcion: <span class="symbol required"></span></label><input type="text" class="form-control" name="cantidad[]'+cont+'" id="cantidad'+cont+'" onKeyUp="this.value=this.value.toUpperCase();" placeholder="Ingrese Cantidad de Porcion" title="Ingrese Cantidad de Porcion" autocomplete="off" required="" aria-required="true"><i class="fa fa-pencil form-control-feedback"></i></div></div>';
    myNewCell=myNewRow.insertCell(-1);
    myNewCell.innerHTML='<div class="col-md-12"><div class="form-group has-feedback"><label class="control-label">Unidad de Medida: <span class="symbol required"></span></label><input type="text" class="form-control" name="medida[]'+cont+'" id="medida'+cont+'" onKeyUp="this.value=this.value.toUpperCase();" placeholder="Ingrese Unidad de Medida" title="Ingrese Unidad de Medida" autocomplete="off" readonly="" required="" aria-required="true"><i class="fa fa-pencil form-control-feedback"></i></div></div>';
    indiceFila++;
    }

    /////////////Borrar() ///////////
    function Delete() {
        var table = document.getElementById('tabla');
        if(table.rows.length > 1)
        {
        table.deleteRow(table.rows.length -1);
        cont--;
        }
    }

    ////////////FUNCION ASIGNA VALOR DE CONT PARA EL FOR DE MOSTRAR DATOS MP-MOD-TT////////
    function asigna() {
       valor=document.form.var_cont.value=cont;
    }
    </script>
    
</body>
</html>

<?php } else { ?>   
        <script type='text/javascript' language='javascript'>
        alert('NO TIENES PERMISO PARA ACCEDER A ESTA PAGINA.\nCONSULTA CON EL ADMINISTRADOR PARA QUE TE DE ACCESO')  
        document.location.href='panel'   
        </script> 
<?php } } else { ?>
        <script type='text/javascript' language='javascript'>
        alert('NO TIENES PERMISO PARA ACCEDER AL SISTEMA.\nDEBERA DE INICIAR SESION')  
        document.location.href='logout'  
        </script> 
<?php } ?>