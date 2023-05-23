<?php

class reclamoController extends Controller
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
	
	public function sendEmailNotification()
	{
		header('Access-Control-Allow-Origin: *');
		
		$input = json_decode(file_get_contents("php://input"), true);
		
		if($input["dato"]['user_session']!="" or $input["dato"]['user_session']!=null){
			//$emails = ["jpena@biomont.com.pe","jsanchez@biomont.com.pe"];
			$emails =[$input["dato"]['email']];
			
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
			
			//$mail->AddAddress($input["dato"]['email']);

			foreach($emails as $email){
				$mail->AddAddress($email); //Destinatarios
			}
			
			$mail->isHTML(true); //Acepta HTML
			$mail->CharSet = "utf-8"; //Acepta caracteres
			$mail->Subject = 'Notificación de Reclamos - Laboratorios Biomont S.A.';
			
			/*
			<table>
				<tr>
					<td><strong>Link de Registro</strong></td>
					<td>:</td>
					<td><a href='https://6462530.app.netsuite.com/app/crm/support/supportcase.nl?id=".$input["dato"]['id_case']."' target='_blank'>Ver registro</a></td>
				</tr>
			</table>
			<br><br>
			*/

			$mailContent	  = "<div class='row'>
								<h1 style='color:#45556E;'>Notificación de Laboratorios Biomont</h1>
								<table>
									<tr>
										<td>Buen día: </td>
									</tr>
									<tr>
										<td>El usuario <strong>".$input["dato"]['user_session']."</strong> ha puesto en proceso el reclamo <strong>Número ".$input["dato"]['num_case']."</strong> en el Sistema NetSuite.</td>
									</tr>
								</table>
								<br><br>
								
								<table>
									<tr>
										<td><h1 style='color:#45556E;'>Atentamente</h1></td>
									</tr>
									<tr>
										<td><h1 style='color:#45556E;'>Notificaciones Laboratorios Biomont</h1></td>
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
		}
		
		
	}

}