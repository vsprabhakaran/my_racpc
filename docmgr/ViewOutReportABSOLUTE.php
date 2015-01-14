<html>
    <head>
        <title>OutSlip Report</title>

        <style>
 table a:link {
	color: #666;
	font-weight: bold;
	text-decoration: none;
}
table a:visited {
	color: #999999;
	font-weight:bold;
	text-decoration:none;
}
table a:active,
table a:hover {
	color: #005fbf;
	text-decoration:underline;
}
 table.center {
    margin-left: auto;
    margin-right: auto;
}
table {
    
	font-family:Arial, Helvetica, sans-serif;
	color:#005fbf;
	font-size:13px;
	text-shadow: 1px 1px 0px #fff;
	background:#eaebec;
	margin:150px auto;
    margin-left: 20%;
    margin-right: auto;
    margin-bottom: auto;
    margin-top: 5%;
	border:#ccc 1px solid;

	-moz-border-radius:3px;
	-webkit-border-radius:3px;
	border-radius:3px;

	-moz-box-shadow: 0 1px 2px #d1d1d1;
	-webkit-box-shadow: 0 1px 2px #d1d1d1;
	box-shadow: 0 1px 2px #d1d1d1;
}
table th {
	padding:21px 25px 22px 25px;
	border-top:1px solid #fafafa;
	border-bottom:1px solid #e0e0e0;
    font-weight: bold;
    
	background: #ededed;
	background: -webkit-gradient(linear, left top, left bottom, from(#ededed), to(#ebebeb));
	background: -moz-linear-gradient(top,  #ededed,  #ebebeb);
}
table th:first-child {
	text-align: left;
	padding-left:20px;
   
}
table tr:first-child th:first-child {
	-moz-border-radius-topleft:3px;
	-webkit-border-top-left-radius:3px;
	border-top-left-radius:3px;
}
table tr:first-child th:last-child {
	-moz-border-radius-topright:3px;
	-webkit-border-top-right-radius:3px;
	border-top-right-radius:3px;
}
table tr {
	text-align: center;
	padding-left:20px;
}
table td:first-child {
	text-align: left;
	padding-left:20px;
	border-left: 0;
}
table td {
	padding:18px;
	border-top: 1px solid #ffffff;
	border-bottom:1px solid #e0e0e0;
	border-left: 1px solid #e0e0e0;

	background: #fafafa;
	background: -webkit-gradient(linear, left top, left bottom, from(#fbfbfb), to(#fafafa));
	background: -moz-linear-gradient(top,  #fbfbfb,  #fafafa);
}
table tr.even td {
	background: #f6f6f6;
	background: -webkit-gradient(linear, left top, left bottom, from(#f8f8f8), to(#f6f6f6));
	background: -moz-linear-gradient(top,  #f8f8f8,  #f6f6f6);
}
table tr:last-child td {
	border-bottom:0;
}
table tr:last-child td:first-child {
	-moz-border-radius-bottomleft:3px;
	-webkit-border-bottom-left-radius:3px;
	border-bottom-left-radius:3px;
}
table tr:last-child td:last-child {
	-moz-border-radius-bottomright:3px;
	-webkit-border-bottom-right-radius:3px;
	border-bottom-right-radius:3px;
}
table tr:hover td {
	background: #f2f2f2;
	background: -webkit-gradient(linear, left top, left bottom, from(#f2f2f2), to(#f0f0f0));
	background: -moz-linear-gradient(top,  #f2f2f2,  #f0f0f0);	
}
</style>

       <!--
<link rel="stylesheet" href="style.css">  
<link rel="stylesheet" href="reset.css">  
<link rel="stylesheet" href="typography.css">  
        -->

</head>
<body style="background-image:url('../img/greyzz.png');">
<?php
ini_set('display_errors','On');
error_reporting(E_ALL | E_STRICT);
session_start();
        if( $_SESSION["role"] != "RACPC_DM")
        {
           $_SESSION["role"] = "";
        ?>
        <meta http-equiv="refresh" content="0;URL=../login.php">
        <?php
        }
		else
		{

$con = new mysqli("localhost", "root", "", "racpc_automation_db");
    if ($con->connect_errno) {
        die("Connection failed: " . $conn->connect_error);
    }
// sending query
$pfno = $_SESSION["pfno"];
$col1 = "Loan Account Number";
$col2 = "Out Time"; 

$col3 = "Receiver PF Index";
$col4 = "Receiver Name";
$result = mysqli_query($con,"SELECT dl.loan_acc_no as '$col1' , max(dl.timestamp) as '$col2', dl.borrower_pf_index as '$col3', u.emp_name as '$col4'
    FROM adms_loan_account_mstr am, loan_account_mstr l, document_activity_log dl, user_mstr u,branch_mstr b, branch_mstr b1, racpc_mstr r1
    WHERE am.document_status = 'OUT'
    AND am.loan_status = 'A'
    AND am.loan_acc_no = l.loan_acc_no
    AND l.loan_acc_no = dl.loan_acc_no
    AND l.branch_code = b1.branch_code
    AND dl.slip_type = 'OUT'
    AND dl.docmgr_pf_index = '$pfno'
    AND dl.borrower_pf_index = u.pf_index
    AND u.branch_code = b.branch_code
    AND b.racpc_code = b1.racpc_code
    group by 1
    ORDER BY 2 ASC ");
if (!$result) {
    die("Query to show fields from table failed");
}

$fields_num = mysqli_num_fields($result);
echo "<br><br>";
echo "<center>";
echo "<table border='1'style='margin:0px;'><tr>";
// printing table headers
for($i=0; $i<$fields_num; $i++)
{
    $field = mysqli_fetch_field($result);
    echo "<td><center><b>{$field->name}</b></center></td>";
}
echo "</tr>\n";
// printing table rows
while($row = mysqli_fetch_row($result))
{
    echo "<tr>";
    foreach($row as $cell)
    echo "<td><center>$cell</center></td>";
    echo "</tr>\n";
}
  mysqli_close($con);
 // echo json_encode(TRUE);
 //}
echo "</table>";
echo "</center>";

        }
?>
</body></html>