$(document).ready(yoza);

function yoza() {
    
//     $('.goBackDikdosham').on('click', function(e){
//         e.preventDefault();
// 		history.back();
		
// 	})

    textSubmit();
    textInClear();

    contextMenu();

    notReplaceBtnClick();

    contextMenuClose();

    // replaceWordActive();

    loginWindowOpen();

    loginHandler();
    loginTrue();
    logoutHandler();

    formIsChange();
    copyText();
    saveChanges();

    openFile();

    textInKeyHandler();
    textOutKeyHandler();

    saveAsFile();

    getFromLocalStorage();
    showTextoutBtns();
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
                    // Записываем данные в localstorage
                    saveToLocalStorage('text_out', data);
                    showTextoutBtns();
                }
            });
        } else {
            alert('Введите текст');
        }
    });
}
// Функция очистки текстового поля text-in
function textInClear() {
    $('#text-in-form').on('reset', function (e) {
        $('.text-field__in-btn-reset').hide();
        textoutClear();
        deleteFromoLocalStorage('text_in');
        deleteFromoLocalStorage('text_out');
        deleteFromoLocalStorage('filename');
    });
}
// Функция вывода кнопки Очистить
function showResetBtn() {
    let text = $('#text-in').val();
    if (text) {
        $('.text-field__in-btn-reset').show();
    }
}
// Функция вызова контекстного меню
function contextMenu() {
    $('body').on('click', '.no-db-word', function (e) {
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

                    $('.context-menu ul').append('<li class="replace-word"><a href="#">' + wordsArray[i] + '</a></li>');

                }
                
                $('.context-menu ul').append('<li class="replace-word"><a href="#">' + word + '</a></li>');


                $('.add-word-btn').css("left","100%");

                // Если контекстное меню выходит за пределы экрана
                // выводим меню чуть левее
                if (contextMenuLeftAndWidth > screenWidth) {
                    contextMenuLeft = e.pageX - (contextMenuLeftAndWidth - screenWidth) - 20 + 'px';
                }

                $('.context-menu').css({
                    top: contextMenuTop,
                    left: contextMenuLeft
                });
                $('.context-menu').fadeIn();

                replaceWordClick();
                

            }
        });

    });
}

// Функция обработки кнопки "Пропустить"
function notReplaceBtnClick() {
    $(document).on('click', '#not-replace-btn', function () {
        notReplace();
        saveToLocalStorageFromTextField('text_out');
    });
}

function notReplace() {
    $('.current-no-db-word').removeClass('red');
    $('.current-no-db-word').removeClass('no-db-word');
    $('.current-no-db-word').removeClass('current-no-db-word');
    $('.context-menu').fadeOut();
}

// Функция закрытия контекстного меню
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
        addWordToDb(wordNew);
        saveToLocalStorageFromTextField('text_out');
    });
}

// Функция добавления слова в БД
function addWordToDb(wordNew) {
    jQuery.ajax({
        type: "POST",
        url: "handler/add_word_handler.php",
        data: {
            wordNew: wordNew
        },
        success: function (data) {
            console.log(data);
        }
    }); 
}

// Функция замены слова
function replaceWord(wordNew) {
    $('.current-no-db-word').text(wordNew);
    $('.current-no-db-word').removeClass('red');
    $('.current-no-db-word').removeClass('no-db-word');
    $('.current-no-db-word').removeClass('current-no-db-word');
    $('.context-menu').fadeOut();
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
                    var logoutHtml = `<span class="header__log_out_text"><a href="admin/">Админ-панель</a></span>
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
                var logoutHtml = `<span class="header__log_out_text"><a href="admin/">Админ-панель</a></span>
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
        var rng2 = document.body.createTextRange();
        rng2.moveToElementText(target);
        rng2.select();
        }

        document.execCommand("copy");

    });
}

// Функция кнопки "Сохранить изменения"
function saveChanges() {
    $(document).on('click','.text-field__out-btn_save', function(){
        $('#text-out p span').removeClass('red');
        $('#text-out p span').removeClass('green');
        $('#text-out p span').removeClass('yellow');
        $('#text-out p span').removeClass('no-db-word');
        $('#text-out p span').removeClass('current-no-db-word');
        saveToLocalStorageFromTextField('text_out');
    });
}

// Обработка нажатия клавиши Backspace или Delete на форме ввода текста
function textInKeyHandler() {
    $('#text-in').on('keyup', function(e){
        var text = $('#text-in').val();
        if (e.keyCode == 8 || e.keyCode == 46) { 
            
            if (text == '') { 
                $(this).attr('data-file','Измененный текст-Yoza');
                $('.text-field__in-btn-reset').hide();
                deleteFromoLocalStorage('text_in');
                textoutClear();
                deleteFromoLocalStorage('text_out');
            }
        }
        if (text) { 
            $('.text-field__in-btn-reset').show();
            saveToLocalStorageFromTextField('text_in'); 
        }
    });
}


// Обработка нажатия клавиши Backspace или Delete на форме вывода измененного текста
function textOutKeyHandler() {
    $('#text-out').on('keyup', function(e){
        var text = $('#text-out').text();
        if (e.keyCode == 8 || e.keyCode == 46) { 
            
            if (text == '') { 
                hideTextoutBtns();
                deleteFromoLocalStorage('text_out');
            }
        }
        if (text) { saveToLocalStorageFromTextField('text_out'); }
        
    });
}

