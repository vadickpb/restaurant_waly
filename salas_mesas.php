<?php
require_once("class/class.php");

$con = new Login();
$con = $con->ConfiguracionPorId();
$simbolo = "<strong>".$con[0]['simbolo']."</strong>";
?>



<?php if (isset($_GET['CargaMesas'])): ?>

<?php
$sala = new Login();
$sala = $sala->ListarSalas();
?>
    <div class="row-horizon">
        <?php 
        if($sala==""){ echo ""; } else {
        $a=1;
        for ($i = 0; $i < sizeof($sala); $i++) { ?>
        <span class="categories <?php echo $activo = ( $sala[$i]['codsala'] == 1 ? "selectedGat" : ""); ?>" id="<?php echo $sala[$i]['nomsala'];?>"><?php echo $sala[$i]['nomsala'];?></span>
        <?php } } ?>
    </div><br>

    <div id="productList2">

        <div class="row-vertical-mesas">
        <?php
        $mesa = new Login();
        $mesa = $mesa->ListarMesas(); 

        if($mesa==""){

        echo "<div class='alert alert-danger'>";
        echo "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>";
        echo "<center><span class='fa fa-info-circle'></span> NO EXISTEN MESAS REGISTRADAS ACTUALMENTE</center>";
        echo "</div>";    

        } else {

        for ($ii = 0; $ii < sizeof($mesa); $ii++) { ?>
   
        <a style="float: left; margin-right: 4px; <?php echo $activo = ( $mesa[$ii]['codsala'] == 1 ? "display: block;" : "display: none;"); ?>" id="<?php echo $sala[$i]['nomsala'];?>">
            <div class="users-list-name codMesa" title="<?php echo $mesa[$ii]['nommesa']; ?>" style="cursor:pointer;" onclick="RecibeMesa('<?php echo encrypt($mesa[$ii]['codmesa']); ?>')">
                <div id="<?php echo $mesa[$ii]['codmesa']; ?>">
                <input type="hidden" id="category" name="category" value="<?php echo $mesa[$ii]['nomsala']; ?>">
                    
                    <div id="<?php echo $mesa[$ii]['nommesa']; ?>" style="width: 126px;height: 126px;-moz-border-radius: 50%;-webkit-border-radius: 50%;border-radius: 50%;background:<?php if ($mesa[$ii]['statusmesa'] == '0') { ?>#5cb85c;<?php } ?>red" class="miMesa"><img src="fotos/mesa.png" style="display:inline;margin:22px;float:left;width:88px;height:70px;"></div> 

                </div>
                <center><strong><?php echo $mesa[$ii]['nommesa']; ?></strong></center>
            </div>
        </a>
    
        <?php } } ?>

        </div> 
    </div>

<?php endif; ?>





<?php if (isset($_GET['CargaProductos'])): ?>

