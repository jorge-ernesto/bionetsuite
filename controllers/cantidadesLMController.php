<?php

class cantidadesLMController extends Controller
{

	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		echo "CANTIDADES LISTA MATERIALES";
	}
	
	public function guardarDatos(){
		$input = json_decode(file_get_contents("php://input"), true);
		
		$objModel = $this->loadModel("cantidadesLM");
		
		$res = 1;
		foreach ($input["dato"] as $dato){
			$res *= $objModel->guardarDatos($dato['id_OT'], $dato['num_OT'], $dato['ensamblaje'], $dato['componente'], $dato['cantidad'], $dato['semana']);
			//$res *= $objModel->guardarDatos($dato[0], $dato[1], $dato[2], $dato[3], $dato[4], $dato[5]);
		}
		
		header('Access-Control-Allow-Origin: *');
		header("Content-type: application/json; charset=utf-8");
		echo json_encode(["res" =>intval($res)]);
	}

}