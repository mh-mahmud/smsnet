<?php defined('BASEPATH') OR exit('No direct script access allowed');
/* 
 *  ============================================================================== 
 *  Author	: Mian Saleem
 *  Email	: saleem@tecdiary.com 
 *  For		: PHPExcel
 *  Web		: https://github.com/PHPOffice/PHPExcels
 *  License	: LGPL (GNU LESSER GENERAL PUBLIC LICENSE)
 *		: https://github.com/PHPOffice/PHPExcel/blob/master/license.md
 *  ============================================================================== 
 */
require_once APPPATH . "/third_party/PHPExcel/PHPExcel.php";

class Excel extends PHPExcel
{
	public $excelTitle = '';
	public $excelColumn = array();
	public $exceldata = array();
	
    public function __construct()
    {
        parent::__construct();
		
    }
	
	public function setExcelData($exceldata = array())
	{
	  if(count($exceldata)>0)
	  {
		$this->excelTitle = $exceldata['title'];
		$this->excelColumn = $exceldata['column'];
		$this->exceldata = $exceldata['data'];
		$this->setParam();
	   return $this;  
	  } else {
		  return 'No Param';
	  }		  
	}
	
	
	public function generate()
	{
	  $filename = 'xlsreport_'.$this->excelTitle .'_'. date('dmyhis') . '.xls'; //save our workbook as this file name
      header('Content-Type: application/vnd.ms-excel'); //mime type
      header('Content-Disposition: attachment;filename="' . $filename . '"'); //tell browser what's the file name
      header('Cache-Control: max-age=0'); //no cache
      $objWriter = PHPExcel_IOFactory::createWriter($this, 'Excel5');
      $objWriter->save('php://output');
	}
	
	public function setParam()
	{
	  //echo '<pre/>';
	  //print_r($this->exceldata); exit;
	  
	  $this->setActiveSheetIndex(0);
      
	  if($this->excelTitle):
		$this->getActiveSheet()->setTitle($this->excelTitle);
		$this->getActiveSheet()->setCellValue('A1', $this->excelTitle);
	  else:
		$this->getActiveSheet()->setTitle('No Title');  
		$this->getActiveSheet()->setCellValue('A1', 'No Title');
	  endif;
	  
	  if(count($this->excelColumn)>0):
		  $loopChar = 65;
		  foreach($this->excelColumn as $colLabel):
		   $this->getActiveSheet()->setCellValue(chr($loopChar).'2', $colLabel);
		   $loopChar++;
		  endforeach;
	  endif;	  
	  
      //merge cell A1 until C1
      $this->getActiveSheet()->mergeCells('A1:I1');
      //set aligment to center for that merged cell (A1 to C1)
      $this->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
      //make the font become bold
      $this->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
      $this->getActiveSheet()->getStyle('A1')->getFont()->setSize(16);
      $this->getActiveSheet()->getStyle('A1')->getFill()->getStartColor()->setARGB('#333');

	  $endCar = count($this->excelColumn) + 65;
	  //for ($col = ord('A'); $col <= ord('I'); $col++) {
      for ($col = ord('A'); $col <= ord(chr($endCar)); $col++) {
	     $this->getActiveSheet()->getColumnDimension(chr($col))->setAutoSize(true);
         $this->getActiveSheet()->getStyle(chr($col))->getFont()->setSize(12);
         $this->getActiveSheet()->getStyle(chr($col))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
      }

      if (!empty($this->exceldata)) {
         //Fill data
         $this->getActiveSheet()->fromArray($this->exceldata, null, 'A3');
		 
		    for ($col = ord('A'); $col <= ord(chr($endCar)); $col++) {
				$this->getActiveSheet()->getStyle(chr($col).'3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			}
      } else {
         $this->getActiveSheet()->setCellValue('A1', 'No Data');
         $this->getActiveSheet()->mergeCells('A1:I1');
      }
      
	}
	
	
	
}
