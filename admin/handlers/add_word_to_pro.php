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
	    $query = "INSERT INTO $user_word_table(word) SELECT '$word' FROM $user_word_table WHERE word='$word' HAVING COUNT(*)=0";
	    
	    // Если слово добавилось в таблицу user_words_pro, удаляем это слово с таблицы user_words
	    if (mysqli_query($db, $query)) {
	    	$query = "DELETE FROM user_words WHERE id = '$word_id'";
	    	if (mysqli_query($db, $query)) {
	    		$success = True;
	    	} else {
	    		$error = 'По техническим причинам не удалось удалить слово из пользовательской базы. Попробуйте еще раз.';
	    	}
	    } else {
	    	$error = 'По техническим причинам не удалось добавить слово в базу Про. Попробуйте еще раз.';
	    }

	    $data = array(
	        'error'   => $error,
	        'success' => $success,
	    );
	    
	    header('Content-Type: application/json');
	    echo json_encode($data, JSON_UNESCAPED_UNICODE);
	    

	}

}

