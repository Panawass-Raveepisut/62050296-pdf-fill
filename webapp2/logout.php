<?php
session_start();
session_destroy();
header("location:role_selection.php");
?>