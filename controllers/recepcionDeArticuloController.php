<?php

class recepcionDeArticuloController extends Controller
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
	
	/* INICIO NUMERO DE ANALISIS */
	
	public function getUltimosCorrelativosNumeroAnalisis()
	{
		$objModel = $this->loadModel("recepcionDeArticulo");
		$res = $objModel->getUltimosCorrelativosNumeroAnalisis();
		$res_MP = explode("/",$res)[0];
		$res_ME = explode("/",$res)[1];
		$res_MCI = explode("/",$res)[2];
		
		header('Access-Control-Allow-Origin: *');
		header("Content-type: application/json; charset=utf-8");
		echo json_encode([
				"MP" 	=> $res_MP,
				"ME" 	=> $res_ME,
				"MCI" 	=> $res_MCI,
		]);
	}
	
	public function getCorrelativo()
	{
		$input = json_decode(file_get_contents("php://input"), true);
		
		$posicion_primer_cero = strpos($input["dato"]["codigo"],"0");
		$cod_evaluar = substr($input["dato"]["codigo"],0,$posicion_primer_cero);
		
		$objModel = $this->loadModel("recepcionDeArticulo");
		$result = $objModel->getCorrelativo($cod_evaluar);
		
		$linea = explode("-",$result)[0];
		$correlat = explode("-",$result)[1];
		
		header('Access-Control-Allow-Origin: *');
		header("Content-type: application/json; charset=utf-8");
		echo json_encode([
				"linea" 	=> $linea,
				"correlat" 	=> intval($correlat),
		]);
	}
	
	public function updateCorrelativoNumeroAnalisis()
	{
		$input = json_decode(file_get_contents("php://input"), true);
		
		$objModel = $this->loadModel("recepcionDeArticulo");
		$res = $objModel->updateCorrelativoNumeroAnalisis($input["dato"]["linea"],$input["dato"]["correlativo"]);
		
		header('Access-Control-Allow-Origin: *');
		header("Content-type: application/json; charset=utf-8");
		echo json_encode([
				"res" 	=> $res
		]);
	}
	
	/* FIN NUMERO DE ANALISIS */
	
	/* INICIO IMPRESION DE ETIQUETAS */
	
	/*public function imprimirEtiquetaIngreso()
	{
		header('Access-Control-Allow-Origin: *');

		$input = json_decode(file_get_contents("php://input"), true);
		
		$this->getLibrary('mpdf/mpdf');

		$mpdf = new mPDF('utf-8', 'TICKET', '', '', 2, 2, 2, 0, 1, 3);

		$mpdf->SetTitle('IMPRESION DE ETIQUETA INGRESO');
		
		$mpdf->SetDefaultFont("Arial");

		$objModel = $this->loadModel("recepcionDeArticulo");
		$dato = $objModel->TransactionxCODPROD_ET_INGRESO($input['obj']['id_recep'],$input['obj']['cod_prod']);
		
		$cont = 0;
		foreach($dato as $dat){
			
			$inventorynumber = explode("#",$dat['inventorynumber']);
			
			$index = strpos($dat['itemid'], "0");
			$cod_letra = substr($dat['itemid'],0,$index);
			
			if($dat['expirationdate']==null || $dat['expirationdate']=="" || $dat['expirationdate']=="01/01/1970"){
				$fecha_exp = "";
			}else{
				$fecha_exp = $dat['expirationdate'];
			}
			
			switch($cod_letra){
				case 'MP':
				case 'MMP':
				case 'UL':
				case 'MCH':
				case 'SU':
				case 'MCI':
					$lote = $inventorynumber[0];
					//$v_potencia = "";
					$num_analisis = $inventorynumber[2];
					//$peso = $inventorynumber[3];
					$fecha_ven_poten = $fecha_exp;
					break;
				case 'ME':
				case 'MV':
				case 'BCH':
				case 'MMV':
				case 'MME':
					$lote = $inventorynumber[0];
					//$v_potencia = "";
					$num_analisis = $inventorynumber[2];
					//$peso = $inventorynumber[3];
					$fecha_ven_poten = $fecha_exp ." - ". $inventorynumber[1];
					break;
				default:
					$lote = $inventorynumber[0];
					//$v_potencia = $inventorynumber[1];
					$num_analisis = $inventorynumber[2];
					//$peso = "";
					$fecha_ven_poten = $inventorynumber[1];
					break;
			}
			
			$mpdf->WriteHTML("
				<table width='100%'>
					<tr>
						<td style='width:15%;'>
							<img src='http://6462530.shop.netsuite.com/core/media/media.nl?id=4499&c=6462530&h=s4vSW99RWLBxlBgfKjmRz8LD0h85_fj5MMZjCgrJwYDFq4v7' width='67' height='27'>
						</td>
						<td style='width:75%;' align='center'>
							<span style='font-size:16px;'><strong>LABORATORIOS BIOMONT S.A.</strong></span>
						</td>
						<td style='width:10%;'>
							F-AL.001.02
						</td>
					</tr>
				</table>
			");
			
			$mpdf->Ln(1);
			
			$mpdf->WriteHTML("
				<table width='100%' style='border: 1px solid black;border-collapse: collapse;'>
					<tr>
						<td style='width:23%;border: 1px solid black;border-collapse: collapse;font-size: 11px;padding:4.5px 3px 5px 3px;font-weight:bold;'>NRO DOC</td>
						<td style='width:35%;border: 1px solid black;border-collapse: collapse;font-size: 11px;padding:4.5px 3px 5px 3px;'>".$dat['TranID']."</td>
						<td style='width:20%;border: 1px solid black;border-collapse: collapse;font-size: 11px;padding:4.5px 3px 5px 3px;font-weight:bold;'>FECHA</td>
						<td style='width:22%;border: 1px solid black;border-collapse: collapse;font-size: 11px;padding:4.5px 3px 5px 3px;'>".$dat['trandate']."</td>
					</tr>
					<tr>
						<td style='width:23%;border: 1px solid black;border-collapse: collapse;font-size: 11px;padding:4.5px 3px 5px 3px;font-weight:bold;'>PROVEEDOR</td>
						<td style='width:77%;border: 1px solid black;border-collapse: collapse;font-size: 11px;padding:4.5px 3px 5px 3px;' colspan=3>".$dat['entityid']."</td>
					</tr>
					<tr>
						<td style='width:23%;border: 1px solid black;border-collapse: collapse;font-size: 11px;padding:4.5px 3px 5px 3px;font-weight:bold;'>CODIGO</td>
						<td style='width:35%;border: 1px solid black;border-collapse: collapse;font-size: 11px;padding:4.5px 3px 5px 3px;'>".$dat['itemid']."</td>
						<td style='width:20%;border: 1px solid black;border-collapse: collapse;font-size: 11px;padding:4.5px 3px 5px 3px;font-weight:bold;'>DUA</td>
						<td style='width:22%;border: 1px solid black;border-collapse: collapse;font-size: 11px;padding:4.5px 3px 5px 3px;'>".$dat['dua']."</td>
					</tr>
					<tr>
						<td style='width:23%;border: 1px solid black;border-collapse: collapse;font-size: 11px;padding:4.5px 3px 5px 3px;font-weight:bold;'>DESCRIPCION</td>
						<td style='width:77%;border: 1px solid black;border-collapse: collapse;font-size: 11px;padding:4.5px 3px 5px 3px;' colspan=3>".$dat['description']."</td>
					</tr>
					<tr>
						<td style='width:23%;border: 1px solid black;border-collapse: collapse;font-size: 11px;padding:4.5px 3px 5px 3px;font-weight:bold;'>CANTIDAD</td>
						<td style='width:35%;border: 1px solid black;border-collapse: collapse;font-size: 11px;padding:4.5px 3px 5px 3px;'>".$dat['quantity']."</td>
						<td style='width:20%;border: 1px solid black;border-collapse: collapse;font-size: 11px;padding:4.5px 3px 5px 3px;font-weight:bold;'>UND</td>
						<td style='width:22%;border: 1px solid black;border-collapse: collapse;font-size: 11px;padding:4.5px 3px 5px 3px;'>".$dat['abbreviation']."</td>
					</tr>
					<tr>
						<td style='width:23%;border: 1px solid black;border-collapse: collapse;font-size: 11px;padding:4.5px 3px 5px 3px;font-weight:bold;'>LOTE</td>
						<td style='width:35%;border: 1px solid black;border-collapse: collapse;font-size: 11px;padding:4.5px 3px 5px 3px;'>".$lote."</td>
						<td style='width:20%;border: 1px solid black;border-collapse: collapse;font-size: 11px;padding:4.5px 3px 5px 3px;font-weight:bold;'>F.VEN / VER</td>
						<td style='width:22%;border: 1px solid black;border-collapse: collapse;font-size: 11px;padding:4.5px 3px 5px 3px;'>".$fecha_ven_poten."</td>
					</tr>
					<tr>
						<td style='width:23%;border: 1px solid black;border-collapse: collapse;font-size: 11px;padding:4.5px 3px 5px 3px;font-weight:bold;'>OBSERVACION</td>
						<td style='width:77%;border: 1px solid black;border-collapse: collapse;font-size: 11px;padding:4.5px 3px 5px 3px;' colspan=3>".$dat['memo']."</td>
					</tr>
				</table>
			");

			$footer = "_________________________________________________________";
			$footer.= "<div align='center'><span style='font-size:23px;font-weight:bold;'>ALMACEN DE ".strtoupper($dat['name'])."</span></div>";

			$mpdf->SetHTMLFooter($footer);
			
			$cont++;
			
			if($cont < count($dato)){
				$mpdf->AddPage();
			}		
		}	
		
		date_default_timezone_set('America/Lima');
		
		$fecha = date("YmdHis",time());
		
		$archivo= "downloads/ET_INGRESO_".$dato[0]['TranID']."_".$fecha.".pdf";
		
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
	
	public function imprimirEtiquetaIngreso()
	{
		header('Access-Control-Allow-Origin: *');

		$input = json_decode(file_get_contents("php://input"), true);
		
		$this->getLibrary('mpdf/mpdf');

		$mpdf = new mPDF('utf-8', 'TICKET', '', '', 2, 2, 2, 0, 1, 1);

		$mpdf->SetTitle('IMPRESION DE ETIQUETA INGRESO');
		
		$mpdf->SetDefaultFont("Arial");

		$objModel = $this->loadModel("recepcionDeArticulo");
		$dato = $objModel->TransactionxCODPROD_ET_INGRESO($input['obj']['id_recep'],$input['obj']['cod_prod']);
		
		$cont = 0;
		foreach($dato as $dat){
			
			$inventorynumber = explode("#",$dat['inventorynumber']);
			
			$index = strpos($dat['itemid'], "0");
			$cod_letra = substr($dat['itemid'],0,$index);
			
			if($dat['expirationdate']==null || $dat['expirationdate']=="" || $dat['expirationdate']=="01/01/1970"){
				$fecha_exp = "";
			}else{
				$fecha_exp = $dat['expirationdate'];
			}
			
			switch($cod_letra){
				case 'MP':
				case 'MMP':
				case 'UL':
				case 'MCH':
				case 'SU':
				case 'MCI':
					$lote = $inventorynumber[0];
					//$v_potencia = "";
					$num_analisis = $inventorynumber[2];
					//$peso = $inventorynumber[3];
					$fecha_ven_poten = $fecha_exp;
					break;
				case 'ME':
				case 'MV':
				case 'BCH':
				case 'MMV':
				case 'MME':
					$lote = $inventorynumber[0];
					//$v_potencia = "";
					$num_analisis = $inventorynumber[2];
					//$peso = $inventorynumber[3];
					$fecha_ven_poten = $fecha_exp ." - ". $inventorynumber[1];
					break;
				default:
					$lote = $inventorynumber[0];
					//$v_potencia = $inventorynumber[1];
					$num_analisis = $inventorynumber[2];
					//$peso = "";
					$fecha_ven_poten = $inventorynumber[1];
					break;
			}
			
			$mpdf->WriteHTML("
				<table width='100%'>
					<tr>
						<td style='width:15%;'>
							<img src='http://6462530.shop.netsuite.com/core/media/media.nl?id=4499&c=6462530&h=s4vSW99RWLBxlBgfKjmRz8LD0h85_fj5MMZjCgrJwYDFq4v7' width='67' height='27'>
						</td>
						<td style='width:75%;' align='center'>
							<span style='font-size:16px;'><strong>LABORATORIOS BIOMONT S.A.</strong></span>
						</td>
						<td style='width:10%;'>
							F-AL.001.03
						</td>
					</tr>
				</table>
			");
			
			$mpdf->Ln(1);
			//<td style='width:35%;border: 1px solid black;border-collapse: collapse;font-size: 11px;padding:4.5px 3px 5px 3px;'>".$dat['itemid']."</td>
			//style='height:0.5px;width:1%;'
			
			//
			$barcode_C128A = "<barcode code='".$dat['itemid']."' type='C128A' size='0.77' height='1.3px' />";
			
			/* primera opcion
			$barcode_C93 = "<barcode code='".$dat['itemid']."' type='C93' size='0.6' />";
					<tr>
						<td rowspan='2' style='width:23%;border: 1px solid black;border-collapse: collapse;font-size: 11px;padding:4.5px 3px 5px 3px;font-weight:bold;'>CODIGO</td>
						<td rowspan='2' style='width:35%;border: 1px solid black;border-collapse: collapse;font-size: 11px;text-align:center'>".$barcode_C93."<span>".$dat['itemid']."</span></td>
						<td style='width:20%;border: 1px solid black;border-collapse: collapse;font-size: 11px;padding:4.5px 3px 5px 3px;font-weight:bold;'>CANTIDAD</td>
						<td style='width:22%;border: 1px solid black;border-collapse: collapse;font-size: 11px;padding:4.5px 3px 5px 3px;'>".$dat['quantity']."</td>
					</tr>
			*/
			
			/* segunda opcion
			$barcode_C93 = "<barcode code='".$dat['itemid']."' type='C93' height='0.7px' />";
					<tr>
						<td rowspan='2' colspan='2' style='width:23%;border: 1px solid black;border-collapse: collapse;font-size: 11px;text-align:center'>".$barcode_C93."<span>".$dat['itemid']."</span></td>
						<td style='width:20%;border: 1px solid black;border-collapse: collapse;font-size: 11px;padding:4.5px 3px 5px 3px;font-weight:bold;'>CANTIDAD</td>
						<td style='width:22%;border: 1px solid black;border-collapse: collapse;font-size: 11px;padding:4.5px 3px 5px 3px;'>".$dat['quantity']."</td>
					</tr>
			*/
			//<span>CODIGO</span>".$barcode_C128A."<span>".$dat['itemid']."</span>
			//<td rowspan='3' style='width:23%;border: 1px solid black;border-collapse: collapse;font-size: 11.2px;padding:4.5px 3px 5px 3px;font-weight:bold;'>CODIGO</td>
			
			$mpdf->WriteHTML("
				<table width='100%' style='border: 1px solid black;border-collapse: collapse;'>
					<tr>
						<td style='width:24.2%;border: 1px solid black;border-collapse: collapse;font-size: 11.2x;padding:4.5px 3px 5px 3px;font-weight:bold;'>NRO DOC</td>
						<td style='width:38.8%;border: 1px solid black;border-collapse: collapse;font-size: 11.2px;padding:4.5px 3px 5px 3px;'>".$dat['TranID']."</td>
						<td style='width:13%;border: 1px solid black;border-collapse: collapse;font-size: 11.2px;padding:4.5px 3px 5px 3px;font-weight:bold;'>FECHA</td>
						<td style='width:24%;border: 1px solid black;border-collapse: collapse;font-size: 11.2px;padding:4.5px 3px 5px 3px;'>".$dat['trandate']."</td>
					</tr>
					<tr>
						<td style='width:24.2%;border: 1px solid black;border-collapse: collapse;font-size: 11.2px;padding:4.5px 3px 5px 3px;font-weight:bold;'>PROVEEDOR</td>
						<td colspan=3 style='width:75.8%;border: 1px solid black;border-collapse: collapse;font-size: 11.2px;padding:4.5px 3px 5px 3px;'>".$dat['entityid']."</td>
					</tr>
					<tr>
						<td rowspan='3' colspan='2' style='width:53%;border: 1px solid black;border-collapse: collapse;font-size: 11.2px;text-align:center'>
							<table>
								<tr>
									<td style='font-size: 10.5px;'>
									CODIGO<br>".$barcode_C128A."<br>".$dat['itemid']."
									</td>
								</tr>
							</table>
						</td>
						<td style='width:13%;border: 1px solid black;border-collapse: collapse;font-size: 11.2px;padding:4.5px 3px 5px 3px;font-weight:bold;'>CANT</td>
						<td style='width:24%;border: 1px solid black;border-collapse: collapse;font-size: 11.2px;padding:4.5px 3px 5px 3px;'>".$dat['quantity']."</td>
					</tr>
					<tr>
						<td style='width:13%;border: 1px solid black;border-collapse: collapse;font-size: 11.2px;padding:4.5px 3px 5px 3px;font-weight:bold;'>UND</td>
						<td style='width:24%;border: 1px solid black;border-collapse: collapse;font-size: 11.2px;padding:4.5px 3px 5px 3px;'>".$dat['abbreviation']."</td>
					</tr>
					<tr>
						<td style='width:13%;border: 1px solid black;border-collapse: collapse;font-size: 11.2px;padding:4.5px 3px 5px 3px;font-weight:bold;'>F.V./VER</td>
						<td style='width:24%;border: 1px solid black;border-collapse: collapse;font-size: 11.2px;padding:4.5px 3px 5px 3px;'>".$fecha_ven_poten."</td>
					</tr>
					<tr>
						<td style='width:24.2%;border: 1px solid black;border-collapse: collapse;font-size: 11.2px;padding:4.5px 3px 5px 3px;font-weight:bold;'>DESCRIPCION</td>
						<td colspan=3 style='width:75.8%;border: 1px solid black;border-collapse: collapse;font-size: 11.2px;padding:4.5px 3px 5px 3px;'>".$dat['description']."</td>
					</tr>
					<tr>
						<td style='width:24.2%;border: 1px solid black;border-collapse: collapse;font-size: 11.2px;padding:4.5px 3px 5px 3px;font-weight:bold;'>LOTE</td>
						<td colspan=3 style='width:75.8%;border: 1px solid black;border-collapse: collapse;font-size: 11.2px;padding:4.5px 3px 5px 3px;'>".$lote."</td>
					</tr>
				</table>
			");
			
			$mpdf->WriteHTML("
				<table width='100%' style='border-collapse: collapse;'>
					<tr>
						<td style='width:23.9%;border-bottom: 1px solid black;border-left: 1px solid black;border-collapse: collapse;font-size: 11.2px;padding:4.5px 3px 5px 3px;font-weight:bold;'>OBSERVACION</td>
						<td colspan=3 style='width:75.4%;border-bottom: 1px solid black;border-left: 1px solid black;border-right: 1px solid black;border-collapse: collapse;font-size: 11.2px;padding:4.5px 3px 5px 3px;'>".$dat['memo']."</td>
					</tr>
				</table>
			");

			$footer = "_________________________________________________________";
			$footer.= "<div align='center'><span style='font-size:23px;font-weight:bold;'>ALMACEN DE ".strtoupper($dat['name'])."</span></div>";

			$mpdf->SetHTMLFooter($footer);
			
			$cont++;
			
			if($cont < count($dato)){
				$mpdf->AddPage();
			}		
		}	
		
		date_default_timezone_set('America/Lima');
		
		$fecha = date("YmdHis",time());
		
		$archivo= "downloads/ET_INGRESO_".$dato[0]['TranID']."_".$fecha.".pdf";
		
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
	
	public function imprimirEtiquetaMateriaPrima()
	{
		
		header('Access-Control-Allow-Origin: *');

		$input = json_decode(file_get_contents("php://input"), true);
		
		$this->getLibrary('mpdf/mpdf');

		$mpdf = new mPDF('utf-8', 'TICKET', '', '', 2, 2, 2, 2, 1, 3); //mgl,mgr,mgt,mgb,mgh,mgf

		$mpdf->SetTitle('IMPRESION DE ETIQUETA MATERIA PRIMA');

		$objModel = $this->loadModel("recepcionDeArticulo");
		$dato = $objModel->TransactionxCODPROD_ET_MATPRIMA($input['obj']['id_recep'],$input['obj']['cod_prod']);
		
		$cont = 0;
		foreach($dato as $dat){
			
			$inventorynumber = explode("#",$dat['inventorynumber']);
			
			$index = strpos($dat['itemid'], "0");
			$cod_letra = substr($dat['itemid'],0,$index);
			
			switch($cod_letra){
				case 'MP':
				case 'MMP':
					$lote = $inventorynumber[0];
					$v_potencia = $inventorynumber[1];
					$num_analisis = $inventorynumber[2];
					$peso = $inventorynumber[3];
					break;
				default:
					$lote = $inventorynumber[0];
					$v_potencia = $inventorynumber[1];
					$num_analisis = $inventorynumber[2];
					$peso = $inventorynumber[3];
					break;
			}
			
			if($dat['expirationdate']==null || $dat['expirationdate']=="" || $dat['expirationdate']=="01/01/1970"){
				$fecha_exp = "";
			}else{
				$fecha_exp = $dat['expirationdate'];
			}
			
			if(explode('|',$dat['arreglo'])[1]==null || explode('|',$dat['arreglo'])[1]=="" || explode('|',$dat['arreglo'])[1]=="01/01/1970"){
				$fecha_ven = "";
			}else{
				$fecha_ven = explode('|',$dat['arreglo'])[1];
			}
			
			if($peso!=""){
				$cant =$dat['quantity'];
			}else{
				$cant = explode('|',$dat['arreglo'])[2];
			}
			
			switch($dat['undPrincipal']){
				case 'NIU':
				case 'KGM':
					switch($dat['abbreviation']){
						case 'GR':
							$cant_aprobada = floatval(explode('|',$dat['arreglo'])[2]*1000);
							break;
						default:
							$cant_aprobada = $cant;
							break;
					}
					break;
				case 'MLL':
				case 'MIL':
				case 'GRM':
					//$cant_aprobada = floatval(explode('|',$dat['arreglo'])[2]*1000);
					$cant_aprobada = explode('|',$dat['arreglo'])[2];
					break;
				case 'GLL':
					//$cant_aprobada = floatval(explode('|',$dat['arreglo'])[2]/3.8);
					$cant_aprobada = explode('|',$dat['arreglo'])[2];
					break;
				case 'LTR':
					$cant_aprobada = floatval(explode('|',$dat['arreglo'])[2]);
					break;	
			}
			
			$cant_aprobada = str_replace(",","",$cant_aprobada);
		
			$mpdf->SetDefaultFont("Arial");
			
			$mpdf->WriteHTML("<div style='position:absolute;font-size:7px;font-weight:bold;'>
								LABORATORIOS BIOMONT S.A.
							</div>
							<div style='font-size:7px;font-weight:bold;text-align:right;'>
								F-CC.001.04
							</div>
							<div align='center' style='font-size:42px;font-weight:bold;padding:0px;'>
								APROBADO
							</div>
							<div align='right' style='font-size:8px;font-weight:bold;'>
								MATERIA PRIMA &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;CONTROL DE CALIDAD
							</div>
							");
			
			$mpdf->Ln(1);
			
			$mpdf->WriteHTML("
				<table width='100%'>
					<tr>
						<td style='width:24%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>NOMBRE</td>
						<td style='width:1%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>:</td>
						<td style='width:75%;font-size: 11px;padding:2px 2px 2px 2px;' colspan=4>".$dat['description']."</td>
					</tr>
					<tr>
						<td style='width:24%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>LOTE</td>
						<td style='width:1%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>:</td>
						<td style='width:75%;font-size: 11px;padding:2px 2px 2px 2px;' colspan=4>".$lote."</td>
					</tr>
					<tr>
						<td style='width:24%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>CODIGO</td>
						<td style='width:1%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>:</td>
						<td style='width:22%;font-size: 11px;padding:2px 2px 2px 2px;'>".$dat['itemid']."</td>
						<td style='width:32%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>NRO DOC.</td>
						<td style='width:1%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>:</td>
						<td style='width:22%;font-size: 11px;padding:2px 2px 2px 2px;'>".$dat['TranID']."</td>
					</tr>
					<tr>
						<td style='width:24%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>N° ANALISIS</td>
						<td style='width:1%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>:</td>
						<td style='width:22%;font-size: 11px;padding:2px 2px 2px 2px;'>".$num_analisis."</td>
						<td style='width:32%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>CANT. APROBADA</td>
						<td style='width:1%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>:</td>
						<td style='width:22%;font-size: 11px;padding:2px 2px 2px 2px;'>".$cant_aprobada." ".$dat['abbreviation']."</td>
					</tr>
					<tr>
						<td style='width:24%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>FEC. ANALISIS</td>
						<td style='width:1%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>:</td>
						<td style='width:22%;font-size: 11px;padding:2px 2px 2px 2px;'>".$fecha_ven."</td>
						<td style='width:32%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>FEC. EXPIRA</td>
						<td style='width:1%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>:</td>
						<td style='width:22;font-size: 11px;padding:2px 2px 2px 2px;'>".$fecha_exp."</td>
					</tr>
					<tr>
						<td style='width:24%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>POTENCIA T/C</td>
						<td style='width:1%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>:</td>
						<td style='width:22%;font-size: 11px;padding:2px 2px 2px 2px;'>".$v_potencia."</td>
						<td style='width:32%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>PESO ESPECIFICO</td>
						<td style='width:1%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>:</td>
						<td style='width:22%;font-size: 11px;padding:2px 2px 2px 2px;'>".$peso."</td>
					</tr>
					<tr>
						<td style='width:25%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>OBSERVACIONES</td>
						<td style='width:1%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>:</td>
						<td style='width:73%;font-size: 11px;padding:2px 2px 2px 2px;' colspan=4>".$dat['memoControl']."</td>
					</tr>
				</table>
			");
			
			$footer = "<table width='100%' nowrap>
						   <tr>
							 <td style='width:15%;font-size: 8px;'></td>
							 <td align='center' style='width:25%;font-size: 8px;'><hr style='width:100%;color:black;' /></td>
							 <td style='width:20%;font-size: 8px;'></td>
							 <td align='center' style='width:25%;font-size: 8px;'><hr style='width:100%;color:black;' /></td>
							 <td style='width:15%;font-size: 8px;'></td>
						   </tr>
						   <tr>
							 <td style='width:15%;font-size: 8px;'></td>
							 <td align='center' style='width:25%;font-size: 8px;'><strong>ANALISTA</strong></td>
							 <td style='width:20%;font-size: 8px;'></td>
							 <td align='center' style='width:25%;font-size: 8px;'><strong>JEFE DE CC</strong></td>
							 <td style='width:15%;font-size: 8px;'></td>
						   </tr>
					 </table>";
					 
			$mpdf->SetHTMLFooter($footer);
		
			$cont++;
			
			if($cont < count($dato)){
				$mpdf->AddPage();
			}		
		}
		
		date_default_timezone_set('America/Lima');
		
		$fecha = date("YmdHis",time());

		$archivo= "downloads/ET_MATPRIMA_".$dato[0]['TranID']."_".$fecha.".pdf";
		
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
	
	public function imprimirEtiquetaMaterialEmpaque()
	{
		header('Access-Control-Allow-Origin: *');

		$input = json_decode(file_get_contents("php://input"), true);
		
		$this->getLibrary('mpdf/mpdf');

		$mpdf = new mPDF('utf-8', 'TICKET', '', '', 2, 2, 2, 2, 1, 3); //mgl,mgr,mgt,mgb,mgh,mgf

		$mpdf->SetTitle('IMPRESION DE ETIQUETA MATERIAL DE EMPAQUE Y ENVASE');

		$objModel = $this->loadModel("recepcionDeArticulo");
		$dato = $objModel->TransactionxCODPROD_ET_EMPENVASE($input["obj"]['id_recep'],$input["obj"]['cod_prod']);
		
		$cont = 0;
		foreach($dato as $dat){
			
			$inventorynumber = explode("#",$dat['inventorynumber']);
			
			$index = strpos($dat['itemid'], "0");
			$cod_letra = substr($dat['itemid'],0,$index);
			
			switch($cod_letra){
				case 'MP':
				case 'MMP':
					$lote = $inventorynumber[0];
					$version = $inventorynumber[1];
					$num_analisis = $inventorynumber[2];
					$peso = $inventorynumber[3];
					break;
				default:
					$lote = $inventorynumber[0];
					$version = $inventorynumber[1];
					$num_analisis = $inventorynumber[2];
					$peso = "";
					break;
			}

			if($dat['expirationdate']==null || $dat['expirationdate']=="" || $dat['expirationdate']=="01/01/1970"){
				$fecha_exp = "";
			}else{
				$fecha_exp = $dat['expirationdate'];
			}			
			
			if(explode('|',$dat['arreglo'])[1]==null || explode('|',$dat['arreglo'])[1]=="" || explode('|',$dat['arreglo'])[1]=="01/01/1970"){
				$fecha_ven = "";
			}else{
				$fecha_ven = explode('|',$dat['arreglo'])[1];
			}
			
			switch($dat['undPrincipal']){
				case 'NIU':
					//$cant_aprobada = floatval(explode('|',$dat['arreglo'])[2]*1000);
					$cant_aprobada = explode('|',$dat['arreglo'])[2];
					break;
				case 'KGM':
					if($dat['TranID']=='823'){
						$cant_aprobada = floatval(explode('|',$dat['arreglo'])[2]*1000);
					}else{
						$cant_aprobada = explode('|',$dat['arreglo'])[2];
					}
					break;
				case 'MLL':
				case 'MIL':
				case 'GRM':
					$cant_aprobada = floatval(explode('|',$dat['arreglo'])[2]*1000);
					break;
				case 'GLL':
					$cant_aprobada = floatval(explode('|',$dat['arreglo'])[2]/3.8);
					break;
				case 'LTR':
					$cant_aprobada = floatval(explode('|',$dat['arreglo'])[2]);
					break;	
			}
			
			$cant_aprobada = str_replace(",","",$cant_aprobada);
			
			$mpdf->SetDefaultFont("Arial");
			
			$mpdf->WriteHTML("<div style='position:absolute;font-size:7px;font-weight:bold;'>
								LABORATORIOS BIOMONT S.A.
							</div>
							<div style='font-size:7px;font-weight:bold;text-align:right;'>
								F-CC.002.03
							</div>
							<div align='center' style='font-size:42px;font-weight:bold;padding:0px;'>
								APROBADO
							</div>
							<div align='right' style='font-size:8px;font-weight:bold;'>
								MATERIAL DE EMPAQUE / ENVASE&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;CONTROL DE CALIDAD
							</div>
							");
			
			$mpdf->Ln(1);
			
			$mpdf->WriteHTML("
				<table width='100%'>
					<tr>
						<td style='width:29%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>NRO ANALISIS</td>
						<td style='width:1%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>:</td>
						<td style='width:70%;font-size: 11px;padding:2px 2px 2px 2px;' colspan=2>".$num_analisis."</td>
					</tr>
					<tr>
						<td style='width:29%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>NRO DOCUMENTO</td>
						<td style='width:1%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>:</td>
						<td style='width:70%;font-size: 11px;padding:2px 2px 2px 2px;' colspan=2>".$dat['TranID']."</td>
					</tr>
					<tr>
						<td style='width:29%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>NOMBRE</td>
						<td style='width:1%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>:</td>
						<td style='width:70%;font-size: 11px;padding:2px 2px 2px 2px;' colspan=4>".$dat['description']."</td>
					</tr>
					<tr>
						<td style='width:29%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>CODIGO</td>
						<td style='width:1%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>:</td>
						<td style='width:28%;font-size: 11px;padding:2px 2px 2px 2px;'>".$dat['itemid']."</td>
						<td style='width:21%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>LOTE</td>
						<td style='width:1%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>:</td>
						<td style='width:22%;font-size: 11px;padding:2px 2px 2px 2px;'>".$lote."</td>
					</tr>
					<tr>
						<td style='width:29%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>CANT. APROBADA</td>
						<td style='width:1%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>:</td>
						<td style='width:28%;font-size: 11px;padding:2px 2px 2px 2px;'>".$cant_aprobada." ".$dat['abbreviation']."</td>
						<td style='width:21%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>FEC. EXPIRA:</td>
						<td style='width:1%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>:</td>
						<td style='width:22%;font-size: 11px;padding:2px 2px 2px 2px;'>".$fecha_exp."</td>
					</tr>
					<tr>
						<td style='width:29%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>FEC. ANALISIS</td>
						<td style='width:1%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>:</td>
						<td style='width:28%;font-size: 11px;padding:2px 2px 2px 2px;'>".$fecha_ven."</td>
						<td style='width:21%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>VERSION</td>
						<td style='width:1%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>:</td>
						<td style='width:22%;font-size: 11px;padding:2px 2px 2px 2px;'>".$version."</td>
					</tr>
					<tr>
						<td style='width:29%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>OBSERVACIONES</td>
						<td style='width:1%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>:</td>
						<td style='width:70%;font-size: 11px;padding:2px 2px 2px 2px;' colspan=4>".$dat['memoControl']."</td>
					</tr>
				</table>
			");
			
			$footer = "<table width='100%' nowrap>
						   <tr>
							 <td style='width:5%;font-size: 8px;'></td>
							 <td align='center' style='width:25%;font-size: 8px;'><hr style='width:100%;color:black;' /></td>
							 <td style='width:20%;font-size: 8px;'></td>
							 <td align='center' style='width:45%;font-size: 8px;'><hr style='width:100%;color:black;' /></td>
							 <td style='width:5%;font-size: 8px;'></td>
						   </tr>
						   <tr>
							 <td style='width:5%;font-size: 8px;'></td>
							 <td align='center' style='width:25%;font-size: 8px;'><strong>ANALISTA</strong></td>
							 <td style='width:20%;font-size: 8px;'></td>
							 <td align='center' style='width:45%;font-size: 8px;'><strong>JEFE DE CC</strong></td>
							 <td style='width:5%;font-size: 8px;'></td>
						   </tr>
					 </table>";
					 
			$mpdf->SetHTMLFooter($footer);
			
			$cont++;
			
			if($cont < count($dato)){
				$mpdf->AddPage();
			}		
		}
		
		date_default_timezone_set('America/Lima');
		
		$fecha = date("YmdHis",time());
		
		$archivo= "downloads/ET_EMPENVASE_".$dato[0]['TranID']."_".$fecha.".pdf";
		
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
	
	public function imprimirEtiquetaRechazado()
	{
		header('Access-Control-Allow-Origin: *');

		$input = json_decode(file_get_contents("php://input"), true);
		
		$this->getLibrary('mpdf/mpdf');

		$mpdf = new mPDF('utf-8', 'TICKET', '', '', 2, 2, 2, 2, 1, 2); //mgl,mgr,mgt,mgb,mgh,mgf

		$mpdf->SetTitle('IMPRESION DE ETIQUETA RECHAZADO');

		$objModel = $this->loadModel("recepcionDeArticulo");
		$dato = $objModel->TransactionxCODPROD_ET_RECHAZADO($input["obj"]['id_recep'],$input["obj"]['cod_prod']);
		
		$cont = 0;
		foreach($dato as $dat){
			
			$inventorynumber = explode("#",$dat['inventorynumber']);
			
			$index = strpos($dat['itemid'], "0");
			$cod_letra = substr($dat['itemid'],0,$index);
			
			switch($cod_letra){
				case 'MP':
				case 'MMP':
					$lote = $inventorynumber[0];
					//$version = $inventorynumber[1];
					$num_analisis = $inventorynumber[2];
					//$peso = $inventorynumber[3];
					break;
				default:
					$lote = $inventorynumber[0];
					//$version = $inventorynumber[1];
					$num_analisis = $inventorynumber[2];
					//$peso = "";
					break;
			}			
			
			if(explode('|',$dat['arreglo'])[1]==null || explode('|',$dat['arreglo'])[1]=="" || explode('|',$dat['arreglo'])[1]=="01/01/1970"){
				$fecha_ven = "";
			}else{
				$fecha_ven = explode('|',$dat['arreglo'])[1];
			}
			
			switch($dat['undPrincipal']){
				case 'NIU':
				case 'KGM':
					$cant_rechazada = explode('|',$dat['arreglo'])[3];
					break;
				case 'MLL':
				case 'MIL':
				case 'GRM':
					$cant_rechazada = floatval(explode('|',$dat['arreglo'])[3]*1000);
					break;
				case 'GLL':
					$cant_rechazada = floatval(explode('|',$dat['arreglo'])[3]/3.8);
					break;
				case 'LTR':
					$cant_rechazada = floatval(explode('|',$dat['arreglo'])[2]);
					break;
			}
			
			$mpdf->SetDefaultFont("Arial");
			
			$mpdf->WriteHTML("<div style='position:absolute;font-size:7px;font-weight:bold;'>
								LABORATORIOS BIOMONT S.A.
							</div>
							<div style='font-size:7px;font-weight:bold;text-align:right;'>
								F-CC.007.03
							</div>
							<div align='center' style='font-size:42px;font-weight:bold;padding:0px;'>
								RECHAZADO
							</div>
							<div align='right' style='font-size:8px;font-weight:bold;'>
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;CONTROL DE CALIDAD
							</div>
							");
			
			$mpdf->Ln(1);
			
			$mpdf->WriteHTML("
				<table width='100%'>
					<tr>
						<td style='width:30%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>NRO ANALISIS</td>
						<td style='width:0.5%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>:</td>
						<td style='width:69.5%;font-size: 11px;padding:2px 2px 2px 2px;' colspan=4>".$num_analisis."</td>
					</tr>
					<tr>
						<td style='width:30%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>NRO DOCUMENTO</td>
						<td style='width:0.5%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>:</td>
						<td style='width:69.5%;font-size: 11px;padding:2px 2px 2px 2px;' colspan=4>".$dat['TranID']."</td>
					</tr>
					<tr>
						<td style='width:30%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>NOMBRE</td>
						<td style='width:0.5%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>:</td>
						<td style='width:69.5%;font-size: 11px;padding:2px 2px 2px 2px;' colspan=4>".$dat['description']."</td>
					</tr>
					<tr>
						<td style='width:30%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>CODIGO</td>
						<td style='width:0.5%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>:</td>
						<td style='width:46%;font-size: 11px;padding:2px 2px 2px 2px;'>".$dat['itemid']."</td>
						<td style='width:3%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>LOTE</td>
						<td style='width:0.5%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>:</td>
						<td style='width:20%;font-size: 11px;padding:2px 2px 2px 2px;'>".$lote."</td>
					</tr>
					<tr>
						<td style='width:30%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>CANT. RECHAZADA</td>
						<td style='width:0.5%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>:</td>
						<td style='width:69.5%;font-size: 11px;padding:2px 2px 2px 2px;' colspan=4>".$cant_rechazada." ".$dat['abbreviation']."</td>
					</tr>
					<tr>
						<td style='width:30%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>FEC. ANALISIS</td>
						<td style='width:0.5%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>:</td>
						<td style='width:69.5%;font-size: 11px;padding:2px 2px 2px 2px;' colspan=4>".$fecha_ven."</td>
					</tr>
					<tr>
						<td style='width:30%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>MOTIVO RECHAZO</td>
						<td style='width:0.5%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>:</td>
						<td style='width:69.5%;font-size: 11px;padding:2px 2px 2px 2px;' colspan=4>".$dat['memoControl']."</td>
					</tr>
				</table>
			");
			
			$footer = "<table width='100%' nowrap>
						   <tr>
							 <td style='width:15%;font-size: 8px;'></td>
							 <td align='center' style='width:25%;font-size: 8px;'><hr style='width:100%;color:black;' /></td>
							 <td style='width:20%;font-size: 8px;'></td>
							 <td align='center' style='width:25%;font-size: 8px;'><hr style='width:100%;color:black;' /></td>
							 <td style='width:15%;font-size: 8px;'></td>
						   </tr>
						   <tr>
							 <td style='width:15%;font-size: 8px;'></td>
							 <td align='center' style='width:25%;font-size: 8px;'><strong>ANALISTA</strong></td>
							 <td style='width:20%;font-size: 8px;'></td>
							 <td align='center' style='width:25%;font-size: 8px;'><strong>JEFE DE CC</strong></td>
							 <td style='width:15%;font-size: 8px;'></td>
						   </tr>
					 </table>";
					 
			$mpdf->SetHTMLFooter($footer);
			
			$cont++;
			
			if($cont < count($dato)){
				$mpdf->AddPage();
			}		
		}
		
		date_default_timezone_set('America/Lima');
		
		$fecha = date("YmdHis",time());
		
		$archivo= "downloads/ET_RECHAZADO_".$dato[0]['TranID']."_".$fecha.".pdf";
		
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
	
	public function imprimirEtiquetaProductoImportacion()
	{
		header('Access-Control-Allow-Origin: *');

		$input = json_decode(file_get_contents("php://input"), true);
		
		$this->getLibrary('mpdf/mpdf');

		$mpdf = new mPDF('utf-8', 'TICKET', '', '', 2, 2, 2, 2, 1, 2); //mgl,mgr,mgt,mgb,mgh,mgf

		$mpdf->SetTitle('IMPRESION DE ETIQUETA PRODUCTO IMPORTACION');

		$objModel = $this->loadModel("recepcionDeArticulo");
		$dato = $objModel->TransactionxCODPROD_ET_PRODUCTOIMPORTACION($input["obj"]['id_recep'],$input["obj"]['cod_prod']);
		
		$cont = 0;
		foreach($dato as $dat){
			
			$inventorynumber = explode("#",$dat['inventorynumber']);
			
			/**/
			$lote = $inventorynumber[0];
			//$version = $inventorynumber[1];
			$num_analisis = $inventorynumber[2];
			//$peso = $inventorynumber[3];
			/**/
			
			/*$index = strpos($dat['itemid'], "0");
			$cod_letra = substr($dat['itemid'],0,$index);
			
			switch($cod_letra){
				case 'MP':
				case 'MMP':
					$lote = $inventorynumber[0];
					//$version = $inventorynumber[1];
					$num_analisis = $inventorynumber[2];
					//$peso = $inventorynumber[3];
					break;
				default:
					$lote = $inventorynumber[0];
					//$version = $inventorynumber[1];
					$num_analisis = $inventorynumber[2];
					//$peso = "";
					break;
			}*/			
			
			if(explode('|',$dat['arreglo'])[1]==null || explode('|',$dat['arreglo'])[1]=="" || explode('|',$dat['arreglo'])[1]=="01/01/1970"){
				$fecha_ven = "";
			}else{
				$fecha_ven = explode('|',$dat['arreglo'])[1];
			}
			
			switch($dat['undPrincipal']){
				case 'NIU':
				case 'KGM':
					$cant_aprobada = explode('|',$dat['arreglo'])[2];
					break;
				case 'MLL':
				case 'MIL':
				case 'GRM':
					$cant_aprobada = floatval(explode('|',$dat['arreglo'])[2]*1000);
					break;
				case 'GLL':
					$cant_aprobada = floatval(explode('|',$dat['arreglo'])[2]/3.8);
					break;
			}
			
			$mpdf->SetDefaultFont("Arial");
			
			$mpdf->WriteHTML("<div style='position:absolute;font-size:8px;font-weight:bold;'>
								LABORATORIOS BIOMONT S.A.
							</div>
							<div style='font-size:8px;font-weight:bold;text-align:right;'>
								F-CC.136.02
							</div>
							<div align='center' style='font-size:32px;font-weight:bold;padding:0px;'>
								APROBADO
							</div>
							<div align='center' style='font-size:10px;font-weight:bold;padding:0px;'>
								PRODUCTO IMPORTADO
							</div>
							<div align='right' style='font-size:8px;font-weight:bold;'>
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;CONTROL DE CALIDAD
							</div>
							");
			
			$mpdf->Ln(1);
			
			$mpdf->WriteHTML("
				<table width='100%'>
					<tr>
						<td style='width:30%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>NRO ANALISIS</td>
						<td style='width:0.5%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>:</td>
						<td style='width:69.5%;font-size: 11px;padding:2px 2px 2px 2px;' colspan=4>".$num_analisis."</td>
					</tr>
					<tr>
						<td style='width:30%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>NRO DOCUMENTO</td>
						<td style='width:0.5%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>:</td>
						<td style='width:69.5%;font-size: 11px;padding:2px 2px 2px 2px;' colspan=4>".$dat['TranID']."</td>
					</tr>
					<tr>
						<td style='width:30%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>NOMBRE</td>
						<td style='width:0.5%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>:</td>
						<td style='width:69.5%;font-size: 11px;padding:2px 2px 2px 2px;' colspan=4>".$dat['description']."</td>
					</tr>
					<tr>
						<td style='width:30%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>CODIGO</td>
						<td style='width:0.5%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>:</td>
						<td style='width:46%;font-size: 11px;padding:2px 2px 2px 2px;'>".$dat['itemid']."</td>
						<td style='width:3%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>LOTE</td>
						<td style='width:0.5%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>:</td>
						<td style='width:20%;font-size: 11px;padding:2px 2px 2px 2px;'>".$lote."</td>
					</tr>
					<tr>
						<td style='width:30%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>CANT. APROBADA</td>
						<td style='width:0.5%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>:</td>
						<td style='width:69.5%;font-size: 11px;padding:2px 2px 2px 2px;' colspan=4>".$cant_aprobada." ".$dat['abbreviation']."</td>
					</tr>
					<tr>
						<td style='width:30%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>FEC. INSPECCIÓN</td>
						<td style='width:0.5%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>:</td>
						<td style='width:69.5%;font-size: 11px;padding:2px 2px 2px 2px;' colspan=4>".$fecha_ven."</td>
					</tr>
					<tr>
						<td style='width:30%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>OBSERVACIONES</td>
						<td style='width:0.5%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>:</td>
						<td style='width:69.5%;font-size: 11px;padding:2px 2px 2px 2px;' colspan=4>".$dat['memoControl']."</td>
					</tr>
				</table>
			");
			
			$footer = "<table width='100%' nowrap>
						   <tr>
							 <td style='width:15%;font-size: 8px;'></td>
							 <td align='center' style='width:25%;font-size: 8px;'><hr style='width:100%;color:black;' /></td>
							 <td style='width:20%;font-size: 8px;'></td>
							 <td align='center' style='width:25%;font-size: 8px;'><hr style='width:100%;color:black;' /></td>
							 <td style='width:15%;font-size: 8px;'></td>
						   </tr>
						   <tr>
							 <td style='width:15%;font-size: 8px;'></td>
							 <td align='center' style='width:25%;font-size: 8px;'><strong>ANALISTA</strong></td>
							 <td style='width:20%;font-size: 8px;'></td>
							 <td align='center' style='width:25%;font-size: 8px;'><strong>JEFE CC</strong></td>
							 <td style='width:15%;font-size: 8px;'></td>
						   </tr>
					 </table>";
					 
			$mpdf->SetHTMLFooter($footer);
			
			$cont++;
			
			if($cont < count($dato)){
				$mpdf->AddPage();
			}		
		}

		date_default_timezone_set('America/Lima');
		
		$fecha = date("YmdHis",time());
		
		$archivo= "downloads/PE_".$dato[0]['TranID']."_".$fecha.".pdf";
		
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
	
	/* FIN IMPRESION DE ETIQUETAS */
	
	public function imprimirNotaIngreso()
	{
		
		header('Access-Control-Allow-Origin: *');
		$input = json_decode(file_get_contents("php://input"), true);
		
		$objModel = $this->loadModel("recepcionDeArticulo");
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
		
		$pdf->Ln(2);
		$pdf->Cell(202,4,"CANTIDAD TOTAL: ".$suma,0,1,'R',false);

		
		if(($pdf->GetPageHeight()/2)>$pdf->GetY()){

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

			$pdf->Ln(2);
			$pdf->Cell(202,4,"CANTIDAD TOTAL: ".$suma,0,1,'R',false);
			
		}else{
			
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

			$pdf->Ln(2);
			$pdf->Cell(202,4,"CANTIDAD TOTAL: ".$suma,0,1,'R',false);
		}

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

}


/*LIBRERIA PARA IMPRESION DE NOTA DE INGRESO*/

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