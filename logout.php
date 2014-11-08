<!doctype html>
<html lang=''>
<head>

    <?php
            session_start();
            $_SESSION["role"] = "";
			session_unset();
			session_destroy();
    ?>
    <script type="text/javascript">
        function logout() {
            window.top.location.href = "login.html";
        }
    </script>
</head>
<body onload="logout()">
    
</body>
</html>