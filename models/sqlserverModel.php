<?php

class sqlserverModel extends Model
{

	public function __construct()
	{
		parent::__construct();
	}
	
	public function getOracle()
	{
		$sql = "select 
			X.item ID_ARTICULO, 
			NVL(X.location,888888) ID_ALMACEN,
			TO_NUMBER(TO_CHAR(Y.trandate,'MM')) NRO_MES,
			TO_NUMBER(TO_CHAR(Y.trandate,'yyyy')) NRO_ANIO,
			TRIM(V.itemid) CODIGO_ARTICULO, 
			TRIM(V.displayname) DESC_ARTICULO, 
			TRIM(W.fullname) DESC_ALMACEN, 
			X.transaction ID_TRANSACCION, 
			TRIM(Y.tranid) NRO_DOCUMENTO, 
			TO_CHAR(Y.trandate, 'dd/MM/yyyy') FECHA_DOCUMENTO,
			Z.standardcost COSTO_ESTANDAR 
			from (select * from transactionline where accountinglinetype='ASSET') X 
			inner join transaction Y on (X.transaction=Y.id) 
			inner join TransactionAccountingLineCostComponent Z on (Z.transaction=X.transaction and Z.transactionline=X.id)
			inner join location W on (W.id=X.location)
			inner join (select * from item where costingmethod='STANDARD') V on (V.id=X.item)
			where Y.abbrevtype='INVREVAL' and X.id>1 and  TO_NUMBER(TO_CHAR(Y.trandate,'yyyy')) in (2023) and TO_NUMBER(TO_CHAR(Y.trandate,'MM'))=9
			order by Y.trandate,X.item, X.location;"; 
		$rs = $this->_db->get_Connection()->Execute($sql);
		$contador = $rs->RecordCount();
		if (intval($contador) > 0) {
			while (!$rs->EOF) {
				$datos[] = [
					"ID_ARTICULO" 		=> $rs->fields[0],
					"ID_ALMACEN" 		=> $rs->fields[1],
					"NRO_MES" 			=> $rs->fields[2],
					"NRO_ANIO" 			=> $rs->fields[3],
					"CODIGO_ARTICULO" 	=> $rs->fields[4],
					"DESC_ARTICULO" 	=> $rs->fields[5],
					"DESC_ALMACEN" 		=> $rs->fields[6],
					"ID_TRANSACCION" 	=> $rs->fields[7],
					"NRO_DOCUMENTO" 	=> $rs->fields[8],
					"FECHA_DOCUMENTO" 	=> $rs->fields[9],
					"COSTO_ESTANDAR" 	=> $rs->fields[10],
				];
				$rs->MoveNext();
			}
		}

		return $datos;
		
	}
	
	public function insertSQLSERVER($datos)
	{		
		$contar=0;
		foreach($datos as $data){
			$sql ="INSERT INTO dbo.NS_CN001_REPORTE_VENTA_COSTO_T_REVALUACIONES_PRUEBA VALUES
						   (".$data['ID_ARTICULO']."
						   ,".$data['ID_ALMACEN']."
						   ,".$data['NRO_MES']."
						   ,".$data['NRO_ANIO']."
						   ,'".trim($data['CODIGO_ARTICULO'])."'
						   ,'".utf8_encode(trim($data['DESC_ARTICULO']))."'
						   ,'".utf8_encode(trim($data['DESC_ALMACEN']))."'
						   ,".$data['ID_TRANSACCION']."
						   ,".$data['NRO_DOCUMENTO']."
						   ,'".explode("/",$data['FECHA_DOCUMENTO'])[2]."-".explode("/",$data['FECHA_DOCUMENTO'])[1]."-".explode("/",$data['FECHA_DOCUMENTO'])[0]."'
						   ,".$data['COSTO_ESTANDAR']."
						   ,GETDATE());";
				
			$rs = $this->_db->get_Connection5()->Execute($sql);
			if($rs){
				$contar++;
			}
		}

		return [$rs, $contar];

	}
	
	public function conexion()
	{
		$sql = "select * from NS_FI002_T_PAGO_CLIENTES_LOG_CORREO_ENVIO";
		$rs = $this->_db->get_Connection5()->Execute($sql);
		$contador = $rs->RecordCount();
		if (intval($contador) > 0) {
			while (!$rs->EOF) {
				$datos[] = [
					"columna0" => $rs->fields[0],
					"columna1" => $rs->fields[1],
					"columna2" => $rs->fields[2],
					"columna3" => $rs->fields[3],
					"columna4" => $rs->fields[4],
					"columna5" => $rs->fields[5],
					"columna6" => $rs->fields[6],
					"columna7" => $rs->fields[7],
					"columna8" => $rs->fields[8],
					"columna9" => $rs->fields[9],
					"columna10" => $rs->fields[10],
					"columna11" => $rs->fields[11],
					"columna12" => $rs->fields[12],
					"columna13" => $rs->fields[13],
					"columna14" => $rs->fields[14],
					"columna15" => $rs->fields[15],
				];
				$rs->MoveNext();
			}
		}
		return $datos;
		
	}
	
}
