<?php

class ejecucionpedidoModel extends Model
{

	public function __construct()
	{
		parent::__construct();
	}
	
	public function getCabeceraEjecucionPedido($id)
	{

		$sql="SELECT 
			T.id as idTrasaccion,
			OPT.name AS tipoOperacion,
			T.tranid as numGuia,
			UPPER(TRIM(T1.addr1)) as direccion,
			(UPPER(TRIM(T1.pr)) || '  ' || UPPER(TRIM(T1.de))) as depa_prov,
			UPPER(TRIM(T1.di)) as distrito,
			T.custbody_ns_total_paq as caja,
			T.custbody_ns_peso_total as peso,
			TX.trandisplayname as creadodesde,
			T.custbody10 as vendedor,
			(CASE WHEN SUBSTR(T.tranid,4,4)='T002' THEN TRIM(T.custbody117) ELSE TRIM(CU.altname) END) as nomCliente,
			(CASE WHEN SUBSTR(T.tranid,4,4)='T002' THEN TRIM(T.custbody118) ELSE CU.custentity_bio_num_doc END) as docCliente,
            T.trandate as fecEmision,
			T.custbody_ns_fech_traslado as fecTraslado,
			TXX.tranid as factura,
			T.custbody_ns_driv_licence as licenConductor,
			T.custbody_ns_driv_docnumber as dniConductor,
			(T.custbody_ns_pe_car_brand || '/' || T.custbody_ns_pe_car_plate) as placa,
			FPE.name as formPagoxEnvio,
			T.custbody_ns_drivers_lastname as apeTransportista,
			TRANS.name as razSocTransportista,
			UPPER(TRANS.custrecord180) as direcTransportista,
			TRANS.custrecord179 as rucTransportista,
			UPPER(T.custbody_ns_pe_dic_origen) as puntoPartida,
			UPPER(TBA.addr1 || ' ' || TBA.addr3 || ' ' || TBA.city || ' ' || TBA.state ) as puntoLLegada,
			TRIM(T.memo) as Nota,
			TRIM(T.custbody_ns_printed_xml_response) as xml,
			UPPER(TRIM(T1.pr)) as depa,
			UPPER(TRIM(T1.de)) as prov
			FROM 
		             (
			     SELECT id,tranid,trandate,custbody_ns_pe_oper_type,employee,billingaddress,entity,custbody17,custbody26,custbody_ns_record_ref,custbody122,custbody119,custbody120,custbody121,custbody_ns_total_paq,custbody_ns_peso_total,custbody10,custbody117,custbody118,custbody_ns_fech_traslado,custbody_ns_driv_licence,custbody_ns_driv_docnumber,custbody_ns_pe_car_brand,custbody_ns_pe_car_plate,custbody_ns_drivers_lastname,custbody_ns_pe_dic_origen,memo,custbody_ns_printed_xml_response, shippingaddress
			     FROM transaction WHERE id='$id'
		             ) T 
			LEFT JOIN (SELECT id,name FROM customrecord_ns_pe_operation_type) OPT ON trim(OPT.id)=trim(T.custbody_ns_pe_oper_type)
			LEFT JOIN (SELECT id FROM employee) E ON trim(E.id)=trim(T.employee)
			LEFT JOIN (SELECT nkey,addr1,addr3,city,state FROM transactionBillingAddress) TBA ON (T.billingaddress=TBA.nkey)
			LEFT JOIN (select distinct transaction,createdfrom from transactionline) TLX ON (TLX.transaction=T.id)
			LEFT JOIN (SELECT id,trandisplayname,custbody27,custbody60 FROM transaction) TX ON (TLX.createdfrom=TX.id)
			LEFT JOIN (SELECT id,altname,custentity_bio_num_doc,defaultbillingaddress FROM customer) CU ON (CU.id=T.entity)
			LEFT JOIN (SELECT id,name FROM CUSTOMLIST1029) FPE ON (FPE.id=T.custbody17)
			LEFT JOIN (SELECT id,name,custrecord180,custrecord179 FROM CUSTOMRECORD1031) TRANS ON (TRANS.id=T.custbody26)
			LEFT JOIN (SELECT nkey,addrtext FROM customerAddressbookEntityAddress) CABEA ON (CABEA.nkey=CU.defaultbillingaddress)
			LEFT JOIN (SELECT id,tranid FROM transaction) TXX ON (TXX.id=T.custbody_ns_record_ref)
			LEFT JOIN 
			     (
			     SELECT CAEA.nkey,CAEA.addr1,DEP.name as de,PRO.name as pr,DIS.name as di
			     FROM customerAddressbookEntityAddress CAEA
			     INNER JOIN CUSTOMLIST1018 DEP ON (CAEA.custrecord176=DEP.id)
			     INNER JOIN CUSTOMRECORD1019 PRO ON (CAEA.custrecord177=PRO.id)
			     INNER JOIN CUSTOMRECORD1020 DIS ON (CAEA.custrecord178=DIS.id and DIS.custrecord175=PRO.id)
			     ) T1 ON (T1.nkey=T.shippingaddress)";
		$rs  = $this->_db->get_Connection()->Execute($sql);
		$contador = $rs->RecordCount();
		if (intval($contador) > 0) {
			while (!$rs->EOF) {
				$datos[] = [
					"idTrasaccion"		=> $rs->fields[0],
					"tipoOperacion" 	=> utf8_encode($rs->fields[1]),
					"numGuia"	 		=> $rs->fields[2],
					"direccion"	 		=> utf8_encode($rs->fields[3]),
					"depa_prov"			=> utf8_encode($rs->fields[4]),
					"distrito"	 		=> utf8_encode($rs->fields[5]),
					"caja"	 	 		=> $rs->fields[6],
					"peso"	 			=> $rs->fields[7],
					"creadodesde"	 	=> utf8_encode($rs->fields[8]),
					"vendedor"	 		=> utf8_encode($rs->fields[9]),
					"nomCliente"	 	=> utf8_encode($rs->fields[10]),
					"docCliente"	 	=> $rs->fields[11],
					"fecEmision"	 	=> date('d/m/Y',strtotime($rs->fields[12])),
					"fecTraslado"	 	=> date('d/m/Y',strtotime($rs->fields[13])),
					"factura"			=> $rs->fields[14],
					"licenConductor"	=> $rs->fields[15],
					"dniConductor"	 	=> $rs->fields[16],
					"placa"	 			=> $rs->fields[17],
					"formPagoxEnvio"	=> utf8_encode($rs->fields[18]),
					"apeTransportista"	=> utf8_encode($rs->fields[19]),
					"razSocTransportista"=> utf8_encode($rs->fields[20]),
					"direcTransportista"=> utf8_encode($rs->fields[21]),
					"rucTransportista"	=> $rs->fields[22],
					"puntoPartida"	 	=> utf8_encode($rs->fields[23]),
					"puntoLLegada"	 	=> utf8_encode($rs->fields[24]),
					"Nota"	 			=> utf8_encode($rs->fields[25]),
					"xml"	 		 	=> $rs->fields[26],
					"depa"	 		 	=> $rs->fields[27],
					"prov"	 		 	=> $rs->fields[28],
				];
				$rs->MoveNext();
			}
		}
		return $datos;
	}
	
