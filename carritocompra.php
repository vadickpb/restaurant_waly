<?php
//CARRITO DE ENTRADAS DE PRODUCTOS
session_start();
$ObjetoCarrito   = json_decode($_POST['MiCarrito']);
if ($ObjetoCarrito->Codigo=="vaciar") {
    unset($_SESSION["CarritoCompra"]);
} else {
    if (isset($_SESSION['CarritoCompra'])) {
        $carrito=$_SESSION['CarritoCompra'];
        if (isset($ObjetoCarrito->Codigo)) {
            $txtCodigo = $ObjetoCarrito->Codigo;
            $producto= $ObjetoCarrito->Producto;
            $codcategoria = $ObjetoCarrito->Codcategoria;
            $categorias = $ObjetoCarrito->Categorias;
            $precio = $ObjetoCarrito->Precio;
            $precio2 = $ObjetoCarrito->Precio2;
            $descproductofact = $ObjetoCarrito->DescproductoFact;
            $descproducto = $ObjetoCarrito->Descproducto;
            $ivaproducto = $ObjetoCarrito->Ivaproducto;
            $precioconiva = $ObjetoCarrito->Precioconiva;
            $lote = $ObjetoCarrito->Lote;
            $fechaelaboracion = $ObjetoCarrito->Fechaelaboracion;
            $fechaexpiracion = $ObjetoCarrito->Fechaexpiracion;
            $tipoentrada = $ObjetoCarrito->Tipoentrada;
            $cantidad = $ObjetoCarrito->Cantidad;
            $opCantidad = $ObjetoCarrito->opCantidad;

            //array_search("whatisearchfor2", array_column(array_column($response, "types"), 0));

            $donde  = array_search($txtCodigo, array_column($carrito, 'txtCodigo'));

            if ($donde !== FALSE) {
                if ($opCantidad === '=') {
                    $cuanto = $cantidad;
                } else {
                    $cuanto = $carrito[$donde]['cantidad'] + $cantidad;
                }
                $carrito[$donde] = array(
                    "txtCodigo"=>$txtCodigo,
                    "producto"=>$producto,
                    "codcategoria"=>$codcategoria,
                    "categorias"=>$categorias,
                    "precio"=>$precio,
                    "precio2"=>$precio2,
                    "descproductofact"=>$descproductofact,
                    "descproducto"=>$descproducto,
                    "ivaproducto"=>$ivaproducto,
                    "precioconiva"=>$precioconiva,
                    "lote"=>$lote,
                    "fechaelaboracion"=>$fechaelaboracion,
                    "fechaexpiracion"=>$fechaexpiracion,
                    "tipoentrada"=>$tipoentrada,
                    "cantidad"=>$cuanto
                );
            } else {
                $carrito[]=array(
                    "txtCodigo"=>$txtCodigo,
                    "producto"=>$producto,
                    "codcategoria"=>$codcategoria,
                    "categorias"=>$categorias,
                    "precio"=>$precio,
                    "precio2"=>$precio2,
                    "descproductofact"=>$descproductofact,
                    "descproducto"=>$descproducto,
                    "ivaproducto"=>$ivaproducto,
                    "precioconiva"=>$precioconiva,
                    "lote"=>$lote,
                    "fechaelaboracion"=>$fechaelaboracion,
                    "fechaexpiracion"=>$fechaexpiracion,
                    "tipoentrada"=>$tipoentrada,
                    "cantidad"=>$cantidad
                );
            }
        }
    } else {
        $txtCodigo = $ObjetoCarrito->Codigo;
        $producto = $ObjetoCarrito->Producto;
        $codcategoria = $ObjetoCarrito->Codcategoria;
        $categorias = $ObjetoCarrito->Categorias;
        $precio = $ObjetoCarrito->Precio;
        $precio2 = $ObjetoCarrito->Precio2;
        $descproductofact = $ObjetoCarrito->DescproductoFact;
        $descproducto = $ObjetoCarrito->Descproducto;
        $ivaproducto = $ObjetoCarrito->Ivaproducto;
        $precioconiva = $ObjetoCarrito->Precioconiva;
        $lote = $ObjetoCarrito->Lote;
        $fechaelaboracion = $ObjetoCarrito->Fechaelaboracion;
        $fechaexpiracion = $ObjetoCarrito->Fechaexpiracion;
        $tipoentrada = $ObjetoCarrito->Tipoentrada;
        $cantidad = $ObjetoCarrito->Cantidad;
        $carrito[] = array(
            "txtCodigo"=>$txtCodigo,
            "producto"=>$producto,
            "codcategoria"=>$codcategoria,
            "categorias"=>$categorias,
            "precio"=>$precio,
            "precio2"=>$precio2,
            "descproductofact"=>$descproductofact,
            "descproducto"=>$descproducto,
            "ivaproducto"=>$ivaproducto,
            "precioconiva"=>$precioconiva,
            "lote"=>$lote,
            "fechaelaboracion"=>$fechaelaboracion,
            "fechaexpiracion"=>$fechaexpiracion,
            "tipoentrada"=>$tipoentrada,
            "cantidad"=>$cantidad
        );
    }
    $carrito = array_values(
        array_filter($carrito, function($v) {
            return $v['cantidad'] > 0;
        })
    );
    $_SESSION['CarritoCompra'] = $carrito;
    echo json_encode($_SESSION['CarritoCompra']);
}
