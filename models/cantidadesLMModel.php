<?php

class cantidadesLMModel extends Model
{

	public function __construct()
	{
		parent::__construct();
	}
	
	public function guardarDatos($datos)
	{
		$c=0;
		$sql="";
		foreach($datos as $data){
			$sql="INSERT INTO tb_registro_cantidad VALUES (null,".$data[0].",'".$data[1]."','".$data[2]."','".$data[3]."',".$data[4].",'".$data[5]."',NOW());";
			$rs  = $this->_db->get_Connection1()->Execute($sql);
			$c++;
		}
		if($rs){
			return true;
		}else{
			return false;
		}
	}
	
}