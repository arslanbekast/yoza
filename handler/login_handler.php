<?php
session_start();
require_once ("../config/db.php");

function clear_login($var) {
    $var = mb_strtolower($var, "UTF-8");
    $var = trim($var);
    $var = strip_tags($var);
    $var = htmlentities($var, ENT_IGNORE, "UTF-8");
    $var = stripcslashes($var);
    return $var;
    // return mysqli_real_escape_string($db, $var);
}
$loged = false;

// Обработка входа
if (isset($_POST['login']) && isset($_POST['password'])) {
    $login = clear_login($_POST['login']);
    $login = mysqli_real_escape_string($db, $login);
	$pass = clear_login($_POST['password']);
    $pass = mysqli_real_escape_string($db, $pass);
    $result = mysqli_query($db, "SELECT * FROM users WHERE login='$login' AND password='$pass'");
    $row = mysqli_fetch_assoc($result);
    if ($row) {
        $loged = true;
        $login = $row['login'];
        $pass = $row['password'];
        $_SESSION['login'] = $login;
        $_SESSION['pass'] = $pass;
        echo $loged;
    }
    else {
        echo "Неверный логин или пароль.";
    }
}
// КОНЕЦ Обработка входа