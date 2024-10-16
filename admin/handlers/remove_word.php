<?php
session_start();
require_once ("../../config/config.php");

if (isset($_POST['wordId']) && !empty($_POST['wordId']) && isset($_POST['parentId']) && !empty($_POST['parentId'])) {

	if (isset($_SESSION['login']) && isset($_SESSION['pass'])) {

		$error = $success = '';

		$word_id = intval($_POST['wordId']);
		$parentId =  mysqli_real_escape_string($db, $_POST['parentId']);
		$db_table = 'user_words';
		if ($parentId == 'user-words-pro-table') $db_table = 'user_words_pro';

		$query = "DELETE FROM $db_table WHERE id = '$word_id'";

	    if (mysqli_query($db, $query)) {
	     	$success = True;
	    } else {
	    	$error = 'По техническим причинам не удалось удалить слово. Попробуйте еще раз.';
	    }

	    $data = array(
	        'error'   => $error,
	        'success' => $success,
	    );
	    
	    header('Content-Type: application/json');
	    echo json_encode($data, JSON_UNESCAPED_UNICODE);

	}

}