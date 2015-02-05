<html>
<body>
<?php
require 'barcode.php';
error_reporting(0);
require 'excel_reader2.php';
require('html2fpdf.php');
$data = new Spreadsheet_Excel_Reader("scan.xls");
$cols=array("ACCOUNT NUMBER","NAME","PRODUCT NAME", "BRANCH CODE","BRANCH NAME");
for($i=0;$i<count($data->sheets);$i++) // Loop to get all sheets in a file.
{
    if(count($data->sheets[$i][cells])>0) // checking sheet not empty
    {

        for($j=2;$j<=count($data->sheets[$i][cells]);$j++) // loop used to get each row of the sheet
        {
			$html="<div ><center><table border='0'>";
			$html.="<tr><td width=\"30\"></td><td ><cetner><img src='img\\6.png' height=49 width=270 /></center></td>";
			$html.="<td width=\"20\"></td><td><center>";
        gen($data->sheets[0][cells][$j][1]);
			$html.="<img src='barcode\\".$data->sheets[$i][cells][$j][1].".png'/>";
			$html.="</center></td></tr>";
            $html.="<tr><td width=\"20\"></td><td colspan=\"4\" height=\"150\"> <center><h3><b>RACPC ANNA NAGAR (15440)<b></h3></center><br/></td></tr>";
            for($k=1;$k<=count($data->sheets[$i][cells][$j]);$k++) // This loop is created to get data in a table format.
            {
				$html.="<tr><td width=\"20\"></td>";
				$html.="<td height=\"50\" ><center>";
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
			$html.="<tr><td width=\"20\"></td><td><center>STORAGE</center></td><td width=\"20\">:</td><td><img src='img\\dottedBox.png'/></td></tr>";
			$html.="<tr><td width=\"20\"></td><td><center>DOCUMENTS</center></td><td width=\"20\">:</td><td><img src='img\\dottedBox.png'/></td></tr>";
			$html.="<tr><td width=\"20\"></td><td><center><center></td><td width=\"20\"></td><td><img src='img\\dottedBox.png'/></td></tr>";
            $html.="</table></center></div>";

			$pdf=new HTML2FPDF();
			$pdf->AddPage();
			$pdf->WriteHTML($html);

      $branchCode=$data->sheets[0][cells][$j][4];
      $target_dir = "pdfs/";
      if(! is_dir("pdfs/$branchCode"))
      {
        mkdir("pdfs/$branchCode",0777);
      }
			$pdf->Output("pdfs/".$branchCode."/".$data->sheets[$i][cells][$j][1].".pdf");

        }
		echo $html;
    }

}
?>
</body>
</html>
