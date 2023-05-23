<?php

class exportarOTxSemanaController extends Controller
{

	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		echo "Exportar OT por Semana de Produccion";
	}
	
	public function getOrdenesProduccionxSemana()
	{
		
		header('Access-Control-Allow-Origin: *');
		
		$input = json_decode(file_get_contents("php://input"), true);
		
		
		$this->getLibrary('PHPExcel/Classes/PHPExcel');
        $this->getLibrary('PHPExcel/Classes/PHPExcel/Reader/Excel5');
        $this->getLibrary('PHPExcel/Classes/PHPExcel/Reader/Excel2007');
		
		$objPHPExcel = new PHPExcel();
		
		$objModel = $this->loadModel("exportarOTxSemana");
		$resultado = $objModel->getOrdenesProduccionxSemana(intval($input['dato']['nroSem']));
		
		$objPHPExcel->getProperties()
							->setCreator("Laboratorios Biomont") //Autor
							->setLastModifiedBy("Laboratorios Biomont") //Ultimo usuario que lo modificó
							->setTitle("Reporte de OT")
							->setSubject("Reporte de OT") //Asunto
							->setDescription("Reporte de OT")//Descripción
							->setKeywords("Reporte de OT") //Etiquetas
                            ->setCategory("Reporte excel");  //Categorias
		
		$objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1','REPORTE DE OT: '.$resultado[0]['semProdEnsamblaje'].'  /  NRO: '.$resultado[0]['nroSemProdEnsamblaje'])
                    ->mergeCells('A1:O1');
					
		$objPHPExcel->getActiveSheet()->setShowGridlines(false);
		
		$estilos_titulo = array(
                'borders' => array(
                    'allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN), 
                )
        );

		$estilos_cabeceras = array(
        	'font' => array(
	        	'name'      => 'Verdana',
    	        'bold'      => true,
        	    'italic'    => false,
                'strike'    => false,
               	'size'      => 11,
	            'color'     => array('rgb' => '000000')
				
            ),
	       'fill' 	=> array(
							'type'		 => PHPExcel_Style_Fill::FILL_SOLID,
							'rotation'   => 90,
							'startcolor' => array('rgb' => 'eaecef'),
							'endcolor'   => array('rgb' => 'eaecef')
			),
            'borders' => array(
								'allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN)), 
								'alignment' =>  array(
													'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
													'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
													'rotation'   => 0,
													'wrap'       => TRUE
								)
			
        );
		
		$estilos_celdas = array(
        	'font' => array(
	        	'name'      => 'Verdana',
    	        'bold'      => false,
        	    'italic'    => false,
                'strike'    => false,
               	'size'      => 8,
	            'color'     => array('rgb' => '000000')
				
            ),
            'borders' => array(
								'allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN)), 
								'alignment' =>  array(
													'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_GENERAL,
													'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
													'rotation'   => 0,
													'wrap'       => FALSE
								)
			
        );
		
			
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A3',  "NRO OT");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B3',  "COD. ENSAMBLAJE");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C3',  "DESC. ENSAMBLAJE");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D3',  "CANTIDAD");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E3',  "FECHA");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F3',  "LOTE");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G3',  "F. CADUCIDAD");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H3',  "LINEA");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I3',  "TIPO ORDEN");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J3',  "EMITIDO");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K3',  "COD. ARTICULO");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('L3',  "DESC. ARTICULO");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('M3',  "RENDIMIENTO");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('N3',  "CANTIDAD");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('O3',  "UNIDAD");
		
		$i=4;
		foreach($resultado as $dato){		

			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.($i), $dato['NroOpe']);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.($i), $dato['codigoEnsamblaje']);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.($i), $dato['productoEnsamblaje']);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.($i), $dato['cantidadEnsamblaje']);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.($i), $dato['FCreacionEnsamblaje']);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.($i), $dato['LoteEnsamblaje']);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.($i), $dato['FexExpEnsamblaje']);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.($i), $dato['LineaEnsamblaje']);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.($i), $dato['tipoOTEnsamblaje']);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.($i), $dato['EmiNomApeEnsamblaje']);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('K'.($i), $dato['codProd']);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('L'.($i), $dato['descProducto']);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('M'.($i), $dato['rendimiento']);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('N'.($i), $dato['CantProd']);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('O'.($i), $dato['unidad']);
			
			$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':O'.$i)->applyFromArray($estilos_celdas);
			$objPHPExcel->getDefaultStyle()->getAlignment('A'.$i.':O'.$i)->setWrapText(true);

			$i++;
		}
		
		$objPHPExcel->getActiveSheet()->getStyle('A1:O1')->applyFromArray($estilos_cabeceras);
		$objPHPExcel->getActiveSheet()->getStyle('A3:O3')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('A3:O3')->applyFromArray($estilos_titulo);

		$sheet = $objPHPExcel->getActiveSheet();
		foreach($sheet->getColumnIterator() as $column){
			$sheet->getColumnDimension($column->getColumnIndex())->setAutosize(true);
		}
		
		$objPHPExcel->getActiveSheet()->setTitle('EXPORTAR OT');
		
		$objPHPExcel->setActiveSheetIndex(0);

		
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		
		$archivo = "EXPORT_".date('dmYHis').".xlsx";
		//$archivo = "downloads/EXPORT_".date('dmYHis').".xlsx";
		
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$archivo.'"');
		header('Cache-Control: max-age=0');
		header('Cache-Control: cache, must-revalidate');
		header('Pragma: public');

		ob_start();
        $objWriter->save("php://output");
		//$objWriter->save($archivo);
        $xlsData = ob_get_contents();
        ob_end_clean();

        $response =  [
					'msg' => 'ok',
					'file' => "data:application/vnd.ms-excel;base64,".base64_encode($xlsData),
					'nombre' => $archivo
				];
		
		
		header('Content-type: application/json; charset=utf-8');
		echo json_encode($response);
		
		/*if (file_exists($archivo)) {
			$msg="ok";
			$file=$archivo;
		} else {
			$msg="no";
			$file="";
		}
				
		header('Content-type: application/json; charset=utf-8');
		echo json_encode([
			"msg"=>$msg,
			"file"=>$file,
		]);*/

	}

}
