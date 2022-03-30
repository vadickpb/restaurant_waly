<?php
define('FPDF_FONTPATH','fpdf/font/');
require 'pdf_js.php';
 
//class PDF extends FPDF{ 
class PDF extends PDF_JavaScript
{
var $flowingBlockAttr;


########################## FUNCION PARA MOSTRAR EL FOOTER ########################
//Pie de página
function Footer2()
    {
  //Posición: a 2 cm del final
  $this->Ln();
  $this->SetY(-12);
  $this->SetFont('courier','B',10);
  //Número de página
  $this->Cell(190,5,'SOFT RESTAURANT (Administración, Compras y ventas)','T',0,'L');
  $this->AliasNbPages();
  $this->Cell(0,5,'Pagina '.$this->PageNo(),'T',1,'R');

      if($this->page>0)
        {
            // Page footer
            $this->_endpage();
        }

    } 
######################## FUNCION PARA MOSTRAR EL FOOTER ########################


######################## FUNCION PARA CARGAR AUTOPRINTF ########################
function AutoPrint($dialog=false)
{
    //Open the print dialog or start printing immediately on the standard printer
    $param=($dialog ? 'true' : 'false');
    $script="print($param);";
    $this->IncludeJS($script);
}

function AutoPrintToPrinter($server, $printer, $dialog=false)
{
    //Print on a shared printer (requires at least Acrobat 6)
    $script = "var pp = getPrintParams();";
    if($dialog)
        $script .= "pp.interactive = pp.constants.interactionLevel.full;";
    else
        $script .= "pp.interactive = pp.constants.interactionLevel.automatic;";
    $script .= "pp.printerName = '\\\\\\\\".$server."\\\\".$printer."';";
    $script .= "print(pp);";
    $this->IncludeJS($script);
}
######################## FUNCION PARA CARGAR AUTOPRINT ########################




############################### REPORTES DE ADMINISTRACION ##############################

########################## FUNCION LISTAR PROVINCIAS ##############################
      function TablaListarProvincias()
   {
    
    $logo = ( file_exists("./fotos/logo-pdf.png") == "" ? "./assets/images/null.png" : "./fotos/logo-pdf.png");
    $logo2 = ( file_exists("./fotos/logo-principal.png") == "" ? "./assets/images/null.png" : "./fotos/logo-principal.png");
    
    $con = new Login();
    $con = $con->ConfiguracionPorId(); 

    $this->Ln(2);
    $this->SetFont('Courier','B',11);
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es blanco)
    $this->SetFillColor(5, 130, 275); // establece el color del fondo de la celda (en este caso es NARANJA
    $this->Cell(45,5,$this->Image($logo, $this->GetX()+18, $this->GetY()+4, 22),0,0,'C');
    $this->Cell(100,5,"GESTIÓN Y CONTROL PARA RESTAURANTES",0,0,'C');
    $this->Cell(45,5,$this->Image($logo2, $this->GetX()+2, $this->GetY()+4, 40),0,0,'C');

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(100,5,utf8_decode($con[0]['nomsucursal']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(100,5,utf8_decode($con[0]['documsucursal'] == '0' ? "" : $con[0]['documento'])." ".utf8_decode($con[0]['cuit']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    if($con[0]['id_provincia']!='0'){

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(100,5,utf8_decode($provincia = ($con[0]['id_provincia'] == '0' ? "" : $con[0]['provincia'])." ".$departamento = ($con[0]['id_departamento'] == '0' ? "" : $con[0]['departamento'])),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    }

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(100,5,utf8_decode($con[0]['direcsucursal']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(100,5,"Nº DE TLF: ".utf8_decode($con[0]['tlfsucursal']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(100,5,utf8_decode($con[0]['correosucursal']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');
    $this->Ln(8);

    $this->SetFont('Courier','B',12);  
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es negro)
    $this->Cell(190,10,'LISTADO GENERAL DE PROVINCIAS',0,0,'C');

    $this->Ln();
    $this->SetFont('courier','B',10);
    $this->SetTextColor(255,255,255);  // Establece el color del texto (en este caso es BLANCO)
    $this->SetFillColor(230, 126, 34); // establece el color del fondo de la celda (en este caso es NARANJA)
    $this->Cell(10,8,'Nº',1,0,'C', True);
    $this->Cell(180,8,'NOMBRE DE PROVINCIA',1,1,'C', True);
    
    $tra = new Login();
    $reg = $tra->ListarProvincias();

    if($reg==""){
    echo "";      
    } else {
 
     /* AQUI DECLARO LAS COLUMNAS */
    $this->SetWidths(array(10,180));

    /* AQUI AGREGO LOS VALORES A MOSTRAR EN COLUMNAS */
    $a=1;
    for($i=0;$i<sizeof($reg);$i++){ 
    $this->SetFont('Courier','',10);  
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es negro)
    $this->Row(array($a++,utf8_decode($reg[$i]["provincia"])));
       }
   }


    $this->Ln(12); 
    $this->SetFont('courier','B',10);
    $this->Cell(4,6,'',0,0,'');
    $this->Cell(100,6,'ELABORADO: '.utf8_decode($_SESSION["nombres"]),0,0,'');
    $this->Cell(86,6,'RECIBIDO:____________________________',0,0,'');
    $this->Ln();
    $this->Cell(4,6,'',0,0,'');
    $this->Cell(100,6,'FECHA/HORA: '.date('d-m-Y h:i:s A'),0,0,'');
    $this->Cell(86,6,'',0,0,'');
    $this->Ln(4);
     }
########################## FUNCION LISTAR PROVINCIAS ##############################


########################## FUNCION LISTAR DEPARTAMENTOS ##############################
      function TablaListarDepartamentos()
   {
    
   $logo = ( file_exists("./fotos/logo-pdf.png") == "" ? "./assets/images/null.png" : "./fotos/logo-pdf.png");
    $logo2 = ( file_exists("./fotos/logo-principal.png") == "" ? "./assets/images/null.png" : "./fotos/logo-principal.png");
    
    $con = new Login();
    $con = $con->ConfiguracionPorId(); 

    $this->Ln(2);
    $this->SetFont('Courier','B',11);
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es blanco)
    $this->SetFillColor(5, 130, 275); // establece el color del fondo de la celda (en este caso es NARANJA
    $this->Cell(45,5,$this->Image($logo, $this->GetX()+18, $this->GetY()+4, 22),0,0,'C');
    $this->Cell(100,5,"GESTIÓN Y CONTROL PARA RESTAURANTES",0,0,'C');
    $this->Cell(45,5,$this->Image($logo2, $this->GetX()+2, $this->GetY()+4, 40),0,0,'C');

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(100,5,utf8_decode($con[0]['nomsucursal']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(100,5,utf8_decode($con[0]['documsucursal'] == '0' ? "" : $con[0]['documento'])." ".utf8_decode($con[0]['cuit']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    if($con[0]['id_provincia']!='0'){

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(100,5,utf8_decode($provincia = ($con[0]['id_provincia'] == '0' ? "" : $con[0]['provincia'])." ".$departamento = ($con[0]['id_departamento'] == '0' ? "" : $con[0]['departamento'])),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    }

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(100,5,utf8_decode($con[0]['direcsucursal']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(100,5,"Nº DE TLF: ".utf8_decode($con[0]['tlfsucursal']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(100,5,utf8_decode($con[0]['correosucursal']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');
    $this->Ln(8);

    $this->SetFont('Courier','B',12);  
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es negro)
    $this->Cell(190,10,'LISTADO GENERAL DE DEPARTAMENTOS',0,0,'C');

    $this->Ln();
    $this->SetFont('courier','B',10);
    $this->SetTextColor(255,255,255);  // Establece el color del texto (en este caso es BLANCO)
    $this->SetFillColor(230, 126, 34); // establece el color del fondo de la celda (en este caso es NARANJA)
    $this->Cell(15,8,'Nº',1,0,'C', True);
    $this->Cell(80,8,'NOMBRE DE PROVINCIA',1,0,'C', True);
    $this->Cell(95,8,'NOMBRE DE DEPARTAMENTO',1,1,'C', True);
    
    $tra = new Login();
    $reg = $tra->ListarDepartamentos();

    if($reg==""){
    echo "";      
    } else {
 
     /* AQUI DECLARO LAS COLUMNAS */
    $this->SetWidths(array(15,80,95));

    /* AQUI AGREGO LOS VALORES A MOSTRAR EN COLUMNAS */
    $a=1;
    for($i=0;$i<sizeof($reg);$i++){ 
    $this->SetFont('Courier','',10);  
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es negro)
    $this->Row(array($a++,utf8_decode($reg[$i]["provincia"]),utf8_decode($reg[$i]["departamento"])));
       }
   }


    $this->Ln(12); 
    $this->SetFont('courier','B',10);
    $this->Cell(4,6,'',0,0,'');
    $this->Cell(100,6,'ELABORADO: '.utf8_decode($_SESSION["nombres"]),0,0,'');
    $this->Cell(86,6,'RECIBIDO:____________________________',0,0,'');
    $this->Ln();
    $this->Cell(4,6,'',0,0,'');
    $this->Cell(100,6,'FECHA/HORA: '.date('d-m-Y h:i:s A'),0,0,'');
    $this->Cell(86,6,'',0,0,'');
    $this->Ln(4);
     }
########################## FUNCION LISTAR DEPARTAMENTOS ##############################


########################## FUNCION LISTAR TIPOS DE DOCUMENTOS ##########################
      function TablaListarDocumentos()
   {
    
    $logo = ( file_exists("./fotos/logo-pdf.png") == "" ? "./assets/images/null.png" : "./fotos/logo-pdf.png");
    $logo2 = ( file_exists("./fotos/logo-principal.png") == "" ? "./assets/images/null.png" : "./fotos/logo-principal.png");
    
    $con = new Login();
    $con = $con->ConfiguracionPorId(); 

    $this->Ln(2);
    $this->SetFont('Courier','B',11);
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es blanco)
    $this->SetFillColor(5, 130, 275); // establece el color del fondo de la celda (en este caso es NARANJA
    $this->Cell(45,5,$this->Image($logo, $this->GetX()+18, $this->GetY()+4, 22),0,0,'C');
    $this->Cell(100,5,"GESTIÓN Y CONTROL PARA RESTAURANTES",0,0,'C');
    $this->Cell(45,5,$this->Image($logo2, $this->GetX()+2, $this->GetY()+4, 40),0,0,'C');

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(100,5,utf8_decode($con[0]['nomsucursal']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(100,5,utf8_decode($con[0]['documsucursal'] == '0' ? "" : $con[0]['documento'])." ".utf8_decode($con[0]['cuit']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    if($con[0]['id_provincia']!='0'){

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(100,5,utf8_decode($provincia = ($con[0]['id_provincia'] == '0' ? "" : $con[0]['provincia'])." ".$departamento = ($con[0]['id_departamento'] == '0' ? "" : $con[0]['departamento'])),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    }

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(100,5,utf8_decode($con[0]['direcsucursal']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(100,5,"Nº DE TLF: ".utf8_decode($con[0]['tlfsucursal']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(100,5,utf8_decode($con[0]['correosucursal']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');
    $this->Ln(8);

    $this->SetFont('Courier','B',12);  
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es negro)
    $this->Cell(190,10,'LISTADO GENERAL DE DOCUMENTOS TRIBUTARIOS',0,0,'C');

    $this->Ln();
    $this->SetFont('courier','B',10);
    $this->SetTextColor(255,255,255);  // Establece el color del texto (en este caso es BLANCO)
    $this->SetFillColor(230, 126, 34); // establece el color del fondo de la celda (en este caso es NARANJA)
    $this->Cell(15,8,'Nº',1,0,'C', True);
    $this->Cell(50,8,'NOMBRE DE DOCUMENTO',1,0,'C', True);
    $this->Cell(125,8,'DESCRIPCIÓN DE DOCUMENTO',1,1,'C', True);
    
    $tra = new Login();
    $reg = $tra->ListarDocumentos();

    if($reg==""){
    echo "";      
    } else {
 
     /* AQUI DECLARO LAS COLUMNAS */
    $this->SetWidths(array(15,50,125));

    /* AQUI AGREGO LOS VALORES A MOSTRAR EN COLUMNAS */
    $a=1;
    for($i=0;$i<sizeof($reg);$i++){ 
    $this->SetFont('Courier','',10);  
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es negro)
    $this->Row(array($a++,utf8_decode($reg[$i]["documento"]),utf8_decode($reg[$i]["descripcion"])));
       }
   }


    $this->Ln(12); 
    $this->SetFont('courier','B',10);
    $this->Cell(4,6,'',0,0,'');
    $this->Cell(100,6,'ELABORADO: '.utf8_decode($_SESSION["nombres"]),0,0,'');
    $this->Cell(86,6,'RECIBIDO:____________________________',0,0,'');
    $this->Ln();
    $this->Cell(4,6,'',0,0,'');
    $this->Cell(100,6,'FECHA/HORA: '.date('d-m-Y h:i:s A'),0,0,'');
    $this->Cell(86,6,'',0,0,'');
    $this->Ln(4);
     }
########################## FUNCION LISTAR TIPOS DE DOCUMENTOS ##########################

########################## FUNCION LISTAR TIPOS DE MONEDA ##############################
      function TablaListarTiposMonedas()
   {
    
    $logo = ( file_exists("./fotos/logo-pdf.png") == "" ? "./assets/images/null.png" : "./fotos/logo-pdf.png");
    $logo2 = ( file_exists("./fotos/logo-principal.png") == "" ? "./assets/images/null.png" : "./fotos/logo-principal.png");
    
    $con = new Login();
    $con = $con->ConfiguracionPorId(); 

    $this->Ln(2);
    $this->SetFont('Courier','B',11);
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es blanco)
    $this->SetFillColor(5, 130, 275); // establece el color del fondo de la celda (en este caso es NARANJA
    $this->Cell(45,5,$this->Image($logo, $this->GetX()+18, $this->GetY()+4, 22),0,0,'C');
    $this->Cell(100,5,"GESTIÓN Y CONTROL PARA RESTAURANTES",0,0,'C');
    $this->Cell(45,5,$this->Image($logo2, $this->GetX()+2, $this->GetY()+4, 40),0,0,'C');

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(100,5,utf8_decode($con[0]['nomsucursal']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(100,5,utf8_decode($con[0]['documsucursal'] == '0' ? "" : $con[0]['documento'])." ".utf8_decode($con[0]['cuit']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    if($con[0]['id_provincia']!='0'){

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(100,5,utf8_decode($provincia = ($con[0]['id_provincia'] == '0' ? "" : $con[0]['provincia'])." ".$departamento = ($con[0]['id_departamento'] == '0' ? "" : $con[0]['departamento'])),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    }

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(100,5,utf8_decode($con[0]['direcsucursal']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(100,5,"Nº DE TLF: ".utf8_decode($con[0]['tlfsucursal']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(100,5,utf8_decode($con[0]['correosucursal']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');
    $this->Ln(8);

    $this->SetFont('Courier','B',12);  
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es negro)
    $this->Cell(190,10,'LISTADO GENERAL DE TIPOS DE MONEDA',0,0,'C');

    $this->Ln();
    $this->SetFont('courier','B',10);
    $this->SetTextColor(255,255,255);  // Establece el color del texto (en este caso es BLANCO)
    $this->SetFillColor(230, 126, 34); // establece el color del fondo de la celda (en este caso es NARANJA)
    $this->Cell(15,8,'Nº',1,0,'C', True);
    $this->Cell(85,8,'NOMBRE DE MONEDA',1,0,'C', True);
    $this->Cell(45,8,'SIGLAS',1,0,'C', True);
    $this->Cell(45,8,'SIMBOLO',1,1,'C', True);
    
    $tra = new Login();
    $reg = $tra->ListarTipoMoneda();

    if($reg==""){
    echo "";      
    } else {
 
     /* AQUI DECLARO LAS COLUMNAS */
    $this->SetWidths(array(15,85,45,45));

    /* AQUI AGREGO LOS VALORES A MOSTRAR EN COLUMNAS */
    $a=1;
    for($i=0;$i<sizeof($reg);$i++){ 
    $this->SetFont('Courier','',10);  
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es negro)
    $this->Row(array($a++,utf8_decode($reg[$i]["moneda"]),utf8_decode($reg[$i]["siglas"]),utf8_decode($reg[$i]["simbolo"])));
       }
   }


    $this->Ln(12); 
    $this->SetFont('courier','B',10);
    $this->Cell(4,6,'',0,0,'');
    $this->Cell(100,6,'ELABORADO: '.utf8_decode($_SESSION["nombres"]),0,0,'');
    $this->Cell(86,6,'RECIBIDO:____________________________',0,0,'');
    $this->Ln();
    $this->Cell(4,6,'',0,0,'');
    $this->Cell(100,6,'FECHA/HORA: '.date('d-m-Y h:i:s A'),0,0,'');
    $this->Cell(86,6,'',0,0,'');
    $this->Ln(4);
     }
########################## FUNCION LISTAR TIPOS DE MONEDA ##############################

########################## FUNCION LISTAR TIPOS DE CAMBIO ##############################
      function TablaListarTiposCambio()
   {
    
    $logo = ( file_exists("./fotos/logo-pdf.png") == "" ? "./assets/images/null.png" : "./fotos/logo-pdf.png");
    $logo2 = ( file_exists("./fotos/logo-principal.png") == "" ? "./assets/images/null.png" : "./fotos/logo-principal.png");
    
    $con = new Login();
    $con = $con->ConfiguracionPorId(); 

    $this->Ln(2);
    $this->SetFont('Courier','B',11);
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es blanco)
    $this->SetFillColor(5, 130, 275); // establece el color del fondo de la celda (en este caso es NARANJA
    $this->Cell(45,5,$this->Image($logo, $this->GetX()+18, $this->GetY()+4, 22),0,0,'C');
    $this->Cell(100,5,"GESTIÓN Y CONTROL PARA RESTAURANTES",0,0,'C');
    $this->Cell(45,5,$this->Image($logo2, $this->GetX()+2, $this->GetY()+4, 40),0,0,'C');

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(100,5,utf8_decode($con[0]['nomsucursal']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(100,5,utf8_decode($con[0]['documsucursal'] == '0' ? "" : $con[0]['documento'])." ".utf8_decode($con[0]['cuit']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    if($con[0]['id_provincia']!='0'){

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(100,5,utf8_decode($provincia = ($con[0]['id_provincia'] == '0' ? "" : $con[0]['provincia'])." ".$departamento = ($con[0]['id_departamento'] == '0' ? "" : $con[0]['departamento'])),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    }

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(100,5,utf8_decode($con[0]['direcsucursal']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(100,5,"Nº DE TLF: ".utf8_decode($con[0]['tlfsucursal']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(100,5,utf8_decode($con[0]['correosucursal']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');
    $this->Ln(8);

    $this->SetFont('Courier','B',12);  
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es negro)
    $this->Cell(190,10,'LISTADO GENERAL DE TIPOS DE CAMBIO',0,0,'C');

    $this->Ln();
    $this->SetFont('courier','B',10);
    $this->SetTextColor(255,255,255);  // Establece el color del texto (en este caso es BLANCO)
    $this->SetFillColor(230, 126, 34); // establece el color del fondo de la celda (en este caso es NARANJA)
    $this->Cell(15,8,'Nº',1,0,'C', True);
    $this->Cell(70,8,'DESCRIPCIÓN DE CAMBIO',1,0,'C', True);
    $this->Cell(35,8,'MONTO DE CAMBIO',1,0,'C', True);
    $this->Cell(35,8,'TIPO DE MONEDA',1,0,'C', True);
    $this->Cell(35,8,'FECHA DE INGRESO',1,1,'C', True);
    
    $tra = new Login();
    $reg = $tra->ListarTipoCambio();

    if($reg==""){
    echo "";      
    } else {
 
     /* AQUI DECLARO LAS COLUMNAS */
    $this->SetWidths(array(15,70,35,35,35));

    /* AQUI AGREGO LOS VALORES A MOSTRAR EN COLUMNAS */
    $a=1;
    for($i=0;$i<sizeof($reg);$i++){ 
    $this->SetFont('Courier','',10);  
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es negro)
    $this->Row(array($a++,utf8_decode($reg[$i]["descripcioncambio"]),utf8_decode($reg[$i]["montocambio"]),utf8_decode($reg[$i]['moneda']."/".$reg[$i]['siglas']),utf8_decode(date("d-m-Y",strtotime($reg[$i]['fechacambio'])))));
       }
   }


    $this->Ln(12); 
    $this->SetFont('courier','B',10);
    $this->Cell(4,6,'',0,0,'');
    $this->Cell(100,6,'ELABORADO: '.utf8_decode($_SESSION["nombres"]),0,0,'');
    $this->Cell(86,6,'RECIBIDO:____________________________',0,0,'');
    $this->Ln();
    $this->Cell(4,6,'',0,0,'');
    $this->Cell(100,6,'FECHA/HORA: '.date('d-m-Y h:i:s A'),0,0,'');
    $this->Cell(86,6,'',0,0,'');
    $this->Ln(4);
     }
########################## FUNCION LISTAR TIPOS DE CAMBIO ##############################

########################## FUNCION LISTAR MEDIOS DE PAGO ##############################
      function TablaListarMediosPagos()
   {
    
    $logo = ( file_exists("./fotos/logo-pdf.png") == "" ? "./assets/images/null.png" : "./fotos/logo-pdf.png");
    $logo2 = ( file_exists("./fotos/logo-principal.png") == "" ? "./assets/images/null.png" : "./fotos/logo-principal.png");
    
    $con = new Login();
    $con = $con->ConfiguracionPorId(); 

    $this->Ln(2);
    $this->SetFont('Courier','B',11);
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es blanco)
    $this->SetFillColor(5, 130, 275); // establece el color del fondo de la celda (en este caso es NARANJA
    $this->Cell(45,5,$this->Image($logo, $this->GetX()+18, $this->GetY()+4, 22),0,0,'C');
    $this->Cell(100,5,"GESTIÓN Y CONTROL PARA RESTAURANTES",0,0,'C');
    $this->Cell(45,5,$this->Image($logo2, $this->GetX()+2, $this->GetY()+4, 40),0,0,'C');

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(100,5,utf8_decode($con[0]['nomsucursal']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(100,5,utf8_decode($con[0]['documsucursal'] == '0' ? "" : $con[0]['documento'])." ".utf8_decode($con[0]['cuit']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    if($con[0]['id_provincia']!='0'){

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(100,5,utf8_decode($provincia = ($con[0]['id_provincia'] == '0' ? "" : $con[0]['provincia'])." ".$departamento = ($con[0]['id_departamento'] == '0' ? "" : $con[0]['departamento'])),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    }

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(100,5,utf8_decode($con[0]['direcsucursal']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(100,5,"Nº DE TLF: ".utf8_decode($con[0]['tlfsucursal']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(100,5,utf8_decode($con[0]['correosucursal']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');
    $this->Ln(8);

    $this->SetFont('Courier','B',12);  
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es negro)
    $this->Cell(190,10,'LISTADO GENERAL DE MEDIOS DE PAGO',0,0,'C');

    $this->Ln();
    $this->SetFont('courier','B',10);
    $this->SetTextColor(255,255,255);  // Establece el color del texto (en este caso es BLANCO)
    $this->SetFillColor(230, 126, 34); // establece el color del fondo de la celda (en este caso es NARANJA)
    $this->Cell(15,8,'Nº',1,0,'C', True);
    $this->Cell(175,8,'NOMBRE DE PAGO',1,1,'C', True);
    
    $tra = new Login();
    $reg = $tra->ListarMediosPagos();

    if($reg==""){
    echo "";      
    } else {
 
     /* AQUI DECLARO LAS COLUMNAS */
    $this->SetWidths(array(15,175));

    /* AQUI AGREGO LOS VALORES A MOSTRAR EN COLUMNAS */
    $a=1;
    for($i=0;$i<sizeof($reg);$i++){ 
    $this->SetFont('Courier','',10);  
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es negro)
    $this->Row(array($a++,utf8_decode($reg[$i]["mediopago"])));
       }
   }


    $this->Ln(12); 
    $this->SetFont('courier','B',10);
    $this->Cell(4,6,'',0,0,'');
    $this->Cell(100,6,'ELABORADO: '.utf8_decode($_SESSION["nombres"]),0,0,'');
    $this->Cell(86,6,'RECIBIDO:____________________________',0,0,'');
    $this->Ln();
    $this->Cell(4,6,'',0,0,'');
    $this->Cell(100,6,'FECHA/HORA: '.date('d-m-Y h:i:s A'),0,0,'');
    $this->Cell(86,6,'',0,0,'');
    $this->Ln(4);
     }
########################## FUNCION LISTAR MEDIOS DE PAGO ##############################

########################## FUNCION LISTAR IMPUESTOS ##############################
      function TablaListarImpuestos()
   {
    
    $logo = ( file_exists("./fotos/logo-pdf.png") == "" ? "./assets/images/null.png" : "./fotos/logo-pdf.png");
    $logo2 = ( file_exists("./fotos/logo-principal.png") == "" ? "./assets/images/null.png" : "./fotos/logo-principal.png");
    
    $con = new Login();
    $con = $con->ConfiguracionPorId(); 

    $this->Ln(2);
    $this->SetFont('Courier','B',11);
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es blanco)
    $this->SetFillColor(5, 130, 275); // establece el color del fondo de la celda (en este caso es NARANJA
    $this->Cell(45,5,$this->Image($logo, $this->GetX()+18, $this->GetY()+4, 22),0,0,'C');
    $this->Cell(100,5,"GESTIÓN Y CONTROL PARA RESTAURANTES",0,0,'C');
    $this->Cell(45,5,$this->Image($logo2, $this->GetX()+2, $this->GetY()+4, 40),0,0,'C');

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(100,5,utf8_decode($con[0]['nomsucursal']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(100,5,utf8_decode($con[0]['documsucursal'] == '0' ? "" : $con[0]['documento'])." ".utf8_decode($con[0]['cuit']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    if($con[0]['id_provincia']!='0'){

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(100,5,utf8_decode($provincia = ($con[0]['id_provincia'] == '0' ? "" : $con[0]['provincia'])." ".$departamento = ($con[0]['id_departamento'] == '0' ? "" : $con[0]['departamento'])),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    }

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(100,5,utf8_decode($con[0]['direcsucursal']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(100,5,"Nº DE TLF: ".utf8_decode($con[0]['tlfsucursal']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(100,5,utf8_decode($con[0]['correosucursal']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');
    $this->Ln(8);

    $this->SetFont('Courier','B',12);  
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es negro)
    $this->Cell(190,10,'LISTADO GENERAL DE IMPUESTOS',0,0,'C');

    $this->Ln();
    $this->SetFont('courier','B',10);
    $this->SetTextColor(255,255,255);  // Establece el color del texto (en este caso es BLANCO)
    $this->SetFillColor(230, 126, 34); // establece el color del fondo de la celda (en este caso es NARANJA)
    $this->Cell(15,8,'Nº',1,0,'C', True);
    $this->Cell(70,8,'NOMBRE DE IMPUESTO',1,0,'C', True);
    $this->Cell(35,8,'VALOR(%)',1,0,'C', True);
    $this->Cell(35,8,'STATUS',1,0,'C', True);
    $this->Cell(35,8,'FECHA DE INGRESO',1,1,'C', True);
    
    $tra = new Login();
    $reg = $tra->ListarImpuestos();

    if($reg==""){
    echo "";      
    } else {
 
     /* AQUI DECLARO LAS COLUMNAS */
    $this->SetWidths(array(15,70,35,35,35));

    /* AQUI AGREGO LOS VALORES A MOSTRAR EN COLUMNAS */
    $a=1;
    for($i=0;$i<sizeof($reg);$i++){ 
    $this->SetFont('Courier','',10);  
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es negro)
    $this->Row(array($a++,utf8_decode($reg[$i]["nomimpuesto"]),utf8_decode($reg[$i]["valorimpuesto"]),utf8_decode($reg[$i]['statusimpuesto']),utf8_decode(date("d-m-Y",strtotime($reg[$i]['fechaimpuesto'])))));
       }
   }


    $this->Ln(12); 
    $this->SetFont('courier','B',10);
    $this->Cell(4,6,'',0,0,'');
    $this->Cell(100,6,'ELABORADO: '.utf8_decode($_SESSION["nombres"]),0,0,'');
    $this->Cell(86,6,'RECIBIDO:____________________________',0,0,'');
    $this->Ln();
    $this->Cell(4,6,'',0,0,'');
    $this->Cell(100,6,'FECHA/HORA: '.date('d-m-Y h:i:s A'),0,0,'');
    $this->Cell(86,6,'',0,0,'');
    $this->Ln(4);
     }
########################## FUNCION LISTAR IMPUESTOS ##############################

########################## FUNCION LISTAR SALAS ##############################
      function TablaListarSalas()
   {
    
    $logo = ( file_exists("./fotos/logo-pdf.png") == "" ? "./assets/images/null.png" : "./fotos/logo-pdf.png");
    $logo2 = ( file_exists("./fotos/logo-principal.png") == "" ? "./assets/images/null.png" : "./fotos/logo-principal.png");
    
    $con = new Login();
    $con = $con->ConfiguracionPorId(); 

    $this->Ln(2);
    $this->SetFont('Courier','B',11);
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es blanco)
    $this->SetFillColor(5, 130, 275); // establece el color del fondo de la celda (en este caso es NARANJA
    $this->Cell(45,5,$this->Image($logo, $this->GetX()+18, $this->GetY()+4, 22),0,0,'C');
    $this->Cell(100,5,"GESTIÓN Y CONTROL PARA RESTAURANTES",0,0,'C');
    $this->Cell(45,5,$this->Image($logo2, $this->GetX()+2, $this->GetY()+4, 40),0,0,'C');

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(100,5,utf8_decode($con[0]['nomsucursal']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(100,5,utf8_decode($con[0]['documsucursal'] == '0' ? "" : $con[0]['documento'])." ".utf8_decode($con[0]['cuit']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    if($con[0]['id_provincia']!='0'){

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(100,5,utf8_decode($provincia = ($con[0]['id_provincia'] == '0' ? "" : $con[0]['provincia'])." ".$departamento = ($con[0]['id_departamento'] == '0' ? "" : $con[0]['departamento'])),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    }

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(100,5,utf8_decode($con[0]['direcsucursal']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(100,5,"Nº DE TLF: ".utf8_decode($con[0]['tlfsucursal']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(100,5,utf8_decode($con[0]['correosucursal']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');
    $this->Ln(8);

    $this->SetFont('Courier','B',12);  
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es negro)
    $this->Cell(190,10,'LISTADO GENERAL DE SALAS',0,0,'C');

    $this->Ln();
    $this->SetFont('courier','B',10);
    $this->SetTextColor(255,255,255);  // Establece el color del texto (en este caso es BLANCO)
    $this->SetFillColor(230, 126, 34); // establece el color del fondo de la celda (en este caso es NARANJA)
    $this->Cell(15,8,'Nº',1,0,'C', True);
    $this->Cell(175,8,'NOMBRE DE SALA',1,1,'C', True);
    
    $tra = new Login();
    $reg = $tra->ListarSalas();

    if($reg==""){
    echo "";      
    } else {
 
     /* AQUI DECLARO LAS COLUMNAS */
    $this->SetWidths(array(15,175));

    /* AQUI AGREGO LOS VALORES A MOSTRAR EN COLUMNAS */
    $a=1;
    for($i=0;$i<sizeof($reg);$i++){ 
    $this->SetFont('Courier','',10);  
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es negro)
    $this->Row(array($a++,utf8_decode($reg[$i]["nomsala"])));
       }
   }


    $this->Ln(12); 
    $this->SetFont('courier','B',10);
    $this->Cell(4,6,'',0,0,'');
    $this->Cell(100,6,'ELABORADO: '.utf8_decode($_SESSION["nombres"]),0,0,'');
    $this->Cell(86,6,'RECIBIDO:____________________________',0,0,'');
    $this->Ln();
    $this->Cell(4,6,'',0,0,'');
    $this->Cell(100,6,'FECHA/HORA: '.date('d-m-Y h:i:s A'),0,0,'');
    $this->Cell(86,6,'',0,0,'');
    $this->Ln(4);
     }
########################## FUNCION LISTAR SALAS ##############################


########################## FUNCION LISTAR MESAS ##############################
      function TablaListarMesas()
   {
    
    $logo = ( file_exists("./fotos/logo-pdf.png") == "" ? "./assets/images/null.png" : "./fotos/logo-pdf.png");
    $logo2 = ( file_exists("./fotos/logo-principal.png") == "" ? "./assets/images/null.png" : "./fotos/logo-principal.png");
    
    $con = new Login();
    $con = $con->ConfiguracionPorId(); 

    $this->Ln(2);
    $this->SetFont('Courier','B',11);
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es blanco)
    $this->SetFillColor(5, 130, 275); // establece el color del fondo de la celda (en este caso es NARANJA
    $this->Cell(45,5,$this->Image($logo, $this->GetX()+18, $this->GetY()+4, 22),0,0,'C');
    $this->Cell(100,5,"GESTIÓN Y CONTROL PARA RESTAURANTES",0,0,'C');
    $this->Cell(45,5,$this->Image($logo2, $this->GetX()+2, $this->GetY()+4, 40),0,0,'C');

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(100,5,utf8_decode($con[0]['nomsucursal']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(100,5,utf8_decode($con[0]['documsucursal'] == '0' ? "" : $con[0]['documento'])." ".utf8_decode($con[0]['cuit']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    if($con[0]['id_provincia']!='0'){

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(100,5,utf8_decode($provincia = ($con[0]['id_provincia'] == '0' ? "" : $con[0]['provincia'])." ".$departamento = ($con[0]['id_departamento'] == '0' ? "" : $con[0]['departamento'])),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    }

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(100,5,utf8_decode($con[0]['direcsucursal']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(100,5,"Nº DE TLF: ".utf8_decode($con[0]['tlfsucursal']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(100,5,utf8_decode($con[0]['correosucursal']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');
    $this->Ln(8);

    $this->SetFont('Courier','B',12);  
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es negro)
    $this->Cell(190,10,'LISTADO GENERAL DE MESAS',0,0,'C');

    $this->Ln();
    $this->SetFont('courier','B',10);
    $this->SetTextColor(255,255,255);  // Establece el color del texto (en este caso es BLANCO)
    $this->SetFillColor(230, 126, 34); // establece el color del fondo de la celda (en este caso es NARANJA)
    $this->Cell(10,8,'Nº',1,0,'C', True);
    $this->Cell(90,8,'NOMBRE DE SALA',1,0,'C', True);
    $this->Cell(90,8,'NOMBRE DE MESA',1,1,'C', True);
    
    $tra = new Login();
    $reg = $tra->ListarMesas();

    if($reg==""){
    echo "";      
    } else {
 
     /* AQUI DECLARO LAS COLUMNAS */
    $this->SetWidths(array(10,90,90));

    /* AQUI AGREGO LOS VALORES A MOSTRAR EN COLUMNAS */
    $a=1;
    for($i=0;$i<sizeof($reg);$i++){ 
    $this->SetFont('Courier','',10);  
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es negro)
    $this->Row(array($a++,utf8_decode($reg[$i]["nomsala"]),utf8_decode($reg[$i]["nommesa"])));
       }
   }


    $this->Ln(12); 
    $this->SetFont('courier','B',10);
    $this->Cell(4,6,'',0,0,'');
    $this->Cell(100,6,'ELABORADO: '.utf8_decode($_SESSION["nombres"]),0,0,'');
    $this->Cell(86,6,'RECIBIDO:____________________________',0,0,'');
    $this->Ln();
    $this->Cell(4,6,'',0,0,'');
    $this->Cell(100,6,'FECHA/HORA: '.date('d-m-Y h:i:s A'),0,0,'');
    $this->Cell(86,6,'',0,0,'');
    $this->Ln(4);
     }
########################## FUNCION LISTAR MESAS ##############################

########################## FUNCION LISTAR CATEGORIAS ##############################
      function TablaListarCategorias()
   {
    
    $logo = ( file_exists("./fotos/logo-pdf.png") == "" ? "./assets/images/null.png" : "./fotos/logo-pdf.png");
    $logo2 = ( file_exists("./fotos/logo-principal.png") == "" ? "./assets/images/null.png" : "./fotos/logo-principal.png");
    
    $con = new Login();
    $con = $con->ConfiguracionPorId(); 

    $this->Ln(2);
    $this->SetFont('Courier','B',11);
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es blanco)
    $this->SetFillColor(5, 130, 275); // establece el color del fondo de la celda (en este caso es NARANJA
    $this->Cell(45,5,$this->Image($logo, $this->GetX()+18, $this->GetY()+4, 22),0,0,'C');
    $this->Cell(100,5,"GESTIÓN Y CONTROL PARA RESTAURANTES",0,0,'C');
    $this->Cell(45,5,$this->Image($logo2, $this->GetX()+2, $this->GetY()+4, 40),0,0,'C');

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(100,5,utf8_decode($con[0]['nomsucursal']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(100,5,utf8_decode($con[0]['documsucursal'] == '0' ? "" : $con[0]['documento'])." ".utf8_decode($con[0]['cuit']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    if($con[0]['id_provincia']!='0'){

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(100,5,utf8_decode($provincia = ($con[0]['id_provincia'] == '0' ? "" : $con[0]['provincia'])." ".$departamento = ($con[0]['id_departamento'] == '0' ? "" : $con[0]['departamento'])),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    }

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(100,5,utf8_decode($con[0]['direcsucursal']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(100,5,"Nº DE TLF: ".utf8_decode($con[0]['tlfsucursal']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(100,5,utf8_decode($con[0]['correosucursal']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');
    $this->Ln(8);

    $this->SetFont('Courier','B',12);  
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es negro)
    $this->Cell(190,10,'LISTADO GENERAL DE CATEGORIAS',0,0,'C');

    $this->Ln();
    $this->SetFont('courier','B',10);
    $this->SetTextColor(255,255,255);  // Establece el color del texto (en este caso es BLANCO)
    $this->SetFillColor(230, 126, 34); // establece el color del fondo de la celda (en este caso es NARANJA)
    $this->Cell(15,8,'Nº',1,0,'C', True);
    $this->Cell(175,8,'NOMBRE DE CATEGORIA',1,1,'C', True);
    
    $tra = new Login();
    $reg = $tra->ListarCategorias();

    if($reg==""){
    echo "";      
    } else {
 
     /* AQUI DECLARO LAS COLUMNAS */
    $this->SetWidths(array(15,175));

    /* AQUI AGREGO LOS VALORES A MOSTRAR EN COLUMNAS */
    $a=1;
    for($i=0;$i<sizeof($reg);$i++){ 
    $this->SetFont('Courier','',10);  
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es negro)
    $this->Row(array($a++,utf8_decode($reg[$i]["nomcategoria"])));
       }
   }


    $this->Ln(12); 
    $this->SetFont('courier','B',10);
    $this->Cell(4,6,'',0,0,'');
    $this->Cell(100,6,'ELABORADO: '.utf8_decode($_SESSION["nombres"]),0,0,'');
    $this->Cell(86,6,'RECIBIDO:____________________________',0,0,'');
    $this->Ln();
    $this->Cell(4,6,'',0,0,'');
    $this->Cell(100,6,'FECHA/HORA: '.date('d-m-Y h:i:s A'),0,0,'');
    $this->Cell(86,6,'',0,0,'');
    $this->Ln(4);
     }
########################## FUNCION LISTAR CATEGORIAS ##############################

########################## FUNCION LISTAR MEDIDAS ##############################
      function TablaListarMedidas()
   {
    
    $logo = ( file_exists("./fotos/logo-pdf.png") == "" ? "./assets/images/null.png" : "./fotos/logo-pdf.png");
    $logo2 = ( file_exists("./fotos/logo-principal.png") == "" ? "./assets/images/null.png" : "./fotos/logo-principal.png");
    
    $con = new Login();
    $con = $con->ConfiguracionPorId(); 

    $this->Ln(2);
    $this->SetFont('Courier','B',11);
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es blanco)
    $this->SetFillColor(5, 130, 275); // establece el color del fondo de la celda (en este caso es NARANJA
    $this->Cell(45,5,$this->Image($logo, $this->GetX()+18, $this->GetY()+4, 22),0,0,'C');
    $this->Cell(100,5,"GESTIÓN Y CONTROL PARA RESTAURANTES",0,0,'C');
    $this->Cell(45,5,$this->Image($logo2, $this->GetX()+2, $this->GetY()+4, 40),0,0,'C');

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(100,5,utf8_decode($con[0]['nomsucursal']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(100,5,utf8_decode($con[0]['documsucursal'] == '0' ? "" : $con[0]['documento'])." ".utf8_decode($con[0]['cuit']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    if($con[0]['id_provincia']!='0'){

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(100,5,utf8_decode($provincia = ($con[0]['id_provincia'] == '0' ? "" : $con[0]['provincia'])." ".$departamento = ($con[0]['id_departamento'] == '0' ? "" : $con[0]['departamento'])),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    }

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(100,5,utf8_decode($con[0]['direcsucursal']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(100,5,"Nº DE TLF: ".utf8_decode($con[0]['tlfsucursal']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(100,5,utf8_decode($con[0]['correosucursal']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');
    $this->Ln(8);

    $this->SetFont('Courier','B',12);  
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es negro)
    $this->Cell(190,10,'LISTADO GENERAL DE MEDIDAS',0,0,'C');

    $this->Ln();
    $this->SetFont('courier','B',10);
    $this->SetTextColor(255,255,255);  // Establece el color del texto (en este caso es BLANCO)
    $this->SetFillColor(230, 126, 34); // establece el color del fondo de la celda (en este caso es NARANJA)
    $this->Cell(10,8,'Nº',1,0,'C', True);
    $this->Cell(180,8,'NOMBRE DE MEDIDA',1,1,'C', True);
    
    $tra = new Login();
    $reg = $tra->ListarMedidas();

    if($reg==""){
    echo "";      
    } else {
 
     /* AQUI DECLARO LAS COLUMNAS */
    $this->SetWidths(array(10,180));

    /* AQUI AGREGO LOS VALORES A MOSTRAR EN COLUMNAS */
    $a=1;
    for($i=0;$i<sizeof($reg);$i++){ 
    $this->SetFont('Courier','',10);  
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es negro)
    $this->Row(array($a++,utf8_decode($reg[$i]["nommedida"])));
       }
   }


    $this->Ln(12); 
    $this->SetFont('courier','B',10);
    $this->Cell(4,6,'',0,0,'');
    $this->Cell(100,6,'ELABORADO: '.utf8_decode($_SESSION["nombres"]),0,0,'');
    $this->Cell(86,6,'RECIBIDO:____________________________',0,0,'');
    $this->Ln();
    $this->Cell(4,6,'',0,0,'');
    $this->Cell(100,6,'FECHA/HORA: '.date('d-m-Y h:i:s A'),0,0,'');
    $this->Cell(86,6,'',0,0,'');
    $this->Ln(4);
     }
########################## FUNCION LISTAR MEDIDAS ##############################


########################## FUNCION LISTAR USUARIOS ##############################
      function TablaListarUsuarios()
   {
    
    $tra = new Login();
    $reg = $tra->ListarUsuarios();

    $logo = ( file_exists("./fotos/logo-pdf.png") == "" ? "./assets/images/null.png" : "./fotos/logo-pdf.png");
    $logo2 = ( file_exists("./fotos/logo-principal.png") == "" ? "./assets/images/null.png" : "./fotos/logo-principal.png");
    
    $con = new Login();
    $con = $con->ConfiguracionPorId(); 

    $this->Ln(2);
    $this->SetFont('Courier','B',12);
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es blanco)
    $this->SetFillColor(5, 130, 275); // establece el color del fondo de la celda (en este caso es NARANJA
    $this->Cell(45,5,$this->Image($logo, $this->GetX()+80, $this->GetY()+4, 24),0,0,'C');
    $this->Cell(240,5,"GESTIÓN Y CONTROL PARA RESTAURANTES",0,0,'C');
    $this->Cell(45,5,$this->Image($logo2, $this->GetX()-62, $this->GetY()+4, 58),0,0,'C');

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(240,5,utf8_decode($con[0]['nomsucursal']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(240,5,utf8_decode($con[0]['documsucursal'] == '0' ? "" : $con[0]['documento'])." ".utf8_decode($con[0]['cuit']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    if($con[0]['id_provincia']!='0'){

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(240,5,utf8_decode($provincia = ($con[0]['id_provincia'] == '0' ? "" : $con[0]['provincia'])." ".$departamento = ($con[0]['id_departamento'] == '0' ? "" : $con[0]['departamento'])),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    }

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(240,5,utf8_decode($con[0]['direcsucursal']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(240,5,"Nº DE TLF: ".utf8_decode($con[0]['tlfsucursal']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(240,5,utf8_decode($con[0]['correosucursal']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');
    $this->Ln(8);

    
    $this->SetFont('Courier','B',14);  
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es negro)
    $this->Cell(330,10,'LISTADO GENERAL DE USUARIOS',0,0,'C');

    $this->Ln();
    $this->SetFont('courier','B',10);
    $this->SetTextColor(255,255,255);  // Establece el color del texto (en este caso es BLANCO)
    $this->SetFillColor(230, 126, 34); // establece el color del fondo de la celda (en este caso es NARANJA)
    $this->Cell(10,8,'Nº',1,0,'C', True);
    $this->Cell(30,8,'Nº DOCUMENTO',1,0,'C', True);
    $this->Cell(80,8,'NOMBRES Y APELLIDOS',1,0,'C', True);
    $this->Cell(25,8,'SEXO',1,0,'C', True);
    $this->Cell(45,8,'Nº DE TELÉFONO',1,0,'C', True);
    $this->Cell(60,8,'EMAIL',1,0,'C', True);
    $this->Cell(40,8,'USUARIO',1,0,'C', True);
    $this->Cell(40,8,'NIVEL',1,1,'C', True);

    if($reg==""){
    echo "";      
    } else {
 
     /* AQUI DECLARO LAS COLUMNAS */
    $this->SetWidths(array(10,30,80,25,45,60,40,40));

    /* AQUI AGREGO LOS VALORES A MOSTRAR EN COLUMNAS */
    $a=1;
    for($i=0;$i<sizeof($reg);$i++){ 
    $this->SetFont('Courier','',10);  
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es negro)
    $this->Row(array($a++,utf8_decode($reg[$i]["dni"]),utf8_decode($reg[$i]["nombres"]),utf8_decode($reg[$i]["sexo"]),utf8_decode($reg[$i]["telefono"]),utf8_decode($reg[$i]["email"]),utf8_decode($reg[$i]["usuario"]),utf8_decode($reg[$i]["nivel"])));
        }
      }


    $this->Ln(12); 
    $this->SetFont('courier','B',10);
    $this->Cell(5,6,'',0,0,'');
    $this->Cell(200,6,'ELABORADO: '.utf8_decode($_SESSION["nombres"]),0,0,'');
    $this->Cell(125,6,'RECIBIDO:_____________________________________',0,0,'');
    $this->Ln();
    $this->Cell(5,6,'',0,0,'');
    $this->Cell(200,6,'FECHA/HORA: '.date('d-m-Y h:i:s A'),0,0,'');
    $this->Cell(125,6,'',0,0,'');
    $this->Ln(4);
     }
########################## FUNCION LISTAR USUARIOS ##############################


########################## FUNCION LISTAR LOGS DE USUARIOS ##############################
 function TablaListarLogs()
   {
    $logo = ( file_exists("./fotos/logo-pdf.png") == "" ? "./assets/images/null.png" : "./fotos/logo-pdf.png");
    $logo2 = ( file_exists("./fotos/logo-principal.png") == "" ? "./assets/images/null.png" : "./fotos/logo-principal.png");
    
    $con = new Login();
    $con = $con->ConfiguracionPorId(); 

    $this->Ln(2);
    $this->SetFont('Courier','B',12);
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es blanco)
    $this->SetFillColor(5, 130, 275); // establece el color del fondo de la celda (en este caso es NARANJA
    $this->Cell(45,5,$this->Image($logo, $this->GetX()+80, $this->GetY()+4, 24),0,0,'C');
    $this->Cell(240,5,"GESTIÓN Y CONTROL PARA RESTAURANTES",0,0,'C');
    $this->Cell(45,5,$this->Image($logo2, $this->GetX()-62, $this->GetY()+4, 58),0,0,'C');

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(240,5,utf8_decode($con[0]['nomsucursal']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(240,5,utf8_decode($con[0]['documsucursal'] == '0' ? "" : $con[0]['documento'])." ".utf8_decode($con[0]['cuit']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    if($con[0]['id_provincia']!='0'){

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(240,5,utf8_decode($provincia = ($con[0]['id_provincia'] == '0' ? "" : $con[0]['provincia'])." ".$departamento = ($con[0]['id_departamento'] == '0' ? "" : $con[0]['departamento'])),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    }

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(240,5,utf8_decode($con[0]['direcsucursal']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(240,5,"Nº DE TLF: ".utf8_decode($con[0]['tlfsucursal']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(240,5,utf8_decode($con[0]['correosucursal']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');
    $this->Ln(8);
    
    $this->SetFont('Courier','B',14);  
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es negro)
    $this->Cell(330,10,'LISTADO DE LOGS DE ACCESO DE USUARIOS',0,0,'C');
    
    $this->Ln();
    $this->SetFont('Courier','B',10);
    $this->SetTextColor(255,255,255);  // Establece el color del texto (en este caso es blanco)
    $this->SetFillColor(230, 126, 34); // establece el color del fondo de la celda (en este caso es NARANJA
    $this->Cell(10,8,'N°',1,0,'C', True);
    $this->Cell(35,8,'IP EQUIPO',1,0,'C', True);
    $this->Cell(45,8,'TIEMPO ENTRADA',1,0,'C', True);
    $this->Cell(145,8,'NAVEGADOR DE ACCESO',1,0,'C', True);
    $this->Cell(60,8,'PÁGINAS DE ACCESO',1,0,'C', True);
    $this->Cell(35,8,'USUARIO',1,1,'C', True);
    

    $tra = new Login();
    $reg = $tra->ListarLogs();

    if($reg==""){
    echo "";      
    } else {
    
    /* AQUI DECLARO LAS COLUMNAS */
    $this->SetWidths(array(10,35,45,145,60,35));

    /* AQUI AGREGO LOS VALORES A MOSTRAR EN COLUMNAS */
    $a=1;
    for($i=0;$i<sizeof($reg);$i++){ 
    $this->SetFont('Courier','',10);  
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es negro)
    $this->Row(array($a++,utf8_decode($reg[$i]["ip"]),utf8_decode($reg[$i]["tiempo"]),utf8_decode($reg[$i]["detalles"]),utf8_decode($reg[$i]["paginas"]),utf8_decode($reg[$i]["usuario"])));
       }
   }

    $this->Ln(12); 
    $this->SetFont('courier','B',10);
    $this->Cell(5,6,'',0,0,'');
    $this->Cell(200,6,'ELABORADO: '.utf8_decode($_SESSION["nombres"]),0,0,'');
    $this->Cell(125,6,'RECIBIDO:_____________________________________',0,0,'');
    $this->Ln();
    $this->Cell(5,6,'',0,0,'');
    $this->Cell(200,6,'FECHA/HORA: '.date('d-m-Y h:i:s A'),0,0,'');
    $this->Cell(125,6,'',0,0,'');
    $this->Ln(4);
   }
########################## FUNCION LISTAR LOGS DE USUARIOS ##############################

############################ REPORTES DE ADMINISTRACION #############################







































############################### REPORTES DE MANTENIMIENTO ##############################

########################## FUNCION LISTAR CLIENTES ##############################
      function TablaListarClientes()
   {
    $logo = ( file_exists("./fotos/logo-pdf.png") == "" ? "./assets/images/null.png" : "./fotos/logo-pdf.png");
    $logo2 = ( file_exists("./fotos/logo-principal.png") == "" ? "./assets/images/null.png" : "./fotos/logo-principal.png");
    
    $con = new Login();
    $con = $con->ConfiguracionPorId(); 

    $this->Ln(2);
    $this->SetFont('Courier','B',12);
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es blanco)
    $this->SetFillColor(5, 130, 275); // establece el color del fondo de la celda (en este caso es NARANJA
    $this->Cell(45,5,$this->Image($logo, $this->GetX()+80, $this->GetY()+4, 24),0,0,'C');
    $this->Cell(240,5,"GESTIÓN Y CONTROL PARA RESTAURANTES",0,0,'C');
    $this->Cell(45,5,$this->Image($logo2, $this->GetX()-62, $this->GetY()+4, 58),0,0,'C');

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(240,5,utf8_decode($con[0]['nomsucursal']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(240,5,utf8_decode($con[0]['documsucursal'] == '0' ? "" : $con[0]['documento'])." ".utf8_decode($con[0]['cuit']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    if($con[0]['id_provincia']!='0'){

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(240,5,utf8_decode($provincia = ($con[0]['id_provincia'] == '0' ? "" : $con[0]['provincia'])." ".$departamento = ($con[0]['id_departamento'] == '0' ? "" : $con[0]['departamento'])),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    }

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(240,5,utf8_decode($con[0]['direcsucursal']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(240,5,"Nº DE TLF: ".utf8_decode($con[0]['tlfsucursal']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(240,5,utf8_decode($con[0]['correosucursal']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');
    $this->Ln(8);
    
    $this->SetFont('Courier','B',14);  
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es negro)
    $this->Cell(330,10,'LISTADO GENERAL DE CLIENTES',0,0,'C');
    
    $this->Ln();
    $this->SetFont('courier','B',10);
    $this->SetTextColor(255,255,255);  // Establece el color del texto (en este caso es BLANCO)
    $this->SetFillColor(230, 126, 34); // establece el color del fondo de la celda (en este caso es NARANJA)
    $this->Cell(15,8,'Nº',1,0,'C', True);
    $this->Cell(35,8,'Nº DE DOCUMENTO',1,0,'C', True);
    $this->Cell(60,8,'NOMBRES Y APELLIDOS',1,0,'C', True);
    $this->Cell(35,8,'Nº DE TELEFONO',1,0,'C', True);
    $this->Cell(75,8,'DIRECCIÓN DOMICILIARIA',1,0,'C', True);
    $this->Cell(60,8,'EMAIL',1,0,'C', True);
    $this->Cell(25,8,'TIPO',1,0,'C', True);
    $this->Cell(25,8,'CRÉDITO',1,1,'C', True);
    
    $tra = new Login();
    $reg = $tra->ListarClientes();

    if($reg==""){
    echo "";      
    } else {
 
     /* AQUI DECLARO LAS COLUMNAS */
    $this->SetWidths(array(15,35,60,35,75,60,25,25));

    /* AQUI AGREGO LOS VALORES A MOSTRAR EN COLUMNAS */
    $a=1;
    for($i=0;$i<sizeof($reg);$i++){ 
    $this->SetFont('Courier','',10);  
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es negro)
    $this->Row(array($a++,utf8_decode($reg[$i]["documento"]." ".$reg[$i]["dnicliente"]),portales(utf8_decode($reg[$i]["nomcliente"])),utf8_decode($reg[$i]["tlfcliente"]),utf8_decode($reg[$i]["provincia"]."-".$reg[$i]["departamento"]."-".$reg[$i]["direccliente"]),utf8_decode($reg[$i]['emailcliente']),utf8_decode($reg[$i]["tipocliente"]),utf8_decode($reg[$i]["limitecredito"])));
       }
   }


    $this->Ln(12); 
    $this->SetFont('courier','B',10);
    $this->Cell(5,6,'',0,0,'');
    $this->Cell(200,6,'ELABORADO: '.utf8_decode($_SESSION["nombres"]),0,0,'');
    $this->Cell(125,6,'RECIBIDO:_____________________________________',0,0,'');
    $this->Ln();
    $this->Cell(5,6,'',0,0,'');
    $this->Cell(200,6,'FECHA/HORA: '.date('d-m-Y h:i:s A'),0,0,'');
    $this->Cell(125,6,'',0,0,'');
    $this->Ln(4);
     }
########################## FUNCION LISTAR CLIENTES ##############################

########################## FUNCION LISTAR PROVEEDORES ##############################
      function TablaListarProveedores()
   {
    $logo = ( file_exists("./fotos/logo-pdf.png") == "" ? "./assets/images/null.png" : "./fotos/logo-pdf.png");
    $logo2 = ( file_exists("./fotos/logo-principal.png") == "" ? "./assets/images/null.png" : "./fotos/logo-principal.png");
    
    $con = new Login();
    $con = $con->ConfiguracionPorId(); 

    $this->Ln(2);
    $this->SetFont('Courier','B',12);
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es blanco)
    $this->SetFillColor(5, 130, 275); // establece el color del fondo de la celda (en este caso es NARANJA
    $this->Cell(45,5,$this->Image($logo, $this->GetX()+80, $this->GetY()+4, 24),0,0,'C');
    $this->Cell(240,5,"GESTIÓN Y CONTROL PARA RESTAURANTES",0,0,'C');
    $this->Cell(45,5,$this->Image($logo2, $this->GetX()-62, $this->GetY()+4, 58),0,0,'C');

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(240,5,utf8_decode($con[0]['nomsucursal']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(240,5,utf8_decode($con[0]['documsucursal'] == '0' ? "" : $con[0]['documento'])." ".utf8_decode($con[0]['cuit']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    if($con[0]['id_provincia']!='0'){

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(240,5,utf8_decode($provincia = ($con[0]['id_provincia'] == '0' ? "" : $con[0]['provincia'])." ".$departamento = ($con[0]['id_departamento'] == '0' ? "" : $con[0]['departamento'])),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    }

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(240,5,utf8_decode($con[0]['direcsucursal']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(240,5,"Nº DE TLF: ".utf8_decode($con[0]['tlfsucursal']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(240,5,utf8_decode($con[0]['correosucursal']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');
    $this->Ln(8);
    
    $this->SetFont('Courier','B',14);  
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es negro)
    $this->Cell(330,10,'LISTADO GENERAL DE PROVEEDORES',0,0,'C');
    
    $this->Ln();
    $this->SetFont('courier','B',10);
    $this->SetTextColor(255,255,255);  // Establece el color del texto (en este caso es BLANCO)
    $this->SetFillColor(230, 126, 34); // establece el color del fondo de la celda (en este caso es NARANJA)
    $this->Cell(15,8,'Nº',1,0,'C', True);
    $this->Cell(35,8,'Nº DE DOCUMENTO',1,0,'C', True);
    $this->Cell(60,8,'NOMBRE DE PROVEEDOR',1,0,'C', True);
    $this->Cell(25,8,'Nº DE TLF',1,0,'C', True);
    $this->Cell(75,8,'DIRECCIÓN DOMICILIARIA',1,0,'C', True);
    $this->Cell(60,8,'EMAIL',1,0,'C', True);
    $this->Cell(35,8,'VENDEDOR',1,0,'C', True);
    $this->Cell(25,8,'Nº DE TLF',1,1,'C', True);
    
    $tra = new Login();
    $reg = $tra->ListarProveedores();

    if($reg==""){
    echo "";      
    } else {
 
     /* AQUI DECLARO LAS COLUMNAS */
    $this->SetWidths(array(15,35,60,25,75,60,35,25));

    /* AQUI AGREGO LOS VALORES A MOSTRAR EN COLUMNAS */
    $a=1;
    for($i=0;$i<sizeof($reg);$i++){ 
    $this->SetFont('Courier','',10);  
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es negro)
    $this->Row(array($a++,utf8_decode($reg[$i]["documento"]." ".$reg[$i]["cuitproveedor"]),portales(utf8_decode($reg[$i]["nomproveedor"])),utf8_decode($reg[$i]["tlfproveedor"]),utf8_decode($reg[$i]["provincia"]."-".$reg[$i]["departamento"]."-".$reg[$i]["direcproveedor"]),utf8_decode($reg[$i]['emailproveedor']),utf8_decode($reg[$i]["vendedor"]),utf8_decode($reg[$i]["tlfvendedor"])));
       }
   }


    $this->Ln(12); 
    $this->SetFont('courier','B',10);
    $this->Cell(5,6,'',0,0,'');
    $this->Cell(200,6,'ELABORADO: '.utf8_decode($_SESSION["nombres"]),0,0,'');
    $this->Cell(125,6,'RECIBIDO:_____________________________________',0,0,'');
    $this->Ln();
    $this->Cell(5,6,'',0,0,'');
    $this->Cell(200,6,'FECHA/HORA: '.date('d-m-Y h:i:s A'),0,0,'');
    $this->Cell(125,6,'',0,0,'');
    $this->Ln(4);
     }
########################## FUNCION LISTAR PROVEEDORES ##############################













########################## FUNCION LISTAR INGREDIENTES ##############################
      function TablaListarIngredientes()
   {

    $imp = new Login();
    $imp = $imp->ImpuestosPorId();
    $impuesto = $imp[0]['nomimpuesto'];
    $valor = $imp[0]['valorimpuesto'];

    $con = new Login();
    $con = $con->ConfiguracionPorId();
    $simbolo = $con[0]['simbolo'];

    $tra = new Login();
    $reg = $tra->ListarIngredientes(); 

    $monedap = new Login();
    $cambio = $monedap->MonedaProductoId(); 
   
    $logo = ( file_exists("./fotos/logo-pdf.png") == "" ? "./assets/images/null.png" : "./fotos/logo-pdf.png");
    $logo2 = ( file_exists("./fotos/logo-principal.png") == "" ? "./assets/images/null.png" : "./fotos/logo-principal.png");
    
    $con = new Login();
    $con = $con->ConfiguracionPorId(); 

    $this->Ln(2);
    $this->SetFont('Courier','B',12);
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es blanco)
    $this->SetFillColor(5, 130, 275); // establece el color del fondo de la celda (en este caso es NARANJA
    $this->Cell(45,5,$this->Image($logo, $this->GetX()+80, $this->GetY()+4, 24),0,0,'C');
    $this->Cell(240,5,"GESTIÓN Y CONTROL PARA RESTAURANTES",0,0,'C');
    $this->Cell(45,5,$this->Image($logo2, $this->GetX()-62, $this->GetY()+4, 24),0,0,'C');

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(240,5,utf8_decode($con[0]['nomsucursal']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(240,5,utf8_decode($con[0]['documsucursal'] == '0' ? "" : $con[0]['documento'])." ".utf8_decode($con[0]['cuit']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    if($con[0]['id_provincia']!='0'){

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(240,5,utf8_decode($provincia = ($con[0]['id_provincia'] == '0' ? "" : $con[0]['provincia'])." ".$departamento = ($con[0]['id_departamento'] == '0' ? "" : $con[0]['departamento'])),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    }

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(240,5,utf8_decode($con[0]['direcsucursal']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(240,5,"Nº DE TLF: ".utf8_decode($con[0]['tlfsucursal']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(240,5,utf8_decode($con[0]['correosucursal']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');
    $this->Ln(8);

    $this->SetFont('Courier','B',14);  
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es negro)
    $this->Cell(330,7,'LISTADO GENERAL DE INGREDIENTES EN ALMACEN',0,0,'C');

    $this->Ln(10);
    $this->SetFont('courier','B',10);
    $this->SetTextColor(255,255,255);  // Establece el color del texto (en este caso es BLANCO)
    $this->SetFillColor(230, 126, 34); // establece el color del fondo de la celda (en este caso es NARANJA)
    $this->Cell(15,8,'Nº',1,0,'C', True);
    $this->Cell(25,8,'CÓDIGO',1,0,'C', True);
    $this->Cell(75,8,'DESCRIPCIÓN',1,0,'C', True);
    $this->Cell(35,8,'MEDIDA',1,0,'C', True);
    $this->Cell(40,8,'PRECIO COMPRA',1,0,'C', True);
    $this->Cell(40,8,'PRECIO VENTA',1,0,'C', True);
    ///$this->Cell(30,8,$cambio[0]['codmoneda2'] == '' ? "CAMBIO" : "PRECIO ".$cambio[0]['siglas'],1,0,'C', True);
    $this->Cell(30,8,'EXISTENCIA',1,0,'C', True);
    $this->Cell(30,8,$impuesto == '' ? "IMPUESTO" : $imp[0]['nomimpuesto'],1,0,'C', True);
    $this->Cell(20,8,'DESC',1,1,'C', True);

    if($reg==""){
    echo "";      
    } else {
 
    $this->SetWidths(array(15,25,75,35,40,40,30,30,20,20));

    $a=1;
    $TotalCompra=0;
    $TotalVenta=0;
    $TotalMoneda=0;
    $TotalArticulos=0;
    for($i=0;$i<sizeof($reg);$i++){ 
    $TotalCompra+=$reg[$i]['preciocompra'];
    $TotalVenta+=$reg[$i]['precioventa']-$reg[$i]['descingrediente']/100;
    //$TotalMoneda+= ($cambio[0]['montocambio'] == 0 ? "0" : $reg[$i]['precioventa']/$cambio[0]['montocambio']);
    $TotalArticulos+=$reg[$i]['cantingrediente'];

    $this->SetFont('Courier','',10);  
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es negro)
    $this->Row(array($a++,utf8_decode($reg[$i]['codingrediente']),
        portales(utf8_decode($reg[$i]["nomingrediente"])),
        utf8_decode($reg[$i]["nommedida"]),
        utf8_decode($simbolo.number_format($reg[$i]['preciocompra'], 2, '.', ',')),
        utf8_decode($simbolo.number_format($reg[$i]['precioventa'], 2, '.', ',')),
        //utf8_decode($cambio[0]['codmoneda2'] == '' ? "0.00" : $cambio[0]['simbolo'].number_format($reg[$i]['precioventa']/$cambio[0]['montocambio'], 2, '.', ',')),
        utf8_decode($reg[$i]['cantingrediente']),
        utf8_decode($reg[$i]['ivaingrediente'] == 'SI' ? $imp[0]["valorimpuesto"]."%" : "(E)"),
        utf8_decode($reg[$i]['descingrediente'])));
       }
   
    $this->Cell(150,5,'',0,0,'C');
    $this->SetFont('courier','B',10);
    $this->SetTextColor(255,255,255);  // Establece el color del texto (en este caso es blanco)
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es blanco)
    $this->CellFitSpace(40,5,utf8_decode($simbolo.number_format($TotalCompra, 2, '.', ',')),1,0,'L');
    $this->CellFitSpace(40,5,utf8_decode($simbolo.number_format($TotalVenta, 2, '.', ',')),1,0,'L');
    //$this->CellFitSpace(30,5,utf8_decode($cambio[0]['simbolo'].number_format($TotalMoneda, 2, '.', ',')),1,0,'L');
    $this->CellFitSpace(30,5,utf8_decode($TotalArticulos),1,0,'L');
    $this->Ln();
   }

    $this->Ln(12); 
    $this->SetFont('courier','B',10);
    $this->Cell(5,6,'',0,0,'');
    $this->Cell(200,6,'ELABORADO: '.utf8_decode($_SESSION["nombres"]),0,0,'');
    $this->Cell(125,6,'RECIBIDO:_____________________________________',0,0,'');
    $this->Ln();
    $this->Cell(5,6,'',0,0,'');
    $this->Cell(200,6,'FECHA/HORA: '.date('d-m-Y h:i:s A'),0,0,'');
    $this->Cell(125,6,'',0,0,'');
    $this->Ln(4);
     }
########################## FUNCION LISTAR INGREDIENTES ##############################

####################### FUNCION LISTAR INGREDIENTES EN STOCK MINIMO ##############################
      function TablaListarIngredientesMinimo()
   {

    $imp = new Login();
    $imp = $imp->ImpuestosPorId();
    $impuesto = $imp[0]['nomimpuesto'];
    $valor = $imp[0]['valorimpuesto'];

    $con = new Login();
    $con = $con->ConfiguracionPorId();
    $simbolo = $con[0]['simbolo'];

    $tra = new Login();
    $reg = $tra->ListarIngredientesMinimo(); 

    $monedap = new Login();
    $cambio = $monedap->MonedaProductoId(); 
   
    $logo = ( file_exists("./fotos/logo-pdf.png") == "" ? "./assets/images/null.png" : "./fotos/logo-pdf.png");
    $logo2 = ( file_exists("./fotos/logo-principal.png") == "" ? "./assets/images/null.png" : "./fotos/logo-principal.png");
    
    $con = new Login();
    $con = $con->ConfiguracionPorId(); 

    $this->Ln(2);
    $this->SetFont('Courier','B',12);
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es blanco)
    $this->SetFillColor(5, 130, 275); // establece el color del fondo de la celda (en este caso es NARANJA
    $this->Cell(45,5,$this->Image($logo, $this->GetX()+80, $this->GetY()+4, 24),0,0,'C');
    $this->Cell(240,5,"GESTIÓN Y CONTROL PARA RESTAURANTES",0,0,'C');
    $this->Cell(45,5,$this->Image($logo2, $this->GetX()-62, $this->GetY()+4, 24),0,0,'C');

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(240,5,utf8_decode($con[0]['nomsucursal']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(240,5,utf8_decode($con[0]['documsucursal'] == '0' ? "" : $con[0]['documento'])." ".utf8_decode($con[0]['cuit']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    if($con[0]['id_provincia']!='0'){

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(240,5,utf8_decode($provincia = ($con[0]['id_provincia'] == '0' ? "" : $con[0]['provincia'])." ".$departamento = ($con[0]['id_departamento'] == '0' ? "" : $con[0]['departamento'])),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    }

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(240,5,utf8_decode($con[0]['direcsucursal']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(240,5,"Nº DE TLF: ".utf8_decode($con[0]['tlfsucursal']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(240,5,utf8_decode($con[0]['correosucursal']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');
    $this->Ln(8);

    $this->SetFont('Courier','B',14);  
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es negro)
    $this->Cell(330,7,'LISTADO GENERAL DE INGREDIENTES EN STOCK MINIMO',0,0,'C');

    $this->Ln(10);
    $this->SetFont('courier','B',10);
    $this->SetTextColor(255,255,255);  // Establece el color del texto (en este caso es BLANCO)
    $this->SetFillColor(230, 126, 34); // establece el color del fondo de la celda (en este caso es NARANJA)
    $this->Cell(15,8,'Nº',1,0,'C', True);
    $this->Cell(25,8,'CÓDIGO',1,0,'C', True);
    $this->Cell(75,8,'DESCRIPCIÓN',1,0,'C', True);
    $this->Cell(35,8,'MEDIDA',1,0,'C', True);
    $this->Cell(40,8,'PRECIO COMPRA',1,0,'C', True);
    $this->Cell(40,8,'PRECIO VENTA',1,0,'C', True);
    $this->Cell(30,8,'EXISTENCIA',1,0,'C', True);
    $this->Cell(30,8,'STOCK MINIMO',1,0,'C', True);
    $this->Cell(30,8,$impuesto == '' ? "IMPUESTO" : $imp[0]['nomimpuesto'],1,0,'C', True);
    $this->Cell(20,8,'DESC',1,1,'C', True);

    if($reg==""){
    echo "";      
    } else {
 
    $this->SetWidths(array(15,25,75,35,40,40,30,30,20,20));

    $a=1;
    $TotalCompra=0;
    $TotalVenta=0;
    $TotalMoneda=0;
    $TotalArticulos=0;
    for($i=0;$i<sizeof($reg);$i++){ 
    $TotalCompra+=$reg[$i]['preciocompra'];
    $TotalVenta+=$reg[$i]['precioventa']-$reg[$i]['descingrediente']/100;
    $TotalMoneda+= ($cambio[0]['montocambio'] == 0 ? "0" : $reg[$i]['precioventa']/$cambio[0]['montocambio']);
    $TotalArticulos+=$reg[$i]['cantingrediente'];

    $this->SetFont('Courier','',10);  
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es negro)
    $this->Row(array($a++,utf8_decode($reg[$i]['codingrediente']),
        portales(utf8_decode($reg[$i]["nomingrediente"])),
        utf8_decode($reg[$i]["nommedida"]),
        utf8_decode($simbolo.number_format($reg[$i]['preciocompra'], 0, '.', '.')),
        utf8_decode($simbolo.number_format($reg[$i]['precioventa'], 0, '.', '.')),
        utf8_decode($reg[$i]['cantingrediente']),
        utf8_decode($reg[$i]['stockminimo']),
        utf8_decode($reg[$i]['ivaingrediente'] == 'SI' ? $imp[0]["valorimpuesto"]."%" : "(E)"),
        utf8_decode($reg[$i]['descingrediente'])));
       }
   
    $this->Cell(150,5,'',0,0,'C');
    $this->SetFont('courier','B',10);
    $this->SetTextColor(255,255,255);  // Establece el color del texto (en este caso es blanco)
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es blanco)
    $this->CellFitSpace(40,5,utf8_decode($simbolo.number_format($TotalCompra, 0, '.', '.')),1,0,'L');
    $this->CellFitSpace(40,5,utf8_decode($simbolo.number_format($TotalVenta, 0, '.', '.')),1,0,'L');
    $this->CellFitSpace(30,5,utf8_decode($cambio[0]['simbolo'].number_format($TotalMoneda, 0, '.', '.')),1,0,'L');
    $this->CellFitSpace(30,5,utf8_decode(number_format($TotalArticulos, 2, '.', '.')),1,0,'L');
    $this->Ln();
   }

    $this->Ln(12); 
    $this->SetFont('courier','B',10);
    $this->Cell(5,6,'',0,0,'');
    $this->Cell(200,6,'ELABORADO: '.utf8_decode($_SESSION["nombres"]),0,0,'');
    $this->Cell(125,6,'RECIBIDO:_____________________________________',0,0,'');
    $this->Ln();
    $this->Cell(5,6,'',0,0,'');
    $this->Cell(200,6,'FECHA/HORA: '.date('d-m-Y h:i:s A'),0,0,'');
    $this->Cell(125,6,'',0,0,'');
    $this->Ln(4);
}
########################## FUNCION LISTAR INGREDIENTES EN STOCK MINIMO ##############################

####################### FUNCION LISTAR INGREDIENTES EN STOCK MAXIMO ##############################
      function TablaListarIngredientesMaximo()
   {

    $imp = new Login();
    $imp = $imp->ImpuestosPorId();
    $impuesto = $imp[0]['nomimpuesto'];
    $valor = $imp[0]['valorimpuesto'];

    $con = new Login();
    $con = $con->ConfiguracionPorId();
    $simbolo = $con[0]['simbolo'];

    $tra = new Login();
    $reg = $tra->ListarIngredientesMaximo(); 

    $monedap = new Login();
    $cambio = $monedap->MonedaProductoId(); 
   
    $logo = ( file_exists("./fotos/logo-pdf.png") == "" ? "./assets/images/null.png" : "./fotos/logo-pdf.png");
    $logo2 = ( file_exists("./fotos/logo-principal.png") == "" ? "./assets/images/null.png" : "./fotos/logo-principal.png");
    
    $con = new Login();
    $con = $con->ConfiguracionPorId(); 

    $this->Ln(2);
    $this->SetFont('Courier','B',12);
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es blanco)
    $this->SetFillColor(5, 130, 275); // establece el color del fondo de la celda (en este caso es NARANJA
    $this->Cell(45,5,$this->Image($logo, $this->GetX()+80, $this->GetY()+4, 24),0,0,'C');
    $this->Cell(240,5,"GESTIÓN Y CONTROL PARA RESTAURANTES",0,0,'C');
    $this->Cell(45,5,$this->Image($logo2, $this->GetX()-62, $this->GetY()+4, 58),0,0,'C');

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(240,5,utf8_decode($con[0]['nomsucursal']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(240,5,utf8_decode($con[0]['documsucursal'] == '0' ? "" : $con[0]['documento'])." ".utf8_decode($con[0]['cuit']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    if($con[0]['id_provincia']!='0'){

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(240,5,utf8_decode($provincia = ($con[0]['id_provincia'] == '0' ? "" : $con[0]['provincia'])." ".$departamento = ($con[0]['id_departamento'] == '0' ? "" : $con[0]['departamento'])),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    }

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(240,5,utf8_decode($con[0]['direcsucursal']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(240,5,"Nº DE TLF: ".utf8_decode($con[0]['tlfsucursal']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(240,5,utf8_decode($con[0]['correosucursal']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');
    $this->Ln(8);

    $this->SetFont('Courier','B',14);  
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es negro)
    $this->Cell(330,7,'LISTADO GENERAL DE INGREDIENTES EN STOCK MAXIMO',0,0,'C');

    $this->Ln(10);
    $this->SetFont('courier','B',10);
    $this->SetTextColor(255,255,255);  // Establece el color del texto (en este caso es BLANCO)
    $this->SetFillColor(230, 126, 34); // establece el color del fondo de la celda (en este caso es NARANJA)
    $this->Cell(15,8,'Nº',1,0,'C', True);
    $this->Cell(25,8,'CÓDIGO',1,0,'C', True);
    $this->Cell(75,8,'DESCRIPCIÓN',1,0,'C', True);
    $this->Cell(35,8,'MEDIDA',1,0,'C', True);
    $this->Cell(40,8,'PRECIO COMPRA',1,0,'C', True);
    $this->Cell(40,8,'PRECIO VENTA',1,0,'C', True);
    $this->Cell(30,8,'EXISTENCIA',1,0,'C', True);
    $this->Cell(30,8,'STOCK MAXIMO',1,0,'C', True);
    $this->Cell(20,8,$impuesto == '' ? "IMPUESTO" : $imp[0]['nomimpuesto'],1,0,'C', True);
    $this->Cell(20,8,'DESC',1,1,'C', True);

    if($reg==""){
    echo "";      
    } else {
 
    $this->SetWidths(array(15,25,75,35,40,40,30,30,20,20));

    $a=1;
    $TotalCompra=0;
    $TotalVenta=0;
    $TotalMoneda=0;
    $TotalArticulos=0;
    for($i=0;$i<sizeof($reg);$i++){ 
    $TotalCompra+=$reg[$i]['preciocompra'];
    $TotalVenta+=$reg[$i]['precioventa']-$reg[$i]['descingrediente']/100;
    $TotalMoneda+= ($cambio[0]['montocambio'] == 0 ? "0" : $reg[$i]['precioventa']/$cambio[0]['montocambio']);
    $TotalArticulos+=$reg[$i]['cantingrediente'];

    $this->SetFont('Courier','',10);  
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es negro)
    $this->Row(array($a++,utf8_decode($reg[$i]['codingrediente']),
        portales(utf8_decode($reg[$i]["nomingrediente"])),
        utf8_decode($reg[$i]["nommedida"]),
        utf8_decode($simbolo.number_format($reg[$i]['preciocompra'], 0, '.', '.')),
        utf8_decode($simbolo.number_format($reg[$i]['precioventa'], 0, '.', '.')),
        utf8_decode($reg[$i]['cantingrediente']),
        utf8_decode($reg[$i]['stockmaximo']),
        utf8_decode($reg[$i]['ivaingrediente'] == 'SI' ? $imp[0]["valorimpuesto"]."%" : "(E)"),
        utf8_decode($reg[$i]['descingrediente'])));
       }
   
    $this->Cell(150,5,'',0,0,'C');
    $this->SetFont('courier','B',10);
    $this->SetTextColor(255,255,255);  // Establece el color del texto (en este caso es blanco)
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es blanco)
    $this->CellFitSpace(40,5,utf8_decode($simbolo.number_format($TotalCompra, 0, '.', '.')),1,0,'L');
    $this->CellFitSpace(40,5,utf8_decode($simbolo.number_format($TotalVenta, 0, '.', '.')),1,0,'L');
    $this->CellFitSpace(30,5,utf8_decode($cambio[0]['simbolo'].number_format($TotalMoneda, 0, '.', '.')),1,0,'L');
    $this->CellFitSpace(30,5,utf8_decode(number_format($TotalArticulos, 2, '.', '.')),1,0,'L');
    $this->Ln();
   }

    $this->Ln(12); 
    $this->SetFont('courier','B',10);
    $this->Cell(5,6,'',0,0,'');
    $this->Cell(200,6,'ELABORADO: '.utf8_decode($_SESSION["nombres"]),0,0,'');
    $this->Cell(125,6,'RECIBIDO:_____________________________________',0,0,'');
    $this->Ln();
    $this->Cell(5,6,'',0,0,'');
    $this->Cell(200,6,'FECHA/HORA: '.date('d-m-Y h:i:s A'),0,0,'');
    $this->Cell(125,6,'',0,0,'');
    $this->Ln(4);
}
########################## FUNCION LISTAR INGREDIENTES EN STOCK MAXIMO ##############################

########################## FUNCION LISTAR KARDEX POR INGREDIENTE ########################
      function TablaListarKardexIngredientes()
   {

    $imp = new Login();
    $imp = $imp->ImpuestosPorId();
    $impuesto = $imp[0]['nomimpuesto'];
    $valor = $imp[0]['valorimpuesto'];

    $con = new Login();
    $con = $con->ConfiguracionPorId();
    $simbolo = $con[0]['simbolo'];

    $kardex = new Login();
    $kardex = $kardex->BuscarKardexIngrediente(); 

    $logo = ( file_exists("./fotos/logo-pdf.png") == "" ? "./assets/images/null.png" : "./fotos/logo-pdf.png");
    $logo2 = ( file_exists("./fotos/logo-principal.png") == "" ? "./assets/images/null.png" : "./fotos/logo-principal.png");
    
    $con = new Login();
    $con = $con->ConfiguracionPorId(); 

    $this->Ln(2);
    $this->SetFont('Courier','B',12);
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es blanco)
    $this->SetFillColor(5, 130, 275); // establece el color del fondo de la celda (en este caso es NARANJA
    $this->Cell(45,5,$this->Image($logo, $this->GetX()+80, $this->GetY()+4, 24),0,0,'C');
    $this->Cell(240,5,"GESTIÓN Y CONTROL PARA RESTAURANTES",0,0,'C');
    $this->Cell(45,5,$this->Image($logo2, $this->GetX()-62, $this->GetY()+4, 58),0,0,'C');

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(240,5,utf8_decode($con[0]['nomsucursal']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(240,5,utf8_decode($con[0]['documsucursal'] == '0' ? "" : $con[0]['documento'])." ".utf8_decode($con[0]['cuit']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    if($con[0]['id_provincia']!='0'){

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(240,5,utf8_decode($provincia = ($con[0]['id_provincia'] == '0' ? "" : $con[0]['provincia'])." ".$departamento = ($con[0]['id_departamento'] == '0' ? "" : $con[0]['departamento'])),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    }

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(240,5,utf8_decode($con[0]['direcsucursal']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(240,5,"Nº DE TLF: ".utf8_decode($con[0]['tlfsucursal']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(240,5,utf8_decode($con[0]['correosucursal']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');
    $this->Ln(8);
    
    $this->SetFont('Courier','B',14);  
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es negro)
    $this->Cell(330,7,"KARDEX GENERAL DEL INGREDIENTE (".portales($kardex[0]["nomingrediente"]).")",0,0,'C');   

    $this->Ln(10);
    $this->SetFont('courier','B',10);
    $this->SetTextColor(255,255,255);  // Establece el color del texto (en este caso es BLANCO)
    $this->SetFillColor(230, 126, 34); // establece el color del fondo de la celda (en este caso es NARANJA)
    $this->Cell(15,8,'Nº',1,0,'C', True);
    $this->Cell(35,8,'MOVIMIENTO',1,0,'C', True);
    $this->Cell(25,8,'ENTRADAS',1,0,'C', True);
    $this->Cell(25,8,'SALIDAS',1,0,'C', True);
    $this->Cell(25,8,'DEVOLUCIÓN',1,0,'C', True);
    $this->Cell(25,8,'EXISTENCIA',1,0,'C', True);
    $this->Cell(20,8,$impuesto == '' ? "IMPUESTO" : $imp[0]['nomimpuesto'],1,0,'C', True);
    $this->Cell(30,8,'DESCUENTO',1,0,'C', True);
    $this->Cell(30,8,'PRECIO',1,0,'C', True);
    $this->Cell(70,8,'DOCUMENTO',1,0,'C', True);
    $this->Cell(30,8,'FECHA KARDEX',1,1,'C', True);

    if($kardex==""){
    echo "";      
    } else {
 
    $this->SetWidths(array(15,35,25,25,25,25,20,30,30,70,30));

    $TotalEntradas=0;
    $TotalSalidas=0;
    $TotalDevolucion=0;
    $a=1;
    for($i=0;$i<sizeof($kardex);$i++){ 
    $TotalEntradas+=$kardex[$i]['entradas'];
    $TotalSalidas+=$kardex[$i]['salidas'];
    $TotalDevolucion+=$kardex[$i]['devolucion'];

    $this->SetFont('Courier','',10);  
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es negro)
    $this->Row(array($a++,
        utf8_decode($kardex[$i]["movimiento"]),
        utf8_decode($kardex[$i]["entradas"]),
        utf8_decode($kardex[$i]["salidas"]),
        utf8_decode($kardex[$i]["devolucion"]),
        utf8_decode($kardex[$i]['stockactual']),
        utf8_decode($kardex[$i]['ivaingrediente']),
        utf8_decode($kardex[$i]['descingrediente']),
        utf8_decode($simbolo.number_format($kardex[$i]['precio'], 2, '.', ',')),
        utf8_decode($kardex[$i]['documento']." ".$num = ($kardex[$i]['documento'] == 'VENTA' || $kardex[$i]['documento'] == 'DEVOLUCION' ? $kardex[$i]['codproceso'] : "")),
        utf8_decode(date("d-m-Y",strtotime($kardex[$i]['fechakardex'])))));
       }
   }
   
    $this->Cell(325,5,'',0,0,'C');
    $this->Ln();
    
    $this->SetFont('courier','B',10);
    $this->SetTextColor(255,255,255);  // Establece el color del texto (en este caso es blanco)
    $this->Cell(120,5,'DETALLES DEL INGREDIENTE',1,0,'C', True);
    $this->Ln();
    
    $this->Cell(35,5,'CÓDIGO',1,0,'C', True);
    $this->SetFont('courier','B',10);
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es blanco)
    $this->CellFitSpace(85,5,utf8_decode($kardex[0]['codingrediente']),1,0,'C');
    $this->Ln();
    
    $this->SetFont('courier','B',10);
    $this->SetTextColor(255,255,255);  // Establece el color del texto (en este caso es blanco)
    $this->Cell(35,5,'DESCRIPCIÓN',1,0,'C', True);
    $this->SetFont('courier','B',10);
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es blanco)
    $this->CellFitSpace(85,5,portales(utf8_decode($kardex[0]['nomingrediente'])),1,0,'C');
    $this->Ln();
    
    $this->SetFont('courier','B',10);
    $this->SetTextColor(255,255,255);  // Establece el color del texto (en este caso es blanco)
    $this->Cell(35,5,'ENTRADAS',1,0,'C', True);
    $this->SetFont('courier','B',10);
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es blanco)
    $this->CellFitSpace(85,5,utf8_decode($TotalEntradas),1,0,'C');
    $this->Ln();
    
    $this->SetFont('courier','B',10);
    $this->SetTextColor(255,255,255);  // Establece el color del texto (en este caso es blanco)
    $this->Cell(35,5,'SALIDAS',1,0,'C', True);
    $this->SetFont('courier','B',10);
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es blanco)
    $this->CellFitSpace(85,5,utf8_decode($TotalSalidas),1,0,'C');
    $this->Ln();

    $this->SetFont('courier','B',10);
    $this->SetTextColor(255,255,255);  // Establece el color del texto (en este caso es blanco)
    $this->Cell(35,5,'DEVOLUCIÓN',1,0,'C', True);
    $this->SetFont('courier','B',10);
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es blanco)
    $this->Cell(85,5,utf8_decode($TotalDevolucion),1,0,'C');
    $this->Ln();
    
    $this->SetFont('courier','B',10);
    $this->SetTextColor(255,255,255);  // Establece el color del texto (en este caso es blanco)
    $this->Cell(35,5,'EXISTENCIA',1,0,'C', True);
    $this->SetFont('courier','B',10);
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es blanco)
    $this->CellFitSpace(85,5,utf8_decode($kardex[0]['cantingrediente']),1,0,'C');
    $this->Ln();
    
    $this->SetFont('courier','B',10);
    $this->SetTextColor(255,255,255);  // Establece el color del texto (en este caso es blanco)
    $this->Cell(35,5,'PRECIO COMPRA',1,0,'C', True);
    $this->SetFont('courier','B',10);
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es blanco)
    $this->CellFitSpace(85,5,utf8_decode($simbolo.number_format($kardex[0]['preciocompra'], 2, '.', ',')),1,0,'C');
    $this->Ln();
    
    $this->SetFont('courier','B',10);
    $this->SetTextColor(255,255,255);  // Establece el color del texto (en este caso es blanco)
    $this->Cell(35,5,'PRECIO VENTA',1,0,'C', True);
    $this->SetFont('courier','B',10);
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es blanco)
    $this->CellFitSpace(85,5,utf8_decode($simbolo.number_format($kardex[0]['precioventa'], 2, '.', ',')),1,0,'C');
    $this->Ln();
    

    $this->Ln(12); 
    $this->SetFont('courier','B',10);
    $this->Cell(5,6,'',0,0,'');
    $this->Cell(200,6,'ELABORADO: '.utf8_decode($_SESSION["nombres"]),0,0,'');
    $this->Cell(125,6,'RECIBIDO:_____________________________________',0,0,'');
    $this->Ln();
    $this->Cell(5,6,'',0,0,'');
    $this->Cell(200,6,'FECHA/HORA: '.date('d-m-Y h:i:s A'),0,0,'');
    $this->Cell(125,6,'',0,0,'');
    $this->Ln(4);
     }
######################## FUNCION LISTAR KARDEX POR INGREDIENTE ########################

######################## FUNCION LISTAR INGREDIENTES VENDIDOS #########################
      function TablaListarIngredientesVendidos()
   {

    $imp = new Login();
    $imp = $imp->ImpuestosPorId();
    $impuesto = $imp[0]['nomimpuesto'];
    $valor = $imp[0]['valorimpuesto'];

    $con = new Login();
    $con = $con->ConfiguracionPorId();
    $simbolo = $con[0]['simbolo'];

    $tra = new Login();
    $reg = $tra->BuscarIngredientesVendidos(); 

    $logo = ( file_exists("./fotos/logo-pdf.png") == "" ? "./assets/images/null.png" : "./fotos/logo-pdf.png");
    $logo2 = ( file_exists("./fotos/logo-principal.png") == "" ? "./assets/images/null.png" : "./fotos/logo-principal.png");
    
    $con = new Login();
    $con = $con->ConfiguracionPorId(); 

    $this->Ln(2);
    $this->SetFont('Courier','B',12);
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es blanco)
    $this->SetFillColor(5, 130, 275); // establece el color del fondo de la celda (en este caso es NARANJA
    $this->Cell(45,5,$this->Image($logo, $this->GetX()+80, $this->GetY()+4, 24),0,0,'C');
    $this->Cell(240,5,"GESTIÓN Y CONTROL PARA RESTAURANTES",0,0,'C');
    $this->Cell(45,5,$this->Image($logo2, $this->GetX()-62, $this->GetY()+4, 58),0,0,'C');

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(240,5,utf8_decode($con[0]['nomsucursal']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(240,5,utf8_decode($con[0]['documsucursal'] == '0' ? "" : $con[0]['documento'])." ".utf8_decode($con[0]['cuit']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    if($con[0]['id_provincia']!='0'){

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(240,5,utf8_decode($provincia = ($con[0]['id_provincia'] == '0' ? "" : $con[0]['provincia'])." ".$departamento = ($con[0]['id_departamento'] == '0' ? "" : $con[0]['departamento'])),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    }

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(240,5,utf8_decode($con[0]['direcsucursal']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(240,5,"Nº DE TLF: ".utf8_decode($con[0]['tlfsucursal']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(240,5,utf8_decode($con[0]['correosucursal']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');
    $this->Ln(8);
    
    $this->SetFont('Courier','B',14);  
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es negro)
    $this->Cell(330,7,'LISTADO DE INGREDIENTES VENDIDOS POR FECHAS (DESDE '.date("d-m-Y", strtotime($_GET["desde"]))." HASTA ".date("d-m-Y", strtotime($_GET["hasta"])).")",0,0,'C');

    $this->Ln(10);
    $this->SetFont('courier','B',10);
    $this->SetTextColor(255,255,255);  // Establece el color del texto (en este caso es BLANCO)
    $this->SetFillColor(230, 126, 34); // establece el color del fondo de la celda (en este caso es NARANJA)
    $this->Cell(15,8,'Nº',1,0,'C', True);
    $this->Cell(30,8,'CÓDIGO',1,0,'C', True);
    $this->Cell(85,8,'DESCRIPCIÓN DE INGREDIENTE',1,0,'C', True);
    $this->Cell(30,8,'MEDIDA',1,0,'C', True);
    $this->Cell(20,8,'DESC',1,0,'C', True);
    $this->Cell(40,8,"PRECIO VENTA",1,0,'C', True);
    $this->Cell(35,8,'EXISTENCIA',1,0,'C', True);
    $this->Cell(35,8,'VENDIDO',1,0,'C', True);
    $this->Cell(40,8,'MONTO TOTAL',1,1,'C', True);

    if($reg==""){
    echo "";      
    } else {
 
    $this->SetWidths(array(15,30,85,30,20,40,35,35,40));

    $precioTotal=0;
    $existeTotal=0;
    $vendidosTotal=0;
    $pagoTotal=0;
    $a=1;
    for($i=0;$i<sizeof($reg);$i++){
    $precioTotal+=$reg[$i]['precioventa'];
    $existeTotal+=$reg[$i]['cantingrediente'];
    $vendidosTotal+=$reg[$i]['cantidad']; 
    $pagoTotal+=$reg[$i]['precioventa']*$reg[$i]['cantidad']-$reg[$i]['descingrediente']/100; 

    $this->SetFont('Courier','',10);  
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es negro)
    $this->Row(array($a++,
        utf8_decode($reg[$i]["codproducto"]),
        portales(utf8_decode($reg[$i]["producto"])),
        utf8_decode($reg[$i]["nommedida"]),
        utf8_decode($reg[$i]['descingrediente']),
        utf8_decode($simbolo.number_format($reg[$i]["precioventa"], 2, '.', ',')),
        utf8_decode($reg[$i]['cantingrediente']),
        utf8_decode(number_format($reg[$i]['cantidad'], 2, '.', ',')),
        utf8_decode($simbolo.number_format($reg[$i]['precioventa']*$reg[$i]['cantidad'], 2, '.', ','))));
       }
   }
   
    $this->Cell(180,5,'',0,0,'C');
    $this->SetFont('courier','B',10);
    $this->SetTextColor(255,255,255);  // Establece el color del texto (en este caso es blanco)
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es blanco)
    $this->CellFitSpace(40,5,utf8_decode($simbolo.number_format($precioTotal, 2, '.', ',')),1,0,'L');
    $this->CellFitSpace(35,5,utf8_decode(number_format($existeTotal, 2, '.', ',')),1,0,'L');
    $this->CellFitSpace(35,5,utf8_decode(number_format($vendidosTotal, 2, '.', ',')),1,0,'L');
    $this->CellFitSpace(40,5,utf8_decode($simbolo.number_format($pagoTotal, 2, '.', ',')),1,0,'L');
    $this->Ln();
   

    $this->Ln(12); 
    $this->SetFont('courier','B',10);
    $this->Cell(5,6,'',0,0,'');
    $this->Cell(200,6,'ELABORADO: '.utf8_decode($_SESSION["nombres"]),0,0,'');
    $this->Cell(125,6,'RECIBIDO:_____________________________________',0,0,'');
    $this->Ln();
    $this->Cell(5,6,'',0,0,'');
    $this->Cell(200,6,'FECHA/HORA: '.date('d-m-Y h:i:s A'),0,0,'');
    $this->Cell(125,6,'',0,0,'');
    $this->Ln(4);
     }
######################## FUNCION LISTAR INGREDIENTES VENDIDOS ########################










########################## FUNCION LISTAR PRODUCTOS ##############################
      function TablaListarProductos()
   {

    $imp = new Login();
    $imp = $imp->ImpuestosPorId();
    $impuesto = $imp[0]['nomimpuesto'];
    $valor = $imp[0]['valorimpuesto'];

    $con = new Login();
    $con = $con->ConfiguracionPorId();
    $simbolo = $con[0]['simbolo'];

    $tra = new Login();
    $reg = $tra->ListarProductos(); 

    $monedap = new Login();
    $cambio = $monedap->MonedaProductoId(); 
   
    $logo = ( file_exists("./fotos/logo-pdf.png") == "" ? "./assets/images/null.png" : "./fotos/logo-pdf.png");
    $logo2 = ( file_exists("./fotos/logo-principal.png") == "" ? "./assets/images/null.png" : "./fotos/logo-principal.png");
    
    $con = new Login();
    $con = $con->ConfiguracionPorId(); 

    $this->Ln(2);
    $this->SetFont('Courier','B',12);
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es blanco)
    $this->SetFillColor(5, 130, 275); // establece el color del fondo de la celda (en este caso es NARANJA
    $this->Cell(45,5,$this->Image($logo, $this->GetX()+80, $this->GetY()+4, 24),0,0,'C');
    $this->Cell(240,5,"GESTIÓN Y CONTROL PARA RESTAURANTES",0,0,'C');
    $this->Cell(45,5,$this->Image($logo2, $this->GetX()-62, $this->GetY()+4, 24),0,0,'C');

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(240,5,utf8_decode($con[0]['nomsucursal']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(240,5,utf8_decode($con[0]['documsucursal'] == '0' ? "" : $con[0]['documento'])." ".utf8_decode($con[0]['cuit']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    if($con[0]['id_provincia']!='0'){

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(240,5,utf8_decode($provincia = ($con[0]['id_provincia'] == '0' ? "" : $con[0]['provincia'])." ".$departamento = ($con[0]['id_departamento'] == '0' ? "" : $con[0]['departamento'])),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    }

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(240,5,utf8_decode($con[0]['direcsucursal']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(240,5,"Nº DE TLF: ".utf8_decode($con[0]['tlfsucursal']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(240,5,utf8_decode($con[0]['correosucursal']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');
    $this->Ln(8);

    $this->SetFont('Courier','B',14);  
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es negro)
    $this->Cell(330,7,'LISTADO GENERAL DE PRODUCTOS EN ALMACEN',0,0,'C');

    $this->Ln(10);
    $this->SetFont('courier','B',10);
    $this->SetTextColor(255,255,255);  // Establece el color del texto (en este caso es BLANCO)
    $this->SetFillColor(230, 126, 34); // establece el color del fondo de la celda (en este caso es NARANJA)
    $this->Cell(15,8,'Nº',1,0,'C', True);
    $this->Cell(25,8,'CÓDIGO',1,0,'C', True);
    $this->Cell(80,8,'DESCRIPCIÓN',1,0,'C', True);
    $this->Cell(30,8,'CATEGORIA',1,0,'C', True);
    $this->Cell(40,8,'PRECIO COMPRA',1,0,'C', True);
    $this->Cell(40,8,'PRECIO VENTA',1,0,'C', True);
    //$this->Cell(30,8,$cambio[0]['codmoneda2'] == '' ? "CAMBIO" : "PRECIO ".$cambio[0]['siglas'],1,0,'C', True);
    $this->Cell(30,8,'EXISTENCIA',1,0,'C', True);
    $this->Cell(30,8,$impuesto == '' ? "IMPUESTO" : $imp[0]['nomimpuesto'],1,0,'C', True);
    $this->Cell(20,8,'DESC',1,1,'C', True);

    if($reg==""){
    echo "";      
    } else {
 
    $this->SetWidths(array(15,25,80,30,40,40,30,30,20,20));

    $a=1;
    $TotalCompra=0;
    $TotalVenta=0;
    $TotalMoneda=0;
    $TotalArticulos=0;
    for($i=0;$i<sizeof($reg);$i++){ 
    $TotalCompra+=$reg[$i]['preciocompra'];
    $TotalVenta+=$reg[$i]['precioventa']-$reg[$i]['descproducto']/100;
   // $TotalMoneda+= ($cambio[0]['montocambio'] == 0 ? "0" : $reg[$i]['precioventa']/$cambio[0]['montocambio']);
    $TotalArticulos+=$reg[$i]['existencia'];

    $this->SetFont('Courier','',10);  
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es negro)
    $this->Row(array($a++,utf8_decode($reg[$i]['codproducto']),
        portales(utf8_decode($reg[$i]["producto"])),
        utf8_decode($reg[$i]["nomcategoria"]),
        utf8_decode($simbolo.number_format($reg[$i]['preciocompra'], 2, '.', ',')),
        utf8_decode($simbolo.number_format($reg[$i]['precioventa'], 2, '.', ',')),
       // utf8_decode($cambio[0]['codmoneda2'] == '' ? "0.00" : $cambio[0]['simbolo'].number_format($reg[$i]['precioventa']/$cambio[0]['montocambio'], 2, '.', ',')),
        utf8_decode($reg[$i]['existencia']),
        utf8_decode($reg[$i]['ivaproducto'] == 'SI' ? $imp[0]["valorimpuesto"]."%" : "(E)"),
        utf8_decode($reg[$i]['descproducto'])));
       }
   
    $this->Cell(150,5,'',0,0,'C');
    $this->SetFont('courier','B',10);
    $this->SetTextColor(255,255,255);  // Establece el color del texto (en este caso es blanco)
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es blanco)
    $this->CellFitSpace(40,5,utf8_decode($simbolo.number_format($TotalCompra, 2, '.', ',')),1,0,'L');
    $this->CellFitSpace(40,5,utf8_decode($simbolo.number_format($TotalVenta, 2, '.', ',')),1,0,'L');
   // $this->CellFitSpace(30,5,utf8_decode($cambio[0]['simbolo'].number_format($TotalMoneda, 2, '.', ',')),1,0,'L');
    $this->CellFitSpace(30,5,utf8_decode(number_format($TotalArticulos, 2, '.', ',')),1,0,'L');
    $this->Ln();
   }

    $this->Ln(12); 
    $this->SetFont('courier','B',10);
    $this->Cell(5,6,'',0,0,'');
    $this->Cell(200,6,'ELABORADO: '.utf8_decode($_SESSION["nombres"]),0,0,'');
    $this->Cell(125,6,'RECIBIDO:_____________________________________',0,0,'');
    $this->Ln();
    $this->Cell(5,6,'',0,0,'');
    $this->Cell(200,6,'FECHA/HORA: '.date('d-m-Y h:i:s A'),0,0,'');
    $this->Cell(125,6,'',0,0,'');
    $this->Ln(4);
     }
########################## FUNCION LISTAR PRODUCTOS ##############################



########################## FUNCION LISTAR KARDEX POR PRODUCTO ########################
      function TablaListarKardexProductos()
   {

    $imp = new Login();
    $imp = $imp->ImpuestosPorId();
    $impuesto = $imp[0]['nomimpuesto'];
    $valor = $imp[0]['valorimpuesto'];

    $con = new Login();
    $con = $con->ConfiguracionPorId();
    $simbolo = $con[0]['simbolo'];

    $kardex = new Login();
    $kardex = $kardex->BuscarKardexProducto(); 

    $logo = ( file_exists("./fotos/logo-pdf.png") == "" ? "./assets/images/null.png" : "./fotos/logo-pdf.png");
    $logo2 = ( file_exists("./fotos/logo-principal.png") == "" ? "./assets/images/null.png" : "./fotos/logo-principal.png");
    
    $con = new Login();
    $con = $con->ConfiguracionPorId(); 

    $this->Ln(2);
    $this->SetFont('Courier','B',12);
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es blanco)
    $this->SetFillColor(5, 130, 275); // establece el color del fondo de la celda (en este caso es NARANJA
    $this->Cell(45,5,$this->Image($logo, $this->GetX()+80, $this->GetY()+4, 24),0,0,'C');
    $this->Cell(240,5,"GESTIÓN Y CONTROL PARA RESTAURANTES",0,0,'C');
    $this->Cell(45,5,$this->Image($logo2, $this->GetX()-62, $this->GetY()+4, 58),0,0,'C');

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(240,5,utf8_decode($con[0]['nomsucursal']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(240,5,utf8_decode($con[0]['documsucursal'] == '0' ? "" : $con[0]['documento'])." ".utf8_decode($con[0]['cuit']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    if($con[0]['id_provincia']!='0'){

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(240,5,utf8_decode($provincia = ($con[0]['id_provincia'] == '0' ? "" : $con[0]['provincia'])." ".$departamento = ($con[0]['id_departamento'] == '0' ? "" : $con[0]['departamento'])),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    }

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(240,5,utf8_decode($con[0]['direcsucursal']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(240,5,"Nº DE TLF: ".utf8_decode($con[0]['tlfsucursal']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(240,5,utf8_decode($con[0]['correosucursal']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');
    $this->Ln(8);
    
    $this->SetFont('Courier','B',14);  
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es negro)
    $this->Cell(330,7,"KARDEX GENERAL DEL PRODUCTO (".portales($kardex[0]["producto"]).")",0,0,'C');   

    $this->Ln(10);
    $this->SetFont('courier','B',10);
    $this->SetTextColor(255,255,255);  // Establece el color del texto (en este caso es BLANCO)
    $this->SetFillColor(230, 126, 34); // establece el color del fondo de la celda (en este caso es NARANJA)
    $this->Cell(15,8,'Nº',1,0,'C', True);
    $this->Cell(35,8,'MOVIMIENTO',1,0,'C', True);
    $this->Cell(25,8,'ENTRADAS',1,0,'C', True);
    $this->Cell(25,8,'SALIDAS',1,0,'C', True);
    $this->Cell(25,8,'DEVOLUCIÓN',1,0,'C', True);
    $this->Cell(25,8,'EXISTENCIA',1,0,'C', True);
    $this->Cell(20,8,$impuesto == '' ? "IMPUESTO" : $imp[0]['nomimpuesto'],1,0,'C', True);
    $this->Cell(30,8,'DESCUENTO',1,0,'C', True);
    $this->Cell(30,8,'PRECIO',1,0,'C', True);
    $this->Cell(70,8,'DOCUMENTO',1,0,'C', True);
    $this->Cell(30,8,'FECHA KARDEX',1,1,'C', True);

    if($kardex==""){
    echo "";      
    } else {
 
    $this->SetWidths(array(15,35,25,25,25,25,20,30,30,70,30));

    $TotalEntradas=0;
    $TotalSalidas=0;
    $TotalDevolucion=0;
    $a=1;
    for($i=0;$i<sizeof($kardex);$i++){ 
    $TotalEntradas+=$kardex[$i]['entradas'];
    $TotalSalidas+=$kardex[$i]['salidas'];
    $TotalDevolucion+=$kardex[$i]['devolucion'];

    $this->SetFont('Courier','',10);  
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es negro)
    $this->Row(array($a++,
        utf8_decode($kardex[$i]["movimiento"]),
        utf8_decode($kardex[$i]["entradas"]),
        utf8_decode($kardex[$i]["salidas"]),
        utf8_decode($kardex[$i]["devolucion"]),
        utf8_decode($kardex[$i]['stockactual']),
        utf8_decode($kardex[$i]['ivaproducto']),
        utf8_decode($kardex[$i]['descproducto']),
        utf8_decode($simbolo.number_format($kardex[$i]['precio'], 2, '.', ',')),
        utf8_decode($kardex[$i]['documento']." ".$num = ($kardex[$i]['documento'] == 'VENTA' || $kardex[$i]['documento'] == 'DEVOLUCION' ? $kardex[$i]['codproceso'] : "")),
        utf8_decode(date("d-m-Y",strtotime($kardex[$i]['fechakardex'])))));
       }
   }
   
    $this->Cell(325,5,'',0,0,'C');
    $this->Ln();
    
    $this->SetFont('courier','B',10);
    $this->SetTextColor(255,255,255);  // Establece el color del texto (en este caso es blanco)
    $this->Cell(120,5,'DETALLES DEL PRODUCTO',1,0,'C', True);
    $this->Ln();
    
    $this->Cell(35,5,'CÓDIGO',1,0,'C', True);
    $this->SetFont('courier','B',10);
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es blanco)
    $this->CellFitSpace(85,5,utf8_decode($kardex[0]['codproducto']),1,0,'C');
    $this->Ln();
    
    $this->SetFont('courier','B',10);
    $this->SetTextColor(255,255,255);  // Establece el color del texto (en este caso es blanco)
    $this->Cell(35,5,'DESCRIPCIÓN',1,0,'C', True);
    $this->SetFont('courier','B',10);
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es blanco)
    $this->CellFitSpace(85,5,portales(utf8_decode($kardex[0]['producto'])),1,0,'C');
    $this->Ln();
    
    $this->SetFont('courier','B',10);
    $this->SetTextColor(255,255,255);  // Establece el color del texto (en este caso es blanco)
    $this->Cell(35,5,'ENTRADAS',1,0,'C', True);
    $this->SetFont('courier','B',10);
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es blanco)
    $this->CellFitSpace(85,5,utf8_decode($TotalEntradas),1,0,'C');
    $this->Ln();
    
    $this->SetFont('courier','B',10);
    $this->SetTextColor(255,255,255);  // Establece el color del texto (en este caso es blanco)
    $this->Cell(35,5,'SALIDAS',1,0,'C', True);
    $this->SetFont('courier','B',10);
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es blanco)
    $this->CellFitSpace(85,5,utf8_decode($TotalSalidas),1,0,'C');
    $this->Ln();

    $this->SetFont('courier','B',10);
    $this->SetTextColor(255,255,255);  // Establece el color del texto (en este caso es blanco)
    $this->Cell(35,5,'DEVOLUCIÓN',1,0,'C', True);
    $this->SetFont('courier','B',10);
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es blanco)
    $this->Cell(85,5,utf8_decode($TotalDevolucion),1,0,'C');
    $this->Ln();
    
    $this->SetFont('courier','B',10);
    $this->SetTextColor(255,255,255);  // Establece el color del texto (en este caso es blanco)
    $this->Cell(35,5,'EXISTENCIA',1,0,'C', True);
    $this->SetFont('courier','B',10);
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es blanco)
    $this->CellFitSpace(85,5,utf8_decode($kardex[0]['existencia']),1,0,'C');
    $this->Ln();
    
    $this->SetFont('courier','B',10);
    $this->SetTextColor(255,255,255);  // Establece el color del texto (en este caso es blanco)
    $this->Cell(35,5,'PRECIO COMPRA',1,0,'C', True);
    $this->SetFont('courier','B',10);
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es blanco)
    $this->CellFitSpace(85,5,utf8_decode($simbolo.number_format($kardex[0]['preciocompra'], 2, '.', ',')),1,0,'C');
    $this->Ln();
    
    $this->SetFont('courier','B',10);
    $this->SetTextColor(255,255,255);  // Establece el color del texto (en este caso es blanco)
    $this->Cell(35,5,'PRECIO VENTA',1,0,'C', True);
    $this->SetFont('courier','B',10);
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es blanco)
    $this->CellFitSpace(85,5,utf8_decode($simbolo.number_format($kardex[0]['precioventa'], 2, '.', ',')),1,0,'C');
    $this->Ln();
    

    $this->Ln(12); 
    $this->SetFont('courier','B',10);
    $this->Cell(5,6,'',0,0,'');
    $this->Cell(200,6,'ELABORADO: '.utf8_decode($_SESSION["nombres"]),0,0,'');
    $this->Cell(125,6,'RECIBIDO:_____________________________________',0,0,'');
    $this->Ln();
    $this->Cell(5,6,'',0,0,'');
    $this->Cell(200,6,'FECHA/HORA: '.date('d-m-Y h:i:s A'),0,0,'');
    $this->Cell(125,6,'',0,0,'');
    $this->Ln(4);
     }
######################## FUNCION LISTAR KARDEX POR PRODUCTO ########################

######################## FUNCION LISTAR PRODUCTOS VENDIDOS #########################
      function TablaListarProductosVendidos()
   {

    $imp = new Login();
    $imp = $imp->ImpuestosPorId();
    $impuesto = $imp[0]['nomimpuesto'];
    $valor = $imp[0]['valorimpuesto'];

    $con = new Login();
    $con = $con->ConfiguracionPorId();
    $simbolo = $con[0]['simbolo'];

    $tra = new Login();
    $reg = $tra->BuscarProductosVendidos(); 

    $logo = ( file_exists("./fotos/logo-pdf.png") == "" ? "./assets/images/null.png" : "./fotos/logo-pdf.png");
    $logo2 = ( file_exists("./fotos/logo-principal.png") == "" ? "./assets/images/null.png" : "./fotos/logo-principal.png");
    
    $con = new Login();
    $con = $con->ConfiguracionPorId(); 

    $this->Ln(2);
    $this->SetFont('Courier','B',12);
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es blanco)
    $this->SetFillColor(5, 130, 275); // establece el color del fondo de la celda (en este caso es NARANJA
    $this->Cell(45,5,$this->Image($logo, $this->GetX()+80, $this->GetY()+4, 24),0,0,'C');
    $this->Cell(240,5,"GESTIÓN Y CONTROL PARA RESTAURANTES",0,0,'C');
    $this->Cell(45,5,$this->Image($logo2, $this->GetX()-62, $this->GetY()+4, 58),0,0,'C');

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(240,5,utf8_decode($con[0]['nomsucursal']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(240,5,utf8_decode($con[0]['documsucursal'] == '0' ? "" : $con[0]['documento'])." ".utf8_decode($con[0]['cuit']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    if($con[0]['id_provincia']!='0'){

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(240,5,utf8_decode($provincia = ($con[0]['id_provincia'] == '0' ? "" : $con[0]['provincia'])." ".$departamento = ($con[0]['id_departamento'] == '0' ? "" : $con[0]['departamento'])),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    }

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(240,5,utf8_decode($con[0]['direcsucursal']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(240,5,"Nº DE TLF: ".utf8_decode($con[0]['tlfsucursal']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(240,5,utf8_decode($con[0]['correosucursal']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');
    $this->Ln(8);
    
    $this->SetFont('Courier','B',14);  
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es negro)
    $this->Cell(330,7,'LISTADO DE PRODUCTOS VENDIDOS POR FECHAS (DESDE '.date("d-m-Y", strtotime($_GET["desde"]))." HASTA ".date("d-m-Y", strtotime($_GET["hasta"])).")",0,0,'C');

    $this->Ln(10);
    $this->SetFont('courier','B',10);
    $this->SetTextColor(255,255,255);  // Establece el color del texto (en este caso es BLANCO)
    $this->SetFillColor(230, 126, 34); // establece el color del fondo de la celda (en este caso es NARANJA)
    $this->Cell(15,8,'Nº',1,0,'C', True);
    $this->Cell(30,8,'CÓDIGO',1,0,'C', True);
    $this->Cell(95,8,'DESCRIPCIÓN DE PRODUCTO',1,0,'C', True);
    $this->Cell(40,8,'CATEGORIA',1,0,'C', True);
    $this->Cell(20,8,'DESC',1,0,'C', True);
    $this->Cell(35,8,"PRECIO VENTA",1,0,'C', True);
    $this->Cell(30,8,'EXISTENCIA',1,0,'C', True);
    $this->Cell(30,8,'VENDIDO',1,0,'C', True);
    $this->Cell(35,8,'MONTO TOTAL',1,1,'C', True);

    if($reg==""){
    echo "";      
    } else {
 
    $this->SetWidths(array(15,30,95,40,20,35,30,30,35));

    $precioTotal=0;
    $existeTotal=0;
    $vendidosTotal=0;
    $pagoTotal=0;
    $a=1;
    for($i=0;$i<sizeof($reg);$i++){
    $precioTotal+=$reg[$i]['precioventa'];
    $existeTotal+=$reg[$i]['existencia'];
    $vendidosTotal+=$reg[$i]['cantidad']; 
    $pagoTotal+=$reg[$i]['precioventa']*$reg[$i]['cantidad']-$reg[$i]['descproducto']/100; 

    $this->SetFont('Courier','',10);  
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es negro)
    $this->Row(array($a++,
        utf8_decode($reg[$i]["codproducto"]),
        portales(utf8_decode($reg[$i]["producto"])),
        utf8_decode($reg[$i]["nomcategoria"]),
        utf8_decode($reg[$i]['descproducto']),
        utf8_decode($simbolo.number_format($reg[$i]["precioventa"], 2, '.', ',')),
        utf8_decode($reg[$i]['existencia']),
        utf8_decode($reg[$i]['cantidad']),
        utf8_decode($simbolo.number_format($reg[$i]['precioventa']*$reg[$i]['cantidad'], 2, '.', ','))));
       }
   }
   
    $this->Cell(200,5,'',0,0,'C');
    $this->SetFont('courier','B',10);
    $this->SetTextColor(255,255,255);  // Establece el color del texto (en este caso es blanco)
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es blanco)
    $this->CellFitSpace(35,5,utf8_decode($simbolo.number_format($precioTotal, 2, '.', ',')),1,0,'L');
    $this->CellFitSpace(30,5,utf8_decode(number_format($existeTotal, 2, '.', ',')),1,0,'L');
    $this->CellFitSpace(30,5,utf8_decode(number_format($vendidosTotal, 2, '.', ',')),1,0,'L');
    $this->CellFitSpace(35,5,utf8_decode($simbolo.number_format($pagoTotal, 2, '.', ',')),1,0,'L');
    $this->Ln();
   

    $this->Ln(12); 
    $this->SetFont('courier','B',10);
    $this->Cell(5,6,'',0,0,'');
    $this->Cell(200,6,'ELABORADO: '.utf8_decode($_SESSION["nombres"]),0,0,'');
    $this->Cell(125,6,'RECIBIDO:_____________________________________',0,0,'');
    $this->Ln();
    $this->Cell(5,6,'',0,0,'');
    $this->Cell(200,6,'FECHA/HORA: '.date('d-m-Y h:i:s A'),0,0,'');
    $this->Cell(125,6,'',0,0,'');
    $this->Ln(4);
     }
######################## FUNCION LISTAR PRODUCTOS VENDIDOS ########################

################### FUNCION LISTAR PRODUCTOS SEGUN MODENA ###################
      function TablaListarProductosxMoneda()
   {

    $imp = new Login();
    $imp = $imp->ImpuestosPorId();
    $impuesto = $imp[0]['nomimpuesto'];
    $valor = $imp[0]['valorimpuesto'];

    $con = new Login();
    $con = $con->ConfiguracionPorId();
    $simbolo = $con[0]['simbolo'];

    $cambio = new Login();
    $cambio = $cambio->BuscarTiposCambios();

    $tra = new Login();
    $reg = $tra->ListarProductos(); 

    $logo = ( file_exists("./fotos/logo-pdf.png") == "" ? "./assets/images/null.png" : "./fotos/logo-pdf.png");
    $logo2 = ( file_exists("./fotos/logo-principal.png") == "" ? "./assets/images/null.png" : "./fotos/logo-principal.png");
    
    $con = new Login();
    $con = $con->ConfiguracionPorId(); 

    $this->Ln(2);
    $this->SetFont('Courier','B',12);
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es blanco)
    $this->SetFillColor(5, 130, 275); // establece el color del fondo de la celda (en este caso es NARANJA
    $this->Cell(45,5,$this->Image($logo, $this->GetX()+80, $this->GetY()+4, 24),0,0,'C');
    $this->Cell(240,5,"GESTIÓN Y CONTROL PARA RESTAURANTES",0,0,'C');
    $this->Cell(45,5,$this->Image($logo2, $this->GetX()-62, $this->GetY()+4, 58),0,0,'C');

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(240,5,utf8_decode($con[0]['nomsucursal']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(240,5,utf8_decode($con[0]['documsucursal'] == '0' ? "" : $con[0]['documento'])." ".utf8_decode($con[0]['cuit']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    if($con[0]['id_provincia']!='0'){

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(240,5,utf8_decode($provincia = ($con[0]['id_provincia'] == '0' ? "" : $con[0]['provincia'])." ".$departamento = ($con[0]['id_departamento'] == '0' ? "" : $con[0]['departamento'])),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    }

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(240,5,utf8_decode($con[0]['direcsucursal']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(240,5,"Nº DE TLF: ".utf8_decode($con[0]['tlfsucursal']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(240,5,utf8_decode($con[0]['correosucursal']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');
    $this->Ln(8);

    $this->SetFont('Courier','B',14);  
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es negro)
    $this->Cell(330,7,'LISTADO DE PRODUCTOS EN ALMACEN POR MONEDA ('.$cambio[0]["moneda"].")",0,0,'C');
    $this->Ln(10);

    $this->SetFont('courier','B',10);
    $this->SetTextColor(255,255,255);  // Establece el color del texto (en este caso es BLANCO)
    $this->SetFillColor(230, 126, 34); // establece el color del fondo de la celda (en este caso es NARANJA)
    $this->Cell(15,8,'Nº',1,0,'C', True);
    $this->Cell(30,8,'CÓDIGO',1,0,'C', True);
    $this->Cell(95,8,'DESCRIPCIÓN',1,0,'C', True);
    $this->Cell(30,8,'CATEGORIA',1,0,'C', True);
    $this->Cell(40,8,'PRECIO VENTA',1,0,'C', True);
    $this->Cell(40,8,'PRECIO '.$cambio[0]['siglas'],1,0,'C', True);
    $this->Cell(35,8,'EXISTENCIA',1,0,'C', True);
    $this->Cell(20,8,$impuesto == '' ? "IMPUESTO" : $imp[0]['nomimpuesto'],1,0,'C', True);
    $this->Cell(25,8,'DESCUENTO',1,1,'C', True);

    if($reg==""){
    echo "";      
    } else {
 
    $this->SetWidths(array(15,30,95,30,40,40,35,20,25));

    $a=1;
    for($i=0;$i<sizeof($reg);$i++){ 
    $this->SetFont('Courier','',10);  
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es negro)
    $this->Row(array($a++,utf8_decode($reg[$i]["codproducto"]),portales(utf8_decode($reg[$i]["producto"])),utf8_decode($reg[$i]["nomcategoria"]),utf8_decode($simbolo.number_format($reg[$i]['precioventa'], 2, '.', ',')),$tipo = ($cambio[0]['moneda'] == "EURO" ? chr(128) : $cambio[0]['simbolo']).utf8_decode(number_format($reg[$i]['precioventa']/$cambio[0]['montocambio'], 2, '.', ',')),utf8_decode($reg[$i]['existencia']),utf8_decode($reg[$i]['ivaproducto'] == 'SI' ? $imp[0]["valorimpuesto"]."%" : "(E)"),utf8_decode($reg[$i]['descproducto'])));
       }
   }

    $this->Ln(12); 
    $this->SetFont('courier','B',10);
    $this->Cell(5,6,'',0,0,'');
    $this->Cell(200,6,'ELABORADO: '.utf8_decode($_SESSION["nombres"]),0,0,'');
    $this->Cell(125,6,'RECIBIDO:_____________________________________',0,0,'');
    $this->Ln();
    $this->Cell(5,6,'',0,0,'');
    $this->Cell(200,6,'FECHA/HORA: '.date('d-m-Y h:i:s A'),0,0,'');
    $this->Cell(125,6,'',0,0,'');
    $this->Ln(4);
     }
################## FUNCION LISTAR PRODUCTOS SEGUN MODENA ###################

############################### REPORTES DE MANTENIMIENTO ##############################



































############################### REPORTES DE COMPRAS ##################################

########################## FUNCION FACTURA COMPRA ##############################
      function FacturaCompra()
    {
        
    $imp = new Login();
    $imp = $imp->ImpuestosPorId();
    $impuesto = $imp[0]['nomimpuesto'];

    $con = new Login();
    $con = $con->ConfiguracionPorId();
    $simbolo = $con[0]['simbolo'];
    $moneda = $con[0]['moneda'];

    $tra = new Login();
    $reg = $tra->ComprasPorId();

    //Logo
   if (file_exists("./fotos/logo-principal.png")) {

        $logo = "./fotos/logo-principal.png";
        $this->Image($logo, 15, 11, 66, 18, "PNG");

    } else {

        $logo = "./assets/images/null.png";                         
        $this->Image($logo, 15, 10, 64, 20, "PNG");  
    }                                      


######################### BLOQUE N° 1 ######################### 
   //BLOQUE DE DATOS DE PRINCIPAL
    $this->SetFillColor(192);
    $this->SetDrawColor(3,3,3);
    $this->SetLineWidth(.1);
    $this->RoundedRect(10, 10, 335, 20, '1.5', '');
    
    //Bloque de membrete principal
    $this->SetFillColor(229);
    $this->SetDrawColor(3,3,3);
    $this->SetLineWidth(.1);
    $this->RoundedRect(161, 13, 13, 13, '1.5', 'F');

    //Bloque de membrete principal
    $this->SetFillColor(229);
    $this->SetDrawColor(3,3,3);
    $this->SetLineWidth(.1);
    $this->RoundedRect(161, 13, 13, 13, '1.5', '');

    $this->SetFont('courier','B',16);
    $this->SetXY(164.5, 14);
    $this->Cell(20, 5, 'C', 0 , 0);
    $this->SetFont('courier','B',9);
    $this->SetXY(161, 19);
    $this->Cell(20, 5, 'Compra', 0, 0);
    
    $this->SetFont('courier','B',12);
    $this->SetXY(270, 12);
    $this->Cell(42, 5, 'N° DE COMPRA ', 0, 0);
    $this->SetFont('courier','B',12);
    $this->SetXY(312, 12);
    $this->CellFitSpace(30, 5,utf8_decode($reg[0]['codcompra']), 0, 0, "R");
    
    $this->SetFont('courier','B',10);
    $this->SetXY(270, 16);
    $this->Cell(42, 5, 'FECHA DE EMISIÓN ', 0, 0);
    $this->SetFont('courier','',10);
    $this->SetXY(312, 16);
    $this->CellFitSpace(30, 5,utf8_decode(date("d-m-Y",strtotime($reg[0]['fechaemision']))), 0, 0, "R");
    
    $this->SetFont('courier','B',10);
    $this->SetXY(270, 20);
    $this->Cell(42, 5, 'FECHA DE RECEPCIÓN ', 0, 0);
    $this->SetFont('courier','',10);
    $this->SetXY(312, 20);
    $this->CellFitSpace(30, 5,utf8_decode(date("d-m-Y",strtotime($reg[0]['fecharecepcion']))), 0, 0, "R");
    
    $this->SetFont('courier','B',10);
    $this->SetXY(270, 24);
    $this->Cell(42, 5, 'ESTADO DE COMPRA', 0, 0);
    $this->SetFont('courier','',10);
    $this->SetXY(312, 24);
    
    if($reg[0]['fechavencecredito']== '0000-00-00') { 
    $this->Cell(30, 5,utf8_decode($reg[0]['statuscompra']), 0, 0, "R");
    } elseif($reg[0]['fechavencecredito'] >= date("Y-m-d")) { 
    $this->Cell(30, 5,utf8_decode($reg[0]['statuscompra']), 0, 0, "R");
    } elseif($reg[0]['fechavencecredito'] < date("Y-m-d")) { 
    $this->Cell(30, 5,utf8_decode("VENCIDA"), 0, 0, "R");
    }
######################### BLOQUE N° 1 ######################### 

############################## BLOQUE N° 2 #####################################   
   //BLOQUE DE DATOS DE PROVEEDOR
    $this->SetFillColor(192);
    $this->SetDrawColor(3,3,3);
    $this->SetLineWidth(.1);
    $this->RoundedRect(10, 32, 335, 30, '1.5', '');

    //DATOS DE SUCURSAL LINEA 1
    $this->SetFont('courier','B',12);
    $this->SetXY(12, 33);
    $this->Cell(330, 5, 'DATOS DE SUCURSAL ', 0, 0);
    //DATOS DE SUCURSAL LINEA 1

    //DATOS DE SUCURSAL LINEA 2
    $this->SetFont('courier','B',10);
    $this->SetXY(12, 38);
    $this->CellFitSpace(28, 5, 'Nº DE '.$documento = ($con[0]['documsucursal'] == '0' ? "REG.:" : $con[0]['documento'].":"), 0, 0);
    $this->SetFont('courier','',10);
    $this->SetXY(40, 38);
    $this->CellFitSpace(30, 5,utf8_decode($con[0]['cuit']), 0, 0);

    $this->SetFont('courier','B',10);
    $this->SetXY(70, 38);
    $this->Cell(30, 5, 'RAZÓN SOCIAL:', 0, 0);
    $this->SetFont('courier','',10);
    $this->SetXY(100, 38);
    $this->CellFitSpace(50, 5,utf8_decode($con[0]['nomsucursal']), 0, 0);

    $this->SetFont('courier','B',10);
    $this->SetXY(150, 38);
    $this->Cell(24, 5, 'DIRECCIÓN:', 0, 0);
    $this->SetFont('courier','',10);
    $this->SetXY(174, 38);
    $this->CellFitSpace(116, 5,utf8_decode($provincia = ($con[0]['provincia'] == '' ? "*********" : $con[0]['provincia'])." ".$departamento = ($con[0]['departamento'] == '' ? "*********" : $con[0]['departamento'])." ".$con[0]['direcsucursal']), 0, 0);

    $this->SetFont('courier','B',10);
    $this->SetXY(290, 38);
    $this->Cell(22, 5, 'N° DE TLF:', 0, 0);
    $this->SetFont('courier','',10);
    $this->SetXY(312, 38);
    $this->CellFitSpace(30, 5,utf8_decode($con[0]['tlfsucursal']), 0, 0);
    //DATOS DE SUCURSAL LINEA 2

    //DATOS DE SUCURSAL LINEA 3
    $this->SetFont('courier','B',10);
    $this->SetXY(12, 42);
    $this->Cell(28, 5, 'EMAIL:', 0, 0);
    $this->SetFont('courier','',10);
    $this->SetXY(40, 42);
    $this->CellFitSpace(80, 5,utf8_decode($con[0]['correosucursal']), 0, 0);
    //DATOS DE SUCURSAL LINEA 3

    //DATOS DE SUCURSAL LINEA 4
    $this->SetFont('courier','B',10);
    $this->SetXY(120, 42);
    $this->Cell(30, 5, 'RESPONSABLE:', 0, 0);
    $this->SetFont('courier','',10);
    $this->SetXY(150, 42);
    $this->CellFitSpace(80, 5,utf8_decode($con[0]['nomencargado']), 0, 0);

    $this->SetFont('courier','B',10);
    $this->SetXY(230, 42);
    $this->CellFitSpace(26, 5,'Nº DE '.$documento = ($con[0]['documencargado'] == '0' ? "DOC.:" : $con[0]['documento2'].":"), 0, 0);
    $this->SetFont('courier','',10);
    $this->SetXY(256, 42);
    $this->CellFitSpace(34, 5,utf8_decode($con[0]['dniencargado']), 0, 0);

    $this->SetFont('courier','B',10);
    $this->SetXY(290, 42);
    $this->Cell(22, 5, 'N° DE TLF:', 0, 0);
    $this->SetFont('courier','',10);
    $this->SetXY(312, 42);
    $this->CellFitSpace(30, 5,utf8_decode($tlf = ($con[0]['tlfencargado'] == '' ? "*********" : $con[0]['tlfencargado'])), 0, 0);
    //DATOS DE SUCURSAL LINEA 4
################################# BLOQUE N° 2 #######################################   

################################# BLOQUE N° 3 #######################################   
    //DATOS DE SUCURSAL LINEA 5
    $this->SetFont('courier','B',12);
    $this->SetXY(12, 48);
    $this->Cell(330, 4, 'DATOS DE PROVEEDOR', 0, 0);
    //DATOS DE SUCURSAL LINEA 5

    //DATOS DE SUCURSAL LINEA 6
    $this->SetFont('courier','B',10);
    $this->SetXY(12, 52);
    $this->CellFitSpace(28, 5, 'Nº DE '.$documento = ($reg[0]['documproveedor'] == '0' ? "DOC.:" : $reg[0]['documento'].":"), 0, 0);
    $this->SetFont('courier','',10);
    $this->SetXY(40, 52);
    $this->CellFitSpace(30, 5,utf8_decode($reg[0]['cuitproveedor']), 0, 0);

    $this->SetFont('courier','B',10);
    $this->SetXY(70, 52);
    $this->Cell(30, 5, 'RAZÓN SOCIAL:', 0, 0);
    $this->SetFont('courier','',10);
    $this->SetXY(100, 52);
    $this->CellFitSpace(84, 5,utf8_decode($reg[0]['nomproveedor']), 0, 0);

    $this->SetFont('courier','B',10);
    $this->SetXY(184, 52);
    $this->Cell(24, 5, 'DIRECCIÓN:', 0, 0);
    $this->SetFont('courier','',10);
    $this->SetXY(208, 52);
    $this->CellFitSpace(134, 5,utf8_decode($provincia = ($reg[0]['provincia'] == '' ? "*********" : $reg[0]['provincia'])." ".$departamento = ($reg[0]['departamento'] == '' ? "*********" : $reg[0]['departamento'])." ".$reg[0]['direcproveedor']), 0, 0);

    $this->SetFont('courier','B',10);
    $this->SetXY(12, 56);
    $this->Cell(28, 5, 'EMAIL:', 0, 0);
    $this->SetFont('courier','',10);
    $this->SetXY(40, 56);
    $this->CellFitSpace(130, 5,utf8_decode($reg[0]['emailproveedor']), 0, 0);
    //DATOS DE SUCURSAL LINEA 6

    //DATOS DE SUCURSAL LINEA 7
    $this->SetFont('courier','B',10);
    $this->SetXY(170, 56);
    $this->Cell(24, 5, 'N° DE TLF:', 0, 0);
    $this->SetFont('courier','',10);
    $this->SetXY(194, 56);
    $this->CellFitSpace(38, 5,utf8_decode($reg[0]['tlfproveedor']), 0, 0);

    $this->SetFont('courier','B',10);
    $this->SetXY(232, 56);
    $this->Cell(24, 5, 'VENDEDOR:', 0, 0);
    $this->SetFont('courier','',10);
    $this->SetXY(256, 56);
    $this->CellFitSpace(86, 5,utf8_decode($reg[0]['vendedor']), 0, 0);
    //DATOS DE SUCURSAL LINEA 7
################################# BLOQUE N° 3 #######################################   

################################# BLOQUE N° 4 #######################################   
    //Bloque Cuadro de Detalles de Productos
    $this->SetFillColor(192);
    $this->SetDrawColor(3,3,3);
    $this->SetLineWidth(.1);
    $this->RoundedRect(10, 68, 335, 90, '0', '');

    $this->SetFont('courier','B',9);
    $this->SetXY(10, 64);
    $this->SetTextColor(3, 3, 3); // Establece el color del texto (en este caso es Negro)
    $this->SetFillColor(229, 229, 229); // establece el color del fondo de la celda (en este caso es GRIS)
    $this->Cell(10,8,'N°',1,0,'C', True);
    $this->Cell(30,8,'CÓDIGO',1,0,'C', True);
    $this->Cell(18,8,'LOTE',1,0,'C', True);
    $this->Cell(28,8,'F.VCTO',1,0,'C', True);
    $this->Cell(80,8,'DESCRIPCIÓN DE PRODUCTO',1,0,'C', True);
    $this->Cell(35,8,'CATEGORIA/MEDIDA',1,0,'C', True);
    $this->Cell(20,8,'CANT',1,0,'C', True);
    $this->Cell(15,8,$impuesto == '' ? "Impuesto" : $imp[0]['nomimpuesto'],1,0,'C', True);
    $this->Cell(25,8,'PRECIO UNIT',1,0,'C', True);
    $this->Cell(25,8,'VALOR TOTAL',1,0,'C', True);
    $this->Cell(18,8,'% DCTO',1,0,'C', True);
    $this->Cell(31,8,'VALOR NETO',1,1,'C', True);
################################# BLOQUE N° 4 ####################################### 

################################# BLOQUE N° 5 ####################################### 
    $tra = new Login();
    $detalle = $tra->VerDetallesCompras();
    $cantidad = 0;
    $SubTotal = 0;

     /* AQUI DECLARO LAS COLUMNAS */
    $this->SetWidths(array(10,30,18,28,80,35,20,15,25,25,18,31));

    /* AQUI AGREGO LOS VALORES A MOSTRAR EN COLUMNAS */
    $a=1;
    for($i=0;$i<sizeof($detalle);$i++){ 
    $cantidad += $detalle[$i]['cantcompra'];
    $valortotal = $detalle[$i]["preciocomprac"]*$detalle[$i]["cantcompra"];
    $SubTotal += $detalle[$i]['valorneto'];

    $this->SetX(10);
    $this->SetFont('Courier','',9);  
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es negro)
    $this->RowFacture(array($a++,
        utf8_decode($detalle[$i]["codproducto"]),
        utf8_decode($detalle[$i]["lotec"]),
        utf8_decode($detalle[$i]["fechaexpiracionc"] == '0000-00-00' ? "******" : $detalle[$i]["fechaexpiracionc"]),
        portales(utf8_decode($detalle[$i]["producto"])),
        utf8_decode($detalle[$i]['tipoentrada'] == 'PRODUCTO' ? $detalle[$i]['nomcategoria'] : $detalle[$i]['nommedida']),
        utf8_decode($detalle[$i]["cantcompra"]),
        utf8_decode($detalle[$i]["ivaproductoc"] == 'SI' ? $reg[0]["ivac"]."%" : "(E)"),
        utf8_decode($simbolo.number_format($detalle[$i]['preciocomprac'], 2, '.', ',')),
        utf8_decode($simbolo.number_format($detalle[$i]['valortotal'], 2, '.', ',')),
        utf8_decode(number_format($detalle[$i]['descfactura'], 2, '.', ',')),
        utf8_decode($simbolo.number_format($detalle[$i]['valorneto'], 2, '.', ','))));
       }
################################# BLOQUE N° 5 ####################################### 

    ########################### BLOQUE N° 5 DE TOTALES #############################    
    //Bloque de Informacion adicional
    $this->SetFillColor(192);
    $this->SetDrawColor(3,3,3);
    $this->SetLineWidth(.1);
    $this->RoundedRect(10, 162, 240, 38, '1.5', '');

    //Linea de membrete Nro 1
    $this->SetFont('courier','B',14);
    $this->SetXY(115, 164);
    $this->Cell(20, 5, 'INFORMACIÓN ADICIONAL', 0 , 0);
       
    //Linea de membrete Nro 2
    $this->SetFont('courier','B',10);
    $this->SetXY(12, 170);
    $this->Cell(20, 5, 'CANTIDAD DE PRODUCTOS:', 0 , 0);
    $this->SetXY(64, 170);
    $this->SetFont('courier','',10);
    $this->Cell(20, 5,utf8_decode(number_format($cantidad, 2, '.', ',')), 0 , 0);
       
    //Linea de membrete Nro 3
    $this->SetFont('courier','B',10);
    $this->SetXY(120, 170);
    $this->Cell(20, 5, 'TIPO DE DOCUMENTO:', 0 , 0);
    $this->SetXY(168, 170);
    $this->SetFont('courier','',10);
    $this->Cell(20, 5,utf8_decode("FACTURA"), 0 , 0);
       
    //Linea de membrete Nro 4
    $this->SetFont('courier','B',10);
    $this->SetXY(12, 175);
    $this->Cell(20, 5, 'TIPO DE PAGO:', 0 , 0);
    $this->SetXY(64, 175);
    $this->SetFont('courier','',10);
    $this->Cell(20, 5,utf8_decode($reg[0]['tipocompra']), 0 , 0);
       
    if($reg[0]['tipocompra']=="CREDITO"){

   //Linea de membrete Nro 5
    $this->SetFont('courier','B',10);
    $this->SetXY(120, 175);
    $this->Cell(20, 5, 'FECHA DE VENCIMIENTO:', 0 , 0);
    $this->SetXY(168, 175);
    $this->SetFont('courier','',10);
    $this->Cell(20, 5,utf8_decode($vence = ( $reg[0]['fechavencecredito'] == '0000-00-00' ? "0" : date("d-m-Y",strtotime($reg[0]['fechavencecredito'])))), 0 , 0);
        
    //Linea de membrete Nro 6
    $this->SetFont('courier','B',10);
    $this->SetXY(200, 175);
    $this->Cell(20, 5, 'DIAS VENCIDOS:', 0 , 0);
    $this->SetXY(234, 175);
    $this->SetFont('courier','',10);
        
      if($reg[0]['fechavencecredito']== '0000-00-00') { 
        $this->Cell(20, 5,utf8_decode("0"), 0 , 0);
      } elseif($reg[0]['fechavencecredito'] >= date("Y-m-d")) { 
        $this->Cell(20, 5,utf8_decode("0"), 0 , 0);
      } elseif($reg[0]['fechavencecredito'] < date("Y-m-d")) { 
        $this->Cell(20, 5,utf8_decode(Dias_Transcurridos(date("Y-m-d"),$reg[0]['fechavencecredito'])), 0 , 0);
      }
    }
    
    //Linea de membrete Nro 4
    $this->SetFont('courier','B',10);
    $this->SetXY(12, 180);
    $this->Cell(20, 5, 'MEDIO DE PAGO:', 0 , 0);
    $this->SetXY(64, 180);
    $this->SetFont('courier','',10);
    $this->Cell(20, 5,utf8_decode($variable = ($reg[0]['tipocompra'] == 'CONTADO' ? $reg[0]['mediopago'] : $reg[0]['formacompra'])), 0 , 0);
    
    //Linea de membrete Nro 4
    $this->SetFont('courier','B',10);
    $this->SetXY(12, 185);
    $this->MultiCell(236,4,$this->SetFont('Courier','',10).utf8_decode(numtoletras($reg[0]["totalpagoc"])),0,'J');
    
    //Bloque de Totales de factura
    $this->SetFillColor(192);
    $this->SetDrawColor(3,3,3);
    $this->SetLineWidth(.1);
    $this->RoundedRect(253, 162, 91, 38, '1.5', '');

     //Linea de membrete Nro 1
    $this->SetFont('courier','B',10);
    $this->SetXY(254, 165);
    $this->CellFitSpace(44, 5, 'SUBTOTAL:', 0, 0);
    $this->SetXY(298, 165);
    $this->SetFont('courier','',10);
    $this->CellFitSpace(46, 5,utf8_decode($simbolo.number_format($SubTotal, 2, '.', ',')), 0, 0, "R");

     //Linea de membrete Nro 2
    $this->SetFont('courier','B',10);
    $this->SetXY(254, 170);
    $this->CellFitSpace(44, 5, 'TOTAL GRAVADO ('.$reg[0]["ivac"].'%):', 0, 0);
    $this->SetXY(298, 170);
    $this->SetFont('courier','',10);
    $this->CellFitSpace(46, 5,utf8_decode($simbolo.number_format($reg[0]["subtotalivasic"], 2, '.', ',')), 0, 0, "R");

     //Linea de membrete Nro 3
    $this->SetFont('courier','B',10);
    $this->SetXY(254, 175);
    $this->CellFitSpace(44, 5, 'TOTAL EXENTO (0%):', 0, 0);
    $this->SetXY(298, 175);
    $this->SetFont('courier','',10);
    $this->CellFitSpace(46, 5,utf8_decode($simbolo.number_format($reg[0]["subtotalivanoc"], 2, '.', ',')), 0, 0, "R");

     //Linea de membrete Nro 4
    $this->SetFont('courier','B',10);
    $this->SetXY(254, 180);
    $this->CellFitSpace(44, 5, $impuesto == '' ? "TOTAL IMP." : "TOTAL ".$imp[0]['nomimpuesto']." (".$reg[0]["ivac"]."%):", 0, 0);
    $this->SetXY(298, 180);
    $this->SetFont('courier','',10);
    $this->CellFitSpace(46, 5,utf8_decode($simbolo.number_format($reg[0]["totalivac"], 2, '.', ',')), 0, 0, "R");

     //Linea de membrete Nro 5
    $this->SetFont('courier','B',10);
    $this->SetXY(254, 185);
    $this->CellFitSpace(44, 5, "DESC. GLOBAL (".$reg[0]["descuentoc"].'%):', 0, 0);
    $this->SetXY(298, 185);
    $this->SetFont('courier','',10);
    $this->CellFitSpace(46, 5,utf8_decode($simbolo.number_format($reg[0]["totaldescuentoc"], 2, '.', ',')), 0, 0, "R");

     //Linea de membrete Nro 6
    $this->SetFont('courier','B',10);
    $this->SetXY(254, 190);
    $this->CellFitSpace(44, 5, 'IMPORTE TOTAL:', 0, 0);
    $this->SetXY(298, 190);
    $this->SetFont('courier','',10);
    $this->CellFitSpace(46, 5,utf8_decode($simbolo.number_format($reg[0]["totalpagoc"], 2, '.', ',')), 0, 0, "R");
    
    }
########################## FUNCION FACTURA COMPRA ##############################

########################## FUNCION LISTAR COMPRAS ##############################
      function TablaListarCompras()
   {
    $con = new Login();
    $con = $con->ConfiguracionPorId();
    $simbolo = $con[0]['simbolo'];
    
    $tra = new Login();
    $reg = $tra->ListarCompras();

    $logo = ( file_exists("./fotos/logo-pdf.png") == "" ? "./assets/images/null.png" : "./fotos/logo-pdf.png");
    $logo2 = ( file_exists("./fotos/logo-principal.png") == "" ? "./assets/images/null.png" : "./fotos/logo-principal.png");
    
    $con = new Login();
    $con = $con->ConfiguracionPorId(); 

    $this->Ln(2);
    $this->SetFont('Courier','B',12);
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es blanco)
    $this->SetFillColor(5, 130, 275); // establece el color del fondo de la celda (en este caso es NARANJA
    $this->Cell(45,5,$this->Image($logo, $this->GetX()+80, $this->GetY()+4, 24),0,0,'C');
    $this->Cell(240,5,"GESTIÓN Y CONTROL PARA RESTAURANTES",0,0,'C');
    $this->Cell(45,5,$this->Image($logo2, $this->GetX()-62, $this->GetY()+4, 58),0,0,'C');

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(240,5,utf8_decode($con[0]['nomsucursal']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(240,5,utf8_decode($con[0]['documsucursal'] == '0' ? "" : $con[0]['documento'])." ".utf8_decode($con[0]['cuit']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    if($con[0]['id_provincia']!='0'){

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(240,5,utf8_decode($provincia = ($con[0]['id_provincia'] == '0' ? "" : $con[0]['provincia'])." ".$departamento = ($con[0]['id_departamento'] == '0' ? "" : $con[0]['departamento'])),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    }

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(240,5,utf8_decode($con[0]['direcsucursal']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(240,5,"Nº DE TLF: ".utf8_decode($con[0]['tlfsucursal']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(240,5,utf8_decode($con[0]['correosucursal']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');
    $this->Ln(8);
    
    $this->SetFont('Courier','B',14);  
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es negro)
    $this->Cell(330,10,'LISTADO GENERAL DE COMPRAS',0,0,'C');

    $this->Ln();
    $this->SetFont('courier','B',10);
    $this->SetTextColor(255,255,255);  // Establece el color del texto (en este caso es BLANCO)
    $this->SetFillColor(230, 126, 34); // establece el color del fondo de la celda (en este caso es NARANJA)
    $this->Cell(15,8,'Nº',1,0,'C', True);
    $this->Cell(35,8,'Nº DE COMPRA',1,0,'C', True);
    $this->Cell(80,8,'DESCRIPCIÓN DE PROVEEDOR',1,0,'C', True);
    $this->Cell(40,8,'Nº ARTICULOS',1,0,'C', True);
    $this->Cell(40,8,'TOTAL GRAVADO',1,0,'C', True);
    $this->Cell(40,8,'TOTAL EXENTO',1,0,'C', True);
    $this->Cell(40,8,'IMPORTE TOTAL',1,0,'C', True);
    $this->Cell(40,8,'FECHA DE EMISIÓN',1,1,'C', True);

    if($reg==""){
    echo "";      
    } else {
 
     /* AQUI DECLARO LAS COLUMNAS */
    $this->SetWidths(array(15,35,80,40,40,40,40,40));

    /* AQUI AGREGO LOS VALORES A MOSTRAR EN COLUMNAS */
    $a=1;
    $TotalArticulos=0;
    $TotalGravado=0;
    $TotalExento=0;
    $TotalImporte=0;

    for($i=0;$i<sizeof($reg);$i++){ 
    
    $TotalArticulos+=$reg[$i]['articulos'];
    $TotalGravado+=$reg[$i]['subtotalivasic'];
    $TotalExento+=$reg[$i]['subtotalivanoc'];
    $TotalImporte+=$reg[$i]['totalpagoc'];
 
    $this->SetFont('Courier','',10);  
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es negro)
    $this->Row(array($a++,utf8_decode($reg[$i]["codcompra"]),portales(utf8_decode($reg[$i]["nomproveedor"])),utf8_decode($reg[$i]["articulos"]),utf8_decode($simbolo.number_format($reg[$i]['subtotalivasic'], 2, '.', ',')),utf8_decode($simbolo.number_format($reg[$i]['subtotalivanoc'], 2, '.', ',')),utf8_decode($simbolo.number_format($reg[$i]['totalpagoc'], 2, '.', ',')),utf8_decode(date("d-m-Y",strtotime($reg[$i]['fechaemision'])))));
        }
   
    $this->Cell(130,5,'',0,0,'C');
    $this->SetFont('courier','B',10);
    $this->SetTextColor(255,255,255);  // Establece el color del texto (en este caso es blanco)
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es blanco)
    $this->CellFitSpace(40,5,utf8_decode(number_format($TotalArticulos, 2, '.', ',')),1,0,'L');
    $this->CellFitSpace(40,5,utf8_decode($simbolo.number_format($TotalGravado, 2, '.', ',')),1,0,'L');
    $this->CellFitSpace(40,5,utf8_decode($simbolo.number_format($TotalExento, 2, '.', ',')),1,0,'L');
    $this->CellFitSpace(40,5,utf8_decode($simbolo.number_format($TotalImporte, 2, '.', ',')),1,0,'L');
    $this->Ln();
      }

    $this->Ln(12); 
    $this->SetFont('courier','B',10);
    $this->Cell(5,6,'',0,0,'');
    $this->Cell(200,6,'ELABORADO: '.utf8_decode($_SESSION["nombres"]),0,0,'');
    $this->Cell(125,6,'RECIBIDO:_____________________________________',0,0,'');
    $this->Ln();
    $this->Cell(5,6,'',0,0,'');
    $this->Cell(200,6,'FECHA/HORA: '.date('d-m-Y h:i:s A'),0,0,'');
    $this->Cell(125,6,'',0,0,'');
    $this->Ln(4);
     }
########################## FUNCION LISTAR COMPRAS ##############################

########################## FUNCION LISTAR CUENTAS POR PAGAR #########################
      function TablaListarCuentasxPagar()
   {

    $con = new Login();
    $con = $con->ConfiguracionPorId();
    $simbolo = $con[0]['simbolo'];

    $tra = new Login();
    $reg = $tra->ListarCuentasxPagar();

    $logo = ( file_exists("./fotos/logo-pdf.png") == "" ? "./assets/images/null.png" : "./fotos/logo-pdf.png");
    $logo2 = ( file_exists("./fotos/logo-principal.png") == "" ? "./assets/images/null.png" : "./fotos/logo-principal.png");
    
    $con = new Login();
    $con = $con->ConfiguracionPorId(); 

    $this->Ln(2);
    $this->SetFont('Courier','B',12);
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es blanco)
    $this->SetFillColor(5, 130, 275); // establece el color del fondo de la celda (en este caso es NARANJA
    $this->Cell(45,5,$this->Image($logo, $this->GetX()+80, $this->GetY()+4, 24),0,0,'C');
    $this->Cell(240,5,"GESTIÓN Y CONTROL PARA RESTAURANTES",0,0,'C');
    $this->Cell(45,5,$this->Image($logo2, $this->GetX()-62, $this->GetY()+4, 58),0,0,'C');

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(240,5,utf8_decode($con[0]['nomsucursal']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(240,5,utf8_decode($con[0]['documsucursal'] == '0' ? "" : $con[0]['documento'])." ".utf8_decode($con[0]['cuit']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    if($con[0]['id_provincia']!='0'){

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(240,5,utf8_decode($provincia = ($con[0]['id_provincia'] == '0' ? "" : $con[0]['provincia'])." ".$departamento = ($con[0]['id_departamento'] == '0' ? "" : $con[0]['departamento'])),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    }

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(240,5,utf8_decode($con[0]['direcsucursal']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(240,5,"Nº DE TLF: ".utf8_decode($con[0]['tlfsucursal']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(240,5,utf8_decode($con[0]['correosucursal']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');
    $this->Ln(8);
    
    $this->SetFont('Courier','B',14);  
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es negro)
    $this->Cell(330,10,'LISTADO GENERAL DE CUENTAS POR PAGAR',0,0,'C');

    $this->Ln();
    $this->SetFont('courier','B',10);
    $this->SetTextColor(255,255,255);  // Establece el color del texto (en este caso es BLANCO)
    $this->SetFillColor(230, 126, 34); // establece el color del fondo de la celda (en este caso es NARANJA)
    $this->Cell(15,8,'Nº',1,0,'C', True);
    $this->Cell(35,8,'Nº DE COMPRA',1,0,'C', True);
    $this->Cell(80,8,'DESCRIPCIÓN DE PROVEEDOR',1,0,'C', True);
    $this->Cell(40,8,'Nº ARTICULOS',1,0,'C', True);
    $this->Cell(40,8,'IMPORTE TOTAL',1,0,'C', True);
    $this->Cell(40,8,'STATUS',1,0,'C', True);
    $this->Cell(40,8,'FECHA VENCE',1,0,'C', True);
    $this->Cell(40,8,'FECHA DE EMISIÓN',1,1,'C', True);
    
    if($reg==""){
    echo "";      
    } else {
 
     /* AQUI DECLARO LAS COLUMNAS */
    $this->SetWidths(array(15,35,80,40,40,40,40,40));

    /* AQUI AGREGO LOS VALORES A MOSTRAR EN COLUMNAS */
    $a=1;
    $TotalArticulos=0;
    $TotalImporte=0;

    for($i=0;$i<sizeof($reg);$i++){ 
    
    $TotalArticulos+=$reg[$i]['articulos'];
    $TotalImporte+=$reg[$i]['totalpagoc'];

    $this->SetFont('Courier','',10);  
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es negro)
    $this->Row(array($a++,utf8_decode($reg[$i]["codcompra"]),portales(utf8_decode($reg[$i]["nomproveedor"])),utf8_decode($reg[$i]["articulos"]),utf8_decode($simbolo.number_format($reg[$i]['totalpagoc'], 2, '.', ',')),utf8_decode($reg[$i]['fechavencecredito']== '0000-00-00' || $reg[$i]['fechavencecredito'] >= date("Y-m-d") ? $reg[$i]['statuscompra'] : "VENCIDA"),utf8_decode($reg[$i]['fechavencecredito'] == '0000-00-00' ? "*********" : date("d-m-Y",strtotime($reg[$i]['fechavencecredito']))),utf8_decode(date("d-m-Y",strtotime($reg[$i]['fechaemision'])))));
        }
   
    $this->Cell(130,5,'',0,0,'C');
    $this->SetFont('courier','B',10);
    $this->SetTextColor(255,255,255);  // Establece el color del texto (en este caso es blanco)
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es blanco)
    $this->CellFitSpace(40,5,utf8_decode(number_format($TotalArticulos, 2, '.', ',')),1,0,'L');
    $this->CellFitSpace(40,5,utf8_decode($simbolo.number_format($TotalImporte, 2, '.', ',')),1,0,'L');
    $this->Ln();
      }

    $this->Ln(12); 
    $this->SetFont('courier','B',10);
    $this->Cell(5,6,'',0,0,'');
    $this->Cell(200,6,'ELABORADO: '.utf8_decode($_SESSION["nombres"]),0,0,'');
    $this->Cell(125,6,'RECIBIDO:_____________________________________',0,0,'');
    $this->Ln();
    $this->Cell(5,6,'',0,0,'');
    $this->Cell(200,6,'FECHA/HORA: '.date('d-m-Y h:i:s A'),0,0,'');
    $this->Cell(125,6,'',0,0,'');
    $this->Ln(4);
     }
########################## FUNCION LISTAR CUENTAS POR PAGAR #########################

####################### FUNCION LISTAR COMPRAS POR PROVEEDORES ########################
      function TablaListarComprasxProveedor()
   {

    $con = new Login();
    $con = $con->ConfiguracionPorId();
    $simbolo = $con[0]['simbolo'];
    
    $tra = new Login();
    $reg = $tra->BuscarComprasxProveedor();

    $logo = ( file_exists("./fotos/logo-pdf.png") == "" ? "./assets/images/null.png" : "./fotos/logo-pdf.png");
    $logo2 = ( file_exists("./fotos/logo-principal.png") == "" ? "./assets/images/null.png" : "./fotos/logo-principal.png");
    
    $con = new Login();
    $con = $con->ConfiguracionPorId(); 

    $this->Ln(2);
    $this->SetFont('Courier','B',12);
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es blanco)
    $this->SetFillColor(5, 130, 275); // establece el color del fondo de la celda (en este caso es NARANJA
    $this->Cell(45,5,$this->Image($logo, $this->GetX()+80, $this->GetY()+4, 24),0,0,'C');
    $this->Cell(240,5,"GESTIÓN Y CONTROL PARA RESTAURANTES",0,0,'C');
    $this->Cell(45,5,$this->Image($logo2, $this->GetX()-62, $this->GetY()+4, 58),0,0,'C');

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(240,5,utf8_decode($con[0]['nomsucursal']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(240,5,utf8_decode($con[0]['documsucursal'] == '0' ? "" : $con[0]['documento'])." ".utf8_decode($con[0]['cuit']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    if($con[0]['id_provincia']!='0'){

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(240,5,utf8_decode($provincia = ($con[0]['id_provincia'] == '0' ? "" : $con[0]['provincia'])." ".$departamento = ($con[0]['id_departamento'] == '0' ? "" : $con[0]['departamento'])),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    }

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(240,5,utf8_decode($con[0]['direcsucursal']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(240,5,"Nº DE TLF: ".utf8_decode($con[0]['tlfsucursal']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(240,5,utf8_decode($con[0]['correosucursal']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');
    $this->Ln(8);
    
    $this->SetFont('Courier','B',14);  
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es negro)
    $this->Cell(330,7,'LISTADO DE COMPRAS POR PROVEEDOR',0,0,'C');

    $this->Ln(10);
    $this->SetFont('courier','B',10);
    $this->SetTextColor(255,255,255);  // Establece el color del texto (en este caso es BLANCO)
    $this->SetFillColor(230, 126, 34); // establece el color del fondo de la celda (en este caso es NARANJA)
    $this->Cell(15,8,'Nº',1,0,'C', True);
    $this->Cell(35,8,'Nº DE COMPRA',1,0,'C', True);
    $this->Cell(80,8,'DESCRIPCIÓN DE PROVEEDOR',1,0,'C', True);
    $this->Cell(40,8,'Nº ARTICULOS',1,0,'C', True);
    $this->Cell(40,8,'IMPORTE TOTAL',1,0,'C', True);
    $this->Cell(40,8,'STATUS',1,0,'C', True);
    $this->Cell(40,8,'FECHA VENCE',1,0,'C', True);
    $this->Cell(40,8,'FECHA DE EMISIÓN',1,1,'C', True);
    
    if($reg==""){
    echo "";      
    } else {
 
     /* AQUI DECLARO LAS COLUMNAS */
    $this->SetWidths(array(15,35,80,40,40,40,40,40));

    /* AQUI AGREGO LOS VALORES A MOSTRAR EN COLUMNAS */
    $a=1;
    $TotalArticulos=0;
    $TotalImporte=0;

    for($i=0;$i<sizeof($reg);$i++){ 
    
    $TotalArticulos+=$reg[$i]['articulos'];
    $TotalImporte+=$reg[$i]['totalpagoc'];
 
    $this->SetFont('Courier','',10);  
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es negro)
    $this->Row(array($a++,utf8_decode($reg[$i]["codcompra"]),portales(utf8_decode($reg[$i]["nomproveedor"])),utf8_decode($reg[$i]["articulos"]),utf8_decode($simbolo.number_format($reg[$i]['totalpagoc'], 2, '.', ',')),utf8_decode($reg[$i]['fechavencecredito']== '0000-00-00' || $reg[$i]['fechavencecredito'] >= date("Y-m-d") ? $reg[$i]['statuscompra'] : "VENCIDA"),utf8_decode($reg[$i]['fechavencecredito'] == '0000-00-00' ? "*********" : date("d-m-Y",strtotime($reg[$i]['fechavencecredito']))),utf8_decode(date("d-m-Y",strtotime($reg[$i]['fechaemision'])))));
        }
      }
   
    $this->Cell(130,5,'',0,0,'C');
    $this->SetFont('courier','B',10);
    $this->SetTextColor(255,255,255);  // Establece el color del texto (en este caso es blanco)
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es blanco)
    $this->CellFitSpace(40,5,utf8_decode(number_format($TotalArticulos, 2, '.', ',')),1,0,'L');
    $this->CellFitSpace(40,5,utf8_decode($simbolo.number_format($TotalImporte, 2, '.', ',')),1,0,'L');
    $this->Ln();

    $this->Ln(12); 
    $this->SetFont('courier','B',10);
    $this->Cell(5,6,'',0,0,'');
    $this->Cell(200,6,'ELABORADO: '.utf8_decode($_SESSION["nombres"]),0,0,'');
    $this->Cell(125,6,'RECIBIDO:_____________________________________',0,0,'');
    $this->Ln();
    $this->Cell(5,6,'',0,0,'');
    $this->Cell(200,6,'FECHA/HORA: '.date('d-m-Y h:i:s A'),0,0,'');
    $this->Cell(125,6,'',0,0,'');
    $this->Ln(4);
     }
####################### FUNCION LISTAR COMPRAS POR PROVEEDORES #########################

####################### FUNCION LISTAR COMPRAS POR FECHAS #########################
      function TablaListarComprasxFechas()
   {

    $con = new Login();
    $con = $con->ConfiguracionPorId();
    $simbolo = $con[0]['simbolo'];
    
    $tra = new Login();
    $reg = $tra->BuscarComprasxFechas();

    $logo = ( file_exists("./fotos/logo-pdf.png") == "" ? "./assets/images/null.png" : "./fotos/logo-pdf.png");
    $logo2 = ( file_exists("./fotos/logo-principal.png") == "" ? "./assets/images/null.png" : "./fotos/logo-principal.png");
    
    $con = new Login();
    $con = $con->ConfiguracionPorId(); 

    $this->Ln(2);
    $this->SetFont('Courier','B',12);
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es blanco)
    $this->SetFillColor(5, 130, 275); // establece el color del fondo de la celda (en este caso es NARANJA
    $this->Cell(45,5,$this->Image($logo, $this->GetX()+80, $this->GetY()+4, 24),0,0,'C');
    $this->Cell(240,5,"GESTIÓN Y CONTROL PARA RESTAURANTES",0,0,'C');
    $this->Cell(45,5,$this->Image($logo2, $this->GetX()-62, $this->GetY()+4, 58),0,0,'C');

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(240,5,utf8_decode($con[0]['nomsucursal']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(240,5,utf8_decode($con[0]['documsucursal'] == '0' ? "" : $con[0]['documento'])." ".utf8_decode($con[0]['cuit']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    if($con[0]['id_provincia']!='0'){

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(240,5,utf8_decode($provincia = ($con[0]['id_provincia'] == '0' ? "" : $con[0]['provincia'])." ".$departamento = ($con[0]['id_departamento'] == '0' ? "" : $con[0]['departamento'])),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    }

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(240,5,utf8_decode($con[0]['direcsucursal']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(240,5,"Nº DE TLF: ".utf8_decode($con[0]['tlfsucursal']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(240,5,utf8_decode($con[0]['correosucursal']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');
    $this->Ln(8);
    
    $this->SetFont('Courier','B',14);  
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es negro)
    $this->Cell(330,7,'LISTADO DE COMPRAS POR FECHAS (DESDE '.date("d-m-Y", strtotime($_GET["desde"]))." HASTA ".date("d-m-Y", strtotime($_GET["hasta"])).")",0,0,'C');

    $this->Ln(10);
    $this->SetFont('courier','B',10);
    $this->SetTextColor(255,255,255);  // Establece el color del texto (en este caso es BLANCO)
    $this->SetFillColor(230, 126, 34); // establece el color del fondo de la celda (en este caso es NARANJA)
    $this->Cell(15,8,'Nº',1,0,'C', True);
    $this->Cell(35,8,'Nº DE COMPRA',1,0,'C', True);
    $this->Cell(80,8,'DESCRIPCIÓN DE PROVEEDOR',1,0,'C', True);
    $this->Cell(40,8,'Nº ARTICULOS',1,0,'C', True);
    $this->Cell(40,8,'IMPORTE TOTAL',1,0,'C', True);
    $this->Cell(40,8,'STATUS',1,0,'C', True);
    $this->Cell(40,8,'FECHA VENCE',1,0,'C', True);
    $this->Cell(40,8,'FECHA DE EMISIÓN',1,1,'C', True);
    
    if($reg==""){
    echo "";      
    } else {
 
     /* AQUI DECLARO LAS COLUMNAS */
    $this->SetWidths(array(15,35,80,40,40,40,40,40));

    /* AQUI AGREGO LOS VALORES A MOSTRAR EN COLUMNAS */
    $a=1;
    $TotalArticulos=0;
    $TotalImporte=0;

    for($i=0;$i<sizeof($reg);$i++){ 
    
    $TotalArticulos+=$reg[$i]['articulos'];
    $TotalImporte+=$reg[$i]['totalpagoc'];
 
    $this->SetFont('Courier','',10);  
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es negro)
    $this->Row(array($a++,utf8_decode($reg[$i]["codcompra"]),portales(utf8_decode($reg[$i]["nomproveedor"])),utf8_decode($reg[$i]["articulos"]),utf8_decode($simbolo.number_format($reg[$i]['totalpagoc'], 2, '.', ',')),utf8_decode($reg[$i]['fechavencecredito']== '0000-00-00' || $reg[$i]['fechavencecredito'] >= date("Y-m-d") ? $reg[$i]['statuscompra'] : "VENCIDA"),utf8_decode($reg[$i]['fechavencecredito'] == '0000-00-00' ? "*********" : date("d-m-Y",strtotime($reg[$i]['fechavencecredito']))),utf8_decode(date("d-m-Y",strtotime($reg[$i]['fechaemision'])))));
        }
      }
   
    $this->Cell(130,5,'',0,0,'C');
    $this->SetFont('courier','B',10);
    $this->SetTextColor(255,255,255);  // Establece el color del texto (en este caso es blanco)
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es blanco)
    $this->CellFitSpace(40,5,utf8_decode(number_format($TotalArticulos, 2, '.', ',')),1,0,'L');
    $this->CellFitSpace(40,5,utf8_decode($simbolo.number_format($TotalImporte, 2, '.', ',')),1,0,'L');
    $this->Ln();

    $this->Ln(12); 
    $this->SetFont('courier','B',10);
    $this->Cell(5,6,'',0,0,'');
    $this->Cell(200,6,'ELABORADO: '.utf8_decode($_SESSION["nombres"]),0,0,'');
    $this->Cell(125,6,'RECIBIDO:_____________________________________',0,0,'');
    $this->Ln();
    $this->Cell(5,6,'',0,0,'');
    $this->Cell(200,6,'FECHA/HORA: '.date('d-m-Y h:i:s A'),0,0,'');
    $this->Cell(125,6,'',0,0,'');
    $this->Ln(4);
     }
########################## FUNCION LISTAR COMPRAS POR FECHAS #########################

############################### REPORTES DE COMPRAS #################################





















































########################### REPORTES DE CAJAS DE VENTAS ##############################

########################## FUNCION LISTAR CAJAS ASIGNADAS ##############################
      function TablaListarCajas()
   {

    $con = new Login();
    $con = $con->ConfiguracionPorId();
    $simbolo = $con[0]['simbolo'];
    
    $tra = new Login();
    $reg = $tra->ListarCajas();
    
    $logo = ( file_exists("./fotos/logo-pdf.png") == "" ? "./assets/images/null.png" : "./fotos/logo-pdf.png");
    $logo2 = ( file_exists("./fotos/logo-principal.png") == "" ? "./assets/images/null.png" : "./fotos/logo-principal.png");
    
    $con = new Login();
    $con = $con->ConfiguracionPorId(); 

    $this->Ln(2);
    $this->SetFont('Courier','B',11);
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es blanco)
    $this->SetFillColor(5, 130, 275); // establece el color del fondo de la celda (en este caso es NARANJA
    $this->Cell(45,5,$this->Image($logo, $this->GetX()+18, $this->GetY()+4, 22),0,0,'C');
    $this->Cell(100,5,"GESTIÓN Y CONTROL PARA RESTAURANTES",0,0,'C');
    $this->Cell(45,5,$this->Image($logo2, $this->GetX()+2, $this->GetY()+4, 40),0,0,'C');

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(100,5,utf8_decode($con[0]['nomsucursal']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(100,5,utf8_decode($con[0]['documsucursal'] == '0' ? "" : $con[0]['documento'])." ".utf8_decode($con[0]['cuit']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    if($con[0]['id_provincia']!='0'){

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(100,5,utf8_decode($provincia = ($con[0]['id_provincia'] == '0' ? "" : $con[0]['provincia'])." ".$departamento = ($con[0]['id_departamento'] == '0' ? "" : $con[0]['departamento'])),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    }

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(100,5,utf8_decode($con[0]['direcsucursal']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(100,5,"Nº DE TLF: ".utf8_decode($con[0]['tlfsucursal']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(100,5,utf8_decode($con[0]['correosucursal']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');
    $this->Ln(8);

    $this->SetFont('Courier','B',12);  
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es negro)
    $this->Cell(190,10,'LISTADO GENERAL DE CAJAS ASIGNADAS',0,0,'C');

    $this->Ln();
    $this->SetFont('courier','B',10);
    $this->SetTextColor(255,255,255);  // Establece el color del texto (en este caso es BLANCO)
    $this->SetFillColor(230, 126, 34); // establece el color del fondo de la celda (en este caso es NARANJA)
    $this->Cell(10,8,'Nº',1,0,'C', True);
    $this->Cell(35,8,'Nº DE CAJA',1,0,'C', True);
    $this->Cell(55,8,'NOMBRE DE CAJA',1,0,'C', True);
    $this->Cell(90,8,'RESPONSABLE',1,1,'C', True);

    if($reg==""){
    echo "";      
    } else {
 
     /* AQUI DECLARO LAS COLUMNAS */
    $this->SetWidths(array(10,35,55,90));

    /* AQUI AGREGO LOS VALORES A MOSTRAR EN COLUMNAS */
    $a=1;
    for($i=0;$i<sizeof($reg);$i++){ 
    $this->SetFont('Courier','',10);  
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es negro)
    $this->Row(array($a++,utf8_decode($reg[$i]["nrocaja"]),utf8_decode($reg[$i]['nomcaja']),utf8_decode($reg[$i]["nombres"])));
        }
      }

    $this->Ln(12); 
    $this->SetFont('courier','B',10);
    $this->Cell(4,6,'',0,0,'');
    $this->Cell(100,6,'ELABORADO: '.utf8_decode($_SESSION["nombres"]),0,0,'');
    $this->Cell(86,6,'RECIBIDO:____________________________',0,0,'');
    $this->Ln();
    $this->Cell(4,6,'',0,0,'');
    $this->Cell(100,6,'FECHA/HORA: '.date('d-m-Y h:i:s A'),0,0,'');
    $this->Cell(86,6,'',0,0,'');
    $this->Ln(4);
     }
########################## FUNCION LISTAR CAJAS ASIGNADAS ##############################

########################## FUNCION LISTAR ARQUEOS DE CAJAS ##############################
      function TablaListarArqueos()
   {

    $con = new Login();
    $con = $con->ConfiguracionPorId();
    $simbolo = $con[0]['simbolo'];
    
    $tra = new Login();
    $reg = $tra->ListarArqueoCaja();

    $logo = ( file_exists("./fotos/logo-pdf.png") == "" ? "./assets/images/null.png" : "./fotos/logo-pdf.png");
    $logo2 = ( file_exists("./fotos/logo-principal.png") == "" ? "./assets/images/null.png" : "./fotos/logo-principal.png");
    
    $con = new Login();
    $con = $con->ConfiguracionPorId(); 

    $this->Ln(2);
    $this->SetFont('Courier','B',12);
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es blanco)
    $this->SetFillColor(5, 130, 275); // establece el color del fondo de la celda (en este caso es NARANJA
    $this->Cell(45,5,$this->Image($logo, $this->GetX()+80, $this->GetY()+4, 24),0,0,'C');
    $this->Cell(240,5,"GESTIÓN Y CONTROL PARA RESTAURANTES",0,0,'C');
    $this->Cell(45,5,$this->Image($logo2, $this->GetX()-62, $this->GetY()+4, 58),0,0,'C');

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(240,5,utf8_decode($con[0]['nomsucursal']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(240,5,utf8_decode($con[0]['documsucursal'] == '0' ? "" : $con[0]['documento'])." ".utf8_decode($con[0]['cuit']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    if($con[0]['id_provincia']!='0'){

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(240,5,utf8_decode($provincia = ($con[0]['id_provincia'] == '0' ? "" : $con[0]['provincia'])." ".$departamento = ($con[0]['id_departamento'] == '0' ? "" : $con[0]['departamento'])),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    }

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(240,5,utf8_decode($con[0]['direcsucursal']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(240,5,"Nº DE TLF: ".utf8_decode($con[0]['tlfsucursal']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(240,5,utf8_decode($con[0]['correosucursal']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');
    $this->Ln(8);

    $this->SetFont('Courier','B',14);  
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es negro)
    $this->Cell(330,10,'LISTADO GENERAL DE ARQUEOS EN CAJAS',0,0,'C');

    $this->Ln();
    $this->SetFont('courier','B',10);
    $this->SetTextColor(255,255,255);  // Establece el color del texto (en este caso es BLANCO)
    $this->SetFillColor(230, 126, 34); // establece el color del fondo de la celda (en este caso es NARANJA)
    $this->Cell(10,8,'Nº',1,0,'C', True);
    $this->Cell(75,8,'Nº DE CAJA',1,0,'C', True);
    $this->Cell(25,8,'INICIO',1,0,'C', True);
    $this->Cell(25,8,'CIERRE',1,0,'C', True);
    $this->Cell(20,8,'INICIAL',1,0,'C', True);
    $this->Cell(25,8,'INGRESOS',1,0,'C', True);
    $this->Cell(25,8,'EGRESOS',1,0,'C', True);
    $this->Cell(25,8,'CRÉDITOS',1,0,'C', True);
    $this->Cell(25,8,'ABONOS',1,0,'C', True);
    $this->Cell(25,8,'PROPINAS',1,0,'C', True);
    $this->Cell(25,8,'EFECTIVO',1,0,'C', True);
    $this->Cell(25,8,'DIFERENCIA',1,1,'C', True);
    
    if($reg==""){
    echo "";      
    } else {
 
     /* AQUI DECLARO LAS COLUMNAS */
    $this->SetWidths(array(10,75,25,25,20,25,25,25,25,25,25,25));

    /* AQUI AGREGO LOS VALORES A MOSTRAR EN COLUMNAS */
    $a=1;
    for($i=0;$i<sizeof($reg);$i++){ 
    $this->SetFont('Courier','',10);  
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es negro)
    $this->Row(array($a++,
        utf8_decode($reg[$i]['nrocaja'].": ".$reg[$i]['nomcaja']."/".$reg[$i]['nombres']),
        utf8_decode( date("d-m-Y h:i:s",strtotime($reg[$i]['fechaapertura']))),
    utf8_decode($reg[$i]['fechacierre'] == '0000-00-00 00:00:00' ? "*********" : date("d-m-Y h:i:s",strtotime($reg[$i]['fechacierre']))),
        utf8_decode($simbolo.number_format($reg[$i]['montoinicial'], 2, '.', ',')),
        utf8_decode($simbolo.number_format($reg[$i]['ingresos'], 2, '.', ',')),
        utf8_decode($simbolo.number_format($reg[$i]['egresos'], 2, '.', ',')),
        utf8_decode($simbolo.number_format($reg[$i]['creditos'], 2, '.', ',')),
        utf8_decode($simbolo.number_format($reg[$i]['abonos'], 2, '.', ',')),
        utf8_decode($simbolo.number_format($reg[$i]['propinas'], 2, '.', ',')),
        utf8_decode($simbolo.number_format($reg[$i]['dineroefectivo'], 2, '.', ',')),
        utf8_decode($simbolo.number_format($reg[$i]['diferencia'], 2, '.', ','))));
        }
      }

    $this->Ln(12); 
    $this->SetFont('courier','B',10);
    $this->Cell(5,6,'',0,0,'');
    $this->Cell(200,6,'ELABORADO: '.utf8_decode($_SESSION["nombres"]),0,0,'');
    $this->Cell(125,6,'RECIBIDO:_____________________________________',0,0,'');
    $this->Ln();
    $this->Cell(5,6,'',0,0,'');
    $this->Cell(200,6,'FECHA/HORA: '.date('d-m-Y h:i:s A'),0,0,'');
    $this->Cell(125,6,'',0,0,'');
    $this->Ln(4);
     }
########################## FUNCION LISTAR ARQUEOS DE CAJAS ##############################

####################### FUNCION LISTAR MOVIMIENTOS EN CAJA ##########################
      function TablaListarMovimientos()
   {

    $con = new Login();
    $con = $con->ConfiguracionPorId();
    $simbolo = $con[0]['simbolo'];
    
    $tra = new Login();
    $reg = $tra->ListarMovimientos();
    
    $logo = ( file_exists("./fotos/logo-pdf.png") == "" ? "./assets/images/null.png" : "./fotos/logo-pdf.png");
    $logo2 = ( file_exists("./fotos/logo-principal.png") == "" ? "./assets/images/null.png" : "./fotos/logo-principal.png");
    
    $con = new Login();
    $con = $con->ConfiguracionPorId(); 

    $this->Ln(2);
    $this->SetFont('Courier','B',11);
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es blanco)
    $this->SetFillColor(5, 130, 275); // establece el color del fondo de la celda (en este caso es NARANJA
    $this->Cell(45,5,$this->Image($logo, $this->GetX()+18, $this->GetY()+4, 22),0,0,'C');
    $this->Cell(100,5,"GESTIÓN Y CONTROL PARA RESTAURANTES",0,0,'C');
    $this->Cell(45,5,$this->Image($logo2, $this->GetX()+2, $this->GetY()+4, 40),0,0,'C');

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(100,5,utf8_decode($con[0]['nomsucursal']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(100,5,utf8_decode($con[0]['documsucursal'] == '0' ? "" : $con[0]['documento'])." ".utf8_decode($con[0]['cuit']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    if($con[0]['id_provincia']!='0'){

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(100,5,utf8_decode($provincia = ($con[0]['id_provincia'] == '0' ? "" : $con[0]['provincia'])." ".$departamento = ($con[0]['id_departamento'] == '0' ? "" : $con[0]['departamento'])),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    }

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(100,5,utf8_decode($con[0]['direcsucursal']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(100,5,"Nº DE TLF: ".utf8_decode($con[0]['tlfsucursal']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(100,5,utf8_decode($con[0]['correosucursal']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');
    $this->Ln(8);

    $this->SetFont('Courier','B',12);  
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es negro)
    $this->Cell(190,10,'LISTADO GENERAL DE MOVIMIENTOS EN CAJA',0,0,'C');

    $this->Ln();
    $this->SetFont('courier','B',10);
    $this->SetTextColor(255,255,255);  // Establece el color del texto (en este caso es BLANCO)
    $this->SetFillColor(230, 126, 34); // establece el color del fondo de la celda (en este caso es NARANJA)
    $this->Cell(10,8,'Nº',1,0,'C', True);
    $this->Cell(40,8,'Nº DE CAJA',1,0,'C', True);
    $this->Cell(20,8,'TIPO',1,0,'C', True);
    $this->Cell(55,8,'DESCRIPCIÓN',1,0,'C', True);
    $this->Cell(30,8,'MONTO',1,0,'C', True);
    $this->Cell(35,8,'MEDIO',1,1,'C', True);

    if($reg==""){
    echo "";      
    } else {
 
     /* AQUI DECLARO LAS COLUMNAS */
    $this->SetWidths(array(10,40,20,55,30,35));

    /* AQUI AGREGO LOS VALORES A MOSTRAR EN COLUMNAS */
    $a=1;
    for($i=0;$i<sizeof($reg);$i++){ 
    $this->SetFont('Courier','',10);  
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es negro)
    $this->Row(array($a++,utf8_decode($reg[$i]['nrocaja'].": ".$reg[$i]['nomcaja']),utf8_decode($reg[$i]["tipomovimiento"]),utf8_decode($reg[$i]['descripcionmovimiento']),utf8_decode($simbolo.number_format($reg[$i]['montomovimiento'], 2, '.', ',')),utf8_decode($reg[$i]["mediopago"])));
        }
      }

    $this->Ln(12); 
    $this->SetFont('courier','B',10);
    $this->Cell(4,6,'',0,0,'');
    $this->Cell(100,6,'ELABORADO: '.utf8_decode($_SESSION["nombres"]),0,0,'');
    $this->Cell(86,6,'RECIBIDO:____________________________',0,0,'');
    $this->Ln();
    $this->Cell(4,6,'',0,0,'');
    $this->Cell(100,6,'FECHA/HORA: '.date('d-m-Y h:i:s A'),0,0,'');
    $this->Cell(86,6,'',0,0,'');
    $this->Ln(4);
     }
######################## FUNCION LISTAR MOVIMIENTOS EN CAJAS #########################

####################### FUNCION LISTAR ARQUEOS DE CAJAS POR FECHAS ######################
      function TablaListarArqueosxFechas()
   {

    $con = new Login();
    $con = $con->ConfiguracionPorId();
    $simbolo = $con[0]['simbolo'];
    
    $tra = new Login();
    $reg = $tra->BuscarArqueosxFechas();

    $logo = ( file_exists("./fotos/logo-pdf.png") == "" ? "./assets/images/null.png" : "./fotos/logo-pdf.png");
    $logo2 = ( file_exists("./fotos/logo-principal.png") == "" ? "./assets/images/null.png" : "./fotos/logo-principal.png");
    
    $con = new Login();
    $con = $con->ConfiguracionPorId(); 

    $this->Ln(2);
    $this->SetFont('Courier','B',12);
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es blanco)
    $this->SetFillColor(5, 130, 275); // establece el color del fondo de la celda (en este caso es NARANJA
    $this->Cell(45,5,$this->Image($logo, $this->GetX()+80, $this->GetY()+4, 24),0,0,'C');
    $this->Cell(240,5,"GESTIÓN Y CONTROL PARA RESTAURANTES",0,0,'C');
    $this->Cell(45,5,$this->Image($logo2, $this->GetX()-62, $this->GetY()+4, 58),0,0,'C');

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(240,5,utf8_decode($con[0]['nomsucursal']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(240,5,utf8_decode($con[0]['documsucursal'] == '0' ? "" : $con[0]['documento'])." ".utf8_decode($con[0]['cuit']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    if($con[0]['id_provincia']!='0'){

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(240,5,utf8_decode($provincia = ($con[0]['id_provincia'] == '0' ? "" : $con[0]['provincia'])." ".$departamento = ($con[0]['id_departamento'] == '0' ? "" : $con[0]['departamento'])),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    }

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(240,5,utf8_decode($con[0]['direcsucursal']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(240,5,"Nº DE TLF: ".utf8_decode($con[0]['tlfsucursal']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(240,5,utf8_decode($con[0]['correosucursal']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');
    $this->Ln(8);
    
    $this->SetFont('Courier','B',14);  
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es negro)
    $this->Cell(330,7,"LISTADO DE ARQUEOS EN CAJA (N°: ".$reg[0]['nrocaja'].": ".$reg[0]['nomcaja'].": ".$reg[0]['nombres'].")",0,1,'C');
    $this->Cell(330,7,"Y FECHAS (DESDE ".date("d-m-Y", strtotime($_GET["desde"]))." HASTA ".date("d-m-Y", strtotime($_GET["hasta"])).")",0,0,'C');

    $this->Ln(10);
    $this->SetFont('courier','B',10);
    $this->SetTextColor(255,255,255);  // Establece el color del texto (en este caso es BLANCO)
    $this->SetFillColor(230, 126, 34); // establece el color del fondo de la celda (en este caso es NARANJA)
    $this->Cell(10,8,'Nº',1,0,'C', True);
    $this->Cell(30,8,'INICIO',1,0,'C', True);
    $this->Cell(30,8,'CIERRE',1,0,'C', True);
    $this->Cell(20,8,'INICIAL',1,0,'C', True);
    $this->Cell(25,8,'INGRESOS',1,0,'C', True);
    $this->Cell(25,8,'EGRESOS',1,0,'C', True);
    $this->Cell(25,8,'CRÉDITOS',1,0,'C', True);
    $this->Cell(22,8,'ABONOS',1,0,'C', True);
    $this->Cell(25,8,'PROPINAS',1,0,'C', True);
    $this->Cell(29,8,'TOTAL VENTAS',1,0,'C', True);
    $this->Cell(30,8,'TOTAL INGRESOS',1,0,'C', True);
    $this->Cell(30,8,'EFECTIVO',1,0,'C', True);
    $this->Cell(29,8,'DIFERENCIA',1,1,'C', True);
    
    if($reg==""){
    echo "";      
    } else {
 
     /* AQUI DECLARO LAS COLUMNAS */
    $this->SetWidths(array(10,30,30,20,25,25,25,22,25,29,30,30,29));

    /* AQUI AGREGO LOS VALORES A MOSTRAR EN COLUMNAS */
    $a=1;
    for($i=0;$i<sizeof($reg);$i++){ 
    $this->SetFont('Courier','',10);  
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es negro)
    $this->Row(array($a++,
        utf8_decode( date("d-m-Y h:i:s",strtotime($reg[$i]['fechaapertura']))),
    utf8_decode($reg[$i]['fechacierre'] == '0000-00-00 00:00:00' ? "*********" : date("d-m-Y h:i:s",strtotime($reg[$i]['fechacierre']))),
        utf8_decode($simbolo.number_format($reg[$i]['montoinicial'], 2, '.', ',')),
        utf8_decode($simbolo.number_format($reg[$i]['ingresos'], 2, '.', ',')),
        utf8_decode($simbolo.number_format($reg[$i]['egresos'], 2, '.', ',')),
        utf8_decode($simbolo.number_format($reg[$i]['creditos'], 2, '.', ',')),
        utf8_decode($simbolo.number_format($reg[$i]['abonos'], 2, '.', ',')),
        utf8_decode($simbolo.number_format($reg[$i]['propinas'], 2, '.', ',')),
        utf8_decode($simbolo.number_format($reg[$i]['ingresos']+$reg[$i]['creditos'], 2, '.', ',')),
        utf8_decode($simbolo.number_format($reg[$i]['montoinicial']+$reg[$i]['ingresos']+$reg[$i]['propinas']+$reg[$i]['abonos']-$reg[$i]['egresos'], 2, '.', ',')),
        utf8_decode($simbolo.number_format($reg[$i]['dineroefectivo'], 2, '.', ',')),
        utf8_decode($simbolo.number_format($reg[$i]['diferencia'], 2, '.', ','))));
        }
      }

    $this->Ln(12); 
    $this->SetFont('courier','B',10);
    $this->Cell(5,6,'',0,0,'');
    $this->Cell(200,6,'ELABORADO: '.utf8_decode($_SESSION["nombres"]),0,0,'');
    $this->Cell(125,6,'RECIBIDO:_____________________________________',0,0,'');
    $this->Ln();
    $this->Cell(5,6,'',0,0,'');
    $this->Cell(200,6,'FECHA/HORA: '.date('d-m-Y h:i:s A'),0,0,'');
    $this->Cell(125,6,'',0,0,'');
    $this->Ln(4);
     }
###################### FUNCION LISTAR ARQUEOS DE CAJAS POR FECHAS ######################

##################### FUNCION LISTAR MOVIMIENTOS EN CAJA POR FECHAS #####################
      function TablaListarMovimientosxFechas()
   {

    $con = new Login();
    $con = $con->ConfiguracionPorId();
    $simbolo = $con[0]['simbolo'];
    
    $tra = new Login();
    $reg = $tra->BuscarMovimientosxFechas();
    
    $logo = ( file_exists("./fotos/logo-pdf.png") == "" ? "./assets/images/null.png" : "./fotos/logo-pdf.png");
    $logo2 = ( file_exists("./fotos/logo-principal.png") == "" ? "./assets/images/null.png" : "./fotos/logo-principal.png");
    
    $con = new Login();
    $con = $con->ConfiguracionPorId(); 

    $this->Ln(2);
    $this->SetFont('Courier','B',11);
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es blanco)
    $this->SetFillColor(5, 130, 275); // establece el color del fondo de la celda (en este caso es NARANJA
    $this->Cell(45,5,$this->Image($logo, $this->GetX()+18, $this->GetY()+4, 22),0,0,'C');
    $this->Cell(100,5,"GESTIÓN Y CONTROL PARA RESTAURANTES",0,0,'C');
    $this->Cell(45,5,$this->Image($logo2, $this->GetX()+2, $this->GetY()+4, 40),0,0,'C');

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(100,5,utf8_decode($con[0]['nomsucursal']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(100,5,utf8_decode($con[0]['documsucursal'] == '0' ? "" : $con[0]['documento'])." ".utf8_decode($con[0]['cuit']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    if($con[0]['id_provincia']!='0'){

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(100,5,utf8_decode($provincia = ($con[0]['id_provincia'] == '0' ? "" : $con[0]['provincia'])." ".$departamento = ($con[0]['id_departamento'] == '0' ? "" : $con[0]['departamento'])),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    }

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(100,5,utf8_decode($con[0]['direcsucursal']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(100,5,"Nº DE TLF: ".utf8_decode($con[0]['tlfsucursal']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(100,5,utf8_decode($con[0]['correosucursal']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');
    $this->Ln(8);

    $this->SetFont('Courier','B',14);  
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es negro)
    $this->Cell(190,7,"LISTADO DE MOVIMIENTOS EN CAJA (N°: ".$reg[0]['nrocaja'].": ".$reg[0]['nomcaja'].")",0,1,'C');
    $this->Cell(190,7,"Y FECHAS (DESDE ".date("d-m-Y", strtotime($_GET["desde"]))." HASTA ".date("d-m-Y", strtotime($_GET["hasta"])).")",0,0,'C');

    $this->Ln(10);
    $this->SetFont('courier','B',10);
    $this->SetTextColor(255,255,255);  // Establece el color del texto (en este caso es BLANCO)
    $this->SetFillColor(230, 126, 34); // establece el color del fondo de la celda (en este caso es NARANJA)
    $this->Cell(10,8,'Nº',1,0,'C', True);
    $this->Cell(20,8,'TIPO',1,0,'C', True);
    $this->Cell(75,8,'DESCRIPCIÓN',1,0,'C', True);
    $this->Cell(40,8,'MONTO',1,0,'C', True);
    $this->Cell(45,8,'MEDIO',1,1,'C', True);

    if($reg==""){
    echo "";      
    } else {
 
     /* AQUI DECLARO LAS COLUMNAS */
    $this->SetWidths(array(10,20,75,40,45));

    /* AQUI AGREGO LOS VALORES A MOSTRAR EN COLUMNAS */
    $a=1;
    for($i=0;$i<sizeof($reg);$i++){ 
    $this->SetFont('Courier','',10);  
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es negro)
    $this->Row(array($a++,utf8_decode($reg[$i]["tipomovimiento"]),utf8_decode($reg[$i]['descripcionmovimiento']),utf8_decode($simbolo.number_format($reg[$i]['montomovimiento'], 2, '.', ',')),utf8_decode($reg[$i]["mediopago"])));
        }
      }

    $this->Ln(12); 
    $this->SetFont('courier','B',10);
    $this->Cell(4,6,'',0,0,'');
    $this->Cell(100,6,'ELABORADO: '.utf8_decode($_SESSION["nombres"]),0,0,'');
    $this->Cell(86,6,'RECIBIDO:____________________________',0,0,'');
    $this->Ln();
    $this->Cell(4,6,'',0,0,'');
    $this->Cell(100,6,'FECHA/HORA: '.date('d-m-Y h:i:s A'),0,0,'');
    $this->Cell(86,6,'',0,0,'');
    $this->Ln(4);
     }
##################### FUNCION LISTAR MOVIMIENTOS EN CAJAS POR FECHAS ###################

############################## REPORTES DE CAJAS DE VENTAS ##############################














































########################## CLASE VENTAS DE PRODUCTOS ########################


########################## FUNCION TICKET COMANDA ##############################
      function TicketComanda()
    {  

    $con = new Login();
    $con = $con->ConfiguracionPorId();
    $simbolo = $con[0]['simbolo'];
    $moneda = $con[0]['moneda'];

    $tra = new Login();
    $reg = $tra->DetallesPedidoMesa();
  
     $this->SetFont('courier','B',12);
    $this->SetFillColor(2,157,116);
    $this->SetXY(4, 5);
    $this->Cell(65, 5, "TICKET DE COMANDA", 0, 0, 'C');
    $this->Ln(5);
  
    $this->SetFont('courier','B',11);
    $this->SetXY(4, 8.5);
    $this->CellFitSpace(65,6,utf8_decode($con[0]['nomsucursal']), 0, 1, 'C');
    $this->SetFont('courier','B',9);
    $this->SetXY(4, 13);
    //$this->MultiCell(65,3,$this->SetFont('Courier','B',8)."MATRIZ:".utf8_decode($provincia = ($con[0]['provincia'] == '' ? "*********" : $con[0]['provincia'])." ".$departamento = ($con[0]['departamento'] == '' ? "*********" : $reg[0]['departamento'])." ".$con[0]["direcsucursal"]),0,'C');
    $this->SetXY(4, 19);
    $this->CellFitSpace(65,3,"OBLIGADO A LLEVAR CONTABILIDAD:".utf8_decode($con[0]['llevacontabilidad']), 0, 1, 'C');
    $this->SetXY(4, 22);
    $this->CellFitSpace(65,3,'Nº DE '.$documento = ($con[0]['documsucursal'] == '0' ? "REG.:" : $con[0]['documento'].":").utf8_decode($con[0]['cuit']), 0, 1, 'C');
    $this->SetXY(4, 25);
    $this->CellFitSpace(65,3,"Nº PEDIDO:".utf8_decode($reg[0]['codpedido']."-".$reg[0]['pedido']), 0, 1, 'C');
    $this->SetXY(4, 28);
    $this->CellFitSpace(65,3,"AMBIENTE: PRODUCCIÓN", 0, 1, 'C');
    $this->SetXY(4, 31);
    $this->CellFitSpace(65,3,"EMISIÓN: NORMAL", 0, 1, 'C');
    $this->SetXY(4, 34);

    $this->SetFont('courier','B',9);
    $this->SetX(2);
    $this->Cell(70,3,'-----------------------------------',0,0,'C');
    $this->Ln(3);

    if($reg[0]['delivery']!="1"){

    $this->SetFont('courier','',8);
    $this->SetX(4);
    $this->CellFitSpace(66, 3, "SALA: ".utf8_decode($reg[0]['nomsala']),0,1,'L');
    $this->SetX(4);
    $this->CellFitSpace(66, 3, "N° DE MESA: ".utf8_decode($reg[0]['nommesa']),0,1,'L');
    $this->SetX(4);
    $this->CellFitSpace(66, 3, "MESERO: ".utf8_decode($reg[0]['nombres']),0,1,'L');
    $this->SetX(4);
    $this->CellFitSpace(66, 3, "FECHA DE IMPRESIÓN: ".date("d-m-Y h:i:s A ",time()+1800),0,1,'L');

    } else {

    $this->SetFont('courier','',8);
    $this->SetX(4);
    $this->CellFitSpace(66, 3, "CAJERO: ".utf8_decode($reg[0]['nombres']),0,1,'L');
    $this->SetX(4);
    $this->CellFitSpace(66, 3, "FECHA DE IMPRESIÓN: ".date("d-m-Y h:i:s A ",time()+1800),0,1,'L');
  
    }

    $this->SetFont('courier','B',9);
    $this->SetX(2);
    $this->Cell(70,3,'------------- CLIENTE -------------',0,0,'C');
    $this->Ln(3);

    if($reg[0]['nomcliente']==""){

        $this->SetFont('courier','',9);
        $this->SetX(4);
        $this->CellFitSpace(66, 3, "CONSUMIDOR FINAL",0,1,'C');

    } else {

        $this->SetFont('courier','',9);
        $this->SetX(4);
        //$this->CellFitSpace(66, 3,utf8_decode($documento = ($reg[0]['documcliente'] == '0' ? "DOC.:" : $reg[0]['documento3'].": ").$reg[0]['dnicliente']),0,1,'C');
        $this->SetX(4);
        $this->CellFitSpace(66, 3,utf8_decode($reg[0]['nomcliente']),0,1,'C');
    }
    $this->SetFont('courier','',9);
    $this->SetX(2);
    $this->Cell(70,3,'-----------------------------------',0,1,'C');

    $this->SetX(4);
    $this->CellFitSpace(65, 3, "FECHA EMISIÓN: ".date("d/m/Y h:i:s A ",time()+1800),0,1,'C');

    $this->SetFont('courier','B',9);
    $this->SetX(2);
    $this->Cell(70,3,'------------ PRODUCTOS ------------',0,1,'C');
    $this->Ln(1);

    $this->SetX(4);
    $this->SetFont('courier','B',8);
    $this->SetTextColor(3, 3, 3); // Establece el color del texto (en este caso es Negro)
    $this->SetFillColor(229, 229, 229); // establece el color del fondo de la celda (en este caso es GRIS)
    $this->Cell(16,3,'CANT',0,0,'C');
    $this->Cell(50,3,'DESCRIPCIÓN DE PRODUCTO',0,1,'C');

    $this->SetFont('courier','B',9);
    $this->SetX(2);
    $this->Cell(70,3,'-----------------------------------',0,0,'C');
    $this->Ln(3);

    $cantidad = 0;
    $SubTotal = 0;
    $a=1;
    for($i=0;$i<sizeof($reg);$i++){
    $SubTotal += $reg[$i]['valorneto'];

    $this->SetX(4);
    $this->SetFillColor(192);
    $this->SetDrawColor(3,3,3);
    $this->SetLineWidth(.2);
    $this->SetFont('courier','',8);  
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es negro)
    $this->Cell(16,3,utf8_decode($reg[$i]['cantventa']),0,0,'C');
    $this->Cell(50,3,portales(utf8_decode(getSubString($reg[$i]["producto"], 30))),0,0,'C');
    $this->Ln();  
    }

    if($reg[0]['observacionespedido']!="0" && $reg[0]['observacionespedido']!=""){

    $this->Ln(2);
    $this->SetFont('courier','B',9);
    $this->SetX(2);
    $this->Cell(70,3,'---------- OBSERVACIONES ----------',0,0,'C');
    $this->Ln(3);

    $this->SetX(4);
    $this->SetFont('arial','B',7);
    $this->SetTextColor(3, 3, 3); // Establece el color del texto (en este caso es Negro)
    $this->SetFillColor(229, 229, 229); // establece el color del fondo de la celda (en este caso es GRIS)

    $this->SetFont('courier','',6);
    $this->SetX(4);
    $this->MultiCell(66,4,utf8_decode($reg[0]['observacionespedido']),0,1,'');
    
    }

    $this->Ln(3);
    $this->SetFont('courier','B',9);
    $this->SetX(2);
    $this->Cell(70,0.5,'-----------------------------------',0,1,'C');
    $this->SetX(2);
    $this->Cell(70,0.5,'-----------------------------------',0,1,'C');
    $this->Ln(3);

    $this->SetFont('courier','BI',9);
    $this->SetX(4);
    $this->SetFillColor(3, 3, 3);
    $this->CellFitSpace(66,3,"GRACIAS POR PREFERIRNOS",0,1,'C');
    $this->Ln(3);
}
########################## FUNCION TICKET COMANDA ##############################


########################## FUNCION TICKET PREGUENTA ##############################
      function TicketPrecuenta()
    { 
   
    $imp = new Login();
    $imp = $imp->ImpuestosPorId();
    $impuesto = $imp[0]['nomimpuesto']; 
   
    $con = new Login();
    $con = $con->ConfiguracionPorId();
    $simbolo = $con[0]['simbolo'];
    $moneda = $con[0]['moneda'];

    $tra = new Login();
    $reg = $tra->DetallesPedidoMesa();
  
     $this->SetFont('courier','B',12);
    $this->SetFillColor(2,157,116);
    $this->SetXY(4, 5);
    $this->Cell(65, 5, "TICKET DE PRECUENTA", 0, 0, 'C');
    $this->Ln(5);
  
    $this->SetFont('courier','B',11);
    $this->SetXY(4, 8.5);
    $this->CellFitSpace(65,6,utf8_decode($con[0]['nomsucursal']), 0, 1, 'C');
    $this->SetFont('courier','B',9);
    $this->SetXY(4, 13);
    //$this->MultiCell(65,3,$this->SetFont('Courier','B',8)."MATRIZ:".utf8_decode($provincia = ($con[0]['provincia'] == '' ? "*********" : $con[0]['provincia'])." ".$departamento = ($con[0]['departamento'] == '' ? "*********" : $reg[0]['departamento'])." ".$con[0]["direcsucursal"]),0,'C');
    $this->SetXY(4, 19);
    $this->CellFitSpace(65,3,"OBLIGADO A LLEVAR CONTABILIDAD:".utf8_decode($con[0]['llevacontabilidad']), 0, 1, 'C');
    $this->SetXY(4, 22);
    $this->CellFitSpace(65,3,'Nº DE '.$documento = ($con[0]['documsucursal'] == '0' ? "REG.:" : $con[0]['documento'].":").utf8_decode($con[0]['cuit']), 0, 1, 'C');
    $this->SetXY(4, 25);
    $this->CellFitSpace(65,3,"Nº PEDIDO:".utf8_decode($reg[0]['codpedido']."-".$reg[0]['pedido']), 0, 1, 'C');
    $this->SetXY(4, 28);
    $this->CellFitSpace(65,3,"AMBIENTE: PRODUCCIÓN", 0, 1, 'C');
    $this->SetXY(4, 31);
    $this->CellFitSpace(65,3,"EMISIÓN: NORMAL", 0, 1, 'C');
    $this->SetXY(4, 34);

    $this->SetFont('courier','B',9);
    $this->SetX(2);
    $this->Cell(70,3,'-----------------------------------',0,0,'C');
    $this->Ln(3);

    if($reg[0]['delivery']!="1"){

    $this->SetFont('courier','',8);
    $this->SetX(4);
    $this->CellFitSpace(66, 3, "SALA: ".utf8_decode($reg[0]['nomsala']),0,1,'L');
    $this->SetX(4);
    $this->CellFitSpace(66, 3, "N° DE MESA: ".utf8_decode($reg[0]['nommesa']),0,1,'L');
    $this->SetX(4);
    $this->CellFitSpace(66, 3, "MESERO: ".utf8_decode($reg[0]['nombres']),0,1,'L');
    $this->SetX(4);
    $this->CellFitSpace(66, 3, "FECHA DE EMISIÓN: ".date("d-m-Y h:i:s A ",time()+1800),0,1,'L');

    } else {

    $this->SetFont('courier','',8);
    $this->SetX(4);
    $this->CellFitSpace(66, 3, "CAJERO: ".utf8_decode($reg[0]['nombres']),0,1,'L');
    $this->SetX(4);
    $this->CellFitSpace(66, 3, "FECHA DE EMISIÓN: ".date("d-m-Y h:i:s A ",time()+1800),0,1,'L');
  
    }

    $this->SetFont('courier','B',9);
    $this->SetX(2);
    $this->Cell(70,3,'------------- CLIENTE -------------',0,0,'C');
    $this->Ln(3);

    if($reg[0]['nomcliente']==""){

        $this->SetFont('courier','',9);
        $this->SetX(4);
        $this->CellFitSpace(66, 3, "CONSUMIDOR FINAL",0,1,'C');

    } else {

        $this->SetFont('courier','',9);
        $this->SetX(4);
        $this->CellFitSpace(66, 3,utf8_decode($documento = ($reg[0]['documcliente'] == '0' ? "DOC.:" : $reg[0]['documento3'].": ").$reg[0]['dnicliente']),0,1,'C');
        $this->SetX(4);
        $this->CellFitSpace(66, 3,utf8_decode($reg[0]['nomcliente']),0,1,'C');
    }
    
    $this->SetFont('courier','B',9);
    $this->SetX(2);
    $this->Cell(70,3,'------------ PRODUCTOS ------------',0,1,'C');
    $this->Ln(1);

    $this->SetX(4);
    $this->SetFont('courier','B',8);
    $this->SetTextColor(3, 3, 3); // Establece el color del texto (en este caso es Negro)
    $this->SetFillColor(229, 229, 229); // establece el color del fondo de la celda (en este caso es GRIS)
    $this->Cell(6,3,'Nº',0,0,'C');
    $this->Cell(60,3,'DETALLES',0,1,'C');
    $this->SetFont('courier','B',9);
    $this->SetX(2);
    $this->Cell(70,3,'-----------------------------------',0,0,'C');
    $this->Ln(3);
 
     /* AQUI DECLARO LAS COLUMNAS */
    $this->SetWidths(array(6,60));

    $cantidad = 0;
    $SubTotal = 0;
    $a=1;
    for($i=0;$i<sizeof($reg);$i++){

    $this->SetX(4);
    $this->SetDrawColor(255,255,255);
    $this->SetLineWidth(.1);
    $detalles = str_replace("<br>","\n", $reg[$i]['detalles']);
    $this->SetFont('Courier','',7.8);  
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es negro)
    $this->Row(array(utf8_decode("#".$reg[$i]["pedido"]),utf8_decode($detalles)));
    $this->SetX(4);
    $this->SetFont('courier','B',8);
    $this->Cell(40,3,"SUBTOTAL: ",1,0,'R');
    $this->SetFont('courier','',8);
    $this->CellFitSpace(26,3,utf8_decode($simbolo.number_format($reg[$i]['suma'], 2, '.', ',')),0,0,'R');
    $this->Ln();
    $this->SetFont('courier','B',9);
    $this->SetX(2);
    $this->Cell(70,3,'-----------------------------------',0,0,'C');
    $this->Ln(3);
   }

    $this->SetFont('courier','B',8);
    $this->SetX(4);
    $this->CellFitSpace(40,3,"TOTAL GRAVADO (".$reg[0]["iva"]."%):",0,0,'R');
    $this->SetFont('courier','',7);
    $this->CellFitSpace(26,3,utf8_decode($simbolo.number_format($reg[0]["subtotalivasi"], 2, '.', ',')),0,1,'R');

    $this->SetX(4);
    $this->SetFont('courier','B',8);
    $this->CellFitSpace(40,3,"TOTAL EXENTO (0%):",0,0,'R');
    $this->SetFont('courier','',8);
    $this->CellFitSpace(26,3,utf8_decode($simbolo.number_format($reg[0]["subtotalivano"], 2, '.', ',')),0,1,'R');

    $this->SetX(4);
    $this->SetFont('courier','B',8);
    $this->CellFitSpace(40,3,$impuesto == '' ? "TOTAL IMP." : "TOTAL ".$imp[0]['nomimpuesto']." (".$reg[0]["iva"]."%):",0,0,'R');
    $this->SetFont('courier','',8);
    $this->CellFitSpace(26,3,utf8_decode($simbolo.number_format($reg[0]["totaliva"], 2, '.', ',')),0,1,'R');

    $this->SetX(4);
    $this->SetFont('courier','B',8);
    $this->CellFitSpace(40,3,"DESC. GLOBAL (".$reg[0]["descuento"]."%):",0,0,'R');
    $this->SetFont('courier','',8);
    $this->CellFitSpace(26,3,utf8_decode($simbolo.number_format($reg[0]["totaldescuento"], 2, '.', ',')),0,1,'R');

    $this->SetX(4);
    $this->SetFont('courier','B',8);
    $this->CellFitSpace(40,3,"IMPORTE TOTAL:",0,0,'R');
    $this->SetFont('courier','',8);
    $this->CellFitSpace(26,3,utf8_decode($simbolo.number_format($reg[0]["totalpago"], 2, '.', ',')),0,1,'R');
    $this->Ln(1);


    $this->SetFont('courier','B',9);
    $this->SetX(2);

    $this->Cell(70,0.5,'-----------------------------------',0,1,'C');
    $this->SetX(2);
    $this->Cell(70,0.5,'-----------------------------------',0,1,'C');
    $this->Ln(3);

    $this->SetFont('courier','BI',9);
    $this->SetX(4);
    $this->SetFillColor(3, 3, 3);
    $this->CellFitSpace(66,3,"GRACIAS POR PREFERIRNOS",0,1,'C');
    $this->Ln(3);
}
########################## FUNCION TICKET PRECUENTA ##############################



















########################## FUNCION TICKET VENTA ##############################
      function TicketVenta()
    {  
   
    $imp = new Login();
    $imp = $imp->ImpuestosPorId();
    $impuesto = $imp[0]['nomimpuesto'];

    $con = new Login();
    $con = $con->ConfiguracionPorId();
    $simbolo = $con[0]['simbolo'];
    $moneda = $con[0]['moneda'];

    $tra = new Login();
    $reg = $tra->VentasPorId();
  
     $this->SetFont('courier','B',12);
    $this->SetFillColor(2,157,116);
    $this->SetXY(4, 5);
    $this->Cell(65, 5, "TICKET DE VENTA", 0, 0, 'C');
    $this->Ln(5);
  
    $this->SetFont('courier','B',11);
    $this->SetXY(4, 8.5);
    $this->CellFitSpace(65,6,utf8_decode($con[0]['nomsucursal']), 0, 1, 'C');
    $this->SetFont('courier','B',9);
    $this->SetXY(4, 13);
    $this->MultiCell(65,3,$this->SetFont('Courier','B',8)."MATRIZ:".utf8_decode($provincia = ($con[0]['provincia'] == '' ? "*********" : $con[0]['provincia'])." ".$departamento = ($con[0]['departamento'] == '' ? "*********" : $reg[0]['departamento'])." ".$con[0]["direcsucursal"]),0,'C');
    $this->SetXY(4, 19);
    $this->CellFitSpace(65,3,"OBLIGADO A LLEVAR CONTABILIDAD:".utf8_decode($con[0]['llevacontabilidad']), 0, 1, 'C');
    $this->SetXY(4, 22);
    $this->CellFitSpace(65,3,'Nº DE '.$documento = ($con[0]['documsucursal'] == '0' ? "REG.:" : $con[0]['documento'].":").utf8_decode($con[0]['cuit']), 0, 1, 'C');
    $this->SetXY(4, 25);
    $this->CellFitSpace(65,3,"FACTURA:".utf8_decode($reg[0]['codventa']), 0, 1, 'C');
    $this->SetXY(4, 28);
    $this->CellFitSpace(65,3,"AMBIENTE: PRODUCCIÓN", 0, 1, 'C');
    $this->SetXY(4, 31);
    $this->CellFitSpace(65,3,"EMISIÓN: NORMAL", 0, 1, 'C');
    $this->SetXY(4, 34);
    $this->SetXY(4, 37);
   // $this->CellFitSpace(65,3,utf8_decode($reg[0]['codautorizacion']), 0, 1, 'C');
    $this->Ln(2);

    $this->SetFont('courier','B',9);
    $this->SetX(2);
    $this->Cell(70,3,'------------- CLIENTE -------------',0,0,'C');
    $this->Ln(3);

    if($reg[0]['nomcliente']==""){

        $this->SetFont('courier','',9);
        $this->SetX(4);
        $this->CellFitSpace(66, 3, "CONSUMIDOR FINAL",0,1,'C');

    } else {

        $this->SetFont('courier','',9);
        $this->SetX(4);
        $this->CellFitSpace(66, 3,utf8_decode($documento = ($reg[0]['documcliente'] == '0' ? "DOC.:" : $reg[0]['documento'].": ").$reg[0]['dnicliente']),0,1,'C');
        $this->SetX(4);
        $this->CellFitSpace(66, 3,utf8_decode($reg[0]['nomcliente']),0,1,'C');

    }
    $this->SetFont('courier','',9);
    $this->SetX(2);
    $this->Cell(70,3,'-----------------------------------',0,1,'C');

    $this->SetX(4);
    $this->CellFitSpace(65, 3, "FECHA EMISIÓN: ".date("d/m/Y h:i:s A ",time()+1800),0,1,'C');

    $this->SetFont('courier','B',9);
    $this->SetX(2);
    $this->Cell(70,3,'------------ PRODUCTOS ------------',0,1,'C');
    $this->Ln(1);

    $this->SetX(4);
    $this->SetFont('courier','B',8);
    $this->SetTextColor(3, 3, 3); // Establece el color del texto (en este caso es Negro)
    $this->SetFillColor(229, 229, 229); // establece el color del fondo de la celda (en este caso es GRIS)
    $this->Cell(12,3,'CANT',0,0,'L');
    $this->Cell(42,3,'DESCRIPCIÓN DE PRODUCTO',0,0,'C');
    $this->Cell(12,3,$impuesto == '' ? "IMP." : $imp[0]['nomimpuesto'],0,1,'C');

    $this->SetX(4);
    $this->Cell(22,3,'PVP.',0,0,'C');
    $this->Cell(22,3,'DCTO.',0,0,'C');
    $this->Cell(22,3,'TOTAL',0,1,'C');

    $this->SetFont('courier','B',9);
    $this->SetX(2);
    $this->Cell(70,3,'-----------------------------------',0,0,'C');
    $this->Ln(3);

    $tra = new Login();
    $detalle = $tra->VerDetallesVentas();
    $cantidad = 0;
    $SubTotal = 0;
    $a=1;
    for($i=0;$i<sizeof($detalle);$i++){
    $SubTotal += $detalle[$i]['valorneto'];

    $this->SetX(4);
    $this->SetFillColor(192);
    $this->SetDrawColor(3,3,3);
    $this->SetLineWidth(.2);
    $this->SetFont('courier','',8);  
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es negro)
    $this->CellFitSpace(12,3,utf8_decode($detalle[$i]['cantventa']),0,0,'C');
    $this->CellFitSpace(42,3,portales(utf8_decode(getSubString($detalle[$i]["producto"], 25))),0,0,'C');
    $this->CellFitSpace(12,3,utf8_decode($iva = ($detalle[$i]['ivaproducto'] == 'SI' ? $reg[0]["iva"]."%" : "(E)")),0,1,'C');
    $this->SetX(4);
    $this->CellFitSpace(24,3,utf8_decode($simbolo.$detalle[$i]["precioventa"]),0,0,'C');
    $this->CellFitSpace(18,3,utf8_decode($detalle[$i]["descproducto"]),0,0,'C');
    $this->CellFitSpace(24,3,utf8_decode($simbolo.$detalle[$i]["valorneto"]),0,0,'C');
    $this->Ln();  
    }

    $this->SetFont('courier','B',9);
    $this->SetX(2);
    $this->Cell(70,3,'-----------------------------------',0,0,'C');
    $this->Ln(3);

    $this->SetFont('courier','B',8);
    $this->SetX(4);
    $this->CellFitSpace(40,3,"SUBTOTAL:",0,0,'R');
    $this->SetFont('courier','',8);
    $this->CellFitSpace(26,3,utf8_decode($simbolo.number_format($SubTotal, 2, '.', ',')),0,1,'R');

    $this->SetFont('courier','B',8);
    $this->SetX(4);
    $this->CellFitSpace(40,3,"TOTAL GRAVADO (".$reg[0]["iva"]."%):",0,0,'R');
    $this->SetFont('courier','',7);
    $this->CellFitSpace(26,3,utf8_decode($simbolo.number_format($reg[0]["subtotalivasi"], 2, '.', ',')),0,1,'R');

    $this->SetX(4);
    $this->SetFont('courier','B',8);
    $this->CellFitSpace(40,3,"TOTAL EXENTO (0%):",0,0,'R');
    $this->SetFont('courier','',8);
    $this->CellFitSpace(26,3,utf8_decode($simbolo.number_format($reg[0]["subtotalivano"], 2, '.', ',')),0,1,'R');

    $this->SetX(4);
    $this->SetFont('courier','B',8);
    $this->CellFitSpace(40,3,$impuesto == '' ? "TOTAL IMP." : "TOTAL ".$imp[0]['nomimpuesto']." (".$reg[0]["iva"]."%):",0,0,'R');
    $this->SetFont('courier','',8);
    $this->CellFitSpace(26,3,utf8_decode($simbolo.number_format($reg[0]["totaliva"], 2, '.', ',')),0,1,'R');

    $this->SetX(4);
    $this->SetFont('courier','B',8);
    $this->CellFitSpace(40,3,"DESC. GLOBAL (".$reg[0]["descuento"]."%):",0,0,'R');
    $this->SetFont('courier','',8);
    $this->CellFitSpace(26,3,utf8_decode($simbolo.number_format($reg[0]["totaldescuento"], 2, '.', ',')),0,1,'R');

    $this->SetX(4);
    $this->SetFont('courier','B',8);
    $this->CellFitSpace(40,3,"IMPORTE TOTAL:",0,0,'R');
    $this->SetFont('courier','',8);
    $this->CellFitSpace(26,3,utf8_decode($simbolo.number_format($reg[0]["totalpago"], 2, '.', ',')),0,1,'R');
    $this->Ln(1);

    $this->SetFont('courier','B',9);
    $this->SetX(2);

    $this->Cell(70,3,'------ INFORMACIÓN ADICIONAL ------',0,1,'C');
    $this->Ln(1);

    $this->SetFont('courier','',9);
    $this->SetX(4);
    $this->CellFitSpace(66, 3,"CAJERO: ".utf8_decode($reg[0]['nombres']),0,1,'C');
    $this->Ln(3);

    $this->SetFont('courier','B',9);
    $this->SetX(2);
    $this->CellFitSpace(70,3,'FIRMA: ___________________________',0,1,'C');
    $this->Ln(4);

    $this->SetFont('courier','B',9);
    $this->SetX(2);
    $this->Cell(70,3,'-------------- PAGO ---------------',0,1,'C');
    $this->Ln(1);

    if($reg[0]['tipopago']=="CREDITO"){

    $this->SetFont('courier','B',9);
    $this->SetX(4);
    $this->SetFont('courier','',9);
    $this->CellFitSpace(66,3,utf8_decode($reg[0]["tipopago"]." - ".$variable = ( $reg[0]['tipopago'] == 'CONTADO' ? $reg[0]['mediopago'] : $reg[0]['formapago'])),0,1,'C');

    $this->SetX(4);
    $this->SetFont('courier','B',9);
    $this->CellFitSpace(40,3,"STATUS PAGO:",0,0,'R');
    $this->SetFont('courier','',9);
    if($reg[0]['fechavencecredito']== '0000-00-00') { 
        $this->CellFitSpace(26,3,utf8_decode($reg[0]["statusventa"]),0,1,'R');
    } elseif($reg[0]['fechavencecredito'] >= date("Y-m-d")) { 
        $this->CellFitSpace(26,3,utf8_decode($reg[0]["statusventa"]),0,1,'R');
    } elseif($reg[0]['fechavencecredito'] < date("Y-m-d")) { 
        $this->CellFitSpace(26,3,utf8_decode("VENCIDA"),0,1,'R');
    }  

    $this->SetX(4);
    $this->SetFont('courier','B',9);
    $this->CellFitSpace(40,3,"FECHA VENC:",0,0,'R');
    $this->SetFont('courier','',8);
    $this->CellFitSpace(26,3,utf8_decode(date("d-m-Y",strtotime($reg[0]["fechavencecredito"]))),0,1,'R');

    $this->SetX(4);
    $this->SetFont('courier','B',9);
    $this->CellFitSpace(40,3,"DIAS VENCIDOS:",0,0,'R');
    $this->SetFont('courier','',9);
    if($reg[0]['fechavencecredito']== '0000-00-00') { 
        $this->CellFitSpace(26,3,utf8_decode("0"),0,1,'R');
    } elseif($reg[0]['fechavencecredito'] >= date("Y-m-d")) { 
        $this->CellFitSpace(26,3,utf8_decode("0"),0,1,'R');
    } elseif($reg[0]['fechavencecredito'] < date("Y-m-d")) { 
        $this->CellFitSpace(26,3,utf8_decode(Dias_Transcurridos(date("Y-m-d"),$reg[0]['fechavencecredito'])),0,1,'R');
    }

    $this->SetX(4);
    $this->SetFont('courier','B',9);
    $this->CellFitSpace(40,3,"TOTAL ABONO:",0,0,'R');
    $this->SetFont('courier','',9);
    $this->CellFitSpace(26,3,utf8_decode($simbolo.number_format($reg[0]["abonototal"], 2, '.', ',')),0,1,'R');

    $this->SetX(4);
    $this->SetFont('courier','B',9);
    $this->CellFitSpace(40,3,"TOTAL DEBE:",0,0,'R');
    $this->SetFont('courier','',9);
    $this->CellFitSpace(26,3,utf8_decode($simbolo.number_format($reg[0]["totalpago"]-$reg[0]["abonototal"], 2, '.', ',')),0,1,'R');
    $this->Ln(1);


    } else {

    $this->SetFont('courier','B',9);
    $this->SetX(4);
    $this->SetFont('courier','',9);
    $this->CellFitSpace(66,3,utf8_decode($reg[0]["tipopago"]." - ".$reg[0]["mediopago"]),0,1,'C');

    $this->SetX(4);
    $this->SetFont('courier','B',9);
    $this->CellFitSpace(40,3,"EFECTIVO:",0,0,'R');
    $this->SetFont('courier','',9);
    $this->CellFitSpace(26,3,utf8_decode($simbolo.number_format($reg[0]["montopagado"], 2, '.', ',')),0,1,'R');

    $this->SetX(4);
    $this->SetFont('courier','B',9);
    $this->CellFitSpace(40,3,"PROPINA:",0,0,'R');
    $this->SetFont('courier','',9);
    $this->CellFitSpace(26,3,utf8_decode($simbolo.number_format($reg[0]["montopropina"], 2, '.', ',')),0,1,'R');

    $this->SetX(4);
    $this->SetFont('courier','B',9);
    $this->CellFitSpace(40,3,"CAMBIO:",0,0,'R');
    $this->SetFont('courier','',9);
    $this->CellFitSpace(26,3,utf8_decode($simbolo.number_format($reg[0]["montodevuelto"], 2, '.', ',')),0,1,'R');

if($reg[0]["montodevuelto"]=="0.00"){

    $this->SetX(4);
    $this->SetFont('courier','B',9);
    $this->CellFitSpace(40,3,"DEBE ABONAR:",0,0,'R');
    $this->SetFont('courier','',9);
$this->CellFitSpace(26,3,utf8_decode($simbolo.number_format($reg[0]["totalpago"]-$reg[0]["montopagado"], 2, '.', ',')),0,1,'R');
          }
    }

    $this->SetFont('courier','B',9);
    $this->SetX(2);
    $this->Cell(70,0.5,'-----------------------------------',0,1,'C');
    $this->SetX(2);
    $this->Cell(70,0.5,'-----------------------------------',0,1,'C');
    $this->Ln(3);

    $this->SetFont('courier','BI',9);
    $this->SetX(4);
    $this->SetFillColor(3, 3, 3);
    $this->CellFitSpace(66,3,"GRACIAS POR SU COMPRA",0,1,'C');
    $this->Ln(3);
}
########################## FUNCION TICKET VENTA ##############################

########################## FUNCION FACTURA VENTA #############################
      function FacturaVenta()
    {     
    $imp = new Login();
    $imp = $imp->ImpuestosPorId();
    $impuesto = $imp[0]['nomimpuesto'];

    $con = new Login();
    $con = $con->ConfiguracionPorId();
    $simbolo = $con[0]['simbolo'];
    $moneda = $con[0]['moneda'];
        
    $tra = new Login();
    $reg = $tra->VentasPorId();

    //Logo
   if (file_exists("./fotos/logo-principal.png")) {

        $logo = "./fotos/logo-principal.png";
        $this->Image($logo, 15, 11, 55, 15, "PNG");

    } else {

        $logo = "./assets/images/null.png";                         
        $this->Image($logo, 15, 10, 64, 20, "PNG");  
    } 

############################### BLOQUE N° 1 FACTURA #################################  
    $this->SetFillColor(192);
    $this->SetDrawColor(3,3,3);
    $this->SetLineWidth(.1);
    $this->RoundedRect(10, 10, 190, 17, '1.5', '');
    
    $this->SetFillColor(229);
    $this->SetDrawColor(3,3,3);
    $this->SetLineWidth(.1);
    $this->RoundedRect(98, 12, 12, 12, '1.5', 'F');

    $this->SetFillColor(229);
    $this->SetDrawColor(3,3,3);
    $this->SetLineWidth(.1);
    $this->RoundedRect(98, 12, 12, 12, '1.5', '');

    $this->SetFont('courier','B',16);
    $this->SetXY(101, 14);
    $this->Cell(20, 5, 'V', 0 , 0);
    $this->SetFont('courier','B',8);
    $this->SetXY(98, 19);
    $this->Cell(20, 5, 'Venta', 0, 0);
    
    $this->SetFont('courier','B',11);
    $this->SetXY(124, 10);
    $this->Cell(34, 5, 'N° DE VENTA ', 0, 0);
    $this->SetFont('courier','B',11);
    $this->SetXY(158, 10);
    $this->CellFitSpace(40, 5,utf8_decode($reg[0]['codventa']), 0, 0, "R");

    $this->SetFont('courier','B',9);
    $this->SetXY(124, 14);
    $this->Cell(34, 4, 'FECHA DE VENTA ', 0, 0);
    $this->SetFont('courier','',9);
    $this->SetXY(158, 14);
    $this->Cell(40, 4,utf8_decode(date("d-m-Y",strtotime($reg[0]['fechaventa']))), 0, 0, "R");

    $this->SetFont('courier','B',9);
    $this->SetXY(124, 18);
    $this->Cell(34, 4, 'FECHA DE EMISIÓN', 0, 0);
    $this->SetFont('courier','',9);
    $this->SetXY(158, 18);
    $this->Cell(40, 4,utf8_decode(date("d-m-Y h:i:s")), 0, 0, "R");
    
    $this->SetFont('courier','B',9);
    $this->SetXY(124, 22);
    $this->Cell(34, 4, 'ESTADO DE PAGO', 0, 0);
    $this->SetFont('courier','B',9);
    $this->SetXY(158, 22);
    
    if($reg[0]['fechavencecredito']== '0000-00-00') { 
    $this->Cell(40, 4,utf8_decode($reg[0]['statusventa']), 0, 0, "R");
    } elseif($reg[0]['fechavencecredito'] >= date("Y-m-d")) { 
    $this->Cell(40, 4,utf8_decode($reg[0]['statusventa']), 0, 0, "R");
    } elseif($reg[0]['fechavencecredito'] < date("Y-m-d")) { 
    $this->Cell(40, 4,utf8_decode("VENCIDA"), 0, 0, "R");
    }
############################## BLOQUE N° 1 FACTURA ###############################     
    
############################# BLOQUE N° 2 SUCURSAL ################################   
   //Bloque de datos de empresa
    $this->SetFillColor(192);
    $this->SetDrawColor(3,3,3);
    $this->SetLineWidth(.1);
    $this->RoundedRect(10, 29, 190, 18, '1.5', '');
    //DATOS DE SUCURSAL LINEA 1
    $this->SetFont('courier','B',9);
    $this->SetXY(12, 30);
    $this->Cell(186, 4, 'DATOS DE SUCURSAL ', 0, 0);
    //DATOS DE SUCURSAL LINEA 1

    //DATOS DE SUCURSAL LINEA 2
    $this->SetFont('courier','B',8);
    $this->SetXY(12, 34);
    $this->Cell(24, 4, 'RAZÓN SOCIAL:', 0, 0);
    $this->SetFont('courier','',8);
    $this->SetXY(36, 34);
    $this->CellFitSpace(66, 4,utf8_decode($con[0]['nomsucursal']), 0, 0);

    $this->SetFont('courier','B',8);
    $this->SetXY(102, 34);
    $this->CellFitSpace(22, 4, 'Nº DE '.$documento = ($con[0]['documsucursal'] == '0' ? "REG.:" : $con[0]['documento'].":"), 0, 0);
    $this->SetFont('courier','',8);
    $this->SetXY(124, 34);
    $this->CellFitSpace(28, 4,utf8_decode($con[0]['cuit']), 0, 0);

    $this->SetFont('courier','B',8);
    $this->SetXY(152, 34);
    $this->Cell(18, 4, 'N° DE TLF:', 0, 0);
    $this->SetFont('courier','',8);
    $this->SetXY(170, 34);
    $this->Cell(28, 4,utf8_decode($con[0]['tlfsucursal']), 0, 0);
    //DATOS DE SUCURSAL LINEA 2

    //DATOS DE SUCURSAL LINEA 3
    $this->SetFont('courier','B',8);
    $this->SetXY(12, 38);
    $this->Cell(24, 4, 'DIRECCIÓN:', 0, 0);
    $this->SetFont('courier','',8);
    $this->SetXY(36, 38);
    $this->CellFitSpace(96, 4,utf8_decode($provincia = ($con[0]['provincia'] == '' ? "*********" : $con[0]['provincia'])." ".$departamento = ($con[0]['departamento'] == '' ? "*********" : $con[0]['departamento'])." ".$con[0]['direcsucursal']), 0, 0);

    $this->SetFont('courier','B',8);
    $this->SetXY(132, 38);
    $this->Cell(12, 4, 'EMAIL:', 0, 0);
    $this->SetFont('courier','',8);
    $this->SetXY(144, 38);
    $this->Cell(54, 4,utf8_decode($con[0]['correosucursal']), 0, 0);
    //DATOS DE SUCURSAL LINEA 3

    //DATOS DE SUCURSAL LINEA 4
    $this->SetFont('courier','B',8);
    $this->SetXY(12, 42);
    $this->Cell(24, 4, 'RESPONSABLE:', 0, 0);
    $this->SetFont('courier','',8);
    $this->SetXY(36, 42);
    $this->CellFitSpace(66, 4,utf8_decode($con[0]['nomencargado']), 0, 0);

    $this->SetFont('courier','B',8);
    $this->SetXY(102, 42);
    $this->CellFitSpace(22, 4, 'Nº DE '.$documento = ($con[0]['documencargado'] == '0' ? "DOC.:" : $con[0]['documento2'].":"), 0, 0);
    $this->SetFont('courier','',8);
    $this->SetXY(124, 42);
    $this->CellFitSpace(28, 4,utf8_decode($con[0]['dniencargado']), 0, 0);

    $this->SetFont('courier','B',8);
    $this->SetXY(152, 42);
    $this->Cell(18, 4, 'N° DE TLF:', 0, 0);
    $this->SetFont('courier','',8);
    $this->SetXY(170, 42);
    $this->Cell(28, 4,utf8_decode($tlf = ($con[0]['tlfencargado'] == '' ? "*********" : $con[0]['tlfencargado'])), 0, 0);
    //DATOS DE SUCURSAL LINEA 4
############################# BLOQUE N° 2 SUCURSAL ################################   


############################## BLOQUE N° 3 CLIENTE ###################################  
    $this->SetFillColor(192);
    $this->SetDrawColor(3,3,3);
    $this->SetLineWidth(.1);
    $this->RoundedRect(10, 49, 190, 14, '1.5', '');

    $this->SetFont('courier','B',9);
    $this->SetXY(12, 50);
    $this->Cell(186, 4, 'DATOS DE CLIENTE ', 0, 0);

    $this->SetFont('courier','B',8);
    $this->SetXY(12, 54);
    $this->Cell(20, 4, 'NOMBRES:', 0, 0);
    $this->SetFont('courier','',8);
    $this->SetXY(32, 54);
    $this->CellFitSpace(58, 4,utf8_decode($nombre = ($reg[0]['nomcliente'] == '' ? "CONSUMIDOR FINAL" : $reg[0]['nomcliente'])), 0, 0);

    $this->SetFont('courier','B',8);
    $this->SetXY(90, 54);
    $this->CellFitSpace(20, 4, 'Nº DE '.$documento = ($reg[0]['documcliente'] == '0' ? "DOC.:" : $reg[0]['documento'].":"), 0, 0);
    $this->SetFont('courier','',8);
    $this->SetXY(110, 54);
    $this->CellFitSpace(24, 4,utf8_decode($nombre = ($reg[0]['dnicliente'] == '' ? "*********" : $reg[0]['dnicliente'])), 0, 0);

    $this->SetFont('courier','B',8);
    $this->SetXY(134, 54);
    $this->Cell(12, 4, 'EMAIL:', 0, 0);
    $this->SetFont('courier','',8);
    $this->SetXY(146, 54);
    $this->CellFitSpace(52, 4,utf8_decode($email = ($reg[0]['emailcliente'] == '' ? "*********" : $reg[0]['emailcliente'])), 0, 0);

    $this->SetFont('courier','B',8);
    $this->SetXY(12, 58);
    $this->Cell(20, 4, 'DIRECCIÓN:', 0, 0);
    $this->SetFont('courier','',8);
    $this->SetXY(32, 58);
    $this->CellFitSpace(124, 4,getSubString(utf8_decode($provincia = ($reg[0]['provincia'] == '' ? "*********" : $reg[0]['provincia'])." ".$departamento = ($reg[0]['departamento'] == '' ? "*********" : $reg[0]['departamento'])." ".$reg[0]['direccliente']), 70), 0, 0);

    $this->SetFont('courier','B',8);
    $this->SetXY(156, 58);
    $this->Cell(20, 4, 'N° DE TLF:', 0, 0);
    $this->SetFont('courier','',8);
    $this->SetXY(176, 58);
    $this->CellFitSpace(22, 4,utf8_decode($tlf = ($reg[0]['tlfcliente'] == '' ? "*********" : $reg[0]['tlfcliente'])), 0, 0); 
############################### BLOQUE N° 3 CLIENTE #################################  

################################# BLOQUE N° 4 #######################################   
    $this->SetFillColor(192);
    $this->SetDrawColor(3,3,3);
    $this->SetLineWidth(.1);
    $this->RoundedRect(10, 68, 190, 180, '0', '');

    $this->SetFont('courier','B',9);
    $this->SetXY(10, 65);
    $this->SetTextColor(3,3,3);
    $this->SetFillColor(229, 229, 229); // establece el color del fondo de la celda (en este caso es GRIS)
    $this->Cell(8, 8,"Nº", 1, 0, 'C', True);
    $this->Cell(45, 8,"DESCRIPCIÓN DE PRODUCTO", 1, 0, 'C', True);
    $this->Cell(25, 8,"CATEGORIA", 1, 0, 'C', True);
    $this->Cell(10, 8,"CANT", 1, 0, 'C', True);
    $this->Cell(25, 8,"P/UNIT", 1, 0, 'C', True);
    $this->Cell(25, 8,"V/TOTAL", 1, 0, 'C', True);
    $this->Cell(15, 8,"DESC %", 1, 0, 'C', True);
    $this->Cell(12, 8,$impuesto == '' ? "Impuesto" : $imp[0]['nomimpuesto'], 1, 0, 'C', True);
    $this->Cell(25, 8,"V/NETO", 1, 1, 'C', True);
################################# BLOQUE N° 4 #######################################  

################################# BLOQUE N° 5 ####################################### 
    $tra = new Login();
    $detalle = $tra->VerDetallesVentas();
    $cantidad = 0;
    $SubTotal = 0;

     /* AQUI DECLARO LAS COLUMNAS */
    $this->SetWidths(array(8,45,25,10,25,25,15,12,25));

    /* AQUI AGREGO LOS VALORES A MOSTRAR EN COLUMNAS */
    $a=1;
    for($i=0;$i<sizeof($detalle);$i++){ 
    $cantidad += $detalle[$i]['cantventa'];
    $valortotal = $detalle[$i]["precioventa"]*$detalle[$i]["cantventa"];
    $SubTotal += $detalle[$i]['valorneto'];

    $this->SetX(10);
    $this->SetFont('Courier','',8);  
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es negro)
    $this->RowFacture(array($a++,
        portales(utf8_decode($detalle[$i]["producto"])),
        utf8_decode($detalle[$i]["nomcategoria"]),
        utf8_decode($detalle[$i]["cantventa"]),
        utf8_decode($simbolo.number_format($detalle[$i]['precioventa'], 2, '.', ',')),
        utf8_decode($simbolo.number_format($detalle[$i]['valortotal'], 2, '.', ',')),
        utf8_decode(number_format($detalle[$i]['descproducto'], 2, '.', ',')),
        utf8_decode($detalle[$i]["ivaproducto"] == 'SI' ? $reg[0]["iva"]."%" : "(E)"),
        utf8_decode($simbolo.number_format($detalle[$i]['valorneto'], 2, '.', ','))));
       }
################################# BLOQUE N° 5 #######################################  

if($reg[0]['observaciones']!="" && $reg[0]['observaciones']!="0"){
########################### BLOQUE N° 6 #############################    
    $this->SetFont('courier','B',7);
    $this->SetXY(10, 240);
    $this->MultiCell(190,3,"OBSERVACIONES: ".utf8_decode($reg[0]['observaciones'] == '' ? "" : $reg[0]['observaciones']),0,1,'');
    $this->Ln(4);
########################### BLOQUE N° 6 #############################    
}

########################### BLOQUE N° 7 #############################    
    $this->SetFillColor(192);
    $this->SetDrawColor(3,3,3);
    $this->SetLineWidth(.1);
    $this->RoundedRect(10, 250, 108, 26, '1.5', '');

    $this->SetFont('courier','B',8);
    $this->SetXY(12, 250);
    $this->Cell(105, 4, 'INFORMACIÓN ADICIONAL', 0, 0, 'C');
    
    $this->SetFont('courier','B',7.5);
    $this->SetXY(12, 254);
    $this->Cell(32, 3, 'CANTIDAD PRODUCTOS:', 0, 0);
    $this->SetXY(44, 254);
    $this->SetFont('courier','',7.5);
    $this->CellFitSpace(15, 3,utf8_decode($cantidad), 0, 0);
    
    $this->SetFont('courier','B',7.5);
    $this->SetXY(59, 254);
    $this->Cell(28, 3, 'TIPO DOCUMENTO:', 0, 0);
    $this->SetXY(87, 254);
    $this->SetFont('courier','',7.5);
    $this->Cell(30, 3,"FACTURA", 0, 0);

    $this->SetFont('courier','B',7.5);
    $this->SetXY(12, 257);
    $this->Cell(32, 3, 'NOMBRE DE CAJERO:', 0, 0);
    $this->SetXY(44, 257);
    $this->SetFont('courier','',7.5);
    $this->CellFitSpace(73, 3,utf8_decode($reg[0]['nombres']), 0, 0);
    
    $this->SetFont('courier','B',7.5);
    $this->SetXY(12, 260);
    $this->Cell(32, 3, 'TIPO DE PAGO:', 0, 0);
    $this->SetXY(44, 260);
    $this->SetFont('courier','',7.5);
    $this->CellFitSpace(18, 3,utf8_decode($reg[0]['tipopago']), 0, 0);
    
if($reg[0]['tipopago']=="CREDITO"){

   //Linea de membrete Nro 5
    $this->SetFont('courier','B',7.5);
    $this->SetXY(62, 260);
    $this->Cell(16, 3, 'F. VENC:', 0, 0);
    $this->SetXY(78, 260);
    $this->SetFont('courier','',7.5);
    $this->CellFitSpace(20, 3,utf8_decode($vence = ( $reg[0]['fechavencecredito'] == '0000-00-00' ? "0" : date("d-m-Y",strtotime($reg[0]['fechavencecredito'])))), 0, 0);
    
    //Linea de membrete Nro 6
    $this->SetFont('courier','B',7.5);
    $this->SetXY(98, 260);
    $this->Cell(10, 3, 'DIAS:', 0, 0);
    $this->SetXY(108, 260);
    $this->SetFont('courier','',7.5);
    
    if($reg[0]['fechavencecredito']== '0000-00-00') { 
    $this->CellFitSpace(9, 3,utf8_decode("0"), 0, 0);
    } elseif($reg[0]['fechavencecredito'] >= date("Y-m-d")) { 
    $this->CellFitSpace(9, 3,utf8_decode("0"), 0, 0);
    } elseif($reg[0]['fechavencecredito'] < date("Y-m-d")) { 
    $this->CellFitSpace(9, 3,utf8_decode(Dias_Transcurridos(date("Y-m-d"),$reg[0]['fechavencecredito'])), 0, 0);
    }
}
    
    $this->SetFont('courier','B',7.5);
    $this->SetXY(12, 263);
    $this->Cell(32, 3, 'MEDIO DE PAGO:', 0, 0);
    $this->SetXY(44, 263);
    $this->SetFont('courier','',7.5);
    $this->CellFitSpace(38, 3,utf8_decode($variable = ($reg[0]['tipopago'] == 'CONTADO' ? $reg[0]['mediopago']."(".$simbolo.$reg[0]["montopagado"].")" : $reg[0]['formapago'])), 0, 0);

if($reg[0]["montodevuelto"]=="0.00"){

    $this->SetFont('courier','B',7.5);
    $this->SetXY(82, 263);
    $this->Cell(18, 3, 'D. ABONAR:', 0, 0);
    $this->SetXY(100, 263);
    $this->SetFont('courier','',7.5);
$this->CellFitSpace(17, 3,utf8_decode($simbolo.number_format($reg[0]["totalpago"]-$reg[0]["montopagado"], 2, '.', ',')), 0, 0);

} else {

    $this->SetFont('courier','B',7.5);
    $this->SetXY(82, 263);
    $this->Cell(18, 3, 'CAMBIO:', 0, 0);
    $this->SetXY(100, 263);
    $this->SetFont('courier','',7.5);
    $this->CellFitSpace(17, 3,utf8_decode($simbolo.$reg[0]['montodevuelto']), 0, 0);
}

    $this->SetFont('courier','B',7.5);
    $this->SetXY(12, 267);
    $this->MultiCell(105,3,$this->SetFont('Courier','',7).utf8_decode(numtoletras($reg[0]['totalpago'])),0,'L');
########################### BLOQUE N° 7 #############################  

################################# BLOQUE N° 8 #######################################  
    //Bloque de Totales de factura
    $this->SetFillColor(192);
    $this->SetDrawColor(3,3,3);
    $this->SetLineWidth(.1);
    $this->RoundedRect(120, 250, 80, 26, '1.5', '');

    //Linea de membrete Nro 1
    $this->SetFont('courier','B',9);
    $this->SetXY(122, 250);
    $this->CellFitSpace(36, 5, 'SUBTOTAL:', 0, 0);
    $this->SetXY(158, 250);
    $this->SetFont('courier','',9);
    $this->CellFitSpace(42, 5,utf8_decode($simbolo.number_format($SubTotal, 2, '.', ',')), 0, 0, "R");

    //Linea de membrete Nro 2
    $this->SetFont('courier','B',9);
    $this->SetXY(122, 254);
    $this->CellFitSpace(36, 5, 'TOTAL GRAVADO ('.$reg[0]["iva"].'%):', 0, 0);
    $this->SetXY(158, 254);
    $this->SetFont('courier','',9);
    $this->CellFitSpace(42, 5,utf8_decode($simbolo.number_format($reg[0]["subtotalivasi"], 2, '.', ',')), 0, 0, "R");

    //Linea de membrete Nro 3
    $this->SetFont('courier','B',9);
    $this->SetXY(122, 258);
    $this->CellFitSpace(36, 5, 'TOTAL EXENTO (0%):', 0, 0);
    $this->SetXY(158, 258);
    $this->SetFont('courier','',9);
    $this->CellFitSpace(42, 5,utf8_decode($simbolo.number_format($reg[0]["subtotalivano"], 2, '.', ',')), 0, 0, "R");

    //Linea de membrete Nro 4
    $this->SetFont('courier','B',9);
    $this->SetXY(122, 262);
    $this->CellFitSpace(36, 5,$impuesto == '' ? "TOTAL IMP." : "TOTAL ".$imp[0]['nomimpuesto']." (".$reg[0]["iva"]."%):", 0, 0);
    $this->SetXY(158, 262);
    $this->SetFont('courier','',9);
    $this->CellFitSpace(42, 5,utf8_decode($simbolo.number_format($reg[0]["totaliva"], 2, '.', ',')), 0, 0, "R");

    //Linea de membrete Nro 5
    $this->SetFont('courier','B',9);
    $this->SetXY(122, 266);
    $this->CellFitSpace(36, 5, "DESC. GLOBAL (".$reg[0]["descuento"].'%):', 0, 0);
    $this->SetXY(158, 266);
    $this->SetFont('courier','',9);
    $this->CellFitSpace(42, 5,utf8_decode($simbolo.number_format($reg[0]["totaldescuento"], 2, '.', ',')), 0, 0, "R");

    //Linea de membrete Nro 6
    $this->SetFont('courier','B',9);
    $this->SetXY(122, 270);
    $this->CellFitSpace(36, 5, 'IMPORTE TOTAL:', 0, 0);
    $this->SetXY(158, 270);
    $this->SetFont('courier','',9);
    $this->CellFitSpace(42, 5,utf8_decode($simbolo.number_format($reg[0]["totalpago"], 2, '.', ',')), 0, 0, "R");
################################# BLOQUE N° 8 #######################################
}
########################## FUNCION FACTURA VENTA ##############################










########################## FUNCION LISTAR VENTAS ##############################
      function TablaListarVentas()
   {

    $con = new Login();
    $con = $con->ConfiguracionPorId();
    $simbolo = $con[0]['simbolo'];
    
    $tra = new Login();
    $reg = $tra->ListarVentas();

    $logo = ( file_exists("./fotos/logo-pdf.png") == "" ? "./assets/images/null.png" : "./fotos/logo-pdf.png");
    $logo2 = ( file_exists("./fotos/logo-principal.png") == "" ? "./assets/images/null.png" : "./fotos/logo-principal.png");
    
    $con = new Login();
    $con = $con->ConfiguracionPorId(); 

    $this->Ln(2);
    $this->SetFont('Courier','B',12);
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es blanco)
    $this->SetFillColor(5, 130, 275); // establece el color del fondo de la celda (en este caso es NARANJA
    $this->Cell(45,5,$this->Image($logo, $this->GetX()+80, $this->GetY()+4, 24),0,0,'C');
    $this->Cell(240,5,"GESTIÓN Y CONTROL PARA RESTAURANTES",0,0,'C');
    $this->Cell(45,5,$this->Image($logo2, $this->GetX()-62, $this->GetY()+4, 58),0,0,'C');

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(240,5,utf8_decode($con[0]['nomsucursal']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(240,5,utf8_decode($con[0]['documsucursal'] == '0' ? "" : $con[0]['documento'])." ".utf8_decode($con[0]['cuit']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    if($con[0]['id_provincia']!='0'){

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(240,5,utf8_decode($provincia = ($con[0]['id_provincia'] == '0' ? "" : $con[0]['provincia'])." ".$departamento = ($con[0]['id_departamento'] == '0' ? "" : $con[0]['departamento'])),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    }

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(240,5,utf8_decode($con[0]['direcsucursal']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(240,5,"Nº DE TLF: ".utf8_decode($con[0]['tlfsucursal']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(240,5,utf8_decode($con[0]['correosucursal']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');
    $this->Ln(8);
    
    $this->SetFont('Courier','B',14);  
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es negro)
    $this->Cell(330,10,'LISTADO GENERAL DE VENTAS',0,0,'C');

    $this->Ln();
    $this->SetFont('courier','B',10);
    $this->SetTextColor(255,255,255);  // Establece el color del texto (en este caso es BLANCO)
    $this->SetFillColor(230, 126, 34); // establece el color del fondo de la celda (en este caso es NARANJA)
    $this->Cell(15,8,'Nº',1,0,'C', True);
    $this->Cell(35,8,'Nº DE VENTA',1,0,'C', True);
    $this->Cell(70,8,'DESCRIPCIÓN DE CLIENTE',1,0,'C', True);
    $this->Cell(20,8,'Nº ARTIC',1,0,'C', True);
    $this->Cell(33,8,'TOTAL GRAVADO',1,0,'C', True);
    $this->Cell(33,8,'TOTAL EXENTO',1,0,'C', True);
    $this->Cell(34,8,'IMPORTE TOTAL',1,0,'C', True);
    $this->Cell(25,8,'STATUS',1,0,'C', True);
    $this->Cell(20,8,'PAGO',1,0,'C', True);
    $this->Cell(45,8,'FECHA EMISIÓN',1,1,'C', True);

    if($reg==""){
    echo "";      
    } else {
 
     /* AQUI DECLARO LAS COLUMNAS */
    $this->SetWidths(array(15,35,70,20,33,33,34,25,20,45));

    /* AQUI AGREGO LOS VALORES A MOSTRAR EN COLUMNAS */
    $a=1;
    $TotalArticulos=0;
    $TotalGravado=0;
    $TotalExento=0;
    $TotalImporte=0;

    for($i=0;$i<sizeof($reg);$i++){ 
    
    $TotalArticulos+=$reg[$i]['articulos'];
    $TotalGravado+=$reg[$i]['subtotalivasi'];
    $TotalExento+=$reg[$i]['subtotalivano'];
    $TotalImporte+=$reg[$i]['totalpago'];

    $this->SetFont('Courier','',10);  
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es negro)
    $this->Row(array($a++,utf8_decode($reg[$i]["codventa"]),utf8_decode($reg[$i]['codcliente'] == '0' ? "CONSUMIDOR FINAL" : $reg[$i]['nomcliente']),utf8_decode($reg[$i]["articulos"]),utf8_decode($simbolo.number_format($reg[$i]['subtotalivasi'], 2, '.', ',')),utf8_decode($simbolo.number_format($reg[$i]['subtotalivano'], 2, '.', ',')),utf8_decode($simbolo.number_format($reg[$i]['totalpago'], 2, '.', ',')),utf8_decode($reg[$i]["statusventa"]),utf8_decode($reg[$i]["tipopago"]),utf8_decode(date("d-m-Y h:i:s",strtotime($reg[$i]['fechaventa'])))));
        }
   
    $this->Cell(120,5,'',0,0,'C');
    $this->SetFont('courier','B',10);
    $this->SetTextColor(255,255,255);  // Establece el color del texto (en este caso es blanco)
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es blanco)
    $this->CellFitSpace(20,5,utf8_decode(number_format($TotalArticulos, 2, '.', ',')),1,0,'L');
    $this->CellFitSpace(33,5,utf8_decode($simbolo.number_format($TotalGravado, 2, '.', ',')),1,0,'L');
    $this->CellFitSpace(33,5,utf8_decode($simbolo.number_format($TotalExento, 2, '.', ',')),1,0,'L');
    $this->CellFitSpace(34,5,utf8_decode($simbolo.number_format($TotalImporte, 2, '.', ',')),1,0,'L');
    $this->Ln();
      }

    $this->Ln(12); 
    $this->SetFont('courier','B',10);
    $this->Cell(5,6,'',0,0,'');
    $this->Cell(200,6,'ELABORADO: '.utf8_decode($_SESSION["nombres"]),0,0,'');
    $this->Cell(125,6,'RECIBIDO:_____________________________________',0,0,'');
    $this->Ln();
    $this->Cell(5,6,'',0,0,'');
    $this->Cell(200,6,'FECHA/HORA: '.date('d-m-Y h:i:s A'),0,0,'');
    $this->Cell(125,6,'',0,0,'');
    $this->Ln(4);
     }
########################## FUNCION LISTAR VENTAS ##############################

########################## FUNCION LISTAR VENTAS DIARIAS ##############################
      function TablaListarVentasDiarias()
   {

    $con = new Login();
    $con = $con->ConfiguracionPorId();
    $simbolo = $con[0]['simbolo'];
    
    $tra = new Login();
    $reg = $tra->BuscarVentasDiarias();

    $logo = ( file_exists("./fotos/logo-pdf.png") == "" ? "./assets/images/null.png" : "./fotos/logo-pdf.png");
    $logo2 = ( file_exists("./fotos/logo-principal.png") == "" ? "./assets/images/null.png" : "./fotos/logo-principal.png");
    
    $con = new Login();
    $con = $con->ConfiguracionPorId(); 

    $this->Ln(2);
    $this->SetFont('Courier','B',12);
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es blanco)
    $this->SetFillColor(5, 130, 275); // establece el color del fondo de la celda (en este caso es NARANJA
    $this->Cell(45,5,$this->Image($logo, $this->GetX()+80, $this->GetY()+4, 24),0,0,'C');
    $this->Cell(240,5,"GESTIÓN Y CONTROL PARA RESTAURANTES",0,0,'C');
    $this->Cell(45,5,$this->Image($logo2, $this->GetX()-62, $this->GetY()+4, 58),0,0,'C');

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(240,5,utf8_decode($con[0]['nomsucursal']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(240,5,utf8_decode($con[0]['documsucursal'] == '0' ? "" : $con[0]['documento'])." ".utf8_decode($con[0]['cuit']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    if($con[0]['id_provincia']!='0'){

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(240,5,utf8_decode($provincia = ($con[0]['id_provincia'] == '0' ? "" : $con[0]['provincia'])." ".$departamento = ($con[0]['id_departamento'] == '0' ? "" : $con[0]['departamento'])),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    }

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(240,5,utf8_decode($con[0]['direcsucursal']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(240,5,"Nº DE TLF: ".utf8_decode($con[0]['tlfsucursal']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(240,5,utf8_decode($con[0]['correosucursal']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');
    $this->Ln(8);
    
    $this->SetFont('Courier','B',14);  
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es negro)
    $this->Cell(330,10,'LISTADO GENERAL DE VENTAS DIARIAS DEL (DIA '.date("d-m-Y").")",0,0,'C');

    $this->Ln();
    $this->SetFont('courier','B',10);
    $this->SetTextColor(255,255,255);  // Establece el color del texto (en este caso es BLANCO)
    $this->SetFillColor(230, 126, 34); // establece el color del fondo de la celda (en este caso es NARANJA)
    $this->Cell(15,8,'Nº',1,0,'C', True);
    $this->Cell(35,8,'Nº DE VENTA',1,0,'C', True);
    $this->Cell(70,8,'DESCRIPCIÓN DE CLIENTE',1,0,'C', True);
    $this->Cell(20,8,'Nº ARTIC',1,0,'C', True);
    $this->Cell(33,8,'TOTAL GRAVADO',1,0,'C', True);
    $this->Cell(33,8,'TOTAL EXENTO',1,0,'C', True);
    $this->Cell(34,8,'IMPORTE TOTAL',1,0,'C', True);
    $this->Cell(25,8,'STATUS',1,0,'C', True);
    $this->Cell(20,8,'PAGO',1,0,'C', True);
    $this->Cell(45,8,'FECHA EMISIÓN',1,1,'C', True);

    if($reg==""){
    echo "";      
    } else {
 
     /* AQUI DECLARO LAS COLUMNAS */
    $this->SetWidths(array(15,35,70,20,33,33,34,25,20,45));

    /* AQUI AGREGO LOS VALORES A MOSTRAR EN COLUMNAS */
    $a=1;
    $TotalArticulos=0;
    $TotalGravado=0;
    $TotalExento=0;
    $TotalImporte=0;

    for($i=0;$i<sizeof($reg);$i++){ 
    
    $TotalArticulos+=$reg[$i]['articulos'];
    $TotalGravado+=$reg[$i]['subtotalivasi'];
    $TotalExento+=$reg[$i]['subtotalivano'];
    $TotalImporte+=$reg[$i]['totalpago'];

    $this->SetFont('Courier','',10);  
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es negro)
    $this->Row(array($a++,utf8_decode($reg[$i]["codventa"]),utf8_decode($reg[$i]['codcliente'] == '0' ? "CONSUMIDOR FINAL" : $reg[$i]['nomcliente']),utf8_decode($reg[$i]["articulos"]),utf8_decode($simbolo.number_format($reg[$i]['subtotalivasi'], 2, '.', ',')),utf8_decode($simbolo.number_format($reg[$i]['subtotalivano'], 2, '.', ',')),utf8_decode($simbolo.number_format($reg[$i]['totalpago'], 2, '.', ',')),utf8_decode($reg[$i]["statusventa"]),utf8_decode($reg[$i]["tipopago"]),utf8_decode(date("d-m-Y h:i:s",strtotime($reg[$i]['fechaventa'])))));
        }
   
    $this->Cell(120,5,'',0,0,'C');
    $this->SetFont('courier','B',10);
    $this->SetTextColor(255,255,255);  // Establece el color del texto (en este caso es blanco)
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es blanco)
    $this->CellFitSpace(20,5,utf8_decode(number_format($TotalArticulos, 2, '.', ',')),1,0,'L');
    $this->CellFitSpace(33,5,utf8_decode($simbolo.number_format($TotalGravado, 2, '.', ',')),1,0,'L');
    $this->CellFitSpace(33,5,utf8_decode($simbolo.number_format($TotalExento, 2, '.', ',')),1,0,'L');
    $this->CellFitSpace(34,5,utf8_decode($simbolo.number_format($TotalImporte, 2, '.', ',')),1,0,'L');
    $this->Ln();
      }


    $this->Ln(12); 
    $this->SetFont('courier','B',10);
    $this->Cell(5,6,'',0,0,'');
    $this->Cell(200,6,'ELABORADO: '.utf8_decode($_SESSION["nombres"]),0,0,'');
    $this->Cell(125,6,'RECIBIDO:_____________________________________',0,0,'');
    $this->Ln();
    $this->Cell(5,6,'',0,0,'');
    $this->Cell(200,6,'FECHA/HORA: '.date('d-m-Y h:i:s A'),0,0,'');
    $this->Cell(125,6,'',0,0,'');
    $this->Ln(4);
}
########################## FUNCION LISTAR VENTAS DIARIAS ##############################

########################## FUNCION LISTAR VENTAS POR CAJAS ##############################
      function TablaListarVentasxCajas()
   {

    $con = new Login();
    $con = $con->ConfiguracionPorId();
    $simbolo = $con[0]['simbolo'];
    
    $tra = new Login();
    $reg = $tra->BuscarVentasxCajas();

    $logo = ( file_exists("./fotos/logo-pdf.png") == "" ? "./assets/images/null.png" : "./fotos/logo-pdf.png");
    $logo2 = ( file_exists("./fotos/logo-principal.png") == "" ? "./assets/images/null.png" : "./fotos/logo-principal.png");
    
    $con = new Login();
    $con = $con->ConfiguracionPorId(); 

    $this->Ln(2);
    $this->SetFont('Courier','B',12);
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es blanco)
    $this->SetFillColor(5, 130, 275); // establece el color del fondo de la celda (en este caso es NARANJA
    $this->Cell(45,5,$this->Image($logo, $this->GetX()+80, $this->GetY()+4, 24),0,0,'C');
    $this->Cell(240,5,"GESTIÓN Y CONTROL PARA RESTAURANTES",0,0,'C');
    $this->Cell(45,5,$this->Image($logo2, $this->GetX()-62, $this->GetY()+4, 58),0,0,'C');

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(240,5,utf8_decode($con[0]['nomsucursal']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(240,5,utf8_decode($con[0]['documsucursal'] == '0' ? "" : $con[0]['documento'])." ".utf8_decode($con[0]['cuit']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    if($con[0]['id_provincia']!='0'){

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(240,5,utf8_decode($provincia = ($con[0]['id_provincia'] == '0' ? "" : $con[0]['provincia'])." ".$departamento = ($con[0]['id_departamento'] == '0' ? "" : $con[0]['departamento'])),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    }

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(240,5,utf8_decode($con[0]['direcsucursal']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(240,5,"Nº DE TLF: ".utf8_decode($con[0]['tlfsucursal']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(240,5,utf8_decode($con[0]['correosucursal']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');
    $this->Ln(8);

    $this->SetFont('Courier','B',14);  
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es negro)
    $this->Cell(330,7,'LISTADO DE VENTAS EN CAJA (Nº: '.utf8_decode($reg[0]["nrocaja"].": ".$reg[0]["nomcaja"]).")",0,1,'C');
    $this->Cell(330,7,"Y FECHAS (DESDE ".date("d-m-Y", strtotime($_GET["desde"]))." HASTA ".date("d-m-Y", strtotime($_GET["hasta"])).")",0,0,'C'); 

    $this->Ln(10);
    $this->SetFont('courier','B',10);
    $this->SetTextColor(255,255,255);  // Establece el color del texto (en este caso es BLANCO)
    $this->SetFillColor(230, 126, 34); // establece el color del fondo de la celda (en este caso es NARANJA)
    $this->Cell(15,8,'Nº',1,0,'C', True);
    $this->Cell(35,8,'Nº DE VENTA',1,0,'C', True);
    $this->Cell(70,8,'DESCRIPCIÓN DE CLIENTE',1,0,'C', True);
    $this->Cell(20,8,'Nº ARTIC',1,0,'C', True);
    $this->Cell(33,8,'TOTAL GRAVADO',1,0,'C', True);
    $this->Cell(33,8,'TOTAL EXENTO',1,0,'C', True);
    $this->Cell(34,8,'IMPORTE TOTAL',1,0,'C', True);
    $this->Cell(25,8,'STATUS',1,0,'C', True);
    $this->Cell(20,8,'PAGO',1,0,'C', True);
    $this->Cell(45,8,'FECHA EMISIÓN',1,1,'C', True);

    if($reg==""){
    echo "";      
    } else {
 
     /* AQUI DECLARO LAS COLUMNAS */
    $this->SetWidths(array(15,35,70,20,33,33,34,25,20,45));

    /* AQUI AGREGO LOS VALORES A MOSTRAR EN COLUMNAS */
    $a=1;
    $TotalArticulos=0;
    $TotalGravado=0;
    $TotalExento=0;
    $TotalImporte=0;

    for($i=0;$i<sizeof($reg);$i++){ 
    
    $TotalArticulos+=$reg[$i]['articulos'];
    $TotalGravado+=$reg[$i]['subtotalivasi'];
    $TotalExento+=$reg[$i]['subtotalivano'];
    $TotalImporte+=$reg[$i]['totalpago'];

    $this->SetFont('Courier','',10);  
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es negro)
    $this->Row(array($a++,utf8_decode($reg[$i]["codventa"]),utf8_decode($reg[$i]['codcliente'] == '0' ? "CONSUMIDOR FINAL" : $reg[$i]['nomcliente']),utf8_decode($reg[$i]["articulos"]),utf8_decode($simbolo.number_format($reg[$i]['subtotalivasi'], 2, '.', ',')),utf8_decode($simbolo.number_format($reg[$i]['subtotalivano'], 2, '.', ',')),utf8_decode($simbolo.number_format($reg[$i]['totalpago'], 2, '.', ',')),utf8_decode($reg[$i]["statusventa"]),utf8_decode($reg[$i]["tipopago"]),utf8_decode(date("d-m-Y h:i:s",strtotime($reg[$i]['fechaventa'])))));
        }
      }
   
    $this->Cell(120,5,'',0,0,'C');
    $this->SetFont('courier','B',10);
    $this->SetTextColor(255,255,255);  // Establece el color del texto (en este caso es blanco)
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es blanco)
    $this->CellFitSpace(20,5,utf8_decode(number_format($TotalArticulos, 2, '.', ',')),1,0,'L');
    $this->CellFitSpace(33,5,utf8_decode($simbolo.number_format($TotalGravado, 2, '.', ',')),1,0,'L');
    $this->CellFitSpace(33,5,utf8_decode($simbolo.number_format($TotalExento, 2, '.', ',')),1,0,'L');
    $this->CellFitSpace(34,5,utf8_decode($simbolo.number_format($TotalImporte, 2, '.', ',')),1,0,'L');
    $this->Ln();

    $this->Ln(12); 
    $this->SetFont('courier','B',10);
    $this->Cell(5,6,'',0,0,'');
    $this->Cell(200,6,'ELABORADO: '.utf8_decode($_SESSION["nombres"]),0,0,'');
    $this->Cell(125,6,'RECIBIDO:_____________________________________',0,0,'');
    $this->Ln();
    $this->Cell(5,6,'',0,0,'');
    $this->Cell(200,6,'FECHA/HORA: '.date('d-m-Y h:i:s A'),0,0,'');
    $this->Cell(125,6,'',0,0,'');
    $this->Ln(4);
     }
########################## FUNCION LISTAR VENTAS POR CAJAS ##############################

########################## FUNCION LISTAR VENTAS POR FECHAS ##############################
      function TablaListarVentasxFechas()
   {

    $con = new Login();
    $con = $con->ConfiguracionPorId();
    $simbolo = $con[0]['simbolo'];
    
    $tra = new Login();
    $reg = $tra->BuscarVentasxFechas();

    $logo = ( file_exists("./fotos/logo-pdf.png") == "" ? "./assets/images/null.png" : "./fotos/logo-pdf.png");
    $logo2 = ( file_exists("./fotos/logo-principal.png") == "" ? "./assets/images/null.png" : "./fotos/logo-principal.png");
    
    $con = new Login();
    $con = $con->ConfiguracionPorId(); 

    $this->Ln(2);
    $this->SetFont('Courier','B',12);
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es blanco)
    $this->SetFillColor(5, 130, 275); // establece el color del fondo de la celda (en este caso es NARANJA
    $this->Cell(45,5,$this->Image($logo, $this->GetX()+80, $this->GetY()+4, 24),0,0,'C');
    $this->Cell(240,5,"GESTIÓN Y CONTROL PARA RESTAURANTES",0,0,'C');
    $this->Cell(45,5,$this->Image($logo2, $this->GetX()-62, $this->GetY()+4, 58),0,0,'C');

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(240,5,utf8_decode($con[0]['nomsucursal']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(240,5,utf8_decode($con[0]['documsucursal'] == '0' ? "" : $con[0]['documento'])." ".utf8_decode($con[0]['cuit']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    if($con[0]['id_provincia']!='0'){

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(240,5,utf8_decode($provincia = ($con[0]['id_provincia'] == '0' ? "" : $con[0]['provincia'])." ".$departamento = ($con[0]['id_departamento'] == '0' ? "" : $con[0]['departamento'])),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    }

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(240,5,utf8_decode($con[0]['direcsucursal']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(240,5,"Nº DE TLF: ".utf8_decode($con[0]['tlfsucursal']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(240,5,utf8_decode($con[0]['correosucursal']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');
    $this->Ln(8);

    $this->SetFont('Courier','B',14);  
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es negro)
    $this->Cell(330,7,'LISTADO DE VENTAS POR FECHAS (DESDE '.date("d-m-Y", strtotime($_GET["desde"]))." HASTA ".date("d-m-Y", strtotime($_GET["hasta"])).")",0,0,'C');

    $this->Ln(10);
    $this->SetFont('courier','B',10);
    $this->SetTextColor(255,255,255);  // Establece el color del texto (en este caso es BLANCO)
    $this->SetFillColor(230, 126, 34); // establece el color del fondo de la celda (en este caso es NARANJA)
    $this->Cell(15,8,'Nº',1,0,'C', True);
    $this->Cell(35,8,'Nº DE VENTA',1,0,'C', True);
    $this->Cell(70,8,'DESCRIPCIÓN DE CLIENTE',1,0,'C', True);
    $this->Cell(20,8,'Nº ARTIC',1,0,'C', True);
    $this->Cell(33,8,'TOTAL GRAVADO',1,0,'C', True);
    $this->Cell(33,8,'TOTAL EXENTO',1,0,'C', True);
    $this->Cell(34,8,'IMPORTE TOTAL',1,0,'C', True);
    $this->Cell(25,8,'STATUS',1,0,'C', True);
    $this->Cell(20,8,'PAGO',1,0,'C', True);
    $this->Cell(45,8,'FECHA EMISIÓN',1,1,'C', True);

    if($reg==""){
    echo "";      
    } else {
 
     /* AQUI DECLARO LAS COLUMNAS */
    $this->SetWidths(array(15,35,70,20,33,33,34,25,20,45));

    /* AQUI AGREGO LOS VALORES A MOSTRAR EN COLUMNAS */
    $a=1;
    $TotalArticulos=0;
    $TotalGravado=0;
    $TotalExento=0;
    $TotalImporte=0;

    for($i=0;$i<sizeof($reg);$i++){ 
    
    $TotalArticulos+=$reg[$i]['articulos'];
    $TotalGravado+=$reg[$i]['subtotalivasi'];
    $TotalExento+=$reg[$i]['subtotalivano'];
    $TotalImporte+=$reg[$i]['totalpago'];

    $this->SetFont('Courier','',10);  
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es negro)
    $this->Row(array($a++,utf8_decode($reg[$i]["codventa"]),utf8_decode($reg[$i]['codcliente'] == '0' ? "CONSUMIDOR FINAL" : $reg[$i]['nomcliente']),utf8_decode($reg[$i]["articulos"]),utf8_decode($simbolo.number_format($reg[$i]['subtotalivasi'], 2, '.', ',')),utf8_decode($simbolo.number_format($reg[$i]['subtotalivano'], 2, '.', ',')),utf8_decode($simbolo.number_format($reg[$i]['totalpago'], 2, '.', ',')),utf8_decode($reg[$i]["statusventa"]),utf8_decode($reg[$i]["tipopago"]),utf8_decode(date("d-m-Y h:i:s",strtotime($reg[$i]['fechaventa'])))));
        }
      }
   
    $this->Cell(120,5,'',0,0,'C');
    $this->SetFont('courier','B',10);
    $this->SetTextColor(255,255,255);  // Establece el color del texto (en este caso es blanco)
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es blanco)
    $this->CellFitSpace(20,5,utf8_decode(number_format($TotalArticulos, 2, '.', ',')),1,0,'L');
    $this->CellFitSpace(33,5,utf8_decode($simbolo.number_format($TotalGravado, 2, '.', ',')),1,0,'L');
    $this->CellFitSpace(33,5,utf8_decode($simbolo.number_format($TotalExento, 2, '.', ',')),1,0,'L');
    $this->CellFitSpace(34,5,utf8_decode($simbolo.number_format($TotalImporte, 2, '.', ',')),1,0,'L');
    $this->Ln();

    $this->Ln(12); 
    $this->SetFont('courier','B',10);
    $this->Cell(5,6,'',0,0,'');
    $this->Cell(200,6,'ELABORADO: '.utf8_decode($_SESSION["nombres"]),0,0,'');
    $this->Cell(125,6,'RECIBIDO:_____________________________________',0,0,'');
    $this->Ln();
    $this->Cell(5,6,'',0,0,'');
    $this->Cell(200,6,'FECHA/HORA: '.date('d-m-Y h:i:s A'),0,0,'');
    $this->Cell(125,6,'',0,0,'');
    $this->Ln(4);
     }
########################## FUNCION LISTAR VENTAS POR FECHAS ##############################

################################### REPORTES DE VENTAS ##################################





















































############################## REPORTES DE CREDITOS ##################################

########################## FUNCION TICKET CREDITO ##############################
function TicketCredito()
    {  
    $imp = new Login();
    $imp = $imp->ImpuestosPorId();
    $impuesto = $imp[0]['nomimpuesto'];

    $con = new Login();
    $con = $con->ConfiguracionPorId();
    $simbolo = $con[0]['simbolo'];
    $moneda = $con[0]['moneda'];

    $tra = new Login();
    $reg = $tra->CreditosPorId();
  
    $this->SetFont('courier','B',12);
    $this->SetFillColor(2,157,116);
    $this->SetXY(4, 5);
    $this->Cell(65, 5, "TICKET DE CRÉDITO", 0, 0, 'C');
    $this->Ln(5);
  
    $this->SetFont('courier','B',11);
    $this->SetXY(4, 8.5);
    $this->CellFitSpace(65,6,utf8_decode($con[0]['nomsucursal']), 0, 1, 'C');
    $this->SetFont('courier','B',9);
    $this->SetXY(4, 13);
    $this->MultiCell(65,3,$this->SetFont('Courier','B',8)."MATRIZ:".utf8_decode($provincia = ($con[0]['provincia'] == '' ? "*********" : $con[0]['provincia'])." ".$departamento = ($con[0]['departamento'] == '' ? "*********" : $con[0]['departamento'])." ".$con[0]["direcsucursal"]),0,'C');
    $this->SetXY(4, 19);
    $this->CellFitSpace(65,3,"OBLIGADO A LLEVAR CONTABILIDAD:".utf8_decode($con[0]['llevacontabilidad']), 0, 1, 'C');
    $this->SetXY(4, 22);
    $this->CellFitSpace(65,3,'Nº DE '.$documento = ($con[0]['documsucursal'] == '0' ? "REG.:" : $con[0]['documento'].":").utf8_decode($con[0]['cuit']), 0, 1, 'C');
    $this->SetXY(4, 25);
    $this->CellFitSpace(65,3,"FACTURA:".utf8_decode($reg[0]['codventa']), 0, 1, 'C');
    $this->SetXY(4, 28);
    $this->CellFitSpace(65,3,"AMBIENTE: PRODUCCIÓN", 0, 1, 'C');
    $this->SetXY(4, 31);
    $this->CellFitSpace(65,3,"EMISIÓN: NORMAL", 0, 1, 'C');
    $this->Ln(2);

    $this->SetFont('courier','B',9);
    $this->SetX(2);
    $this->Cell(70,3,'------------- CLIENTE -------------',0,0,'C');
    $this->Ln(3);

    if($reg[0]['nomcliente']==""){

        $this->SetFont('courier','',9);
        $this->SetX(4);
        $this->CellFitSpace(66, 3, "CONSUMIDOR FINAL",0,1,'C');

    } else {

        $this->SetFont('courier','',8);
        $this->SetX(4);
        $this->CellFitSpace(66, 3,utf8_decode($documento = ($reg[0]['documcliente'] == '0' ? "DOC.:" : $reg[0]['documento'].": ").$reg[0]['dnicliente']),0,1,'C');
        $this->SetX(4);
        $this->CellFitSpace(66, 3,utf8_decode($reg[0]['nomcliente']),0,1,'C');

    }
    $this->SetFont('courier','',9);
    $this->SetX(2);
    $this->Cell(70,3,'-----------------------------------',0,1,'C');

    $this->SetX(4);
    $this->CellFitSpace(65, 3, "FECHA EMISIÓN: ".date("d/m/Y h:i:s A ",time()+1800),0,1,'C');

    $this->SetFont('courier','B',9);
    $this->SetX(2);
    $this->Cell(70,3,'-------- DETALLES DE ABONO --------',0,1,'C');
    $this->Ln(1);

    $this->SetX(4);
    $this->SetFont('courier','B',8);
    $this->SetTextColor(3, 3, 3); // Establece el color del texto (en este caso es Negro)
    $this->SetFillColor(229, 229, 229); // establece el color del fondo de la celda (en este caso es GRIS)
    $this->Cell(16,3,"Nº CAJA",0,0,'C');
    $this->Cell(24,3,"MONTO ABONO",0,0,'C');
    $this->Cell(26,3,"FECHA",0,1,'C');

    $this->SetFont('courier','B',9);
    $this->SetX(2);
    $this->Cell(70,3,'-----------------------------------',0,0,'C');
    $this->Ln(3);

    $tra = new Login();
    $detalle = $tra->VerDetallesAbonos();
    if($detalle==""){
        echo "";      
    } else {
    $cantidad=0;

     /* AQUI DECLARO LAS COLUMNAS */
    $this->SetWidths(array(22,22,22));

    $a=1;
    for($i=0;$i<sizeof($detalle);$i++){

    $this->SetX(4);
    $this->SetFont('courier','',8);  
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es negro)
    $this->CellFitSpace(16,4,utf8_decode($detalle[$i]['nrocaja']),0,0,'C');
    $this->CellFitSpace(24,4,utf8_decode($simbolo.number_format($detalle[$i]['montoabono'], 2, '.', ',')),0,0,'C');
    $this->CellFitSpace(26,4,utf8_decode(date("d-m-Y h:i:s",strtotime($detalle[$i]['fechaabono']))),0,0,'C');
    $this->Ln();  
       }
    }

    $this->SetFont('courier','B',9);
    $this->SetX(2);

    $this->Cell(70,3,'------ INFORMACIÓN ADICIONAL ------',0,1,'C');
    $this->Ln(1);

    $this->SetFont('courier','B',9);
    $this->SetX(2);
    $this->Cell(70,3,'FIRMA: ___________________________',0,1,'C');
    $this->Ln(4);

    $this->SetFont('courier','B',9);
    $this->SetX(2);
    $this->Cell(70,3,'-------------- PAGO ---------------',0,1,'C');
    $this->Ln(1);


    $this->SetFont('courier','B',9);
    $this->SetX(4);
    $this->SetFont('courier','',9);
    $this->CellFitSpace(66,3,utf8_decode($reg[0]["tipopago"]." - ".$variable = ( $reg[0]['tipopago'] == 'CONTADO' ? $reg[0]['mediopago'] : $reg[0]['formapago'])),0,1,'C');

    $this->SetX(4);
    $this->SetFont('courier','B',9);
    $this->CellFitSpace(40,3,"STATUS PAGO:",0,0,'R');
    $this->SetFont('courier','',9);
    if($reg[0]['fechavencecredito']== '0000-00-00') { 
        $this->CellFitSpace(26,3,utf8_decode($reg[0]["statusventa"]),0,1,'R');
    } elseif($reg[0]['fechavencecredito'] >= date("Y-m-d")) { 
        $this->CellFitSpace(26,3,utf8_decode($reg[0]["statusventa"]),0,1,'R');
    } elseif($reg[0]['fechavencecredito'] < date("Y-m-d")) { 
        $this->CellFitSpace(26,3,utf8_decode("VENCIDA"),0,1,'R');
    }  

    $this->SetX(4);
    $this->SetFont('courier','B',9);
    $this->CellFitSpace(40,3,"FECHA VENC:",0,0,'R');
    $this->SetFont('courier','',8);
    $this->CellFitSpace(26,3,utf8_decode(date("d-m-Y",strtotime($reg[0]["fechavencecredito"]))),0,1,'R');

    $this->SetX(4);
    $this->SetFont('courier','B',9);
    $this->CellFitSpace(40,3,"DIAS VENCIDOS:",0,0,'R');
    $this->SetFont('courier','',9);
    if($reg[0]['fechavencecredito']== '0000-00-00') { 
        $this->CellFitSpace(26,3,utf8_decode("0"),0,1,'R');
    } elseif($reg[0]['fechavencecredito'] >= date("Y-m-d")) { 
        $this->CellFitSpace(26,3,utf8_decode("0"),0,1,'R');
    } elseif($reg[0]['fechavencecredito'] < date("Y-m-d")) { 
        $this->CellFitSpace(26,3,utf8_decode(Dias_Transcurridos(date("Y-m-d"),$reg[0]['fechavencecredito'])),0,1,'R');
    }

    $this->SetX(4);
    $this->SetFont('courier','B',9);
    $this->CellFitSpace(40,3,"IMPORTE TOTAL:",0,0,'R');
    $this->SetFont('courier','',8);
    $this->CellFitSpace(26,3,utf8_decode($simbolo.number_format($reg[0]["totalpago"], 2, '.', ',')),0,1,'R');

    $this->SetX(4);
    $this->SetFont('courier','B',9);
    $this->CellFitSpace(40,3,"TOTAL ABONO:",0,0,'R');
    $this->SetFont('courier','',8);
    $this->CellFitSpace(26,3,utf8_decode($simbolo.number_format($reg[0]["abonototal"], 2, '.', ',')),0,1,'R');

    $this->SetX(4);
    $this->SetFont('courier','B',9);
    $this->CellFitSpace(40,3,"TOTAL DEBE:",0,0,'R');
    $this->SetFont('courier','',8);
    $this->CellFitSpace(26,3,utf8_decode($simbolo.number_format($reg[0]["totalpago"]-$reg[0]["abonototal"], 2, '.', ',')),0,1,'R');
    $this->Ln(1);

    $this->SetFont('courier','B',9);
    $this->SetX(2);
    $this->Cell(70,0.5,'-----------------------------------',0,1,'C');
    $this->SetX(2);
    $this->Cell(70,0.5,'-----------------------------------',0,1,'C');
    $this->Ln(3);

    $this->SetFont('courier','BI',9);
    $this->SetX(4);
    $this->SetFillColor(3, 3, 3);
    $this->CellFitSpace(65,3,"GRACIAS POR PREFERIRNOS",0,1,'C');
    $this->Ln(3);
        
}
########################## FUNCION TICKET CREDITO ##############################

########################## FUNCION LISTAR CREDITOS ##############################
      function TablaListarCreditos()
   {

    $con = new Login();
    $con = $con->ConfiguracionPorId();
    $simbolo = $con[0]['simbolo'];
    
    $tra = new Login();
    $reg = $tra->ListarCreditos();

   $logo = ( file_exists("./fotos/logo-pdf.png") == "" ? "./assets/images/null.png" : "./fotos/logo-pdf.png");
    $logo2 = ( file_exists("./fotos/logo-principal.png") == "" ? "./assets/images/null.png" : "./fotos/logo-principal.png");
    
    $con = new Login();
    $con = $con->ConfiguracionPorId(); 

    $this->Ln(2);
    $this->SetFont('Courier','B',12);
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es blanco)
    $this->SetFillColor(5, 130, 275); // establece el color del fondo de la celda (en este caso es NARANJA
    $this->Cell(45,5,$this->Image($logo, $this->GetX()+80, $this->GetY()+4, 24),0,0,'C');
    $this->Cell(240,5,"GESTIÓN Y CONTROL PARA RESTAURANTES",0,0,'C');
    $this->Cell(45,5,$this->Image($logo2, $this->GetX()-62, $this->GetY()+4, 58),0,0,'C');

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(240,5,utf8_decode($con[0]['nomsucursal']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(240,5,utf8_decode($con[0]['documsucursal'] == '0' ? "" : $con[0]['documento'])." ".utf8_decode($con[0]['cuit']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    if($con[0]['id_provincia']!='0'){

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(240,5,utf8_decode($provincia = ($con[0]['id_provincia'] == '0' ? "" : $con[0]['provincia'])." ".$departamento = ($con[0]['id_departamento'] == '0' ? "" : $con[0]['departamento'])),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    }

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(240,5,utf8_decode($con[0]['direcsucursal']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(240,5,"Nº DE TLF: ".utf8_decode($con[0]['tlfsucursal']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(240,5,utf8_decode($con[0]['correosucursal']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');
    $this->Ln(8);
    
    $this->SetFont('Courier','B',14);  
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es negro)
    $this->Cell(330,10,'LISTADO GENERAL DE CRÉDITOS',0,0,'C');

    $this->Ln();
    $this->SetFont('courier','B',10);
    $this->SetTextColor(255,255,255);  // Establece el color del texto (en este caso es BLANCO)
    $this->SetFillColor(230, 126, 34); // establece el color del fondo de la celda (en este caso es NARANJA)
    $this->Cell(15,8,'Nº',1,0,'C', True);
    $this->Cell(35,8,'Nº DE VENTA',1,0,'C', True);
    $this->Cell(80,8,'DESCRIPCIÓN DE CLIENTE',1,0,'C', True);
    $this->Cell(35,8,'IMPORTE TOTAL',1,0,'C', True);
    $this->Cell(30,8,'TOTAL ABONO',1,0,'C', True);
    $this->Cell(35,8,'TOTAL DEBE',1,0,'C', True);
    $this->Cell(30,8,'STATUS',1,0,'C', True);
    $this->Cell(20,8,'DIAS VENC',1,0,'C', True);
    $this->Cell(50,8,'FECHA EMISIÓN',1,1,'C', True);

    if($reg==""){
    echo "";      
    } else {
 
     /* AQUI DECLARO LAS COLUMNAS */
    $this->SetWidths(array(15,35,80,35,30,35,30,20,50));

    /* AQUI AGREGO LOS VALORES A MOSTRAR EN COLUMNAS */
    $a=1;
    $TotalImporte=0;
    $TotalAbono=0;
    $TotalDebe=0;

    for($i=0;$i<sizeof($reg);$i++){ 
    
    $TotalImporte+=$reg[$i]['totalpago'];
    $TotalAbono+=$reg[$i]['abonototal'];
    $TotalDebe+=$reg[$i]['totalpago']-$reg[$i]['abonototal'];

    $this->SetFont('Courier','',10);  
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es negro)
    $this->Row(array($a++,utf8_decode($reg[$i]["codventa"]),utf8_decode($reg[$i]['codcliente'] == '0' ? "CONSUMIDOR FINAL" : $reg[$i]['nomcliente']),utf8_decode($simbolo.number_format($reg[$i]['totalpago'], 2, '.', ',')),utf8_decode($simbolo.number_format($reg[$i]['abonototal'], 2, '.', ',')),utf8_decode($simbolo.number_format($reg[$i]['totalpago']-$reg[$i]['abonototal'], 2, '.', ',')),utf8_decode($reg[$i]["statusventa"]),
        utf8_decode($reg[$i]['fechavencecredito'] == '0000-00-00' || $reg[$i]['fechavencecredito'] >= date("Y-m-d") && $reg[$i]['fechapagado']== "0000-00-00" 
            ? "0" 
            : $reg[$i]['fechavencecredito'] < date("Y-m-d") && $reg[$i]['fechapagado']== "0000-00-00" ? Dias_Transcurridos(date("Y-m-d"),$reg[$i]['fechavencecredito']) : Dias_Transcurridos($reg[$i]['fechapagado'],$reg[$i]['fechavencecredito'])
        ),
        utf8_decode(date("d-m-Y h:i:s",strtotime($reg[$i]['fechaventa'])))));

        }
   
    $this->Cell(130,5,'',0,0,'C');
    $this->SetFont('courier','B',10);
    $this->SetTextColor(255,255,255);  // Establece el color del texto (en este caso es blanco)
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es blanco)
    $this->CellFitSpace(35,5,utf8_decode($simbolo.number_format($TotalImporte, 2, '.', ',')),1,0,'L');
    $this->CellFitSpace(30,5,utf8_decode($simbolo.number_format($TotalAbono, 2, '.', ',')),1,0,'L');
    $this->CellFitSpace(35,5,utf8_decode($simbolo.number_format($TotalDebe, 2, '.', ',')),1,0,'L');
    $this->Ln();
      }


    $this->Ln(12); 
    $this->SetFont('courier','B',10);
    $this->Cell(5,6,'',0,0,'');
    $this->Cell(200,6,'ELABORADO: '.utf8_decode($_SESSION["nombres"]),0,0,'');
    $this->Cell(125,6,'RECIBIDO:_____________________________________',0,0,'');
    $this->Ln();
    $this->Cell(5,6,'',0,0,'');
    $this->Cell(200,6,'FECHA/HORA: '.date('d-m-Y h:i:s A'),0,0,'');
    $this->Cell(125,6,'',0,0,'');
    $this->Ln(4);
     }
########################## FUNCION LISTAR CREDITOS ##############################

######################## FUNCION LISTAR CREDITOS POR CLIENTES #########################
      function TablaListarCreditosxClientes()
   {

    $con = new Login();
    $con = $con->ConfiguracionPorId();
    $simbolo = $con[0]['simbolo'];
    
    $tra = new Login();
    $reg = $tra->BuscarCreditosxClientes();

    $logo = ( file_exists("./fotos/logo-pdf.png") == "" ? "./assets/images/null.png" : "./fotos/logo-pdf.png");
    $logo2 = ( file_exists("./fotos/logo-principal.png") == "" ? "./assets/images/null.png" : "./fotos/logo-principal.png");
    
    $con = new Login();
    $con = $con->ConfiguracionPorId(); 

    $this->Ln(2);
    $this->SetFont('Courier','B',12);
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es blanco)
    $this->SetFillColor(5, 130, 275); // establece el color del fondo de la celda (en este caso es NARANJA
    $this->Cell(45,5,$this->Image($logo, $this->GetX()+80, $this->GetY()+4, 24),0,0,'C');
    $this->Cell(240,5,"GESTIÓN Y CONTROL PARA RESTAURANTES",0,0,'C');
    $this->Cell(45,5,$this->Image($logo2, $this->GetX()-62, $this->GetY()+4, 58),0,0,'C');

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(240,5,utf8_decode($con[0]['nomsucursal']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(240,5,utf8_decode($con[0]['documsucursal'] == '0' ? "" : $con[0]['documento'])." ".utf8_decode($con[0]['cuit']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    if($con[0]['id_provincia']!='0'){

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(240,5,utf8_decode($provincia = ($con[0]['id_provincia'] == '0' ? "" : $con[0]['provincia'])." ".$departamento = ($con[0]['id_departamento'] == '0' ? "" : $con[0]['departamento'])),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    }

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(240,5,utf8_decode($con[0]['direcsucursal']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(240,5,"Nº DE TLF: ".utf8_decode($con[0]['tlfsucursal']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(240,5,utf8_decode($con[0]['correosucursal']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');
    $this->Ln(8);

    $this->SetFont('Courier','B',14);  
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es negro)
    $this->Cell(330,10,'LISTADO DE CRÉDITOS POR CLIENTES ',0,0,'C');

    $this->Ln(10);
    $this->SetFont('courier','B',10);
    $this->SetTextColor(255,255,255);  // Establece el color del texto (en este caso es BLANCO)
    $this->SetFillColor(230, 126, 34); // establece el color del fondo de la celda (en este caso es NARANJA)
    $this->Cell(15,8,'Nº',1,0,'C', True);
    $this->Cell(35,8,'Nº DE VENTA',1,0,'C', True);
    $this->Cell(90,8,'DESCRIPCIÓN DE CLIENTE',1,0,'C', True);
    $this->Cell(40,8,'IMPORTE TOTAL',1,0,'C', True);
    $this->Cell(35,8,'TOTAL ABONO',1,0,'C', True);
    $this->Cell(35,8,'TOTAL DEBE',1,0,'C', True);
    $this->Cell(30,8,'STATUS',1,0,'C', True);
    $this->Cell(50,8,'FECHA EMISIÓN',1,1,'C', True);

    if($reg==""){
    echo "";      
    } else {
 
     /* AQUI DECLARO LAS COLUMNAS */
    $this->SetWidths(array(15,35,90,40,35,35,30,50));

    /* AQUI AGREGO LOS VALORES A MOSTRAR EN COLUMNAS */
    $a=1;
    $TotalImporte=0;
    $TotalAbono=0;
    $TotalDebe=0;

    for($i=0;$i<sizeof($reg);$i++){ 
    
    $TotalImporte+=$reg[$i]['totalpago'];
    $TotalAbono+=$reg[$i]['abonototal'];
    $TotalDebe+=$reg[$i]['totalpago']-$reg[$i]['abonototal'];

    $this->SetFont('Courier','',10);  
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es negro)
    $this->Row(array($a++,utf8_decode($reg[$i]["codventa"]),utf8_decode($reg[$i]['codcliente'] == '0' ? "CONSUMIDOR FINAL" : $reg[$i]['nomcliente']),utf8_decode($simbolo.number_format($reg[$i]['totalpago'], 2, '.', ',')),utf8_decode($simbolo.number_format($reg[$i]['abonototal'], 2, '.', ',')),utf8_decode($simbolo.number_format($reg[$i]['totalpago']-$reg[$i]['abonototal'], 2, '.', ',')),utf8_decode($reg[$i]["statusventa"]),utf8_decode(date("d-m-Y h:i:s",strtotime($reg[$i]['fechaventa'])))));
        }
      }
   
    $this->Cell(140,5,'',0,0,'C');
    $this->SetFont('courier','B',10);
    $this->SetTextColor(255,255,255);  // Establece el color del texto (en este caso es blanco)
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es blanco)
    $this->CellFitSpace(40,5,utf8_decode($simbolo.number_format($TotalImporte, 2, '.', ',')),1,0,'L');
    $this->CellFitSpace(35,5,utf8_decode($simbolo.number_format($TotalAbono, 2, '.', ',')),1,0,'L');
    $this->CellFitSpace(35,5,utf8_decode($simbolo.number_format($TotalDebe, 2, '.', ',')),1,0,'L');
    $this->Ln();
    

    $this->Ln(12); 
    $this->SetFont('courier','B',10);
    $this->Cell(5,6,'',0,0,'');
    $this->Cell(200,6,'ELABORADO: '.utf8_decode($_SESSION["nombres"]),0,0,'');
    $this->Cell(125,6,'RECIBIDO:_____________________________________',0,0,'');
    $this->Ln();
    $this->Cell(5,6,'',0,0,'');
    $this->Cell(200,6,'FECHA/HORA: '.date('d-m-Y h:i:s A'),0,0,'');
    $this->Cell(125,6,'',0,0,'');
    $this->Ln(4);
     }
######################## FUNCION LISTAR CREDITOS POR CLIENTES #########################

######################## FUNCION LISTAR CREDITOS POR FECHAS ##########################
      function TablaListarCreditosxFechas()
   {

    $con = new Login();
    $con = $con->ConfiguracionPorId();
    $simbolo = $con[0]['simbolo'];
    
    $tra = new Login();
    $reg = $tra->BuscarCreditosxFechas();

    $logo = ( file_exists("./fotos/logo-pdf.png") == "" ? "./assets/images/null.png" : "./fotos/logo-pdf.png");
    $logo2 = ( file_exists("./fotos/logo-principal.png") == "" ? "./assets/images/null.png" : "./fotos/logo-principal.png");
    
    $con = new Login();
    $con = $con->ConfiguracionPorId(); 

    $this->Ln(2);
    $this->SetFont('Courier','B',12);
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es blanco)
    $this->SetFillColor(5, 130, 275); // establece el color del fondo de la celda (en este caso es NARANJA
    $this->Cell(45,5,$this->Image($logo, $this->GetX()+80, $this->GetY()+4, 24),0,0,'C');
    $this->Cell(240,5,"GESTIÓN Y CONTROL PARA RESTAURANTES",0,0,'C');
    $this->Cell(45,5,$this->Image($logo2, $this->GetX()-62, $this->GetY()+4, 58),0,0,'C');

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(240,5,utf8_decode($con[0]['nomsucursal']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(240,5,utf8_decode($con[0]['documsucursal'] == '0' ? "" : $con[0]['documento'])." ".utf8_decode($con[0]['cuit']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    if($con[0]['id_provincia']!='0'){

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(240,5,utf8_decode($provincia = ($con[0]['id_provincia'] == '0' ? "" : $con[0]['provincia'])." ".$departamento = ($con[0]['id_departamento'] == '0' ? "" : $con[0]['departamento'])),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    }

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(240,5,utf8_decode($con[0]['direcsucursal']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(240,5,"Nº DE TLF: ".utf8_decode($con[0]['tlfsucursal']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');

    $this->Ln();
    $this->Cell(45,5,"",0,0,'C');
    $this->Cell(240,5,utf8_decode($con[0]['correosucursal']),0,0,'C');
    $this->Cell(45,5,"",0,0,'C');
    $this->Ln(8);

    $this->SetFont('Courier','B',14);  
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es negro)
    $this->Cell(330,7,'LISTADO DE CRÉDITOS POR FECHAS (DESDE '.date("d-m-Y", strtotime($_GET["desde"]))." HASTA ".date("d-m-Y", strtotime($_GET["hasta"])).")",0,0,'C');

    $this->Ln(10);
    $this->SetFont('courier','B',10);
    $this->SetTextColor(255,255,255);  // Establece el color del texto (en este caso es BLANCO)
    $this->SetFillColor(230, 126, 34); // establece el color del fondo de la celda (en este caso es NARANJA)
    $this->Cell(15,8,'Nº',1,0,'C', True);
    $this->Cell(35,8,'Nº DE VENTA',1,0,'C', True);
    $this->Cell(90,8,'DESCRIPCIÓN DE CLIENTE',1,0,'C', True);
    $this->Cell(40,8,'IMPORTE TOTAL',1,0,'C', True);
    $this->Cell(35,8,'TOTAL ABONO',1,0,'C', True);
    $this->Cell(35,8,'TOTAL DEBE',1,0,'C', True);
    $this->Cell(30,8,'STATUS',1,0,'C', True);
    $this->Cell(50,8,'FECHA EMISIÓN',1,1,'C', True);

    if($reg==""){
    echo "";      
    } else {
 
     /* AQUI DECLARO LAS COLUMNAS */
    $this->SetWidths(array(15,35,90,40,35,35,30,50));

    /* AQUI AGREGO LOS VALORES A MOSTRAR EN COLUMNAS */
    $a=1;
    $TotalImporte=0;
    $TotalAbono=0;
    $TotalDebe=0;

    for($i=0;$i<sizeof($reg);$i++){ 
    
    $TotalImporte+=$reg[$i]['totalpago'];
    $TotalAbono+=$reg[$i]['abonototal'];
    $TotalDebe+=$reg[$i]['totalpago']-$reg[$i]['abonototal'];

    $this->SetFont('Courier','',10);  
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es negro)
    $this->Row(array($a++,utf8_decode($reg[$i]["codventa"]),utf8_decode($reg[$i]['codcliente'] == '0' ? "CONSUMIDOR FINAL" : $reg[$i]['nomcliente']),utf8_decode($simbolo.number_format($reg[$i]['totalpago'], 2, '.', ',')),utf8_decode($simbolo.number_format($reg[$i]['abonototal'], 2, '.', ',')),utf8_decode($simbolo.number_format($reg[$i]['totalpago']-$reg[$i]['abonototal'], 2, '.', ',')),utf8_decode($reg[$i]["statusventa"]),utf8_decode(date("d-m-Y h:i:s",strtotime($reg[$i]['fechaventa'])))));
        }
      }
   
    $this->Cell(140,5,'',0,0,'C');
    $this->SetFont('courier','B',10);
    $this->SetTextColor(255,255,255);  // Establece el color del texto (en este caso es blanco)
    $this->SetTextColor(3,3,3);  // Establece el color del texto (en este caso es blanco)
    $this->CellFitSpace(40,5,utf8_decode($simbolo.number_format($TotalImporte, 2, '.', ',')),1,0,'L');
    $this->CellFitSpace(35,5,utf8_decode($simbolo.number_format($TotalAbono, 2, '.', ',')),1,0,'L');
    $this->CellFitSpace(35,5,utf8_decode($simbolo.number_format($TotalDebe, 2, '.', ',')),1,0,'L');
    $this->Ln();
    

    $this->Ln(12); 
    $this->SetFont('courier','B',10);
    $this->Cell(5,6,'',0,0,'');
    $this->Cell(200,6,'ELABORADO: '.utf8_decode($_SESSION["nombres"]),0,0,'');
    $this->Cell(125,6,'RECIBIDO:_____________________________________',0,0,'');
    $this->Ln();
    $this->Cell(5,6,'',0,0,'');
    $this->Cell(200,6,'FECHA/HORA: '.date('d-m-Y h:i:s A'),0,0,'');
    $this->Cell(125,6,'',0,0,'');
    $this->Ln(4);
     }
######################## FUNCION LISTAR CREDITOS POR FECHAS ##########################

############################### REPORTES DE CREDITOS ###############################







































































   ###################### AQUI COMIENZA CODIGO PARA AJUSTAR TEXTO #########################

########### FUNCION PARA CODIGO DE BARRA CON CODE39 ############
function Code39($x, $y, $code, $ext = true, $cks = false, $w = 0.4, $h = 20, $wide = true) {

    //Display code
    $this->SetFont('Arial', '', 10);
    $this->Text($x, $y+$h+4, $code);

    if($ext) {
        //Extended encoding
        $code = $this->encode_code39_ext($code);
    }
    else {
        //Convert to upper case
        $code = strtoupper($code);
        //Check validity
        if(!preg_match('|^[0-9A-Z. $/+%-]*$|', $code))
            $this->Error('Invalid barcode value: '.$code);
    }

    //Compute checksum
    if ($cks)
        $code .= $this->checksum_code39($code);

    //Add start and stop characters
    $code = '*'.$code.'*';

    //Conversion tables
    $narrow_encoding = array (
        '0' => '101001101101', '1' => '110100101011', '2' => '101100101011', 
        '3' => '110110010101', '4' => '101001101011', '5' => '110100110101', 
        '6' => '101100110101', '7' => '101001011011', '8' => '110100101101', 
        '9' => '101100101101', 'A' => '110101001011', 'B' => '101101001011', 
        'C' => '110110100101', 'D' => '101011001011', 'E' => '110101100101', 
        'F' => '101101100101', 'G' => '101010011011', 'H' => '110101001101', 
        'I' => '101101001101', 'J' => '101011001101', 'K' => '110101010011', 
        'L' => '101101010011', 'M' => '110110101001', 'N' => '101011010011', 
        'O' => '110101101001', 'P' => '101101101001', 'Q' => '101010110011', 
        'R' => '110101011001', 'S' => '101101011001', 'T' => '101011011001', 
        'U' => '110010101011', 'V' => '100110101011', 'W' => '110011010101', 
        'X' => '100101101011', 'Y' => '110010110101', 'Z' => '100110110101', 
        '-' => '100101011011', '.' => '110010101101', ' ' => '100110101101', 
        '*' => '100101101101', '$' => '100100100101', '/' => '100100101001', 
        '+' => '100101001001', '%' => '101001001001' );

    $wide_encoding = array (
        '0' => '101000111011101', '1' => '111010001010111', '2' => '101110001010111', 
        '3' => '111011100010101', '4' => '101000111010111', '5' => '111010001110101', 
        '6' => '101110001110101', '7' => '101000101110111', '8' => '111010001011101', 
        '9' => '101110001011101', 'A' => '111010100010111', 'B' => '101110100010111', 
        'C' => '111011101000101', 'D' => '101011100010111', 'E' => '111010111000101', 
        'F' => '101110111000101', 'G' => '101010001110111', 'H' => '111010100011101', 
        'I' => '101110100011101', 'J' => '101011100011101', 'K' => '111010101000111', 
        'L' => '101110101000111', 'M' => '111011101010001', 'N' => '101011101000111', 
        'O' => '111010111010001', 'P' => '101110111010001', 'Q' => '101010111000111', 
        'R' => '111010101110001', 'S' => '101110101110001', 'T' => '101011101110001', 
        'U' => '111000101010111', 'V' => '100011101010111', 'W' => '111000111010101', 
        'X' => '100010111010111', 'Y' => '111000101110101', 'Z' => '100011101110101', 
        '-' => '100010101110111', '.' => '111000101011101', ' ' => '100011101011101', 
        '*' => '100010111011101', '$' => '100010001000101', '/' => '100010001010001', 
        '+' => '100010100010001', '%' => '101000100010001');

    $encoding = $wide ? $wide_encoding : $narrow_encoding;

    //Inter-character spacing
    $gap = ($w > 0.29) ? '00' : '0';

    //Convert to bars
    $encode = '';
    for ($i = 0; $i< strlen($code); $i++)
        $encode .= $encoding[$code[$i]].$gap;

    //Draw bars
    $this->draw_code39($encode, $x, $y, $w, $h);
}

function checksum_code39($code) {

    //Compute the modulo 43 checksum

    $chars = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 
                            'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 
                            'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 
                            'W', 'X', 'Y', 'Z', '-', '.', ' ', '$', '/', '+', '%');
    $sum = 0;
    for ($i=0 ; $i<strlen($code); $i++) {
        $a = array_keys($chars, $code[$i]);
        $sum += $a[0];
    }
    $r = $sum % 43;
    return $chars[$r];
}

function encode_code39_ext($code) {

    //Encode characters in extended mode

    $encode = array(
        chr(0) => '%U', chr(1) => '$A', chr(2) => '$B', chr(3) => '$C', 
        chr(4) => '$D', chr(5) => '$E', chr(6) => '$F', chr(7) => '$G', 
        chr(8) => '$H', chr(9) => '$I', chr(10) => '$J', chr(11) => '£K', 
        chr(12) => '$L', chr(13) => '$M', chr(14) => '$N', chr(15) => '$O', 
        chr(16) => '$P', chr(17) => '$Q', chr(18) => '$R', chr(19) => '$S', 
        chr(20) => '$T', chr(21) => '$U', chr(22) => '$V', chr(23) => '$W', 
        chr(24) => '$X', chr(25) => '$Y', chr(26) => '$Z', chr(27) => '%A', 
        chr(28) => '%B', chr(29) => '%C', chr(30) => '%D', chr(31) => '%E', 
        chr(32) => ' ', chr(33) => '/A', chr(34) => '/B', chr(35) => '/C', 
        chr(36) => '/D', chr(37) => '/E', chr(38) => '/F', chr(39) => '/G', 
        chr(40) => '/H', chr(41) => '/I', chr(42) => '/J', chr(43) => '/K', 
        chr(44) => '/L', chr(45) => '-', chr(46) => '.', chr(47) => '/O', 
        chr(48) => '0', chr(49) => '1', chr(50) => '2', chr(51) => '3', 
        chr(52) => '4', chr(53) => '5', chr(54) => '6', chr(55) => '7', 
        chr(56) => '8', chr(57) => '9', chr(58) => '/Z', chr(59) => '%F', 
        chr(60) => '%G', chr(61) => '%H', chr(62) => '%I', chr(63) => '%J', 
        chr(64) => '%V', chr(65) => 'A', chr(66) => 'B', chr(67) => 'C', 
        chr(68) => 'D', chr(69) => 'E', chr(70) => 'F', chr(71) => 'G', 
        chr(72) => 'H', chr(73) => 'I', chr(74) => 'J', chr(75) => 'K', 
        chr(76) => 'L', chr(77) => 'M', chr(78) => 'N', chr(79) => 'O', 
        chr(80) => 'P', chr(81) => 'Q', chr(82) => 'R', chr(83) => 'S', 
        chr(84) => 'T', chr(85) => 'U', chr(86) => 'V', chr(87) => 'W', 
        chr(88) => 'X', chr(89) => 'Y', chr(90) => 'Z', chr(91) => '%K', 
        chr(92) => '%L', chr(93) => '%M', chr(94) => '%N', chr(95) => '%O', 
        chr(96) => '%W', chr(97) => '+A', chr(98) => '+B', chr(99) => '+C', 
        chr(100) => '+D', chr(101) => '+E', chr(102) => '+F', chr(103) => '+G', 
        chr(104) => '+H', chr(105) => '+I', chr(106) => '+J', chr(107) => '+K', 
        chr(108) => '+L', chr(109) => '+M', chr(110) => '+N', chr(111) => '+O', 
        chr(112) => '+P', chr(113) => '+Q', chr(114) => '+R', chr(115) => '+S', 
        chr(116) => '+T', chr(117) => '+U', chr(118) => '+V', chr(119) => '+W', 
        chr(120) => '+X', chr(121) => '+Y', chr(122) => '+Z', chr(123) => '%P', 
        chr(124) => '%Q', chr(125) => '%R', chr(126) => '%S', chr(127) => '%T');

    $code_ext = '';
    for ($i = 0 ; $i<strlen($code); $i++) {
        if (ord($code[$i]) > 127)
            $this->Error('Invalid character: '.$code[$i]);
        $code_ext .= $encode[$code[$i]];
    }
    return $code_ext;
}

function draw_code39($code, $x, $y, $w, $h) {

    //Draw bars

    for($i=0; $i<strlen($code); $i++) {
        if($code[$i] == '1')
            $this->Rect($x+$i*$w, $y, $w, $h, 'F');
    }
}


########### FUNCION PARA CODIGO DE BARRA CON EAN13 ############
function EAN13($x, $y, $barcode, $h=16, $w=.35)
{
 $this->Barcode($x,$y,$barcode,$h,$w,13);
}
function UPC_A($x, $y, $barcode, $h=16, $w=.35)
{
 $this->Barcode($x,$y,$barcode,$h,$w,12);
}
function GetCheckDigit($barcode)
{
 //Compute the check digit
 $sum=0;
 for($i=1;$i<=11;$i+=2)
 $sum+=3*$barcode[$i];
 for($i=0;$i<=10;$i+=2)
 $sum+=$barcode[$i];
 $r=$sum%10;
 if($r>0)
 $r=10-$r;
 return $r;
}
function TestCheckDigit($barcode)
{
 //Test validity of check digit
 $sum=0;
 for($i=1;$i<=11;$i+=2)
 $sum+=3*$barcode[$i];
 for($i=0;$i<=10;$i+=2)
 $sum+=$barcode[$i];
 return ($sum+$barcode[12])%10==0;
}
function Barcode($x, $y, $barcode, $h, $w, $len)
{
 //Padding
 $barcode=str_pad($barcode,$len-1,'0',STR_PAD_LEFT);
 if($len==12)
 $barcode='0'.$barcode;
 //Add or control the check digit
 if(strlen($barcode)==12)
 $barcode.=$this->GetCheckDigit($barcode);
 elseif(!$this->TestCheckDigit($barcode))
 $this->Error('Incorrect check digit');
 //Convert digits to bars
 $codes=array(
 'A'=>array(
 '0'=>'0001101','1'=>'0011001','2'=>'0010011','3'=>'0111101','4'=>'0100011',
 '5'=>'0110001','6'=>'0101111','7'=>'0111011','8'=>'0110111','9'=>'0001011'),
 'B'=>array(
 '0'=>'0100111','1'=>'0110011','2'=>'0011011','3'=>'0100001','4'=>'0011101',
 '5'=>'0111001','6'=>'0000101','7'=>'0010001','8'=>'0001001','9'=>'0010111'),
 'C'=>array(
 '0'=>'1110010','1'=>'1100110','2'=>'1101100','3'=>'1000010','4'=>'1011100',
 '5'=>'1001110','6'=>'1010000','7'=>'1000100','8'=>'1001000','9'=>'1110100')
 );
 $parities=array(
 '0'=>array('A','A','A','A','A','A'),
 '1'=>array('A','A','B','A','B','B'),
 '2'=>array('A','A','B','B','A','B'),
 '3'=>array('A','A','B','B','B','A'),
 '4'=>array('A','B','A','A','B','B'),
 '5'=>array('A','B','B','A','A','B'),
 '6'=>array('A','B','B','B','A','A'),
 '7'=>array('A','B','A','B','A','B'),
 '8'=>array('A','B','A','B','B','A'),
 '9'=>array('A','B','B','A','B','A')
 );
 $code='101';
 $p=$parities[$barcode[0]];
 for($i=1;$i<=6;$i++)
 $code.=$codes[$p[$i-1]][$barcode[$i]];
 $code.='01010';
 for($i=7;$i<=12;$i++)
 $code.=$codes['C'][$barcode[$i]];
 $code.='101';
 //Draw bars
 for($i=0;$i<strlen($code);$i++)
 {
 if($code[$i]=='1')
 $this->Rect($x+$i*$w,$y,$w,$h,'F');
 }
 //Print text uder barcode
 $this->SetFont('Arial','',12);
 $this->Text($x,$y+$h+11/$this->k,substr($barcode,-$len));
}



########### FUNCION PARA CREAR MULTICELL SIN SALTO DE LINEA ############
function SetWidths($w)
{
//Set the array of column widths
$this->widths=$w;
}

function SetAligns($a)
{
//Set the array of column alignments
$this->aligns=$a;
}

function Row($data)
{
//Calculate the height of the row
$nb=0;
for($i=0;$i<count($data);$i++)
$nb=max($nb,$this->NbLines($this->widths[$i],$data[$i]));
$h=5*$nb;
//Issue a page break first if needed
$this->CheckPageBreak($h);
//Draw the cells of the row
for($i=0;$i<count($data);$i++)
{
$w=$this->widths[$i];
$a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
//Save the current position
$x=$this->GetX();
$y=$this->GetY();
//Draw the border
$this->Rect($x,$y,$w,$h);
//Print the text
$this->MultiCell($w,5,$data[$i],0,$a);
//Put the position to the right of the cell
$this->SetXY($x+$w,$y);
}
//Go to the next line
$this->Ln($h);
}

function RowFacture($data)
{
//Calculate the height of the row
$nb=0;
for($i=0;$i<count($data);$i++)
$nb=max($nb,$this->NbLines($this->widths[$i],$data[$i]));
$h=4*$nb;
//Issue a page break first if needed
$this->CheckPageBreak($h);
//Draw the cells of the row
for($i=0;$i<count($data);$i++)
{
$w=$this->widths[$i];
$a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'C';
//Save the current position
$x=$this->GetX();
$y=$this->GetY();
//Draw the border
$this->Rect($x,$y,$w,$h);
//Print the text
$this->MultiCell($w,4,$data[$i],0,$a);
//Put the position to the right of the cell
$this->SetXY($x+$w,$y);
}
//Go to the next line
$this->Ln($h);
}

function CheckPageBreak($h)
{
//If the height h would cause an overflow, add a new page immediately
if($this->GetY()+$h>$this->PageBreakTrigger)
$this->AddPage($this->CurOrientation);
}

function NbLines($w,$txt)
{
//Computes the number of lines a MultiCell of width w will take
$cw=&$this->CurrentFont['cw'];
if($w==0)
$w=$this->w-$this->rMargin-$this->x;
$wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
$s=str_replace("\r",'',$txt);
$nb=strlen($s);
if($nb>0 and $s[$nb-1]=="\n")
$nb--;
$sep=-1;
$i=0;
$j=0;
$l=0;
$nl=1;
while($i<$nb)
{
$c=$s[$i];
if($c=="\n")
{
$i++;
$sep=-1;
$j=$i;
$l=0;
$nl++;
continue;
}
if($c==' ')
$sep=$i;
$l+=$cw[$c];
if($l>$wmax)
{
if($sep==-1)
{
if($i==$j)
$i++;
}
else
$i=$sep+1;
$sep=-1;
$j=$i;
$l=0;
$nl++;
}
else
$i++;
}
return $nl;
}
########### FUNCION PARA CREAR MULTICELL SIN SALTO DE LINEA ############

function GetMultiCellHeight($w, $h, $txt, $border=null, $align='J') {
    // Calculate MultiCell with automatic or explicit line breaks height
    // $border is un-used, but I kept it in the parameters to keep the call
    //   to this function consistent with MultiCell()
    $cw = &$this->CurrentFont['cw'];
    if($w==0)
        $w = $this->w-$this->rMargin-$this->x;
    $wmax = ($w-2*$this->cMargin)*1000/$this->FontSize;
    $s = str_replace("\r",'',$txt);
    $nb = strlen($s);
    if($nb>0 && $s[$nb-1]=="\n")
        $nb--;
    $sep = -1;
    $i = 0;
    $j = 0;
    $l = 0;
    $ns = 0;
    $height = 0;
    while($i<$nb)
    {
        // Get next character
        $c = $s[$i];
        if($c=="\n")
        {
            // Explicit line break
            if($this->ws>0)
            {
                $this->ws = 0;
                $this->_out('0 Tw');
            }
            //Increase Height
            $height += $h;
            $i++;
            $sep = -1;
            $j = $i;
            $l = 0;
            $ns = 0;
            continue;
        }
        if($c==' ')
        {
            $sep = $i;
            $ls = $l;
            $ns++;
        }
        $l += $cw[$c];
        if($l>$wmax)
        {
            // Automatic line break
            if($sep==-1)
            {
                if($i==$j)
                    $i++;
                if($this->ws>0)
                {
                    $this->ws = 0;
                    $this->_out('0 Tw');
                }
                //Increase Height
                $height += $h;
            }
            else
            {
                if($align=='J')
                {
                    $this->ws = ($ns>1) ? ($wmax-$ls)/1000*$this->FontSize/($ns-1) : 0;
                    $this->_out(sprintf('%.3F Tw',$this->ws*$this->k));
                }
                //Increase Height
                $height += $h;
                $i = $sep+1;
            }
            $sep = -1;
            $j = $i;
            $l = 0;
            $ns = 0;
        }
        else
            $i++;
    }
    // Last chunk
    if($this->ws>0)
    {
        $this->ws = 0;
        $this->_out('0 Tw');
    }
    //Increase Height
    $height += $h;

    return $height;
}

function MultiAlignCell($w,$h,$text,$border=0,$ln=0,$align='L',$fill=false)
{
    // Store reset values for (x,y) positions
    $x = $this->GetX() + $w;
    $y = $this->GetY();

    // Make a call to FPDF's MultiCell
    $this->MultiCell($w,$h,$text,$border,$align,$fill);

    // Reset the line position to the right, like in Cell
    if( $ln==0 )
    {
        $this->SetXY($x,$y);
    }
}


function MultiCellText($w, $h, $txt, $border=0, $ln=0, $align='J', $fill=false)
{
    // Custom Tomaz Ahlin
    if($ln == 0) {
        $current_y = $this->GetY();
        $current_x = $this->GetX();
    }

    // Output text with automatic or explicit line breaks
    $cw = &$this->CurrentFont['cw'];
    if($w==0)
        $w = $this->w-$this->rMargin-$this->x;
    $wmax = ($w-2*$this->cMargin)*1000/$this->FontSize;
    $s = str_replace("\r",'',$txt);
    $nb = strlen($s);
    if($nb>0 && $s[$nb-1]=="\n")
        $nb--;
    $b = 0;
    if($border)
    {
        if($border==1)
        {
            $border = 'LTRB';
            $b = 'LRT';
            $b2 = 'LR';
        }
        else
        {
            $b2 = '';
            if(strpos($border,'L')!==false)
                $b2 .= 'L';
            if(strpos($border,'R')!==false)
                $b2 .= 'R';
            $b = (strpos($border,'T')!==false) ? $b2.'T' : $b2;
        }
    }
    $sep = -1;
    $i = 0;
    $j = 0;
    $l = 0;
    $ns = 0;
    $nl = 1;
    while($i<$nb)
    {
        // Get next character
        $c = $s[$i];
        if($c=="\n")
        {
            // Explicit line break
            if($this->ws>0)
            {
                $this->ws = 0;
                $this->_out('0 Tw');
            }
            $this->Cell($w,$h,substr($s,$j,$i-$j),$b,2,$align,$fill);
            $i++;
            $sep = -1;
            $j = $i;
            $l = 0;
            $ns = 0;
            $nl++;
            if($border && $nl==2)
                $b = $b2;
            continue;
        }
        if($c==' ')
        {
            $sep = $i;
            $ls = $l;
            $ns++;
        }
        $l += $cw[$c];
        if($l>$wmax)
        {
            // Automatic line break
            if($sep==-1)
            {
                if($i==$j)
                    $i++;
                if($this->ws>0)
                {
                    $this->ws = 0;
                    $this->_out('0 Tw');
                }
                $this->Cell($w,$h,substr($s,$j,$i-$j),$b,2,$align,$fill);
            }
            else
            {
                if($align=='J')
                {
                    $this->ws = ($ns>1) ?     ($wmax-$ls)/1000*$this->FontSize/($ns-1) : 0;
                    $this->_out(sprintf('%.3F Tw',$this->ws*$this->k));
                }
                $this->Cell($w,$h,substr($s,$j,$sep-$j),$b,2,$align,$fill);
                $i = $sep+1;
            }
            $sep = -1;
            $j = $i;
            $l = 0;
            $ns = 0;
            $nl++;
            if($border && $nl==2)
                $b = $b2;
        }
        else
            $i++;
    }
    // Last chunk
    if($this->ws>0)
    {
        $this->ws = 0;
        $this->_out('0 Tw');
    }
    if($border && strpos($border,'B')!==false)
        $b .= 'B';
    $this->Cell($w,$h,substr($s,$j,$i-$j),$b,2,$align,$fill);
    $this->x = $this->lMargin;

    // Custom Tomaz Ahlin
    if($ln == 0) {
        $this->SetXY($current_x + $w, $current_y);
    }
}


function RoundedRect($x, $y, $w, $h, $r, $style = '')
    {
        $k = $this->k;
        $hp = $this->h;
        if($style=='F')
            $op='f';
        elseif($style=='FD' || $style=='DF')
            $op='B';
        else
            $op='S';
        $MyArc = 4/3 * (sqrt(2) - 1);
        $this->_out(sprintf('%.2F %.2F m',($x+$r)*$k,($hp-$y)*$k ));
        $xc = $x+$w-$r ;
        $yc = $y+$r;
        $this->_out(sprintf('%.2F %.2F l', $xc*$k,($hp-$y)*$k ));

        $this->_Arc($xc + $r*$MyArc, $yc - $r, $xc + $r, $yc - $r*$MyArc, $xc + $r, $yc);
        $xc = $x+$w-$r ;
        $yc = $y+$h-$r;
        $this->_out(sprintf('%.2F %.2F l',($x+$w)*$k,($hp-$yc)*$k));
        $this->_Arc($xc + $r, $yc + $r*$MyArc, $xc + $r*$MyArc, $yc + $r, $xc, $yc + $r);
        $xc = $x+$r ;
        $yc = $y+$h-$r;
        $this->_out(sprintf('%.2F %.2F l',$xc*$k,($hp-($y+$h))*$k));
        $this->_Arc($xc - $r*$MyArc, $yc + $r, $xc - $r, $yc + $r*$MyArc, $xc - $r, $yc);
        $xc = $x+$r ;
        $yc = $y+$r;
        $this->_out(sprintf('%.2F %.2F l',($x)*$k,($hp-$yc)*$k ));
        $this->_Arc($xc - $r, $yc - $r*$MyArc, $xc - $r*$MyArc, $yc - $r, $xc, $yc - $r);
        $this->_out($op);
    }

    function _Arc($x1, $y1, $x2, $y2, $x3, $y3)
    {
        $h = $this->h;
        $this->_out(sprintf('%.2F %.2F %.2F %.2F %.2F %.2F c ', $x1*$this->k, ($h-$y1)*$this->k,
            $x2*$this->k, ($h-$y2)*$this->k, $x3*$this->k, ($h-$y3)*$this->k));
    }


    function CellFit($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=false, $link='', $scale=false, $force=true)

    {

        //Get string width

        $str_width=$this->GetStringWidth($txt);


        //Calculate ratio to fit cell

        if($w==0)

            $w = $this->w-$this->rMargin-$this->x;

        $ratio = ($w-$this->cMargin*2)/$str_width;


        $fit = ($ratio < 1 || ($ratio > 1 && $force));

        if ($fit)

        {

            if ($scale)

            {

                //Calculate horizontal scaling

                $horiz_scale=$ratio*100.0;

                //Set horizontal scaling

                $this->_out(sprintf('BT %.2F Tz ET',$horiz_scale));

            }

            else

            {

                //Calculate character spacing in points

                $char_space=($w-$this->cMargin*2-$str_width)/max($this->MBGetStringLength($txt)-1,1)*$this->k;

                //Set character spacing

                $this->_out(sprintf('BT %.2F Tc ET',$char_space));

            }

            //Override user alignment (since text will fill up cell)

            $align='';

        }


        //Pass on to Cell method

        $this->Cell($w,$h,$txt,$border,$ln,$align,$fill,$link);


        //Reset character spacing/horizontal scaling

        if ($fit)

            $this->_out('BT '.($scale ? '100 Tz' : '0 Tc').' ET');

    }


    function CellFitSpace($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=false, $link='')

    {

        $this->CellFit($w,$h,$txt,$border,$ln,$align,$fill,$link,false,false);

    }


    //Patch to also work with CJK double-byte text

    function MBGetStringLength($s)

    {

        if($this->CurrentFont['type']=='Type0')

        {

            $len = 0;

            $nbbytes = strlen($s);

            for ($i = 0; $i < $nbbytes; $i++)

            {

                if (ord($s[$i])<128)

                    $len++;

                else

                {

                    $len++;

                    $i++;

                }

            }

            return $len;

        }

        else

            return strlen($s);

    }

####################### FIN DEL CODIGO PARA AJUSTAR TEXTO EN CELDAS #####################

function saveFont()
    {

        $saved = array();

        $saved[ 'family' ] = $this->FontFamily;
        $saved[ 'style' ] = $this->FontStyle;
        $saved[ 'sizePt' ] = $this->FontSizePt;
        $saved[ 'size' ] = $this->FontSize;
        $saved[ 'curr' ] =& $this->CurrentFont;

        return $saved;

    }

    function restoreFont( $saved )
    {

        $this->FontFamily = $saved[ 'family' ];
        $this->FontStyle = $saved[ 'style' ];
        $this->FontSizePt = $saved[ 'sizePt' ];
        $this->FontSize = $saved[ 'size' ];
        $this->CurrentFont =& $saved[ 'curr' ];

        if( $this->page > 0)
            $this->_out( sprintf( 'BT /F%d %.2F Tf ET', $this->CurrentFont[ 'i' ], $this->FontSizePt ) );

    }

    function newFlowingBlock( $w, $h, $b = 0, $a = 'J', $f = 0 )
    {

        // cell width in points
        $this->flowingBlockAttr[ 'width' ] = $w * $this->k;

        // line height in user units
        $this->flowingBlockAttr[ 'height' ] = $h;

        $this->flowingBlockAttr[ 'lineCount' ] = 0;

        $this->flowingBlockAttr[ 'border' ] = $b;
        $this->flowingBlockAttr[ 'align' ] = $a;
        $this->flowingBlockAttr[ 'fill' ] = $f;

        $this->flowingBlockAttr[ 'font' ] = array();
        $this->flowingBlockAttr[ 'content' ] = array();
        $this->flowingBlockAttr[ 'contentWidth' ] = 0;

    }

    function finishFlowingBlock()
    {

        $maxWidth =& $this->flowingBlockAttr[ 'width' ];

        $lineHeight =& $this->flowingBlockAttr[ 'height' ];

        $border =& $this->flowingBlockAttr[ 'border' ];
        $align =& $this->flowingBlockAttr[ 'align' ];
        $fill =& $this->flowingBlockAttr[ 'fill' ];

        $content =& $this->flowingBlockAttr[ 'content' ];
        $font =& $this->flowingBlockAttr[ 'font' ];

        // set normal spacing
        $this->_out( sprintf( '%.3F Tw', 0 ) );

        // print out each chunk

        // the amount of space taken up so far in user units
        $usedWidth = 0;

        foreach ( $content as $k => $chunk )
        {

            $b = '';

            if ( is_int( strpos( $border, 'B' ) ) )
                $b .= 'B';

            if ( $k == 0 && is_int( strpos( $border, 'L' ) ) )
                $b .= 'L';

            if ( $k == count( $content ) - 1 && is_int( strpos( $border, 'R' ) ) )
                $b .= 'R';

            $this->restoreFont( $font[ $k ] );

            // if it's the last chunk of this line, move to the next line after
            if ( $k == count( $content ) - 1 )
                $this->Cell( ( $maxWidth / $this->k ) - $usedWidth + 2 * $this->cMargin, $lineHeight, $chunk, $b, 1, $align, $fill );
            else
                $this->Cell( $this->GetStringWidth( $chunk ), $lineHeight, $chunk, $b, 0, $align, $fill );

            $usedWidth += $this->GetStringWidth( $chunk );

        }

    }

    function WriteFlowingBlock( $s )
    {

        // width of all the content so far in points
        $contentWidth =& $this->flowingBlockAttr[ 'contentWidth' ];

        // cell width in points
        $maxWidth =& $this->flowingBlockAttr[ 'width' ];

        $lineCount =& $this->flowingBlockAttr[ 'lineCount' ];

        // line height in user units
        $lineHeight =& $this->flowingBlockAttr[ 'height' ];

        $border =& $this->flowingBlockAttr[ 'border' ];
        $align =& $this->flowingBlockAttr[ 'align' ];
        $fill =& $this->flowingBlockAttr[ 'fill' ];

        $content =& $this->flowingBlockAttr[ 'content' ];
        $font =& $this->flowingBlockAttr[ 'font' ];

        $font[] = $this->saveFont();
        $content[] = '';

        $currContent =& $content[ count( $content ) - 1 ];

        // where the line should be cutoff if it is to be justified
        $cutoffWidth = $contentWidth;

        // for every character in the string
        for ( $i = 0; $i < strlen( $s ); $i++ )
        {

            // extract the current character
            $c = $s[ $i ];

            // get the width of the character in points
            $cw = $this->CurrentFont[ 'cw' ][ $c ] * ( $this->FontSizePt / 1000 );

            if ( $c == ' ' )
            {

                $currContent .= ' ';
                $cutoffWidth = $contentWidth;

                $contentWidth += $cw;

                continue;

            }

            // try adding another char
            if ( $contentWidth + $cw > $maxWidth )
            {

                // won't fit, output what we have
                $lineCount++;

                // contains any content that didn't make it into this print
                $savedContent = '';
                $savedFont = array();

                // first, cut off and save any partial words at the end of the string
                $words = explode( ' ', $currContent );

                // if it looks like we didn't finish any words for this chunk
                if ( count( $words ) == 1 )
                {

                    // save and crop off the content currently on the stack
                    $savedContent = array_pop( $content );
                    $savedFont = array_pop( $font );

                    // trim any trailing spaces off the last bit of content
                    $currContent =& $content[ count( $content ) - 1 ];

                    $currContent = rtrim( $currContent );

                }

                // otherwise, we need to find which bit to cut off
                else
                {

                    $lastContent = '';

                    for ( $w = 0; $w < count( $words ) - 1; $w++)
                        $lastContent .= "{$words[ $w ]} ";

                    $savedContent = $words[ count( $words ) - 1 ];
                    $savedFont = $this->saveFont();

                    // replace the current content with the cropped version
                    $currContent = rtrim( $lastContent );

                }

                // update $contentWidth and $cutoffWidth since they changed with cropping
                $contentWidth = 0;

                foreach ( $content as $k => $chunk )
                {

                    $this->restoreFont( $font[ $k ] );

                    $contentWidth += $this->GetStringWidth( $chunk ) * $this->k;

                }

                $cutoffWidth = $contentWidth;

                // if it's justified, we need to find the char spacing
                if( $align == 'J' )
                {

                    // count how many spaces there are in the entire content string
                    $numSpaces = 0;

                    foreach ( $content as $chunk )
                        $numSpaces += substr_count( $chunk, ' ' );

                    // if there's more than one space, find word spacing in points
                    if ( $numSpaces > 0 )
                        $this->ws = ( $maxWidth - $cutoffWidth ) / $numSpaces;
                    else
                        $this->ws = 0;

                    $this->_out( sprintf( '%.3F Tw', $this->ws ) );

                }

                // otherwise, we want normal spacing
                else
                    $this->_out( sprintf( '%.3F Tw', 0 ) );

                // print out each chunk
                $usedWidth = 0;

                foreach ( $content as $k => $chunk )
                {

                    $this->restoreFont( $font[ $k ] );

                    $stringWidth = $this->GetStringWidth( $chunk ) + ( $this->ws * substr_count( $chunk, ' ' ) / $this->k );

                    // determine which borders should be used
                    $b = '';

                    if ( $lineCount == 1 && is_int( strpos( $border, 'T' ) ) )
                        $b .= 'T';

                    if ( $k == 0 && is_int( strpos( $border, 'L' ) ) )
                        $b .= 'L';

                    if ( $k == count( $content ) - 1 && is_int( strpos( $border, 'R' ) ) )
                        $b .= 'R';

                    // if it's the last chunk of this line, move to the next line after
                    if ( $k == count( $content ) - 1 )
                        $this->Cell( ( $maxWidth / $this->k ) - $usedWidth + 2 * $this->cMargin, $lineHeight, $chunk, $b, 1, $align, $fill );
                    else
                    {

                        $this->Cell( $stringWidth + 2 * $this->cMargin, $lineHeight, $chunk, $b, 0, $align, $fill );
                        $this->x -= 2 * $this->cMargin;

                    }

                    $usedWidth += $stringWidth;

                }

                // move on to the next line, reset variables, tack on saved content and current char
                $this->restoreFont( $savedFont );

                $font = array( $savedFont );
                $content = array( $savedContent . $s[ $i ] );

                $currContent =& $content[ 0 ];

                $contentWidth = $this->GetStringWidth( $currContent ) * $this->k;
                $cutoffWidth = $contentWidth;

            }

            // another character will fit, so add it on
            else
            {

                $contentWidth += $cw;
                $currContent .= $s[ $i ];

            }

        }

    }
    
    ########### FUNCION PARA CODIGO DE BARRA CON CODABAR ############
    function Codabar($xpos, $ypos, $code, $start='A', $end='A', $basewidth=0.12, $height=10) {
    $barChar = array (
        '0' => array (6.5, 4.4, 6.5, 3.4, 6.5, 7.3, 2.9),
        '1' => array (6.5, 4.4, 6.5, 8.4, 4.9, 4.3, 6.5),
        '2' => array (6.5, 4.0, 6.5, 9.4, 6.5, 3.0, 8.6),
        '3' => array (17.9, 24.3, 6.5, 6.4, 6.5, 3.4, 6.5),
        '4' => array (6.5, 2.4, 8.9, 6.4, 6.5, 4.3, 6.5),
        '5' => array (5.9,  2.4, 6.5, 6.4, 6.5, 4.3, 6.5),
        '6' => array (6.5, 8.3, 6.5, 6.4, 6.5, 6.4, 7.9),
        '7' => array (6.5, 8.3, 6.5, 2.4, 7.9, 6.4, 6.5),
        '8' => array (6.5, 8.3, 5.9, 10.4, 6.5, 6.4, 6.5),
        '9' => array (7.6, 5.0, 6.5, 8.4, 6.5, 3.0, 6.5),
        '$' => array (6.5, 5.0, 18.6, 24.4, 6.5, 10.0, 6.5),
        '-' => array (6.5, 5.0, 6.5, 4.4, 8.6, 10.0, 6.5),
        ':' => array (16.7, 9.3, 6.5, 9.3, 16.7, 9.3, 14.7),
        '/' => array (14.7, 9.3, 16.7, 9.3, 6.5, 9.3, 16.7),
        '.' => array (13.6, 10.1, 14.9, 10.1, 17.2, 10.1, 6.5),
        '+' => array (6.5, 10.1, 17.2, 10.1, 14.9, 10.1, 13.6),
        'A' => array (6.5, 8.0, 19.6, 19.4, 6.5, 16.1, 6.5),
        'T' => array (6.5, 8.0, 19.6, 19.4, 6.5, 16.1, 6.5),
        'B' => array (6.5, 16.1, 6.5, 19.4, 6.5, 8.0, 19.6),
        'N' => array (6.5, 16.1, 6.5, 19.4, 6.5, 8.0, 19.6),
        'C' => array (6.5, 8.0, 6.5, 19.4, 6.5, 16.1, 19.6),
        '*' => array (6.5, 8.0, 6.5, 19.4, 6.5, 16.1, 19.6),
        'D' => array (6.5, 8.0, 6.5, 19.4, 19.6, 16.1, 6.5),
        'E' => array (6.5, 8.0, 6.5, 19.4, 19.6, 16.1, 6.5),
    );
    $this->SetFont('Arial','',8.5);
    $this->SetTextColor(3, 3, 3);  // Establece el color del texto (en este caso es blanco 259)
    $this->Text($xpos, $ypos + $height + 2, $code);
    $this->SetFillColor(0);
    $code = strtoupper($start.$code.$end);
    for($i=0; $i<strlen($code); $i++){
        $char = $code[$i];
        if(!isset($barChar[$char])){
            $this->Error('Invalid character in barcode: '.$char);
        }
        $seq = $barChar[$char];
        for($bar=0; $bar<7; $bar++){
            $lineWidth = $basewidth*$seq[$bar]/4;
            if($bar % 2 == 0){
                $this->Rect($xpos, $ypos, $lineWidth, $height, 'F');
            }
            $xpos += $lineWidth;
        }
        $xpos += $basewidth*10.4/6.5;
    }
}

   function TextWithDirection($x, $y, $txt, $direction='R')
{
    if ($direction=='R')
        $s=sprintf('BT %.2F %.2F %.2F %.2F %.2F %.2F Tm (%s) Tj ET', 1, 0, 0, 1, $x*$this->k, ($this->h-$y)*$this->k, $this->_escape($txt));
    elseif ($direction=='L')
        $s=sprintf('BT %.2F %.2F %.2F %.2F %.2F %.2F Tm (%s) Tj ET', -1, 0, 0, -1, $x*$this->k, ($this->h-$y)*$this->k, $this->_escape($txt));
    elseif ($direction=='U')
        $s=sprintf('BT %.2F %.2F %.2F %.2F %.2F %.2F Tm (%s) Tj ET', 0, 1, -1, 0, $x*$this->k, ($this->h-$y)*$this->k, $this->_escape($txt));
    elseif ($direction=='D')
        $s=sprintf('BT %.2F %.2F %.2F %.2F %.2F %.2F Tm (%s) Tj ET', 0, -1, 1, 0, $x*$this->k, ($this->h-$y)*$this->k, $this->_escape($txt));
    else
        $s=sprintf('BT %.2F %.2F Td (%s) Tj ET', $x*$this->k, ($this->h-$y)*$this->k, $this->_escape($txt));
    if ($this->ColorFlag)
        $s='q '.$this->TextColor.' '.$s.' Q';
    $this->_out($s);
}

function TextWithRotation($x, $y, $txt, $txt_angle, $font_angle=0)
{
    $font_angle+=90+$txt_angle;
    $txt_angle*=M_PI/180;
    $font_angle*=M_PI/180;

    $txt_dx=cos($txt_angle);
    $txt_dy=sin($txt_angle);
    $font_dx=cos($font_angle);
    $font_dy=sin($font_angle);

    $s=sprintf('BT %.2F %.2F %.2F %.2F %.2F %.2F Tm (%s) Tj ET', $txt_dx, $txt_dy, $font_dx, $font_dy, $x*$this->k, ($this->h-$y)*$this->k, $this->_escape($txt));
    if ($this->ColorFlag)
        $s='q '.$this->TextColor.' '.$s.' Q';
    $this->_out($s);
}
 // FIN Class PDF
}
?>