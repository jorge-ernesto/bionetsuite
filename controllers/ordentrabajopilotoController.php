<?php

class ordentrabajopilotoController extends Controller
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
	
	public function imprimirBOM_IDE()
	{
		
		
		$input = json_decode(file_get_contents("php://input"), true);
		
		$objModel = $this->loadModel("index");
		$dato_cabecera_OP = $objModel->getCabeceraOrdenTrabajo_ID(intval($input['dato']['idOT']));
		

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

		$dato_detalle_OP = $objModel->getDetalleOrdenTrabajo_ID(intval($input['dato']['idOT']));
		
		foreach($dato_detalle_OP as $art1){
			
			if($art1['principActivo']=='T'){
				//$principio_activo="background-color:#D6DBDF;color:#000000";
				$principio_activo="";
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
		
		$mpdf->WriteHTML("
			<table class='tabla2' width='100%'>
				<tr>
					<td colspan=2 style='font-size:11px;'><strong>Revisión y descarga de OT</strong></td>
				</tr>
				<tr>
					<td class='celda1' style='font-size:9px;text-align:center;'>Emitido por</td>
					<td class='celda1' style='font-size:9px;text-align:center;'>Revisado por</td>
					<td class='celda1' style='font-size:9px;text-align:center;'>Aprobado por</td>
					<td class='celda1' style='font-size:9px;text-align:center;'>Recibido por</td>
				</tr>
				<tr>
					<td class='celda1' style='font-size:9px;text-align:center;height:60px;border-bottom:0px solid white;'>".$dato_cabecera_OP[0]['EmiNomApe']."<br>".$dato_cabecera_OP[0]['firmaemitido']."</td>
					<td class='celda1' style='font-size:9px;text-align:center;height:60px;border-bottom:0px solid white;'>".$dato_cabecera_OP[0]['RevNomApe']."<br>".$dato_cabecera_OP[0]['firmarevisado']."</td>
					<td class='celda1' style='font-size:9px;text-align:center;height:60px;border-bottom:0px solid white;'>".$dato_cabecera_OP[0]['AprobNomApe']."<br>".$dato_cabecera_OP[0]['firmaaaprobado']."</td>
					<td class='celda1' style='font-size:9px;text-align:center;height:60px;border-bottom:0px solid white;'>".$dato_cabecera_OP[0]['RecibNomApe']."<br>".$dato_cabecera_OP[0]['firmarecibido']."</td>
				</tr>
				<tr>
					<td style='font-size:7px;text-align:center;border-left:1px solid black;border-right:1px solid black;border-top:0px solid white;'>Firma digital desde NETSUITE</td>
					<td style='font-size:7px;text-align:center;border-right:1px solid black;border-top:0px solid white;'>Firma digital desde NETSUITE</td>
					<td style='font-size:7px;text-align:center;border-right:1px solid black;border-top:0px solid white;'>Firma digital desde NETSUITE</td>
					<td style='font-size:7px;text-align:center;border-right:1px solid black;border-top:0px solid white;'>Firma digital desde NETSUITE</td>
				</tr>
				<tr>
					<td class='celda1' style='font-size:9px;text-align:center;'>Asistente IDE</td>
					<td class='celda1' style='font-size:9px;text-align:center;'>Asistente IDE</td>
					<td class='celda1' style='font-size:9px;text-align:center;'>Logística</td>
					<td class='celda1' style='font-size:9px;text-align:center;'>Almacén</td>
				</tr>
			</table>
		");
		
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
		
		header('Access-Control-Allow-Origin: *');
		
		$fecha = date("YmdHis",time());
		
		$archivo= "downloads/OT_".$dato_cabecera_OP[0]['idOP']."_".$fecha.".pdf";

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
	
	public function sendEmail_IDE()
	{
		
		header('Access-Control-Allow-Origin: *');
		
		$input = json_decode(file_get_contents("php://input"), true);

		if($input["dato"]['email']=='logistica'){
			$emails = ["jpena@biomont.com.pe","cbonilla@biomont.com.pe","avilchez@biomont.com.pe","etacunan@biomont.com.pe"];
		}else if($input["dato"]['email']=='almacen'){
			$emails = ["jpena@biomont.com.pe","mramirez@biomont.com.pe","etacunan@biomont.com.pe","id@biomont.com.pe","mmancilla@biomont.com.pe"];
		}else if($input["dato"]['email']=='todos'){
			$emails = ["jpena@biomont.com.pe","etacunan@biomont.com.pe","id@biomont.com.pe","csuncion@biomont.com.pe"];
		}
		
		$this->getLibrary('PHPMailer/PHPMailer');
		$this->getLibrary('PHPMailer/SMTP');

		$mail = new PHPMailer();

		$mail->isSMTP();
		$mail->SMTPDebug = false;
		$mail->SMTPAuth = true; //Habilita uso de usuario y contraseña
		$mail->SMTPSecure = 'tls';
		$mail->Mailer = 'smtp';
		$mail->Host = MAIL_APP_HOST;
		$mail->Username = MAIL_APP_USER;
		$mail->Password = MAIL_APP_PASSWORD;
		$mail->Port = 587;
		$mail->setFrom(MAIL_APP_USER);
		
		for($i = 0; $i < count($emails); $i++) {
			$mail->AddAddress($emails[$i]); //Destionatarios
		}
		
		$mail->isHTML(true); //Acepta HTML
		$mail->CharSet = "utf-8"; //Acepta caracteres
		$mail->Subject = 'Notificación de Orden de Trabajo IDE - Sistema NetSuite';

		$mailContent	  = "<div class='row'>
							<h1 style='color:#45556E;'>Notificación de NetSuite</h1>
							<table>
								<tr>
									<td>Buen día:</td>
								</tr>
								<tr>
									<td>El usuario <strong>".$input["dato"]['firmante']."</strong> ha ".$input["dato"]['accion']." la <strong>Orden de Trabajo Piloto</strong> en el Sistema NetSuite.</td>
								</tr>
							</table>
							<br><br>
							<table>
								<tr>
									<td><strong>Concepto</strong></td>
									<td>:</td>
									<td><span>".$input["dato"]['concepto']."</span></td>
								</tr>
								<tr>
									<td><strong>Orden de Trabajo</strong></td>
									<td>:</td>
									<td>".$input["dato"]['numOP']."</td>
								</tr>
								<tr>
									<td><strong>Tipo de Orden de Trabajo</strong></td>
									<td>:</td>
									<td>".$input["dato"]['tipoOT']."</td>
								</tr>
								<tr>
									<td><strong>Link de Registro</strong></td>
									<td>:</td>
									<td><a href='https://6462530.app.netsuite.com/app/accounting/transactions/transaction.nl?id=".$input["dato"]['idOP']."' target='_blank'>Ver registro</a></td>
								</tr>
							</table>
							<br><br>
							<table>
								<tr>
									<td><h1 style='color:#45556E;'>Atentamente</h1></td>
								</tr>
								<tr>
									<td><h1 style='color:#45556E;'>Notificaciones NetSuite</h1></td>
								</tr>
							</table>
							</div>";
							
		$mail->Body   = $mailContent;

		if (!$mail->send()) {
			//echo "no :".$mail->ErrorInfo; //Error al enviar el correo
			$est=1; //error al enviar email
		} else {
			//echo "ok"; //Se envió correctamente
			$est=0; //email enviado
		}
						
		
		header("Content-type: application/json; charset=utf-8");
		echo json_encode(["est"=>$est]);

	}

}