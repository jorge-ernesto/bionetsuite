<?php

class emailfirmaOTController extends Controller
{

	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		echo "FIRMAR ORDEN DE TRABAJO Y ENVIAR EMAIL";
	}
	
	/*public function sendEmail()
	{
		
		header('Access-Control-Allow-Origin: *');
		
		$input = json_decode(file_get_contents("php://input"), true);

		if($input["dato"]['email']=='ajuste'){
			$emails = ["jpena@biomont.com.pe","cpomiano@biomont.com.pe","mramirez@biomont.com.pe","mmancilla@biomont.com.pe","aquinones@biomont.com.pe"];
		}
		else if($input["dato"]['email']=='todos'){
			$emails = ["jpena@biomont.com.pe","mramirez@biomont.com.pe","vgalan@biomont.com.pe","mmancilla@biomont.com.pe","csuncion@biomont.com.pe","mperez@biomont.com.pe"];
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
		$mail->Subject = 'Notificación de Orden de Trabajo - Sistema NetSuite';

		$mailContent	  = "<div class='row'>
							<h1 style='color:#45556E;'>Notificación de NetSuite</h1>
							<table>
								<tr>
									<td>Buen día:</td>
								</tr>
								<tr>
									<td>El usuario <strong>".$input["dato"]['firmante']."</strong> ha ".$input["dato"]['accion']." la <strong>Orden de Trabajo</strong> en el Sistema NetSuite.</td>
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
			$est=1; //error al enviar email
		} else {
			$est=0; //email enviado
		}
						
		
		header("Content-type: application/json; charset=utf-8");
		echo json_encode(["est"=>$est]);

	}*/
	
	public function sendEmail_cesar()
	{
		
		header('Access-Control-Allow-Origin: *');
		
		$input = json_decode(file_get_contents("php://input"), true);
		
		if($input["dato"]['email']=='ajuste'){
			$asunto="Notificación de Orden de Trabajo - Sistema NetSuite";
			//$emails = ["jpena@biomont.com.pe","fcastro@biomont.com.pe"];
			$emails = ["jpena@biomont.com.pe","cpomiano@biomont.com.pe","mramirez@biomont.com.pe","mmancilla@biomont.com.pe","aquinones@biomont.com.pe"];
			
			$mailContent	  = "<div class='row'>
							<h1 style='color:#45556E;'>Notificación de NetSuite</h1>
							<table>
								<tr>
									<td>Buen día:</td>
								</tr>
								<tr>
									<td>El usuario <strong>".$input["dato"]['firmante']."</strong> ha ".$input["dato"]['accion']." la <strong>Orden de Trabajo</strong> en el Sistema NetSuite.</td>
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
		}
		else if($input["dato"]['email']=='todos'){
			$asunto="El usuario ".$input["dato"]['firmante']." ha ".$input["dato"]['accion']." la Orden de Trabajo ".$input["dato"]['numOP'];
			//$emails = ["jpena@biomont.com.pe","fcastro@biomont.com.pe"];
			$emails = ["jpena@biomont.com.pe","mramirez@biomont.com.pe","vgalan@biomont.com.pe","mmancilla@biomont.com.pe","csuncion@biomont.com.pe","mperez@biomont.com.pe"];
			
			$mailContent	  = "<div class='row'>
							<h1 style='color:#45556E;'>Notificación de NetSuite</h1>
							<table>
								<tr>
									<td>Buen día:</td>
								</tr>
								<tr>
									<td>El usuario <strong>".$input["dato"]['firmante']."</strong> ha ".$input["dato"]['accion']." la <strong>Orden de Trabajo</strong> en el Sistema NetSuite.</td>
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
									<td><strong>Almacén</strong></td>
									<td>:</td>
									<td>".$input["dato"]['almacen']."</td>
								</tr>
								<tr>
									<td><strong>Link de Registro</strong></td>
									<td>:</td>
									<td><a href='https://6462530.app.netsuite.com/app/accounting/transactions/transaction.nl?id=".$input["dato"]['idOP']."' target='_blank'>Ver registro</a></td>
								</tr>
								<tr>
									<td><strong>Códigos de Artículos</strong></td>
									<td></td>
									<td></td>
								</tr>
								</table>
								<table style='border:1px solid black;border-collapse:collapse;'>
									<tr>
										<th style='border:1px solid black;padding:3px;'>Código</th>
										<th style='border:1px solid black;padding:3px;'>Descripción</th>
									</tr>";	
							foreach($input["dato"]['articulos'] as $art){
		$mailContent		.=		"<tr>
										<td style='border:1px solid black;padding:3px;'>".$art[0]."</td>
										<td style='border:1px solid black;padding:3px;'>".$art[1]."</td>
									</tr>";					
							}
		$mailContent		.=	"</table>						
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
		$mail->CharSet = "utf-8"; //Acepta caracteres especiales
		$mail->Subject = $asunto;			
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