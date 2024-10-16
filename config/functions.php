<?php

// Функция для очистки переменной
function clear($var) {
    // $var = mb_strtolower($var, "UTF-8");
    $var = trim($var);
    $var = strip_tags($var);
    // $var = htmlentities($var, ENT_IGNORE, "UTF-8");
    $var = stripcslashes($var);
    $var = replace_lat_to_kir($var);
    $var = replace_I($var);
    return $var;
    // return mysqli_real_escape_string($db, $var);
}

// Функция замены чеченской буквы I
function replace_I($string) {
    if ( preg_match_all('/[А-я][1Iiӏ]|[1Iiӏ][А-я]/u', $string, $matches) ) {
        
        $search_array = Array('1','I','i','І','і','ӏ','ӏ');
        $match_array = $matches[0];

        $match_replaced_array = str_replace($search_array, "Ӏ", $match_array);
        $string = str_replace($match_array, $match_replaced_array, $string);
    }
    return $string;
}

// Функция замены латинских символов на кириллические
function replace_lat_to_kir($text) {
    if ( preg_match('/[AaBCcEeHKMOoPpTXxYy]/u',$text) ){
        $lat_array = ['A','a','B','C','c','E','e','H','K','M','O','o','P','p','T','X','x','Y','y'];
        $kir_array = ['А','а','В','С','с','Е','е','Н','К','М','О','о','Р','р','Т','Х','х','У','у'];

        // Делим текст на абзацы по знаку абзаца
        $paragraphs_array = explode("\n", $text);
        $new_paragraphs_array = Array();

        // Перебираем абзацы
        foreach ($paragraphs_array as $key => $paragraph) {
            // Делим абзацы на слова по пробелам
            $words_array = mb_split('\s+', $paragraph);
        
            // Перебираем слова абзаца
            foreach ($words_array as $key => $word) {

                if ( preg_match_all('/[AaBCcEeHKMOoPpTXxYy]/u', $word, $matches) && preg_match('/[А-я]/u', $word) ) {
                    $word = str_replace($lat_array, $kir_array, $word);
                }

                // Обратно собираем слова в массив
                $words_array[$key] = $word;
            }

            // Обратно собираем абзацы в массив
            $new_paragraph = implode(' ', $words_array);
            $new_paragraphs_array[] = $new_paragraph;
        }

        // Обратно собираем весь текст
        $text = implode("\n", $new_paragraphs_array);
    
    }
    return $text;
}

// Функция определения является ли первая буква ЗАГЛАВНОЙ
function fl_is_upper($word) {
    $first_letter = mb_substr($word, 0, 1);
    if (mb_strtolower($first_letter) !== $first_letter) return True;
    else return False;
}

// Функция для перевода первой буквы строки в верхний регистр
function fl_to_upper($str) {
    return mb_strtoupper(mb_substr($str, 0, 1)) . mb_substr($str, 1);
}

// Функция проверяет на наличие в слове буквы в верхнем регистре, если есть переводит нужную букву в верхний регистр.
function letter_to_upper($word, $new_word) {
    if ( preg_match('/[\-\_\–]/u', $new_word, $matches) ) {
        $defis = $matches[0];
        $defis_pos = mb_strpos($word, $defis);
        $word1 = mb_substr($word, 0, $defis_pos);
        $word2 = mb_substr($word, $defis_pos+1);

        $defis_pos = mb_strpos($new_word, $defis);
        $word_new1 = mb_substr($new_word, 0, $defis_pos);
        $word_new2 = mb_substr($new_word, $defis_pos+1);

        if ( fl_is_upper($word1) ) $word_new1 = fl_to_upper($word_new1);
        if ( fl_is_upper($word2) ) $word_new2 = fl_to_upper($word_new2);
        
        return $word_new1 . $defis . $word_new2;
    }

    if ( fl_is_upper($word) ) return fl_to_upper($new_word);
    return $new_word;
}

// Функция преобразования слова по паттернам регулярного выражения
function convert_word($word) {
$regexp_array = [
        ['/йа/iu','йъа'],
        ['/йу/iu','йъу'],
        ['/^я/iu','йа'],
        ['/^ё/iu','йо'],
        ['/^ю/iu','йу'],
        ['/^е/iu','йе'],
        ['/[йуеыаоэяию-]я/iu','/я/iu','йа'],
        ['/[йуеыаоэяию-]ё/iu','/ё/iu','йо'],
        ['/[йуеыаоэяию-]е/iu','/е/iu','йе'],
        ['/[йуеыаоэяию-]ю/iu','/ю/iu','йу'],
        ['/[^ к]ъя/iu','/ъя/iu','йа'],
        ['/[^ к]ъё/iu','/ъё/iu','йо'],
        ['/[^ к]ъю/iu','/ъю/iu','йу'],
        ['/[^ к]ъе/iu','/ъе/iu','йе'],
        ['/[^ х]ья/iu','/ья/iu','йа'],
        ['/[^ х]ьё/iu','/ьё/iu','йо'],
        ['/[^ х]ью/iu','/ью/iu','йу'],
        ['/[^ х]ье/iu','/ье/iu','йе']
    ];

    $word_all_replaced = $word;
    $words_array = Array();
    $words_all_array = Array();
    $matches_array = Array();
    $ye_replaced = False;
    
    foreach ( $regexp_array as $regexp_item ) {
        
        $search_regexp = $regexp_item[0];
        $pattern = count($regexp_item) == 2 ? $regexp_item[0] : $regexp_item[1];
        $replacement = count($regexp_item) == 2 ? $regexp_item[1] : $regexp_item[2];
        
        if ( preg_match_all($search_regexp, $word, $matches, PREG_OFFSET_CAPTURE) ) { 

            foreach ( $matches[0] as $matche ) {
                $matches_array[] = $matche;
                $match_pos = $matche[1] / 2;
                $word_left = mb_substr($word, 0, $match_pos);
                $word_right = mb_substr($word, $match_pos+mb_strlen($matche[0]));
                $words_array[] = $word_left . preg_replace($pattern, $replacement, $matche[0], 1) . $word_right;
                
            }

            // Манипуляции для всех замен в слове
            $offset = 0;
            if ($ye_replaced && $pattern == '/е/iu') $offset = 4;
            preg_match_all($search_regexp, $word_all_replaced, $matches_all_replaced, PREG_OFFSET_CAPTURE, $offset);
            
            $matche_replaced_more = False;
            foreach ( $matches_all_replaced[0] as $matche ) {
                $match_pos = $matche[1] / 2;
                $matche_replaced = preg_replace($pattern, $replacement, $matche[0], 1);
                if ($matche_replaced_more) $match_pos += 1;
                $word_all_replaced = mb_substr($word_all_replaced, 0, $match_pos).
                                     $matche_replaced.
                                     mb_substr($word_all_replaced, $match_pos+mb_strlen($matche[0]));

                if (!in_array($word_all_replaced, $words_array)) $words_all_array[] = $word_all_replaced;
               

                if (mb_strlen($matche[0]) < mb_strlen($matche_replaced) ) $matche_replaced_more = True;
            }

            // for ($i=0; $i < count($matches_array); $i++) { 
            //     for ($j=$i; $j < count($matches_array); $j++) {
                    
            //     }
            // } 
            
            if ($pattern == '/^е/iu') $ye_replaced = True;

        }

    }
    if ( count($words_array) > 1 ) $words_array = array_merge($words_array, $words_all_array);

    return $words_array;
}