<html>
<body>
<?php
   
       function get_ip_address(){
            if (isset($_SERVER))
            {
                if (isset($_SERVER)) 
                {
                if (isset($_SERVER["HTTP_X_FORWARDED_FOR"]) && ip2long($_SERVER["HTTP_X_FORWARDED_FOR"]) !== false) 
                {
                $ipadres = $_SERVER["HTTP_X_FORWARDED_FOR"];  
                }
                elseif (isset($_SERVER["HTTP_CLIENT_IP"])  && ip2long($_SERVER["HTTP_CLIENT_IP"]) !== false) 
                {
                $ipadres = $_SERVER["HTTP_CLIENT_IP"];  
                }
                else 
                {
                $ipadres = $_SERVER["REMOTE_ADDR"]; 
                }
                }
            } 
            else 
            {
                if (getenv('HTTP_X_FORWARDED_FOR') && ip2long(getenv('HTTP_X_FORWARDED_FOR')) !== false) 
                {
                $ipadres = getenv('HTTP_X_FORWARDED_FOR'); 
                }
                elseif (getenv('HTTP_CLIENT_IP') && ip2long(getenv('HTTP_CLIENT_IP')) !== false) 
                {
                $ipadres = getenv('HTTP_CLIENT_IP');  
                }
                else 
                {
                $ipadres = getenv('REMOTE_ADDR'); 
                }
            }
        return $ipadres;
        }
 
   session_start();
        if( !($_SESSION["role"] == "BRANCH_VIEW" || $_SESSION["role"] == "RACPC_VIEW" || $_SESSION["role"] == "RACPC_ADMIN" || $_SESSION["role"] == "RACPC_DM" ))
        {
           $_SESSION["role"] = "";
           $_SESSION["pfno"] = "";
        ?>
		<meta http-equiv="refresh" content="0;URL=login.php"><?php
        }
		else
		{
      
	$accountNumber = $_GET["accNo"];
    $type=$_GET["type"];
    $pf_index = $_SESSION["pfno"];
    $ipaddress = get_ip_address();
  
	$con = new mysqli("localhost", "root", "", "racpc_automation_db");
    if ($con->connect_errno) 
    {
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
        return;
    }

    // activity log for document viewing with ip address 
   
    $con = new mysqli("localhost", "root", "", "racpc_automation_db");
    if ($con->connect_errno) 
        {
        die("Connection failed: " . $conn->connect_error);
        }  
    $query=mysqli_query($con,"INSERT INTO doc_view_activity_log (pf_index,loan_acc_no,ip_address)  
	values ('$pf_index','$accountNumber','$ipaddress')"); 

     mysqli_close($con);
        $filePath = "E://uploads/" .$branchCode."/". $accountNumber.".pdf";
        $filename=$accountNumber.".pdf";

        if (file_exists($filePath))
        {
			// $contents = file_get_contents($filePath);
            header('Content-Type: application/pdf');
            header('Content-Length: ' . filesize($filePath));
            if($type=="view")
            {
            header('Content-Disposition: inline; filename="' . $filename . '"');
            }
            else if($type=="download")
            {
              header('Content-Disposition: attachment; filename="' . $filename . '"');
            }
            header('Content-Transfer-Encoding: binary');
            header('Accept-Ranges: bytes'); 
           // echo $contents;
           @readfile($filePath);
        }
		else echo "file not found";
	}
?>

</body>
</html>
