<?php
require_once ("../config/config.php");

if (isset($_POST['word'])) {

    $word = clear($_POST['word']);
    
    $words_array = convert_word($word);
    
    // Если первая буква слова ЗАГЛАВНАЯ, переводим первую букву всех,
    // предлагаемых для замены, слов в верхний регистр
    
    // Символ "&" перед перемнной $value означает,
    // что значения элементов массива сразу изменяются
    foreach ( $words_array as &$value ) {
        $value = letter_to_upper($word, $value);
    }
    unset($value);

    echo json_encode($words_array);

}