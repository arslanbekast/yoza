<?php
require_once ("../config/config.php");
set_time_limit(3600);

if (isset($_POST['text'])) {
    $text = clear($_POST['text']);
    $text = preg_replace("/\n+/u","\n",$text);

    // Делим текст на абзацы по знаку абзаца
    $paragraphs_array = explode("\n", $text);
    $new_paragraphs_array = Array();
    
    // Перебираем абзацы
    foreach ($paragraphs_array as $key => $paragraph) {

        // Делим абзацы на слова по пробелам
        $words_array = mb_split('\s+', $paragraph);
    
        // Перебираем слова
        foreach ($words_array as $key => $word) {
            
            // Отделяем до и после слова лишние знаки, если есть
            $before_chars = '';
            $after_chars = '';
            $before_chars_pattern = '/^[^А-яӀ]+/u';
            $after_chars_pattern = '/[^А-яӀ]+$/u';
            // Получаем знаки перед словом 
            if ( preg_match($before_chars_pattern, $word, $matches) ) {
                $before_chars = $matches[0];
                $word = preg_replace($before_chars_pattern, '', $word);
            }
            // Получаем знаки после слова        
            if ( preg_match($after_chars_pattern, $word, $matches) ) {
                $after_chars = $matches[0];
                $word = preg_replace($after_chars_pattern, '', $word);
            }


            // В базе проверяем только слова, в которых есть символы в условии 
            if (preg_match('/(йа|йу)/iu', $word) or 
                preg_match('/^[еёюя]/iu', $word) or
                preg_match('/[йуеаоэяию-]е|[йуеаоэяию-]ё|[йуеаоэяию-]ю|[йуеаоэяию-]я/iu', $word) or
                preg_match('/([^ к]ъе|[^ к]ъё|[^ к]ъю|[^ к]ъя)/iu', $word) or
                preg_match('/([^ х]ье|[^ х]ьё|[^ х]ью|[^ х]ья)/iu', $word)) {

                // Проверяем на наличие слова в базе в таблице orthographic_dictionary и user_words_pro,
                // если в базе есть это слово, переходим к другому слову.
                $word_mres = mysqli_real_escape_string($db, $word); // Промежуточная очищенная переменная
                $result = mysqli_query($db, "SELECT word FROM orthographic_dictionary WHERE word='$word_mres'");
                if (mysqli_num_rows($result) == 0) $result = mysqli_query($db, "SELECT word FROM user_words_pro WHERE word='$word_mres'");
                
                if ( mysqli_num_rows($result) > 0 )  {
                    $words_array[$key] = $before_chars . $word . $after_chars;
                    continue;
                }
                else {
                    $word_is_replaced = False;

                    // Получаем массив преобразованных слов по паттернам регулярного выражения
                    $new_words_array = convert_word($word);
                    
                    // Перебираем преобразованные слова и проверяем на наличие в БД
                    foreach ($new_words_array as $new_word) {
                        
                        $new_word_mres = mysqli_real_escape_string($db, $new_word); // Промежуточная очищенная переменная
                        $result = mysqli_query($db, "SELECT word FROM orthographic_dictionary WHERE word='$new_word_mres'");
                        if (mysqli_num_rows($result) == 0) $result = mysqli_query($db, "SELECT word FROM user_words_pro WHERE word='$new_word_mres'");
                        $row = mysqli_fetch_assoc($result);
                        // Если новое слово есть в базе, заменяем слово на новое слово
                        if ( mysqli_num_rows($result) > 0 ) {
                            $new_word = $row['word'];
                            // Если в слове первая буква ЗАГЛАВНАЯ, 
                            // в новом слове переводим первую букву в ЗАГЛАВНУЮ.
                            $new_word = letter_to_upper($word,$new_word);
                            $words_array[$key] = $before_chars . "<span class='yellow'>$new_word</span>" . $after_chars;
                            $word_is_replaced = True;
                            break;
                        }
  
                    }
                    
                    if (!$word_is_replaced) $words_array[$key] = $before_chars . "<span class='no-db-word red' title='Нажмите на правую кнопку мыши'>$word</span>" . $after_chars;
     
                }
            }
            

        }
        $new_paragraph = implode(' ', $words_array);
        $new_paragraphs_array[] = "<p>".$new_paragraph."</p>";

    }

    $result_text = implode(' ', $new_paragraphs_array);
    echo $result_text;
}

?>