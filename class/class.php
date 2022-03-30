<?php
session_start();
require_once("classconexion.php");
include_once('funciones_basicas.php');
include "class.phpmailer.php";
include "class.smtp.php";

ini_set('memory_limit', '-1'); //evita el error Fatal error: Allowed memory size of X bytes exhausted (tried to allocate Y bytes)...
ini_set('max_execution_time', 3800); // es lo mismo que set_time_limit(300) ; 

################################## CLASE LOGIN ###################################
class Login extends Db
{
	public function __construct()
	{
		parent::__construct();
	} 	

###################### FUNCION PARA EXPIRAR SESSION POR INACTIVIDAD ####################
	public function ExpiraSession(){


	if(!isset($_SESSION['usuario'])){// Esta logeado?.
		header("Location: logout.php"); 
	}

	//Verifico el tiempo si esta seteado, caso contrario lo seteo.
	if(isset($_SESSION['time'])){
		$tiempo = $_SESSION['time'];
	}else{
		$tiempo = strtotime(date("Y-m-d h:i:s"));
	}

	$inactividad =7200; //(1 hora de cierre sesion )600 equivale a 10 minutos

	$actual =  strtotime(date("Y-m-d h:i:s"));

	if( ($actual-$tiempo) >= $inactividad){
		?>					
		<script type='text/javascript' language='javascript'>
			alert('SU SESSION A EXPIRADO \nPOR FAVOR LOGUEESE DE NUEVO PARA ACCEDER AL SISTEMA') 
			document.location.href='logout'	 
		</script> 
		<?php

	}else{

		$_SESSION['time'] =$actual;

	} 
}

###################### FUNCION PARA EXPIRAR SESSION POR INACTIVIDAD ####################



#################### FUNCION PARA ACCEDER AL SISTEMA ####################
public function Logueo()
{
	self::SetNames();
	if(empty($_POST["usuario"]) or empty($_POST["password"]))
	{
		echo "1";
		exit;
	}

	$pass = sha1(md5($_POST["password"]));
	$sql = "SELECT * FROM usuarios WHERE usuario = ? AND password = ? AND status = 1";
	$stmt = $this->dbh->prepare($sql);
	$stmt->execute(array($_POST["usuario"],$pass));
	$num = $stmt->rowCount();
	if($num == 0)
	{
		echo "2";
		exit;
	}
	else
	{
			
		if($row = $stmt->fetch(PDO::FETCH_ASSOC))
		{
			$p[]=$row;
		}
		
		######### DATOS DEL USUARIO ###########
		$_SESSION["codigo"] = $p[0]["codigo"];
		$_SESSION["dni"] = $p[0]["dni"];
		$_SESSION["nombres"] = $p[0]["nombres"];
		$_SESSION["sexo"] = $p[0]["sexo"];
		$_SESSION["direccion"] = $p[0]["direccion"];
		$_SESSION["telefono"] = $p[0]["telefono"];
		$_SESSION["email"] = $p[0]["email"];
		$_SESSION["usuario"] = $p[0]["usuario"];
		$_SESSION["password"] = $p[0]["password"];
		$_SESSION["nivel"] = $p[0]["nivel"];
		$_SESSION["status"] = $p[0]["status"];
		$_SESSION["ingreso"] = limpiar(date("d-m-Y h:i:s A"));

		$query = "INSERT INTO log VALUES (null, ?, ?, ?, ?, ?);";
		$stmt = $this->dbh->prepare($query);
		$stmt->bindParam(1,$a);
		$stmt->bindParam(2,$b);
		$stmt->bindParam(3,$c);
		$stmt->bindParam(4,$d);
		$stmt->bindParam(5,$e);

		$a = limpiar($_SERVER['REMOTE_ADDR']);
		$b = limpiar(date("Y-m-d h:i:s"));
		$c = limpiar($_SERVER['HTTP_USER_AGENT']);
		$d = limpiar($_SERVER['PHP_SELF']);
		$e = limpiar($_POST["usuario"]);
		$stmt->execute();

		switch($_SESSION["nivel"])
		{
			case 'ADMINISTRADOR(A)':
			$_SESSION["acceso"]="administrador";

			?>

			<script type="text/javascript">
				window.location="panel";
			</script>

			<?php
			break;
			case 'SECRETARIA':
			$_SESSION["acceso"]="secretaria";

			?>

			<script type="text/javascript">
				window.location="panel";
			</script>

			<?php
			break;
			case 'CAJERO(A)':
			$_SESSION["acceso"]="cajero";

			?>

			<script type="text/javascript">
				window.location="panel";
			</script>

			<?php
			break;
			case 'MESERO(A)':
			$_SESSION["acceso"]="mesero";
			?>

			<script type="text/javascript">
				window.location="panel";
			</script>

			<?php
			break;
			case 'COCINERO(A)':
			$_SESSION["acceso"]="cocinero";
			?>

			<script type="text/javascript">
				window.location="panel";
			</script>

			<?php
			break;
			case 'REPARTIDOR':
			$_SESSION["acceso"]="repartidor";
			?>

			<script type="text/javascript">
				window.location="panel";
			</script>
			
			<?php
			break;
		}
	}
		//print_r($_POST);
	exit;
}
#################### FUNCION PARA ACCEDER AL SISTEMA ####################



















######################## FUNCION RECUPERAR Y ACTUALIZAR PASSWORD #######################

########################### FUNCION PARA RECUPERAR CLAVE #############################
public function RecuperarPassword()
{
	self::SetNames();
	if(empty($_POST["email"]))
	{
		echo "1";
		exit;
	}

	$sql = " SELECT * FROM usuarios WHERE email = ?";
	$stmt = $this->dbh->prepare($sql);
	$stmt->execute(array($_POST["email"]));
	$num = $stmt->rowCount();
	if($num==0)
	{
		echo "2";
		exit;
	}
	else
	{
			
		if($row = $stmt->fetch(PDO::FETCH_ASSOC))
		{
			$pa[] = $row;
		}
		$id = $pa[0]["codigo"];
		$nombres = $pa[0]["nombres"];
		$password = $pa[0]["password"];
	}
	
	$sql = " UPDATE usuarios set "
	." password = ? "
	." where "
	." codigo = ?;
	";
	$stmt = $this->dbh->prepare($sql);
	$stmt->bindParam(1, $password);
	$stmt->bindParam(2, $codigo);

	$codigo = $id;
	$pass = strtoupper(generar_clave(10));
	$password = sha1(md5($pass));
	$stmt->execute();

	$para = $_POST["email"];
	$titulo = 'RECUPERACION DE PASSWORD';
	$header = 'From: ' . 'SISTEMA PARA LA GESTION DE VENTAS E INVENTARIO SOFT RESTAURANT';
	$msjCorreo = " Nombre: $nombres\n Nuevo Passw: $pass\n Mensaje: Por favor use esta nueva clave de acceso para ingresar al Sistema de Ventas E Inventario\n";
	mail($para, $titulo, $msjCorreo, $header);

	echo "<span class='fa fa-check-square-o'></span> SU NUEVA CLAVE DE ACCESO LE FUE ENVIADA A SU CORREO ELECTRONICO EXITOSAMENTE";
}	
############################# FUNCION PARA RECUPERAR CLAVE ############################

########################## FUNCION PARA ACTUALIZAR PASSWORD ############################
public function ActualizarPassword()
{
	self::SetNames();
	if(empty($_POST["dni"]))
	{
		echo "1";
		exit;
	}

	if(sha1(md5($_POST["password"]))==$_POST["clave"]){

		echo "2";
		exit;

	} else {
		
		$sql = " UPDATE usuarios set "
		." usuario = ?, "
		." password = ? "
		." where "
		." codigo = ?;
		";
		$stmt = $this->dbh->prepare($sql);
		$stmt->bindParam(1, $usuario);
		$stmt->bindParam(2, $password);
		$stmt->bindParam(3, $codigo);	

		$usuario = limpiar($_POST["usuario"]);
		$password = sha1(md5($_POST["password"]));
		$codigo = limpiar($_POST["codigo"]);
		$stmt->execute();
		
		echo "<span class='fa fa-check-square-o'></span> SU CLAVE DE ACCESO FUE ACTUALIZADA EXITOSAMENTE, SER&Aacute; EXPULSADO DE SU SESI&Oacute;N Y DEBER&Aacute; DE ACCEDER NUEVAMENTE";
		?>
		<script>
			function redireccionar(){location.href="logout.php";}
			setTimeout ("redireccionar()", 3000);
		</script>
		<?php
		exit;
	}
}
########################## FUNCION PARA ACTUALIZAR PASSWORD  ############################

####################### FUNCION RECUPERAR Y ACTUALIZAR PASSWORD ########################


























###################### FUNCION CONFIGURACION GENERAL DEL SISTEMA #######################

######################## FUNCION ID CONFIGURACION DEL SISTEMA #########################
public function ConfiguracionPorId()
{
	self::SetNames();
	$sql = " SELECT 
	configuracion.id,
	configuracion.documsucursal,
	configuracion.cuit,
	configuracion.nomsucursal,
	configuracion.tlfsucursal,
	configuracion.correosucursal,
	configuracion.id_provincia,
	configuracion.id_departamento,
	configuracion.direcsucursal,
	configuracion.nroactividad,
	configuracion.iniciofactura,
	configuracion.fechaautorizacion,
	configuracion.llevacontabilidad,
	configuracion.documencargado,
	configuracion.dniencargado,
	configuracion.nomencargado,
	configuracion.tlfencargado,
	configuracion.descuentoglobal,
	configuracion.porcentaje,
	configuracion.codmoneda,
	configuracion.codmoneda2,
	documentos.documento,
	documentos2.documento AS documento2,
	tiposmoneda.moneda,
	tiposmoneda2.moneda AS moneda2,
	tiposmoneda.simbolo,
	tiposmoneda2.simbolo AS simbolo2,
	provincias.provincia,
	departamentos.departamento
	FROM configuracion 
	LEFT JOIN documentos ON configuracion.documsucursal = documentos.coddocumento
	LEFT JOIN documentos AS documentos2 ON configuracion.documencargado = documentos2.coddocumento 
	LEFT JOIN provincias ON configuracion.id_provincia = provincias.id_provincia 
	LEFT JOIN departamentos ON configuracion.id_departamento = departamentos.id_departamento 
	LEFT JOIN tiposmoneda ON configuracion.codmoneda = tiposmoneda.codmoneda
	LEFT JOIN tiposmoneda AS tiposmoneda2 ON configuracion.codmoneda2 = tiposmoneda2.codmoneda WHERE configuracion.id = ?";
	$stmt = $this->dbh->prepare($sql);
	$stmt->execute(array('1'));
	$num = $stmt->rowCount();
	if($num==0)
	{
		echo "";
	}
	else
	{
		if($row = $stmt->fetch(PDO::FETCH_ASSOC))
		{
			$this->p[] = $row;
		}
		return $this->p;
		$this->dbh=null;
	}
}
######################## FUNCION ID CONFIGURACION DEL SISTEMA #########################

######################## FUNCION  ACTUALIZAR CONFIGURACION ##########################
public function ActualizarConfiguracion()
{

	self::SetNames();
	if(empty($_POST["cuit"]) or empty($_POST["nomsucursal"]) or empty($_POST["tlfsucursal"]))
	{
		echo "1";
		exit;
	}
	$sql = " UPDATE configuracion set "
	." documsucursal = ?, "
	." cuit = ?, "
	." nomsucursal = ?, "
	." tlfsucursal = ?, "
	." correosucursal = ?, "
	." id_provincia = ?, "
	." id_departamento = ?, "
	." direcsucursal = ?, "
	." nroactividad = ?, "
	." iniciofactura = ?, "
	." fechaautorizacion = ?, "
	." llevacontabilidad = ?, "
	." documencargado = ?, "
	." dniencargado = ?, "
	." nomencargado = ?, "
	." tlfencargado = ?, "
	." descuentoglobal = ?, "
	." porcentaje = ?, "
	." codmoneda = ?, "
	." codmoneda2 = ? "
	." where "
	." id = ?;
	";
	$stmt = $this->dbh->prepare($sql);
	$stmt->bindParam(1, $documsucursal);
	$stmt->bindParam(2, $cuit);
	$stmt->bindParam(3, $nomsucursal);
	$stmt->bindParam(4, $tlfsucursal);
	$stmt->bindParam(5, $correosucursal);
	$stmt->bindParam(6, $id_provincia);
	$stmt->bindParam(7, $id_departamento);
	$stmt->bindParam(8, $direcsucursal);
	$stmt->bindParam(9, $nroactividad);
	$stmt->bindParam(10, $iniciofactura);
	$stmt->bindParam(11, $fechaautorizacion);
	$stmt->bindParam(12, $llevacontabilidad);
	$stmt->bindParam(13, $documencargado);
	$stmt->bindParam(14, $dniencargado);
	$stmt->bindParam(15, $nomencargado);
	$stmt->bindParam(16, $tlfencargado);
	$stmt->bindParam(17, $descuentoglobal);
	$stmt->bindParam(18, $porcentaje);
	$stmt->bindParam(19, $codmoneda);
	$stmt->bindParam(20, $codmoneda2);
	$stmt->bindParam(21, $id);

	$documsucursal = limpiar($_POST["documsucursal"]);
	$cuit = limpiar($_POST["cuit"]);
	$nomsucursal = limpiar($_POST["nomsucursal"]);
	$tlfsucursal = limpiar($_POST["tlfsucursal"]);
	$correosucursal = limpiar($_POST["correosucursal"]);
	$id_provincia = limpiar($_POST["id_provincia"]);
	$id_departamento = limpiar($_POST["id_departamento"]);
	$direcsucursal = limpiar($_POST["direcsucursal"]);
	$nroactividad = limpiar($_POST["nroactividad"]);
	$iniciofactura = limpiar($_POST["iniciofactura"]);
if (limpiar($_POST['fechaautorizacion']!="") && limpiar($_POST['fechaautorizacion']!="0000-00-00")) { $fechaautorizacion = limpiar(date("Y-m-d",strtotime($_POST['fechaautorizacion']))); } else { $fechaautorizacion = limpiar('0000-00-00'); };
	$llevacontabilidad = limpiar($_POST["llevacontabilidad"]);
	$documencargado = limpiar($_POST["documencargado"]);
	$dniencargado = limpiar($_POST["dniencargado"]);
	$nomencargado = limpiar($_POST["nomencargado"]);
	$tlfencargado = limpiar($_POST["tlfencargado"]);
	$descuentoglobal = limpiar($_POST["descuentoglobal"]);
	$porcentaje = limpiar($_POST["porcentaje"]);
	$codmoneda = limpiar($_POST["codmoneda"]);
	$codmoneda2 = limpiar($_POST["codmoneda2"]);
	$id = limpiar($_POST["id"]);
	$stmt->execute();

	################## SUBIR LOGO PRINCIPAL #1 ######################################
         //datos del arhivo  
if (isset($_FILES['imagen']['name'])) { $nombre_archivo = $_FILES['imagen']['name']; } else { $nombre_archivo =''; }
if (isset($_FILES['imagen']['type'])) { $tipo_archivo = $_FILES['imagen']['type']; } else { $tipo_archivo =''; }
if (isset($_FILES['imagen']['size'])) { $tamano_archivo = $_FILES['imagen']['size']; } else { $tamano_archivo =''; }  
         //compruebo si las características del archivo son las que deseo  
	if ((strpos($tipo_archivo,'image/png')!==false)&&$tamano_archivo<200000) {  
			if (move_uploaded_file($_FILES['imagen']['tmp_name'], "fotos/".$nombre_archivo) && rename("fotos/".$nombre_archivo,"fotos/logo-principal.png"))
			
					{ 
		 ## se puede dar un aviso
					} 
		 ## se puede dar otro aviso 
				}
	################## FINALIZA SUBIR LOGO PRINCIPAL #1 ##################

	################## SUBIR LOGO PDF #1 ######################################
         //datos del arhivo  
if (isset($_FILES['imagen2']['name'])) { $nombre_archivo = $_FILES['imagen2']['name']; } else { $nombre_archivo =''; }
if (isset($_FILES['imagen2']['type'])) { $tipo_archivo = $_FILES['imagen2']['type']; } else { $tipo_archivo =''; }
if (isset($_FILES['imagen2']['size'])) { $tamano_archivo = $_FILES['imagen2']['size']; } else { $tamano_archivo =''; }  
         //compruebo si las características del archivo son las que deseo  
	if ((strpos($tipo_archivo,'image/png')!==false)&&$tamano_archivo<200000) {  
			if (move_uploaded_file($_FILES['imagen2']['tmp_name'], "fotos/".$nombre_archivo) && rename("fotos/".$nombre_archivo,"fotos/logo-pdf.png"))
			
					{ 
		 ## se puede dar un aviso
					} 
		 ## se puede dar otro aviso 
				}
	################## FINALIZA SUBIR LOGO PDF #1 ######################################

	echo "<span class='fa fa-check-square-o'></span> LOS DATOS DE CONFIGURACI&Oacute;N FUERON ACTUALIZADOS EXITOSAMENTE";
	exit;
}
######################## FUNCION  ACTUALIZAR CONFIGURACION #######################

#################### FIN DE FUNCION CONFIGURACION GENERAL DEL SISTEMA ##################


























################################## CLASE USUARIOS #####################################

############################## FUNCION REGISTRAR USUARIOS ##############################
public function RegistrarUsuarios()
{
	self::SetNames();
	if(empty($_POST["nombres"]) or empty($_POST["usuario"]) or empty($_POST["password"]))
	{
		echo "1";
		exit;
	}

	$sql = " SELECT dni FROM usuarios WHERE dni = ?";
	$stmt = $this->dbh->prepare($sql);
	$stmt->execute(array($_POST["dni"]));
	$num = $stmt->rowCount();
	if($num > 0)
	{
		
		echo "4";
		exit;
	}
	else
	{
		$sql = " SELECT email FROM usuarios WHERE email = ?";
		$stmt = $this->dbh->prepare($sql);
		$stmt->execute(array($_POST["email"]));
		$num = $stmt->rowCount();
		if($num > 0)
		{

			echo "5";
			exit;
		}
		else
		{
			$sql = " SELECT usuario FROM usuarios WHERE usuario = ?";
			$stmt = $this->dbh->prepare($sql);
			$stmt->execute(array($_POST["usuario"]));
			$num = $stmt->rowCount();
			if($num == 0)
			{
				$query = " INSERT INTO usuarios values (null, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);";
				$stmt = $this->dbh->prepare($query);
				$stmt->bindParam(1, $dni);
				$stmt->bindParam(2, $nombres);
				$stmt->bindParam(3, $sexo);
				$stmt->bindParam(4, $direccion);
				$stmt->bindParam(5, $telefono);
				$stmt->bindParam(6, $email);
				$stmt->bindParam(7, $usuario);
				$stmt->bindParam(8, $password);
				$stmt->bindParam(9, $nivel);
				$stmt->bindParam(10, $status);
				$stmt->bindParam(11, $comision);

				$dni = limpiar($_POST["dni"]);
				$nombres = limpiar($_POST["nombres"]);
				$sexo = limpiar($_POST["sexo"]);
				$direccion = limpiar($_POST["direccion"]);
				$telefono = limpiar($_POST["telefono"]);
				$email = limpiar($_POST["email"]);
				$usuario = limpiar($_POST["usuario"]);
				$password = sha1(md5($_POST["password"]));
				$nivel = limpiar($_POST["nivel"]);
				$status = limpiar($_POST["status"]);
				$comision = limpiar($_POST["comision"]);
				$stmt->execute();

		################## SUBIR FOTO DE USUARIOS ######################################
         //datos del arhivo  
				if (isset($_FILES['imagen']['name'])) { $nombre_archivo = $_FILES['imagen']['name']; } else { $nombre_archivo =''; }
				if (isset($_FILES['imagen']['type'])) { $tipo_archivo = $_FILES['imagen']['type']; } else { $tipo_archivo =''; }
				if (isset($_FILES['imagen']['size'])) { $tamano_archivo = $_FILES['imagen']['size']; } else { $tamano_archivo =''; }  
         //compruebo si las características del archivo son las que deseo  
				if ((strpos($tipo_archivo,'image/jpeg')!==false)&&$tamano_archivo<50000) 
				{  
					if (move_uploaded_file($_FILES['imagen']['tmp_name'], "fotos/".$nombre_archivo) && rename("fotos/".$nombre_archivo,"fotos/".$_POST["dni"].".jpg"))
					{ 
		 ## se puede dar un aviso
					} 
		 ## se puede dar otro aviso 
				}
		################## FINALIZA SUBIR FOTO DE USUARIOS ##################

				echo "<span class='fa fa-check-square-o'></span> EL USUARIO HA SIDO REGISTRADO EXITOSAMENTE";
				exit;
			}
			else
			{
				echo "6";
				exit;
			}
		}
	}
}
############################# FUNCION REGISTRAR USUARIOS ###############################

############################# FUNCION LISTAR USUARIOS ################################
public function ListarUsuarios()
{
	self::SetNames();

	$sql = "SELECT * FROM usuarios";
	foreach ($this->dbh->query($sql) as $row)
	{
		$this->p[] = $row;
	}
	return $this->p;
	$this->dbh=null;
}
############################## FUNCION LISTAR USUARIOS ################################

###################### FUNCION LISTAR USUARIOS REPARTIDORES ######################
public function ListarRepartidores()
{
	self::SetNames();
	$sql = " SELECT * FROM usuarios WHERE nivel = 'REPARTIDOR'";
	foreach ($this->dbh->query($sql) as $row)
	{
		$this->p[] = $row;
	}
	return $this->p;
	$this->dbh=null;
}
##################### FUNCION LISTAR USUARIOS REPARTIDORES #########################

########################### FUNCION LISTAR LOGS DE USUARIOS ###########################
public function ListarLogs()
{
	self::SetNames();
	$sql = "SELECT * FROM log";
	foreach ($this->dbh->query($sql) as $row)
	{
		$this->p[] = $row;
	}
	return $this->p;
	$this->dbh=null;

   }
########################### FUNCION LISTAR LOGS DE USUARIOS ###########################

############################ FUNCION ID USUARIOS #################################
public function UsuariosPorId()
{
	self::SetNames();
	$sql = "SELECT * FROM usuarios WHERE codigo = ?";
	$stmt = $this->dbh->prepare($sql);
	$stmt->execute(array(decrypt($_GET["codigo"])));
	$num = $stmt->rowCount();
	if($num==0)
	{
		echo "";
	}
	else
	{
		if($row = $stmt->fetch(PDO::FETCH_ASSOC))
		{
			$this->p[] = $row;
		}
		return $this->p;
		$this->dbh=null;
	}
}
############################ FUNCION ID USUARIOS #################################

############################ FUNCION ACTUALIZAR USUARIOS ############################
public function ActualizarUsuarios()
{

	self::SetNames();
	if(empty($_POST["dni"]) or empty($_POST["nombres"]) or empty($_POST["usuario"]) or empty($_POST["password"]))
	{
		echo "1";
		exit;
	}

	$sql = "SELECT * FROM usuarios WHERE codigo != ? AND dni = ?";
	$stmt = $this->dbh->prepare($sql);
	$stmt->execute(array($_POST["codigo"],$_POST["dni"]));
	$num = $stmt->rowCount();
	if($num > 0)
	{
		echo "2";
		exit;
	}
	else
	{
		$sql = " SELECT email FROM usuarios WHERE codigo != ? AND email = ?";
		$stmt = $this->dbh->prepare($sql);
		$stmt->execute(array($_POST["codigo"],$_POST["email"]));
		$num = $stmt->rowCount();
		if($num > 0)
		{
			echo "3";
			exit;
		}
		else
		{
			$sql = " SELECT usuario FROM usuarios WHERE codigo != ? AND usuario = ?";
			$stmt = $this->dbh->prepare($sql);
			$stmt->execute(array($_POST["codigo"],$_POST["usuario"]));
			$num = $stmt->rowCount();
			if($num == 0)
			{
				$sql = " UPDATE usuarios set "
				." dni = ?, "
				." nombres = ?, "
				." sexo = ?, "
				." direccion = ?, "
				." telefono = ?, "
				." email = ?, "
				." usuario = ?, "
				." password = ?, "
				." nivel = ?, "
				." status = ?, "
				." comision = ? "
				." where "
				." codigo = ?;
				";
				$stmt = $this->dbh->prepare($sql);
				$stmt->bindParam(1, $dni);
				$stmt->bindParam(2, $nombres);
				$stmt->bindParam(3, $sexo);
				$stmt->bindParam(4, $direccion);
				$stmt->bindParam(5, $telefono);
				$stmt->bindParam(6, $email);
				$stmt->bindParam(7, $usuario);
				$stmt->bindParam(8, $password);
				$stmt->bindParam(9, $nivel);
				$stmt->bindParam(10, $status);
				$stmt->bindParam(11, $comision);
				$stmt->bindParam(12, $codigo);

				$dni = limpiar($_POST["dni"]);
				$nombres = limpiar($_POST["nombres"]);
				$sexo = limpiar($_POST["sexo"]);
				$direccion = limpiar($_POST["direccion"]);
				$telefono = limpiar($_POST["telefono"]);
				$email = limpiar($_POST["email"]);
				$usuario = limpiar($_POST["usuario"]);
				$password = sha1(md5($_POST["password"]));
				$nivel = limpiar($_POST["nivel"]);
				$status = limpiar($_POST["status"]);
				$comision = limpiar($_POST["comision"]);
				$codigo = limpiar($_POST["codigo"]);
				$stmt->execute();

		################## SUBIR FOTO DE USUARIOS ######################################
         //datos del arhivo  
				if (isset($_FILES['imagen']['name'])) { $nombre_archivo = $_FILES['imagen']['name']; } else { $nombre_archivo =''; }
				if (isset($_FILES['imagen']['type'])) { $tipo_archivo = $_FILES['imagen']['type']; } else { $tipo_archivo =''; }
				if (isset($_FILES['imagen']['size'])) { $tamano_archivo = $_FILES['imagen']['size']; } else { $tamano_archivo =''; }  
         //compruebo si las características del archivo son las que deseo  
				if ((strpos($tipo_archivo,'image/jpeg')!==false)&&$tamano_archivo<50000) 
				{  
					if (move_uploaded_file($_FILES['imagen']['tmp_name'], "fotos/".$nombre_archivo) && rename("fotos/".$nombre_archivo,"fotos/".$_POST["dni"].".jpg"))
					{ 
		 ## se puede dar un aviso
					} 
		 ## se puede dar otro aviso 
				}
		################## FINALIZA SUBIR FOTO DE USUARIOS ######################################

				echo "<span class='fa fa-check-square-o'></span> EL USUARIO HA SIDO ACTUALIZADO EXITOSAMENTE";
				exit;

			}
			else
			{
				echo "4";
				exit;
			}
		}
	}
}
############################ FUNCION ACTUALIZAR USUARIOS ############################

############################# FUNCION ELIMINAR USUARIOS ################################
public function EliminarUsuarios()
{
	self::SetNames();
	if ($_SESSION['acceso'] == "administrador") {

		$sql = "SELECT codigo FROM ventas WHERE codigo = ?";
		$stmt = $this->dbh->prepare($sql);
		$stmt->execute(array(decrypt($_GET["codigo"])));
		$num = $stmt->rowCount();
		if($num == 0)
		{

			$sql = " DELETE FROM usuarios WHERE codigo = ?";
			$stmt = $this->dbh->prepare($sql);
			$stmt->bindParam(1,$codigo);
			$codigo = decrypt($_GET["codigo"]);
			$stmt->execute();

			$dni = decrypt($_GET["dni"]);
			if (file_exists("fotos/".$dni.".jpg")){
		//funcion para eliminar una carpeta con contenido
			$archivos = "fotos/".$dni.".jpg";		
			unlink($archivos);
			}

			echo "1";
			exit;

		} else {
		   
			echo "2";
			exit;
		  } 
			
		} else {
		
		echo "3";
		exit;
	 }	
}
############################## FUNCION ELIMINAR USUARIOS ##############################

############################ FIN DE CLASE USUARIOS ################################


























################################ CLASE PROVINCIAS ##################################

########################## FUNCION REGISTRAR PROVINCIAS ###############################
public function RegistrarProvincias()
{
	self::SetNames();
	if(empty($_POST["provincia"]))
	{
		echo "1";
		exit;
	}

			$sql = " SELECT provincia FROM provincias WHERE provincia = ?";
			$stmt = $this->dbh->prepare($sql);
			$stmt->execute(array($_POST["provincia"]));
			$num = $stmt->rowCount();
			if($num == 0)
			{
				$query = " INSERT INTO provincias values (null, ?);";
				$stmt = $this->dbh->prepare($query);
				$stmt->bindParam(1, $provincia);

				$provincia = limpiar($_POST["provincia"]);
				$stmt->execute();

			echo "<span class='fa fa-check-square-o'></span> LA PROVINCIA HA SIDO REGISTRADA EXITOSAMENTE";
			exit;

			} else {

			echo "2";
			exit;
	    }
}
############################ FUNCION REGISTRAR PROVINCIAS ############################

############################ FUNCION LISTAR PROVINCIAS ################################
public function ListarProvincias()
{
	self::SetNames();
	$sql = "SELECT * FROM provincias";
	foreach ($this->dbh->query($sql) as $row)
	{
		$this->p[] = $row;
	}
	return $this->p;
	$this->dbh=null;
 }
########################### FUNCION LISTAR PROVINCIAS ################################

########################### FUNCION ID PROVINCIAS #################################
public function ProvinciasPorId()
{
	self::SetNames();
	$sql = "SELECT * FROM provincias WHERE id_provincia = ?";
	$stmt = $this->dbh->prepare($sql);
	$stmt->execute(array(decrypt($_GET["id_provincia"])));
	$num = $stmt->rowCount();
	if($num==0)
	{
		echo "";
	}
	else
	{
		if($row = $stmt->fetch(PDO::FETCH_ASSOC))
		{
			$this->p[] = $row;
		}
		return $this->p;
		$this->dbh=null;
	}
}
############################ FUNCION ID PROVINCIAS #################################

############################ FUNCION ACTUALIZAR PROVINCIAS ############################
public function ActualizarProvincias()
{

	self::SetNames();
	if(empty($_POST["id_provincia"]) or empty($_POST["provincia"]))
	{
		echo "1";
		exit;
	}

			$sql = " SELECT provincia FROM provincias WHERE id_provincia != ? AND provincia = ?";
			$stmt = $this->dbh->prepare($sql);
			$stmt->execute(array($_POST["id_provincia"],$_POST["provincia"]));
			$num = $stmt->rowCount();
			if($num == 0)
			{
				$sql = " UPDATE provincias set "
				." provincia = ? "
				." where "
				." id_provincia = ?;
				";
				$stmt = $this->dbh->prepare($sql);
				$stmt->bindParam(1, $provincia);
				$stmt->bindParam(2, $id_provincia);

				$provincia = limpiar($_POST["provincia"]);
	if (limpiar($_POST['id_provincia']=="")) { $id_provincia = limpiar('0'); } else { $id_provincia = limpiar($_POST['id_provincia']); }
				$stmt->execute();

			echo "<span class='fa fa-check-square-o'></span> LA PROVINCIA HA SIDO ACTUALIZADA EXITOSAMENTE";
			exit;

			} else {

			echo "2";
			exit;
		}
}
############################ FUNCION ACTUALIZAR PROVINCIAS ############################

############################ FUNCION ELIMINAR PROVINCIAS ############################
public function EliminarProvincias()
{
	self::SetNames();
	if($_SESSION['acceso'] == "administrador") {

		$sql = "SELECT id_provincia FROM departamentos WHERE id_provincia = ?";
		$stmt = $this->dbh->prepare($sql);
		$stmt->execute(array(decrypt($_GET["id_provincia"])));
		$num = $stmt->rowCount();
		if($num == 0)
		{

			$sql = "DELETE FROM provincias WHERE id_provincia = ?";
			$stmt = $this->dbh->prepare($sql);
			$stmt->bindParam(1,$id_provincia);
			$id_provincia = decrypt($_GET["id_provincia"]);
			$stmt->execute();

			echo "1";
			exit;

		} else {
		   
			echo "2";
			exit;
		  } 
			
		} else {
		
		echo "3";
		exit;
	 }	
}
############################ FUNCION ELIMINAR PROVINCIAS ##############################

############################## FIN DE CLASE PROVINCIAS ################################


























############################### CLASE DEPARTAMENTOS ################################

############################# FUNCION REGISTRAR DEPARTAMENTOS ###########################
public function RegistrarDepartamentos()
{
	self::SetNames();
	if(empty($_POST["departamento"]) or empty($_POST["id_provincia"]))
	{
		echo "1";
		exit;
	}

			$sql = " SELECT departamento FROM departamentos WHERE departamento = ? AND id_provincia = ?";
			$stmt = $this->dbh->prepare($sql);
			$stmt->execute(array($_POST["departamento"],$_POST["id_provincia"]));
			$num = $stmt->rowCount();
			if($num == 0)
			{
				$query = " INSERT INTO departamentos values (null, ?, ?);";
				$stmt = $this->dbh->prepare($query);
				$stmt->bindParam(1, $departamento);
				$stmt->bindParam(2, $id_provincia);

				$departamento = limpiar($_POST["departamento"]);
	if (limpiar($_POST['id_provincia']=="")) { $id_provincia = limpiar('0'); } else { $id_provincia = limpiar($_POST['id_provincia']); }
				$stmt->execute();

		echo "<span class='fa fa-check-square-o'></span> EL DEPARTAMENTO HA SIDO REGISTRADO EXITOSAMENTE";
			exit;

			} else {

			echo "2";
			exit;
	    }
}
########################### FUNCION REGISTRAR DEPARTAMENTOS ########################

########################## FUNCION PARA LISTAR DEPARTAMENTOS ##########################
	public function ListarDepartamentos()
	{
		self::SetNames();
		$sql = "SELECT * FROM departamentos LEFT JOIN provincias ON departamentos.id_provincia = provincias.id_provincia";
		foreach ($this->dbh->query($sql) as $row)
		{
			$this->p[] = $row;
		}
		return $this->p;
		$this->dbh=null;
	}
######################### FUNCION PARA LISTAR DEPARTAMENTOS ##########################

###################### FUNCION LISTAR DEPARTAMENTOS POR PROVINCIAS #####################
	public function ListarDepartamentoXProvincias() 
	       {
		self::SetNames();
		$sql = "SELECT * FROM departamentos WHERE id_provincia = ?";
		$stmt = $this->dbh->prepare($sql);
		$stmt->execute(array($_GET["id_provincia"]));
		$num = $stmt->rowCount();
		    if($num==0)
		{
			echo "<option value='0' selected> -- SIN RESULTADOS -- </option>";
			exit;
		}
		else
		{
		while($row = $stmt->fetch())
			{
				$this->p[]=$row;
			}
			return $this->p;
			$this->dbh=null;
		}
	}
##################### FUNCION LISTAR DEPARTAMENTOS POR PROVINCIAS ######################

################# FUNCION PARA SELECCIONAR DEPARTAMENTOS POR PROVINCIA #################
	public function SeleccionaDepartamento()
	{
		self::SetNames();
		$sql = "SELECT * FROM departamentos WHERE id_provincia = ?";
		$stmt = $this->dbh->prepare($sql);
		$stmt->execute(array($_GET["id_provincia"]));
		$num = $stmt->rowCount();
		if($num==0)
		{
			echo "<option value=''> -- SIN RESULTADOS -- </option>";
			exit;
		}
		else
		{
			while($row = $stmt->fetch())
			{
				$this->p[]=$row;
			}
			return $this->p;
			$this->dbh=null;
		}
	}
################# FUNCION PARA SELECCIONAR DEPARTAMENTOS POR PROVINCIA ################

############################ FUNCION ID DEPARTAMENTOS #################################
public function DepartamentosPorId()
{
	self::SetNames();
	$sql = "SELECT * FROM departamentos LEFT JOIN provincias ON departamentos.id_provincia = provincias.id_provincia WHERE departamentos.id_provincia = ?";
	$stmt = $this->dbh->prepare($sql);
	$stmt->execute(array(decrypt($_GET["id_provincia"])));
	$num = $stmt->rowCount();
	if($num==0)
	{
		echo "";
	}
	else
	{
		if($row = $stmt->fetch(PDO::FETCH_ASSOC))
		{
			$this->p[] = $row;
		}
		return $this->p;
		$this->dbh=null;
	}
}
############################ FUNCION ID DEPARTAMENTOS #################################

######################## FUNCION ACTUALIZAR DEPARTAMENTOS ############################
public function ActualizarDepartamentos()
{
	self::SetNames();
	if(empty($_POST["id_departamento"]) or empty($_POST["departamento"]) or empty($_POST["id_provincia"]))
	{
		echo "1";
		exit;
	}

			$sql = "SELECT departamento FROM departamentos WHERE id_departamento != ? AND departamento = ? AND id_provincia = ?";
			$stmt = $this->dbh->prepare($sql);
			$stmt->execute(array($_POST["id_departamento"],$_POST["departamento"],$_POST["id_provincia"]));
			$num = $stmt->rowCount();
			if($num == 0)
			{
				$sql = " UPDATE departamentos set "
				." departamento = ?, "
				." id_provincia = ? "
				." where "
				." id_departamento = ?;
				";
				$stmt = $this->dbh->prepare($sql);
				$stmt->bindParam(1, $departamento);
				$stmt->bindParam(2, $id_provincia);
				$stmt->bindParam(3, $id_departamento);

				$departamento = limpiar($_POST["departamento"]);
	if (limpiar($_POST['id_provincia']=="")) { $id_provincia = limpiar('0'); } else { $id_provincia = limpiar($_POST['id_provincia']); }
	if (limpiar($_POST['id_departamento']=="")) { $id_departamento = limpiar('0'); } else { $id_departamento = limpiar($_POST['id_departamento']); }
				$stmt->execute();

		echo "<span class='fa fa-check-square-o'></span> EL DEPARTAMENTO HA SIDO ACTUALIZADO EXITOSAMENTE";
			exit;

			} else {

			echo "2";
			exit;
		}
}
############################ FUNCION ACTUALIZAR DEPARTAMENTOS #######################

############################ FUNCION ELIMINAR DEPARTAMENTOS ###########################
public function EliminarDepartamentos()
{
	self::SetNames();
	if($_SESSION['acceso'] == "administrador") {

		$sql = "SELECT id_departamento FROM configuracion WHERE id_departamento = ?";
		$stmt = $this->dbh->prepare($sql);
		$stmt->execute(array(decrypt($_GET["id_departamento"])));
		$num = $stmt->rowCount();
		if($num == 0)
		{

			$sql = "DELETE FROM departamentos WHERE id_departamento = ?";
			$stmt = $this->dbh->prepare($sql);
			$stmt->bindParam(1,$id_departamento);
			$id_departamento = decrypt($_GET["id_departamento"]);
			$stmt->execute();

			echo "1";
			exit;

		} else {
		   
			echo "2";
			exit;
		  } 
			
		} else {
		
		echo "3";
		exit;
	 }	
}
########################### FUNCION ELIMINAR DEPARTAMENTOS ############################

############################## FIN DE CLASE DEPARTAMENTOS ##############################


























################################ CLASE TIPOS DE DOCUMENTOS ##############################

########################### FUNCION REGISTRAR TIPO DE DOCUMENTOS ########################
public function RegistrarDocumentos()
{
	self::SetNames();
	if(empty($_POST["documento"]) or empty($_POST["descripcion"]))
	{
		echo "1";
		exit;
	}

			$sql = " SELECT * FROM documentos WHERE documento = ?";
			$stmt = $this->dbh->prepare($sql);
			$stmt->execute(array($_POST["documento"]));
			$num = $stmt->rowCount();
			if($num == 0)
			{
				$query = " INSERT INTO documentos values (null, ?, ?);";
				$stmt = $this->dbh->prepare($query);
				$stmt->bindParam(1, $documento);
				$stmt->bindParam(2, $descripcion);

				$documento = limpiar($_POST["documento"]);
				$descripcion = limpiar($_POST["descripcion"]);
				$stmt->execute();

			echo "<span class='fa fa-check-square-o'></span> EL TIPO DE DOCUMENTO HA SIDO REGISTRADO EXITOSAMENTE";
			exit;

			} else {

			echo "2";
			exit;
	    }
}
############################ FUNCION REGISTRAR TIPO DE MONEDA ########################

########################## FUNCION LISTAR TIPO DE MONEDA ################################
public function ListarDocumentos()
{
	self::SetNames();
	$sql = "SELECT * FROM documentos ORDER BY documento ASC";
	foreach ($this->dbh->query($sql) as $row)
	{
		$this->p[] = $row;
	}
	return $this->p;
	$this->dbh=null;
 }
######################### FUNCION LISTAR TIPO DE DOCUMENTOS ##########################

######################### FUNCION ID TIPO DE DOCUMENTOS ###############################
public function DocumentoPorId()
{
	self::SetNames();
	$sql = "SELECT * FROM documentos WHERE coddocumento = ?";
	$stmt = $this->dbh->prepare($sql);
	$stmt->execute(array(decrypt($_GET["coddocumento"])));
	$num = $stmt->rowCount();
	if($num==0)
	{
		echo "";
	}
	else
	{
		if($row = $stmt->fetch(PDO::FETCH_ASSOC))
		{
			$this->p[] = $row;
		}
		return $this->p;
		$this->dbh=null;
	}
}
########################## FUNCION ID TIPO DE DOCUMENTOS #########################

######################### FUNCION ACTUALIZAR TIPO DE DOCUMENTOS ########################
public function ActualizarDocumentos()
{

	self::SetNames();
	if(empty($_POST["coddocumento"]) or empty($_POST["documento"]) or empty($_POST["descripcion"]))
	{
		echo "1";
		exit;
	}

			$sql = " SELECT documento FROM documentos WHERE coddocumento != ? AND documento = ?";
			$stmt = $this->dbh->prepare($sql);
			$stmt->execute(array($_POST["coddocumento"],$_POST["documento"]));
			$num = $stmt->rowCount();
			if($num == 0)
			{
				$sql = " UPDATE documentos set "
				." documento = ?, "
				." descripcion = ? "
				." where "
				." coddocumento = ?;
				";
				$stmt = $this->dbh->prepare($sql);
				$stmt->bindParam(1, $documento);
				$stmt->bindParam(2, $descripcion);
				$stmt->bindParam(3, $coddocumento);

				$documento = limpiar($_POST["documento"]);
				$descripcion = limpiar($_POST["descripcion"]);
				$coddocumento = limpiar($_POST["coddocumento"]);
				$stmt->execute();

			echo "<span class='fa fa-check-square-o'></span> EL TIPO DE DOCUMENTO HA SIDO ACTUALIZADO EXITOSAMENTE";
			exit;

			} else {

			echo "2";
			exit;
		}
}
####################### FUNCION ACTUALIZAR TIPO DE DOCUMENTOS #######################

######################### FUNCION ELIMINAR TIPO DE DOCUMENTOS #########################
public function EliminarDocumentos()
{
	self::SetNames();
	if ($_SESSION['acceso'] == "administrador") {

		$sql = "SELECT coddocumento FROM sucursales WHERE coddocumento = ?";
		$stmt = $this->dbh->prepare($sql);
		$stmt->execute(array(decrypt($_GET["coddocumento"])));
		$num = $stmt->rowCount();
		if($num == 0)
		{

			$sql = "DELETE FROM documentos WHERE coddocumento = ?";
			$stmt = $this->dbh->prepare($sql);
			$stmt->bindParam(1,$coddocumento);
			$coddocumento = decrypt($_GET["coddocumento"]);
			$stmt->execute();

			echo "1";
			exit;

		} else {
		   
			echo "2";
			exit;
		  } 
			
		} else {
		
		echo "3";
		exit;
	 }	
}
######################## FUNCION ELIMINAR TIPOS DE DOCUMENTOS ###########################

########################### FIN DE CLASE TIPOS DE DOCUMENTOS ###########################



























############################### CLASE TIPOS DE MONEDAS ##############################

############################ FUNCION REGISTRAR TIPO DE MONEDA ##########################
public function RegistrarTipoMoneda()
{
	self::SetNames();
	if(empty($_POST["moneda"]) or empty($_POST["moneda"]) or empty($_POST["simbolo"]))
	{
		echo "1";
		exit;
	}

			$sql = " SELECT * FROM tiposmoneda WHERE moneda = ?";
			$stmt = $this->dbh->prepare($sql);
			$stmt->execute(array($_POST["moneda"]));
			$num = $stmt->rowCount();
			if($num == 0)
			{
				$query = " INSERT INTO tiposmoneda values (null, ?, ?, ?);";
				$stmt = $this->dbh->prepare($query);
				$stmt->bindParam(1, $moneda);
				$stmt->bindParam(2, $siglas);
				$stmt->bindParam(3, $simbolo);

				$moneda = limpiar($_POST["moneda"]);
				$siglas = limpiar($_POST["siglas"]);
				$simbolo = limpiar($_POST["simbolo"]);
				$stmt->execute();

			echo "<span class='fa fa-check-square-o'></span> EL TIPO DE MONEDA HA SIDO REGISTRADO EXITOSAMENTE";
			exit;

			} else {

			echo "2";
			exit;
	    }
}
######################### FUNCION REGISTRAR TIPO DE MONEDA #######################

########################## FUNCION LISTAR TIPO DE MONEDA ################################
public function ListarTipoMoneda()
{
	self::SetNames();
	$sql = "SELECT * FROM tiposmoneda";
	foreach ($this->dbh->query($sql) as $row)
	{
		$this->p[] = $row;
	}
	return $this->p;
	$this->dbh=null;
 }
########################### FUNCION LISTAR TIPO DE MONEDA #########################

############################ FUNCION ID TIPO DE MONEDA #################################
public function TipoMonedaPorId()
{
	self::SetNames();
	$sql = "SELECT * FROM tiposmoneda WHERE codmoneda = ?";
	$stmt = $this->dbh->prepare($sql);
	$stmt->execute(array(decrypt($_GET["codmoneda"])));
	$num = $stmt->rowCount();
	if($num==0)
	{
		echo "";
	}
	else
	{
		if($row = $stmt->fetch(PDO::FETCH_ASSOC))
		{
			$this->p[] = $row;
		}
		return $this->p;
		$this->dbh=null;
	}
}
############################ FUNCION ID TIPO DE MONEDA #################################

####################### FUNCION ACTUALIZAR TIPO DE MONEDA ###########################
public function ActualizarTipoMoneda()
{

	self::SetNames();
	if(empty($_POST["codmoneda"]) or empty($_POST["moneda"]) or empty($_POST["siglas"]) or empty($_POST["simbolo"]))
	{
		echo "1";
		exit;
	}

			$sql = " SELECT moneda FROM tiposmoneda WHERE codmoneda != ? AND moneda = ?";
			$stmt = $this->dbh->prepare($sql);
			$stmt->execute(array($_POST["codmoneda"],$_POST["moneda"]));
			$num = $stmt->rowCount();
			if($num == 0)
			{
				$sql = " UPDATE tiposmoneda set "
				." moneda = ?, "
				." siglas = ?, "
				." simbolo = ? "
				." where "
				." codmoneda = ?;
				";
				$stmt = $this->dbh->prepare($sql);
				$stmt->bindParam(1, $moneda);
				$stmt->bindParam(2, $siglas);
				$stmt->bindParam(3, $simbolo);
				$stmt->bindParam(4, $codmoneda);

				$moneda = limpiar($_POST["moneda"]);
				$siglas = limpiar($_POST["siglas"]);
				$simbolo = limpiar($_POST["simbolo"]);
				$codmoneda = limpiar($_POST["codmoneda"]);
				$stmt->execute();

			echo "<span class='fa fa-check-square-o'></span> EL TIPO DE MONEDA HA SIDO ACTUALIZADO EXITOSAMENTE";
			exit;

			} else {

			echo "2";
			exit;
		}
}
######################## FUNCION ACTUALIZAR TIPO DE MONEDA ############################

######################### FUNCION ELIMINAR TIPO DE MONEDA ###########################
public function EliminarTipoMoneda()
{
	self::SetNames();
	if ($_SESSION['acceso'] == "administrador") {

		$sql = "SELECT codmoneda FROM tiposcambio WHERE codmoneda = ?";
		$stmt = $this->dbh->prepare($sql);
		$stmt->execute(array(decrypt($_GET["codmoneda"])));
		$num = $stmt->rowCount();
		if($num == 0)
		{

			$sql = "DELETE FROM tiposmoneda WHERE codmoneda = ?";
			$stmt = $this->dbh->prepare($sql);
			$stmt->bindParam(1,$codmoneda);
			$codmoneda = decrypt($_GET["codmoneda"]);
			$stmt->execute();

			echo "1";
			exit;

		} else {
		   
			echo "2";
			exit;
		  } 
			
		} else {
		
		echo "3";
		exit;
	 }	
}
########################### FUNCION ELIMINAR TIPOS DE MONEDAS ########################

##################### FUNCION BUSCAR TIPOS DE CAMBIOS POR MONEDA #######################
public function BuscarTiposCambios()
{
	self::SetNames();
	$sql = "SELECT * FROM tiposmoneda INNER JOIN tiposcambio ON tiposmoneda.codmoneda = tiposcambio.codmoneda WHERE tiposcambio.codmoneda = ? ORDER BY tiposcambio.codcambio DESC LIMIT 1";
	$stmt = $this->dbh->prepare($sql);
	$stmt->execute(array(decrypt($_GET["codmoneda"])));
	$num = $stmt->rowCount();
	if($num==0)
	{
		echo "<center><div class='alert alert-danger'>";
		echo "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>";
		echo "<span class='fa fa-info-circle'></span> NO SE ENCONTRARON TIPOS DE CAMBIO PARA LA MONEDA SELECCIONADA</div></center>";
		exit;
	}
	else
	{
		if($row = $stmt->fetch(PDO::FETCH_ASSOC))
		{
			$this->p[] = $row;
		}
		return $this->p;
		$this->dbh=null;
	}
}
##################### FUNCION BUSCAR TIPOS DE CAMBIOS POR MONEDA #####################

############################# FIN DE CLASE TIPOS DE MONEDAS #############################
























############################## CLASE TIPOS DE CAMBIOS ################################

########################## FUNCION REGISTRAR TIPO DE CAMBIO #########################
public function RegistrarTipoCambio()
{
	self::SetNames();
	if(empty($_POST["descripcioncambio"]) or empty($_POST["montocambio"]) or empty($_POST["codmoneda"]) or empty($_POST["fechacambio"]))
	{
		echo "1";
		exit;
	}
			
		$sql = "SELECT codmoneda, fechacambio FROM tiposcambio WHERE codmoneda = ? AND fechacambio = ?";
		$stmt = $this->dbh->prepare($sql);
		$stmt->execute(array($_POST["codmoneda"],date("Y-m-d",strtotime($_POST['fechacambio']))));
		$num = $stmt->rowCount();
		if($num == 0)
		{
			$query = "INSERT INTO tiposcambio values (null, ?, ?, ?, ?); ";
			$stmt = $this->dbh->prepare($query);
			$stmt->bindParam(1, $descripcioncambio);
			$stmt->bindParam(2, $montocambio);
			$stmt->bindParam(3, $codmoneda);
			$stmt->bindParam(4, $fechacambio);

			$descripcioncambio = limpiar($_POST["descripcioncambio"]);
			$montocambio = number_format($_POST["montocambio"], 3, '.', '');
			$codmoneda = limpiar($_POST["codmoneda"]);
			$fechacambio = limpiar(date("Y-m-d",strtotime($_POST['fechacambio'])));
			$stmt->execute();

			echo "<span class='fa fa-check-square-o'></span> EL TIPO DE CAMBIO HA SIDO REGISTRADO EXITOSAMENTE";
			exit;

			} else {

			echo "2";
			exit;
	    }
}
######################### FUNCION REGISTRAR TIPO DE CAMBIO ########################

########################### FUNCION LISTAR TIPO DE CAMBIO ########################
public function ListarTipoCambio()
{
	self::SetNames();
	$sql = "SELECT * FROM tiposcambio INNER JOIN tiposmoneda ON tiposcambio.codmoneda = tiposmoneda.codmoneda";
	foreach ($this->dbh->query($sql) as $row)
	{
		$this->p[] = $row;
	}
	return $this->p;
	$this->dbh=null;
 }
######################### FUNCION LISTAR TIPO DE CAMBIO ################################

######################## FUNCION ID TIPO DE CAMBIO #################################
public function TipoCambioPorId()
{
	self::SetNames();
	$sql = "SELECT * FROM tiposcambio INNER JOIN tiposmoneda ON tiposcambio.codmoneda = tiposmoneda.codmoneda WHERE tiposcambio.codcambio = ?";
	$stmt = $this->dbh->prepare($sql);
	$stmt->execute(array(decrypt($_GET["codcambio"])));
	$num = $stmt->rowCount();
	if($num==0)
	{
		echo "";
	}
	else
	{
		if($row = $stmt->fetch(PDO::FETCH_ASSOC))
		{
			$this->p[] = $row;
		}
		return $this->p;
		$this->dbh=null;
	}
}
############################ FUNCION ID TIPO DE CAMBIO #################################

####################### FUNCION ACTUALIZAR TIPO DE CAMBIO ############################
public function ActualizarTipoCambio()
{
	self::SetNames();
	if(empty($_POST["codcambio"])or empty($_POST["descripcioncambio"]) or empty($_POST["montocambio"]) or empty($_POST["codmoneda"]) or empty($_POST["fechacambio"]))
	{
		echo "1";
		exit;
	}
			
		$sql = "SELECT codmoneda, fechacambio FROM tiposcambio WHERE codcambio != ? AND codmoneda = ? AND fechacambio = ?";
		$stmt = $this->dbh->prepare($sql);
		$stmt->execute(array($_POST["codcambio"],$_POST["codmoneda"],date("Y-m-d",strtotime($_POST['fechacambio']))));
		$num = $stmt->rowCount();
		if($num == 0)
		{
			$sql = "UPDATE tiposcambio set "
			." descripcioncambio = ?, "
			." montocambio = ?, "
			." codmoneda = ?, "
			." fechacambio = ? "
			." where "
			." codcambio = ?;
			";
			$stmt = $this->dbh->prepare($sql);
			$stmt->bindParam(1, $descripcioncambio);
			$stmt->bindParam(2, $montocambio);
			$stmt->bindParam(3, $codmoneda);
			$stmt->bindParam(4, $fechacambio);
			$stmt->bindParam(5, $codcambio);

			$descripcioncambio = limpiar($_POST["descripcioncambio"]);
			$montocambio = number_format($_POST["montocambio"], 3, '.', '');
			$codmoneda = limpiar($_POST["codmoneda"]);
			$fechacambio = limpiar(date("Y-m-d",strtotime($_POST['fechacambio'])));
			$codcambio = limpiar($_POST["codcambio"]);
			$stmt->execute();

			echo "<span class='fa fa-check-square-o'></span> EL TIPO DE CAMBIO HA SIDO ACTUALIZADO EXITOSAMENTE";
			exit;

			} else {

			echo "2";
			exit;
	    }
}
###################### FUNCION ACTUALIZAR TIPO DE CAMBIO ############################

########################## FUNCION ELIMINAR TIPO DE CAMBIO ###########################
public function EliminarTipoCambio()
{
	self::SetNames();
		if ($_SESSION['acceso'] == "administrador") {

		    $sql = "DELETE FROM tiposcambio WHERE codcambio = ?";
			$stmt = $this->dbh->prepare($sql);
			$stmt->bindParam(1,$codcambio);
			$codcambio = decrypt($_GET["codcambio"]);
			$stmt->execute();

			echo "1";
			exit;

		} else {
		   
			echo "2";
			exit;
		} 
}
########################### FUNCION ELIMINAR TIPO DE CAMBIO ###########################

######################## FUNCION BUSCAR PRODUCTOS POR MONEDA ###########################
public function MonedaProductoId()
{
	self::SetNames();
	$sql = "SELECT configuracion.codmoneda2, tiposmoneda.moneda, tiposmoneda.siglas, tiposmoneda.simbolo, tiposcambio.montocambio 
	FROM configuracion 
	INNER JOIN tiposmoneda ON configuracion.codmoneda2 = tiposmoneda.codmoneda
	INNER JOIN tiposcambio ON tiposmoneda.codmoneda = tiposcambio.codmoneda WHERE configuracion.id = ? ORDER BY tiposcambio.codcambio DESC LIMIT 1";
	$stmt = $this->dbh->prepare($sql);
	$stmt->execute(array('1'));
	$num = $stmt->rowCount();
	if($num==0)
	{
		echo "";
	}
	else
	{
		if($row = $stmt->fetch(PDO::FETCH_ASSOC))
		{
			$this->p[] = $row;
		}
		return $this->p;
		$this->dbh=null;
	   }
}
###################### FUNCION BUSCAR PRODUCTOS POR MONEDA ##########################

############################ FIN DE CLASE TIPOS DE CAMBIOS #############################


























################################# CLASE MEDIOS DE PAGOS ################################

########################### FUNCION REGISTRAR MEDIOS DE PAGOS ###########################
public function RegistrarMediosPagos()
{
	self::SetNames();
	if(empty($_POST["mediopago"]))
	{
		echo "1";
		exit;
	}

			$sql = " SELECT mediopago FROM mediospagos WHERE mediopago = ?";
			$stmt = $this->dbh->prepare($sql);
			$stmt->execute(array($_POST["mediopago"]));
			$num = $stmt->rowCount();
			if($num == 0)
			{
				$query = " INSERT INTO mediospagos values (null, ?);";
				$stmt = $this->dbh->prepare($query);
				$stmt->bindParam(1, $mediopago);

				$mediopago = limpiar($_POST["mediopago"]);
				$stmt->execute();

			echo "<span class='fa fa-check-square-o'></span> EL MEDIO DE PAGO HA SIDO REGISTRADO EXITOSAMENTE";
			exit;

			} else {

			echo "2";
			exit;
	    }
}
############################ FUNCION REGISTRAR MEDIOS DE PAGOS ##########################

########################## FUNCION LISTAR MEDIOS DE PAGOS ##########################
public function ListarMediosPagos()
{
	self::SetNames();
	$sql = "SELECT * FROM mediospagos";
	foreach ($this->dbh->query($sql) as $row)
	{
		$this->p[] = $row;
	}
	return $this->p;
	$this->dbh=null;
 }
########################### FUNCION LISTAR MEDIOS DE PAGOS ##########################

############################ FUNCION ID MEDIOS DE PAGOS #################################
public function MediosPagosPorId()
{
	self::SetNames();
	$sql = "SELECT * FROM mediospagos WHERE codmediopago = ?";
	$stmt = $this->dbh->prepare($sql);
	$stmt->execute(array(decrypt($_GET["codmediopago"])));
	$num = $stmt->rowCount();
	if($num==0)
	{
		echo "";
	}
	else
	{
		if($row = $stmt->fetch(PDO::FETCH_ASSOC))
		{
			$this->p[] = $row;
		}
		return $this->p;
		$this->dbh=null;
	}
}
############################ FUNCION ID MEDIOS DE PAGOS #################################

##################### FUNCION ACTUALIZAR MEDIOS DE PAGOS ############################
public function ActualizarMediosPagos()
{
	self::SetNames();
	if(empty($_POST["codmediopago"]) or empty($_POST["mediopago"]))
	{
		echo "1";
		exit;
	}

			$sql = " SELECT mediopago FROM mediospagos WHERE codmediopago != ? AND mediopago = ?";
			$stmt = $this->dbh->prepare($sql);
			$stmt->execute(array($_POST["codmediopago"],$_POST["mediopago"]));
			$num = $stmt->rowCount();
			if($num == 0)
			{
				$sql = " UPDATE mediospagos set "
				." mediopago = ? "
				." where "
				." codmediopago = ?;
				";
				$stmt = $this->dbh->prepare($sql);
				$stmt->bindParam(1, $mediopago);
				$stmt->bindParam(2, $codmediopago);

				$mediopago = limpiar($_POST["mediopago"]);
				$codmediopago = limpiar($_POST["codmediopago"]);
				$stmt->execute();

			echo "<span class='fa fa-check-square-o'></span> EL MEDIO DE PAGO HA SIDO ACTUALIZADO EXITOSAMENTE";
			exit;

			} else {

			echo "2";
			exit;
		}
}
##################### FUNCION ACTUALIZAR MEDIOS DE PAGOS ############################

########################## FUNCION ELIMINAR MEDIOS DE PAGOS #########################
public function EliminarMediosPagos()
{
	self::SetNames();
		if ($_SESSION['acceso'] == "administrador") {

		$sql = "SELECT formapago FROM ventas WHERE formapago = ?";
		$stmt = $this->dbh->prepare($sql);
		$stmt->execute(array(decrypt($_GET["codmediopago"])));
		$num = $stmt->rowCount();
		if($num == 0)
		{

			$sql = "DELETE FROM mediospagos WHERE codmediopago = ?";
			$stmt = $this->dbh->prepare($sql);
			$stmt->bindParam(1,$codmediopago);
			$codmediopago = decrypt($_GET["codmediopago"]);
			$stmt->execute();

			echo "1";
			exit;

		} else {
		   
			echo "2";
			exit;
		  } 
			
		} else {
		
		echo "3";
		exit;
	 }	
}
########################## FUNCION ELIMINAR MEDIOS DE PAGOS ###########################

############################ FIN DE CLASE MEDIOS DE PAGOS ##############################

























############################### CLASE IMPUESTOS ####################################

############################ FUNCION REGISTRAR IMPUESTOS ###############################
public function RegistrarImpuestos()
{
	self::SetNames();
	if(empty($_POST["nomimpuesto"]) or empty($_POST["valorimpuesto"]) or empty($_POST["statusimpuesto"]))
	{
		echo "1";
		exit;
	}

			$sql = " SELECT statusimpuesto FROM impuestos WHERE nomimpuesto != ? AND statusimpuesto = ? AND statusimpuesto = 'ACTIVO'";
			$stmt = $this->dbh->prepare($sql);
			$stmt->execute(array($_POST["nomimpuesto"],$_POST["statusimpuesto"]));
			$num = $stmt->rowCount();
			if($num>0)
			{
				echo "2";
				exit;
			}
			else
			{

			$sql = " SELECT nomimpuesto FROM impuestos WHERE nomimpuesto = ?";
			$stmt = $this->dbh->prepare($sql);
			$stmt->execute(array($_POST["nomimpuesto"]));
			$num = $stmt->rowCount();
			if($num == 0)
			{
				$query = " INSERT INTO impuestos values (null, ?, ?, ?, ?);";
				$stmt = $this->dbh->prepare($query);
				$stmt->bindParam(1, $nomimpuesto);
				$stmt->bindParam(2, $valorimpuesto);
				$stmt->bindParam(3, $statusimpuesto);
				$stmt->bindParam(4, $fechaimpuesto);

				$nomimpuesto = limpiar($_POST["nomimpuesto"]);
				$valorimpuesto = limpiar($_POST["valorimpuesto"]);
				$statusimpuesto = limpiar($_POST["statusimpuesto"]);
				$fechaimpuesto = limpiar(date("Y-m-d",strtotime($_POST['fechaimpuesto'])));
				$stmt->execute();

		echo "<span class='fa fa-check-square-o'></span> EL IMPUESTO HA SIDO REGISTRADO  EXITOSAMENTE";
			exit;

			} else {

			echo "3";
			exit;
	    }
	}
}
############################ FUNCION REGISTRAR IMPUESTOS ###############################

############################# FUNCION LISTAR IMPUESTOS ################################
public function ListarImpuestos()
{
	self::SetNames();
	$sql = "SELECT * FROM impuestos";
	foreach ($this->dbh->query($sql) as $row)
	{
		$this->p[] = $row;
	}
	return $this->p;
	$this->dbh=null;
 }
############################# FUNCION LISTAR IMPUESTOS ################################

############################ FUNCION ID IMPUESTOS #################################
public function ImpuestosPorId()
{
	self::SetNames();
	$sql = "SELECT * FROM impuestos WHERE statusimpuesto = ?";
	$stmt = $this->dbh->prepare($sql);
	$stmt->execute(array("ACTIVO"));
	$num = $stmt->rowCount();
	if($row = $stmt->fetch(PDO::FETCH_ASSOC))
		{
			$this->p[] = $row;
		}
		return $this->p;
		$this->dbh=null;
}
############################ FUNCION ID IMPUESTOS #################################

############################ FUNCION ACTUALIZAR IMPUESTOS ############################
public function ActualizarImpuestos()
{

	self::SetNames();
	if(empty($_POST["codimpuesto"]) or empty($_POST["nomimpuesto"]) or empty($_POST["valorimpuesto"]) or empty($_POST["statusimpuesto"]))
	{
		echo "1";
		exit;
	}

			$sql = " SELECT statusimpuesto FROM impuestos WHERE codimpuesto != ? AND statusimpuesto = ? AND statusimpuesto = 'ACTIVO'";
			$stmt = $this->dbh->prepare($sql);
			$stmt->execute(array($_POST["codimpuesto"],$_POST["statusimpuesto"]));
			$num = $stmt->rowCount();
			if($num>0)
			{
				echo "2";
				exit;
			}
			else
			{

			$sql = "SELECT nomimpuesto FROM impuestos WHERE codimpuesto != ? AND nomimpuesto = ?";
			$stmt = $this->dbh->prepare($sql);
			$stmt->execute(array($_POST["codimpuesto"],$_POST["nomimpuesto"]));
			$num = $stmt->rowCount();
			if($num == 0)
			{
				$sql = " UPDATE impuestos set "
				." nomimpuesto = ?, "
				." valorimpuesto = ?, "
				." statusimpuesto = ? "
				." where "
				." codimpuesto = ?;
				";
				$stmt = $this->dbh->prepare($sql);
				$stmt->bindParam(1, $nomimpuesto);
				$stmt->bindParam(2, $valorimpuesto);
				$stmt->bindParam(3, $statusimpuesto);
				$stmt->bindParam(4, $codimpuesto);

				$nomimpuesto = limpiar($_POST["nomimpuesto"]);
				$valorimpuesto = limpiar($_POST["valorimpuesto"]);
				$statusimpuesto = limpiar($_POST["statusimpuesto"]);
				$codimpuesto = limpiar($_POST["codimpuesto"]);
				$stmt->execute();

		echo "<span class='fa fa-check-square-o'></span> EL IMPUESTO HA SIDO ACTUALIZADO EXITOSAMENTE";
			exit;

			} else {

			echo "3";
			exit;
		}
	}
}
############################ FUNCION ACTUALIZAR IMPUESTOS ############################

############################ FIN DE CLASE IMPUESTOS ################################





















#################################### CLASE SALAS ##################################

############################# FUNCION REGISTRAR SALAS ############################
public function RegistrarSalas()
{
	self::SetNames();
	if(empty($_POST["nomsala"]))
	{
		echo "1";
		exit;
	}

			$sql = " SELECT nomsala FROM salas WHERE nomsala = ?";
			$stmt = $this->dbh->prepare($sql);
			$stmt->execute(array($_POST["nomsala"]));
			$num = $stmt->rowCount();
			if($num == 0)
			{
				$query = " INSERT INTO salas values (null, ?, ?);";
				$stmt = $this->dbh->prepare($query);
				$stmt->bindParam(1, $nomsala);
				$stmt->bindParam(2, $fecha);

				$nomsala = limpiar($_POST["nomsala"]);
		        $fecha = limpiar(date("Y-m-d"));
				$stmt->execute();

			echo "<span class='fa fa-check-square-o'></span> LA SALA HA SIDO REGISTRADA EXITOSAMENTE";
			exit;

			} else {

			echo "2";
			exit;
	    }
}
############################# FUNCION REGISTRAR SALAS ##############################

############################# FUNCION LISTAR SALAS #############################
public function ListarSalas()
{
	self::SetNames();
	$sql = "SELECT * FROM salas";
	foreach ($this->dbh->query($sql) as $row)
	{
		$this->p[] = $row;
	}
	return $this->p;
	$this->dbh=null;
 }
############################## FUNCION LISTAR SALAS #############################

############################ FUNCION ID SALAS #################################
public function SalasPorId()
{
	self::SetNames();
	$sql = "SELECT salas.codsala, salas.nomsala, salas.fecha, mesas.codmesa, mesas.nommesa, mesas.fecha, mesas.statusmesa FROM salas LEFT JOIN mesas ON salas.codsala = mesas.codsala WHERE salas.codsala = ?";
	$stmt = $this->dbh->prepare($sql);
	$stmt->execute(array(decrypt($_GET["codsala"])));
	$num = $stmt->rowCount();
	if($num==0)
	{
		echo "";
	}
	else
	{
		if($row = $stmt->fetch(PDO::FETCH_ASSOC))
		{
			$this->p[] = $row;
		}
		return $this->p;
		$this->dbh=null;
	}
}
############################ FUNCION ID SALAS #################################

############################ FUNCION ACTUALIZAR SALAS ############################
public function ActualizarSalas()
{

	self::SetNames();
	if(empty($_POST["codsala"]) or empty($_POST["nomsala"]))
	{
		echo "1";
		exit;
	}

			$sql = " SELECT nomsala FROM salas WHERE codsala != ? AND nomsala = ?";
			$stmt = $this->dbh->prepare($sql);
			$stmt->execute(array($_POST["codsala"],$_POST["nomsala"]));
			$num = $stmt->rowCount();
			if($num == 0)
			{
				$sql = " UPDATE salas set "
				." nomsala = ? "
				." where "
				." codsala = ?;
				";
				$stmt = $this->dbh->prepare($sql);
				$stmt->bindParam(1, $nomsala);
				$stmt->bindParam(2, $codsala);

				$nomsala = limpiar($_POST["nomsala"]);
				$codsala = limpiar($_POST["codsala"]);
				$stmt->execute();

			echo "<span class='fa fa-check-square-o'></span> LA SALA HA SIDO ACTUALIZADA EXITOSAMENTE";
			exit;

			} else {

			echo "2";
			exit;
		}
}
############################ FUNCION ACTUALIZAR SALAS ############################

############################ FUNCION ELIMINAR SALAS ############################
public function EliminarSalas()
{
	self::SetNames();

	if($_SESSION['acceso'] == "administrador") {

		$sql = "SELECT codsala FROM mesas WHERE codsala = ?";
		$stmt = $this->dbh->prepare($sql);
		$stmt->execute(array(decrypt($_GET["codsala"])));
		$num = $stmt->rowCount();
		if($num == 0)
		{

			$sql = "DELETE FROM salas WHERE codsala = ?";
			$stmt = $this->dbh->prepare($sql);
			$stmt->bindParam(1,$codsala);
			$codsala = decrypt($_GET["codsala"]);
			$stmt->execute();

			echo "1";
			exit;

		} else {
		   
			echo "2";
			exit;
		  } 
			
		} else {
		
		echo "3";
		exit;
	 }	
}
############################# FUNCION ELIMINAR SALAS #############################

################################ FIN DE CLASE SALAS ###############################






















#################################### CLASE MESAS ##################################

############################# FUNCION REGISTRAR MESAS ############################
public function RegistrarMesas()
{
	self::SetNames();
	if(empty($_POST["codsala"]) or empty($_POST["nommesa"]))
	{
		echo "1";
		exit;
	}

			$sql = " SELECT codsala, nommesa FROM mesas WHERE codsala = ? and nommesa = ?";
			$stmt = $this->dbh->prepare($sql);
			$stmt->execute(array($_POST["codsala"], $_POST["nommesa"]));
			$num = $stmt->rowCount();
			if($num == 0)
			{
				$query = " INSERT INTO mesas values (null, ?, ?, ?, ?);";
				$stmt = $this->dbh->prepare($query);
				$stmt->bindParam(1, $codsala);
				$stmt->bindParam(2, $nommesa);
				$stmt->bindParam(3, $fecha);
		        $stmt->bindParam(4, $statusmesa);

				$codsala = limpiar($_POST["codsala"]);
				$nommesa = limpiar($_POST["nommesa"]);
		        $fecha = limpiar(date("Y-m-d"));
		        $statusmesa = limpiar("0");
				$stmt->execute();

			echo "<span class='fa fa-check-square-o'></span> LA MESA HA SIDO REGISTRADA EXITOSAMENTE";
			exit;

			} else {

			echo "2";
			exit;
	    }
}
############################# FUNCION REGISTRAR MESAS ##############################

############################# FUNCION LISTAR MESAS #############################
public function ListarMesas()
{
	self::SetNames();
	$sql = "SELECT salas.codsala, salas.nomsala, salas.fecha, mesas.codmesa, mesas.nommesa, mesas.fecha, mesas.statusmesa FROM mesas LEFT JOIN salas ON mesas.codsala = salas.codsala ORDER BY nomsala ASC";
	foreach ($this->dbh->query($sql) as $row)
	{
		$this->p[] = $row;
	}
	return $this->p;
	$this->dbh=null;
 }
############################## FUNCION LISTAR MESAS #############################

############################ FUNCION ID MESAS #################################
public function MesasPorId()
{
	self::SetNames();
	$sql = "SELECT salas.codsala, salas.nomsala, salas.fecha, mesas.codmesa, mesas.nommesa, mesas.fecha, mesas.statusmesa FROM mesas LEFT JOIN salas ON mesas.codsala = salas.codsala WHERE mesas.codmesa = ?";
	$stmt = $this->dbh->prepare($sql);
	$stmt->execute(array(decrypt($_GET["codmesa"])));
	$num = $stmt->rowCount();
	if($num==0)
	{
		echo "";
	}
	else
	{
		if($row = $stmt->fetch(PDO::FETCH_ASSOC))
		{
			$this->p[] = $row;
		}
		return $this->p;
		$this->dbh=null;
	}
}
############################ FUNCION ID MESAS #################################

############################ FUNCION ACTUALIZAR MESAS ############################
public function ActualizarMesas()
{

	self::SetNames();
	if(empty($_POST["codmesa"]) or empty($_POST["codsala"]) or empty($_POST["nommesa"]))
	{
		echo "1";
		exit;
	}

			$sql = " SELECT codsala, nommesa FROM mesas WHERE codmesa != ? AND codsala = ? AND nommesa = ?";
			$stmt = $this->dbh->prepare($sql);
			$stmt->execute(array($_POST["codmesa"],$_POST["codsala"],$_POST["nommesa"]));
			$num = $stmt->rowCount();
			if($num == 0)
			{
				$sql = " UPDATE mesas set "
				." codsala = ?, "
				." nommesa = ? "
				." where "
				." codmesa = ?;
				";
				$stmt = $this->dbh->prepare($sql);
				$stmt->bindParam(1, $codsala);
				$stmt->bindParam(2, $nommesa);
				$stmt->bindParam(3, $codmesa);

				$codsala = limpiar($_POST["codsala"]);
				$nommesa = limpiar($_POST["nommesa"]);
				$codmesa = limpiar($_POST["codmesa"]);
				$stmt->execute();

			echo "<span class='fa fa-check-square-o'></span> LA MESA HA SIDO ACTUALIZADA EXITOSAMENTE";
			exit;

			} else {

			echo "2";
			exit;
		}
}
############################ FUNCION ACTUALIZAR MESAS ############################

############################ FUNCION ELIMINAR MESAS ############################
public function EliminarMesas()
{
	self::SetNames();

	if($_SESSION['acceso'] == "administrador") {

		$sql = "SELECT codmesa FROM ventas WHERE codmesa = ?";
		$stmt = $this->dbh->prepare($sql);
		$stmt->execute(array(decrypt($_GET["codmesa"])));
		$num = $stmt->rowCount();
		if($num == 0)
		{

			$sql = "DELETE FROM mesas WHERE codmesa = ?";
			$stmt = $this->dbh->prepare($sql);
			$stmt->bindParam(1,$codmesa);
			$codmesa = decrypt($_GET["codmesa"]);
			$stmt->execute();

			echo "1";
			exit;

		} else {
		   
			echo "2";
			exit;
		  } 
			
		} else {
		
		echo "3";
		exit;
	 }	
}
############################# FUNCION ELIMINAR MESAS #############################

################################ FIN DE CLASE MESAS ###############################























#################################### CLASE CATEGORIAS ##################################

############################# FUNCION REGISTRAR CATEGORIAS ############################
public function RegistrarCategorias()
{
	self::SetNames();
	if(empty($_POST["nomcategoria"]))
	{
		echo "1";
		exit;
	}

			$sql = " SELECT nomcategoria FROM categorias WHERE nomcategoria = ?";
			$stmt = $this->dbh->prepare($sql);
			$stmt->execute(array($_POST["nomcategoria"]));
			$num = $stmt->rowCount();
			if($num == 0)
			{
				$query = " INSERT INTO categorias values (null, ?);";
				$stmt = $this->dbh->prepare($query);
				$stmt->bindParam(1, $nomcategoria);

				$nomcategoria = limpiar($_POST["nomcategoria"]);
				$stmt->execute();

			echo "<span class='fa fa-check-square-o'></span> LA CATEGORIA HA SIDO REGISTRADA EXITOSAMENTE";
			exit;

			} else {

			echo "2";
			exit;
	    }
}
############################# FUNCION REGISTRAR CATEGORIAS ##############################

############################# FUNCION LISTAR CATEGORIAS #############################
public function ListarCategorias()
{
	self::SetNames();
	$sql = "SELECT * FROM categorias ORDER BY nomcategoria ASC";
	foreach ($this->dbh->query($sql) as $row)
	{
		$this->p[] = $row;
	}
	return $this->p;
	$this->dbh=null;
 }
############################## FUNCION LISTAR NOTICIAS #############################

############################ FUNCION ID CATEGORIAS #################################
public function CategoriasPorId()
{
	self::SetNames();
	$sql = "SELECT * FROM categorias WHERE codcategoria = ?";
	$stmt = $this->dbh->prepare($sql);
	$stmt->execute(array(decrypt($_GET["codcategoria"])));
	$num = $stmt->rowCount();
	if($num==0)
	{
		echo "";
	}
	else
	{
		if($row = $stmt->fetch(PDO::FETCH_ASSOC))
		{
			$this->p[] = $row;
		}
		return $this->p;
		$this->dbh=null;
	}
}
############################ FUNCION ID CATEGORIAS #################################

############################ FUNCION ACTUALIZAR CATEGORIAS ############################
public function ActualizarCategorias()
{

	self::SetNames();
	if(empty($_POST["codcategoria"]) or empty($_POST["nomcategoria"]))
	{
		echo "1";
		exit;
	}

			$sql = " SELECT nomcategoria FROM categorias WHERE codcategoria != ? AND nomcategoria = ?";
			$stmt = $this->dbh->prepare($sql);
			$stmt->execute(array($_POST["codcategoria"],$_POST["nomcategoria"]));
			$num = $stmt->rowCount();
			if($num == 0)
			{
				$sql = " UPDATE categorias set "
				." nomcategoria = ? "
				." where "
				." codcategoria = ?;
				";
				$stmt = $this->dbh->prepare($sql);
				$stmt->bindParam(1, $nomcategoria);
				$stmt->bindParam(2, $codcategoria);

				$nomcategoria = limpiar($_POST["nomcategoria"]);
				$codcategoria = limpiar($_POST["codcategoria"]);
				$stmt->execute();

			echo "<span class='fa fa-check-square-o'></span> LA CATEGORIA HA SIDO ACTUALIZADA EXITOSAMENTE";
			exit;

			} else {

			echo "2";
			exit;
		}
}
############################ FUNCION ACTUALIZAR CATEGORIAS ############################

############################ FUNCION ELIMINAR CATEGORIAS ############################
public function EliminarCategorias()
{
	self::SetNames();

	if($_SESSION['acceso'] == "administrador") {

		$sql = "SELECT codcategoria FROM productos WHERE codcategoria = ?";
		$stmt = $this->dbh->prepare($sql);
		$stmt->execute(array(decrypt($_GET["codcategoria"])));
		$num = $stmt->rowCount();
		if($num == 0)
		{

			$sql = "DELETE FROM categorias WHERE codcategoria = ?";
			$stmt = $this->dbh->prepare($sql);
			$stmt->bindParam(1,$codcategoria);
			$codcategoria = decrypt($_GET["codcategoria"]);
			$stmt->execute();

			echo "1";
			exit;

		} else {
		   
			echo "2";
			exit;
		  } 
			
		} else {
		
		echo "3";
		exit;
	 }	
}
############################# FUNCION ELIMINAR CATEGORIAS #############################

################################ FIN DE CLASE CATEGORIAS ###############################


























#################################### CLASE MEDIDAS ##################################

############################# FUNCION REGISTRAR MEDIDAS ############################
public function RegistrarMedidas()
{
	self::SetNames();
	if(empty($_POST["nommedida"]))
	{
		echo "1";
		exit;
	}

			$sql = " SELECT nommedida FROM medidas WHERE nommedida = ?";
			$stmt = $this->dbh->prepare($sql);
			$stmt->execute(array($_POST["nommedida"]));
			$num = $stmt->rowCount();
			if($num == 0)
			{
				$query = " INSERT INTO medidas values (null, ?);";
				$stmt = $this->dbh->prepare($query);
				$stmt->bindParam(1, $nommedida);

				$nommedida = limpiar($_POST["nommedida"]);
				$stmt->execute();

			echo "<span class='fa fa-check-square-o'></span> LA MEDIDA HA SIDO REGISTRADA EXITOSAMENTE";
			exit;

			} else {

			echo "2";
			exit;
	    }
}
############################# FUNCION REGISTRAR MEDIDAS ##############################

############################# FUNCION LISTAR MEDIDAS #############################
public function ListarMedidas()
{
	self::SetNames();
	$sql = "SELECT * FROM medidas";
	foreach ($this->dbh->query($sql) as $row)
	{
		$this->p[] = $row;
	}
	return $this->p;
	$this->dbh=null;
 }
############################## FUNCION LISTAR MEDIDAS #############################

############################ FUNCION ID MEDIDAS #################################
public function MedidasPorId()
{
	self::SetNames();
	$sql = "SELECT * FROM medidas WHERE codmedida = ?";
	$stmt = $this->dbh->prepare($sql);
	$stmt->execute(array(decrypt($_GET["codmedida"])));
	$num = $stmt->rowCount();
	if($num==0)
	{
		echo "";
	}
	else
	{
		if($row = $stmt->fetch(PDO::FETCH_ASSOC))
		{
			$this->p[] = $row;
		}
		return $this->p;
		$this->dbh=null;
	}
}
############################ FUNCION ID MEDIDAS #################################

############################ FUNCION ACTUALIZAR MEDIDAS ############################
public function ActualizarMedidas()
{

	self::SetNames();
	if(empty($_POST["codmedida"]) or empty($_POST["nommedida"]))
	{
		echo "1";
		exit;
	}

			$sql = " SELECT nommedida FROM medidas WHERE codmedida != ? AND nommedida = ?";
			$stmt = $this->dbh->prepare($sql);
			$stmt->execute(array($_POST["codmedida"],$_POST["nommedida"]));
			$num = $stmt->rowCount();
			if($num == 0)
			{
				$sql = " UPDATE medidas set "
				." nommedida = ? "
				." where "
				." codmedida = ?;
				";
				$stmt = $this->dbh->prepare($sql);
				$stmt->bindParam(1, $nommedida);
				$stmt->bindParam(2, $codmedida);

				$nommedida = limpiar($_POST["nommedida"]);
				$codmedida = limpiar($_POST["codmedida"]);
				$stmt->execute();

			echo "<span class='fa fa-check-square-o'></span> LA MEDIDA HA SIDO ACTUALIZADA EXITOSAMENTE";
			exit;

			} else {

			echo "2";
			exit;
		}
}
############################ FUNCION ACTUALIZAR MEDIDAS ############################

############################ FUNCION ELIMINAR MEDIDAS ############################
public function EliminarMedidas()
{
	self::SetNames();

	if($_SESSION['acceso'] == "administrador") {

		$sql = "SELECT codmedida FROM ingredientes WHERE codmedida = ?";
		$stmt = $this->dbh->prepare($sql);
		$stmt->execute(array(decrypt($_GET["codmedida"])));
		$num = $stmt->rowCount();
		if($num == 0)
		{

			$sql = "DELETE FROM medidas WHERE codmedida = ?";
			$stmt = $this->dbh->prepare($sql);
			$stmt->bindParam(1,$codmedida);
			$codmedida = decrypt($_GET["codmedida"]);
			$stmt->execute();

			echo "1";
			exit;

		} else {
		   
			echo "2";
			exit;
		  } 
			
		} else {
		
		echo "3";
		exit;
	 }	
}
############################# FUNCION ELIMINAR MEDIDAS #############################

################################ FIN DE CLASE MEDIDAS ###############################
























################################## CLASE CLIENTES ##################################

############################### FUNCION CARGAR CLIENTES ##############################
	public function CargarClientes()
	{
		self::SetNames();
		if(empty($_FILES["sel_file"]))
		{
			echo "1";
			exit;
		}
        //Aquí es donde seleccionamos nuestro csv
         $fname = $_FILES['sel_file']['name'];
         //echo 'Cargando nombre del archivo: '.$fname.' ';
         $chk_ext = explode(".",$fname);
         
        if(strtolower(end($chk_ext)) == "csv")
        {
        //si es correcto, entonces damos permisos de lectura para subir
        $filename = $_FILES['sel_file']['tmp_name'];
        $handle = fopen($filename, "r");
        $this->dbh->beginTransaction();
        
        while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {

               //Insertamos los datos con los valores...
			   
		$query = "INSERT INTO clientes values (null, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);";
		$stmt = $this->dbh->prepare($query);
		$stmt->bindParam(1, $codcliente);
		$stmt->bindParam(2, $documcliente);
		$stmt->bindParam(3, $dnicliente);
		$stmt->bindParam(4, $nomcliente);
		$stmt->bindParam(5, $tlfcliente);
		$stmt->bindParam(6, $id_provincia);
		$stmt->bindParam(7, $id_departamento);
		$stmt->bindParam(8, $direccliente);
		$stmt->bindParam(9, $emailcliente);
		$stmt->bindParam(10, $tipocliente);
		$stmt->bindParam(11, $limitecredito);
		$stmt->bindParam(12, $fechaingreso);

		$codcliente = limpiar($data[0]);
		$documcliente = limpiar($data[1]);
		$dnicliente = limpiar($data[2]);
		$nomcliente = limpiar($data[3]);
		$tlfcliente = limpiar($data[4]);
		$id_provincia = limpiar($data[5]);
		$id_departamento = limpiar($data[6]);
		$direccliente = limpiar($data[7]);
		$emailcliente = limpiar($data[8]);
		$tipocliente = limpiar($data[9]);
		$limitecredito = limpiar($data[10]);
		$fechaingreso = limpiar(date("Y-m-d"));
		$stmt->execute();
				
        }
           $this->dbh->commit();
           //cerramos la lectura del archivo "abrir archivo" con un "cerrar archivo"
           fclose($handle);
	        
	echo "<span class='fa fa-check-square-o'></span> LA CARGA MASIVA DE CLIENTES FUE REALIZADA EXITOSAMENTE";
	exit;
             
         }
         else
         {
    //si aparece esto es posible que el archivo no tenga el formato adecuado, inclusive cuando es cvs, revisarlo para ver si esta separado por " , "
         echo "2";
		 exit;
      }  
}
################################# FUNCION CARGAR CLIENTES ###############################

############################ FUNCION REGISTRAR CLIENTES ###############################
	public function RegistrarClientes()
	{
		self::SetNames();
		if(empty($_POST["dnicliente"]) or empty($_POST["nomcliente"]) or empty($_POST["direccliente"]))
		{
			echo "1";
			exit;
		}

		$sql = "SELECT codcliente FROM clientes ORDER BY idcliente DESC LIMIT 1";
		foreach ($this->dbh->query($sql) as $row){

			$id=$row["codcliente"];

		}
		if(empty($id))
		{
			$codcliente = "C1";

		} else {

			$resto = substr($id, 0, 1);
			$coun = strlen($resto);
			$num     = substr($id, $coun);
			$codigo     = $num + 1;
			$codcliente = "C".$codigo;
		}

		$sql = "SELECT dnicliente FROM clientes WHERE dnicliente = ?";
		$stmt = $this->dbh->prepare($sql);
		$stmt->execute(array($_POST["dnicliente"]));
		$num = $stmt->rowCount();
		if($num == 0)
		{
			$query = " INSERT INTO clientes values (null, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);";
			$stmt = $this->dbh->prepare($query);
			$stmt->bindParam(1, $codcliente);
		    $stmt->bindParam(2, $documcliente);
			$stmt->bindParam(3, $dnicliente);
			$stmt->bindParam(4, $nomcliente);
			$stmt->bindParam(5, $tlfcliente);
			$stmt->bindParam(6, $id_provincia);
			$stmt->bindParam(7, $id_departamento);
			$stmt->bindParam(8, $direccliente);
			$stmt->bindParam(9, $emailcliente);
			$stmt->bindParam(10, $tipocliente);
		    $stmt->bindParam(11, $limitecredito);
			$stmt->bindParam(12, $fechaingreso);
			
			$documcliente = limpiar($_POST["documcliente"]);
			$dnicliente = limpiar($_POST["dnicliente"]);
			$nomcliente = limpiar($_POST["nomcliente"]);
			$tlfcliente = limpiar($_POST["tlfcliente"]);
if (limpiar($_POST['id_provincia']=="")) { $id_provincia = limpiar('0'); } else { $id_provincia = limpiar($_POST['id_provincia']); }
if (limpiar($_POST['id_departamento']=="")) { $id_departamento = limpiar('0'); } else { $id_departamento = limpiar($_POST['id_departamento']); }
			$direccliente = limpiar($_POST["direccliente"]);
			$emailcliente = limpiar($_POST["emailcliente"]);
			$tipocliente = limpiar($_POST["tipocliente"]);
			$limitecredito = limpiar($_POST["limitecredito"]);
		    $fechaingreso = limpiar(date("Y-m-d"));
			$stmt->execute();

			echo "<span class='fa fa-check-square-o'></span> EL CLIENTE HA SIDO REGISTRADO EXITOSAMENTE";
			exit;

		} else {

			echo "2";
			exit;
		}
	}
######################## FUNCION REGISTRAR CLIENTES ###############################

############################ FUNCION LISTAR CLIENTES ################################
	public function ListarClientes()
	{
		self::SetNames();
	$sql = "SELECT
		clientes.codcliente,
		clientes.documcliente,
		clientes.dnicliente,
		clientes.nomcliente,
		clientes.tlfcliente,
		clientes.id_provincia,
		clientes.id_departamento,
		clientes.direccliente,
		clientes.emailcliente,
		clientes.tipocliente,
		clientes.limitecredito,
		clientes.fechaingreso,
	    documentos.documento,
		provincias.provincia,
		departamentos.departamento
		FROM clientes 
		LEFT JOIN documentos ON clientes.documcliente = documentos.coddocumento
		LEFT JOIN provincias ON clientes.id_provincia = provincias.id_provincia 
		LEFT JOIN departamentos ON clientes.id_departamento = departamentos.id_departamento";
	foreach ($this->dbh->query($sql) as $row)
	{
		$this->p[] = $row;
	}
	return $this->p;
	$this->dbh=null;
}
######################### FUNCION LISTAR CLIENTES ################################

######################### FUNCION ID CLIENTES #################################
	public function ClientesPorId()
	{
		self::SetNames();
		$sql = "SELECT
		clientes.codcliente,
		clientes.documcliente,
		clientes.dnicliente,
		clientes.nomcliente,
		clientes.tlfcliente,
		clientes.id_provincia,
		clientes.id_departamento,
		clientes.direccliente,
		clientes.emailcliente,
		clientes.tipocliente,
		clientes.limitecredito,
		clientes.fechaingreso,
	    documentos.documento,
		provincias.provincia,
		departamentos.departamento
		FROM clientes 
		LEFT JOIN documentos ON clientes.documcliente = documentos.coddocumento
		LEFT JOIN provincias ON clientes.id_provincia = provincias.id_provincia 
		LEFT JOIN departamentos ON clientes.id_departamento = departamentos.id_departamento WHERE clientes.codcliente = ?";
		$stmt = $this->dbh->prepare($sql);
		$stmt->execute(array(decrypt($_GET["codcliente"])));
		$num = $stmt->rowCount();
		if($num==0)
		{
			echo "";
		}
		else
		{
			if($row = $stmt->fetch(PDO::FETCH_ASSOC))
			{
				$this->p[] = $row;
			}
			return $this->p;
			$this->dbh=null;
		}
	}
############################ FUNCION ID CLIENTES #################################
	
############################ FUNCION ACTUALIZAR CLIENTES ############################
	public function ActualizarClientes()
	{
		
	self::SetNames();
		if(empty($_POST["codcliente"]) or empty($_POST["dnicliente"]) or empty($_POST["nomcliente"]) or empty($_POST["direccliente"]))
		{
			echo "1";
			exit;
		}
		$sql = " SELECT dnicliente FROM clientes WHERE codcliente != ? AND dnicliente = ?";
		$stmt = $this->dbh->prepare($sql);
		$stmt->execute(array($_POST["codcliente"],$_POST["dnicliente"]));
		$num = $stmt->rowCount();
		if($num == 0)
		{
			$sql = "UPDATE clientes set "
			." documcliente = ?, "
			." dnicliente = ?, "
			." nomcliente = ?, "
			." tlfcliente = ?, "
			." id_provincia = ?, "
			." id_departamento = ?, "
			." direccliente = ?, "
			." emailcliente = ?, "
			." tipocliente = ?, "
			." limitecredito = ? "
			." where "
			." codcliente = ?;
			";
			$stmt = $this->dbh->prepare($sql);
		    $stmt->bindParam(1, $documcliente);
			$stmt->bindParam(2, $dnicliente);
			$stmt->bindParam(3, $nomcliente);
			$stmt->bindParam(4, $tlfcliente);
			$stmt->bindParam(5, $id_provincia);
			$stmt->bindParam(6, $id_departamento);
			$stmt->bindParam(7, $direccliente);
			$stmt->bindParam(8, $emailcliente);
			$stmt->bindParam(9, $tipocliente);
			$stmt->bindParam(10, $limitecredito);
			$stmt->bindParam(11, $codcliente);
			
			$documcliente = limpiar($_POST["documcliente"]);
			$dnicliente = limpiar($_POST["dnicliente"]);
			$nomcliente = limpiar($_POST["nomcliente"]);
			$tlfcliente = limpiar($_POST["tlfcliente"]);
if (limpiar($_POST['id_provincia']=="")) { $id_provincia = limpiar('0'); } else { $id_provincia = limpiar($_POST['id_provincia']); }
if (limpiar($_POST['id_departamento']=="")) { $id_departamento = limpiar('0'); } else { $id_departamento = limpiar($_POST['id_departamento']); }
			$direccliente = limpiar($_POST["direccliente"]);
			$emailcliente = limpiar($_POST["emailcliente"]);
			$tipocliente = limpiar($_POST["tipocliente"]);
			$limitecredito = limpiar($_POST["limitecredito"]);
			$codcliente = limpiar($_POST["codcliente"]);
			$stmt->execute();
        
		echo "<span class='fa fa-check-square-o'></span> EL CLIENTE HA SIDO ACTUALIZADO EXITOSAMENTE";
		exit;

		} else {

			echo "2";
			exit;
		}
	}
############################ FUNCION ACTUALIZAR CLIENTES ############################

########################### FUNCION ELIMINAR CLIENTES #################################
	public function EliminarClientes()
	{
	self::SetNames();
		if ($_SESSION['acceso'] == "administrador") {

		$sql = "SELECT codcliente FROM ventas WHERE codcliente = ?";
		$stmt = $this->dbh->prepare($sql);
		$stmt->execute(array(decrypt($_GET["codcliente"])));
		$num = $stmt->rowCount();
		if($num == 0)
		{

			$sql = "DELETE FROM clientes where codcliente = ?";
			$stmt = $this->dbh->prepare($sql);
			$stmt->bindParam(1,$codcliente);
			$codcliente = decrypt($_GET["codcliente"]);
			$stmt->execute();

			echo "1";
			exit;

		} else {
		   
			echo "2";
			exit;
		  } 
			
		} else {
		
		echo "3";
		exit;
	 }	
}
########################## FUNCION ELIMINAR CLIENTES #################################

############################## FIN DE CLASE CLIENTES #################################


























################################## CLASE PROVEEDORES ###################################

########################## FUNCION CARGAR PROVEEDORES ###############################
	public function CargarProveedores()
	{
		self::SetNames();
		if(empty($_FILES["sel_file"]))
		{
			echo "1";
			exit;
		}
        //Aquí es donde seleccionamos nuestro csv
         $fname = $_FILES['sel_file']['name'];
         //echo 'Cargando nombre del archivo: '.$fname.' ';
         $chk_ext = explode(".",$fname);
         
        if(strtolower(end($chk_ext)) == "csv")
        {
        //si es correcto, entonces damos permisos de lectura para subir
        $filename = $_FILES['sel_file']['tmp_name'];
        $handle = fopen($filename, "r");
        $this->dbh->beginTransaction();
        
        while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {

               //Insertamos los datos con los valores...
			   
		$query = "INSERT INTO proveedores values (null, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);";
		$stmt = $this->dbh->prepare($query);
		$stmt->bindParam(1, $codproveedor);
		$stmt->bindParam(2, $documproveedor);
		$stmt->bindParam(3, $dniproveedor);
		$stmt->bindParam(4, $nomproveedor);
		$stmt->bindParam(5, $tlfproveedor);
		$stmt->bindParam(6, $id_provincia);
		$stmt->bindParam(7, $id_departamento);
		$stmt->bindParam(8, $direcproveedor);
		$stmt->bindParam(9, $emailproveedor);
		$stmt->bindParam(10, $vendedor);
		$stmt->bindParam(11, $tlfvendedor);
		$stmt->bindParam(12, $fechaingreso);

		$codproveedor = limpiar($data[0]);
		$documproveedor = limpiar($data[1]);
		$dniproveedor = limpiar($data[2]);
		$nomproveedor = limpiar($data[3]);
		$tlfproveedor = limpiar($data[4]);
		$id_provincia = limpiar($data[5]);
		$id_departamento = limpiar($data[6]);
		$direcproveedor = limpiar($data[7]);
		$emailproveedor = limpiar($data[8]);
		$vendedor = limpiar($data[9]);
		$tlfvendedor = limpiar($data[10]);
		$fechaingreso = limpiar(date("Y-m-d"));
		$stmt->execute();
				
        }
           $this->dbh->commit();
           //cerramos la lectura del archivo "abrir archivo" con un "cerrar archivo"
           fclose($handle);
	        
	echo "<span class='fa fa-check-square-o'></span> LA CARGA MASIVA DE PROVEEDORES FUE REALIZADA EXITOSAMENTE";
	exit;
             
         }
         else
         {
    //si aparece esto es posible que el archivo no tenga el formato adecuado, inclusive cuando es cvs, revisarlo para ver si esta separado por " , "
         echo "2";
		 exit;
      }  
}
############################# FUNCION CARGAR PROVEEDORES ##############################

############################ FUNCION REGISTRAR PROVEEDORES ##########################
	public function RegistrarProveedores()
	{
		self::SetNames();
		if(empty($_POST["cuitproveedor"]) or empty($_POST["nomproveedor"]) or empty($_POST["direcproveedor"]))
		{
			echo "1";
			exit;
		}

		$sql = "SELECT codproveedor FROM proveedores ORDER BY idproveedor DESC LIMIT 1";
		foreach ($this->dbh->query($sql) as $row){

			$id=$row["codproveedor"];

		}
		if(empty($id))
		{
			$codproveedor = "P1";

		} else {

			$resto = substr($id, 0, 1);
			$coun = strlen($resto);
			$num     = substr($id, $coun);
			$codigo     = $num + 1;
			$codproveedor = "P".$codigo;
		}

		$sql = " SELECT cuitproveedor FROM proveedores WHERE cuitproveedor = ?";
		$stmt = $this->dbh->prepare($sql);
		$stmt->execute(array($_POST["cuitproveedor"]));
		$num = $stmt->rowCount();
		if($num == 0)
		{
			$query = " INSERT INTO proveedores values (null, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);";
			$stmt = $this->dbh->prepare($query);
			$stmt->bindParam(1, $codproveedor);
		    $stmt->bindParam(2, $documproveedor);
			$stmt->bindParam(3, $cuitproveedor);
			$stmt->bindParam(4, $nomproveedor);
			$stmt->bindParam(5, $tlfproveedor);
			$stmt->bindParam(6, $id_provincia);
			$stmt->bindParam(7, $id_departamento);
			$stmt->bindParam(8, $direcproveedor);
			$stmt->bindParam(9, $emailproveedor);
			$stmt->bindParam(10, $vendedor);
			$stmt->bindParam(11, $tlfvendedor);
			$stmt->bindParam(12, $fechaingreso);
			
			$documproveedor = limpiar($_POST["documproveedor"]);
			$cuitproveedor = limpiar($_POST["cuitproveedor"]);
			$nomproveedor = limpiar($_POST["nomproveedor"]);
			$tlfproveedor = limpiar($_POST["tlfproveedor"]);
if (limpiar($_POST['id_provincia']=="")) { $id_provincia = limpiar('0'); } else { $id_provincia = limpiar($_POST['id_provincia']); }
if (limpiar($_POST['id_departamento']=="")) { $id_departamento = limpiar('0'); } else { $id_departamento = limpiar($_POST['id_departamento']); }
			$direcproveedor = limpiar($_POST["direcproveedor"]);
			$emailproveedor = limpiar($_POST["emailproveedor"]);
			$vendedor = limpiar($_POST["vendedor"]);
			$tlfvendedor = limpiar($_POST["tlfvendedor"]);
		    $fechaingreso = limpiar(date("Y-m-d"));
			$stmt->execute();

			echo "<span class='fa fa-check-square-o'></span> EL PROVEEDOR HA SIDO REGISTRADO EXITOSAMENTE";
			exit;

		} else {

			echo "2";
			exit;
		}
	}
########################### FUNCION REGISTRAR PROVEEDORES ########################

########################### FUNCION LISTAR PROVEEDORES ################################
	public function ListarProveedores()
	{
		self::SetNames();
	    $sql = "SELECT
		proveedores.codproveedor,
		proveedores.documproveedor,
		proveedores.cuitproveedor,
		proveedores.nomproveedor,
		proveedores.tlfproveedor,
		proveedores.id_provincia,
		proveedores.id_departamento,
		proveedores.direcproveedor,
		proveedores.emailproveedor,
		proveedores.vendedor,
		proveedores.tlfvendedor,
		proveedores.fechaingreso,
	    documentos.documento,
		provincias.provincia,
		departamentos.departamento
		FROM proveedores 
		LEFT JOIN documentos ON proveedores.documproveedor = documentos.coddocumento
		LEFT JOIN provincias ON proveedores.id_provincia = provincias.id_provincia 
		LEFT JOIN departamentos ON proveedores.id_departamento = departamentos.id_departamento";
	foreach ($this->dbh->query($sql) as $row)
	{
		$this->p[] = $row;
	}
	return $this->p;
	$this->dbh=null;
}
########################### FUNCION LISTAR PROVEEDORES ################################

########################### FUNCION ID PROVEEDORES #################################
	public function ProveedoresPorId()
	{
		self::SetNames();
		$sql = "SELECT
		proveedores.codproveedor,
		proveedores.documproveedor,
		proveedores.cuitproveedor,
		proveedores.nomproveedor,
		proveedores.tlfproveedor,
		proveedores.id_provincia,
		proveedores.id_departamento,
		proveedores.direcproveedor,
		proveedores.emailproveedor,
		proveedores.vendedor,
		proveedores.tlfvendedor,
		proveedores.fechaingreso,
	    documentos.documento,
		provincias.provincia,
		departamentos.departamento
		FROM proveedores 
		LEFT JOIN documentos ON proveedores.documproveedor = documentos.coddocumento
		LEFT JOIN provincias ON proveedores.id_provincia = provincias.id_provincia 
		LEFT JOIN departamentos ON proveedores.id_departamento = departamentos.id_departamento WHERE proveedores.codproveedor = ?";
		$stmt = $this->dbh->prepare($sql);
		$stmt->execute(array(decrypt($_GET["codproveedor"])));
		$num = $stmt->rowCount();
		if($num==0)
		{
			echo "";
		}
		else
		{
			if($row = $stmt->fetch(PDO::FETCH_ASSOC))
			{
				$this->p[] = $row;
			}
			return $this->p;
			$this->dbh=null;
		}
	}
############################ FUNCION ID PROVEEDORES #################################
	
############################ FUNCION ACTUALIZAR PROVEEDORES ############################
	public function ActualizarProveedores()
	{
	self::SetNames();
		if(empty($_POST["codproveedor"]) or empty($_POST["cuitproveedor"]) or empty($_POST["nomproveedor"]) or empty($_POST["direcproveedor"]))
		{
			echo "1";
			exit;
		}
		$sql = " SELECT cuitproveedor FROM proveedores WHERE codproveedor != ? AND cuitproveedor = ?";
		$stmt = $this->dbh->prepare($sql);
		$stmt->execute(array($_POST["codproveedor"],$_POST["cuitproveedor"]));
		$num = $stmt->rowCount();
		if($num == 0)
		{
			$sql = "UPDATE proveedores set "
			." documproveedor = ?, "
			." cuitproveedor = ?, "
			." nomproveedor = ?, "
			." tlfproveedor = ?, "
			." id_provincia = ?, "
			." id_departamento = ?, "
			." direcproveedor = ?, "
			." emailproveedor = ?, "
			." vendedor = ?, "
			." tlfvendedor = ? "
			." where "
			." codproveedor = ?;
			";
			$stmt = $this->dbh->prepare($sql);
			$stmt->bindParam(1, $documproveedor);
			$stmt->bindParam(2, $cuitproveedor);
			$stmt->bindParam(3, $nomproveedor);
			$stmt->bindParam(4, $tlfproveedor);
			$stmt->bindParam(5, $id_provincia);
			$stmt->bindParam(6, $id_departamento);
			$stmt->bindParam(7, $direcproveedor);
			$stmt->bindParam(8, $emailproveedor);
			$stmt->bindParam(9, $vendedor);
			$stmt->bindParam(10, $tlfvendedor);
			$stmt->bindParam(11, $codproveedor);
			
			$documproveedor = limpiar($_POST["documproveedor"]);
			$cuitproveedor = limpiar($_POST["cuitproveedor"]);
			$nomproveedor = limpiar($_POST["nomproveedor"]);
			$tlfproveedor = limpiar($_POST["tlfproveedor"]);
if (limpiar($_POST['id_provincia']=="")) { $id_provincia = limpiar('0'); } else { $id_provincia = limpiar($_POST['id_provincia']); }
if (limpiar($_POST['id_departamento']=="")) { $id_departamento = limpiar('0'); } else { $id_departamento = limpiar($_POST['id_departamento']); }
			$direcproveedor = limpiar($_POST["direcproveedor"]);
			$emailproveedor = limpiar($_POST["emailproveedor"]);
			$vendedor = limpiar($_POST["vendedor"]);
			$tlfvendedor = limpiar($_POST["tlfvendedor"]);
			$codproveedor = limpiar($_POST["codproveedor"]);
			$stmt->execute();
        
		echo "<span class='fa fa-check-square-o'></span> EL PROVEEDOR HA SIDO ACTUALIZADO EXITOSAMENTE";
		exit;

		} else {

			echo "2";
			exit;
		}
	}
############################ FUNCION ACTUALIZAR PROVEEDORES ############################

########################## FUNCION ELIMINAR PROVEEDORES #################################
	public function EliminarProveedores()
	{
	self::SetNames();
		if ($_SESSION['acceso'] == "administrador") {

		$sql = "SELECT codproveedor FROM productos WHERE codproveedor = ?";
		$stmt = $this->dbh->prepare($sql);
		$stmt->execute(array(decrypt($_GET["codproveedor"])));
		$num = $stmt->rowCount();
		if($num == 0)
		{

			$sql = "DELETE FROM proveedores where codproveedor = ?";
			$stmt = $this->dbh->prepare($sql);
			$stmt->bindParam(1,$codproveedor);
			$codproveedor = decrypt($_GET["codproveedor"]);
			$stmt->execute();

			echo "1";
			exit;

		} else {
		   
			echo "2";
			exit;
		  } 
			
		} else {
		
		echo "3";
		exit;
	 }	
}
########################### FUNCION ELIMINAR PROVEEDORES #########################

############################## FIN DE CLASE PROVEEDORES #################################




























################################# CLASE INGREDIENTES ######################################

############################### FUNCION CARGAR INGREDIENTES ##############################
	public function CargarIngredientes()
	{
		self::SetNames();
		if(empty($_FILES["sel_file"]))
		{
			echo "1";
			exit;
		}

		$sql = " SELECT porcentaje FROM configuracion WHERE id = 1";
		foreach ($this->dbh->query($sql) as $row)
		{
			$this->p[] = $row;
		}
		$porcentaje = $row['porcentaje'];

        //Aquí es donde seleccionamos nuestro csv
         $fname = $_FILES['sel_file']['name'];
         //echo 'Cargando nombre del archivo: '.$fname.' ';
         $chk_ext = explode(".",$fname);
         
        if(strtolower(end($chk_ext)) == "csv")
        {
        //si es correcto, entonces damos permisos de lectura para subir
        $filename = $_FILES['sel_file']['tmp_name'];
        $handle = fopen($filename, "r");
        $this->dbh->beginTransaction();
        
        while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {

               //Insertamos los datos con los valores...
    $query = "INSERT INTO ingredientes values (null, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);";
    	$stmt = $this->dbh->prepare($query);
    	$stmt->bindParam(1, $codingrediente);
    	$stmt->bindParam(2, $nomingrediente);
    	$stmt->bindParam(3, $codmedida);
    	$stmt->bindParam(4, $preciocompra);
    	$stmt->bindParam(5, $precioventa);
    	$stmt->bindParam(6, $cantingrediente);
    	$stmt->bindParam(7, $stockminimo);
    	$stmt->bindParam(8, $stockmaximo);
    	$stmt->bindParam(9, $ivaingrediente);
    	$stmt->bindParam(10, $descingrediente);
		$stmt->bindParam(11, $lote);
    	$stmt->bindParam(12, $fechaexpiracion);
    	$stmt->bindParam(13, $codproveedor);

    	$codingrediente = limpiar($data[0]);
    	$nomingrediente = limpiar($data[1]);
    	$codmedida = limpiar($data[2]);
    	$preciocompra = limpiar($data[3]);
    	$calculo = number_format($data[3]*($porcentaje/100), 2, '.', '');
    	$calculoventa = number_format($data[3]+$calculo, 2, '.', '');
    	$precioventa = ($porcentaje == "0.00" ? limpiar($data[4]) : $calculoventa);
    	//$precioventa = limpiar($data[4]);
    	$cantingrediente = limpiar($data[5]);
    	$stockminimo = limpiar($data[6]);
    	$stockmaximo = limpiar($data[7]);
    	$ivaingrediente = limpiar($data[8]);
    	$descingrediente = limpiar($data[9]);
    	$lote = limpiar($data[10]);
    	$fechaexpiracion = limpiar($data[11]);
    	$codproveedor = limpiar($data[12]);
    	$stmt->execute();

##################### REGISTRAMOS LOS DATOS DE INGREDIENTES EN KARDEX #####################
		$query = "INSERT INTO kardex_ingredientes values (null, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);";
		$stmt = $this->dbh->prepare($query);
		$stmt->bindParam(1, $codproceso);
		$stmt->bindParam(2, $codresponsable);
		$stmt->bindParam(3, $codingrediente);
		$stmt->bindParam(4, $movimiento);
		$stmt->bindParam(5, $entradas);
		$stmt->bindParam(6, $salidas);
		$stmt->bindParam(7, $devolucion);
		$stmt->bindParam(8, $stockactual);
		$stmt->bindParam(9, $ivaingrediente);
		$stmt->bindParam(10, $descingrediente);
		$stmt->bindParam(11, $precio);
		$stmt->bindParam(12, $documento);
		$stmt->bindParam(13, $fechakardex);
		
		$codproceso = limpiar($data[0]);
		$codresponsable = limpiar("0");
		$codproducto = limpiar($data[0]);
		$movimiento = limpiar("ENTRADAS");
		$entradas = limpiar($data[5]);
		$salidas = limpiar("0");
		$devolucion = limpiar("0");
		$stockactual = limpiar($data[5]);
		$ivaproducto = limpiar($data[8]);
		$descproducto = limpiar($data[9]);
    	$calculo = number_format($data[3]*($porcentaje/100), 2, '.', '');
    	$calculoventa = number_format($data[3]+$calculo, 2, '.', '');
    	$precio = ($porcentaje == "0.00" ? limpiar($data[4]) : $calculoventa);
		//$precio = limpiar($data[4]);
		$documento = limpiar("INVENTARIO INICIAL");
		$fechakardex = limpiar(date("Y-m-d"));
		$stmt->execute();
##################### REGISTRAMOS LOS DATOS DE INGREDIENTES EN KARDEX #####################
	
        }
           
           $this->dbh->commit();
           //cerramos la lectura del archivo "abrir archivo" con un "cerrar archivo"
           fclose($handle);
	        
	echo "<span class='fa fa-check-square-o'></span> LA CARGA MASIVA DE INGREDIENTES FUE REALIZADA EXITOSAMENTE";
	exit;
             
         }
         else
         {
    //si aparece esto es posible que el archivo no tenga el formato adecuado, inclusive cuando es cvs, revisarlo para ver si esta separado por " , "
         echo "2";
		 exit;
      }  
}
############################## FUNCION CARGAR INGREDIENTES ##############################

########################### FUNCION REGISTRAR INGREDIENTES ###############################
	public function RegistrarIngredientes()
	{
		self::SetNames();
		if(empty($_POST["codingrediente"]) or empty($_POST["nomingrediente"]) or empty($_POST["codmedida"]))
		{
			echo "1";
			exit;
		}


		$sql = " SELECT codingrediente FROM ingredientes WHERE codingrediente = ?";
		$stmt = $this->dbh->prepare($sql);
		$stmt->execute(array($_POST["codingrediente"]));
		$num = $stmt->rowCount();
		if($num == 0)
		{
	$query = "INSERT INTO ingredientes values (null, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);";
			$stmt = $this->dbh->prepare($query);
			$stmt->bindParam(1, $codingrediente);
			$stmt->bindParam(2, $nomingrediente);
			$stmt->bindParam(3, $codmedida);
			$stmt->bindParam(4, $preciocompra);
			$stmt->bindParam(5, $precioventa);
			$stmt->bindParam(6, $cantingrediente);
			$stmt->bindParam(7, $stockminimo);
			$stmt->bindParam(8, $stockmaximo);
			$stmt->bindParam(9, $ivaingrediente);
			$stmt->bindParam(10, $descingrediente);
			$stmt->bindParam(11, $lote);
			$stmt->bindParam(12, $fechaexpiracion);
			$stmt->bindParam(13, $codproveedor);

			$codingrediente = limpiar($_POST["codingrediente"]);
			$nomingrediente = limpiar($_POST["nomingrediente"]);
			$codmedida = limpiar($_POST["codmedida"]);
			$preciocompra = limpiar($_POST["preciocompra"]);
			$precioventa = limpiar($_POST["precioventa"]);
			$cantingrediente = limpiar($_POST["cantingrediente"]);
			$stockminimo = limpiar($_POST["stockminimo"]);
			$stockmaximo = limpiar($_POST["stockmaximo"]);
			$ivaingrediente = limpiar($_POST["ivaingrediente"]);
			$descingrediente = limpiar($_POST["descingrediente"]);
			$lote = limpiar($_POST['lote'] == '' ? "0" : $_POST['lote']);
			if (limpiar($_POST['fechaexpiracion']=="")) { $fechaexpiracion = "0000-00-00";  } else { $fechaexpiracion = limpiar(date("Y-m-d",strtotime($_POST['fechaexpiracion']))); }
			$codproveedor = limpiar($_POST['codproveedor'] == '' ? "0" : $_POST['codproveedor']);
			$stmt->execute();

##################### REGISTRAMOS LOS DATOS DE INGREDIENTE EN KARDEX #####################
			$query = "INSERT INTO kardex_ingredientes values (null, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?); ";
			$stmt = $this->dbh->prepare($query);
			$stmt->bindParam(1, $codproceso);
			$stmt->bindParam(2, $codresponsable);
			$stmt->bindParam(3, $codingrediente);
			$stmt->bindParam(4, $movimiento);
			$stmt->bindParam(5, $entradas);
			$stmt->bindParam(6, $salidas);
			$stmt->bindParam(7, $devolucion);
			$stmt->bindParam(8, $stockactual);
			$stmt->bindParam(9, $ivaingrediente);
			$stmt->bindParam(10, $descingrediente);
			$stmt->bindParam(11, $precio);
			$stmt->bindParam(12, $documento);
			$stmt->bindParam(13, $fechakardex);

			$codproceso = limpiar($_POST['codingrediente']);
			$codresponsable = limpiar("0");
			$codingrediente = limpiar($_POST['codingrediente']);
			$movimiento = limpiar("ENTRADAS");
			$entradas = limpiar($_POST['cantingrediente']);
			$salidas = limpiar("0");
			$devolucion = limpiar("0");
			$stockactual = limpiar($_POST['cantingrediente']);
			$ivaingrediente = limpiar($_POST["ivaingrediente"]);
			$descingrediente = limpiar($_POST["descingrediente"]);
			$precio = limpiar($_POST['precioventa']);
			$documento = limpiar("INVENTARIO INICIAL");
			$fechakardex = limpiar(date("Y-m-d"));
			$stmt->execute();
##################### REGISTRAMOS LOS DATOS DE INGREDIENTE EN KARDEX #####################


			echo "<span class='fa fa-check-square-o'></span> EL INGREDIENTE HA SIDO REGISTRADO EXITOSAMENTE";
			exit;

		} else {

			echo "2";
			exit;
		}
	}
########################## FUNCION REGISTRAR INGREDIENTES ###############################

########################### FUNCION LISTAR INGREDIENTES ################################
	public function ListarIngredientes()
	{
		self::SetNames();
        $sql = "SELECT
		ingredientes.idingrediente,
		ingredientes.codingrediente,
		ingredientes.nomingrediente,
		ingredientes.codmedida,
		ingredientes.preciocompra,
		ingredientes.precioventa,
		ingredientes.cantingrediente,
		ingredientes.stockminimo,
		ingredientes.stockmaximo,
		ingredientes.ivaingrediente,
		ingredientes.descingrediente,
		ingredientes.lote,
		ingredientes.fechaexpiracion,
		ingredientes.codproveedor,
		medidas.nommedida,
		proveedores.cuitproveedor,
		proveedores.nomproveedor
	FROM (ingredientes INNER JOIN medidas ON ingredientes.codmedida=medidas.codmedida)
	LEFT JOIN proveedores ON ingredientes.codproveedor=proveedores.codproveedor";
	foreach ($this->dbh->query($sql) as $row)
	{
		$this->p[] = $row;
	}
	return $this->p;
	$this->dbh=null;
}
########################## FUNCION LISTAR INGREDIENTES ################################

########################### FUNCION LISTAR INGREDIENTES EN STOCK MINIMO ################################
	public function ListarIngredientesMinimo()
	{
		self::SetNames();
        $sql = "SELECT
		ingredientes.idingrediente,
		ingredientes.codingrediente,
		ingredientes.nomingrediente,
		ingredientes.codmedida,
		ingredientes.preciocompra,
		ingredientes.precioventa,
		ingredientes.cantingrediente,
		ingredientes.stockminimo,
		ingredientes.stockmaximo,
		ingredientes.ivaingrediente,
		ingredientes.descingrediente,
		ingredientes.lote,
		ingredientes.fechaexpiracion,
		ingredientes.codproveedor,
		medidas.nommedida,
		proveedores.cuitproveedor,
		proveedores.nomproveedor
	FROM (ingredientes INNER JOIN medidas ON ingredientes.codmedida=medidas.codmedida)
	LEFT JOIN proveedores ON ingredientes.codproveedor=proveedores.codproveedor
	WHERE ingredientes.cantingrediente <= ingredientes.stockminimo";
	foreach ($this->dbh->query($sql) as $row)
	{
		$this->p[] = $row;
	}
	return $this->p;
	$this->dbh=null;
}
########################## FUNCION LISTAR INGREDIENTES EN STOCK MINIMO ################################

########################### FUNCION LISTAR INGREDIENTES EN STOCK MAXIMO ################################
	public function ListarIngredientesMaximo()
	{
		self::SetNames();
        $sql = "SELECT
		ingredientes.idingrediente,
		ingredientes.codingrediente,
		ingredientes.nomingrediente,
		ingredientes.codmedida,
		ingredientes.preciocompra,
		ingredientes.precioventa,
		ingredientes.cantingrediente,
		ingredientes.stockminimo,
		ingredientes.stockmaximo,
		ingredientes.ivaingrediente,
		ingredientes.descingrediente,
		ingredientes.lote,
		ingredientes.fechaexpiracion,
		ingredientes.codproveedor,
		medidas.nommedida,
		proveedores.cuitproveedor,
		proveedores.nomproveedor
	FROM (ingredientes INNER JOIN medidas ON ingredientes.codmedida=medidas.codmedida)
	LEFT JOIN proveedores ON ingredientes.codproveedor=proveedores.codproveedor
	WHERE ingredientes.cantingrediente >= ingredientes.stockmaximo";
	foreach ($this->dbh->query($sql) as $row)
	{
		$this->p[] = $row;
	}
	return $this->p;
	$this->dbh=null;
}
########################## FUNCION LISTAR INGREDIENTES EN STOCK MAXIMO ################################

############################ FUNCION ID INGREDIENTES #################################
	public function IngredientesPorId()
	{
		self::SetNames();
		$sql = "SELECT
		ingredientes.idingrediente,
		ingredientes.codingrediente,
		ingredientes.nomingrediente,
		ingredientes.codmedida,
		ingredientes.preciocompra,
		ingredientes.precioventa,
		ingredientes.cantingrediente,
		ingredientes.stockminimo,
		ingredientes.stockmaximo,
		ingredientes.ivaingrediente,
		ingredientes.descingrediente,
		ingredientes.lote,
		ingredientes.fechaexpiracion,
		ingredientes.codproveedor,
		medidas.nommedida,
		proveedores.cuitproveedor,
		proveedores.nomproveedor
	FROM (ingredientes INNER JOIN medidas ON ingredientes.codmedida=medidas.codmedida)
	LEFT JOIN proveedores ON ingredientes.codproveedor=proveedores.codproveedor WHERE ingredientes.codingrediente = ?";
		$stmt = $this->dbh->prepare($sql);
		$stmt->execute(array(decrypt($_GET["codingrediente"])));
		$num = $stmt->rowCount();
		if($num==0)
		{
			echo "";
		}
		else
		{
			if($row = $stmt->fetch(PDO::FETCH_ASSOC))
			{
				$this->p[] = $row;
			}
			return $this->p;
			$this->dbh=null;
		}
	}
############################ FUNCION ID INGREDIENTES #################################

############################ FUNCION VER INGREDIENTES EN PRODUCTOS ############################
public function VerDetallesIngredientes()
{
	self::SetNames();
	$sql ="SELECT 
	productosxingredientes.codproducto, 
	productosxingredientes.cantracion, 
	ingredientes.codingrediente, 
	ingredientes.nomingrediente, 
	ingredientes.precioventa, 
	ingredientes.cantingrediente, 
	ingredientes.codmedida, 
	medidas.nommedida 
	FROM (productos LEFT JOIN productosxingredientes ON productos.codproducto=productosxingredientes.codproducto) 
	LEFT JOIN ingredientes ON ingredientes.codingrediente=productosxingredientes.codingrediente
	INNER JOIN medidas ON ingredientes.codmedida=medidas.codmedida WHERE productosxingredientes.codproducto = ?";
	    $stmt = $this->dbh->prepare($sql);
		$stmt->execute(array(decrypt($_GET["codproducto"])));
		$num = $stmt->rowCount();
		if($num==0)
		{
		echo "";		
		}
		else
		{
			while($row = $stmt->fetch(PDO::FETCH_ASSOC))
			{
				$this->p[]=$row;
			}
			return $this->p;
			$this->dbh=null;
		}
}
############################ FUNCION VER INGREDIENTES EN PRODUCTOS ############################

############################ FUNCION ACTUALIZAR INGREDIENTES ############################
	public function ActualizarIngredientes()
	{
	self::SetNames();
		if(empty($_POST["codingrediente"]) or empty($_POST["nomingrediente"]) or empty($_POST["codmedida"]))
		{
			echo "1";
			exit;
		}
		$sql = "SELECT codingrediente FROM ingredientes WHERE idingrediente != ? AND codingrediente = ?";
		$stmt = $this->dbh->prepare($sql);
		$stmt->execute(array($_POST["idingrediente"],$_POST["codingrediente"]));
		$num = $stmt->rowCount();
		if($num == 0)
		{
			$sql = "UPDATE ingredientes set"
			." nomingrediente = ?, "
			." codmedida = ?, "
			." preciocompra = ?, "
			." precioventa = ?, "
			." cantingrediente = ?, "
			." stockminimo = ?, "
			." stockmaximo = ?, "
			." ivaingrediente = ?, "
			." descingrediente = ?, "
			." lote = ?, "
			." fechaexpiracion = ?, "
			." codproveedor = ? "
			." where "
			." idingrediente = ?;
			";
			$stmt = $this->dbh->prepare($sql);
			$stmt->bindParam(1, $nomingrediente);
			$stmt->bindParam(2, $codmedida);
			$stmt->bindParam(3, $preciocompra);
			$stmt->bindParam(4, $precioventa);
			$stmt->bindParam(5, $cantingrediente);
			$stmt->bindParam(6, $stockminimo);
			$stmt->bindParam(7, $stockmaximo);
			$stmt->bindParam(8, $ivaingrediente);
			$stmt->bindParam(9, $descingrediente);
			$stmt->bindParam(10, $lote);
			$stmt->bindParam(11, $fechaexpiracion);
			$stmt->bindParam(12, $codproveedor);
			$stmt->bindParam(13, $idingrediente);

			$nomingrediente = limpiar($_POST["nomingrediente"]);
			$codmedida = limpiar($_POST["codmedida"]);
			$preciocompra = limpiar($_POST["preciocompra"]);
			$precioventa = limpiar($_POST["precioventa"]);
			$cantingrediente = limpiar($_POST["cantingrediente"]);
			$stockminimo = limpiar($_POST["stockminimo"]);
			$stockmaximo = limpiar($_POST["stockmaximo"]);
			$ivaingrediente = limpiar($_POST["ivaingrediente"]);
			$descingrediente = limpiar($_POST["descingrediente"]);
			$lote = limpiar($_POST['lote'] == '' ? "0" : $_POST['lote']);
			if (limpiar($_POST['fechaexpiracion']=="")) { $fechaexpiracion = "0000-00-00";  } else { $fechaexpiracion = limpiar(date("Y-m-d",strtotime($_POST['fechaexpiracion']))); }
			$codproveedor = limpiar($_POST['codproveedor'] == '' ? "0" : $_POST['codproveedor']);
			$codingrediente = limpiar($_POST["codingrediente"]);
			$idingrediente = limpiar($_POST["idingrediente"]);
			$stmt->execute();
        
		echo "<span class='fa fa-check-square-o'></span> EL INGREDIENTE HA SIDO ACTUALIZADO EXITOSAMENTE";
		exit;

		} else {

			echo "2";
			exit;
		}
	}
############################ FUNCION ACTUALIZAR INGREDIENTES ############################

########################## FUNCION ELIMINAR DETALLES INGREDIENTES ###########################
	public function EliminarDetalleIngrediente()
	{
	self::SetNames();
		if ($_SESSION["acceso"]=="administrador") {

		$sql = "DELETE FROM productosxingredientes WHERE codproducto = ? and codingrediente = ?";
		$stmt = $this->dbh->prepare($sql);
		$stmt->bindParam(1,$codproducto);
		$stmt->bindParam(2,$codingrediente);
		$codproducto = decrypt($_GET["codproducto"]);
		$codingrediente = decrypt($_GET["codingrediente"]);
		$stmt->execute();

			echo "1";
			exit;

		} else {
		   
			echo "2";
			exit;
		  } 
}
########################## FUNCION ELIMINAR DETALLES INGREDIENTES #################################

########################## FUNCION ELIMINAR INGREDIENTES ###########################
	public function EliminarIngredientes()
	{
	self::SetNames();
		if ($_SESSION["acceso"]=="administrador") {

		$sql = "SELECT codingrediente FROM productosxingredientes WHERE codingrediente = ?";
		$stmt = $this->dbh->prepare($sql);
		$stmt->execute(array(decrypt($_GET["codingrediente"])));
		$num = $stmt->rowCount();
		if($num == 0)
		{

			$sql = "DELETE FROM ingredientes WHERE codingrediente = ?";
			$stmt = $this->dbh->prepare($sql);
			$stmt->bindParam(1,$codingrediente);
			$codingrediente = decrypt($_GET["codingrediente"]);
			$stmt->execute();

			$sql = "DELETE FROM kardex_ingredientes where codingrediente = ?";
			$stmt = $this->dbh->prepare($sql);
			$stmt->bindParam(1,$codingrediente);
			$codingrediente = decrypt($_GET["codingrediente"]);
			$stmt->execute();

			echo "1";
			exit;

		} else {
		   
			echo "2";
			exit;
		  } 
			
		} else {
		
		echo "3";
		exit;
	 }	
}
########################## FUNCION ELIMINAR INGREDIENTES #################################

######################## FUNCION BUSCA KARDEX INGREDIENTES ##########################
public function BuscarKardexIngrediente() 
	       {
		self::SetNames();
		$sql ="SELECT * FROM (ingredientes LEFT JOIN kardex_ingredientes ON ingredientes.codingrediente=kardex_ingredientes.codingrediente) 
		LEFT JOIN medidas ON ingredientes.codmedida=medidas.codmedida 
		WHERE kardex_ingredientes.codingrediente = ?";
        $stmt = $this->dbh->prepare($sql);
		$stmt->execute(array($_GET["codingrediente"]));
		$num = $stmt->rowCount();
		if($num==0)
		{
		echo "<div class='alert alert-danger'>";
		echo "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>";
		echo "<center><span class='fa fa-info-circle'></span> NO EXISTEN MOVIMIENTOS EN KARDEX PARA EL INGREDIENTE INGRESADO</center>";
		echo "</div>";		
		exit;
		}
		else
		{
			while($row = $stmt->fetch(PDO::FETCH_ASSOC))
			{
				$this->p[]=$row;
			}
			return $this->p;
			$this->dbh=null;
		}
	}
######################## FUNCION BUSCA KARDEX INGREDIENTES #########################

###################### FUNCION BUSCAR INGREDIENTES FACTURADOS #########################
public function BuscarIngredientesVendidos() 
	       {
		self::SetNames();
       $sql = "SELECT productos.codproducto, productos.producto, productos.codcategoria, ingredientes.precioventa, ingredientes.codingrediente, ingredientes.nomingrediente, ingredientes.cantingrediente, ingredientes.descingrediente, productosxingredientes.cantracion, medidas.nommedida, detalleventas.cantventa, SUM(productosxingredientes.cantracion*detalleventas.cantventa) as cantidad
       FROM (ventas LEFT JOIN detalleventas ON ventas.codventa=detalleventas.codventa)
       LEFT JOIN productos ON detalleventas.codproducto=productos.codproducto
       INNER JOIN productosxingredientes ON productos.codproducto=productosxingredientes.codproducto 
       LEFT JOIN ingredientes ON productosxingredientes.codingrediente = ingredientes.codingrediente 
       LEFT JOIN medidas ON ingredientes.codmedida = medidas.codmedida 
       WHERE DATE_FORMAT(ventas.fechaventa,'%Y-%m-%d') >= ? AND DATE_FORMAT(ventas.fechaventa,'%Y-%m-%d') <= ? GROUP BY ingredientes.codingrediente";

       /*$sql = "SELECT productos.codproducto, productos.producto, productos.codcategoria, productos.precioventa, productos.existencia, ingredientes.costoingrediente, ingredientes.codingrediente, ingredientes.nomingrediente, ingredientes.cantingrediente, productosvsingredientes.cantracion, detalleventas.cantventa, SUM(productosvsingredientes.cantracion*detalleventas.cantventa) as cantidades FROM productos INNER JOIN detalleventas ON productos.codproducto=detalleventas.codproducto INNER JOIN productosvsingredientes ON productos.codproducto=productosvsingredientes.codproducto LEFT JOIN ingredientes ON productosvsingredientes.codingrediente = ingredientes.codingrediente WHERE DATE_FORMAT(detalleventas.fechadetalleventa,'%Y-%m-%d') >= ? AND DATE_FORMAT(detalleventas.fechadetalleventa,'%Y-%m-%d') <= ? GROUP BY ingredientes.codingrediente";

       $sql ="SELECT ingredientes.codingrediente, ingredientes.nomingrediente, ingredientes.codmedida, detalleventas.descproducto, detalleventas.precioventa, productos.existencia, categorias.nomcategoria, ventas.fechaventa, SUM(detalleventas.cantventa) as cantidad 
       FROM (ventas LEFT JOIN detalleventas ON ventas.codventa=detalleventas.codventa) LEFT JOIN productos ON detalleventas.codproducto=productos.codproducto LEFT JOIN categorias ON productos.codcategoria=categorias.codcategoria WHERE DATE_FORMAT(ventas.fechaventa,'%Y-%m-%d') >= ? AND DATE_FORMAT(ventas.fechaventa,'%Y-%m-%d') <= ? GROUP BY detalleventas.codproducto, detalleventas.precioventa, detalleventas.descproducto ORDER BY productos.codproducto ASC";*/

		$stmt = $this->dbh->prepare($sql);
		$stmt->bindValue(1, trim(date("Y-m-d",strtotime($_GET['desde']))));
		$stmt->bindValue(2, trim(date("Y-m-d",strtotime($_GET['hasta']))));
		$stmt->execute();
		$num = $stmt->rowCount();
		if($num==0)
		{
		echo "<div class='alert alert-danger'>";
		echo "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>";
		echo "<center><span class='fa fa-info-circle'></span> NO EXISTEN INGREDIENTES FACTURADOS PARA EL RANGO DE FECHA INGRESADA</center>";
		echo "</div>";		
		exit;
		}
		else
		{
			while($row = $stmt->fetch(PDO::FETCH_ASSOC))
			{
				$this->p[]=$row;
			}
			return $this->p;
			$this->dbh=null;
		}
	}
########################### FUNCION INGREDIENTES FACTURADOS ###############################

############################### FIN DE CLASE INGREDIENTES ###############################






























################################# CLASE PRODUCTOS ######################################

############################### FUNCION CARGAR PRODUCTOS ##############################
	public function CargarProductos()
	{
		self::SetNames();
		if(empty($_FILES["sel_file"]))
		{
			echo "1";
			exit;
		}

		$sql = " SELECT porcentaje FROM configuracion WHERE id = 1";
		foreach ($this->dbh->query($sql) as $row)
		{
			$this->p[] = $row;
		}
		$porcentaje = $row['porcentaje'];

        //Aquí es donde seleccionamos nuestro csv
         $fname = $_FILES['sel_file']['name'];
         //echo 'Cargando nombre del archivo: '.$fname.' ';
         $chk_ext = explode(".",$fname);
         
        if(strtolower(end($chk_ext)) == "csv")
        {
        //si es correcto, entonces damos permisos de lectura para subir
        $filename = $_FILES['sel_file']['tmp_name'];
        $handle = fopen($filename, "r");
        $this->dbh->beginTransaction();
        
        while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {

               //Insertamos los datos con los valores...
    $query = "INSERT INTO productos values (null, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);";
    	$stmt = $this->dbh->prepare($query);
    	$stmt->bindParam(1, $codproducto);
    	$stmt->bindParam(2, $producto);
    	$stmt->bindParam(3, $codcategoria);
    	$stmt->bindParam(4, $preciocompra);
    	$stmt->bindParam(5, $precioventa);
    	$stmt->bindParam(6, $existencia);
    	$stmt->bindParam(7, $stockminimo);
    	$stmt->bindParam(8, $stockmaximo);
    	$stmt->bindParam(9, $ivaproducto);
    	$stmt->bindParam(10, $descproducto);
    	$stmt->bindParam(11, $codigobarra);
		$stmt->bindParam(12, $lote);
    	$stmt->bindParam(13, $fechaelaboracion);
    	$stmt->bindParam(14, $fechaexpiracion);
    	$stmt->bindParam(15, $codproveedor);
    	$stmt->bindParam(16, $stockteorico);
    	$stmt->bindParam(17, $motivoajuste);
    	$stmt->bindParam(18, $favorito);

    	$codproducto = limpiar($data[0]);
    	$producto = limpiar($data[1]);
    	$codcategoria = limpiar($data[2]);
    	$preciocompra = limpiar($data[3]);
    	$calculo = number_format($data[3]*($porcentaje/100), 2, '.', '');
    	$calculoventa = number_format($data[3]+$calculo, 2, '.', '');
    	$precioventa = ($porcentaje == "0.00" ? limpiar($data[4]) : $calculoventa);
    	//$precioventa = limpiar($data[4]);
    	$existencia = limpiar($data[5]);
    	$stockminimo = limpiar($data[6]);
    	$stockmaximo = limpiar($data[7]);
    	$ivaproducto = limpiar($data[8]);
    	$descproducto = limpiar($data[9]);
    	$codigobarra = limpiar($data[10]);
    	$lote = limpiar($data[11]);
    	$fechaelaboracion = limpiar($data[12]);
    	$fechaexpiracion = limpiar($data[13]);
    	$codproveedor = limpiar($data[14]);
    	$stockteorico = limpiar("0");
    	$motivoajuste = limpiar("NINGUNO");
    	$favorito = limpiar($data[15]);
    	$stmt->execute();

##################### REGISTRAMOS LOS DATOS DE PRODUCTOS EN KARDEX #####################
		$query = "INSERT INTO kardex_productos values (null, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);";
		$stmt = $this->dbh->prepare($query);
		$stmt->bindParam(1, $codproceso);
		$stmt->bindParam(2, $codresponsable);
		$stmt->bindParam(3, $codproducto);
		$stmt->bindParam(4, $movimiento);
		$stmt->bindParam(5, $entradas);
		$stmt->bindParam(6, $salidas);
		$stmt->bindParam(7, $devolucion);
		$stmt->bindParam(8, $stockactual);
		$stmt->bindParam(9, $ivaproducto);
		$stmt->bindParam(10, $descproducto);
		$stmt->bindParam(11, $precio);
		$stmt->bindParam(12, $documento);
		$stmt->bindParam(13, $fechakardex);
		
		$codproceso = limpiar($data[0]);
		$codresponsable = limpiar("0");
		$codproducto = limpiar($data[0]);
		$movimiento = limpiar("ENTRADAS");
		$entradas = limpiar($data[5]);
		$salidas = limpiar("0");
		$devolucion = limpiar("0");
		$stockactual = limpiar($data[5]);
		$ivaproducto = limpiar($data[8]);
		$descproducto = limpiar($data[9]);
    	$calculo = number_format($data[3]*($porcentaje/100), 2, '.', '');
    	$calculoventa = number_format($data[3]+$calculo, 2, '.', '');
    	$precio = ($porcentaje == "0.00" ? limpiar($data[4]) : $calculoventa);
		//$precio = limpiar($data[4]);
		$documento = limpiar("INVENTARIO INICIAL");
		$fechakardex = limpiar(date("Y-m-d"));
		$stmt->execute();
##################### REGISTRAMOS LOS DATOS DE PRODUCTOS EN KARDEX #####################
	
        }
           
           $this->dbh->commit();
           //cerramos la lectura del archivo "abrir archivo" con un "cerrar archivo"
           fclose($handle);
	        
	echo "<span class='fa fa-check-square-o'></span> LA CARGA MASIVA DE PRODUCTOS FUE REALIZADA EXITOSAMENTE";
	exit;
             
         }
         else
         {
    //si aparece esto es posible que el archivo no tenga el formato adecuado, inclusive cuando es cvs, revisarlo para ver si esta separado por " , "
         echo "2";
		 exit;
      }  
}
############################## FUNCION CARGAR PRODUCTOS ##############################

########################### FUNCION REGISTRAR PRODUCTOS ###############################
	public function RegistrarProductos()
	{
		self::SetNames();
		if(empty($_POST["codproducto"]) or empty($_POST["producto"]) or empty($_POST["codcategoria"]))
		{
			echo "1";
			exit;
		}

		if (isset($_POST["codingrediente"])) {

		$ingrediente = $_POST['codingrediente'];
		$repeated = array_filter(array_count_values($ingrediente), function($count) {
			return $count > 1;
		});
		foreach ($repeated as $key => $value) {
			echo "2";
			exit;
		}
	}


		$sql = " SELECT codproducto FROM productos WHERE codproducto = ?";
		$stmt = $this->dbh->prepare($sql);
		$stmt->execute(array($_POST["codproducto"]));
		$num = $stmt->rowCount();
		if($num == 0)
		{
	$query = "INSERT INTO productos values (null, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);";
			$stmt = $this->dbh->prepare($query);
			$stmt->bindParam(1, $codproducto);
			$stmt->bindParam(2, $producto);
			$stmt->bindParam(3, $codcategoria);
			$stmt->bindParam(4, $preciocompra);
			$stmt->bindParam(5, $precioventa);
			$stmt->bindParam(6, $existencia);
			$stmt->bindParam(7, $stockminimo);
			$stmt->bindParam(8, $stockmaximo);
			$stmt->bindParam(9, $ivaproducto);
			$stmt->bindParam(10, $descproducto);
			$stmt->bindParam(11, $codigobarra);
			$stmt->bindParam(12, $lote);
			$stmt->bindParam(13, $fechaelaboracion);
			$stmt->bindParam(14, $fechaexpiracion);
			$stmt->bindParam(15, $codproveedor);
			$stmt->bindParam(16, $stockteorico);
			$stmt->bindParam(17, $motivoajuste);
			$stmt->bindParam(18, $favorito);

			$codproducto = limpiar($_POST["codproducto"]);
			$producto = limpiar($_POST["producto"]);
			$codcategoria = limpiar($_POST["codcategoria"]);
			$preciocompra = limpiar($_POST["preciocompra"]);
			$precioventa = limpiar($_POST["precioventa"]);
			$existencia = limpiar($_POST["existencia"]);
			$stockminimo = limpiar($_POST["stockminimo"]);
			$stockmaximo = limpiar($_POST["stockmaximo"]);
			$ivaproducto = limpiar($_POST["ivaproducto"]);
			$descproducto = limpiar($_POST["descproducto"]);
			$codigobarra = limpiar($_POST['codigobarra'] == '' ? "0" : $_POST['codigobarra']);
			$lote = limpiar($_POST['lote'] == '' ? "0" : $_POST['lote']);
			if (limpiar($_POST['fechaelaboracion']=="")) { $fechaelaboracion = "0000-00-00";  } else { $fechaelaboracion = limpiar(date("Y-m-d",strtotime($_POST['fechaelaboracion']))); }
			if (limpiar($_POST['fechaexpiracion']=="")) { $fechaexpiracion = "0000-00-00";  } else { $fechaexpiracion = limpiar(date("Y-m-d",strtotime($_POST['fechaexpiracion']))); }
			$codproveedor = limpiar($_POST["codproveedor"]);
			$stockteorico = limpiar("0");
			$motivoajuste = limpiar("NINGUNO");
			$favorito = limpiar("0");
			$stmt->execute();

##################### REGISTRAMOS LOS DATOS DE PRODUCTOS EN KARDEX #####################
			$query = "INSERT INTO kardex_productos values (null, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?); ";
			$stmt = $this->dbh->prepare($query);
			$stmt->bindParam(1, $codproceso);
			$stmt->bindParam(2, $codresponsable);
			$stmt->bindParam(3, $codproducto);
			$stmt->bindParam(4, $movimiento);
			$stmt->bindParam(5, $entradas);
			$stmt->bindParam(6, $salidas);
			$stmt->bindParam(7, $devolucion);
			$stmt->bindParam(8, $stockactual);
			$stmt->bindParam(9, $ivaproducto);
			$stmt->bindParam(10, $descproducto);
			$stmt->bindParam(11, $precio);
			$stmt->bindParam(12, $documento);
			$stmt->bindParam(13, $fechakardex);

			$codproceso = limpiar($_POST['codproducto']);
			$codresponsable = limpiar("0");
			$codproducto = limpiar($_POST['codproducto']);
			$movimiento = limpiar("ENTRADAS");
			$entradas = limpiar($_POST['existencia']);
			$salidas = limpiar("0");
			$devolucion = limpiar("0");
			$stockactual = limpiar($_POST['existencia']);
			$ivaproducto = limpiar($_POST["ivaproducto"]);
			$descproducto = limpiar($_POST["descproducto"]);
			$precio = limpiar($_POST['precioventa']);
			$documento = limpiar("INVENTARIO INICIAL");
			$fechakardex = limpiar(date("Y-m-d"));
			$stmt->execute();
##################### REGISTRAMOS LOS DATOS DE PRODUCTOS EN KARDEX #####################


	##################  SUBIR FOTO DE PRODUCTO ######################################
         //datos del arhivo  
if (isset($_FILES['imagen']['name'])) { $nombre_archivo = $_FILES['imagen']['name']; } else { $nombre_archivo =''; }
if (isset($_FILES['imagen']['type'])) { $tipo_archivo = $_FILES['imagen']['type']; } else { $tipo_archivo =''; }
if (isset($_FILES['imagen']['size'])) { $tamano_archivo = $_FILES['imagen']['size']; } else { $tamano_archivo =''; } 
         //compruebo si las características del archivo son las que deseo  
if ((strpos($tipo_archivo,'image/jpeg')!==false)&&$tamano_archivo<200000) 
		 {  
if (move_uploaded_file($_FILES['imagen']['tmp_name'], "fotos/productos/".$nombre_archivo) && rename("fotos/productos/".$nombre_archivo,"fotos/productos/".$codproducto.".jpg"))
		 { 
		 ## se puede dar un aviso
		 } 
		 ## se puede dar otro aviso 
		 }
	##################  FINALIZA SUBIR FOTO DE PRODUCTO ######################################

	################## PROCESO DE REGISTRO DE INSCREDIENTES A PRODUCTOS ####################
	for($i=0;$i<count($_POST['codingrediente']);$i++){  //recorro el array
		if (!empty($_POST['codingrediente'][$i])) {

			$query = " INSERT INTO productosxingredientes values (null, ?, ?, ?); ";
			$stmt = $this->dbh->prepare($query);
			$stmt->bindParam(1, $codproducto);
			$stmt->bindParam(2, $codingrediente);
			$stmt->bindParam(3, $cantidad);

			$codproducto = limpiar($_POST["codproducto"]);
			$codingrediente = limpiar($_POST['codingrediente'][$i]);
			$cantidad = limpiar($_POST['cantidad'][$i]);
			$stmt->execute();
		}
	}
	################### PROCESO DE REGISTRO DE INSCREDIENTES A PRODUCTOS ##################

			echo "<span class='fa fa-check-square-o'></span> EL PRODUCTO HA SIDO REGISTRADO EXITOSAMENTE";
			exit;

		} else {

			echo "3";
			exit;
		}
	}
########################## FUNCION REGISTRAR PRODUCTOS ###############################

########################### FUNCION LISTAR PRODUCTOS ################################
	public function ListarProductos()
	{
		self::SetNames();
        $sql = "SELECT
		productos.idproducto,
		productos.codproducto,
		productos.producto,
		productos.codcategoria,
		productos.preciocompra,
		productos.precioventa,
		productos.existencia,
		productos.stockminimo,
		productos.stockmaximo,
		productos.ivaproducto,
		productos.descproducto,
		productos.codigobarra,
		productos.lote,
		productos.fechaelaboracion,
		productos.fechaexpiracion,
		productos.codproveedor,
		productos.stockteorico,
		productos.motivoajuste,
		productos.favorito,
		categorias.nomcategoria,
		proveedores.cuitproveedor,
		proveedores.nomproveedor
	FROM (productos INNER JOIN categorias ON productos.codcategoria=categorias.codcategoria)
	LEFT JOIN proveedores ON productos.codproveedor=proveedores.codproveedor";
	foreach ($this->dbh->query($sql) as $row)
	{
		$this->p[] = $row;
	}
	return $this->p;
	$this->dbh=null;
}
########################## FUNCION LISTAR PRODUCTOS ################################

########################### FUNCION LISTAR PRODUCTOS EN STOCK MINIMO ################################
	public function ListarProductosMinimo()
	{
		self::SetNames();
        $sql = "SELECT
		productos.idproducto,
		productos.codproducto,
		productos.producto,
		productos.codcategoria,
		productos.preciocompra,
		productos.precioventa,
		productos.existencia,
		productos.stockminimo,
		productos.stockmaximo,
		productos.ivaproducto,
		productos.descproducto,
		productos.codigobarra,
		productos.lote,
		productos.fechaelaboracion,
		productos.fechaexpiracion,
		productos.codproveedor,
		productos.stockteorico,
		productos.motivoajuste,
		productos.favorito,
		categorias.nomcategoria,
		proveedores.cuitproveedor,
		proveedores.nomproveedor
	FROM (productos INNER JOIN categorias ON productos.codcategoria=categorias.codcategoria)
	LEFT JOIN proveedores ON productos.codproveedor=proveedores.codproveedor
	WHERE productos.existencia <= productos.stockminimo";
	foreach ($this->dbh->query($sql) as $row)
	{
		$this->p[] = $row;
	}
	return $this->p;
	$this->dbh=null;
}
########################## FUNCION LISTAR PRODUCTOS EN STOCK MINIMO ################################

########################### FUNCION LISTAR PRODUCTOS EN STOCK MAXIMO ################################
	public function ListarProductosMaximo()
	{
		self::SetNames();
        $sql = "SELECT
		productos.idproducto,
		productos.codproducto,
		productos.producto,
		productos.codcategoria,
		productos.preciocompra,
		productos.precioventa,
		productos.existencia,
		productos.stockminimo,
		productos.stockmaximo,
		productos.ivaproducto,
		productos.descproducto,
		productos.codigobarra,
		productos.lote,
		productos.fechaelaboracion,
		productos.fechaexpiracion,
		productos.codproveedor,
		productos.stockteorico,
		productos.motivoajuste,
		productos.favorito,
		categorias.nomcategoria,
		proveedores.cuitproveedor,
		proveedores.nomproveedor
	FROM (productos INNER JOIN categorias ON productos.codcategoria=categorias.codcategoria)
	LEFT JOIN proveedores ON productos.codproveedor=proveedores.codproveedor
	WHERE productos.existencia >= productos.stockmaximo";
	foreach ($this->dbh->query($sql) as $row)
	{
		$this->p[] = $row;
	}
	return $this->p;
	$this->dbh=null;
}
########################## FUNCION LISTAR PRODUCTOS EN STOCK MAXIMO ################################

############################# FUNCION LISTAR PRODUCTOS ################################
	public function ListarProductosModal()
	{
		self::SetNames();
        $sql = "SELECT * FROM productos 
        INNER JOIN categorias ON productos.codcategoria=categorias.codcategoria";
	foreach ($this->dbh->query($sql) as $row)
	{
		$this->p[] = $row;
	}
	return $this->p;
	$this->dbh=null;
}
########################## FUNCION LISTAR PRODUCTOS ################################

############################ FUNCION ID PRODUCTOS #################################
	public function ProductosPorId()
	{
		self::SetNames();
		$sql = "SELECT
		productos.idproducto,
		productos.codproducto,
		productos.producto,
		productos.codcategoria,
		productos.preciocompra,
		productos.precioventa,
		productos.existencia,
		productos.stockminimo,
		productos.stockmaximo,
		productos.ivaproducto,
		productos.descproducto,
		productos.codigobarra,
		productos.lote,
		productos.fechaelaboracion,
		productos.fechaexpiracion,
		productos.codproveedor,
		productos.stockteorico,
		productos.motivoajuste,
		productos.favorito,
		categorias.nomcategoria,
		proveedores.cuitproveedor,
		proveedores.nomproveedor
	FROM(productos LEFT JOIN categorias ON productos.codcategoria=categorias.codcategoria)
	LEFT JOIN proveedores ON productos.codproveedor=proveedores.codproveedor WHERE productos.codproducto = ?";
		$stmt = $this->dbh->prepare($sql);
		$stmt->execute(array(decrypt($_GET["codproducto"])));
		$num = $stmt->rowCount();
		if($num==0)
		{
			echo "";
		}
		else
		{
			if($row = $stmt->fetch(PDO::FETCH_ASSOC))
			{
				$this->p[] = $row;
			}
			return $this->p;
			$this->dbh=null;
		}
	}
############################ FUNCION ID PRODUCTOS #################################

############################ FUNCION ACTUALIZAR PRODUCTOS ############################
	public function ActualizarProductos()
	{
	self::SetNames();
		if(empty($_POST["codproducto"]) or empty($_POST["producto"]) or empty($_POST["codcategoria"]))
		{
			echo "1";
			exit;
		}
 
    if (isset($_POST["codingrediente"])) {

		$ingrediente = $_POST['codingrediente'];
		$repeated = array_filter(array_count_values($ingrediente), function($count) {
			return $count > 1;
		});
		foreach ($repeated as $key => $value) {
			echo "2";
			exit;
		}
	}

		$sql = "SELECT codproducto FROM productos WHERE idproducto != ? AND codproducto = ?";
		$stmt = $this->dbh->prepare($sql);
		$stmt->execute(array($_POST["idproducto"],$_POST["codproducto"]));
		$num = $stmt->rowCount();
		if($num == 0)
		{
			$sql = "UPDATE productos set"
			." producto = ?, "
			." codcategoria = ?, "
			." preciocompra = ?, "
			." precioventa = ?, "
			." existencia = ?, "
			." stockminimo = ?, "
			." stockmaximo = ?, "
			." ivaproducto = ?, "
			." descproducto = ?, "
			." codigobarra = ?, "
			." lote = ?, "
			." fechaelaboracion = ?, "
			." fechaexpiracion = ?, "
			." codproveedor = ? "
			." where "
			." idproducto = ?;
			";
			$stmt = $this->dbh->prepare($sql);
			$stmt->bindParam(1, $producto);
			$stmt->bindParam(2, $codcategoria);
			$stmt->bindParam(3, $preciocompra);
			$stmt->bindParam(4, $precioventa);
			$stmt->bindParam(5, $existencia);
			$stmt->bindParam(6, $stockminimo);
			$stmt->bindParam(7, $stockmaximo);
			$stmt->bindParam(8, $ivaproducto);
			$stmt->bindParam(9, $descproducto);
			$stmt->bindParam(10, $codigobarra);
			$stmt->bindParam(11, $lote);
			$stmt->bindParam(12, $fechaelaboracion);
			$stmt->bindParam(13, $fechaexpiracion);
			$stmt->bindParam(14, $codproveedor);
			$stmt->bindParam(15, $idproducto);

			$producto = limpiar($_POST["producto"]);
			$codcategoria = limpiar($_POST["codcategoria"]);
			$preciocompra = limpiar($_POST["preciocompra"]);
			$precioventa = limpiar($_POST["precioventa"]);
			$existencia = limpiar($_POST["existencia"]);
			$stockminimo = limpiar($_POST["stockminimo"]);
			$stockmaximo = limpiar($_POST["stockmaximo"]);
			$ivaproducto = limpiar($_POST["ivaproducto"]);
			$descproducto = limpiar($_POST["descproducto"]);
			$codigobarra = limpiar($_POST['codigobarra'] == '' ? "0" : $_POST['codigobarra']);
			$lote = limpiar($_POST['lote'] == '' ? "0" : $_POST['lote']);
			if (limpiar($_POST['fechaelaboracion']=="")) { $fechaelaboracion = "0000-00-00";  } else { $fechaelaboracion = limpiar(date("Y-m-d",strtotime($_POST['fechaelaboracion']))); }
			if (limpiar($_POST['fechaexpiracion']=="")) { $fechaexpiracion = "0000-00-00";  } else { $fechaexpiracion = limpiar(date("Y-m-d",strtotime($_POST['fechaexpiracion']))); }
			$codproveedor = limpiar($_POST["codproveedor"]);
			$codproducto = limpiar($_POST["codproducto"]);
			$idproducto = limpiar($_POST["idproducto"]);
			$stmt->execute();

			$sql = "DELETE FROM productosxingredientes WHERE codproducto = ?";
			$stmt = $this->dbh->prepare($sql);
			$stmt->bindParam(1,$codproducto);
			$codproducto = limpiar($_POST["codproducto"]);
			$stmt->execute();


	##################  SUBIR FOTO DE PRODUCTO ######################################
         //datos del arhivo  
if (isset($_FILES['imagen']['name'])) { $nombre_archivo = $_FILES['imagen']['name']; } else { $nombre_archivo =''; }
if (isset($_FILES['imagen']['type'])) { $tipo_archivo = $_FILES['imagen']['type']; } else { $tipo_archivo =''; }
if (isset($_FILES['imagen']['size'])) { $tamano_archivo = $_FILES['imagen']['size']; } else { $tamano_archivo =''; } 
         //compruebo si las características del archivo son las que deseo  
if ((strpos($tipo_archivo,'image/jpeg')!==false)&&$tamano_archivo<200000) 
		 {  
if (move_uploaded_file($_FILES['imagen']['tmp_name'], "fotos/productos/".$nombre_archivo) && rename("fotos/productos/".$nombre_archivo,"fotos/productos/".$codproducto.".jpg"))
		 { 
		 ## se puede dar un aviso
		 } 
		 ## se puede dar otro aviso 
		 }
	################## FINALIZA SUBIR FOTO DE PRODUCTO ##########################

	################## PROCESO DE REGISTRO DE INSCREDIENTES A PRODUCTOS ####################
	if (isset($_POST["codingrediente"])) {
	    for($i=0;$i<count($_POST['codingrediente']);$i++){  //recorro el array
		   if (!empty($_POST['codingrediente'][$i])) {

			$query = " INSERT INTO productosxingredientes values (null, ?, ?, ?); ";
			$stmt = $this->dbh->prepare($query);
			$stmt->bindParam(1, $codproducto);
			$stmt->bindParam(2, $codingrediente);
			$stmt->bindParam(3, $cantidad);

			$codproducto = limpiar($_POST["codproducto"]);
			$codingrediente = limpiar($_POST['codingrediente'][$i]);
			$cantidad = limpiar($_POST['cantidad'][$i]);
			$stmt->execute();
		   }
       }
	}
	################### PROCESO DE REGISTRO DE INSCREDIENTES A PRODUCTOS ##################
        
		echo "<span class='fa fa-check-square-o'></span> EL PRODUCTO HA SIDO ACTUALIZADO EXITOSAMENTE";
		exit;

		} else {

			echo "3";
			exit;
		}
	}
############################ FUNCION ACTUALIZAR PRODUCTOS ############################

############################ FUNCION AGREGAR INGREDIENTES A PRODUCTOS ############################
	public function AgregarIngredientes()
	{
	self::SetNames();
		if(empty($_POST["codproducto"]) or empty($_POST["codingrediente"]) or empty($_POST["cantidad"]))
		{
			echo "1";
			exit;
		}

		$ingrediente = $_POST['codingrediente'];
		$repeated = array_filter(array_count_values($ingrediente), function($count) {
			return $count > 1;
		});
		foreach ($repeated as $key => $value) {
			echo "2";
			exit;
		}

	############## VALIDO SI LOS NUEVOS INGREDIENTES YA ESTAN ASIGNADOS ############
	for($i=0;$i<count($_POST['codingrediente']);$i++){  //recorro el array
		if (!empty($_POST['codingrediente'][$i])) {

			$sql = " SELECT * FROM productosxingredientes WHERE codproducto = ? and codingrediente = ?";
			$stmt = $this->dbh->prepare($sql);
			$stmt->execute(array($_POST["codproducto"], $_POST["codingrediente"][$i]));
			$num = $stmt->rowCount();
			if($num > 0)
			{
				echo "3";
				exit;
			}
		}
	}
	############## VALIDO SI LOS NUEVOS INGREDIENTES YA ESTAN ASIGNADOS ############


	################## PROCESO DE REGISTRO DE INSCREDIENTES A PRODUCTOS ####################
	for($i=0;$i<count($_POST['codingrediente']);$i++){  //recorro el array
		if (!empty($_POST['codingrediente'][$i])) {

			$query = " INSERT INTO productosxingredientes values (null, ?, ?, ?); ";
			$stmt = $this->dbh->prepare($query);
			$stmt->bindParam(1, $codproducto);
			$stmt->bindParam(2, $codingrediente);
			$stmt->bindParam(3, $cantidad);

			$codproducto = limpiar($_POST["codproducto"]);
			$codingrediente = limpiar($_POST['codingrediente'][$i]);
			$cantidad = limpiar($_POST['cantidad'][$i]);
			$stmt->execute();
		}
	}
	################### PROCESO DE REGISTRO DE INSCREDIENTES A PRODUCTOS ##################	
        
		echo "<span class='fa fa-check-square-o'></span> LOS INGREDIENTES FUERON AGREGADOS AL PRODUCTO EXITOSAMENTE";
		exit;
}
############################ FUNCION AGREGAR INGREDIENTES A PRODUCTOS ############################

########################## FUNCION AJUSTAR STOCK DE PRODUCTOS ###########################
	public function ActualizarAjuste()
	{
	self::SetNames();
		if(empty($_POST["codproducto"]) or empty($_POST["stockteorico"]) or empty($_POST["motivoajuste"]))
		{
			echo "1";
		    exit;
		}
		
		$sql = "UPDATE productos set"
			  ." stockteorico = ?, "
			  ." motivoajuste = ? "
			  ." where "
			  ." idproducto = ?;
			   ";
		$stmt = $this->dbh->prepare($sql);
		$stmt->bindParam(1, $stockteorico);
		$stmt->bindParam(2, $motivoajuste);
	    $stmt->bindParam(3, $idproducto);
		
		$stockteorico = limpiar($_POST["stockteorico"]);
		$motivoajuste = limpiar($_POST["motivoajuste"]);
		$idproducto = limpiar($_POST["idproducto"]);
		$stmt->execute();
	
		echo "<span class='fa fa-check-square-o'></span> EL AJUSTE DE STOCK DEL PRODUCTO SE HA REALIZADO EXITOSAMENTE";
		exit;
	}
###################### FUNCION AJUSTAR STOCK DE PRODUCTOS #########################

########################## FUNCION ELIMINAR PRODUCTOS ###########################
	public function EliminarProductos()
	{
	self::SetNames();
		if ($_SESSION["acceso"]=="administrador") {

		$sql = "SELECT codproducto FROM detalleventas WHERE codproducto = ?";
		$stmt = $this->dbh->prepare($sql);
		$stmt->execute(array(decrypt($_GET["codproducto"])));
		$num = $stmt->rowCount();
		if($num == 0)
		{

			$sql = "DELETE FROM productos WHERE codproducto = ?";
			$stmt = $this->dbh->prepare($sql);
			$stmt->bindParam(1,$codproducto);
			$codproducto = decrypt($_GET["codproducto"]);
			$stmt->execute();

			$sql = "DELETE FROM kardex_productos where codproducto = ?";
			$stmt = $this->dbh->prepare($sql);
			$stmt->bindParam(1,$codproducto);
			$codproducto = decrypt($_GET["codproducto"]);
			$stmt->execute();

			$codproducto = decrypt($_GET["codproducto"]);
			if (file_exists("fotos/productos/".$codproducto.".jpg")){
		    //funcion para eliminar una carpeta con contenido
			$archivos = "fotos/productos/".$codproducto.".jpg";		
			unlink($archivos);
			}

			echo "1";
			exit;

		} else {
		   
			echo "2";
			exit;
		  } 
			
		} else {
		
		echo "3";
		exit;
	 }	
}
########################## FUNCION ELIMINAR PRODUCTOS #################################

######################## FUNCION BUSCA KARDEX PRODUCTOS ##########################
public function BuscarKardexProducto() 
	       {
		self::SetNames();
		$sql ="SELECT * FROM (productos LEFT JOIN kardex_productos ON productos.codproducto=kardex_productos.codproducto) 
		LEFT JOIN categorias ON productos.codcategoria=categorias.codcategoria 
		LEFT JOIN proveedores ON productos.codproveedor=proveedores.codproveedor 
		WHERE kardex_productos.codproducto = ?";
        $stmt = $this->dbh->prepare($sql);
		$stmt->execute(array($_GET["codproducto"]));
		$num = $stmt->rowCount();
		if($num==0)
		{
		echo "<div class='alert alert-danger'>";
		echo "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>";
		echo "<center><span class='fa fa-info-circle'></span> NO EXISTEN MOVIMIENTOS EN KARDEX PARA EL PRODUCTO INGRESADO</center>";
		echo "</div>";		
		exit;
		}
		else
		{
			while($row = $stmt->fetch(PDO::FETCH_ASSOC))
			{
				$this->p[]=$row;
			}
			return $this->p;
			$this->dbh=null;
		}
	}
######################## FUNCION BUSCA KARDEX PRODUCTOS #########################

###################### FUNCION BUSCAR PRODUCTOS FACTURADOS #########################
public function BuscarProductosVendidos() 
	       {
		self::SetNames();
       $sql ="SELECT productos.codproducto, productos.producto, productos.codcategoria, detalleventas.descproducto, detalleventas.precioventa, productos.existencia, categorias.nomcategoria, ventas.fechaventa, SUM(detalleventas.cantventa) as cantidad FROM (ventas LEFT JOIN detalleventas ON ventas.codventa=detalleventas.codventa) LEFT JOIN productos ON detalleventas.codproducto=productos.codproducto LEFT JOIN categorias ON productos.codcategoria=categorias.codcategoria WHERE DATE_FORMAT(ventas.fechaventa,'%Y-%m-%d') >= ? AND DATE_FORMAT(ventas.fechaventa,'%Y-%m-%d') <= ? GROUP BY detalleventas.codproducto, detalleventas.precioventa, detalleventas.descproducto ORDER BY productos.codproducto ASC";
		$stmt = $this->dbh->prepare($sql);
		$stmt->bindValue(1, trim(date("Y-m-d",strtotime($_GET['desde']))));
		$stmt->bindValue(2, trim(date("Y-m-d",strtotime($_GET['hasta']))));
		$stmt->execute();
		$num = $stmt->rowCount();
		if($num==0)
		{
		echo "<div class='alert alert-danger'>";
		echo "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>";
		echo "<center><span class='fa fa-info-circle'></span> NO EXISTEN PRODUCTOS FACTURADOS PARA EL RANGO DE FECHA INGRESADA</center>";
		echo "</div>";		
		exit;
		}
		else
		{
			while($row = $stmt->fetch(PDO::FETCH_ASSOC))
			{
				$this->p[]=$row;
			}
			return $this->p;
			$this->dbh=null;
		}
	}
########################### FUNCION PRODUCTOS FACTURADOS ###############################

############################### FIN DE CLASE PRODUCTOS ###############################







































###################################### CLASE COMPRAS ###################################

############################# FUNCION REGISTRAR COMPRAS #############################
	public function RegistrarCompras()
	{
		self::SetNames();
	if(empty($_POST["codcompra"]) or empty($_POST["fechaemision"]) or empty($_POST["fecharecepcion"]) or empty($_POST["codproveedor"]))
		{
			echo "1";
			exit;
		}

		if (limpiar(isset($_POST['fechavencecredito']))) {  

		$fechaactual = date("Y-m-d");
		$fechavence = date("Y-m-d",strtotime($_POST['fechavencecredito']));
		
	     if (strtotime($fechavence) < strtotime($fechaactual)) {
	  
	     echo "2";
		 exit;
	  
	         }
        }

		if(empty($_SESSION["CarritoCompra"]))
		{
			echo "3";
			exit;
			
		} else {

        $sql = "SELECT codcompra FROM compras WHERE codcompra = ?";
		$stmt = $this->dbh->prepare($sql);
		$stmt->execute(array($_POST['codcompra']));
		$num = $stmt->rowCount();
		if($num == 0)
		{

        $query = "INSERT INTO compras values (null, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);";
		$stmt = $this->dbh->prepare($query);
		$stmt->bindParam(1, $codcompra);
		$stmt->bindParam(2, $codproveedor);
		$stmt->bindParam(3, $subtotalivasic);
		$stmt->bindParam(4, $subtotalivanoc);
		$stmt->bindParam(5, $ivac);
		$stmt->bindParam(6, $totalivac);
		$stmt->bindParam(7, $descuentoc);
		$stmt->bindParam(8, $totaldescuentoc);
		$stmt->bindParam(9, $totalpagoc);
		$stmt->bindParam(10, $tipocompra);
		$stmt->bindParam(11, $formacompra);
		$stmt->bindParam(12, $fechavencecredito);
		$stmt->bindParam(13, $fechapagado);
		$stmt->bindParam(14, $observaciones);
		$stmt->bindParam(15, $statuscompra);
		$stmt->bindParam(16, $fechaemision);
		$stmt->bindParam(17, $fecharecepcion);
		$stmt->bindParam(18, $codigo);
	    
		$codcompra = limpiar($_POST["codcompra"]);
		$codproveedor = limpiar($_POST["codproveedor"]);
		$subtotalivasic = limpiar($_POST["txtsubtotal"]);
		$subtotalivanoc = limpiar($_POST["txtsubtotal2"]);
		$ivac = limpiar($_POST["iva"]);
		$totalivac = limpiar($_POST["txtIva"]);
		$descuentoc = limpiar($_POST["descuento"]);
		$totaldescuentoc = limpiar($_POST["txtDescuento"]);
		$totalpagoc = limpiar($_POST["txtTotal"]);
		$tipocompra = limpiar($_POST["tipocompra"]);
if (limpiar($_POST["tipocompra"]=="CONTADO")) { $formacompra = limpiar($_POST["formacompra"]); } else { $formacompra = "CREDITO"; }
if (limpiar($_POST["tipocompra"]=="CREDITO")) { $fechavencecredito = limpiar(date("Y-m-d",strtotime($_POST['fechavencecredito']))); } else { $fechavencecredito = "0000-00-00"; }
        $fechapagado = limpiar("0000-00-00");
        $observaciones = limpiar("NINGUNA");
if (limpiar($_POST["tipocompra"]=="CONTADO")) { $statuscompra = limpiar("PAGADA"); } else { $statuscompra = "PENDIENTE"; }
        $fechaemision = limpiar(date("Y-m-d",strtotime($_POST['fechaemision'])));
        $fecharecepcion = limpiar(date("Y-m-d",strtotime($_POST['fecharecepcion'])));
		$codigo = limpiar($_SESSION["codigo"]);
		$stmt->execute();
		
		$this->dbh->beginTransaction();

		$detalle = $_SESSION["CarritoCompra"];
		for($i=0;$i<count($detalle);$i++){

		$query = "INSERT INTO detallecompras values (null, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?); ";
		$stmt = $this->dbh->prepare($query);
		$stmt->bindParam(1, $codcompra);
	    $stmt->bindParam(2, $tipoentrada);
	    $stmt->bindParam(3, $codproducto);
	    $stmt->bindParam(4, $producto);
	    $stmt->bindParam(5, $codcategoria);
		$stmt->bindParam(6, $preciocomprac);
		$stmt->bindParam(7, $precioventac);
		$stmt->bindParam(8, $cantcompra);
		$stmt->bindParam(9, $ivaproductoc);
		$stmt->bindParam(10, $descproductoc);
		$stmt->bindParam(11, $descfactura);
		$stmt->bindParam(12, $valortotal);
		$stmt->bindParam(13, $totaldescuentoc);
		$stmt->bindParam(14, $valorneto);
		$stmt->bindParam(15, $lotec);
		$stmt->bindParam(16, $fechaelaboracionc);
		$stmt->bindParam(17, $fechaexpiracionc);
			
		$codcompra = limpiar($_POST['codcompra']);
		$tipoentrada = limpiar($detalle[$i]['tipoentrada']);
		$codproducto = limpiar($detalle[$i]['txtCodigo']);
		$producto = limpiar($detalle[$i]['producto']);
		$codcategoria = limpiar($detalle[$i]['codcategoria']);
		$preciocomprac = limpiar($detalle[$i]['precio']);
		$precioventac = limpiar($detalle[$i]['precio2']);
		$cantcompra = limpiar(number_format($detalle[$i]['cantidad'], 2, '.', ''));
		$ivaproductoc = limpiar($detalle[$i]['ivaproducto']);
		$descproductoc = limpiar($detalle[$i]['descproducto']);
		$descfactura = limpiar($detalle[$i]['descproductofact']);
		$descuento = $detalle[$i]["descproductofact"]/100;
		$valortotal = number_format($detalle[$i]['precio']*$detalle[$i]['cantidad'], 2, '.', '');
		$totaldescuentoc = number_format($valortotal*$descuento, 2, '.', '');
	    $valorneto = number_format($valortotal-$totaldescuentoc, 2, '.', '');

		$lotec = limpiar($detalle[$i]['lote']);
if (limpiar($detalle[$i]['fechaelaboracion']=="")) { $fechaelaboracionc = "0000-00-00";  } else { $fechaelaboracionc = limpiar(date("Y-m-d",strtotime($detalle[$i]['fechaelaboracion']))); }
if (limpiar($detalle[$i]['fechaexpiracion']=="")) { $fechaexpiracionc = "0000-00-00";  } else { $fechaexpiracionc = limpiar(date("Y-m-d",strtotime($detalle[$i]['fechaexpiracion']))); }
		$stmt->execute();


	if(limpiar($detalle[$i]['tipoentrada'])=="PRODUCTO"){


        ############### VERIFICO LA EXISTENCIA DEL PRODUCTO EN ALMACEN ################
		$sql = "SELECT existencia FROM productos WHERE codproducto = '".limpiar($detalle[$i]['txtCodigo'])."'";
		foreach ($this->dbh->query($sql) as $row)
		{
			$this->p[] = $row;
		}
		$existenciaproductobd = $row['existencia'];

		############# ACTUALIZAMOS LA EXISTENCIA DE PRODUCTOS COMPRADOS ###############
		$sql = "UPDATE productos set "
		      ." preciocompra = ?, "
			  ." precioventa = ?, "
			  ." existencia = ?, "
			  ." ivaproducto = ?, "
			  ." descproducto = ?, "
			  ." fechaelaboracion = ?, "
			  ." fechaexpiracion = ?, "
			  ." codproveedor = ?, "
			  ." lote = ? "
			  ." WHERE "
			  ." codproducto = ?;
			   ";
		$stmt = $this->dbh->prepare($sql);
		$stmt->bindParam(1, $preciocompra);
		$stmt->bindParam(2, $precioventa);
		$stmt->bindParam(3, $existencia);
		$stmt->bindParam(4, $ivaproducto);
		$stmt->bindParam(5, $descproducto);
		$stmt->bindParam(6, $fechaelaboracion);
		$stmt->bindParam(7, $fechaexpiracion);
		$stmt->bindParam(8, $codproveedor);
		$stmt->bindParam(9, $lote);
		$stmt->bindParam(10, $codproducto);
		
		$preciocompra = limpiar($detalle[$i]['precio']);
		$precioventa = limpiar($detalle[$i]['precio2']);
		$existencia = limpiar(number_format($detalle[$i]['cantidad']+$existenciaproductobd, 2, '.', ''));
		$ivaproducto = limpiar($detalle[$i]['ivaproducto']);
		$descproducto = limpiar($detalle[$i]['descproducto']);
if (limpiar($detalle[$i]['fechaelaboracion']=="")) { $fechaelaboracion = "0000-00-00";  } else { $fechaelaboracion = limpiar(date("Y-m-d",strtotime($detalle[$i]['fechaelaboracion']))); }
if (limpiar($detalle[$i]['fechaexpiracion']=="")) { $fechaexpiracion = "0000-00-00";  } else { $fechaexpiracion = limpiar(date("Y-m-d",strtotime($detalle[$i]['fechaexpiracion']))); }
		$codproveedor = limpiar($_POST['codproveedor']);
		$lote = limpiar($detalle[$i]['lote']);
		$codproducto = limpiar($detalle[$i]['txtCodigo']);
		$stmt->execute();

		############### REGISTRAMOS LOS DATOS DE PRODUCTOS EN KARDEX ###################
        $query = "INSERT INTO kardex_productos values (null, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?); ";
		$stmt = $this->dbh->prepare($query);
		$stmt->bindParam(1, $codcompra);
		$stmt->bindParam(2, $codproveedor);
		$stmt->bindParam(3, $codproducto);
		$stmt->bindParam(4, $movimiento);
		$stmt->bindParam(5, $entradas);
		$stmt->bindParam(6, $salidas);
		$stmt->bindParam(7, $devolucion);
		$stmt->bindParam(8, $stockactual);
		$stmt->bindParam(9, $ivaproducto);
		$stmt->bindParam(10, $descproducto);
		$stmt->bindParam(11, $precio);
		$stmt->bindParam(12, $documento);
		$stmt->bindParam(13, $fechakardex);		

		$codcompra = limpiar($_POST['codcompra']);
		$codproveedor = limpiar($_POST["codproveedor"]);
		$codproducto = limpiar($detalle[$i]['txtCodigo']);
		$movimiento = limpiar("ENTRADAS");
		$entradas= limpiar(number_format($detalle[$i]['cantidad'], 2, '.', ''));
		$salidas = limpiar("0");
		$devolucion = limpiar("0");
		$stockactual = limpiar(number_format($detalle[$i]['cantidad']+$existenciaproductobd, 2, '.', ''));
		$precio = limpiar($detalle[$i]["precio"]);
		$ivaproducto = limpiar($detalle[$i]['ivaproducto']);
		$descproducto = limpiar($detalle[$i]['descproducto']);
		$documento = limpiar("COMPRA");
		$fechakardex = limpiar(date("Y-m-d"));
		$stmt->execute();


	} else {

		############### VERIFICO LA EXISTENCIA DEL INSUMO EN ALMACEN ################
		$sql = "SELECT cantingrediente FROM ingredientes WHERE codingrediente = '".limpiar($detalle[$i]['txtCodigo'])."'";
		foreach ($this->dbh->query($sql) as $row)
		{
			$this->p[] = $row;
		}
		$existenciaingredientebd = $row['cantingrediente'];

		############# ACTUALIZAMOS LA EXISTENCIA DE INGREDIENTES COMPRADOS ###############
		$sql = "UPDATE ingredientes set "
		      ." preciocompra = ?, "
			  ." precioventa = ?, "
			  ." cantingrediente = ?, "
			  ." ivaingrediente = ?, "
			  ." descingrediente = ?, "
			  ." fechaexpiracion = ?, "
			  ." codproveedor = ?, "
			  ." lote = ? "
			  ." WHERE "
			  ." codingrediente = ?;
			   ";
		$stmt = $this->dbh->prepare($sql);
		$stmt->bindParam(1, $preciocompra);
		$stmt->bindParam(2, $precioventa);
		$stmt->bindParam(3, $cantingrediente);
		$stmt->bindParam(4, $ivaingrediente);
		$stmt->bindParam(5, $descingrediente);
		$stmt->bindParam(6, $fechaexpiracion);
		$stmt->bindParam(7, $codproveedor);
		$stmt->bindParam(8, $lote);
		$stmt->bindParam(9, $codingrediente);
		
		$preciocompra = limpiar($detalle[$i]['precio']);
		$precioventa = limpiar($detalle[$i]['precio2']);
		$cantingrediente = limpiar(number_format($detalle[$i]['cantidad']+$existenciaingredientebd, 2, '.', ''));
		$ivaingrediente = limpiar($detalle[$i]['ivaproducto']);
		$descingrediente = limpiar($detalle[$i]['descproducto']);
if (limpiar($detalle[$i]['fechaexpiracion']=="")) { $fechaexpiracion = "0000-00-00";  } else { $fechaexpiracion = limpiar(date("Y-m-d",strtotime($detalle[$i]['fechaexpiracion']))); }
		$codproveedor = limpiar($_POST['codproveedor']);
		$lote = limpiar($detalle[$i]['lote']);
		$codingrediente = limpiar($detalle[$i]['txtCodigo']);
		$stmt->execute();

		############### REGISTRAMOS LOS DATOS DE INSUMOS EN KARDEX ###################
        $query = "INSERT INTO kardex_ingredientes values (null, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?); ";
		$stmt = $this->dbh->prepare($query);
		$stmt->bindParam(1, $codcompra);
		$stmt->bindParam(2, $codproveedor);
		$stmt->bindParam(3, $codingrediente);
		$stmt->bindParam(4, $movimiento);
		$stmt->bindParam(5, $entradas);
		$stmt->bindParam(6, $salidas);
		$stmt->bindParam(7, $devolucion);
		$stmt->bindParam(8, $stockactual);
		$stmt->bindParam(9, $ivaingrediente);
		$stmt->bindParam(10, $descingrediente);
		$stmt->bindParam(11, $precio);
		$stmt->bindParam(12, $documento);
		$stmt->bindParam(13, $fechakardex);		

		$codcompra = limpiar($_POST['codcompra']);
		$codproveedor = limpiar($_POST["codproveedor"]);
		$codingrediente = limpiar($detalle[$i]['txtCodigo']);
		$movimiento = limpiar("ENTRADAS");
		$entradas= limpiar(number_format($detalle[$i]['cantidad'], 2, '.', ''));
		$salidas = limpiar("0");
		$devolucion = limpiar("0");
		$stockactual = limpiar(number_format($detalle[$i]['cantidad']+$existenciaingredientebd, 2, '.', ''));
		$precio = limpiar($detalle[$i]["precio"]);
		$ivaingrediente = limpiar($detalle[$i]['ivaproducto']);
		$descingrediente = limpiar($detalle[$i]['descproducto']);
		$documento = limpiar("COMPRA");
		$fechakardex = limpiar(date("Y-m-d"));
		$stmt->execute();

		}

      }
		####################### DESTRUYO LA VARIABLE DE SESSION #####################
	    unset($_SESSION["CarritoCompra"]);
        $this->dbh->commit();

		
echo "<span class='fa fa-check-square-o'></span> LA COMPRA DE PRODUCTOS E INGREDIENTES HA SIDO REGISTRADA EXITOSAMENTE <a href='reportepdf?codcompra=".encrypt($codcompra)."&tipo=".encrypt("FACTURACOMPRA")."' class='on-default' data-placement='left' data-toggle='tooltip' data-original-title='Imprimir Documento' target='_black' rel='noopener noreferrer'><font color='black'><strong>IMPRIMIR REPORTE</strong></font color></a></div>";
	exit;
		}
		else
		{
			echo "4";
			exit;
		}
	}
}
############################ FUNCION REGISTRAR COMPRAS ##########################

######################### FUNCION LISTAR COMPRAS ################################
public function ListarCompras()
{
	self::SetNames();
	$sql = "SELECT 
	compras.codcompra, 
	compras.codproveedor, 
	compras.subtotalivasic, 
	compras.subtotalivanoc, 
	compras.ivac, 
	compras.totalivac, 
	compras.descuentoc, 
	compras.totaldescuentoc, 
	compras.totalpagoc, 
	compras.statuscompra, 
	compras.fechavencecredito, 
	compras.fechapagado,
	compras.observaciones,
	compras.fecharecepcion, 
	compras.fechaemision,
	proveedores.documproveedor, 
	proveedores.documproveedor, 
	proveedores.cuitproveedor, 
	proveedores.nomproveedor, 
	documentos.documento,
	SUM(detallecompras.cantcompra) AS articulos 
	FROM (compras LEFT JOIN detallecompras ON detallecompras.codcompra = compras.codcompra) 
	LEFT JOIN proveedores ON compras.codproveedor = proveedores.codproveedor 
	LEFT JOIN documentos ON proveedores.documproveedor = documentos.coddocumento
	LEFT JOIN usuarios ON compras.codigo = usuarios.codigo WHERE compras.statuscompra = 'PAGADA' GROUP BY detallecompras.codcompra";
	foreach ($this->dbh->query($sql) as $row)
	{
		$this->p[] = $row;
	}
	return $this->p;
	$this->dbh=null;
}
################################## FUNCION LISTAR COMPRAS ############################

########################### FUNCION LISTAR CUENTAS POR PAGAR #######################
public function ListarCuentasxPagar()
{
	self::SetNames();
	$sql = "SELECT 
	compras.codcompra, 
	compras.codproveedor, 
	compras.subtotalivasic, 
	compras.subtotalivanoc, 
	compras.ivac, 
	compras.totalivac, 
	compras.descuentoc, 
	compras.totaldescuentoc, 
	compras.totalpagoc, 
	compras.statuscompra, 
	compras.fechavencecredito, 
	compras.fechapagado,
	compras.observaciones,
	compras.fecharecepcion, 
	compras.fechaemision,
	proveedores.documproveedor, 
	proveedores.documproveedor, 
	proveedores.cuitproveedor, 
	proveedores.nomproveedor, 
	documentos.documento,
	SUM(detallecompras.cantcompra) AS articulos 
	FROM (compras LEFT JOIN detallecompras ON detallecompras.codcompra = compras.codcompra) 
	LEFT JOIN proveedores ON compras.codproveedor = proveedores.codproveedor 
	LEFT JOIN documentos ON proveedores.documproveedor = documentos.coddocumento
	LEFT JOIN usuarios ON compras.codigo = usuarios.codigo WHERE compras.statuscompra = 'PENDIENTE' GROUP BY detallecompras.codcompra";
	foreach ($this->dbh->query($sql) as $row)
	{
		$this->p[] = $row;
	}
	return $this->p;
	$this->dbh=null;
}
######################### FUNCION LISTAR CUENTAS POR PAGAR ############################

############################ FUNCION PARA PAGAR COMPRAS ############################
public function PagarCompras()
	{
		if ($_SESSION['acceso'] == "administrador" || $_SESSION["acceso"]=="secretaria") {
		
		self::SetNames();
		$sql = " UPDATE compras SET"
			  ." statuscompra = ?, "
			  ." fechapagado = ? "
			  ." WHERE "
			  ." codcompra = ?;
			   ";
		$stmt = $this->dbh->prepare($sql);
		$stmt->bindParam(1, $statuscompra);
		$stmt->bindParam(2, $fechapagado);
		$stmt->bindParam(3, $codcompra);

		$statuscompra = limpiar("PAGADA");
		$fechapagado = limpiar(date("Y-m-d"));
		$codcompra = limpiar(decrypt($_GET["codcompra"]));
		$stmt->execute();
	
		echo "1";
		exit;
		   
		   }else {
		   
			echo "2";
			exit;
		  }
			
	}
########################## FUNCION PARA PAGAR COMPRAS ###############################

############################ FUNCION ID COMPRAS #################################
	public function ComprasPorId()
	{
		self::SetNames();
		$sql = "SELECT 
		compras.idcompra, 
		compras.codcompra,
		compras.codproveedor, 
		compras.subtotalivasic,
		compras.subtotalivanoc, 
		compras.ivac,
		compras.totalivac, 
		compras.descuentoc,
		compras.totaldescuentoc, 
		compras.totalpagoc, 
		compras.tipocompra,
		compras.formacompra,
		compras.fechavencecredito,
	    compras.fechapagado,
	    compras.observaciones,
		compras.statuscompra,
		compras.fechaemision,
		compras.fecharecepcion,
		compras.codigo,
		proveedores.documproveedor,
		proveedores.cuitproveedor, 
		proveedores.nomproveedor, 
		proveedores.tlfproveedor, 
		proveedores.id_provincia, 
		proveedores.id_departamento, 
		proveedores.direcproveedor, 
		proveedores.emailproveedor,
		proveedores.vendedor,
		proveedores.tlfvendedor,
	    documentos.documento, 
		mediospagos.mediopago,
		usuarios.dni, 
		usuarios.nombres,
		provincias.provincia,
		departamentos.departamento
		FROM (compras INNER JOIN proveedores ON compras.codproveedor = proveedores.codproveedor) 
		LEFT JOIN documentos ON proveedores.documproveedor = documentos.coddocumento
		LEFT JOIN provincias ON proveedores.id_provincia = provincias.id_provincia 
		LEFT JOIN departamentos ON proveedores.id_departamento = departamentos.id_departamento
		LEFT JOIN mediospagos ON compras.formacompra = mediospagos.codmediopago
		LEFT JOIN usuarios ON compras.codigo = usuarios.codigo
		WHERE compras.codcompra = ?";
		$stmt = $this->dbh->prepare($sql);
		$stmt->execute(array(decrypt($_GET["codcompra"])));
		$num = $stmt->rowCount();
		if($num==0)
		{
			echo "";
		}
		else
		{
			if($row = $stmt->fetch(PDO::FETCH_ASSOC))
			{
				$this->p[] = $row;
			}
			return $this->p;
			$this->dbh=null;
		}
	}
############################ FUNCION ID COMPRAS #################################
	
############################ FUNCION VER DETALLES COMPRAS ############################
public function VerDetallesCompras()
	{
		self::SetNames();
		$sql = "SELECT
		detallecompras.coddetallecompra,
		detallecompras.codcompra,
		detallecompras.tipoentrada,
		detallecompras.codproducto,
		detallecompras.producto,
		detallecompras.codcategoria,
		detallecompras.preciocomprac,
		detallecompras.precioventac,
		detallecompras.cantcompra,
		detallecompras.ivaproductoc,
		detallecompras.descproductoc,
		detallecompras.descfactura,
		detallecompras.valortotal, 
		detallecompras.totaldescuentoc,
		detallecompras.valorneto,
		detallecompras.lotec,
		detallecompras.fechaelaboracionc,
		detallecompras.fechaexpiracionc,
		categorias.nomcategoria,
		medidas.nommedida
		FROM detallecompras 
		LEFT JOIN categorias ON detallecompras.codcategoria = categorias.codcategoria 
		LEFT JOIN medidas ON detallecompras.codcategoria = medidas.codmedida 
		WHERE detallecompras.codcompra = ?";
		$stmt = $this->dbh->prepare($sql);
		$stmt->execute(array(decrypt($_GET["codcompra"])));
		$num = $stmt->rowCount();
		
			while($row = $stmt->fetch(PDO::FETCH_ASSOC))
			{
				$this->p[]=$row;
			}
			return $this->p;
			$this->dbh=null;
		}
############################ FUNCION VER DETALLES COMPRAS ##############################

############################## FUNCION ACTUALIZAR COMPRAS #############################
	public function ActualizarCompras()
	{
		self::SetNames();
		if(empty($_POST["codcompra"]) or empty($_POST["fechaemision"]) or empty($_POST["fecharecepcion"]) or empty($_POST["codproveedor"]))
		{
			echo "1";
			exit;
		}

		if (limpiar(isset($_POST['fechavencecredito']))) {  

		$fechaactual = date("Y-m-d");
		$fechavence = date("Y-m-d",strtotime($_POST['fechavencecredito']));
		
	     if (strtotime($fechavence) < strtotime($fechaactual)) {
	  
	     echo "2";
		 exit;
	  
	         }
        }

		for($i=0;$i<count($_POST['coddetallecompra']);$i++){  //recorro el array
	        if (!empty($_POST['coddetallecompra'][$i])) {

		       if($_POST['cantcompra'][$i]==0){

			      echo "3";
			      exit();

		        }
	        }
        }

        $this->dbh->beginTransaction();

        for($i=0;$i<count($_POST['coddetallecompra']);$i++){  //recorro el array
	         if (!empty($_POST['coddetallecompra'][$i])) {

	$sql = "SELECT cantcompra FROM detallecompras WHERE coddetallecompra = '".limpiar($_POST['coddetallecompra'][$i])."' AND codcompra = '".limpiar($_POST["codcompra"])."'";
		foreach ($this->dbh->query($sql) as $row)
		{
			$this->p[] = $row;
		}
		
		$cantidadbd = $row['cantcompra'];

		if($cantidadbd != $_POST['cantcompra'][$i]){

			$query = "UPDATE detallecompras set"
			." cantcompra = ?, "
			." valortotal = ?, "
			." totaldescuentoc = ?, "
			." valorneto = ? "
			." WHERE "
			." coddetallecompra = ? AND codcompra = ?;
			";
			$stmt = $this->dbh->prepare($query);
			$stmt->bindParam(1, $cantcompra);
			$stmt->bindParam(2, $valortotal);
			$stmt->bindParam(3, $totaldescuento);
			$stmt->bindParam(4, $valorneto);
			$stmt->bindParam(5, $coddetallecompra);
			$stmt->bindParam(6, $codcompra);

			$cantcompra = limpiar(number_format($_POST['cantcompra'][$i], 2, '.', ''));
			$preciocompra = limpiar($_POST['preciocompra'][$i]);
			$precioventa = limpiar($_POST['precioventa'][$i]);
			$ivaproducto = limpiar($_POST['ivaproducto'][$i]);
			$descuento = limpiar($_POST['descfactura'][$i]/100);
			$valortotal = number_format($_POST['preciocompra'][$i] * $_POST['cantcompra'][$i], 2, '.', '');
			$totaldescuento = number_format($valortotal * $descuento, 2, '.', '');
			$valorneto = number_format($valortotal - $totaldescuento, 2, '.', '');
			$coddetallecompra = limpiar($_POST['coddetallecompra'][$i]);
			$codcompra = limpiar($_POST["codcompra"]);
			$stmt->execute();


	if(limpiar($_POST['tipoentrada'][$i])=="PRODUCTO"){

			$sql = "SELECT existencia FROM productos WHERE codproducto = '".limpiar($_POST['codproducto'][$i])."'";
		    foreach ($this->dbh->query($sql) as $row)
		    {
			$this->p[] = $row;
		    }
		    $existenciaproductobd = $row['existencia'];
		    $cantcompra = $_POST["cantcompra"][$i];
		    $cantidadcomprabd = $_POST["cantidadcomprabd"][$i];
		    $totalcompra = $cantcompra-$cantidadcomprabd;

		    ############ ACTUALIZAMOS EXISTENCIA DEL PRODUCTO EN ALMACEN ################
		    $sql2 = " UPDATE productos set "
		    ." existencia = ? "
		    ." WHERE "
		    ." codproducto = '".limpiar($_POST["codproducto"][$i])."';
		    ";
		    $stmt = $this->dbh->prepare($sql2);
		    $stmt->bindParam(1, $existencia);
		    $existencia = limpiar(number_format($existenciaproductobd+$totalcompra, 2, '.', ''));
		    $stmt->execute();

		    ############## ACTUALIZAMOS LOS DATOS DEL PRODUCTO EN KARDEX ###################
		    $sql3 = " UPDATE kardex set "
		    ." entradas = ?, "
		    ." stockactual = ? "
		    ." WHERE "
		    ." codproceso = '".limpiar($_POST["codcompra"])."' and codproducto = '".limpiar($_POST["codproducto"][$i])."';
		    ";
		    $stmt = $this->dbh->prepare($sql3);
		    $stmt->bindParam(1, $entradas);
		    $stmt->bindParam(2, $existencia);

		    $entradas = limpiar(number_format($_POST["cantcompra"][$i], 2, '.', ''));
		    $existencia = limpiar(number_format($existenciaproductobd+$totalcompra, 2, '.', ''));
		    $stmt->execute();

		} else {

		    ############### VERIFICO LA EXISTENCIA DEL INGREDIENTE EN ALMACEN ################	
			$sql = "SELECT cantingrediente FROM ingredientes WHERE codingrediente = '".limpiar($_POST['codproducto'][$i])."'";
			foreach ($this->dbh->query($sql) as $row)
			{
				$this->p[] = $row;
			}
			$existenciaingredientebd = $row['cantingrediente'];
			$cantcompra = $_POST["cantcompra"][$i];
			$cantidadcomprabd = $_POST["cantidadcomprabd"][$i];
			$totalcompra = $cantcompra-$cantidadcomprabd;

		    ############ ACTUALIZAMOS EXISTENCIA DEL INGREDIENTE EN ALMACEN ################
			$sql2 = " UPDATE ingredientes set "
			." cantingrediente = ? "
			." WHERE "
			." codingrediente = '".limpiar($_POST["codproducto"][$i])."';
			";
			$stmt = $this->dbh->prepare($sql2);
			$stmt->bindParam(1, $cantingrediente);
			$cantingrediente = limpiar(number_format($existenciaingredientebd+$totalcompra, 2, '.', ''));
			$stmt->execute();

		############## ACTUALIZAMOS LOS DATOS DEL INSUMOS EN KARDEX ###################
			$sql3 = " UPDATE kardex_ingredientes set "
			." entradas = ?, "
			." stockactual = ? "
			." WHERE "
			." codproceso = '".limpiar($_POST["codcompra"])."' and codingrediente = '".limpiar($_POST["codproducto"][$i])."';
			";
			$stmt = $this->dbh->prepare($sql3);
			$stmt->bindParam(1, $entradas);
			$stmt->bindParam(2, $cantingrediente);

			$entradas = limpiar(number_format($_POST["cantcompra"][$i], 2, '.', ''));
			$cantingrediente = limpiar(number_format($existenciaingredientebd+$totalcompra, 2, '.', ''));
			$stmt->execute();

		}


			} else {

               echo "";

		       }
	        }
        }

        $this->dbh->commit();

            ############ SUMO LOS IMPORTE DE PRODUCTOS CON IVA ##############
			$sql3 = "SELECT SUM(valorneto) AS valorneto FROM detallecompras WHERE codcompra = '".limpiar($_POST["codcompra"])."' AND ivaproductoc = 'SI'";
			foreach ($this->dbh->query($sql3) as $row3)
			{
				$this->p[] = $row3;
			}
			$subtotalivasic = ($row3['valorneto']== "" ? "0.00" : $row3['valorneto']);

		    ############ SUMO LOS IMPORTE DE PRODUCTOS SIN IVA ##############
			$sql4 = "SELECT SUM(valorneto) AS valorneto FROM detallecompras WHERE codcompra = '".limpiar($_POST["codcompra"])."' AND ivaproductoc = 'NO'";
			foreach ($this->dbh->query($sql4) as $row4)
			{
				$this->p[] = $row4;
			}
			$subtotalivanoc = ($row4['valorneto']== "" ? "0.00" : $row4['valorneto']);

           ############ ACTUALIZO LOS TOTALES EN LA COTIZACION ##############
			$sql = " UPDATE compras SET "
			." codproveedor = ?, "
			." subtotalivasic = ?, "
			." subtotalivanoc = ?, "
			." totalivac = ?, "
			." descuentoc = ?, "
			." totaldescuentoc = ?, "
			." totalpagoc = ?, "
			." tipocompra = ?, "
			." formacompra = ?, "
			." fechavencecredito = ?, "
			." fechaemision = ?, "
			." fecharecepcion = ? "
			." WHERE "
			." codcompra = ?;
			";
			$stmt = $this->dbh->prepare($sql);
			$stmt->bindParam(1, $codproveedor);
			$stmt->bindParam(2, $subtotalivasic);
			$stmt->bindParam(3, $subtotalivanoc);
			$stmt->bindParam(4, $totalivac);
			$stmt->bindParam(5, $descuentoc);
			$stmt->bindParam(6, $totaldescuentoc);
			$stmt->bindParam(7, $totalpagoc);
			$stmt->bindParam(8, $tipocompra);
			$stmt->bindParam(9, $formacompra);
			$stmt->bindParam(10, $fechavencecredito);
			$stmt->bindParam(11, $fechaemision);
			$stmt->bindParam(12, $fecharecepcion);
			$stmt->bindParam(13, $codcompra);

			$codproveedor = limpiar($_POST["codproveedor"]);
			$ivac = $_POST["iva"]/100;
			$totalivac = number_format($subtotalivasic*$ivac, 2, '.', '');
			$descuentoc = limpiar($_POST["descuento"]);
		    $txtDescuento = $_POST["descuento"]/100;
		    $total = number_format($subtotalivasic+$subtotalivanoc+$totalivac, 2, '.', '');
		    $totaldescuentoc = number_format($total*$txtDescuento, 2, '.', '');
		    $totalpagoc = number_format($total-$totaldescuentoc, 2, '.', '');

			$tipocompra = limpiar($_POST["tipocompra"]);
			if (limpiar($_POST["tipocompra"]=="CONTADO")) { $formacompra = limpiar($_POST["formacompra"]); } else { $formacompra = "CREDITO"; }
			if (limpiar($_POST["tipocompra"]=="CREDITO")) { $fechavencecredito = limpiar(date("Y-m-d",strtotime($_POST['fechavencecredito']))); } else { $fechavencecredito = "0000-00-00"; }
			if (limpiar($_POST["tipocompra"]=="CONTADO")) { $statuscompra = limpiar("PAGADA"); } else { $statuscompra = "PENDIENTE"; }
			
			$fechaemision = limpiar(date("Y-m-d",strtotime($_POST['fechaemision'])));
			$fecharecepcion = limpiar(date("Y-m-d",strtotime($_POST['fecharecepcion'])));
			$codcompra = limpiar($_POST["codcompra"]);
			$stmt->execute();


echo "<span class='fa fa-check-square-o'></span> LA COMPRA DE PRODUCTOS E INGREDIENTES HA SIDO ACTUALIZADA EXITOSAMENTE <a href='reportepdf?codcompra=".encrypt($codcompra)."&tipo=".encrypt("FACTURACOMPRA")."' class='on-default' data-placement='left' data-toggle='tooltip' data-original-title='Imprimir Documento' target='_black' rel='noopener noreferrer'><font color='black'><strong>IMPRIMIR REPORTE</strong></font color></a></div>";
	exit;
}
############################# FUNCION ACTUALIZAR COMPRAS #########################

########################## FUNCION ELIMINAR DETALLES COMPRAS ########################
	public function EliminarDetallesCompras()
	{
	    self::SetNames();
		if ($_SESSION["acceso"]=="administrador") {

		$sql = "SELECT * FROM detallecompras WHERE codcompra = ?";
		$stmt = $this->dbh->prepare($sql);
		$stmt->execute(array(decrypt($_GET["codcompra"])));
		$num = $stmt->rowCount();
		if($num > 1)
		{

			$sql = "SELECT tipoentrada, codproducto, cantcompra, preciocomprac, ivaproductoc, descproductoc FROM detallecompras WHERE coddetallecompra = ?";
			$stmt = $this->dbh->prepare($sql);
			$stmt->execute(array(decrypt($_GET["coddetallecompra"])));
			$num = $stmt->rowCount();

			if($row = $stmt->fetch(PDO::FETCH_ASSOC))
			{
				$p[] = $row;
			}
			$tipoentrada = $row['tipoentrada'];
			$codproducto = $row['codproducto'];
			$cantidadbd = $row['cantcompra'];
			$preciocomprabd = $row['preciocomprac'];
			$ivaproductobd = $row['ivaproductoc'];
			$descproductobd = $row['descproductoc'];

 	if(limpiar($tipoentrada)=="PRODUCTO"){

			$sql2 = "SELECT existencia FROM productos WHERE codproducto = ?";
			$stmt = $this->dbh->prepare($sql2);
			$stmt->execute(array($codproducto));
			$num = $stmt->rowCount();

			if($row = $stmt->fetch(PDO::FETCH_ASSOC))
			{
				$p[] = $row;
			}
			$existenciaproductobd = $row['existencia'];

			############# ACTUALIZAMOS LA EXISTENCIA DE PRODUCTO EN ALMACEN #############
			$sql = "UPDATE productos SET "
			." existencia = ? "
			." WHERE "
			." codproducto = ?;
			";
			$stmt = $this->dbh->prepare($sql);
			$stmt->bindParam(1, $existencia);
			$stmt->bindParam(2, $codproducto);

			$existencia = limpiar(number_format($existenciaproductobd-$cantidadbd, 2, '.', ''));
			$stmt->execute();


		    ########## REGISTRAMOS LOS DATOS DEL PRODUCTO ELIMINADO EN KARDEX ##########
			$query = "INSERT INTO kardex_productos values (null, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?); ";
			$stmt = $this->dbh->prepare($query);
			$stmt->bindParam(1, $codcompra);
			$stmt->bindParam(2, $codproveedor);
			$stmt->bindParam(3, $codproducto);
			$stmt->bindParam(4, $movimiento);
			$stmt->bindParam(5, $entradas);
			$stmt->bindParam(6, $salidas);
			$stmt->bindParam(7, $devolucion);
			$stmt->bindParam(8, $stockactual);
			$stmt->bindParam(9, $ivaproducto);
			$stmt->bindParam(10, $descproducto);
			$stmt->bindParam(11, $precio);
			$stmt->bindParam(12, $documento);
			$stmt->bindParam(13, $fechakardex);		

			$codcompra = limpiar(decrypt($_GET["codcompra"]));
		    $codproveedor = limpiar(decrypt($_GET["codproveedor"]));
			$movimiento = limpiar("DEVOLUCION");
			$entradas= limpiar("0");
			$salidas = limpiar("0");
			$devolucion = limpiar(number_format($cantidadbd, 2, '.', ''));
			$stockactual = limpiar(number_format($existenciaproductobd-$cantidadbd, 2, '.', ''));
			$precio = limpiar($preciocomprabd);
			$ivaproducto = limpiar($ivaproductobd);
			$descproducto = limpiar($descproductobd);
			$documento = limpiar("DEVOLUCION COMPRA");
			$fechakardex = limpiar(date("Y-m-d"));
			$stmt->execute();

		} else {

			############### VERIFICO LA EXISTENCIA DEL INGREDIENTE EN ALMACEN ################
			$sql2 = "SELECT cantingrediente FROM ingredientes WHERE codingrediente = ?";
			$stmt = $this->dbh->prepare($sql2);
			$stmt->execute(array($codproducto));
			$num = $stmt->rowCount();

			if($row = $stmt->fetch(PDO::FETCH_ASSOC))
			{
				$p[] = $row;
			}
			$existenciaingredientebd = $row['cantingrediente'];

			############# ACTUALIZAMOS LA EXISTENCIA DE INGREDIENTE EN ALMACEN #############
			$sql = "UPDATE ingredientes SET "
			." cantingrediente = ? "
			." WHERE "
			." codingrediente = ?;
			";
			$stmt = $this->dbh->prepare($sql);
			$stmt->bindParam(1, $cantingrediente);
			$stmt->bindParam(2, $codproducto);

			$cantingrediente = limpiar(number_format($existenciaingredientebd-$cantidadbd, 2, '.', ''));
			$stmt->execute();

		    ########## REGISTRAMOS LOS DATOS DEL INGREDIENTE ELIMINADO EN KARDEX ##########
			$query = "INSERT INTO kardex_ingredientes values (null, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?); ";
			$stmt = $this->dbh->prepare($query);
			$stmt->bindParam(1, $codcompra);
			$stmt->bindParam(2, $codproveedor);
			$stmt->bindParam(3, $codproducto);
			$stmt->bindParam(4, $movimiento);
			$stmt->bindParam(5, $entradas);
			$stmt->bindParam(6, $salidas);
			$stmt->bindParam(7, $devolucion);
			$stmt->bindParam(8, $stockactual);
			$stmt->bindParam(9, $ivaingrediente);
			$stmt->bindParam(10, $descingrediente);
			$stmt->bindParam(11, $precio);
			$stmt->bindParam(12, $documento);
			$stmt->bindParam(13, $fechakardex);		

			$codcompra = limpiar(decrypt($_GET["codcompra"]));
		    $codproveedor = limpiar(decrypt($_GET["codproveedor"]));
			$movimiento = limpiar("DEVOLUCION");
			$entradas= limpiar("0");
			$salidas = limpiar("0");
			$devolucion = limpiar(number_format($cantidadbd, 2, '.', ''));
			$stockactual = limpiar(number_format($existenciaingredientebd-$cantidadbd, 2, '.', ''));
			$ivaingrediente = limpiar($ivaproductobd);
			$descingrediente = limpiar($descproductobd);
			$precio = limpiar($preciocomprabd);
			$documento = limpiar("DEVOLUCION COMPRA");
			$fechakardex = limpiar(date("Y-m-d"));
			$stmt->execute();

		}


			########## ELIMINAMOS EL PRODUCTO EN DETALLES DE COMPRAS ###########
			$sql = "DELETE FROM detallecompras WHERE coddetallecompra = ?";
			$stmt = $this->dbh->prepare($sql);
			$stmt->bindParam(1,$coddetallecompra);
			$coddetallecompra = decrypt($_GET["coddetallecompra"]);
			$stmt->execute();


		    ############ CONSULTO LOS TOTALES DE COMPRAS ##############
		    $sql2 = "SELECT ivac, descuentoc FROM compras WHERE codcompra = ?";
		    $stmt = $this->dbh->prepare($sql2);
		    $stmt->execute(array(decrypt($_GET["codcompra"])));
		    $num = $stmt->rowCount();

			if($row = $stmt->fetch(PDO::FETCH_ASSOC))
			{
				$paea[] = $row;
			}
			$iva = $paea[0]["ivac"]/100;
		    $descuento = $paea[0]["descuentoc"]/100;

             ############ SUMO LOS IMPORTE DE PRODUCTOS CON IVA ##############
			$sql3 = "SELECT SUM(valorneto) AS valorneto FROM detallecompras WHERE codcompra = '".limpiar(decrypt($_GET["codcompra"]))."' AND ivaproductoc = 'SI'";
			foreach ($this->dbh->query($sql3) as $row3)
			{
				$this->p[] = $row3;
			}
			$subtotalivasic = ($row3['valorneto']== "" ? "0.00" : $row3['valorneto']);

		    ############ SUMO LOS IMPORTE DE PRODUCTOS SIN IVA ##############
			$sql4 = "SELECT SUM(valorneto) AS valorneto FROM detallecompras WHERE codcompra = '".limpiar(decrypt($_GET["codcompra"]))."' AND ivaproductoc = 'NO'";
			foreach ($this->dbh->query($sql4) as $row4)
			{
				$this->p[] = $row4;
			}
			$subtotalivanoc = ($row4['valorneto']== "" ? "0.00" : $row4['valorneto']);


            ############ ACTUALIZO LOS TOTALES EN LA COMPRAS ##############
			$sql = " UPDATE compras SET "
			." subtotalivasic = ?, "
			." subtotalivanoc = ?, "
			." totalivac = ?, "
			." totaldescuentoc = ?, "
			." totalpagoc = ? "
			." WHERE "
			." codcompra = ?;
			";
			$stmt = $this->dbh->prepare($sql);
			$stmt->bindParam(1, $subtotalivasic);
			$stmt->bindParam(2, $subtotalivanoc);
			$stmt->bindParam(3, $totalivac);
			$stmt->bindParam(4, $totaldescuentoc);
			$stmt->bindParam(5, $totalpagoc);
			$stmt->bindParam(6, $codcompra);

			$totalivac= number_format($subtotalivasic*$iva, 2, '.', '');
		    $total= number_format($subtotalivasic+$subtotalivanoc+$totalivac, 2, '.', '');
		    $totaldescuentoc = number_format($total*$descuento, 2, '.', '');
		    $totalpagoc = number_format($total-$totaldescuentoc, 2, '.', '');
			$codcompra = limpiar(decrypt($_GET["codcompra"]));
			$stmt->execute();

			echo "1";
			exit;

		} else {
		   
			echo "2";
			exit;
		  } 
			
		} else {
		
		echo "3";
		exit;
	 }	
}
###################### FUNCION ELIMINAR DETALLES COMPRAS #######################

####################### FUNCION ELIMINAR COMPRAS #################################
	public function EliminarCompras()
	{
	self::SetNames();
		if ($_SESSION["acceso"]=="administrador") {

	$sql = "SELECT tipoentrada, codproducto, cantcompra, preciocomprac, ivaproductoc, descproductoc FROM detallecompras WHERE codcompra = '".limpiar(decrypt($_GET["codcompra"]))."'";

		$array=array();

	foreach ($this->dbh->query($sql) as $row)
		{
				$this->p[] = $row;

			$tipoentrada = $row['tipoentrada'];
			$codproducto = $row['codproducto'];
			$cantidadbd = $row['cantcompra'];
			$preciocomprabd = $row['preciocomprac'];
			$ivaproductobd = $row['ivaproductoc'];
			$descproductobd = $row['descproductoc'];

	if(limpiar($tipoentrada)=="PRODUCTO"){

			$sql2 = "SELECT existencia FROM productos WHERE codproducto = ?";
			$stmt = $this->dbh->prepare($sql2);
			$stmt->execute( array($codproducto));
			$num = $stmt->rowCount();

			if($row = $stmt->fetch(PDO::FETCH_ASSOC))
			{
				$p[] = $row;
			}
			$existenciaproductobd = $row['existencia'];

			########### ACTUALIZAMOS LA EXISTENCIA DE PRODUCTO EN ALMACEN #############
			$sql = "UPDATE productos SET "
			." existencia = ? "
			." WHERE "
			." codproducto = ?;
			";
			$stmt = $this->dbh->prepare($sql);
			$stmt->bindParam(1, $existencia);
			$stmt->bindParam(2, $codproducto);

			$existencia = limpiar(number_format($existenciaproductobd-$cantidadbd, 2, '.', ''));
			$stmt->execute();

		    ########## REGISTRAMOS LOS DATOS DEL PRODUCTO ELIMINADO EN KARDEX ##########
			$query = "INSERT INTO kardex_productos values (null, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?); ";
			$stmt = $this->dbh->prepare($query);
			$stmt->bindParam(1, $codcompra);
			$stmt->bindParam(2, $codproveedor);
			$stmt->bindParam(3, $codproducto);
			$stmt->bindParam(4, $movimiento);
			$stmt->bindParam(5, $entradas);
			$stmt->bindParam(6, $salidas);
			$stmt->bindParam(7, $devolucion);
			$stmt->bindParam(8, $stockactual);
			$stmt->bindParam(9, $ivaproducto);
			$stmt->bindParam(10, $descproducto);
			$stmt->bindParam(11, $precio);
			$stmt->bindParam(12, $documento);
			$stmt->bindParam(13, $fechakardex);		

			$codcompra = limpiar(decrypt($_GET["codcompra"]));
		    $codproveedor = limpiar(decrypt($_GET["codproveedor"]));
			$movimiento = limpiar("DEVOLUCION");
			$entradas= limpiar("0");
			$salidas = limpiar("0");
			$devolucion = limpiar($cantidadbd);
			$stockactual = limpiar($existenciaproductobd-$cantidadbd);
			$precio = limpiar($preciocomprabd);
			$ivaproducto = limpiar($ivaproductobd);
			$descproducto = limpiar($descproductobd);
			$documento = limpiar("DEVOLUCION COMPRA");
			$fechakardex = limpiar(date("Y-m-d"));
			$stmt->execute();

		} else {

			############### VERIFICO LA EXISTENCIA DEL INGREDIENTE EN ALMACEN ################
			$sql2 = "SELECT cantingrediente FROM ingredientes WHERE codingrediente = ?";
			$stmt = $this->dbh->prepare($sql2);
			$stmt->execute(array($codproducto));
			$num = $stmt->rowCount();

			if($row = $stmt->fetch(PDO::FETCH_ASSOC))
			{
				$p[] = $row;
			}
			$existenciaingredientebd = $row['cantingrediente'];

			############# ACTUALIZAMOS LA EXISTENCIA DE INGREDIENTE EN ALMACEN #############
			$sql = "UPDATE ingredientes SET "
			." cantingrediente = ? "
			." WHERE "
			." codingrediente = ?;
			";
			$stmt = $this->dbh->prepare($sql);
			$stmt->bindParam(1, $existencia);
			$stmt->bindParam(2, $codproducto);

			$existencia = limpiar(number_format($existenciaingredientebd-$cantidadbd, 2, '.', ''));
			$stmt->execute();

		    ########## REGISTRAMOS LOS DATOS DEL INGREDIENTE ELIMINADO EN KARDEX ##########
			$query = "INSERT INTO kardex_ingredientes values (null, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?); ";
			$stmt = $this->dbh->prepare($query);
			$stmt->bindParam(1, $codcompra);
			$stmt->bindParam(2, $codproveedor);
			$stmt->bindParam(3, $codproducto);
			$stmt->bindParam(4, $movimiento);
			$stmt->bindParam(5, $entradas);
			$stmt->bindParam(6, $salidas);
			$stmt->bindParam(7, $devolucion);
			$stmt->bindParam(8, $stockactual);
			$stmt->bindParam(9, $ivaingrediente);
			$stmt->bindParam(10, $descingrediente);
			$stmt->bindParam(11, $precio);
			$stmt->bindParam(12, $documento);
			$stmt->bindParam(13, $fechakardex);		

			$codcompra = limpiar(decrypt($_GET["codcompra"]));
		    $codproveedor = limpiar(decrypt($_GET["codproveedor"]));
			$movimiento = limpiar("DEVOLUCION");
			$entradas= limpiar("0");
			$salidas = limpiar("0");
			$devolucion = limpiar($cantidadbd);
			$stockactual = limpiar(number_format($existenciaingredientebd-$cantidadbd, 2, '.', ''));
			$ivaingrediente = limpiar($ivaproductobd);
			$descingrediente = limpiar($descproductobd);
			$precio = limpiar($preciocomprabd);
			$documento = limpiar("DEVOLUCION COMPRA");
			$fechakardex = limpiar(date("Y-m-d"));
			$stmt->execute();

		}

	}

			$sql = "DELETE FROM compras WHERE codcompra = ?";
			$stmt = $this->dbh->prepare($sql);
			$stmt->bindParam(1,$codcompra);
			$codcompra = decrypt($_GET["codcompra"]);
			$stmt->execute();

			$sql = "DELETE FROM detallecompras WHERE codcompra = ?";
			$stmt = $this->dbh->prepare($sql);
			$stmt->bindParam(1,$codcompra);
			$codcompra = decrypt($_GET["codcompra"]);
			$stmt->execute();

			echo "1";
			exit;

		} else {

			echo "2";
			exit;
		}
	}
######################### FUNCION ELIMINAR COMPRAS #################################

##################### FUNCION BUSQUEDA COMPRAS POR PROVEEDORES ###################
	public function BuscarComprasxProveedor() 
	{
		self::SetNames();
		$sql = "SELECT 
		compras.codcompra,
		compras.codproveedor, 
		compras.subtotalivasic,
		compras.subtotalivanoc, 
		compras.ivac,
		compras.totalivac, 
		compras.descuentoc,
		compras.totaldescuentoc, 
		compras.totalpagoc, 
		compras.tipocompra,
		compras.formacompra,
		compras.fechavencecredito,
	    compras.fechapagado,
	    compras.observaciones,
		compras.statuscompra,
		compras.fechaemision,
		compras.fecharecepcion,
		proveedores.documproveedor,
		proveedores.cuitproveedor, 
		proveedores.nomproveedor, 
		proveedores.tlfproveedor, 
		proveedores.id_provincia, 
		proveedores.id_departamento, 
		proveedores.direcproveedor, 
		proveedores.emailproveedor,
		proveedores.vendedor,
		proveedores.tlfvendedor,
	    documentos.documento,
		provincias.provincia,
		departamentos.departamento, 
		SUM(detallecompras.cantcompra) as articulos 
		FROM (compras LEFT JOIN detallecompras ON compras.codcompra=detallecompras.codcompra) 
		INNER JOIN proveedores ON compras.codproveedor = proveedores.codproveedor 
		LEFT JOIN documentos ON proveedores.documproveedor = documentos.coddocumento
		LEFT JOIN provincias ON proveedores.id_provincia = provincias.id_provincia 
		LEFT JOIN departamentos ON proveedores.id_departamento = departamentos.id_departamento 
		WHERE compras.codproveedor = ? GROUP BY detallecompras.codcompra";
		$stmt = $this->dbh->prepare($sql);
		$stmt->execute(array(decrypt($_GET["codproveedor"])));
		$num = $stmt->rowCount();
		if($num==0)
		{
	echo "<div class='alert alert-danger'>";
	echo "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>";
	echo "<center><span class='fa fa-info-circle'></span> NO SE ENCONTRARON COMPRAS DE PRODUCTOS PARA EL PROVEEDOR SELECCIONADO</center>";
	echo "</div>";		
	exit;
		}
		else
		{
			while($row = $stmt->fetch(PDO::FETCH_ASSOC))
			{
				$this->p[]=$row;
			}
			return $this->p;
			$this->dbh=null;
		}
	}
################### FUNCION BUSQUEDA COMPRAS POR PROVEEDORES ###################

###################### FUNCION BUSQUEDA COMPRAS POR FECHAS ###########################
	public function BuscarComprasxFechas() 
	{
		self::SetNames();
		$sql ="SELECT 
		compras.codcompra,
		compras.codproveedor, 
		compras.subtotalivasic,
		compras.subtotalivanoc, 
		compras.ivac,
		compras.totalivac, 
		compras.descuentoc,
		compras.totaldescuentoc, 
		compras.totalpagoc, 
		compras.tipocompra,
		compras.formacompra,
		compras.fechavencecredito,
	    compras.fechapagado,
	    compras.observaciones,
		compras.statuscompra,
		compras.fechaemision,
		compras.fecharecepcion,
		proveedores.documproveedor,
		proveedores.cuitproveedor, 
		proveedores.nomproveedor, 
		proveedores.tlfproveedor, 
		proveedores.id_provincia, 
		proveedores.id_departamento, 
		proveedores.direcproveedor, 
		proveedores.emailproveedor,
		proveedores.vendedor,
		proveedores.tlfvendedor,
	    documentos.documento,
		provincias.provincia,
		departamentos.departamento, 
		SUM(detallecompras.cantcompra) as articulos 
		FROM (compras LEFT JOIN detallecompras ON compras.codcompra=detallecompras.codcompra) 
		INNER JOIN proveedores ON compras.codproveedor = proveedores.codproveedor 
		LEFT JOIN documentos ON proveedores.documproveedor = documentos.coddocumento
		LEFT JOIN provincias ON proveedores.id_provincia = provincias.id_provincia 
		LEFT JOIN departamentos ON proveedores.id_departamento = departamentos.id_departamento
		 WHERE DATE_FORMAT(compras.fecharecepcion,'%Y-%m-%d') >= ? AND DATE_FORMAT(compras.fecharecepcion,'%Y-%m-%d') <= ? GROUP BY detallecompras.codcompra";
		$stmt = $this->dbh->prepare($sql);
		$stmt->bindValue(1, trim(date("Y-m-d",strtotime($_GET['desde']))));
		$stmt->bindValue(2, trim(date("Y-m-d",strtotime($_GET['hasta']))));
		$stmt->execute();
		$num = $stmt->rowCount();
		if($num==0)
		{
	echo "<div class='alert alert-danger'>";
	echo "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>";
	echo "<center><span class='fa fa-info-circle'></span> NO SE ENCONTRARON COMPRAS DE PRODUCTO PARA EL RANGO DE FECHA INGRESADO</center>";
	echo "</div>";		
	exit;
		}
		else
		{
			while($row = $stmt->fetch(PDO::FETCH_ASSOC))
			{
				$this->p[]=$row;
			}
			return $this->p;
			$this->dbh=null;
		}
	}
###################### FUNCION BUSQUEDA COMPRAS POR FECHAS ###########################

############################# FIN DE CLASE COMPRAS ###################################































################################ CLASE CAJAS DE VENTAS ################################

######################### FUNCION REGISTRAR CAJAS DE VENTAS #######################
public function RegistrarCajas()
{
	self::SetNames();
	if(empty($_POST["nrocaja"]) or empty($_POST["nomcaja"]) or empty($_POST["codigo"]))
	{
		echo "1";
		exit;
	}
		
		$sql = "SELECT nrocaja FROM cajas WHERE nrocaja = ?";
		$stmt = $this->dbh->prepare($sql);
		$stmt->execute(array($_POST["nrocaja"]));
		$num = $stmt->rowCount();
		if($num > 0)
		{
		    echo "2";
		    exit;

		} else {
			
		$sql = "SELECT nomcaja FROM cajas WHERE nomcaja = ?";
		$stmt = $this->dbh->prepare($sql);
		$stmt->execute(array($_POST["nomcaja"]));
		$num = $stmt->rowCount();
		if($num > 0)
		{
			echo "3";
			exit;

		} else {
			
		$sql = "SELECT codigo FROM cajas WHERE codigo = ?";
		$stmt = $this->dbh->prepare($sql);
		$stmt->execute(array($_POST["codigo"]));
		$num = $stmt->rowCount();
		if($num == 0)
		{
			$query = "INSERT INTO cajas values (null, ?, ?, ?); ";
			$stmt = $this->dbh->prepare($query);
			$stmt->bindParam(1, $nrocaja);
			$stmt->bindParam(2, $nomcaja);
			$stmt->bindParam(3, $codigo);

			$nrocaja = limpiar($_POST["nrocaja"]);
			$nomcaja = limpiar($_POST["nomcaja"]);
			$codigo = limpiar($_POST["codigo"]);
			$stmt->execute();

			echo "<span class='fa fa-check-square-o'></span> LA CAJA PARA VENTA HA SIDO REGISTRADA EXITOSAMENTE";
			exit;

			} else {

			echo "4";
			exit;
		    }
		}
	}
}
######################### FUNCION REGISTRAR CAJAS DE VENTAS #########################

######################### FUNCION LISTAR CAJAS DE VENTAS ################################
public function ListarCajas()
{
	self::SetNames();
	
	if($_SESSION['acceso'] == "administrador") {

        $sql = "SELECT * FROM cajas INNER JOIN usuarios ON cajas.codigo = usuarios.codigo";
			foreach ($this->dbh->query($sql) as $row)
			{
				$this->p[] = $row;
			}
			return $this->p;
			$this->dbh=null;

	} else {

        $sql = "SELECT * FROM cajas INNER JOIN usuarios ON cajas.codigo = usuarios.codigo WHERE cajas.codigo = '".limpiar($_SESSION["codigo"])."'";
			foreach ($this->dbh->query($sql) as $row)
			{
				$this->p[] = $row;
			}
			return $this->p;
			$this->dbh=null;
	}
}
######################### FUNCION LISTAR CAJAS DE VENTAS ################################

######################### FUNCION LISTAR CAJAS ABIERTAS ##########################
public function ListarCajasAbiertas()
{
	self::SetNames();
	if ($_SESSION['acceso'] == "administrador") {

	$sql = "SELECT * FROM cajas INNER JOIN arqueocaja ON cajas.codcaja = arqueocaja.codcaja LEFT JOIN usuarios ON cajas.codigo = usuarios.codigo WHERE arqueocaja.statusarqueo = '1'";
		foreach ($this->dbh->query($sql) as $row)
	    {
		$this->p[] = $row;
	    }
	    return $this->p;
	    $this->dbh=null;

	} else {

        $sql = "SELECT * FROM cajas INNER JOIN arqueocaja ON cajas.codcaja = arqueocaja.codcaja LEFT JOIN usuarios ON cajas.codigo = usuarios.codigo WHERE cajas.codigo = '".limpiar($_SESSION["codigo"])."'";
			foreach ($this->dbh->query($sql) as $row)
	    {
		$this->p[] = $row;
	    }
	    return $this->p;
	    $this->dbh=null;
    }
}
######################### FUNCION LISTAR CAJAS ABIERTAS ##########################

############################ FUNCION ID CAJAS DE VENTAS #################################
public function CajasPorId()
{
	self::SetNames();
	$sql = "SELECT * FROM cajas LEFT JOIN usuarios ON usuarios.codigo = cajas.codigo WHERE cajas.codcaja = ?";
	$stmt = $this->dbh->prepare($sql);
	$stmt->execute(array(decrypt($_GET["codcaja"])));
	$num = $stmt->rowCount();
	if($num==0)
	{
		echo "";
	}
	else
	{
		if($row = $stmt->fetch(PDO::FETCH_ASSOC))
		{
			$this->p[] = $row;
		}
		return $this->p;
		$this->dbh=null;
	}
}
############################ FUNCION ID CAJAS DE VENTAS #################################

#################### FUNCION ACTUALIZAR CAJAS DE VENTAS ############################
public function ActualizarCajas()
{
	self::SetNames();
	if(empty($_POST["codcaja"]) or empty($_POST["nrocaja"]) or empty($_POST["nomcaja"]) or empty($_POST["codigo"]))
	{
		echo "1";
		exit;
	}
		$sql = "SELECT nrocaja FROM cajas WHERE codcaja != ? AND nrocaja = ?";
		$stmt = $this->dbh->prepare($sql);
		$stmt->execute(array($_POST["codcaja"],$_POST["nrocaja"]));
		$num = $stmt->rowCount();
		if($num > 0)
		{
		    echo "2";
		    exit;

		} else {
			
		$sql = "SELECT nomcaja FROM cajas WHERE codcaja != ? AND nomcaja = ?";
		$stmt = $this->dbh->prepare($sql);
		$stmt->execute(array($_POST["codcaja"],$_POST["nomcaja"]));
		$num = $stmt->rowCount();
		if($num > 0)
		{
			echo "3";
			exit;

		} else {
			
		$sql = "SELECT codigo FROM cajas WHERE codcaja != ? AND codigo = ?";
		$stmt = $this->dbh->prepare($sql);
		$stmt->execute(array($_POST["codcaja"],$_POST["codigo"]));
		$num = $stmt->rowCount();
		if($num == 0)
		{
			$sql = "UPDATE cajas set "
			." nrocaja = ?, "
			." nomcaja = ?, "
			." codigo = ? "
			." where "
			." codcaja = ?;
			";
			$stmt = $this->dbh->prepare($sql);
			$stmt->bindParam(1, $nrocaja);
			$stmt->bindParam(2, $nomcaja);
			$stmt->bindParam(3, $codigo);
			$stmt->bindParam(4, $codcaja);

			$nrocaja = limpiar($_POST["nrocaja"]);
			$nomcaja = limpiar($_POST["nomcaja"]);
			$codigo = limpiar($_POST["codigo"]);
			$codcaja = limpiar($_POST["codcaja"]);
			$stmt->execute();

			echo "<span class='fa fa-check-square-o'></span> LA CAJA PARA VENTA HA SIDO ACTUALIZADA EXITOSAMENTE";
			exit;

			} else {

			echo "4";
			exit;
		    }
		}
	}
}
#################### FUNCION ACTUALIZAR CAJAS DE VENTAS ###########################

####################### FUNCION ELIMINAR CAJAS DE VENTAS ########################
public function EliminarCajas()
{
	self::SetNames();
		if ($_SESSION['acceso'] == "administrador") {

		$sql = "SELECT codcaja FROM ventas WHERE codcaja = ?";
		$stmt = $this->dbh->prepare($sql);
		$stmt->execute(array(decrypt($_GET["codcaja"])));
		$num = $stmt->rowCount();
		if($num == 0)
		{

			$sql = "DELETE FROM cajas WHERE codcaja = ?";
			$stmt = $this->dbh->prepare($sql);
			$stmt->bindParam(1,$codcaja);
			$codcaja = decrypt($_GET["codcaja"]);
			$stmt->execute();

			echo "1";
			exit;

		} else {
		   
			echo "2";
			exit;
		  } 
			
		} else {
		
		echo "3";
		exit;
	 }	
}
####################### FUNCION ELIMINAR CAJAS DE VENTAS #######################

############################ FIN DE CLASE CAJAS DE VENTAS ##############################


























########################## CLASE ARQUEOS DE CAJA ###################################

########################## FUNCION PARA REGISTRAR ARQUEO DE CAJA ####################
public function RegistrarArqueoCaja()
{
	self::SetNames();
	if(empty($_POST["codcaja"]) or empty($_POST["montoinicial"]) or empty($_POST["fecharegistro"]))
	{
		echo "1";
		exit;
	}

	$sql = "SELECT codcaja FROM arqueocaja WHERE codcaja = ? AND statusarqueo = '1'";
	$stmt = $this->dbh->prepare($sql);
	$stmt->execute(array($_POST["codcaja"]));
	$num = $stmt->rowCount();
	if($num == 0)
	{
		$query = "INSERT INTO arqueocaja values (null, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?); ";
		$stmt = $this->dbh->prepare($query);
		$stmt->bindParam(1, $codcaja);
		$stmt->bindParam(2, $montoinicial);
		$stmt->bindParam(3, $ingresos);
		$stmt->bindParam(4, $egresos);
		$stmt->bindParam(5, $creditos);
		$stmt->bindParam(6, $abonos);
		$stmt->bindParam(7, $propinas);
		$stmt->bindParam(8, $dineroefectivo);
		$stmt->bindParam(9, $diferencia);
		$stmt->bindParam(10, $comentarios);
		$stmt->bindParam(11, $fechaapertura);
		$stmt->bindParam(12, $fechacierre);
		$stmt->bindParam(13, $statusarqueo);

		$codcaja = limpiar($_POST["codcaja"]);
		$montoinicial = limpiar($_POST["montoinicial"]);
	if (limpiar(isset($_POST['ingresos']))) { $ingresos = limpiar($_POST['ingresos']); } else { $ingresos = limpiar('0.00'); }
	if (limpiar(isset($_POST['egresos']))) { $egresos = limpiar($_POST['egresos']); } else { $egresos = limpiar('0.00'); }
	if (limpiar(isset($_POST['creditos']))) { $creditos = limpiar($_POST['creditos']); } else { $creditos = limpiar('0.00'); }
	if (limpiar(isset($_POST['abonos']))) { $abonos = limpiar($_POST['abonos']); } else { $abonos = limpiar('0.00'); }
	if (limpiar(isset($_POST['propinas']))) { $propinas = limpiar($_POST['propinas']); } else { $propinas = limpiar('0.00'); }
	if (limpiar(isset($_POST['dineroefectivo']))) { $dineroefectivo = limpiar($_POST['dineroefectivo']); } else { $dineroefectivo = limpiar('0.00'); }
	if (limpiar(isset($_POST['diferencia']))) { $diferencia = limpiar($_POST['diferencia']); } else { $diferencia = limpiar('0.00'); }
	if (limpiar(isset($_POST['comentarios']))) { $comentarios = limpiar($_POST['comentarios']); } else { $comentarios = limpiar('NINGUNO'); }
		$fechaapertura = limpiar(date("Y-m-d h:i:s",strtotime($_POST['fecharegistro'])));
		$fechacierre = limpiar(date("0000-00-00 00:00:00"));
		$statusarqueo = limpiar("1");
		$stmt->execute();

		echo "<span class='fa fa-check-square-o'></span> EL ARQUEO DE CAJA HA SIDO REALIZADO EXITOSAMENTE";
		exit;

			} else {

			echo "2";
			exit;
	    }
}
######################## FUNCION PARA REGISTRAR ARQUEO DE CAJA #######################

######################## FUNCION PARA LISTAR ARQUEO DE CAJA ########################
public function ListarArqueoCaja()
{
	self::SetNames();
	
	if($_SESSION['acceso'] == "administrador") {

        $sql = "SELECT * FROM arqueocaja INNER JOIN cajas ON arqueocaja.codcaja = cajas.codcaja LEFT JOIN usuarios ON cajas.codigo = usuarios.codigo";
			foreach ($this->dbh->query($sql) as $row)
			{
				$this->p[] = $row;
			}
			return $this->p;
			$this->dbh=null;

	} else {

        $sql = "SELECT * FROM arqueocaja INNER JOIN cajas ON arqueocaja.codcaja = cajas.codcaja LEFT JOIN usuarios ON cajas.codigo = usuarios.codigo WHERE cajas.codigo = '".limpiar($_SESSION["codigo"])."'";
			foreach ($this->dbh->query($sql) as $row)
			{
				$this->p[] = $row;
			}
			return $this->p;
			$this->dbh=null;
	}
}
######################## FUNCION PARA LISTAR ARQUEO DE CAJA #########################

########################## FUNCION ID ARQUEO DE CAJA #############################
public function ArqueoCajaPorId()
{
	self::SetNames();
	$sql = "SELECT * FROM arqueocaja INNER JOIN cajas ON arqueocaja.codcaja = cajas.codcaja LEFT JOIN usuarios ON cajas.codigo = usuarios.codigo WHERE arqueocaja.codarqueo = ?";
	$stmt = $this->dbh->prepare($sql);
	$stmt->execute(array(decrypt($_GET["codarqueo"])));
	$num = $stmt->rowCount();
	if($num==0)
	{
		echo "";
	}
	else
	{
		if($row = $stmt->fetch(PDO::FETCH_ASSOC))
			{
				$this->p[] = $row;
			}
			return $this->p;
			$this->dbh=null;
		}
	}
########################## FUNCION ID ARQUEO DE CAJA #############################

##################### FUNCION VERIFICA ARQUEO DE CAJA POR USUARIO #######################
public function ArqueoCajaPorUsuario()
{
	self::SetNames();
	$sql = "SELECT * FROM arqueocaja INNER JOIN cajas ON arqueocaja.codcaja = cajas.codcaja INNER JOIN usuarios ON cajas.codigo = usuarios.codigo WHERE usuarios.codigo = ? AND arqueocaja.statusarqueo = '1'";
	$stmt = $this->dbh->prepare($sql);
	$stmt->execute(array(limpiar($_SESSION["codigo"])));
	$num = $stmt->rowCount();
	if($num==0)
	{
		echo "";
	}
	else
	{
		if($row = $stmt->fetch(PDO::FETCH_ASSOC))
			{
				$this->p[] = $row;
			}
			return $this->p;
			$this->dbh=null;
		}
	}
###################### FUNCION VERIFICA ARQUEO DE CAJA POR USUARIO ###################

######################### FUNCION PARA CERRAR ARQUEO DE CAJA #########################
public function CerrarArqueoCaja()
{
	self::SetNames();
	if(empty($_POST["codarqueo"]) or empty($_POST["dineroefectivo"]))
	{
		echo "1";
		exit;
	}

	if($_POST["dineroefectivo"] != 0.00 || $_POST["dineroefectivo"] != 0){

		$sql = "UPDATE arqueocaja SET "
		." dineroefectivo = ?, "
		." diferencia = ?, "
		." comentarios = ?, "
		." fechacierre = ?, "
		." statusarqueo = ? "
		." WHERE "
		." codarqueo = ?;
		";
		$stmt = $this->dbh->prepare($sql);
		$stmt->bindParam(1, $dineroefectivo);
		$stmt->bindParam(2, $diferencia);
		$stmt->bindParam(3, $comentarios);
		$stmt->bindParam(4, $fechacierre);
		$stmt->bindParam(5, $statusarqueo);
		$stmt->bindParam(6, $codarqueo);

		$dineroefectivo = limpiar($_POST["dineroefectivo"]);
		$diferencia = limpiar($_POST["diferencia"]);
		$comentarios = limpiar($_POST['comentarios']);
		$fechacierre = limpiar(date("Y-m-d h:i:s",strtotime($_POST['fecharegistro2'])));
		$statusarqueo = limpiar("0");
		$codarqueo = limpiar($_POST["codarqueo"]);
		$stmt->execute();

		echo "<span class='fa fa-check-square-o'></span> EL CIERRE DE CAJA FUE REALIZADO EXITOSAMENTE";
		exit;

			} else {

			echo "2";
			exit;
	    }
}
######################### FUNCION PARA CERRAR ARQUEO DE CAJA ######################

###################### FUNCION BUSCAR ARQUEOS DE CAJA POR FECHAS ######################
public function BuscarArqueosxFechas() 
	       {
		self::SetNames();		
$sql = "SELECT * FROM arqueocaja INNER JOIN cajas ON arqueocaja.codcaja = cajas.codcaja LEFT JOIN usuarios ON cajas.codigo = usuarios.codigo WHERE arqueocaja.codcaja = ? AND DATE_FORMAT(arqueocaja.fechaapertura,'%Y-%m-%d') >= ? AND DATE_FORMAT(arqueocaja.fechaapertura,'%Y-%m-%d') <= ?";
		$stmt = $this->dbh->prepare($sql);
		$stmt->bindValue(1, trim(decrypt($_GET['codcaja'])));
		$stmt->bindValue(2, trim(date("Y-m-d",strtotime($_GET['desde']))));
		$stmt->bindValue(3, trim(date("Y-m-d",strtotime($_GET['hasta']))));
		$stmt->execute();
		$num = $stmt->rowCount();
		if($num==0)
		{
		echo "<center><div class='alert alert-danger'>";
		echo "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>";
		echo "<span class='fa fa-info-circle'></span> NO SE ENCONTRARON ARQUEOS DE CAJAS PARA LAS FECHAS SELECCIONADAS</div></center>";
		exit;
		}
		else
		{
			while($row = $stmt->fetch(PDO::FETCH_ASSOC))
			{
				$this->p[]=$row;
			}
			return $this->p;
			$this->dbh=null;
	    }
	
}
######################## FUNCION BUSCAR ARQUEOS DE CAJA POR FECHAS ####################

############################# FIN DE CLASE ARQUEOS DE CAJA ###########################


























############################ CLASE MOVIMIENTOS EN CAJAS ##############################

###################### FUNCION PARA REGISTRAR MOVIMIENTO EN CAJA #######################
public function RegistrarMovimientos()
{
	self::SetNames();
	if(empty($_POST["tipomovimiento"]) or empty($_POST["montomovimiento"]) or empty($_POST["codmediopago"]) or empty($_POST["codcaja"]))
	{
		echo "1";
		exit;
	}

	$sql = " SELECT * FROM arqueocaja WHERE codcaja = '".limpiar($_POST["codcaja"])."' AND statusarqueo = '1'";
	$stmt = $this->dbh->prepare($sql);
	$stmt->execute();
	$num = $stmt->rowCount();
	if($num==0)
	{
		echo "2";
		exit;

	}  
	else if($_POST["montomovimiento"]>0)
	{

	#################### AGREGAMOS EL INGRESO A ARQUEO EN CAJA ####################
	$sql = "SELECT montoinicial, ingresos, egresos FROM arqueocaja WHERE codcaja = '".limpiar($_POST["codcaja"])."'";
	foreach ($this->dbh->query($sql) as $row)
	{
		$this->p[] = $row;
	}
	$inicial = $row['montoinicial'];
	$ingreso = $row['ingresos'];
	$egresos = $row['egresos'];
	$total = $inicial+$ingreso-$egresos;

	if($_POST["tipomovimiento"]=="INGRESO"){

		$sql = " UPDATE arqueocaja SET "
		." ingresos = ? "
		." WHERE "
		." codcaja = ? AND statusarqueo = '1';
		";
		$stmt = $this->dbh->prepare($sql);
		$stmt->bindParam(1, $ingresos);
		$stmt->bindParam(2, $codcaja);

		$ingresos = number_format($_POST["montomovimiento"]+$ingreso, 2, '.', '');
		$codcaja = limpiar($_POST["codcaja"]);
		$stmt->execute();

		$query = "INSERT INTO movimientoscajas values (null, ?, ?, ?, ?, ?, ?); ";
		$stmt = $this->dbh->prepare($query);
		$stmt->bindParam(1, $codcaja);
		$stmt->bindParam(2, $tipomovimiento);
		$stmt->bindParam(3, $descripcionmovimiento);
		$stmt->bindParam(4, $montomovimiento);
		$stmt->bindParam(5, $codmediopago);
		$stmt->bindParam(6, $fechamovimiento);

		$codcaja = limpiar($_POST["codcaja"]);
		$tipomovimiento = limpiar($_POST["tipomovimiento"]);
		$descripcionmovimiento = limpiar($_POST["descripcionmovimiento"]);
		$montomovimiento = limpiar($_POST["montomovimiento"]);
		$codmediopago = limpiar($_POST["codmediopago"]);
		$fechamovimiento = limpiar(date("Y-m-d h:i:s",strtotime($_POST['fecharegistro'])));
		$stmt->execute();

	} else {

		if($_POST["montomovimiento"]>$total){

			echo "3";
			exit;

    } else {

		$sql = "UPDATE arqueocaja SET "
		." egresos = ? "
		." WHERE "
		." codcaja = ? AND statusarqueo = '1';
		";
		$stmt = $this->dbh->prepare($sql);
		$stmt->bindParam(1, $egresos);
		$stmt->bindParam(2, $codcaja);

		$egresos = number_format($_POST["montomovimiento"]+$egresos, 2, '.', '');
		$codcaja = limpiar($_POST["codcaja"]);
		$stmt->execute();

		$query = "INSERT INTO movimientoscajas values (null, ?, ?, ?, ?, ?, ?); ";
		$stmt = $this->dbh->prepare($query);
		$stmt->bindParam(1, $codcaja);
		$stmt->bindParam(2, $tipomovimiento);
		$stmt->bindParam(3, $descripcionmovimiento);
		$stmt->bindParam(4, $montomovimiento);
		$stmt->bindParam(5, $codmediopago);
		$stmt->bindParam(6, $fechamovimiento);

		$codcaja = limpiar($_POST["codcaja"]);
		$tipomovimiento = limpiar($_POST["tipomovimiento"]);
		$descripcionmovimiento = limpiar($_POST["descripcionmovimiento"]);
		$montomovimiento = limpiar($_POST["montomovimiento"]);
		$codmediopago = limpiar($_POST["codmediopago"]);
		$fechamovimiento = limpiar(date("Y-m-d h:i:s",strtotime($_POST['fecharegistro'])));
		$stmt->execute();

	      }
	}

		echo "<span class='fa fa-check-square-o'></span> EL MOVIMIENTO EN CAJA HA SIDO REGISTRADO EXITOSAMENTE";
		exit;

			} else {

			echo "4";
			exit;
	    }
}
##################### FUNCION PARA REGISTRAR MOVIMIENTO EN CAJA #######################

###################### FUNCION PARA LISTAR MOVIMIENTO EN CAJA #######################
public function ListarMovimientos()
{
	self::SetNames();
	
	if($_SESSION['acceso'] == "administrador") {

        $sql = " SELECT * FROM movimientoscajas INNER JOIN cajas ON movimientoscajas.codcaja = cajas.codcaja LEFT JOIN usuarios ON cajas.codigo = usuarios.codigo LEFT JOIN mediospagos ON mediospagos.codmediopago = movimientoscajas.codmediopago";
			foreach ($this->dbh->query($sql) as $row)
			{
				$this->p[] = $row;
			}
			return $this->p;
			$this->dbh=null;

	} else {

        $sql = " SELECT * FROM movimientoscajas INNER JOIN cajas ON movimientoscajas.codcaja = cajas.codcaja LEFT JOIN usuarios ON cajas.codigo = usuarios.codigo LEFT JOIN mediospagos ON mediospagos.codmediopago = movimientoscajas.codmediopago WHERE usuarios.codigo = '".limpiar($_SESSION["codigo"])."'";
			foreach ($this->dbh->query($sql) as $row)
			{
				$this->p[] = $row;
			}
			return $this->p;
			$this->dbh=null;
	}
}
###################### FUNCION PARA LISTAR MOVIMIENTO EN CAJA ######################

########################## FUNCION ID MOVIMIENTO EN CAJA #############################
public function MovimientosPorId()
{
	self::SetNames();
	$sql = " SELECT * from movimientoscajas LEFT JOIN cajas ON movimientoscajas.codcaja = cajas.codcaja LEFT JOIN mediospagos ON movimientoscajas.codmediopago = mediospagos.codmediopago LEFT JOIN usuarios ON cajas.codigo = usuarios.codigo WHERE movimientoscajas.codmovimiento = ?";
	$stmt = $this->dbh->prepare($sql);
	$stmt->execute(array(decrypt($_GET["codmovimiento"])));
	$num = $stmt->rowCount();
	if($num==0)
	{
		echo "";
	}
	else
	{
		if($row = $stmt->fetch(PDO::FETCH_ASSOC))
			{
				$this->p[] = $row;
			}
			return $this->p;
			$this->dbh=null;
		}
	}
########################## FUNCION ID MOVIMIENTO EN CAJA #############################

##################### FUNCION PARA ACTUALIZAR MOVIMIENTOS EN CAJA ##################
public function ActualizarMovimientos()
{
	self::SetNames();
if(empty($_POST["tipomovimiento"]) or empty($_POST["montomovimiento"]) or empty($_POST["codmediopago"]) or empty($_POST["codcaja"]))
	{
		echo "1";
		exit;
	}

	if($_POST["montomovimiento"]>0)
	{

	#################### AGREGAMOS EL INGRESO A ARQUEO EN CAJA ####################
	$sql = "SELECT montoinicial, ingresos, egresos FROM arqueocaja WHERE codcaja = '".limpiar($_POST["codcaja"])."' AND statusarqueo = '1'";
	foreach ($this->dbh->query($sql) as $row)
	{
		$this->p[] = $row;
	}
	$inicial = $row['montoinicial'];
	$ingreso = $row['ingresos'];
	$egreso = $row['egresos'];
	$total = $inicial+$ingreso-$egreso;
	$montomovimiento = limpiar($_POST["montomovimiento"]);
	$montomovimientodb = limpiar($_POST["montomovimientodb"]);
	$ingresobd = number_format($ingreso-$montomovimientodb, 2, '.', '');
	$totalmovimiento = number_format($montomovimiento-$montomovimientodb, 2, '.', '');

	if($_POST["tipomovimiento"]=="INGRESO"){

	$sql = "UPDATE arqueocaja SET "
		." ingresos = ? "
		." WHERE "
		." codcaja = ? AND statusarqueo = '1';
		";
		$stmt = $this->dbh->prepare($sql);
		$stmt->bindParam(1, $ingresos);
		$stmt->bindParam(2, $codcaja);

		$ingresos = number_format($montomovimiento+$ingresobd, 2, '.', '');
		$codcaja = limpiar($_POST["codcaja"]);
		$stmt->execute();

	    $sql = "UPDATE movimientoscajas SET"
		." codcaja = ?, "
		." tipomovimiento = ?, "
		." descripcionmovimiento = ?, "
		." montomovimiento = ?, "
		." codmediopago = ? "
		." WHERE "
		." codmovimiento = ?;
		";
		$stmt = $this->dbh->prepare($sql);
		$stmt->bindParam(1, $codcaja);
		$stmt->bindParam(2, $tipomovimiento);
		$stmt->bindParam(3, $descripcionmovimiento);
		$stmt->bindParam(4, $montomovimiento);
		$stmt->bindParam(5, $codmediopago);
		$stmt->bindParam(6, $codmovimiento);

		$codcaja = limpiar($_POST["codcaja"]);
		$tipomovimiento = limpiar($_POST["tipomovimiento"]);
		$descripcionmovimiento = limpiar($_POST["descripcionmovimiento"]);
		$montomovimiento = limpiar($_POST["montomovimiento"]);
		$codmediopago = limpiar($_POST["codmediopago"]);
		$codmovimiento = limpiar($_POST["codmovimiento"]);
		$stmt->execute();

	} else {

		   if($totalmovimiento>$total){
		
		echo "2";
		exit;

	         } else {

	$sql = "UPDATE arqueocaja SET"
		." egresos = ? "
		." WHERE "
		." codcaja = ? AND statusarqueo = '1';
		";
		$stmt = $this->dbh->prepare($sql);
		$stmt->bindParam(1, $egresos);
		$stmt->bindParam(2, $codcaja);

		$egresos = number_format($totalmovimiento+$egreso, 2, '.', '');
		$codcaja = limpiar($_POST["codcaja"]);
		$stmt->execute();

		$sql = "UPDATE movimientoscajas SET"
		." codcaja = ?, "
		." tipomovimiento = ?, "
		." descripcionmovimiento = ?, "
		." montomovimiento = ?, "
		." codmediopago = ? "
		." WHERE "
		." codmovimiento = ?;
		";
		$stmt = $this->dbh->prepare($sql);
		$stmt->bindParam(1, $codcaja);
		$stmt->bindParam(2, $tipomovimiento);
		$stmt->bindParam(3, $descripcionmovimiento);
		$stmt->bindParam(4, $montomovimiento);
		$stmt->bindParam(5, $codmediopago);
		$stmt->bindParam(6, $codmovimiento);

		$codcaja = limpiar($_POST["codcaja"]);
		$tipomovimiento = limpiar($_POST["tipomovimiento"]);
		$descripcionmovimiento = limpiar($_POST["descripcionmovimiento"]);
		$montomovimiento = limpiar($_POST["montomovimiento"]);
		$codmediopago = limpiar($_POST["codmediopago"]);
		$codmovimiento = limpiar($_POST["codmovimiento"]);
		$stmt->execute();

	        }
	}	
	
echo "<span class='fa fa-check-square-o'></span> EL MOVIMIENTO EN CAJA HA SIDO ACTUALIZADO EXITOSAMENTE";
exit;
	}
	else
	{
		echo "3";
		exit;
	}
}
##################### FUNCION PARA ACTUALIZAR MOVIMIENTOS EN CAJA ####################	

###################### FUNCION PARA ELIMINAR MOVIMIENTOS EN CAJA ######################
public function EliminarMovimiento()
{
	if($_SESSION['acceso'] == "administrador" || $_SESSION['acceso'] == "secretaria" || $_SESSION['acceso'] == "cajero") {

    #################### AGREGAMOS EL INGRESO A ARQUEO EN CAJA ####################
	$sql = "SELECT * FROM movimientoscajas WHERE codmovimiento = '".limpiar(decrypt($_GET["codmovimiento"]))."'";
	foreach ($this->dbh->query($sql) as $row)
	{
		$this->p[] = $row;
	}
	$tipomovimiento = $row['tipomovimiento'];
	$montomovimiento = $row['montomovimiento'];
	$codmediopago = $row['codmediopago'];
	$codcaja = $row['codcaja'];
	$descripcionmovimiento = $row['descripcionmovimiento'];
	$fechamovimiento = $row['fechamovimiento'];

	#################### AGREGAMOS EL INGRESO A ARQUEO EN CAJA ####################
	$sql2 = "SELECT montoinicial, ingresos, egresos FROM arqueocaja WHERE codcaja = '".limpiar($codcaja)."' AND statusarqueo = '1'";
	foreach ($this->dbh->query($sql2) as $row2)
	{
		$this->p[] = $row2;
	}
	$inicial = $row2['montoinicial'];
	$ingreso = $row2['ingresos'];
	$egreso = $row2['egresos'];

if($tipomovimiento=="INGRESO"){

		$sql = "UPDATE arqueocaja SET"
		." ingresos = ? "
		." WHERE "
		." codcaja = ? AND statusarqueo = '1';
		";
		$stmt = $this->dbh->prepare($sql);
		$stmt->bindParam(1, $ingresos);
		$stmt->bindParam(2, $codcaja);

		$entro = $montomovimiento;
		$ingresos = number_format($ingreso-$entro, 2, '.', '');
		$stmt->execute();

} else {

		$sql = "UPDATE arqueocaja SET "
		." egresos = ? "
		." WHERE "
		." codcaja = ? AND statusarqueo = '1';
		";
		$stmt = $this->dbh->prepare($sql);
		$stmt->bindParam(1, $egresos);
		$stmt->bindParam(2, $codcaja);

		$salio = $montomovimiento;
		$egresos = number_format($egreso-$salio, 2, '.', '');
		$stmt->execute();
       }

		$sql = "DELETE FROM movimientoscajas WHERE codmovimiento = ? ";
		$stmt = $this->dbh->prepare($sql);
		$stmt->bindParam(1,$codmovimiento);
		$codmovimiento = decrypt($_GET["codmovimiento"]);
		$stmt->execute();

		echo "1";
		exit;

	} else {

		echo "2";
		exit;
	} 
}
###################### FUNCION PARA ELIMINAR MOVIMIENTOS EN CAJAS  ####################

################## FUNCION BUSCAR MOVIMIENTOS DE CAJA POR FECHAS #######################
public function BuscarMovimientosxFechas() 
	       {
		self::SetNames();		
$sql = "SELECT * FROM movimientoscajas INNER JOIN cajas ON movimientoscajas.codcaja = cajas.codcaja LEFT JOIN usuarios ON cajas.codigo = usuarios.codigo LEFT JOIN mediospagos ON mediospagos.codmediopago = movimientoscajas.codmediopago WHERE movimientoscajas.codcaja = ? AND DATE_FORMAT(movimientoscajas.fechamovimiento,'%Y-%m-%d') >= ? AND DATE_FORMAT(movimientoscajas.fechamovimiento,'%Y-%m-%d') <= ?";
		$stmt = $this->dbh->prepare($sql);
		$stmt->bindValue(1, trim(decrypt($_GET['codcaja'])));
		$stmt->bindValue(2, trim(date("Y-m-d",strtotime($_GET['desde']))));
		$stmt->bindValue(3, trim(date("Y-m-d",strtotime($_GET['hasta']))));
		$stmt->execute();
		$num = $stmt->rowCount();
		if($num==0)
		{
		echo "<center><div class='alert alert-danger'>";
		echo "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>";
		echo "<span class='fa fa-info-circle'></span> NO SE ENCONTRARON MOVIMIENTOS DE CAJAS PARA LAS FECHAS SELECCIONADAS</div></center>";
		exit;
		}
		else
		{
			while($row = $stmt->fetch(PDO::FETCH_ASSOC))
			{
				$this->p[]=$row;
			}
			return $this->p;
			$this->dbh=null;
	    }
	
}
###################### FUNCION BUSCAR MOVIMIENTOS DE CAJA POR FECHAS ###################

######################### FIN DE CLASE MOVIMIENTOS EN CAJAS #############################


























































###################################### CLASE VENTAS ###################################

####################### FUNCION VERIFICAR PEDIDOS EN MESAS #######################
public function VerificaMesa()
{
	self::SetNames();
	$imp = new Login();
	$imp = $imp->ImpuestosPorId();
	$impuesto = $imp[0]['nomimpuesto'];
	$valor = $imp[0]['valorimpuesto'];

	$con = new Login();
	$con = $con->ConfiguracionPorId();
	$simbolo = "<strong>".$con[0]['simbolo']."</strong>";

$sql = "SELECT clientes.dnicliente, clientes.nomcliente, ventas.codpedido, ventas.codcaja, ventas.codcliente, documentos.documento, ventas.subtotalivasi, ventas.subtotalivano, ventas.iva, ventas.totaliva, ventas.descuento, ventas.totaldescuento, ventas.totalpago, ventas.totalpago2, ventas.codigo, ventas.observaciones, detallepedidos.coddetallepedido, detallepedidos.codpedido, detallepedidos.pedido, detallepedidos.codproducto, detallepedidos.producto, detallepedidos.cantventa, detallepedidos.ivaproducto, detallepedidos.descproducto, detallepedidos.valortotal, detallepedidos.totaldescuentov, detallepedidos.valorneto, salas.nomsala, mesas.codmesa, mesas.nommesa, usuarios.nombres 
    FROM mesas INNER JOIN ventas ON mesas.codmesa = ventas.codmesa 
    INNER JOIN detallepedidos ON detallepedidos.codpedido = ventas.codpedido
    INNER JOIN salas ON salas.codsala = mesas.codsala  
    LEFT JOIN clientes ON ventas.codcliente = clientes.codcliente 
    LEFT JOIN documentos ON clientes.documcliente = documentos.coddocumento 
    LEFT JOIN usuarios ON ventas.codigo = usuarios.codigo 
    WHERE mesas.codmesa = ? and mesas.statusmesa = 1 AND ventas.statuspago = 1";
		$stmt = $this->dbh->prepare($sql);
		$stmt->execute(array(decrypt($_GET["codmesa"])));
		$num = $stmt->rowCount();
		if($num==0)
		{
		?>

		<?php
        $mesa = new Login();
        $mesa = $mesa->MesasPorId();
        ?>

        <h4 class="text-danger"><strong><?php echo $mesa[0]['nomsala']; ?></strong></h4> 
        <h4 class="text-danger"><strong><?php echo $mesa[0]['nommesa']; ?></strong></h4>
        <input type="hidden" name="mesa" id="mesa" value="<?php echo encrypt($mesa[0]['codmesa']); ?>">
        <input type="hidden" name="codmesa" id="codmesa" value="<?php echo $mesa[0]['codmesa']; ?>">
        <input type="hidden" name="nombremesa" id="nombremesa" value="<?php echo $mesa[0]['nommesa']; ?>">
        <hr>
        <input type="hidden" name="codproducto" id="codproducto">
        <input type="hidden" name="producto" id="producto">
        <input type="hidden" name="codcategoria" id="codcategoria">
        <input type="hidden" name="categorias" id="categorias">
        <input type="hidden" name="precioventa" id="precioventa">
        <input type="hidden" name="preciocompra" id="preciocompra"> 
        <input type="hidden" name="precioconiva" id="precioconiva">
        <input type="hidden" name="ivaproducto" id="ivaproducto">
        <input type="hidden" name="descproducto" id="descproducto">
        <input type="hidden" name="cantidad" id="cantidad" value="1">
        <input type="hidden" name="existencia" id="existencia">

        <div class="row">
            <div class="col-md-10">
                <div class="form-group has-feedback">
                    <label class="control-label">Búsqueda de Clientes: </label>
                    <input type="hidden" name="codcliente" id="codcliente" <?php if (isset($reg[0]['codventa'])) { ?> value="<?php echo $reg[0]['codcliente'] == '' ? "0" : $reg[0]['codcliente']; ?>" <?php } else { ?> value="0" <?php } ?>>
                    <input type="text" class="form-control" name="busqueda" id="busqueda" onKeyUp="this.value=this.value.toUpperCase();" placeholder="Ingrese Criterio para la Búsqueda del Cliente" <?php if (isset($reg[0]['codventa'])) { ?> 
                        value="<?php echo $reg[0]['codcliente'] == '' ? "CONSUMIDOR FINAL" : $reg[0]['documento3'].": ".$reg[0]['dnicliente'].": ".$reg[0]['nomcliente']; ?>" <?php } ?> autocomplete="off"/>
                  <i class="fa fa-pencil form-control-feedback"></i>
                </div>
            </div>  

            <div class="col-md-2"> 
                <div class="form-group has-feedback"> 
                  <label class="control-label"></label><br>
                  <button type="button" class="btn btn-info waves-effect waves-light" data-placement="left" title="Nuevo Cliente" data-original-title="" data-href="#" data-toggle="modal" data-target="#myModalCliente" data-backdrop="static" data-keyboard="false"><i class="fa fa-user-plus"></i></button> 
                </div> 
            </div>    
        </div>

        <!--<div class="row">
            <div class="col-md-12"> 
                <div class="form-group has-feedback"> 
                  <label class="control-label">Realice la Búsqueda de Producto: </label>
                  <input type="text" class="form-control" name="busquedaproductov" id="busquedaproductov" onKeyUp="this.value=this.value.toUpperCase();" autocomplete="off" placeholder="Realice la Búsqueda por Código o Descripción de Producto">
                  <i class="fa fa-search form-control-feedback"></i> 
                </div> 
            </div> 
        </div>-->

        <div class="table-responsive m-t-10">
            <table id="carrito" class="table table-hover">
                <thead>
                    <tr class="text-center">
                        <th>Cantidad</th>
                        <th>Descripción</th>
                        <th>Precio</th>
                        <th>Importe</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="text-center" colspan=5><h4>NO HAY DETALLES AGREGADOS<h4></td>
                    </tr>
                </tbody>
            </table> 
        </div>

        <hr>

        <div class="table-responsive">
          <table id="carritototal" width="100%">
            <tr>
              <td><h5 class="text-left"><label>TOTAL A CONFIRMAR:</label></h5></td>
              <td><h5 class="text-right"> <?php echo $simbolo; ?><label id="lbltotal" name="lbltotal">0.00</label></h5></td>
              <input type="hidden" name="txtsubtotal" id="txtsubtotal" value="0.00"/>
              <input type="hidden" name="txtsubtotal2" id="txtsubtotal2" value="0.00"/>
              <input type="hidden" name="iva" id="iva" value="<?php echo $valor == '' ? "0.00" : $imp[0]['valorimpuesto']; ?>">
              <input type="hidden" name="txtIva" id="txtIva" value="0.00"/>
              <input type="hidden" name="descuento" id="descuento" value="<?php echo $con[0]['descuentoglobal'] ?>">
              <input type="hidden" name="txtDescuento" id="txtDescuento" value="0.00"/>
              <input type="hidden" name="txtTotal" id="txtTotal" value="0.00"/>
              <input type="hidden" name="txtTotalCompra" id="txtTotalCompra" value="0.00"/>
            </tr>
          </table>
        </div>

        <div class="row">
        	<div class="col-md-12"> 
        		<div class="form-group has-feedback2"> 
        			<label class="control-label">OBSERVACIONES DE PEDIDO: </label> 
        			<textarea class="form-control" type="text" name="observacionespedido" id="observacionespedido" onKeyUp="this.value=this.value.toUpperCase();" autocomplete="off" placeholder="Ingrese Observaciones de Pedido"></textarea>
        			<i class="fa fa-comment-o form-control-feedback2"></i> 
        		</div> 
        	</div>
        </div>

      <div class="pull-left">
<input id="boton" onClick="mostrar();" style="cursor: pointer;" class="btn btn-info waves-effect waves-light" value="Ver Mesas" type="button"><div id="remision" style="display: none;"></div>
      </div>

      <div class="text-right">
<button type="submit" name="btn-nuevo" id="btn-nuevo" class="btn btn-warning"><span class="fa fa-save"></span> Guardar</button>
<button type="button" id="vaciar" class="btn btn-dark"><span class="fa fa-trash-o"></span> Limpiar</button>
      </div>

		<?php  
		exit;
		} else {
		while($row = $stmt->fetch(PDO::FETCH_ASSOC))
		{
			$this->p[]=$row;
		}
		return $this->p;
		$this->dbh=null;
	}
}
####################### FUNCION VERIFICAR PEDIDOS EN MESAS #######################

############################ FUNCION REGISTRAR VENTAS ##############################
	public function NuevoPedido()
	{
		self::SetNames();
		if(empty($_SESSION["CarritoVenta"]) || $_POST["txtTotal"]=="0.00")
		{
			echo "1";
			exit;
			
		}

		############ VALIDO SI LA CANTIDAD ES MAYOR QUE LA EXISTENCIA ############
		$v = $_SESSION["CarritoVenta"];
		for($i=0;$i<count($v);$i++){

		    $sql = "SELECT existencia FROM productos WHERE codproducto = '".$v[$i]['txtCodigo']."'";
		    foreach ($this->dbh->query($sql) as $row)
		    {
			$this->p[] = $row;
		    }
		
		    $existenciadb = $row['existencia'];
		    $cantidad = $v[$i]['cantidad'];

            if ($cantidad > $existenciadb) 
            { 
		       echo "2";
		       exit;
	        }
		}

		##################### AGREGAMOS EL NUMERO DE PEDIDO A LA VENTA #######################
		$sql = "SELECT codpedido FROM ventas ORDER BY idventa DESC LIMIT 1";
		foreach ($this->dbh->query($sql) as $row){

			$id=$row["codpedido"];

		}
		if(empty($id))
		{
			$codpedido = "P1";

		} else {

			$resto = substr($id, 0, 1);
			$coun = strlen($resto);
			$num     = substr($id, $coun);
			$codigo     = $num + 1;
			$codpedido = "P".$codigo;
		}
		##################### AGREGAMOS EL NUMERO DE PEDIDO A LA VENTA #######################

        $fecha = date("Y-m-d h:i:s");

        $sql = " SELECT codmesa FROM ventas WHERE codmesa = ? AND statuspago = 1";
			$stmt = $this->dbh->prepare($sql);
			$stmt->execute(array(limpiar($_POST['codmesa'])));
			$num = $stmt->rowCount();
			if($num == 0)
			{

		$query = "INSERT INTO ventas values (null, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);";
		$stmt = $this->dbh->prepare($query);
		$stmt->bindParam(1, $codpedido);
		$stmt->bindParam(2, $codmesa);
		$stmt->bindParam(3, $tipodocumento);
		$stmt->bindParam(4, $codcaja);
		$stmt->bindParam(5, $codventa);
		$stmt->bindParam(6, $codserie);
		$stmt->bindParam(7, $codautorizacion);
		$stmt->bindParam(8, $codcliente);
		$stmt->bindParam(9, $subtotalivasi);
		$stmt->bindParam(10, $subtotalivano);
		$stmt->bindParam(11, $iva);
		$stmt->bindParam(12, $totaliva);
		$stmt->bindParam(13, $descuento);
		$stmt->bindParam(14, $totaldescuento);
		$stmt->bindParam(15, $totalpago);
		$stmt->bindParam(16, $totalpago2);
		$stmt->bindParam(17, $tipopago);
		$stmt->bindParam(18, $formapago);
		$stmt->bindParam(19, $montopagado);
		$stmt->bindParam(20, $montopropina);
		$stmt->bindParam(21, $montodevuelto);
		$stmt->bindParam(22, $fechavencecredito);
		$stmt->bindParam(23, $fechapagado);
		$stmt->bindParam(24, $statusventa);
		$stmt->bindParam(25, $statuspago);
		$stmt->bindParam(26, $fechaventa);
		$stmt->bindParam(27, $delivery);
		$stmt->bindParam(28, $repartidor);
		$stmt->bindParam(29, $entregado);
		$stmt->bindParam(30, $observaciones);
		$stmt->bindParam(31, $codigo);
		$stmt->bindParam(32, $bandera);
	    
		$codmesa = limpiar($_POST["codmesa"]);
		$tipodocumento = limpiar("0");
		$codcaja = limpiar('0');
		$codventa = limpiar('0');
		$codserie = limpiar('0');
		$codautorizacion = limpiar('0');
		$codcliente = limpiar($_POST["codcliente"]);
		$subtotalivasi = limpiar($_POST["txtsubtotal"]);
		$subtotalivano = limpiar($_POST["txtsubtotal2"]);
		$iva = limpiar($_POST["iva"]);
		$totaliva = limpiar($_POST["txtIva"]);
		$descuento = limpiar($_POST["descuento"]);
		$totaldescuento = limpiar($_POST["txtDescuento"]);
		$totalpago = limpiar($_POST["txtTotal"]);
		$totalpago2 = limpiar($_POST["txtTotalCompra"]);
        $tipopago = limpiar("0");
        $formapago = limpiar("0");
        $montopagado = limpiar("0.00");
		$montopropina ='0.00';
        $montodevuelto = limpiar("0.00");
        $fechavencecredito = limpiar("0000-00-00");
	    $fechapagado = limpiar("0000-00-00");
	    $statusventa = limpiar("0");
	    $statuspago = limpiar("1");
        $fechaventa = limpiar($fecha);
		$delivery = limpiar("0");
		$repartidor = limpiar("0");
		$entregado = limpiar('0');
		$observaciones = limpiar("0");
		$codigo = limpiar($_SESSION["codigo"]);
		$bandera = limpiar("0");
		$stmt->execute();

		#################### ACTUALIZAMOS EL STATUS DE MESA ####################
		$sql = "UPDATE mesas set "
		." statusmesa = ? "
		." WHERE "
		." codmesa = ?;
		";
		$stmt = $this->dbh->prepare($sql);
		$stmt->bindParam(1, $statusmesa);
		$stmt->bindParam(2, $codmesa);

		$statusmesa = limpiar('1');
		$codmesa = limpiar($_POST["codmesa"]);
		$stmt->execute();
       #################### ACTUALIZAMOS EL STATUS DE MESA ####################

		$this->dbh->beginTransaction();
		$detalle = $_SESSION["CarritoVenta"];
		for($i=0;$i<count($detalle);$i++){

		$query = "INSERT INTO detalleventas values (null, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?); ";
		$stmt = $this->dbh->prepare($query);
		$stmt->bindParam(1, $codpedido);
		$stmt->bindParam(2, $codventa);
	    $stmt->bindParam(3, $codproducto);
	    $stmt->bindParam(4, $producto);
	    $stmt->bindParam(5, $codcategoria);
		$stmt->bindParam(6, $cantidad);
		$stmt->bindParam(7, $preciocompra);
		$stmt->bindParam(8, $precioventa);
		$stmt->bindParam(9, $ivaproducto);
		$stmt->bindParam(10, $descproducto);
		$stmt->bindParam(11, $valortotal);
		$stmt->bindParam(12, $totaldescuentov);
		$stmt->bindParam(13, $valorneto);
		$stmt->bindParam(14, $valorneto2);
		
		$codventa = limpiar('0');
		$codproducto = limpiar($detalle[$i]['txtCodigo']);
		$producto = limpiar($detalle[$i]['producto']);
		$codcategoria = limpiar($detalle[$i]['codcategoria']);
		$cantidad = limpiar($detalle[$i]['cantidad']);
		$preciocompra = limpiar($detalle[$i]['precio']);
		$precioventa = limpiar($detalle[$i]['precio2']);
		$ivaproducto = limpiar($detalle[$i]['ivaproducto']);
		$descproducto = limpiar($detalle[$i]['descproducto']);
		$descuento = $detalle[$i]['descproducto']/100;
		$valortotal = number_format($detalle[$i]['precio2']*$detalle[$i]['cantidad'], 2, '.', '');
		$totaldescuentov = number_format($valortotal*$descuento, 2, '.', '');
	    $valorneto = number_format($valortotal-$totaldescuentov, 2, '.', '');
		$valorneto2 = number_format($detalle[$i]['precio']*$detalle[$i]['cantidad'], 2, '.', '');
		$stmt->execute();


		$query = "INSERT INTO detallepedidos values (null, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);";
		$stmt = $this->dbh->prepare($query);
		$stmt->bindParam(1, $codpedido);
		$stmt->bindParam(2, $pedido);
		$stmt->bindParam(3, $codproducto);
		$stmt->bindParam(4, $producto);
		$stmt->bindParam(5, $codcategoria);
		$stmt->bindParam(6, $cantidad);
		$stmt->bindParam(7, $precioventa);
		$stmt->bindParam(8, $ivaproducto);
		$stmt->bindParam(9, $descproducto);
		$stmt->bindParam(10, $valortotal);
		$stmt->bindParam(11, $totaldescuentov);
		$stmt->bindParam(12, $valorneto);
		$stmt->bindParam(13, $observacionespedido);
		$stmt->bindParam(14, $cocinero);

		$pedido = limpiar("1");
		$codproducto = limpiar($detalle[$i]['txtCodigo']);
		$producto = limpiar($detalle[$i]['producto']);
		$codcategoria = limpiar($detalle[$i]['codcategoria']);
		$cantidad = limpiar($detalle[$i]['cantidad']);
		$precioventa = limpiar($detalle[$i]['precio2']);
		$ivaproducto = limpiar($detalle[$i]['ivaproducto']);
		$descproducto = limpiar($detalle[$i]['descproducto']);
		$descuento = $detalle[$i]['descproducto']/100;
		$valortotal = number_format($detalle[$i]['precio2']*$detalle[$i]['cantidad'], 2, '.', '');
		$totaldescuentov = number_format($valortotal*$descuento, 2, '.', '');
	    $valorneto = number_format($valortotal-$totaldescuentov, 2, '.', '');
	    if (limpiar($_POST['observacionespedido']!="")) { $observacionespedido = limpiar($_POST['observacionespedido']); } else { $observacionespedido ='0'; }
		$cocinero = limpiar('1');
		$stmt->execute();

############################### PROCESO PARA VERIFICAR LOS PRODUCTOS ######################################		

		############## VERIFICO LA EXISTENCIA DEL PRODUCTO EN ALMACEN #################
		$sql = "SELECT existencia FROM productos WHERE codproducto = '".limpiar($detalle[$i]['txtCodigo'])."'";
		foreach ($this->dbh->query($sql) as $row)
		{
			$this->p[] = $row;
		}
		$existenciaproductobd = $row['existencia'];

	    ##################### ACTUALIZO LA EXISTENCIA DEL ALMACEN ####################
		$sql = " UPDATE productos set "
			  ." existencia = ? "
			  ." where "
			  ." codproducto = '".limpiar($detalle[$i]['txtCodigo'])."';
			   ";
		$stmt = $this->dbh->prepare($sql);
		$stmt->bindParam(1, $existencia);
		$cantventa = limpiar($detalle[$i]['cantidad']);
		$existencia = number_format($existenciaproductobd-$cantventa, 2, '.', '');
		$stmt->execute();

		############## REGISTRAMOS LOS DATOS DE PRODUCTOS EN KARDEX ###################
        $query = "INSERT INTO kardex_productos values (null, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?); ";
		$stmt = $this->dbh->prepare($query);
		$stmt->bindParam(1, $codpedido);
		$stmt->bindParam(2, $codcliente);
		$stmt->bindParam(3, $codproducto);
		$stmt->bindParam(4, $movimiento);
		$stmt->bindParam(5, $entradas);
		$stmt->bindParam(6, $salidas);
		$stmt->bindParam(7, $devolucion);
		$stmt->bindParam(8, $stockactual);
		$stmt->bindParam(9, $ivaproducto);
		$stmt->bindParam(10, $descproducto);
		$stmt->bindParam(11, $precio);
		$stmt->bindParam(12, $documento);
		$stmt->bindParam(13, $fechakardex);		

		$codcliente = limpiar($_POST["codcliente"]);
		$codproducto = limpiar($detalle[$i]['txtCodigo']);
		$movimiento = limpiar("SALIDAS");
		$entradas = limpiar("0");
		$salidas= limpiar($detalle[$i]['cantidad']);
		$devolucion = limpiar("0");
		$stockactual = number_format($existenciaproductobd-$detalle[$i]['cantidad'], 2, '.', '');
		$precio = limpiar($detalle[$i]['precio2']);
		$ivaproducto = limpiar($detalle[$i]['ivaproducto']);
		$descproducto = limpiar($detalle[$i]['descproducto']);
		$documento = limpiar("PEDIDO EN MESA");
		$fechakardex = limpiar(date("Y-m-d"));
		$stmt->execute();

############################### PROCESO PARA VERIFICAR LOS PRODUCTOS ######################################	



############################### PROCESO PARA VERIFICAR LOS INGREDIENTES ######################################	

	    ############## VERIFICO SSI EL PRODUCTO TIENE INGREDIENTES RELACIONADOS #################
	    $sql = "SELECT * FROM productosxingredientes WHERE codproducto = ?";
		$stmt = $this->dbh->prepare($sql);
		$stmt->execute(array(limpiar($detalle[$i]['txtCodigo'])));
		$num = $stmt->rowCount();
        if($num>0) {  

        	$sql = "SELECT * FROM productosxingredientes LEFT JOIN ingredientes ON productosxingredientes.codingrediente = ingredientes.codingrediente WHERE productosxingredientes.codproducto IN ('".$detalle[$i]['txtCodigo']."')";
        	foreach ($this->dbh->query($sql) as $row)
		    { 
			   $this->p[] = $row;

			   //$codproducto = $row['codproducto'];
			   $cantracionbd = $row['cantracion'];
			   $codingredientebd = $row['codingrediente'];
			   $cantingredientebd = $row['cantingrediente'];
			   $precioventaingredientebd = $row['precioventa'];
			   $ivaingredientebd = $row['ivaingrediente'];
			   $descingredientebd = $row['descingrediente'];

			   ############## ACTUALIZO LOS DATOS DEL INGREDIENTE #################
			   $update = "UPDATE ingredientes set "
			   ." cantingrediente = ? "
			   ." WHERE "
			   ." codingrediente = ?;
			   ";
			   $stmt = $this->dbh->prepare($update);
			   $stmt->bindParam(1, $cantidadracion);
			   $stmt->bindParam(2, $codingredientebd);

			   $racion = number_format($cantracionbd*$detalle[$i]['cantidad'], 2, '.', '');
			   $cantidadracion = number_format($cantingredientebd-$racion, 2, '.', '');
			   $stmt->execute();

			   ############## REGISTRAMOS LOS DATOS DE PRODUCTOS EN KARDEX ###################
			   $query = "INSERT INTO kardex_ingredientes values (null, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?); ";
			   $stmt = $this->dbh->prepare($query);
			   $stmt->bindParam(1, $codpedido);
			   $stmt->bindParam(2, $codcliente);
			   $stmt->bindParam(3, $codingrediente);
			   $stmt->bindParam(4, $movimiento);
			   $stmt->bindParam(5, $entradas);
			   $stmt->bindParam(6, $salidas);
			   $stmt->bindParam(7, $devolucion);
			   $stmt->bindParam(8, $stockactual);
			   $stmt->bindParam(9, $ivaingrediente);
			   $stmt->bindParam(10, $descingrediente);
			   $stmt->bindParam(11, $precio);
			   $stmt->bindParam(12, $documento);
			   $stmt->bindParam(13, $fechakardex);		

			   $codcliente = limpiar($_POST["codcliente"]);
			   $codingrediente = limpiar($codingredientebd);
			   $movimiento = limpiar("SALIDAS");
			   $entradas = limpiar("0");
			   $salidas= limpiar($racion);
			   $devolucion = limpiar("0");
			   $stockactual = number_format($cantingredientebd-$racion, 2, '.', '');
			   $precio = limpiar($precioventaingredientebd);
			   $ivaingrediente = limpiar($ivaingredientebd);
			   $descingrediente = limpiar($descingredientebd);
			   $documento = limpiar("PEDIDO EN MESA");
			   $fechakardex = limpiar(date("Y-m-d"));
			   $stmt->execute();
		    }

		  }//fin de consulta de ingredientes de productos	

############################### PROCESO PARA VERIFICAR LOS INGREDIENTES ######################################	

      }
		
	####################### DESTRUYO LA VARIABLE DE SESSION #####################
	unset($_SESSION["CarritoVenta"]);
    $this->dbh->commit();

echo "<span class='fa fa-check-square-o'></span> EL PEDIDO DE LA ".limpiar($_POST["nombremesa"]).", FUE REGISTRADO EXITOSAMENTE <a href='reportepdf?codpedido=".encrypt($codpedido)."&pedido=".encrypt($pedido)."&tipo=".encrypt("COMANDA")."' class='on-default' data-placement='left' data-toggle='tooltip' data-original-title='Imprimir Documento' target='_black' rel='noopener noreferrer'><font color='black'><strong>IMPRIMIR COMANDA</strong></font color></a></div>";

echo "<script>window.open('reportepdf?codpedido=".encrypt($codpedido)."&pedido=".encrypt($pedido)."&tipo=".encrypt("COMANDA")."', '_blank');</script>";
	exit;
    
    } else {

		echo "3";
		exit;
	}
}
######################### FUNCION REGISTRAR VENTAS ############################


############################ FUNCION AGREGAR PEDIDOS A VENTAS ##############################
	public function AgregaPedido()
	{
		self::SetNames();
		if(empty($_SESSION["CarritoVenta"]) || $_POST["txtTotal"]=="0.00")
		{
			echo "1";
			exit;
			
		}

		############ VALIDO SI LA CANTIDAD ES MAYOR QUE LA EXISTENCIA ############
		$v = $_SESSION["CarritoVenta"];
		for($i=0;$i<count($v);$i++){

		    $sql = "SELECT existencia FROM productos WHERE codproducto = '".$v[$i]['txtCodigo']."'";
		    foreach ($this->dbh->query($sql) as $row)
		    {
			$this->p[] = $row;
		    }
		
		    $existenciadb = $row['existencia'];
		    $cantidad = $v[$i]['cantidad'];

            if ($cantidad > $existenciadb) 
            { 
		       echo "2";
		       exit;
	        }
		}


		##################### AGREGAMOS EL NUMERO DE PEDIDO A LA VENTA #######################
		$sql = "SELECT pedido FROM detallepedidos WHERE codpedido = '".limpiar($_POST['codpedido'])."' ORDER BY coddetallepedido DESC LIMIT 1";
	    foreach ($this->dbh->query($sql) as $row){

	    $nuevopedido=$row["pedido"];

	    }
		
	    $dig = $nuevopedido + 1;
	    $pedido = $dig;
		##################### AGREGAMOS EL NUMERO DE PEDIDO A LA VENTA #######################

		$this->dbh->beginTransaction();
		$detalle = $_SESSION["CarritoVenta"];
		for($i=0;$i<count($detalle);$i++){

		############ REVISAMOS QUE EL PRODUCTO NO ESTE EN LA BD ###################
	    $sql = "SELECT codpedido, codproducto FROM detalleventas WHERE codpedido = '".limpiar($_POST['codpedido'])."' AND codproducto = '".limpiar($detalle[$i]['txtCodigo'])."'";
		$stmt = $this->dbh->prepare($sql);
		$stmt->execute();
		$num = $stmt->rowCount();
		if($num == 0)
		{

		$query = "INSERT INTO detalleventas values (null, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?); ";
		$stmt = $this->dbh->prepare($query);
		$stmt->bindParam(1, $codpedido);
		$stmt->bindParam(2, $codventa);
	    $stmt->bindParam(3, $codproducto);
	    $stmt->bindParam(4, $producto);
	    $stmt->bindParam(5, $codcategoria);
		$stmt->bindParam(6, $cantidad);
		$stmt->bindParam(7, $preciocompra);
		$stmt->bindParam(8, $precioventa);
		$stmt->bindParam(9, $ivaproducto);
		$stmt->bindParam(10, $descproducto);
		$stmt->bindParam(11, $valortotal);
		$stmt->bindParam(12, $totaldescuentov);
		$stmt->bindParam(13, $valorneto);
		$stmt->bindParam(14, $valorneto2);
		
		$codpedido = limpiar($_POST["codpedido"]);
		$codventa = limpiar('0');
		$codproducto = limpiar($detalle[$i]['txtCodigo']);
		$producto = limpiar($detalle[$i]['producto']);
		$codcategoria = limpiar($detalle[$i]['codcategoria']);
		$cantidad = limpiar($detalle[$i]['cantidad']);
		$preciocompra = limpiar($detalle[$i]['precio']);
		$precioventa = limpiar($detalle[$i]['precio2']);
		$ivaproducto = limpiar($detalle[$i]['ivaproducto']);
		$descproducto = limpiar($detalle[$i]['descproducto']);
		$descuento = $detalle[$i]['descproducto']/100;
		$valortotal = number_format($detalle[$i]['precio2']*$detalle[$i]['cantidad'], 2, '.', '');
		$totaldescuentov = number_format($valortotal*$descuento, 2, '.', '');
	    $valorneto = number_format($valortotal-$totaldescuentov, 2, '.', '');
		$valorneto2 = number_format($detalle[$i]['precio']*$detalle[$i]['cantidad'], 2, '.', '');
		$stmt->execute();

		$query = "INSERT INTO detallepedidos values (null, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);";
		$stmt = $this->dbh->prepare($query);
		$stmt->bindParam(1, $codpedido);
		$stmt->bindParam(2, $pedido);
		$stmt->bindParam(3, $codproducto);
		$stmt->bindParam(4, $producto);
		$stmt->bindParam(5, $codcategoria);
		$stmt->bindParam(6, $cantidad);
		$stmt->bindParam(7, $precioventa);
		$stmt->bindParam(8, $ivaproducto);
		$stmt->bindParam(9, $descproducto);
		$stmt->bindParam(10, $valortotal);
		$stmt->bindParam(11, $totaldescuentov);
		$stmt->bindParam(12, $valorneto);
		$stmt->bindParam(13, $observacionespedido);
		$stmt->bindParam(14, $cocinero);

		$codpedido = limpiar($_POST["codpedido"]);
		$codproducto = limpiar($detalle[$i]['txtCodigo']);
		$producto = limpiar($detalle[$i]['producto']);
		$codcategoria = limpiar($detalle[$i]['codcategoria']);
		$cantidad = limpiar($detalle[$i]['cantidad']);
		$precioventa = limpiar($detalle[$i]['precio2']);
		$ivaproducto = limpiar($detalle[$i]['ivaproducto']);
		$descproducto = limpiar($detalle[$i]['descproducto']);
		$descuento = $detalle[$i]['descproducto']/100;
		$valortotal = number_format($detalle[$i]['precio2']*$detalle[$i]['cantidad'], 2, '.', '');
		$totaldescuentov = number_format($valortotal*$descuento, 2, '.', '');
	    $valorneto = number_format($valortotal-$totaldescuentov, 2, '.', '');
	    if (limpiar(isset($_POST['observacionespedido']))) { $observacionespedido = limpiar($_POST['observacionespedido']); } else { $observacionespedido ='0'; }
		$cocinero = limpiar('1');
		$stmt->execute();


############################### PROCESO PARA VERIFICAR LOS PRODUCTOS ######################################		

		############## VERIFICO LA EXISTENCIA DEL PRODUCTO EN ALMACEN #################
		$sql = "SELECT existencia FROM productos WHERE codproducto = '".limpiar($detalle[$i]['txtCodigo'])."'";
		foreach ($this->dbh->query($sql) as $row)
		{
			$this->p[] = $row;
		}
		$existenciaproductobd = $row['existencia'];
		############## VERIFICO LA EXISTENCIA DEL PRODUCTO EN ALMACEN #################

		##################### ACTUALIZO LA EXISTENCIA DEL ALMACEN ####################
		$sql = " UPDATE productos set "
			  ." existencia = ? "
			  ." where "
			  ." codproducto = '".limpiar($detalle[$i]['txtCodigo'])."';
			   ";
		$stmt = $this->dbh->prepare($sql);
		$stmt->bindParam(1, $existencia);
		$cantventa = limpiar($detalle[$i]['cantidad']);
		$existencia = number_format($existenciaproductobd-$cantventa, 2, '.', '');
		$stmt->execute();
		##################### ACTUALIZO LA EXISTENCIA DEL ALMACEN ####################

		############## REGISTRAMOS LOS DATOS DE PRODUCTOS EN KARDEX ###################
        $query = "INSERT INTO kardex_productos values (null, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?); ";
		$stmt = $this->dbh->prepare($query);
		$stmt->bindParam(1, $codpedido);
		$stmt->bindParam(2, $codcliente);
		$stmt->bindParam(3, $codproducto);
		$stmt->bindParam(4, $movimiento);
		$stmt->bindParam(5, $entradas);
		$stmt->bindParam(6, $salidas);
		$stmt->bindParam(7, $devolucion);
		$stmt->bindParam(8, $stockactual);
		$stmt->bindParam(9, $ivaproducto);
		$stmt->bindParam(10, $descproducto);
		$stmt->bindParam(11, $precio);
		$stmt->bindParam(12, $documento);
		$stmt->bindParam(13, $fechakardex);		

		$codpedido = limpiar($_POST["codpedido"]);
		$codcliente = limpiar($_POST["codcliente"]);
		$codproducto = limpiar($detalle[$i]['txtCodigo']);
		$movimiento = limpiar("SALIDAS");
		$entradas = limpiar("0");
		$salidas= limpiar($detalle[$i]['cantidad']);
		$devolucion = limpiar("0");
		$stockactual = number_format($existenciaproductobd-$detalle[$i]['cantidad'], 2, '.', '');
		$precio = limpiar($detalle[$i]['precio2']);
		$ivaproducto = limpiar($detalle[$i]['ivaproducto']);
		$descproducto = limpiar($detalle[$i]['descproducto']);
		$documento = limpiar("PEDIDO EN MESA");
		$fechakardex = limpiar(date("Y-m-d"));
		$stmt->execute();
############################### PROCESO PARA VERIFICAR LOS PRODUCTOS ######################################	



############################### PROCESO PARA VERIFICAR LOS INGREDIENTES ######################################	

	    ############## VERIFICO SI EL PRODUCTO TIENE INGREDIENTES RELACIONADOS #################
	    $sql = "SELECT * FROM productosxingredientes WHERE codproducto = ?";
		$stmt = $this->dbh->prepare($sql);
		$stmt->execute(array(limpiar($detalle[$i]['txtCodigo'])));
		$num = $stmt->rowCount();
        if($num>0) {  

        	$sql = "SELECT * FROM productosxingredientes LEFT JOIN ingredientes ON productosxingredientes.codingrediente = ingredientes.codingrediente WHERE productosxingredientes.codproducto IN ('".$detalle[$i]['txtCodigo']."')";
        	foreach ($this->dbh->query($sql) as $row)
		    { 
			   $this->p[] = $row;

			   $cantracionbd = $row['cantracion'];
			   $codingredientebd = $row['codingrediente'];
			   $cantingredientebd = $row['cantingrediente'];
			   $precioventaingredientebd = $row['precioventa'];
			   $ivaingredientebd = $row['ivaingrediente'];
			   $descingredientebd = $row['descingrediente'];

			   ############## ACTUALIZO LOS DATOS DEL INGREDIENTE #################
			   $update = "UPDATE ingredientes set "
			   ." cantingrediente = ? "
			   ." WHERE "
			   ." codingrediente = ?;
			   ";
			   $stmt = $this->dbh->prepare($update);
			   $stmt->bindParam(1, $cantidadracion);
			   $stmt->bindParam(2, $codingredientebd);

			   $racion = number_format($cantracionbd*$detalle[$i]['cantidad'], 2, '.', '');
			   $cantidadracion = number_format($cantingredientebd-$racion, 2, '.', '');
			   $stmt->execute();

			   ############## REGISTRAMOS LOS DATOS DE PRODUCTOS EN KARDEX ###################
			   $query = "INSERT INTO kardex_ingredientes values (null, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?); ";
			   $stmt = $this->dbh->prepare($query);
			   $stmt->bindParam(1, $codpedido);
			   $stmt->bindParam(2, $codcliente);
			   $stmt->bindParam(3, $codingrediente);
			   $stmt->bindParam(4, $movimiento);
			   $stmt->bindParam(5, $entradas);
			   $stmt->bindParam(6, $salidas);
			   $stmt->bindParam(7, $devolucion);
			   $stmt->bindParam(8, $stockactual);
			   $stmt->bindParam(9, $ivaingrediente);
			   $stmt->bindParam(10, $descingrediente);
			   $stmt->bindParam(11, $precio);
			   $stmt->bindParam(12, $documento);
			   $stmt->bindParam(13, $fechakardex);		

			   $codpedido = limpiar($_POST["codpedido"]);
			   $codcliente = limpiar($_POST["codcliente"]);
			   $codingrediente = limpiar($codingredientebd);
			   $movimiento = limpiar("SALIDAS");
			   $entradas = limpiar("0");
			   $salidas= limpiar($racion);
			   $devolucion = limpiar("0");
			   $stockactual = number_format($cantingredientebd-$racion, 2, '.', '');
			   $precio = limpiar($precioventaingredientebd);
			   $ivaingrediente = limpiar($ivaingredientebd);
			   $descingrediente = limpiar($descingredientebd);
			   $documento = limpiar("PEDIDO EN MESA");
			   $fechakardex = limpiar(date("Y-m-d"));
			   $stmt->execute();
		    }

		  }//fin de consulta de ingredientes de productos	

############################### PROCESO PARA VERIFICAR LOS INGREDIENTES ######################################	

		} else {

		##################### VERIFICO LA CANTIDAD YA REGISTRADA DEL PRODUCTO VENDIDO ####################
		$sql = "SELECT cantventa FROM detalleventas WHERE codpedido = '".limpiar($_POST['codpedido'])."' AND codproducto = '".limpiar($detalle[$i]['txtCodigo'])."'";
		foreach ($this->dbh->query($sql) as $row)
		{
			$this->p[] = $row;
		}
		$cantidad = $row['cantventa'];
		##################### VERIFICO LA CANTIDAD YA REGISTRADA DEL PRODUCTO VENDIDO ####################

	  	$query = "UPDATE detalleventas set"
		." cantventa = ?, "
		." descproducto = ?, "
		." valortotal = ?, "
		." totaldescuentov = ?, "
		." valorneto = ?, "
		." valorneto2 = ? "
		." WHERE "
		." codpedido = ? AND codproducto = ?;
		";
		$stmt = $this->dbh->prepare($query);
		$stmt->bindParam(1, $cantventa);
		$stmt->bindParam(2, $descproducto);
		$stmt->bindParam(3, $valortotal);
		$stmt->bindParam(4, $totaldescuentov);
		$stmt->bindParam(5, $valorneto);
		$stmt->bindParam(6, $valorneto2);
		$stmt->bindParam(7, $codpedido);
		$stmt->bindParam(8, $codproducto);

		$cantventa = limpiar($detalle[$i]['cantidad']+$cantidad);
		$preciocompra = limpiar($detalle[$i]['precio']);
		$precioventa = limpiar($detalle[$i]['precio2']);
		$ivaproducto = limpiar($detalle[$i]['ivaproducto']);
		$descproducto = limpiar($detalle[$i]['descproducto']);
		$descuento = $detalle[$i]['descproducto']/100;
		$valortotal = number_format($detalle[$i]['precio2'] * $cantventa, 2, '.', '');
		$totaldescuentov = number_format($valortotal * $descuento, 2, '.', '');
		$valorneto = number_format($valortotal - $totaldescuentov, 2, '.', '');
		$valorneto2 = number_format($detalle[$i]['precio'] * $cantventa, 2, '.', '');
		$codpedido = limpiar($_POST["codpedido"]);
		$codproducto = limpiar($detalle[$i]['txtCodigo']);
		$stmt->execute();

		$query = "INSERT INTO detallepedidos values (null, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);";
		$stmt = $this->dbh->prepare($query);
		$stmt->bindParam(1, $codpedido);
		$stmt->bindParam(2, $pedido);
		$stmt->bindParam(3, $codproducto);
		$stmt->bindParam(4, $producto);
		$stmt->bindParam(5, $codcategoria);
		$stmt->bindParam(6, $cantidad);
		$stmt->bindParam(7, $precioventa);
		$stmt->bindParam(8, $ivaproducto);
		$stmt->bindParam(9, $descproducto);
		$stmt->bindParam(10, $valortotal);
		$stmt->bindParam(11, $totaldescuentov);
		$stmt->bindParam(12, $valorneto);
		$stmt->bindParam(13, $observacionespedido);
		$stmt->bindParam(14, $cocinero);

		$codpedido = limpiar($_POST["codpedido"]);
		$codproducto = limpiar($detalle[$i]['txtCodigo']);
		$producto = limpiar($detalle[$i]['producto']);
		$codcategoria = limpiar($detalle[$i]['codcategoria']);
		$cantidad = limpiar($detalle[$i]['cantidad']);
		$precioventa = limpiar($detalle[$i]['precio2']);
		$ivaproducto = limpiar($detalle[$i]['ivaproducto']);
		$descproducto = limpiar($detalle[$i]['descproducto']);
		$descuento = $detalle[$i]['descproducto']/100;
		$valortotal = number_format($detalle[$i]['precio2']*$detalle[$i]['cantidad'], 2, '.', '');
		$totaldescuentov = number_format($valortotal*$descuento, 2, '.', '');
	    $valorneto = number_format($valortotal-$totaldescuentov, 2, '.', '');
	    if (limpiar($_POST['observacionespedido']!="")) { $observacionespedido = limpiar($_POST['observacionespedido']); } else { $observacionespedido ='0'; }
		$cocinero = limpiar('1');
		$stmt->execute();

############################### PROCESO PARA VERIFICAR LOS PRODUCTOS ######################################

		############## VERIFICO LA EXISTENCIA DEL PRODUCTO EN ALMACEN #################
		$sql = "SELECT existencia FROM productos WHERE codproducto = '".limpiar($detalle[$i]['txtCodigo'])."'";
		foreach ($this->dbh->query($sql) as $row)
		{
			$this->p[] = $row;
		}
		$existenciaproductobd = $row['existencia'];
		############## VERIFICO LA EXISTENCIA DEL PRODUCTO EN ALMACEN #################		

		##################### ACTUALIZO LA EXISTENCIA DEL ALMACEN ####################
		$sql = " UPDATE productos set "
			  ." existencia = ? "
			  ." where "
			  ." codproducto = '".limpiar($detalle[$i]['txtCodigo'])."';
			   ";
		$stmt = $this->dbh->prepare($sql);
		$stmt->bindParam(1, $existencia);
		$cantventa = limpiar($detalle[$i]['cantidad']);
		$existencia = number_format($existenciaproductobd-$cantventa, 2, '.', '');
		$stmt->execute();
		##################### ACTUALIZO LA EXISTENCIA DEL ALMACEN ####################

		########## ACTUALIZAMOS LOS DATOS DEL PRODUCTO EN KARDEX ###################
		$sql3 = " UPDATE kardex_productos set "
		      ." salidas = ?, "
		      ." stockactual = ? "
			  ." WHERE "
			  ." codproceso = '".limpiar($_POST["codpedido"])."' and codproducto = '".limpiar($detalle[$i]['txtCodigo'])."';
			   ";
		$stmt = $this->dbh->prepare($sql3);
		$stmt->bindParam(1, $salidas);
		$stmt->bindParam(2, $stockactual);
		
		$salidas = number_format($detalle[$i]['cantidad']+$cantidad, 2, '.', '');
		$stockactual = number_format($existenciaproductobd-$cantventa, 2, '.', '');
		$stmt->execute();

############################### PROCESO PARA VERIFICAR LOS PRODUCTOS ######################################		



############################### PROCESO PARA VERIFICAR LOS INGREDIENTES ######################################	

		############## VERIFICO SSI EL PRODUCTO TIENE INGREDIENTES RELACIONADOS #################
	    $sql = "SELECT * FROM productosxingredientes WHERE codproducto = ?";
		$stmt = $this->dbh->prepare($sql);
		$stmt->execute(array(limpiar($detalle[$i]['txtCodigo'])));
		$num = $stmt->rowCount();
        if($num>0) {  

        	$sql = "SELECT * FROM productosxingredientes LEFT JOIN ingredientes ON productosxingredientes.codingrediente = ingredientes.codingrediente WHERE productosxingredientes.codproducto IN ('".$detalle[$i]['txtCodigo']."')";
        	foreach ($this->dbh->query($sql) as $row)
		    { 
			   $this->p[] = $row;

			   $cantracionbd = $row['cantracion'];
			   $codingredientebd = $row['codingrediente'];
			   $cantingredientebd = $row['cantingrediente'];
			   $precioventaingredientebd = $row['precioventa'];
			   $ivaingredientebd = $row['ivaingrediente'];
			   $descingredientebd = $row['descingrediente'];

			   ############## ACTUALIZO LOS DATOS DEL INGREDIENTE #################
			   $update = "UPDATE ingredientes set "
			   ." cantingrediente = ? "
			   ." WHERE "
			   ." codingrediente = ?;
			   ";
			   $stmt = $this->dbh->prepare($update);
			   $stmt->bindParam(1, $cantidadracion);
			   $stmt->bindParam(2, $codingredientebd);

			   $racion = number_format($cantracionbd*$detalle[$i]['cantidad'], 2, '.', '');
			   $cantidadracion = number_format($cantingredientebd-$racion, 2, '.', '');
			   $stmt->execute();

			   ############## VERIFICO LA EXISTENCIA DEL PRODUCTO EN ALMACEN #################
			   $sql = "SELECT salidas FROM kardex_ingredientes WHERE codproceso = '".limpiar($_POST['codpedido'])."' AND codingrediente = '".limpiar($codingredientebd)."'";
			   foreach ($this->dbh->query($sql) as $row)
			   {
			   	$this->p[] = $row;
			   }
			   $salidakardex = $row['salidas'];
		############## VERIFICO LA EXISTENCIA DEL PRODUCTO EN ALMACEN #################	

			   ########## ACTUALIZAMOS LOS DATOS DEL INGREDIENTE EN KARDEX ###################
			   $sql3 = " UPDATE kardex_ingredientes set "
			   ." salidas = ?, "
			   ." stockactual = ? "
			   ." WHERE "
			   ." codproceso = '".limpiar($_POST["codpedido"])."' and codingrediente = '".limpiar($codingredientebd)."';
			   ";
			   $stmt = $this->dbh->prepare($sql3);
			   $stmt->bindParam(1, $salidas);
			   $stmt->bindParam(2, $stockactual);

			   $racion = number_format($cantracionbd*$detalle[$i]['cantidad'], 2, '.', '');
			   $salidas = number_format($salidakardex+$racion, 2, '.', '');
			   
			   $substock = number_format($cantracionbd*$detalle[$i]['cantidad'], 2, '.', '');
			   $stockactual = number_format($cantingredientebd-$substock, 2, '.', '');
			   $stmt->execute();

		    }

		  }//fin de consulta de ingredientes de productos

############################### PROCESO PARA VERIFICAR LOS INGREDIENTES ######################################	


        }
    }
		
	####################### DESTRUYO LA VARIABLE DE SESSION #####################
	unset($_SESSION["CarritoVenta"]);
    $this->dbh->commit();

    ############ SUMO LOS IMPORTE DE PRODUCTOS CON IVA ##############
    $sql3 = "SELECT SUM(valorneto) AS valorneto, SUM(valorneto2) AS valorneto2 FROM detalleventas WHERE codpedido = '".limpiar($_POST["codpedido"])."' AND ivaproducto = 'SI'";
    foreach ($this->dbh->query($sql3) as $row3)
    {
    	$this->p[] = $row3;
    }
    $subtotalivasi = ($row3['valorneto']== "" ? "0.00" : $row3['valorneto']);
    $subtotalivasi2 = ($row3['valorneto2']== "" ? "0.00" : $row3['valorneto2']);

    ############ SUMO LOS IMPORTE DE PRODUCTOS SIN IVA ##############
    $sql4 = "SELECT SUM(valorneto) AS valorneto, SUM(valorneto2) AS valorneto2 FROM detalleventas WHERE codpedido = '".limpiar($_POST["codpedido"])."' AND ivaproducto = 'NO'";
    foreach ($this->dbh->query($sql4) as $row4)
    {
    	$this->p[] = $row4;
    }
    $subtotalivano = ($row4['valorneto']== "" ? "0.00" : $row4['valorneto']);
    $subtotalivano2 = ($row4['valorneto2']== "" ? "0.00" : $row4['valorneto2']);

    ############ ACTUALIZO LOS TOTALES EN LA PEDIDO ##############
    $sql = " UPDATE ventas SET "
    ." codcliente = ?, "
    ." subtotalivasi = ?, "
    ." subtotalivano = ?, "
    ." totaliva = ?, "
    ." descuento = ?, "
    ." totaldescuento = ?, "
    ." totalpago = ?, "
    ." totalpago2 = ? "
    ." WHERE "
    ." codpedido = ?;
    ";
    $stmt = $this->dbh->prepare($sql);
    $stmt->bindParam(1, $codcliente);
    $stmt->bindParam(2, $subtotalivasi);
    $stmt->bindParam(3, $subtotalivano);
    $stmt->bindParam(4, $totaliva);
    $stmt->bindParam(5, $descuento);
    $stmt->bindParam(6, $totaldescuento);
    $stmt->bindParam(7, $totalpago);
    $stmt->bindParam(8, $totalpago2);
    $stmt->bindParam(9, $codpedido);

    $codcliente = limpiar($_POST["codcliente"]);
    $iva = $_POST["iva"]/100;
    $totaliva = number_format($subtotalivasi*$iva, 2, '.', '');
    $descuento = limpiar($_POST["descuento"]);
    $txtDescuento = $_POST["descuento"]/100;
    $total = number_format($subtotalivasi+$subtotalivano+$totaliva, 2, '.', '');
    $totaldescuento = number_format($total*$txtDescuento, 2, '.', '');
    $totalpago = number_format($total-$totaldescuento, 2, '.', '');
    $totalpago2 = number_format($subtotalivasi2+$subtotalivano2, 2, '.', '');
    $codpedido = limpiar($_POST["codpedido"]);
    $stmt->execute();

echo "<span class='fa fa-check-square-o'></span> EL PEDIDO FUE AGREGADO A LA ".limpiar($_POST["nombremesa"])." EXITOSAMENTE <a href='reportepdf?codpedido=".encrypt($codpedido)."&pedido=".encrypt($pedido)."&tipo=".encrypt("COMANDA")."' class='on-default' data-placement='left' data-toggle='tooltip' data-original-title='Imprimir Documento' target='_black' rel='noopener noreferrer'><font color='black'><strong>IMPRIMIR COMANDA</strong></font color></a></div>";

echo "<script>window.open('reportepdf?codpedido=".encrypt($codpedido)."&pedido=".encrypt($pedido)."&tipo=".encrypt("COMANDA")."', '_blank');</script>";
	exit;
}
######################### FUNCION AGREGAR PEDIDOS A VENTAS ############################

############################ FUNCION CERRAR VENTAS ##############################
	public function CerrarMesa()
	{
		self::SetNames();
		$sql = "SELECT * FROM arqueocaja INNER JOIN cajas ON arqueocaja.codcaja = cajas.codcaja INNER JOIN usuarios ON cajas.codigo = usuarios.codigo WHERE usuarios.codigo = ?";
		$stmt = $this->dbh->prepare($sql);
		$stmt->execute(array(limpiar($_SESSION["codigo"])));
		$num = $stmt->rowCount();
		if($num==0)
		{
			echo "4";
			exit;
	    }
	    elseif(limpiar($_POST["PagoGeneral"]=="0.00"))
		{
			echo "5";
			exit;
			
		}

		################### SELECCIONE LOS DATOS DEL CLIENTE ######################
	    $sql = "SELECT
	    clientes.nomcliente, 
	    clientes.emailcliente, 
	    clientes.limitecredito,
	    ROUND(SUM(if(pag.montocredito!='0',pag.montocredito,'0.00')), 2) montoactual,
	    ROUND(SUM(if(pag.montocredito!='0',clientes.limitecredito-pag.montocredito,clientes.limitecredito)), 2) creditodisponible
        FROM clientes 
        LEFT JOIN
           (SELECT
           codcliente, montocredito       
           FROM creditosxclientes) pag ON pag.codcliente = clientes.codcliente
           WHERE clientes.codcliente = ?";
		$stmt = $this->dbh->prepare($sql);
		$stmt->execute(array(limpiar($_POST['codcliente'])));
		$num = $stmt->rowCount();
		if($row = $stmt->fetch(PDO::FETCH_ASSOC))
		{
			$p[] = $row;
		}
        $nomcliente = $row['nomcliente'];
        $emailcliente = $row['emailcliente'];
        $limitecredito = $row['limitecredito'];
        $montoactual = $row['montoactual'];
        $creditodisponible = $row['creditodisponible'];
        $montoabono = (empty($_POST["montoabono"]) ? "0.00" : $_POST["montoabono"]);
        $total = number_format($_POST["PagoGeneral"]-$montoabono, 2, '.', '');
		
		if (limpiar(isset($_POST['fechavencecredito']))) {  

			$fechaactual = date("Y-m-d");
			$fechavence = date("Y-m-d",strtotime($_POST['fechavencecredito']));

			if (strtotime($fechavence) < strtotime($fechaactual)) {

				echo "6";
				exit;
			}
		}

		if ($_POST["tipopago"] == "CREDITO" && $_POST["codcliente"] == '0') { 

		        echo "7";
		        exit;

	    } else if ($_POST["tipopago"] == "CREDITO") {

		    if ($limitecredito != "0.00" && $total > $creditodisponible) {
	  
	           echo "8";
		       exit;

	        } 
	    }

	    if($_POST["tipopago"]=="CREDITO" && $_POST["montoabono"] >= $_POST["PagoGeneral"])
		{
			echo "9";
			exit;
			
		} else {

		################ CREO EL CODIGO DE BANDERA PARA NUMERO DE VENTA ################
		$sql = " SELECT bandera from ventas WHERE statuspago = 0 ORDER BY bandera DESC LIMIT 1";
		foreach ($this->dbh->query($sql) as $row){

	    $idbandera=$row["bandera"];

	    }
		if(empty($idbandera)){

			$bandera = '1';

		} else {
			$num     = $idbandera + 1;
			$bandera = $num;
		}
		################ CREO EL CODIGO DE BANDERA PARA NUMERO DE VENTA ################
		
	    ################ CREO LOS CODIGO VENTA-SERIE-AUTORIZACION ################
		$sql = " SELECT nroactividad, iniciofactura FROM configuracion";
		foreach ($this->dbh->query($sql) as $row)
		{
			$this->p[] = $row;
		}
		$nroactividad = $row['nroactividad'];
		$iniciofactura = $row['iniciofactura'];
		
		$sql = "SELECT codventa FROM ventas WHERE statuspago = 0 ORDER BY bandera DESC LIMIT 1";
		foreach ($this->dbh->query($sql) as $row){

			$venta=$row["codventa"];

		}
		if(empty($venta))
		{
			$codventa = $nroactividad.'-'.$iniciofactura;
			$codserie = $nroactividad;
			$codautorizacion = limpiar(GenerateRandomStringg());

		} else {

			$var = strlen($nroactividad."-");
            $var1 = substr($venta , $var);
            $var2 = strlen($var1);
            $var3 = $var1 + 1;
            $var4 = str_pad($var3, $var2, "0", STR_PAD_LEFT);
            $codventa = $nroactividad.'-'.$var4;
			$codserie = $nroactividad;
			$codautorizacion = limpiar(GenerateRandomStringg());
		}
        ################# CREO LOS CODIGO VENTA-SERIE-AUTORIZACION ###############

		$sql = "UPDATE ventas set "
		." tipodocumento = ?, "
		." codcaja = ?, "
		." codventa = ?, "
		." codserie = ?, "
		." codautorizacion = ?, "
		." codcliente = ?, "
		." tipopago = ?, "
		." formapago = ?, "
		." montopagado = ?, "
		." montopropina = ?, "
		." montodevuelto = ?, "
		." fechavencecredito = ?, "
		." statusventa = ?, "
		." statuspago = ?, "
		." observaciones = ?, "
		." bandera = ? "
		." WHERE "
		." codpedido = ?;
		";
		$stmt = $this->dbh->prepare($sql);
		$stmt->bindParam(1, $tipodocumento);
		$stmt->bindParam(2, $codcaja);
		$stmt->bindParam(3, $codventa);
		$stmt->bindParam(4, $codserie);
		$stmt->bindParam(5, $codautorizacion);
		$stmt->bindParam(6, $codcliente);
		$stmt->bindParam(7, $tipopago);
		$stmt->bindParam(8, $formapago);
		$stmt->bindParam(9, $montopagado);
		$stmt->bindParam(10, $montopropina);
		$stmt->bindParam(11, $montodevuelto);
		$stmt->bindParam(12, $fechavencecredito);
		$stmt->bindParam(13, $statusventa);
		$stmt->bindParam(14, $statuspago);
		$stmt->bindParam(15, $observaciones);
		$stmt->bindParam(16, $bandera);
		$stmt->bindParam(17, $codpedido);
	    
		$tipodocumento = limpiar($_POST["tipodocumento"]);
		$codcaja = limpiar($_POST["codcaja"]);
		$codcliente = limpiar($_POST["codcliente"]);
		$tipopago = limpiar($_POST["tipopago"]);
	if (limpiar($_POST["tipopago"]=="CONTADO")) { $formapago = limpiar(decrypt($_POST["codmediopago"])); } else { $formapago = "CREDITO"; }
	if (limpiar(isset($_POST['montopagado']))) { $montopagado = limpiar($_POST['montopagado']); } else { $montopagado ='0.00'; }
	if (limpiar(isset($_POST['montopropina']))) { $montopropina = limpiar($_POST['montopropina']); } else { $montopropina ='0.00'; }
	if (limpiar(isset($_POST['montodevuelto']))) { $montodevuelto = limpiar($_POST['montodevuelto']); } else { $montodevuelto ='0.00'; }
	if (limpiar(isset($_POST['fechavencecredito']))) { $fechavencecredito = limpiar(date("Y-m-d",strtotime($_POST['fechavencecredito']))); } else { $fechavencecredito ='0000-00-00'; }
	if (limpiar($_POST["tipopago"]=="CONTADO")) { $statusventa = limpiar("PAGADA"); } else { $statusventa = limpiar("PENDIENTE"); }
		$statuspago = limpiar("0");
		if (limpiar($_POST["observaciones"]!="")) { $observaciones = limpiar($_POST['observaciones']); } else { $observaciones =limpiar('0'); }
		$codpedido = limpiar($_POST["codpedido"]);
		$stmt->execute();

		#################### ACTUALIZAMOS EL Nº DE VENTA EN DETALLES DE VENTAS ####################
		$sql = "UPDATE detalleventas set "
		." codventa = ? "
		." WHERE "
		." codpedido = ?;
		";
		$stmt = $this->dbh->prepare($sql);
		$stmt->bindParam(1, $codventa);
		$stmt->bindParam(2, $codpedido);
	    
		$codpedido = limpiar($_POST["codpedido"]);
		$stmt->execute();
		#################### ACTUALIZAMOS EL Nº DE VENTA EN DETALLES DE VENTAS ####################

		#################### ACTUALIZAMOS EL STATUS DE MESA ####################
		$sql = "UPDATE mesas set "
		." statusmesa = ? "
		." WHERE "
		." codmesa = ?;
		";
		$stmt = $this->dbh->prepare($sql);
		$stmt->bindParam(1, $statusmesa);
		$stmt->bindParam(2, $codmesa);

		$statusmesa = limpiar('0');
		$codmesa = limpiar($_POST["codmesa"]);
		$stmt->execute();
        #################### ACTUALIZAMOS EL STATUS DE MESA ####################


        #################### ELIMINAMOS LOS DETALLES DE PEDIDOS DE ESTA VENTA ####################
		$sql = "DELETE FROM detallepedidos WHERE codpedido = ?";
		$stmt = $this->dbh->prepare($sql);
		$stmt->bindParam(1,$codpedido);
		$codpedido = limpiar($_POST["codpedido"]);
		$stmt->execute();
		#################### ELIMINAMOS LOS DETALLES DE PEDIDOS DE ESTA VENTA ####################


    ############## AGREGAMOS EL INGRESO DE VENTAS PAGADAS A CAJA ###############
	if (limpiar($_POST["tipopago"]=="CONTADO")){

		$sql = "SELECT ingresos, propinas FROM arqueocaja WHERE codcaja = '".limpiar($_POST["codcaja"])."' AND statusarqueo = '1'";
		foreach ($this->dbh->query($sql) as $row)
		{
			$this->p[] = $row;
		}
		$ingreso = ($row['ingresos']== "" ? "0.00" : $row['ingresos']);
		$propina = ($row['propinas']== "" ? "0.00" : $row['propinas']);

		$sql = "UPDATE arqueocaja set "
		." ingresos = ?, "
		." propinas = ? "
		." WHERE "
		." codcaja = ? AND statusarqueo = '1';
		";
		$stmt = $this->dbh->prepare($sql);
		$stmt->bindParam(1, $txtTotal);
		$stmt->bindParam(2, $txtPropina);
		$stmt->bindParam(3, $codcaja);

		$txtTotal = number_format($_POST["PagoGeneral"]+$ingreso, 2, '.', '');
		$txtPropina = number_format($_POST["montopropina"]+$propina, 2, '.', '');
		$codcaja = limpiar($_POST["codcaja"]);
		$stmt->execute();
	}
    ################ AGREGAMOS EL INGRESO DE VENTAS PAGADAS A CAJA ##############

    ######### AGREGAMOS EL INGRESO Y ABONOS DE VENTAS A CREDITOS A CAJA ############
	if (limpiar($_POST["tipopago"]=="CREDITO" && $_POST["montoabono"]=="0.00" && $_POST["montoabono"]=="0")) {

		$sql = "SELECT creditos FROM arqueocaja WHERE codcaja = '".limpiar($_POST["codcaja"])."' AND statusarqueo = '1'";
		foreach ($this->dbh->query($sql) as $row)
		{
			$this->p[] = $row;
		}
		$credito = ($row['creditos']== "" ? "0.00" : $row['creditos']);

		$sql = " UPDATE arqueocaja SET "
		." creditos = ? "
		." where "
		." codcaja = ? and statusarqueo = '1';
		";
		$stmt = $this->dbh->prepare($sql);
		$stmt->bindParam(1, $txtTotal);
		$stmt->bindParam(2, $codcaja);

		$txtTotal = number_format($_POST["PagoGeneral"]+$credito, 2, '.', '');
		$codcaja = limpiar($_POST["codcaja"]);
		$stmt->execute(); 

		$sql = " SELECT codcliente FROM creditosxclientes WHERE codcliente = ?";
		$stmt = $this->dbh->prepare($sql);
		$stmt->execute(array(limpiar($_POST["codcliente"])));
		$num = $stmt->rowCount();
		if($num == 0)
		{
			$query = "INSERT INTO creditosxclientes values (null, ?, ?);";
			$stmt = $this->dbh->prepare($query);
			$stmt->bindParam(1, $codcliente);
			$stmt->bindParam(2, $montocredito);

			$codcliente = limpiar($_POST["codcliente"]);
			$montocredito = number_format($_POST["PagoGeneral"]-$_POST["montoabono"], 2, '.', '');
			$stmt->execute();

		} else { 

			$sql = "UPDATE creditosxclientes set"
			." montocredito = ? "
			." where "
			." codcliente = ?;
			";
			$stmt = $this->dbh->prepare($sql);
			$stmt->bindParam(1, $montocredito);
			$stmt->bindParam(2, $codcliente);

			$montocredito = number_format($montoactual+($_POST["PagoGeneral"]-$_POST["montoabono"]), 2, '.', '');
			$codcliente = limpiar($_POST["codcliente"]);
			$stmt->execute();
		}

	} else if (limpiar($_POST["tipopago"]=="CREDITO" && $_POST["montoabono"]!="0.00" && $_POST["montoabono"]!="0")) { 

		$sql = "SELECT creditos, abonos FROM arqueocaja WHERE codcaja = '".limpiar($_POST["codcaja"])."' AND statusarqueo = '1'";
		foreach ($this->dbh->query($sql) as $row)
		{
			$this->p[] = $row;
		}
		$credito = ($row['creditos']== "" ? "0.00" : $row['creditos']);
		$abono = ($row['abonos']== "" ? "0.00" : $row['abonos']);

		$sql = " UPDATE arqueocaja SET "
		." creditos = ?, "
		." abonos = ? "
		." where "
		." codcaja = ? and statusarqueo = '1';
		";
		$stmt = $this->dbh->prepare($sql);
		$stmt->bindParam(1, $txtTotal);
		$stmt->bindParam(2, $totalabono);
		$stmt->bindParam(3, $codcaja);

		$txtTotal = number_format($_POST["PagoGeneral"]+$credito, 2, '.', '');
		$totalabono = number_format($_POST["montoabono"]+$abono, 2, '.', '');
		$codcaja = limpiar($_POST["codcaja"]);
		$stmt->execute();

		$query = "INSERT INTO abonoscreditos values (null, ?, ?, ?, ?, ?, ?); ";
		$stmt = $this->dbh->prepare($query);
		$stmt->bindParam(1, $codcaja);
		$stmt->bindParam(2, $codventa);
		$stmt->bindParam(3, $codcliente);
		$stmt->bindParam(4, $montoabono);
		$stmt->bindParam(5, $fechaabono);

		$codcaja = limpiar($_POST["codcaja"]);
		$codcliente = limpiar($_POST["codcliente"]);
		$montoabono = number_format($_POST["montoabono"], 2, '.', '');
		$fechaabono = limpiar($fecha);
		$stmt->execute();

		$sql = " SELECT codcliente FROM creditosxclientes WHERE codcliente = ?";
		$stmt = $this->dbh->prepare($sql);
		$stmt->execute(array(limpiar($_POST["codcliente"])));
		$num = $stmt->rowCount();
		if($num == 0)
		{
			$query = "INSERT INTO creditosxclientes values (null, ?, ?);";
			$stmt = $this->dbh->prepare($query);
			$stmt->bindParam(1, $codcliente);
			$stmt->bindParam(2, $montocredito);

			$codcliente = limpiar($_POST["codcliente"]);
			$montocredito = number_format($_POST["PagoGeneral"]-$_POST["montoabono"], 2, '.', '');
			$stmt->execute();

		} else { 

			$sql = "UPDATE creditosxclientes set"
			." montocredito = ? "
			." where "
			." codcliente = ?;
			";
			$stmt = $this->dbh->prepare($sql);
			$stmt->bindParam(1, $montocredito);
			$stmt->bindParam(2, $codcliente);

			$montocredito = number_format($montoactual+($_POST["PagoGeneral"]-$_POST["montoabono"]), 2, '.', '');
			$codcliente = limpiar($_POST["codcliente"]);
			$stmt->execute();
		}
	}
    ########## AGREGAMOS EL INGRESO Y ABONOS DE VENTAS A CREDITOS A CAJA ########

echo "<span class='fa fa-check-square-o'></span> LA MESA HA SIDO COBRADA EN CAJA EXITOSAMENTE <a href='reportepdf?codventa=".encrypt($codventa)."&tipo=".encrypt($tipodocumento)."' class='on-default' data-placement='left' data-toggle='tooltip' data-original-title='Imprimir Documento' target='_black' rel='noopener noreferrer'><font color='black'><strong>IMPRIMIR REPORTE</strong></font color></a></div>";

echo "<script>window.open('reportepdf?codventa=".encrypt($codventa)."&tipo=".encrypt($tipodocumento)."', '_blank');</script>";
	exit;

   }
}
############################ FUNCION CERRAR VENTA ############################

############################ FUNCION VER DETALLES PEDIDOS PARA TICKET #############################
public function CancelarPedidoMesa()
	{
		self::SetNames();

		#################### SELECCIONO LOS PRODUCTOS EN DETALLES VENTAS ####################
		$sql = "SELECT * FROM detalleventas WHERE codpedido = '".limpiar(decrypt($_GET["codpedido"]))."'";

	    foreach ($this->dbh->query($sql) as $row)
		{
			$this->p[] = $row;

			$codproductobd = $row['codproducto'];
			$cantidadbd = $row['cantventa'];
			$precioventabd = $row['precioventa'];
			$ivaproductobd = $row['ivaproducto'];
			$descproductobd = $row['descproducto'];


############################### PROCESO PARA VERIFICAR LOS PRODUCTOS ######################################		

			############## VERIFICO LA EXISTENCIA DEL PRODUCTO EN ALMACEN #################
			$sql2 = "SELECT existencia FROM productos WHERE codproducto = ?";
			$stmt = $this->dbh->prepare($sql2);
			$stmt->execute(array($codproductobd));
			$num = $stmt->rowCount();

			if($row = $stmt->fetch(PDO::FETCH_ASSOC))
			{
				$p[] = $row;
			}
			$existenciaproductobd = $row['existencia'];
			############## VERIFICO LA EXISTENCIA DEL PRODUCTO EN ALMACEN #################	

			########### ACTUALIZAMOS LA EXISTENCIA DE PRODUCTO EN ALMACEN ###############
			$sql = "UPDATE productos SET "
			." existencia = ? "
			." WHERE "
			." codproducto = ?;
			";
			$stmt = $this->dbh->prepare($sql);
			$stmt->bindParam(1, $existencia);
			$stmt->bindParam(2, $codproductobd);

			$existencia = limpiar($existenciaproductobd+$cantidadbd);
			$stmt->execute();
			########### ACTUALIZAMOS LA EXISTENCIA DE PRODUCTO EN ALMACEN #############

		    ########## REGISTRAMOS LOS DATOS DEL PRODUCTO ELIMINADO EN KARDEX ########
			$query = "INSERT INTO kardex_productos values (null, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?); ";
			$stmt = $this->dbh->prepare($query);
			$stmt->bindParam(1, $codpedido);
			$stmt->bindParam(2, $codcliente);
			$stmt->bindParam(3, $codproductobd);
			$stmt->bindParam(4, $movimiento);
			$stmt->bindParam(5, $entradas);
			$stmt->bindParam(6, $salidas);
			$stmt->bindParam(7, $devolucion);
			$stmt->bindParam(8, $stockactual);
			$stmt->bindParam(9, $ivaproducto);
			$stmt->bindParam(10, $descproducto);
			$stmt->bindParam(11, $precio);
			$stmt->bindParam(12, $documento);
			$stmt->bindParam(13, $fechakardex);		

		    $codpedido = limpiar(decrypt($_GET["codpedido"]));
			$codcliente = limpiar("0");
			$movimiento = limpiar("DEVOLUCION");
			$entradas= limpiar("0");
			$salidas = limpiar("0");
			$devolucion = limpiar($cantidadbd);
			$stockactual = limpiar($existenciaproductobd+$cantidadbd);
			$precio = limpiar($precioventabd);
			$ivaproducto = limpiar($ivaproductobd);
			$descproducto = limpiar($descproductobd);
			$documento = limpiar("DEVOLUCION PEDIDO EN MESA");
			$fechakardex = limpiar(date("Y-m-d"));
			$stmt->execute();
############################### PROCESO PARA VERIFICAR LOS PRODUCTOS ######################################



############################### PROCESO PARA VERIFICAR LOS INGREDIENTES ###################################	

			############## VERIFICO SI EL PRODUCTO TIENE INGREDIENTES RELACIONADOS #################
			$sql = "SELECT * FROM productosxingredientes WHERE codproducto = ?";
			$stmt = $this->dbh->prepare($sql);
			$stmt->execute(array(limpiar($codproductobd)));
			$num = $stmt->rowCount();
			if($num>0) {  

				$sql = "SELECT * FROM productosxingredientes LEFT JOIN ingredientes ON productosxingredientes.codingrediente = ingredientes.codingrediente WHERE productosxingredientes.codproducto IN ('".$codproductobd."')";
				foreach ($this->dbh->query($sql) as $row)
				{ 
					$this->p[] = $row;

					$cantracionbd = $row['cantracion'];
					$codingredientebd = $row['codingrediente'];
					$cantingredientebd = $row['cantingrediente'];
					$precioventaingredientebd = $row['precioventa'];
					$ivaingredientebd = $row['ivaingrediente'];
					$descingredientebd = $row['descingrediente'];

			   ############## ACTUALIZO LOS DATOS DEL INGREDIENTE #################
			   $update = "UPDATE ingredientes set "
			   ." cantingrediente = ? "
			   ." WHERE "
			   ." codingrediente = ?;
			   ";
			   $stmt = $this->dbh->prepare($update);
			   $stmt->bindParam(1, $cantidadracion);
			   $stmt->bindParam(2, $codingredientebd);

			   $racion = number_format($cantracionbd*$cantidadbd, 2, '.', '');
			   $cantidadracion = number_format($cantingredientebd+$racion, 2, '.', '');
			   $stmt->execute();
			   ############## ACTUALIZO LOS DATOS DEL INGREDIENTE #################

			   ############## REGISTRAMOS LOS DATOS DE INGREDIENTES EN KARDEX ###################
			   $query = "INSERT INTO kardex_ingredientes values (null, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?); ";
			   $stmt = $this->dbh->prepare($query);
			   $stmt->bindParam(1, $codpedido);
			   $stmt->bindParam(2, $codcliente);
			   $stmt->bindParam(3, $codingrediente);
			   $stmt->bindParam(4, $movimiento);
			   $stmt->bindParam(5, $entradas);
			   $stmt->bindParam(6, $salidas);
			   $stmt->bindParam(7, $devolucion);
			   $stmt->bindParam(8, $stockactual);
			   $stmt->bindParam(9, $ivaingrediente);
			   $stmt->bindParam(10, $descingrediente);
			   $stmt->bindParam(11, $precio);
			   $stmt->bindParam(12, $documento);
			   $stmt->bindParam(13, $fechakardex);		

			   $codpedido = limpiar(decrypt($_GET["codpedido"]));
			   $codcliente = limpiar("0");
			   $codingrediente = limpiar($codingredientebd);
			   $movimiento = limpiar("DEVOLUCION");
			   $entradas = limpiar("0");
			   $salidas= limpiar("0");
			   $devolucion = limpiar($racion);
			   $stockactual = number_format($cantidadracion, 2, '.', '');
			   $precio = limpiar($precioventaingredientebd);
			   $ivaingrediente = limpiar($ivaingredientebd);
			   $descingrediente = limpiar($descingredientebd);
			   $documento = limpiar("DEVOLUCION PEDIDO EN MESA");
			   $fechakardex = limpiar(date("Y-m-d"));
			   $stmt->execute();

		        }
		    }//fin de consulta de ingredientes de productos

############################### PROCESO PARA VERIFICAR LOS INGREDIENTES ###################################	

		}//fin de detalles ventas	


		
		#################### ELIMINAMOS EL PEDIDO EN VENTAS ####################
		$sql = "DELETE FROM ventas WHERE codpedido = ?";
		$stmt = $this->dbh->prepare($sql);
		$stmt->bindParam(1,$codpedido);
		$codpedido = decrypt($_GET["codpedido"]);
		$stmt->execute();
		#################### ELIMINAMOS EL PEDIDO EN VENTAS ####################

		#################### ELIMINAMOS EL PEDIDO EN DETALLES VENTAS ####################
		$sql = "DELETE FROM detalleventas WHERE codpedido = ?";
		$stmt = $this->dbh->prepare($sql);
		$stmt->bindParam(1,$codpedido);
		$codpedido = decrypt($_GET["codpedido"]);
		$stmt->execute();
		#################### ELIMINAMOS EL PEDIDO EN DETALLES VENTAS ####################


		#################### ELIMINAMOS EL PEDIDO EN DETALLES PEDIDOS ####################
		$sql = "DELETE FROM detallepedidos WHERE codpedido = ?";
		$stmt = $this->dbh->prepare($sql);
		$stmt->bindParam(1,$codpedido);
		$codpedido = decrypt($_GET["codpedido"]);
		$stmt->execute();
		#################### ELIMINAMOS EL PEDIDO EN DETALLES PEDIDOS ####################

		#################### ACTUALIZAMOS EL STATUS DE MESA ####################
		$sql = "UPDATE mesas set "
		." statusmesa = ? "
		." WHERE "
		." codmesa = ?;
		";
		$stmt = $this->dbh->prepare($sql);
		$stmt->bindParam(1, $statusmesa);
		$stmt->bindParam(2, $codmesa);

		$statusmesa = limpiar('0');
		$codmesa = decrypt($_GET["codmesa"]);
		$stmt->execute();
        #################### ACTUALIZAMOS EL STATUS DE MESA ####################
		
        echo "1";
        exit;
  }
########################### FUNCION VER DETALLES PEDIDOS PARA TICKET ###########################

############################ FUNCION VER DETALLES PEDIDOS EN VENTAS #############################
public function DetallesPedidoMesa()
	{
	self::SetNames();

	if (isset($_GET["tipo"]) && decrypt($_GET["tipo"])=="COMANDA") {

		$sql = "SELECT * 
		FROM ventas INNER JOIN detallepedidos ON detallepedidos.codpedido = ventas.codpedido 
		LEFT JOIN clientes ON ventas.codcliente = clientes.codcliente 
		LEFT JOIN mesas ON mesas.codmesa = ventas.codmesa 
		LEFT JOIN salas ON mesas.codsala = salas.codsala 
	    LEFT JOIN usuarios ON ventas.codigo = usuarios.codigo
	    WHERE detallepedidos.codpedido = ? AND detallepedidos.pedido = ?";
	$stmt = $this->dbh->prepare($sql);
	$stmt->execute(array(decrypt($_GET["codpedido"]),decrypt($_GET["pedido"])));
	$num = $stmt->rowCount();
	
	while($row = $stmt->fetch(PDO::FETCH_ASSOC))
	{
		$this->p[]=$row;
	}
		return $this->p;
		$this->dbh=null;

	} else if (isset($_GET["tipo"]) && decrypt($_GET["tipo"])=="PRECUENTA") {

	$sql = "SELECT ventas.idventa, ventas.codpedido, ventas.codcliente, ventas.codmesa, ventas.subtotalivasi, ventas.subtotalivano, ventas.iva, ventas.totaliva, ventas.descuento, ventas.totaldescuento, ventas.totalpago, ventas.delivery, ventas.repartidor, usuarios.nombres, clientes.dnicliente, clientes.nomcliente, salas.nomsala, mesas.nommesa, detallepedidos.pedido, detallepedidos.observacionespedido, detallepedidos.cocinero, 
	GROUP_CONCAT(cantventa, ' | ', substr(producto, 1,17) , ' | ', valorneto SEPARATOR '<br>') AS detalles, SUM(valorneto) AS suma 
	FROM ventas INNER JOIN detallepedidos ON detallepedidos.codpedido = ventas.codpedido 
	LEFT JOIN clientes ON ventas.codcliente = clientes.codcliente 
	LEFT JOIN mesas ON mesas.codmesa = ventas.codmesa 
	LEFT JOIN salas ON mesas.codsala = salas.codsala 
	LEFT JOIN usuarios ON ventas.codigo = usuarios.codigo 
	WHERE detallepedidos.codpedido = ? GROUP BY detallepedidos.codpedido, detallepedidos.pedido";
	$stmt = $this->dbh->prepare($sql);
	$stmt->execute(array(decrypt($_GET["codpedido"])));
	$num = $stmt->rowCount();
	
	while($row = $stmt->fetch(PDO::FETCH_ASSOC))
	{
		$this->p[]=$row;
	}
		return $this->p;
		$this->dbh=null;
	}
	 else {

	$sql = "SELECT * FROM mesas INNER JOIN ventas ON mesas.codmesa = ventas.codmesa INNER JOIN detallepedidos ON detallepedidos.codpedido = ventas.codpedido WHERE mesas.codmesa = ? and mesas.statusmesa = '1'";
		$stmt = $this->dbh->prepare($sql);
		$stmt->execute(array(decrypt($_GET["codmesa"])));
		$num = $stmt->rowCount();
	    if($num==0)
	    {
		echo "";
	    }
	   else
	   {
		while($row = $stmt->fetch(PDO::FETCH_ASSOC))
			{
				$this->p[]=$row;
			}
			return $this->p;
			$this->dbh=null;
		}
	}
}
########################### FUNCION VER DETALLES PEDIDOS EN VENTAS ###########################

######################## FUNCION ELIMINAR DETALLES DE PEDIDOS EN MESA #######################
public function EliminarDetallesPedidoMesa()
{
	
	self::SetNames();

		$sql = "SELECT * FROM detalleventas WHERE codpedido = ?";
		$stmt = $this->dbh->prepare($sql);
		$stmt->execute(array(decrypt($_GET["codpedido"])));
		$num = $stmt->rowCount();
		if($num > 1)
		{

			############## OBTENGO LOS DATOS DEL PEDIDO A ELIMINAR #################
			$sql = "SELECT cantventa, preciocompra, precioventa, ivaproducto, descproducto FROM detalleventas WHERE codpedido = ? AND codproducto = ?";
			$stmt = $this->dbh->prepare($sql);
			$stmt->execute(array(decrypt($_GET["codpedido"]),decrypt($_GET["codproducto"])));
			$num = $stmt->rowCount();

			if($row = $stmt->fetch(PDO::FETCH_ASSOC))
			{
				$p[] = $row;
			}
			$cantidadbd = $row['cantventa'];
			$preciocomprabd = $row['preciocompra'];
			$precioventabd = $row['precioventa'];
			$ivaproductobd = $row['ivaproducto']/100;
			$descproductobd = $row['descproducto']/100;


############################### PROCESO PARA VERIFICAR LOS PRODUCTOS ######################################		

			############## VERIFICO LA EXISTENCIA DEL PRODUCTO EN ALMACEN #################
			$sql2 = "SELECT existencia FROM productos WHERE codproducto = ?";
			$stmt = $this->dbh->prepare($sql2);
			$stmt->execute(array(decrypt($_GET["codproducto"])));
			$num = $stmt->rowCount();

			if($row = $stmt->fetch(PDO::FETCH_ASSOC))
			{
				$p[] = $row;
			}
			$existenciaproductobd = $row['existencia'];
			############## VERIFICO LA EXISTENCIA DEL PRODUCTO EN ALMACEN #################

			########### ACTUALIZAMOS LA EXISTENCIA DE PRODUCTO EN ALMACEN #############
			$sql = "UPDATE productos SET "
			." existencia = ? "
			." WHERE "
			." codproducto = ?;
			";
			$stmt = $this->dbh->prepare($sql);
			$stmt->bindParam(1, $existencia);
			$stmt->bindParam(2, $codproducto);

			$existencia = number_format($existenciaproductobd+$cantidadbd, 2, '.', '');
			$codproducto = limpiar(decrypt($_GET["codproducto"]));
			$stmt->execute();
			########### ACTUALIZAMOS LA EXISTENCIA DE PRODUCTO EN ALMACEN #############

		    ######## REGISTRAMOS LOS DATOS DEL PRODUCTO ELIMINADO EN KARDEX ###########
			$query = "INSERT INTO kardex_productos values (null, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?); ";
			$stmt = $this->dbh->prepare($query);
			$stmt->bindParam(1, $codpedido);
			$stmt->bindParam(2, $codcliente);
			$stmt->bindParam(3, $codproducto);
			$stmt->bindParam(4, $movimiento);
			$stmt->bindParam(5, $entradas);
			$stmt->bindParam(6, $salidas);
			$stmt->bindParam(7, $devolucion);
			$stmt->bindParam(8, $stockactual);
			$stmt->bindParam(9, $ivaproducto);
			$stmt->bindParam(10, $descproducto);
			$stmt->bindParam(11, $precio);
			$stmt->bindParam(12, $documento);
			$stmt->bindParam(13, $fechakardex);		

			$codpedido = limpiar(decrypt($_GET["codpedido"]));
			$codcliente = limpiar(decrypt($_GET["codcliente"]));
			$movimiento = limpiar("DEVOLUCION");
			$entradas= limpiar("0");
			$salidas = limpiar("0");
			$devolucion = limpiar($cantidadbd);
			$stockactual = limpiar($existenciaproductobd+$cantidadbd);
			$precio = limpiar($precioventabd);
			$ivaproducto = limpiar($ivaproductobd);
			$descproducto = limpiar($descproductobd);
			$documento = limpiar("DEVOLUCION PEDIDO EN MESA");
			$fechakardex = limpiar(date("Y-m-d"));
			$stmt->execute();

############################### PROCESO PARA VERIFICAR LOS PRODUCTOS ######################################	



############################### PROCESO PARA VERIFICAR LOS INGREDIENTES ###################################	

			############## VERIFICO SI EL PRODUCTO TIENE INGREDIENTES RELACIONADOS #################
			$sql = "SELECT * FROM productosxingredientes WHERE codproducto = ?";
			$stmt = $this->dbh->prepare($sql);
			$stmt->execute(array(limpiar(decrypt($_GET["codproducto"]))));
			$num = $stmt->rowCount();
			if($num>0) {  

				$sql = "SELECT * FROM productosxingredientes LEFT JOIN ingredientes ON productosxingredientes.codingrediente = ingredientes.codingrediente WHERE productosxingredientes.codproducto IN ('".decrypt($_GET["codproducto"])."')";
				foreach ($this->dbh->query($sql) as $row)
				{ 
					$this->p[] = $row;

					$cantracionbd = $row['cantracion'];
					$codingredientebd = $row['codingrediente'];
					$cantingredientebd = $row['cantingrediente'];
					$precioventaingredientebd = $row['precioventa'];
					$ivaingredientebd = $row['ivaingrediente'];
					$descingredientebd = $row['descingrediente'];

			   ############## ACTUALIZO LOS DATOS DEL INGREDIENTE #################
			   $update = "UPDATE ingredientes set "
			   ." cantingrediente = ? "
			   ." WHERE "
			   ." codingrediente = ?;
			   ";
			   $stmt = $this->dbh->prepare($update);
			   $stmt->bindParam(1, $cantidadracion);
			   $stmt->bindParam(2, $codingredientebd);

			   $racion = number_format($cantracionbd*$cantidadbd, 2, '.', '');
			   $cantidadracion = number_format($cantingredientebd+$racion, 2, '.', '');
			   $stmt->execute();

			   ############## REGISTRAMOS LOS DATOS DE INGREDIENTES EN KARDEX ###################
			   $query = "INSERT INTO kardex_ingredientes values (null, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?); ";
			   $stmt = $this->dbh->prepare($query);
			   $stmt->bindParam(1, $codpedido);
			   $stmt->bindParam(2, $codcliente);
			   $stmt->bindParam(3, $codingrediente);
			   $stmt->bindParam(4, $movimiento);
			   $stmt->bindParam(5, $entradas);
			   $stmt->bindParam(6, $salidas);
			   $stmt->bindParam(7, $devolucion);
			   $stmt->bindParam(8, $stockactual);
			   $stmt->bindParam(9, $ivaingrediente);
			   $stmt->bindParam(10, $descingrediente);
			   $stmt->bindParam(11, $precio);
			   $stmt->bindParam(12, $documento);
			   $stmt->bindParam(13, $fechakardex);		

			   $codpedido = limpiar(decrypt($_GET["codpedido"]));
			   $codcliente = limpiar(decrypt($_GET["codcliente"]));
			   $codingrediente = limpiar($codingredientebd);
			   $movimiento = limpiar("DEVOLUCION");
			   $entradas = limpiar("0");
			   $salidas= limpiar("0");
			   $devolucion = limpiar($racion);
			   $stockactual = number_format($cantingredientebd+$racion, 2, '.', '');
			   $precio = limpiar($precioventaingredientebd);
			   $ivaingrediente = limpiar($ivaingredientebd);
			   $descingrediente = limpiar($descingredientebd);
			   $documento = limpiar("DEVOLUCION PEDIDO EN MESA");
			   $fechakardex = limpiar(date("Y-m-d"));
			   $stmt->execute();

		        }
		    }//fin de consulta de ingredientes de productos	

############################### PROCESO PARA VERIFICAR LOS INGREDIENTES ###################################	

			$sql = "DELETE FROM detallepedidos WHERE codpedido = ? AND pedido = ? AND codproducto = ?";
			$stmt = $this->dbh->prepare($sql);
			$stmt->bindParam(1,$codpedido);
			$stmt->bindParam(2,$pedido);
			$stmt->bindParam(3,$codproducto);
			$codpedido = decrypt($_GET["codpedido"]);
			$pedido = decrypt($_GET["pedido"]);
			$codproducto = decrypt($_GET["codproducto"]);
			$stmt->execute();

			if($cantidadbd>decrypt($_GET["cantventa"])){

			$sql = "UPDATE detalleventas set "
			." cantventa = ?, "
			." valortotal = ?, "
			." totaldescuentov = ?, "
			." valorneto = ?, "
			." valorneto2 = ? "
			." WHERE "
			." codpedido = ? AND codproducto = ?;
			";
			$stmt = $this->dbh->prepare($sql);
			$stmt->bindParam(1, $cantidad);
			$stmt->bindParam(2, $valortotal);
			$stmt->bindParam(3, $totaldescuentov);
			$stmt->bindParam(4, $valorneto);
			$stmt->bindParam(5, $valorneto2);
			$stmt->bindParam(6, $codpedido);
			$stmt->bindParam(7, $codproducto);

			$cantidad = number_format($cantidadbd-decrypt($_GET["cantventa"]), 2, '.', '');
		    $valortotal = number_format($precioventabd * $cantidad, 2, '.', '');
		    $totaldescuentov = number_format($valortotal * $descuentobd, 2, '.', '');
		    $valorneto = number_format($valortotal - $totaldescuentov, 2, '.', '');
		    $valorneto2 = number_format($preciocomprabd * $cantidad, 2, '.', '');
			$codpedido = decrypt($_GET["codpedido"]);
			$codproducto = decrypt($_GET["codproducto"]);
			$stmt->execute();

			} else {

			$sql = "DELETE FROM detalleventas WHERE codpedido = ? AND codproducto = ?";
			$stmt = $this->dbh->prepare($sql);
			$stmt->bindParam(1,$codpedido);
			$stmt->bindParam(2,$codproducto);
			$codpedido = decrypt($_GET["codpedido"]);
			$codproducto = decrypt($_GET["codproducto"]);
			$stmt->execute();

			}

		    ############ CONSULTO LOS TOTALES DE VENTAS ##############
			$sql2 = "SELECT iva, descuento FROM ventas WHERE codpedido = ?";
			$stmt = $this->dbh->prepare($sql2);
			$stmt->execute(array(decrypt($_GET["codpedido"])));
			$num = $stmt->rowCount();

			if($row = $stmt->fetch(PDO::FETCH_ASSOC))
			{
				$paea[] = $row;
			}
			$iva = $paea[0]["iva"]/100;
			$descuento = $paea[0]["descuento"]/100;

            ############ SUMO LOS IMPORTE DE PRODUCTOS CON IVA ##############
			$sql3 = "SELECT SUM(valorneto) AS valorneto, SUM(valorneto2) AS valorneto2 FROM detalleventas WHERE codpedido = '".limpiar(decrypt($_GET["codpedido"]))."' AND ivaproducto = 'SI'";
			foreach ($this->dbh->query($sql3) as $row3)
			{
				$this->p[] = $row3;
			}
			$subtotalivasi = ($row3['valorneto']== "" ? "0.00" : $row3['valorneto']);
			$subtotalivasi2 = ($row3['valorneto2']== "" ? "0.00" : $row3['valorneto2']);

		    ############ SUMO LOS IMPORTE DE PRODUCTOS SIN IVA ##############
			$sql4 = "SELECT SUM(valorneto) AS valorneto, SUM(valorneto2) AS valorneto2 FROM detalleventas WHERE codpedido = '".limpiar(decrypt($_GET["codpedido"]))."' AND ivaproducto = 'NO'";
			foreach ($this->dbh->query($sql4) as $row4)
			{
				$this->p[] = $row4;
			}
			$subtotalivano = ($row4['valorneto']== "" ? "0.00" : $row4['valorneto']);
			$subtotalivano2 = ($row4['valorneto2']== "" ? "0.00" : $row4['valorneto2']);

            ############ ACTUALIZO LOS TOTALES EN LA COTIZACION ##############
			$sql = " UPDATE ventas SET "
			." subtotalivasi = ?, "
			." subtotalivano = ?, "
			." totaliva = ?, "
			." totaldescuento = ?, "
			." totalpago = ?, "
			." totalpago2= ? "
			." WHERE "
			." codpedido = ?;
			";
			$stmt = $this->dbh->prepare($sql);
			$stmt->bindParam(1, $subtotalivasi);
			$stmt->bindParam(2, $subtotalivano);
			$stmt->bindParam(3, $totaliva);
			$stmt->bindParam(4, $totaldescuento);
			$stmt->bindParam(5, $totalpago);
			$stmt->bindParam(6, $totalpago2);
			$stmt->bindParam(7, $codpedido);

			$totaliva= number_format($subtotalivasi*$iva, 2, '.', '');
			$total= number_format($subtotalivasi+$subtotalivano+$totaliva, 2, '.', '');
			$totaldescuento= number_format($total*$descuento, 2, '.', '');
			$totalpago= number_format($total-$totaldescuento, 2, '.', '');
			$totalpago2 = number_format($subtotalivasi2+$subtotalivano2, 2, '.', '');
			$codpedido = limpiar(decrypt($_GET["codpedido"]));
			$stmt->execute();
			
			echo "1";
			exit;

		} else {

			############## OBTENGO LOS DATOS DEL PEDIDO A ELIMINAR #################
			$sql = "SELECT cantventa, preciocompra, precioventa, ivaproducto, descproducto FROM detalleventas WHERE codpedido = ? AND codproducto = ?";
			$stmt = $this->dbh->prepare($sql);
			$stmt->execute(array(decrypt($_GET["codpedido"]),decrypt($_GET["codproducto"])));
			$num = $stmt->rowCount();

			if($row = $stmt->fetch(PDO::FETCH_ASSOC))
			{
				$p[] = $row;
			}
			$cantidadbd = $row['cantventa'];
			$preciocomprabd = $row['preciocompra'];
			$precioventabd = $row['precioventa'];
			$ivaproductobd = $row['ivaproducto']/100;
			$descproductobd = $row['descproducto']/100;

############################### PROCESO PARA VERIFICAR LOS PRODUCTOS ######################################		

			############## VERIFICO LA EXISTENCIA DEL PRODUCTO EN ALMACEN #################
			$sql2 = "SELECT existencia FROM productos WHERE codproducto = ?";
			$stmt = $this->dbh->prepare($sql2);
			$stmt->execute(array(decrypt($_GET["codproducto"])));
			$num = $stmt->rowCount();

			if($row = $stmt->fetch(PDO::FETCH_ASSOC))
			{
				$p[] = $row;
			}
			$existenciaproductobd = $row['existencia'];
			############## VERIFICO LA EXISTENCIA DEL PRODUCTO EN ALMACEN #################

			########### ACTUALIZAMOS LA EXISTENCIA DE PRODUCTO EN ALMACEN #############
			$sql = "UPDATE productos SET "
			." existencia = ? "
			." WHERE "
			." codproducto = ?;
			";
			$stmt = $this->dbh->prepare($sql);
			$stmt->bindParam(1, $existencia);
			$stmt->bindParam(2, $codproducto);

			$existencia = number_format($existenciaproductobd+$cantidadbd, 2, '.', '');
			$codproducto = limpiar(decrypt($_GET["codproducto"]));
			$stmt->execute();
			########### ACTUALIZAMOS LA EXISTENCIA DE PRODUCTO EN ALMACEN #############

		    ######## REGISTRAMOS LOS DATOS DEL PRODUCTO ELIMINADO EN KARDEX ###########
			$query = "INSERT INTO kardex_productos values (null, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?); ";
			$stmt = $this->dbh->prepare($query);
			$stmt->bindParam(1, $codpedido);
			$stmt->bindParam(2, $codcliente);
			$stmt->bindParam(3, $codproducto);
			$stmt->bindParam(4, $movimiento);
			$stmt->bindParam(5, $entradas);
			$stmt->bindParam(6, $salidas);
			$stmt->bindParam(7, $devolucion);
			$stmt->bindParam(8, $stockactual);
			$stmt->bindParam(9, $ivaproducto);
			$stmt->bindParam(10, $descproducto);
			$stmt->bindParam(11, $precio);
			$stmt->bindParam(12, $documento);
			$stmt->bindParam(13, $fechakardex);		

			$codpedido = limpiar(decrypt($_GET["codpedido"]));
			$codcliente = limpiar(decrypt($_GET["codcliente"]));
			$movimiento = limpiar("DEVOLUCION");
			$entradas= limpiar("0");
			$salidas = limpiar("0");
			$devolucion = limpiar($cantidadbd);
			$stockactual = limpiar($existenciaproductobd+$cantidadbd);
			$precio = limpiar($precioventabd);
			$ivaproducto = limpiar($ivaproductobd);
			$descproducto = limpiar($descproductobd);
			$documento = limpiar("DEVOLUCION PEDIDO EN MESA");
			$fechakardex = limpiar(date("Y-m-d"));
			$stmt->execute();

############################### PROCESO PARA VERIFICAR LOS PRODUCTOS ######################################	



############################### PROCESO PARA VERIFICAR LOS INGREDIENTES ###################################	

			############## VERIFICO SI EL PRODUCTO TIENE INGREDIENTES RELACIONADOS #################
			$sql = "SELECT * FROM productosxingredientes WHERE codproducto = ?";
			$stmt = $this->dbh->prepare($sql);
			$stmt->execute(array(limpiar(decrypt($_GET["codproducto"]))));
			$num = $stmt->rowCount();
			if($num>0) {  

				$sql = "SELECT * FROM productosxingredientes LEFT JOIN ingredientes ON productosxingredientes.codingrediente = ingredientes.codingrediente WHERE productosxingredientes.codproducto IN ('".decrypt($_GET["codproducto"])."')";
				foreach ($this->dbh->query($sql) as $row)
				{ 
					$this->p[] = $row;

					$cantracionbd = $row['cantracion'];
					$codingredientebd = $row['codingrediente'];
					$cantingredientebd = $row['cantingrediente'];
					$precioventaingredientebd = $row['precioventa'];
					$ivaingredientebd = $row['ivaingrediente'];
					$descingredientebd = $row['descingrediente'];

			   ############## ACTUALIZO LOS DATOS DEL INGREDIENTE #################
			   $update = "UPDATE ingredientes set "
			   ." cantingrediente = ? "
			   ." WHERE "
			   ." codingrediente = ?;
			   ";
			   $stmt = $this->dbh->prepare($update);
			   $stmt->bindParam(1, $cantidadracion);
			   $stmt->bindParam(2, $codingredientebd);

			   $racion = number_format($cantracionbd*$cantidadbd, 2, '.', '');
			   $cantidadracion = number_format($cantingredientebd+$racion, 2, '.', '');
			   $stmt->execute();

			   ############## REGISTRAMOS LOS DATOS DE INGREDIENTES EN KARDEX ###################
			   $query = "INSERT INTO kardex_ingredientes values (null, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?); ";
			   $stmt = $this->dbh->prepare($query);
			   $stmt->bindParam(1, $codpedido);
			   $stmt->bindParam(2, $codcliente);
			   $stmt->bindParam(3, $codingrediente);
			   $stmt->bindParam(4, $movimiento);
			   $stmt->bindParam(5, $entradas);
			   $stmt->bindParam(6, $salidas);
			   $stmt->bindParam(7, $devolucion);
			   $stmt->bindParam(8, $stockactual);
			   $stmt->bindParam(9, $ivaingrediente);
			   $stmt->bindParam(10, $descingrediente);
			   $stmt->bindParam(11, $precio);
			   $stmt->bindParam(12, $documento);
			   $stmt->bindParam(13, $fechakardex);		

			   $codpedido = limpiar(decrypt($_GET["codpedido"]));
			   $codcliente = limpiar(decrypt($_GET["codcliente"]));
			   $codingrediente = limpiar($codingredientebd);
			   $movimiento = limpiar("DEVOLUCION");
			   $entradas = limpiar("0");
			   $salidas= limpiar("0");
			   $devolucion = limpiar($racion);
			   $stockactual = number_format($cantingredientebd+$racion, 2, '.', '');
			   $precio = limpiar($precioventaingredientebd);
			   $ivaingrediente = limpiar($ivaingredientebd);
			   $descingrediente = limpiar($descingredientebd);
			   $documento = limpiar("DEVOLUCION PEDIDO EN MESA");
			   $fechakardex = limpiar(date("Y-m-d"));
			   $stmt->execute();

		        }
		    }//fin de consulta de ingredientes de productos	

############################### PROCESO PARA VERIFICAR LOS INGREDIENTES ###################################	


		    #################### ACTUALIZAMOS EL STATUS DE MESA ####################
			$sql = "UPDATE mesas set "
			." statusmesa = ? "
			." WHERE "
			." codmesa = ?;
			";
			$stmt = $this->dbh->prepare($sql);
			$stmt->bindParam(1, $statusmesa);
			$stmt->bindParam(2, $codmesa);

			$statusmesa = limpiar('0');
			$codmesa = decrypt($_GET["codmesa"]);
			$stmt->execute();
            #################### ACTUALIZAMOS EL STATUS DE MESA ####################

			$sql = "DELETE FROM detallepedidos WHERE codpedido = ? AND pedido = ?";
			$stmt = $this->dbh->prepare($sql);
			$stmt->bindParam(1,$codpedido);
			$stmt->bindParam(2,$pedido);
			$codpedido = decrypt($_GET["codpedido"]);
			$pedido = decrypt($_GET["pedido"]);
			$stmt->execute();
			
			$sql = "DELETE FROM detalleventas WHERE codpedido = ? AND codproducto = ?";
			$stmt = $this->dbh->prepare($sql);
			$stmt->bindParam(1,$codpedido);
			$stmt->bindParam(2,$codproducto);
			$codpedido = decrypt($_GET["codpedido"]);
			$codproducto = decrypt($_GET["codproducto"]);
			$stmt->execute();
			
			$sql = "DELETE FROM ventas WHERE codpedido = ?";
			$stmt = $this->dbh->prepare($sql);
			$stmt->bindParam(1,$codpedido);
			$codpedido = decrypt($_GET["codpedido"]);
			$stmt->execute();

			echo "1";
			exit;
	}	
}
##################### FUNCION ELIMINAR DETALLES DE PEDIDOS EN MESA #################################

############################ FUNCION REGISTRAR DELIVERY ##############################
	public function RegistrarDelivery()
	{
		self::SetNames();

		$sql = "SELECT * FROM arqueocaja INNER JOIN cajas ON arqueocaja.codcaja = cajas.codcaja INNER JOIN usuarios ON cajas.codigo = usuarios.codigo WHERE usuarios.codigo = ?";
		$stmt = $this->dbh->prepare($sql);
		$stmt->execute(array($_SESSION["codigo"]));
		$num = $stmt->rowCount();
		if($num==0)
		{
			echo "1";
			exit;
	    }
	    else if(empty($_POST["tipodocumento"]) or empty($_POST["tipopago"]))
		{
			echo "2";
			exit;
		}
	    else if(limpiar($_POST["tipopedido"]=="EXTERNO")  && limpiar($_POST["repartidor"] == ''))
		{
			echo "3";
			exit;
		}
		elseif(empty($_SESSION["CarritoVenta"]) || $_POST["txtTotal"]=="0.00")
		{
			echo "4";
			exit;
			
		}

		################### SELECCIONE LOS DATOS DEL CLIENTE ######################
	    $sql = "SELECT
	    clientes.nomcliente, 
	    clientes.emailcliente, 
	    clientes.limitecredito,
	    ROUND(SUM(if(pag.montocredito!='0',pag.montocredito,'0.00')), 2) montoactual,
	    ROUND(SUM(if(pag.montocredito!='0',clientes.limitecredito-pag.montocredito,clientes.limitecredito)), 2) creditodisponible
        FROM clientes 
        LEFT JOIN
           (SELECT
           codcliente, montocredito       
           FROM creditosxclientes) pag ON pag.codcliente = clientes.codcliente
           WHERE clientes.codcliente = ?";
		$stmt = $this->dbh->prepare($sql);
		$stmt->execute(array(limpiar($_POST['codcliente'])));
		$num = $stmt->rowCount();
		if($row = $stmt->fetch(PDO::FETCH_ASSOC))
		{
			$p[] = $row;
		}
        $nomcliente = $row['nomcliente'];
        $emailcliente = $row['emailcliente'];
        $limitecredito = $row['limitecredito'];
        $montoactual = $row['montoactual'];
        $creditodisponible = $row['creditodisponible'];
        $montoabono = (empty($_POST["montoabono"]) ? "0.00" : $_POST["montoabono"]);
        $total = number_format($_POST["txtTotal"]-$montoabono, 2, '.', '');
		
		if (limpiar(isset($_POST['fechavencecredito']))) {  

			$fechaactual = date("Y-m-d");
			$fechavence = date("Y-m-d",strtotime($_POST['fechavencecredito']));

			if (strtotime($fechavence) < strtotime($fechaactual)) {

				echo "5";
				exit;
			}
		}

		if ($_POST["tipopago"] == "CREDITO" && $_POST["codcliente"] == '0') { 

		        echo "6";
		        exit;

	    } else if ($_POST["tipopago"] == "CREDITO") {

		    if ($limitecredito != "0.00" && $total > $creditodisponible) {
	  
	           echo "7";
		       exit;

	        } 
	    }

	    if($_POST["tipopago"]=="CREDITO" && $_POST["montoabono"] >= $_POST["txtTotal"])
		{
			echo "8";
			exit;
			
		} else {

		############ VALIDO SI LA CANTIDAD ES MAYOR QUE LA EXISTENCIA ############
		$v = $_SESSION["CarritoVenta"];
		for($i=0;$i<count($v);$i++){

		    $sql = "SELECT existencia FROM productos WHERE codproducto = '".$v[$i]['txtCodigo']."'";
		    foreach ($this->dbh->query($sql) as $row)
		    {
			$this->p[] = $row;
		    }
		
		    $existenciadb = $row['existencia'];
		    $cantidad = $v[$i]['cantidad'];

            if ($cantidad > $existenciadb) 
            { 
		       echo "9";
		       exit;
	        }
		}

		################ CREO EL CODIGO DE BANDERA PARA NUMERO DE VENTA ################
		$sql = " SELECT bandera from ventas WHERE statuspago = 0 ORDER BY bandera DESC LIMIT 1";
		foreach ($this->dbh->query($sql) as $row){

	    $idbandera=$row["bandera"];

	    }
		if(empty($idbandera)){

			$bandera = '1';

		} else {
			$num     = $idbandera + 1;
			$bandera = $num;
		}
		################ CREO EL CODIGO DE BANDERA PARA NUMERO DE VENTA ################

		##################### AGREGAMOS EL NUMERO DE PEDIDO A LA VENTA #######################
		$sql = "SELECT codpedido FROM ventas ORDER BY idventa DESC LIMIT 1";
		foreach ($this->dbh->query($sql) as $row){

			$id=$row["codpedido"];

		}
		if(empty($id))
		{
			$codpedido = "P1";

		} else {

			$resto = substr($id, 0, 1);
			$coun = strlen($resto);
			$num     = substr($id, $coun);
			$codigo     = $num + 1;
			$codpedido = "P".$codigo;
		}
		##################### AGREGAMOS EL NUMERO DE PEDIDO A LA VENTA #######################

		################ CREO LOS CODIGO VENTA-SERIE-AUTORIZACION ################
		$sql = " SELECT nroactividad, iniciofactura FROM configuracion";
		foreach ($this->dbh->query($sql) as $row)
		{
			$this->p[] = $row;
		}
		$nroactividad = $row['nroactividad'];
		$iniciofactura = $row['iniciofactura'];
		
		$sql = "SELECT codventa FROM ventas WHERE statuspago = 0 ORDER BY bandera DESC LIMIT 1";
		foreach ($this->dbh->query($sql) as $row){

			$venta=$row["codventa"];

		}
		if(empty($venta))
		{
			$codventa = $nroactividad.'-'.$iniciofactura;
			$codserie = $nroactividad;
			$codautorizacion = limpiar(GenerateRandomStringg());

		} else {

			$var = strlen($nroactividad."-");
            $var1 = substr($venta , $var);
            $var2 = strlen($var1);
            $var3 = $var1 + 1;
            $var4 = str_pad($var3, $var2, "0", STR_PAD_LEFT);
            $codventa = $nroactividad.'-'.$var4;
			$codserie = $nroactividad;
			$codautorizacion = limpiar(GenerateRandomStringg());
		}
        ################# CREO LOS CODIGO VENTA-SERIE-AUTORIZACION ###############

        $fecha = date("Y-m-d h:i:s");

        $query = "INSERT INTO ventas values (null, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);";
		$stmt = $this->dbh->prepare($query);
		$stmt->bindParam(1, $codpedido);
		$stmt->bindParam(2, $codmesa);
		$stmt->bindParam(3, $tipodocumento);
		$stmt->bindParam(4, $codcaja);
		$stmt->bindParam(5, $codventa);
		$stmt->bindParam(6, $codserie);
		$stmt->bindParam(7, $codautorizacion);
		$stmt->bindParam(8, $codcliente);
		$stmt->bindParam(9, $subtotalivasi);
		$stmt->bindParam(10, $subtotalivano);
		$stmt->bindParam(11, $iva);
		$stmt->bindParam(12, $totaliva);
		$stmt->bindParam(13, $descuento);
		$stmt->bindParam(14, $totaldescuento);
		$stmt->bindParam(15, $totalpago);
		$stmt->bindParam(16, $totalpago2);
		$stmt->bindParam(17, $tipopago);
		$stmt->bindParam(18, $formapago);
		$stmt->bindParam(19, $montopagado);
		$stmt->bindParam(20, $montopropina);
		$stmt->bindParam(21, $montodevuelto);
		$stmt->bindParam(22, $fechavencecredito);
		$stmt->bindParam(23, $fechapagado);
		$stmt->bindParam(24, $statusventa);
		$stmt->bindParam(25, $statuspago);
		$stmt->bindParam(26, $fechaventa);
		$stmt->bindParam(27, $delivery);
		$stmt->bindParam(28, $repartidor);
		$stmt->bindParam(29, $entregado);
		$stmt->bindParam(30, $observaciones);
		$stmt->bindParam(31, $codigo);
		$stmt->bindParam(32, $bandera);
	    
		$codmesa = limpiar("0");
		$tipodocumento = limpiar($_POST["tipodocumento"]);
		$codcaja = limpiar($_POST["codcaja"]);
		$codcliente = limpiar($_POST["codcliente"]);
		$subtotalivasi = limpiar($_POST["txtsubtotal"]);
		$subtotalivano = limpiar($_POST["txtsubtotal2"]);
		$iva = limpiar($_POST["iva"]);
		$totaliva = limpiar($_POST["txtIva"]);
		$descuento = limpiar($_POST["descuento"]);
		$totaldescuento = limpiar($_POST["txtDescuento"]);
		$totalpago = limpiar($_POST["txtTotal"]);
		$totalpago2 = limpiar($_POST["txtTotalCompra"]);
		$tipopago = limpiar($_POST["tipopago"]);
	if (limpiar($_POST["tipopago"]=="CONTADO")) { $formapago = limpiar(decrypt($_POST["codmediopago"])); } else { $formapago = "CREDITO"; }
	if (limpiar(isset($_POST['montopagado']))) { $montopagado = limpiar($_POST['montopagado']); } else { $montopagado ='0.00'; }

	if (limpiar(isset($_POST['montopropina']))) { $montopropina = limpiar($_POST['montopropina']); } else { $montopropina ='0.00'; }
	if (limpiar(isset($_POST['montodevuelto']))) { $montodevuelto = limpiar($_POST['montodevuelto']); } else { $montodevuelto ='0.00'; }
	if (limpiar(isset($_POST['fechavencecredito']))) { $fechavencecredito = limpiar(date("Y-m-d",strtotime($_POST['fechavencecredito']))); } else { $fechavencecredito ='0000-00-00'; }
	    $fechapagado = limpiar("0000-00-00");
	if (limpiar($_POST["tipopago"]=="CONTADO")) { $statusventa = limpiar("PAGADA"); } else { $statusventa = "PENDIENTE"; }
	    $statuspago = limpiar("0");
        $fechaventa = limpiar($fecha);
		$delivery = limpiar("1");
if (limpiar(isset($_POST['repartidor']))) { $repartidor = limpiar(decrypt($_POST['repartidor'])); } else { $repartidor ='0'; }
if (limpiar(isset($_POST['repartidor']) && decrypt($_POST['repartidor'])!=0)) { $entregado = limpiar("1"); } else { $entregado ='0'; }
if (limpiar($_POST["observaciones"]!="")) { $observaciones = limpiar($_POST['observaciones']); } else { $observaciones =limpiar('0'); }
		
		$codigo = limpiar($_SESSION["codigo"]);
		$stmt->execute();


		$this->dbh->beginTransaction();
		$detalle = $_SESSION["CarritoVenta"];
		for($i=0;$i<count($detalle);$i++){

		$query = "INSERT INTO detalleventas values (null, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?); ";
		$stmt = $this->dbh->prepare($query);
		$stmt->bindParam(1, $codpedido);
		$stmt->bindParam(2, $codventa);
	    $stmt->bindParam(3, $codproducto);
	    $stmt->bindParam(4, $producto);
	    $stmt->bindParam(5, $codcategoria);
		$stmt->bindParam(6, $cantidad);
		$stmt->bindParam(7, $preciocompra);
		$stmt->bindParam(8, $precioventa);
		$stmt->bindParam(9, $ivaproducto);
		$stmt->bindParam(10, $descproducto);
		$stmt->bindParam(11, $valortotal);
		$stmt->bindParam(12, $totaldescuentov);
		$stmt->bindParam(13, $valorneto);
		$stmt->bindParam(14, $valorneto2);
		
		$codproducto = limpiar($detalle[$i]['txtCodigo']);
		$producto = limpiar($detalle[$i]['producto']);
		$codcategoria = limpiar($detalle[$i]['codcategoria']);
		$cantidad = limpiar($detalle[$i]['cantidad']);
		$preciocompra = limpiar($detalle[$i]['precio']);
		$precioventa = limpiar($detalle[$i]['precio2']);
		$ivaproducto = limpiar($detalle[$i]['ivaproducto']);
		$descproducto = limpiar($detalle[$i]['descproducto']);
		$descuento = $detalle[$i]['descproducto']/100;
		$valortotal = number_format($detalle[$i]['precio2']*$detalle[$i]['cantidad'], 2, '.', '');
		$totaldescuentov = number_format($valortotal*$descuento, 2, '.', '');
	    $valorneto = number_format($valortotal-$totaldescuentov, 2, '.', '');
		$valorneto2 = number_format($detalle[$i]['precio']*$detalle[$i]['cantidad'], 2, '.', '');
		$stmt->execute();

		$query = "INSERT INTO detallepedidos values (null, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);";
		$stmt = $this->dbh->prepare($query);
		$stmt->bindParam(1, $codpedido);
		$stmt->bindParam(2, $pedido);
		$stmt->bindParam(3, $codproducto);
		$stmt->bindParam(4, $producto);
		$stmt->bindParam(5, $codcategoria);
		$stmt->bindParam(6, $cantidad);
		$stmt->bindParam(7, $precioventa);
		$stmt->bindParam(8, $ivaproducto);
		$stmt->bindParam(9, $descproducto);
		$stmt->bindParam(10, $valortotal);
		$stmt->bindParam(11, $totaldescuentov);
		$stmt->bindParam(12, $valorneto);
		$stmt->bindParam(13, $observacionespedido);
		$stmt->bindParam(14, $cocinero);

		$pedido = limpiar("1");
		$codproducto = limpiar($detalle[$i]['txtCodigo']);
		$producto = limpiar($detalle[$i]['producto']);
		$codcategoria = limpiar($detalle[$i]['codcategoria']);
		$cantidad = limpiar($detalle[$i]['cantidad']);
		$precioventa = limpiar($detalle[$i]['precio2']);
		$ivaproducto = limpiar($detalle[$i]['ivaproducto']);
		$descproducto = limpiar($detalle[$i]['descproducto']);
		$descuento = $detalle[$i]['descproducto']/100;
		$valortotal = number_format($detalle[$i]['precio2']*$detalle[$i]['cantidad'], 2, '.', '');
		$totaldescuentov = number_format($valortotal*$descuento, 2, '.', '');
	    $valorneto = number_format($valortotal-$totaldescuentov, 2, '.', '');
	    if (limpiar($_POST['observacionespedido']!="")) { $observacionespedido = limpiar($_POST['observacionespedido']); } else { $observacionespedido ='0'; }
		$cocinero = limpiar('1');
		$stmt->execute();

############################### PROCESO PARA VERIFICAR LOS PRODUCTOS ######################################		

		############## VERIFICO LA EXISTENCIA DEL PRODUCTO EN ALMACEN #################
		$sql = "SELECT existencia FROM productos WHERE codproducto = '".limpiar($detalle[$i]['txtCodigo'])."'";
		foreach ($this->dbh->query($sql) as $row)
		{
			$this->p[] = $row;
		}
		$existenciaproductobd = $row['existencia'];

	    ##################### ACTUALIZO LA EXISTENCIA DEL ALMACEN ####################
		$sql = " UPDATE productos set "
			  ." existencia = ? "
			  ." where "
			  ." codproducto = '".limpiar($detalle[$i]['txtCodigo'])."';
			   ";
		$stmt = $this->dbh->prepare($sql);
		$stmt->bindParam(1, $existencia);
		$cantventa = limpiar($detalle[$i]['cantidad']);
		$existencia = number_format($existenciaproductobd-$cantventa, 2, '.', '');
		$stmt->execute();

		############## REGISTRAMOS LOS DATOS DE PRODUCTOS EN KARDEX ###################
        $query = "INSERT INTO kardex_productos values (null, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?); ";
		$stmt = $this->dbh->prepare($query);
		$stmt->bindParam(1, $codpedido);
		$stmt->bindParam(2, $codcliente);
		$stmt->bindParam(3, $codproducto);
		$stmt->bindParam(4, $movimiento);
		$stmt->bindParam(5, $entradas);
		$stmt->bindParam(6, $salidas);
		$stmt->bindParam(7, $devolucion);
		$stmt->bindParam(8, $stockactual);
		$stmt->bindParam(9, $ivaproducto);
		$stmt->bindParam(10, $descproducto);
		$stmt->bindParam(11, $precio);
		$stmt->bindParam(12, $documento);
		$stmt->bindParam(13, $fechakardex);		

		$codcliente = limpiar($_POST["codcliente"]);
		$codproducto = limpiar($detalle[$i]['txtCodigo']);
		$movimiento = limpiar("SALIDAS");
		$entradas = limpiar("0");
		$salidas= limpiar($detalle[$i]['cantidad']);
		$devolucion = limpiar("0");
		$stockactual = number_format($existenciaproductobd-$detalle[$i]['cantidad'], 2, '.', '');
		$precio = limpiar($detalle[$i]['precio2']);
		$ivaproducto = limpiar($detalle[$i]['ivaproducto']);
		$descproducto = limpiar($detalle[$i]['descproducto']);
		$documento = limpiar("PEDIDO EN VENTA");
		$fechakardex = limpiar(date("Y-m-d"));
		$stmt->execute();

############################### PROCESO PARA VERIFICAR LOS PRODUCTOS ######################################	



############################### PROCESO PARA VERIFICAR LOS INGREDIENTES ######################################	

	    ############## VERIFICO SSI EL PRODUCTO TIENE INGREDIENTES RELACIONADOS #################
	    $sql = "SELECT * FROM productosxingredientes WHERE codproducto = ?";
		$stmt = $this->dbh->prepare($sql);
		$stmt->execute(array(limpiar($detalle[$i]['txtCodigo'])));
		$num = $stmt->rowCount();
        if($num>0) {  

        	$sql = "SELECT * FROM productosxingredientes LEFT JOIN ingredientes ON productosxingredientes.codingrediente = ingredientes.codingrediente WHERE productosxingredientes.codproducto IN ('".$detalle[$i]['txtCodigo']."')";
        	foreach ($this->dbh->query($sql) as $row)
		    { 
			   $this->p[] = $row;

			   //$codproducto = $row['codproducto'];
			   $cantracionbd = $row['cantracion'];
			   $codingredientebd = $row['codingrediente'];
			   $cantingredientebd = $row['cantingrediente'];
			   $precioventaingredientebd = $row['precioventa'];
			   $ivaingredientebd = $row['ivaingrediente'];
			   $descingredientebd = $row['descingrediente'];

			   ############## ACTUALIZO LOS DATOS DEL INGREDIENTE #################
			   $update = "UPDATE ingredientes set "
			   ." cantingrediente = ? "
			   ." WHERE "
			   ." codingrediente = ?;
			   ";
			   $stmt = $this->dbh->prepare($update);
			   $stmt->bindParam(1, $cantidadracion);
			   $stmt->bindParam(2, $codingredientebd);

			   $racion = number_format($cantracionbd*$detalle[$i]['cantidad'], 2, '.', '');
			   $cantidadracion = number_format($cantingredientebd-$racion, 2, '.', '');
			   $stmt->execute();

			   ############## REGISTRAMOS LOS DATOS DE PRODUCTOS EN KARDEX ###################
			   $query = "INSERT INTO kardex_ingredientes values (null, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?); ";
			   $stmt = $this->dbh->prepare($query);
			   $stmt->bindParam(1, $codpedido);
			   $stmt->bindParam(2, $codcliente);
			   $stmt->bindParam(3, $codingrediente);
			   $stmt->bindParam(4, $movimiento);
			   $stmt->bindParam(5, $entradas);
			   $stmt->bindParam(6, $salidas);
			   $stmt->bindParam(7, $devolucion);
			   $stmt->bindParam(8, $stockactual);
			   $stmt->bindParam(9, $ivaingrediente);
			   $stmt->bindParam(10, $descingrediente);
			   $stmt->bindParam(11, $precio);
			   $stmt->bindParam(12, $documento);
			   $stmt->bindParam(13, $fechakardex);		

			   $codcliente = limpiar($_POST["codcliente"]);
			   $codingrediente = limpiar($codingredientebd);
			   $movimiento = limpiar("SALIDAS");
			   $entradas = limpiar("0");
			   $salidas= limpiar($racion);
			   $devolucion = limpiar("0");
			   $stockactual = number_format($cantingredientebd-$racion, 2, '.', '');
			   $precio = limpiar($precioventaingredientebd);
			   $ivaingrediente = limpiar($ivaingredientebd);
			   $descingrediente = limpiar($descingredientebd);
			   $documento = limpiar("PEDIDO EN VENTA");
			   $fechakardex = limpiar(date("Y-m-d"));
			   $stmt->execute();
		    }

		  }//fin de consulta de ingredientes de productos	

############################### PROCESO PARA VERIFICAR LOS INGREDIENTES ######################################
      }
		
	####################### DESTRUYO LA VARIABLE DE SESSION #####################
	unset($_SESSION["CarritoVenta"]);
    $this->dbh->commit();

    ############## AGREGAMOS EL INGRESO DE VENTAS PAGADAS A CAJA ###############
	if (limpiar($_POST["tipopago"]=="CONTADO")){

		$sql = "SELECT ingresos FROM arqueocaja WHERE codcaja = '".limpiar($_POST["codcaja"])."' AND statusarqueo = '1'";
		foreach ($this->dbh->query($sql) as $row)
		{
			$this->p[] = $row;
		}
		$ingreso = ($row['ingresos']== "" ? "0.00" : $row['ingresos']);

		$sql = "UPDATE arqueocaja set "
		." ingresos = ? "
		." WHERE "
		." codcaja = ? AND statusarqueo = '1';
		";
		$stmt = $this->dbh->prepare($sql);
		$stmt->bindParam(1, $txtTotal);
		$stmt->bindParam(2, $codcaja);

		$txtTotal = number_format($_POST["txtTotal"]+$ingreso, 2, '.', '');
		$codcaja = limpiar($_POST["codcaja"]);
		$stmt->execute();
	}
    ################ AGREGAMOS EL INGRESO DE VENTAS PAGADAS A CAJA ##############

    ######### AGREGAMOS EL INGRESO Y ABONOS DE VENTAS A CREDITOS A CAJA ############
	if (limpiar($_POST["tipopago"]=="CREDITO" && $_POST["montoabono"]=="0.00" && $_POST["montoabono"]=="0")) {

		$sql = "SELECT creditos FROM arqueocaja WHERE codcaja = '".$_POST["codcaja"]."' AND statusarqueo = '1'";
		foreach ($this->dbh->query($sql) as $row)
		{
			$this->p[] = $row;
		}
		$credito = ($row['creditos']== "" ? "0.00" : $row['creditos']);

		$sql = " UPDATE arqueocaja SET "
		." creditos = ? "
		." where "
		." codcaja = ? and statusarqueo = '1';
		";
		$stmt = $this->dbh->prepare($sql);
		$stmt->bindParam(1, $txtTotal);
		$stmt->bindParam(2, $codcaja);

		$txtTotal = number_format($_POST["txtTotal"]+$credito, 2, '.', '');
		$codcaja = limpiar($_POST["codcaja"]);
		$stmt->execute(); 

		$sql = " SELECT codcliente FROM creditosxclientes WHERE codcliente = ?";
		$stmt = $this->dbh->prepare($sql);
		$stmt->execute(array($_POST["codcliente"]));
		$num = $stmt->rowCount();
		if($num == 0)
		{
			$query = "INSERT INTO creditosxclientes values (null, ?, ?);";
			$stmt = $this->dbh->prepare($query);
			$stmt->bindParam(1, $codcliente);
			$stmt->bindParam(2, $montocredito);

			$codcliente = limpiar($_POST["codcliente"]);
			$montocredito = number_format($_POST["txtTotal"]-$_POST["montoabono"], 2, '.', '');
			$stmt->execute();

		} else { 

			$sql = "UPDATE creditosxclientes set"
			." montocredito = ? "
			." where "
			." codcliente = ?;
			";
			$stmt = $this->dbh->prepare($sql);
			$stmt->bindParam(1, $montocredito);
			$stmt->bindParam(2, $codcliente);

			$montocredito = number_format($montoactual+($_POST["txtTotal"]-$_POST["montoabono"]), 2, '.', '');
			$codcliente = limpiar($_POST["codcliente"]);
			$stmt->execute();
		}

	} else if (limpiar($_POST["tipopago"]=="CREDITO" && $_POST["montoabono"]!="0.00" && $_POST["montoabono"]!="0")) { 

		$sql = "SELECT creditos, abonos FROM arqueocaja WHERE codcaja = '".limpiar($_POST["codcaja"])."' AND statusarqueo = '1'";
		foreach ($this->dbh->query($sql) as $row)
		{
			$this->p[] = $row;
		}
		$credito = ($row['creditos']== "" ? "0.00" : $row['creditos']);
		$abono = ($row['abonos']== "" ? "0.00" : $row['abonos']);

		$sql = " UPDATE arqueocaja SET "
		." creditos = ?, "
		." abonos = ? "
		." where "
		." codcaja = ? and statusarqueo = '1';
		";
		$stmt = $this->dbh->prepare($sql);
		$stmt->bindParam(1, $txtTotal);
		$stmt->bindParam(2, $totalabono);
		$stmt->bindParam(3, $codcaja);

		$txtTotal = number_format($_POST["txtTotal"]+$credito, 2, '.', '');
		$totalabono = number_format($_POST["montoabono"]+$abono, 2, '.', '');
		$codcaja = limpiar($_POST["codcaja"]);
		$stmt->execute();

		$query = "INSERT INTO abonoscreditos values (null, ?, ?, ?, ?, ?); ";
		$stmt = $this->dbh->prepare($query);
		$stmt->bindParam(1, $codcaja);
		$stmt->bindParam(2, $codventa);
		$stmt->bindParam(3, $codcliente);
		$stmt->bindParam(4, $montoabono);
		$stmt->bindParam(5, $fechaabono);

		$codcaja = limpiar($_POST["codcaja"]);
		$codcliente = limpiar($_POST["codcliente"]);
		$montoabono = number_format($_POST["montoabono"], 2, '.', '');
		$fechaabono = limpiar($fecha);
		$stmt->execute();

		$sql = " SELECT codcliente FROM creditosxclientes WHERE codcliente = ?";
		$stmt = $this->dbh->prepare($sql);
		$stmt->execute(array($_POST["codcliente"]));
		$num = $stmt->rowCount();
		if($num == 0)
		{
			$query = "INSERT INTO creditosxclientes values (null, ?, ?);";
			$stmt = $this->dbh->prepare($query);
			$stmt->bindParam(1, $codcliente);
			$stmt->bindParam(2, $montocredito);

			$codcliente = limpiar($_POST["codcliente"]);
			$montocredito = number_format($_POST["txtTotal"]-$_POST["montoabono"], 2, '.', '');
			$stmt->execute();

		} else { 

			$sql = "UPDATE creditosxclientes set"
			." montocredito = ? "
			." where "
			." codcliente = ? AND codsucursal = ?;
			";
			$stmt = $this->dbh->prepare($sql);
			$stmt->bindParam(1, $montocredito);
			$stmt->bindParam(2, $codcliente);

			$montocredito = number_format($montoactual+($_POST["txtTotal"]-$_POST["montoabono"]), 2, '.', '');
			$codcliente = limpiar($_POST["codcliente"]);
			$stmt->execute();
		}
	}
    ########## AGREGAMOS EL INGRESO Y ABONOS DE VENTAS A CREDITOS A CAJA ########

echo "<span class='fa fa-check-square-o'></span> LA VENTA DE PRODUCTOS HA SIDO REGISTRADA EXITOSAMENTE <a href='reportepdf?codventa=".encrypt($codventa)."&tipo=".encrypt($tipodocumento)."' class='on-default' data-placement='left' data-toggle='tooltip' data-original-title='Imprimir Documento' target='_black' rel='noopener noreferrer'><font color='black'><strong>IMPRIMIR REPORTE</strong></font color></a></div>";

echo "<script>window.open('reportepdf?codventa=".encrypt($codventa)."&tipo=".encrypt($tipodocumento)."', '_blank');</script>";
	exit;
   }
}
######################### FUNCION REGISTRAR DELIVERY ############################

######################### FUNCION LISTAR PEDIDOS EN MOSTRADOR ########################
	public function ListarMostrador()
	{
		self::SetNames();
	$sql = "SELECT ventas.idventa, ventas.codpedido, ventas.codcliente, ventas.codmesa, ventas.totalpago, ventas.delivery, ventas.repartidor, clientes.dnicliente, clientes.nomcliente, salas.nomsala, mesas.nommesa, detallepedidos.pedido, detallepedidos.observacionespedido, detallepedidos.cocinero, GROUP_CONCAT(cantventa, ' | ', producto SEPARATOR '<br>') AS detalles FROM ventas INNER JOIN detallepedidos ON detallepedidos.codpedido = ventas.codpedido LEFT JOIN clientes ON ventas.codcliente = clientes.codcliente LEFT JOIN mesas ON mesas.codmesa = ventas.codmesa LEFT JOIN salas ON mesas.codsala = salas.codsala WHERE detallepedidos.cocinero = '1' GROUP BY detallepedidos.codpedido, detallepedidos.pedido";
        foreach ($this->dbh->query($sql) as $row)
		{
			$this->p[] = $row;
		}
		return $this->p;
		$this->dbh=null;
	}
######################### FUNCION LISTAR PEDIDOS EN MOSTRADOR ########################

######################### FUNCION LISTAR DELIVERY EN MOSTRADOR ########################
	public function ListarDelivery()
	{
		self::SetNames();
	$sql = "SELECT ventas.idventa, ventas.codpedido, ventas.codcliente, ventas.codmesa, ventas.totalpago, ventas.delivery, ventas.repartidor, clientes.dnicliente, clientes.nomcliente, clientes.direccliente, clientes.tlfcliente, detallepedidos.pedido, detallepedidos.observacionespedido, detallepedidos.cocinero, GROUP_CONCAT(cantventa, ' | ', producto SEPARATOR '<br>') AS detalles 
	    FROM ventas INNER JOIN detallepedidos ON detallepedidos.codpedido = ventas.codpedido LEFT JOIN clientes ON ventas.codcliente = clientes.codcliente LEFT JOIN usuarios ON ventas.repartidor = usuarios.codigo WHERE ventas.repartidor = '".$_SESSION["codigo"]."' AND ventas.entregado = 1 GROUP BY detallepedidos.codpedido, detallepedidos.pedido";
        foreach ($this->dbh->query($sql) as $row)
		{
			$this->p[] = $row;
		}
		return $this->p;
		$this->dbh=null;
	}
######################### FUNCION LISTAR DELIVERY EN MOSTRADOR ########################

################## FUNCION PARA ENTREGA DE PEDIDOS POR COCINERO #####################
	public function EntregarPedidoMesa()
	{
		self::SetNames();

		if(limpiar(decrypt($_GET["delivery"]))==0){
		
		$sql = "UPDATE detallepedidos set "
			  ." cocinero = ? "
			  ." WHERE "
			  ." codpedido = ? AND pedido = ?;
			   ";
		$stmt = $this->dbh->prepare($sql);
		$stmt->bindParam(1, $cocinero);
		$stmt->bindParam(2, $codpedido);
		$stmt->bindParam(3, $pedido);
		
		$cocinero = limpiar("0");
		$codpedido = limpiar(decrypt($_GET["codpedido"]));
		$pedido = limpiar(decrypt($_GET["pedido"]));
		$stmt->execute();
		
        echo "1";
        exit;

	  } else {
		
	  	$sql = "UPDATE detallepedidos set "
	  	." cocinero = ? "
	  	." WHERE "
	  	." codpedido = ? AND pedido = ?;
	  	";
	  	$stmt = $this->dbh->prepare($sql);
	  	$stmt->bindParam(1, $cocinero);
	  	$stmt->bindParam(2, $codpedido);
	  	$stmt->bindParam(3, $pedido);

	  	$cocinero = limpiar("0");
	  	$codpedido = limpiar(decrypt($_GET["codpedido"]));
	  	$pedido = limpiar(decrypt($_GET["pedido"]));
	  	$stmt->execute();
		
        echo "2";
		exit;

	  }
  }
##################### FUNCION PARA ENTREGA DE PEDIDOS POR COCINERO ###################

################## FUNCION PARA ENTREGA DE PEDIDOS POR DELIVERY #####################
	public function EntregarPedidoDelivery()
	{
		self::SetNames();
		
		$sql = "UPDATE ventas set "
			  ." entregado = ? "
			  ." WHERE "
			  ." codpedido = ?;
			   ";
		$stmt = $this->dbh->prepare($sql);
		$stmt->bindParam(1, $entregado);
		$stmt->bindParam(2, $codpedido);
		
		$entregado = strip_tags("0");
		$codpedido = limpiar(decrypt($_GET["codpedido"]));
		$stmt->execute();

		$sql = "DELETE FROM detallepedidos WHERE codpedido = ?";
		$stmt = $this->dbh->prepare($sql);
		$stmt->bindParam(1,$codpedido);
		$codpedido = limpiar(decrypt($_GET["codpedido"]));
		$stmt->execute();
		
        echo "1";
        exit;

  }
##################### FUNCION PARA ENTREGA DE PEDIDOS POR DELIVERY ###################

################################## FUNCION LISTAR VENTAS ################################
public function ListarVentas()
{
	self::SetNames();

if ($_SESSION['acceso'] == "administrador") {

	$sql = "SELECT 
	ventas.idventa, 
	ventas.codmesa,
	ventas.tipodocumento, 
	ventas.codventa, 
	ventas.codserie, 
	ventas.codautorizacion, 
	ventas.codcaja, 
	ventas.codcliente, 
	ventas.subtotalivasi, 
	ventas.subtotalivano, 
	ventas.iva, 
	ventas.totaliva, 
	ventas.descuento, 
	ventas.totaldescuento, 
	ventas.totalpago, 
	ventas.totalpago2, 
	ventas.tipopago, 
	ventas.formapago, 
	ventas.montopagado, 
	ventas.montodevuelto, 
	ventas.fechavencecredito, 
	ventas.fechapagado,
	ventas.statusventa, 
	ventas.fechaventa,
	ventas.observaciones, 
	cajas.nrocaja,
	cajas.nomcaja,
	clientes.documcliente, 
	clientes.dnicliente, 
	clientes.nomcliente, 
	documentos.documento,
	mediospagos.mediopago,
	SUM(detalleventas.cantventa) AS articulos 
	FROM (ventas LEFT JOIN detalleventas ON detalleventas.codventa = ventas.codventa)
	LEFT JOIN mesas ON ventas.codmesa = mesas.codmesa 
	LEFT JOIN cajas ON ventas.codcaja = cajas.codcaja 
	LEFT JOIN clientes ON ventas.codcliente = clientes.codcliente
	LEFT JOIN documentos ON clientes.documcliente = documentos.coddocumento
	LEFT JOIN mediospagos ON ventas.formapago = mediospagos.codmediopago 
	LEFT JOIN usuarios ON ventas.codigo = usuarios.codigo WHERE ventas.statuspago = '0' GROUP BY detalleventas.codventa";
	foreach ($this->dbh->query($sql) as $row)
	{
		$this->p[] = $row;
	}
	return $this->p;
	$this->dbh=null;

 } else {

      $sql = "SELECT 
	ventas.idventa, 
	ventas.codmesa,
	ventas.tipodocumento, 
	ventas.codventa, 
	ventas.codserie, 
	ventas.codautorizacion, 
	ventas.codcaja, 
	ventas.codcliente, 
	ventas.subtotalivasi, 
	ventas.subtotalivano, 
	ventas.iva, 
	ventas.totaliva, 
	ventas.descuento, 
	ventas.totaldescuento, 
	ventas.totalpago, 
	ventas.totalpago2, 
	ventas.tipopago, 
	ventas.formapago, 
	ventas.montopagado, 
	ventas.montodevuelto, 
	ventas.fechavencecredito, 
	ventas.fechapagado,
	ventas.statusventa, 
	ventas.fechaventa,
	ventas.observaciones,
	cajas.nrocaja,
	cajas.nomcaja,
	clientes.documcliente, 
	clientes.dnicliente, 
	clientes.nomcliente, 
	documentos.documento,
	mediospagos.mediopago,
	SUM(detalleventas.cantventa) AS articulos 
	FROM (ventas LEFT JOIN detalleventas ON detalleventas.codventa = ventas.codventa)
	LEFT JOIN mesas ON ventas.codmesa = mesas.codmesa 
	LEFT JOIN cajas ON ventas.codcaja = cajas.codcaja 
	LEFT JOIN clientes ON ventas.codcliente = clientes.codcliente
	LEFT JOIN documentos ON clientes.documcliente = documentos.coddocumento
	LEFT JOIN mediospagos ON ventas.formapago = mediospagos.codmediopago 
	LEFT JOIN usuarios ON ventas.codigo = usuarios.codigo WHERE ventas.codigo = '".limpiar($_SESSION["codigo"])."' AND ventas.statuspago = '0' GROUP BY detalleventas.codventa";
	foreach ($this->dbh->query($sql) as $row)
	{
		$this->p[] = $row;
	}
	return $this->p;
	$this->dbh=null;
    }
}
################################## FUNCION LISTAR VENTAS ############################

############################ FUNCION ID VENTAS #################################
public function VentasPorId()
	{
	self::SetNames();
	$sql = "SELECT 
		ventas.idventa, 
		ventas.codpedido,
		ventas.codmesa,
		ventas.tipodocumento, 
		ventas.codventa, 
		ventas.codserie, 
		ventas.codautorizacion, 
		ventas.codcaja, 
		ventas.codcliente, 
		ventas.subtotalivasi, 
		ventas.subtotalivano, 
		ventas.iva, 
		ventas.totaliva, 
		ventas.descuento, 
		ventas.totaldescuento, 
		ventas.totalpago, 
		ventas.totalpago2, 
		ventas.tipopago, 
		ventas.formapago, 
		ventas.montopagado,
		ventas.montopropina, 
		ventas.montodevuelto, 
		ventas.fechavencecredito, 
	    ventas.fechapagado,
		ventas.statusventa, 
		ventas.fechaventa,
	    ventas.observaciones, 
	    salas.nomsala,
	    mesas.nommesa,
		clientes.codcliente,
		clientes.documcliente,
		clientes.dnicliente, 
		clientes.nomcliente, 
		clientes.tlfcliente, 
		clientes.id_provincia, 
		clientes.id_departamento, 
		clientes.direccliente, 
		clientes.emailcliente,
		clientes.limitecredito,
		documentos.documento,
	    cajas.nrocaja,
	    cajas.nomcaja,
	    mediospagos.mediopago,
	    usuarios.dni, 
	    usuarios.nombres,
	    provincias.provincia,
	    departamentos.departamento,
		ROUND(SUM(if(pag.montocredito!='0',pag.montocredito,'0.00')), 2) montoactual,
	    ROUND(SUM(if(pag.montocredito!='0',clientes.limitecredito-pag.montocredito,clientes.limitecredito)), 2) creditodisponible,
	    pag2.abonototal
        FROM (ventas LEFT JOIN mesas ON ventas.codmesa = mesas.codmesa)
        LEFT JOIN salas ON mesas.codsala = salas.codsala
        LEFT JOIN clientes ON ventas.codcliente = clientes.codcliente
	LEFT JOIN documentos ON clientes.documcliente = documentos.coddocumento
	LEFT JOIN provincias ON clientes.id_provincia = provincias.id_provincia 
	LEFT JOIN departamentos ON clientes.id_departamento = departamentos.id_departamento 
	LEFT JOIN cajas ON ventas.codcaja = cajas.codcaja
	LEFT JOIN mediospagos ON ventas.formapago = mediospagos.codmediopago 
	LEFT JOIN usuarios ON ventas.codigo = usuarios.codigo
    
    LEFT JOIN
        (SELECT
        codcliente, montocredito       
        FROM creditosxclientes) pag ON pag.codcliente = clientes.codcliente
    
    LEFT JOIN
        (SELECT
        codventa, codcliente, SUM(if(montoabono!='0',montoabono,'0.00')) AS abonototal
        FROM abonoscreditos 
        WHERE codventa = '".limpiar(decrypt($_GET["codventa"]))."') pag2 ON pag2.codcliente = clientes.codcliente
        WHERE ventas.codventa = ? AND ventas.statuspago = '0'";
    $stmt = $this->dbh->prepare($sql);
	$stmt->execute(array(decrypt($_GET["codventa"])));
	$num = $stmt->rowCount();
	if($num==0)
	{
		echo "";
	}
	else
	{
		if($row = $stmt->fetch(PDO::FETCH_ASSOC))
		{
			$this->p[] = $row;
		}
		return $this->p;
		$this->dbh=null;
	}
}
############################ FUNCION ID VENTAS #################################
	
############################ FUNCION VER DETALLES VENTAS #############################
public function VerDetallesVentas()
	{
	self::SetNames();
	$sql = "SELECT
	detalleventas.coddetalleventa,
	detalleventas.codventa,
	detalleventas.codproducto,
	detalleventas.producto,
	detalleventas.codcategoria,
	detalleventas.cantventa,
	detalleventas.preciocompra,
	detalleventas.precioventa,
	detalleventas.ivaproducto,
	detalleventas.descproducto,
	detalleventas.valortotal, 
	detalleventas.totaldescuentov,
	detalleventas.valorneto,
	detalleventas.valorneto2,
	categorias.nomcategoria
	FROM detalleventas INNER JOIN categorias ON detalleventas.codcategoria = categorias.codcategoria
	WHERE detalleventas.codventa = ?";
	$stmt = $this->dbh->prepare($sql);
	$stmt->execute(array(decrypt($_GET["codventa"])));
	$num = $stmt->rowCount();
	
	while($row = $stmt->fetch(PDO::FETCH_ASSOC))
	{
		$this->p[]=$row;
	}
		return $this->p;
		$this->dbh=null;
}
########################### FUNCION VER DETALLES VENTAS ###########################

############################# FUNCION ACTUALIZAR VENTAS #############################
public function ActualizarVentas()
	{
	self::SetNames();
	if(empty($_POST["codpedido"]) or empty($_POST["codventa"]))
	{
		echo "1";
		exit;
	}

	    ############ CONSULTO TOTAL ACTUAL ##############
		$sql = "SELECT totalpago FROM ventas WHERE codventa = '".limpiar($_POST["codventa"])."'";
		foreach ($this->dbh->query($sql) as $row)
		{
			$this->p[] = $row;
		}
		$totalpagobd = $row['totalpago'];

	   ################### SELECCIONE LOS DATOS DEL CLIENTE ######################
	    $sql = "SELECT
	    clientes.nomcliente, 
	    clientes.emailcliente, 
	    clientes.limitecredito,
	    ROUND(SUM(if(pag.montocredito!='0',pag.montocredito,'0.00')), 2) montoactual,
	    ROUND(SUM(if(pag.montocredito!='0',clientes.limitecredito-pag.montocredito,clientes.limitecredito)), 2) creditodisponible
        FROM clientes 
        LEFT JOIN
           (SELECT
           codcliente, montocredito       
           FROM creditosxclientes) pag ON pag.codcliente = clientes.codcliente
           WHERE clientes.codcliente = ?";
		$stmt = $this->dbh->prepare($sql);
		$stmt->execute(array(limpiar($_POST['codcliente'])));
		$num = $stmt->rowCount();
		if($row = $stmt->fetch(PDO::FETCH_ASSOC))
		{
			$p[] = $row;
		}
        $nomcliente = $row['nomcliente'];
        $emailcliente = $row['emailcliente'];
        $limitecredito = $row['limitecredito'];
        $montoactual = $row['montoactual'];
        $creditodisponible = $row['creditodisponible'];
        $montoabono = (empty($_POST["abonototal"]) ? "0.00" : $_POST["abonototal"]);
        $total = number_format($_POST["txtTotal"], 2, '.', '');

		for($i=0;$i<count($_POST['coddetalleventa']);$i++){  //recorro el array
			if (!empty($_POST['coddetalleventa'][$i])) {

				if($_POST['cantventa'][$i]==0){

					echo "2";
					exit();

				}
			}
		}

	$this->dbh->beginTransaction();
	for($i=0;$i<count($_POST['coddetalleventa']);$i++){  //recorro el array
	if (!empty($_POST['coddetalleventa'][$i])) {

	    $sql = "SELECT cantventa FROM detalleventas WHERE coddetalleventa = '".limpiar($_POST['coddetalleventa'][$i])."' AND codventa = '".limpiar($_POST["codventa"])."'";
		foreach ($this->dbh->query($sql) as $row)
		{
			$this->p[] = $row;
		}
		
		$cantidadbd = $row['cantventa'];

	if($cantidadbd != $_POST['cantventa'][$i]){

	############### VALIDO SI LA CANTIDAD ES MAYOR QUE LA EXISTENCIA ############
	   $sql = "SELECT existencia FROM productos WHERE codproducto = '".limpiar($_POST['codproducto'][$i])."'";
		    foreach ($this->dbh->query($sql) as $row)
		{
			$this->p[] = $row;
		}
		$existenciaproductobd = $row['existencia'];
		$cantventa = $_POST["cantventa"][$i];
		$cantidadventabd = $_POST["cantidadventabd"][$i];
		$totalventa = $cantventa-$cantidadventabd;

     if ($totalventa > $existenciaproductobd) 
        { 
		    echo "4";
		    exit;
	    }

	    $query = "UPDATE detalleventas set"
		." cantventa = ?, "
		." valortotal = ?, "
		." totaldescuentov = ?, "
		." valorneto = ?, "
		." valorneto2 = ? "
		." WHERE "
		." coddetalleventa = ? AND codventa = ?;
		";
		$stmt = $this->dbh->prepare($query);
		$stmt->bindParam(1, $cantventa);
		$stmt->bindParam(2, $valortotal);
		$stmt->bindParam(3, $totaldescuentov);
		$stmt->bindParam(4, $valorneto);
		$stmt->bindParam(5, $valorneto2);
		$stmt->bindParam(6, $coddetalleventa);
		$stmt->bindParam(7, $codventa);

		$cantventa = limpiar($_POST['cantventa'][$i]);
		$preciocompra = limpiar($_POST['preciocompra'][$i]);
		$precioventa = limpiar($_POST['precioventa'][$i]);
		$ivaproducto = limpiar($_POST['ivaproducto'][$i]);
		$descuento = $_POST['descproducto'][$i]/100;
		$valortotal = number_format($_POST['precioventa'][$i] * $_POST['cantventa'][$i], 2, '.', '');
		$totaldescuentov = number_format($valortotal * $descuento, 2, '.', '');
		$valorneto = number_format($valortotal - $totaldescuentov, 2, '.', '');
		$valorneto2 = number_format($_POST['preciocompra'][$i] * $_POST['cantventa'][$i], 2, '.', '');
		$coddetalleventa = limpiar($_POST['coddetalleventa'][$i]);
		$codventa = limpiar($_POST["codventa"]);
		$stmt->execute();

############################### PROCESO PARA VERIFICAR LOS PRODUCTOS ######################################
	
		##################### ACTUALIZO LA EXISTENCIA DEL ALMACEN ####################
		$sql = " UPDATE productos set "
			  ." existencia = ? "
			  ." where "
			  ." codproducto = '".limpiar($_POST["codproducto"][$i])."';
			   ";
		$stmt = $this->dbh->prepare($sql);
		$stmt->bindParam(1, $existencia);
		$existencia = number_format($existenciaproductobd-$totalventa, 2, '.', '');
		$stmt->execute();
		##################### ACTUALIZO LA EXISTENCIA DEL ALMACEN ####################

		############# ACTUALIZAMOS LOS DATOS DEL PRODUCTO EN KARDEX ###################
		$sql3 = " UPDATE kardex_productos set "
		      ." salidas = ?, "
		      ." stockactual = ? "
			  ." WHERE "
			  ." codproceso = '".limpiar($_POST["codpedido"])."' and codproducto = '".limpiar($_POST["codproducto"][$i])."';
			   ";
		$stmt = $this->dbh->prepare($sql3);
		$stmt->bindParam(1, $salidas);
		$stmt->bindParam(2, $existencia);
		
		$salidas = limpiar($_POST["cantventa"][$i]);
		$existencia = number_format($existenciaproductobd-$totalventa, 2, '.', '');
		$stmt->execute();

############################### PROCESO PARA VERIFICAR LOS PRODUCTOS ######################################		



############################### PROCESO PARA VERIFICAR LOS INGREDIENTES ######################################	

		############## VERIFICO SSI EL PRODUCTO TIENE INGREDIENTES RELACIONADOS #################
	    $sql = "SELECT * FROM productosxingredientes WHERE codproducto = ?";
		$stmt = $this->dbh->prepare($sql);
		$stmt->execute(array(limpiar($_POST["codproducto"][$i])));
		$num = $stmt->rowCount();
        if($num>0) {  

        	$sql = "SELECT * FROM productosxingredientes LEFT JOIN ingredientes ON productosxingredientes.codingrediente = ingredientes.codingrediente WHERE productosxingredientes.codproducto IN ('".$_POST["codproducto"][$i]."')";
        	foreach ($this->dbh->query($sql) as $row)
		    { 
			   $this->p[] = $row;

			   $cantracionbd = $row['cantracion'];
			   $codingredientebd = $row['codingrediente'];
			   $cantingredientebd = $row['cantingrediente'];
			   $precioventaingredientebd = $row['precioventa'];
			   $ivaingredientebd = $row['ivaingrediente'];
			   $descingredientebd = $row['descingrediente'];

			   ############## ACTUALIZO LOS DATOS DEL INGREDIENTE #################
			   $update = "UPDATE ingredientes set "
			   ." cantingrediente = ? "
			   ." WHERE "
			   ." codingrediente = ?;
			   ";
			   $stmt = $this->dbh->prepare($update);
			   $stmt->bindParam(1, $cantidadracion);
			   $stmt->bindParam(2, $codingredientebd);

			   $racion = number_format($cantracionbd*$totalventa, 2, '.', '');
			   $cantidadracion = number_format($cantingredientebd-$racion, 2, '.', '');
			   $stmt->execute();

			   ############## VERIFICO LA EXISTENCIA DEL PRODUCTO EN ALMACEN #################
			   $sql = "SELECT salidas FROM kardex_ingredientes WHERE codproceso = '".limpiar($_POST['codpedido'])."' AND codingrediente = '".limpiar($codingredientebd)."'";
			   foreach ($this->dbh->query($sql) as $row)
			   {
			   	$this->p[] = $row;
			   }
			   $salidakardex = $row['salidas'];
		       ############## VERIFICO LA EXISTENCIA DEL PRODUCTO EN ALMACEN #################

			   ########## ACTUALIZAMOS LOS DATOS DEL INGREDIENTE EN KARDEX ###################
			   $sql3 = " UPDATE kardex_ingredientes set "
			   ." salidas = ?, "
			   ." stockactual = ? "
			   ." WHERE "
			   ." codproceso = '".limpiar($_POST["codpedido"])."' and codingrediente = '".limpiar($codingredientebd)."';
			   ";
			   $stmt = $this->dbh->prepare($sql3);
			   $stmt->bindParam(1, $salidas);
			   $stmt->bindParam(2, $stockactual);

			   $racion = number_format($cantracionbd*$totalventa, 2, '.', '');
			   $salidas = number_format($salidakardex+$racion, 2, '.', '');
			   
			   $substock = number_format($cantracionbd*$totalventa, 2, '.', '');
			   $stockactual = number_format($cantingredientebd-$substock, 2, '.', '');
			   $stmt->execute();	

		    }

		  }//fin de consulta de ingredientes de productos

############################### PROCESO PARA VERIFICAR LOS INGREDIENTES ######################################		


			} else {

               echo "";

		       }
	        } 
        }    
            $this->dbh->commit();

        ############ SUMO LOS IMPORTE DE PRODUCTOS CON IVA ##############
        $sql3 = "SELECT SUM(valorneto) AS valorneto, SUM(valorneto2) AS valorneto2 FROM detalleventas WHERE codventa = '".limpiar($_POST["codventa"])."' AND ivaproducto = 'SI'";
        foreach ($this->dbh->query($sql3) as $row3)
        {
        	$this->p[] = $row3;
        }
        $subtotalivasi = ($row3['valorneto']== "" ? "0.00" : $row3['valorneto']);
        $subtotalivasi2 = ($row3['valorneto2']== "" ? "0.00" : $row3['valorneto2']);

	    ############ SUMO LOS IMPORTE DE PRODUCTOS SIN IVA ##############
        $sql4 = "SELECT SUM(valorneto) AS valorneto, SUM(valorneto2) AS valorneto2 FROM detalleventas WHERE codventa = '".limpiar($_POST["codventa"])."' AND ivaproducto = 'NO'";
        foreach ($this->dbh->query($sql4) as $row4)
        {
        	$this->p[] = $row4;
        }
        $subtotalivano = ($row4['valorneto']== "" ? "0.00" : $row4['valorneto']);
        $subtotalivano2 = ($row4['valorneto2']== "" ? "0.00" : $row4['valorneto2']);

        ############ ACTUALIZO LOS TOTALES EN LA COTIZACION ##############
        $sql = " UPDATE ventas SET "
        ." codcliente = ?, "
        ." subtotalivasi = ?, "
        ." subtotalivano = ?, "
        ." totaliva = ?, "
        ." descuento = ?, "
        ." totaldescuento = ?, "
        ." totalpago = ?, "
		." totalpago2 = ?, "
		." montodevuelto = ? "
        ." WHERE "
        ." codventa = ?;
        ";
        $stmt = $this->dbh->prepare($sql);
        $stmt->bindParam(1, $codcliente);
        $stmt->bindParam(2, $subtotalivasi);
        $stmt->bindParam(3, $subtotalivano);
        $stmt->bindParam(4, $totaliva);
        $stmt->bindParam(5, $descuento);
        $stmt->bindParam(6, $totaldescuento);
        $stmt->bindParam(7, $totalpago);
        $stmt->bindParam(8, $totalpago2);
		$stmt->bindParam(9, $montodevuelto);
        $stmt->bindParam(10, $codventa);

        $codcliente = limpiar($_POST["codcliente"]);
        $iva = $_POST["iva"]/100;
        $totaliva = number_format($subtotalivasi*$iva, 2, '.', '');
        $descuento = limpiar($_POST["descuento"]);
        $txtDescuento = $_POST["descuento"]/100;
        $total = number_format($subtotalivasi+$subtotalivano+$totaliva, 2, '.', '');
        $totaldescuento = number_format($total*$txtDescuento, 2, '.', '');
        $totalpago = number_format($total-$totaldescuento, 2, '.', '');
        $totalpago2 = number_format($subtotalivasi2+$subtotalivano2, 2, '.', '');
		$montodevuelto = number_format($totalpago > $_POST["pagado"] ? "0.00" : $_POST["pagado"]-$totalpago, 2, '.', '');
        $codventa = limpiar($_POST["codventa"]);
        $tipodocumento = limpiar($_POST["tipodocumento"]);
        $tipopago = limpiar($_POST["tipopago"]);
        $stmt->execute();

    ################ AGREGAMOS O QUITAMOS LA DIFERENCIA EN CAJA ####################
	if (limpiar($_POST["tipopago"]=="CONTADO") && $totalpagobd != $totalpago){

		$sql = "SELECT ingresos FROM arqueocaja WHERE codcaja = '".limpiar($_POST["codcaja"])."' AND statusarqueo = '1'";
		foreach ($this->dbh->query($sql) as $row)
		{
			$this->p[] = $row;
		}
		$ingreso = ($row['ingresos']== "" ? "0.00" : $row['ingresos']);

		$sql = "UPDATE arqueocaja set "
		." ingresos = ? "
		." WHERE "
		." codcaja = ? AND statusarqueo = '1';
		";
		$stmt = $this->dbh->prepare($sql);
		$stmt->bindParam(1, $TxtTotal);
		$stmt->bindParam(2, $codcaja);

        $TxtTotal = number_format(($totalpagobd>$totalpago ? $ingreso-($totalpagobd-$totalpago) : $ingreso+($totalpago-$totalpagobd)), 2, '.', '');
		$codcaja = limpiar($_POST["codcaja"]);
		$stmt->execute();
	}
    ############### AGREGAMOS O QUITAMOS LA DIFERENCIA EN CAJA ####################

    ############## AGREGAMOS O QUITAMOS LA DIFERENCIA EN CAJA ##################
	if (limpiar($_POST["tipopago"]=="CREDITO") && $totalpagobd != $totalpago) {

		$sql = "SELECT creditos FROM arqueocaja WHERE codcaja = '".limpiar($_POST["codcaja"])."' AND statusarqueo = '1'";
		foreach ($this->dbh->query($sql) as $row)
		{
			$this->p[] = $row;
		}
		$credito = ($row['creditos']== "" ? "0.00" : $row['creditos']);

		$sql = " UPDATE arqueocaja SET "
		." creditos = ? "
		." where "
		." codcaja = ? and statusarqueo = '1';
		";
		$stmt = $this->dbh->prepare($sql);
		$stmt->bindParam(1, $TxtTotal);
		$stmt->bindParam(2, $codcaja);

		$TxtTotal = number_format(($totalpagobd>$totalpago ? $credito-($totalpagobd-$totalpago) : $credito+($totalpago-$totalpagobd)), 2, '.', '');
		$codcaja = limpiar($_POST["codcaja"]);
		$stmt->execute(); 	

		$sql = "UPDATE creditosxclientes set"
		." montocredito = ? "
		." where "
		." codcliente = ?;
		";
		$stmt = $this->dbh->prepare($sql);
		$stmt->bindParam(1, $montocredito);
		$stmt->bindParam(2, $codcliente);

        $montocredito = number_format(($totalpagobd>$totalpago ? $montoactual-($totalpagobd-$totalpago) : $montoactual+($totalpago-$totalpagobd)), 2, '.', '');
		$codcliente = limpiar($_POST["codcliente"]);
		$stmt->execute(); 
	}
    ############## AGREGAMOS O QUITAMOS LA DIFERENCIA EN CAJA ##################

echo "<span class='fa fa-check-square-o'></span> LA VENTA DE PRODUCTOS HA SIDO ACTUALIZADA EXITOSAMENTE <a href='reportepdf?codventa=".encrypt($codventa)."&tipo=".encrypt($tipodocumento)."' class='on-default' data-placement='left' data-toggle='tooltip' data-original-title='Imprimir Documento' target='_black' rel='noopener noreferrer'><font color='black'><strong>IMPRIMIR REPORTE</strong></font color></a></div>";

echo "<script>window.open('reportepdf?codventa=".encrypt($codventa)."&tipo=".encrypt($tipodocumento)."', '_blank');</script>";
	exit;
}
############################ FUNCION ACTUALIZAR VENTAS ###########################

########################### FUNCION AGREGAR DETALLES VENTAS ##########################
public function AgregarDetallesVentas()
	{
		self::SetNames();
		if(empty($_POST["codpedido"]) or empty($_POST["codventa"]))
		{
			echo "1";
			exit;
		}

		else if(empty($_SESSION["CarritoVenta"]) || $_POST["txtTotal"]=="0.00")
		{
			echo "2";
			exit;
			
		} else {

        ############ CONSULTO TOTAL ACTUAL ##############
		$sql = "SELECT totalpago FROM ventas WHERE codventa = '".limpiar($_POST["codventa"])."'";
		foreach ($this->dbh->query($sql) as $row)
		{
			$this->p[] = $row;
		}
		$totalpagobd = $row['totalpago'];

		################### SELECCIONE LOS DATOS DEL CLIENTE ######################
	    $sql = "SELECT
	    clientes.nomcliente, 
	    clientes.emailcliente, 
	    clientes.limitecredito,
	    ROUND(SUM(if(pag.montocredito!='0',pag.montocredito,'0.00')), 2) montoactual,
	    ROUND(SUM(if(pag.montocredito!='0',clientes.limitecredito-pag.montocredito,clientes.limitecredito)), 2) creditodisponible
        FROM clientes 
        LEFT JOIN
           (SELECT
           codcliente, montocredito       
           FROM creditosxclientes) pag ON pag.codcliente = clientes.codcliente
           WHERE clientes.codcliente = ?";
		$stmt = $this->dbh->prepare($sql);
		$stmt->execute(array(limpiar($_POST['codcliente'])));
		$num = $stmt->rowCount();
		if($row = $stmt->fetch(PDO::FETCH_ASSOC))
		{
			$p[] = $row;
		}
        $nomcliente = $row['nomcliente'];
        $emailcliente = $row['emailcliente'];
        $limitecredito = $row['limitecredito'];
        $montoactual = $row['montoactual'];
        $creditodisponible = $row['creditodisponible'];
        $montoabono = (empty($_POST["abonototal"]) ? "0.00" : $_POST["abonototal"]);
        $total = number_format($_POST["txtTotal"], 2, '.', '');

	   if ($_POST["tipopago"] == "CREDITO") {
      
		    if ($limitecredito != "0.00" && $total > $creditodisponible) {	
	  
	           echo "3";
		       exit;

	        } 
	    }

	    $this->dbh->beginTransaction();
	    $detalle = $_SESSION["CarritoVenta"];
		for($i=0;$i<count($detalle);$i++){

        ############### SELECCIONAMOSLA EXISTENCIA DEL PRODUCTO ################
	    $sql2 = "SELECT existencia FROM productos WHERE codproducto = '".limpiar($detalle[$i]['txtCodigo'])."'";
		   foreach ($this->dbh->query($sql2) as $row)
		   {
		$this->p[] = $row;
		   }
		
		$existenciaproductobd = $row["existencia"];

		################ REVISAMOS QUE LA CANTIDAD NO SEA IGUAL A CERO #############
			if($detalle[$i]['cantidad']==0){

				echo "4";
				exit;
		    }

		#### REVISAMOS SI LA CANTIDAD ES MAYOR QUE LA EXISTENCIA EN ALMACEN #######
            if ($detalle[$i]['cantidad'] > $existenciaproductobd) 
            { 
		       echo "5";
		       exit;
	        }

	    ############ REVISAMOS QUE EL PRODUCTO NO ESTE EN LA BD ###################
	    $sql = "SELECT codventa, codproducto FROM detalleventas WHERE codventa = '".limpiar($_POST['codventa'])."' AND codproducto = '".limpiar($detalle[$i]['txtCodigo'])."'";
		$stmt = $this->dbh->prepare($sql);
		$stmt->execute();
		$num = $stmt->rowCount();
		if($num == 0)
		{

        $query = "INSERT INTO detalleventas values (null, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?); ";
		$stmt = $this->dbh->prepare($query);
		$stmt->bindParam(1, $codpedido);
		$stmt->bindParam(2, $codventa);
	    $stmt->bindParam(3, $codproducto);
	    $stmt->bindParam(4, $producto);
	    $stmt->bindParam(5, $codcategoria);
		$stmt->bindParam(6, $cantidad);
		$stmt->bindParam(7, $preciocompra);
		$stmt->bindParam(8, $precioventa);
		$stmt->bindParam(9, $ivaproducto);
		$stmt->bindParam(10, $descproducto);
		$stmt->bindParam(11, $valortotal);
		$stmt->bindParam(12, $totaldescuentov);
		$stmt->bindParam(13, $valorneto);
		$stmt->bindParam(14, $valorneto2);
			
		$codpedido = limpiar($_POST["codpedido"]);
		$codventa = limpiar($_POST["codventa"]);
		$codproducto = limpiar($detalle[$i]['txtCodigo']);
		$producto = limpiar($detalle[$i]['producto']);
		$codcategoria = limpiar($detalle[$i]['codcategoria']);
		$cantidad = limpiar($detalle[$i]['cantidad']);
		$preciocompra = limpiar($detalle[$i]['precio']);
		$precioventa = limpiar($detalle[$i]['precio2']);
		$ivaproducto = limpiar($detalle[$i]['ivaproducto']);
		$descproducto = limpiar($detalle[$i]['descproducto']);
		$descuento = $detalle[$i]['descproducto']/100;
		$valortotal = number_format($detalle[$i]['precio2']*$detalle[$i]['cantidad'], 2, '.', '');
		$totaldescuentov = number_format($valortotal*$descuento, 2, '.', '');
	    $valorneto = number_format($valortotal-$totaldescuentov, 2, '.', '');
		$valorneto2 = number_format($detalle[$i]['precio']*$detalle[$i]['cantidad'], 2, '.', '');
		$stmt->execute();


############################### PROCESO PARA VERIFICAR LOS PRODUCTOS ######################################		

		##################### ACTUALIZO LA EXISTENCIA DEL ALMACEN ####################
		$sql = " UPDATE productos set "
			  ." existencia = ? "
			  ." where "
			  ." codproducto = '".limpiar($detalle[$i]['txtCodigo'])."';
			   ";
		$stmt = $this->dbh->prepare($sql);
		$stmt->bindParam(1, $existencia);
		$cantventa = limpiar($detalle[$i]['cantidad']);
		$existencia = number_format($existenciaproductobd-$cantventa, 2, '.', '');
		$stmt->execute();
		##################### ACTUALIZO LA EXISTENCIA DEL ALMACEN ####################

		############## REGISTRAMOS LOS DATOS DE PRODUCTOS EN KARDEX ###################
        $query = "INSERT INTO kardex_productos values (null, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?); ";
		$stmt = $this->dbh->prepare($query);
		$stmt->bindParam(1, $codpedido);
		$stmt->bindParam(2, $codcliente);
		$stmt->bindParam(3, $codproducto);
		$stmt->bindParam(4, $movimiento);
		$stmt->bindParam(5, $entradas);
		$stmt->bindParam(6, $salidas);
		$stmt->bindParam(7, $devolucion);
		$stmt->bindParam(8, $stockactual);
		$stmt->bindParam(9, $ivaproducto);
		$stmt->bindParam(10, $descproducto);
		$stmt->bindParam(11, $precio);
		$stmt->bindParam(12, $documento);
		$stmt->bindParam(13, $fechakardex);		

		$codpedido = limpiar($_POST["codpedido"]);
		$codcliente = limpiar($_POST["codcliente"]);
		$codproducto = limpiar($detalle[$i]['txtCodigo']);
		$movimiento = limpiar("SALIDAS");
		$entradas = limpiar("0");
		$salidas= limpiar($detalle[$i]['cantidad']);
		$devolucion = limpiar("0");
		$stockactual = number_format($existenciaproductobd-$detalle[$i]['cantidad'], 2, '.', '');
		$precio = limpiar($detalle[$i]['precio2']);
		$ivaproducto = limpiar($detalle[$i]['ivaproducto']);
		$descproducto = limpiar($detalle[$i]['descproducto']);
		$documento = limpiar("DETALLE AGREGADO EN VENTA");
		$fechakardex = limpiar(date("Y-m-d"));
		$stmt->execute();
############################### PROCESO PARA VERIFICAR LOS PRODUCTOS ######################################	



############################### PROCESO PARA VERIFICAR LOS INGREDIENTES ######################################	

	    ############## VERIFICO SI EL PRODUCTO TIENE INGREDIENTES RELACIONADOS #################
	    $sql = "SELECT * FROM productosxingredientes WHERE codproducto = ?";
		$stmt = $this->dbh->prepare($sql);
		$stmt->execute(array(limpiar($detalle[$i]['txtCodigo'])));
		$num = $stmt->rowCount();
        if($num>0) {  

        	$sql = "SELECT * FROM productosxingredientes LEFT JOIN ingredientes ON productosxingredientes.codingrediente = ingredientes.codingrediente WHERE productosxingredientes.codproducto IN ('".$detalle[$i]['txtCodigo']."')";
        	foreach ($this->dbh->query($sql) as $row)
		    { 
			   $this->p[] = $row;

			   $cantracionbd = $row['cantracion'];
			   $codingredientebd = $row['codingrediente'];
			   $cantingredientebd = $row['cantingrediente'];
			   $precioventaingredientebd = $row['precioventa'];
			   $ivaingredientebd = $row['ivaingrediente'];
			   $descingredientebd = $row['descingrediente'];

			   ############## ACTUALIZO LOS DATOS DEL INGREDIENTE #################
			   $update = "UPDATE ingredientes set "
			   ." cantingrediente = ? "
			   ." WHERE "
			   ." codingrediente = ?;
			   ";
			   $stmt = $this->dbh->prepare($update);
			   $stmt->bindParam(1, $cantidadracion);
			   $stmt->bindParam(2, $codingredientebd);

			   $racion = number_format($cantracionbd*$detalle[$i]['cantidad'], 2, '.', '');
			   $cantidadracion = number_format($cantingredientebd-$racion, 2, '.', '');
			   $stmt->execute();

			   ############## REGISTRAMOS LOS DATOS DE PRODUCTOS EN KARDEX ###################
			   $query = "INSERT INTO kardex_ingredientes values (null, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?); ";
			   $stmt = $this->dbh->prepare($query);
			   $stmt->bindParam(1, $codpedido);
			   $stmt->bindParam(2, $codcliente);
			   $stmt->bindParam(3, $codingrediente);
			   $stmt->bindParam(4, $movimiento);
			   $stmt->bindParam(5, $entradas);
			   $stmt->bindParam(6, $salidas);
			   $stmt->bindParam(7, $devolucion);
			   $stmt->bindParam(8, $stockactual);
			   $stmt->bindParam(9, $ivaingrediente);
			   $stmt->bindParam(10, $descingrediente);
			   $stmt->bindParam(11, $precio);
			   $stmt->bindParam(12, $documento);
			   $stmt->bindParam(13, $fechakardex);		

			   $codpedido = limpiar($_POST["codpedido"]);
			   $codcliente = limpiar($_POST["codcliente"]);
			   $codingrediente = limpiar($codingredientebd);
			   $movimiento = limpiar("SALIDAS");
			   $entradas = limpiar("0");
			   $salidas= limpiar($racion);
			   $devolucion = limpiar("0");
			   $stockactual = number_format($cantingredientebd-$racion, 2, '.', '');
			   $precio = limpiar($precioventaingredientebd);
			   $ivaingrediente = limpiar($ivaingredientebd);
			   $descingrediente = limpiar($descingredientebd);
			   $documento = limpiar("DETALLE AGREGADO EN VENTA");
			   $fechakardex = limpiar(date("Y-m-d"));
			   $stmt->execute();
		    }

		  }//fin de consulta de ingredientes de productos	

############################### PROCESO PARA VERIFICAR LOS INGREDIENTES ######################################	

	      } else {

	    ##################### VERIFICO LA CANTIDAD YA REGISTRADA DEL PRODUCTO VENDIDO ####################
		$sql = "SELECT cantventa FROM detalleventas WHERE codventa = '".limpiar($_POST['codventa'])."' AND codproducto = '".limpiar($detalle[$i]['txtCodigo'])."'";
		foreach ($this->dbh->query($sql) as $row)
		{
			$this->p[] = $row;
		}
		$cantidad = $row['cantventa'];
		##################### VERIFICO LA CANTIDAD YA REGISTRADA DEL PRODUCTO VENDIDO ####################

	  	$query = "UPDATE detalleventas set"
		." cantventa = ?, "
		." descproducto = ?, "
		." valortotal = ?, "
		." totaldescuentov = ?, "
		." valorneto = ?, "
		." valorneto2 = ? "
		." WHERE "
		." codventa = ? AND codproducto = ?;
		";
		$stmt = $this->dbh->prepare($query);
		$stmt->bindParam(1, $cantventa);
		$stmt->bindParam(2, $descproducto);
		$stmt->bindParam(3, $valortotal);
		$stmt->bindParam(4, $totaldescuentov);
		$stmt->bindParam(5, $valorneto);
		$stmt->bindParam(6, $valorneto2);
		$stmt->bindParam(7, $codventa);
		$stmt->bindParam(8, $codproducto);

		$cantventa = limpiar($detalle[$i]['cantidad']+$cantidad);
		$preciocompra = limpiar($detalle[$i]['precio']);
		$precioventa = limpiar($detalle[$i]['precio2']);
		$ivaproducto = limpiar($detalle[$i]['ivaproducto']);
		$descproducto = limpiar($detalle[$i]['descproducto']);
		$descuento = $detalle[$i]['descproducto']/100;
		$valortotal = number_format($detalle[$i]['precio2'] * $cantventa, 2, '.', '');
		$totaldescuentov = number_format($valortotal * $descuento, 2, '.', '');
		$valorneto = number_format($valortotal - $totaldescuentov, 2, '.', '');
		$valorneto2 = number_format($detalle[$i]['precio'] * $cantventa, 2, '.', '');
		$codventa = limpiar($_POST["codventa"]);
		$codproducto = limpiar($detalle[$i]['txtCodigo']);
		$stmt->execute();

############################### PROCESO PARA VERIFICAR LOS PRODUCTOS ######################################

		############## VERIFICO LA EXISTENCIA DEL PRODUCTO EN ALMACEN #################
		$sql = "SELECT existencia FROM productos WHERE codproducto = '".limpiar($detalle[$i]['txtCodigo'])."'";
		foreach ($this->dbh->query($sql) as $row)
		{
			$this->p[] = $row;
		}
		$existenciaproductobd = $row['existencia'];
		############## VERIFICO LA EXISTENCIA DEL PRODUCTO EN ALMACEN #################		

		##################### ACTUALIZO LA EXISTENCIA DEL ALMACEN ####################
		$sql = " UPDATE productos set "
			  ." existencia = ? "
			  ." where "
			  ." codproducto = '".limpiar($detalle[$i]['txtCodigo'])."';
			   ";
		$stmt = $this->dbh->prepare($sql);
		$stmt->bindParam(1, $existencia);
		$cantventa = limpiar($detalle[$i]['cantidad']);
		$existencia = number_format($existenciaproductobd-$cantventa, 2, '.', '');
		$stmt->execute();
		##################### ACTUALIZO LA EXISTENCIA DEL ALMACEN ####################

		########## ACTUALIZAMOS LOS DATOS DEL PRODUCTO EN KARDEX ###################
		$sql3 = " UPDATE kardex_productos set "
		      ." salidas = ?, "
		      ." stockactual = ? "
			  ." WHERE "
			  ." codproceso = '".limpiar($_POST["codpedido"])."' and codproducto = '".limpiar($detalle[$i]['txtCodigo'])."';
			   ";
		$stmt = $this->dbh->prepare($sql3);
		$stmt->bindParam(1, $salidas);
		$stmt->bindParam(2, $stockactual);
		
		$salidas = number_format($detalle[$i]['cantidad']+$cantidad, 2, '.', '');
		$stockactual = number_format($existenciaproductobd-$cantventa, 2, '.', '');
		$stmt->execute();

############################### PROCESO PARA VERIFICAR LOS PRODUCTOS ######################################		



############################### PROCESO PARA VERIFICAR LOS INGREDIENTES ######################################	

		############## VERIFICO SSI EL PRODUCTO TIENE INGREDIENTES RELACIONADOS #################
	    $sql = "SELECT * FROM productosxingredientes WHERE codproducto = ?";
		$stmt = $this->dbh->prepare($sql);
		$stmt->execute(array(limpiar($detalle[$i]['txtCodigo'])));
		$num = $stmt->rowCount();
        if($num>0) {  

        	$sql = "SELECT * FROM productosxingredientes LEFT JOIN ingredientes ON productosxingredientes.codingrediente = ingredientes.codingrediente WHERE productosxingredientes.codproducto IN ('".$detalle[$i]['txtCodigo']."')";
        	foreach ($this->dbh->query($sql) as $row)
		    { 
			   $this->p[] = $row;

			   $cantracionbd = $row['cantracion'];
			   $codingredientebd = $row['codingrediente'];
			   $cantingredientebd = $row['cantingrediente'];
			   $precioventaingredientebd = $row['precioventa'];
			   $ivaingredientebd = $row['ivaingrediente'];
			   $descingredientebd = $row['descingrediente'];

			   ############## ACTUALIZO LOS DATOS DEL INGREDIENTE #################
			   $update = "UPDATE ingredientes set "
			   ." cantingrediente = ? "
			   ." WHERE "
			   ." codingrediente = ?;
			   ";
			   $stmt = $this->dbh->prepare($update);
			   $stmt->bindParam(1, $cantidadracion);
			   $stmt->bindParam(2, $codingredientebd);

			   $racion = number_format($cantracionbd*$detalle[$i]['cantidad'], 2, '.', '');
			   $cantidadracion = number_format($cantingredientebd-$racion, 2, '.', '');
			   $stmt->execute();

			   ############## VERIFICO LA EXISTENCIA DEL PRODUCTO EN ALMACEN #################
			   $sql = "SELECT salidas FROM kardex_ingredientes WHERE codproceso = '".limpiar($_POST['codpedido'])."' AND codingrediente = '".limpiar($codingredientebd)."'";
			   foreach ($this->dbh->query($sql) as $row)
			   {
			   	$this->p[] = $row;
			   }
			   $salidakardex = $row['salidas'];
		############## VERIFICO LA EXISTENCIA DEL PRODUCTO EN ALMACEN #################	

			   ########## ACTUALIZAMOS LOS DATOS DEL INGREDIENTE EN KARDEX ###################
			   $sql3 = " UPDATE kardex_ingredientes set "
			   ." salidas = ?, "
			   ." stockactual = ? "
			   ." WHERE "
			   ." codproceso = '".limpiar($_POST["codpedido"])."' and codingrediente = '".limpiar($codingredientebd)."';
			   ";
			   $stmt = $this->dbh->prepare($sql3);
			   $stmt->bindParam(1, $salidas);
			   $stmt->bindParam(2, $stockactual);

			   $racion = number_format($cantracionbd*$detalle[$i]['cantidad'], 2, '.', '');
			   $salidas = number_format($salidakardex+$racion, 2, '.', '');
			   
			   $substock = number_format($cantracionbd*$detalle[$i]['cantidad'], 2, '.', '');
			   $stockactual = number_format($cantingredientebd-$substock, 2, '.', '');
			   $stmt->execute();

		    }

		  }//fin de consulta de ingredientes de productos

############################### PROCESO PARA VERIFICAR LOS INGREDIENTES ######################################	


	       }
        }
        ####################### DESTRUYO LA VARIABLE DE SESSION #####################
	    unset($_SESSION["CarritoVenta"]);
        $this->dbh->commit();

        ############ SUMO LOS IMPORTE DE PRODUCTOS CON IVA ##############
		$sql3 = "SELECT SUM(valorneto) AS valorneto, SUM(valorneto2) AS valorneto2 FROM detalleventas WHERE codventa = '".limpiar($_POST["codventa"])."' AND ivaproducto = 'SI'";
			foreach ($this->dbh->query($sql3) as $row3)
			{
				$this->p[] = $row3;
			}
			$subtotalivasi = ($row3['valorneto']== "" ? "0.00" : $row3['valorneto']);
			$subtotalivasi2 = ($row3['valorneto2']== "" ? "0.00" : $row3['valorneto2']);

		############ SUMO LOS IMPORTE DE PRODUCTOS SIN IVA ##############
		$sql4 = "SELECT SUM(valorneto) AS valorneto, SUM(valorneto2) AS valorneto2 FROM detalleventas WHERE codventa = '".limpiar($_POST["codventa"])."' AND ivaproducto = 'NO'";
			foreach ($this->dbh->query($sql4) as $row4)
			{
				$this->p[] = $row4;
			}
			$subtotalivano = ($row4['valorneto']== "" ? "0.00" : $row4['valorneto']);
			$subtotalivano2 = ($row4['valorneto2']== "" ? "0.00" : $row4['valorneto2']);

        ############ ACTUALIZO LOS TOTALES EN LA COTIZACION ##############
		$sql = " UPDATE ventas SET "
		." codcliente = ?, "
		." subtotalivasi = ?, "
		." subtotalivano = ?, "
		." totaliva = ?, "
		." descuento = ?, "
		." totaldescuento = ?, "
		." totalpago = ?, "
		." totalpago2 = ?, "
		." montodevuelto = ? "
		." WHERE "
		." codventa = ?;
		";
		$stmt = $this->dbh->prepare($sql);
		$stmt->bindParam(1, $codcliente);
		$stmt->bindParam(2, $subtotalivasi);
		$stmt->bindParam(3, $subtotalivano);
		$stmt->bindParam(4, $totaliva);
		$stmt->bindParam(5, $descuento);
		$stmt->bindParam(6, $totaldescuento);
		$stmt->bindParam(7, $totalpago);
		$stmt->bindParam(8, $totalpago2);
		$stmt->bindParam(9, $montodevuelto);
		$stmt->bindParam(10, $codventa);

		$codcliente = limpiar($_POST["codcliente"]);
		$iva = $_POST["iva"]/100;
		$totaliva = number_format($subtotalivasi*$iva, 2, '.', '');
		$descuento = limpiar($_POST["descuento"]);
		$txtDescuento = $_POST["descuento"]/100;
		$total = number_format($subtotalivasi+$subtotalivano+$totaliva, 2, '.', '');
		$totaldescuento = number_format($total*$txtDescuento, 2, '.', '');
		$totalpago = number_format($total-$totaldescuento, 2, '.', '');
		$totalpago2 = number_format($subtotalivasi2+$subtotalivano2, 2, '.', '');
		$montodevuelto = number_format($totalpago > $_POST["pagado"] ? "0.00" : $_POST["pagado"]-$totalpago, 2, '.', '');
		$codventa = limpiar($_POST["codventa"]);
		$tipodocumento = limpiar($_POST["tipodocumento"]);
		$tipopago = limpiar($_POST["tipopago"]);
		$fecha = date("Y-m-d h:i:s");
		$stmt->execute();

    ############## AGREGAMOS O QUITAMOS LA DIFERENCIA EN CAJA ####################
    if (limpiar($_POST["tipopago"]=="CONTADO") && $totalpagobd != $totalpago){

        $sql = "SELECT ingresos FROM arqueocaja WHERE codcaja = '".limpiar($_POST["codcaja"])."' AND statusarqueo = '1'";
        foreach ($this->dbh->query($sql) as $row)
        {
            $this->p[] = $row;
        }
        $ingreso = ($row['ingresos']== "" ? "0.00" : $row['ingresos']);

        $sql = "UPDATE arqueocaja set "
        ." ingresos = ? "
        ." WHERE "
        ." codcaja = ? AND statusarqueo = '1';
        ";
        $stmt = $this->dbh->prepare($sql);
        $stmt->bindParam(1, $TxtTotal);
        $stmt->bindParam(2, $codcaja);

        $TxtTotal = number_format(($totalpagobd>$totalpago ? $ingreso-($totalpagobd-$totalpago) : $ingreso+($totalpago-$totalpagobd)), 2, '.', '');
        $codcaja = limpiar($_POST["codcaja"]);
        $stmt->execute();
    }
    ############# AGREGAMOS O QUITAMOS LA DIFERENCIA EN CAJA ####################

    ############## AGREGAMOS O QUITAMOS LA DIFERENCIA EN CAJA ##################
    if (limpiar($_POST["tipopago"]=="CREDITO") && $totalpagobd != $totalpago) {

        $sql = "SELECT creditos FROM arqueocaja WHERE codcaja = '".limpiar($_POST["codcaja"])."' AND statusarqueo = '1'";
        foreach ($this->dbh->query($sql) as $row)
        {
            $this->p[] = $row;
        }
        $credito = ($row['creditos']== "" ? "0.00" : $row['creditos']);

        $sql = " UPDATE arqueocaja SET "
        ." creditos = ? "
        ." where "
        ." codcaja = ? and statusarqueo = '1';
        ";
        $stmt = $this->dbh->prepare($sql);
        $stmt->bindParam(1, $TxtTotal);
        $stmt->bindParam(2, $codcaja);

        $TxtTotal = number_format(($totalpagobd>$totalpago ? $credito-($totalpagobd-$totalpago) : $credito+($totalpago-$totalpagobd)), 2, '.', '');
        $codcaja = limpiar($_POST["codcaja"]);
        $stmt->execute(); 

		$sql = "UPDATE creditosxclientes set"
		." montocredito = ? "
		." where "
		." codcliente = ?;
		";
		$stmt = $this->dbh->prepare($sql);
		$stmt->bindParam(1, $montocredito);
		$stmt->bindParam(2, $codcliente);

        $montocredito = number_format(($totalpagobd>$totalpago ? $montoactual-($totalpagobd-$totalpago) : $montoactual+($totalpago-$totalpagobd)), 2, '.', '');
		$codcliente = limpiar($_POST["codcliente"]);
		$stmt->execute(); 
    }
    ############## AGREGAMOS O QUITAMOS LA DIFERENCIA EN CAJA ##################

echo "<span class='fa fa-check-square-o'></span> LOS DETALLES DE PRODUCTOS FUERON AGREGADOS A LA VENTA EXITOSAMENTE <a href='reportepdf?codventa=".encrypt($codventa)."&tipo=".encrypt($tipodocumento)."' class='on-default' data-placement='left' data-toggle='tooltip' data-original-title='Imprimir Documento' target='_black' rel='noopener noreferrer'><font color='black'><strong>IMPRIMIR REPORTE</strong></font color></a></div>";

echo "<script>window.open('reportepdf?codventa=".encrypt($codventa)."&tipo=".encrypt($tipodocumento)."', '_blank');</script>";
	exit;
	}
}
########################## FUNCION AGREGAR DETALLES VENTAS ##########################

######################## FUNCION ELIMINAR DETALLES VENTAS #######################
public function EliminarDetallesVentas()
{
	
	self::SetNames();
	if ($_SESSION["acceso"]=="administrador") {

        ############ CONSULTO TOTAL ACTUAL ##############
		$sql = "SELECT codpedido, codcaja, codcliente, tipopago, totalpago FROM ventas WHERE codventa = '".limpiar(decrypt($_GET["codventa"]))."'";
		foreach ($this->dbh->query($sql) as $row)
		{
			$this->p[] = $row;
		}
		$codpedidobd = $row['codpedido'];
		$cajabd = $row['codcaja'];
		$clientebd = $row['codcliente'];
		$tipopagobd = $row['tipopago'];
		$totalpagobd = $row['totalpago'];

		################### VERIFICO MONTO DE CREDITO DEL CLIENTE ######################
		$sql = "SELECT montocredito FROM creditosxclientes WHERE codcliente = '".$clientebd."'";
		foreach ($this->dbh->query($sql) as $row)
		{
			$this->p[] = $row;
		}
		$monto = (empty($row['montocredito']) ? "0.00" : $row['montocredito']);


		$sql = "SELECT * FROM detalleventas WHERE codventa = ?";
		$stmt = $this->dbh->prepare($sql);
		$stmt->execute(array(decrypt($_GET["codventa"])));
		$num = $stmt->rowCount();
		if($num > 1)
		{

			$sql = "SELECT codproducto, cantventa, precioventa, ivaproducto, descproducto FROM detalleventas WHERE coddetalleventa = ?";
			$stmt = $this->dbh->prepare($sql);
			$stmt->execute(array(decrypt($_GET["coddetalleventa"])));
			$num = $stmt->rowCount();

			if($row = $stmt->fetch(PDO::FETCH_ASSOC))
			{
				$p[] = $row;
			}
			$codproductobd = $row['codproducto'];
			$cantidadbd = $row['cantventa'];
			$precioventabd = $row['precioventa'];
			$ivaproductobd = $row['ivaproducto'];
			$descproductobd = $row['descproducto'];


############################### PROCESO PARA VERIFICAR LOS PRODUCTOS ######################################		

			############## VERIFICO LA EXISTENCIA DEL PRODUCTO EN ALMACEN #################
			$sql2 = "SELECT existencia FROM productos WHERE codproducto = ?";
			$stmt = $this->dbh->prepare($sql2);
			$stmt->execute(array($codproductobd));
			$num = $stmt->rowCount();

			if($row = $stmt->fetch(PDO::FETCH_ASSOC))
			{
				$p[] = $row;
			}
			$existenciaproductobd = $row['existencia'];
			############## VERIFICO LA EXISTENCIA DEL PRODUCTO EN ALMACEN #################	

			########### ACTUALIZAMOS LA EXISTENCIA DE PRODUCTO EN ALMACEN #############
			$sql = "UPDATE productos SET "
			." existencia = ? "
			." WHERE "
			." codproducto = ?;
			";
			$stmt = $this->dbh->prepare($sql);
			$stmt->bindParam(1, $existencia);
			$stmt->bindParam(2, $codproductobd);

			$existencia = limpiar($existenciaproductobd+$cantidadbd);
			$stmt->execute();
			########### ACTUALIZAMOS LA EXISTENCIA DE PRODUCTO EN ALMACEN #############

		    ######## REGISTRAMOS LOS DATOS DEL PRODUCTO ELIMINADO EN KARDEX ###########
			$query = "INSERT INTO kardex_productos values (null, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?); ";
			$stmt = $this->dbh->prepare($query);
			$stmt->bindParam(1, $codpedidobd);
			$stmt->bindParam(2, $codcliente);
			$stmt->bindParam(3, $codproductobd);
			$stmt->bindParam(4, $movimiento);
			$stmt->bindParam(5, $entradas);
			$stmt->bindParam(6, $salidas);
			$stmt->bindParam(7, $devolucion);
			$stmt->bindParam(8, $stockactual);
			$stmt->bindParam(9, $ivaproducto);
			$stmt->bindParam(10, $descproducto);
			$stmt->bindParam(11, $precio);
			$stmt->bindParam(12, $documento);
			$stmt->bindParam(13, $fechakardex);		

			$codcliente = limpiar(decrypt($_GET["codcliente"]) == '' ? "0" : decrypt($_GET["codcliente"]));
			$movimiento = limpiar("DEVOLUCION");
			$entradas= limpiar("0");
			$salidas = limpiar("0");
			$devolucion = limpiar($cantidadbd);
			$stockactual = limpiar($existenciaproductobd+$cantidadbd);
			$precio = limpiar($precioventabd);
			$ivaproducto = limpiar($ivaproductobd);
			$descproducto = limpiar($descproductobd);
			$documento = limpiar("DEVOLUCION DETALLE VENTA");
			$fechakardex = limpiar(date("Y-m-d"));
			$stmt->execute();
############################### PROCESO PARA VERIFICAR LOS PRODUCTOS ######################################	



############################### PROCESO PARA VERIFICAR LOS INGREDIENTES ###################################	

			############## VERIFICO SI EL PRODUCTO TIENE INGREDIENTES RELACIONADOS #################
			$sql = "SELECT * FROM productosxingredientes WHERE codproducto = ?";
			$stmt = $this->dbh->prepare($sql);
			$stmt->execute(array(limpiar($codproductobd)));
			$num = $stmt->rowCount();
			if($num>0) {  

				$sql = "SELECT * FROM productosxingredientes LEFT JOIN ingredientes ON productosxingredientes.codingrediente = ingredientes.codingrediente WHERE productosxingredientes.codproducto IN ('".$codproductobd."')";
				foreach ($this->dbh->query($sql) as $row)
				{ 
					$this->p[] = $row;

					$cantracionbd = $row['cantracion'];
					$codingredientebd = $row['codingrediente'];
					$cantingredientebd = $row['cantingrediente'];
					$precioventaingredientebd = $row['precioventa'];
					$ivaingredientebd = $row['ivaingrediente'];
					$descingredientebd = $row['descingrediente'];

			   ############## ACTUALIZO LOS DATOS DEL INGREDIENTE #################
			   $update = "UPDATE ingredientes set "
			   ." cantingrediente = ? "
			   ." WHERE "
			   ." codingrediente = ?;
			   ";
			   $stmt = $this->dbh->prepare($update);
			   $stmt->bindParam(1, $cantidadracion);
			   $stmt->bindParam(2, $codingredientebd);

			   $racion = number_format($cantracionbd*$cantidadbd, 2, '.', '');
			   $cantidadracion = number_format($cantingredientebd+$racion, 2, '.', '');
			   $stmt->execute();
			   ############## ACTUALIZO LOS DATOS DEL INGREDIENTE #################

			   ############## REGISTRAMOS LOS DATOS DE INGREDIENTES EN KARDEX ###################
			   $query = "INSERT INTO kardex_ingredientes values (null, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?); ";
			   $stmt = $this->dbh->prepare($query);
			   $stmt->bindParam(1, $codpedidobd);
			   $stmt->bindParam(2, $codcliente);
			   $stmt->bindParam(3, $codingrediente);
			   $stmt->bindParam(4, $movimiento);
			   $stmt->bindParam(5, $entradas);
			   $stmt->bindParam(6, $salidas);
			   $stmt->bindParam(7, $devolucion);
			   $stmt->bindParam(8, $stockactual);
			   $stmt->bindParam(9, $ivaingrediente);
			   $stmt->bindParam(10, $descingrediente);
			   $stmt->bindParam(11, $precio);
			   $stmt->bindParam(12, $documento);
			   $stmt->bindParam(13, $fechakardex);		

			   $codcliente = limpiar(decrypt($_GET["codcliente"]) == '' ? "0" : decrypt($_GET["codcliente"]));
			   $codingrediente = limpiar($codingredientebd);
			   $movimiento = limpiar("DEVOLUCION");
			   $entradas = limpiar("0");
			   $salidas= limpiar("0");
			   $devolucion = limpiar($racion);
			   $stockactual = number_format($cantidadracion, 2, '.', '');
			   $precio = limpiar($precioventaingredientebd);
			   $ivaingrediente = limpiar($ivaingredientebd);
			   $descingrediente = limpiar($descingredientebd);
			   $documento = limpiar("DEVOLUCION DETALLE VENTA");
			   $fechakardex = limpiar(date("Y-m-d"));
			   $stmt->execute();

		        }
		    }//fin de consulta de ingredientes de productos

############################### PROCESO PARA VERIFICAR LOS INGREDIENTES ###################################	


			$sql = "DELETE FROM detalleventas WHERE coddetalleventa = ?";
			$stmt = $this->dbh->prepare($sql);
			$stmt->bindParam(1,$coddetalleventa);
			$coddetalleventa = decrypt($_GET["coddetalleventa"]);
			$stmt->execute();

		    ############ CONSULTO LOS TOTALES DE COTIZACIONES ##############
			$sql2 = "SELECT iva, descuento FROM ventas WHERE codventa = ?";
			$stmt = $this->dbh->prepare($sql2);
			$stmt->execute(array(decrypt($_GET["codventa"])));
			$num = $stmt->rowCount();

			if($row = $stmt->fetch(PDO::FETCH_ASSOC))
			{
				$paea[] = $row;
			}
			$iva = $paea[0]["iva"]/100;
			$descuento = $paea[0]["descuento"]/100;

            ############ SUMO LOS IMPORTE DE PRODUCTOS CON IVA ##############
			$sql3 = "SELECT SUM(valorneto) AS valorneto, SUM(valorneto2) AS valorneto2 FROM detalleventas WHERE codventa = '".limpiar(decrypt($_GET["codventa"]))."' AND ivaproducto = 'SI'";
			foreach ($this->dbh->query($sql3) as $row3)
			{
				$this->p[] = $row3;
			}
			$subtotalivasi = ($row3['valorneto']== "" ? "0.00" : $row3['valorneto']);
			$subtotalivasi2 = ($row3['valorneto2']== "" ? "0.00" : $row3['valorneto2']);

		    ############ SUMO LOS IMPORTE DE PRODUCTOS SIN IVA ##############
			$sql4 = "SELECT SUM(valorneto) AS valorneto, SUM(valorneto2) AS valorneto2 FROM detalleventas WHERE codventa = '".limpiar(decrypt($_GET["codventa"]))."' AND ivaproducto = 'NO'";
			foreach ($this->dbh->query($sql4) as $row4)
			{
				$this->p[] = $row4;
			}
			$subtotalivano = ($row4['valorneto']== "" ? "0.00" : $row4['valorneto']);
			$subtotalivano2 = ($row4['valorneto2']== "" ? "0.00" : $row4['valorneto2']);

            ############ ACTUALIZO LOS TOTALES EN LA COTIZACION ##############
			$sql = " UPDATE ventas SET "
			." subtotalivasi = ?, "
			." subtotalivano = ?, "
			." totaliva = ?, "
			." totaldescuento = ?, "
			." totalpago = ?, "
			." totalpago2= ? "
			." WHERE "
			." codventa = ?;
			";
			$stmt = $this->dbh->prepare($sql);
			$stmt->bindParam(1, $subtotalivasi);
			$stmt->bindParam(2, $subtotalivano);
			$stmt->bindParam(3, $totaliva);
			$stmt->bindParam(4, $totaldescuento);
			$stmt->bindParam(5, $totalpago);
			$stmt->bindParam(6, $totalpago2);
			$stmt->bindParam(7, $codventa);

			$totaliva= number_format($subtotalivasi*$iva, 2, '.', '');
			$total= number_format($subtotalivasi+$subtotalivano+$totaliva, 2, '.', '');
			$totaldescuento= number_format($total*$descuento, 2, '.', '');
			$totalpago= number_format($total-$totaldescuento, 2, '.', '');
			$totalpago2 = number_format($subtotalivasi+$subtotalivano, 2, '.', '');
			$codventa = limpiar(decrypt($_GET["codventa"]));
			$stmt->execute();

	#################### QUITAMOS LA DIFERENCIA EN CAJA ####################
	if ($tipopagobd=="CONTADO"){

		$sql = "SELECT ingresos FROM arqueocaja WHERE codcaja = '".$cajabd."' AND statusarqueo = '1'";
		foreach ($this->dbh->query($sql) as $row)
		{
			$this->p[] = $row;
		}
		$ingreso = ($row['ingresos']== "" ? "0.00" : $row['ingresos']);

		$sql = "UPDATE arqueocaja set "
		." ingresos = ? "
		." WHERE "
		." codcaja = ? AND statusarqueo = '1';
		";
		$stmt = $this->dbh->prepare($sql);
		$stmt->bindParam(1, $TxtTotal);
		$stmt->bindParam(2, $cajabd);

		$Calculo = number_format($totalpagobd-$totalpago, 2, '.', '');
		$TxtTotal = number_format($ingreso-$Calculo, 2, '.', '');
		$stmt->execute();
	}
    #################### QUITAMOS LA DIFERENCIA EN CAJA ####################
    
    ############## QUITAMOS LA DIFERENCIA EN CAJA ##################
	if ($tipopagobd=="CREDITO") {

		$sql = "SELECT creditos FROM arqueocaja WHERE codcaja = '".$cajabd."' AND statusarqueo = '1'";
		foreach ($this->dbh->query($sql) as $row)
		{
			$this->p[] = $row;
		}
		$credito = ($row['creditos']== "" ? "0.00" : $row['creditos']);

		$sql = " UPDATE arqueocaja SET "
		    ." creditos = ? "
		    ." where "
		    ." codcaja = ? and statusarqueo = '1';
		    ";
		    $stmt = $this->dbh->prepare($sql);
		    $stmt->bindParam(1, $TxtTotal);
		    $stmt->bindParam(2, $cajabd);

		    $Calculo = number_format($totalpagobd-$totalpago, 2, '.', '');
		    $TxtTotal = number_format($credito-$Calculo, 2, '.', '');
		    $stmt->execute();

		$sql = "UPDATE creditosxclientes set"
		    ." montocredito = ? "
			." where "
			." codcliente = ?;
			";
			$stmt = $this->dbh->prepare($sql);
			$stmt->bindParam(1, $montocredito);
			$stmt->bindParam(2, $clientebd);

			$montocredito = number_format($monto-$Calculo, 2, '.', '');
			$stmt->execute(); 	
	}
    ############## QUITAMOS LA DIFERENCIA EN CAJA ##################

			echo "1";
			exit;

		} else {

			echo "2";
			exit;
		} 

	} else {
		
		echo "3";
		exit;
	}	
}
##################### FUNCION ELIMINAR DETALLES VENTAS #################################

########################## FUNCION ELIMINAR VENTAS #################################
public function EliminarVentas()
	{
	
	self::SetNames();
	if ($_SESSION["acceso"]=="administrador") {

        ############ CONSULTO TOTAL ACTUAL ##############
		$sql = "SELECT codpedido, codcaja, codcliente, tipopago, totalpago FROM ventas WHERE codventa = '".limpiar(decrypt($_GET["codventa"]))."'";
		foreach ($this->dbh->query($sql) as $row)
			{
				$this->p[] = $row;
			}
		        $codpedidobd = $row['codpedido'];
		        $cajabd = $row['codcaja'];
		        $clientebd = $row['codcliente'];
		        $tipopagobd = $row['tipopago'];
		        $totalpagobd = $row['totalpago'];

		################### VERIFICO MONTO DE CREDITO DEL CLIENTE ######################
		$sql = "SELECT montocredito FROM creditosxclientes WHERE codcliente = '".$clientebd."'";
		foreach ($this->dbh->query($sql) as $row)
		{
			$this->p[] = $row;
		}
        $monto = (empty($row['montocredito']) ? "0.00" : $row['montocredito']);

	    $sql = "SELECT * FROM detalleventas WHERE codventa = '".limpiar(decrypt($_GET["codventa"]))."'";

	    foreach ($this->dbh->query($sql) as $row)
		{
			$this->p[] = $row;

			$codproductobd = $row['codproducto'];
			$cantidadbd = $row['cantventa'];
			$precioventabd = $row['precioventa'];
			$ivaproductobd = $row['ivaproducto'];
			$descproductobd = $row['descproducto'];


############################### PROCESO PARA VERIFICAR LOS PRODUCTOS ######################################		

			############## VERIFICO LA EXISTENCIA DEL PRODUCTO EN ALMACEN #################
			$sql2 = "SELECT existencia FROM productos WHERE codproducto = ?";
			$stmt = $this->dbh->prepare($sql2);
			$stmt->execute(array($codproductobd));
			$num = $stmt->rowCount();

			if($row = $stmt->fetch(PDO::FETCH_ASSOC))
			{
				$p[] = $row;
			}
			$existenciaproductobd = $row['existencia'];
			############## VERIFICO LA EXISTENCIA DEL PRODUCTO EN ALMACEN #################	

			########### ACTUALIZAMOS LA EXISTENCIA DE PRODUCTO EN ALMACEN ###############
			$sql = "UPDATE productos SET "
			." existencia = ? "
			." WHERE "
			." codproducto = ?;
			";
			$stmt = $this->dbh->prepare($sql);
			$stmt->bindParam(1, $existencia);
			$stmt->bindParam(2, $codproductobd);

			$existencia = limpiar($existenciaproductobd+$cantidadbd);
			$stmt->execute();
			########### ACTUALIZAMOS LA EXISTENCIA DE PRODUCTO EN ALMACEN #############

		    ########## REGISTRAMOS LOS DATOS DEL PRODUCTO ELIMINADO EN KARDEX ########
			$query = "INSERT INTO kardex_productos values (null, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?); ";
			$stmt = $this->dbh->prepare($query);
			$stmt->bindParam(1, $codpedidobd);
			$stmt->bindParam(2, $codcliente);
			$stmt->bindParam(3, $codproductobd);
			$stmt->bindParam(4, $movimiento);
			$stmt->bindParam(5, $entradas);
			$stmt->bindParam(6, $salidas);
			$stmt->bindParam(7, $devolucion);
			$stmt->bindParam(8, $stockactual);
			$stmt->bindParam(9, $ivaproducto);
			$stmt->bindParam(10, $descproducto);
			$stmt->bindParam(11, $precio);
			$stmt->bindParam(12, $documento);
			$stmt->bindParam(13, $fechakardex);		

			$codcliente = limpiar(decrypt($_GET["codcliente"]) == '' ? "0" : decrypt($_GET["codcliente"]));
			$movimiento = limpiar("DEVOLUCION");
			$entradas= limpiar("0");
			$salidas = limpiar("0");
			$devolucion = limpiar($cantidadbd);
			$stockactual = limpiar($existenciaproductobd+$cantidadbd);
			$precio = limpiar($precioventabd);
			$ivaproducto = limpiar($ivaproductobd);
			$descproducto = limpiar($descproductobd);
			$documento = limpiar("DEVOLUCION VENTA GENERAL");
			$fechakardex = limpiar(date("Y-m-d"));
			$stmt->execute();
############################### PROCESO PARA VERIFICAR LOS PRODUCTOS ######################################	



############################### PROCESO PARA VERIFICAR LOS INGREDIENTES ###################################	

			############## VERIFICO SI EL PRODUCTO TIENE INGREDIENTES RELACIONADOS #################
			$sql = "SELECT * FROM productosxingredientes WHERE codproducto = ?";
			$stmt = $this->dbh->prepare($sql);
			$stmt->execute(array(limpiar($codproductobd)));
			$num = $stmt->rowCount();
			if($num>0) {  

				$sql = "SELECT * FROM productosxingredientes LEFT JOIN ingredientes ON productosxingredientes.codingrediente = ingredientes.codingrediente WHERE productosxingredientes.codproducto IN ('".$codproductobd."')";
				foreach ($this->dbh->query($sql) as $row)
				{ 
					$this->p[] = $row;

					$cantracionbd = $row['cantracion'];
					$codingredientebd = $row['codingrediente'];
					$cantingredientebd = $row['cantingrediente'];
					$precioventaingredientebd = $row['precioventa'];
					$ivaingredientebd = $row['ivaingrediente'];
					$descingredientebd = $row['descingrediente'];

			   ############## ACTUALIZO LOS DATOS DEL INGREDIENTE #################
			   $update = "UPDATE ingredientes set "
			   ." cantingrediente = ? "
			   ." WHERE "
			   ." codingrediente = ?;
			   ";
			   $stmt = $this->dbh->prepare($update);
			   $stmt->bindParam(1, $cantidadracion);
			   $stmt->bindParam(2, $codingredientebd);

			   $racion = number_format($cantracionbd*$cantidadbd, 2, '.', '');
			   $cantidadracion = number_format($cantingredientebd+$racion, 2, '.', '');
			   $stmt->execute();
			   ############## ACTUALIZO LOS DATOS DEL INGREDIENTE #################

			   ############## REGISTRAMOS LOS DATOS DE INGREDIENTES EN KARDEX ###################
			   $query = "INSERT INTO kardex_ingredientes values (null, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?); ";
			   $stmt = $this->dbh->prepare($query);
			   $stmt->bindParam(1, $codpedidobd);
			   $stmt->bindParam(2, $codcliente);
			   $stmt->bindParam(3, $codingrediente);
			   $stmt->bindParam(4, $movimiento);
			   $stmt->bindParam(5, $entradas);
			   $stmt->bindParam(6, $salidas);
			   $stmt->bindParam(7, $devolucion);
			   $stmt->bindParam(8, $stockactual);
			   $stmt->bindParam(9, $ivaingrediente);
			   $stmt->bindParam(10, $descingrediente);
			   $stmt->bindParam(11, $precio);
			   $stmt->bindParam(12, $documento);
			   $stmt->bindParam(13, $fechakardex);		

			   $codcliente = limpiar(decrypt($_GET["codcliente"]) == '' ? "0" : decrypt($_GET["codcliente"]));
			   $codingrediente = limpiar($codingredientebd);
			   $movimiento = limpiar("DEVOLUCION");
			   $entradas = limpiar("0");
			   $salidas= limpiar("0");
			   $devolucion = limpiar($racion);
			   $stockactual = number_format($cantidadracion, 2, '.', '');
			   $precio = limpiar($precioventaingredientebd);
			   $ivaingrediente = limpiar($ivaingredientebd);
			   $descingrediente = limpiar($descingredientebd);
			   $documento = limpiar("DEVOLUCION VENTA GENERAL");
			   $fechakardex = limpiar(date("Y-m-d"));
			   $stmt->execute();

		        }
		    }//fin de consulta de ingredientes de productos

############################### PROCESO PARA VERIFICAR LOS INGREDIENTES ###################################	

		}//fin de detalles ventas

	#################### QUITAMOS LA DIFERENCIA EN CAJA ####################
	if ($tipopagobd=="CONTADO"){

		$sql = "SELECT ingresos FROM arqueocaja WHERE codcaja = '".$cajabd."' AND statusarqueo = '1'";
		foreach ($this->dbh->query($sql) as $row)
		{
			$this->p[] = $row;
		}
		$ingreso = ($row['ingresos']== "" ? "0.00" : $row['ingresos']);

		$sql = "UPDATE arqueocaja set "
		    ." ingresos = ? "
		    ." WHERE "
		    ." codcaja = ? AND statusarqueo = '1';
		    ";
		    $stmt = $this->dbh->prepare($sql);
		    $stmt->bindParam(1, $TxtTotal);
		    $stmt->bindParam(2, $cajabd);

            $TxtTotal = number_format($ingreso-$totalpagobd, 2, '.', '');
		    $stmt->execute();
	}
    #################### QUITAMOS LA DIFERENCIA EN CAJA ####################

    ############## QUITAMOS LA DIFERENCIA EN CAJA ##################
	if ($tipopagobd=="CREDITO") {

		$sql = "SELECT creditos FROM arqueocaja WHERE codcaja = '".$cajabd."' AND statusarqueo = '1'";
		foreach ($this->dbh->query($sql) as $row)
		{
			$this->p[] = $row;
		}
		$credito = ($row['creditos']== "" ? "0.00" : $row['creditos']);

		$sql = " UPDATE arqueocaja SET "
		    ." creditos = ? "
		    ." where "
		    ." codcaja = ? and statusarqueo = '1';
		    ";
		    $stmt = $this->dbh->prepare($sql);
		    $stmt->bindParam(1, $TxtTotal);
		    $stmt->bindParam(2, $cajabd);

		    $TxtTotal = number_format($credito-$totalpagobd, 2, '.', '');
		    $stmt->execute();

		$sql = "UPDATE creditosxclientes set"
		    ." montocredito = ? "
			." where "
			." codcliente = ?;
			";
			$stmt = $this->dbh->prepare($sql);
			$stmt->bindParam(1, $montocredito);
			$stmt->bindParam(2, $clientebd);

			$montocredito = number_format($monto-$totalpagobd, 2, '.', '');
			$stmt->execute(); 	
	}
    ############## QUITAMOS LA DIFERENCIA EN CAJA ##################

			$sql = "DELETE FROM ventas WHERE codventa = ?";
			$stmt = $this->dbh->prepare($sql);
			$stmt->bindParam(1,$codventa);
			$codventa = decrypt($_GET["codventa"]);
			$stmt->execute();

			$sql = "DELETE FROM detalleventas WHERE codventa = ?";
			$stmt = $this->dbh->prepare($sql);
			$stmt->bindParam(1,$codventa);
			$codventa = decrypt($_GET["codventa"]);
			$stmt->execute();

			echo "1";
			exit;

		} else {

			echo "2";
			exit;
		}
	}
####################### FUNCION ELIMINAR VENTAS #################################

########################## FUNCION LISTAR VENTAS DIARIAS ################################
public function BuscarVentasDiarias()
{
	self::SetNames();
      $sql = "SELECT 
		ventas.idventa, 
		ventas.codmesa,
		ventas.tipodocumento, 
		ventas.codventa, 
		ventas.codserie, 
		ventas.codautorizacion, 
		ventas.codcaja, 
		ventas.codcliente, 
		ventas.subtotalivasi, 
		ventas.subtotalivano, 
		ventas.iva, 
		ventas.totaliva, 
		ventas.descuento, 
		ventas.totaldescuento, 
		ventas.totalpago, 
		ventas.totalpago2, 
		ventas.tipopago, 
		ventas.formapago, 
		ventas.montopagado, 
		ventas.montopropina, 
		ventas.montodevuelto, 
		ventas.fechavencecredito, 
	    ventas.fechapagado,
		ventas.statusventa, 
		ventas.fechaventa, 
	    ventas.observaciones, 
		cajas.nrocaja,
		cajas.nomcaja,
		clientes.documcliente,
		clientes.dnicliente, 
		clientes.nomcliente, 
		clientes.tlfcliente, 
		clientes.id_provincia, 
		clientes.id_departamento, 
		clientes.direccliente, 
		clientes.emailcliente,
		documentos.documento,
		provincias.provincia,
		departamentos.departamento,
		mediospagos.mediopago,
		SUM(detalleventas.cantventa) as articulos 
		FROM (ventas LEFT JOIN detalleventas ON detalleventas.codventa=ventas.codventa)
		LEFT JOIN cajas ON ventas.codcaja = cajas.codcaja 
		LEFT JOIN mediospagos ON ventas.formapago = mediospagos.codmediopago 
		LEFT JOIN clientes ON ventas.codcliente = clientes.codcliente
		LEFT JOIN documentos ON clientes.documcliente = documentos.coddocumento
		LEFT JOIN provincias ON clientes.id_provincia = provincias.id_provincia 
		LEFT JOIN departamentos ON clientes.id_departamento = departamentos.id_departamento WHERE ventas.codigo = '".limpiar($_SESSION["codigo"])."' AND DATE_FORMAT(ventas.fechaventa,'%d-%m-%Y') = '".date("d-m-Y")."' AND ventas.statuspago = '0' GROUP BY detalleventas.codventa";
	foreach ($this->dbh->query($sql) as $row)
		{
			$this->p[] = $row;
		}
		    return $this->p;
			$this->dbh=null;
}
########################### FUNCION LISTAR VENTAS DIARIAS ############################

###################### FUNCION BUSQUEDA VENTAS POR CAJAS ###########################
	public function BuscarVentasxCajas() 
	{
		self::SetNames();
		$sql ="SELECT 
		ventas.idventa, 
		ventas.codmesa,
		ventas.tipodocumento, 
		ventas.codventa, 
		ventas.codserie, 
		ventas.codautorizacion, 
		ventas.codcaja, 
		ventas.codcliente, 
		ventas.subtotalivasi, 
		ventas.subtotalivano, 
		ventas.iva, 
		ventas.totaliva, 
		ventas.descuento, 
		ventas.totaldescuento, 
		ventas.totalpago, 
		ventas.totalpago2, 
		ventas.tipopago, 
		ventas.formapago, 
		ventas.montopagado, 
		ventas.montopropina, 
		ventas.montodevuelto, 
		ventas.fechavencecredito, 
	    ventas.fechapagado,
		ventas.statusventa, 
		ventas.fechaventa, 
	    ventas.observaciones, 
		cajas.nrocaja,
		cajas.nomcaja,
		clientes.documcliente,
		clientes.dnicliente, 
		clientes.nomcliente, 
		clientes.tlfcliente, 
		clientes.id_provincia, 
		clientes.id_departamento, 
		clientes.direccliente, 
		clientes.emailcliente,
		documentos.documento,
		provincias.provincia,
		departamentos.departamento,
		mediospagos.mediopago,
		SUM(detalleventas.cantventa) as articulos 
		FROM (ventas LEFT JOIN detalleventas ON detalleventas.codventa=ventas.codventa)
		LEFT JOIN cajas ON ventas.codcaja = cajas.codcaja 
		LEFT JOIN mediospagos ON ventas.formapago = mediospagos.codmediopago 
		LEFT JOIN clientes ON ventas.codcliente = clientes.codcliente
		LEFT JOIN documentos ON clientes.documcliente = documentos.coddocumento
		LEFT JOIN provincias ON clientes.id_provincia = provincias.id_provincia 
		LEFT JOIN departamentos ON clientes.id_departamento = departamentos.id_departamento
		 WHERE ventas.codcaja = ? AND DATE_FORMAT(ventas.fechaventa,'%Y-%m-%d') >= ? AND DATE_FORMAT(ventas.fechaventa,'%Y-%m-%d') <= ? AND ventas.statuspago = '0' GROUP BY detalleventas.codventa";
		$stmt = $this->dbh->prepare($sql);
		$stmt->bindValue(1, trim(decrypt($_GET['codcaja'])));
		$stmt->bindValue(2, trim(date("Y-m-d",strtotime($_GET['desde']))));
		$stmt->bindValue(3, trim(date("Y-m-d",strtotime($_GET['hasta']))));
		$stmt->execute();
		$num = $stmt->rowCount();
		if($num==0)
		{
	echo "<div class='alert alert-danger'>";
	echo "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>";
	echo "<center><span class='fa fa-info-circle'></span> NO SE ENCONTRARON VENTAS PARA LA CAJA SELECCIONADA</center>";
	echo "</div>";		
	exit;
		}
		else
		{
			while($row = $stmt->fetch(PDO::FETCH_ASSOC))
			{
				$this->p[]=$row;
			}
			return $this->p;
			$this->dbh=null;
		}
	}
###################### FUNCION BUSQUEDA VENTAS POR CAJAS ###########################

###################### FUNCION BUSQUEDA VENTAS POR FECHAS ###########################
	public function BuscarVentasxFechas() 
	{
		self::SetNames();
		$sql ="SELECT 
		ventas.idventa, 
		ventas.codmesa,
		ventas.tipodocumento, 
		ventas.codventa, 
		ventas.codserie, 
		ventas.codautorizacion, 
		ventas.codcaja, 
		ventas.codcliente, 
		ventas.subtotalivasi, 
		ventas.subtotalivano, 
		ventas.iva, 
		ventas.totaliva, 
		ventas.descuento, 
		ventas.totaldescuento, 
		ventas.totalpago, 
		ventas.totalpago2, 
		ventas.tipopago, 
		ventas.formapago, 
		ventas.montopagado, 
		ventas.montopropina, 
		ventas.montodevuelto, 
		ventas.fechavencecredito, 
	    ventas.fechapagado,
		ventas.statusventa, 
		ventas.fechaventa, 
	    ventas.observaciones,
		clientes.documcliente,
		clientes.dnicliente, 
		clientes.nomcliente, 
		clientes.tlfcliente, 
		clientes.id_provincia, 
		clientes.id_departamento, 
		clientes.direccliente, 
		clientes.emailcliente,
		documentos.documento,
		provincias.provincia,
		departamentos.departamento,
		mediospagos.mediopago,
		SUM(detalleventas.cantventa) as articulos 
		FROM (ventas LEFT JOIN detalleventas ON detalleventas.codventa=ventas.codventa)
		LEFT JOIN mediospagos ON ventas.formapago = mediospagos.codmediopago 
		LEFT JOIN clientes ON ventas.codcliente = clientes.codcliente
		LEFT JOIN documentos ON clientes.documcliente = documentos.coddocumento
		LEFT JOIN provincias ON clientes.id_provincia = provincias.id_provincia 
		LEFT JOIN departamentos ON clientes.id_departamento = departamentos.id_departamento
		 WHERE DATE_FORMAT(ventas.fechaventa,'%Y-%m-%d') >= ? AND DATE_FORMAT(ventas.fechaventa,'%Y-%m-%d') <= ? AND ventas.statuspago = '0' GROUP BY detalleventas.codventa";
		$stmt = $this->dbh->prepare($sql);
		$stmt->bindValue(1, trim(date("Y-m-d",strtotime($_GET['desde']))));
		$stmt->bindValue(2, trim(date("Y-m-d",strtotime($_GET['hasta']))));
		$stmt->execute();
		$num = $stmt->rowCount();
		if($num==0)
		{
	echo "<div class='alert alert-danger'>";
	echo "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>";
	echo "<center><span class='fa fa-info-circle'></span> NO SE ENCONTRARON VENTAS PARA EL RANGO DE FECHA INGRESADO</center>";
	echo "</div>";		
	exit;
		}
		else
		{
			while($row = $stmt->fetch(PDO::FETCH_ASSOC))
			{
				$this->p[]=$row;
			}
			return $this->p;
			$this->dbh=null;
		}
	}
###################### FUNCION BUSQUEDA VENTAS POR FECHAS ###########################

############################## FIN DE CLASE VENTAS ###################################













































###################################### CLASE CREDITOS ###################################

####################### FUNCION REGISTRAR PAGOS A CREDITOS #############################
	public function RegistrarPago()
	{
		self::SetNames();

		$sql = "SELECT * FROM arqueocaja INNER JOIN cajas ON arqueocaja.codcaja = cajas.codcaja INNER JOIN usuarios ON cajas.codigo = usuarios.codigo WHERE usuarios.codigo = ?";
		$stmt = $this->dbh->prepare($sql);
		$stmt->execute(array($_SESSION["codigo"]));
		$num = $stmt->rowCount();
		if($num==0)
		{
			echo "1";
			exit;
	    }
	    else if(empty($_POST["codcliente"]) or empty($_POST["codventa"]) or empty($_POST["montoabono"]))
		{
			echo "2";
			exit;
		} 
		else if($_POST["montoabono"] > $_POST["totaldebe"])
		{
			echo "3";
			exit;

		} else {

		################### VERIFICO MONTO DE CREDITO DEL CLIENTE ######################
		$sql = "SELECT montocredito FROM creditosxclientes WHERE codcliente = '".limpiar($_POST['codcliente'])."'";
		foreach ($this->dbh->query($sql) as $row)
		{
			$this->p[] = $row;
		}
        $monto = (empty($row['montocredito']) ? "0.00" : $row['montocredito']);

		$query = "INSERT INTO abonoscreditos values (null, ?, ?, ?, ?, ?); ";
		$stmt = $this->dbh->prepare($query);
		$stmt->bindParam(1, $codcaja);
		$stmt->bindParam(2, $codventa);
		$stmt->bindParam(3, $codcliente);
		$stmt->bindParam(4, $montoabono);
		$stmt->bindParam(5, $fechaabono);

		$codcaja = limpiar($_POST["codcaja"]);
		$codventa = limpiar($_POST["codventa"]);
		$codcliente = limpiar($_POST["codcliente"]);
		$montoabono = limpiar($_POST["montoabono"]);
		$fechaabono = limpiar(date("Y-m-d h:i:s"));
		$stmt->execute();
	
    ############## AGREGAMOS EL INGRESO DE PAGOS DE CREDITOS A CAJA ##############
		$sql = "SELECT ingresos FROM arqueocaja WHERE codcaja = '".limpiar($_POST["codcaja"])."' AND statusarqueo = '1'";
		foreach ($this->dbh->query($sql) as $row)
		{
			$this->p[] = $row;
		}
		$ingreso = ($row['ingresos']== "" ? "0.00" : $row['ingresos']);

		$sql = "UPDATE arqueocaja set "
		." ingresos = ? "
		." WHERE "
		." codcaja = ? AND statusarqueo = '1';
		";
		$stmt = $this->dbh->prepare($sql);
		$stmt->bindParam(1, $txtTotal);
		$stmt->bindParam(2, $codcaja);

		$txtTotal = number_format($_POST["montoabono"]+$ingreso, 2, '.', '');
		$codcaja = limpiar($_POST["codcaja"]);
		$stmt->execute();

		$sql = "UPDATE creditosxclientes set"
		." montocredito = ? "
		." where "
		." codcliente = ?;
		";
		$stmt = $this->dbh->prepare($sql);
		$stmt->bindParam(1, $montocredito);
		$stmt->bindParam(2, $codcliente);

        $montocredito = number_format($monto-$_POST["montoabono"], 2, '.', '');
		$codcliente = limpiar($_POST["codcliente"]);
		$stmt->execute(); 
	############## AGREGAMOS EL INGRESO DE PAGOS DE CREDITOS A CAJA ############

	############## ACTUALIZAMOS EL STATUS DE LA FACTURA ##################
		if($_POST["montoabono"] == $_POST["totaldebe"]) {

			$sql = "UPDATE ventas set "
			." statusventa = ?, "
			." fechapagado = ? "
			." WHERE "
			." codventa = ?;
			";
			$stmt = $this->dbh->prepare($sql);
			$stmt->bindParam(1, $statusventa);
			$stmt->bindParam(2, $fechapagado);
			$stmt->bindParam(3, $codventa);

			$statusventa = limpiar("PAGADA");
			$fechapagado = limpiar(date("Y-m-d"));
			$codventa = limpiar($_POST["codventa"]);
			$stmt->execute();
		}
    ############## ACTUALIZAMOS EL STATUS DE LA FACTURA ##################

		
echo "<span class='fa fa-check-square-o'></span> EL ABONO AL CR&Eacute;DITO DE VENTA HA SIDO REGISTRADO EXITOSAMENTE <a href='reportepdf?codventa=".encrypt($codventa)."&tipo=".encrypt("TICKETCREDITO")."' class='on-default' data-placement='left' data-toggle='tooltip' data-original-title='Imprimir Documento' target='_black' rel='noopener noreferrer'><font color='black'><strong>IMPRIMIR TICKET</strong></font color></a></div>";

echo "<script>window.open('reportepdf?codventa=".encrypt($codventa)."&tipo=".encrypt("TICKETCREDITO")."', '_blank');</script>";
	exit;
   }
}
##################### FUNCION REGISTRAR PAGOS A CREDITOS ###########################

###################### FUNCION LISTAR CREDITOS ####################### 
public function ListarCreditos()
{
	self::SetNames();
	$sql = "SELECT 
	ventas.codventa, 
	ventas.totalpago, 
	ventas.tipopago,
	ventas.statusventa,
	ventas.fechaventa, 
	ventas.fechavencecredito,
	ventas.fechapagado,
	clientes.codcliente,
	clientes.documcliente, 
	clientes.dnicliente, 
	clientes.nomcliente, 
	clientes.tlfcliente, 
	abonoscreditos.codventa as codigo, 
	abonoscreditos.fechaabono, 
	documentos.documento,
	SUM(abonoscreditos.montoabono) AS abonototal 
	FROM (ventas INNER JOIN clientes ON ventas.codcliente = clientes.codcliente)
	LEFT JOIN abonoscreditos ON ventas.codventa = abonoscreditos.codventa
	LEFT JOIN documentos ON clientes.documcliente = documentos.coddocumento
	WHERE ventas.tipopago ='CREDITO' GROUP BY ventas.codventa";
	foreach ($this->dbh->query($sql) as $row)
	{
		$this->p[] = $row;
	}
	return $this->p;
	$this->dbh=null;
}
###################### FUNCION LISTAR CREDITOS ####################### 

############################ FUNCION ID CREDITOS #################################
	public function CreditosPorId()
	{
		self::SetNames();
		$sql = " SELECT 
		ventas.idventa, 
		ventas.tipodocumento, 
		ventas.codventa, 
		ventas.codserie, 
		ventas.codautorizacion, 
		ventas.codcaja, 
		ventas.codcliente, 
		ventas.subtotalivasi, 
		ventas.subtotalivano, 
		ventas.iva, 
		ventas.totaliva, 
		ventas.descuento, 
		ventas.totaldescuento, 
		ventas.totalpago, 
		ventas.totalpago2, 
		ventas.tipopago, 
		ventas.formapago, 
		ventas.montopagado, 
		ventas.montodevuelto, 
		ventas.fechavencecredito, 
	    ventas.fechapagado,
		ventas.statusventa, 
		ventas.fechaventa,
	    ventas.observaciones,
		cajas.nrocaja,
		cajas.nomcaja,
		clientes.documcliente,
		clientes.dnicliente, 
		clientes.nomcliente, 
		clientes.tlfcliente, 
		clientes.id_provincia, 
		clientes.id_departamento, 
		clientes.direccliente, 
		clientes.emailcliente,
		documentos.documento,
		mediospagos.mediopago,
		usuarios.dni, 
		usuarios.nombres,
		provincias.provincia,
		departamentos.departamento,
		SUM(montoabono) AS abonototal
		FROM (ventas LEFT JOIN abonoscreditos ON ventas.codventa = abonoscreditos.codventa)
		LEFT JOIN clientes ON ventas.codcliente = clientes.codcliente
		LEFT JOIN documentos ON clientes.documcliente = documentos.coddocumento
		LEFT JOIN provincias ON clientes.id_provincia = provincias.id_provincia 
		LEFT JOIN departamentos ON clientes.id_departamento = departamentos.id_departamento 
		LEFT JOIN cajas ON abonoscreditos.codcaja = cajas.codcaja
		LEFT JOIN mediospagos ON ventas.formapago = mediospagos.codmediopago 
		LEFT JOIN usuarios ON cajas.codigo = usuarios.codigo
		WHERE ventas.codventa = ? GROUP BY abonoscreditos.codventa";
		$stmt = $this->dbh->prepare($sql);
		$stmt->execute(array(decrypt($_GET["codventa"])));
		$num = $stmt->rowCount();
		if($num==0)
		{
			echo "";
		}
		else
		{
			if($row = $stmt->fetch(PDO::FETCH_ASSOC))
			{
				$this->p[] = $row;
			}
			return $this->p;
			$this->dbh=null;
		}
	}
############################ FUNCION ID CREDITOS #################################
	
######################### FUNCION VER DETALLES VENTAS ############################
public function VerDetallesAbonos()
{
	self::SetNames();
	$sql = "SELECT * FROM abonoscreditos INNER JOIN ventas ON abonoscreditos.codventa = ventas.codventa LEFT JOIN cajas ON abonoscreditos.codcaja = cajas.codcaja WHERE abonoscreditos.codventa = ?";	
	$stmt = $this->dbh->prepare($sql);
	$stmt->bindValue(1, trim(decrypt($_GET["codventa"])));
	$stmt->execute();
	$num = $stmt->rowCount();
	if($num==0)
	{
		echo "";
	}
	else
	{
		while($row = $stmt->fetch(PDO::FETCH_ASSOC))
			{
				$this->p[]=$row;
			}
			return $this->p;
			$this->dbh=null;
		}
	}
########################## FUNCION VER DETALLES VENTAS ###########################

###################### FUNCION BUSQUEDA CREDITOS POR CLIENTES ###########################
	public function BuscarCreditosxClientes() 
	{
		self::SetNames();
		$sql = "SELECT 
	ventas.codventa, 
	ventas.totalpago, 
	ventas.tipopago,
	ventas.statusventa,
	ventas.fechaventa, 
	ventas.fechavencecredito,
	ventas.fechapagado,
	clientes.codcliente,
	clientes.documcliente, 
	clientes.dnicliente, 
	clientes.nomcliente, 
	clientes.tlfcliente, 
	abonoscreditos.codventa as codigo, 
	abonoscreditos.fechaabono, 
	documentos.documento,
	SUM(abonoscreditos.montoabono) AS abonototal  
	FROM (ventas INNER JOIN clientes ON ventas.codcliente = clientes.codcliente)
	LEFT JOIN abonoscreditos ON ventas.codventa = abonoscreditos.codventa 
	LEFT JOIN documentos ON clientes.documcliente = documentos.coddocumento
		WHERE ventas.codcliente = ? AND ventas.tipopago ='CREDITO' GROUP BY ventas.codventa";
		$stmt = $this->dbh->prepare($sql);
		$stmt->bindValue(1, trim($_GET['codcliente']));
		$stmt->execute();
		$num = $stmt->rowCount();
		if($num==0)
		{
	echo "<div class='alert alert-danger'>";
	echo "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>";
	echo "<center><span class='fa fa-info-circle'></span> NO SE ENCONTRARON CREDITOS PARA EL CLIENTE INGRESADO</center>";
	echo "</div>";		
	exit;
		}
		else
		{
			while($row = $stmt->fetch(PDO::FETCH_ASSOC))
			{
				$this->p[]=$row;
			}
			return $this->p;
			$this->dbh=null;
		}
	}
###################### FUNCION BUSQUEDA CREDITOS POR CLIENTES ###########################

###################### FUNCION BUSQUEDA CREDITOS POR FECHAS ###########################
	public function BuscarCreditosxFechas() 
	{
		self::SetNames();
		$sql = "SELECT 
	ventas.codventa, 
	ventas.totalpago, 
	ventas.tipopago,
	ventas.statusventa,
	ventas.fechaventa, 
	ventas.fechavencecredito,
	ventas.fechapagado,
	clientes.codcliente,
	clientes.documcliente, 
	clientes.dnicliente, 
	clientes.nomcliente, 
	clientes.tlfcliente, 
	abonoscreditos.codventa as codigo, 
	abonoscreditos.fechaabono, 
	documentos.documento,
	SUM(abonoscreditos.montoabono) AS abonototal  
	FROM (ventas INNER JOIN clientes ON ventas.codcliente = clientes.codcliente)
	LEFT JOIN abonoscreditos ON ventas.codventa = abonoscreditos.codventa
	LEFT JOIN documentos ON clientes.documcliente = documentos.coddocumento
	WHERE DATE_FORMAT(ventas.fechaventa,'%Y-%m-%d') >= ? AND DATE_FORMAT(ventas.fechaventa,'%Y-%m-%d') <= ?
	 AND ventas.tipopago ='CREDITO' GROUP BY ventas.codventa";
		$stmt = $this->dbh->prepare($sql);
		$stmt->bindValue(1, trim(date("Y-m-d",strtotime($_GET['desde']))));
		$stmt->bindValue(2, trim(date("Y-m-d",strtotime($_GET['hasta']))));
		$stmt->execute();
		$num = $stmt->rowCount();
		if($num==0)
		{
	echo "<div class='alert alert-danger'>";
	echo "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>";
	echo "<center><span class='fa fa-info-circle'></span> NO SE ENCONTRARON CREDITOS PARA EL RANGO DE FECHA INGRESADO</center>";
	echo "</div>";		
	exit;
		}
		else
		{
			while($row = $stmt->fetch(PDO::FETCH_ASSOC))
			{
				$this->p[]=$row;
			}
			return $this->p;
			$this->dbh=null;
		}
	}
###################### FUNCION BUSQUEDA CREDITOS POR FECHAS ###########################

###################################### CLASE CREDITOS ###################################


















########################## FUNCION PARA GRAFICOS #################################

#################### FUNCION SUMAR COMPRAS - VENTAS POR SUCURSALES ##################
public function GraficoxSucursal()
{
	self::SetNames();
    $sql = "SELECT 
    sucursales.codsucursal id,
	sucursales.razonsocial,
    pag.sumcompras,
    pag2.sumcotizacion,
    pag3.sumventas
     FROM
       sucursales
     LEFT JOIN
       ( SELECT
           codsucursal, SUM(totalpagoc) AS sumcompras         
           FROM compras WHERE DATE_FORMAT(fechaemision,'%Y') = '".date("Y")."' GROUP BY codsucursal) pag ON pag.codsucursal = sucursales.codsucursal  
     LEFT JOIN
       ( SELECT
           codsucursal, SUM(totalpago) AS sumcotizacion
         FROM cotizaciones WHERE DATE_FORMAT(fechacotizacion,'%Y') = '".date("Y")."' GROUP BY codsucursal) pag2 ON pag2.codsucursal = sucursales.codsucursal 
     LEFT JOIN
       ( SELECT
           codsucursal, SUM(totalpago) AS sumventas
         FROM ventas WHERE DATE_FORMAT(fechaventa,'%Y') = '".date("Y")."' GROUP BY codsucursal) pag3 ON pag3.codsucursal = sucursales.codsucursal GROUP BY id";
	foreach ($this->dbh->query($sql) as $row)
	{
		$this->p[] = $row;
	}
	return $this->p;
	$this->dbh=null;
}
################### FUNCION SUMAR COMPRAS - VENTAS POR SUCURSALES ####################

############# FUNCION SUMA DE COMPRAS Y VENTAS PARA GRAFICOS #############
 public function SumaCompras()
{
	self::SetNames();

	$sql ="SELECT  
	MONTH(fecharecepcion) mes, 
	SUM(totalpagoc) totalmes
	FROM compras 
	WHERE YEAR(fecharecepcion) = '".date('Y')."' AND MONTH(fecharecepcion) GROUP BY MONTH(fecharecepcion) ORDER BY 1";
	foreach ($this->dbh->query($sql) as $row)
	{
		$this->p[] = $row;
	}
	return $this->p;
	$this->dbh=null;
 
 } 

 public function SumaVentas()
{
	self::SetNames();

	$sql ="SELECT  
	MONTH(fechaventa) mes, 
	SUM(totalpago) totalmes
	FROM ventas 
	WHERE YEAR(fechaventa) = '".date('Y')."' AND MONTH(fechaventa) GROUP BY MONTH(fechaventa) ORDER BY 1";
	foreach ($this->dbh->query($sql) as $row)
	{
		$this->p[] = $row;
	}
	return $this->p;
	$this->dbh=null;
 
 }
############ FUNCION SUMA DE COMPRAS Y VENTAS PARA GRAFICOS ##############

########################### FUNCION PRODUCTOS 5 MAS VENDIDOS ############################
	public function ProductosMasVendidos()
	{
		self::SetNames();

	$sql = "SELECT productos.codproducto, productos.producto, productos.codcategoria, detalleventas.descproducto, detalleventas.precioventa, productos.existencia, categorias.nomcategoria, ventas.fechaventa, SUM(detalleventas.cantventa) as cantidad FROM (ventas LEFT JOIN detalleventas ON ventas.codventa=detalleventas.codventa) LEFT JOIN productos ON detalleventas.codproducto=productos.codproducto LEFT JOIN categorias ON categorias.codcategoria=productos.codcategoria GROUP BY detalleventas.codproducto, detalleventas.precioventa, detalleventas.descproducto ORDER BY productos.codproducto ASC LIMIT 8";
	foreach ($this->dbh->query($sql) as $row)
	{
		$this->p[] = $row;
	}
	return $this->p;
	$this->dbh=null;
}
########################## FUNCION 5 PRODUCTOS MAS VENDIDOS ###########################

########################## FUNCION SUMAR VENTAS POR USUARIOS ##########################
	public function VentasxUsuarios()
	{
		self::SetNames();
     $sql = "SELECT usuarios.codigo, usuarios.nombres, SUM(ventas.totalpago) as total FROM (usuarios INNER JOIN ventas ON usuarios.codigo=ventas.codigo) GROUP BY usuarios.codigo";
	foreach ($this->dbh->query($sql) as $row)
	{
		$this->p[] = $row;
	}
	return $this->p;
	$this->dbh=null;
}
########################## FUNCION SUMAR VENTAS POR USUARIOS #########################

#################### FUNCION PARA CONTAR REGISTROS ###################################
public function ContarRegistros()
	{
      self::SetNames();

$sql = "SELECT
(SELECT COUNT(codigo) FROM usuarios) AS usuarios,
(SELECT COUNT(codproducto) FROM productos) AS productos,
(SELECT COUNT(codingrediente) FROM ingredientes) AS ingredientes,
(SELECT COUNT(codcliente) FROM clientes) AS clientes,
(SELECT COUNT(codproveedor) FROM proveedores) AS proveedores,
(SELECT COUNT(codproducto) FROM productos WHERE existencia <= stockminimo) AS minimo,
(SELECT COUNT(codproducto) FROM productos WHERE fechaexpiracion != '0000-00-00' AND fechaexpiracion <= '".date("Y-m-d")."') AS vencidos,
(SELECT COUNT(idcompra) FROM compras) AS compras,
(SELECT COUNT(idventa) FROM ventas) AS ventas,
(SELECT COUNT(idcompra) FROM compras WHERE tipocompra = 'CREDITO' AND statuscompra = 'PENDIENTE' AND fechavencecredito <= '".date("Y-m-d")."') AS creditoscomprasvencidos,
(SELECT COUNT(idventa) FROM ventas WHERE tipopago = 'CREDITO' AND statusventa = 'PENDIENTE' AND fechavencecredito <= '".date("Y-m-d")."') AS creditosventasvencidos";

		foreach ($this->dbh->query($sql) as $row)
		{
			$this->p[] = $row;
		}
		return $this->p;
		$this->dbh=null;
}
##################### FUNCION PARA CONTAR REGISTROS ##################


########################### FUNCION PARA GRAFICOS #################################


}
############## TERMINA LA CLASE LOGIN ######################
?>