<?php
$categoria = new Login();
$categoria = $categoria->ListarCategorias();
?>
    <div class="row-horizon">
        <span class="categories selectedGat" id=""><i class="fa fa-home"></i></span>
        <?php 
        if($categoria==""){ echo ""; } else {
        $a=1;
        for ($i = 0; $i < sizeof($categoria); $i++) { ?>
        <span class="categories" id="<?php echo $categoria[$i]['nomcategoria'];?>"><?php echo $categoria[$i]['nomcategoria'];?></span>
        <?php } } ?>
    </div>

    <div class="col-md-12">
        <div id="searchContaner"> 
            <div class="form-group has-feedback2"> 
                <label class="control-label"></label>
                <input type="text" class="form-control" name="busquedaproductov" id="busquedaproductov" onKeyUp="this.value=this.value.toUpperCase();" autocomplete="off" placeholder="Realice la Búsqueda del Producto por Nombre">
                  <i class="fa fa-search form-control-feedback2"></i> 
            </div> 
        </div>
    </div>
    

    <div id="productList2">
        <?php
        $producto = new Login();
        $producto = $producto->ListarProductosModal();

        $monedap = new Login();
        $cambio = $monedap->MonedaProductoId(); 

        if($producto==""){

        echo "<div class='alert alert-danger'>";
        echo "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>";
        echo "<center><span class='fa fa-info-circle'></span> NO EXISTEN PRODUCTOS REGISTRADOS ACTUALMENTE</center>";
        echo "</div>";  

        } else { ?>

        <div class="row row-vertical">
        <?php for ($ii = 0; $ii < sizeof($producto); $ii++) { ?>


    <div ng-click="afterClick()" ng-repeat="product in ::getFavouriteProducts()" OnClick="DoAction('<?php echo $producto[$ii]['codproducto']; ?>','<?php echo $producto[$ii]['producto']; ?>','<?php echo $producto[$ii]['codcategoria']; ?>','<?php echo $producto[$ii]['nomcategoria']; ?>','<?php echo $producto[$ii]['preciocompra']; ?>','<?php echo $producto[$ii]['precioventa']; ?>','<?php echo $producto[$ii]['descproducto']; ?>','<?php echo $producto[$ii]['ivaproducto']; ?>','<?php echo $producto[$ii]['existencia']; ?>','<?php echo $precioconiva = ( $producto[$ii]['ivaproducto'] == 'SI' ? $producto[$ii]['precioventa'] : "0.00"); ?>');"> 
        <div id="<?php echo $producto[$ii]['codproducto']; ?>">
            <div class="darkblue-panel pn" title="<?php echo $producto[$ii]['producto'].' | ('.$producto[$ii]['nomcategoria'].')';?>">
                    <div class="darkblue-header">
                        <div id="proname" class="text-white"><?php echo getSubString($producto[$ii]['producto'],18);?></div>
                    </div>
        <?php if (file_exists("./fotos/productos/".$producto[$ii]["codproducto"].".jpg")){

        echo "<img src='fotos/productos/".$producto[$ii]['codproducto'].".jpg?' class='rounded-circle' style='width:150px;height:134px;'>"; 

        } else {

        echo "<img src='fotos/producto.png' class='rounded-circle' style='width:150px;height:134px;'>";  } ?>
                <input type="hidden" id="category" name="category" value="<?php echo $producto[$ii]['nomcategoria']; ?>">
                <div class="mask">
                    <a class="text-white">
                    <?php echo $simbolo.$producto[$ii]['precioventa'];?><br>
                    
                    </a>
                    <h5><i class="fa fa-bars"></i> <?php echo $producto[$ii]['existencia'];?></h5>
                </div>

            </div>
        </div>
    </div>
                 
        <?php } } ?>

        </div> 
    </div>

<?php endif; ?>










<?php if (isset($_GET['CargaMesas2222'])): ?>
<div class="table-responsive">    
    <!-- Nav tabs -->
    <ul class="nav nav-tabs customtab" role="tablist">
    <?php
    $sala = new Login();
    $sala = $sala->ListarSalas();
    if($sala==""){ echo "";      
    } else {
    $a=1;
    for ($i = 0; $i < sizeof($sala); $i++) { ?>
    <?php if ($i === 0): ?>
        <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#v<?php echo $sala[$i]['codsala'];?>" role="tab"><span class="hidden-sm-up"><i class="mdi mdi-sale"></i></span> <span class="hidden-xs-down"><?php echo $sala[$i]['nomsala'];?></span></a>
    <?php else: ?>
        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#v<?php echo $sala[$i]['codsala'];?>" role="tab"><span class="hidden-sm-up"><i class="mdi mdi-sale"></i></span> <span class="hidden-xs-down"><?php echo $sala[$i]['nomsala'];?></span></a>
    <?php endif; ?>
        </li>
    <?php } } ?>   
    </ul>

    <!-- Tab panes -->
    <div class="tab-content">
    <?php
    $sala = new Login();
    $sala = $sala->ListarSalas();
    if($sala==""){ echo "";      
    } else {
    for ($i = 0; $i < sizeof($sala); $i++) { ?>
    <?php if ($i === 0): ?>
        <div class="tab-pane active" id="v<?php echo $sala[$i]['codsala'];?>" role="tabpanel">
    <?php else: ?>
        <div class="tab-pane" id="v<?php echo $sala[$i]['codsala'];?>" role="tabpanel">
    <?php endif; ?>
    <?php $codigo_sala = $sala[$i]['codsala']; ?>

        <div class="p-4" id="listMesas">

        <?php
        $mesa = new Login();
        $mesa = $mesa->ListarMesas();
        if($mesa==""){ echo "";      
        } else {
        for ($ii = 0; $ii < sizeof($mesa); $ii++) { ?>
        <?php if ($mesa[$ii]['codsala'] == $codigo_sala) { ?>
            <li style="display:inline;float: left; margin-right: 4px;">
                <div class="users-list-name codMesa" title="<?php echo $mesa[$ii]['nommesa']; ?>" style="cursor:pointer;" onclick="RecibeMesa('<?php echo encrypt($mesa[$ii]['codmesa']); ?>')">
                    <div id="<?php echo $mesa[$ii]['nommesa']; ?>" style="width: 130px;height: 130px;-moz-border-radius: 50%;-webkit-border-radius: 50%;border-radius: 50%;background:<?php if ($mesa[$ii]['statusmesa'] == '0') { ?>#5cb85c;<?php } ?>red" class="miMesa"><img src="fotos/mesa.png" style="display:inline;margin:24px;float:left;width:88px;height:70px;"></div>
                    <center><strong><?php echo $mesa[$ii]['nommesa']; ?></strong></center><br>
                </div>
            </li>
        <?php } } } ?>

        </div>
        </div>
        <?php } } ?>
    </div>
    <!-- Tab panes -->
