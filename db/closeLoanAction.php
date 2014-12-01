<?php
        session_start();
        if( $_SESSION["role"] != "RACPC_ADMIN")
        {
           $_SESSION["role"] = "";
		   $_SESSION["pfno"] = "";
        ?><meta http-equiv="refresh" content="0;URL=../login.php"><?php
        }
		else
		{
    ?>

<html>
<head>
<link rel="stylesheet" href="../css/pure-min.css">
<style type="text/css">
          p {
           font-family: 'Trebuchet MS';
           font-size: large; 
          }
            .button-success,
            .button-error,
            .button-warning,
            .button-secondary {
                color: white;
                border-radius: 4px;
                text-shadow: 0 1px 1px rgba(0, 0, 0, 0.2);
            }

            .button-success {
                background: rgb(28, 184, 65); /* this is a green */
            }

            .button-error {
                background: rgb(202, 60, 60); /* this is a maroon */
            }

            .button-warning {
                background: rgb(223, 117, 20); /* this is an orange */
            }

            .button-secondary {
                background: rgb(66, 184, 221); /* this is a light blue */
            }
 </style>

<script type="text/javascript">
            function goBack() {
                window.location = "../admin/closeLoan.php";
            }
</script>

</head>
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
    ?>
<?php
$accountNumber=$_POST["accNumber"];
$pf_index = $_SESSION["pfno"];
$ipaddress = get_ip_address();
$con = new mysqli("localhost", "root", "", "racpc_automation_db");
    if ($con->connect_errno) {
        die("Connection failed: " . $conn->connect_error);
    }
	$query=mysqli_query($con,"UPDATE adms_loan_account_mstr set loan_status='C', document_status='C' where loan_acc_no ='$accountNumber'");
    $query_log=mysqli_query($con,"insert into admin_loan_activity_log(loan_acc_no,pf_index,status,ip_address) 
        values ('$accountNumber','$pf_index','Close Loan','$ipaddress')");
	if($query && $query_log)
	{
        
	?>
	<div class="pure-controls">
        <p>Loan closed successfully!</p>
		<button class="button-success pure-button" id="backButton1" name="backButton1" onclick="goBack()">Back</button>
	</div>
	<?php
	}
	else
	{
	?>
    <div class="pure-controls">
        <p>Loan closing failed!!!.</p>
		<button class="button-error pure-button" id="backButton2" name="backButton2" onclick="goBack()">Back</button>
	</div>
    <?php
	}
	mysqli_close($con);
	?>
</body>
</html>
<?php
}
?>