<?php

class correlativoarticuloModel extends Model
{

	public function __construct()
	{
		parent::__construct();
	}
	
	public function getCorrelativoLineaArticulo($linea)
	{
		$sql = "SELECT ultimo_correlativo, nomenclatura_articulo FROM tb_correlativo_lineas WHERE linea='$linea'";
		$rs  = $this->_db->get_Connection4()->Execute($sql);
		return [$rs->fields[0],$rs->fields[1]];
	}
	
	public function updateCorrelativoLineaArticulo($nuevo_correlativo,$nomenclatura)
	{
		$sql = "UPDATE tb_correlativo_lineas SET ultimo_correlativo=$nuevo_correlativo WHERE nomenclatura_articulo='$nomenclatura';";
		$rs = $this->_db->get_Connection4()->Execute($sql);
		return $rs;
	}
	
}