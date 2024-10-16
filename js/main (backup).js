$(document).ready(yoza);

function yoza() {

    textSubmit();

    contextMenu();

    notReplaceBtnClick();

    contextMenuClose();

    replaceWordActive();

    loginWindowOpen();

    loginHandler();
    loginTrue();
    logoutHandler();

    formIsChange();
    copyText();
    
}

// Функция отправки текста на сервер
function textSubmit() {
    var preloader = '<div class="preloader"><img src="img/preloader.svg" alt="preloader"></div>';
    $('#text-in-form').on('submit', function (e) {
        var form = $(this);
        e.preventDefault();
        let text = $(this).find('#text-in').val();

        if (text != '') {
            jQuery.ajax({
                type: "POST",
                url: "handler/handler.php",
                data: {
                    text: text
                },
                beforeSend: function() {
                    $('.text-field__out #text-out').html(preloader);
                    $('.preloader').css('display','flex');
                    form.find('button').addClass('disable');
                },
                success: function (data) {
                    form.find('button').removeClass('disable');
                    $('#text-out').html(data);
                    $('.text-field__out-btns').fadeIn();
                }
            });
        } else {
            alert('Введите текст');
        }
    });
}

// Функция вызова контекстного меню
function contextMenu() {
    $('body').on('contextmenu', '.no-db-word', function (e) {
        e.preventDefault();
        let screenWidth = $(window).width();
        let contextMenuWidth = $('.context-menu').width();
        let contextMenuTop = e.pageY + 10 + 'px';
        let contextMenuLeft = e.pageX + 'px';
        let contextMenuLeftAndWidth = e.pageX + contextMenuWidth; 
        
        $('#text-out .current-no-db-word').removeClass('current-no-db-word');
        $(this).addClass('current-no-db-word');
        let word = $(this).text();

        jQuery.ajax({
            type: "POST",
            url: "handler/no_db_word_handler.php",
            data: {
                word: word
            },
            success: function (data) {
                let wordsArray = JSON.parse(data);
                
                $('.context-menu ul').html('');
                for (var i = 0; i < wordsArray.length; i++) {

                    $('.context-menu ul').append('<li class="replace-word"><a href="#">' + wordsArray[i] + 
                                                 '</a><button class="add-word-btn">Добавить в словарь</button></li>');
                }

                $('.add-word-btn').css("left","100%");

                // Если контекстное меню выходит за пределы экрана
                // выводим меню чуть левее
                if (contextMenuLeftAndWidth > screenWidth) {
                    contextMenuLeft = e.pageX - (contextMenuLeftAndWidth - screenWidth) + 'px';
                    $('.add-word-btn').css({"left":"auto","right":"100%"});
                }

                // Если кнопка "Добавить в словарь" выходит за пределы экрана
                // выводим эту кнопку слева от контекстного меню
                let addWordBtnWidth = $('.add-word-btn').width();
                if (contextMenuLeftAndWidth + addWordBtnWidth > screenWidth) {
                    $('.add-word-btn').css({"left":"auto","right":"100%"});
                }

                $('.context-menu').css({
                    top: contextMenuTop,
                    left: contextMenuLeft
                });
                $('.context-menu').fadeIn();

                replaceWordClick();
                addWordToDb(word);
                notReplaceAddWordBtnClick(word);
                

            }
        });

    });
}

function notReplaceBtnClick() {
    $(document).on('click', '#not-replace-btn', function () {

        notReplace();

    });
}

function notReplaceAddWordBtnClick(word) {
    $(document).off('click', '#not-replace-add-word-btn');
    $(document).on('click', '#not-replace-add-word-btn', function () {

        jQuery.ajax({
            type: "POST",
            url: "handler/add_word_handler.php",
            data: {
                notReplaceAddWord: word
            },
            success: function (data) {
                console.log(data);
        
                notReplace();
            }
        }); 

        

    });
}

function notReplace() {
    $('.current-no-db-word').removeClass('red');
    $('.current-no-db-word').removeClass('no-db-word');
    $('.current-no-db-word').removeClass('current-no-db-word');
    $('.context-menu').fadeOut();
}

function contextMenuClose() {
    $(document).on('click', function (e) {
        var conMenu = $('.context-menu');
        var noDbWord = $('.no-db-word');
        if (!conMenu.is(e.target) && conMenu.has(e.target).length === 0 && !noDbWord.is(e.target)) {
            $('.context-menu').fadeOut();
        }
    });
}

function replaceWordClick() {
    $(document).off('click', '.replace-word a');
    $(document).on('click', '.replace-word a', function (e) {
        e.preventDefault();
        let wordNew = $(this).text();
        
        replaceWord(wordNew);
    });
}

