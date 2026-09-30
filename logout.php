<?php
session_start();
session_destroy();
header("Location: /agripact/login.php");
exit();
?>
