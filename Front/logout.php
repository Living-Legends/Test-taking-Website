<?php
session_start();

error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
ini_set('display errors', 1);

$sidvalue = session_id();
$_SESSION = array();
session_destroy();
header("Location: https://afsaccess4.njit.edu/~vs653/ProjCand/loginForm.php");
exit();
?>