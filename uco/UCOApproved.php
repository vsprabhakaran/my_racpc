<?php
session_start();

?>
<!doctype html>
<html lang=''>
<head>

<link rel="stylesheet" href="css/loginstyles.css">
</head>
<body bgcolor='#F0F0F0'>

<div>
<table border="0" style="width:100%;height:100%;border-width:2px;">


<tr><td colspan="3"><div>
<?php


$approveuser=$_POST['auser'];


$userapprove = strtok($approveuser, " ");
$_SESSION['appuser']= $userapprove;

$con = mysql_connect("localhost","root");
if (!$con)
  {
 	die('Could not connect: ' . mysql_error());
  }
  $db=mysql_select_db("racpc_automation_db",$con);

  $rec=mysql_fetch_array(mysql_query("SELECT * FROM user_mstr WHERE pf_index='$userapprove'"));

    $rec1=mysql_fetch_array(mysql_query("SELECT * FROM adms_user_mstr WHERE pf_index='$userapprove'"));

	$rec2=mysql_fetch_array(mysql_query("SELECT * FROM user_mstr WHERE pf_index='$userapprove'"));

	$brcode=$rec2['branch_code'];

		$rec3=mysql_fetch_array(mysql_query("SELECT * FROM branch_mstr WHERE branch_code='$brcode'"));

echo "<form action='UCOApproved1.php' name='submit1'>";


echo "<table  width='500' border='0' cellspacing='4' cellpadding='5' align=center>";

 echo "<tr>";
 echo "<td colspan='20' align='center'>";
 echo "Are you sure to create user for <b>".$userapprove." </b> with below details";
echo "</td>";
echo "</tr>";

 echo "<tr>";
 echo "<td colspan='20' align='center'>";
 echo "PF Name <b>".$rec['emp_name']."</b>";
echo "</td>";
echo "</tr>";

 echo "<tr>";
 echo "<td colspan='20' align='center'>";
 echo "Branch Code <b>".$rec['branch_code']."</b>";
echo "</td>";
echo "</tr>";

 echo "<tr>";
 echo "<td colspan='20' align='center'>";
 echo "Branch Name <b>".$rec3['branch_name']."</b>";
echo "</td>";
echo "</tr>";

 echo "<tr>";
 echo "<td colspan='20' align='center'>";
 echo "Designation <b>".$rec['designation']."</b>";
echo "</td>";
echo "</tr>";


 echo "<tr>";
 echo "<td colspan='20' align='center'>";
 echo "ADMS Role <b>".$rec1['adms_role']."</b>";
echo "</td>";
echo "</tr>";

echo "<tr>";
    echo "<br>";
	echo "<br>";
	 echo "<td>";
    echo "<div id=lower2>";
	echo "<input type='submit' id='confirm' value='Confirm'>";
	echo "</div>";
	echo "</td>";
 echo "<td>";
	echo "<input type='submit' class='cancel1' id='Back' value='Cancel' onclick='javascript:history.go(-1)'>";
 echo "</td>";
		  //  echo "<td  align='center'><font face='verdana, arial, helvetica' size='4' align='center'> <br>";
  			//	echo "<a href='userApproval.php' target='_self' class='dispmenu'>Cancel</a></td>";
	echo "</tr>";
  echo "</table>";
echo "</form>";



?>

</td>
</tr>
</table>
</div>

</body>
</html>
