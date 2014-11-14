<?php
session_start();
?>

<html>
<head>
<link rel="stylesheet" href="css/loginstyles.css">
</head>
<body bgcolor="#FOFOFO">
<?php 

$auser = $_SESSION[appuser];


$con = mysql_connect("localhost","root");
if (!$con)  
  { 
 	die('Could not connect: ' . mysql_error());  
  } 
  $db=mysql_select_db("racpc_automation_db",$con);


$query1 = mysql_query("update adms_user_mstr set status_flag='A' where pf_index='$auser'");
if(!$query1)
{
	echo "<form action='userApproval.php'>";


echo "<table  width='300'  bgcolor='#658fb1' border='0' cellspacing='4' cellpadding='5' align=center>";
 echo "<tr>";
 echo "<td align='center'>";
 echo "Database Error Occurred. Please contact LHO";
echo "</td>";
echo "</tr>";
 
echo "<tr>";

	    echo "<td  align='center'><font face='verdana, arial, helvetica' size='4' align='center'> <br>";
  				echo "<a href='userApproval.php' target='_self' class='dispmenu'>Home</a></td>";
    echo "</tr>";
  echo "</table>";
echo "</form>";
}
else
{
echo "<form action='userApproval.php'>";


echo "<table  width='300'  bgcolor='#F0F0F0' border='0' cellspacing='4' cellpadding='5' align=center>";
 echo "<tr>";
 echo "<td align='center'>";
 echo "<b>Branch user approved successfully</b>";
echo "</td>";
echo "</tr>";
 
echo "<tr>";

	
 echo "<td align='center'>";
	echo "<input type='submit' class='cancel1' value='Home'>";
 echo "</td>";

    echo "</tr>";
  echo "</table>";
echo "</form>";
}


?>

</body>

</html>