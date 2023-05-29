//get position to open or close navbar
function getCursorPosition(e){
    var cursorX = e.clientX;
    
    if(cursorX <= 10 && !$('.navbar')[0].classList.contains('open')){
        $('.navbar').addClass('open');
    }

    if(cursorX >= 60 && $('.navbar')[0].classList.contains('open')){
        $('.navbar').removeClass('open');
    }
}

//get notebook and your childs with date url
function openNotebooksDay(date){
    window.location.href = 'notebooks?date='+date;
}

//get contents and make page notebook with date url
function openNotebook(datetime){
    window.location.href = 'notebook?q='+datetime;
}

//create a new paper modal
function openModalNewNotebook(){
    $('#modal-new-notebook').addClass('d-block')
}

// create a new notebook with date url
function createNewNotebook(){
    var name = ""
    var queryString = window.location.search;
    var urlParams = new URLSearchParams(queryString);
    var temp = urlParams.get('date')

    if($('#name-notebook').val() != ''){
        name = $('#name-notebook').val();
    }else{
        name = 'Ainda sem nome';
    }


    $.ajax({
        url: 'components/create-new-notebook',
        type: 'POST',
        data: {notebook: name, date: temp}
    })
    .done(function(data){
        window.location.href = 'http://localhost/notebook/notebook?q='+data
        console.log(data)
    })
}

//load richtext in notebook page
if(window.location.href.indexOf('notebook?') != -1){
    var tempHTML = $('.notebook')[0].innerHTML
    var editor = new RichTextEditor(".notebook");
    console.log(tempHTML)
    editor.setHTMLCode(tempHTML)
}

// start tooltips and set time sync contents
var idleTime = 0;
$(function () {
    if(window.location.href.indexOf('notebook?') != -1){
        var idleInterval = setInterval(timerIncrement, 10000);
    }

    $(this).mousemove(function (e) {
        idleTime = 0;
    });
    $(this).keypress(function (e) {
        idleTime = 0;
    });

    $('[data-toggle="tooltip"]').tooltip()
})

function viewNotebook(date){
    $.ajax({
        url: 'components/get-contents',
        type: 'GET',
        data: {date: date}
    })
    .done(function(data){
        $('.notebook-view').addClass('open')
        $('.notebook-view').html(data)
        $('body').addClass('notebookOpen')
    })
}

// close notebook modal view
$(document).click(function(event) { 
    var $target = $(event.target);
    if(!$target.closest('.notebook-view').length && 
    $('.notebook-view').is(":visible")) {
        $('.notebook-view').removeClass('open');
    }        
});

function timerIncrement() {
    var queryString = window.location.search;
    var urlParams = new URLSearchParams(queryString);
    var temp = urlParams.get('q')
    var content = editor.getHTMLCode()
    
    idleTime = idleTime + 1;
    if (idleTime > 1) {
        $('.fa-arrow-up').addClass('show')
        $('.fa-check').removeClass('show')
        $.ajax({
            url: "components/save-contents",
            type: "POST",
            data: {date: temp, content: content}
        })
        .done(function(data){
            var obj = JSON.parse(data)
            if(obj.status == 200){
                setTimeout(() => {
                    $('.fa-check').addClass('show')
                    $('.fa-arrow-up').removeClass('show')
                    
                    window.history.pushState('Update', 'Sync Update', '/mynotebook/notebook?q='+obj.date+'');
                }, 2000);
            }
        })
    }
}