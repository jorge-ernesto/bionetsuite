<?php

class ordencompraController extends Controller
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
	
	public function imprimirPDF(){
		header('Access-Control-Allow-Origin: *');
		
		$input = json_decode(file_get_contents("php://input"), true);
		
		$this->getLibrary('mpdf/mpdf');
		
		$mpdf = new mPDF('utf-8', 'A4', '', '', 8, 8, 70, 100, 8, 8);
		
		$mpdf->SetTitle('ORDEN DE COMPRA');
		
		$mpdf->SetDefaultFont("Arial");
		
		$barcode_EAN = "<barcode code='c:".$input['dato']['num_OC']."' type='C128B' size='0.8' height='1.4px' />";
		
		$mpdf->SetHTMLHeader("
			<table width='100%'>
				<tr>
					<td style='width:33.33%;'>
						<img src='https://6462530.app.netsuite.com/core/media/media.nl?id=4498&amp;c=6462530&amp;h=WGqzDnYLOhCXE3qk74mYZlj8bgv6xbLP-HjgdEZcJqu0Ekwg' width='160' height='65'>
					</td>
					<td style='width:33.33%;text-align:center;'>
						<p>".$barcode_EAN."</p>
					</td>
					<td style='width:33.33%;text-align:center;border:1px solid black;'>
						<p style='font-size:19px;font-weight:bold;'>ORDEN DE COMPRA
						<br>
						N° ".$input['dato']['num_OC']."
						</p>
					</td>
				</tr>
			</table>
			<table width='100%'>
				<tr>
					<td colspan='4' style='text-align:left;'>
						<p style='font-size:10px;font-weight:bold;'>LABORATORIOS BIOMONT S.A</p>
					</td>
				</tr>
				<tr>
					<td style='width:37%;text-align:left;'>
						<p style='font-size:10px;font-weight:bold;'>Av. Industrial 184 Urb. La Aurora - Ate - Lima</p>
					</td>
					<td style='width:20%;text-align:left;'>
						<p style='font-size:10px;font-weight:bold;'>Telefono: 206-2700</p>
					</td>
					<td style='width:25%;text-align:left;'>
						<p style='font-size:10px;font-weight:bold;'>Email: logistica@biomont.com.pe</p>
					</td>
					<td style='width:18%;text-align:right;'>
						<p style='font-size:10px;font-weight:bold;'>RUC: 20100278708</p>
					</td>
				</tr>
			</table>
			<hr />
			<table width='100%'>
				<tr>
					<td style='width:11%;text-align:left;font-size:11px;'><b>Señores</b></td>
					<td style='width:1%;text-align:left;font-size:11px;'><b>:</b></td>
					<td style='width:49%;text-align:left;font-size:11px;'>".$input['dato']['proveedor']."</td>
					<td style='width:18%;text-align:left;font-size:11px;'><b>Fecha de Emisión</b></td>
					<td style='width:1%;text-align:left;font-size:11px;'><b>:</b></td>
					<td style='width:20%;text-align:left;font-size:11px;'>".$input['dato']['fecha_emision']."</td>
				</tr>
				<tr>
					<td style='width:11%;text-align:left;font-size:11px;'><b>RUC</b></td>
					<td style='width:1%;text-align:left;font-size:11px;'><b>:</b></td>
					<td style='width:49%;text-align:left;font-size:11px;'>".$input['dato']['ruc_proveedor']."</td>
					<td style='width:18%;text-align:left;font-size:11px;'><b>Tipo de OC</b></td>
					<td style='width:1%;text-align:left;font-size:11px;'><b>:</b></td>
					<td style='width:20%;text-align:left;font-size:11px;'>".$input['dato']['tipo_oc']."</td>
				</tr>
				<tr>
					<td style='width:11%;text-align:left;font-size:11px;'><b>Atención</b></td>
					<td style='width:1%;text-align:left;font-size:11px;'><b>:</b></td>
					<td style='width:49%;text-align:left;font-size:11px;'></td>
					<td style='width:18%;text-align:left;font-size:11px;'><b>Comprador</b></td>
					<td style='width:1%;text-align:left;font-size:11px;'><b>:</b></td>
					<td style='width:20%;text-align:left;font-size:11px;'>".$input['dato']['comprador']."</td>
				</tr>
				<tr>
					<td style='width:11%;text-align:left;font-size:11px;'><b>Dirección</b></td>
					<td style='width:1%;text-align:left;font-size:11px;'><b>:</b></td>
					<td style='width:49%;text-align:left;font-size:11px;'>".$input['dato']['direccion']."</td>
					<td style='width:18%;text-align:left;font-size:11px;'><b>Area solicitante</b></td>
					<td style='width:1%;text-align:left;font-size:11px;'><b>:</b></td>
					<td style='width:2%;text-align:left;font-size:11px;'>".$input['dato']['area_solicitante']."</td>
				</tr>
			</table>
			<hr />
		");
		
		if($input['dato']['estado_aprobacion']==="Aprobado" && 
			($input['dato']['proximo_aprobador']==="ALDO JESUS PEDRAGLIO BELMONT" || $input['dato']['proximo_aprobador']==="ALDO SANTIAGO PEDRAGLIO DE COSSIO" || $input['dato']['proximo_aprobador']==="ALDO SANTIAGO PEDRAGLIO DE COSSIO")
		){
			$aprobador_1 = $input['dato']['proximo_aprobador'];
			if($input['dato']['proximo_aprobador']==="ALDO SANTIAGO PEDRAGLIO DE COSSIO"){
				$cargo_1 = "Gerente General Adjunto";
			}else if($input['dato']['proximo_aprobador']==="ALDO JESUS PEDRAGLIO BELMONT"){
				$cargo_1 = "Gerente General";
			}
		}
		
		if($input['dato']['estado_aprobacion']==="Aprobado" && 
			($input['dato']['proximo_aprobador']==="ALDO JESUS PEDRAGLIO BELMONT" || $input['dato']['proximo_aprobador']==="ALDO SANTIAGO PEDRAGLIO DE COSSIO" || $input['dato']['proximo_aprobador']==="ALDO SANTIAGO PEDRAGLIO DE COSSIO")
		){
			$aprobador_2 = "LILIAN BASUALDO SOTO";
			$cargo_2 = "Supervisor de Logística";
		}

		
		$mpdf->SetHTMLFooter("
			<hr />
			<table width='100%'>
				<tr>
					<td style='width:5%;font-size:10px;'><b>Nota</b></td>
					<td style='width:1%;font-size:10px;'><b>:</b></td>
					<td style='width:73%;font-size:10px;'>".$input['dato']['nota']."</td>
					<td style='width:8%;font-size:10px;'><b>Sub Total</b></td>
					<td style='width:1%;font-size:10px;'><b>:</b></td>
					<td align='right' style='width:12%;font-size:10px;'>S/ ".$input['dato']['subtotal']."</td>
				</tr>
				<tr>
					<td style='width:5%;font-size:10px;'></td>
					<td style='width:1%;font-size:10px;'></td>
					<td style='width:73%;font-size:10px;'></td>
					<td style='width:8%;font-size:10px;'><b>IGV: 18 %</b></td>
					<td style='width:1%;font-size:10px;'><b>:</b></td>
					<td align='right' style='width:12%;font-size:10px;'>S/ ".$input['dato']['igv']."</td>
				</tr>
				<tr>
					<td style='width:5%;font-size:10px;'></td>
					<td style='width:1%;font-size:10px;'></td>
					<td style='width:73%;font-size:10px;'></td>
					<td style='width:8%;font-size:10px;'><b>Total</b></td>
					<td style='width:1%;font-size:10px;'><b>:</b></td>
					<td align='right' style='width:12%;font-size:10px;'>S/ ".$input['dato']['total']."</td>
				</tr>
			</table>
			<table width='100%'>
				<tr>
					<td style='width:15%;font-size:10px;'><b>Fecha de Entrega</b></td>
					<td style='width:1%;font-size:10px;'><b>:</b></td>
					<td style='width:84%;font-size:10px;'>".$input['dato']['fecha_entrega']."</td>
				</tr>
				<tr>
					<td style='width:15%;font-size:10px;'><b>Lugar de Entrega</b></td>
					<td style='width:1%;font-size:10px;'><b>:</b></td>
					<td style='width:84%;font-size:10px;'>".$input['dato']['lugar_entrega']."</td>
				</tr>
				<tr>
					<td style='width:15%;font-size:10px;'><b>Condición de pago</b></td>
					<td style='width:1%;font-size:10px;'><b>:</b></td>
					<td style='width:84%;font-size:10px;'>".$input['dato']['condicion_pago']."</td>
				</tr>
			</table>
			<br>
			<table width='100%' style='border: 1px solid black;'>
				<tr>
					<td style='font-size:11px;font-weight:bold;'>
					Sírvase entregar la mercadería según lo especificado.<br>
					Almacén SOLO atendera a proveedores que cuenten con Guía de Remisión, Factura, Orden de Compra y Certificado de Análisis (Obligatorio).<br>
					Las facturas se entregan en secretaría, adjuntando: Guia de Remisíon (sellada por Almacén), orden de Compra y Letras (opcional).<br>
					Horario de recepción de mercaderías : De Lunes a Viernes 08:00 am a 11:30 am y 01:30 pm a 03:30 pm.
					</td>
				</tr>
			</table>
			<hr />
			<table width='100%'>
				<tr>
					<td style='width:33.33%;height:55px;text-align:center;'>
						<p style='font-size:11px;margin-bottom:3px;'>".$aprobador_1."</p>
						<p style='font-size:9px;margin-bottom:3px;'>Firma Digital desde NETSUITE</p>
						<p style='font-size:9px;margin-bottom:3px;'>".$cargo_1."</p>
					</td>
					<td style='width:33.33%;height:55px;text-align:center;'>
						<p style='font-size:11px;padding-bottom:2px;'>".$aprobador_2."</p>
						<p style='font-size:9px;padding-bottom:2px;'>Firma Digital desde NETSUITE</p>
						<p style='font-size:9px;padding-bottom:2px;'>".$cargo_2."</p>
					</td>
					<td style='width:33.33%;height:55px;text-align:center;'>
						<p style='font-size:11px;padding-bottom:2px;'>ALBA DEL RISCO RIOS</p>
						<p style='font-size:9px;padding-bottom:2px;'>Firma Digital desde NETSUITE</p>
						<p style='font-size:9px;padding-bottom:2px;'>Comprador</p>
					</td>
				</tr>
			</table>
			<table width='100%'>
				<tr>
					<td style='text-align:center;'>
						<p style='font-size: 14px;font-weight:bold;'>Somos agentes de percepción desde el 01 de julio del 2013</p>
					</td>
				</tr>
				<tr>
					<td style='text-align:center;'>
						<p style='font-size: 14px;font-weight:bold;'>Somos agentes de retención desde el 01 de septiembre del 2016</p>
					</td>
				</tr>
			</table>
		");
		
		$mpdf->WriteHTML("
			<table width='100%' style='border:1px solid black; border-collapse:collapse;'>
				<tr>
					<td align='center' style='width:10%;font-size:10px;border:1px solid black;padding:3px;background-color:#EFEFEF;'><b>Código</b></td>
					<td style='width:33%;font-size:10px;border:1px solid black;padding:3px;background-color:#EFEFEF;'><b>Artículo</b></td>
					<td align='center' style='width:8%;font-size:10px;border:1px solid black;padding:3px;background-color:#EFEFEF;'><b>Versión</b></td>
					<td align='right' style='width:11%;font-size:10px;border:1px solid black;padding:3px;background-color:#EFEFEF;'><b>Fecha Entrega</b></td>
					<td align='center' style='width:7%;font-size:10px;border:1px solid black;padding:3px;background-color:#EFEFEF;'><b>Unidad</b></td>
					<td align='center' style='width:9%;font-size:10px;border:1px solid black;padding:3px;background-color:#EFEFEF;'><b>Cantidad</b></td>
					<td align='center' style='width:10%;font-size:10px;border:1px solid black;padding:3px;background-color:#EFEFEF;'><b>Precio</b></td>
					<td align='center' style='width:11%;font-size:10px;border:1px solid black;padding:3px;background-color:#EFEFEF;'><b>Importe</b></td>
				</tr>
		");
		
		$cont=1;
		foreach($input['dato']['detalle'] as $art){
			
			$mpdf->WriteHTML("
				<tr>
					<td align='center' style='width:10%;font-size:10px;border:1px solid black;padding:2px;'>".$art[0]."</td>
					<td style='width:33%;font-size:10px;border:1px solid black;padding:2px;'>".$art[1]."</td>
					<td style='width:10%;font-size:8px;border:1px solid black;padding:2px;'></td>
					<td align='center' style='width:11%;font-size:10px;border:1px solid black;padding:2px;'>".$art[2]."</td>
					<td align='center' style='width:7%;font-size:10px;border:1px solid black;padding:2px;'>".$art[3]."</td>
					<td align='right' style='width:9%;font-size:10px;border:1px solid black;padding:2px;'>".$art[4]."</td>
					<td align='right' style='width:10%;font-size:10px;border:1px solid black;padding:2px;'>S/ ".$art[5]."</td>
					<td align='right' style='width:11%;font-size:10px;border:1px solid black;padding:2px;'>S/ ".$art[6]."</td>
				</tr>
			");
			
			$cont++;

		}

		$mpdf->WriteHTML("
				</table>
		");
		
		$mpdf->Ln(3);
		
		date_default_timezone_set('America/Lima');
		
		$fecha = date("YmdHis",time());
		
		$archivo= "downloads/OC_".$input['dato']['num_OC']."_".$fecha.".pdf";

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
	
	public function imprimirPDF_importacion(){
		header('Access-Control-Allow-Origin: *');
		
		$input = json_decode(file_get_contents("php://input"), true);
		
		$this->getLibrary('mpdf/mpdf');

		$mpdf = new mPDF('utf-8', 'A4', '', '', 8, 8, 78, 100, 8, 8);
		
		$mpdf->SetTitle('PURCHASE ORDER');
		
		$mpdf->SetDefaultFont("Arial");
		
		$barcode_EAN = "<barcode code='c:".$input['dato']['num_OC']."' type='C128B' size='0.8' height='1.4px' />";
		
		$mpdf->SetHTMLHeader("
			<table width='100%'>
				<tr>
					<td style='width:33.33%;'>
						<img src='https://6462530.app.netsuite.com/core/media/media.nl?id=4498&amp;c=6462530&amp;h=WGqzDnYLOhCXE3qk74mYZlj8bgv6xbLP-HjgdEZcJqu0Ekwg' width='160' height='65'>
					</td>
					<td style='width:33.33%;text-align:center;'>
						<p>".$barcode_EAN."</p>
					</td>
					<td style='width:33.33%;text-align:center;border:1px solid black;'>
						<p style='font-size:19px;font-weight:bold;'>PURCHASE ORDER
						<br>
						N° ".$input['dato']['num_OC']."
						</p>
					</td>
				</tr>
			</table>
			<table width='100%'>
				<tr>
					<td colspan='4' style='text-align:left;'>
						<p style='font-size:10px;font-weight:bold;'>LABORATORIOS BIOMONT S.A</p>
					</td>
				</tr>
				<tr>
					<td style='width:37%;text-align:left;'>
						<p style='font-size:10px;font-weight:bold;'>Industrial Avenue 184 Urb. La Aurora - Ate - Lima</p>
					</td>
					<td style='width:20%;text-align:left;'>
						<p style='font-size:10px;font-weight:bold;'>Phone: (511) 206-2700</p>
					</td>
					<td style='width:25%;text-align:left;'>
						<p style='font-size:10px;font-weight:bold;'>Email: logistica@biomont.com.pe</p>
					</td>
					<td style='width:18%;text-align:right;'>
						<p style='font-size:10px;font-weight:bold;'>RUC: 20100278708</p>
					</td>
				</tr>
			</table>
			<hr />
			<table width='100%'>
				<tr>
					<td style='width:15%;text-align:left;font-size:11px;'><b>To</b></td>
					<td style='width:1%;text-align:left;font-size:11px;'><b>:</b></td>
					<td style='width:45%;text-align:left;font-size:11px;'>".$input['dato']['proveedor']."</td>
					<td style='width:18%;text-align:left;font-size:11px;'><b>Invoice Date</b></td>
					<td style='width:1%;text-align:left;font-size:11px;'><b>:</b></td>
					<td style='width:20%;text-align:left;font-size:11px;'>".$input['dato']['fecha_emision']."</td>
				</tr>
				<tr>
					<td style='width:15%;text-align:left;font-size:11px;'><b>NIT/TAX ID</b></td>
					<td style='width:1%;text-align:left;font-size:11px;'><b>:</b></td>
					<td style='width:45%;text-align:left;font-size:11px;'>".$input['dato']['ruc_proveedor']."</td>
					<td style='width:18%;text-align:left;font-size:11px;'><b>Type PO</b></td>
					<td style='width:1%;text-align:left;font-size:11px;'><b>:</b></td>
					<td style='width:20%;text-align:left;font-size:11px;'>".$input['dato']['tipo_oc']."</td>
				</tr>
				<tr>
					<td style='width:15%;text-align:left;font-size:11px;'><b>Incoterm</b></td>
					<td style='width:1%;text-align:left;font-size:11px;'><b>:</b></td>
					<td style='width:45%;text-align:left;font-size:11px;'>".$input['dato']['incoterm']."</td>
					<td style='width:18%;text-align:left;font-size:11px;'><b>Purchaser</b></td>
					<td style='width:1%;text-align:left;font-size:11px;'><b>:</b></td>
					<td style='width:20%;text-align:left;font-size:11px;'>".$input['dato']['comprador']."</td>
				</tr>
				<tr>
					<td style='width:15%;text-align:left;font-size:11px;'><b>Contact Person</b></td>
					<td style='width:1%;text-align:left;font-size:11px;'><b>:</b></td>
					<td style='width:45%;text-align:left;font-size:11px;'></td>
					<td style='width:18%;text-align:left;font-size:11px;'><b>Requesting Area</b></td>
					<td style='width:1%;text-align:left;font-size:11px;'><b>:</b></td>
					<td style='width:2%;text-align:left;font-size:11px;'>".$input['dato']['area_solicitante']."</td>
				</tr>
				<tr>
					<td style='width:15%;text-align:left;font-size:11px;'><b>Address</b></td>
					<td style='width:1%;text-align:left;font-size:11px;'><b>:</b></td>
					<td colspan='4' style='width:84%;text-align:left;font-size:11px;'>".str_replace("\n"," ",$input['dato']['direccion'])."</td>
				</tr>
			</table>
			<hr />
		");
		
		if($input['dato']['estado_aprobacion']==="Aprobado" && 
			($input['dato']['proximo_aprobador']==="ALDO JESUS PEDRAGLIO BELMONT" || $input['dato']['proximo_aprobador']==="ALDO SANTIAGO PEDRAGLIO DE COSSIO" || $input['dato']['proximo_aprobador']==="ALDO SANTIAGO PEDRAGLIO DE COSSIO")
		){
			$aprobador_1 = $input['dato']['proximo_aprobador'];
			if($input['dato']['proximo_aprobador']==="ALDO SANTIAGO PEDRAGLIO DE COSSIO"){
				$cargo_1 = "Gerente General Adjunto";
			}else if($input['dato']['proximo_aprobador']==="ALDO JESUS PEDRAGLIO BELMONT"){
				$cargo_1 = "Gerente General";
			}
		}
		
		if($input['dato']['estado_aprobacion']==="Aprobado" && 
			($input['dato']['proximo_aprobador']==="ALDO JESUS PEDRAGLIO BELMONT" || $input['dato']['proximo_aprobador']==="ALDO SANTIAGO PEDRAGLIO DE COSSIO" || $input['dato']['proximo_aprobador']==="ALDO SANTIAGO PEDRAGLIO DE COSSIO")
		){
			$aprobador_2 = "LILIAN BASUALDO SOTO";
			$cargo_2 = "Supervisor de Logística";
		}

		
		$mpdf->SetHTMLFooter("
			<hr />
			<table width='100%'>
				<tr>
					<td style='width:5%;font-size:10px;'><b>Note</b></td>
					<td style='width:1%;font-size:10px;'><b>:</b></td>
					<td style='width:75%;font-size:10px;'>".$input['dato']['nota']."</td>
					<td style='width:6%;font-size:10px;'><b>Total</b></td>
					<td style='width:1%;font-size:10px;'><b>:</b></td>
					<td align='right' style='width:12%;font-size:10px;'>$/ ".$input['dato']['total']."</td>
				</tr>
			</table>
			<br>
			<table width='100%'>
				<tr>
					<td style='width:15%;font-size:10px;'><b>Shipment Date</b></td>
					<td style='width:1%;font-size:10px;'><b>:</b></td>
					<td style='width:84%;font-size:10px;'>".$input['dato']['fecha_entrega']."</td>
				</tr>
				<tr>
					<td style='width:15%;font-size:10px;'><b>Delivery Place</b></td>
					<td style='width:1%;font-size:10px;'><b>:</b></td>
					<td style='width:84%;font-size:10px;'>".$input['dato']['lugar_entrega']."</td>
				</tr>
				<tr>
					<td style='width:15%;font-size:10px;'><b>Payment Condition</b></td>
					<td style='width:1%;font-size:10px;'><b>:</b></td>
					<td style='width:84%;font-size:10px;'>".$input['dato']['condicion_pago']."</td>
				</tr>
			</table>
			<br>
			<table width='100%' style='border: 1px solid black;'>
				<tr>
					<td style='font-size:11px;font-weight:bold;'>
					1. Send us a copy of the original documents via e-mail before shipment.<br>
					2. Confirm date of shipment via e-mail.<br>
					3. Send us the original documents by courier.<br>
					4. Each package must show the name of the product, net weight and gross weight.
					</td>
				</tr>
			</table>
			<hr />
			<table width='100%'>
				<tr>
					<td style='width:33.33%;height:55px;text-align:center;'>
						<p style='font-size:11px;margin-bottom:3px;'>".$aprobador_1."</p>
						<p style='font-size:9px;margin-bottom:3px;'>Firma Digital desde NETSUITE</p>
						<p style='font-size:9px;margin-bottom:3px;'>".$cargo_1."</p>
					</td>
					<td style='width:33.33%;height:55px;text-align:center;'>
						<p style='font-size:11px;padding-bottom:2px;'>".$aprobador_2."</p>
						<p style='font-size:9px;padding-bottom:2px;'>Firma Digital desde NETSUITE</p>
						<p style='font-size:9px;padding-bottom:2px;'>".$cargo_2."</p>
					</td>
					<td style='width:33.33%;height:55px;text-align:center;'>
						<p style='font-size:11px;padding-bottom:2px;'>FATIMA LUCERO LEON MEJIA</p>
						<p style='font-size:9px;padding-bottom:2px;'>Firma Digital desde NETSUITE</p>
						<p style='font-size:9px;padding-bottom:2px;'>Comprador</p>
					</td>
				</tr>
			</table>
			<table width='100%'>
				<tr>
					<td style='text-align:center;'>
						<p style='font-size: 14px;font-weight:bold;'>We are perception agents since July 1, 2013</p>
					</td>
				</tr>
				<tr>
					<td style='text-align:center;'>
						<p style='font-size: 14px;font-weight:bold;'>We are withholding agents since September 1, 2016</p>
					</td>
				</tr>
			</table>
		");
		
		$mpdf->WriteHTML("
			<table width='100%' style='border:1px solid black; border-collapse:collapse;'>
				<tr>
					<td align='center' style='width:10%;font-size:10px;border:1px solid black;padding:3px;background-color:#EFEFEF;'><b>Code</b></td>
					<td style='width:33%;font-size:10px;border:1px solid black;padding:3px;background-color:#EFEFEF;'><b>Description</b></td>
					<td align='right' style='width:11%;font-size:10px;border:1px solid black;padding:3px;background-color:#EFEFEF;'><b>Shipment Date</b></td>
					<td align='center' style='width:7%;font-size:10px;border:1px solid black;padding:3px;background-color:#EFEFEF;'><b>Unit</b></td>
					<td align='center' style='width:9%;font-size:10px;border:1px solid black;padding:3px;background-color:#EFEFEF;'><b>Quantity</b></td>
					<td align='center' style='width:10%;font-size:10px;border:1px solid black;padding:3px;background-color:#EFEFEF;'><b>Unit Price</b></td>
					<td align='center' style='width:11%;font-size:10px;border:1px solid black;padding:3px;background-color:#EFEFEF;'><b>Amount</b></td>
				</tr>
		");
		
		$cont=1;
		foreach($input['dato']['detalle'] as $art){
			
			$mpdf->WriteHTML("
				<tr>
					<td align='center' style='width:10%;font-size:10px;border:1px solid black;padding:2px;'>".$art[0]."</td>
					<td style='width:33%;font-size:10px;border:1px solid black;padding:2px;'>".$art[1]."</td>
					<td align='center' style='width:11%;font-size:10px;border:1px solid black;padding:2px;'>".$art[2]."</td>
					<td align='center' style='width:7%;font-size:10px;border:1px solid black;padding:2px;'>".$art[3]."</td>
					<td align='right' style='width:9%;font-size:10px;border:1px solid black;padding:2px;'>".$art[4]."</td>
					<td align='right' style='width:10%;font-size:10px;border:1px solid black;padding:2px;'>$/ ".$art[5]."</td>
					<td align='right' style='width:11%;font-size:10px;border:1px solid black;padding:2px;'>$/ ".$art[6]."</td>
				</tr>
			");
			
			$cont++;

		}

		$mpdf->WriteHTML("
				</table>
		");
		
		$mpdf->Ln(3);
		
		date_default_timezone_set('America/Lima');
		
		$fecha = date("YmdHis",time());
		
		$archivo= "downloads/OC_".$input['dato']['num_OC']."_".$fecha.".pdf";

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

}