<html>
<body>
<?php
  require 'excel_reader2.php';
  $data = new Spreadsheet_Excel_Reader("branch_mstr.xls");
  $cols=array("branch_code","branch_name","network","zone","region","racpc_code","address");

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

    $currentBranchCode = $data->val($crow,1);
    if(doesBranchCodeExists($currentBranchCode))
    {
      echo $currentBranchCode." exists";
      echo " value : ".$data->val($crow,2);
      if(UpdateExistingBranchCode($currentBranchCode,$data->val($crow,2),$data->val($crow,3),$data->val($crow,4),$data->val($crow,5),$data->val($crow,6),$data->val($crow,7)))
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
      echo $currentBranchCode." does not exists";
      if(InsertNewBranchCode($currentBranchCode,$data->val($crow,2),$data->val($crow,3),$data->val($crow,4),$data->val($crow,5),$data->val($crow,6),$data->val($crow,7)))
      {
        echo ". Insert Success<br/>";
      }
      else
      {
        echo ". Insert Failed!!!<br/>";
      }
    }
  }
  
  function doesBranchCodeExists($BranchCode)
  {
    $con = NULL;
    db_prelude($con);
    $colname = "branch_code";
    $query=$con->query("SELECT branch_code as  '$colname' FROM branch_mstr WHERE branch_code = '$BranchCode'");
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
  function UpdateExistingBranchCode($BranchCode,$BranchName,$Network,$Zone,$Region,$Racpc_code,$Address)
  {
    $con = NULL;
    db_prelude($con);
    $row=mysqli_query($con,"select racpc_code from branch_mstr where branch_code='$BranchCode'");
    $dbRacpcCode=mysqli_fetch_array($row);
    if($dbRacpcCode[0]!=$Racpc_code)
        echo $BranchCode."s RACPC code is changing from ".$dbRacpcCode[0]." to ".$Racpc_code;
    $dbQuery =  "update branch_mstr set branch_name = '$BranchName', network='$Network', zone='$Zone', region='$Region', racpc_code='$Racpc_code', address='$Address' where branch_code = '$BranchCode'";

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

  function InsertNewBranchCode($BranchCode,$BranchName,$Network,$Zone,$Region,$Racpc_code,$Address)
  {
    $con = NULL;
    db_prelude($con);
    $dbQuery =  "insert into branch_mstr(branch_code,branch_name,network,zone,region,racpc_code,address) values('$BranchCode','$BranchName','$Network','$Zone','$Region','$Racpc_code','$Address')";
    $query=$con->query($dbQuery);
    $affectedRows = $con->affected_rows;

    if($affectedRows > 0)
    {
      mysqli_close($con);
      return TRUE;
    }
    else
    {
      echo mysqli_error($con);
      mysqli_close($con);
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
?>
</body>
</html>
