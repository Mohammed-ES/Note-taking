<?php
session_start();
session_unset();
session_destroy();
header("Location: ../auth/login.php");  // Correction du chemin
exit();
?>
