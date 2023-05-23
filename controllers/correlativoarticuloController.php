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
		echo json_encode(["res" =>$res]);
	}

}