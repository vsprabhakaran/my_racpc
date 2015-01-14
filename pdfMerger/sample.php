<?php
include 'PDFMerger.php';

$pdf = new PDFMerger;

$pdf->addPDF('E:/uploads/2233/10114319956.pdf', 'all')
	->addPDF('E:/uploads/2233/1011431995611.pdf', '1-2')
	->merge('file', 'E:/uploads/2233/10114319956new.pdf');

	//REPLACE 'file' WITH 'browser', 'download', 'string', or 'file' for output options
	//You do not need to give a file path for browser, string, or download - just the name.
