<html>
<body>
<?php
require 'barcode.php';
error_reporting(0);
require 'excel_reader2.php';
require('html2fpdf.php');
$data = new Spreadsheet_Excel_Reader("Already_Scanned.xls");
$cols=array("ACCOUNT NUMBER","NAME","PRODUCT NAME", "BRANCH CODE","BRANCH NAME");
for($i=0;$i<count($data->sheets);$i++) // Loop to get all sheets in a file.
{
    if(count($data->sheets[$i][cells])>0) // checking sheet not empty
    {

        for($j=7;$j<=count($data->sheets[$i][cells])-26;$j++) // loop used to get each row of the sheet
        {
			$html="<div ><center><table border='0'>";
			$html.="<tr><td colspan=\"3\" height=\"150\" ><img src='img\\5.png' /></td></tr>";

			$html.="<tr><td>";
        gen($data->sheets[0][cells][$j][1]);
			$html.="<img src='barcode\\".$data->sheets[$i][cells][$j][1].".png'/>";
			$html.="</td><td></td><td ></td></tr>";
            $html.="<tr><td colspan=\"3\" height=\"150\"> <center><h3><b>RACPC Ayyappanthangal (17938)<b></h3></center><br/></td></tr>";
            for($k=1;$k<=count($data->sheets[$i][cells][$j]);$k++) // This loop is created to get data in a table format.
            {
				$html.="<tr>";
				$html.="<td height=\"50\" width=\"350\"><center>";
				//$html.="<td style=\"text-align:right;\">";
                $html.=$cols[$k-1];
				//$html.="<br/>";
                $html.="</center></td>";

				$html.="<td width=\"50\">:</td>";

                $html.="<td>";
                $html.=$data->sheets[$i][cells][$j][$k];
				$html.="<br/>";
                $html.="</td>";
				$html.="</tr>";
            }
			$html.="<tr><td><center>STORAGE</center></td><td width=\"50\">:</td><td><img src='img\\123.png'/></td></tr>";
			$html.="<tr><td><center>DOCUMENTS</center></td><td width=\"50\">:</td><td><img src='img\\123.png'/></td></tr>";
			$html.="<tr><td><center><center></td><td width=\"50\"></td><td><img src='img\\123.png'/></td></tr>";
            $html.="</table></center></div>";

			$pdf=new HTML2FPDF();
			$pdf->AddPage();
			$pdf->WriteHTML($html);

			$pdf->Output("pdfs\\".$data->sheets[$i][cells][$j][1].".pdf");

        }
		echo $html;
    }

}
?>
</body>
</html>
