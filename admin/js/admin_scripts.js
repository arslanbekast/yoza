$(document).ready(adminScripts);

function adminScripts() {

    loadWords('user_words');
    loadWords('user_words_pro');

    $(document).on('click', '.user_words__add-btn', function() {

        const wordId = $(this).parents('.user_words__word').attr('data-wordId');
        const word = $(this).parents('.user_words__word').children('.word').text();
        const wordBlock = $(this).parents('.user_words__word');
        
        addWordToPro(wordId, word, wordBlock);

    });

    $(document).on('click', '.user_words__del-btn', function() {

        const wordId = $(this).parents('.user_words__word').attr('data-wordId');
        const wordBlock = $(this).parents('.user_words__word');
        const parentId = wordBlock.parents('.user-words__table').attr('id');
        
        removeWord(wordId, wordBlock, parentId);

    });

    $(document).on('click', '.user_words__update-btn', function() {

        const wordId = $(this).parents('.user_words__word').attr('data-wordId');
        const word = $(this).parents('.user_words__word').children('.word').text();
        const wordBlock = $(this).parents('.user_words__word');
        
        updateWordToPro(wordId, word, wordBlock);

    });

}

function loadWords(dbTable) {
    let dbTableSelector = '#user-words-table';
    let btnAddUpdate = '<button class="user_words__add-btn">Добавить</button>'; 
    if (dbTable === 'user_words_pro') {
        dbTableSelector = '#user-words-pro-table';
        btnAddUpdate = '<button class="user_words__update-btn">Обновить</button>';
    }

    jQuery.ajax({
        type: "POST",
        url: "handlers/load_words.php",
        data: {dbTable: dbTable},
        dataType : 'json',
        // beforeSend: function() {
        //     $('.text-field__out #text-out').html(preloader);
        //     $('.preloader').css('display','flex');
        //     form.find('button').addClass('disable');
        // },
        success: function (data) {
            $(dbTableSelector).html('');
            data.forEach(wordObj => {

                $(dbTableSelector).append(`

                    <div class="user_words__word" data-wordId = ${wordObj.id}>
                        <div contenteditable="true" class="word">${wordObj.word}</div>
                        <div class="user_words__word-btns">
                            <button class="user_words__del-btn"></button>
                            ${btnAddUpdate}
                        </div>  
                    </div> 

                `);

            });
            
        }
    });

}

function addWordToPro(id, word, wordBlockSelector) {

    jQuery.ajax({
        type: "POST",
        url: "handlers/add_word_to_pro.php",
        data: {wordId: id, word: word},
        dataType : 'json',
        beforeSend: function() {
            wordBlockSelector.addClass('word_disabled');
        },
        success: function (data) {

            const error = data.error;
            const success = data.success;

            if (success) {
                loadWords('user_words');
                loadWords('user_words_pro');
            } else {
                alert(error);
            }
            wordBlockSelector.removeClass('word_disabled');
            
        }
    });

}

function updateWordToPro(id, word, wordBlockSelector) {

    jQuery.ajax({
        type: "POST",
        url: "handlers/update_word_from_pro.php",
        data: {wordId: id, word: word},
        dataType : 'json',
        beforeSend: function() {
            wordBlockSelector.addClass('word_disabled');
        },
        success: function (data) {

            const error = data.error;
            const success = data.success;

            if (success) {
                loadWords('user_words_pro');
            } else {
                alert(error);
            }
            wordBlockSelector.removeClass('word_disabled');
            
        }
    });

}

function removeWord(id, wordBlockSelector, parentId) {

    let dbTable = 'user_words';
    if (parentId === 'user-words-pro-table') dbTable = 'user_words_pro';

    jQuery.ajax({
        type: "POST",
        url: "handlers/remove_word.php",
        data: {wordId: id, parentId: parentId},
        dataType : 'json',
        beforeSend: function() {
            wordBlockSelector.addClass('word_disabled');
        },
        success: function (data) {

            const error = data.error;
            const success = data.success;

            if (success) {
                loadWords(dbTable);
                
            } else {
                alert(error);
            }
            wordBlockSelector.removeClass('word_disabled');
            
        }
    });

}