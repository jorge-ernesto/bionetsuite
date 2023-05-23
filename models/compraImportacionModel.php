<?php

class compraImportacionModel extends Model
{

	public function __construct()
	{
		parent::__construct();
	}
	
	///*(T.custbody_stc_amount_after_discount / TL.quantity) as preciounitario,*/
	public function getCabeceraPolizaImportacion($id)
	{
		$sql="select 
			T.id as idTransaccion, 
			TRIM(T.custbody59) as nropoliza, 
			TRIM(E.entityid) as proveedor, 
			TO_CHAR(T.trandate,'DD/MM/YYYY') as fecha, 
			TRIM(TD.custrecord_ns_prefix_tipo_documento) as tipodoc,
			T.custbody_ns_serie_cxp || '-' || T.custbody_ns_num_correlativo as numdoc,
			I.fullname as codigo,
			I.displayname as descripcion, 
			TL.quantity as cantidad,
			NTL.previousdoc as ordencompra,
			NTLX.nextdoc as recepcion,
			TX.exchangerate as tipcambiorecep
			from transaction T
			inner join transactionline TL on (T.id=TL.transaction)
			inner join item I on (I.id=TL.item)
			inner join entity E on (T.entity=E.id)
			inner join CUSTOMRECORD_NS_TIPO_DOCUMENTO TD on (TD.id=T.custbody_ns_document_type)
			inner join NextTransactionLink NTL on (NTL.nextdoc=T.id)
			inner join (select * from NextTransactionLink where linktype='ShipRcpt') NTLX on (NTLX.previousdoc=NTL.previousdoc)
			inner join transaction TX on (TX.id=NTLX.nextdoc)
			where T.id='$id';";
		$rs  = $this->_db->get_Connection()->Execute($sql);
		$contador = $rs->RecordCount();
		if (intval($contador) > 0) {
			while (!$rs->EOF) {
				$datos[] = [
					"idTransaccion"	=> $rs->fields[0],
					"nropoliza" 	=> utf8_encode($rs->fields[1]),
					"proveedor"	 	=> utf8_encode($rs->fields[2]),
					"fecha"	 		=> $rs->fields[3],
					"tipodoc"		=> $rs->fields[4],
					"numdoc"	 	=> $rs->fields[5],
					"codigo"	 	=> $rs->fields[6],
					"descripcion"	=> utf8_encode($rs->fields[7]),
					"cantidad"	 	=> $rs->fields[8],
					"ordencompra"	=> $rs->fields[9],
					"recepcion"		=> $rs->fields[10],
					"tipcambiorecep"=> $rs->fields[11],
				];
				$rs->MoveNext();
			}
		}
		return $datos;
	}
	
	public function getDetallePolizaImportacion($nropoliza)
	{
		$sql="SELECT 
			(CASE WHEN T.custbody129=1 THEN 'ORIGEN' ELSE 'GASTO' END) as TipoPoliza,
			T.tranid as nrodoc,
			TRIM(TD.custrecord_ns_prefix_tipo_documento) as tipodoc,
			TRIM(T.custbody59) as nropoliza,
			TO_CHAR(T.trandate,'DD/MM/YYYY') as fecha,
			TRIM(E.entityid) as proveedor, 
			(CASE WHEN T.custbody129=1 THEN TL.foreignamount ELSE TL.rate END) as montosoles,
			T.currency as moneda
			from transaction T
			inner join CUSTOMRECORD_NS_TIPO_DOCUMENTO TD on (TD.id=T.custbody_ns_document_type)
			inner join transactionline TL on (T.id=TL.transaction)
			inner join entity E on (T.entity=E.id)
			WHERE TRIM(T.custbody59)='$nropoliza' and TL.linesequencenumber=1
			ORDER BY T.custbody129 ASC;";
		$rs  = $this->_db->get_Connection()->Execute($sql);
		$contador = $rs->RecordCount();
		if (intval($contador) > 0) {
			while (!$rs->EOF) {
				$datos[] = [
					"TipoPoliza"=> $rs->fields[0],
					"nrodoc" 	=> $rs->fields[1],
					"tipodoc"	=> $rs->fields[2],
					"nropoliza"	=> utf8_encode($rs->fields[3]),
					"fecha"		=> $rs->fields[4],
					"proveedor"	=> utf8_encode($rs->fields[5]),
					"montosoles"=> $rs->fields[6],
					"moneda"	=> $rs->fields[7],
				];
				$rs->MoveNext();
			}
		}
		return $datos;
	}
	
}
