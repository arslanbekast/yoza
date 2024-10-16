<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="css/admin-style.css">
    <link rel="shortcut icon" href="../img/favicon.png" type="image/png">
    <title>Административная панель Yoza</title>
</head>
<body>
    <header>
        <div class="header container">
            <div class="header__logo">
                <a href="/yoza/admin/">
                    <div class="header__logo-img"><img src="../img/logo.png" alt="Логотип"></div>
                    <div class="header__title">
                        <h1>Админ-панель Yoza</h1>
                    </div>
                </a>
            </div>
            <div class="header__text">
                <a href="/yoza/" class="header__text-link">Перейти на сайт</a>
            </div>
        </div>
    </header>
    <?php if (isset($_SESSION['login']) && isset($_SESSION['pass'])) { ?>
    <main>
        <div class="container">
            <div class="user-words-tables">
                <div class="user-words">
                    <div class="user-words__header">
                        <h3 class="user-words__title">Пользовательская база</h3>
                    </div>
                    <div class="user-words__table" id="user-words-table">
                        <!-- <div class="user_words__word">
                            <div contenteditable="true">Слово</div>
                            <div class="user_words__word-btns">
                                <button class="user_words__del-btn"></button>
                                <button class="user_words__add-btn">Добавить</button>
                            </div>  
                        </div> -->  
                    </div>
                </div>
                <div class="user-words-pro">
                    <div class="user-words__header">
                        <h3 class="user-words__title">База про</h3>
                    </div>
                    <div class="user-words__table" id="user-words-pro-table">
                        <!-- <div class="user_words__word">
                            <div contenteditable="true">Слово</div>
                            <div class="user_words__word-btns">
                                <button class="user_words__del-btn"></button>
                                <button class="user_words__add-btn">Добавить</button>
                            </div>  
                        </div> -->  
                    </div>
                </div>
            </div>
        </div>
    </main>
    <?php } else { 
        header("Location: https://ps95.ru/yoza/");
        exit("<meta http-equiv='refresh' content='0; url= /yoza/'>"); } 
    ?>
    <script src="../js/jquery-3.6.0.min.js"></script>
    <script src="js/admin_scripts.js"></script>
</body>
</html>