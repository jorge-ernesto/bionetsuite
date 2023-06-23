<?php

class reportesVentasModel extends Model
{

	public function __construct()
	{
		parent::__construct();
	}

    public function getVentasCostos($dateBegin, $dateEnd)
	{
		ini_set('memory_limit', '-1');
		ini_set('max_execution_time', '-1');

		$sql = "SELECT 
					A.ID, --0
					(CASE A.CUSTBODY_NS_DOCUMENT_TYPE WHEN 2 THEN 'Factura' WHEN 4 THEN 'Boleta de Venta' WHEN 8 THEN 'Nota de credito' WHEN 9 THEN 'Nota de debito' ELSE 'NO' END) TIPO_DOC, --1
					SUBSTR(A.TRANID,4,4) SERIE, --2
					A.TRANID NRO_DOC, --3
					C.CUSTENTITY_BIO_NUM_DOC RUC_DNI, --4
					C.ALTNAME NOMBRE_CLIENTE, --5
					TO_CHAR(A.TRANDATE,'DD/MM/YYYY') FECHA, --6
					NVL((CASE A.CUSTBODY_NS_DOCUMENT_TYPE WHEN 8 THEN CASE A.CURRENCY WHEN 2 THEN A.FOREIGNTOTAL ELSE ROUND(A.FOREIGNTOTAL/1.18,2) END ELSE A.CUSTBODY_STC_AMOUNT_AFTER_DISCOUNT END),0.00) SUBTOTAL, --7
					A.FOREIGNTOTAL TOTAL, --8
					E.ITEMID CODIGO, --9
					NVL(REPLACE(B.MEMO,CHR(10),''),' ') DESCRIPCION, --10
					NVL((B.QUANTITY*-1),0) CANTIDAD, --11
					B.RATE PRECIO, --12
					(CASE A.CURRENCY WHEN 2 THEN ROUND((B.NETAMOUNT*-1)*A.EXCHANGERATE,2) ELSE (B.NETAMOUNT*-1) END) IMPORTE_NETO_SOLES, --13
        			(CASE A.CURRENCY WHEN 2 THEN (B.NETAMOUNT*-1) ELSE 0 END) IMPORTE_NETO_USD, --14
					NVL(E.CUSTITEM14, ' ') PRODUCTO, --15
					NVL(F.NAME, ' ') PRESENT, --16
					NVL(G.NAME,' ') LINEA, --17
					NVL(M.ENTITYID,' ') VENDEDOR, --18
					NVL(J.NAME,' ') DIVISION, --19
					NVL(K.NAME,' ') ZONA, --20
					NVL(O.NAME,' ' ) REGION, --21
					NVL(Q.NAME,' ' ) DEPARTAMENTO, --22
					(CASE A.CURRENCY WHEN 1 THEN 'Soles' ELSE 'US Dollar' END) MONEDA, --23
					A.EXCHANGERATE T_CAMBIO, --24
					(A.CUSTBODY_NS_DOC_SERIE_REF||'-'||A.CUSTBODY_NS_NUM_DOC_REF) DOC_REFERENCIA, --25
					NVL(T.NAME,' ') CONDICION_PAGO, --26
					NVL(R.NAME,' ') TIPO_IMPUESTO, --27
					TO_CHAR(A.TRANDATE,'MONTH') MES, --28
					NVL(S.NAME,' ') TIPO_OPERACION, --29
					-- ADICIONAL
					TO_CHAR(A.TRANDATE,'yyyy') NRO_ANIO, --30
					TO_CHAR(A.TRANDATE,'mm') NRO_MES, --31
					B.LOCATION ID_ALMACEN, --32
					LOC.FULLNAME AS NOMBRE_ALMACEN, --33
					(CASE A.CURRENCY WHEN 2 THEN ROUND((B.FOREIGNAMOUNT*-1)*A.EXCHANGERATE,2) ELSE (B.FOREIGNAMOUNT*-1) END) IMPORTE_BRUTO_SOLES, --34
					CONCAT(CONCAT(TRIM(NVL(A.custbody_ns_gr_rel_serie,'')),'-'), TRIM(NVL(A.custbody_ns_gr_rel_num,''))) GUIA_REMISION --35
				FROM 
					TRANSACTION A
					INNER JOIN TRANSACTIONLINE B ON A.ID = B.TRANSACTION
					INNER JOIN CUSTOMER C ON A.ENTITY = C.ID
					LEFT JOIN CUSTOMER D ON C.CUSTENTITY27 = D.ID
					INNER JOIN ITEM E ON B.ITEM = E.ID
					LEFT JOIN CUSTOMLIST1054 F ON E.CUSTITEM6 = F.ID
					LEFT JOIN CUSTOMLIST1334 G ON E.CUSTITEM3 = G.ID
					LEFT JOIN CUSTOMLIST1055 H ON E.CUSTITEM7 = H.ID
					LEFT JOIN CUSTOMLIST1257 I ON E.CUSTITEM15 = I.ID
					LEFT JOIN CUSTOMLIST1061 J ON C.CUSTENTITY14 = J.ID
					LEFT JOIN CUSTOMLIST1060 K ON C.TERRITORY = K.ID 
					LEFT JOIN INVOICESALESTEAM L ON A.ID = L.TRANSACTION
					LEFT JOIN EMPLOYEE M ON L.EMPLOYEE = M.ID
					LEFT JOIN CUSTOMLIST1059 O ON C.CUSTENTITY19 = O.ID
					LEFT JOIN CUSTOMERADDRESSBOOKENTITYADDRESS P ON A.BILLINGADDRESS = P.NKEY
					LEFT JOIN CUSTOMLIST1018 Q ON P.CUSTRECORD176 = Q.ID 
					LEFT JOIN CUSTOMRECORD_NS_AFEC_IGV R ON B.CUSTCOL_NS_AFEC_IGV = R.ID
					LEFT JOIN CUSTOMRECORD_NS_PE_OPERATION_TYPE S ON A.CUSTBODY_NS_PE_OPER_TYPE = S.ID
					LEFT JOIN CUSTOMLIST1026 T ON A.CUSTBODY12 = T.ID
					LEFT JOIN LOCATION LOC on (LOC.id = B.location)
				WHERE
					A.RECORDTYPE IN ('invoice','creditmemo')
					AND A.VOIDED = 'F'
					AND TO_DATE(A.TRANDATE, 'dd/MM/yyyy') BETWEEN '$dateBegin' AND '$dateEnd'
					AND B.ACCOUNTINGLINETYPE IN ('INCOME','PAYMENT')
					AND L.CONTRIBUTION > 0
				ORDER BY
					A.ID DESC;";

		$rs = $this->_db->get_Connection()->Execute($sql);
		$contador = $rs->RecordCount();
		if (intval($contador) > 0) {
			while (!$rs->EOF) {
				$datos[] = [
					"ID" => $rs->fields[0],
					"TIPO_DOC" => $rs->fields[1],
					"SERIE" => $rs->fields[2],
					"NRO_DOC" => $rs->fields[3],
					"RUC_DNI" => $rs->fields[4],
					"NOMBRE_CLIENTE" => $rs->fields[5],
					"FECHA" => $rs->fields[6],
					"SUBTOTAL" => $rs->fields[7],
					"TOTAL" => $rs->fields[8],
					"CODIGO" => $rs->fields[9],
					"DESCRIPCION" => $rs->fields[10],
					"CANTIDAD" => $rs->fields[11],
					"PRECIO" => $rs->fields[12],					
					"IMPORTE_NETO_SOLES" => $rs->fields[13],
					"IMPORTE_NETO_USD" => $rs->fields[14],
					"PRODUCTO" => $rs->fields[15],
					"PRESENT" => $rs->fields[16],
					"LINEA" => $rs->fields[17],
					"VENDEDOR" => $rs->fields[18],
					"DIVISION" => $rs->fields[19],
					"ZONA" => $rs->fields[20],
					"REGION" => $rs->fields[21],
					"DEPARTAMENTO" => $rs->fields[22],
					"MONEDA" => $rs->fields[23],
					"T_CAMBIO" => $rs->fields[24],
					"DOC_REFERENCIA" => $rs->fields[25],
					"CONDICION_PAGO" => $rs->fields[26],
					"TIPO_IMPUESTO" => $rs->fields[27],
					"MES" => $rs->fields[28],
					"TIPO_OPERACION" => $rs->fields[29],
					// ADICIONAL
					"NRO_ANIO" => $rs->fields[30],
					"NRO_MES" => $rs->fields[31],
					"ID_ALMACEN" => $rs->fields[32],
					"NOMBRE_ALMACEN" => $rs->fields[33],
					"IMPORTE_BRUTO_SOLES" => $rs->fields[34],
					"GUIA_REMISION" => $rs->fields[35],
				];
				$rs->MoveNext();
			}
		}
		return $datos;
    }

