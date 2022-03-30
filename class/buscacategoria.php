<?php
	include('class.consultas.php');
	$filtro    = $_GET["term"];
	$Json      = new Json;
	$categoria  = $Json->BuscaCategoria($filtro);
	echo  json_encode($categoria);
	
?>  