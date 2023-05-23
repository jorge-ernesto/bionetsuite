<?php

class bomModel extends Model
{

	public function __construct()
	{
		parent::__construct();
	}
	
	public function getCabeceraOrdenTrabajo($idtransaccion){
	
			$sql = "select
			T.ID as idtransaccion,
			T.TranID as NroOpe, 
			T.trandate as FechaCreacion, 
			T.trandisplayname as NombreTraslado,
			T.memo as Nota,
			I.itemid as codProd,
			t.custbodyiqsassydescription as producto,
			I.displayname as producto1,
			T.custbody41 AS Linea,
			T.custbodybio_cam_fechacaducidad as FexExp,
			T.custbodybio_cam_lote as Lote,
			T.custbody126 as FecFab,
			TL.quantity as CantProd,
			U.abbreviation as unidad,
			E1.firstname || ' ' || E1.lastname as EmiNomApe,
			T.custbody71 as firmaemitido,
			E2.firstname || ' ' || E2.lastname as RevNomApe,
			T.custbody72 as firmarevisado,
			E3.firstname || ' ' || E3.lastname as AjuNomApe,
			T.custbody73 as firmaajustado,
			E4.firstname || ' ' || E4.lastname as VerNomApe,
			T.custbody74 as firmaverificado,
			CL.name as TipOT
			from Transaction T
			left join TransactionLine TL ON ( TL.Transaction = T.ID and TL.id=0 )
			left join (select id,itemid,description,consumptionunit,displayname from item) I on I.id=TL.item
			INNER JOIN unitsTypeUom U on (U.internalid=I.consumptionunit)
			left join CUSTOMLIST1025 CL on CL.id=T.custbody8
			left join (select ID,firstname,lastname from Employee)E1 on E1.id=T.custbody67
			left join (select ID,firstname,lastname from Employee)E2 on E2.id=T.custbody69
			left join (select ID,firstname,lastname from Employee)E3 on E3.id=T.custbody70
			left join (select ID,firstname,lastname from Employee)E4 on E4.id=T.custbody68
			where T.ID IN ('$idtransaccion');";
		$rs  = $this->_db->get_Connection()->Execute($sql);
		$contador = $rs->RecordCount();
		if (intval($contador) > 0) {
			while (!$rs->EOF) {
				$datos[] = [
					"idtransaccion"	=> $rs->fields[0],
					"NroOpe"		=> $rs->fields[1],
					"FechaCreacion"	=> date("d/m/Y",strtotime($rs->fields[2])),
					"NombreTraslado"=> utf8_encode($rs->fields[3]),
					"Nota"			=> utf8_encode($rs->fields[4]),
					"codProd"		=> $rs->fields[5],
					"producto"		=> utf8_encode($rs->fields[6]),
					"producto1"		=> utf8_encode($rs->fields[7]),
					"Linea"			=> utf8_encode($rs->fields[8]),
					"FexExp"		=> date("d/m/Y",strtotime($rs->fields[9])),
					"Lote"			=> $rs->fields[10],
					"FecFab"		=> date("d/m/Y",strtotime($rs->fields[11])),
					"CantProd"		=> $rs->fields[12],
					"unidad"		=> $rs->fields[13],
					"EmiNomApe"		=> utf8_encode($rs->fields[14]),
					"firmaemitido"	=> $rs->fields[15],
					"RevNomApe"		=> utf8_encode($rs->fields[16]),
					"firmarevisado"	=> $rs->fields[17],
					"AjuNomApe"		=> utf8_encode($rs->fields[18]),
					"firmaajustado"	=> $rs->fields[19],
					"VerNomApe"		=> utf8_encode($rs->fields[20]),
					"firmaverificado"=> $rs->fields[21],
					"TipOT"			=> utf8_encode($rs->fields[22]),
				];
				$rs->MoveNext();
			}
		}
		return $datos;
	}
	
	public function getDetalleOrdenTrabajo($idtransaccion,$where1,$where2){

		$sql="select distinct
			I.ID , 
			I.fullname as codigo,
			I.displayname,
			(CASE 
			WHEN PT.abbreviation = 'GR' THEN (TL.quantity*1000*-1) 
			WHEN PT.abbreviation = 'UND' THEN round(TL.quantity*-1,0)
			ELSE (TL.quantity*-1) 
			END) as cantidad,
			PT.abbreviation,
			(CASE WHEN PT.custrecord184 = 'T' THEN 'T' ELSE 'F' END) as principActivo,
			TL.linesequencenumber as secuencia 
			from (SELECT * FROM TransactionLine $where1) TL
			left JOIN Item I ON ( I.ID = TL.Item ) 
			INNER JOIN (
				select I.itemid, bcr.custrecord184, TL.quantity*-1 as quantity, U.abbreviation
				FROM Transaction T
				INNER JOIN (SELECT * FROM TransactionLine where itemsource like 'STOCK%') TL ON ( TL.Transaction = T.ID )
				INNER JOIN Item I ON ( I.ID = TL.Item )
				INNER JOIN unitsTypeUom U ON ( U.internalid=I.saleunit )
				LEFT JOIN bomrevisioncomponent bcr on (bcr.bomrevision=T.billofmaterialsrevision and bcr.item=TL.Item)
				WHERE T.ID IN ('$idtransaccion')
				) PT on PT.itemid=I.itemid  
			where TL.transaction='$idtransaccion' $where2 
			order by TL.linesequencenumber asc;";
		$rs  = $this->_db->get_Connection()->Execute($sql);
		$contador = $rs->RecordCount();
		if (intval($contador) > 0) {
			while (!$rs->EOF) {
				$datos[] = [
					"ID"			=> $rs->fields[0],
					"codigo"		=> $rs->fields[1],
					"articulo"		=> utf8_encode($rs->fields[2]),
					"cantidad"		=> $rs->fields[3],
					"und"			=> $rs->fields[4],
					"principActivo"	=> $rs->fields[5],
					"secuencia" 	=> $rs->fields[6],
				];
				$rs->MoveNext();
			}
		}
		return $datos;
	}
	
}