</div>
<?php endif; ?>



<?php if (isset($_GET['CargaProductos2222'])): ?>
<div class="table-responsive">
  <!-- Nav tabs -->
  <ul class="nav nav-tabs customtab" role="tablist">
    <?php
    $categoria = new Login();
    $categoria = $categoria->ListarCategorias();
    if($categoria==""){ echo "";      
    } else {
    $a=1;
    for ($i = 0; $i < sizeof($categoria); $i++) { ?>
    <?php if ($i === 0): ?>
        <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#v<?php echo $categoria[$i]['codcategoria'];?>" role="tab"><span class="hidden-sm-up"><i class="mdi mdi-sale"></i></span> <span class="hidden-xs-down"><?php echo $categoria[$i]['nomcategoria'];?></span></a>
    <?php else: ?>
        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#v<?php echo $categoria[$i]['codcategoria'];?>" role="tab"><span class="hidden-sm-up"><i class="mdi mdi-sale"></i></span> <span class="hidden-xs-down"><?php echo $categoria[$i]['nomcategoria'];?></span></a>
    <?php endif; ?>
        </li>
    <?php } } ?>   
    </ul><br>

    <!-- Tab panes -->
    <div class="tab-content">
    <?php
    $categoria = new Login();
    $categoria = $categoria->ListarCategorias();
    if($categoria==""){ echo "";      
    } else {
    for ($i = 0; $i < sizeof($categoria); $i++) { ?>
    <?php if ($i === 0): ?>
        <div class="tab-pane active" id="v<?php echo $categoria[$i]['codcategoria'];?>" role="tabpanel">
    <?php else: ?>
        <div class="tab-pane" id="v<?php echo $categoria[$i]['codcategoria'];?>" role="tabpanel">
    <?php endif; ?>
    <?php $codigo_cate = $categoria[$i]['codcategoria']; ?>

        <div class="row">

        <?php
        $producto = new Login();
        $producto = $producto->ListarProductosModal();

        $monedap = new Login();
        $cambio = $monedap->MonedaProductoId(); 

        if($producto==""){

                echo "<div class='alert alert-danger'>";
                echo "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>";
                echo "<center><span class='fa fa-info-circle'></span> NO EXISTEN PRODUCTOS REGISTRADOS ACTUALMENTE</center>";
                echo "</div>";    

        } else {

        for ($ii = 0; $ii < sizeof($producto); $ii++) {

        if ($producto[$ii]['codcategoria'] == $codigo_cate && $producto[$ii]['existencia'] > 0) { ?>
            <div class="mb" ng-click="afterClick()" ng-repeat="product in ::getFavouriteProducts()" 

            OnClick="DoAction('<?php echo $producto[$ii]['codproducto']; ?>','<?php echo $producto[$ii]['producto']; ?>','<?php echo $producto[$ii]['codcategoria']; ?>','<?php echo $producto[$ii]['nomcategoria']; ?>','<?php echo $producto[$ii]['preciocompra']; ?>','<?php echo $producto[$ii]['precioventa']; ?>','<?php echo $producto[$ii]['descproducto']; ?>','<?php echo $producto[$ii]['ivaproducto']; ?>','<?php echo $producto[$ii]['existencia']; ?>','<?php echo $precioconiva = ( $producto[$ii]['ivaproducto'] == 'SI' ? $producto[$ii]['precioventa'] : "0.00"); ?>');">
             <div class="darkblue-panel pn" title="<?php echo $producto[$ii]['producto'].' | CATEGORIA: ('.$producto[$ii]['nomcategoria'].')';?>">
            <div class="darkblue-header">
                <a class="text-white"><?php echo getSubString($producto[$ii]['producto'],16);?></a>
            </div>
            <?php if (file_exists("./fotos/productos/".$producto[$ii]["codproducto"].".jpg")){

            echo "<img src='fotos/productos/".$producto[$ii]['codproducto'].".jpg?' class='rounded-circle' style='width:136px;height:124px;'>"; 

                } else {

            echo "<img src='fotos/producto.png' class='rounded-circle' style='width:136px;height:124px;'>";  } ?>
            <a class="text-white"> <?php echo $simbolo.$producto[$ii]['precioventa'];?><br>
    <?php echo $cambio[0]['codmoneda2'] == '' ? "" : $cambio[0]['simbolo']."</strong>".number_format($producto[$ii]['precioventa']/$cambio[0]['montocambio'], 2, '.', ','); ?></a>
            <h5><i class="fa fa-bars"></i> <?php echo $producto[$ii]['existencia'];?></h5><br>
                </div>
            </div>
            <?php } } } ?>

        </div>
        </div>
        <?php } } ?>

    </div>
    <!-- Tab panes -->   
