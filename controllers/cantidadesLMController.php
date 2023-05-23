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
		$res = $objModel->guardarDatos($input["dato"]);
		
		header('Access-Control-Allow-Origin: *');
		header("Content-type: application/json; charset=utf-8");
		echo json_encode(["res" =>$res]);
	}

}