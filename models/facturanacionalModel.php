<?php

class facturanacionalModel extends Model
{

	public function __construct()
	{
		parent::__construct();
	}

	public function getCabeceraFacturaNacional($id)
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
			T.createddate as fecEmision,
			T.duedate as fecVencimiento,
			T.foreigntotal as importeTotal,
			T.custbody_ns_amount_words as importeTexto,
			T.custbody_ns_guia_relac as idGuiaRemision,
			(T.custbody_ns_gr_rel_serie || '-' || T.custbody_ns_gr_rel_num) as guiaRemision,
			T.memo as observacion,
			T.custbody_stc_amount_after_discount as opGrabada,
			T.custbody_stc_amount_after_discount * 0.18 as igv,
			(SELECT SUM(debitforeignamount) from transactionline where transaction='$id' and accountinglinetype='DISCOUNT' and custcol_ns_pe_bonus_item='T') as opGratuita,
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
					"opGrabada"	 	 => $rs->fields[23],
					"igv"	 		 => $rs->fields[24],
					"opGratuita"	 => $rs->fields[25],
					"xml"	 		 => $rs->fields[26],
				];
				$rs->MoveNext();
			}
		}
		return $datos;
	}
	
	//SUBSTR(INU.inventorynumber,1,INSTR(INU.inventorynumber, '#') -1)  as lote,
	public function getDetalleFacturaNacional($id)
	{
		/*$sql="select  
		TL.transaction as idtransaccion,
		TL.item as iditem,
		I.itemid as codigo,
		trim(I.description) as description,
		U.abbreviation as unidad,
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
			SELECT id,saleunit,itemid,description,lastpurchaseprice 
			FROM Item
			) I ON ( I.id = TL.Item )
		INNER JOIN (
			SELECT internalid,abbreviation 
			FROM unitsTypeUom
			) U on (U.internalid=I.saleunit)
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
			FROM pricing where currency='1'
			) PRI on (PRI.pricelevel=TL.price and PRI.item=TL.item and PRI.saleunit=I.saleunit)
		order by TL.linesequencenumber asc;";*/
		
		//U.abbreviation as unidad,
		
		/*
		LEFT JOIN (
				SELECT *
				FROM unitsTypeUom
				) U on (U.internalid=I.saleunit)
		*/
		$sql = "select  
			TL.transaction as idtransaccion,
			TL.item as iditem,
			I.itemid as codigo,
			trim(I.description) as description,
			I.custitem_ns_pe_cod_unit_med as unidad,
			(TL.quantity * -1 ) as quantity,
			(CASE WHEN TL.custcol_bio_desc_1=0 THEN 0.00 ELSE TL.rate END) as valorUnitario, 
			TL.creditforeignamount as importe,
			TL.custcol_bio_desc_1 as desc1,
			TL.custcol_bio_desc_2 as desc2,
			(CASE WHEN TL.custcol_bio_desc_1=0 THEN (TL.quantity * -1 ) * TL.rate ELSE (TL.netamount * -1) END) as valorVenta
			FROM (
				SELECT * 
				FROM TransactionLine 
				WHERE transaction='$id' and accountinglinetype='INCOME'
				) TL 
			LEFT JOIN (
				SELECT * 
				FROM Item
				) I ON ( I.id = TL.Item )
			INNER JOIN (
				SELECT * 
				FROM pricing where currency='1'
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
					"unidad"			=> utf8_encode($rs->fields[4]),
					"quantity"	 		=> $rs->fields[5],
					"valorUnitario"		=> $rs->fields[6],
					"importe"			=> $rs->fields[7],
					"desc1"				=> $rs->fields[8],
					"desc2"				=> $rs->fields[9],
					"valorVenta"		=> $rs->fields[10],
				];
				$rs->MoveNext();
			}
		}
		return $datos;
	}
}
