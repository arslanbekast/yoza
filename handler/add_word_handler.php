<?php
session_start();
require_once ("../config/config.php");

// Инициализация переменных
$user_word_table = 'user_words';

if (isset($_SESSION['login']) && isset($_SESSION['pass'])) $user_word_table = 'user_words_pro';

// Добавляем заменяемое слово в БД
if (isset($_POST['wordNew']) && !empty($_POST['wordNew'])) {

    $word_new = clear($_POST['wordNew']);
    $word_new = mysqli_real_escape_string($db, $word_new);
    
    // Добавляем заменяемое слово в БД, если такого слова нет в базе
    $query = "INSERT INTO $user_word_table(word) SELECT '$word_new' FROM $user_word_table WHERE word='$word_new' HAVING COUNT(*)=0";
    
    $result = mysqli_query($db, $query);
    echo $result;
}