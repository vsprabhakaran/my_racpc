<!--
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> 
<!DOCTYPE html xmlns="http://www.w3.org/1999/xhtml"> 
-->
<html>
<head> 
<title>ADMS In REPORT</title> 
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
$config['table'] = "adms_loan_account_mstr";
$config['nicefields'] = true; //true or false | "Field Name" or "field_name"
$config['perpage'] = 20;
$config['showpagenumbers'] = true; //true or false
$config['showprevnext'] = true; //true or false



include './Pagination.php';
$Pagination = new Pagination();

//CONNECT
mysql_connect($config['host'], $config['user'], $config['pass']);
mysql_select_db($config['database']);
    
    // fetch racpc name
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

//get total rows
$totalrows = mysql_fetch_array(mysql_query("SELECT count(*) as total FROM adms_loan_account_mstr am, loan_account_mstr l, branch_mstr b
where am.loan_status='A' and am.document_status='IN' 
and am.loan_acc_no = l.loan_acc_no 
and l.branch_code = b.branch_code
and b.racpc_code = $racpc "));

    //$sql = "SELECT * from `".$config['table']."`";
    //$result = mysql_query($sql) or die(mysql_error());
    //$totalrows = mysql_num_rows($result);
    //echo $totalrows;

//echo $totalrows;
//limit per page, what is current page, define first record for page
$limit = $config['perpage'];
if(isset($_GET['page']) && is_numeric(trim($_GET['page']))){$page = mysql_real_escape_string($_GET['page']);}else{$page = 1;}
$startrow = $Pagination->getStartRow($page,$limit);

echo "<br>";
//create page links
if($config['showpagenumbers'] == true){
	$pagination_links = $Pagination->showPageNumbers($totalrows['total'],$page,$limit);
}else{$pagination_links=null;}

if($config['showprevnext'] == true){
	$prev_link = $Pagination->showPrev($totalrows['total'],$page,$limit);
	$next_link = $Pagination->showNext($totalrows['total'],$page,$limit);
}else{$prev_link=null;$next_link=null;}

// total number of loans in the RACPC
    $sql = "select a.loan_acc_no from  adms_loan_account_mstr a, loan_account_mstr l, 
    branch_mstr b, racpc_mstr r,user_mstr u
    where u.pf_index = $pfno 
    and u.branch_code = r.racpc_code
    and b.racpc_code = r.racpc_code
    and l.branch_code = b.branch_code
    and a.loan_acc_no = l.loan_acc_no ";

    $result = mysql_query($sql) or die(mysql_error());
    $num_rows1 = mysql_num_rows($result);


// total number of loans which have their documents inside the RACPC
    $sql = "select a.loan_acc_no from  adms_loan_account_mstr a, loan_account_mstr l, 
    branch_mstr b, racpc_mstr r,user_mstr u
    where u.pf_index = $pfno 
    and u.branch_code = r.racpc_code
    and b.racpc_code = r.racpc_code
    and l.branch_code = b.branch_code
    and a.loan_acc_no = l.loan_acc_no 
    and a.document_status = 'IN' 
    and a.loan_status ='A'";

    $result = mysql_query($sql) or die(mysql_error());
    $num_rows2 = mysql_num_rows($result);
   
//IF ORDERBY NOT SET, SET DEFAULT
if(!isset($_GET['orderby']) OR trim($_GET['orderby']) == ""){
	//GET FIRST FIELD IN TABLE TO BE DEFAULT SORT
	//$sql = "SELECT * FROM `".$config['table']."` LIMIT 1";
$sql= "SELECT a.loan_acc_no AS loan_acc_no, l.acc_holder_name AS  'acc_holder_name', 
l.branch_code AS  'branch_code', a.folio_no AS  'folio_no', a.rack AS rack
FROM adms_loan_account_mstr a, loan_account_mstr l, branch_mstr b
WHERE a.loan_status =  'A'
AND a.document_status =  'IN'
AND a.loan_acc_no = l.loan_acc_no
AND l.branch_code = b.branch_code
AND b.racpc_code =$racpc
LIMIT 1"; 
/*
    $sql = "select  a.loan_acc_no, a.document_status, a.folio_no, a.rack
    from adms_loan_account_mstr a, loan_account_mstr l, 
    branch_mstr b, racpc_mstr r,user_mstr u
    where u.pf_index = $pfno 
    and u.branch_code = r.racpc_code
    and b.racpc_code = r.racpc_code
    and l.branch_code = b.branch_code
    and a.loan_acc_no = l.loan_acc_no
    LIMIT 1";
*/    
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

// below query is modified |||| removed user_mstr u |||| removed u.pf_index = $pfno and u.branch_code = r.racpc_code
/*
 "SELECT a.loan_acc_no as loan_acc_no, l.acc_holder_name as 'acc_holder_name', l.branch_code as 'branch_code', 
a.folio_no as 'folio_no', a.rack as rack
FROM adms_loan_account_mstr a, loan_account_mstr l, 
branch_mstr b, racpc_mstr r,user_mstr u
where u.pf_index = $pfno 
and u.branch_code = r.racpc_code
and b.racpc_code = r.racpc_code
and l.branch_code = b.branch_code
and a.loan_acc_no = l.loan_acc_no
and a.loan_status = 'A'
and a.document_status= 'IN'
ORDER BY cast(a.$orderby as unsigned) $sort 
LIMIT $startrow,$limit";   
 */

//GET DATA
// sort adms_loan_account_mstr data
if($orderby=='loan_acc_no'||$orderby=='folio_no'||$orderby=='rack')
{
   
$sql= "SELECT a.loan_acc_no AS loan_acc_no, l.acc_holder_name AS  'acc_holder_name', 
l.branch_code AS  'branch_code', a.folio_no AS  'folio_no', a.rack AS rack
FROM adms_loan_account_mstr a, loan_account_mstr l, branch_mstr b
WHERE a.loan_status =  'A'
AND a.document_status =  'IN'
AND a.loan_acc_no = l.loan_acc_no
AND l.branch_code = b.branch_code
AND b.racpc_code =$racpc
ORDER BY cast(a.$orderby as unsigned) $sort 
LIMIT $startrow,$limit";   
}
// sort loan_account_mstr data
else if($orderby=='branch_code')
{
$sql= "SELECT a.loan_acc_no AS loan_acc_no, l.acc_holder_name AS  'acc_holder_name', 
l.branch_code AS  'branch_code', a.folio_no AS  'folio_no', a.rack AS rack
FROM adms_loan_account_mstr a, loan_account_mstr l, branch_mstr b
WHERE a.loan_status =  'A'
AND a.document_status =  'IN'
AND a.loan_acc_no = l.loan_acc_no
AND l.branch_code = b.branch_code
AND b.racpc_code =$racpc
ORDER BY cast(l.$orderby as unsigned) $sort 
LIMIT $startrow,$limit"; 
}
else
{
$sql= "SELECT a.loan_acc_no AS loan_acc_no, l.acc_holder_name AS  'acc_holder_name', 
l.branch_code AS  'branch_code', a.folio_no AS  'folio_no', a.rack AS rack
FROM adms_loan_account_mstr a, loan_account_mstr l, branch_mstr b
WHERE a.loan_status =  'A'
AND a.document_status =  'IN'
AND a.loan_acc_no = l.loan_acc_no
AND l.branch_code = b.branch_code
AND b.racpc_code =$racpc
ORDER BY cast(l.$orderby as char) $sort 
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
<div id="tot1"><p style="color: #33089e; font-size: small" >* Total number of documents: <?php echo $num_rows1?> </p></div>
<div id="tot2"><p style="color: #33089e; font-size: small" >* Total number of documents inside <?php echo $racpc_name ?>: <?php echo $num_rows2?> </p></div>
</body>
</html>
