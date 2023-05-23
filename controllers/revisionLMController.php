<?php

class revisionLMController extends Controller
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
	
	/*public function enviarCorreoCambios()
	{
		header('Access-Control-Allow-Origin: *');
		
		$emails = ["jpena@biomont.com.pe"];
		
		$input = json_decode(file_get_contents("php://input"), true);
		
		if(count($input["dato"])!=0){
			
			$objModel = $this->loadModel("revisionLM");
			$res = $objModel->guardarDatos($input["dato"]['arreglo_inicial']);
			
			if($res){
				 
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
				$mail->Subject = 'Notificación de Edición Revision Lista de Materiales - Sistema NetSuite';
				
				
				$contenido = "<div class='row'>
									<h1 style='color:#45556E;'>Notificación de NetSuite</h1>
									<br>
									<table>
										<tr>
											<td>Buen día: </td>
										</tr>
										<tr>
											<td>El usuario <strong>".$input["dato"]['nameuser']."</strong> ha editado La Revisión de Lista de Materiales en el Sistema NetSuite correspondiente a: </td>
										</tr>
									</table>
									<br>
									<table>
										<tr>
											<td><strong>REVISION - NOMBRE DE PRODUCTO</strong></td>
											<td>:</td>
											<td><span>".$input["dato"]['namerevision']."</span></td>
										</tr>
										<tr>
											<td><strong>CÓDIGO BULK - LINEA PRODUCTO</strong></td>
											<td>:</td>
											<td><span>".$input["dato"]['codeline']."</span></td>
										</tr>
										<tr>
											<td><strong>FÓRMULA</strong></td>
											<td>:</td>
											<td><span>".$input["dato"]['formula']."</span></td>
										</tr>
										<tr>
											<td><strong>Link de Registro</strong></td>
											<td>:</td>
											<td><a href='https://6462530.app.netsuite.com/app/accounting/manufacturing/bomrevision.nl?id=".$input["dato"]['idformulario']."' target='_blank'>Ver registro</a></td>
										</tr>
									</table>
									<br>";
								
				$contenido .= "<table width='100%' nowwrap style='border:1px solid black; border-collapse:collapse;'>";
				$contenido .= "<tr><th colspan='5' align='center' style='background-color:#45556E;color:white;font-size:14px;'>ANTES</th></tr>";
				$contenido .= "<tr>
								<th align='center' style='border:1px solid black;'>CÓDIGO/DESCRIPCIÓN</th>
								<th align='center' style='border:1px solid black;'>RENDIMIENTO</th>
								<th align='center' style='border:1px solid black;'>PRINCIPIO ACTIVO</th>
								<th align='center' style='border:1px solid black;'>CANTIDAD</th>
								<th align='center' style='border:1px solid black;'>UNID. MEDIDA</th>
							</tr>";
				$i=0;
				foreach($input["dato"]['arreglo_inicial'] as $item_main){
					$contenido .= "<tr>";
					foreach($item_main as $item_second){
						if($input["dato"]['arreglo_concat_inicial'][$i][0]!=$input["dato"]['arreglo_concat_final'][$i][0]){
							$color_celda_inicial = "background-color:#F9FF7A;";
						}else{
							$color_celda_inicial = "";
						}
						
						$contenido .= "<td style='".$color_celda_inicial."border:1px solid black;'>".$item_second."</td>";
						
					}
					$contenido .= "</tr>";
					$i++;
				}
				$contenido .= "</table><br><br>";
				
				$contenido .= "<table width='100%' nowwrap style='border:1px solid black; border-collapse:collapse;'>";
				$contenido .= "<tr><th colspan='5' align='center' style='background-color:#45556E;color:white;font-size:14px;'>DESPUES</th></tr>";
				$contenido .= "<tr>
								<th align='center' style='border:1px solid black;'>CÓDIGO/DESCRIPCIÓN</th>
								<th align='center' style='border:1px solid black;'>RENDIMIENTO</th>
								<th align='center' style='border:1px solid black;'>PRINCIPIO ACTIVO</th>
								<th align='right' style='border:1px solid black;'>CANTIDAD</th>
								<th align='center' style='border:1px solid black;'>UNID. MEDIDA</th>
							</tr>";
				$j=0;
				foreach($input["dato"]['arreglo_final'] as $item_main){
					$contenido .= "<tr>";
					foreach($item_main as $item_second){
						if($input["dato"]['arreglo_concat_inicial'][$j][0]!=$input["dato"]['arreglo_concat_final'][$j][0]){
							$color_celda_final = "background-color:#FF8A8A;";
						}else{
							$color_celda_final = "";
						}
						
						$contenido .= "<td style='".$color_celda_final."border:1px solid black;'>".$item_second."</td>";
						
					}
					$contenido .= "</tr>";
					$j++;
				}
				$contenido .= "</table>";
				
				$contenido .= "<br>
									<table>
										<tr>
											<td><h1 style='color:#45556E;'>Atentamente</h1></td>
										</tr>
										<tr>
											<td><h1 style='color:#45556E;'>Notificaciones NetSuite</h1></td>
										</tr>
									</table>
								</div>";
				
				$mailContent	  = $contenido; //"<p>".count($input["dato"])."</p>";
									
				$mail->Body   = $mailContent;

				if (!$mail->send()) {
					$est=1; //error al enviar email
				} else {
					$est=0; //email enviado
				}
								
				
				header("Content-type: application/json; charset=utf-8");
				echo json_encode(["est"=>$est]);
				
			}
	
		}
		
	}*/
	
	public function sendEmailSign()
	{
		header('Access-Control-Allow-Origin: *');
		
		$input = json_decode(file_get_contents("php://input"), true);
		
		//$emails = ["jpena@biomont.com.pe","fcastro@biomont.com.pe"];

		if($input["dato"]['email']=='ide'){ //emision
			$emails = [
				"jpena@biomont.com.pe",
				"avilchez@biomont.com.pe",
				"kcelestino@biomont.com.pe",
				"etacunan@biomont.com.pe",
				"acampos@biomont.com.pe",
				"id@biomont.com.pe"
			];
		}else if($input["dato"]['email']=='planta'){ //revision aprobacion
			$emails = [
				"jpena@biomont.com.pe",
				"wpena@biomont.com.pe",
				"avilchez@biomont.com.pe",
				"kcelestino@biomont.com.pe",
				"etacunan@biomont.com.pe",
				"acampos@biomont.com.pe",
				"id@biomont.com.pe"
			];
		}else if($input["dato"]['email']=='planta_rechazado'){ //revision rechazo
			$emails = [
				"jpena@biomont.com.pe",
				"avilchez@biomont.com.pe",
				"kcelestino@biomont.com.pe",
				"etacunan@biomont.com.pe",
				"acampos@biomont.com.pe",
				"id@biomont.com.pe"
			];
		}else if($input["dato"]['email']=='todos'){ //aprobacion
			$emails = [
				"jpena@biomont.com.pe",
				"wpena@biomont.com.pe",
				"avilchez@biomont.com.pe",
				"kcelestino@biomont.com.pe",
				"etacunan@biomont.com.pe",
				"acampos@biomont.com.pe",
				"kfranco@biomont.com.pe",
				"evera@biomont.com.pe",
				"crujel@biomont.com.pe",
				"kcastillo@biomont.com.pe",
				"id@biomont.com.pe",
				"dguzman@biomont.com.pe",
				"cbonilla@biomont.com.pe"
			];
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
			$mail->AddAddress($emails[$i]); //Destinatarios
		}
		
		$mail->isHTML(true); //Acepta HTML
		$mail->CharSet = "utf-8"; //Acepta caracteres
		$mail->Subject = 'Notificación Revisión de Lista de Materiales - Sistema NetSuite';

		$mailContent	  = "<div class='row'>
							<h1 style='color:#45556E;'>Notificación de NetSuite</h1>
							<table>
								<tr>
									<td>Buen día:</td>
								</tr>
								<tr>
									<td>El usuario <strong>".$input["dato"]['firmante']."</strong> ha ".$input["dato"]['accion']." la <strong>Revisión de Lista de Materiales</strong> en el Sistema NetSuite.</td>
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
									<td><strong>Lista</strong></td>
									<td>:</td>
									<td>".$input["dato"]['lista']."</td>
								</tr>
								<tr>
									<td><strong>Revisión</strong></td>
									<td>:</td>
									<td>".$input["dato"]['revision']."</td>
								</tr>
								<tr>
									<td><strong>Link de Registro</strong></td>
									<td>:</td>
									<td><a href='https://6462530.app.netsuite.com/app/accounting/manufacturing/bomrevision.nl?id=".$input["dato"]['id']."&e=T&l=T' target='_blank'>Ver registro</a></td>
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
	
	public function imprimirPDF()
	{
		header('Access-Control-Allow-Origin: *');
		
		$input = json_decode(file_get_contents("php://input"), true);
		
		$objModel = $this->loadModel("revisionLM");
		$dato_cabecera_RLM = $objModel->getCabeceraRLM(intval($input['dato']['id']));

		$this->getLibrary('mpdf/mpdf');

		$mpdf = new mPDF();
		
		$mpdf -> SetTitle('REVISION DE LISTA DE MATERIALES');
		
		$mpdf->SetDefaultFont("Arial");
		
		$html="<style>@page {
				 margin-top: 40px;
				 margin-bottom: 40px;
				 margin-right: 40px;
				 margin-left: 40px;
				}</style>";
				
		$mpdf->WriteHTML($html);
		
		$codigo = substr($dato_cabecera_RLM[0]['productoBulkLinea'],0,strpos($dato_cabecera_RLM[0]['productoBulkLinea'],'0'));
		switch($codigo){
			case 'BK';
				$titulo = "REVISIÓN DE LISTA DE MATERIALES FABRICACION";
				break;
			case 'INY':
			case 'SOL':
			case 'SEM':
			case 'SOT':
			case 'LIQ':
			case 'POL':
				$titulo = "REVISIÓN DE LISTA DE MATERIALES ENVASE Y EMPAQUE";
				break;
			case 'IMP':
				$titulo = "REVISIÓN DE LISTA DE MATERIALES IMPORTADOS";
				break;
			case 'MM':
				$titulo = "REVISIÓN DE LISTA DE MATERIALES MUESTRA MÉDICAS";
				break;
			default:
				$titulo = "SIN TITULO";
				break;
		}
		
		$mpdf->WriteHTML("
			<table width='100%'>
				<tr>
					<td rowspan='4'>
						<img src='http://6462530.shop.netsuite.com/core/media/media.nl?id=4499&c=6462530&h=s4vSW99RWLBxlBgfKjmRz8LD0h85_fj5MMZjCgrJwYDFq4v7' width='160' height='60'>
						<p style='font-size:14px;'>Laboratorios Biomont S.A.</p>
					</td>
					<td style='text-align:right;'>
						<p style='font-size:16px;font-weight:bold;'>&nbsp;</p>
					</td>
				</tr>
				<tr>
					<td style='text-align:right;'>
						<p style='font-size:17px;font-weight:bold;'>".$titulo."</p>
					</td>
				</tr>
				<tr>
					<td style='text-align:right;'>
						<p style='font-size:14px;'><b>ID INTERNO: </b>".$dato_cabecera_RLM[0]['idRevision']."</p>
					</td>
				</tr>
			</table>
		");
		
		$mpdf->Ln(8);

		$mpdf->WriteHTML("
			<table style='width:100%;border:1px solid black;'>
				<tr>
					<td style='padding:2.5px;width:16%;text-align:left;font-size:10px;'><b>Revisión/Producto</b></td>
					<td style='padding:2.5px;width:1%;text-align:left;font-size:10px;'>:</td>
					<td style='padding:2.5px;width:35%;text-align:left;font-size:10px;'>".$dato_cabecera_RLM[0]['nombreRevision']."</td>
					<td style='padding:2.5px;width:26%;text-align:left;font-size:10px;'><b>Fecha creación:</b></td>
					<td style='padding:2.5px;width:1%;text-align:left;font-size:10px;'>:</td>
					<td style='padding:2.5px;width:21%;text-align:left;font-size:10px;'>".$dato_cabecera_RLM[0]['fechaCreado']."</td>
				</tr>
				<tr>
					<td style='padding:2.5px;width:16%;text-align:left;font-size:10px;'><b>Producto Bulk/Linea</b></td>
					<td style='padding:2.5px;width:1%;text-align:left;font-size:10px;'>:</td>
					<td style='padding:2.5px;width:35%;text-align:left;font-size:10px;'>".$dato_cabecera_RLM[0]['productoBulkLinea']."</td>
					<td style='padding:2.5px;width:26%;text-align:left;font-size:10px;'></td>
					<td style='padding:2.5px;width:1%;text-align:left;font-size:10px;'></td>
					<td style='padding:2.5px;width:21%;text-align:left;font-size:10px;'>&nbsp;</td>
				</tr>
				<tr>
					<td style='padding:2.5px;width:16%;text-align:left;font-size:10px;'><b>Fórmula</b></td>
					<td style='padding:2.5px;width:1%;text-align:left;font-size:10px;'>:</td>
					<td style='padding:2.5px;width:35%;text-align:left;font-size:10px;'>".$dato_cabecera_RLM[0]['nombreListaMateriales']."</td>
					<td style='padding:2.5px;width:26%;text-align:left;font-size:10px;'></td>
					<td style='padding:2.5px;width:1%;text-align:left;font-size:10px;'></td>
					<td style='padding:2.5px;width:21%;text-align:left;font-size:10px;'>&nbsp;</td>
				</tr>
				<tr>
					<td style='padding:2.5px;width:16%;text-align:left;font-size:10px;'><b>Principio Activo</b></td>
					<td style='padding:2.5px;width:1%;text-align:left;font-size:10px;'>:</td>
					<td style='padding:2.5px;width:35%;text-align:left;font-size:10px;'><p style='background-color:#D6DBDF;color:#D6DBDF;'>Hola</p></td>
					<td style='padding:2.5px;width:26%;text-align:left;font-size:10px;'>&nbsp;</td>
					<td style='padding:2.5px;width:1%;text-align:left;font-size:10px;'>&nbsp;</td>
					<td style='padding:2.5px;width:21%;text-align:left;font-size:10px;'>&nbsp;</td>
				</tr>
			</table>
		");
		
		$mpdf->Ln(5);
		
		$mpdf->WriteHTML("
			<table width='100%' style='border:1px solid black; border-collapse:collapse;'>
				<tr>
					<td align='center' style='width:12%;font-size:10px;border:1px solid black;padding:3px;'><b>Codigo</b></td>
					<td align='center' style='width:48%;font-size:10px;border:1px solid black;padding:3px;'><b>Descripción</b></td>
					<td align='center' style='width:14%;font-size:10px;border:1px solid black;padding:3px;'><b>Rendimiento</b></td>
					<td align='center' style='width:13%;font-size:10px;border:1px solid black;padding:3px;'><b>Cantidad</b></td>
					<td align='center' style='width:13%;font-size:10px;border:1px solid black;padding:3px;'><b>UND</b></td>
				</tr>
		");
		
		//<td align='center' style='width:15%;font-size:10px;border:1px solid black;padding:3px;".$principio_activo."'>".$art['principioActivo']."</td>
		//<td align='center' style='width:15%;font-size:10px;border:1px solid black;padding:3px;'><b>Principio Activo</b></td>

		$dato_detalle_RLM = $objModel->getDetalleRLM(intval($input['dato']['id']));

		foreach($dato_detalle_RLM as $art){
			
			if($art['principioActivo']=='T'){
				$principio_activo="background-color:#D6DBDF;color:#000000";
			}else{
				$principio_activo="";
			}
			
			$mpdf->WriteHTML("
				<tr>
					<td align='center' style='width:12%;font-size:10px;border:1px solid black;padding:3px;".$principio_activo."'>".$art['codigo']."</td>
					<td align='left' style='width:48%;font-size:10px;border:1px solid black;padding:3px;".$principio_activo."'>".$art['descripcion']."</td>
					<td align='center' style='width:14%;font-size:10px;border:1px solid black;padding:3px;".$principio_activo."'>".number_format($art['rendimiento']*100, 2)." %</td>
					<td align='center' style='width:13%;font-size:10px;border:1px solid black;padding:3px;".$principio_activo."'>".number_format($art['cantidad'], 5)."</td>
					<td align='center' style='width:13%;font-size:10px;border:1px solid black;padding:3px;".$principio_activo."'>".$art['unidad']."</td>
				</tr>
			");
		}
		
		$mpdf->WriteHTML("
			</table>
		");
		
		$mpdf->Ln(5);
		
		date_default_timezone_set('America/Lima');

		$mpdf->WriteHTML("
			<table class='tabla2' width='90%'>
				<tr>
					<td colspan=2 style='font-size:11px;'><strong>Emisión, Revisión y Aprobación</strong></td>
				</tr>
				<tr>
					<td class='celda1' style='font-size:9px;text-align:center;'>Emitido por</td>
					<td class='celda1' style='font-size:9px;text-align:center;'>Revisado por</td>
					<td class='celda1' style='font-size:9px;text-align:center;'>Aprobado por</td>
				</tr>
				<tr>
					<td class='celda1' style='font-size:9px;text-align:center;height:60px;border-bottom:0px solid white;'>".$dato_cabecera_RLM[0]['firmanteEmitido']."<br>".$dato_cabecera_RLM[0]['fechaFirmaEmitido']."</td>
					<td class='celda1' style='font-size:9px;text-align:center;height:60px;border-bottom:0px solid white;'>".$dato_cabecera_RLM[0]['firmanteRevisado']."<br>".$dato_cabecera_RLM[0]['fechaFirmaRevisado']."</td>
					<td class='celda1' style='font-size:9px;text-align:center;height:60px;border-bottom:0px solid white;'>".$dato_cabecera_RLM[0]['firmanteAprobado']."<br>".$dato_cabecera_RLM[0]['fechaFirmaAprobado']."</td>
				</tr>
				<tr>
					<td style='font-size:7px;text-align:center;border-left:1px solid black;border-right:1px solid black;border-top:0px solid white;'>Firma digital desde NETSUITE</td>
					<td style='font-size:7px;text-align:center;border-right:1px solid black;border-top:0px solid white;'>Firma digital desde NETSUITE</td>
					<td style='font-size:7px;text-align:center;border-right:1px solid black;border-top:0px solid white;'>Firma digital desde NETSUITE</td>
				</tr>
				<tr>
					<td class='celda1' style='font-size:9px;text-align:center;'>Logística</td>
					<td class='celda1' style='font-size:9px;text-align:center;'>Investigación y Desarrollo</td>
					<td class='celda1' style='font-size:9px;text-align:center;'>Gerencia de Planta</td>
				</tr>
			</table>
		");
		
		$fecha = date("YmdHis",time());
		
		$archivo= "downloads/RLM_".$dato_cabecera_RLM[0]['idListaMateriales']."_".$dato_cabecera_RLM[0]['idRevision']."_".$fecha.".pdf";

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
	
	/*public function imprimirEstadoListaMaterialesPDF()
	{
		header('Access-Control-Allow-Origin: *');
		
		$input = json_decode(file_get_contents("php://input"), true);
		
		$objModel = $this->loadModel("revisionLM");
		$dato_lista_materiales = $objModel->getEstadoListaMateriales();

		$this->getLibrary('mpdf/mpdf');

		$mpdf = new mPDF();
		
		$mpdf -> SetTitle('REPORTE ESTADO DE LISTA DE MATERIALES');
		
		$mpdf->SetDefaultFont("Arial");
		
		$html="<style>@page {
				 margin-top: 25px;
				 margin-bottom: 25px;
				 margin-right: 25px;
				 margin-left: 25px;
				}</style>";
				
		$mpdf->WriteHTML($html);
		
		$mpdf->WriteHTML("
			<table width='100%'>
				<tr>
					<td rowspan='4'>
						<img src='http://6462530.shop.netsuite.com/core/media/media.nl?id=4499&c=6462530&h=s4vSW99RWLBxlBgfKjmRz8LD0h85_fj5MMZjCgrJwYDFq4v7' width='160' height='60'>
						<p style='font-size:14px;'>Laboratorios Biomont S.A.</p>
					</td>
					<td style='text-align:right;'>
						<p style='font-size:16px;font-weight:bold;'>&nbsp;</p>
					</td>
				</tr>
				<tr>
					<td style='text-align:right;'>
						<p style='font-size:17px;font-weight:bold;'>REPORTE ESTADO LISTA DE MATERIALES</p>
					</td>
				</tr>
				<tr>
					<td style='text-align:right;'>
						<p style='font-size:14px;'></p>
					</td>
				</tr>
			</table>
		");
		
		$mpdf->Ln(8);
		
		$mpdf->WriteHTML("
			<table width='100%' style='border:1px solid black; border-collapse:collapse;'>
				<tr>
					<td align='center' style='width:26%;font-size:10px;border:1px solid black;padding:3px;'><b>Lista de Materiales</b></td>
					<td align='center' style='width:26%;font-size:10px;border:1px solid black;padding:3px;'><b>Revisión</b></td>
					<td align='center' style='width:22%;font-size:10px;border:1px solid black;padding:3px;'><b>Producto Bulk/Linea</b></td>
					<td align='center' style='width:17%;font-size:10px;border:1px solid black;padding:3px;'><b>Fecha Creación</b></td>
					<td align='center' style='width:9%;font-size:10px;border:1px solid black;padding:3px;'><b>Estado</b></td>
				</tr>
		");

		foreach($dato_lista_materiales as $rep){
			
			switch($rep['estado']){
				case 'Aprobado':
					$background="background-color:#9FFF33;color:#000000;";
					break;
				case 'Revisado':
					$background="background-color:#FFE840;color:#000000;";
					break;
				case 'Emitido':
					$background="background-color:#FCFFAD;color:#000000;";
					break;
				default:
					$background="";
					break;
			}
			
			$mpdf->WriteHTML("
				<tr>
					<td align='left' style='width:26%;font-size:10px;border:1px solid black;padding:3px;".$background."'>".$rep['nombreListaMateriales']."</td>
					<td align='left' style='width:26%;font-size:10px;border:1px solid black;padding:3px;".$background."'>".$rep['nombreRevision']."</td>
					<td align='left' style='width:22%;font-size:10px;border:1px solid black;padding:3px;".$background."'>".$rep['productoBulkLinea']."</td>
					<td align='left' style='width:17%;font-size:10px;border:1px solid black;padding:3px;".$background."'>".$rep['fechaCreado']."</td>
					<td align='left' style='width:9%;font-size:10px;border:1px solid black;padding:3px;".$background."'>".$rep['estado']."</td>
				</tr>
			");
		}
		
		$mpdf->WriteHTML("
			</table>
		");

		$fecha = date("YmdHis",time());
		
		$archivo= "downloads/RELM_".$dato_cabecera_RLM[0]['idListaMateriales']."_".$dato_cabecera_RLM[0]['idRevision']."_".$fecha.".pdf";

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
			"file"=>$archivo,
		]);
	}*/
	
	public function imprimirEstadoListaMaterialesEXCEL()
	{
		header('Access-Control-Allow-Origin: *');

		$this->getLibrary('PHPExcel/Classes/PHPExcel');
        $this->getLibrary('PHPExcel/Classes/PHPExcel/Reader/Excel5');
        $this->getLibrary('PHPExcel/Classes/PHPExcel/Reader/Excel2007');
		
		$objPHPExcel = new PHPExcel();
		
		$objModel = $this->loadModel("revisionLM");
		$resultado = $objModel->getEstadoListaMateriales();
		
		$objPHPExcel->getProperties()
							->setCreator("Laboratorios Biomont") //Autor
							->setLastModifiedBy("Laboratorios Biomont") //Ultimo usuario que lo modificó
							->setTitle("Reporte Estado de Lista de Materiales")
							->setSubject("Reporte Estado de Lista de Materiales") //Asunto
							->setDescription("Reporte Estado de Lista de Materiales")//Descripción
							->setKeywords("Reporte Estado de Lista de Materiales") //Etiquetas
                            ->setCategory("Reporte Estado de Lista de Materiales");  //Categorias
		
		$objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1','REPORTE ESTADO DE LISTA DE MATERIALES')
                    ->mergeCells('A1:E1');
					
		$objPHPExcel->getActiveSheet()->setShowGridlines(false);
		
		$estilos_titulo = [
			'borders' => [
				'allborders' => ['style' => PHPExcel_Style_Border::BORDER_THIN], 
			]
        ];

		$estilos_cabeceras = [
        	'font' => [
				'name'      => 'Verdana',
				'bold'      => true,
				'italic'    => false,
				'strike'    => false,
				'size'      => 11,
				'color'     => ['rgb' => '000000']
            ],
	       'fill' 	=> [
				'type'		 => PHPExcel_Style_Fill::FILL_SOLID,
				'rotation'   => 90,
				'startcolor' => ['rgb' => 'eaecef'],
				'endcolor'   => ['rgb' => 'eaecef']
			],
            'borders' => [
				'allborders' => ['style' => PHPExcel_Style_Border::BORDER_THIN]], 
				'alignment' =>  [
						'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
						'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
						'rotation'   => 0,
						'wrap'       => TRUE
				]
        ];
		
		$estilos_celdas = [
        	'font' => [
	        	'name'      => 'Verdana',
    	        'bold'      => false,
        	    'italic'    => false,
                'strike'    => false,
               	'size'      => 8,
	            'color'     => ['rgb' => '000000']
            ],
            'borders' => [
					'allborders' => ['style' => PHPExcel_Style_Border::BORDER_THIN]], 
					'alignment' =>  [
							'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_GENERAL,
							'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
							'rotation'   => 0,
							'wrap'       => FALSE
					]
			
        ];
			
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A3',  "LISTA DE MATERIALES");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B3',  "REVISION");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C3',  "PRODUCTO BULK/LINEA");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D3',  "FECHA CREACION");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E3',  "ESTADO");
		
		$i=4;
		foreach($resultado as $dato){		

			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.($i), $dato['nombreListaMateriales']);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.($i), $dato['nombreRevision']);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.($i), $dato['productoBulkLinea']);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.($i), $dato['fechaCreado']);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.($i), $dato['estado']);
			
			$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':E'.$i)->applyFromArray($estilos_celdas);
			$objPHPExcel->getDefaultStyle()->getAlignment('A'.$i.':E'.$i)->setWrapText(true);

			$i++;
		}
		
		$objPHPExcel->getActiveSheet()->getStyle('A1:E1')->applyFromArray($estilos_cabeceras);
		$objPHPExcel->getActiveSheet()->getStyle('A3:E3')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('A3:E3')->applyFromArray($estilos_titulo);

		$sheet = $objPHPExcel->getActiveSheet();
		foreach($sheet->getColumnIterator() as $column){
			$sheet->getColumnDimension($column->getColumnIndex())->setAutosize(true);
		}
		
		$objPHPExcel->getActiveSheet()->setTitle('Reporte');
		
		$objPHPExcel->setActiveSheetIndex(0);
		
		$archivo = "EXPORT_".date('Ymd_His');

		ob_start();
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save("php://output");
        $xlsData = ob_get_contents();
        ob_end_clean();
		
		header('Content-Type: application/vnd.ms-excel');
		//header('Content-Disposition: attachment;filename='.$archivo);
		header('Cache-Control: max-age=0');
		header('Cache-Control: cache, must-revalidate');
		header('Pragma: public');
		
		header('Content-type: application/json; charset=utf-8');
		echo json_encode([
					'msg' => 'ok',
					'file' => "data:application/vnd.ms-excel;base64,".base64_encode($xlsData),
					'name' => $archivo
				]);

	}

}