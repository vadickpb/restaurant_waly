// FUNCION AUTOCOMPLETE 
$(function() {  
    var animales = ["Ardilla roja", "Gato", "Gorila occidental",  
      "Leon", "Oso pardo", "Perro", "Tigre de Bengala"];  
      
    $("#prueba").autocomplete({  
      source: animales  
    });  
});

// FUNCION AUTOCOMPLETE
$(function() {

    $("#busquedatipo").keyup(function() {

        var tipo = $('input:radio[name=tipoentrada]:checked').val(); 

        if (tipo == "") {

            $("#tipoentrada").focus();
            $('#tipoentrada').css('border-color', '#2cabe3');
            $("#busquedatipo").val("");
            swal("Oops", "POR FAVOR SELECCIONE EL TIPO DE COMPRA!", "error");
            return false;

        } else if (tipo == "PRODUCTO") {

            $("#busquedatipo").autocomplete({
            source: "class/buscaproducto.php",
            minLength: 1,
            select: function(event, ui) {
                $('#codproducto').val(ui.item.codproducto);
                $('#producto').val(ui.item.producto);
                $('#codcategoria').val(ui.item.codcategoria);
                $('#categorias').val(ui.item.nomcategoria);
                $('#preciocompra').val(ui.item.preciocompra);
                $('#precioventa').val(ui.item.precioventa);
                $('#precioconiva').val((ui.item.ivaproducto == "SI") ? ui.item.preciocompra : "0.00");
                $('#existencia').val(ui.item.existencia);
                $('#ivaproducto').val(ui.item.ivaproducto);
                $('#descproducto').val(ui.item.descproducto);
                $("#cantidad").focus();
            }
          });

          return false;

        } else if (tipo == "INGREDIENTE") {

            $("#busquedatipo").autocomplete({
            source: "class/buscaingrediente.php",
            minLength: 1,
            select: function(event, ui) {
              $('#codproducto').val(ui.item.codingrediente);
                $('#producto').val(ui.item.nomingrediente);
                $('#codcategoria').val(ui.item.codmedida);
                $('#categorias').val(ui.item.nommedida);
                $('#preciocompra').val(ui.item.preciocompra);
                $('#precioventa').val(ui.item.precioventa);
                $('#precioconiva').val((ui.item.ivaingrediente == "SI") ? ui.item.precioventa : "0.00");
                $('#existencia').val(ui.item.cantingrediente);
                $('#ivaproducto').val(ui.item.ivaingrediente);
                $('#descproducto').val(ui.item.descingrediente);
                $("#cantidad").focus();
            }
          });

        }
    });
}); 

$(function() {
    $("#busquedakardexproducto").autocomplete({
        source: "class/buscaproducto.php",
        minLength: 1,
        select: function(event, ui) {
            $('#codproducto').val(ui.item.codproducto);
        }
    });
});

$(function() {
    $("#busquedakardexingrediente").autocomplete({
        source: "class/buscaingrediente.php",
        minLength: 1,
        select: function(event, ui) {
            $('#codingrediente').val(ui.item.codingrediente);
        }
    });
});

$(function() {
           $("#categorias").autocomplete({
           source: "class/buscacategoria.php",
           minLength: 1,
           select: function(event, ui) { 
           $('#codcategoria').val(ui.item.codcategoria);
           }  
        });
 });

$(function() {
    $("#busquedaproductoc").autocomplete({
        source: "class/buscaproducto.php",
        minLength: 1,
        select: function(event, ui) {
            $('#codproducto').val(ui.item.codproducto);
            $('#producto').val(ui.item.producto);
            $('#codcategoria').val(ui.item.codcategoria);
            $('#categoria').val(ui.item.nomcategoria);
            $('#preciocompra').val(ui.item.preciocompra);
            $('#precioventa').val(ui.item.precioventa);
            $('#precioconiva').val((ui.item.ivaproducto == "SI") ? ui.item.preciocompra : "0.00");
            $('#existencia').val(ui.item.existencia);
            $('#ivaproducto').val(ui.item.ivaproducto);
            $('#descproducto').val(ui.item.descproducto);
            $("#cantidad").focus();
        }
    });
});

$(function() {
    $("#busquedaproductov").autocomplete({
        source: "class/buscaproducto.php",
        minLength: 1,
        select: function(event, ui) {
            $('#codproducto').val(ui.item.codproducto);
            $('#producto').val(ui.item.producto);
            $('#codcategoria').val(ui.item.codcategoria);
            $('#categorias').val(ui.item.nomcategoria);
            $('#preciocompra').val(ui.item.preciocompra);
            $('#precioventa').val(ui.item.precioventa);
            $('#precioconiva').val((ui.item.ivaproducto == "SI") ? ui.item.precioventa : "0.00");
            $('#existencia').val(ui.item.existencia);
            $('#ivaproducto').val(ui.item.ivaproducto);
            $('#descproducto').val(ui.item.descproducto);
            $('#fechaelaboracion').val((ui.item.fechaelaboracion == "0000-00-00") ? "" : ui.item.fechaelaboracion);
            $('#fechaexpiracion').val((ui.item.fechaexpiracion == "0000-00-00") ? "" : ui.item.fechaexpiracion);
            $("#cantidad").focus();
            setTimeout(function() {
                var e = jQuery.Event("keypress");
                e.which = 13;
                e.keyCode = 13;
                $("#busquedaproductov").trigger(e);
            }, 100);
        }
    });
});


$(function() {
    $("#busquedaingrediente").autocomplete({
        source: "class/buscaingrediente.php",
        minLength: 1,
        select: function(event, ui) {
            $('#codingrediente').val(ui.item.codingrediente);
            $('#nomingrediente').val(ui.item.nomingrediente);
            $('#codmedida').val(ui.item.codmedida);
            $('#medida').val(ui.item.medida);
            $('#preciocompra').val(ui.item.preciocompra);
            $('#precioventa').val(ui.item.precioventa);
            $('#precioconiva').val((ui.item.ivaingrediente == "SI") ? ui.item.precioventa : "0.00");
            $('#cantingrediente').val(ui.item.cantingrediente);
            $('#ivaingrediente').val(ui.item.ivaingrediente);
            $('#descingrediente').val(ui.item.descingrediente);
            $('#fechaexpiracion').val((ui.item.fechaexpiracion == "0000-00-00") ? "" : ui.item.fechaexpiracion);
            $("#cantidad").focus();
        }
    });
});

function autocompletar(contador) {
    contador = contador.replace("agregaingrediente[]", "");
    $("#agregaingrediente" + contador).autocomplete({
        source: "class/buscaingrediente.php",
        minLength: 1,
        select: function(event, ui) {
            $('#codingrediente' + contador).val(ui.item.codingrediente);
            $('#medida' + contador).val(ui.item.nommedida);
        }
    });
}

$(function() {
           $("#busqueda").autocomplete({
           source: "class/buscacliente.php",
           minLength: 1,
           select: function(event, ui) { 
          $('#codcliente').val(ui.item.codcliente);
          $('#creditoinicial').val(ui.item.limitecredito);
          $('#montocredito').val(ui.item.creditodisponible);
          $('#creditodisponible').val(ui.item.creditodisponible);
          $('#TextCliente').text(ui.item.nomcliente);
          $('#TextCredito').text(ui.item.creditodisponible);
           }  
      });
 });