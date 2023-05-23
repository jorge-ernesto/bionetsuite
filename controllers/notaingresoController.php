<?php

class notaingresoController extends Controller
{

	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		echo "Nota Ingreso";
	}
	
	public function imprimirNotaIngreso()
	{
		
		header('Access-Control-Allow-Origin: *');
		$input = json_decode(file_get_contents("php://input"), true);
		
		$objModel = $this->loadModel("notaingreso");
		$cabecera_NI = $objModel->getNotaIngresoCabecera(intval($input["dato"]['id']));
		
		$this->getLibrary('fpdf/fpdf');

	    //$pdf = new FPDF('P', 'mm', 'A4');
		
		$pdf = new PDF_MC_Table();

		$pdf->AddPage();
		$pdf->SetTitle("NOTA DE INGRESO");
		
		//Establecemos los márgenes izquierda, arriba y derecha:
		$pdf->SetMargins(3, 0 , 3);
		
		//Establecemos el margen inferior:
		$pdf->SetAutoPageBreak(true,5);
		
		$pdf->SetFont('Arial','B',10);
		
		$pdf->Ln(0.5);
		$pdf->Cell(101,4,"LABORATORIOS BIOMONT S.A.",0,0,'L',false);
		$pdf->Cell(101,4,"F-AL.003.02",0,1,'R',false);
		
		$pdf->SetFont('Arial','',8);
		
		$pdf->Ln(1);
		$pdf->Cell(101,4,utf8_decode("INGRESOS AL ALMACÉN"),0,0,'L',false);
		$pdf->Cell(101,4,"NRO. DOCUMENTO: ".$cabecera_NI[0]['NroDocumento'],0,1,'R',false);
		
		$pdf->SetFont('Arial','',7);
		$pdf->Ln(3);
		$pdf->Cell(25,4,"PROVEEDOR",0,0,'L',false);
		$pdf->Cell(1);
		$pdf->Cell(75,4,": ".$cabecera_NI[0]['Proveedor'],0,0,'L',false);
		$pdf->Cell(30);
		$pdf->Cell(15,4,"FECHA",0,0,'L',false);
		$pdf->Cell(1);
		$pdf->Cell(55,4,": ".$cabecera_NI[0]['Fecha'],0,1,'L',false);

		$pdf->Cell(25,4,"DUA",0,0,'L',false);
		$pdf->Cell(1);
		$pdf->Cell(75,4,": ".$cabecera_NI[0]['dua'],0,0,'L',false);
		$pdf->Cell(30);
		$pdf->Cell(15,4,"O/C",0,0,'L',false);
		$pdf->Cell(1);
		$pdf->Cell(55,4,": ".$cabecera_NI[0]['NroOrdenCompra'],0,1,'L',false);
		
		$pdf->Cell(25,4,"OBSERVACIONES",0,0,'L',false);
		$pdf->Cell(1);
		$pdf->Cell(75,4,": ".$cabecera_NI[0]['Nota'],0,0,'L',false);
		$pdf->Cell(30);
		$pdf->Cell(15,4,"USUARIO",0,0,'L',false);
		$pdf->Cell(1);
		$pdf->Cell(55,4,": ".$cabecera_NI[0]['usuario'],0,1,'L',false);
		
		$pdf->Ln(2);
		$pdf->SetFont('Arial','B',7);
		$pdf->Multicell(150,4,
			$pdf->Cell(5,4,"ID",1,0,'C',false),
			$pdf->Cell(18,4,"CODIGO",1,0,'L',false),
			$pdf->Cell(47,4,"DESCRIPCION",1,0,'L',false),
			$pdf->Cell(10,4,"CANT",1,0,'C',false),
			$pdf->Cell(9,4,"UND",1,0,'C',false),
			$pdf->Cell(16,4,"LOTE",1,0,'C',false),
			$pdf->Cell(15,4,"F.VEN",1,0,'C',false),
			$pdf->Cell(41,4,"ALMACEN DESTINO",1,0,'C',false),
			$pdf->Cell(41,4,"NOTA",1,0,'L',false),
		1,'J');
		
		$pdf->SetFont('Arial','',7);
		$pdf->SetWidths([5,18,47,10,9,16,15,41,41]);
		$pdf->setLineHeight(4);
		
		$detalle_NI = $objModel->getNotaIngresoDetalle(intval($input["dato"]['id']));
		
		$suma=0;
		$i=1;
		foreach($detalle_NI as $item){
			
			if($item['FechaVencimiento']=="" || $item['FechaVencimiento']==null || $item['FechaVencimiento']=="01/01/1970"){
				$fecha_ven = "";
			}else{
				$fecha_ven = $item['FechaVencimiento'];
			}
			
			$suma += $item['cantidadDetalle'];
			$pdf->Row([$i,$item['codigo'],$item['descripcion2'],$item['cantidadDetalle'],$item['unidad'],explode("#",$item['SerieLote'])[0],$fecha_ven,$item['almacenDestino'],$item['observacion']]);
			$i++;
		}
		
		/*$data = array(
			array("Codigo","Descripcion",140,"UND","45654654","25/10/2022","Almacen Destino","Observacion"),
			array("Codigo","Descripcion",140,"UND","45654654","25/10/2022","Almacen Destino","Observacion"),
			array("Codigo","Descripcion",140,"UND","45654654","25/10/2022","Almacen Destino","Observacion"),
			array("Codigo","Descripcion",140,"UND","45654654","25/10/2022","Almacen Destino","Observacion"),
			array("Codigo","Descripcion",140,"UND","45654654","25/10/2022","Almacen Destino","Observacion"),
			array("Codigo","Descripcion",140,"UND","45654654","25/10/2022","Almacen Destino","Observacion"),
			
		);
		
		$j=10;
		foreach($data as $item){
			$pdf->Row([$j,$item[0],$item[1],$item[2],$item[3],$item[4],$item[5],$item[6],$item[7]]);
			$j++;
		}*/
		
		$pdf->Ln(2);
		$pdf->Cell(202,4,"CANTIDAD TOTAL: ".$suma,0,1,'R',false);

		/*$pdf->Ln(2);
		$pdf->Cell(5,2,"Posicion eje Y: ".$pdf->GetY(),0,1,'L');
		$pdf->Ln(3);*/
		
		if(($pdf->GetPageHeight()/2)>$pdf->GetY()){
			/*$pdf->Cell(5,2,"Aun no pasa de la mitad de la pagina",0,1,'L');*/
			$pdf->SetDrawColor(225,225,225);
			$pdf->Line(10,148,195,148);
			$pdf->SetY(152);
			
			$pdf->SetDrawColor(0,0,0);
			$pdf->SetFont('Arial','B',10);
		
			$pdf->Ln(4);
			$pdf->Cell(101,4,"LABORATORIOS BIOMONT S.A.",0,0,'L',false);
			$pdf->Cell(101,4,"F-AL.003.02",0,1,'R',false);
			
			$pdf->SetFont('Arial','',8);
			
			$pdf->Ln(1);
			$pdf->Cell(101,4,utf8_decode("INGRESOS AL ALMACÉN"),0,0,'L',false);
			$pdf->Cell(101,4,"NRO. DOCUMENTO: ".$cabecera_NI[0]['NroDocumento'],0,1,'R',false);
			
			$pdf->SetFont('Arial','',7);
			$pdf->Ln(3);
			$pdf->Cell(25,4,"PROVEEDOR",0,0,'L',false);
			$pdf->Cell(1);
			$pdf->Cell(75,4,": ".$cabecera_NI[0]['Proveedor'],0,0,'L',false);
			$pdf->Cell(30);
			$pdf->Cell(15,4,"FECHA",0,0,'L',false);
			$pdf->Cell(1);
			$pdf->Cell(55,4,": ".$cabecera_NI[0]['Fecha'],0,1,'L',false);

			$pdf->Cell(25,4,"DUA",0,0,'L',false);
			$pdf->Cell(1);
			$pdf->Cell(75,4,": ".$cabecera_NI[0]['dua'],0,0,'L',false);
			$pdf->Cell(30);
			$pdf->Cell(15,4,"O/C",0,0,'L',false);
			$pdf->Cell(1);
			$pdf->Cell(55,4,": ".$cabecera_NI[0]['NroOrdenCompra'],0,1,'L',false);
			
			$pdf->Cell(25,4,"OBSERVACIONES",0,0,'L',false);
			$pdf->Cell(1);
			$pdf->Cell(75,4,": ".$cabecera_NI[0]['Nota'],0,0,'L',false);
			$pdf->Cell(30);
			$pdf->Cell(15,4,"USUARIO",0,0,'L',false);
			$pdf->Cell(1);
			$pdf->Cell(55,4,": ".$cabecera_NI[0]['usuario'],0,1,'L',false);
			
			$pdf->Ln(2);
			$pdf->SetFont('Arial','B',7);
			$pdf->Multicell(150,4,
				$pdf->Cell(5,4,"ID",1,0,'C',false),
				$pdf->Cell(18,4,"CODIGO",1,0,'L',false),
				$pdf->Cell(47,4,"DESCRIPCION",1,0,'L',false),
				$pdf->Cell(10,4,"CANT",1,0,'C',false),
				$pdf->Cell(9,4,"UND",1,0,'C',false),
				$pdf->Cell(16,4,"LOTE",1,0,'C',false),
				$pdf->Cell(15,4,"F.VEN",1,0,'C',false),
				$pdf->Cell(41,4,"ALMACEN DESTINO",1,0,'C',false),
				$pdf->Cell(41,4,"NOTA",1,0,'L',false),
			1,'J');
			
			$pdf->SetFont('Arial','',7);
			$pdf->SetWidths([5,18,47,10,9,16,15,41,41]);
			$pdf->setLineHeight(4);
			
			$suma=0;
			$i=1;
			foreach($detalle_NI as $item){
				
				if($item['FechaVencimiento']=="" || $item['FechaVencimiento']==null || $item['FechaVencimiento']=="01/01/1970"){
					$fecha_ven = "";
				}else{
					$fecha_ven = $item['FechaVencimiento'];
				}
				
				$suma += $item['cantidadDetalle'];
				$pdf->Row([$i,$item['codigo'],$item['descripcion2'],$item['cantidadDetalle'],$item['unidad'],explode("#",$item['SerieLote'])[0],$fecha_ven,$item['almacenDestino'],$item['observacion']]);
				$i++;
			}

			/*$j=10;
			foreach($data as $item){
				$pdf->Row([$j,$item[0],$item[1],$item[2],$item[3],$item[4],$item[5],$item[6],$item[7]]);
				$j++;
			}*/
			
			$pdf->Ln(2);
			$pdf->Cell(202,4,"CANTIDAD TOTAL: ".$suma,0,1,'R',false);
			
		}else{
			/*$pdf->Cell(5,2,utf8_decode("Se pasó de la mitad de la pagina"),0,1,'L');*/
			
			$pdf->AddPage();
			
			$pdf->SetTitle("NOTA DE INGRESO");
		
			//Establecemos los márgenes izquierda, arriba y derecha:
			$pdf->SetMargins(3, 0 , 3);
			
			//Establecemos el margen inferior:
			$pdf->SetAutoPageBreak(true,5);
		
			
			$pdf->SetFont('Arial','B',10);
		
			$pdf->Ln(10);
			$pdf->Cell(101,4,"LABORATORIOS BIOMONT S.A.",0,0,'L',false);
			$pdf->Cell(101,4,"F-AL.003.02",0,1,'R',false);
			
			$pdf->SetFont('Arial','',8);
			
			$pdf->Ln(1);
			$pdf->Cell(101,4,utf8_decode("INGRESOS AL ALMACÉN"),0,0,'L',false);
			$pdf->Cell(101,4,"NRO. DOCUMENTO: ".$cabecera_NI[0]['NroDocumento'],0,1,'R',false);
			
			$pdf->SetFont('Arial','',7);
			$pdf->Ln(3);
			$pdf->Cell(25,4,"PROVEEDOR",0,0,'L',false);
			$pdf->Cell(1);
			$pdf->Cell(75,4,": ".$cabecera_NI[0]['Proveedor'],0,0,'L',false);
			$pdf->Cell(30);
			$pdf->Cell(15,4,"FECHA",0,0,'L',false);
			$pdf->Cell(1);
			$pdf->Cell(55,4,": ".$cabecera_NI[0]['Fecha'],0,1,'L',false);

			$pdf->Cell(25,4,"DUA",0,0,'L',false);
			$pdf->Cell(1);
			$pdf->Cell(75,4,": ".$cabecera_NI[0]['dua'],0,0,'L',false);
			$pdf->Cell(30);
			$pdf->Cell(15,4,"O/C",0,0,'L',false);
			$pdf->Cell(1);
			$pdf->Cell(55,4,": ".$cabecera_NI[0]['NroOrdenCompra'],0,1,'L',false);
			
			$pdf->Cell(25,4,"OBSERVACIONES",0,0,'L',false);
			$pdf->Cell(1);
			$pdf->Cell(75,4,": ".$cabecera_NI[0]['Nota'],0,0,'L',false);
			$pdf->Cell(30);
			$pdf->Cell(15,4,"USUARIO",0,0,'L',false);
			$pdf->Cell(1);
			$pdf->Cell(55,4,": ".$cabecera_NI[0]['usuario'],0,1,'L',false);
			
			$pdf->Ln(2);
			$pdf->SetFont('Arial','B',7);
			$pdf->Multicell(150,4,
				$pdf->Cell(5,4,"ID",1,0,'C',false),
				$pdf->Cell(18,4,"CODIGO",1,0,'L',false),
				$pdf->Cell(47,4,"DESCRIPCION",1,0,'L',false),
				$pdf->Cell(10,4,"CANT",1,0,'C',false),
				$pdf->Cell(9,4,"UND",1,0,'C',false),
				$pdf->Cell(16,4,"LOTE",1,0,'C',false),
				$pdf->Cell(15,4,"F.VEN",1,0,'C',false),
				$pdf->Cell(41,4,"ALMACEN DESTINO",1,0,'C',false),
				$pdf->Cell(41,4,"NOTA",1,0,'L',false),
			1,'J');
			
			$pdf->SetFont('Arial','',7);
			$pdf->SetWidths([5,18,47,10,9,16,15,41,41]);
			$pdf->setLineHeight(4);
			
			$suma=0;
			$i=1;
			foreach($detalle_NI as $item){
				
				if($item['FechaVencimiento']=="" || $item['FechaVencimiento']==null || $item['FechaVencimiento']=="01/01/1970"){
					$fecha_ven = "";
				}else{
					$fecha_ven = $item['FechaVencimiento'];
				}
				
				$suma += $item['cantidadDetalle'];
				$pdf->Row([$i,$item['codigo'],$item['descripcion2'],$item['cantidadDetalle'],$item['unidad'],explode("#",$item['SerieLote'])[0],$fecha_ven,$item['almacenDestino'],$item['observacion']]);
				$i++;
			}		

			/*$j=10;
			foreach($data as $item){
				$pdf->Row([$j,$item[0],$item[1],$item[2],$item[3],$item[4],$item[5],$item[6],$item[7]]);
				$j++;
			}*/
			
			$pdf->Ln(2);
			$pdf->Cell(202,4,"CANTIDAD TOTAL: ".$suma,0,1,'R',false);
		}
		
		/*$pdf->Ln(3);
		$pdf->Cell(15,2,"Medida mitad de pagina: ".$pdf->GetPageHeight()/2,0,1,'L');*/

		date_default_timezone_set('America/Lima');
		
		$fecha = date("YmdHis",time());
		
		$archivo= "downloads/NI_".$fecha.".pdf";

		$pdf->Output('F',$archivo);
		
		if (file_exists($archivo)) {
			$msg="ok";
			$file=$archivo;
		} else {
			$msg="no";
			$file="";
		}
		
		header('Content-type: application/json; charset=utf-8');
		echo json_encode([
			"msg"=>$msg,
			"file"=>$file,
		]);
		
	}
	
	/*public function imprimirNotaIngreso__()
	{
		header('Access-Control-Allow-Origin: *');
		$input = json_decode(file_get_contents("php://input"), true);
		
		$objModel = $this->loadModel("notaingreso");
		$cabecera_NI = $objModel->getNotaIngresoCabecera(intval($input["dato"]['id']));
		
		$this->getLibrary('mpdf/mpdf');
		//izquierda, derecha, arriba, abajo
		$mpdf = new mPDF('utf-8', 'A4', '', '', 5, 5, 10, 5, 10, 4);
		
		$mpdf->SetTitle('NOTA DE INGRESO');
		
		$mpdf->SetDefaultFont("Arial");
		
		$mpdf->WriteHTML("
			<table width='100%'>
				<tr>
					<td align='left' style='font-size:13px;'><b>LABORATORIOS BIOMONT S.A.</b></td>
					<td align='right' style='font-size:13px;'><b>F-AL.003.02</b></td>
				</tr>
				<tr>
					<td align='left' style='font-size:11px;'>INGRESOS AL ALMACÉN</td>
					<td align='right' style='font-size:11px;'><b>NRO. DOCUMENTO: </b>".$cabecera_NI[0]['NroDocumento']."</td>
				</tr>
			</table>
		");
		
		$mpdf->Ln(3);
		
		$mpdf->WriteHTML("
			<table width='100%'>
				<tr>
					<td align='left' style='width:12%;font-size:9px;padding-top:5px;'><b>PROVEEDOR</b></td>
					<td align='left' style='width:2%;font-size:9px;padding-top:5px;'>:</td>
					<td align='left' style='width:48%;font-size:9px;padding-top:5px;'>".$cabecera_NI[0]['Proveedor']."</td>
					<td align='left' style='width:7%%;font-size:9px;padding-top:5px;'><b>FECHA</b></td>
					<td align='left' style='width:2%;font-size:9px;padding-top:5px;'>:</td>
					<td align='left' style='width:29%;font-size:9px;padding-top:5px;'>".$cabecera_NI[0]['Fecha']."</td>
				</tr>
				<tr>
					<td align='left' style='width:12%;font-size:9px;padding-top:5px;'><b>DUA</b></td>
					<td align='left' style='width:2%;font-size:9px;padding-top:5px;'>:</td>
					<td align='left' style='width:48%;font-size:9px;padding-top:5px;'>".$cabecera_NI[0]['dua']."</td>
					<td align='left' style='width:7%%;font-size:9px;padding-top:5px;'><b>O/C</b></td>
					<td align='left' style='width:2%;font-size:9px;padding-top:5px;'>:</td>
					<td align='left' style='width:29%;font-size:9px;padding-top:5px;'>".$cabecera_NI[0]['NroOrdenCompra']."</td>
				</tr>
				<tr>
					<td align='left' style='width:12%;font-size:9px;padding-top:5px;'><b>OBSERVACIONES</b></td>
					<td align='left' style='width:2%;font-size:9px;padding-top:5px;'>:</td>
					<td align='left' style='width:48%;font-size:9px;padding-top:5px;'>".$cabecera_NI[0]['Nota']."</td>
					<td align='left' style='width:7%%;font-size:9px;padding-top:5px;'><b>USUARIO</b></td>
					<td align='left' style='width:2%;font-size:9px;padding-top:5px;'>:</td>
					<td align='left' style='width:29%;font-size:9px;padding-top:5px;'>".$cabecera_NI[0]['usuario']."</td>
				</tr>
			</table>
		");
		
		//$mpdf->Ln(3);
		//
		//$mpdf->WriteHTML("
		//	<table width='100%' style='border:1px solid black; border-collapse:collapse;'>
		//		<tr>
		//			<td align='left' style='width:3%;font-size:10px;border:1px solid black;padding:5px;'><b>ID</b></td>
		//			<td align='left' style='width:10%;font-size:10px;border:1px solid black;padding:5px;'><b>CODIGO</b></td>
		//			<td align='left' style='width:30%;font-size:10px;border:1px solid black;padding:5px;'><b>DESCRIPCION</b></td>
		//			<td align='center' style='width:7%;font-size:10px;border:1px solid black;padding:5px;'><b>CANTIDAD</b></td>
		//			<td align='center' style='width:5%;font-size:10px;border:1px solid black;padding:5px;'><b>UND</b></td>
		//			<td align='center' style='width:10%;font-size:10px;border:1px solid black;padding:5px;'><b>LOTE</b></td>
		//			<td align='center' style='width:10%;font-size:10px;border:1px solid black;padding:5px;'><b>F.VEN</b></td>
		//			<td align='left' style='width:25%;font-size:10px;border:1px solid black;padding:5px;'><b>NOTA</b></td>
		//		</tr>
		//");
		//
		//$detalle_NI = $objModel->getNotaIngresoDetalle(intval($input["dato"]['id']));
		//
		//$suma=0;
		//$c=1;
		//$filas="";
		//foreach($detalle_NI as $dat){
		//	$suma += $dat['cantidadDetalle'];
		//	
		//	if($dat['FechaVencimiento']=="" || $dat['FechaVencimiento']==null || $dat['FechaVencimiento']=="01/01/1970"){
		//		$fecha_ven = "";
		//	}else{
		//		$fecha_ven = $dat['FechaVencimiento'];
		//	}
		//	
		//	$filas .= "<tr>
		//				<td align='left' style='width:3%;font-size:10px;border:1px solid black;padding:5px;'>".$c."</td>
		//				<td align='left' style='width:10%;font-size:10px;border:1px solid black;padding:5px;'>".$dat['codigo']."</td>
		//				<td align='left' style='width:30%;font-size:10px;border:1px solid black;padding:5px;'>".$dat['descripcion2']."</td>
		//				<td align='center' style='width:7%;font-size:10px;border:1px solid black;padding:5px;'>".$dat['cantidadDetalle']."</td>
		//				<td align='center' style='width:5%;font-size:10px;border:1px solid black;padding:5px;'>".$dat['unidad']."</td>
		//				<td align='center' style='width:10%;font-size:10px;border:1px solid black;padding:5px;'>".explode("#",$dat['SerieLote'])[0]."</td>
		//				<td align='center' style='width:10%;font-size:10px;border:1px solid black;padding:5px;'>".$fecha_ven."</td>
		//				<td align='left' style='width:25%;font-size:10px;border:1px solid black;padding:5px;'>".$dat['observacion']."</td>
		//			</tr>";
		//	$c++;
		//}
		//
		//$filas .= "</table>";
		
		$mpdf->Ln(3);
		
		$mpdf->WriteHTML("
			<table width='100%' style='border:1px solid black; border-collapse:collapse;'>
				<tr>
					<td align='left' style='width:3%;font-size:9px;border:1px solid black;padding:5px;'><b>ID</b></td>
					<td align='left' style='width:10%;font-size:9px;border:1px solid black;padding:5px;'><b>CODIGO</b></td>
					<td align='left' style='width:25%;font-size:9px;border:1px solid black;padding:5px;'><b>DESCRIPCION</b></td>
					<td align='center' style='width:7%;font-size:9px;border:1px solid black;padding:5px;'><b>CANTIDAD</b></td>
					<td align='center' style='width:5%;font-size:9px;border:1px solid black;padding:5px;'><b>UND</b></td>
					<td align='center' style='width:7%;font-size:9px;border:1px solid black;padding:5px;'><b>LOTE</b></td>
					<td align='center' style='width:10%;font-size:9px;border:1px solid black;padding:5px;'><b>F.VEN</b></td>
					<td align='left' style='width:13%;font-size:9px;border:1px solid black;padding:5px;'><b>ALMACÉN DESTINO</b></td>
					<td align='left' style='width:20%;font-size:9px;border:1px solid black;padding:5px;'><b>NOTA</b></td>
				</tr>
		");
		
		$detalle_NI = $objModel->getNotaIngresoDetalle(intval($input["dato"]['id']));
		
		$suma=0;
		$c=1;
		$filas="";
		foreach($detalle_NI as $dat){
			$suma += $dat['cantidadDetalle'];
			
			if($dat['FechaVencimiento']=="" || $dat['FechaVencimiento']==null || $dat['FechaVencimiento']=="01/01/1970"){
				$fecha_ven = "";
			}else{
				$fecha_ven = $dat['FechaVencimiento'];
			}
			
			//if($dat['unidad']=='M³') {
			//	$unidad = "M&sup3";
			//}else{
				$unidad = $dat['unidad'];
			//}
			
			$filas .= "<tr>
						<td align='left' style='width:3%;font-size:9px;border:1px solid black;padding:5px;'>".$c."</td>
						<td align='left' style='width:10%;font-size:9px;border:1px solid black;padding:5px;'>".$dat['codigo']."</td>
						<td align='left' style='width:25%;font-size:9px;border:1px solid black;padding:5px;'>".$dat['descripcion2']."</td>
						<td align='center' style='width:7%;font-size:9px;border:1px solid black;padding:5px;'>".$dat['cantidadDetalle']."</td>
						<td align='center' style='width:5%;font-size:9px;border:1px solid black;padding:5px;'>".$unidad."</td>
						<td align='center' style='width:7%;font-size:9px;border:1px solid black;padding:5px;'>".explode("#",$dat['SerieLote'])[0]."</td>
						<td align='center' style='width:10%;font-size:9px;border:1px solid black;padding:5px;'>".$fecha_ven."</td>
						<td align='left' style='width:13%;font-size:9px;border:1px solid black;padding:5px;'>".$dat['almacenDestino']."</td>
						<td align='left' style='width:20%;font-size:9px;border:1px solid black;padding:5px;'>".$dat['observacion']."</td>
					</tr>";
			$c++;
			
		}
		
		$filas .= "</table>";
		
		$mpdf->WriteHTML($filas);

		$mpdf->Ln(3);
		
		$mpdf->WriteHTML("
			<table width='100%'>
				<tr>
					<td align='right' style='width:100%;font-size:10px;'><b>CANTIDAD TOTAL: </b>".$suma."</td>
				</tr>
			</table>
		");
		
		date_default_timezone_set('America/Lima');
		
		$fecha = date("YmdHis",time());
		
		$archivo= "downloads/NI_".$fecha.".pdf";

		$mpdf->Output($archivo, 'F');  //D: descarga directa, I: visualizacion, F: descarga en ruta especifica
		
		if (file_exists($archivo)) {
			$msg="ok";
			$file=$archivo;
		} else {
			$msg="no";
			$file="";
		}
		
		header('Content-type: application/json; charset=utf-8');
		echo json_encode([
			"msg"=>$msg,
			"file"=>$file,
		]);
		
	}*/

}


