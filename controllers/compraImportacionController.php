<?php

class compraImportacionController extends Controller
{

	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		header('Access-Control-Allow-Origin: *');
		header('Content-type: application/json; charset=utf-8');
		echo json_encode([
			"con"=>"ok",
		]);
	}
	
	public function downloadReportePolizaImportacion()
	{
		$input = json_decode(file_get_contents("php://input"), true);
		
		$objModel = $this->loadModel("compraImportacion");
		$cabecera_PI = $objModel->getCabeceraPolizaImportacion(intval($input["dato"]['id']));
		$detalle_PI = $objModel->getDetallePolizaImportacion($cabecera_PI[0]['nropoliza']);
		
		$this->getLibrary('mpdf/mpdf');
		
		//$mpdf = new mPDF('utf-8', 'A4', '', '', 7, 7, 65, 90, 10, 4);
		$mpdf = new mPDF('utf-8', 'A4-L', '', '', 7, 7, 7, 7, 4, 4);

		$mpdf->SetTitle('POLIZA DE IMPORTACION');
		
		$mpdf->SetDefaultFont("Arial");
		
		date_default_timezone_set('America/Lima');
		
		$titulo_poliza = array("SOLES","DOLARES");
		$tipo_cambio = array(1,$cabecera_PI[0]['tipcambiorecep']);
		$titulo_detalle_gasto = array("TOTAL S/.","TOTAL U$$");
		$titulo_detalle_distribucion = array("COST. S/.","COST. U$$");
		
		
		for ($i=0; $i<2; $i++) {
			$this->imprimir_poliza(
				$i,
				$mpdf,
				$cabecera_PI,
				$detalle_PI,
				$input["dato"]['ruc_proveedor'],
				$titulo_poliza[$i],
				$tipo_cambio[$i],
				$titulo_detalle_gasto[$i],
				$titulo_detalle_distribucion[$i]
			);	
			
			if($i==0) $mpdf->addPage();
		}

		$fecha = date("YmdHis",time());
		
		$archivo= "downloads/PI_".$fecha.".pdf";

		$mpdf->Output($archivo, 'F');  //D: descarga directa, I: visualizacion, F: descarga en ruta especifica
		
		if (file_exists($archivo)) {
			$msg="ok";
			$file=$archivo;
		} else {
			$msg="no";
			$file="";
		}
		
		header('Access-Control-Allow-Origin: *');
		header('Content-type: application/json; charset=utf-8');
		echo json_encode([
			"msg"=>$msg,
			"file"=>$file,
		]);
		
	}
	
	public function imprimir_poliza($iterador,$obj,$cab,$det,$ruc_proveedor,$titulo_poliza,$tipo_cambio,$titulo_detalle_gasto,$titulo_detalle_distribucion)
	{
		$iterador = $iterador + 1;
		
		$obj->WriteHTML("
			<table width='100%'>
				<tr style='height:200px;'>
					<td style='width:45%;text-align:center;'>
						<p style='font-size:15px;text-align:center;'><b>LABORATORIOS BIOMONT S.A.</b></p>
						<p style='font-size:10px;text-align:center;'>Av. Industrial N° 184 - La Aurora - Ate - Lima - Lima - Perú</p>
						<p style='font-size:10px;text-align:center;'>Telfs.: (00 511) 206-2700 * 206-2701 * 2062702</p>
						<p style='font-size:10px;text-align:center;'>Email: laboratorios@biomont.com.pe Web: www.biomont.com.pe</p>
					</td>
					<td style='width:40%;text-align:center;'>
					</td>
					<td style='width:15%;text-align:left;'>
						<span style='font-size:10px;'>&nbsp;</span><p style='font-size:3px;'>&nbsp;</p>
						<span style='font-size:10px;'>Fecha&nbsp;&nbsp;: ".date("d/m/Y",time())."</span><p style='font-size:3px;'>&nbsp;</p>
						<span style='font-size:10px;'>Hora&nbsp;&nbsp;&nbsp;&nbsp;: ".date("H:i:s",time())."</span><p style='font-size:3px;'>&nbsp;</p>
						<span style='font-size:10px;'>Página&nbsp;: ".$iterador." / 2</span>
					</td>
				</tr>
			</table>
			<br>
			<table width='100%'>
				<tr>
					<td style='text-align:center;'>
						<span style='font-size:15px;'><b>POLIZA DE IMPORTACION</b></span>
					</td>
				</tr>
				<tr>
					<td style='text-align:center;'>
						<span style='font-size:15px;'><b>EXPRESADO EN: ".$titulo_poliza."</b></span>
					</td>
				</tr>
			</table>
			<br>
			<div style='border:1px solid black;border-radius:6px;padding:2px;'>
				<table style='width:100%;'>
					<tr>
						<td style='padding:1px;width:9%;text-align:left;font-size:10px;'><b>NRO. POLIZA</b></td>
						<td style='padding:1px;width:2%;text-align:left;font-size:10px;'>:</td>
						<td colspan='10' style='padding:1px;width:89%;text-align:left;font-size:10px;'>".$cab[0]['nropoliza']."</td>
					</tr>
					<tr>
						<td style='padding:1px;width:9%;text-align:left;font-size:10px;'><b>PROVEEDOR</b></td>
						<td style='padding:1px;width:2%;text-align:left;font-size:10px;'>:</td>
						<td colspan='7' style='padding:1px;width:67%;text-align:left;font-size:10px;'>".$cab[0]['proveedor']."</td>
						<td style='padding:1px;width:5%;text-align:left;font-size:10px;'><b>R.U.C</b> </td>
						<td style='padding:1px;width:2%;text-align:left;font-size:10px;'>:</td>
						<td style='padding:1px;width:15%;text-align:left;font-size:10px;'>".$ruc_proveedor."</td>
					</tr>
					<tr>
						<td style='padding:1px;width:9%;text-align:left;font-size:10px;'><b>TIPO DOC</b></td>
						<td style='padding:1px;width:2%;text-align:left;font-size:10px;'>:</td>
						<td style='padding:1px;width:18%;text-align:left;font-size:10px;'>".$cab[0]['tipodoc']."</td>
						
						<td style='padding:1px;width:7%;text-align:left;font-size:10px;'><b>NRO. DOC</b></td>
						<td style='padding:1px;width:2%;text-align:left;font-size:10px;'>:</td>
						<td style='padding:1px;width:15%;text-align:left;font-size:10px;'>".$cab[0]['numdoc']."</td>
						
						<td style='padding:1px;width:8%;text-align:left;font-size:10px;'><b>T.C. RECEP</b></td>
						<td style='padding:1px;width:2%;text-align:left;font-size:10px;'>:</td>
						<td style='padding:1px;width:15%;text-align:left;font-size:10px;'>".$tipo_cambio."</td>
						
						<td style='padding:1px;width:5%;text-align:left;font-size:10px;'><b>FECHA</b></td>
						<td style='padding:1px;width:2%;text-align:left;font-size:10px;'>:</td>
						<td style='padding:1px;width:15%;text-align:left;font-size:10px;'>".$cab[0]['fecha']."</td>
					</tr>
				</table>
			</div>
		");
		
		$obj->WriteHTML("<br>
			<table width='100%'>
				<tr>
					<td align='left' style='font-size:10px;padding:2px;'><b>GASTOS ".$titulo_poliza."</b></td>
				</tr>
			</table>
			<table width='100%' style='border:1px solid black; border-collapse:collapse;'>
				<tr>
					<td align='center' style='width:8%;font-size:10px;border:1px solid black;padding:2px;'><b>T/D</b></td>
					<td align='center' style='width:15%;font-size:10px;border:1px solid black;padding:2px;'><b>N DOCUMENTO</b></td>
					<td align='center' style='width:10%;font-size:10px;border:1px solid black;padding:2px;'><b>FECHA</b></td>
					<td align='center' style='width:40%;font-size:10px;border:1px solid black;padding:2px;'><b>PROVEEDOR</b></td>
					<td align='center' style='width:7%;font-size:10px;border:1px solid black;padding:2px;'><b>T.C.</b></td>
					<td align='center' style='width:10%;font-size:10px;border:1px solid black;padding:2px;'><b>".$titulo_detalle_gasto."</b></td>
				</tr>
		");
		
		$cont = 1;
		$suma = 0;
		$array_distribucion = array();
		
		foreach($det as $dat)
		{		
	
			if($cont == 1 && $titulo_poliza=="SOLES"){
				$monto = floatval($dat['montosoles']) * floatval($cab[0]['tipcambiorecep']);
			}elseif ($cont != 1 && $titulo_poliza=="SOLES"){	
				$monto = floatval($dat['montosoles']);				
			}
			
			if($cont == 1 && $titulo_poliza=="DOLARES"){
				$monto = floatval($dat['montosoles']);
			}elseif($cont != 1 && $titulo_poliza=="DOLARES"){
				$monto = floatval($dat['montosoles']) / floatval($cab[0]['tipcambiorecep']);			
			}

			array_push($array_distribucion,$monto);
			
			$suma = $suma + $monto;

			$obj->WriteHTML("
				<tr>
					<td align='center' style='width:8%;font-size:10px;border:1px solid black;padding:2px;'>".$dat['tipodoc']."</td>
					<td align='center' style='width:15%;font-size:10px;border:1px solid black;padding:2px;'>".$dat['nrodoc']."</td>
					<td align='center' style='width:10%;font-size:10px;border:1px solid black;padding:2px;'>".$dat['fecha']."</td>
					<td align='left' style='width:50%;font-size:10px;border:1px solid black;padding:2px;'>".$dat['proveedor']."</td>
					<td align='center' style='width:7%;font-size:10px;border:1px solid black;padding:2px;'>".$tipo_cambio."</td>
					<td align='right' style='width:10%;font-size:10px;border:1px solid black;padding:2px;'>".number_format($monto, 2)."</td>
				</tr>
			");
			
			$cont++;
		}
		
		$obj->WriteHTML("
				<tr>
					<td align='center' style='width:8%;font-size:10px;border-left:1px solid white;border-bottom:1px solid white;padding:2px;'></td>
					<td align='center' style='width:15%;font-size:10px;border-left:1px solid white;border-bottom:1px solid white;padding:2px;'></td>
					<td align='center' style='width:10%;font-size:10px;border-left:1px solid white;border-bottom:1px solid white;padding:2px;'></td>
					<td align='left' style='width:50%;font-size:10px;border-left:1px solid white;border-bottom:1px solid white;padding:2px;'></td>
					<td align='right' style='width:7%;font-size:10px;border-bottom:1px solid white;padding:2px;'></td>
					<td align='right' style='width:10%;font-size:10px;border:1px solid black;padding:2px;'><b>".number_format($suma, 2)."</b></td>
				</tr>
			</table>
		");
		
		$obj->WriteHTML("<br>
			<table width='100%'>
				<tr>
					<td align='left' style='font-size:10px;padding:2px;'><b>DISTRIBUCION ".$titulo_poliza."</b></td>
				</tr>
			</table>
			<table width='100%' style='border:1px solid black; border-collapse:collapse;'>
				<tr>
					<td align='center' style='width:8%;font-size:9px;border:1px solid black;padding:2px;'><b>CODIGO</b></td>
					<td align='center' style='width:20%;font-size:9px;border:1px solid black;padding:2px;'><b>DESCRIPCION</b></td>
					<td align='center' style='width:5%;font-size:9px;border:1px solid black;padding:2px;'><b>CANT</b></td>
					<td align='center' style='width:8%;font-size:9px;border:1px solid black;padding:2px;'><b>".$titulo_detalle_distribucion."</b></td>
		");
				
				for($c = 1; $c<$cont-1; $c++){
					$obj->WriteHTML("
						<td align='center' style='font-size:9px;border:1px solid black;padding:2px;'><b>GASTO".$c."</b></td>
					");
				}

		$obj->WriteHTML("
					<td align='center' style='width:7%;font-size:9px;border:1px solid black;padding:2px;'><b>TOTAL</b></td>
					<td align='center' style='width:7%;font-size:9px;border:1px solid black;padding:2px;'><b>COST.UNIT</b></td>
				</tr>
				<tr>
					<td align='center' style='width:8%;font-size:9px;border:1px solid black;padding:2px;'>".$cab[0]['codigo']."</td>
					<td align='left' style='width:20%;font-size:9px;border:1px solid black;padding:2px;'>".$cab[0]['descripcion']."</td>
					<td align='center' style='width:5%;font-size:9px;border:1px solid black;padding:2px;'>".$cab[0]['cantidad']."</td>
					<td align='center' style='width:8%;font-size:9px;border:1px solid black;padding:2px;'>".number_format($array_distribucion[0], 2)."</td>
		");
		
				array_shift($array_distribucion);
				foreach($array_distribucion as $value){
					$obj->WriteHTML("
						<td align='center' style='font-size:9px;border:1px solid black;padding:2px;'>".number_format($value, 2)."</td>
					");
				}
				
		$obj->WriteHTML("
					<td align='center' style='width:7%;font-size:9px;border:1px solid black;padding:2px;'>".number_format($suma, 2)."</td>
					<td align='center' style='width:7%;font-size:9px;border:1px solid black;padding:2px;'>".number_format($suma/$cab[0]['cantidad'], 2)."</td>
				</tr>		
			</table>
		");

	}

}