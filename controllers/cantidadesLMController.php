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
		
		$el = $objModel->eliminarDatos($input["dato"][0]['num_OT']);

		$res = 1;
		foreach ($input["dato"] as $dato){
			$res *= $objModel->guardarDatos($dato['id_OT'], $dato['num_OT'], $dato['ensamblaje'], $dato['componente'], $dato['cantidad'], $dato['semana']);
		}
		
		header('Access-Control-Allow-Origin: *');
		header("Content-type: application/json; charset=utf-8");
		echo json_encode(["res" =>intval($res)]);

	}

}