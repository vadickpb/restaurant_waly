<?php
require_once("class/class.php");

header('Content-Type: application/json');

################ GRAFICO PRODUCTOS MAS VENDIDOS ########################
if (isset($_GET['ProductosVendidos'])):

$prod = new Login();
$p = $prod->ProductosMasVendidos();

$data = array();
foreach ($p as $row) {
	$data[] = $row;
}

echo json_encode($data);

endif;



################ GRAFICO VENTAS POR USUARIOS ########################
if (isset($_GET['VentasxUsuarios'])):

$user = new Login();
$u = $user->VentasxUsuarios();

$data = array();
foreach ($u as $row) {
	$data[] = $row;
}

echo json_encode($data);

endif;
?>