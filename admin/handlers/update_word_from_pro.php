<?php
session_start();
require_once ("../../config/config.php");

if (isset($_POST['wordId']) && !empty($_POST['wordId']) && isset($_POST['word']) && !empty($_POST['word'])) {
	
	if (isset($_SESSION['login']) && isset($_SESSION['pass'])) {

		$error = $success = '';

		$word_id = intval($_POST['wordId']);
		$word = mysqli_real_escape_string($db, clear($_POST['word']));
		$user_word_table = 'user_words_pro';

		// Добавляем слово в таблицу user_words_pro, если такого слова нет в этой таблице
	    $query = "UPDATE $user_word_table SET word = '$word' WHERE id = '$word_id'";
	    
	    // Если слово добавилось в таблицу user_words_pro, удаляем это слово с таблицы user_words
	    if (mysqli_query($db, $query)) {
	    	$success = True;
	    } else {
	    	$error = 'По техническим причинам не удалось обновить слово в базе Про. Попробуйте еще раз.';
	    }

	    $data = array(
	        'error'   => $error,
	        'success' => $success,
	    );
	    
	    header('Content-Type: application/json');
	    echo json_encode($data, JSON_UNESCAPED_UNICODE);
	    

	}

}

