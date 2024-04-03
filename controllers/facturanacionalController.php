<?php

class facturanacionalController extends Controller
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
	
	public function imprimirFacturaNacional()
	{
		header('Access-Control-Allow-Origin: *');
		
		$input = json_decode(file_get_contents("php://input"), true);
		
		$objModel = $this->loadModel("facturanacional");
		$cabecera_FN = $objModel->getCabeceraFacturaNacional(intval($input["dato"]['id']));
		
		$this->getLibrary('mpdf/mpdf');
		
		$mpdf = new mPDF('utf-8', 'A4', '', '', 8, 8, 70, 98, 4, 4);
		
		
		$mpdf->SetTitle('FACTURA DE VENTA EXPORTACION');
		
		$mpdf->SetDefaultFont("Arial");
		
		$mpdf->SetHTMLHeader("
			<table width='100%'>
				<tr>
					<td style='width:70%;'>
						<img src='https://6462530.app.netsuite.com/core/media/media.nl?id=54016&c=6462530&h=oZq8NEwo0ydc7UgU_hRFdVoDpNnf1DNn8nBDnFIcKGkX95bT&fcts=20220915215609&whence=' width='155' height='60'>
						<p style='font-size:10px;'><b>Laboratorios Biomont S.A.</b></p>
						<p style='font-size:10px;'>Av. Industrial N° 184 - La Aurora - Ate - Lima - Lima - Perú</p>
						<p style='font-size:10px;'>Telfs.: (00 511) 206-2700 * 206-2701 * 2062702</p>
						<p style='font-size:10px;'>Email: laboratorios@biomont.com.pe Web: www.biomont.com.pe</p>
					</td>
					<td style='width:30%;text-align:center;border:1px solid black;'>
						<p style='font-size:15px;font-weight:bold;'>R.U.C. N° 20100278708</p><br>
						<p style='font-size:15px;font-weight:bold;'>FACTURA ELECTRÓNICA</p><br>
						<p style='font-size:14px;font-weight:bold;'>".str_replace("FA ","",trim($cabecera_FN[0]['numFactura']))."</p>
					</td>
				</tr>
			</table>
			<br>
			<table width='100%'>
				<tr>
					<td style='width:16%;text-align:left;font-size:10px;'><b>RUC:</b></td>
					<td style='width:44%;text-align:left;font-size:10px;'>".$cabecera_FN[0]['docCliente']."</td>
					<td style='width:15%;text-align:left;font-size:10px;'><b>Fecha de Emisión:</b></td>
					<td style='width:23%;text-align:left;font-size:10px;'>".date('d/m/Y',strtotime($cabecera_FN[0]['fecEmision']))."</td>
				</tr>
				<tr>
					<td style='width:16%;text-align:left;font-size:10px;'><b>Nombre/Razón Social:</b></td>
					<td style='width:44%;text-align:left;font-size:10px;'>".$cabecera_FN[0]['nomCliente']."</td>
					<td style='width:15%;text-align:left;font-size:10px;'><b>Fecha Vencimiento:</b></td>
					<td style='width:23%;text-align:left;font-size:10px;'>".date('d/m/Y',strtotime($cabecera_FN[0]['fecVencimiento']))."</td>
				</tr>
				<tr>
					<td style='width:16%;text-align:left;font-size:10px;'><b>Dirección:</b></td>
					<td style='width:44%;text-align:left;font-size:10px;'>".$cabecera_FN[0]['addr1']."</td>
					<td style='width:15%;text-align:left;font-size:10px;'><b>Vendedor:</b></td>
					<td style='width:23%;text-align:left;font-size:10px;'>".$cabecera_FN[0]['vendedor']."</td>
				</tr>
				<tr>
					<td style='width:16%;text-align:left;font-size:10px;'><b>Transportista:</b></td>
					<td style='width:44%;text-align:left;font-size:10px;'>".$cabecera_FN[0]['Transportista']."</td>
					<td style='width:15%;text-align:left;font-size:10px;'><b>Nº de Pedido:</b></td>
					<td style='width:23%;text-align:left;font-size:10px;'>".$cabecera_FN[0]['creadodesde']."</td>
				</tr>
				<tr>
					<td style='width:16%;text-align:left;font-size:10px;'><b>Guía:</b></td>
					<td style='width:44%;text-align:left;font-size:10px;'>".$cabecera_FN[0]['guiaRemision']."</td>
					<td style='width:15%;text-align:left;font-size:10px;'><b>Condición de Pago:</b></td>
					<td style='width:23%;text-align:left;font-size:10px;'>".$cabecera_FN[0]['formaPago']."</td>
				</tr>
			</table>
		");
		
		if($cabecera_FN[0]['xml']!=""){
			$url = $cabecera_FN[0]['xml'];
			$xml = file_get_contents($url);
			$html = '<pre>' . str_replace('<', '&lt;', $xml) . '</pre>';
			$porciones = explode("ds:DigestValue", $html);
			$hash = str_replace(array('\\','&lt;','&gt;','<','>','/'),'',$porciones[1]);
		}else{
			$hash = "";
		}
		
		if($hash!=""){
			$this->getLibrary('phpqrcode/qrlib');
			$filename = "downloads/QR_".str_replace("FA ","",trim($cabecera_FN[0]['numFactura'])).".png";
			
			$tamano = 2;
			$level = "M";
			$framSize = 2;
			
			$numFactura = explode("-",str_replace("FA ","",trim($cabecera_FN[0]['numFactura'])));
			
			$contenido = "20100278708|01|".$numFactura[0]."|".$numFactura[1]."|0.00|".number_format($cabecera_FN[0]['importeTotal'],2,'.','')."|".explode("/", $cabecera_FN[0]['fecEmision'])[2]."-".explode("/", $cabecera_FN[0]['fecEmision'])[1]."-".explode("/", $cabecera_FN[0]['fecEmision'])[0]."|0|".$cabecera_FN[0]['docCliente']."|".$hash."|";
			
			QRcode::png($contenido,$filename,$level,$tamano,$framSize);
		}else{
			$filename = "";
		}
			
		
		$mpdf->SetHTMLFooter("
			<table width='100%'>
				<tr>
					<td rowspan='11' nowwrap style='width:65%;font-size:10px;'>
						<table width='100%' height='100%'>
							<tr>
								<td style='font-size:10px;padding:5px;'>
									<p style='font-size:10px;'><b>Son:</b> ".$cabecera_FN[0]['importeTexto']."</p>
								</td>
							</tr>
							<tr>
								<td style='font-size:10px;border:1px solid black;padding:5px;'>
									<p style='font-size:10px;'><b>Observaciones:</b> ".$cabecera_FN[0]['observacion']."</p>
								</td>
							</tr>
							<tr>
								<td style='font-size:10px;border:1px solid black;padding:5px;'><p style='font-size:10px;'><b>NOTAS</b></p>
									<p style='font-size:10px;'>
									1.- Sírvase a cancelar esta factura con cheque a la orden de Laboratorios Biomont S.A.<br>
									2.- Una vez entregada la mercadería no hay lugar a reclamos, cambio o devolución.<br>
									3.- Toda factura o letra que no sea cancelada a su vencimiento estará afectada a intereses.<br>
									4.- Todo canje y/o devolución se realizarán a través del vendedor, indicando el documento con el cual se adquirió el producto.
									</p>
								</td>
							</tr>
							<tr>
								<td style='font-size:10px;border:1px solid black;padding:5px;'>
									<p style='font-size:10px;'><b>Observaciones de SUNAT</b></p>
									<p style='font-size:10px;'>El comprobante numero ".str_replace("FA ","",trim($cabecera_FN[0]['numFactura'])).", ha sido aceptada</p>
								</td>
							</tr>
						</table>
					</td>
					<td rowspan='10' style='width:3%;font-size:10px;'></td>
					<td style='width:18%;font-size:10px;'><b>Op. Gravada</b></td>
					<td align='center' style='width:2%;font-size:10px;'>S/</td>
					<td align='right' style='width:12%;font-size:10px;'>".number_format($cabecera_FN[0]['opGrabada'],2,'.',',')."</td>
				</tr>
				<tr>
					<td style='width:18%;font-size:10px;'><b>Anticipo</b></td>
					<td align='center' style='width:2%;font-size:10px;'>S/</td>
					<td align='right' style='width:12%;font-size:10px;'>0.00</td>
				</tr>
				<tr>
					<td style='width:18%;font-size:10px;'><b>I.G.V. (18%)</b></td>
					<td align='center' style='width:2%;font-size:10px;'>S/</td>
					<td align='right' style='width:12%;font-size:10px;'>".number_format($cabecera_FN[0]['igv'],2,'.',',')."</td>
				</tr>
				<tr>
					<td style='width:18%;font-size:10px;'><b>Op. Gratuita</b></td>
					<td align='center' style='width:2%;font-size:10px;'>S/</td>
					<td align='right' style='width:12%;font-size:10px;'>".number_format($cabecera_FN[0]['opGratuita'],2,'.',',')."</td>
				</tr>
				<tr>
					<td style='width:18%;font-size:10px;'><b>Op. Inafecta</b></td>
					<td align='center' style='width:2%;font-size:10px;'>S/</td>
					<td align='right' style='width:12%;font-size:10px;'>0.00</td>
				</tr>
				<tr>
					<td style='width:18%;font-size:10px;'><b>Op. Exonerada</b></td>
					<td align='center' style='width:2%;font-size:10px;'>S/</td>
					<td align='right' style='width:12%;font-size:10px;'>0.00</td>
				</tr>
				<tr>
					<td style='width:18%;font-size:10px;'><b>Op. Exportación</b></td>
					<td align='center' style='width:2%;font-size:10px;'>S/</td>
					<td align='right' style='width:12%;font-size:10px;border-bottom:1px solid black;'>0.00</td>
				</tr>
				<tr>
					<td style='width:18%;font-size:10px;'><b>Importe Total</b></td>
					<td align='center' style='width:2%;font-size:10px;'>S/</td>
					<td align='right' style='width:12%;font-size:10px;'>".number_format($cabecera_FN[0]['importeTotal'],2,'.',',')."</td>
				</tr>
			</table>
			<p style='font-size:1px;'>&nbsp;</p>
			<table width='70%'>
				<tr>
					<td style='font-size:9px;'>
						Autorizado a ser emisor electrónico mediante R.I. SUNAT Nº0340050004781<br>
						<i>Representación Impresa de la Factura Electrónica, consulte en https://sfe.bizlinks.com.pe</i>
					</td>
				</tr>
			</table>
			<table width='100%'>
				<tr>
					<td align='right'><img src='".$filename."'></td>
				</tr>
				<tr>
					<td align='right' style='font-size:11px;'><i>Powered by Bizlinks</i></td>
				</tr>
			</table>
			<p style='font-size:1px;'>&nbsp;</p>
			<table width='100%'>
				<tr>
					<td align='left' style='width:33.3%;font-size:8px;'>Código Hash: ".$hash."</td>
					<td align='center' style='width:33.3%;font-size:8px;'>Página {PAGENO} / {nbpg}</td>
					<td align='right' style='width:33.3%;font-size:8px;'>R.U.C. 20100278708-".str_replace("FA ","",trim($cabecera_FN[0]['numFactura']))."</td>
				</tr>
			</table>
		");
		
		$mpdf->Ln(3);
		// class='rowalternate'
		$mpdf->WriteHTML("
			<table width='100%' style='border:1px solid black; border-collapse:collapse;'>
				<tr>
					<td align='center' style='width:5%;font-size:11px;border:1px solid black;padding:3px;'><b>Item</b></td>
					<td style='width:10%;font-size:11px;border:1px solid black;padding:3px;'><b>Código</b></td>
					<td style='width:32%;font-size:11px;border:1px solid black;padding:3px;'><b>Descripción</b></td>
					<td align='center' style='width:6%;font-size:11px;border:1px solid black;padding:3px;'><b>UND</b></td>
					<td align='right' style='width:6%;font-size:11px;border:1px solid black;padding:3px;'><b>Cant.</b></td>
					<td align='center' style='width:10%;font-size:11px;border:1px solid black;padding:3px;'><b>V. Unitario</b></td>
					<td align='center' style='width:10%;font-size:11px;border:1px solid black;padding:3px;'><b>P. Unitario</b></td>
					<td align='center' style='width:7%;font-size:11px;border:1px solid black;padding:3px;'><b>Dscto1</b></td>
					<td align='center' style='width:7%;font-size:11px;border:1px solid black;padding:3px;'><b>Dscto2</b></td>
					<td align='center' style='width:9%;font-size:11px;border:1px solid black;padding:3px;'><b>V. Venta</b></td>
				</tr>
		");
		
		$detalle_FE = $objModel->getDetalleFacturaNacional(intval($input["dato"]['id']));
		
		$cont=1;
		foreach($detalle_FE as $art){
			
			if($art['desc1']==0){
				$importe_resultado = 0.00;
			}else{
			
				$importe_aux = $art['importe'] * 0.18;
				$importe_bruto = $art['importe'] + $importe_aux;
				
				$importe_dscto_aux = $art['importe'] * ((100-((100-floatval($art['desc1']))*(100-floatval($art['desc2'])))/100)/100);
				$importe_dscto_bruto = ($art['importe'] * ((100-((100-floatval($art['desc1']))*(100-floatval($art['desc2'])))/100)/100)) *0.18;
				
				$importe_dscto = $importe_dscto_aux + $importe_dscto_bruto;
				
				$importe_resultado = ($importe_bruto - $importe_dscto)/$art['quantity'];

			}
			
			$mpdf->WriteHTML("
				<tr>
					<td align='center' style='width:5%;font-size:11px;border:1px solid black;padding:3px;'>".$cont."</td>
					<td style='width:10%;font-size:11px;border:1px solid black;padding:3px;'>".$art['codigo']."</td>
					<td style='width:32%;font-size:11px;border:1px solid black;padding:3px;'>".$art['description']."</td>
					<td align='center' style='width:6%;font-size:11px;border:1px solid black;padding:3px;'>".$art['unidad']."</td>
					<td align='right' style='width:6%;font-size:11px;border:1px solid black;padding:3px;'>".$art['quantity']."</td>
					<td align='right' style='width:10%;font-size:11px;border:1px solid black;padding:3px;'>".number_format($art['valorUnitario'],2,'.',',')."</td>
					<td align='right' style='width:10%;font-size:11px;border:1px solid black;padding:3px;'>".number_format($importe_resultado,2,'.',',')."</td>
					<td align='right' style='width:7%;font-size:11px;border:1px solid black;padding:3px;'>".$art['desc1']."</td>
					<td align='right' style='width:7%;font-size:11px;border:1px solid black;padding:3px;'>".$art['desc2']."</td>
					<td align='right' style='width:9%;font-size:11px;border:1px solid black;padding:3px;'>".number_format($art['valorVenta'],2,'.',',')."</td>
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
		
		$archivo= "downloads/FN_".$fecha.".pdf";

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
