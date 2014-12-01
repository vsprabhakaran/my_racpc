
<?php
require('barcode.php');
require('accountInformations.php');
            $html="<html><body>";
			$html.="<div ><center><table border='0'>";
			$html.="<tr><td colspan=\"3\" height=\"150\" ><img src='logo.png' /></td></tr>";
			$html.="<tr><td>";
            $number=
             //echo "<img id='barcode' style='visibility:hidden;' alt='testing' src='barcode.php?text=$number' />";
            gen($number);
			$html.= "<img src='Images/$number.png'/>";
			$html.="</td><td></td><td ></td></tr>";
            $html.="<tr><td colspan=\"3\" height=\"150\"> <center><h3><b>RACPC Ayyappanthangal (17938)<b></h3></center><br/></td></tr>";

            $con = NULL;
            db_prelude($con);


            for($k=1;$k<=count($data->sheets[$i][cells][$j]);$k++) // This loop is created to get data in a table format.
            {
				$html.="<tr> <td height=\"50\" width=\"350\"><center>";
                $html.="ACCOUNT NUMBER";
                $html.="</center></td> <td width=\"50\">:</td> <td>";
                $html.=$data->sheets[$i][cells][$j][$k];
				$html.="<br/> </td> </tr>";

                $html.="<tr> <td height=\"50\" width=\"350\"><center>";
                $html.="NAME";
                $html.="</center></td> <td width=\"50\">:</td> <td>";
                $html.=$data->sheets[$i][cells][$j][$k];
				$html.="<br/> </td> </tr>";

                $html.="<tr> <td height=\"50\" width=\"350\"><center>";
                $html.="PRODUCT NAME";
                $html.="</center></td> <td width=\"50\">:</td> <td>";
                $html.=$data->sheets[$i][cells][$j][$k];
				$html.="<br/> </td> </tr>";

                $html.="<tr> <td height=\"50\" width=\"350\"><center>";
                $html.="BRANCH CODE";
                $html.="</center></td> <td width=\"50\">:</td> <td>";
                $html.=$data->sheets[$i][cells][$j][$k];
				$html.="<br/> </td> </tr>";

                $html.="<tr> <td height=\"50\" width=\"350\"><center>";
                $html.="BRANCH NAME";
                $html.="</center></td> <td width=\"50\">:</td> <td>";
                $html.=$data->sheets[$i][cells][$j][$k];
				$html.="<br/> </td> </tr>";
            }
			$html.="<tr><td><center>STORAGE<center></td><td width=\"50\">:</td><td><img src='box.png'/></td></tr>";
			$html.="<tr><td><center>DOCUMENTS<center></td><td width=\"50\">:</td><td><img src='box.png'/></td></tr>";
			$html.="<tr><td><center><center></td><td width=\"50\"></td><td><img src='box.png'/></td></tr>";
            $html.="</table></center></div>";
            $html.="</body></html>";
			echo $html;
			
        }
		
    }

}
?>
