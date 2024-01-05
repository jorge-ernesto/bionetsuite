<?php

class revisionLMModel extends Model
{

	public function __construct()
	{
		parent::__construct();
	}
	
	/*public function guardarDatos($datos)
	{
		$c=0;
		$sql="";
		foreach($datos as $data){
			$sql="INSERT INTO tb_revision_antes VALUES (null,1111,2222,'XXX','YYY','ZZZ','QQQ','".$data[0]."','".$data[1]."','".$data[2]."','".$data[3]."','".$data[4]."',NOW());";
			$rs  = $this->_db->get_Connection3()->Execute($sql);
			$c++;
		}
		return $rs;
	}*/
	
	public function getCabeceraRLM($id)
	{
		$sql = "select 
			B.id as idListaMateriales,
			BR.id as idRevision,
			BR.custrecord211 as firmanteEmitido,
			BR.custrecord212 as fechaFirmaEmitido,
			BR.custrecord213 as firmanteRevisado,
			BR.custrecord214 as fechaFirmaRevisado,
			BR.custrecord215 as firmanteAprobado,
			BR.custrecord216 as fechaFirmaAprobado,
			LOWER(TO_CHAR(BR.createddate-5/24,'DD/MM/YYYY HH12:MI:SS AM')) as fechaCreado,
			B.name as nombreListaMateriales,
			BR.name as nombreRevision,
			BR.memo as productoBulkLinea
			from bomrevision BR
			inner join bom B on (B.id=BR.billofmaterials)
			where BR.id='$id';";
		$rs  = $this->_db->get_Connection()->Execute($sql);
		$contador = $rs->RecordCount();
		if (intval($contador) > 0) {
			while (!$rs->EOF) {
				$datos[] = [
					"idListaMateriales"	 	=> $rs->fields[0],
					"idRevision"	 		=> $rs->fields[1],
					"firmanteEmitido" 		=> utf8_encode($rs->fields[2]),
					"fechaFirmaEmitido"		=> $rs->fields[3],
					"firmanteRevisado"	 	=> utf8_encode($rs->fields[4]),
					"fechaFirmaRevisado"	=> $rs->fields[5],
					"firmanteAprobado"		=> utf8_encode($rs->fields[6]),
					"fechaFirmaAprobado"	=> $rs->fields[7],
					"fechaCreado"	 		=> $rs->fields[8],
					"nombreListaMateriales"	=> utf8_encode($rs->fields[9]),
					"nombreRevision"	 	=> utf8_encode($rs->fields[10]),
					"productoBulkLinea"	 	=> utf8_encode($rs->fields[11]),
				];
				$rs->MoveNext();
			}
		}
		return $datos;
	}
	
	public function getDetalleRLM($id)
	{
		$sql = "select 
			BRC.id as id,
			(I.itemid || ' ' || I.displayname) as codigoDescripcion,
			TRIM(I.itemid) as codigo,
			TRIM(I.displayname) as descripcion,
			BRC.componentyield as rendimiento,
			NVL(BRC.custrecord184,'F') as principioActivo,
			BRC.bomquantity as cantidad,
			U.pluralabbreviation AS unidad 
			from bomrevisioncomponent BRC
			inner join item I on (I.id=BRC.item)
			inner join unitsTypeUom U ON (U.internalid=I.consumptionunit)
			where BRC.bomrevision='$id' 
			order by BRC.id asc;";
		$rs  = $this->_db->get_Connection()->Execute($sql);
		$contador = $rs->RecordCount();
		if (intval($contador) > 0) {
			while (!$rs->EOF) {
				$datos[] = [
					"id"				=> utf8_encode($rs->fields[0]),
					"codigoDescripcion"	=> utf8_encode($rs->fields[1]),
					"codigo"			=> utf8_encode($rs->fields[2]),
					"descripcion"		=> utf8_encode($rs->fields[3]),
					"rendimiento"	 	=> $rs->fields[4],
					"principioActivo" 	=> $rs->fields[5],
					"cantidad"			=> $rs->fields[6],
					"unidad"	 		=> utf8_encode($rs->fields[7]),
				];
				$rs->MoveNext();
			}
		}
		return $datos;
	}
	
	public function getEstadoListaMateriales()
	{
		$sql = "select 
			B.id as idListaMateriales,
			BR.id as idRevision,
			BR.custrecord211 as firmanteEmitido,
			BR.custrecord212 as fechaFirmaEmitido,
			BR.custrecord213 as firmanteRevisado,
			BR.custrecord214 as fechaFirmaRevisado,
			BR.custrecord215 as firmanteAprobado,
			BR.custrecord216 as fechaFirmaAprobado,
			(CASE 
					WHEN BR.custrecord211 is null and BR.custrecord213 is null and BR.custrecord215 is null 
					THEN 'Sin Emision' 
					ELSE 
						  CASE 
								WHEN BR.custrecord211 is not null and BR.custrecord213 is null and BR.custrecord215 is null 
								THEN 'Emitido' 
								ELSE 
									  CASE 
											WHEN BR.custrecord211 is not null and BR.custrecord213 is not null and BR.custrecord215 is null 
											THEN 'Revisado' 
											ELSE 
												 CASE 
													  WHEN BR.custrecord211 is not null and BR.custrecord213 is not null and BR.custrecord215 is not null 
													  THEN 'Aprobado' 
													  ELSE 
															null
													  END
											END
								END
					END
			) as estado,
			LOWER(TO_CHAR(BR.createddate,'DD/MM/YYYY HH24:MI:SS AM')) as fechaCreado,
			B.name as nombreListaMateriales,
			BR.name as nombreRevision,
			BR.memo as productoBulkLinea,
			(CASE WHEN BR.isinactive='T' THEN 'SI' ELSE 'NO' END) as inactivo
			from bomrevision BR
			inner join bom B on (B.id=BR.billofmaterials)
			order by B.id asc, BR.id asc;";
		$rs  = $this->_db->get_Connection()->Execute($sql);
		$contador = $rs->RecordCount();
		if (intval($contador) > 0) {
			while (!$rs->EOF) {
				$datos[] = [
					"idListaMateriales"	 	=> $rs->fields[0],
					"idRevision"	 		=> $rs->fields[1],
					"firmanteEmitido" 		=> utf8_encode($rs->fields[2]),
					"fechaFirmaEmitido"		=> $rs->fields[3],
					"firmanteRevisado"	 	=> utf8_encode($rs->fields[4]),
					"fechaFirmaRevisado"	=> $rs->fields[5],
					"firmanteAprobado"		=> utf8_encode($rs->fields[6]),
					"fechaFirmaAprobado"	=> $rs->fields[7],
					"estado"				=> utf8_encode($rs->fields[8]),
					"fechaCreado"	 		=> $rs->fields[9],
					"nombreListaMateriales"	=> utf8_encode($rs->fields[10]),
					"nombreRevision"	 	=> utf8_encode($rs->fields[11]),
					"productoBulkLinea"	 	=> utf8_encode($rs->fields[12]),
					"inactivo"	 			=> $rs->fields[13],
				];
				$rs->MoveNext();
			}
		}
		return $datos;
	}
	
}