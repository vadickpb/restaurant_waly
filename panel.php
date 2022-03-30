<?php
require_once("class/class.php"); 
if(isset($_SESSION['acceso'])) { 
     if ($_SESSION['acceso'] == "administrador" || $_SESSION["acceso"]=="secretaria" || $_SESSION["acceso"]=="cajero" || $_SESSION["acceso"]=="mesero" || $_SESSION["acceso"]=="cocinero" || $_SESSION["acceso"]=="repartidor") {

$tra = new Login();
$ses = $tra->ExpiraSession();

$grafico = new Login();
$grafico = $grafico->ContarRegistros();  

$imp = new Login();
$imp = $imp->ImpuestosPorId();
$impuesto = $imp[0]['nomimpuesto'];
$valor = $imp[0]['valorimpuesto'];

$con = new Login();
$con = $con->ConfiguracionPorId();
$simbolo = "<strong>".$con[0]['simbolo']."</strong>";

if(isset($_POST['btn-nuevo']))
{
$reg = $tra->NuevoPedido();
exit;
}
elseif(isset($_POST['btn-agregar']))
{
$reg = $tra->AgregaPedido();
exit;
}
elseif(isset($_POST['btn-cerrar']))
{
$reg = $tra->CerrarMesa();
exit;
}
else if(isset($_POST["btn-cliente"]))
{
$reg = $tra->RegistrarClientes();
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
    <!-- Datatables CSS -->
    <link href="assets/plugins/datatables/dataTables.bootstrap4.css" rel="stylesheet">
    <!-- Sweet-Alert -->
    <link rel="stylesheet" href="assets/css/sweetalert.css">
    <!-- animation CSS -->
    <link href="assets/css/animate.css" rel="stylesheet">
    <!-- needed css -->
    <link href="assets/css/style.css" rel="stylesheet">
    <!-- color CSS -->
    <link href="assets/css/default.css" id="theme" rel="stylesheet">
   <!--Bootstrap Horizontal CSS -->
    <link href="assets/css/bootstrap-horizon.css" rel="stylesheet">

    <!-- script jquery -->
    <script src="assets/script/jquery.min.js"></script> 
    <script type="text/javascript" src="assets/plugins/chart.js/chart.min.js"></script>
    <script type="text/javascript" src="assets/script/graficos.js"></script>
    <!--  script jquery -->

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



<!--############################## MODAL PARA REGISTRO DE NUEVO CLIENTE ######################################-->
<!-- sample modal content -->
<div id="myModalCliente" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h4 class="modal-title text-white" id="myModalLabel"><i class="fa fa-pencil"></i> Nuevo Cliente</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><img src="assets/images/close.png"/></button>
            </div>
            
        <form class="form form-material" name="clienteventa" id="clienteventa" action="#">
                
               <div class="modal-body">

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group has-feedback">
                            <label class="control-label">Tipo de Documento: </label>
                            <i class="fa fa-bars form-control-feedback"></i> 
                            <select name="documcliente" id="documcliente" class='form-control' required="" aria-required="true">
                            <option value=""> -- SELECCIONE -- </option>
                            <?php
                            $doc = new Login();
                            $doc = $doc->ListarDocumentos();
                            for($i=0;$i<sizeof($doc);$i++){ ?>
                            <option value="<?php echo $doc[$i]['coddocumento'] ?>"><?php echo $doc[$i]['documento'] ?></option>
                            <?php }  ?>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group has-feedback">
                            <label class="control-label">Nº de Documento: <span class="symbol required"></span></label>
                            <input type="text" class="form-control" name="dnicliente" id="dnicliente" onKeyUp="this.value=this.value.toUpperCase();" placeholder="Ingrese Nº de Documento" autocomplete="off" required="" aria-required="true"/> 
                            <i class="fa fa-bolt form-control-feedback"></i> 
                        </div>
                    </div>
                </div>


                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group has-feedback">
                            <label class="control-label">Nombre de Cliente: <span class="symbol required"></span></label>
                            <input type="text" class="form-control" name="nomcliente" id="nomcliente" onKeyUp="this.value=this.value.toUpperCase();" placeholder="Ingrese Nombre de Cliente" autocomplete="off" required="" aria-required="true"/>  
                            <i class="fa fa-pencil form-control-feedback"></i> 
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group has-feedback">
                            <label class="control-label">Nº de Teléfono: </label>
                            <input type="text" class="form-control phone-inputmask" name="tlfcliente" id="tlfcliente" onKeyUp="this.value=this.value.toUpperCase();" placeholder="Ingrese Nº de Teléfono" autocomplete="off" required="" aria-required="true"/>  
                            <i class="fa fa-phone form-control-feedback"></i> 
                        </div>
                    </div>
                </div>


                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group has-feedback">
                            <label class="control-label">Correo de Cliente: </label>
                            <input type="text" class="form-control" name="emailcliente" id="emailcliente" onKeyUp="this.value=this.value.toUpperCase();" placeholder="Ingrese Correo Electronico" autocomplete="off" required="" aria-required="true"/> 
                            <i class="fa fa-envelope-o form-control-feedback"></i>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group has-feedback">
                            <label class="control-label">Tipo de Cliente: <span class="symbol required"></span></label>
                            <i class="fa fa-bars form-control-feedback"></i>
                            <select name="tipocliente" id="tipocliente" class="form-control" required="" aria-required="true">
                                <option value=""> -- SELECCIONE -- </option>
                                <option value="NATURAL">NATURAL</option>
                                <option value="JURIDICO">JURIDICO</option>
                            </select> 
                        </div>
                    </div>
                </div>


                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group has-feedback">
                            <label class="control-label">Provincia: </label>
                            <i class="fa fa-bars form-control-feedback"></i>
                            <select name="id_provincia" id="id_provincia" onChange="CargaDepartamentos(this.form.id_provincia.value);" class='form-control' required="" aria-required="true">
                                <option value=""> -- SELECCIONE -- </option>
                                <?php
                                $pro = new Login();
                                $pro = $pro->ListarProvincias();
                                for($i=0;$i<sizeof($pro);$i++){ ?>
                                <option value="<?php echo $pro[$i]['id_provincia'] ?>"><?php echo $pro[$i]['provincia'] ?></option>        
                                  <?php } ?>
                          </select> 
                      </div>
                   </div>

                    <div class="col-md-6">
                        <div class="form-group has-feedback">
                            <label class="control-label">Departamentos: </label>
                            <i class="fa fa-bars form-control-feedback"></i>
                            <select class="form-control" id="id_departamento" name="id_departamento" required="" aria-required="true">
                                <option value=""> -- SIN RESULTADOS -- </option>
                            </select>
                        </div>
                    </div>
                </div>


                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group has-feedback">
                            <label class="control-label">Dirección Domiciliaria: <span class="symbol required"></span></label>
                            <input type="text" class="form-control" name="direccliente" id="direccliente" onKeyUp="this.value=this.value.toUpperCase();" placeholder="Ingrese Dirección Domiciliaria" autocomplete="off" required="" aria-required="true"/> 
                            <i class="fa fa-map-marker form-control-feedback"></i>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group has-feedback">
                            <label class="control-label">Limite de Crédito: <span class="symbol required"></span></label>
                            <input type="text" class="form-control" name="limitecredito" id="limitecredito" onKeyUp="this.value=this.value.toUpperCase();" onKeyPress="EvaluateText('%f', this);" onBlur="this.value = NumberFormat(this.value, '2', '.', '')" placeholder="Ingrese Limite de Crédito" autocomplete="off" required="" aria-required="true"/>  
                            <i class="fa fa-usd form-control-feedback"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
<button type="submit" name="btn-cliente" id="btn-cliente" class="btn btn-warning"><span class="fa fa-save"></span> Guardar</button>
<button class="btn btn-dark" type="reset" class="close" data-dismiss="modal" aria-hidden="true"><span class="fa fa-trash-o"></span> Cerrar</button>
            </div>
        </form>

    </div>
    <!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->
</div>
<!-- /.modal --> 

    
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
                <h5 class="font-medium text-uppercase mb-0"><i class="fa fa-tasks"></i> <?php echo $var = ($_SESSION['acceso'] == "mesero" || $_SESSION['acceso'] == "cocinero" || $_SESSION['acceso'] == "repartidor" ? "Mostrador de Pedidos" : "Gestión de Ventas"); ?></h5>
                    </div>
                    <div class="col-lg-9 col-md-8 col-xs-12 align-self-center">
                        <nav aria-label="breadcrumb" class="mt-2 float-md-right float-left">
                            <ol class="breadcrumb mb-0 justify-content-end p-0">
                                <li class="breadcrumb-item"><a href="panel" class="text-info"> Mostrador</a></li>
                                <li class="breadcrumb-item active" aria-current="page"><?php echo $var = ($_SESSION['acceso'] == "mesero" || $_SESSION['acceso'] == "cocinero" || $_SESSION['acceso'] == "repartidor" ? "<a href='logout' class='text-info'> Cerrar Sesión</a>" : "Ventas"); ?></li>
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

<?php if ($_SESSION['acceso'] == "administrador" || $_SESSION["acceso"]=="cajero" || $_SESSION["acceso"]=="mesero") { ?>
            
<!-- Row -->
<div class="row">
    <div class="col-lg-5">
        <div class="card">
            <div class="card-header bg-warning">
                <h4 class="card-title text-white"><i class="fa fa-pencil"></i> Gestión de Ventas</h4>
            </div>
        <form class="form form-material" method="post" action="#" name="saveventas" id="saveventas">

                <div id="save">
                 <!-- error will be shown here ! -->
                </div>

             <div class="form-body">

                <div class="card-body">

                <div id="muestradetallemesa"><center>SELECCIONE MESA PARA CONTINUAR -></center></div>
        
                </div>
            </div>
        </form>
        </div>
    </div>
<!--</div>
End Row -->

<!-- Row 
<div class="row">-->
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header bg-warning">
                <h4 class="card-title text-white"><i class="fa fa-tasks"></i> Mesas/Productos</h4>
            </div>

            <div class="form-body">

                <div class="card-body">

                <div id="loading"></div>

                </div>

            </div>
        </div>
    </div>

</div>
<!-- End Row -->

<?php } else if ($_SESSION['acceso'] == "cocinero") { ?>

<!-- Row -->
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header bg-warning">
                <h4 class="card-title text-white"><i class="fa fa-desktop"></i> Mostrador de Pedidos</h4>
            </div>

            <div class="form-body">

                <div class="card-body">

                <div id="mostrador"></div>

            </div>
        </div>
     </div>
  </div>
</div>
<!-- End Row -->

<script type="text/javascript">
    $('document').ready(function() {
    setTimeout(function run() {        
        $("#mostrador").load("consultas.php?CargaMostrador=si");
        setTimeout(run, 12000);
       }, 12000);                         
    });
</script>

<?php } else if ($_SESSION['acceso'] == "repartidor") { ?>

<!-- Row -->
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header bg-warning">
                <h4 class="card-title text-white"><i class="fa fa-desktop"></i> Mostrador de Pedidos</h4>
            </div>

            <div class="form-body">

                <div class="card-body">
				
                <div id="delivery"></div>

            </div>
        </div>
     </div>
  </div>
</div>
<!-- End Row -->

<script type="text/javascript">
    $('document').ready(function() {
    setTimeout(function run() {        
        $("#delivery").load("consultas.php?CargaDelivery=si");
        setTimeout(run, 12000);
       }, 12000);                         
    });
</script>

<?php } else { ?>


<div class="row">
    <div class="col-md-12 col-lg-6">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title text-uppercase mb-0">
                Gráfico de Registros
            </h5>
            <div id="chart-container">
                <canvas id="bar-chart" width="800" height="400"></canvas>
            </div>
            <script>
                    // Bar chart
                    new Chart(document.getElementById("bar-chart"), {
                        type: 'bar',
                        data: {
                            labels: ["Clientes", "Proveedores", "Ingredientes", "Productos", "Compras", "Ventas"],
                            datasets: [
                            {
                                label: "Cantidad Nº",
                                backgroundColor: ["#ff7676", "#3e95cd","#3cba9f","#003399","#f0ad4e","#969788"],
                                data: [<?php echo $grafico[0]['clientes'] ?>,<?php echo $grafico[0]['proveedores'] ?>,<?php echo $grafico[0]['ingredientes'] ?>,<?php echo $grafico[0]['productos'] ?>,<?php echo $grafico[0]['compras'] ?>,<?php echo $grafico[0]['ventas'] ?>]
                            }
                            ]
                        },
                        options: {
                            legend: { display: false },
                            title: {
                                display: true,
                                text: 'Cantidad de Registros'
                            }
                        }
                    });
                </script>
            </div>
        </div>
    </div>

<?php  
$compra = new Login();
$commes = $compra->SumaCompras();

$venta = new Login();
$venmes = $venta->SumaVentas();

?>

<div class="col-md-12 col-lg-6">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title text-uppercase mb-0">
                    Compras del Año <?php echo date("Y"); ?>
                </h5>
                    <div id="chart-container">
                    <canvas id="bar-chart3" width="800" height="400"></canvas>
                    </div>
                    <script>
                            // Bar chart
                            new Chart(document.getElementById("bar-chart3"), {
                                type: 'bar',
                                data: {
                                    labels: ["Ene","Feb","Mar","Abr","May","Jun","Jul","Ago","Sep","Oct","Nov","Dic"],
                                    datasets: [
                                    {
                                        label: "Monto Mensual",
                                        backgroundColor: ["#ff7676","#3e95cd","#808080","#F38630","#25AECD","#008080","#00FFFF","#3cba9f","#2E64FE","#e8c3b9","#F7BE81","#FA5858"],
                                        data: [<?php 

                              if($commes[0]['totalmes'] == 0) { echo 0; } else {

                                  $meses = array(1 => 0, 2=> 0, 3=> 0, 4=> 0, 5=> 0, 6=> 0, 7=> 0, 8=> 0, 9=> 0, 10=> 0, 11=> 0, 12 => 0);
                                  foreach($commes as $row) {
                                    $mes = $row['mes'];
                                    $meses[$mes] = $row['totalmes'];
                                }
                                foreach($meses as $mes) {
                                    echo "{$mes},"; } } ?>]
                                }]
                            },
                            options: {
                                legend: { display: false },
                                title: {
                                    display: true,
                                    text: 'Suma de Monto Mensual'
                                }
                            }
                        });
                    </script>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12 col-lg-6">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title text-uppercase mb-0">
                    Ventas del Año <?php echo date("Y"); ?>
                </h5>
                <div id="chart-container">
                <canvas id="bar-chart4" width="800" height="400"></canvas>
                </div>
                <script>
                // Bar chart
                new Chart(document.getElementById("bar-chart4"), {
                    type: 'bar',
                    data: {
                    labels: ["Ene","Feb","Mar","Abr","May","Jun","Jul","Ago","Sep","Oct","Nov","Dic"],
                    datasets: [
                          {
                            label: "Monto Mensual",
                            backgroundColor: ["#ff7676","#3e95cd","#808080","#F38630","#7B82EC","#8EE1BC","#D3E37D","#E8AC9E","#2E64FE","#E399DA","#F7BE81","#FA5858"],
                            data: [<?php 

                      if($venmes[0]['totalmes'] == 0) { echo 0; } else {

                        $meses = array(1 => 0, 2=> 0, 3=> 0, 4=> 0, 5=> 0, 6=> 0, 7=> 0, 8=> 0, 9=> 0, 10=> 0, 11=> 0, 12 => 0);
                          foreach($venmes as $row) {
                            $mes = $row['mes'];
                            $meses[$mes] = $row['totalmes'];
                        }
                        foreach($meses as $mes) {
                            echo "{$mes},"; } } ?>]
                        }]
                    },
                    options: {
                        legend: { display: false },
                        title: {
                        display: true,
                        text: 'Suma de Monto Mensual'
                        }
                    }
                });
                </script>
            </div>
        </div>
    </div>

    <div class="col-md-12 col-lg-6">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title text-uppercase mb-0">
                    8 Productos Mas Vendidos del Año <?php echo date("Y"); ?>
                </h5>
                    <div id="chart-container">
                    <canvas id="DoughnutChart" width="800" height="500"></canvas>
                    </div>
                    <script>
                    $(document).ready(function () {
                        showGraphDoughnutPV();
                    });
                    </script>
            </div>
        </div>
    </div>
</div>

<?php } ?>
    
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

    <!-- script jquery -->
    <script type="text/javascript" src="assets/script/titulos.js"></script>
    <script type="text/javascript" src="assets/script/jquery.mask.js"></script>
    <script type="text/javascript" src="assets/script/mask.js"></script>
    <script type="text/javascript" src="assets/script/script2.js"></script>
    <script type="text/javascript" src="assets/script/jsventas.js"></script>
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
    <script type="text/jscript">
    $('#loading').append('<center><i class="fa fa-spin fa-spinner"></i> Por favor espere, cargando registros ......</center>').fadeIn("slow");
    setTimeout(function() {
    $('#loading').load("salas_mesas?CargaMesas=si");
     }, 1000);
    </script>
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