	public function getDetalleEjecucionPedido($id)
	{
		$sql="select  
		TL.transaction as idtransaccion,
		TL.item as iditem,
		I.itemid,
		trim(I.description) as description,
		TRIM(INU.inventorynumber) as Lote,
		TO_CHAR(INU.expirationdate,'MM-YYYY') as fechacaducidad,
		I.custitem_ns_pe_cod_unit_med as unidad,
		(IA.quantity * -1 ) as quantity
		from TransactionLine TL 
		INNER JOIN Item I ON ( I.ID = TL.Item )
		INNER JOIN unitsTypeUom U on (U.internalid=I.saleunit)
		INNER JOIN inventoryAssignment IA on (IA.transaction=TL.transaction and IA.transactionline=TL.id)
		INNER JOIN inventoryNumber INU ON (INU.id=IA.inventorynumber)
		where  TL.transaction='$id' and TL.custcol_ns_pe_um  is not null
		order by TL.linesequencenumber asc;";
		$rs  = $this->_db->get_Connection()->Execute($sql);
		$contador = $rs->RecordCount();
		if (intval($contador) > 0) {
			while (!$rs->EOF) {
				$datos[] = [
					"idtransaccion"		=> $rs->fields[0],
					"iditem"	 		=> $rs->fields[1],
					"itemid"	 		=> $rs->fields[2],
					"description"		=> utf8_encode($rs->fields[3]),
					"lote"				=> $rs->fields[4],
					"fechacaducidad"	=> $rs->fields[5],
					"unidad"	 		=> $rs->fields[6],
					"quantity"			=> $rs->fields[7],
				];
				$rs->MoveNext();
			}
		}
		return $datos;
	}
	
	public function getDetalleEjecucionPedido1($id)
	{
		$sql="select  
		TL.transaction as idtransaccion,
		TL.item as iditem,
		I.itemid,
		trim(I.description) as description,
		TRIM(INU.inventorynumber) as Lote,
		TO_CHAR(INU.expirationdate,'DD/MM/YYYY') as fechacaducidad,
		I.custitem_ns_pe_cod_unit_med as unidad,
		(IA.quantity * -1 ) as quantity
		from TransactionLine TL 
		INNER JOIN Item I ON ( I.ID = TL.Item )
		INNER JOIN unitsTypeUom U on (U.internalid=I.saleunit)
		INNER JOIN inventoryAssignment IA on (IA.transaction=TL.transaction and IA.transactionline=TL.id)
		INNER JOIN inventoryNumber INU ON (INU.id=IA.inventorynumber)
		where  TL.transaction='$id' and TL.custcol_ns_pe_um  is not null
		order by TL.linesequencenumber asc;";
		$rs  = $this->_db->get_Connection()->Execute($sql);
		$contador = $rs->RecordCount();
		if (intval($contador) > 0) {
			while (!$rs->EOF) {
				$datos[] = [
					"idtransaccion"		=> $rs->fields[0],
					"iditem"	 		=> $rs->fields[1],
					"itemid"	 		=> $rs->fields[2],
					"description"		=> utf8_encode($rs->fields[3]),
					"lote"				=> $rs->fields[4],
					"fechacaducidad"	=> $rs->fields[5],
					"unidad"	 		=> $rs->fields[6],
					"quantity"			=> $rs->fields[7],
				];
				$rs->MoveNext();
			}
		}
		return $datos;
	}
	
}