<?php

class reportesVentasController extends Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->loadHelper('functions');
    }

    public function index()
    {
        echo "Controlador de Reportes de Ventas";
    }

    /**
     * Proyecto 5188 - REPORTE DE VENTAS CON COSTO
     * Desde: 01/06/2023
     * Hasta: 30/06/2023
     */
    public function getReporteVentasCostos()
    {
        header('Access-Control-Allow-Origin: *');

        $input = json_decode(file_get_contents("php://input"), true);

        // Verificar datos
        // error_log('Verificar datos');
        // error_log(json_encode($input));
        // die();

        // Verificar datos
        // $response = array(
        //     'status' => 'error',
        //     'input' => $input
        // );
        // echo json_encode($response);
        // die();

        if ($input['paramsRequest']['dateBegin'] != null && $input['paramsRequest']['dateEnd'] != null) {
            ini_set('memory_limit', '-1');
            ini_set('max_execution_time', '-1');            

            $dateBegin = $input['paramsRequest']['dateBegin'];
            $dateEnd = $input['paramsRequest']['dateEnd'];
            $mod = 'REPORTE_VENTAS_COSTOS';
            $typeRep = 'reporteVentasCostos';
            $titleDocument = 'Reporte de Ventas con Costos';        

            /*Obtenemos fecha en formato correcto*/
            // $formatDateBegin = formatDate($dateBegin,4);
            // $formatDateEnd = formatDate($dateEnd,4);
            $formatDateBegin = $dateBegin;
            $formatDateEnd = $dateEnd;
            /*Cerrar Obtenemos fecha en formato correcto*/

            $this->getLibrary('PHPExcel/Classes/PHPExcel');
            $this->getLibrary('PHPExcel/Classes/PHPExcel/Reader/Excel5');
            $this->getLibrary('PHPExcel/Classes/PHPExcel/Reader/Excel2007');

            $objPHPExcel = new PHPExcel();

            // Obtener datos
            $objModel = $this->loadModel("reportesVentas");
            error_log("Inicio de consulta");
            $dataVentasCostos = $objModel->getVentasCostos($formatDateBegin, $formatDateEnd);
            error_log("Fin de consulta");

            error_log("Inicio de conversion UTF8");
            $dataVentasCostos = utf8_encode_recursive($dataVentasCostos);
            error_log("Fin de conversion UTF8");

            error_log("Inicio de obtener costos");
            $response = $this->getCostos($dataVentasCostos);
            $dataVentasCostos = $response['dataVentasCostos'];
            $dataCostos = $response['dataCostos'];
            error_log("Fin de obtener costos");
            // Cerrar Obtener datos

            $objPHPExcel->getProperties()
                                ->setCreator("Laboratorios Biomont") //Autor
                                ->setLastModifiedBy("Laboratorios Biomont") //Ultimo usuario que lo modificó
                                ->setTitle("Reporte de Ventas con Costo") //Título
                                ->setSubject("Reporte de Ventas con Costo") //Asunto
                                ->setDescription("Reporte de Ventas con Costo") //Descripción
                                ->setKeywords("Reporte de Ventas con Costo") //Etiquetas
                                ->setCategory("Reporte Excel"); //Categorías

            $objPHPExcel->setActiveSheetIndex(0);
            $objPHPExcel->getActiveSheet()->setTitle($titleDocument);
            $objPHPExcel->getActiveSheet()->setCellValue('A1', appName());
            $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(20);
            $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
            $objPHPExcel->getActiveSheet()->mergeCells('A1:F1');
            $objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(30);

            $objPHPExcel->getActiveSheet()->setCellValue('A3', 'Fechas');
            $objPHPExcel->getActiveSheet()->setCellValue('B3', $dateBegin);
            $objPHPExcel->getActiveSheet()->setCellValue('C3', $dateEnd);

            $objPHPExcel->getActiveSheet()->setCellValue('A4', 'Empresa');
            $objPHPExcel->getActiveSheet()->setCellValue('B4', appName());
            
            $row = 7;

            //Formatemos tamaño de columnas
            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth('15'); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth('15'); //16
            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth('15'); //16
            $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth('15'); //16
            $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth('15'); //16
            $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth('15'); //16
            $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth('15'); //16
            $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth('15'); //16
            $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth('15'); //16
            $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth('15'); //16
            $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth('15'); //16
            $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth('15'); //16
            $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth('15'); //16
            $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth('15'); //16
            $objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth('15'); //16
            $objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth('15'); //16
            $objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setWidth('15'); //16
            $objPHPExcel->getActiveSheet()->getColumnDimension('R')->setWidth('15'); //16
            $objPHPExcel->getActiveSheet()->getColumnDimension('S')->setWidth('15'); //16
            $objPHPExcel->getActiveSheet()->getColumnDimension('T')->setWidth('15'); //16
            $objPHPExcel->getActiveSheet()->getColumnDimension('U')->setWidth('15'); //16
            $objPHPExcel->getActiveSheet()->getColumnDimension('V')->setWidth('15'); //16
            $objPHPExcel->getActiveSheet()->getColumnDimension('W')->setWidth('15'); //16
            $objPHPExcel->getActiveSheet()->getColumnDimension('X')->setWidth('15'); //16
            $objPHPExcel->getActiveSheet()->getColumnDimension('Y')->setWidth('15'); //16
            $objPHPExcel->getActiveSheet()->getColumnDimension('Z')->setWidth('15'); //16
            $objPHPExcel->getActiveSheet()->getColumnDimension('AA')->setWidth('15'); //16
            $objPHPExcel->getActiveSheet()->getColumnDimension('AB')->setWidth('15'); //16
            $objPHPExcel->getActiveSheet()->getColumnDimension('AC')->setWidth('15'); //16
            $objPHPExcel->getActiveSheet()->getColumnDimension('AD')->setWidth('15'); //16            
            $objPHPExcel->getActiveSheet()->getColumnDimension('AE')->setWidth('15'); //16
            $objPHPExcel->getActiveSheet()->getColumnDimension('AF')->setWidth('15'); //16
            //Cerrar Formateamos tamaño de columnas

            error_log("Inicio de creacion de Excel");
            //DATOS PARA MOSTRAR EN EXCEL
                //Inicio de cabecera (tabla)
                $objPHPExcel->getActiveSheet()->setCellValue('A'.$row, 'TIPO DOC');
                $objPHPExcel->getActiveSheet()->setCellValue('B'.$row, 'NRO DOC');
                $objPHPExcel->getActiveSheet()->setCellValue('C'.$row, 'RUC/DNI');
                $objPHPExcel->getActiveSheet()->setCellValue('D'.$row, 'NOMBRE DEL CLIENTE');
                $objPHPExcel->getActiveSheet()->setCellValue('E'.$row, 'FECHA');
                $objPHPExcel->getActiveSheet()->setCellValue('F'.$row, 'LINEA');
                $objPHPExcel->getActiveSheet()->setCellValue('G'.$row, 'CÓDIGO');
                $objPHPExcel->getActiveSheet()->setCellValue('H'.$row, 'DESCRIPCIÓN');
                $objPHPExcel->getActiveSheet()->setCellValue('I'.$row, 'PRESENTACIÓN');
                $objPHPExcel->getActiveSheet()->setCellValue('J'.$row, 'CANTIDAD VENDIDA'); // CANTIDAD
                $objPHPExcel->getActiveSheet()->setCellValue('K'.$row, 'PRECIO UNITARIO DE VENTA'); // PRECIO
                $objPHPExcel->getActiveSheet()->setCellValue('L'.$row, 'COSTO UNITARIO ESTANDAR'); // COSTO UNITARIO ESTANDAR
                $objPHPExcel->getActiveSheet()->setCellValue('M'.$row, 'COSTO TOTAL ESTANDAR'); // COSTO TOTAL ESTANDAR
                $objPHPExcel->getActiveSheet()->setCellValue('N'.$row, 'PRECIO DE VENTA'); // IMPORTE BRUTO SOLES
                $objPHPExcel->getActiveSheet()->setCellValue('O'.$row, 'DESCUENTO'); // DESCUENTO SOLES
                $objPHPExcel->getActiveSheet()->setCellValue('P'.$row, 'PRECIO VENTA NETO S/'); // IMPORTE NETO SOLES
                $objPHPExcel->getActiveSheet()->setCellValue('Q'.$row, 'PRECIO VENTA NETO $'); // IMPORTE NETO USD
                $objPHPExcel->getActiveSheet()->setCellValue('R'.$row, 'MONEDA');
                $objPHPExcel->getActiveSheet()->setCellValue('S'.$row, 'TIPO DE CAMBIO');
                $objPHPExcel->getActiveSheet()->setCellValue('T'.$row, 'DOC. REFRENCIA');
                $objPHPExcel->getActiveSheet()->setCellValue('U'.$row, 'CONDICIÓN PAGO');
                $objPHPExcel->getActiveSheet()->setCellValue('V'.$row, 'VENDEDOR');
                $objPHPExcel->getActiveSheet()->setCellValue('W'.$row, 'DEPARTAMENTO');
                $objPHPExcel->getActiveSheet()->setCellValue('X'.$row, 'UNIDAD DE NEGOCIO'); // ANTES DIVISION
                $objPHPExcel->getActiveSheet()->setCellValue('Y'.$row, 'ZONA');
                $objPHPExcel->getActiveSheet()->setCellValue('Z'.$row, 'REGION');
                $objPHPExcel->getActiveSheet()->setCellValue('AA'.$row, 'TIPO DE IMPUESTO');
                $objPHPExcel->getActiveSheet()->setCellValue('AB'.$row, 'TIPO DE OPERACIÓN');
                $objPHPExcel->getActiveSheet()->setCellValue('AC'.$row, 'GUÍA DE REMISIÓN');
                $objPHPExcel->getActiveSheet()->setCellValue('AD'.$row, 'ALMACÉN');
                $objPHPExcel->getActiveSheet()->setCellValue('AE'.$row, 'MES');
                $objPHPExcel->getActiveSheet()->setCellValue('AF'.$row, 'AÑO');
                $objPHPExcel->getActiveSheet()->setCellValue('AG'.$row, 'SECTOR');

                $objPHPExcel->getActiveSheet()->setAutoFilter('A'.$row.':AG'.$row); // Comentar para no ensuciar el Log de PHP

                $objPHPExcel->getActiveSheet()->getStyle('A'.$row.':AG'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);              
                
                $objPHPExcel->getActiveSheet()->getRowDimension($row)->setRowHeight(16);
                $objPHPExcel->getActiveSheet()->getStyle('A'.$row.':AG'.$row)->applyFromArray(
                    array(
                        'fill' => array(
                            'type' => PHPExcel_Style_Fill::FILL_SOLID,
                            'color' => array('rgb' => '337ab7')
                        ),
                        'font' => array(
                            'bold'  => true,
                            'color' => array('rgb' => 'FFFFFF'),
                            'size'  => 12,
                            //'name'  => 'Verdana'
                        )
                    )
                );              
                $row++;                             
                $objPHPExcel->getActiveSheet()->freezePane('A'.$row);
                //Fin de cabecera (tabla)

                //Ajustar el ancho de las columnas automáticamente
                $hoja = $objPHPExcel->getActiveSheet();
                foreach ($hoja->getColumnIterator() as $columna) {
                    $hoja->getColumnDimension($columna->getColumnIndex())->setAutoSize(true);
                }
                //Fin Ajustar el ancho de las columnas automáticamente

                //Inicio de cuerpo (tabla)
                foreach ($dataVentasCostos as $key => $dataCombustibles) {
                    $objPHPExcel->getActiveSheet()->setCellValue('A'.$row, $dataCombustibles['TIPO_DOC']);
                    $objPHPExcel->getActiveSheet()->setCellValue('B'.$row, $dataCombustibles['NRO_DOC']);
                    $objPHPExcel->getActiveSheet()->setCellValue('C'.$row, $dataCombustibles['RUC_DNI']);
                    $objPHPExcel->getActiveSheet()->setCellValue('D'.$row, $dataCombustibles['NOMBRE_CLIENTE']);
                    $objPHPExcel->getActiveSheet()->setCellValue('E'.$row, $dataCombustibles['FECHA']);
                    $objPHPExcel->getActiveSheet()->setCellValue('F'.$row, $dataCombustibles['LINEA']);
                    $objPHPExcel->getActiveSheet()->setCellValue('G'.$row, $dataCombustibles['CODIGO']);
                    $objPHPExcel->getActiveSheet()->setCellValue('H'.$row, $dataCombustibles['DESCRIPCION']);
                    $objPHPExcel->getActiveSheet()->setCellValue('I'.$row, $dataCombustibles['PRESENT']);
                    $objPHPExcel->getActiveSheet()->setCellValue('J'.$row, $dataCombustibles['CANTIDAD']); // CANTIDAD
                    $objPHPExcel->getActiveSheet()->setCellValue('K'.$row, $dataCombustibles['PRECIO']); // PRECIO
                    $objPHPExcel->getActiveSheet()->setCellValue('L'.$row, $dataCombustibles['COSTO_UNITARIO_ESTANDAR']); // COSTO UNITARIO ESTANDAR
                    $objPHPExcel->getActiveSheet()->setCellValue('M'.$row, $dataCombustibles['CANTIDAD'] * $dataCombustibles['COSTO_UNITARIO_ESTANDAR']); // COSTO TOTAL ESTANDAR
                    $objPHPExcel->getActiveSheet()->setCellValue('N'.$row, $dataCombustibles['IMPORTE_BRUTO_SOLES']); // IMPORTE BRUTO SOLES
                    $objPHPExcel->getActiveSheet()->setCellValue('O'.$row, $dataCombustibles['IMPORTE_BRUTO_SOLES'] - $dataCombustibles['IMPORTE_NETO_SOLES']); // DESCUENTO SOLES
                    $objPHPExcel->getActiveSheet()->setCellValue('P'.$row, $dataCombustibles['IMPORTE_NETO_SOLES']); // IMPORTE NETO SOLES
                    $objPHPExcel->getActiveSheet()->setCellValue('Q'.$row, $dataCombustibles['IMPORTE_NETO_USD']); // IMPORTE NETO USD
                    $objPHPExcel->getActiveSheet()->setCellValue('R'.$row, $dataCombustibles['MONEDA']);
                    $objPHPExcel->getActiveSheet()->setCellValue('S'.$row, $dataCombustibles['T_CAMBIO']);
                    $objPHPExcel->getActiveSheet()->setCellValue('T'.$row, $dataCombustibles['DOC_REFERENCIA']);
                    $objPHPExcel->getActiveSheet()->setCellValue('U'.$row, $dataCombustibles['CONDICION_PAGO']);
                    $objPHPExcel->getActiveSheet()->setCellValue('V'.$row, $dataCombustibles['VENDEDOR']);
                    $objPHPExcel->getActiveSheet()->setCellValue('W'.$row, $dataCombustibles['DEPARTAMENTO']);
                    $objPHPExcel->getActiveSheet()->setCellValue('X'.$row, $dataCombustibles['DIVISION']);
                    $objPHPExcel->getActiveSheet()->setCellValue('Y'.$row, $dataCombustibles['ZONA']);
                    $objPHPExcel->getActiveSheet()->setCellValue('Z'.$row, $dataCombustibles['REGION']);
                    $objPHPExcel->getActiveSheet()->setCellValue('AA'.$row, $dataCombustibles['TIPO_IMPUESTO']);
                    $objPHPExcel->getActiveSheet()->setCellValue('AB'.$row, $dataCombustibles['TIPO_OPERACION']);
                    $objPHPExcel->getActiveSheet()->setCellValue('AC'.$row, $dataCombustibles['GUIA_REMISION']);
                    $objPHPExcel->getActiveSheet()->setCellValue('AD'.$row, $dataCombustibles['NOMBRE_ALMACEN']);
                    $objPHPExcel->getActiveSheet()->setCellValue('AE'.$row, $dataCombustibles['MES']);
                    $objPHPExcel->getActiveSheet()->setCellValue('AF'.$row, $dataCombustibles['NRO_ANIO']);
                    $ruc = strlen(strval($dataCombustibles['RUC_DNI']));
                    $sector = ($ruc == 8 || $ruc == 11) ? "Nacional" : "Extranjero";
                    $objPHPExcel->getActiveSheet()->setCellValue('AG'.$row, $sector);
                    $row++;
                }
                //Fin de cuerpo (tabla)
            //CERRAR DATOS PARA MOSTRAR EN EXCEL
            error_log("Fin de creacion de Excel");

            /*
            //GENERACION EXCEL
            //componer nombre: biomont_TYPEREPORT_YYYYMMMDD_HHMMSS.xls
            $comp = date('Ymd_His');
            $filename='biomont_'.$typeRep.'_'.$comp.'.xls'; //save our workbook as this file name
            header('Content-Type: application/vnd.ms-excel'); //mime type
            header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
            header('Cache-Control: max-age=0'); //no cache

            //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
            //if you want to save it as .XLSX Excel 2007 format
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');  
            //force user to download the Excel file without writing it to server's HD
            $objWriter->save('php://output');
            //CERRAR GENERACION EXCEL
            //CERRAR GENERACION EXCEL
            */

            error_log("Inicio de guardado de Excel");
            //GENERACION EXCEL Y GUARDARLO EN CARPETA
            $comp = date('Ymd_His');
            $filename='biomont_'.$typeRep.'_'.$comp.'.xls'; //save our workbook as this file name
            $savePath = 'downloads/reportesVentas/'.$filename;
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');  
            $objWriter->save($savePath);
            //CERRAR GENERACION EXCEL Y GUARDARLO EN CARPETA
            error_log("Fin de guardado de Excel");

            // Construir el JSON con la ruta del archivo
            $response = array(
                'status' => 'success',
                'dataVentasCostos' => $dataVentasCostos,
                'dataCostos' => $dataCostos,
                'ruta' => $savePath
            );

            // Devolver el JSON como respuesta
            header('Content-Type: application/json; charset=UTF-8');
            echo json_encode($response);
        } else {
            echo 'No';
            //parametros vacios
            //error 404
        }
    }

    public function getReporteVentasCostosTest()
    {
        $objModel = $this->loadModel("reportesVentas");
        $dataVentasCostos = $objModel->getVentasCostosTest('01/01/2023', '30/05/2023');
    }

    public function getCostos($dataVentasCostos)
    {
        header('Access-Control-Allow-Origin: *');

        // Obtener datos
        $objModel = $this->loadModel("reportesVentas");
        $dataCostos = $objModel->getCostos();
        // Cerrar Obtener datos

        // Verificar datos
        // var_log($dataCostos);
        // die();

        // Formatear datos
        $dataCostosFormateado = array();
        foreach ($dataCostos as $key => $costos) {
            $anio     = TRIM(intval($costos['NRO_ANIO']));
            $mes      = TRIM(intval($costos['NRO_MES']));
            $almacen  = TRIM(intval($costos['ID_ALMACEN']));
            $articulo = TRIM($costos['CODIGO_ARTICULO']);

            $dataCostosFormateado['NRO_ANIO'][$anio]['NRO_MES'][$mes]['ID_ALMACEN'][$almacen]['CODIGO_ARTICULO'][$articulo] = $costos;
        }

        // Verificar datos
        // var_log($dataCostosFormateado);
        // die();

        // Verificar datos
        // echo "<pre>";
        // echo json_encode($dataCostosFormateado);
        // echo "</pre>";
        // die();

        // Obtener costos en array de ventas
        foreach ($dataVentasCostos as $key => $ventas) {
            $anio     = TRIM(intval($ventas['NRO_ANIO']));
            $mes      = TRIM(intval($ventas['NRO_MES']));
            $almacen  = TRIM(intval($ventas['ID_ALMACEN']));
            $articulo = TRIM($ventas['CODIGO']);

            $dataVentasCostos[$key]['COSTO_UNITARIO_ESTANDAR'] = NULL;
            if ( isset($dataCostosFormateado['NRO_ANIO'][$anio]['NRO_MES'][$mes]['ID_ALMACEN'][$almacen]['CODIGO_ARTICULO'][$articulo]['COSTO_ESTANDAR']) ) {
                $dataVentasCostos[$key]['COSTO_UNITARIO_ESTANDAR'] = $dataCostosFormateado['NRO_ANIO'][$anio]['NRO_MES'][$mes]['ID_ALMACEN'][$almacen]['CODIGO_ARTICULO'][$articulo]['COSTO_ESTANDAR'];
            }
        }

        // Retornar arrays
        $response = array(
            "dataVentasCostos" => $dataVentasCostos,
            "dataCostos" => $dataCostosFormateado,
        );
        return $response;
    }

    /**
     * Proyecto 6082 - Reporte Descuentos sobre VTAS
     * Desde: 01/06/2023
     * Hasta: 30/06/2023
     */
    public function getReporteDescuentoSobreVtas()
    {
        header('Access-Control-Allow-Origin: *');

        $input = json_decode(file_get_contents("php://input"), true);

        // Verificar datos
        // error_log('Verificar datos');
        // error_log(json_encode($input));
        // die();

        // Verificar datos
        // $response = array(
        //     'status' => 'error',
        //     'input' => $input
        // );
        // echo json_encode($response);
        // die();

        if ($input['paramsRequest']['dateBegin'] != null && $input['paramsRequest']['dateEnd'] != null) {
            ini_set('memory_limit', '-1');
            ini_set('max_execution_time', '-1');

            $dateBegin = $input['paramsRequest']['dateBegin'];
            $dateEnd = $input['paramsRequest']['dateEnd'];
            $mod = 'REPORTE_DESCUENTOS_VENTAS';
            $typeRep = 'reporteDescuentosVtas';
            $titleDocument = 'Reporte Descuentos sobre VTAS';

            /*Obtenemos fecha del año pasado*/
            $_dateBegin = explode('/', $dateBegin);
            $_dateBegin = $_dateBegin[0].'/'.$_dateBegin[1].'/'.($_dateBegin[2] - 1);
            $_dateEnd = explode('/', $dateEnd);
            $_dateEnd = $_dateEnd[0].'/'.$_dateEnd[1].'/'.($_dateEnd[2] - 1);
            /*Cerrar Obtenemos fecha del año pasado*/

            /*Obtenemos años*/
            $year = explode('/', $dateBegin);
            $year = $year[2];
            $_year = explode('/', $_dateBegin);
            $_year = $_year[2];
            /*Cerrar Obtenemos años*/

            /*Obtenemos fecha en formato correcto*/
            // $formatDateBegin = formatDate($dateBegin,4);
            // $formatDateEnd = formatDate($dateEnd,4);
            $formatDateBegin = $dateBegin;
            $formatDateEnd = $dateEnd;
            /*Cerrar Obtenemos fecha en formato correcto*/

            $this->getLibrary('PHPExcel/Classes/PHPExcel');
            $this->getLibrary('PHPExcel/Classes/PHPExcel/Reader/Excel5');
            $this->getLibrary('PHPExcel/Classes/PHPExcel/Reader/Excel2007');

            $objPHPExcel = new PHPExcel();

            // Obtener datos
            $objModel = $this->loadModel("reportesVentas");
            error_log("Inicio de consulta");
            $dataDescuentoVtas = $objModel->getDescuentoVtas($formatDateBegin, $formatDateEnd, $_dateBegin, $_dateEnd);
            error_log("Fin de consulta");

            error_log("Inicio de conversion UTF8");
            $dataDescuentoVtas = utf8_encode_recursive($dataDescuentoVtas);
            error_log("Fin de conversion UTF8");

            error_log("Inicio de formatear informacion");
            $response = $this->getDescuentoVtasAgrupadoDetallado($dataDescuentoVtas);
            $dataDescuentoVtas = $response['dataDescuentoVtas'];
            error_log("Fin de formatear informacion");
            // Cerrar Obtener datos

            $objPHPExcel->getProperties()
                                ->setCreator("Laboratorios Biomont") //Autor
                                ->setLastModifiedBy("Laboratorios Biomont") //Ultimo usuario que lo modificó
                                ->setTitle("Reporte Descuentos sobre VTAS") //Título
                                ->setSubject("Reporte Descuentos sobre VTAS") //Asunto
                                ->setDescription("Reporte Descuentos sobre VTAS") //Descripción
                                ->setKeywords("Reporte Descuentos sobre VTAS") //Etiquetas
                                ->setCategory("Reporte Excel"); //Categorías

            $objPHPExcel->setActiveSheetIndex(0);
            $objPHPExcel->getActiveSheet()->setTitle($titleDocument);
            $objPHPExcel->getActiveSheet()->setCellValue('A1', appName());
            $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(20);
            $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
            $objPHPExcel->getActiveSheet()->mergeCells('A1:F1');
            $objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(30);

            $objPHPExcel->getActiveSheet()->setCellValue('A3', 'Fecha');
            $objPHPExcel->getActiveSheet()->setCellValue('B3', $dateBegin);
            $objPHPExcel->getActiveSheet()->setCellValue('C3', $dateEnd);

            $objPHPExcel->getActiveSheet()->setCellValue('A4', 'Reporte');
            $objPHPExcel->getActiveSheet()->setCellValue('B4', $titleDocument);
            
            $row = 7;

            //Formatemos tamaño de columnas
            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth('15'); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth('15'); //16
            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth('15'); //16
            $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth('15'); //16
            $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth('15'); //16
            $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth('15'); //16
            $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth('15'); //16
            $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth('15'); //16           
            $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth('2'); //16  
            $objPHPExcel->getActiveSheet()->getStyle('E:M')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            //Cerrar Formateamos tamaño de columnas            

            error_log("Inicio de creacion de Excel");
            //DATOS PARA MOSTRAR EN EXCEL
                //Inicio de cabecera (tabla)
                $row--;
                $objPHPExcel->getActiveSheet()->setCellValue('E'.$row, $year);
                $objPHPExcel->getActiveSheet()->setCellValue('J'.$row, $_year);

                $objPHPExcel->getActiveSheet()->getStyle('A'.$row.':M'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                
                $objPHPExcel->getActiveSheet()->getStyle('A'.$row.':M'.$row)->applyFromArray(
                    array(
                        'font' => array(
                            'bold'  => true,
                        )
                    )
                );
                $row++;
                //Fin de cabecera (tabla)

                //Inicio de cabecera (tabla)
                $objPHPExcel->getActiveSheet()->setCellValue('A'.$row, 'MES');
                $objPHPExcel->getActiveSheet()->setCellValue('B'.$row, 'CLIENTE');
                $objPHPExcel->getActiveSheet()->setCellValue('C'.$row, 'DOC');
                $objPHPExcel->getActiveSheet()->setCellValue('D'.$row, 'Nº DOC');
                $objPHPExcel->getActiveSheet()->setCellValue('E'.$row, 'VENTA');
                $objPHPExcel->getActiveSheet()->setCellValue('F'.$row, 'DSCTO');
                $objPHPExcel->getActiveSheet()->setCellValue('G'.$row, 'V.NETA');
                $objPHPExcel->getActiveSheet()->setCellValue('H'.$row, '% Dscto');
                $objPHPExcel->getActiveSheet()->setCellValue('I'.$row, '');
                $objPHPExcel->getActiveSheet()->setCellValue('J'.$row, 'VENTA');
                $objPHPExcel->getActiveSheet()->setCellValue('K'.$row, 'DSCTO');
                $objPHPExcel->getActiveSheet()->setCellValue('L'.$row, 'V. NETA');
                $objPHPExcel->getActiveSheet()->setCellValue('M'.$row, '% Dscto');

                $objPHPExcel->getActiveSheet()->getStyle('A'.$row.':M'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);               
                
                $objPHPExcel->getActiveSheet()->getRowDimension($row)->setRowHeight(16);
                $objPHPExcel->getActiveSheet()->getStyle('A'.$row.':M'.$row)->applyFromArray(
                    array(
                        'fill' => array(
                            'type' => PHPExcel_Style_Fill::FILL_SOLID,
                            'color' => array('rgb' => '337ab7')
                        ),
                        'font' => array(
                            'bold'  => true,
                            'color' => array('rgb' => 'FFFFFF'),
                            'size'  => 12,
                            //'name'  => 'Verdana'
                        )
                    )
                );              
                $row++;                             
                //Fin de cabecera (tabla)

                //Ajustar el ancho de las columnas automáticamente
                // $hoja = $objPHPExcel->getActiveSheet();
                // foreach ($hoja->getColumnIterator() as $columna) {
                //     $hoja->getColumnDimension($columna->getColumnIndex())->setAutoSize(true);
                // }
                //Fin Ajustar el ancho de las columnas automáticamente

                //Inicio de cuerpo (tabla)    
                foreach ($dataDescuentoVtas['ANIO'][$year]['MES'] as $keymeses => $meses) {
                    foreach ($meses as $keyventas => $ventas) {
                        
                        if ($keyventas == 'TOTALES') {
                            // Obtenemos informacion por meses - Año actual
                            $importe_bruto_soles = $ventas['IMPORTE_BRUTO_SOLES'];
                            $descuento           = $ventas['DESCUENTO'];
                            $importe_neto_soles  = $ventas['IMPORTE_NETO_SOLES'];
                            if ($importe_bruto_soles == 0) {
                                $porcentaje = 0;
                            } else {
                                $porcentaje = round( ($descuento * 100) / $importe_bruto_soles, 0 );
                            }                            

                            // Totales por meses - Año actual
                            $objPHPExcel->getActiveSheet()->setCellValue('A'.$row, $keymeses);
                            $objPHPExcel->getActiveSheet()->setCellValue('B'.$row, '');
                            $objPHPExcel->getActiveSheet()->setCellValue('C'.$row, '');
                            $objPHPExcel->getActiveSheet()->setCellValue('D'.$row, '');
                            $objPHPExcel->getActiveSheet()->setCellValue('E'.$row, number_format($importe_bruto_soles, 0, '.', ','));
                            $objPHPExcel->getActiveSheet()->getStyle('E'.$row)->getNumberFormat()->setFormatCode('0');
                            $objPHPExcel->getActiveSheet()->setCellValue('F'.$row, number_format($descuento, 0, '.', ','));
                            $objPHPExcel->getActiveSheet()->getStyle('F'.$row)->getNumberFormat()->setFormatCode('0');
                            $objPHPExcel->getActiveSheet()->setCellValue('G'.$row, number_format($importe_neto_soles, 0, '.', ','));
                            $objPHPExcel->getActiveSheet()->getStyle('G'.$row)->getNumberFormat()->setFormatCode('0');
                            $objPHPExcel->getActiveSheet()->setCellValue('H'.$row, $porcentaje.'%');
                            $objPHPExcel->getActiveSheet()->getStyle('H'.$row)->getNumberFormat()->setFormatCode('0');
                               
                            // Obtenemos informacion por meses - Año anterior
                            $_importe_bruto_soles = $dataDescuentoVtas['ANIO'][$_year]['MES'][$keymeses]['TOTALES']['IMPORTE_BRUTO_SOLES'];
                            $_descuento           = $dataDescuentoVtas['ANIO'][$_year]['MES'][$keymeses]['TOTALES']['DESCUENTO'];
                            $_importe_neto_soles  = $dataDescuentoVtas['ANIO'][$_year]['MES'][$keymeses]['TOTALES']['IMPORTE_NETO_SOLES'];
                            if ($_importe_bruto_soles == 0) {
                                $_porcentaje = 0;
                            } else {
                                $_porcentaje = round( ($_descuento * 100) / $_importe_bruto_soles, 0 );
                            }

                            // Totales por meses - Año anterior
                            $objPHPExcel->getActiveSheet()->setCellValue('I'.$row, '');
                            $objPHPExcel->getActiveSheet()->setCellValue('J'.$row, number_format($_importe_bruto_soles, 0, '.', ','));
                            $objPHPExcel->getActiveSheet()->getStyle('J'.$row)->getNumberFormat()->setFormatCode('0');
                            $objPHPExcel->getActiveSheet()->setCellValue('K'.$row, number_format($_descuento, 0, '.', ','));
                            $objPHPExcel->getActiveSheet()->getStyle('K'.$row)->getNumberFormat()->setFormatCode('0');
                            $objPHPExcel->getActiveSheet()->setCellValue('L'.$row, number_format($_importe_neto_soles, 0, '.', ','));
                            $objPHPExcel->getActiveSheet()->getStyle('L'.$row)->getNumberFormat()->setFormatCode('0');
                            $objPHPExcel->getActiveSheet()->setCellValue('M'.$row, $_porcentaje.'%');
                            $objPHPExcel->getActiveSheet()->getStyle('M'.$row)->getNumberFormat()->setFormatCode('0');
                        
                            // Establecer un estilo para la fila (fila de detalle)
                            $detailRowStyle = array(
                                'font' => array('bold' => true),
                            );
                            $objPHPExcel->getActiveSheet()->getStyle('A'.$row.':M'.$row)->applyFromArray($detailRowStyle);
                            $row++;
                        }

                        if ($keyventas == 'DETALLE') {
                            foreach ($ventas as $keyfacturas => $facturas) {
                                foreach ($facturas as $keyfacturasdetalle => $facturasdetalle) {
                                    if ($facturasdetalle['IMPORTE_BRUTO_SOLES'] == 0) {
                                        $porcentaje_detalle = 0;
                                    } else {
                                        $porcentaje_detalle = round( ($facturasdetalle['DESCUENTO'] * 100) / $facturasdetalle['IMPORTE_BRUTO_SOLES'], 0 ); 
                                    }

                                    $objPHPExcel->getActiveSheet()->setCellValue('A'.$row, '');
                                    $objPHPExcel->getActiveSheet()->setCellValue('B'.$row, $facturasdetalle['NOMBRE_CLIENTE']);
                                    $objPHPExcel->getActiveSheet()->setCellValue('C'.$row, $facturasdetalle['TIPO_DOC']);
                                    $objPHPExcel->getActiveSheet()->setCellValue('D'.$row, $facturasdetalle['NRO_DOC']);
                                    $objPHPExcel->getActiveSheet()->setCellValue('E'.$row, number_format($facturasdetalle['IMPORTE_BRUTO_SOLES'], 2, '.', ','));
                                    $objPHPExcel->getActiveSheet()->getStyle('E'.$row)->getNumberFormat()->setFormatCode('0.00');
                                    $objPHPExcel->getActiveSheet()->setCellValue('F'.$row, number_format($facturasdetalle['DESCUENTO'], 2, '.', ','));
                                    $objPHPExcel->getActiveSheet()->getStyle('F'.$row)->getNumberFormat()->setFormatCode('0.00');
                                    $objPHPExcel->getActiveSheet()->setCellValue('G'.$row, number_format($facturasdetalle['IMPORTE_NETO_SOLES'], 2, '.', ','));
                                    $objPHPExcel->getActiveSheet()->getStyle('G'.$row)->getNumberFormat()->setFormatCode('0.00');
                                    $objPHPExcel->getActiveSheet()->setCellValue('H'.$row, $porcentaje_detalle.'%');
                                    $objPHPExcel->getActiveSheet()->getStyle('H'.$row)->getNumberFormat()->setFormatCode('0.00');

                                    $objPHPExcel->getActiveSheet()->getRowDimension($row)->setOutlineLevel(1);
                                    $objPHPExcel->getActiveSheet()->getRowDimension($row)->setVisible(false);
                                    $objPHPExcel->getActiveSheet()->getRowDimension($row)->setCollapsed(true);
                                    $row++;
                                }
                            }
                        }
                    }
                }
                //Fin de cuerpo (tabla)

                //Inicio pie (tabla)
                $totales  = $dataDescuentoVtas['ANIO'][$year]['TOTALES'];
                $_totales = $dataDescuentoVtas['ANIO'][$_year]['TOTALES'];
                if ($totales['IMPORTE_BRUTO_SOLES'] == 0) {
                    $porcentaje_total = 0;
                } else {
                    $porcentaje_total = round( ($totales['DESCUENTO'] * 100) / $totales['IMPORTE_BRUTO_SOLES'], 0 );
                }
                if ($_totales['IMPORTE_BRUTO_SOLES'] == 0) {
                    $_porcentaje_total = 0;
                } else {
                    $_porcentaje_total = round( ($_totales['DESCUENTO'] * 100) / $_totales['IMPORTE_BRUTO_SOLES'], 0 );
                }                                

                $objPHPExcel->getActiveSheet()->setCellValue('A'.$row, 'Total general');
                $objPHPExcel->getActiveSheet()->setCellValue('B'.$row, '');
                $objPHPExcel->getActiveSheet()->setCellValue('C'.$row, '');
                $objPHPExcel->getActiveSheet()->setCellValue('D'.$row, '');
                $objPHPExcel->getActiveSheet()->setCellValue('E'.$row, number_format($totales['IMPORTE_BRUTO_SOLES'], 0, '.', ','));
                $objPHPExcel->getActiveSheet()->getStyle('E'.$row)->getNumberFormat()->setFormatCode('0');
                $objPHPExcel->getActiveSheet()->setCellValue('F'.$row, number_format($totales['DESCUENTO'], 0, '.', ','));
                $objPHPExcel->getActiveSheet()->getStyle('F'.$row)->getNumberFormat()->setFormatCode('0');
                $objPHPExcel->getActiveSheet()->setCellValue('G'.$row, number_format($totales['IMPORTE_NETO_SOLES'], 0, '.', ','));
                $objPHPExcel->getActiveSheet()->getStyle('G'.$row)->getNumberFormat()->setFormatCode('0');
                $objPHPExcel->getActiveSheet()->setCellValue('H'.$row, $porcentaje_total.'%');
                $objPHPExcel->getActiveSheet()->getStyle('H'.$row)->getNumberFormat()->setFormatCode('0');

                $objPHPExcel->getActiveSheet()->setCellValue('I'.$row, '');
                $objPHPExcel->getActiveSheet()->setCellValue('J'.$row, number_format($_totales['IMPORTE_BRUTO_SOLES'], 0, '.', ','));
                $objPHPExcel->getActiveSheet()->getStyle('J'.$row)->getNumberFormat()->setFormatCode('0');
                $objPHPExcel->getActiveSheet()->setCellValue('K'.$row, number_format($_totales['DESCUENTO'], 0, '.', ','));
                $objPHPExcel->getActiveSheet()->getStyle('K'.$row)->getNumberFormat()->setFormatCode('0');
                $objPHPExcel->getActiveSheet()->setCellValue('L'.$row, number_format($_totales['IMPORTE_NETO_SOLES'], 0, '.', ','));
                $objPHPExcel->getActiveSheet()->getStyle('L'.$row)->getNumberFormat()->setFormatCode('0');
                $objPHPExcel->getActiveSheet()->setCellValue('M'.$row, $_porcentaje_total.'%');
                $objPHPExcel->getActiveSheet()->getStyle('M'.$row)->getNumberFormat()->setFormatCode('0');
                
                $objPHPExcel->getActiveSheet()->getStyle('A'.$row.':M'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);               
                
                $objPHPExcel->getActiveSheet()->getRowDimension($row)->setRowHeight(16);
                $objPHPExcel->getActiveSheet()->getStyle('A'.$row.':M'.$row)->applyFromArray(
                    array(
                        'fill' => array(
                            'type' => PHPExcel_Style_Fill::FILL_SOLID,
                            'color' => array('rgb' => '337ab7')
                        ),
                        'font' => array(
                            'bold'  => true,
                            'color' => array('rgb' => 'FFFFFF'),
                            'size'  => 12,
                            //'name'  => 'Verdana'
                        )
                    )
                );              
                $row++;                
                //Fin de pie (tabla)

                //Inicio prueba
                // $objPHPExcel->getActiveSheet()->setCellValue('A'.$row, 'Cabecera 1');
                // $objPHPExcel->getActiveSheet()->setCellValue('B'.$row, 'Cabecera 1');
                // $objPHPExcel->getActiveSheet()->setCellValue('C'.$row, 'Cabecera 1');
                // $objPHPExcel->getActiveSheet()->setCellValue('D'.$row, 'Cabecera 1');
                // $objPHPExcel->getActiveSheet()->setCellValue('E'.$row, 'Cabecera 1');
                // $objPHPExcel->getActiveSheet()->setCellValue('F'.$row, 'Cabecera 1');
                // $objPHPExcel->getActiveSheet()->setCellValue('G'.$row, 'Cabecera 1');
                // $objPHPExcel->getActiveSheet()->setCellValue('H'.$row, 'Cabecera 1');
                // $row++;
                
                // $objPHPExcel->getActiveSheet()->setCellValue('A'.$row, 'Detalle 1');
                // $objPHPExcel->getActiveSheet()->setCellValue('B'.$row, 'Detalle 2');
                // $objPHPExcel->getActiveSheet()->setCellValue('C'.$row, 'Detalle 3');
                // $objPHPExcel->getActiveSheet()->setCellValue('D'.$row, 'Detalle 4');
                // $objPHPExcel->getActiveSheet()->setCellValue('E'.$row, 'Detalle 5');
                // $objPHPExcel->getActiveSheet()->setCellValue('F'.$row, 'Detalle 6');
                // $objPHPExcel->getActiveSheet()->setCellValue('G'.$row, 'Detalle 7');
                // $objPHPExcel->getActiveSheet()->setCellValue('H'.$row, 'Detalle 8');
                //     // Establecer un estilo para la fila (fila de detalle)
                //     $detailRowStyle = array(
                //         'font' => array('bold' => true),
                //         'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'startcolor' => array('rgb' => 'C6E0B4'))
                //     );
                //     $objPHPExcel->getActiveSheet()->getStyle('A'.$row.':H'.$row)->applyFromArray($detailRowStyle);
                //     // Agrupar fila para que se pueda expandir
                //     $objPHPExcel->getActiveSheet()->getRowDimension($row)->setOutlineLevel(1);
                //     $objPHPExcel->getActiveSheet()->getRowDimension($row)->setVisible(false); // Ocultar la fila
                //     $objPHPExcel->getActiveSheet()->getRowDimension($row)->setCollapsed(true); // Colapsar la fila
                //     $row++;

                // $objPHPExcel->getActiveSheet()->setCellValue('A'.$row, 'Detalle 1');
                // $objPHPExcel->getActiveSheet()->setCellValue('B'.$row, 'Detalle 2');
                // $objPHPExcel->getActiveSheet()->setCellValue('C'.$row, 'Detalle 3');
                // $objPHPExcel->getActiveSheet()->setCellValue('D'.$row, 'Detalle 4');
                // $objPHPExcel->getActiveSheet()->setCellValue('E'.$row, 'Detalle 5');
                // $objPHPExcel->getActiveSheet()->setCellValue('F'.$row, 'Detalle 6');
                // $objPHPExcel->getActiveSheet()->setCellValue('G'.$row, 'Detalle 7');
                // $objPHPExcel->getActiveSheet()->setCellValue('H'.$row, 'Detalle 8');
                //     // Establecer un estilo para la fila (fila de detalle)
                //     $detailRowStyle = array(
                //         'font' => array('bold' => true),
                //         'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'startcolor' => array('rgb' => 'C6E0B4'))
                //     );
                //     $objPHPExcel->getActiveSheet()->getStyle('A'.$row.':H'.$row)->applyFromArray($detailRowStyle);
                //     // Agrupar fila para que se pueda expandir
                //     $objPHPExcel->getActiveSheet()->getRowDimension($row)->setOutlineLevel(1);
                //     $objPHPExcel->getActiveSheet()->getRowDimension($row)->setVisible(false); // Ocultar la fila
                //     $objPHPExcel->getActiveSheet()->getRowDimension($row)->setCollapsed(true); // Colapsar la fila
                //     $row++;

                // $objPHPExcel->getActiveSheet()->setCellValue('A'.$row, 'Cabecera 2');
                // $objPHPExcel->getActiveSheet()->setCellValue('B'.$row, 'Cabecera 2');
                // $objPHPExcel->getActiveSheet()->setCellValue('C'.$row, 'Cabecera 2');
                // $objPHPExcel->getActiveSheet()->setCellValue('D'.$row, 'Cabecera 2');
                // $objPHPExcel->getActiveSheet()->setCellValue('E'.$row, 'Cabecera 2');
                // $objPHPExcel->getActiveSheet()->setCellValue('F'.$row, 'Cabecera 2');
                // $objPHPExcel->getActiveSheet()->setCellValue('G'.$row, 'Cabecera 2');
                // $objPHPExcel->getActiveSheet()->setCellValue('H'.$row, 'Cabecera 2');
                // $row++;

                // $objPHPExcel->getActiveSheet()->setCellValue('A'.$row, 'Detalle 1');
                // $objPHPExcel->getActiveSheet()->setCellValue('B'.$row, 'Detalle 2');
                // $objPHPExcel->getActiveSheet()->setCellValue('C'.$row, 'Detalle 3');
                // $objPHPExcel->getActiveSheet()->setCellValue('D'.$row, 'Detalle 4');
                // $objPHPExcel->getActiveSheet()->setCellValue('E'.$row, 'Detalle 5');
                // $objPHPExcel->getActiveSheet()->setCellValue('F'.$row, 'Detalle 6');
                // $objPHPExcel->getActiveSheet()->setCellValue('G'.$row, 'Detalle 7');
                // $objPHPExcel->getActiveSheet()->setCellValue('H'.$row, 'Detalle 8');
                //     // Establecer un estilo para la fila (fila de detalle)
                //     $detailRowStyle = array(
                //         'font' => array('bold' => true),
                //         'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'startcolor' => array('rgb' => 'C6E0B4'))
                //     );
                //     $objPHPExcel->getActiveSheet()->getStyle('A'.$row.':H'.$row)->applyFromArray($detailRowStyle);
                //     // Agrupar fila para que se pueda expandir
                //     $objPHPExcel->getActiveSheet()->getRowDimension($row)->setOutlineLevel(1);
                //     $objPHPExcel->getActiveSheet()->getRowDimension($row)->setVisible(false); // Ocultar la fila
                //     $objPHPExcel->getActiveSheet()->getRowDimension($row)->setCollapsed(true); // Colapsar la fila
                //     $row++;

                // $objPHPExcel->getActiveSheet()->setCellValue('A'.$row, 'Detalle 1');
                // $objPHPExcel->getActiveSheet()->setCellValue('B'.$row, 'Detalle 2');
                // $objPHPExcel->getActiveSheet()->setCellValue('C'.$row, 'Detalle 3');
                // $objPHPExcel->getActiveSheet()->setCellValue('D'.$row, 'Detalle 4');
                // $objPHPExcel->getActiveSheet()->setCellValue('E'.$row, 'Detalle 5');
                // $objPHPExcel->getActiveSheet()->setCellValue('F'.$row, 'Detalle 6');
                // $objPHPExcel->getActiveSheet()->setCellValue('G'.$row, 'Detalle 7');
                // $objPHPExcel->getActiveSheet()->setCellValue('H'.$row, 'Detalle 8');
                //     // Establecer un estilo para la fila (fila de detalle)
                //     $detailRowStyle = array(
                //         'font' => array('bold' => true),
                //         'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'startcolor' => array('rgb' => 'C6E0B4'))
                //     );
                //     $objPHPExcel->getActiveSheet()->getStyle('A'.$row.':H'.$row)->applyFromArray($detailRowStyle);
                //     // Agrupar fila para que se pueda expandir
                //     $objPHPExcel->getActiveSheet()->getRowDimension($row)->setOutlineLevel(1);
                //     $objPHPExcel->getActiveSheet()->getRowDimension($row)->setVisible(false); // Ocultar la fila
                //     $objPHPExcel->getActiveSheet()->getRowDimension($row)->setCollapsed(true); // Colapsar la fila
                //     $row++;
                //Fin prueba
            //CERRAR DATOS PARA MOSTRAR EN EXCEL
            error_log("Fin de creacion de Excel");

            /*
            //GENERACION EXCEL
            //componer nombre: biomont_TYPEREPORT_YYYYMMMDD_HHMMSS.xls
            $comp = date('Ymd_His');
            $filename='biomont_'.$typeRep.'_'.$comp.'.xls'; //save our workbook as this file name
            header('Content-Type: application/vnd.ms-excel'); //mime type
            header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
            header('Cache-Control: max-age=0'); //no cache

            //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
            //if you want to save it as .XLSX Excel 2007 format
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');  
            //force user to download the Excel file without writing it to server's HD
            $objWriter->save('php://output');
            //CERRAR GENERACION EXCEL
            //CERRAR GENERACION EXCEL
            */

            error_log("Inicio de guardado de Excel");
            //GENERACION EXCEL Y GUARDARLO EN CARPETA
            $comp = date('Ymd_His');
            $filename='biomont_'.$typeRep.'_'.$comp.'.xls'; //save our workbook as this file name
            $savePath = 'downloads/reportesVentas/'.$filename;
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');  
            $objWriter->save($savePath);
            //CERRAR GENERACION EXCEL Y GUARDARLO EN CARPETA
            error_log("Fin de guardado de Excel");

            // Construir el JSON con la ruta del archivo
            $response = array(
                'status' => 'success',
                'dataDescuentoVtas' => $dataDescuentoVtas,
                'ruta' => $savePath
            );

            // Devolver el JSON como respuesta
            header('Content-Type: application/json; charset=UTF-8');
            echo json_encode($response);
        } else {
            echo 'No';
            //parametros vacios
            //error 404
        }
    }

    public function getDescuentoVtasAgrupadoDetallado($dataDescuentoVtas)
    {
        $dataDescuentoVtasFormateado = array();

        // Obtenemos descuento
        foreach ($dataDescuentoVtas as $key => $ventas) {            
            $tipo_doc = TRIM($ventas['TIPO_DOC']);
            $codigo   = TRIM($ventas['CODIGO']);

            if ($tipo_doc == "Nota de credito" && ($codigo == "VAR0000003" || $codigo == "VAR0000013" || $codigo == "VAR0000067" || $codigo == "VAR0000008")) {
                $dataDescuentoVtas[$key]['DESCUENTO'] = ABS($ventas['IMPORTE_BRUTO_SOLES']);
                $dataDescuentoVtas[$key]['IMPORTE_BRUTO_SOLES'] = 0;
            } else {
                $dataDescuentoVtas[$key]['DESCUENTO'] = $ventas['IMPORTE_BRUTO_SOLES'] - $ventas['IMPORTE_NETO_SOLES'];
            }
        }

        // Formateamos array
        foreach ($dataDescuentoVtas as $key => $ventas) {
            $anio    = TRIM($ventas['ANIO']);
            $mes     = TRIM($ventas['MES']);
            $nro_doc = TRIM($ventas['NRO_DOC']);

            // Totales por meses
            $dataDescuentoVtasFormateado['ANIO'][$anio]['MES'][$mes]['TOTALES']['IMPORTE_BRUTO_SOLES'] += $ventas['IMPORTE_BRUTO_SOLES'];
            $dataDescuentoVtasFormateado['ANIO'][$anio]['MES'][$mes]['TOTALES']['DESCUENTO']           += $ventas['DESCUENTO'];
            $dataDescuentoVtasFormateado['ANIO'][$anio]['MES'][$mes]['TOTALES']['IMPORTE_NETO_SOLES']  += $ventas['IMPORTE_NETO_SOLES'];

            // Detalle por meses
            $dataDescuentoVtasFormateado['ANIO'][$anio]['MES'][$mes]['DETALLE'][$nro_doc][] = $ventas;

            // Totales por año
            $dataDescuentoVtasFormateado['ANIO'][$anio]['TOTALES']['IMPORTE_BRUTO_SOLES'] += $ventas['IMPORTE_BRUTO_SOLES'];
            $dataDescuentoVtasFormateado['ANIO'][$anio]['TOTALES']['DESCUENTO']           += $ventas['DESCUENTO'];
            $dataDescuentoVtasFormateado['ANIO'][$anio]['TOTALES']['IMPORTE_NETO_SOLES']  += $ventas['IMPORTE_NETO_SOLES'];
        }        

        // Retornos array de descuentos sobre ventas
        $response = array(
            "dataDescuentoVtas" => $dataDescuentoVtasFormateado,
        );
        return $response;
    }

    /**
     * Proyecto 5187 - DATA DE MV IMPORTACIONES
     * Desde: 01/06/2023
     * Hasta: 30/06/2023
     */
    public function getReporteDamImportacionesDrawBack()
    {
        header('Access-Control-Allow-Origin: *');

        $input = json_decode(file_get_contents("php://input"), true);

        // Verificar datos
        // error_log('Verificar datos');
        // error_log(json_encode($input));
        // die();

        // Verificar datos
        // $response = array(
        //     'status' => 'error',
        //     'input' => $input
        // );
        // echo json_encode($response);
        // die();

        if ($input['paramsRequest']['dateBegin'] != null && $input['paramsRequest']['dateEnd'] != null) {
            ini_set('memory_limit', '-1');
            ini_set('max_execution_time', '-1');

            $dateBegin = $input['paramsRequest']['dateBegin'];
            $dateEnd = $input['paramsRequest']['dateEnd'];
            $codigo_articulo = $input['paramsRequest']['codigo_articulo'];
            $linea_articulo = $input['paramsRequest']['linea_articulo'];
            $mod = 'REPORTE_DAM_IMPORTACIONES_DRAWBACK';
            $typeRep = 'reporteDamImportacionesDrawback';
            $titleDocument = 'Reporte de DAM DE IMPORTACIONES';
            $titleDocument_ = 'Reporte de DAM DE IMPORTACIONES - DRAWBACK';

            /*Obtenemos fecha en formato correcto*/
            // $formatDateBegin = formatDate($dateBegin,4);
            // $formatDateEnd = formatDate($dateEnd,4);
            $formatDateBegin = $dateBegin;
            $formatDateEnd = $dateEnd;
            /*Cerrar Obtenemos fecha en formato correcto*/

            $this->getLibrary('PHPExcel/Classes/PHPExcel');
            $this->getLibrary('PHPExcel/Classes/PHPExcel/Reader/Excel5');
            $this->getLibrary('PHPExcel/Classes/PHPExcel/Reader/Excel2007');

            $objPHPExcel = new PHPExcel();

            // Obtener datos
            $objModel = $this->loadModel("reportesVentas");
            error_log("Inicio de consulta");
            $dataDamImportaciones = $objModel->getDamImportaciones($formatDateBegin, $formatDateEnd, $codigo_articulo, $linea_articulo);
            error_log("Fin de consulta");

            error_log("Inicio de conversion UTF8");
            $dataDamImportaciones = utf8_encode_recursive($dataDamImportaciones);
            error_log("Fin de conversion UTF8");

            error_log("Inicio de obtener porcentaje advalorem");
            $response = $this->getPorcentajeAdValorem($dataDamImportaciones);
            $dataDamImportaciones = $response['dataDamImportaciones'];
            $dataPorcentajeAdValorem = $response['dataPorcentajeAdValorem'];
            error_log("Fin de obtener porcentaje advalorem");
            // Cerrar Obtener datos

            $objPHPExcel->getProperties()
                                ->setCreator("Laboratorios Biomont") //Autor
                                ->setLastModifiedBy("Laboratorios Biomont") //Ultimo usuario que lo modificó
                                ->setTitle("Reporte de DAM DE IMPORTACIONES - DRAWBACK") //Título
                                ->setSubject("Reporte de DAM DE IMPORTACIONES - DRAWBACK") //Asunto
                                ->setDescription("Reporte de DAM DE IMPORTACIONES - DRAWBACK") //Descripción
                                ->setKeywords("Reporte de DAM DE IMPORTACIONES - DRAWBACK") //Etiquetas
                                ->setCategory("Reporte Excel"); //Categorías

            $objPHPExcel->setActiveSheetIndex(0);
            $objPHPExcel->getActiveSheet()->setTitle($titleDocument);
            $objPHPExcel->getActiveSheet()->setCellValue('A1', appName());
            $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(20);
            $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
            $objPHPExcel->getActiveSheet()->mergeCells('A1:F1');
            $objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(30);

            $objPHPExcel->getActiveSheet()->setCellValue('A3', 'Fechas');
            $objPHPExcel->getActiveSheet()->setCellValue('B3', $dateBegin);
            $objPHPExcel->getActiveSheet()->setCellValue('C3', $dateEnd);

            $objPHPExcel->getActiveSheet()->setCellValue('A4', 'Reporte');
            $objPHPExcel->getActiveSheet()->setCellValue('B4', $titleDocument_);
            
            $row = 7;

            //Formatemos tamaño de columnas
            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth('15'); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth('15'); //16
            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth('15'); //16
            $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth('15'); //16
            $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth('15'); //16
            $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth('15'); //16
            $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth('15'); //16
            $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth('15'); //16
            $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth('15'); //16
            $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth('15'); //16
            $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth('15'); //16
            $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth('15'); //16
            $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth('15'); //16
            $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth('15'); //16
            $objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth('15'); //16
            $objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth('15'); //16
            $objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setWidth('15'); //16
            $objPHPExcel->getActiveSheet()->getColumnDimension('R')->setWidth('15'); //16
            $objPHPExcel->getActiveSheet()->getColumnDimension('S')->setWidth('15'); //16
            $objPHPExcel->getActiveSheet()->getColumnDimension('T')->setWidth('15'); //16
            $objPHPExcel->getActiveSheet()->getColumnDimension('U')->setWidth('15'); //16
            $objPHPExcel->getActiveSheet()->getColumnDimension('V')->setWidth('15'); //16
            $objPHPExcel->getActiveSheet()->getColumnDimension('W')->setWidth('15'); //16
            $objPHPExcel->getActiveSheet()->getColumnDimension('X')->setWidth('15'); //16
            $objPHPExcel->getActiveSheet()->getColumnDimension('Y')->setWidth('15'); //16
            $objPHPExcel->getActiveSheet()->getColumnDimension('Z')->setWidth('15'); //16
            $objPHPExcel->getActiveSheet()->getColumnDimension('AA')->setWidth('15'); //16
            $objPHPExcel->getActiveSheet()->getStyle('A:B')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $objPHPExcel->getActiveSheet()->getStyle('D:P')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $objPHPExcel->getActiveSheet()->getStyle('R:AA')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            //Cerrar Formateamos tamaño de columnas
            
            error_log("Inicio de creacion de Excel");
            //DATOS PARA MOSTRAR EN EXCEL
                //Inicio de cabecera (tabla)
                $objPHPExcel->getActiveSheet()->setCellValue('A'.$row, 'INSUMO');
                $objPHPExcel->getActiveSheet()->setCellValue('D'.$row, 'DAM DE IMPORTACIÓN');
                $objPHPExcel->getActiveSheet()->setCellValue('N'.$row, 'ORDEN DE COMPRA Y FACTURA');

                $objPHPExcel->getActiveSheet()->mergeCells('A'.$row.':C'.$row);
                $objPHPExcel->getActiveSheet()->mergeCells('D'.$row.':M'.$row);
                $objPHPExcel->getActiveSheet()->mergeCells('N'.$row.':AA'.$row);
                $objPHPExcel->getActiveSheet()->getStyle('A'.$row.':AA'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

                $objPHPExcel->getActiveSheet()->getRowDimension($row)->setRowHeight(20);
                $objPHPExcel->getActiveSheet()->getStyle('A'.$row.':AA'.$row)->applyFromArray(
                    array(
                        'fill' => array(
                            'type' => PHPExcel_Style_Fill::FILL_SOLID,
                            'color' => array('rgb' => '337ab7')
                        ),
                        'font' => array(
                            'bold'  => true,
                            'color' => array('rgb' => 'FFFFFF'),
                            'size'  => 12,
                            //'name'  => 'Verdana'
                        )
                    )
                );              
                $row++;

                //Inicio de cabecera (tabla)
                $objPHPExcel->getActiveSheet()->setCellValue('A'.$row, 'NRO REC');
                $objPHPExcel->getActiveSheet()->setCellValue('B'.$row, 'CODIGO');
                $objPHPExcel->getActiveSheet()->setCellValue('C'.$row, 'DESCRIPCIÓN');
                $objPHPExcel->getActiveSheet()->setCellValue('D'.$row, 'DUA');
                $objPHPExcel->getActiveSheet()->setCellValue('E'.$row, 'FECHA RECEPCION');
                $objPHPExcel->getActiveSheet()->setCellValue('F'.$row, 'CÓDIGO ADUANA');
                $objPHPExcel->getActiveSheet()->setCellValue('G'.$row, 'AÑO');
                $objPHPExcel->getActiveSheet()->setCellValue('H'.$row, 'NUMERO');
                $objPHPExcel->getActiveSheet()->setCellValue('I'.$row, 'SERIE');
                $objPHPExcel->getActiveSheet()->setCellValue('J'.$row, 'RÉGIMEN');
                $objPHPExcel->getActiveSheet()->setCellValue('K'.$row, 'FECHA NUMERACIÓN');
                $objPHPExcel->getActiveSheet()->setCellValue('L'.$row, 'SUBPARTIDA ARANCELARIA');
                $objPHPExcel->getActiveSheet()->setCellValue('M'.$row, '% ADVALOREM');
                $objPHPExcel->getActiveSheet()->setCellValue('N'.$row, 'ORIGEN');
                $objPHPExcel->getActiveSheet()->setCellValue('O'.$row, 'NRO O/C');
                $objPHPExcel->getActiveSheet()->setCellValue('P'.$row, 'RUC');
                $objPHPExcel->getActiveSheet()->setCellValue('Q'.$row, 'PROVEEDOR');
                $objPHPExcel->getActiveSheet()->setCellValue('R'.$row, 'FECHA FACTURA');
                $objPHPExcel->getActiveSheet()->setCellValue('S'.$row, 'FACTURA');
                $objPHPExcel->getActiveSheet()->setCellValue('T'.$row, 'MONEDA');
                $objPHPExcel->getActiveSheet()->setCellValue('U'.$row, 'T.C.');
                $objPHPExcel->getActiveSheet()->setCellValue('V'.$row, 'UNIDAD');
                $objPHPExcel->getActiveSheet()->setCellValue('W'.$row, 'CANTIDAD (FACTURA)');
                $objPHPExcel->getActiveSheet()->setCellValue('X'.$row, 'CANTIDAD (DAM)');
                $objPHPExcel->getActiveSheet()->setCellValue('Y'.$row, 'PRECIO');
                $objPHPExcel->getActiveSheet()->setCellValue('Z'.$row, 'TOTAL LINEA');
                $objPHPExcel->getActiveSheet()->setCellValue('AA'.$row, 'TOTAL FACTURA');

                $objPHPExcel->getActiveSheet()->getStyle('A'.$row.':AA'.$row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

                $objPHPExcel->getActiveSheet()->getRowDimension($row)->setRowHeight(16);
                $objPHPExcel->getActiveSheet()->getStyle('A'.$row.':AA'.$row)->applyFromArray(
                    array(
                        'fill' => array(
                            'type' => PHPExcel_Style_Fill::FILL_SOLID,
                            'color' => array('rgb' => '337ab7')
                        ),
                        'font' => array(
                            'bold'  => true,
                            'color' => array('rgb' => 'FFFFFF'),
                            'size'  => 12,
                            //'name'  => 'Verdana'
                        )
                    )
                );              
                $row++;                             
                $objPHPExcel->getActiveSheet()->freezePane('A'.$row);
                //Fin de cabecera (tabla)

                //Ajustar el ancho de las columnas automáticamente
                $hoja = $objPHPExcel->getActiveSheet();
                foreach ($hoja->getColumnIterator() as $columna) {
                    if ($columna->getColumnIndex() != 'B') { // No quiero que aplique autosize a la columna B
                        $hoja->getColumnDimension($columna->getColumnIndex())->setAutoSize(true);
                    }
                }
                //Fin Ajustar el ancho de las columnas automáticamente

                //Inicio de cuerpo (tabla)
                foreach ($dataDamImportaciones as $key => $importaciones) {
                    $objPHPExcel->getActiveSheet()->setCellValue('A'.$row, $importaciones['NRO_REC']);
                    $objPHPExcel->getActiveSheet()->setCellValue('B'.$row, $importaciones['CODIGO']);
                    $objPHPExcel->getActiveSheet()->setCellValue('C'.$row, $importaciones['DESCRIPCION']);
                    $objPHPExcel->getActiveSheet()->setCellValue('D'.$row, $importaciones['DUA']);
                    $objPHPExcel->getActiveSheet()->setCellValue('E'.$row, $importaciones['FECHA_RECEPCION']);
                    $objPHPExcel->getActiveSheet()->setCellValue('F'.$row, $importaciones['CODIGO_ADUANA']);
                    $objPHPExcel->getActiveSheet()->setCellValue('G'.$row, $importaciones['ANIO']);
                    $objPHPExcel->getActiveSheet()->setCellValue('H'.$row, $importaciones['NUMERO']);
                    $objPHPExcel->getActiveSheet()->setCellValue('I'.$row, $importaciones['SERIE']);
                    $objPHPExcel->getActiveSheet()->setCellValue('J'.$row, $importaciones['REGIMEN']);
                    $objPHPExcel->getActiveSheet()->setCellValue('K'.$row, $importaciones['FECHA_NUMERACION']);
                    $objPHPExcel->getActiveSheet()->setCellValue('L'.$row, $importaciones['SUBPARTIDA_ARANCELARIA']);
                    $objPHPExcel->getActiveSheet()->setCellValue('M'.$row, $importaciones['PORCENTAJE_AD_VALOREM']."%");
                    $objPHPExcel->getActiveSheet()->setCellValue('N'.$row, $importaciones['ORIGEN']);
                    $objPHPExcel->getActiveSheet()->setCellValue('O'.$row, $importaciones['NRO_ORDEN_COMPRA']);
                    $objPHPExcel->getActiveSheet()->setCellValue('P'.$row, $importaciones['RUC']);
                    $objPHPExcel->getActiveSheet()->setCellValue('Q'.$row, $importaciones['PROVEEDOR']);
                    $objPHPExcel->getActiveSheet()->setCellValue('R'.$row, $importaciones['FECHA_FACTURA']);
                    $objPHPExcel->getActiveSheet()->setCellValue('S'.$row, $importaciones['NRO_FACTURA']);
                    $objPHPExcel->getActiveSheet()->setCellValue('T'.$row, $importaciones['MONEDA']);
                    $objPHPExcel->getActiveSheet()->setCellValue('U'.$row, $importaciones['T_CAMBIO']);
                    $objPHPExcel->getActiveSheet()->setCellValue('V'.$row, $importaciones['UNIDAD']);

                    $cantidad_factura = $importaciones['CANTIDAD_FACTURA'];
                    $cantidad_dam     = $importaciones['CANTIDAD_DAM'];
                    $precio_linea     = $importaciones['PRECIO_LINEA'];
                    $total_linea      = $importaciones['TOTAL_LINEA'];
                    $total_factura    = $importaciones['TOTAL_FACTURA'];

                    $cantidad_factura = isset($cantidad_factura) ? number_format($cantidad_factura, 2, '.', ',') : $cantidad_factura;
                    $cantidad_dam     = isset($cantidad_dam)     ? number_format($cantidad_dam, 2, '.', ',')     : $cantidad_dam;
                    $precio_linea     = isset($precio_linea)     ? number_format($precio_linea, 2, '.', ',')     : $precio_linea;
                    $total_linea      = isset($total_linea)      ? number_format($total_linea, 2, '.', ',')      : $total_linea;
                    $total_factura    = isset($total_factura)    ? number_format($total_factura, 2, '.', ',')    : $total_factura;

                    $objPHPExcel->getActiveSheet()->setCellValue('W'.$row, $cantidad_factura);
                    $objPHPExcel->getActiveSheet()->setCellValue('X'.$row, $cantidad_dam);
                    $objPHPExcel->getActiveSheet()->setCellValue('Y'.$row, $precio_linea);
                    $objPHPExcel->getActiveSheet()->setCellValue('Z'.$row, $total_linea);
                    $objPHPExcel->getActiveSheet()->setCellValue('AA'.$row, $total_factura);
                    $row++;
                }
                //Fin de cuerpo (tabla)
            //CERRAR DATOS PARA MOSTRAR EN EXCEL
            error_log("Fin de creacion de Excel");

            /*
            //GENERACION EXCEL
            //componer nombre: biomont_TYPEREPORT_YYYYMMMDD_HHMMSS.xls
            $comp = date('Ymd_His');
            $filename='biomont_'.$typeRep.'_'.$comp.'.xls'; //save our workbook as this file name
            header('Content-Type: application/vnd.ms-excel'); //mime type
            header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
            header('Cache-Control: max-age=0'); //no cache

            //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
            //if you want to save it as .XLSX Excel 2007 format
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');  
            //force user to download the Excel file without writing it to server's HD
            $objWriter->save('php://output');
            //CERRAR GENERACION EXCEL
            //CERRAR GENERACION EXCEL
            */

            error_log("Inicio de guardado de Excel");
            //GENERACION EXCEL Y GUARDARLO EN CARPETA
            $comp = date('Ymd_His');
            $filename='biomont_'.$typeRep.'_'.$comp.'.xls'; //save our workbook as this file name
            $savePath = 'downloads/reportesVentas/'.$filename;
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');  
            $objWriter->save($savePath);
            //CERRAR GENERACION EXCEL Y GUARDARLO EN CARPETA
            error_log("Fin de guardado de Excel");

            // Construir el JSON con la ruta del archivo
            $response = array(
                'status' => 'success',
                'dataDamImportaciones' => $dataDamImportaciones,
                'dataPorcentajeAdValorem' => $dataPorcentajeAdValorem,
                'ruta' => $savePath
            );

            // Devolver el JSON como respuesta
            header('Content-Type: application/json; charset=UTF-8');
            echo json_encode($response);
        } else {
            echo 'No';
            //parametros vacios
            //error 404
        }
    }

    public function getPorcentajeAdValorem($dataDamImportaciones)
    {
        header('Access-Control-Allow-Origin: *');

        // Obtener datos
        $objModel = $this->loadModel("reportesVentas");
        $dataPorcentajeAdValorem = $objModel->getPorcentajeAdValorem();
        // Cerrar Obtener datos

        // Verificar datos
        // var_log($dataCostos);
        // die();

        // Formatear datos
        $dataPorcentajeAdValoremFormateado = array();
        foreach ($dataPorcentajeAdValorem as $key => $advalorem) {
            $id_subpartida         = TRIM($advalorem['ID_SUBPARTIDA']);
            $codigo_subpartida     = TRIM($advalorem['CODIGO_SUBPARTIDA']);
            $porcentaje_ad_valorem = TRIM($advalorem['PORCENTAJE_AD_VALOREM']);

            $dataPorcentajeAdValoremFormateado['CODIGO_SUBPARTIDA'][$codigo_subpartida] = $advalorem;
        }

        // Verificar datos
        // var_log($dataPorcentajeAdValoremFormateado);
        // die();

        // Verificar datos
        // echo "<pre>";
        // echo json_encode($dataPorcentajeAdValoremFormateado);
        // echo "</pre>";
        // die();
        
        // Obtener costos en array de ventas
        foreach ($dataDamImportaciones as $key => $importaciones) {
            $codigo_subpartida = TRIM($importaciones['SUBPARTIDA_ARANCELARIA']);

            $dataDamImportaciones[$key]['PORCENTAJE_AD_VALOREM'] = NULL;
            if ( isset($dataPorcentajeAdValoremFormateado['CODIGO_SUBPARTIDA'][$codigo_subpartida]) ) {
                $dataDamImportaciones[$key]['PORCENTAJE_AD_VALOREM'] = $dataPorcentajeAdValoremFormateado['CODIGO_SUBPARTIDA'][$codigo_subpartida]['PORCENTAJE_AD_VALOREM'];
            }
        }

        // Retornar arrays
        $response = array(
            "dataDamImportaciones" => $dataDamImportaciones,
            "dataPorcentajeAdValorem" => $dataPorcentajeAdValoremFormateado,
        );
        return $response;
    }

}
