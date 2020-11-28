<?php
    session_start();
    if(isset($_SESSION['currentUserID']))
    {
        unset($_SESSION['currentUserID']);
        unset($_SESSION['currentUserRol']);
    }
    else
    {
        echo "<script>
                    alert(\"Error no hay usuarios logeados\");
            </script>";
    }
    echo "<script> window.location.href = \"index.php\"; </script>";
?>