	public function getCostos()
	{
		$sql = "SELECT
					ID,
					ID_ARTICULO,
					ID_ALMACEN,
					NRO_MES,
					NRO_ANIO,
					CODIGO_ARTICULO,
					DESC_ARTICULO,
					DESC_ALMACEN,
					ID_TRANSACCION,
					NRO_DOCUMENTO,
					FECHA_DOCUMENTO,
					COSTO_ESTANDAR,
					FECHA_INSERCION
				FROM
					dbo.NS_CN001_REPORTE_VENTA_COSTO_T_REVALUACIONES_TEST"; // dbo.NS_CN001_REPORTE_VENTA_COSTO_T_REVALUACIONES

		$rs = $this->_db->get_Connection5()->Execute($sql);
		$contador = $rs->RecordCount();
		if (intval($contador) > 0) {
			while (!$rs->EOF) {
				$datos[] = [
					"ID" => $rs->fields[0],
					"ID_ARTICULO" => $rs->fields[1],
					"ID_ALMACEN" => $rs->fields[2],
					"NRO_MES" => $rs->fields[3],
					"NRO_ANIO" => $rs->fields[4],
					"CODIGO_ARTICULO" => $rs->fields[5],
					"DESC_ARTICULO" => $rs->fields[6],
					"DESC_ALMACEN" => $rs->fields[7],
					"ID_TRANSACCION" => $rs->fields[8],
					"NRO_DOCUMENTO" => $rs->fields[9],
					"FECHA_DOCUMENTO" => $rs->fields[10],
					"COSTO_ESTANDAR" => $rs->fields[11],
					"FECHA_INSERCION" => $rs->fields[12],
				];
				$rs->MoveNext();
			}
		}
		return $datos;
	}

}