// Функция открытия файла
function openFile() {
    $('#file-upload_input').change(function(){
        var preloader = '<div class="preloader"><img src="img/preloader.svg" alt="preloader"></div>';
        var value = $(this).val();
        if (value !== "") {
            var formData = new FormData();
            formData.append('user_file', $("#file-upload_input")[0].files[0]);
            
            $.ajax({
                type: "POST",
                url: 'handler/file_upload.php',
                cache: false,
                contentType: false,
                processData: false,
                data: formData,
                dataType : 'json',
                beforeSend: function() {
                    $('#text-in').val('');
                    $('.text-in__preloader').html(preloader).fadeIn();
                    $('.preloader').css('display','flex');
                    $('.open-file__btn').addClass('disable');
                },
                success: function(msg){

                    $('.open-file__btn').removeClass('disable');
                    $('.text-in__preloader').html('').fadeOut();
                    $('.preloader').css('display','none');

                    if (msg.error == '') {
                        let text = msg.success;
                        let fileName = msg.filename;
                        $('#text-in').val(text);
                        saveToLocalStorage('text_in', text, fileName);
                        $('#text-in').attr('data-file', fileName);
                        $('.text-field__in-btn-reset').show();
                    } else {
                        alert(msg.error);
                    }
                }
            });
        }
        

    });
}

// Функция сохранения файла
function saveAsFile() {
    
    $('.save-as__btn').on('click', function(){
        $( this ).toggleClass( "save-as__btn--active" );
        // $( '.save-as' ).toggleClass( "save-as--active" );
        var display = $( ".save-as__list" ).css('display');
  
        if (display === 'none') {
            $( ".save-as__list" ).slideDown( 200 );
        }
        else if(display === 'block'){
            $( ".save-as__list" ).slideUp( 200 );
        }
 
    });

    $(document).mouseup( function(e){  // событие клика по веб-документу
        var saveAs = $( ".save-as" ); // тут указываем ID элемента
        var display = $( ".save-as__list" ).css('display');
        // если клик был не по нашему блоку и не по его дочерним элементам
        if ( !saveAs.is(e.target) && saveAs.has(e.target).length === 0 ) { 
                // скрываем его
                if (display === 'block'){
                    $( ".save-as__list" ).slideUp( 200 );
                    $('.save-as__btn').removeClass(' save-as__btn--active ');
                }
        }
    });

    $('.save-as__link').on('click', function(e) {
        e.preventDefault();
        var thisLink = $(this);
        var fileName = $('#text-in').attr('data-file');
        var extension = thisLink.attr('data-ext');
        var text = $('#text-out').text();
        fileName = fileName + "." + extension;
        
        // if (extension == 'docx') {
        //     text = $('#text-out').html();
        // }

        if (text !== '') {

            // Получаем в массив все абзацы текста
            var paragraphsArray = [];
            var paragraphs = document.querySelector('#text-out').querySelectorAll('p');
            paragraphs.forEach((p) => {
                paragraphsArray.push( p.innerText );
            });
            var paragraphsArrayJSON = JSON.stringify(paragraphsArray);
            
            $.ajax({
                type: "POST",
                url: 'handler/file_save.php',
                data: {ext: extension, text: paragraphsArrayJSON},
                dataType : 'json',
                beforeSend: function() {
                    thisLink.addClass('save-as__link--preloader');
                    thisLink.addClass('save-as__link--disable');
                },
                success: function(data){

                    var error = data.error;
                    var linkToFile = data.linktofile;
                    

                    thisLink.removeClass('save-as__link--preloader');
                    thisLink.removeClass('save-as__link--disable');

                    if (error == '') {
                        var link = document.createElement('a');
                        link.setAttribute('href', linkToFile);
                        link.setAttribute('download', fileName);
                        link.click();
                        $('.save-as__btn').click();
                    } else {
                        alert(data.error);
                    }
                }
            });

        }
        

    });
    
}

// Функция вывода кнопок управления в окне text-out, если поле не пустое
function showTextoutBtns() {
    let text = $('#text-out').html();
    if (text !== '') {
        $('.text-field__out-btns').css("display","flex");
        $('.save-as').css("display","block");
    }
}

// Функция скрытия кнопок управления в окне text-out, если поле не пустое
function hideTextoutBtns() {
    let text = $('#text-out').html();
    if (text == '') {
        $('.text-field__out-btns').fadeOut(); 
        $('.save-as').fadeOut();
    }
}
// Функция очистки формы text-out
function textoutClear() {
    $('#text-out').html('');
    hideTextoutBtns();
}

// Функция сохранения данных в LocalStorage
function saveToLocalStorage(key, value, filename='') {
    try {
        localStorage.setItem(key, value);
        if (filename !== '') {
            localStorage.setItem('filename', filename);
        }
    } catch (error) {
        alert('Не хватило памяти для сохранения изменений'); 
    }
    
}

// Функция сохранения данных в LocalStorage из полей ввода text-in или text-out
function saveToLocalStorageFromTextField(key) {
    let text = $('#text-in').val();
    if (key === 'text_out') {
        text = $('#text-out').html();
    }
    try {
        localStorage.setItem(key, text);
    } catch (error) {
        alert('Не хватило памяти для сохранения изменений');
    }
    
}

// Функция удаления данных из LocalStorage
function deleteFromoLocalStorage(key) {
    if ( localStorage.getItem(key) ) {
        localStorage.removeItem(key);
    }
}

// Функция получения данных из LocalStorage
function getFromLocalStorage() {
    if (localStorage.getItem('text_in')) {
        $('#text-in').val( localStorage.getItem('text_in') );
        showResetBtn();
    } 
    if (localStorage.getItem('text_out')) {
        $('#text-out').html( localStorage.getItem('text_out') );
    } 
    if (localStorage.getItem('filename')) {
        let fileName = localStorage.getItem('filename');
        $('#text-in').attr('data-file', fileName);
    }
}
