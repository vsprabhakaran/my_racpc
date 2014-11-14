<?php
session_start();

?>

<html>
<head>
<link rel="stylesheet" href="css/loginstyles.css">
</head>

<body bgcolor="#F0F0F0">

<?php 

$con = mysql_connect("localhost","root");

if (!$con)  
  { 
 	die('Could not connect: ' . mysql_error());  
  } 
  $db=mysql_select_db("racpc_automation_db",$con);

?>
 <?php
 $sql    = 'SELECT * FROM adms_user_mstr where status_flag = "A"';
$result = mysql_query($sql, $con);
$pfarray = array();

 while ($row = mysql_fetch_assoc($result)) {
	$pf_index=	 $row['pf_index'];
	//echo $pf_index;
array_push($pfarray,$pf_index);
//print_r($pfarray);

	}
//echo count($pfarray);

if(count($pfarray)>0)
{
$pfname = array();
$pfdetail = array();
for($i=0;$i<count($pfarray);$i++)
{
 $rec1=mysql_fetch_array(mysql_query("SELECT * FROM user_mstr WHERE pf_index='$pfarray[$i]'"));
 array_push($pfname,$rec1['emp_name']);
//print_r($pfname);
array_push($pfdetail,$pfarray[$i]." -- ".$rec1['emp_name']);
//print_r($pfdetail);
}
}
//echo count($pfname);
//echo count($pfdetail);
$noofrows=count($pfdetail);
//echo $noofrows;
?>
<form action='UCOApproved.php' method='post'>
<center>
<?php
if($noofrows>0)
{
?>
<b>Please select the userid to approve &nbsp;&nbsp;<b>
<select id='auser' name='auser'>

<?php
for($j=0;$j<count($pfarray);$j++){
    echo "<option>".$pfdetail[$j]."</option>";
}
?>
</select>
<br>
<br>
<input type='submit' class='cancel1' value='Submit'>
<?php
}
else
{
?>
<b>You have approved all the assigned users.<b>
<?php
}
?>
</center>
<?php
mysql_free_result($result);

  ?>  
  

</body>

</html>