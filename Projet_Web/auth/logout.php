<?php
session_start();
session_unset();
session_destroy();
// le chemin correct
header("Location: ../auth/login.php");
exit();
?>
