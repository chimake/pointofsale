<?php
require_once(APPPATH.'libraries/barcode/BCGFontFile.php');
require_once(APPPATH.'libraries/barcode/BCGColor.php');
require_once(APPPATH.'libraries/barcode/BCGDrawing.php');
require_once (APPPATH."libraries/barcode/BCGcode128.barcode.php");

class Barcode extends CI_Controller 
{
	function __construct()
	{
		parent::__construct();	
	}
	
	function index()
	{
		$text = rawurldecode($this->input->get('text'));
		$barcode = rawurldecode($this->input->get('barcode'));
		$scale = $this->input->get('scale') ? $this->input->get('scale') : 1;
		$thickness = $this->input->get('thickness') ? $this->input->get('thickness') : 30;
		
		$font = new BCGFontFile(APPPATH.'libraries/barcode/font/Arial.ttf', 10);
		$color_black = new BCGColor(0, 0, 0);
		$color_white = new BCGColor(255, 255, 255);
		
		// Barcode Part
		$code = new BCGcode128();
		$code->setScale($scale);
		$code->setThickness($thickness);
		$code->setForegroundColor($color_black);
		$code->setBackgroundColor($color_white);
		$code->setFont($font);
		$code->setLabel($text);
		$code->parse($barcode);
 
		// Drawing Part
		$drawing = new BCGDrawing('', $color_white);
		$drawing->setBarcode($code);
		$drawing->draw();
		header('Content-Type: image/png');
		$drawing->finish(BCGDrawing::IMG_FORMAT_PNG);	
	}	
}
?>