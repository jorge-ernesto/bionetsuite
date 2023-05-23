<?php

class asientoContableController extends Controller
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
	
	public function imprimirPDF()
	{
		header('Access-Control-Allow-Origin: *');
		
		$input = json_decode(file_get_contents("php://input"), true);

		$this->getLibrary('mpdf/mpdf');

		//$mpdf = new mPDF();
		$mpdf = new mPDF('utf-8', 'A4', '', '', 7, 7, 20, 90, 15, 2);
		
		$mpdf -> SetTitle('DIARIO');
		
		$mpdf->SetDefaultFont("Arial");
		
		$mpdf->Ln(8);
		
		$mpdf->SetHTMLHeader("
			<table width='100%'>
				<tr>
					<td align='left' style='width:33.3%;font-size:9px;'></td>
					<td align='center' style='width:33.3%;font-size:9px;'>VOUCHER DE DIARIO</td>
					<td align='center' style='width:33.3%;font-size:9px;'></td>
				</tr>
			</table>
		");
		
		$dia = explode('/',$input['dato']['fecha'])[0];
		$mes = explode('/',$input['dato']['fecha'])[1];
		$anio = explode('/',$input['dato']['fecha'])[2];
		
		if($input['dato']['moneda']==="Soles"){
			$moneda="soles";
		}else{
			$moneda="dólares";
		}
		
		$this->getLibrary('NumeroALetras/NumeroALetras');
		
		$cifraletra=new NumeroALetras(); 
		
		$numero_a_letras = $cifraletra->toInvoice(number_format(str_replace(',','',$input['dato']['articulos'][0][3]), 2, '.', ''),2,$moneda);
		
		if(substr($numero_a_letras,0,3)==="MIL"){
			$valor_letras = "UN ".$numero_a_letras;
		}else{
			$valor_letras = $numero_a_letras;
		}
		
		$mpdf->SetHTMLFooter("
			<table width='100%'>
				<tr>
					<td style='padding:1px;width:8%;text-align:left;font-size:9px;'>VOUCHER</td>
					<td style='padding:1px;width:1%;text-align:left;font-size:9px;'>:</td>
					<td style='padding:1px;width:15%;text-align:left;font-size:9px;'>".$input['dato']['nro_entrada']."</td>
					<td rowspan='9' style='width:76%;text-align:left;font-size:9px;'>
						<table width='100%' >
							<tr>
								<td style='padding-top:3px;width:20%;'>&nbsp;</td>
								<td style='padding-top:3px;width:20%;'>&nbsp;</td>
								<td style='padding-top:3px;width:20%;'>&nbsp;</td>
								<td style='padding-top:3px;width:20%;'>&nbsp;</td>
								<td style='padding-top:3px;width:20%;'>&nbsp;</td>
							</tr>
							<tr style='height:25%;'>
								<td style='width:20%;'>&nbsp;</td>
								<td style='width:20%;'>&nbsp;</td>
								<td colspan=2 align='left' style='font-size:11px;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;LIMA&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$dia."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$mes."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$anio."</td>
								<td align='center' style='font-size:12px;'><b>".$input['dato']['articulos'][0][3]." ******</b></td>
							</tr>
							<tr>
								<td style='padding-top:8px;width:20%;'>&nbsp;</td>
								<td style='padding-top:8px;width:20%;'>&nbsp;</td>
								<td style='padding-top:8px;width:20%;'>&nbsp;</td>
								<td style='padding-top:8px;width:20%;'>&nbsp;</td>
								<td style='padding-top:8px;width:20%;'>&nbsp;</td>
							</tr>
							<tr>
								<td colspan=5 style='padding:5px;font-size:14px;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$input['dato']['articulos'][0][1]."</td>
							</tr>
							<tr>
								<td colspan=5 style='padding:5px;font-size:14px;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$valor_letras." ******</td>
							</tr>
							
							<tr>
								<td style='padding-top:10px;width:20%;'>&nbsp;</td>
								<td style='padding-top:10px;width:20%;'>&nbsp;</td>
								<td style='padding-top:10px;width:20%;'>&nbsp;</td>
								<td style='padding-top:10px;width:20%;'>&nbsp;</td>
								<td style='padding-top:10px;width:20%;'>&nbsp;</td>
							</tr>
							<tr>
								<td style='padding-top:10px;width:20%;'>&nbsp;</td>
								<td style='padding-top:10px;width:20%;'>&nbsp;</td>
								<td style='padding-top:10px;width:20%;'>&nbsp;</td>
								<td style='padding-top:10px;width:20%;'>&nbsp;</td>
								<td style='padding-top:10px;width:20%;'>&nbsp;</td>
							</tr>
							<tr>
								<td style='padding-top:10px;width:20%;'>&nbsp;</td>
								<td style='padding-top:10px;width:20%;'>&nbsp;</td>
								<td style='padding-top:10px;width:20%;'>&nbsp;</td>
								<td style='padding-top:10px;width:20%;'>&nbsp;</td>
								<td style='padding-top:10px;width:20%;'>&nbsp;</td>
							</tr>
							<tr>
								<td style='padding-top:10px;width:20%;'>&nbsp;</td>
								<td style='padding-top:10px;width:20%;'>&nbsp;</td>
								<td style='padding-top:10px;width:20%;'>&nbsp;</td>
								<td style='padding-top:10px;width:20%;'>&nbsp;</td>
								<td style='padding-top:10px;width:20%;'>&nbsp;</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td style='padding:1px;width:8%;text-align:left;font-size:9px;'><p>&nbsp;</p>GIRADO<p>&nbsp;</p></td>
					<td style='padding:1px;width:1%;ext-align:left;font-size:9px;'>:</td>
					<td style='padding:1px;width:15%;text-align:left;font-size:9px;'>".$input['dato']['articulos'][0][1]."</td>
				</tr>
				<tr>
					<td style='padding:1px;width:8%;text-align:left;font-size:9px;'>FECHA</td>
					<td style='padding:1px;width:1%;text-align:left;font-size:9px;'>:</td>
					<td style='padding:1px;width:15%;text-align:left;font-size:9px;'>".$input['dato']['fecha']."</td>
				<tr>
					<td style='padding:1px;width:8%;text-align:left;font-size:9px;'>CHEQUE</td>
					<td style='padding:1px;width:1%;text-align:left;font-size:9px;'>:</td>
					<td style='padding:1px;width:15%;text-align:left;font-size:9px;'>".$input['dato']['nro_operacion']."</td>
				</tr>
				<tr>
					<td style='padding:1px 4px 1px 4px;width:8%;text-align:left;font-size:9px;'><p>&nbsp;</p>AUTORIZADO<p>&nbsp;</p></td>
					<td style='padding:1px 4px 1px 4px;width:1%;text-align:left;font-size:9px;'>:</td>
					<td style='padding:1px 4px 1px 4px;width:15%;text-align:left;font-size:9px;'>___________________</td>
				</tr>
				<tr>
					<td style='padding:1px 2px 1px 2px;width:8%;text-align:left;font-size:9px;'><p>&nbsp;</p>GENERAL<p>&nbsp;</p></td>
					<td style='padding:1px 2px 1px 2px;width:1%;text-align:left;font-size:9px;'>:</td>
					<td style='padding:1px 2px 1px 2px;width:15%;text-align:left;font-size:9px;'>___________________</td>
				</tr>
				<tr>
					<td style='padding:1px 2px 1px 2px;width:8%;text-align:left;font-size:9px;'><p>&nbsp;</p>PREPARADO<p>&nbsp;</p></td>
					<td style='padding:1px 2px 1px 2px;width:1%;text-align:left;font-size:9px;'>:</td>
					<td style='padding:1px 2px 1px 2px;width:15%;text-align:left;font-size:9px;'>___________________</td>
				</tr>
				<tr>
					<td style='padding:1px 2px 1px 2px;width:8%;text-align:left;font-size:9px;'><p>&nbsp;</p>RECIBIDO<p>&nbsp;</p></td>
					<td style='padding:1px 2px 1px 2px;width:1%;text-align:left;font-size:9px;'>:</td>
					<td style='padding:1px 2px 1px 2px;width:15%;text-align:left;font-size:9px;'>___________________</td>
				</tr>
				<tr>
					<td style='padding:1px 2px 1px 2px;width:8%;text-align:left;font-size:9px;'>&nbsp;</td>
					<td style='padding:1px 2px 1px 2px;width:1%;text-align:left;font-size:9px;'>&nbsp;</td>
					<td style='padding:1px 2px 1px 2px;width:15%;text-align:left;font-size:9px;'>&nbsp;</td>
				</tr>
			</table>
		");

		$mpdf->WriteHTML("
			<table width='100%'>
				<tr>
					<td style='padding:1.5px;width:15%;text-align:left;font-size:9px;'><b>PERIODO CONTABLE</b></td>
					<td style='padding:1.5px;width:1%;text-align:left;font-size:9px;'>:</td>
					<td style='padding:1.5px;width:22%;text-align:left;font-size:9px;'>".ucfirst($input['dato']['periodo'])."</td>
					<td style='padding:1.5px;width:12%;text-align:left;font-size:9px;'><b>MONEDA</b></td>
					<td style='padding:1.5px;width:1%;text-align:left;font-size:9px;'>:</td>
					<td style='padding:1.5px;width:21%;text-align:left;font-size:9px;'>".strtoupper($input['dato']['moneda'])."</td>
					<td style='padding:1.5px;width:7%;text-align:left;font-size:9px;'><b>VOUCHER</b></td>
					<td style='padding:1.5px;width:1%;text-align:left;font-size:9px;'>:</td>
					<td style='padding:1.5px;width:20%;text-align:left;font-size:9px;'>".$input['dato']['nro_entrada']."</td>
				</tr>
				<tr>
					<td style='padding:1.5px;width:15%;text-align:left;font-size:9px;'><b>BANCO</b></td>
					<td style='padding:1.5px;width:1%;text-align:left;font-size:9px;'>:</td>
					<td style='padding:1.5px;width:22%;text-align:left;font-size:9px;'>BANCO DE CREDITO DEL PERÚ</td>
					<td style='padding:1.5px;width:12%;text-align:left;font-size:9px;'><b>T/C</b></td>
					<td style='padding:1.5px;width:1%;text-align:left;font-size:9px;'>:</td>
					<td style='padding:1.5px;width:21%;text-align:left;font-size:9px;'>".$input['dato']['tipo_cambio']."</td>
					<td style='padding:1.5px;width:7%;text-align:left;font-size:9px;'><b>FECHA</b></td>
					<td style='padding:1.5px;width:1%;text-align:left;font-size:9px;'>:</td>
					<td style='padding:1.5px;width:20%;text-align:left;font-size:9px;'>".$input['dato']['fecha']."</td>
				</tr>
				<tr>
					<td style='padding:1.5px;width:15%;text-align:left;font-size:9px;'><b>FORMA DE PAGO</b></td>
					<td style='padding:1.5px;width:1%;text-align:left;font-size:9px;'>:</td>
					<td style='padding:1.5px;width:22%;text-align:left;font-size:9px;'>".strtoupper($input['dato']['metodo_pago'])."</td>
					<td style='padding:1.5px;width:12%;text-align:left;font-size:9px;'><b>N° DOCUMENTO</b></td>
					<td style='padding:1.5px;width:1%;text-align:left;font-size:9px;'>:</td>
					<td style='padding:1.5px;width:21%;text-align:left;font-size:9px;'>".$input['dato']['nro_operacion']."</td>
					<td style='padding:1.5px;width:7%;text-align:left;font-size:9px;'></td>
					<td style='padding:1.5px;width:1%;text-align:left;font-size:9px;'></td>
					<td style='padding:1.5px;width:20%;text-align:left;font-size:9px;'></td>
				</tr>
				<tr>
					<td style='padding:1.5px;width:15%;text-align:left;font-size:9px;'><b>NOTA PRINCIPAL</b></td>
					<td style='padding:1.5px;width:1%;text-align:left;font-size:9px;'>:</td>
					<td style='padding:1.5px;width:22%;text-align:left;font-size:9px;'>".$input['dato']['nota']."</td>
					<td style='padding:1.5px;width:12%;text-align:left;font-size:9px;'><b>BENEFICIARIO</b></td>
					<td style='padding:1.5px;width:1%;text-align:left;font-size:9px;'>:</td>
					<td colspan=4 style='padding:1.5px;width:51%;text-align:left;font-size:9px;'>".$input['dato']['articulos'][0][1]."</td>
				</tr>
			</table>
		");
		
		$mpdf->Ln(3);
		
		$mpdf->WriteHTML("
			<table width='100%' style='border:0.5px solid black; border-collapse:collapse;'>
				<tr>
					<th align='center' style='width:4%;font-size:9px;border:0.5px solid black;padding:1.5px;'>Item</th>
					<th align='center' style='width:25%;font-size:9px;border:0.5px solid black;padding:1.5px;'>Cuenta</th>
					<th align='center' style='width:15%;font-size:9px;border:0.5px solid black;padding:1.5px;'>Nombre</th>
					<th align='center' style='width:10%;font-size:9px;border:0.5px solid black;padding:1.5px;'>Nro Documento</th>
					<th align='center' style='width:8%;font-size:9px;border:0.5px solid black;padding:1.5px;'>Débito</th>
					<th align='center' style='width:8%;font-size:9px;border:0.5px solid black;padding:1.5px;'>Crédito</th>
					<th align='center' style='width:10%;font-size:9px;border:0.5px solid black;padding:1.5px;'>Nota</th>
				</tr>
		");
		
		$i=1;
		foreach($input['dato']['articulos'] as $art){
			$mpdf->WriteHTML("
				<tr>
					<td align='center' style='width:4%;font-size:9px;border:0.5px solid black;padding:1.5px;'>".$i."</td>
					<td align='left' style='width:25%;font-size:9px;border:0.5px solid black;padding:1.5px;'>".$art[0]."</td>
					<td align='center' style='width:15%;font-size:9px;border:0.5px solid black;padding:1.5px;'>".$art[1]."</td>
					<td align='center' style='width:10%;font-size:9px;border:0.5px solid black;padding:1.5px;'>".$art[2]."</td>
					<td align='center' style='width:8%;font-size:9px;border:0.5px solid black;padding:1.5px;'>".$art[3]."</td>
					<td align='center' style='width:8%;font-size:9px;border:0.5px solid black;padding:1.5px;'>".$art[4]."</td>
					<td align='center' style='width:15%;font-size:9px;border:0.5px solid black;padding:1.5px;'>".$art[5]."</td>
				</tr>
			");
			$i++;
		}
		
		$mpdf->WriteHTML("
			</table>
		");

		date_default_timezone_set('America/Lima');

		$fecha = date("YmdHis",time());
		
		$archivo= "downloads/DIA_".$input['dato']['nro_entrada']."_".$fecha.".pdf";

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
	}

}