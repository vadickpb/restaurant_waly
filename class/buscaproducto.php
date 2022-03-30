<?php
	include('class.consultas.php');
	$filtro    = $_GET["term"];
	$Json      = new Json;
	$producto  = $Json->BuscaProducto($filtro);
	echo  json_encode($producto);
	
?>  