<?php
require_once ("../config/config.php");
require_once ("../libs/PHPDocx/PHPDocx_0.9.2.php");

if (isset($_POST['text']) && isset($_POST['ext'])) {

    $error = $success = $link_to_file = $file_name = '';
    $text_array = json_decode($_POST['text']);
    $ext =  $_POST['ext'];
    
    $path = '../files/downloads/';
    $path_js = 'files/downloads/';
    
    // Генерируем уникальное имя файла
    $uniq_name = "Yoza_".uniqid() . "." . $ext;
    $file_name = $path . $uniq_name;

    if ($ext == 'txt') {
        foreach ($text_array as $paragraph) {
            file_put_contents($file_name, $paragraph . PHP_EOL, FILE_APPEND);
        }
    }
    elseif ($ext == 'docx') {
        // Создаем и пишем в файл. Деструктор закрывает
        $docx_file = new WordDocument( $file_name );
        foreach ($text_array as $paragraph) {
            $docx_file->assign($paragraph);
        }
        $docx_file->create();
    }
    else {
        $error = 'Данный тип файла не поддерживается.';
    }

    if (file_exists($file_name)) {
        $link_to_file = $path_js . $uniq_name;
        $query = "INSERT INTO files(file_name) VALUES('$uniq_name')";
        $result = mysqli_query($db, $query);
    }
    else {
        $error = 'По техническим причинам не удалось создать файл. Попробуйте еще раз.';
    }
    
    $data = array(
        'error'   => $error,
        'linktofile' => $link_to_file,
    );
    
    header('Content-Type: application/json');
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    
}

?>