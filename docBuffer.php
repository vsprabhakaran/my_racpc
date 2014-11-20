<html>
<body>
<?php
	session_start();
        if( !($_SESSION["role"] == "BRANCH_USER" || $_SESSION["role"] == "RACPC_VIEW" || $_SESSION["role"] == "RACPC_ADMIN" ))
        {
           $_SESSION["role"] = "";
           $_SESSION["pfno"] = "";
        ?>
		<meta http-equiv="refresh" content="0;URL=login.php"><?php
        }
		else
		{
	$accountNumber = $_GET["accNo"];
	$con = new mysqli("localhost", "root", "", "racpc_automation_db");
    if ($con->connect_errno) {
        die("Connection failed: " . $conn->connect_error);
    }
	$colname = "branch_code"; 
    $branchCode=null;
	$query=mysqli_query($con,"SELECT branch_code AS '$colname' FROM loan_account_mstr WHERE loan_acc_no = '$accountNumber'");
    $row = mysqli_fetch_array($query);
    if($row[$colname] != "")
    {
        $branchCode=$row[$colname];
		
    }
    else
    {
        echo "Branch code not found.";
        //(FALSE);
    }
     mysqli_close($con);
        $filePath = "D://uploads/" .$branchCode."/". $accountNumber.".pdf";

        if (file_exists($filePath))
        {
			 $contents = file_get_contents($filePath);
            header('Content-Type: application/pdf');
            header('Content-Length: ' . filesize($filePath));
            echo $contents;
        }
		else
		echo "file not found";
	}
?>

</body>
</html>