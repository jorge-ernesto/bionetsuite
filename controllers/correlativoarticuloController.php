<?php

class correlativoarticuloController extends Controller
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
	
	public function getCorrelativoLineaArticulo(){
		
		header('Access-Control-Allow-Origin: *');
		
		$input = json_decode(file_get_contents("php://input"), true);
		
		$objModel = $this->loadModel("correlativoarticulo");
		$res = $objModel->getCorrelativoLineaArticulo($input['dato']['linea']);
		
		header("Content-type: application/json; charset=utf-8");
		echo json_encode([
			"num" =>intval($res[0]),
			"nomen"=>$res[1]
		]);
	}
	
	public function updateCorrelativoLineaArticulo()
	{
		header('Access-Control-Allow-Origin: *');
		
		$input = json_decode(file_get_contents("php://input"), true);

		$objModel = $this->loadModel("correlativoarticulo");
		$res = $objModel->updateCorrelativoLineaArticulo(intval($input['dato']['correlativo_vista']),$input['dato']['nomenclatura']);
		
		header("Content-type: application/json; charset=utf-8");
		echo json_encode([
			"res" 	=> intval($res)
		]);
	}
	
	public function sendEmailNotification()
	{
		header('Access-Control-Allow-Origin: *');
		
		$input = json_decode(file_get_contents("php://input"), true);

		$asunto = "Notificación de ".$input["dato"]['concepto']." de Artículo - Sistema NetSuite";
		
		/*
		$incrustado = "";
		foreach($input["dato"]['email_notificacion_aux'] as $email){
			$incrustado .= "<p>".$email."</p>";
		}
		*/
		
		
		if($input["dato"]['accion'] !== 'Creado'){
			
			$link = "<tr>
						<td><strong>Link de Registro</strong></td>
						<td>:</td>
						<td><a href='https://6462530.app.netsuite.com/app/common/item/item.nl?id=".$input["dato"]['id_formulario']."' target='_blank'>Ver registro</a></td>
					</tr>";
						
		}else {
		
			$link = "";
			
		}
		
		$mailContent= "<div>
							<h1 style='color:#45556E;'>Notificación de NetSuite</h1>
							<table>
								<tr>
									<td>Buen día:</td>
								</tr>
								<tr>
									<td>El usuario <strong>".$input["dato"]['nombre_ejecutor']."</strong> ha ".$input["dato"]['accion']." <strong> un Artículo</strong> en el Sistema NetSuite.</td>
								</tr>
							</table>
							<br><br>
							<table>
								<tr>
									<td><strong>Concepto</strong></td>
									<td>:</td>
									<td><span>".$input["dato"]['concepto']." de Artículo</span></td>
								</tr>
								<tr>
									<td><strong>Código</strong></td>
									<td>:</td>
									<td>".$input["dato"]['codigo_articulo']."</td>
								</tr>
								<tr>
									<td><strong>Descripción</strong></td>
									<td>:</td>
									<td>".$input["dato"]['descripcion_articulo']."</td>
								</tr>
								<tr>
									<td><strong>Línea</strong></td>
									<td>:</td>
									<td>".$input["dato"]['linea_articulo']."</td>
								</tr>
								".$link."
							</table>			
							<br>
							<table>
								<tr>
									<td><h1 style='color:#45556E;'>Atentamente</h1></td>
								</tr>
								<tr>
									<td><h1 style='color:#45556E;'>Notificaciones NetSuite</h1></td>
								</tr>
							</table>
						</div>";
						
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
		
		for($i = 0; $i < count($input["dato"]['email_notificacion']); $i++) {
			$mail->AddAddress($input["dato"]['email_notificacion'][$i]); //Destionatarios
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