<?php
session_start();
require_once ("../../config/config.php");

if (isset($_POST['dbTable']) && !empty($_POST['dbTable'])) {
    
    if (isset($_SESSION['login']) && isset($_SESSION['pass'])) {

        $db_table =  mysqli_real_escape_string($db, $_POST['dbTable']);
        $words_array = array();
        
        $query = "SELECT id, word FROM $db_table";
        
        if ($db_table == 'user_words_pro') {
            $query = "SELECT id, word FROM $db_table ORDER BY id DESC LIMIT 100";
        }

        $result = mysqli_query($db, $query);

        while ($row = mysqli_fetch_assoc($result)) {
            $words_array[] = $row;
        }
       
        header('Content-Type: application/json');
        echo json_encode($words_array);

    }

}