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

	public function formatoNombreCompleto($nombreCompleto) {
		// Convertir toda la cadena a minúsculas
		$cadena_minusculas = mb_convert_case($nombreCompleto, MB_CASE_LOWER, "UTF-8");

		// Capitalizar la primera letra de cada palabra
		$cadena_formateada = mb_convert_case($cadena_minusculas, MB_CASE_TITLE, "UTF-8");
	
		return $cadena_formateada;
	}

	public function formatearFechaEnEspanol($fecha, $formato) {
		// Definir un array de nombres de meses en español
		$meses_espanol = array(
			1 => 'Ene',
			2 => 'Feb',
			3 => 'Mar',
			4 => 'Abr',
			5 => 'May',
			6 => 'Jun',
			7 => 'Jul',
			8 => 'Ago',
			9 => 'Sep',
			10 => 'Oct',
			11 => 'Nov',
			12 => 'Dic'
		);
	
		// Convertir la cadena de fecha en un objeto de fecha
		$fecha_objeto = DateTime::createFromFormat('d/m/Y', $fecha);
	
		// Obtener el número del mes
		$mes = $fecha_objeto->format('n');
	
		// Obtener el nombre del mes en español utilizando el array definido anteriormente
		$nombre_mes = $meses_espanol[$mes];
	
		// Formatear la fecha en el formato deseado
		
		if ($formato == 'm-y') {
			$fecha_formateada = $nombre_mes . '-' . $fecha_objeto->format('y');
		} else if ($formato == 'd-m') {
			$fecha_formateada = $fecha_objeto->format('d') . '-' . $nombre_mes;
		} else {
			$fecha_formateada = $fecha;
		}
	
		return $fecha_formateada;
	}

	public function imprimirOTLiberacionAnticipada()
	{
		header('Access-Control-Allow-Origin: *');
		
		$input = json_decode(file_get_contents("php://input"), true);

		// Verificar datos
		// echo "<pre>";
		// print_r($input);
		// echo "<pre>";
		// return;

		// Imagen
		// $img = BASE_URL . "public/assets/img/logo_bio.png";
		$img = "https://www.biomont.com.pe/storage/img/logo.png";

		// Datos para la impresion PDF
		// SOLICITUD DE LIBERACION ANTICIPADA
		$n_solicitud = $input['dato']['n_solicitud'];
		$emitido_por = $input['dato']['emitido_por'];
		$fecha_emitido_por = $input['dato']['fecha_emitido_por']; // Viene en formato dd/mm/YYYY hh:mm:ss
		$area_que_notifica = $input['dato']['area_que_notifica'];
		
		// Conversion de datos
			// Fecha emitido por (Debe mostrarse en formato dd/mm/YYYY)
			$timestamp = strtotime(str_replace('/', '-', $fecha_emitido_por));
			$fecha_emitido_por_formateada = date("d/m/Y", $timestamp);
		
		// 1.- MOTIVO
		$data_motivo_liberacion = $input['dato']['data_motivo_liberacion'];
		$select_motivo_liberacion = $input['dato']['select_motivo_liberacion'];

		// 2.- DATOS GENERALES DEL PRODUCTO
		$producto_bulk = $input['dato']['producto_bulk'];
		$lote = $input['dato']['lote'];
		$fecha_fabricacion = $input['dato']['fecha_fabricacion']; // Viene en formato dd/mm/YYYY
		$cantidad_total = $input['dato']['cantidad_total'];
		$fecha_envasado = $input['dato']['fecha_envasado'];
		$producto_presentacion = $input['dato']['producto_presentacion'];
		$ot = $input['dato']['ot'];
		$fecha_vencimiento = $input['dato']['fecha_vencimiento']; // Viene en formato dd/mm/YYYY
		$cantidad_real = $input['dato']['cantidad_real'];
		$fecha_liberacion = $input['dato']['fecha_liberacion'];
		
		// Conversion de datos
			// Nombre del producto y Presentacion del producto
			$nombre_producto = $producto_bulk;
			$presentacion_producto = $producto_presentacion;

			// Fecha de Fabricacion y Fecha de Vencimiento (Debe mostrarse en formato MES-dd en Español)
			// Establecer el idioma y la configuración regional en español
			setlocale(LC_TIME, 'es_ES.utf8');

			// Convertir la cadena de fecha en un objeto de fecha
			$fecha_fabricacion_objeto = DateTime::createFromFormat('d/m/Y', $fecha_fabricacion);
			$fecha_vencimiento_objeto = DateTime::createFromFormat('d/m/Y', $fecha_vencimiento);
			$fecha_envasado_objeto = DateTime::createFromFormat('d/m/Y', $fecha_envasado);
			$fecha_liberacion_objeto = DateTime::createFromFormat('d/m/Y', $fecha_liberacion);

			// Formatear la fecha en el formato deseado
			$fecha_fabricacion_formateada = strftime('%b-%y', $fecha_fabricacion_objeto->getTimestamp());
			$fecha_vencimiento_formateada = strftime('%b-%y', $fecha_vencimiento_objeto->getTimestamp());
			$fecha_envasado_formateada = strftime('%d-%b', $fecha_envasado_objeto->getTimestamp());
			$fecha_liberacion_formateada = strftime('%d-%b', $fecha_liberacion_objeto->getTimestamp());

			$fecha_fabricacion_formateada = $this->formatearFechaEnEspanol($fecha_fabricacion, "m-y");
			$fecha_vencimiento_formateada = $this->formatearFechaEnEspanol($fecha_vencimiento, "m-y");
			$fecha_envasado_formateada = $this->formatearFechaEnEspanol($fecha_envasado, "d-m");
			$fecha_liberacion_formateada = $this->formatearFechaEnEspanol($fecha_liberacion, "d-m");

		// 3.-  DECISION DE CONTROL DE CALIDAD
		$dias_liberacion_anticipada = $input['dato']['dias_liberacion_anticipada'];
		$resultado_fisicoquimico = $input['dato']['resultado_fisicoquimico'];

		// 4.- FIRMAS A LAS APROBACION
		$usuario_control_calidad = $this->formatoNombreCompleto( $input['dato']['usuario_control_calidad'] );
		$firma_control_calidad = $input['dato']['firma_control_calidad'];

		$usuario_produccion = $this->formatoNombreCompleto( $input['dato']['usuario_produccion'] );
		$firma_produccion = $input['dato']['firma_produccion'];

		$usuario_aseguramiento = $this->formatoNombreCompleto( $input['dato']['usuario_aseguramiento'] );
		$firma_aseguramiento = $input['dato']['firma_aseguramiento'];

		$usuario_gerencia_planta_original = $input['dato']['usuario_gerencia_planta']; // TODO: Revisar
		$usuario_gerencia_planta = $this->formatoNombreCompleto( $input['dato']['usuario_gerencia_planta'] ); // TODO: Revisar
		$firma_gerencia_planta = $input['dato']['firma_gerencia_planta'];

			// Convertir usuarios que firman
			$usuario_control_calidad = (TRIM($firma_control_calidad) == '') ? '' : $usuario_control_calidad;
			$usuario_produccion = (TRIM($firma_produccion) == '') ? '' : $usuario_produccion;
			$usuario_aseguramiento = (TRIM($firma_aseguramiento) == '') ? '' : $usuario_aseguramiento;
			$usuario_gerencia_planta = (TRIM($firma_gerencia_planta) == '') ? '' : $usuario_gerencia_planta;

		// Conversion de datos
			// $palabras = explode(" ", $usuario_control_calidad);
			// $apellidos = array_slice($palabras, 2);
			// $apellidos = implode(" ", $apellidos);
			// $usuario_control_calidad = $palabras[0].' '.$apellidos;

			// $palabras = explode(" ", $usuario_produccion);
			// $apellidos = array_slice($palabras, 2);
			// $apellidos = implode(" ", $apellidos);
			// $usuario_produccion = $palabras[0].' '.$apellidos;

			// $palabras = explode(" ", $usuario_aseguramiento);
			// $apellidos = array_slice($palabras, 2);
			// $apellidos = implode(" ", $apellidos);
			// $usuario_aseguramiento = $palabras[0].' '.$apellidos;

			// $palabras = explode(" ", $usuario_gerencia_planta);
			// $apellidos = array_slice($palabras, 2);
			// $apellidos = implode(" ", $apellidos);
			// $usuario_gerencia_planta = $palabras[0].' '.$apellidos;

		// HTML MOTIVO
		$html_motivo = "";
		foreach ($data_motivo_liberacion as $key => $motivo_liberacion) {
			if ($motivo_liberacion['value'] <= 0) {
				continue;
			}

			$seleccionado = '';
			if ($motivo_liberacion['value'] === $select_motivo_liberacion) {
				$seleccionado = 'x';
			}

			$html_motivo .= '
			<tr>
				<td class="celda-center" colspan="1" style="font-size: 10px; width: 30%;">'.$motivo_liberacion['text'].'</td>
				<td class="celda-center" colspan="1">'.$seleccionado.'</td>
				<td class="celda-center" colspan="3"></td>
			</tr>
			';
		}

		$html = <<<HTML
		<html>

		<head>
			<style>
				body {
					font-family: sans-serif;
					font-size: 10pt;
				}

				p {
					margin: 0pt;
				}

				table.items {
					border: 0.1mm solid #000000;
				}

				td {
					vertical-align: top;
				}

				.items td {
					border-left: 0.1mm solid #000000;
					border-right: 0.1mm solid #000000;
				}

				table thead td { /* SOLICITUD DE LIBERACIÓN ANTICIPADA */
					background-color: #EEEEEE;
					text-align: center;
					border: 0.1mm solid #000000;
					font-variant: small-caps;
				}

				.items td.blanktotal {
					background-color: #EEEEEE;
					border: 0.1mm solid #000000;
					background-color: #FFFFFF;
					border: 0mm none #000000;
					border-top: 0.1mm solid #000000;
					border-right: 0.1mm solid #000000;
				}

				.items td.totals {
					text-align: right;
					border: 0.1mm solid #000000;
				}

				.items td.cost {
					text-align: "." center;
				}

				/****** Personalizado ******/
				.cabecera1_1 {
					text-align: left; 
					font-weight: normal; 
					font-size: small;
				}

				img {
					width: 100px;
				}

				.cabecera1_2 {
					text-align: right; 
					font-weight: normal; 
					font-size: small;
				}

				.cabecera4 {
					text-align: right; 
					font-weight: normal;
				}

				.celda-left {
					text-align: left;
					border: 0.1mm solid #000000;
				}

				.celda-center {
					text-align: center;
					border: 0.1mm solid #000000;
				}

				.celda-right {
					text-align: right;
					border: 0.1mm solid #000000;
				}

				.celda-firma-izquierda {
					font-size: 20px;
				}

				.celda-firma-derecha {
					font-size: 10px;
				}

				.cabecera-left {
					background-color: #EEEEEE;
					text-align: left;
					border: 0.1mm solid #000000;
					font-variant: small-caps;
					font-weight: normal;
				}

				.cabecera-center {
					background-color: #EEEEEE;
					text-align: center;
					border: 0.1mm solid #000000;
					font-variant: small-caps;
					font-weight: normal;
				}
			</style>
		</head>

		<body>

			<!-- <br /> -->

			<!-- <img src='https://www.biomont.com.pe/storage/img/logo.png'></img> -->
			<!-- <img src='https://192.168.1.207:8080/bionetsuite/public/assets/img/logo_bio.png'></img> -->

			<table class="items" width="100%" style="font-size: 9pt; border-collapse: collapse; " cellpadding="8">
				<thead>
					<tr>
						<th colspan="1" class="cabecera1_1"><img src="{$img}"></img></th>
						<th colspan="4" class="cabecera1_2">F-AC.0683.03<br/> (FORMATO<br /> INTERNO)</th>
					</tr>
					<tr>
						<th colspan="4" class="cabecera4">N° SOLICITUD: {$n_solicitud}</th>
					</tr>
					<tr>
						<td colspan="5">SOLICITUD DE LIBERACION ANTICIPADA</td>
					</tr>
				</thead>
				<tbody>
					<!-- SOLICITUD DE LIBERACION ANTICIPADA -->
					<!-- <tr>
						<td class="cabecera-center" colspan="5">SOLICITUD DE LIBERACION ANTICIPADA</td>
					</tr> -->
					<tr>
						<td class="celda-left">NOTIFICADO POR</td>
						<td class="celda-left" colspan="4">{$emitido_por}</td>
					</tr>
					<tr>
						<td class="celda-left">AREA QUE NOTIFICA:</td>
						<td class="celda-left" colspan="2">{$area_que_notifica}</td>
						<td class="celda-left">FECHA</td>
						<td class="celda-center">{$fecha_emitido_por_formateada}</td>
					</tr>

					<!-- 1.- MOTIVO -->
					<tr>
						<td class="cabecera-left" colspan="5">1.- MOTIVO</td>
					</tr>
					{$html_motivo}

					<!-- 2.- DATOS GENERALES DEL PRODUCTO -->
					<tr>
						<td class="cabecera-left" colspan="5">2.- DATOS GENERALES DEL PRODUCTO</td>
					</tr>
					<tr>
						<td class="celda-left">NOMBRE DEL PRODUCTO:</td>
						<td class="celda-center" colspan="2" style="font-size: 10px;">{$nombre_producto}</td>
						<td class="celda-left">PRESENTACIÓN:</td>
						<td class="celda-center" style="font-size: 10px;">{$presentacion_producto}</td>
					</tr>
					<tr>
						<td class="celda-left">LOTE:</td>
						<td class="celda-center" colspan="2" style="font-size: 10px;">{$lote}</td>
						<td class="celda-left">O.T.:</td>
						<td class="celda-center" style="font-size: 10px;">{$ot}</td>
					</tr>
					<tr>
						<td class="celda-left">FECHA DE FABRICACIÓN:</td>
						<td class="celda-center" colspan="2" style="font-size: 10px;">{$fecha_fabricacion_formateada}</td>
						<td class="celda-left">FECHA DE VENCIMIENTO:</td>
						<td class="celda-center" style="font-size: 10px;">{$fecha_vencimiento_formateada}</td>
					</tr>
					<tr>
						<td class="celda-left">CANTIDAD TOTAL:</td>
						<td class="celda-center" colspan="2" style="font-size: 10px;">{$cantidad_total}</td>
						<td class="celda-left">CANTIDAD REAL:</td>
						<td class="celda-center" style="font-size: 10px;">{$cantidad_real}</td>
					</tr>
					<tr>
						<td class="celda-left">FECHA DE ENVASADO:</td>
						<td class="celda-center" colspan="2" style="font-size: 10px;">{$fecha_envasado_formateada}</td>
						<td class="celda-left">FECHA DE LIBERACIÓN:</td>
						<td class="celda-center" style="font-size: 10px;">{$fecha_liberacion_formateada}</td>
					</tr>

					<!-- 3. DECISIÓN DE CONTROL DE CALIDAD -->
					<tr>
						<td class="cabecera-left" colspan="5">3. DECISIÓN DE CONTROL DE CALIDAD</td>
					</tr>
					<tr>
						<td class="celda-left" colspan="4">N° DE DIAS DE LIBERACIÓN MICROBIOLOGICA: {$dias_liberacion_anticipada} Días</td>
						<td class="celda-center" colspan="1">Firma</td>
					</tr>
					<tr>
						<td class="celda-left" colspan="3">RESULTADO FISICOQUIMICO: ${resultado_fisicoquimico}</td>
						<td class="celda-center celda-firma-izquierda" style="border-right: 0px;">
							<span>{$usuario_control_calidad}</span>
						</td>
						<td class="celda-left celda-firma-derecha" style="border-left: 0px;">
							<span>Firmado digitalmente por {$usuario_control_calidad}</span><br />							
							<span>Fecha: {$firma_control_calidad}</span>							
						</td>
					</tr>

					<!-- 4.- FIRMAS A LAS APROBACIÓN -->
					<tr>
						<td class="cabecera-left" colspan="5">4.- FIRMAS A LAS APROBACIÓN</td>
					</tr>
					<tr>
						<td class="celda-center">VERIFICADO POR:</td>
						<td class="celda-center">JEFE DE PRODUCCIÓN</td>
						<td class="celda-center">FIRMA</td>
						<td class="celda-center celda-firma-izquierda" style="border-right: 0px;">
							<span>{$usuario_produccion}</span>
						</td>
						<td class="celda-left celda-firma-derecha" style="border-left: 0px;">
							<span>Firmado digitalmente por {$usuario_produccion}</span><br />							
							<span>Fecha: {$firma_produccion}</span>
						</td>
					</tr>
					<tr>
						<td class="celda-center">VERIFICADO POR:</td>
						<td class="celda-center">ASEGURAMIENTO DE LA CALIDAD</td>
						<td class="celda-center">FIRMA</td>
						<td class="celda-center celda-firma-izquierda" style="border-right: 0px;">
							<span>{$usuario_aseguramiento}</span>
						</td>
						<td class="celda-left celda-firma-derecha" style="border-left: 0px;">
							<span>Firmado digitalmente por {$usuario_aseguramiento}</span><br />							
							<span>Fecha: {$firma_aseguramiento}</span>
						</td>
					</tr>
					<tr>
						<td class="celda-center">APROBADO POR:</td>
						<td class="celda-center">GERENTE DE PLANTA</td>
						<td class="celda-center">FIRMA</td>
						<td class="celda-center celda-firma-izquierda" style="border-right: 0px;">
							<!-- <span>{$usuario_gerencia_planta}</span> --> <!-- TODO: Revisar -->
							<span>{$usuario_gerencia_planta}</span>
						</td>
						<td class="celda-left celda-firma-derecha" style="border-left: 0px;">
							<span>Firmado digitalmente por {$usuario_gerencia_planta}</span><br />
							<span>Fecha: {$firma_gerencia_planta}</span>
						</td>
					</tr>
				</tbody>
			</table>

			<!-- <div style="text-align: center; font-style: italic;">Payment terms: payment due in 30 days</div> -->

		</body>

		</html>
HTML;

		$this->getLibrary('mpdf/mpdf');

		$mpdf = new mPDF([
			'margin_left' => 20,
			'margin_right' => 15,
			'margin_top' => 48,
			'margin_bottom' => 25,
			'margin_header' => 10,
			'margin_footer' => 10
		]);

		$mpdf->SetProtection(array('print'));
		$mpdf->SetTitle("F-AC.068.03 SOLICITUD-DE-LIBERACION-ANTICIPADAS");
		$mpdf->SetAuthor("Laboratorios Biomont S.A.");
		// $mpdf->SetWatermarkText("Paid");
		$mpdf->showWatermarkText = true;
		$mpdf->watermark_font = 'DejaVuSansCondensed';
		$mpdf->watermarkTextAlpha = 0.1;
		$mpdf->SetDisplayMode('fullpage');
		
		$mpdf->WriteHTML($html);
		
		$mpdf->Output('filename.pdf', 'D');
	}

}