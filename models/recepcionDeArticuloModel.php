<?php

class recepcionDeArticuloModel extends Model
{

	public function __construct()
	{
		parent::__construct();
	}
	
	public function getUltimosCorrelativosNumeroAnalisis()
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
	
	public function getCorrelativo($codevaluar)
	{
		$sql = "SELECT L.id, L.correlativo
			FROM tb_condicion C
			INNER JOIN tb_linea L on L.id=C.id_linea 
			WHERE C.inicial='$codevaluar' and L.anio=2024;";
		$rs  = $this->_db->get_Connection2()->Execute($sql);
		
		switch(intval($rs->fields[0])){
			case 1:
			case 4:
				$linea = 'MP';
				break;
			case 2:
			case 5:
				$linea = 'ME';
				break;
			case 3:
			case 6:
				$linea = 'MCI';
				break;
		}
		return $linea."-".$rs->fields[1];
	}
	
	public function updateCorrelativoNumeroAnalisis($linea,$correlativo)
	{
		if($this->_db->get_Connection2()){
			$sql = "SELECT id_linea FROM tb_condicion WHERE inicial='".$linea."' and id_linea > 3;";
			$rs = $this->_db->get_Connection2()->Execute($sql);
			
			$sql1 = "UPDATE tb_linea SET correlativo=".intval($correlativo)." WHERE id=".intval($rs->fields[0])." and anio=2024;";
			$rs1 = $this->_db->get_Connection2()->Execute($sql1);
			
			return $rs1;
		}
		return false;
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
				SELECT id,item,Transaction,custcol8,quantity,inventoryreportinglocation,units
				FROM TransactionLine 
				WHERE transaction IN ('$id')
				) TL
			INNER JOIN (
				SELECT id,itemid,consumptionunit,description,displayname , saleunit
				FROM Item
				) I ON ( I.id = TL.item )
			INNER JOIN (
				SELECT internalid,abbreviation 
				FROM unitsTypeUom
				) U ON ( U.internalid=I.saleunit )
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
					"descripcion1"	 	=> $rs->fields[2],
					"descripcion2"	 	=> $rs->fields[3],
					"cantidadDetalle"	=> $rs->fields[4],
					"unidad"	 		=> utf8_decode(utf8_encode($rs->fields[5])),
					"SerieLote"	 		=> $rs->fields[6],
					"FechaVencimiento"	=> date("d/m/Y",strtotime($rs->fields[7])),
					"almacenDestino"	=> $rs->fields[8],
					"observacion"	 	=> $rs->fields[9],
				];
				$rs->MoveNext();
			}
		}
		return $datos;
	}
	
	public function TransactionxCODPROD_ET_INGRESO($id,$codprod)
	{
		$sql = "SELECT 
			T.TranID, 
			T.createddate, 
			T.trandate, 
			T.trandisplayname,
			T.memo || ' - ' || TL.custcol8 as memo,
			E.entitytitle,
			E.entityid,
			TL.quantity,
			I.description,
			I.displayname,
			I.itemid,
			I.purchasedescription,
			U.abbreviation,
			T.custbody39,
			B.binnumber,
			INU.inventorynumber,
			IST.name,
			INU.expirationdate,
			(
				CASE 
				WHEN U.abbreviation = 'GLL' THEN (IA.quantity/3.8) 
				WHEN U.abbreviation = 'GR' THEN (IA.quantity*1000) 
				ELSE IA.quantity END
			) as quantity,
			L.fullname,
			EP.firstname,
			EP.lastname,
			TL.linesequencenumber
			FROM Transaction T
			INNER JOIN TransactionLine TL ON ( TL.Transaction = T.ID )
			INNER JOIN Item I ON ( I.ID = TL.Item )
			INNER JOIN unitsTypeUom U ON ( U.internalid=I.saleunit )
			INNER JOIN inventoryAssignment IA ON (IA.transaction=TL.Transaction and IA.transactionline=TL.id)
			LEFT JOIN bin B ON (B.id=IA.bin)
			INNER JOIN inventoryNumber INU ON (INU.id=IA.inventorynumber)
			INNER JOIN inventoryStatus IST ON (IST.id=IA.inventorystatus)
			LEFT JOIN entity E ON (E.id=T.entity)
			LEFT JOIN Location L ON (L.id=TL.inventoryreportinglocation)
			LEFT JOIN employee EP ON (EP.id=T.employee)
			WHERE T.ID IN ('$id') and I.id='$codprod'
			order by TL.linesequencenumber;";
		error_log("SQL TransactionxCODPROD_ET_INGRESO");
		error_log($sql);
		$rs  = $this->_db->get_Connection()->Execute($sql);
		$contador = $rs->RecordCount();
		if (intval($contador) > 0) {
			while (!$rs->EOF) {
				$data[] = [
					"TranID"				=> $rs->fields[0],
					"createddate"			=> $rs->fields[1],
					"trandate"				=> date("d/m/Y",strtotime($rs->fields[2])),
					"trandisplayname"		=> utf8_encode($rs->fields[3]),
					"memo"					=> utf8_encode($rs->fields[4]),
					"entitytitle"			=> utf8_encode($rs->fields[5]),
					"entityid"				=> utf8_encode($rs->fields[6]),
					"total"					=> $rs->fields[7],
					"description"			=> utf8_encode($rs->fields[8]),
					"displayname"			=> utf8_encode($rs->fields[9]),
					"itemid"				=> $rs->fields[10],
					"purchasedescription"	=> utf8_encode($rs->fields[11]),
					"abbreviation"			=> $rs->fields[12],
					"dua"					=> $rs->fields[13],
					"binnumber"				=> $rs->fields[14],
					"inventorynumber"		=> $rs->fields[15],
					"name"					=> utf8_encode($rs->fields[16]),
					"expirationdate"		=> date("d/m/Y",strtotime($rs->fields[17])),
					"quantity"				=> $rs->fields[18],
					"fullname"				=> utf8_encode($rs->fields[19]),
					"firstname"				=> utf8_encode($rs->fields[20]),
					"lastname"				=> utf8_encode($rs->fields[21]),
					"linesequencenumber"	=> $rs->fields[22],
				];
				$rs->MoveNext();
			}
		}
		return $data;
	}
	
	public function TransactionxCODPROD_ET_MATPRIMA($id,$codprod)
	{	
		$sql = "SELECT 
		T.TranID, 
		T.createddate, 
		T.trandate, 
		T.trandisplayname,
		T.memo || ' - ' || TL.custcol8 as memo,
		E.entitytitle,
		E.entityid,
		TL.quantity,
		I.description,
		I.displayname,
		I.itemid,
		I.purchasedescription,
		U.abbreviation,
		T.custbody39,
		B.binnumber,
		INU.inventorynumber,
		IST.name,
		INU.expirationdate,
		(CASE INSTR(REPLACE(TL.custcol12,chr(10),'|'),'|')
		
		      WHEN 11 THEN 
		      
		           CASE INSTR(REPLACE(TL.custcol12,chr(10),'|'),'|',1,2)
		            
		                WHEN 22 THEN 
                                        
                                        ''
		                
		                ELSE 
                                         CASE  
                                                WHEN TRIM(SUBSTR(INU.inventorynumber,INSTR(INU.inventorynumber,'#',1,2)+1,10)) = TRIM(SUBSTR(REPLACE(REPLACE(TL.custcol12,chr(10),'|'),chr(13),''),INSTR(REPLACE(REPLACE(TL.custcol12,chr(10),'|'),chr(13),''),'|',1,2 )+1,10)) 
                                                        THEN TRIM(SUBSTR(REPLACE(REPLACE(TL.custcol12,chr(10),'|'),chr(13),''), INSTR(REPLACE(REPLACE(TL.custcol12,chr(10),'|'),chr(13),''),'|',1,2 )+1, 10)) || '|' || TRIM(SUBSTR(REPLACE(REPLACE(TL.custcol30,chr(10),'|'),chr(13),''),INSTR(REPLACE(REPLACE(TL.custcol30,chr(10),'|'),chr(13),''),'|',1,1 )+1,10)) || '|' || SUBSTR( REPLACE(REPLACE(TL.custcol13,chr(10),'|'),chr(13),''),  1, INSTR(REPLACE(REPLACE(TL.custcol13,chr(10),'|'),chr(13),''), '|', 1,1)-1) || '|' || SUBSTR( REPLACE(REPLACE(TL.custcol31,chr(10),'|'),chr(13),''),  1, INSTR(REPLACE(REPLACE(TL.custcol31,chr(10),'|'),chr(13),''), '|', 1,1)-1) || '|' || SUBSTR( REPLACE(REPLACE(TL.custcol18,chr(10),'|'),chr(13),''),  1, INSTR(REPLACE(REPLACE(TL.custcol18,chr(10),'|'),chr(13),''), '|', 1,1)-1)
                                                WHEN TRIM(SUBSTR(INU.inventorynumber,INSTR(INU.inventorynumber,'#',1,2)+1,10)) = TRIM(SUBSTR(REPLACE(REPLACE(TL.custcol12,chr(10),'|'),chr(13),''),INSTR(REPLACE(REPLACE(TL.custcol12,chr(10),'|'),chr(13),''),'|',1,1 )+1,10)) 
                                                        THEN TRIM(SUBSTR(REPLACE(REPLACE(TL.custcol12,chr(10),'|'),chr(13),''), INSTR(REPLACE(REPLACE(TL.custcol12,chr(10),'|'),chr(13),''),'|',1,1)+1, 10))  || '|' || TRIM(SUBSTR(REPLACE(REPLACE(TL.custcol30,chr(10),'|'),chr(13),''), INSTR(REPLACE(REPLACE(TL.custcol30,chr(10),'|'),chr(13),''),'|',1,1 )+1 ,10 )) || '|' || SUBSTR( REPLACE(REPLACE(TL.custcol13,chr(10),'|'),chr(13),''),  INSTR(REPLACE(REPLACE(TL.custcol13,chr(10),'|'),chr(13),''),'|',1,1 )+1, INSTR(REPLACE(REPLACE(TL.custcol13,chr(10),'|'),chr(13),''),'|' ) ) || '|' || SUBSTR( REPLACE(REPLACE(TL.custcol31,chr(10),'|'),chr(13),''),  INSTR(REPLACE(REPLACE(TL.custcol31,chr(10),'|'),chr(13),''),'|',1,1 )+1, INSTR(REPLACE(REPLACE(TL.custcol31,chr(10),'|'),chr(13),''),'|' ) ) || '|' || SUBSTR( REPLACE(REPLACE(TL.custcol18,chr(10),'|'),chr(13),''),  INSTR(REPLACE(REPLACE(TL.custcol31,chr(10),'|'),chr(13),''),'|',1,1 )+1, INSTR(REPLACE(REPLACE(TL.custcol18,chr(10),'|'),chr(13),''),'|' ) )
                                         END
		                END 
		              
		      ELSE 
		              CASE  
                                   WHEN TRIM(SUBSTR(INU.inventorynumber,INSTR(INU.inventorynumber,'#',1,2)+1,10)) = TRIM(SUBSTR(REPLACE(REPLACE(TL.custcol12,chr(10),'|'),chr(13),''),INSTR(REPLACE(REPLACE(TL.custcol12,chr(10),'|'),chr(13),''),'|',1,1 )+1,10)) 
                                        THEN TRIM(TL.custcol12) || '|' || TRIM(TL.custcol30) || '|' || TRIM(TL.custcol13) || '|' || TRIM(TL.custcol31) || '|' || TRIM(TL.custcol18)
                              END
		      END
		) as arreglo, 
		IA.quantity,
		L.fullname,
		EP.firstname,
		EP.lastname,
		TL.linesequencenumber,
		TRIM(I.custitem_ns_pe_cod_unit_med) as undPrincipal,
		TRIM(TL.custcol18) as memoControl,
		TL.custcol30 as fec_analisis
		FROM Transaction T
		INNER JOIN TransactionLine TL ON ( TL.Transaction = T.ID )
		INNER JOIN Item I ON ( I.ID = TL.Item )
		INNER JOIN unitsTypeUom U ON ( U.internalid=I.consumptionunit )
		INNER JOIN inventoryAssignment IA ON (IA.transaction=TL.Transaction and IA.transactionline=TL.id)
		LEFT JOIN bin B ON (B.id=IA.bin)
		INNER JOIN inventoryNumber INU ON (INU.id=IA.inventorynumber)
		INNER JOIN inventoryStatus IST ON (IST.id=IA.inventorystatus)
		LEFT JOIN entity E ON (E.id=T.entity)
		LEFT JOIN Location L ON (L.id=TL.inventoryreportinglocation)
		LEFT JOIN employee EP ON (EP.id=T.employee)
		WHERE T.ID IN ('$id') and I.id='$codprod'
		order by TL.linesequencenumber;";
		//I.consumptionunit  TL.units linea 323
		$rs  = $this->_db->get_Connection()->Execute($sql);
		$contador = $rs->RecordCount();
		if (intval($contador) > 0) {
			while (!$rs->EOF) {
				$data[] = [
					"TranID"				=> $rs->fields[0],
					"createddate"			=> $rs->fields[1],
					"trandate"				=> date("d/m/Y",strtotime($rs->fields[2])),
					"trandisplayname"		=> utf8_encode($rs->fields[3]),
					"memo"					=> utf8_encode($rs->fields[4]),
					"entitytitle"			=> $rs->fields[5],
					"entityid"				=> $rs->fields[6],
					"total"					=> $rs->fields[7],
					"description"			=> utf8_encode($rs->fields[8]),
					"displayname"			=> utf8_encode($rs->fields[9]),
					"itemid"				=> $rs->fields[10],
					"purchasedescription"	=> utf8_encode($rs->fields[11]),
					"abbreviation"			=> $rs->fields[12],
					"dua"					=> $rs->fields[13],
					"binnumber"				=> $rs->fields[14],
					"inventorynumber"		=> $rs->fields[15],
					"name"					=> utf8_encode($rs->fields[16]),
					"expirationdate"		=> date("d/m/Y",strtotime($rs->fields[17])),
					"arreglo"				=> $rs->fields[18],
					"quantity"				=> $rs->fields[19],
					"fullname"				=> utf8_encode($rs->fields[20]),
					"firstname"				=> utf8_encode($rs->fields[21]),
					"lastname"				=> utf8_encode($rs->fields[22]),
					"linesequencenumber"	=> $rs->fields[23],
					"undPrincipal"			=> $rs->fields[24],
					"memoControl"			=> utf8_encode($rs->fields[25]),
					"fec_analisis"			=> $rs->fields[26],
				];
				$rs->MoveNext();
			}
		}
		return $data;
	}
	
	public function TransactionxCODPROD_ET_EMPENVASE($id,$codprod)
	{
		$sql= "SELECT 
		T.TranID, 
		T.createddate, 
		T.trandate, 
		T.trandisplayname,
		T.memo || ' - ' || TL.custcol8 as memo,
		TL.custcol18 as memoControl,
		E.entitytitle,
		E.entityid,
		TL.quantity,
		I.description,
		I.displayname,
		I.itemid,
		I.purchasedescription,
		U.abbreviation,
		T.custbody39,
		B.binnumber,
		INU.inventorynumber,
		IST.name,
		INU.expirationdate,
		(CASE INSTR(REPLACE(TL.custcol12,chr(10),'|'),'|')
		
		      WHEN 11 THEN 
		      
		           CASE INSTR(REPLACE(TL.custcol12,chr(10),'|'),'|',1,2)
		            
		                WHEN 22 THEN 
                                        
                                        ''
		                
		                ELSE 
                                         CASE  
                                                WHEN TRIM(SUBSTR(INU.inventorynumber,INSTR(INU.inventorynumber,'#',1,2)+1,10)) = TRIM(SUBSTR(REPLACE(REPLACE(TL.custcol12,chr(10),'|'),chr(13),''),INSTR(REPLACE(REPLACE(TL.custcol12,chr(10),'|'),chr(13),''),'|',1,2 )+1,10)) 
                                                        THEN TRIM(SUBSTR(REPLACE(REPLACE(TL.custcol12,chr(10),'|'),chr(13),''), INSTR(REPLACE(REPLACE(TL.custcol12,chr(10),'|'),chr(13),''),'|',1,2 )+1, 10)) || '|' || TRIM(SUBSTR(REPLACE(REPLACE(TL.custcol30,chr(10),'|'),chr(13),''),INSTR(REPLACE(REPLACE(TL.custcol30,chr(10),'|'),chr(13),''),'|',1,1 )+1,10)) || '|' || SUBSTR( REPLACE(REPLACE(TL.custcol13,chr(10),'|'),chr(13),''),  1, INSTR(REPLACE(REPLACE(TL.custcol13,chr(10),'|'),chr(13),''), '|', 1,1)-1) || '|' || SUBSTR( REPLACE(REPLACE(TL.custcol31,chr(10),'|'),chr(13),''),  1, INSTR(REPLACE(REPLACE(TL.custcol31,chr(10),'|'),chr(13),''), '|', 1,1)-1) || '|' || SUBSTR( REPLACE(REPLACE(TL.custcol18,chr(10),'|'),chr(13),''),  1, INSTR(REPLACE(REPLACE(TL.custcol18,chr(10),'|'),chr(13),''), '|', 1,1)-1)
                                                WHEN TRIM(SUBSTR(INU.inventorynumber,INSTR(INU.inventorynumber,'#',1,2)+1,10)) = TRIM(SUBSTR(REPLACE(REPLACE(TL.custcol12,chr(10),'|'),chr(13),''),INSTR(REPLACE(REPLACE(TL.custcol12,chr(10),'|'),chr(13),''),'|',1,1 )+1,10)) 
                                                        THEN TRIM(SUBSTR(REPLACE(REPLACE(TL.custcol12,chr(10),'|'),chr(13),''), INSTR(REPLACE(REPLACE(TL.custcol12,chr(10),'|'),chr(13),''),'|',1,1)+1, 10))  || '|' || TRIM(SUBSTR(REPLACE(REPLACE(TL.custcol30,chr(10),'|'),chr(13),''), INSTR(REPLACE(REPLACE(TL.custcol30,chr(10),'|'),chr(13),''),'|',1,1 )+1 ,10 )) || '|' || SUBSTR( REPLACE(REPLACE(TL.custcol13,chr(10),'|'),chr(13),''),  INSTR(REPLACE(REPLACE(TL.custcol13,chr(10),'|'),chr(13),''),'|',1,1 )+1, INSTR(REPLACE(REPLACE(TL.custcol13,chr(10),'|'),chr(13),''),'|' ) ) || '|' || SUBSTR( REPLACE(REPLACE(TL.custcol31,chr(10),'|'),chr(13),''),  INSTR(REPLACE(REPLACE(TL.custcol31,chr(10),'|'),chr(13),''),'|',1,1 )+1, INSTR(REPLACE(REPLACE(TL.custcol31,chr(10),'|'),chr(13),''),'|' ) ) || '|' || SUBSTR( REPLACE(REPLACE(TL.custcol18,chr(10),'|'),chr(13),''),  INSTR(REPLACE(REPLACE(TL.custcol31,chr(10),'|'),chr(13),''),'|',1,1 )+1, INSTR(REPLACE(REPLACE(TL.custcol18,chr(10),'|'),chr(13),''),'|' ) )
                                         END
		                END 
		              
		      ELSE 
		              CASE  
                                   WHEN TRIM(SUBSTR(INU.inventorynumber,INSTR(INU.inventorynumber,'#',1,2)+1,10)) = TRIM(SUBSTR(REPLACE(REPLACE(TL.custcol12,chr(10),'|'),chr(13),''),INSTR(REPLACE(REPLACE(TL.custcol12,chr(10),'|'),chr(13),''),'|',1,1 )+1,10)) 
                                        THEN TRIM(TL.custcol12) || '|' || TRIM(TL.custcol30) || '|' || TRIM(TL.custcol13) || '|' || TRIM(TL.custcol31) || '|' || TRIM(TL.custcol18)
                              END
		      END
		) as arreglo, 
		IA.quantity,
		L.fullname,
		EP.firstname,
		EP.lastname,
		TL.linesequencenumber,
		TRIM(I.custitem_ns_pe_cod_unit_med) as undPrincipal,
		TL.custcol30 as fecha_analisis
		FROM Transaction T
		INNER JOIN TransactionLine TL ON ( TL.Transaction = T.ID )
		INNER JOIN Item I ON ( I.ID = TL.Item )
		INNER JOIN unitsTypeUom U ON ( U.internalid=I.consumptionunit )
		INNER JOIN inventoryAssignment IA ON (IA.transaction=TL.Transaction and IA.transactionline=TL.id)
		LEFT JOIN bin B ON (B.id=IA.bin)
		INNER JOIN inventoryNumber INU ON (INU.id=IA.inventorynumber)
		INNER JOIN inventoryStatus IST ON (IST.id=IA.inventorystatus)
		LEFT JOIN entity E ON (E.id=T.entity)
		LEFT JOIN Location L ON (L.id=TL.inventoryreportinglocation)
		LEFT JOIN employee EP ON (EP.id=T.employee)
		WHERE T.ID IN ('$id') and I.id='$codprod'
		order by TL.linesequencenumber;";
		$rs  = $this->_db->get_Connection()->Execute($sql);
		$contador = $rs->RecordCount();
		if (intval($contador) > 0) {
			while (!$rs->EOF) {
				$data[] = [
					"TranID"				=> $rs->fields[0],
					"createddate"			=> $rs->fields[1],
					"trandate"				=> date("d/m/Y",strtotime($rs->fields[2])),
					"trandisplayname"		=> utf8_encode($rs->fields[3]),
					"memo"					=> utf8_encode($rs->fields[4]),
					"memoControl"			=> utf8_encode($rs->fields[5]),
					"entitytitle"			=> $rs->fields[6],
					"entityid"				=> $rs->fields[7],
					"total"					=> $rs->fields[8],
					"description"			=> utf8_encode($rs->fields[9]),
					"displayname"			=> utf8_encode($rs->fields[10]),
					"itemid"				=> $rs->fields[11],
					"purchasedescription"	=> utf8_encode($rs->fields[12]),
					"abbreviation"			=> $rs->fields[13],
					"dua"					=> $rs->fields[14],
					"binnumber"				=> $rs->fields[15],
					"inventorynumber"		=> $rs->fields[16],
					"name"					=> utf8_encode($rs->fields[17]),
					"expirationdate"		=> date("d/m/Y",strtotime($rs->fields[18])),
					"arreglo"				=> $rs->fields[19],
					"quantity"				=> $rs->fields[20],
					"fullname"				=> utf8_encode($rs->fields[21]),
					"firstname"				=> utf8_encode($rs->fields[22]),
					"lastname"				=> utf8_encode($rs->fields[23]),
					"linesequencenumber"	=> $rs->fields[24],
					"undPrincipal"			=> $rs->fields[25],
					"fecha_analisis"		=> $rs->fields[26],
				];
				$rs->MoveNext();
			}
		}
		return $data;
	}
	
	public function TransactionxCODPROD_ET_RECHAZADO($id,$codprod)
	{
		
		$sql = "SELECT 
		T.TranID, 
		T.createddate, 
		T.trandate, 
		T.trandisplayname,
		T.memo || ' - ' || TL.custcol8 as memo,
		TL.custcol18 as memoControl,
		E.entitytitle,
		E.entityid,
		TL.quantity,
		I.description,
		I.displayname,
		I.itemid,
		I.purchasedescription,
		U.abbreviation,
		T.custbody39,
		B.binnumber,
		INU.inventorynumber,
		IST.name,
		INU.expirationdate,
		
		(CASE INSTR(REPLACE(TL.custcol12,chr(10),'|'),'|')
		
		      WHEN 11 THEN 
		      
		           CASE INSTR(REPLACE(TL.custcol12,chr(10),'|'),'|',1,2)
		            
		                WHEN 22 THEN 
                                        
                                        ''
		                
		                ELSE 
                                         CASE  
                                                WHEN TRIM(SUBSTR(INU.inventorynumber,INSTR(INU.inventorynumber,'#',1,2)+1,10)) = TRIM(SUBSTR(REPLACE(REPLACE(TL.custcol12,chr(10),'|'),chr(13),''),INSTR(REPLACE(REPLACE(TL.custcol12,chr(10),'|'),chr(13),''),'|',1,2 )+1,10)) 
                                                        THEN TRIM(SUBSTR(REPLACE(REPLACE(TL.custcol12,chr(10),'|'),chr(13),''), INSTR(REPLACE(REPLACE(TL.custcol12,chr(10),'|'),chr(13),''),'|',1,2 )+1, 10)) || '|' || TRIM(SUBSTR(REPLACE(REPLACE(TL.custcol30,chr(10),'|'),chr(13),''),INSTR(REPLACE(REPLACE(TL.custcol30,chr(10),'|'),chr(13),''),'|',1,1 )+1,10)) || '|' || SUBSTR( REPLACE(REPLACE(TL.custcol13,chr(10),'|'),chr(13),''),  1, INSTR(REPLACE(REPLACE(TL.custcol13,chr(10),'|'),chr(13),''), '|', 1,1)-1) || '|' || SUBSTR( REPLACE(REPLACE(TL.custcol31,chr(10),'|'),chr(13),''),  1, INSTR(REPLACE(REPLACE(TL.custcol31,chr(10),'|'),chr(13),''), '|', 1,1)-1) || '|' || SUBSTR( REPLACE(REPLACE(TL.custcol18,chr(10),'|'),chr(13),''),  1, INSTR(REPLACE(REPLACE(TL.custcol18,chr(10),'|'),chr(13),''), '|', 1,1)-1)
                                                WHEN TRIM(SUBSTR(INU.inventorynumber,INSTR(INU.inventorynumber,'#',1,2)+1,10)) = TRIM(SUBSTR(REPLACE(REPLACE(TL.custcol12,chr(10),'|'),chr(13),''),INSTR(REPLACE(REPLACE(TL.custcol12,chr(10),'|'),chr(13),''),'|',1,1 )+1,10)) 
                                                        THEN TRIM(SUBSTR(REPLACE(REPLACE(TL.custcol12,chr(10),'|'),chr(13),''), INSTR(REPLACE(REPLACE(TL.custcol12,chr(10),'|'),chr(13),''),'|',1,1)+1, 10))  || '|' || TRIM(SUBSTR(REPLACE(REPLACE(TL.custcol30,chr(10),'|'),chr(13),''), INSTR(REPLACE(REPLACE(TL.custcol30,chr(10),'|'),chr(13),''),'|',1,1 )+1 ,10 )) || '|' || SUBSTR( REPLACE(REPLACE(TL.custcol13,chr(10),'|'),chr(13),''),  INSTR(REPLACE(REPLACE(TL.custcol13,chr(10),'|'),chr(13),''),'|',1,1 )+1, INSTR(REPLACE(REPLACE(TL.custcol13,chr(10),'|'),chr(13),''),'|' ) ) || '|' || SUBSTR( REPLACE(REPLACE(TL.custcol31,chr(10),'|'),chr(13),''),  INSTR(REPLACE(REPLACE(TL.custcol31,chr(10),'|'),chr(13),''),'|',1,1 )+1, INSTR(REPLACE(REPLACE(TL.custcol31,chr(10),'|'),chr(13),''),'|' ) ) || '|' || SUBSTR( REPLACE(REPLACE(TL.custcol18,chr(10),'|'),chr(13),''),  INSTR(REPLACE(REPLACE(TL.custcol31,chr(10),'|'),chr(13),''),'|',1,1 )+1, INSTR(REPLACE(REPLACE(TL.custcol18,chr(10),'|'),chr(13),''),'|' ) )
                                         END
		                END 
		              
		      ELSE 
		              CASE  
                                   WHEN TRIM(SUBSTR(INU.inventorynumber,INSTR(INU.inventorynumber,'#',1,2)+1,10)) = TRIM(SUBSTR(REPLACE(REPLACE(TL.custcol12,chr(10),'|'),chr(13),''),INSTR(REPLACE(REPLACE(TL.custcol12,chr(10),'|'),chr(13),''),'|',1,1 )+1,10)) 
                                        THEN TRIM(TL.custcol12) || '|' || TRIM(TL.custcol30) || '|' || TRIM(TL.custcol13) || '|' || TRIM(TL.custcol31) || '|' || TRIM(TL.custcol18)
                              END
		      END
		) as arreglo, 
		IA.quantity,
		L.fullname,
		EP.firstname,
		EP.lastname,
		TL.linesequencenumber,
		TRIM(I.custitem_ns_pe_cod_unit_med) as undPrincipal
		FROM Transaction T
		INNER JOIN TransactionLine TL ON ( TL.Transaction = T.ID )
		INNER JOIN Item I ON ( I.ID = TL.Item )
		INNER JOIN unitsTypeUom U ON ( U.internalid=I.consumptionunit )
		INNER JOIN inventoryAssignment IA ON (IA.transaction=TL.Transaction and IA.transactionline=TL.id)
		LEFT JOIN bin B ON (B.id=IA.bin)
		INNER JOIN inventoryNumber INU ON (INU.id=IA.inventorynumber)
		INNER JOIN inventoryStatus IST ON (IST.id=IA.inventorystatus)
		LEFT JOIN entity E ON (E.id=T.entity)
		LEFT JOIN Location L ON (L.id=TL.inventoryreportinglocation)
		LEFT JOIN employee EP ON (EP.id=T.employee)
		WHERE T.ID IN ('$id') and I.id='$codprod'      
		order by TL.linesequencenumber;";
		$rs  = $this->_db->get_Connection()->Execute($sql);
		$contador = $rs->RecordCount();
		if (intval($contador) > 0) {
			while (!$rs->EOF) {
				$data[] = [
					"TranID"				=> $rs->fields[0],
					"createddate"			=> $rs->fields[1],
					"trandate"				=> date("d/m/Y",strtotime($rs->fields[2])),
					"trandisplayname"		=> utf8_encode($rs->fields[3]),
					"memo"					=> utf8_encode($rs->fields[4]),
					"memoControl"			=> utf8_encode($rs->fields[5]),
					"entitytitle"			=> $rs->fields[6],
					"entityid"				=> $rs->fields[7],
					"total"					=> $rs->fields[8],
					"description"			=> utf8_encode($rs->fields[9]),
					"displayname"			=> utf8_encode($rs->fields[10]),
					"itemid"				=> $rs->fields[11],
					"purchasedescription"	=> utf8_encode($rs->fields[12]),
					"abbreviation"			=> $rs->fields[13],
					"dua"					=> $rs->fields[14],
					"binnumber"				=> $rs->fields[15],
					"inventorynumber"		=> $rs->fields[16],
					"name"					=> utf8_encode($rs->fields[17]),
					"expirationdate"		=> date("d/m/Y",strtotime($rs->fields[18])),
					"arreglo"				=> $rs->fields[19],
					"quantity"				=> $rs->fields[20],
					"fullname"				=> utf8_encode($rs->fields[21]),
					"firstname"				=> utf8_encode($rs->fields[22]),
					"lastname"				=> utf8_encode($rs->fields[23]),
					"linesequencenumber"	=> $rs->fields[24],
					"undPrincipal"			=> $rs->fields[25],
				];
				$rs->MoveNext();
			}
		}
		return $data;
	}
	
	public function TransactionxCODPROD_ET_PRODUCTOIMPORTACION($id,$codprod)
	{
		
		$sql = "SELECT 
		T.TranID, 
		T.createddate, 
		T.trandate, 
		T.trandisplayname,
		T.memo || ' - ' || TL.custcol8 as memo,
		TL.custcol18 as memoControl,
		E.entitytitle,
		E.entityid,
		TL.quantity,
		I.description,
		I.displayname,
		I.itemid,
		I.purchasedescription,
		U.abbreviation,
		T.custbody39,
		B.binnumber,
		INU.inventorynumber,
		IST.name,
		INU.expirationdate,
		(CASE INSTR(REPLACE(TL.custcol12,chr(10),'|'),'|')
		
		      WHEN 11 THEN 
		      
		           CASE INSTR(REPLACE(TL.custcol12,chr(10),'|'),'|',1,2)
		            
		                WHEN 22 THEN 
                                        
                                        ''
		                
		                ELSE 
                                         CASE  
                                                WHEN TRIM(SUBSTR(INU.inventorynumber,INSTR(INU.inventorynumber,'#',1,2)+1,10)) = TRIM(SUBSTR(REPLACE(REPLACE(TL.custcol12,chr(10),'|'),chr(13),''),INSTR(REPLACE(REPLACE(TL.custcol12,chr(10),'|'),chr(13),''),'|',1,2 )+1,10)) 
                                                        THEN TRIM(SUBSTR(REPLACE(REPLACE(TL.custcol12,chr(10),'|'),chr(13),''), INSTR(REPLACE(REPLACE(TL.custcol12,chr(10),'|'),chr(13),''),'|',1,2 )+1, 10)) || '|' || TRIM(SUBSTR(REPLACE(REPLACE(TL.custcol30,chr(10),'|'),chr(13),''),INSTR(REPLACE(REPLACE(TL.custcol30,chr(10),'|'),chr(13),''),'|',1,1 )+1,10)) || '|' || SUBSTR( REPLACE(REPLACE(TL.custcol13,chr(10),'|'),chr(13),''),  1, INSTR(REPLACE(REPLACE(TL.custcol13,chr(10),'|'),chr(13),''), '|', 1,1)-1) || '|' || SUBSTR( REPLACE(REPLACE(TL.custcol31,chr(10),'|'),chr(13),''),  1, INSTR(REPLACE(REPLACE(TL.custcol31,chr(10),'|'),chr(13),''), '|', 1,1)-1) || '|' || SUBSTR( REPLACE(REPLACE(TL.custcol18,chr(10),'|'),chr(13),''),  1, INSTR(REPLACE(REPLACE(TL.custcol18,chr(10),'|'),chr(13),''), '|', 1,1)-1)
                                                WHEN TRIM(SUBSTR(INU.inventorynumber,INSTR(INU.inventorynumber,'#',1,2)+1,10)) = TRIM(SUBSTR(REPLACE(REPLACE(TL.custcol12,chr(10),'|'),chr(13),''),INSTR(REPLACE(REPLACE(TL.custcol12,chr(10),'|'),chr(13),''),'|',1,1 )+1,10)) 
                                                        THEN TRIM(SUBSTR(REPLACE(REPLACE(TL.custcol12,chr(10),'|'),chr(13),''), INSTR(REPLACE(REPLACE(TL.custcol12,chr(10),'|'),chr(13),''),'|',1,1)+1, 10))  || '|' || TRIM(SUBSTR(REPLACE(REPLACE(TL.custcol30,chr(10),'|'),chr(13),''), INSTR(REPLACE(REPLACE(TL.custcol30,chr(10),'|'),chr(13),''),'|',1,1 )+1 ,10 )) || '|' || SUBSTR( REPLACE(REPLACE(TL.custcol13,chr(10),'|'),chr(13),''),  INSTR(REPLACE(REPLACE(TL.custcol13,chr(10),'|'),chr(13),''),'|',1,1 )+1, INSTR(REPLACE(REPLACE(TL.custcol13,chr(10),'|'),chr(13),''),'|' ) ) || '|' || SUBSTR( REPLACE(REPLACE(TL.custcol31,chr(10),'|'),chr(13),''),  INSTR(REPLACE(REPLACE(TL.custcol31,chr(10),'|'),chr(13),''),'|',1,1 )+1, INSTR(REPLACE(REPLACE(TL.custcol31,chr(10),'|'),chr(13),''),'|' ) ) || '|' || SUBSTR( REPLACE(REPLACE(TL.custcol18,chr(10),'|'),chr(13),''),  INSTR(REPLACE(REPLACE(TL.custcol31,chr(10),'|'),chr(13),''),'|',1,1 )+1, INSTR(REPLACE(REPLACE(TL.custcol18,chr(10),'|'),chr(13),''),'|' ) )
                                         END
		                END 
		              
		      ELSE 
		              CASE  
                                   WHEN TRIM(SUBSTR(INU.inventorynumber,INSTR(INU.inventorynumber,'#',1,2)+1,10)) = TRIM(SUBSTR(REPLACE(REPLACE(TL.custcol12,chr(10),'|'),chr(13),''),INSTR(REPLACE(REPLACE(TL.custcol12,chr(10),'|'),chr(13),''),'|',1,1 )+1,10)) 
                                        THEN TRIM(TL.custcol12) || '|' || TRIM(TL.custcol30) || '|' || TRIM(TL.custcol13) || '|' || TRIM(TL.custcol31) || '|' || TRIM(TL.custcol18)
                              END
		      END
		) as arreglo, 
		IA.quantity,
		L.fullname,
		EP.firstname,
		EP.lastname,
		TL.linesequencenumber,
		TRIM(I.custitem_ns_pe_cod_unit_med) as undPrincipal
		FROM Transaction T
		INNER JOIN TransactionLine TL ON ( TL.Transaction = T.ID )
		INNER JOIN Item I ON ( I.ID = TL.Item )
		INNER JOIN unitsTypeUom U ON ( U.internalid=I.consumptionunit )
		INNER JOIN inventoryAssignment IA ON (IA.transaction=TL.Transaction and IA.transactionline=TL.id)
		LEFT JOIN bin B ON (B.id=IA.bin)
		INNER JOIN inventoryNumber INU ON (INU.id=IA.inventorynumber)
		INNER JOIN inventoryStatus IST ON (IST.id=IA.inventorystatus)
		LEFT JOIN entity E ON (E.id=T.entity)
		LEFT JOIN Location L ON (L.id=TL.inventoryreportinglocation)
		LEFT JOIN employee EP ON (EP.id=T.employee)
		WHERE T.ID IN ('$id') and I.id='$codprod'
		order by TL.linesequencenumber;";
		$rs  = $this->_db->get_Connection()->Execute($sql);
		$contador = $rs->RecordCount();
		if (intval($contador) > 0) {
			while (!$rs->EOF) {
				$data[] = [
					"TranID"				=> $rs->fields[0],
					"createddate"			=> $rs->fields[1],
					"trandate"				=> date("d/m/Y",strtotime($rs->fields[2])),
					"trandisplayname"		=> utf8_encode($rs->fields[3]),
					"memo"					=> utf8_encode($rs->fields[4]),
					"memoControl"			=> utf8_encode($rs->fields[5]),
					"entitytitle"			=> $rs->fields[6],
					"entityid"				=> $rs->fields[7],
					"total"					=> $rs->fields[8],
					"description"			=> utf8_encode($rs->fields[9]),
					"displayname"			=> utf8_encode($rs->fields[10]),
					"itemid"				=> $rs->fields[11],
					"purchasedescription"	=> utf8_encode($rs->fields[12]),
					"abbreviation"			=> $rs->fields[13],
					"dua"					=> $rs->fields[14],
					"binnumber"				=> $rs->fields[15],
					"inventorynumber"		=> $rs->fields[16],
					"name"					=> utf8_encode($rs->fields[17]),
					"expirationdate"		=> date("d/m/Y",strtotime($rs->fields[18])),
					"arreglo"				=> $rs->fields[19],
					"quantity_aprobada"		=> $rs->fields[20],
					"fullname"				=> utf8_encode($rs->fields[21]),
					"firstname"				=> utf8_encode($rs->fields[22]),
					"lastname"				=> utf8_encode($rs->fields[23]),
					"linesequencenumber"	=> $rs->fields[24],
					"undPrincipal"			=> $rs->fields[25],
				];
				$rs->MoveNext();
			}
		}
		return $data;
	}
	
}
