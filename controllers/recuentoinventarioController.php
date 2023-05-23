<?php

class recuentoinventarioController extends Controller
{

	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		echo "RECUENTO DE INVENTARIO";
	}
	
	public function getRecuento()
	{
		header('Access-Control-Allow-Origin: *');
		
		$input = json_decode(file_get_contents("php://input"), true);
		
		header('Content-type: application/json; charset=utf-8');
		echo json_encode([
			"res"=>$input["dato"],
		]);
		
	}

}