<?php
session_start();
require_once ("../config/db.php");

// Проверка сессии
if (isset($_SESSION["login"]) && isset($_SESSION["pass"])) {
	
	$session_login = $_SESSION['login'];
	$session_pass = $_SESSION['pass'];
	$session_login = mysqli_real_escape_string($db, $session_login);
	$session_pass = mysqli_real_escape_string($db, $session_pass);
	$result = mysqli_query($db,"SELECT * FROM users WHERE login='$session_login' AND password='$session_pass'");
	$row = mysqli_fetch_assoc($result);
	if ($row) {
		$loged = true;	
	}
	else {
		$loged = false;
		session_unset();
		session_destroy();
		setcookie(session_name(), session_id(), time()-60*60*24);
	}
	echo $loged;	
}
else {
	$loged = false;
	session_destroy();
	setcookie(session_name(), session_id(), time()-60*60*24);
	echo $loged;
}
// КОНЕЦ Проверка сессии

?>