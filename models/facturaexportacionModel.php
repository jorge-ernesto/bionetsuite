<?php

class facturaexportacionModel extends Model
{

	public function __construct()
	{
		parent::__construct();
	}

	public function getCabeceraFacturaExportacion($id)
	{
		$sql = "SELECT 
			T.id as idTrasaccion,
			T.custbody_ns_document_type as tipoDocumento,
			OPT.name AS tipoOperacion,
			T.tranid as numFactura,
			TBA.addressee,
			TBA.addr1,
			TX.trandisplayname as creadodesde,
			T.exchangerate as tipoCambio,
			E.id as idCreador,
			E.firstname as nomCreador,
			E.lastname as apeCreador,
			T.custbody10 as vendedor,
			CU.altname as nomCliente,
			CU.custentity_bio_num_doc as docCliente,
			TRANS.name as Transportista,
			FP.name as formaPago,
			TO_CHAR(T.trandate,'DD/MM/YYYY') as fecEmision,
			TO_CHAR(T.duedate,'DD/MM/YYYY') as fecVencimiento,
			T.foreigntotal as importeTotal,
			T.custbody_ns_amount_words as importeTexto,
			T.custbody_ns_guia_relac as idGuiaRemision,
			(T.custbody_ns_gr_rel_serie || '-' || T.custbody_ns_gr_rel_num) as guiaRemision,
			T.memo as observacion,
			TRIM(T.custbody_ns_printed_xml_response) as xml
			from transaction T 
			LEFT JOIN customrecord_ns_pe_operation_type OPT ON trim(OPT.id)=trim(T.custbody_ns_pe_oper_type)
			INNER JOIN employee E ON trim(E.id)=trim(T.employee)
			INNER JOIN transactionBillingAddress TBA ON (T.billingaddress=TBA.nkey)
			INNER JOIN (select distinct transaction,createdfrom from transactionline) TLX ON (TLX.transaction=T.id)
		    INNER JOIN transaction TX ON (TLX.createdfrom=TX.id)
			INNER JOIN customer CU ON (CU.id=T.entity)
			INNER JOIN CUSTOMLIST1026 FP ON (FP.id=T.custbody12)
			LEFT JOIN CUSTOMRECORD1031 TRANS ON (TRANS.id=T.custbody26)
			WHERE T.id='$id';";
		$rs  = $this->_db->get_Connection()->Execute($sql);
		$contador = $rs->RecordCount();
		if (intval($contador) > 0) {
			while (!$rs->EOF) {
				$datos[] = [
					"idTrasaccion"	 => $rs->fields[0],
					"tipoDocumento"	 => $rs->fields[1],
					"tipoOperacion"  => utf8_encode($rs->fields[2]),
					"numFactura"	 => $rs->fields[3],
					"addressee"	 	 => utf8_encode($rs->fields[4]),
					"addr1"	 	     => utf8_encode($rs->fields[5]),
					"creadodesde"	 => utf8_encode($rs->fields[6]),
					"tipoCambio"	 => $rs->fields[7],
					"idCreador"	 	 => $rs->fields[8],
					"nomCreador"	 => utf8_encode($rs->fields[9]),
					"apeCreador"	 => utf8_encode($rs->fields[10]),
					"vendedor"	 	 => utf8_encode($rs->fields[11]),
					"nomCliente"	 => utf8_encode($rs->fields[12]),
					"docCliente"	 => utf8_encode($rs->fields[13]),
					"Transportista"	 => utf8_encode($rs->fields[14]),
					"formaPago"	 	 => utf8_encode($rs->fields[15]),
					"fecEmision"	 => $rs->fields[16],
					"fecVencimiento" => $rs->fields[17],
					"importeTotal"	 => $rs->fields[18],
					"importeTexto"	 => utf8_encode($rs->fields[19]),
					"idGuiaRemision" => $rs->fields[20],
					"guiaRemision"	 => $rs->fields[21],
					"observacion"	 => utf8_encode($rs->fields[22]),
					"xml"	 		 => $rs->fields[23],
				];
				$rs->MoveNext();
			}
		}
		return $datos;
	}
	
	//SUBSTR(INU.inventorynumber,1,INSTR(INU.inventorynumber, '#') -1)  as lote,
	
	//U.abbreviation as unidad,
	/*
	INNER JOIN (
			SELECT internalid,abbreviation 
			FROM unitsTypeUom
			) U on (U.internalid=I.saleunit)
	*/
	public function getDetalleFacturaExportacion($id)
	{
		$sql="select  
		TL.transaction as idtransaccion,
		TL.item as iditem,
		I.itemid as codigo,
		trim(I.description) as description,
		I.custitem_ns_pe_cod_unit_med as unidad,
		TL.custcol_bio_item_reg as reg,
		(IA.quantity * -1 ) as quantity,
		TRIM(INU.inventorynumber) as Lote,
		SUBSTR(INU.inventorynumber,INSTR(INU.inventorynumber, '#')+1, LENGTH(INU.inventorynumber) - INSTR(INU.inventorynumber, '#'))  as fechafabricacion,
		TO_CHAR(INU.expirationdate,'MM-YYYY') as fechacaducidad,
		(CASE WHEN PRI.unitprice IS NULL THEN ( (TL.creditforeignamount*-1) / IA.quantity ) ELSE PRI.unitprice END)  as precioUnitario, 
		(CASE WHEN PRI.unitprice IS NULL THEN  TL.creditforeignamount ELSE ((IA.quantity * -1 ) * PRI.unitprice ) END) as valorVenta,
		TRIM(PRI.pricelevel) as nivel
		FROM (
			SELECT id,Item,custcol_bio_item_reg,price,transaction,linesequencenumber,creditforeignamount 
			FROM TransactionLine 
			WHERE transaction='$id' and costestimatetype='AVGCOST' 
			) TL 
		INNER JOIN (
			SELECT id,saleunit,itemid,description,lastpurchaseprice,custitem_ns_pe_cod_unit_med
			FROM Item
			) I ON ( I.id = TL.Item )
		INNER JOIN (
			SELECT transaction,transactionline,inventorynumber,quantity 
			FROM inventoryAssignment
			) IA on (IA.transaction=TL.transaction and IA.transactionline=TL.id)
		INNER JOIN (
			SELECT id,expirationdate,inventorynumber 
			FROM inventoryNumber
			) INU ON (INU.id=IA.inventorynumber)
		LEFT JOIN (
			SELECT pricelevel,item,saleunit,unitprice 
			FROM pricing where currency='2'
			) PRI on (PRI.pricelevel=TL.price and PRI.item=TL.item and PRI.saleunit=I.saleunit)
		order by TL.linesequencenumber asc;";
		$rs  = $this->_db->get_Connection()->Execute($sql);
		$contador = $rs->RecordCount();
		if (intval($contador) > 0) {
			while (!$rs->EOF) {
				$datos[] = [
					"idtransaccion"		=> $rs->fields[0],
					"iditem"	 		=> $rs->fields[1],
					"codigo"	 		=> $rs->fields[2],
					"description"		=> utf8_encode($rs->fields[3]),
					"unidad"			=> $rs->fields[4],
					"reg"				=> $rs->fields[5],
					"quantity"	 		=> $rs->fields[6],
					"lote"				=> $rs->fields[7],
					"fechafabricacion"	=> $rs->fields[8],
					"fechacaducidad"	=> $rs->fields[9],
					"precioUnitario"	=> $rs->fields[10],
					"valorVenta"		=> $rs->fields[11],
					"nivel"				=> $rs->fields[12],
				];
				$rs->MoveNext();
			}
		}
		return $datos;
	}
}