</div>
<?php endif; ?>








<?php 
############################ MUESTRA PRODUCTOS FAVORITOS ###########################
if (isset($_GET['Muestra_Favoritos'])) { 

$favoritos = new Login();
$favoritos = $favoritos->ListarProductosFavoritos();
$x=1;

echo $status = ( $favoritos[0]["codproducto"] == '' ? '' : '<label class="control-label"><h4>Productos Favoritos: </h4></label><br>');

if($favoritos==""){

echo "";      

} else {

for($i=0;$i<sizeof($favoritos);$i++){  ?>

<button type="button" class="button ng-scope" 
style="font-size:8px;border-radius:5px;width:69px; height:50px;cursor:pointer;"

ert-add-pending-addition="" ng-click="afterClick()" ng-repeat="product in ::getFavouriteProducts()" OnClick="DoAction('<?php echo $favoritos[$i]['codproducto']; ?>','<?php echo $favoritos[$i]['producto']; ?>','<?php echo $favoritos[$i]['codcategoria']; ?>','<?php echo $precioconiva = ( $favoritos[$i]['ivaproducto'] == 'SI' ? $favoritos[$i]['preciocompra'] : "0.00"); ?>','<?php echo $favoritos[$i]['preciocompra']; ?>','<?php echo $favoritos[$i]['precioventa']; ?>','<?php echo $favoritos[$i]['ivaproducto']; ?>','<?php echo $favoritos[$i]['existencia']; ?>');" title="<?php echo $favoritos[$i]['producto'];?>">

<?php if (file_exists("./fotos/".$favoritos[$i]["codproducto"].".jpg")){

echo "<img src='./fotos/".$favoritos[$i]['codproducto'].".jpg?' alt='x' style='border-radius:4px;width:40px;height:35px;'>"; 
}else{
echo "<img src='./fotos/producto.png' alt='x' style='border-radius:4px;width:40px;height:35px;'>";  
} ?>

<span class="product-label ng-binding "><?php echo getSubString($favoritos[$i]['producto'], 8);?></span>
</button>

<?php  if($x==8){ echo "<div class='clearfix'></div>"; $x=0; } $x++; } }

echo $status = ( $favoritos[0]["codproducto"] == '' ? '' : '<hr>'); ?>

<?php  }
############################ MUESTRA PRODUCTOS FAVORITOS ###########################
?>


<script type="text/javascript">
$(document).ready(function() {

    //  search product
   $("#busquedaproductov").keyup(function(){
      // Retrieve the input field text
      var filter = $(this).val();
      // Loop through the list
      $("#productList2 #proname").each(function(){
         // If the list item does not contain the text phrase fade it out
         if ($(this).text().search(new RegExp(filter, "i")) < 0) {
             $(this).parent().parent().parent().hide();
         // Show the list item if the phrase matches
         } else {
             $(this).parent().parent().parent().show();
         }
      });
   });
});


$(".categories").on("click", function () {
   // Retrieve the input field text
   var filter = $(this).attr('id');
   $(this).parent().children().removeClass('selectedGat');

   $(this).addClass('selectedGat');
   // Loop through the list
   $("#productList2 #category").each(function(){
      // If the list item does not contain the text phrase fade it out
      if ($(this).val().search(new RegExp(filter, "i")) < 0) {
         $(this).parent().parent().parent().hide();
         // Show the list item if the phrase matches
      } else {
         $(this).parent().parent().parent().show();
      }
   });
});

</script>