<html>
<body>
<?php
  require 'excel_reader2.php';
  $data = new Spreadsheet_Excel_Reader("loan_mstr.xls");
  $cols=array("loan_acc_no","branch_code","acc_holder_name","racpc_code","product_name");
  $NoofInserts=0;
  $NoofUpdates=0;
  $NoofMigrations=0;
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
    set_time_limit (10);
    $currentAccNo = $data->val($crow,1);
    if(doesAccNoExists($currentAccNo))
    {
      echo $currentAccNo." exists";
      echo " value : ".$data->val($crow,3);
      if(UpdateExistingAccNo($currentAccNo,$data->val($crow,2),$data->val($crow,3),$data->val($crow,4),$data->val($crow,5)))
      {
        $NoofUpdates++;
        echo ". Update Success<br/>";
      }
      else
      {
        echo ". Update Failed!!!<br/>";
      }
    }
    else
    {
      echo $currentAccNo." does not exists";
      if(InsertNewAccNo($currentAccNo,$data->val($crow,2),$data->val($crow,3),$data->val($crow,4),$data->val($crow,5)))
      {
        $NoofInserts++;
        echo ". Insert Success<br/>";
      }
      else
      {
        echo ". Insert Failed!!!<br/>";
      }
    }
  }
  echo "No. of Updates =".$NoofUpdates.", No.of Inserts=".$NoofInserts." and No. of Migrations=".$NoofMigrations;
  function doesAccNoExists($AccNo)
  {
    $con = NULL;
    db_prelude($con);
    $colname = "loan_acc_no";
    $query=mysqli_query($con,"SELECT count(*) as total FROM loan_account_mstr WHERE loan_acc_no = '$AccNo'");
    $row=mysqli_fetch_assoc($query);
    //echo "Result".$row['total'];
    //$affectedRows = $con->affected_rows;
    mysqli_close($con);
    if($row['total'] > 0)
    {
      return TRUE;
    }
    else
    {
      return FALSE;
    }
  }
  function UpdateExistingAccNo($AccNo,$BranchCode,$Name,$racpcCode,$productName)
  {
    $con = NULL;
    db_prelude($con);
    // $row=mysqli_query($con,"select racpc_code from branch_mstr where branch_code='$BranchCode'");
    // $dbRacpcCode=mysqli_fetch_array($row);
    // if($dbRacpcCode[0]!=$Racpc_code)
    //     echo $BranchCode."s RACPC code is changing from ".$dbRacpcCode[0]." to ".$Racpc_code;
    $query= mysqli_query($con,"SELECT branch_code, racpc_code from loan_account_mstr where loan_acc_no='$AccNo'");
    $row=mysqli_fetch_array($query);
    if($row['branch_code']!=$BranchCode)
    {
      echo $AccNo." branch code changed from ".$row['branch_code']." to ".$BranchCode;
    }
    else if($row['racpc_code']!=$racpcCode)
    {
      $GLOBALS['NoofMigrations']++;
      echo $AccNo." racpc code changed from ".$row['racpc_code']." to ".$racpcCode;
    }
    $dbQuery =  "update loan_account_mstr set branch_code='$BranchCode', acc_holder_name = '$Name', racpc_code='$racpcCode', product_name='$productName' where loan_acc_no = '$AccNo'";
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

  function InsertNewAccNo($AccNo,$BranchCode,$Name,$racpcCode,$productName)
  {
    $con = NULL;
    db_prelude($con);
    $dbQuery =  "insert into loan_account_mstr(loan_acc_no,branch_code,acc_holder_name,racpc_code,product_name)
    values('$AccNo','$BranchCode','$Name','$racpcCode','$productName')";
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
