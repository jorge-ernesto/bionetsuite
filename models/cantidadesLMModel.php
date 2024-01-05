<?php

class cantidadesLMModel extends Model
{

	public function __construct()
	{
		parent::__construct();
	}
	
	/*public function guardarDatos($datos)
	{
		$sql_ = "DELETE FROM tb_registro_cantidad WHERE num_ot='".$datos[0][1]."'";
		$rs_ = $this->_db->get_Connection1()->Execute($sql_);
		if($rs_){
			$c=0;
			$sql="";
			foreach($datos as $data){
				$sql = "INSERT INTO tb_registro_cantidad VALUES (null,".$data[0].",'".$data[1]."','".$data[2]."','".$data[3]."',".$data[4].",'".$data[5]."',NOW());";
				$rs = $this->_db->get_Connection1()->Execute($sql);
				$c++;
			}
			return $rs;
		}
		
	}*/
	
	public function guardarDatos($id_OT,$num_OT,$ensamblaje,$componente,$cantidad,$semana)
	{
		
		$sql = "INSERT INTO tb_registro_cantidad VALUES (null,".$id_OT.",'".$num_OT."','".$ensamblaje."','".$componente."',".$cantidad.",'".$semana."',NOW());";
		$res = $this->_db->get_Connection1()->Execute($sql);
		return $res;
	}
	
}