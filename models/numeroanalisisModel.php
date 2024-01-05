<?php

class numeroanalisisModel extends Model
{

	public function __construct()
	{
		parent::__construct();
	}
	
	public function getCorrelativo($codevaluar)
	{
		$sql = "SELECT L.id, L.correlativo
			FROM tb_condicion C
			INNER JOIN tb_linea L on L.id=C.id_linea 
			WHERE C.inicial='$codevaluar' and L.anio=2024";
		$rs  = $this->_db->get_Connection2()->Execute($sql);
		
		/*if(intval($rs->fields[0])==1){
			$linea = 'MP';
		}else if(intval($rs->fields[0])==2){
			$linea = 'ME';
		}else{
			$linea = 'MCI';
		}*/
		
		switch(intval($rs->fields[0])){
			//case 1:
			case 4:
				$linea = 'MP';
				break;
			//case 2:
			case 5:
				$linea = 'ME';
				break;
			//case 3:
			case 6:
				$linea = 'MCI';
				break;
		}
		
		return $linea."-".$rs->fields[1];
	}
	
	public function updateCorrelativo($linea,$correlativo)
	{
		$sql = "SELECT id_linea FROM tb_condicion WHERE inicial='".$linea."' and id_linea > 3;";
		$rs = $this->_db->get_Connection2()->Execute($sql);
		
		$sql1 = "UPDATE tb_linea SET correlativo=".intval($correlativo)." WHERE id=".intval($rs->fields[0])." and anio=2024;";
		$rs1 = $this->_db->get_Connection2()->Execute($sql1);
		return $rs1;
		/*if($rs1){
			return true;
		}else{
			return false;
		}*/
	}
	
	public function getUltimosCorrelativos()
	{
		//$sql_MP = "SELECT correlativo FROM tb_linea WHERE id=1;";
		$sql_MP = "SELECT correlativo FROM tb_linea WHERE id=4;";
		$rs_MP = $this->_db->get_Connection2()->Execute($sql_MP);
		
		//$sql_ME = "SELECT correlativo FROM tb_linea WHERE id=2;";
		$sql_ME = "SELECT correlativo FROM tb_linea WHERE id=5;";
		$rs_ME = $this->_db->get_Connection2()->Execute($sql_ME);
		
		//$sql_MCI = "SELECT correlativo FROM tb_linea WHERE id=3;";
		$sql_MCI = "SELECT correlativo FROM tb_linea WHERE id=6;";
		$rs_MCI = $this->_db->get_Connection2()->Execute($sql_MCI);

		return $rs_MP->fields[0]."/".$rs_ME->fields[0]."/".$rs_MCI->fields[0];

	}
}
