<?php

class notaingresoModel extends Model
{

	public function __construct()
	{
		parent::__construct();
	}
	
	public function getNotaIngresoCabecera($id)
	{
		$sql = "SELECT
				T.ID AS IdRecepcion,
				T.TranID AS NroDocumento, 
				T.trandate AS Fecha, 
				T.memo AS Nota,
				T.custbody39 AS dua,
				TOC.trandisplayname AS NroOrdenCompra,
				E.altname AS Proveedor,
				EP.firstname AS nomusu,
				EP.lastname AS apeusu
				FROM (
					SELECT id,TranID,trandate,memo,custbody39,createdby,entity 
					FROM TRANSACTION 
					WHERE id IN ('$id')
					) T
				INNER JOIN (
					SELECT id, transaction,createdfrom 
					FROM TRANSACTIONLINE
					) TL ON (TL.Transaction = T.id)
				INNER JOIN (
					SELECT transaction, transactionline 
					FROM INVENTORYASSIGNMENT
					) IA ON (IA.transaction=TL.Transaction and IA.transactionline=TL.id)
				INNER JOIN (
					SELECT id, altname 
					FROM ENTITY
					) E ON (E.id=T.entity)
				LEFT JOIN (
					SELECT id,firstname,lastname 
					FROM EMPLOYEE
					) EP ON (EP.id=T.createdby)
				INNER JOIN (
					SELECT id, trandisplayname 
					FROM TRANSACTION
					) TOC ON (TOC.id=TL.createdfrom);";
		$rs  = $this->_db->get_Connection()->Execute($sql);
		$contador = $rs->RecordCount();
		if (intval($contador) > 0) {
			while (!$rs->EOF) {
				$datos[] = [
					"IdRecepcion"	=> $rs->fields[0],
					"NroDocumento"	=> $rs->fields[1],
					"Fecha"	 		=> date("d/m/Y",strtotime($rs->fields[2])),
					"Nota"	 		=> utf8_decode($rs->fields[3]),
					"dua"	 		=> $rs->fields[4],
					"NroOrdenCompra"=> $rs->fields[5],
					"Proveedor"	 	=> utf8_decode($rs->fields[6]),
					"usuario"	 	=> utf8_decode($rs->fields[7])." ".utf8_decode($rs->fields[8])
				];
				$rs->MoveNext();
			}
		}
		return $datos;
	}
	
	public function getNotaIngresoDetalle($id)
	{
		$sql = "SELECT 
			TL.id as idTransaccion,
			I.itemid as codigo,
			I.description as descripcion1,
			I.displayname as descripcion2,
			(
			CASE 
			WHEN U.abbreviation = 'GLL' THEN (IA.quantity/3.8) 
			WHEN U.abbreviation = 'GR' THEN (IA.quantity*1000) 
			WHEN U.abbreviation like 'M_' THEN (IA.quantity/1000) 
			ELSE IA.quantity 
			END
			) as cantidadDetalle,
			U.abbreviation as unidad,
			INU.inventorynumber AS SerieLote,
			INU.expirationdate AS FechaVencimiento,
			L.fullname AS AlmacenDestino,
			TL.custcol8 as observacion
			FROM (
				SELECT id,item,Transaction,custcol8,quantity,inventoryreportinglocation
				FROM TransactionLine 
				WHERE transaction IN ('$id')
				) TL
			INNER JOIN (
				SELECT id,itemid,consumptionunit,description,displayname 
				FROM Item
				) I ON ( I.id = TL.item )
			INNER JOIN (
				SELECT internalid,abbreviation 
				FROM unitsTypeUom
				) U ON ( U.internalid=I.consumptionunit )
			INNER JOIN (
				SELECT transaction,transactionline,inventorynumber,quantity 
				FROM inventoryAssignment
				) IA ON (IA.transaction=TL.Transaction and IA.transactionline=TL.id)
			INNER JOIN (
				SELECT id,inventorynumber,expirationdate 
				FROM inventoryNumber
				) INU ON (INU.id=IA.inventorynumber)
			LEFT JOIN (
				SELECT id,fullname 
				FROM LOCATION
				) L ON (L.id=TL.inventoryreportinglocation)";
		$rs  = $this->_db->get_Connection()->Execute($sql);
		$contador = $rs->RecordCount();
		if (intval($contador) > 0) {
			while (!$rs->EOF) {
				$datos[] = [
					"idTransaccion"		=> $rs->fields[0],
					"codigo"			=> $rs->fields[1],
					"descripcion1"	 	=> utf8_decode($rs->fields[2]),
					"descripcion2"	 	=> utf8_decode($rs->fields[3]),
					"cantidadDetalle"	=> $rs->fields[4],
					"unidad"	 		=> utf8_decode(utf8_encode($rs->fields[5])),
					"SerieLote"	 		=> $rs->fields[6],
					"FechaVencimiento"	=> date("d/m/Y",strtotime($rs->fields[7])),
					"almacenDestino"	=> utf8_decode($rs->fields[8]),
					"observacion"	 	=> utf8_decode($rs->fields[9]),
				];
				$rs->MoveNext();
			}
		}
		return $datos;
	}
	
}