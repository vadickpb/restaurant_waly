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
$reg = $tra->RegistrarIngredientes();
exit;
} 
elseif(isset($_POST["proceso"]) and $_POST["proceso"]=="update")
{
$reg = $tra->ActualizarIngredientes();
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
        <h5 class="font-medium text-uppercase mb-0"><i class="fa fa-tasks"></i> Gestión de Ingredientes</h5>
                    </div>
                    <div class="col-lg-9 col-md-8 col-xs-12 align-self-center">
                        <nav aria-label="breadcrumb" class="mt-2 float-md-right float-left">
                            <ol class="breadcrumb mb-0 justify-content-end p-0">
                                <li class="breadcrumb-item">Mantenimiento</li>
                                <li class="breadcrumb-item active" aria-current="page">Ingredientes</li>
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
                <h4 class="card-title text-white"><i class="fa fa-pencil"></i> Gestión de Ingredientes</h4>
            </div>

<?php  if (isset($_GET['codingrediente'])) {
      
      $reg = $tra->IngredientesPorId(); ?>
      
<form class="form form-material" method="post" action="#" name="updateingredientes" id="updateingredientes" enctype="multipart/form-data">
        
    <?php } else { ?>
        
<form class="form form-material" method="post" action="#" name="saveingredientes" id="saveingredientes" enctype="multipart/form-data">
      
    <?php } ?>

                <div id="save">
                   <!-- error will be shown here ! -->
               </div>

               <div class="form-body">

            <div class="card-body">

                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group has-feedback">
                            <label class="control-label">Código de Ingrediente: <span class="symbol required"></span></label>
                            <input type="hidden" name="idingrediente" id="idingrediente" <?php if (isset($reg[0]['idingrediente'])) { ?> value="<?php echo $reg[0]['idingrediente']; ?>"<?php } ?>>
                            <input type="hidden" name="proceso" id="proceso" <?php if (isset($reg[0]['idingrediente'])) { ?> value="update" <?php } else { ?> value="save" <?php } ?>/>
                            <input type="text" class="form-control" name="codingrediente" id="codingrediente" onKeyUp="this.value=this.value.toUpperCase();" placeholder="Ingrese Código de Ingrediente" autocomplete="off" <?php if (isset($reg[0]['codingrediente'])) { ?> value="<?php echo $reg[0]['codingrediente']; ?>" readonly="readonly" <?php } else { ?><?php } ?> required="" aria-required="true"/> 
                            <i class="fa fa-bolt form-control-feedback"></i> 
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group has-feedback">
                            <label class="control-label">Nombre de Ingrediente: <span class="symbol required"></span></label>
                            <input type="text" class="form-control" name="nomingrediente" id="nomingrediente" onKeyUp="this.value=this.value.toUpperCase();" placeholder="Ingrese Nombre de Ingrediente" autocomplete="off" <?php if (isset($reg[0]['nomingrediente'])) { ?> value="<?php echo $reg[0]['nomingrediente']; ?>" <?php } ?> required="" aria-required="true"/>  
                            <i class="fa fa-pencil form-control-feedback"></i> 
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group has-feedback">
                            <label class="control-label">Medida de Ingrediente: <span class="symbol required"></span></label>
                            <i class="fa fa-bars form-control-feedback"></i>
                            <?php if (isset($reg[0]['codmedida'])) { ?>
                            <select name="codmedida" id="codmedida" class='form-control' required="" aria-required="true">
                                <option value=""> -- SELECCIONE -- </option>
                                <?php
                                $medida = new Login();
                                $medida = $medida->ListarMedidas();
                                for($i=0;$i<sizeof($medida);$i++){ ?>
                                <option value="<?php echo $medida[$i]['codmedida'] ?>"<?php if (!(strcmp($reg[0]['codmedida'], htmlentities($medida[$i]['codmedida'])))) { echo "selected=\"selected\"";} ?>><?php echo $medida[$i]['nommedida'] ?></option>        
                                  <?php } ?>
                          </select>  
                             <?php } else { ?>
                          <select name="codmedida" id="codmedida" class='form-control' required="" aria-required="true">
                                <option value=""> -- SELECCIONE -- </option>
                                <?php
                                $medida = new Login();
                                $medida = $medida->ListarMedidas();
                                for($i=0;$i<sizeof($medida);$i++){ ?>
                                <option value="<?php echo $medida[$i]['codmedida'] ?>"><?php echo $medida[$i]['nommedida'] ?></option>        
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
                            <label class="control-label">Cantidad de Ingrediente: <span class="symbol required"></span></label>
                            <input type="text" class="form-control" name="cantingrediente" id="cantingrediente" onKeyUp="this.value=this.value.toUpperCase();" onKeyPress="EvaluateText('%f', this);" onBlur="this.value = NumberFormat(this.value, '2', '.', '')" placeholder="Ingrese Cantidad de Ingrediente" autocomplete="off" <?php if (isset($reg[0]['cantingrediente'])) { ?> value="<?php echo $reg[0]['cantingrediente']; ?>" <?php } ?> required="" aria-required="true"/>  
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
                                <?php if (isset($reg[0]['ivaingrediente'])) { ?>
                        <select name="ivaingrediente" id="ivaingrediente" class="form-control" required="" aria-required="true">
                                <option value=""> -- SELECCIONE -- </option>
<option value="SI"<?php if (!(strcmp('SI', $reg[0]['ivaingrediente']))) {echo "selected=\"selected\"";} ?>>SI</option>
<option value="NO"<?php if (!(strcmp('NO', $reg[0]['ivaingrediente']))) {echo "selected=\"selected\"";} ?>>NO</option>
                            </select>
                               <?php } else { ?>
                        <select name="ivaingrediente" id="ivaingrediente" class="form-control" required="" aria-required="true">
                                <option value=""> -- SELECCIONE -- </option>
                                <option value="SI">SI</option>
                                <option value="NO">NO</option>
                            </select>
                               <?php } ?>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group has-feedback">
                            <label class="control-label">Descuento de Ingrediente: <span class="symbol required"></span></label>
                            <input type="text" class="form-control" name="descingrediente" id="descingrediente" onKeyUp="this.value=this.value.toUpperCase();" onKeyPress="EvaluateText('%f', this);" onBlur="this.value = NumberFormat(this.value, '2', '.', '')" placeholder="Ingrese Descuento de Ingrediente" autocomplete="off" <?php if (isset($reg[0]['descingrediente'])) { ?> value="<?php echo $reg[0]['descingrediente']; ?>" <?php } ?> required="" aria-required="true"/>  
                            <i class="fa fa-tint form-control-feedback"></i>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group has-feedback">
                            <label class="control-label">Nº de Lote: </label>
                            <input type="text" class="form-control" name="lote" id="lote" onKeyUp="this.value=this.value.toUpperCase();" placeholder="Ingrese Nº de Lote" autocomplete="off" <?php if (isset($reg[0]['lote'])) { ?> value="<?php echo $reg[0]['lote']; ?>" <?php } ?> required="" aria-required="true"/>  
                            <i class="fa fa-pencil form-control-feedback"></i> 
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
                </div>

             <div class="text-right">
    <?php  if (isset($_GET['codingrediente'])) { ?>
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