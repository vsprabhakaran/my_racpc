<html>
<head>
<?php
session_start();
$con = new mysqli("localhost", "root", "", "racpc_automation_db");
if ($con->connect_errno) {
    die("Connection failed: " . $conn->connect_error);
}
$pfno=$_POST["pfno"];
$defaultHash=md5('12345');
$password=$_POST["password"];
$passwordHash= md5($password);
$query=mysqli_query($con,"select adms_password,adms_role,status_flag from adms_user_mstr where pf_index = '$pfno'");
$row = mysqli_fetch_array($query);
$role = $row['adms_role'];
	if($row['status_flag']=='C')
	{
		?>
			<script type="text/javascript">
				alert("Your account is not yet approved by UCO.");
			</script>
			<meta http-equiv="refresh" content="0;URL=login.php">
		<?php
	}
	else if($row['status_flag']=='D')
	{
		?>
			<script type="text/javascript">
				alert("Your account is disabled.");
			</script>
			<meta http-equiv="refresh" content="0;URL=login.php">
		<?php
	}
	else if($row['status_flag']=='E' && $passwordHash == $defaultHash)
{
		$_SESSION["pfno"]=$pfno;
		?>
			<script type="text/javascript">
				alert("Change Default Password.");
			</script>
			<meta http-equiv="refresh" content="0;URL=changePassword.php">
		<?php
	}
	else if($row['status_flag']=='A' && $password != "" && $row['adms_password']==$passwordHash && $role !="" )
	{
	$_SESSION["pfno"]=$pfno;
    switch($role)
    {
        case "RACPC_ADMIN":
        {
            $_SESSION["role"] = "RACPC_ADMIN" ;
            ?><meta http-equiv="refresh" content="0;URL=admin/adminPage.php"><?php
            break;
        }
        case "RACPC_UCO":
        {
            $_SESSION["role"] = "RACPC_UCO";
            ?><meta http-equiv="refresh" content="0;URL=uco/ucoPage.php"><?php
            break;
        }
        case "RACPC_VIEW":
        {
            $_SESSION["role"] = "RACPC_VIEW";
            ?><meta http-equiv="refresh" content="0;URL=docmgr/docViewPage.php"><?php
            break;
        }
        case "BRANCH_VIEW":
        {
            $_SESSION["role"] = "BRANCH_VIEW";
            ?><meta http-equiv="refresh" content="0;URL=docmgr/docViewPage.php"><?php
            break;
        }
        case "RACPC_DM":
        {
            $_SESSION["role"] = "RACPC_DM";
            ?><meta http-equiv="refresh" content="0;URL=docmgr/docManagerPage.php"><?php
            break;
        }
        default:
        {
            ?>
                <script type="text/javascript">
	                alert("User role not found.");
                </script>
                <meta http-equiv="refresh" content="0;URL=login.php">
            <?php
            break;
        }
    }
}
else
{
	?>
        <script type="text/javascript">
	        alert("Invalid username/password");
        </script>
        <meta http-equiv="refresh" content="0;URL=login.php">
<?php
}
$con->close();
?>
        
</head>
    <body>
        
</body>
</html>