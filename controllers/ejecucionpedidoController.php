<?php

class ejecucionpedidoController extends Controller
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
	
	public function imprimirEjecucionPedido()
	{
		
		header('Access-Control-Allow-Origin: *');
		
		$input = json_decode(file_get_contents("php://input"), true);
		
		$objModel = $this->loadModel("ejecucionpedido");
		$cabecera_EP = $objModel->getCabeceraEjecucionPedido(intval($input["dato"]['id']));
		
		$this->getLibrary('mpdf/mpdf');
		
		/*
		$mpdfConfig = array(
				'mode' => 'utf-8', 
				'format' => 'A4',   		 	// format - A4, for example, default ''
				'default_font_size' => 0,    	// font size - default 0
				'default_font' => '',    	 	// default font family
				'margin_left' => 0,    		 	// 15 margin_left
				'margin_right' => 0,    	 	// 15 margin right
				// 'mgt' => $headerTopMargin,	// 16 margin top
				// 'mgb' => $footerTopMargin,	// margin bottom
				'margin_header' => 0,     	 	// 9 margin header
				'margin_footer' => 0,     	 	// 9 margin footer
				'orientation' => 'P'  		 	// L - landscape, P - portrait
		*/
		$mpdf = new mPDF('utf-8', 'A4', '', '', 7, 7, 90, 90, 4, 4);
		
		
		$mpdf->SetTitle('EJECUCION DE PEDIDO');
		
		$mpdf->SetDefaultFont("Arial");
		
		//$stylesheet = file_get_contents('css/relatorios.css');
		//$mpdf->WriteHTML($stylesheet, 1);
		
		
		/*$array_dep_pro = explode(" ",$cabecera_EP[0]['depa_prov']);
		
		$res_dep_prov = "";
		foreach($array_dep_pro as $dp){
			$res_dep_prov .= $dp."&nbsp;&nbsp;&nbsp;";
		}*/
				
		$factura = preg_match("/FA/i", $cabecera_EP[0]['factura']) ? str_replace("FA ","",$cabecera_EP[0]['factura']) : str_replace("BV ","",$cabecera_EP[0]['factura']);
		
		$mpdf->SetHTMLHeader("
			<table width='100%'>
				<tr style='height:200px;'>
					<td style='width:45%;text-align:center;'>
						<img src='https://6462530.app.netsuite.com/core/media/media.nl?id=54016&c=6462530&h=oZq8NEwo0ydc7UgU_hRFdVoDpNnf1DNn8nBDnFIcKGkX95bT&fcts=20220915215609&whence=' width='190' height='90'>
						<p style='font-size:13px;text-align:center;'><b>Laboratorios Biomont S.A.</b></p>
						<p style='font-size:10px;text-align:center;'>Av. Industrial N° 184 - La Aurora - Ate - Lima - Lima - Perú</p>
						<p style='font-size:10px;text-align:center;'>Telfs.: (00 511) 206-2700 * 206-2701 * 2062702</p>
						<p style='font-size:10px;text-align:center;'>Email: laboratorios@biomont.com.pe Web: www.biomont.com.pe</p>
					</td>
					<td style='width:20%;text-align:center;'>
					</td>
					<td style='width:35%;text-align:center;border:2px solid black;'>
						<span style='font-size:20px;font-weight:bold;'>RUC N°20100278708</span><p style='font-size:5px;'>&nbsp;</p>
						<span style='font-size:20px;font-weight:bold;'>GUIA DE REMISIÓN</span><p style='font-size:5px;'>&nbsp;</p>
						<span style='font-size:20px;font-weight:bold;'>REMITENTE</span><p style='font-size:5px;'>&nbsp;</p>
						<span style='font-size:20px;font-weight:bold;'>ELECTRÓNICA</span><p style='font-size:5px;'>&nbsp;</p>
						<span style='font-size:20px;font-weight:bold;'>".str_replace("GR ","",$cabecera_EP[0]['numGuia'])."</span>
					</td>
				</tr>
			</table>
			<br>
			<table style='width:100%;'>
				<tr>
					<td colspan='4' style='padding-left:3px;padding-bottom:14px;text-align:left;font-size:10px;'><b>DESTINATARIO</b></td>
				</tr>
				<tr>
					<td style='padding:1.5px;width:17%;text-align:left;font-size:10px;'><b>RUC:</b></td>
					<td style='padding:1.5px;width:43%;text-align:left;font-size:10px;'>".$cabecera_EP[0]['docCliente']."</td>
					<td style='padding:1.5px;width:15%;text-align:left;font-size:10px;'><b>Fecha de Emisión:</b></td>
					<td style='padding:1.5px;width:25%;text-align:left;font-size:10px;'>".$cabecera_EP[0]['fecEmision']."</td>
				</tr>
				<tr>
					<td style='padding:1.5px;width:17%;text-align:left;font-size:10px;'><b>Nombre / Razón Social:</b> </td>
					<td style='padding:1.5px;width:43%;text-align:left;font-size:10px;'>".$cabecera_EP[0]['nomCliente']."</td>
					<td style='padding:1.5px;width:15%;text-align:left;font-size:10px;'><b>N° Pedido:</b> </td>
					<td style='padding:1.5px;width:25%;text-align:left;font-size:10px;'>".$cabecera_EP[0]['creadodesde']."</td>
				</tr>
				<tr>
					<td style='padding:1.5px;width:17%;text-align:left;font-size:10px;'><b>Dirección:</b></td>
					<td style='padding:1.5px;width:43%;text-align:left;font-size:10px;'>".$cabecera_EP[0]['direccion']."</td>
					<td style='padding:1.5px;width:15%;text-align:left;font-size:10px;'><b>Motivo de Traslado:</b></td>
					<td style='padding:1.5px;width:25%;text-align:left;font-size:10px;'>".$cabecera_EP[0]['tipoOperacion']."</td>
				</tr>
				<tr>
					<td style='padding:1.5px;width:17%;text-align:left;font-size:10px;;'><b>Prov. / Dep.:</b></td>
					<td style='padding:1.5px;width:43%;text-align:left;font-size:10px;;'>".str_replace("  "," / ",$cabecera_EP[0]['depa_prov'])."</td>
					<td style='padding:1.5px;width:15%;text-align:left;font-size:10px;;'><b>Fecha de Traslado:</b></td>
					<td style='padding:1.5px;width:25%;text-align:left;font-size:10px;;'>".$cabecera_EP[0]['fecTraslado']."</td>
				</tr>
				<tr>
					<td style='padding:1.5px;width:17%;text-align:left;font-size:10px;'><b>Distrito:</b></td>
					<td style='padding:1.5px;width:43%;text-align:left;font-size:10px;'>".$cabecera_EP[0]['distrito']."</td>
					<td style='padding:1.5px;width:15%;text-align:left;font-size:10px;'><b>Factura:</b></td>
					<td style='padding:1.5px;width:25%;text-align:left;font-size:10px;'>".$factura."</td>
				</tr>
				<tr>
					<td style='padding:1.5px;width:17%;text-align:left;font-size:10px;'><b>Peso / Caja:</b></td>
					<td style='padding:1.5px;width:43%;text-align:left;font-size:10px;'>".$cabecera_EP[0]['peso']."/".$cabecera_EP[0]['caja']."</td>
					<td style='padding:1.5px;width:15%;text-align:left;font-size:10px;'><b>Vendedor:</b></td>
					<td style='padding:1.5px;width:25%;text-align:left;font-size:10px;'>".$cabecera_EP[0]['vendedor']."</td>
				</tr>
			</table>
		");
		
		if($cabecera_EP[0]['xml']!=""){
			$url = $cabecera_EP[0]['xml'];
			$xml = file_get_contents($url);
			$html = '<pre>' . str_replace('<', '&lt;', $xml) . '</pre>';
			$porciones = explode("ds:DigestValue", $html);
			$hash = str_replace(array('\\','&lt;','&gt;','<','>','/'),'',$porciones[1]);
		}else{
			$hash="";
		}
		
		$mpdf->SetHTMLFooter("
			<span style='font-size:10px;'><b>Datos del Vehículo y Conductor</b></span> <p style='font-size:1px;'>&nbsp;</p>
			<table style='width:100%;border:1px solid black;'>
				<tr>
					<td style='width:30%;font-size:10px;padding:3px;'><b>Marca y Placa: </b>".$cabecera_EP[0]['placa']."</td>
					<td style='width:17%;font-size:10px;padding:3px;'><b>DNI: </b>".$cabecera_EP[0]['dniConductor']."</td>
					<td style='width:28%;font-size:10px;padding:3px;'><b>Licencia de Conducir: </b>".$cabecera_EP[0]['licenConductor']."</td>
					<td style='width:25%;font-size:10px;padding:3px;'><b>Form. Pago por Envío: </b>".$cabecera_EP[0]['formPagoxEnvio']."</td>
				</tr>	
			</table>
			<p style='font-size:3px;'>&nbsp;</p>
			<span style='font-size:10px;'><b>Datos del Transportista</b></span> <p style='font-size:1px;'>&nbsp;</p>
			<table style='width:100%;border:1px solid black;'>
				<tr>
					<td style='width:50%;font-size:10px;padding:3px;'><b>RUC: </b>".$cabecera_EP[0]['rucTransportista']."</td>
					<td style='width:50%;font-size:10px;padding:3px;'><b>Nombre/Razón Social: </b>".$cabecera_EP[0]['razSocTransportista']."</td>
				</tr>
				<tr>
					<td style='width:50%;font-size:10px;padding:3px;'><b>Dirección: </b>".$cabecera_EP[0]['direcTransportista']."</td>
					<td style='width:50%;font-size:10px;padding:3px;'></td>
				</tr>			
			</table>
			<p style='font-size:3px;'>&nbsp;</p>
			<span style='font-size:10px;'><b>Direcciones</b></span> <p style='font-size:1px;'>&nbsp;</p>
			<table style='width:100%;border:1px solid black;'>
				<tr>
					<td style='width:100%;font-size:10px;padding:3px;'><b>Domicilio del Punto de Partida: </b>".$cabecera_EP[0]['puntoPartida']."</td>
				</tr>
				<tr>
					<td style='width:100%;font-size:10px;padding:3px;'><b>Domicilio del Punto de Llegada: </b>".$cabecera_EP[0]['direccion']." - ". $cabecera_EP[0]['distrito'] . " - ".str_replace("  "," - ",$cabecera_EP[0]['depa_prov'])."</td>
				</tr>			
			</table>
			<p style='font-size:3px;'>&nbsp;</p>
			<table style='width:100%;border:1px solid black;'>
				<tr>
					<td style='width:100%;font-size:10px;padding:3px;'>
						<b>Observaciones</b>
					</td>
				</tr>
				<tr>
					<td style='width:100%;font-size:10px;padding:3px;'>
						<span style='font-size:10px;'>".$cabecera_EP[0]['Nota']." Peso Total: ".$cabecera_EP[0]['peso']." KGM Total Bultos: ".$cabecera_EP[0]['caja']."</span><p style='font-size:2px;'>&nbsp;</p>
						<span style='font-size:10px;'>El comprobante numero ".str_replace("GR ","",$cabecera_EP[0]['numGuia']).", ha sido aceptada</span>
					</td>
				</tr>			
			</table>
			<p style='font-size:9px;'><i>Representación Impresa de la Guía de Remisión Remitente Electrónica, consulte en https://sfe.bizlinks.com.pe</i></p>
			<br>
			<table width='100%'>
				<tr>
					<td align='left' style='width:33.3%;font-size:8px;'>Código Hash: ".$hash."</td>
					<td align='center' style='width:33.3%;font-size:8px;'>Página {PAGENO} / {nbpg}</td>
					<td align='right' style='width:33.3%;font-size:8px;'>R.U.C. 20100278708-".str_replace("GR ","",$cabecera_EP[0]['numGuia'])."</td>
				</tr>
			</table>
		");
		
		/*$html="<style>
				
				table.rowalternate tr:nth-child(odd){ 
					background-color: #FBFBFB;
				}
				table.rowalternate tr:nth-child(even){ 
					background-color: #FFFFFF;
				}     
				
				</style>";
				
		$mpdf->WriteHTML($html);*/
	
		$mpdf->Ln(4);
		//class='rowalternate'
		$mpdf->WriteHTML("
			<table width='100%' style='border:1px solid black; border-collapse:collapse;'>
				<tr>
					<td align='center' style='width:5%;font-size:10px;border:1px solid black;padding:3px;'><b>Item</b></td>
					<td align='left' style='width:10%;font-size:10px;border:1px solid black;padding:3px;'><b>Código</b></td>
					<td align='left' style='width:45%;font-size:10px;border:1px solid black;padding:3px;'><b>Descripción</b></td>
					<td align='center' style='width:10%;font-size:10px;border:1px solid black;padding:3px;'><b>Lote</b></td>
					<td align='center' style='width:10%;font-size:10px;border:1px solid black;padding:3px;'><b>Venc.</b></td>
					<td align='center' style='width:10%;font-size:10px;border:1px solid black;padding:3px;'><b>UND</b></td>
					<td align='right' style='width:10%;font-size:10px;border:1px solid black;padding:3px;'><b>Cantidad</b></td>
				</tr>
		");
		
		$detalle_EP = $objModel->getDetalleEjecucionPedido(intval($input["dato"]['id']));
		
		$cont=1;
		foreach($detalle_EP as $art){
			
			$contiene_numeral = strpos($art['lote'],"#");
			if($contiene_numeral){
				$lote=explode("#",$art['lote'])[0];
			}else{
				$lote=$art['lote'];
			}
			
			$mpdf->WriteHTML("
				<tr>
					<td align='center' style='width:5%;font-size:10px;border:1px solid black;padding:2px;'>".$cont."</td>
					<td align='left' style='width:10%;font-size:10px;border:1px solid black;padding:2px;'>".$art['itemid']."</td>
					<td align='left' style='width:45%;font-size:10px;border:1px solid black;padding:2px;'>".$art['description']."</td>
					<td align='center' style='width:10%;font-size:10px;border:1px solid black;padding:2px;'>".$lote."</td>
					<td align='center' style='width:10%;font-size:10px;border:1px solid black;padding:2px;'>".$art['fechacaducidad']."</td>
					<td align='center' style='width:10%;font-size:10px;border:1px solid black;padding:2px;'>".$art['unidad']."</td>
					<td align='right' style='width:10%;font-size:10px;border:1px solid black;padding:2px;'>".number_format($art['quantity'], 2)."</td>
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
		
		$archivo= "downloads/EP_".$fecha.".pdf";

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
	
	public function imprimirEjecucionFisicaPedido()
	{
		
		header('Access-Control-Allow-Origin: *');
		
		$input = json_decode(file_get_contents("php://input"), true);
		
		$objModel = $this->loadModel("ejecucionpedido");
		$cabecera_EP = $objModel->getCabeceraEjecucionPedido(intval($input["dato"]['id']));
		
		$this->getLibrary('mpdf/mpdf');
		
		$mpdf = new mPDF('utf-8', 'LETTER', '', '', 10, 15, 75, 115, 40, 45);
		
		$mpdf->SetTitle('EJECUCION DE PEDIDO FISICO');
		
		$mpdf->SetDefaultFont("Arial");
		
		$factura = preg_match("/FA/i", $cabecera_EP[0]['factura']) ? str_replace("FA ","",$cabecera_EP[0]['factura']) : str_replace("BV ","",$cabecera_EP[0]['factura']);
		$factura = str_replace("-","",$factura);
		
		$mpdf->SetHTMLHeader("	
			<table width='100%'>
				<tr>
					<td style='width:68%;text-align:center;'>
						&nbsp;
					</td>
					<td style='width:32%;text-align:center;'>
						<span style='font-size:9px;'><b>Fact:</b> ".$factura."
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<b>Guia:</b> ".str_replace("GR T","",str_replace("-","",$cabecera_EP[0]['numGuia']))."</span>
					</td>
				</tr>
			</table>
			<table style='width:100%;'>
				<tr>
					<td style='width:100%;padding-left:2px;padding-bottom:10px;text-align:left;font-size:10px;'><b>DESTINATARIO</b></td>
				</tr>
			</table>
			<table style='width:100%;'>
				<tr>
					<td style='padding:0.3px;width:12%;text-align:left;font-size:10px;'><b>RUC</b></td>
					<td style='padding:0.3px;width:15%;text-align:left;font-size:10px;'><b>: </b>".$cabecera_EP[0]['docCliente']."</td>
					<td style='padding:0.3px;width:8%;text-align:left;font-size:10px;'><b>Cliente</b></td>
					<td style='padding:0.3px;width:65%;text-align:left;font-size:10px;'><b>: </b>".$cabecera_EP[0]['nomCliente']."</td>
				</tr>
			</table>
			<table style='width:100%;'>
				<tr>
					<td style='padding:0.3px;width:12%;text-align:left;font-size:10px;'><b>Dirección</b> </td>
					<td style='padding:0.3px;width:88%;text-align:left;font-size:10px;'><b>: </b>".$cabecera_EP[0]['direccion']."</td>
				</tr>
			</table>
			<table style='width:100%;'>
				<tr>
					<td style='padding:0.3px;width:12%;text-align:left;font-size:10px;'><b>Departamento</b></td>
					<td style='padding:0.3px;width:24%;text-align:left;font-size:10px;'><b>: </b>".$cabecera_EP[0]['depa']."</td>
					<td style='padding:0.3px;width:8%;text-align:left;font-size:10px;'><b>Provincia</b></td>
					<td style='padding:0.3px;width:24%;text-align:left;font-size:10px;'><b>: </b>".$cabecera_EP[0]['prov']."</td>
					<td style='padding:0.3px;width:8%;text-align:left;font-size:10px;'><b>Distrito</b></td>
					<td style='padding:0.3px;width:25%;text-align:left;font-size:10px;'><b>: </b>".$cabecera_EP[0]['distrito']."</td>
				</tr>
			</table>
			<table style='width:100%;'>
				<tr>
					<td style='padding:0.3px;width:12%;text-align:left;font-size:10px;'><b>Fecha Emisión</b></td>
					<td style='padding:0.3px;width:12%;text-align:left;font-size:10px;'><b>: </b>".$cabecera_EP[0]['fecEmision']."</td>
					<td style='padding:0.3px;width:15%;text-align:left;font-size:10px;'><b>Fch. Inicio Traslado</b></td>
					<td style='padding:0.3px;width:12%;text-align:left;font-size:10px;'><b>: </b>".$cabecera_EP[0]['fecTraslado']."</td>
					<td style='padding:0.3px;width:8%;text-align:left;font-size:10px;'><b>N° Pedido</b></td>
					<td style='padding:0.3px;width:11%;text-align:left;font-size:10px;'><b>: </b></td>
					<td style='padding:0.3px;width:8%;text-align:left;font-size:10px;'><b>Vendedor</b></td>
					<td style='padding:0.3px;width:23%;text-align:left;font-size:10px;'><b>: </b>".$cabecera_EP[0]['vendedor']."</td>
				</tr>
			</table>
		");

		$mpdf->SetHTMLFooter("
			<table style='width:100%;'>
				<tr>
					<td colspan='2' style='font-size:11px;padding-bottom:4px;'><b>TRANSPORTISTA: ".$cabecera_EP[0]['razSocTransportista'].", RUC: ".$cabecera_EP[0]['rucTransportista'].", ".$cabecera_EP[0]['Nota']." Peso Total: ".$cabecera_EP[0]['peso']." KGM Total Bultos: ".$cabecera_EP[0]['caja']."</b></td>
				</tr>
				<tr>
					<td style='width:18%;font-size:10px;padding:2px;'><b>Vehículo Marca y Placa<b></td>
					<td style='width:82%;font-size:10px;padding:2px;'><b>: </b>".$cabecera_EP[0]['placa']."</td>
				</tr>	
				<tr>
					<td style='width:18%;font-size:10px;padding:2px;'><b>Licencia de Conducir<b></td>
					<td style='width:82%;font-size:10px;padding:2px;'><b>: </b>".$cabecera_EP[0]['licenConductor']."</td>
				</tr>
				<tr>
					<td style='width:18%;font-size:10px;padding:2px;'><b>Form. Pago por Envío<b></td>
					<td style='width:82%;font-size:10px;padding:2px;'><b>: </b>".$cabecera_EP[0]['formPagoxEnvio']."</td>
				</tr>
				<tr>
					<td colspan='2' style='font-size:10px;padding:2px;'><hr></td>
				</tr>
				<tr>
					<td style='width:18%;font-size:10px;padding:2px;'><b>Nombre o Razón Social<b></td>
					<td style='width:82%;font-size:10px;padding:2px;'><b>: </b>".$cabecera_EP[0]['razSocTransportista']."</td>
				</tr>
				<tr>
					<td style='width:18%;font-size:10px;padding:2px;'><b>Dirección<b></td>
					<td style='width:82%;font-size:10px;padding:2px;'><b>: </b>".$cabecera_EP[0]['direcTransportista']."</td>
				</tr>
				<tr>
					<td colspan='2' style='font-size:10px;padding:2px;'><hr></td>
				</tr>
				<tr>
					<td style='width:15%;font-size:10px;padding:2px;'>&nbsp;</td>
					<td style='width:85%;font-size:10px;padding:2px;'>".$cabecera_EP[0]['puntoPartida']."</td>
				</tr>
				<tr>
					<td style='width:15%;font-size:10px;padding:2px;'>&nbsp;</td>
					<td style='width:85%;font-size:10px;padding:2px;'>".$cabecera_EP[0]['direccion']." - ". $cabecera_EP[0]['distrito'] . " - ".str_replace("  "," - ",$cabecera_EP[0]['depa_prov'])."</td>
				</tr>			
			</table>
		");

		$mpdf->Ln(4);
		//style='border:1px solid black; border-collapse:collapse;'
		//border:1px solid black;
		$mpdf->WriteHTML("
			<table width='100%' cellspacing='0'>
				<tr>
					<td align='center' style='width:5%;font-size:10px;padding:2px;'><b>Item</b></td>
					<td align='center' style='width:10%;font-size:10px;padding:2px;'><b>Código</b></td>
					<td align='center' style='width:10%;font-size:10px;;padding:2px;'><b>UND</b></td>
					<td align='left' style='width:55%;font-size:10px;padding:2px;'><b>Descripción</b></td>
					<td align='center' style='width:10%;font-size:10px;padding:2px;'><b>Lote</b></td>
					<td align='right' style='width:10%;font-size:10px;padding:2px;'><b>Cantidad</b></td>
				</tr>
		");
		
		$detalle_EP = $objModel->getDetalleEjecucionPedido(intval($input["dato"]['id']));
		
		$cont=1;
		foreach($detalle_EP as $art){
			
			$contiene_numeral = strpos($art['lote'],"#");
			if($contiene_numeral){
				$lote=explode("#",$art['lote'])[0];
			}else{
				$lote=$art['lote'];
			}
			//border:1px solid black;
			$mpdf->WriteHTML("
				<tr>
					<td align='center' style='width:5%;font-size:10px;padding:1px;'>".$cont."</td>
					<td align='center' style='width:10%;font-size:10px;padding:1px;'>".$art['itemid']."</td>
					<td align='center' style='width:10%;font-size:10px;padding:1px;'>".$art['unidad']."</td>
					<td align='left' style='width:55%;font-size:10px;padding:1px;'>".$art['description']."</td>
					<td align='center' style='width:10%;font-size:10px;padding:1px;'>".$lote."</td>
					<td align='right' style='width:10%;font-size:10px;padding:1px;'>".$art['quantity']."</td>
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
		
		$archivo= "downloads/EP_".$fecha.".pdf";

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
	
	public function downloadEjecucionPedidoXLSX()
	{
		header('Access-Control-Allow-Origin: *');
		
		$input = json_decode(file_get_contents("php://input"), true);
		
		$this->getLibrary('PHPExcel/Classes/PHPExcel');
        $this->getLibrary('PHPExcel/Classes/PHPExcel/Reader/Excel5');
        $this->getLibrary('PHPExcel/Classes/PHPExcel/Reader/Excel2007');
		
		$objPHPExcel = new PHPExcel();
		
		$objModel = $this->loadModel("ejecucionpedido");
		$cabecera_EP = $objModel->getCabeceraEjecucionPedido(intval($input["dato"]['id']));
		
		$objPHPExcel->getProperties()
							->setCreator("Laboratorios Biomont") //Autor
							->setLastModifiedBy("Laboratorios Biomont") //Ultimo usuario que lo modificó
							->setTitle("Reporte de EP")
							->setSubject("Reporte de EP") //Asunto
							->setDescription("Reporte de EP")//Descripción
							->setKeywords("Reporte de EP") //Etiquetas
                            ->setCategory("Reporte excel");  //Categorias
		
		$objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1','GUIA: '.$cabecera_EP[0]['numGuia'])
                    ->mergeCells('A1:G1');
					
		//Cabecera			
					
		$objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A3','RUC: ')
                    ->mergeCells('A3:B3');
		
		$objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A4','Nombre / Razon Social: ')
                    ->mergeCells('A4:B4');					
		
		$objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A5','Direccion: ')
                    ->mergeCells('A5:B5');
					
		$objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A6','Prov / Dep: ')
                    ->mergeCells('A6:B6');
		
		$objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A7','Distrito: ')
                    ->mergeCells('A7:B7');
					
		$objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A8','Peso / Caja: ')
                    ->mergeCells('A8:B8');
					
					
		$objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('D3','Fecha de Emision: ')
                    ->mergeCells('D3:E3');
		
		$objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('D4','N Pedido: ')
                    ->mergeCells('D4:E4');					
		
		$objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('D5','Motivo de Traslado: ')
                    ->mergeCells('D5:E5');
					
		$objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('D6','Fecha de Traslado: ')
                    ->mergeCells('D6:E6');
		
		$objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('D7','Factura: ')
                    ->mergeCells('D7:E7');
					
		$objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('D8','Vendedor: ')
                    ->mergeCells('D8:E8');
		
		$header_bold_title = array(
        	'font' => array(
				'name'      => 'Verdana',
    	        'bold'      => true,
				'size'      => 8
            )
		);
					
		$objPHPExcel->getActiveSheet()->getStyle('A3:B8')->applyFromArray($header_bold_title);
		$objPHPExcel->getActiveSheet()->getStyle('D3:E8')->applyFromArray($header_bold_title);
		
		
		$header_title = array(
			'font' => array(
	        	'name'      => 'Verdana',
               	'size'      => 8
            ),
			'alignment' =>  array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT	
			)
		);
		
		$objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('C3',$cabecera_EP[0]['docCliente'])
                    ->mergeCells('C3:C3');
		
		$objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('C4',$cabecera_EP[0]['nomCliente'])
                    ->mergeCells('C4:C4');
		
		$objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('C5',$cabecera_EP[0]['direccion'])
                    ->mergeCells('C5:C5');
		
		$objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('C6',str_replace("  "," / ",$cabecera_EP[0]['depa_prov']))
                    ->mergeCells('C6:C6');
		
		$objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('C7',$cabecera_EP[0]['distrito'])
                    ->mergeCells('C7:C7');
		
		$objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('C8',$cabecera_EP[0]['peso']."/".$cabecera_EP[0]['caja'])
                    ->mergeCells('C8:C8');
					
		$factura = preg_match("/FA/i", $cabecera_EP[0]['factura']) ? str_replace("FA ","",$cabecera_EP[0]['factura']) : str_replace("BV ","",$cabecera_EP[0]['factura']);
		
		$objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('F3',$cabecera_EP[0]['fecEmision'])
                    ->mergeCells('F3:G3');
		
		$objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('F4',$cabecera_EP[0]['creadodesde'])
                    ->mergeCells('F4:G4');
		
		$objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('F5',$cabecera_EP[0]['tipoOperacion'])
                    ->mergeCells('F5:G5');
		
		$objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('F6',$cabecera_EP[0]['fecTraslado'])
                    ->mergeCells('F6:G6');
		
		$objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('F7',$factura)
                    ->mergeCells('F7:G7');
		
		$objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('F8',$cabecera_EP[0]['vendedor'])
                    ->mergeCells('F8:G8');
		
		$objPHPExcel->getActiveSheet()->getStyle('C3:C8')->applyFromArray($header_title);
		$objPHPExcel->getActiveSheet()->getStyle('F3:G8')->applyFromArray($header_title);
					
					
		$objPHPExcel->getActiveSheet()->setShowGridlines(false);
		
		$estilos_titulo = array(
            'borders' => array(
                'allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN), 
            )
        );

		$estilos_cabeceras = array(
        	'font' => array(
	        	'name'      => 'Verdana',
    	        'bold'      => true,
        	    'italic'    => false,
                'strike'    => false,
               	'size'      => 11,
	            'color'     => array('rgb' => '000000')
				
            ),
			'alignment' =>  array(
								'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
								'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
								'rotation'   => 0,
								'wrap'       => TRUE
			)
        );
		
		$estilos_celdas = array(
        	'font' => array(
	        	'name'      => 'Verdana',
    	        'bold'      => false,
        	    'italic'    => false,
                'strike'    => false,
               	'size'      => 8,
	            'color'     => array('rgb' => '000000')
				
            ),
            'borders' => array(
								'allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN)), 
								'alignment' =>  array(
													'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_GENERAL,
													'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
													'rotation'   => 0,
													'wrap'       => FALSE
								)
        );
			
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A10',  "Item");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B10',  "Codigo");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C10',  "Descripcion");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D10',  "Lote");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E10',  "Vencimiento");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F10',  "Unidad");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G10',  "Cantidad");
		
		$detalle_EP = $objModel->getDetalleEjecucionPedido1(intval($input["dato"]['id']));
		
		$i=11;
		foreach($detalle_EP as $det){	

			$contiene_numeral = strpos($det['lote'],"#");
			if($contiene_numeral){
				$lote=explode("#",$det['lote'])[0];
			}else{
				$lote=$det['lote'];
			}		

			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.($i), $i-10);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.($i), $det['itemid']);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.($i), $det['description']);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.($i), $lote);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.($i), $det['fechacaducidad']);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.($i), $det['unidad']);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.($i), number_format($det['quantity'], 2));
			
			$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':G'.$i)->applyFromArray($estilos_celdas);
			$objPHPExcel->getDefaultStyle()->getAlignment('A'.$i.':G'.$i)->setWrapText(true);

			$i++;
		}
		
		$objPHPExcel->getActiveSheet()->getStyle('A1:G1')->applyFromArray($estilos_cabeceras);
		$objPHPExcel->getActiveSheet()->getStyle('A10:G10')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('A10:G10')->applyFromArray($estilos_titulo);

		$sheet = $objPHPExcel->getActiveSheet();
		foreach($sheet->getColumnIterator() as $column){
			$sheet->getColumnDimension($column->getColumnIndex())->setAutosize(true);
		}
		
		$objPHPExcel->getActiveSheet()->setTitle('EXPORTAR EP');
		
		$objPHPExcel->setActiveSheetIndex(0);

		
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		
		$archivo = "EXPORT_EP_".date('dmYHis').".xlsx";
		
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$archivo.'"');
		header('Cache-Control: max-age=0');
		header('Cache-Control: cache, must-revalidate');
		header('Pragma: public');

		ob_start();
        $objWriter->save("php://output");
        $xlsData = ob_get_contents();
        ob_end_clean();

        $response =  [
					'msg' => 'ok',
					'file' => "data:application/vnd.ms-excel;base64,".base64_encode($xlsData),
					'nombre' => $archivo
				];
		
		
		header('Content-type: application/json; charset=utf-8');
		echo json_encode($response);
	}

}