<?php
require_once ("../config/config.php");

$path = '../files/downloads/';
// Получаем записи в таблице files, котоорым больше 1 дня.
$query = "SELECT id,file_name FROM files WHERE file_date <= DATE_SUB(NOW(), INTERVAL 1 DAY);";
$result = mysqli_query($db, $query);
// $row = mysqli_fetch_assoc($result);

while ($row = mysqli_fetch_assoc($result)) {
    $file_id = $row['id'];
    $file_name = $path . $row['file_name'];

    // Удаляем запись в таблице files
    mysqli_query($db, "DELETE FROM files WHERE id='$file_id'");

    // Удаляем сам файл
    if (file_exists($file_name)) {
        unlink($file_name);
    }
}
