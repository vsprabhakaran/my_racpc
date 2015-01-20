<html>
<body>
<?php
require 'excel_reader2.php';

$data = new Spreadsheet_Excel_Reader("product_master.xls");


$cols=array("product_code","product_name");


if(count($data->sheets) != 1)
{
    die("The number of sheets is too much or none at all!!!");
}
if(!(count($data->sheets[0][cells])>0))
{
    die("No data found in the excel sheet");
}
if($data->colcount() != count($cols))
{
    die("Invalid number of columns");
}
for($i=1; $i<=count($cols); $i++)
{
    if($cols[$i-1] != $data->val(1,$i))
    {
        die("The column header mismatch : ".$cols[$i-1]." vs ".$data->val(1,$i));
    }
}


for($crow=2;$crow<=$data->rowcount();$crow++)
{

    $currentProductCode = $data->val($crow,1);
    if(doesProductCodeExists($currentProductCode))
    {
        echo $currentProductCode." exists";
        echo " value : ".$data->val($crow,2);
        if(UpdateExistingProductCode($currentProductCode,$data->val($crow,2)))
        {
            echo ". Update Success<br/>";
        }
        else
        {
            echo ". Update Failed!!!<br/>";
        }
    }
    else
    {
        echo $currentProductCode." does not exists";
        if(InsertNewProductCode($currentProductCode,$data->val($crow,2)))
        {
            echo ". Insert Success<br/>";
        }
        else
        {
            echo ". Insert Failed!!!<br/>";
        }
    }
}

/*function doesProductCodeExists($ProductCode)
{
    $con = NULL;
    db_prelude($con);
    $colname = "product_code"; 
    $query=mysqli_query($con,"SELECT product_code as  '$colname' FROM loan_product_mstr WHERE product_code = '$ProductCode'");
    $row=mysqli_fetch_array($query);
    mysqli_close($con);
	if($row[0] != "")
    {
        return TRUE;
    }
    else
    {
        return FALSE;
    }
}*/
function doesProductCodeExists($ProductCode)
{
    $con = NULL;
    db_prelude($con);
    $colname = "product_code"; 
    $query=$con->query("SELECT product_code as  '$colname' FROM loan_product_mstr WHERE product_code = '$ProductCode'");
    //$row=mysqli_fetch_array($query);
    $affectedRows = $con->affected_rows;
    mysqli_close($con);
	if($affectedRows > 0)
    {
        return TRUE;
    }
    else
    {
        return FALSE;
    }
}
function UpdateExistingProductCode($ProductCode,$ProductName)
{
    $con = NULL;
    db_prelude($con);
    $ProductName = $con->real_escape_string($ProductName);
    $dbQuery =  "update loan_product_mstr set product_name = '$ProductName' where product_code = '$ProductCode'";
    $query=$con->query($dbQuery);
    getLastUpdateQueryStats($con,$affectedRows,$matchedRows);
    mysqli_close($con);
	if($matchedRows > 0)
    {
        return TRUE;
    }
    else
    {
        return FALSE;
    }
}

function InsertNewProductCode($ProductCode,$ProductName)
{
    $con = NULL;
    db_prelude($con);
    $ProductName = $con->real_escape_string($ProductName);
    $dbQuery =  "insert loan_product_mstr(product_code,product_name) values('$ProductCode','$ProductName')";
    $query=$con->query($dbQuery);
    $affectedRows = $con->affected_rows;
    mysqli_close($con);
	if($affectedRows > 0)
    {
        return TRUE;
    }
    else
    {
        return FALSE;
    }
}

function db_prelude(&$con)
{
    $con = new mysqli("localhost", "root", "", "racpc_automation_db");
    if ($con->connect_errno) {
        die("Connection failed: " . $conn->connect_error);
    }
}

function getLastUpdateQueryStats($con,&$rowsAffected,&$rowsMatched)
{
    preg_match_all ('/(\S[^:]+): (\d+)/', $con->info, $matches); 
    $info = array_combine ($matches[1], $matches[2]);
    $rowsAffected = $info ['Changed'];
    $rowsMatched = $info['Rows matched'];
}
/*function getLastInsertQueryStats($con,&$rowsAffected,&$rowsMatched)
{
    
    list($rowsMatched, $rowsAffected, $warnings) = sscanf($con->info, "Rows matched: %d Changed: %d Warnings: %d");
    
    echo ". Rows affected : ".$rowsAffected;
}*/
?>
</body>
</html>