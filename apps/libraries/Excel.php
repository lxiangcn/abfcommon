<?php
defined ( 'BASEPATH' ) or die ( 'No direct script access allowed' );

/**
 * FileName : Excel.php
 * DateTime : 2015年10月30日
 *
 * Author : Alex Liu<lxiangcn@gmail.com>
 * Description :
 * Copyright (c) 2015 http://orzm.net All Rights Reserved.
 */

require_once APPPATH . DIRECTORY_SEPARATOR . 'third_party' . DIRECTORY_SEPARATOR . 'Excel' . DIRECTORY_SEPARATOR . 'PHPExcel.php';

class Excel extends PHPExcel {

	function __construct() {
		parent::__construct ();
		ini_set ( 'memory_limit', '1024M' );
		ini_set ( 'max_execution_time', 360 );
		ini_set ( 'error_reporting', E_ALL & ~ E_NOTICE );
	}

	private function columnName($index) {
		if ($index >= 0 && $index < 26) return chr ( ord ( 'A' ) + $index );
		else if ($index > 25) return ($this->columnName ( $index / 26 )) . ($this->columnName ( $index % 26 + 1 ));
		else
			throw new Exception ( "Invalid Column # " . ($index + 1) );
	}

	/**
	 * 读取Excel文件的内容
	 *
	 * @param string $file 文件名
	 * @param string $type 文件类型 xlsx xls csv
	 * @param intger $sheet 要读取哪张sheet表 0为第一张表
	 * @return array
	 */
	public function read($file, $type = 'xlsx', $sheet = 0) {
		$nodata = false;
		if (! file_exists ( $file )) {
			return $nodata;
		}
		if (! $ftype = $this->ext_type ( $type )) {
			return $nodata;
		}
		
		/**
		 * 读取CSV文件的内容需将GB2312转成UTF-8编码
		 */
		if ($ftype == 'CSV') {
			$content = file_get_contents ( $file );
			$content = iconv ( 'GB2312', 'UTF-8//IGNORE', $content );
			file_put_contents ( $file, $content );
		}
		
		/**
		 * 分析文件将数据取出并生成二维数组
		 */
		$reader = PHPExcel_IOFactory::createReader ( $ftype );
		$obj_excel = $reader->load ( $file );
		
		if (! method_exists ( $obj_excel, "getSheet" )) {
			return $nodata;
		}
		$obj_work_sheet = $obj_excel->getSheet ( $sheet ); // 选中要读取的sheet表
		if (! $obj_work_sheet) {
			return $nodata;
		}
		$total_row = $obj_work_sheet->getHighestRow (); // 取得总行数
		$total_columm = $obj_work_sheet->getHighestColumn (); // 取得总列数
		
		$arr = array ();
		/**
		 * 循环读取每个单元格的数据
		 */
		for($row = 1; $row <= $total_row; $row ++) { // 行数是以第1行开始
			for($column = 'A'; $column <= $total_columm; $column ++) { // 列数是以A列开始
				$dataset [] = $obj_work_sheet->getCell ( $column . $row )->getValue ();
				$value = $obj_work_sheet->getCell ( $column . $row )->getValue ();
				$arr [$row] [$column] = is_numeric ( $value ) ? ( string ) number_format ( $value, 0, '', '' ) : $value;
			}
		}
		return $arr;
	}

	/**
	 * 将数组内容生成Excel相关格式的文件
	 *
	 * @param array $data 要处理的二维数组数据
	 * @param string $type 要生成的文件类型
	 * @param string $title SHEET叫什么名字
	 * @return boolean
	 */
	public function write($data, $type = 'xlsx', $title = "sheet1") {
		$ftype = $this->ext_type ( $type );
		if (! $ftype || ! is_array ( $data )) {
			return false;
		}
		
		$this->getProperties ()->setCreator ( 'huiber' );
		$this->getProperties ()->setLastModifiedBy ( 'huiber' );
		
		// Add data
		$this->setActiveSheetIndex ( 0 );
		$row = 1;
		foreach ( $data as $v ) {
			foreach ( $v as $k2 => $v2 ) {
				// $this->getActiveSheet ()->getColumnDimension ( $this->columnName ( $index ) )->setWidth ( $this->width [$index - 1] );
				$this->getActiveSheet ()->getColumnDimension ( $this->columnName ( $k2 ) )->setAutoSize ( true );
				$obj_style = $this->getActiveSheet ()->getStyle ( $this->columnName ( $k2 ) . $row );
				$obj_style->getNumberFormat ()->setFormatCode ( PHPExcel_Style_NumberFormat::FORMAT_GENERAL );
				
				// 是否为出错格式的数据编码并设以红颜色表示
				if (isset ( $v2 [0] ) && $v2 [0] == '`') {
					$obj_style->getFont ()->getColor ()->setARGB ( PHPExcel_Style_Color::COLOR_RED );
					$v2 = substr ( $v2, 1 );
				}
				$this->getActiveSheet ()->getCell ( $this->columnName ( $k2 ) . $row )->setValueExplicit ( $v2, PHPExcel_Cell_DataType::TYPE_STRING );
				
				// 首行加粗
				if ($row == 1) {
					$obj_style->getFont ()->setBold ( true );
					$obj_style->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_CENTER );
				}
			}
			$row ++;
		}
		$this->getActiveSheet ()->setTitle ( $title ); // Rename sheet
		$this->setActiveSheetIndex ( 0 );
		// Save Excel file
		$filename = date ( "YmdH" ) . rand ( 99999, 999999 ) . '.' . $type;
		header ( "Content-Type: application/vnd.ms-excel" );
		header ( "Content-Disposition: attachment;filename=\"$filename\"" );
		header ( "Cache-Control: max-age=0" );
		$objWriter = PHPExcel_IOFactory::createWriter ( $this, $ftype );
		$objWriter->save ( 'php://output' );
	}

	/**
	 * 根据扩展名取类型
	 *
	 * @param string $type 类型
	 * @return string 返回对应的类型
	 */
	private function ext_type($type) {
		switch (strtolower ( $type )) {
			case 'csv' :
				return 'CSV';
			case 'xls' :
				return 'Excel5';
			case 'xlsx' :
				return 'Excel2007';
			case 'pdf' :
				return 'PDF';
			case 'htm' :
			case 'html' :
				return 'HTML';
			case 'phpxl' :
				return 'Serialized';
			default :
				return '';
		}
	}
}