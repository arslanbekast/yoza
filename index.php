<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css?v=<?php echo filectime('css/style.css'); ?>">
    <link rel="shortcut icon" href="img/favicon.png" type="image/png">
    <title>Йоза - программа для перевода чеченского текста из старой орфографии на новую.</title>
</head>
<body>
    <header>
        <div class="header container">
            <div class="header__logo">
                <a href="/yoza/">
                    <div class="header__logo-img"><img src="img/logo.png" alt="Логотип"></div>
                    <div class="header__title">
                        <h1>Йоза</h1>
                    </div>
                </a>
            </div>
            
            <div class="header__log_in-log_out">
                <a href="#login" class="header__log_in_btn">Вход на сайт</a>
            </div>
            <!-- <div class="header__log_out">
                <span class="header__log_out_text">Администратор</span>
                <a href="#logout" class="header__log_out_btn">Выйти</a>
            </div> -->
        </div>
    </header>
    <div class="open-file container">
         

        <a href='https://ps95.ru/dikdosham' class='goBackDikdosham'>Вернуться в ДикДошам</a>
    
        <label for="file-upload_input" class="open-file__btn">
            <input type="file" class="open-file__open" name="user_file" id="file-upload_input" accept=".txt, .docx">
            <div class="open-file__btn-icon"></div>
            <div class="open-file__btn-text">
                <span class="open-file__btn-text-open">Открыть файл</span> 
                <span class="open-file__btn-text-extensions">.docx, .txt</span>       
            </div>
        </label>

    </div>
    <main>
        <div class="text-fields container">
            <div class="text-field__in">
                <div class="text-in__preloader"></div>
                <form action="" method="post" id="text-in-form">
                    <div class="text-field__header">
                        <label for="text-in">Исходный текст</label>
                    </div>
                    <textarea name="text-in" id="text-in" spellcheck="false" placeholder="Вставьте чеченский текст на основе старой орфографии. В соседнем окне появится этот текст на основе новой орфографии" data-file="Измененный текст-Yoza"></textarea>
                    <div class="text-field__in-form-btn">
                        <button>Изменить</button>
                        <button class="text-field__in-btn-reset" type="reset">Очистить</button>
                    </div>
                    
                </form>
            </div>
            <div class="text-field__out">
                    <div class="text-field__header">
                        <label for="text-out">Преобразованный текст</label>
                        <div class="save-as">
                            <button class="save-as__btn">Сохранить как...</button>
                            <ul class="save-as__list">
                                <li class="save-as__item"><a href="" class="save-as__link save-as__link--txt" data-ext="txt">Текстовый документ (.txt)</a></li>
                                <li class="save-as__item"><a href="" class="save-as__link save-as__link--docx" data-ext="docx">Документ Microsoft Word (.docx)</a></li>
                            </ul>
                        </div>
                        
                    </div>
                    
                    <div id="text-out" contenteditable="true" spellcheck="false"></div>
                    <div class="text-field__out-btns">
                        <button class="text-field__out-btn_save">Завершить изменения</button>
                        <button class="text-field__out-btn_copy" title="Копировать в буфер обмена"></button>
                       
                        
                    </div>
                    <!-- Контекстное  меню -->
                    <div class="context-menu">
                        <ul class="context-menu__list">
                        
                        </ul>
                        <div class="context-menu__footer">
                            <button id="not-replace-btn" class="context-menu__button">Пропустить</button>
                            <!-- <button id="not-replace-add-word-btn" class="context-menu__button">Добавить в словарь</button>   -->
                        </div>
                    </div>
            </div>
        </div>
    </main>


    <div class="log_in__back">
        <div class="log_in__body">
            <button class="log_in__close-btn"></button>
            <h3 class="log_in__title">Вход на сайт</h3>
            <p class="log_in__subtitle">На сайт могут войти только те, <br>у кого есть доступ.</p>
            <form action="" method="post" class="log_in__form" id="log_in__form">
                <div class="log_in__form_input">
                    <input type="text" name="login" pattern="[a-z]{3,100}" maxlength="100" placeholder="Введите логин" autocomplete="off" required>
                </div>
                <div class="log_in__form_input">
                    <input type="password" name="password" pattern="[a-zA-Z0-9]{3,12}" maxlength="12" placeholder="Введите пароль" required>
                </div>
                <div class="log_in__form_btn">
                    <button>Войти на сайт</button>
                </div>
                
            </form>
            <div class="log_in__error"></div>
        </div>
    </div>

    <script src="js/jquery-3.6.0.min.js"></script>
    <script src="js/main.js?v=<?php echo filectime('js/main.js'); ?>"></script>
    <!-- <script>
        var link = document.createElement('a');
        link.setAttribute('href', 'http://hello-site.ru/main/images/logo.jpg');
        link.setAttribute('download', 'filename');
        onload = link.click();
    </script> -->
</body>
</html>