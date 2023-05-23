<?php

class numeroanalisisController extends Controller
{

	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		echo "Proyecto Número de Análisis";
	}
	
	public function getCorrelativo()
	{
		$input = json_decode(file_get_contents("php://input"), true);
		
		$posicion_primer_cero = strpos($input["dato"]["codigo"],"0");
		$cod_evaluar = substr($input["dato"]["codigo"],0,$posicion_primer_cero);
		
		$objModel = $this->loadModel("numeroanalisis");
		$result = $objModel->getCorrelativo($cod_evaluar);
		
		$linea = explode("-",$result)[0];
		$correlat = explode("-",$result)[1];
		
		header('Access-Control-Allow-Origin: *');
		header("Content-type: application/json; charset=utf-8");
		echo json_encode([
				"linea" 	=> $linea,
				"correlat" 	=> intval($correlat),
		]);
	}
	
	public function updateCorrelativo()
	{
		$input = json_decode(file_get_contents("php://input"), true);
		
		$objModel = $this->loadModel("numeroanalisis");
		$res = $objModel->updateCorrelativo($input["dato"]["linea"],$input["dato"]["correlativo"]);
		
		header('Access-Control-Allow-Origin: *');
		header("Content-type: application/json; charset=utf-8");
		echo json_encode([
				"res" 	=> $res
		]);
	}
	
	public function getUltimosCorrelativos()
	{
		$objModel = $this->loadModel("numeroanalisis");
		$res = $objModel->getUltimosCorrelativos();
		$res_MP = explode("/",$res)[0];
		$res_ME = explode("/",$res)[1];
		$res_MCI = explode("/",$res)[2];
		
		header('Access-Control-Allow-Origin: *');
		header("Content-type: application/json; charset=utf-8");
		echo json_encode([
				"MP" 	=> $res_MP,
				"ME" 	=> $res_ME,
				"MCI" 	=> $res_MCI,
		]);
	}

}