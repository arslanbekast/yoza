<?php
session_start();

// Обработка выхода
if (isset($_POST['logout'])) {
	$logout = true;
	if ($logout) {
		session_unset();
		session_destroy();
		setcookie(session_name(), session_id(), time()-60*60*24);
	}
}
// КОНЕЦ Обработка выхода

?>