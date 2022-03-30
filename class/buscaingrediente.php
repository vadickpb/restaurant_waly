<?php
	include('class.consultas.php');
	$filtro    = $_GET["term"];
	$Json      = new Json;
	$kardex  = $Json->BuscaIngrediente($filtro);
	echo  json_encode($kardex);
?>  