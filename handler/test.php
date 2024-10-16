<?php
require_once ("../config/config.php");
// $word = '«ю».';
// echo (mb_substr($word,1,-1));

$str = "Закан-Юрт";
$str2 = "закан-йурт";

$word = "юртӀАП..)!!";

if ( preg_match_all('/^[^А-яӀ]+|[^А-яӀ]+$/u', $word, $matches) ) {\

    print_r($matches);
    // echo "<br>";
    // $word = preg_replace('/^[^А-яӀ]+|[^А-яӀ]+$/u', '', $word);
    // print_r($word);
}
else echo "Ничего нет";

echo '<br><br>';

$word2 = "123АюртӀАП123";

if ( preg_match('/^[^А-яӀ]+/u', $word2, $matches) ) {
    print_r($matches);
    echo "<br>";
    $word = preg_replace('/^[^А-яӀ]+/u', '', $word);
    print_r($word);
} else 'Ничего нет';


// echo letter_to_upper($str,$str2);
