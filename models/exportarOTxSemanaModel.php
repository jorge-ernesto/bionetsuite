<?php

class exportarOTxSemanaModel extends Model
{

	public function __construct()
	{
		parent::__construct();
	}
	
	public function getOrdenesProduccionxSemana($nroSemana)
	{
		$sql = "select
			T.ID as idtransaccion,
			T.TranID as NroOpe, 
			T.trandisplayname as nroOrdenTrabajo,
			TLX.itemid as codigoEnsamblaje,
			TLX.displayname as productoEnsamblaje,
		    TLX.quantity as cantidadEnsamblaje,
			T.trandate as FCreacionEnsamblaje, 
			T.custbodybio_cam_lote as LoteEnsamblaje,
			T.custbodybio_cam_fechacaducidad as FexExpEnsamblaje,
			T.custbody93 as semProdEnsamblaje,
			T.custbody94 as nroSemProdEnsamblaje,
			T.custbody41 AS LineaEnsamblaje,
			CL.name as tipoOTEnsamblaje,
			E.firstname || ' ' || E.lastname as EmiNomApeEnsamblaje,
			I.itemid as codProd,
			I.displayname as descProducto,
			(TL.componentyield*100) as rendimiento,
			(CASE WHEN U.abbreviation='GR' THEN (TL.quantity*-1*1000) ELSE (TL.quantity*-1) END) as CantProd,
			U.abbreviation as unidad
			FROM (
				SELECT id,componentyield,item,transaction,quantity 
				FROM TransactionLine 
				WHERE itemsource='STOCK'
				) TL 
			INNER JOIN (
				SELECT id,custbody8,custbody67,trandate,TranID,trandisplayname,custbodybio_cam_lote,custbodybio_cam_fechacaducidad,custbody93,custbody94,custbody41 
				FROM Transaction 
				WHERE TO_NUMBER(custbody94)=$nroSemana
				) T ON (TL.transaction = T.id)
			INNER JOIN (
				SELECT id,itemid,description,consumptionunit,displayname 
				FROM item
				) I ON (I.id=TL.item)
			INNER JOIN (
				SELECT internalid,abbreviation 
				FROM unitsTypeUom
				) U ON (U.internalid=I.consumptionunit)
			INNER JOIN (
				SELECT id,name FROM CUSTOMLIST1025
				) CL ON (CL.id=T.custbody8)
			INNER JOIN (
				SELECT id,firstname,lastname 
				FROM Employee
				) E ON (E.id=T.custbody67)
			LEFT JOIN (
				SELECT X.transaction, Y.itemid, Y.displayname,X.quantity 
				FROM transactionLine X INNER JOIN item Y on (Y.id=X.item) 
				WHERE X.mainline='T'
				) TLX ON (TLX.transaction = T.id)
			ORDER BY T.TranID ASC, I.itemid ASC, TL.id ASC;";
		$rs  = $this->_db->get_Connection()->Execute($sql);
		$contador = $rs->RecordCount();
		if (intval($contador) > 0) {
			while (!$rs->EOF) {
				$datos[] = [
					"idtransaccion"	 	 => $rs->fields[0],
					"NroOpe"	 		 => $rs->fields[1],
					"nroOrdenTrabajo"  	 => $rs->fields[2],
					"codigoEnsamblaje"	 => trim($rs->fields[3]),
					"productoEnsamblaje" => trim(utf8_encode($rs->fields[4])),
					"cantidadEnsamblaje" => $rs->fields[5],
					"FCreacionEnsamblaje"=> date('d/m/Y',strtotime($rs->fields[6])),
					"LoteEnsamblaje"	 => trim(utf8_encode($rs->fields[7])),
					"FexExpEnsamblaje"	 => date('d/m/Y',strtotime($rs->fields[8])),
					"semProdEnsamblaje"	 => trim(utf8_encode($rs->fields[9])),
					"nroSemProdEnsamblaje"=> trim($rs->fields[10]),
					"LineaEnsamblaje"	 => trim(utf8_encode($rs->fields[11])),
					"tipoOTEnsamblaje"	 => trim(utf8_encode($rs->fields[12])),
					"EmiNomApeEnsamblaje"=> trim(utf8_encode($rs->fields[13])),
					"codProd"	 		 => trim($rs->fields[14]),
					"descProducto"	 	 => trim(utf8_encode($rs->fields[15])),
					"rendimiento"	 	 => $rs->fields[16],
					"CantProd"	 		 => $rs->fields[17],
					"unidad" 			 => $rs->fields[18]
				];
				$rs->MoveNext();
			}
		}
		return $datos;
	}
	
}
