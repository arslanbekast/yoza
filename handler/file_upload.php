<?php
setlocale(LC_ALL, 'ru_RU.utf8');
require_once ("../libs/DocxConversion.php");
require_once ("../libs/pdf2text.php");
require_once ("../config/functions.php");

// Название <input type="file">
$input_name = 'user_file';
 
// Разрешенные расширения файлов.
$allow = array('txt','docx');
 
// Директория куда будут загружаться файлы.
$path = '../files/uploads/';

$error = $success = $name = $file_name = '';
if (!isset($_FILES[$input_name])) {
	$error = 'Файл не загружен.';
} else {
	$file = $_FILES[$input_name];
	
	// Проверим на ошибки загрузки.
	if (!empty($file['error']) || empty($file['tmp_name'])) {
		$error = 'Не удалось загрузить файл.';
	} elseif ($file['tmp_name'] == 'none' || !is_uploaded_file($file['tmp_name'])) {
		$error = 'Не удалось загрузить файл.';
	} else {
		// Оставляем в имени файла только буквы, цифры и некоторые символы.
		$pattern = "[^a-zа-яё0-9,~!@#%^-_\$\?\(\)\{\}\[\]\.]";
		$name = mb_eregi_replace($pattern, '-', $file['name']);
		$name = mb_ereg_replace('[-]+', '-', $name);

		$name = $file['name'];
		$parts = pathinfo($name);

		// Получаем расширение файла
        $extension = $parts['extension'];
		// Получаем имя файла без расширения и добавялем к имени файла слово "yoza"
		$file_name = $parts['filename'] . "-Yoza";

        // Генерируем уникальное имя файла
        $uniq_name = "file_".uniqid().".".$extension;
        
		if (empty($name) || empty($parts['extension'])) {
			$error = 'Недопустимый тип файла';
		} 
        elseif (!empty($allow) && !in_array(strtolower($extension), $allow)) {
			$error = 'Недопустимый тип файла';
		} 
        else {
			// Перемещаем файл в директорию.
			// if (move_uploaded_file($file['tmp_name'], $path . $name)) {
            if (move_uploaded_file($file['tmp_name'], $path . $uniq_name)) {

				// Далее считываем текст из файла в зависимости от типа                
                if ($extension == "docx") {
                    $file_docx = $path . $uniq_name;
                    $docObj = new DocxConversion($file_docx);
                    $success = $docText= $docObj->convertToText();
                    $success = mb_substr($success, 2, mb_strlen($success), 'UTF-8');
                    unlink($file_docx);
                }
                else if ($extension == "txt"){
                    $file_txt = $path . $uniq_name;
                    $success = file_get_contents($file_txt);
                    unlink($file_txt);
                }
                // else if ($extension == "pdf") {
                //     $file_pdf = $path . $uniq_name;
                //     $success = "Файлы PDF временно не поддерживаются. В скором времени это исправим.";
                //     unlink($file_pdf);
                // }
			} else {
				$error = 'Не удалось загрузить файл.';
			}
		}
	}
}


// Заменяем букву I на нужную
// if (!empty($success)) {
// 	$success = replace_lat_to_kir($success);
// 	$success = replace_I($success);
// }

$data = array(
	'error'   => $error,
	'success' => $success,
	'filename' => $file_name,
);
 
header('Content-Type: application/json');
echo json_encode($data, JSON_UNESCAPED_UNICODE);
?>