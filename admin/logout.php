<?php
session_start();
session_destroy();
header("Location: /cineplanet/index.php");
exit();
?>