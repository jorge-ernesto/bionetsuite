<?php

class ordentrabajoController extends Controller
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
	
	public function imprimirBOM()
	{
		header('Access-Control-Allow-Origin: *');
		
		$input = json_decode(file_get_contents("php://input"), true);
		
		$objModel = $this->loadModel("ordentrabajo");
		$dato_cabecera_OP = $objModel->getCabeceraOrdenTrabajo(intval($input['dato']['id']));

		$this->getLibrary('mpdf/mpdf');

		$mpdf = new mPDF();
		
		$mpdf -> SetTitle('ORDEN DE TRABAJO');
		
		$mpdf->SetDefaultFont("Arial");
		
		$html="<style>@page {
				 margin-top: 20px;
				 margin-bottom: 20px;
				 margin-right: 20px;
				 margin-left: 20px;
				}</style>";
				
		$mpdf->WriteHTML($html);
		
		$mpdf->WriteHTML("
			<table width='100%'>
				<tr>
					<td rowspan='4'>
						<img src='http://6462530.shop.netsuite.com/core/media/media.nl?id=4499&c=6462530&h=s4vSW99RWLBxlBgfKjmRz8LD0h85_fj5MMZjCgrJwYDFq4v7' width='140' height='55'>
						<p style='font-size:12px;'>Laboratorios Biomont S.A.</p>
					</td>
					<td style='text-align:right;'>
						<p style='font-size:16px;font-weight:bold;'>F-LOG.004.05</p>
					</td>
				</tr>
				<tr>
					<td style='text-align:right;'>
						<p style='font-size:17px;font-weight:bold;'>ORDEN DE TRABAJO</p>
					</td>
				</tr>
				<tr>
					<td style='text-align:right;'>
						<p style='font-size:14px;'><strong>Tipo de Orden de Trabajo:</strong> ".$dato_cabecera_OP[0]['TipOT']."</p>
					</td>
				</tr>
			</table>
		");
		
		$mpdf->Ln(3);

		$mpdf->WriteHTML("
			<table class='tabla' width='100%' style='border:#000000 1px solid;'>
				<tr class='fila'>
					<td class='celda' style='text-align:right;font-size:11px;font-weight:bold;'>Código Producto:</td>
					<td class='celda' style='font-size:11px;'>".$dato_cabecera_OP[0]['codProd']."</td>
					<td class='celda' style='text-align:right;font-size:11px;font-weight:bold;'>Producto:</td>
					<td class='celda' style='font-size:11px;'>".$dato_cabecera_OP[0]['producto1']."</td>
				</tr>
				<tr class='fila'>
					<td class='celda' style='text-align:right;font-size:11px;font-weight:bold;'>OT:</td>
					<td class='celda' style='font-size:11px;'>".$dato_cabecera_OP[0]['NroOpe']."</td>
					<td class='celda' style='text-align:right;font-size:11px;font-weight:bold;'>Cantidad a Producir:</td>
					<td class='celda' style='font-size:11px;'>".$dato_cabecera_OP[0]['CantProd']." ".$dato_cabecera_OP[0]['unidad']."</td>
				</tr>
				<tr class='fila'>
					<td class='celda' style='text-align:right;font-size:11px;font-weight:bold;'>F. registro:</td>
					<td class='celda' style='font-size:11px;'>".$dato_cabecera_OP[0]['FechaCreacion']."</td>
					<td class='celda' style='text-align:right;font-size:11px;font-weight:bold;'>Lote:</td>
					<td class='celda' style='font-size:11px;'>".$dato_cabecera_OP[0]['Lote']."</td>
				</tr>
				<tr class='fila'>
					<td class='celda' style='text-align:right;font-size:11px;font-weight:bold;'>F. Fabricación:</td>
					<td class='celda' style='font-size:11px;'>".substr($dato_cabecera_OP[0]['FecFab'],3,2)."-".substr($dato_cabecera_OP[0]['FecFab'],6,4)."</td>
					<td class='celda' style='text-align:right;font-size:11px;font-weight:bold;'>F. Expira:</td>
					<td class='celda' style='font-size:11px;'>".substr($dato_cabecera_OP[0]['FexExp'],3,2)."-".substr($dato_cabecera_OP[0]['FexExp'],6,4)."</td>
				</tr>
				<tr class='fila'>
					<td class='celda' style='text-align:right;font-size:11px;font-weight:bold;'>Línea:</td>
					<td class='celda' style='font-size:11px;'>".$dato_cabecera_OP[0]['Linea']."</td>
					<td class='celda' style='text-align:right;font-size:11px;font-weight:bold;'>Principio activo:</td>
					<td class='celda' style='font-size:11px;'><p style='background-color:#D6DBDF;color:#D6DBDF;'>Hola</p></td>
				</tr>
			</table>
		");
		
		$mpdf->Ln(3); 
		
		$mpdf->WriteHTML("
			<table class='tabla1' width='100%'>
				<tr class='fila1'>
					<td class='celda1' style='width:15%;font-size:10px;'><strong>Código</strong></td>
					<td class='celda1' style='width:60%;font-size:10px;'><strong>Descripcion</strong></td>
					<td class='celda1' style='width:15%;font-size:10px;text-align:center;'><strong>Cantidad</strong></td>
					<td class='celda1' style='width:10%;font-size:10px;text-align:center;'><strong>UND</strong></td>
				</tr>
		");
		
		$where1="";
		$where2="";
		if($dato_cabecera_OP[0]['TipOT']=="REACONDICIONADO"){
			$where1 .= " where itemsource like 'STOCK%'";
			$where2 .= "";
		}else{
			$where1 .= "";
			$where2 .= " and (I.fullname LIKE 'M%' or I.fullname LIKE 'B%')";
		}

		$dato_detalle_OP = $objModel->getDetalleOrdenTrabajo(intval($input['dato']['id']),$where1,$where2);

		foreach($dato_detalle_OP as $art1){
			
			if(substr($dato_cabecera_OP[0]['codProd'],0,2)=="BK"){
				if(substr($art1['codigo'],0,2)=="BK"){
					continue;
				}
			}
			
			if($art1['principActivo']=='T'){
				$principio_activo="background-color:#D6DBDF;color:#000000";
			}else{
				$principio_activo="";
			}

			$mpdf->WriteHTML("
				<tr class='fila1'>
					<td class='celda1' style='width:15%;font-size:10px;".$principio_activo."'>".$art1['codigo']."</td>
					<td class='celda1' style='width:60%;font-size:10px;".$principio_activo."'>".$art1['articulo']."</td>
					<td class='celda1' style='width:15%;font-size:10px;text-align:center;".$principio_activo."'>".number_format($art1['cantidad'], 3, '.', ',')."</td>
					<td class='celda1' style='width:10%;font-size:10px;text-align:center;".$principio_activo."'>".$art1['und']."</td>
				</tr>
			");
		}
		
		$mpdf->WriteHTML("
			</table>
		");
		
		$mpdf->Ln(3);
		
		date_default_timezone_set('America/Lima');
		
		if(intval($input['dato']['numOT'])<585){
			$mpdf->WriteHTML("
				<table class='tabla2' width='100%'>
					<tr>
						<td colspan=2 style='font-size:11px;'><strong>Revisión y descarga de OT</strong></td>
					</tr>
					<tr>
						<td class='celda1' style='font-size:9px;text-align:center;'>Emitido por</td>
						<td class='celda1' style='font-size:9px;text-align:center;'>Revisado por</td>
						<td class='celda1' style='font-size:9px;text-align:center;'>Ajustado por</td>
						<td class='celda1' style='font-size:9px;text-align:center;'>Verificado por</td>
					</tr>
					<tr>
						<td class='celda1' style='font-size:9px;text-align:center;height:60px;border-bottom:0px solid white;'>".$dato_cabecera_OP[0]['EmiNomApe']."<br>".$dato_cabecera_OP[0]['firmaemitido']."</td>
						<td class='celda1' style='font-size:9px;text-align:center;height:60px;border-bottom:0px solid white;'>".$dato_cabecera_OP[0]['RevNomApe']."<br>".$dato_cabecera_OP[0]['firmarevisado']."</td>
						<td class='celda1' style='font-size:9px;text-align:center;height:60px;border-bottom:0px solid white;'>".$dato_cabecera_OP[0]['AjuNomApe']."<br>".$dato_cabecera_OP[0]['firmaajustado']."</td>
						<td class='celda1' style='font-size:9px;text-align:center;height:60px;border-bottom:0px solid white;'>".$dato_cabecera_OP[0]['VerNomApe']."<br>".$dato_cabecera_OP[0]['firmaverificado']."</td>
					</tr>
					<tr>
						<td style='font-size:7px;text-align:center;border-left:1px solid black;border-right:1px solid black;border-top:0px solid white;'>Firma digital desde NETSUITE</td>
						<td style='font-size:7px;text-align:center;border-right:1px solid black;border-top:0px solid white;'>Firma digital desde NETSUITE</td>
						<td style='font-size:7px;text-align:center;border-right:1px solid black;border-top:0px solid white;'>Firma digital desde NETSUITE</td>
						<td style='font-size:7px;text-align:center;border-right:1px solid black;border-top:0px solid white;'>Firma digital desde NETSUITE</td>
					</tr>
					<tr>
						<td class='celda1' style='font-size:9px;text-align:center;'>Logística</td>
						<td class='celda1' style='font-size:9px;text-align:center;'>Almacén</td>
						<td class='celda1' style='font-size:9px;text-align:center;'>Producción</td>
						<td class='celda1' style='font-size:9px;text-align:center;'>Aseguramiento</td>
					</tr>
				</table>
			");
		}else{
			$mpdf->WriteHTML("
				<table class='tabla2' width='80%'>
					<tr>
						<td colspan=2 style='font-size:11px;'><strong>Revisión y descarga de OT</strong></td>
					</tr>
					<tr>
						<td class='celda1' style='font-size:9px;text-align:center;'>Emitido por</td>
						<td class='celda1' style='font-size:9px;text-align:center;'>Revisado por</td>
						<td class='celda1' style='font-size:9px;text-align:center;'>Ajustado por</td>
					</tr>
					<tr>
						<td class='celda1' style='font-size:9px;text-align:center;height:60px;border-bottom:0px solid white;'>".$dato_cabecera_OP[0]['EmiNomApe']."<br>".$dato_cabecera_OP[0]['firmaemitido']."</td>
						<td class='celda1' style='font-size:9px;text-align:center;height:60px;border-bottom:0px solid white;'>".$dato_cabecera_OP[0]['RevNomApe']."<br>".$dato_cabecera_OP[0]['firmarevisado']."</td>
						<td class='celda1' style='font-size:9px;text-align:center;height:60px;border-bottom:0px solid white;'>".$dato_cabecera_OP[0]['AjuNomApe']."<br>".$dato_cabecera_OP[0]['firmaajustado']."</td>
					</tr>
					<tr>
						<td style='font-size:7px;text-align:center;border-left:1px solid black;border-right:1px solid black;border-top:0px solid white;'>Firma digital desde NETSUITE</td>
						<td style='font-size:7px;text-align:center;border-right:1px solid black;border-top:0px solid white;'>Firma digital desde NETSUITE</td>
						<td style='font-size:7px;text-align:center;border-right:1px solid black;border-top:0px solid white;'>Firma digital desde NETSUITE</td>
					</tr>
					<tr>
						<td class='celda1' style='font-size:9px;text-align:center;'>Logística</td>
						<td class='celda1' style='font-size:9px;text-align:center;'>Almacén</td>
						<td class='celda1' style='font-size:9px;text-align:center;'>Producción</td>
					</tr>
				</table>
			");
		}

		$mpdf->Ln(3);
		
		$mpdf->WriteHTML("
			<table class='tabla2' width='100%'>
				<tr>
					<td colspan=4 style='font-size:11px;'><strong>Observaciones</strong></td>
				</tr>
				<tr>
					<td colspan=4 style='font-size:10px;'>".$dato_cabecera_OP[0]['Nota']."</td>
				</tr>
			</table>
		");
		
		$fecha = date("YmdHis",time());
		
		$archivo= "downloads/OT_".$dato_cabecera_OP[0]['idtransaccion']."_".$fecha.".pdf";

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