include './libs/fpdf/fpdf.php';

class PDF_MC_Table extends FPDF 
{

	// variable to store widths and aligns of cells, and line height
	var $widths;
	var $aligns;
	var $lineHeight;

	//Set the array of column widths
	function SetWidths($w){
		$this->widths=$w;
	}

	//Set the array of column alignments
	function SetAligns($a){
		$this->aligns=$a;
	}

	//Set line height
	function SetLineHeight($h){
		$this->lineHeight=$h;
	}

	//Calculate the height of the row
	function Row($data)
	{
		// number of line
		$nb=0;

		// loop each data to find out greatest line number in a row.
		for($i=0;$i<count($data);$i++){
			// NbLines will calculate how many lines needed to display text wrapped in specified width.
			// then max function will compare the result with current $nb. Returning the greatest one. And reassign the $nb.
			$nb=max($nb,$this->NbLines($this->widths[$i],$data[$i]));
		}
		
		//multiply number of line with line height. This will be the height of current row
		$h=$this->lineHeight * $nb;

		//Issue a page break first if needed
		$this->CheckPageBreak($h);

		//Draw the cells of current row
		for($i=0;$i<count($data);$i++)
		{
			// width of the current col
			$w=$this->widths[$i];
			// alignment of the current col. if unset, make it left.
			$a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
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
		//calculate the number of lines a MultiCell of width w will take
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
}