// Функция добавления слова в БД
function addWordToDb(wordOld) {
    $(document).off('click', '.add-word-btn');
    $(document).on('click', '.add-word-btn', function(){
        let wordNew = $(this).parent('li.replace-word').find('a').text();
        jQuery.ajax({
            type: "POST",
            url: "handler/add_word_handler.php",
            data: {
                wordOld: wordOld, wordNew: wordNew
            },
            success: function (data) {
                console.log(data);
        
                replaceWord(wordNew);
            }
        }); 
    });  
}

function replaceWord(wordNew) {
    $('.current-no-db-word').text(wordNew);
    $('.current-no-db-word').removeClass('red');
    $('.current-no-db-word').removeClass('no-db-word');
    $('.current-no-db-word').removeClass('current-no-db-word');
    $('.context-menu').fadeOut();
}

function replaceWordActive() {
    $(document).on('mouseover', '.add-word-btn', function(){
        $(this).parent('li.replace-word').find('a').addClass('replace-word-active');
    });
    $(document).on('mouseout', '.add-word-btn', function(){
        $(this).parent('li.replace-word').find('a').removeClass('replace-word-active');
    });
}

// Функция открытия окна для входа на сайт
function loginWindowOpen()  {
    $('.header__log_in_btn').on('click', function(e){
        e.preventDefault();
        $('.log_in__back').css('display','flex');
        $('.log_in__body').slideDown(200);  
    });

    $('.log_in__close-btn').on('click', function(){
        $('.log_in__body').slideUp(200);
        $('.log_in__back').fadeOut(400);
    });

    $(document).mouseup( function(e){ // событие клика по веб-документу
		var div = $( ".log_in__body" ); // тут указываем ID элемента
        // если клик был не по нашему блоку и не по его дочерним элементам
		if ( !div.is(e.target) && div.has(e.target).length === 0 ) { 
                // скрываем его
                $('.log_in__body').slideUp(200);
                $('.log_in__back').fadeOut(400); 
		}
	});
}

// Функция обработки входа на сайт
function loginHandler() {
    $('#log_in__form').on('submit', function (e) {
        var form = $(this);
        e.preventDefault();
        jQuery.ajax({
            type: "POST",
            url: "handler/login_handler.php",
            data: form.serialize(),
            // beforeSend: function() {
            //     $('.text-field__out #text-out').html('');
            //     $('.preloader').css('display','flex');
            //     form.find('button').addClass('disable');
            // },
            success: function (data) {
                if (data == 1) {
                    $('.log_in__body').slideUp(200);
                    $('.log_in__back').fadeOut(400);
                    var logoutHtml = `<span class="header__log_out_text">Администратор</span>
                                      <a href="#logout" class="header__log_out_btn">Выйти</a>`;
                    $('.header__log_in-log_out').html(logoutHtml);
                }
                else {
                    $('.log_in__error').text(data).fadeIn(300);
                }
            }
        });
    });
}

// Функция обработки выхода из сайта
function logoutHandler() {
    $(document).on('click', '.header__log_out_btn', function (e) {
        e.preventDefault();
        jQuery.ajax({
            type: "POST",
            url: "handler/logout_handler.php",
            data: {'logout': 1},
            success: function (data) {console.log(data);
                document.location.reload();
            }
        });
    });
}


// Функция проверки сессии
function loginTrue() {
    jQuery.ajax({
        type: "POST",
        url: "handler/session_handler.php",
        data: '',
        success: function (data) {
            if (data == 1) {
                var logoutHtml = `<span class="header__log_out_text">Администратор</span>
                                  <a href="#logout" class="header__log_out_btn">Выйти</a>`;
                $('.header__log_in-log_out').html(logoutHtml);
            }
        }
    });
}

// Функция отслеживания изменения формы
function formIsChange() {
    $(document).on('keydown', '.log_in__form_input input', function(){
        $('.log_in__error').fadeOut(200);
    });
}

// Функция копирования текста
function copyText() {
    $(document).on('click','.text-field__out-btn_copy',function(){
        // let text = $('#text-out').text();
        // let text = window.getSelection().anchorNode; 
        // navigator.clipboard.writeText(text);

        var target = document.querySelector('#text-out');
        var rng, sel;
        if (document.createRange) {
        rng = document.createRange();
        rng.selectNode(target);
        sel = window.getSelection();
        sel.removeAllRanges();
        sel.addRange(rng);
        } else {
        var rng = document.body.createTextRange();
        rng.moveToElementText(target);
        rng.select();
        }

        document.execCommand("copy");

    });
}
