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

    public function getCostos($dataVentasCostos) {
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

        // Retornos array de ventas y array de costos
        $response = array(
            "dataVentasCostos" => $dataVentasCostos,
            "dataCostos" => $dataCostosFormateado,
        );
        return $response;
    }

}
