<?php

class rotuloProductoTerminadoController extends Controller
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
	
	public function imprimirRotulo()
	{
		header('Access-Control-Allow-Origin: *');
		
		$input = json_decode(file_get_contents("php://input"), true);
		
		$this->getLibrary('mpdf/mpdf');

		$mpdf = new mPDF('utf-8', 'TICKET', '', '', 1, 1, 1, 1, 1, 3);

		
		
		$mpdf->SetDefaultFont("Arial");
		
		//$barcode_C93 = "<barcode code='".trim($input['dato']['codigo'])."' type='C93' size='0.75' height='1.3px' />";
		//$barcode_EAN = "<barcode code='".trim($input['dato']['codigo_ean'])."' type='EAN13'  height='0.55px' />";
		

		$this->crear_etiqueta($mpdf, $input);

		date_default_timezone_set('America/Lima');
		
		$fecha = date("YmdHis",time());

		$archivo= "downloads/ET_PT_".$input['dato']['idTransaccion']."_".$fecha.".pdf";
		
		$mpdf->Output($archivo, 'F');
		
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
	
	public function crear_etiqueta($objPDF, $objInput){

		if(!$objInput['dato']['muestra'] and !$objInput['dato']['saldo'])
		{
			
			$objPDF->SetTitle('ROTULO DE PRODUCTO TERMINADO');
			//CON RANGO DE IMPRESION
			for($i=intval($objInput['dato']['desde']); $i<=intval($objInput['dato']['hasta']); $i++){
			
				$objPDF->WriteHTML("
					<table style='width:100%;border: 1px solid black;border-collapse: collapse;'>
						<tr>
							<td colspan='6' style='border: 1px solid black;border-collapse: collapse;position:relative;height:18px;'>
								<p style='position:absolute;top:-10;right:220;font-size: 11px;'><b>".$objInput['dato']['subsidiary']."</b></p>
								<p style='position:absolute;top:-10;right:10;font-size: 11px;'><b>".$objInput['dato']['format_number']."</b></p>
							</td>
						</tr>
						<tr>
							<td colspan='6' style='border: 1px solid black;border-collapse: collapse;height:52px;'>
								<p style='font-size: 17px;'> ".$objInput['dato']['descripcion']."</p>
							</td>
						</tr>
					</table>
					<table style='width:100%;border: 1px solid black;border-collapse: collapse;'>
						<tr>
							<td style='width:47%;height:72px;border: 1px solid black;border-collapse: collapse;position:relative;'>
								<p style='font-weight:bold;font-size:14px;position:absolute;top:58;right:285;'>CODIGO</p>
								<p style='font-size:20px;position:absolute;top:74;right:231;'>
									<barcode code='".trim($objInput['dato']['codigo'])."' type='C93' size='0.75' height='1.3px' />
								</p>
								<p style='font-size:11px;position:absolute;top:110;right:283;'>".$objInput['dato']['codigo']."</p>
							</td>
							<td style='width:22%;height:72px;border: 1px solid black;border-collapse: collapse;position:relative;text-align:center;'>
								<p style='font-weight:bold;font-size:14px;position:absolute;top:58;right:168;'>N° OT</p>
								<p style='font-size:20px;'>".$objInput['dato']['numOT']."</p>
							</td>
							<td style='width:22%;height:72px;border: 1px solid black;border-collapse: collapse;position:relative;text-align:center;'>
								<p style='font-weight:bold;font-size:14px;position:absolute;top:58;right:97;'>LOTE</p>
								<p style='font-size:20px;'>".$objInput['dato']['lote']."</p>
							</td>
							<td style='width:19%;height:72px;border: 1px solid black;border-collapse: collapse;position:relative;text-align:center;'>
								<p style='font-weight:bold;font-size:14px;position:absolute;top:58;right:21;'>CANT</p>
								<p style='font-size:20px;'>".$objInput['dato']['cantxcaja']."</p>
							</td>
						</tr>
					</table>
					<table style='width:100%;border: 1px solid black;border-collapse: collapse;position:relative;'>
						<tr>
							<td style='width:20%;height:79px;border: 1px solid black;border-collapse: collapse;text-align:center;'>
								<p style='font-weight:bold;font-size:14px;position:absolute;top:124;right:335;'>PESO</p>
								<p style='font-size:20px;'>&nbsp;</p>
							</td>
							<td style='width:30%;height:79px;border: 1px solid black;border-collapse: collapse;text-align:center;'>
								<p style='font-weight:bold;font-size:14px;position:absolute;top:124;right:245;'>FECHA</p>
								<p style='font-size:20px;'>".$objInput['dato']['fecha']."</p>
							</td>
							<td style='width:50%;height:79px;border: 1px solid black;border-collapse: collapse;text-align:center;'>
								<p style='font-weight:bold;font-size:14px;position:absolute;top:124;right:90;'>EAN</p>
								<p style='position:absolute;top:142;right:35;'>
									<barcode code='".trim($objInput['dato']['codigo_ean'])."' type='EAN13'  height='0.55px' />
								</p>
							</td>
						</tr>
					</table>
					<table style='width:100%;border: 1px solid black;border-collapse: collapse;'>
						<tr>
							<td colspan='2' style='width:37%;height:65px;border: 1px solid black;border-collapse: collapse;position:relative;text-align:center;'>
								<p style='font-weight:bold;font-size:14px;position:absolute;top:204;right:315;'>SALDO</p>
								<p style='font-size:20px;'>0</p>
							</td>
							<td colspan='3' style='width:42%;height:65px;border: 1px solid black;border-collapse: collapse;position:relative;text-align:center;'>
								<p style='font-weight:bold;font-size:14px;position:absolute;top:204;right:170;'>CLAVE</p>
								<p style='font-size:20px;'>".$objInput['dato']['clave']."</p>
							</td>
							<td style='width:21%;height:65px;border: 1px solid black;border-collapse: collapse;position:relative;text-align:center;'>
								<p style='font-weight:bold;font-size:14px;position:absolute;top:204;right:18;'>N° CAJA</p>
								<p style='font-size:20px;'>".$i."</p>
							</td>
						</tr>
					</table>
				");
				
				if($i < intval($objInput['dato']['hasta'])){
					$objPDF->AddPage();
				}	
			}
		}else if($objInput['dato']['muestra'] and !$objInput['dato']['saldo'])
		{
			
			$objPDF->SetTitle('ROTULO DE MUESTRA');

			//MUESTRA
			$objPDF->WriteHTML("
				<table style='width:100%;border: 1px solid black;border-collapse: collapse;'>
					<tr>
						<td colspan='6' style='border: 1px solid black;border-collapse: collapse;position:relative;height:18px;'>
							<p style='position:absolute;top:-10;right:220;font-size: 11px;'><b>".$objInput['dato']['subsidiary']."</b></p>
							<p style='position:absolute;top:-10;right:10;font-size: 11px;'><b>".$objInput['dato']['format_number']."</b></p>
						</td>
					</tr>
					<tr>
						<td colspan='6' style='border: 1px solid black;border-collapse: collapse;height:52px;'>
							<p style='font-size: 17px;'> ".$objInput['dato']['descripcion']."</p>
						</td>
					</tr>
				</table>
				<table style='width:100%;border: 1px solid black;border-collapse: collapse;'>
					<tr>
						<td style='width:47%;height:72px;border: 1px solid black;border-collapse: collapse;position:relative;'>
							<p style='font-weight:bold;font-size:14px;position:absolute;top:58;right:285;'>CODIGO</p>
							<p style='font-size:20px;position:absolute;top:74;right:231;'>
								<barcode code='".trim($objInput['dato']['codigo'])."' type='C93' size='0.75' height='1.3px' />
							</p>
							<p style='font-size:11px;position:absolute;top:110;right:283;'>".$objInput['dato']['codigo']."</p>
						</td>
						<td style='width:22%;height:72px;border: 1px solid black;border-collapse: collapse;position:relative;text-align:center;'>
							<p style='font-weight:bold;font-size:14px;position:absolute;top:58;right:168;'>N° OT</p>
							<p style='font-size:20px;'>".$objInput['dato']['numOT']."</p>
						</td>
						<td style='width:22%;height:72px;border: 1px solid black;border-collapse: collapse;position:relative;text-align:center;'>
							<p style='font-weight:bold;font-size:14px;position:absolute;top:58;right:97;'>LOTE</p>
							<p style='font-size:20px;'>".$objInput['dato']['lote']."</p>
						</td>
						<td style='width:19%;height:72px;border: 1px solid black;border-collapse: collapse;position:relative;text-align:center;'>
							<p style='font-weight:bold;font-size:14px;position:absolute;top:58;right:21;'>CANT</p>
							<p style='font-size:20px;'>".$objInput['dato']['cantxcaja']."</p>
						</td>
					</tr>
				</table>
				<table style='width:100%;border: 1px solid black;border-collapse: collapse;position:relative;'>
					<tr>
						<td style='width:20%;height:79px;border: 1px solid black;border-collapse: collapse;text-align:center;'>
							<p style='font-weight:bold;font-size:14px;position:absolute;top:124;right:335;'>PESO</p>
							<p style='font-size:20px;'>&nbsp;</p>
						</td>
						<td style='width:30%;height:79px;border: 1px solid black;border-collapse: collapse;text-align:center;'>
							<p style='font-weight:bold;font-size:14px;position:absolute;top:124;right:245;'>FECHA</p>
							<p style='font-size:20px;'>".$objInput['dato']['fecha']."</p>
						</td>
						<td style='width:50%;height:79px;border: 1px solid black;border-collapse: collapse;text-align:center;'>
							<p style='font-weight:bold;font-size:14px;position:absolute;top:124;right:90;'>EAN</p>
							<p style='position:absolute;top:142;right:35;'>
								<barcode code='".trim($objInput['dato']['codigo_ean'])."' type='EAN13'  height='0.55px' />
							</p>
						</td>
					</tr>
				</table>
				<table style='width:100%;border: 1px solid black;border-collapse: collapse;'>
					<tr>
						<td colspan='2' style='width:37%;height:65px;border: 1px solid black;border-collapse: collapse;position:relative;text-align:center;'>
							<p style='font-weight:bold;font-size:14px;position:absolute;top:204;right:315;'>SALDO</p>
							<p style='font-size:20px;'>0</p>
						</td>
						<td colspan='3' style='width:42%;height:65px;border: 1px solid black;border-collapse: collapse;position:relative;text-align:center;'>
							<p style='font-weight:bold;font-size:14px;position:absolute;top:204;right:170;'>CLAVE</p>
							<p style='font-size:20px;'>".$objInput['dato']['clave']."</p>
						</td>
						<td style='width:21%;height:65px;border: 1px solid black;border-collapse: collapse;position:relative;text-align:center;'>
							<p style='font-weight:bold;font-size:14px;position:absolute;top:204;right:18;'>N° CAJA</p>
							<p style='font-size:20px;'>&nbsp;</p>
						</td>
					</tr>
				</table>
			");
			
		}else if(!$objInput['dato']['muestra'] and $objInput['dato']['saldo'])
		{
			
			$objPDF->SetTitle('ROTULO DE SALDO');

			//SALDO
			$objPDF->WriteHTML("
				<table style='width:100%;border: 1px solid black;border-collapse: collapse;'>
					<tr>
						<td colspan='6' style='border: 1px solid black;border-collapse: collapse;position:relative;height:18px;'>
							<p style='position:absolute;top:-10;right:220;font-size: 11px;'><b>".$objInput['dato']['subsidiary']."</b></p>
							<p style='position:absolute;top:-10;right:10;font-size: 11px;'><b>".$objInput['dato']['format_number']."</b></p>
						</td>
					</tr>
					<tr>
						<td colspan='6' style='border: 1px solid black;border-collapse: collapse;height:52px;'>
							<p style='font-size: 17px;'> ".$objInput['dato']['descripcion']."</p>
						</td>
					</tr>
				</table>
				<table style='width:100%;border: 1px solid black;border-collapse: collapse;'>
					<tr>
						<td style='width:47%;height:72px;border: 1px solid black;border-collapse: collapse;position:relative;'>
							<p style='font-weight:bold;font-size:14px;position:absolute;top:58;right:285;'>CODIGO</p>
							<p style='font-size:20px;position:absolute;top:74;right:231;'>
								<barcode code='".trim($objInput['dato']['codigo'])."' type='C93' size='0.75' height='1.3px' />
							</p>
							<p style='font-size:11px;position:absolute;top:110;right:283;'>".$objInput['dato']['codigo']."</p>
						</td>
						<td style='width:22%;height:72px;border: 1px solid black;border-collapse: collapse;position:relative;text-align:center;'>
							<p style='font-weight:bold;font-size:14px;position:absolute;top:58;right:168;'>N° OT</p>
							<p style='font-size:20px;'>".$objInput['dato']['numOT']."</p>
						</td>
						<td style='width:22%;height:72px;border: 1px solid black;border-collapse: collapse;position:relative;text-align:center;'>
							<p style='font-weight:bold;font-size:14px;position:absolute;top:58;right:97;'>LOTE</p>
							<p style='font-size:20px;'>".$objInput['dato']['lote']."</p>
						</td>
						<td style='width:19%;height:72px;border: 1px solid black;border-collapse: collapse;position:relative;text-align:center;'>
							<p style='font-weight:bold;font-size:14px;position:absolute;top:58;right:21;'>CANT</p>
							<p style='font-size:20px;'>0</p>
						</td>
					</tr>
				</table>
				<table style='width:100%;border: 1px solid black;border-collapse: collapse;position:relative;'>
					<tr>
						<td style='width:20%;height:79px;border: 1px solid black;border-collapse: collapse;text-align:center;'>
							<p style='font-weight:bold;font-size:14px;position:absolute;top:124;right:335;'>PESO</p>
							<p style='font-size:20px;'>&nbsp;</p>
						</td>
						<td style='width:30%;height:79px;border: 1px solid black;border-collapse: collapse;text-align:center;'>
							<p style='font-weight:bold;font-size:14px;position:absolute;top:124;right:245;'>FECHA</p>
							<p style='font-size:20px;'>".$objInput['dato']['fecha']."</p>
						</td>
						<td style='width:50%;height:79px;border: 1px solid black;border-collapse: collapse;text-align:center;'>
							<p style='font-weight:bold;font-size:14px;position:absolute;top:124;right:90;'>EAN</p>
							<p style='position:absolute;top:142;right:35;'>
								<barcode code='".trim($objInput['dato']['codigo_ean'])."' type='EAN13'  height='0.55px' />
							</p>
						</td>
					</tr>
				</table>
				<table style='width:100%;border: 1px solid black;border-collapse: collapse;position:relative;'>
					<tr>
						<td colspan='2' style='width:37%;height:65px;border: 1px solid black;border-collapse: collapse;position:relative;text-align:center;'>
							<p style='font-weight:bold;font-size:14px;position:absolute;top:204;right:315;'>SALDO</p>
							<p style='font-size:20px;'>&nbsp;</p>
						</td>
						<td colspan='3' style='width:42%;height:65px;border: 1px solid black;border-collapse: collapse;position:relative;text-align:center;'>
							<p style='font-weight:bold;font-size:14px;position:absolute;top:204;right:170;'>CLAVE</p>
							<p style='font-size:20px;'>".$objInput['dato']['clave']."</p>
						</td>
						<td style='width:21%;height:65px;border: 1px solid black;border-collapse: collapse;position:relative;text-align:center;'>
							<p style='font-weight:bold;font-size:14px;position:absolute;top:204;right:18;'>N° CAJA</p>
							<p style='font-size:20px;'>&nbsp;</p>
						</td>
					</tr>
				</table>
			");
			
		}
		
	}

}