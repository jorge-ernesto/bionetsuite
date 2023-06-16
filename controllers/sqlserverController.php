<?php

class sqlserverController extends Controller
{

	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		echo "BIOMONT NETSUITE OK";
	}
	
	public function procesoReporteVenta(){
		
		$objModel = $this->loadModel("sqlserver");
		$result_oracle = $objModel->getOracle();
		
		if(count($result_oracle)>0){
			$result_sqlserver = $objModel->insertSQLSERVER($result_oracle);
		}
		
		print_r($result_sqlserver);
		
	}

}