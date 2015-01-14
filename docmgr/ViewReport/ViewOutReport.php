<!--
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> 
<!DOCTYPE html xmlns="http://www.w3.org/1999/xhtml"> 
-->
<html>
<head> 
<title>ADMS OUT REPORT</title> 
<link rel="stylesheet" type="text/css" href="reset.css" />
<link rel="stylesheet" type="text/css" href="typography.css" />
<link rel="stylesheet" type="text/css" href="style.css" />
</head> 
<body style="background-image:url('../../img/greyzz.png');">
<?php

ini_set('display_errors','Off');
error_reporting(E_ALL | E_STRICT);

session_start();
if( $_SESSION["role"] != "RACPC_DM")
     {
     $_SESSION["role"] = "";
     ?>
     <meta http-equiv="refresh" content="0;URL=../../login.php">
     <?php
     }
	 else
	 {
function columnSortArrows($field,$text,$currentfield=null,$currentsort=null){	
	//defaults all field links to SORT ASC
	//if field link is current ORDERBY then make arrow and opposite current SORT
	
	$sortquery = "sort=ASC";
	$orderquery = "orderby=".$field;
	if($currentsort == "ASC"){
		$sortquery = "sort=DESC";
		$sortarrow = '<img src="arrow_up.png" />';
	}
	
	if($currentsort == "DESC"){
		$sortquery = "sort=ASC";
		$sortarrow = '<img src="arrow_down.png" />';
	}
	
	if($currentfield == $field){
		$orderquery = "orderby=".$field;
	}else{	
		$sortarrow = null;
	}
	
	return '<a href="?'.$orderquery.'&'.$sortquery.'">'.$text.'</a> '. $sortarrow;	
	
}
function db_prelude(&$con)
{
    $con = new mysqli("localhost", "root", "", "racpc_automation_db");
    if ($con->connect_errno) {
        die("Connection failed: " . $conn->connect_error);
    }
}

$pfno = $_SESSION["pfno"];
//DATABASE SETTINGS
$config['host'] = "localhost";
$config['user'] = "root";
$config['pass'] = "";
$config['database'] = "racpc_automation_db";
//$config['table'] = "adms_loan_account_mstr";
$config['nicefields'] = true; //true or false | "Field Name" or "field_name"
$config['perpage'] = 15;
$config['showpagenumbers'] = true; //true or false
$config['showprevnext'] = true; //true or false

include './Pagination.php';
$Pagination = new Pagination();

//CONNECT
mysql_connect($config['host'], $config['user'], $config['pass']);
mysql_select_db($config['database']);

// query to fetch racpc code of login user
    $con = NULL;
    db_prelude($con);  
    $colname = "racpc_code";

    $query=mysqli_query($con,"select b.racpc_code from branch_mstr b, user_mstr u, adms_user_mstr au
	where au.pf_index='$pfno'
	and au.pf_index = u.pf_index
	and u.branch_code = b.branch_code");
    $row = mysqli_fetch_array($query);
    $racpc = $row[$colname];
     
    $colname = "racpc_name";
    $query=mysqli_query($con,"select racpc_name from racpc_mstr	where racpc_code = $racpc");
    $row = mysqli_fetch_array($query);
    $racpc_name = $row[$colname]; 
    
echo "<br>";
$sql = "SELECT am.loan_acc_no from adms_loan_account_mstr am, loan_account_mstr l, branch_mstr b
where am.loan_status='A' and am.document_status='OUT' and am.loan_acc_no = l.loan_acc_no and l.branch_code = b.branch_code and 
b.racpc_code = $racpc ";
$result = mysql_query($sql) or die(mysql_error());
$total = mysql_num_rows($result);
//echo $total; 

//get total rows
/*
$totalrows = mysql_fetch_array(mysql_query("SELECT count(dl.loan_acc_no) as total
    FROM adms_loan_account_mstr am, loan_account_mstr l, document_activity_log dl, user_mstr u,branch_mstr b, branch_mstr b1, racpc_mstr r1
    WHERE am.document_status = 'OUT'
    AND am.loan_status = 'A'
    AND am.loan_acc_no = l.loan_acc_no
    AND l.loan_acc_no = dl.loan_acc_no
    AND l.branch_code = b1.branch_code
    AND dl.slip_type = 'OUT'
    AND dl.borrower_pf_index = u.pf_index
    AND u.branch_code = b.branch_code
    AND b.racpc_code = b1.racpc_code 
    AND b1.racpc_code = $racpc"));
*/
$totalrows = mysql_fetch_array(mysql_query("SELECT COUNT(*) as total from adms_loan_account_mstr am, loan_account_mstr l, branch_mstr b
where am.loan_status='A' and am.document_status='OUT' and am.loan_acc_no = l.loan_acc_no and l.branch_code = b.branch_code and 
b.racpc_code = $racpc"));

//limit per page, what is current page, define first record for page
$limit = $config['perpage'];
if(isset($_GET['page']) && is_numeric(trim($_GET['page']))){$page = mysql_real_escape_string($_GET['page']);}else{$page = 1;}
$startrow = $Pagination->getStartRow($page,$limit);

//create page links
if($config['showpagenumbers'] == true){
	$pagination_links = $Pagination->showPageNumbers($totalrows['total'],$page,$limit);
}else{$pagination_links=null;}

if($config['showprevnext'] == true){
	$prev_link = $Pagination->showPrev($totalrows['total'],$page,$limit);
	$next_link = $Pagination->showNext($totalrows['total'],$page,$limit);
}else{$prev_link=null;$next_link=null;}
    
// this query is modified;  changing  l.branch_code = b1.branch_code into b.branch_code ||||||  
// removing  u.branch_code = b.branch_code AND b.racpc_code = b1.racpc_code |||| removed  branch_mstr b1, racpc_mstr r1
/*
$sql = "SELECT l.loan_acc_no as loan_acc_no , l.acc_holder_name , max(dl.timestamp) as 'Out Timestamp',
    dl.borrower_pf_index as 'Receiver PF Index', u.emp_name as 'Receiver Name'
    FROM adms_loan_account_mstr am, loan_account_mstr l, document_activity_log dl, user_mstr u,branch_mstr b, branch_mstr b1, racpc_mstr r1
    WHERE am.document_status = 'OUT'
    AND am.loan_status = 'A'
    AND am.loan_acc_no = l.loan_acc_no
    AND l.loan_acc_no = dl.loan_acc_no
    AND l.branch_code = b1.branch_code
    AND dl.slip_type = 'OUT'
    AND dl.borrower_pf_index = u.pf_index
    AND u.branch_code = b.branch_code
    AND b.racpc_code = b1.racpc_code 
    AND b1.racpc_code = $racpc
    GROUP BY loan_acc_no order by 1 asc";
*/
    
//IF ORDERBY NOT SET, SET DEFAULT
if(!isset($_GET['orderby']) OR trim($_GET['orderby']) == ""){
	//GET FIRST FIELD IN TABLE TO BE DEFAULT SORT
	//$sql = "SELECT * FROM `".$config['table']."` LIMIT 1";
    $sql = "SELECT l.loan_acc_no as loan_acc_no , l.acc_holder_name , max(dl.timestamp) as 'Out Timestamp',
    dl.borrower_pf_index as 'Receiver PF Index', u.emp_name as 'Receiver Name'
    FROM adms_loan_account_mstr am, loan_account_mstr l, document_activity_log dl, user_mstr u,branch_mstr b
    WHERE am.document_status = 'OUT'
    AND am.loan_status = 'A'
    AND am.loan_acc_no = l.loan_acc_no
    AND l.loan_acc_no = dl.loan_acc_no
    AND l.branch_code = b.branch_code
    AND dl.slip_type = 'OUT'
    AND dl.borrower_pf_index = u.pf_index
    AND b.racpc_code = $racpc
    GROUP BY loan_acc_no order by 1 asc";

    
	$result = mysql_query($sql) or die(mysql_error());
	$array = mysql_fetch_assoc($result);
	//first field
	$i = 0;
	foreach($array as $key=>$value){
		if($i > 0){break;}else{
		$orderby=$key;}
		$i++;		
	}
	//default sort
	$sort="ASC";
}else{
	$orderby=mysql_real_escape_string($_GET['orderby']);
    }	

//IF SORT NOT SET OR VALID, SET DEFAULT
if(!isset($_GET['sort']) OR ($_GET['sort'] != "ASC" AND $_GET['sort'] != "DESC")){
	//default sort
		$sort="ASC";
	}else{	
		$sort=mysql_real_escape_string($_GET['sort']);
        
}

//GET DATA
// Fetch all out docs of the racpc to which the User belongs
// sort adms_loan_account_mstr data
if($orderby=='loan_acc_no')
{
$sql = "SELECT l.loan_acc_no  ,l.acc_holder_name , 
    max(dl.timestamp) as 'Out Timestamp' , dl.borrower_pf_index 'Receiver PF Index', u.emp_name as 'Receiver Name'
    FROM adms_loan_account_mstr am, loan_account_mstr l, document_activity_log dl, user_mstr u,branch_mstr b 
    WHERE am.document_status = 'OUT'
    AND am.loan_status = 'A'
    AND am.loan_acc_no = l.loan_acc_no
    AND l.loan_acc_no = dl.loan_acc_no
    AND l.branch_code = b.branch_code
    AND dl.slip_type = 'OUT'
    AND dl.borrower_pf_index = u.pf_index
    AND b.racpc_code = $racpc
    group by 1
    ORDER BY cast(l.$orderby as unsigned) $sort 
    LIMIT $startrow,$limit";   
}
else if ($orderby=='acc_holder_name')
{

$sql = "SELECT dl.loan_acc_no  , l.acc_holder_name ,
    max(dl.timestamp) as 'Out Timestamp', dl.borrower_pf_index as 'Receiver PF Index', u.emp_name as 'Receiver Name'
    FROM adms_loan_account_mstr am, loan_account_mstr l, document_activity_log dl, user_mstr u,branch_mstr b 
    WHERE am.document_status = 'OUT'
    AND am.loan_status = 'A'
    AND am.loan_acc_no = l.loan_acc_no
    AND l.loan_acc_no = dl.loan_acc_no
    AND l.branch_code = b.branch_code
    AND dl.slip_type = 'OUT'
    AND dl.borrower_pf_index = u.pf_index
    AND b.racpc_code = $racpc
    group by 1
    ORDER BY cast(l.acc_holder_name as char) $sort 
    LIMIT $startrow,$limit";   
}
else if ($orderby=='timestamp')
{

$sql = "SELECT dl.loan_acc_no , l.acc_holder_name ,
    max(dl.timestamp) as 'Out Timestamp', dl.borrower_pf_index as 'Receiver PF Index', u.emp_name as 'Receiver Name'
    FROM adms_loan_account_mstr am, loan_account_mstr l, document_activity_log dl, user_mstr u,branch_mstr b
    WHERE am.document_status = 'OUT'
    AND am.loan_status = 'A'
    AND am.loan_acc_no = l.loan_acc_no
    AND l.loan_acc_no = dl.loan_acc_no
    AND l.branch_code = b.branch_code
    AND dl.slip_type = 'OUT'
    AND dl.borrower_pf_index = u.pf_index
    AND b.racpc_code = $racpc
    group by 1
    ORDER BY cast(dl.timestamp as unsigned) $sort 
    LIMIT $startrow,$limit";   
}
else if ($orderby=='borrower_pf_index')
{
    echo $orderby; echo $sort;
$sql = "SELECT dl.loan_acc_no  , l.acc_holder_name ,
    max(dl.timestamp) as 'Out Timestamp' , dl.borrower_pf_index as 'Receiver PF Index' , u.emp_name as 'Receiver Name'
    FROM adms_loan_account_mstr am, loan_account_mstr l, document_activity_log dl, user_mstr u,branch_mstr b
    WHERE am.document_status = 'OUT'
    AND am.loan_status = 'A'
    AND am.loan_acc_no = l.loan_acc_no
    AND l.loan_acc_no = dl.loan_acc_no
    AND l.branch_code = b.branch_code
    AND dl.slip_type = 'OUT'
    AND dl.borrower_pf_index = u.pf_index
    AND b.racpc_code = $racpc
    group by 1
ORDER BY dl.borrower_pf_index $sort 
LIMIT $startrow,$limit";   
}

else
{

$sql = "SELECT dl.loan_acc_no  ,l.acc_holder_name ,
    max(dl.timestamp) as 'Out Timestamp' , dl.borrower_pf_index as 'Receiver PF Index', u.emp_name as 'Receiver Name'
    FROM adms_loan_account_mstr am, loan_account_mstr l, document_activity_log dl, user_mstr u,branch_mstr b
    WHERE am.document_status = 'OUT'
    AND am.loan_status = 'A'
    AND am.loan_acc_no = l.loan_acc_no
    AND l.loan_acc_no = dl.loan_acc_no
    AND l.branch_code = b.branch_code
    AND dl.slip_type = 'OUT'
    AND dl.borrower_pf_index = u.pf_index
    AND b.racpc_code = $racpc
    group by 1
ORDER BY cast(u.emp_name as char) $sort 
LIMIT $startrow,$limit";    
}

$result = mysql_query($sql) or die(mysql_error());

//START TABLE AND TABLE HEADER
echo "<table border='1' align='center'>\n<tr>";
$array = mysql_fetch_assoc($result);
foreach ($array as $key=>$value) {
	if($config['nicefields']){
	$field = str_replace("_"," ",$key);
	$field = ucwords($field);
	}
	
	$field = columnSortArrows($key,$field,$orderby,$sort);
	echo "<th>" . $field . "</th>\n";
}
echo "</tr>\n";

//reset result pointer
mysql_data_seek($result,0);

//start first row style
$tr_class = "class='odd'";

//LOOP TABLE ROWS
while($row = mysql_fetch_assoc($result)){

	echo "<tr ".$tr_class.">\n";
	foreach ($row as $field=>$value) {	
		echo "<td>" . $value . "</td>\n";
	}
	echo "</tr>\n";
	
	//switch row style
	if($tr_class == "class='odd'"){
		$tr_class = "class='even'";
	}else{
		$tr_class = "class='odd'";
	}
	
}

//END TABLE
echo "</table>\n";

if(!($prev_link==null && $next_link==null && $pagination_links==null)){
echo '<div class="pagination">'."\n";
echo $prev_link;
echo $pagination_links;
echo $next_link;
echo '<div style="clear:both;"></div>'."\n";
echo "</div>\n";
}

/*FUNCTIONS*/


     }            
?>
<br/>
<?php 
if($total == 0){
?>
<div id="tot1"><p style="color: #33089e; font-size: small" >* No OutSlips Generated for <?php echo $racpc_name; ?>  </p></div>
<?php 
} else {
?>
<div id="tot1"><p style="color: #33089e; font-size: small" >* OutSlips Generated for the <?php echo $racpc_name; ?> is dispalyed here </p></div>
<?php
     }
 ?>
</body>
</html>
