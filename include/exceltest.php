<?php
	require_once 'PHPExcel.php';
	require_once 'Excel5.php';
	
	// 创建一个处理对象实例
	$objExcel = new PHPExcel();
	
	// 创建文件格式写入对象实例, uncomment
	$objWriter = new PHPExcel_Writer_Excel5($objExcel);
	
	//设置文档基本属性/**似乎一般情况下用不到**/
	$objProps = $objExcel->getProperties();
	$objProps->setCreator("test1");
	$objProps->setLastModifiedBy("test11");
	$objProps->setTitle("test111");
	$objProps->setSubject("test1111");
	$objProps->setDescription("test11111");
	$objProps->setKeywords("test111111");
	$objProps->setCategory("test1111111");
	
	//*************************************       
	//设置当前的sheet索引，用于后续的内容操作。       
	//一般只有在使用多个sheet的时候才需要显示调用。       
	//缺省情况下，PHPExcel会自动创建第一个sheet被设置SheetIndex=0       
	$objExcel->setActiveSheetIndex(0);       
	$objActSheet = $objExcel->getActiveSheet();       
	      
	//设置当前活动sheet的名称       
	$objActSheet->setTitle('当前sheetname');   
	
	//设置宽度，这个值和EXCEL里的不同，不知道是什么单位，略小于EXCEL中的宽度   
	//$objActSheet->getColumnDimension('A')->setWidth(20); 
	//$objActSheet->getRowDimension(1)->setRowHeight(30);  //高度
	$objActSheet->getColumnDimension('D')->setWidth(50); 
	//设置单元格的值     
	$objActSheet->setCellValue('A1', '总标题显示');    
	//合并单元格   
	$objActSheet->mergeCells('A1:D1');    
	//设置样式   
	/*$objStyleA1 = $objActSheet->getStyle('A1');       
	$objStyleA1->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);   
	$objFontA1 = $objStyleA1->getFont();       
	$objFontA1->setName('宋体');       
	$objFontA1->setSize(18);     
	$objFontA1->setBold(true); */  

	//设置列居中对齐   
	//$objActSheet->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	//设置边框   
	//$objActSheet->getStyle('A2')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN ); 
	
	//设置表格标题栏内容
	$objActSheet->setCellValue('A2', '现所在单位');    
	$objActSheet->setCellValue('B2', '姓名');    
	$objActSheet->setCellValue('C2', '性别');    
	$objActSheet->setCellValue('D2', '身份证号码');    
	
	$n=3;
	//循环复制
	$objActSheet->setCellValue('A'.$n, "内容啊1");  
	$objActSheet->setCellValue('B'.$n, "内容啊2"); 
	$objActSheet->setCellValue('C'.$n, "内容啊3"); 
	$objActSheet->setCellValue('D'.$n, "内容啊4"); 
	
	//输出内容       
	$outputFileName =time().".xls";       
	//到文件       
	$objWriter->save($outputFileName);       
	//下面这个输出我是有个页面用Ajax接收返回的信息   
	//echo("<a href="tables/".$cancel_time."addminus.xls" mce_href="tables/".$cancel_time."addminus.xls" target='_blank'>点击下载电子表</a>");  
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	