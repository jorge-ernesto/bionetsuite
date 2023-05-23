<?php

class correlativoarticuloModel extends Model
{

	public function __construct()
	{
		parent::__construct();
	}
	
	public function getCorrelativoLineaArticulo($linea)
	{
		$sql="SELECT ultimo_correlativo FROM tb_correlativo_lineas WHERE linea='$linea'";
		$rs  = $this->_db->get_Connection4()->Execute($sql);
		return $rs->fields[0];
	}
	
}