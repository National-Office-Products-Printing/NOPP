<?php 
	
	$excelcompany = $company;
	$exceltitle = $lang['form-excel-title'];
	$excelsubject = $lang['form-excel-subject'];
	$exceldescription = $lang['form-excel-description'];
	$excelkeywords = $lang['form-excel-keywords'];
	$excelcategory = $lang['form-excel-category'];
	
	if($merchantsupport == true) {	
	
		$excel = new PHPExcel();
		$excel->getProperties()
		    ->setCreator($excelcompany)
			->setTitle($exceltitle)
			->setSubject($excelsubject)
			->setDescription($exceldescription)
			->setKeywords($excelkeywords)
			->setCategory($excelcategory);
		$excel->setActiveSheetIndex(0)
		    ->setCellValue('A1', $lang['form-excel-firstname'])
			->setCellValue('B1', $lang['form-excel-lastname'])
			->setCellValue('C1', $lang['form-excel-email'])
			->setCellValue('D1', $lang['form-excel-subject'])
			->setCellValue('E1', $lang['form-excel-message'])
			->setCellValue('F1', $lang['form-excel-ticket'])
			->setCellValue('G1', $lang['form-excel-newsletter'])
			->setCellValue('H1', $lang['form-excel-send-to-me'])
			->setCellValue('A2', $finalfirstname)
			->setCellValue('B2', $finallastname)
			->setCellValue('C2', $finalemail)
			->setCellValue('D2', $finalsubject)
			->setCellValue('E2', $finalmessage)
			->setCellValue('F2', $finalticket)
			->setCellValue('G2', $finalnewsletter)
			->setCellValue('H2', $finalsendtome);

		foreach(range('A', 'H') as $column) {
			$excel->getActiveSheet()->getColumnDimension($column)->setAutoSize(true);
		}
		
		$writer1 = PHPExcel_IOFactory::createWriter($excel, 'Excel5');
		$writer1->save('../upload/reports/excel/excel-'.$finalnumber1.'.xls');
		
		$writer2 = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
		$writer2->save('../upload/reports/excel/excel-'.$finalnumber1.'.xlsx');
		
	} elseif ($merchantservice == true) {
		
		$excel = new PHPExcel();
		$excel->getProperties()
			->setCreator($excelcompany)
			->setTitle($exceltitle)
			->setSubject($excelsubject)
			->setDescription($exceldescription)
			->setKeywords($excelkeywords)
			->setCategory($excelcategory);
			
		$excel->setActiveSheetIndex(0)
			->setCellValue('A1', $lang['form-excel-firstname'])
			->setCellValue('B1', $lang['form-excel-lastname'])
			->setCellValue('C1', $lang['form-excel-email'])
			->setCellValue('D1', $lang['form-excel-subject'])
			->setCellValue('E1', $lang['form-excel-message'])
			->setCellValue('F1', $lang['form-excel-service'])
			->setCellValue('G1', $lang['form-excel-price'])
			->setCellValue('H1', $lang['form-excel-ticket'])
			->setCellValue('I1', $lang['form-excel-newsletter'])
			->setCellValue('J1', $lang['form-excel-send-to-me'])
			->setCellValue('A2', $finalfirstname)
			->setCellValue('B2', $finallastname)
			->setCellValue('C2', $finalemail)
			->setCellValue('D2', $finalsubject)
			->setCellValue('E2', $finalmessage)
			->setCellValue('F2', $finalservices)
			->setCellValue('G2', $finalserviceprice)
			->setCellValue('H2', $finalticket)
			->setCellValue('I2', $finalnewsletter)
			->setCellValue('J2', $finalsendtome);
			

		foreach(range('A', 'J') as $column) {
			$excel->getActiveSheet()->getColumnDimension($column)->setAutoSize(true);
		}
		
		$writer1 = PHPExcel_IOFactory::createWriter($excel, 'Excel5');
		$writer1->save('../upload/reports/excel/excel-'.$finalnumber1.'.xls');
		
		$writer2 = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
		$writer2->save('../upload/reports/excel/excel-'.$finalnumber1.'.xlsx');
		
    } elseif($merchantpayment == true){
		
		if($onetimepayment == true){
					
			$excel = new PHPExcel();
			$excel->getProperties()
				->setCreator($excelcompany)
				->setTitle($exceltitle)
				->setSubject($excelsubject)
				->setDescription($exceldescription)
				->setKeywords($excelkeywords)
				->setCategory($excelcategory);
			$excel->setActiveSheetIndex(0)
				->setCellValue('A1', $lang['form-excel-firstname'])
				->setCellValue('B1', $lang['form-excel-lastname'])
				->setCellValue('C1', $lang['form-excel-email'])
				->setCellValue('D1', $lang['form-excel-subject'])
				->setCellValue('E1', $lang['form-excel-message'])
				->setCellValue('F1', $lang['form-excel-customer'])
				->setCellValue('G1', $lang['form-excel-service'])
				->setCellValue('H1', $lang['form-excel-price'])
				->setCellValue('I1', $lang['form-excel-ticket'])
				->setCellValue('J1', $lang['form-excel-method'])
				->setCellValue('K1', $lang['form-excel-newsletter'])
				->setCellValue('L1', $lang['form-excel-send-to-me'])
				->setCellValue('A2', $finalfirstname)
				->setCellValue('B2', $finallastname)
				->setCellValue('C2', $finalemail)
				->setCellValue('D2', $finalsubject)
				->setCellValue('E2', $finalmessage)
				->setCellValue('F2', $finalcustomerid)
				->setCellValue('G2', $finalpayments)
				->setCellValue('H2', $finalpaymentprice)
				->setCellValue('I2', $finalticket)
				->setCellValue('J2', $finalmethod)
				->setCellValue('K2', $finalnewsletter)
				->setCellValue('L2', $finalsendtome);
				
			foreach(range('A', 'L') as $column) {
				$excel->getActiveSheet()->getColumnDimension($column)->setAutoSize(true);
			}
			
			$writer1 = PHPExcel_IOFactory::createWriter($excel, 'Excel5');
			$writer1->save('../upload/reports/excel/excel-'.$finalnumber1.'.xls');
			
			$writer2 = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
			$writer2->save('../upload/reports/excel/excel-'.$finalnumber1.'.xlsx');
			
	    } else {
			
			$excel = new PHPExcel();
			$excel->getProperties()
				->setCreator($excelcompany)
				->setTitle($exceltitle)
				->setSubject($excelsubject)
				->setDescription($exceldescription)
				->setKeywords($excelkeywords)
				->setCategory($excelcategory);
			$excel->setActiveSheetIndex(0)
				->setCellValue('A1', $lang['form-excel-firstname'])
				->setCellValue('B1', $lang['form-excel-lastname'])
				->setCellValue('C1', $lang['form-excel-email'])
				->setCellValue('D1', $lang['form-excel-subject'])
				->setCellValue('E1', $lang['form-excel-message'])
				->setCellValue('F1', $lang['form-excel-customer'])
				->setCellValue('G1', $lang['form-excel-plan'])
				->setCellValue('H1', $lang['form-excel-price'])
				->setCellValue('I1', $lang['form-excel-ticket'])
				->setCellValue('J1', $lang['form-excel-method'])
				->setCellValue('K1', $lang['form-excel-newsletter'])
				->setCellValue('L1', $lang['form-excel-send-to-me'])
				->setCellValue('A2', $finalfirstname)
				->setCellValue('B2', $finallastname)
				->setCellValue('C2', $finalemail)
				->setCellValue('D2', $finalsubject)
				->setCellValue('E2', $finalmessage)
				->setCellValue('F2', $finalcustomerid)
				->setCellValue('G2', $finalrecurrings)
				->setCellValue('H2', $finalrecurringprice)
				->setCellValue('I2', $finalticket)
				->setCellValue('J2', $finalmethod)
				->setCellValue('K2', $finalnewsletter)
				->setCellValue('L2', $finalsendtome);
				
			foreach(range('A', 'L') as $column) {
				$excel->getActiveSheet()->getColumnDimension($column)->setAutoSize(true);
			}
			
			$writer1 = PHPExcel_IOFactory::createWriter($excel, 'Excel5');
			$writer1->save('../upload/reports/excel/excel-'.$finalnumber1.'.xls');
			
			$writer2 = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
			$writer2->save('../upload/reports/excel/excel-'.$finalnumber1.'.xlsx');
			
		}
			
	}

?>