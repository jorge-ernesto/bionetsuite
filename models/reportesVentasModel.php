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

	public function getVentasCostosTest($dateBegin, $dateEnd)
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
		error_log('getVentasCostosTest');
		error_log('Contador: '.$contador);
		die();
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

	public function getDescuentoVtas($dateBegin, $dateEnd, $_dateBegin, $_dateEnd)
	{
		$sql = "SELECT
					TO_CHAR(A.TRANDATE,'yyyy') ANIO,

					TO_CHAR(A.TRANDATE,'MONTH') MES,

					C.ALTNAME NOMBRE_CLIENTE,
				
					(CASE A.CUSTBODY_NS_DOCUMENT_TYPE WHEN 2 THEN 'Factura' WHEN 4 THEN 'Boleta de Venta' WHEN 8 THEN 'Nota de credito' WHEN 9 THEN 'Nota de debito' ELSE 'NO' END) TIPO_DOC,
				
					A.TRANID NRO_DOC,
					
					'' CODIGO,
							
					SUM(
						(CASE A.CURRENCY WHEN 2 THEN ROUND((B.FOREIGNAMOUNT*-1)*A.EXCHANGERATE,2) ELSE (B.FOREIGNAMOUNT*-1) END)
					) IMPORTE_BRUTO_SOLES,
						
					SUM( 
						(CASE A.CURRENCY WHEN 2 THEN ROUND((B.NETAMOUNT*-1)*A.EXCHANGERATE,2) ELSE (B.NETAMOUNT*-1) END) 
					) IMPORTE_NETO_SOLES
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
					AND (TO_DATE(A.TRANDATE, 'dd/MM/yyyy') BETWEEN '$dateBegin' AND '$dateEnd' OR TO_DATE(A.TRANDATE, 'dd/MM/yyyy') BETWEEN '$_dateBegin' AND '$_dateEnd')
					AND B.ACCOUNTINGLINETYPE IN ('INCOME','PAYMENT')
					AND L.CONTRIBUTION > 0
					-- FILTROS ADICIONALES
					AND E.ITEMID NOT IN ('SER0000189', 'Pago Anticipo')
					AND (G.NAME IN ('INYECTABLES', 'LIQUIDOS', 'POLVOS', 'SEMISOLIDOS', 'SOLIDOS', 'SOLUCIONES TOPICAS') OR (G.NAME = 'VARIOS' AND E.ITEMID IN ('VAR0000003', 'VAR0000013', 'VAR0000067', 'VAR0000008')))
					AND A.CUSTBODY114 NOT IN ('13', '22')
					AND A.CUSTBODY_NS_DOCUMENT_TYPE != '8'
					AND R.ID != '16'
				GROUP BY
					A.TRANDATE, A.CUSTBODY_NS_DOCUMENT_TYPE, A.TRANID, C.ALTNAME
							
				UNION ALL
				
				SELECT
					TO_CHAR(A.TRANDATE,'yyyy') ANIO,

					TO_CHAR(A.TRANDATE,'MONTH') MES,
				
					C.ALTNAME NOMBRE_CLIENTE,
				
					(CASE A.CUSTBODY_NS_DOCUMENT_TYPE WHEN 2 THEN 'Factura' WHEN 4 THEN 'Boleta de Venta' WHEN 8 THEN 'Nota de credito' WHEN 9 THEN 'Nota de debito' ELSE 'NO' END) TIPO_DOC,
				
					A.TRANID NRO_DOC,
					
					E.ITEMID CODIGO,
							
					(CASE A.CURRENCY WHEN 2 THEN ROUND((B.FOREIGNAMOUNT*-1)*A.EXCHANGERATE,2) ELSE (B.FOREIGNAMOUNT*-1) END) IMPORTE_BRUTO_SOLES,
						
					(CASE A.CURRENCY WHEN 2 THEN ROUND((B.NETAMOUNT*-1)*A.EXCHANGERATE,2) ELSE (B.NETAMOUNT*-1) END) IMPORTE_NETO_SOLES
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
					AND (TO_DATE(A.TRANDATE, 'dd/MM/yyyy') BETWEEN '$dateBegin' AND '$dateEnd' OR TO_DATE(A.TRANDATE, 'dd/MM/yyyy') BETWEEN '$_dateBegin' AND '$_dateEnd')
					AND B.ACCOUNTINGLINETYPE IN ('INCOME','PAYMENT')
					AND L.CONTRIBUTION > 0
					-- FILTROS ADICIONALES
					AND E.ITEMID NOT IN ('SER0000189', 'Pago Anticipo')
					AND (G.NAME IN ('INYECTABLES', 'LIQUIDOS', 'POLVOS', 'SEMISOLIDOS', 'SOLIDOS', 'SOLUCIONES TOPICAS') OR (G.NAME = 'VARIOS' AND E.ITEMID IN ('VAR0000003', 'VAR0000013', 'VAR0000067', 'VAR0000008')))
					AND A.CUSTBODY114 NOT IN ('13', '22')
					AND A.CUSTBODY_NS_DOCUMENT_TYPE = '8'
					AND R.ID != '16';";

		$rs = $this->_db->get_Connection()->Execute($sql);
		$contador = $rs->RecordCount();
		if (intval($contador) > 0) {
			while (!$rs->EOF) {
				$datos[] = [
					"ANIO" => $rs->fields[0],
					"MES" => $rs->fields[1],
					"NOMBRE_CLIENTE" => $rs->fields[2],
					"TIPO_DOC" => $rs->fields[3],
					"NRO_DOC" => $rs->fields[4],
					"CODIGO" => $rs->fields[5],
					"IMPORTE_BRUTO_SOLES" => $rs->fields[6],
					"IMPORTE_NETO_SOLES" => $rs->fields[7],
				];
				$rs->MoveNext();
			}
		}
		return $datos;
	}

	public function getDamImportaciones($dateBegin, $dateEnd, $codigo_articulo, $linea_articulo)
	{
		/* Condicion de Articulo */
		$where_codigo = '';
		$codigo_articulo = TRIM($codigo_articulo);
		if (!empty($codigo_articulo)) {
			$where_codigo = "AND I.ITEMID IN ('$codigo_articulo')";
		}
		/* Cerrar */

		/* Condicion de Linea de Articulo */
		$where_linea_articulo = '';
		$linea_articulo = TRIM($linea_articulo);
		if (!empty($linea_articulo)) {
			$where_linea_articulo = "AND L.NAME IN ('$linea_articulo')";
		}
		/* Cerrar */

		$sql = "SELECT
					-- INSUMO
					T.ID,
					I.ITEMID AS CODIGO,
					I.DISPLAYNAME AS DESCRIPCION,
					-- DAM DE IMPORTACION
					T.custbody39 AS DUA,
					TO_CHAR(T.TRANDATE,'DD/MM/YYYY') AS FECHA_RECEPCION,
					T.custbodybio_cam_codigo_aduana_dam AS CODIGO_ADUANA,
					TO_CHAR(T.custbodybio_cam_fecha_numeracion_dam,'yyyy') AS ANIO,
					T.custbodybio_cam_numero_dam AS NUMERO,
					TL.custcolbio_cam_serie_dam AS SERIE,
					T.custbodybio_cam_regimen_dam AS REGIMEN,
					TO_CHAR(T.custbodybio_cam_fecha_numeracion_dam,'DD/MM/YYYY') AS FECHA_NUMERACION,
					TL.custcolbio_cam_subpartida_aran_dam AS SUBPARTIDA_ARANCELARIA,
					-- ORDEN DE COMPRA
					OPERTYPE.NAME AS ORIGEN,
					TCAB_OC.TRANID AS NRO_ORDEN_COMPRA,
					V.CUSTENTITY_BIO_PROVEEDOR_NUM_DOC AS RUC,
					V.COMPANYNAME AS PROVEEDOR,
					-- FACTURA DE COMPRA
					CASE
						WHEN TCAB_FAC.TRANDATE IS NOT NULL THEN TO_CHAR(TCAB_FAC.TRANDATE,'DD/MM/YYYY')
						ELSE TO_CHAR(TCAB_FAC2.TRANDATE,'DD/MM/YYYY')
					END AS FECHA_FACTURA,
					CASE 
						WHEN TCAB_FAC.TRANID IS NOT NULL THEN TCAB_FAC.TRANID
						ELSE TCAB_FAC2.TRANID
					END AS NRO_FACTURA,
					CASE
						WHEN TCAB_FAC.CURRENCY IS NOT NULL THEN (CASE WHEN TCAB_FAC.CURRENCY = 1 THEN 'Soles' WHEN TCAB_FAC.CURRENCY = 2 THEN 'US Dollar' ELSE '' END)
						ELSE (CASE WHEN TCAB_FAC2.CURRENCY = 1 THEN 'Soles' WHEN TCAB_FAC2.CURRENCY = 2 THEN 'US Dollar' ELSE '' END)
					END AS MONEDA,
					CASE
						WHEN TCAB_FAC.EXCHANGERATE IS NOT NULL THEN TCAB_FAC.EXCHANGERATE
						ELSE TCAB_FAC2.EXCHANGERATE
					END AS T_CAMBIO,
					UTO.ABBREVIATION AS UNIDAD,
					CASE
						WHEN TDET_FAC.QUANTITY IS NOT NULL THEN TDET_FAC.QUANTITY
						ELSE TDET_FAC2.QUANTITY
					END AS CANTIDAD_FACTURA,
					CASE
						WHEN UTO.ABBREVIATION = 'GLL' THEN (TL.QUANTITY/3.8)
						WHEN UTO.ABBREVIATION = 'GR' THEN (TL.QUANTITY*1000)
						WHEN UTO.ABBREVIATION = 'MIL' THEN (TL.QUANTITY/1000)
						ELSE TL.QUANTITY
					END AS CANTIDAD_DAM,
					CASE
						WHEN TDET_FAC.NETAMOUNT IS NOT NULL THEN (TDET_FAC.NETAMOUNT/TDET_FAC.QUANTITY)
						ELSE (TDET_FAC2.NETAMOUNT/TDET_FAC2.QUANTITY)
					END AS PRECIO_LINEA,
					CASE
						WHEN TDET_FAC.NETAMOUNT IS NOT NULL THEN TDET_FAC.NETAMOUNT
						ELSE TDET_FAC2.NETAMOUNT
					END AS TOTAL_LINEA,
					CASE
						WHEN TCAB_FAC.FOREIGNTOTAL IS NOT NULL THEN TCAB_FAC.FOREIGNTOTAL
						ELSE TCAB_FAC2.FOREIGNTOTAL
					END AS TOTAL_FACTURA,
					NVL(L.NAME, ' ') LINEA,
					T.TRANID AS NRO_REC
				FROM
					TRANSACTION T
					INNER JOIN TRANSACTIONLINE TL                                       ON ( T.ID = TL.TRANSACTION )
					INNER JOIN ITEM I                                                   ON ( TL.ITEM = I.ID )
					LEFT JOIN CUSTOMRECORD1334 L                                        ON ( I.CUSTITEM3 = L.ID )
					LEFT JOIN CUSTOMRECORD_NS_PE_OPERATION_TYPE OPERTYPE                ON ( T.custbody_ns_pe_oper_type = OPERTYPE.ID )
					LEFT JOIN ( SELECT ID, TRANID, ENTITY FROM TRANSACTION ) AS TCAB_OC ON ( TL.createdfrom = TCAB_OC.ID )    
					LEFT JOIN unitsTypeUom UTO                                          ON ( TL.units = UTO.internalid )
					LEFT JOIN VENDOR V                                                  ON ( TCAB_OC.ENTITY = V.ID )
				
					-- ENCONTRAMOS FACTURA CON MISMO ITEM Y CANTIDAD
					LEFT JOIN (
						SELECT
							previousdoc, item, quantity, MIN(nextdoc) AS nextdoc
						FROM 
							TRANSACTIONLINE TLINE
							INNER JOIN NextTransactionLineLink NTLINE ON ( TLINE.TRANSACTION = NTLINE.nextdoc )
						WHERE
							nexttype = 'VendBill' AND item IS NOT NULL
						GROUP BY
							previousdoc, item, quantity
					) AS NTL ON ( TCAB_OC.ID = NTL.previousdoc AND TL.ITEM = NTL.ITEM AND TL.QUANTITY = NTL.QUANTITY )
				
					-- FACTURA CABECERA
					LEFT JOIN (
						SELECT ID, TRANID, TRANDATE, CURRENCY, EXCHANGERATE, (FOREIGNTOTAL*-1) AS FOREIGNTOTAL FROM TRANSACTION
					) AS TCAB_FAC ON ( NTL.nextdoc = TCAB_FAC.ID )
				
					-- FACTURA DETALLE
					LEFT JOIN (
						SELECT
							TRANSACTION, ITEM, SUM(CASE
														WHEN unitsTypeUom.abbreviation = 'GLL' THEN (QUANTITY/3.8)
														WHEN unitsTypeUom.abbreviation = 'GR' THEN (QUANTITY*1000)
														WHEN unitsTypeUom.abbreviation = 'MIL' THEN (QUANTITY/1000)
														ELSE QUANTITY
													END) AS QUANTITY,
												SUM(NETAMOUNT) AS NETAMOUNT
						FROM
							TRANSACTIONLINE
							INNER JOIN ITEM ON ( TRANSACTIONLINE.ITEM = ITEM.ID )
							LEFT JOIN unitsTypeUom ON ( TRANSACTIONLINE.units = unitsTypeUom.internalid )
						WHERE
							1 = 1
							AND QUANTITY IS NOT NULL
							AND UNITS IS NOT NULL
							AND SUBSTR(ITEMID, 1, 2) IN ('MV', 'ME', 'MP')
						GROUP BY 
							TRANSACTION, ITEM
					) AS TDET_FAC ON ( TCAB_FAC.ID = TDET_FAC.TRANSACTION AND TL.ITEM = TDET_FAC.ITEM )
				
					-- ENCONTRAMOS FACTURA CON MISMO ITEM
					LEFT JOIN (
						SELECT
							previousdoc, item, MIN(nextdoc) AS nextdoc
						FROM 
							TRANSACTIONLINE TLINE
							INNER JOIN NextTransactionLineLink NTLINE ON ( TLINE.TRANSACTION = NTLINE.nextdoc )
						WHERE
							nexttype = 'VendBill' AND item IS NOT NULL
						GROUP BY
							previousdoc, item
					) AS NTL2 ON ( TCAB_OC.ID = NTL2.previousdoc AND TL.ITEM = NTL2.ITEM )
				
					-- FACTURA CABECERA
					LEFT JOIN (
						SELECT ID, TRANID, TRANDATE, CURRENCY, EXCHANGERATE, (FOREIGNTOTAL*-1) AS FOREIGNTOTAL FROM TRANSACTION
					) AS TCAB_FAC2 ON ( NTL2.nextdoc = TCAB_FAC2.ID )
				
					-- FACTURA DETALLE
					LEFT JOIN (
						SELECT
							TRANSACTION, ITEM, SUM(CASE
														WHEN unitsTypeUom.abbreviation = 'GLL' THEN (QUANTITY/3.8)
														WHEN unitsTypeUom.abbreviation = 'GR' THEN (QUANTITY*1000)
														WHEN unitsTypeUom.abbreviation = 'MIL' THEN (QUANTITY/1000)
														ELSE QUANTITY
													END) AS QUANTITY,
												SUM(NETAMOUNT) AS NETAMOUNT
						FROM
							TRANSACTIONLINE
							INNER JOIN ITEM ON ( TRANSACTIONLINE.ITEM = ITEM.ID )
							LEFT JOIN unitsTypeUom ON ( TRANSACTIONLINE.units = unitsTypeUom.internalid )
						WHERE
							1 = 1
							AND QUANTITY IS NOT NULL
							AND UNITS IS NOT NULL
							AND SUBSTR(ITEMID, 1, 2) IN ('MV', 'ME', 'MP')
						GROUP BY 
							TRANSACTION, ITEM
					) AS TDET_FAC2 ON ( TCAB_FAC2.ID = TDET_FAC2.TRANSACTION AND TL.ITEM = TDET_FAC2.ITEM )
				WHERE
					1 = 1
					AND T.RECORDTYPE IN ('itemreceipt')
					AND T.VOIDED = 'F'
					AND TO_DATE(T.TRANDATE, 'dd/MM/yyyy') BETWEEN '$dateBegin' AND '$dateEnd'
					-- FILTROS ADICIONALES
					AND TL.QUANTITY IS NOT NULL
					AND TL.UNITS IS NOT NULL
					AND SUBSTR(I.ITEMID, 1, 2) IN ('MV', 'ME', 'MP')
					AND OPERTYPE.id = '18'
					-- FILTROS DINAMICOS
					$where_codigo
					$where_linea_articulo
				ORDER BY
					T.ID DESC;";
		error_log($sql);

		$rs = $this->_db->get_Connection()->Execute($sql);
		$contador = $rs->RecordCount();
		if (intval($contador) > 0) {
			while (!$rs->EOF) {
				$datos[] = [
					"ID" => $rs->fields[0],
					"CODIGO" => $rs->fields[1],
					"DESCRIPCION" => $rs->fields[2],
					"DUA" => $rs->fields[3],
					"FECHA_RECEPCION" => $rs->fields[4],
					"CODIGO_ADUANA" => $rs->fields[5],
					"ANIO" => $rs->fields[6],
					"NUMERO" => $rs->fields[7],
					"SERIE" => $rs->fields[8],
					"REGIMEN" => $rs->fields[9],
					"FECHA_NUMERACION" => $rs->fields[10],
					"SUBPARTIDA_ARANCELARIA" => $rs->fields[11],
					"PORCENTAJE_AD_VALOREM" => NULL,
					"ORIGEN" => $rs->fields[12],
					"NRO_ORDEN_COMPRA" => $rs->fields[13],
					"RUC" => $rs->fields[14],
					"PROVEEDOR" => $rs->fields[15],
					"FECHA_FACTURA" => $rs->fields[16],
					"NRO_FACTURA" => $rs->fields[17],
					"MONEDA" => $rs->fields[18],
					"T_CAMBIO" => $rs->fields[19],
					"UNIDAD" => $rs->fields[20],
					"CANTIDAD_FACTURA" => $rs->fields[21],
					"CANTIDAD_DAM" => $rs->fields[22],
					"PRECIO_LINEA" => $rs->fields[23],
					"TOTAL_LINEA" => $rs->fields[24],
					"TOTAL_FACTURA" => $rs->fields[25],
					"LINEA" => $rs->fields[26],
					"NRO_REC" => $rs->fields[27],
				];
				$rs->MoveNext();
			}
		}
		return $datos;
	}

	public function getPorcentajeAdValorem()
	{
		$sql = "EXEC dbo.NS_CN003_REPORTE_DRAWBACK_SP_SUBPARTIDAS;"; // dbo.NS_CN003_REPORTE_DRAWBACK_SP_SUBPARTIDAS

		$rs = $this->_db->get_Connection5()->Execute($sql);
		$contador = $rs->RecordCount();
		if (intval($contador) > 0) {
			while (!$rs->EOF) {
				$datos[] = [
					"ID_SUBPARTIDA" => $rs->fields[0],
					"CODIGO_SUBPARTIDA" => $rs->fields[1],
					"PORCENTAJE_AD_VALOREM" => $rs->fields[2],
				];
				$rs->MoveNext();
			}
		}
		return $datos;
	}

}
