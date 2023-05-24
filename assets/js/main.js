function getCursorPosition(e){
    var cursorX = e.clientX;
    
    if(cursorX <= 10 && !$('.navbar')[0].classList.contains('open')){
        $('.navbar').addClass('open');
    }

    if(cursorX >= 60 && $('.navbar')[0].classList.contains('open')){
        $('.navbar').removeClass('open');
    }
}

function openNotebooksDay(date){
    window.location.href = 'notebooks?date='+date;
}

function openNotebook(datetime){
    window.location.href = 'notebook?q='+datetime;
}

function openModalNewNotebook(){
    $('#modal-new-notebook').addClass('d-block')
}

function createNewNotebook(){
    var name = ""
    if($('#name-notebook').val() != ''){
        name = $('#name-notebook').val();
    }else{
        name = 'Ainda sem nome';
    }


    $.ajax({
        url: 'components/create-new-notebook',
        type: 'POST',
        data: {notebook: name}
    })
    .done(function(data){
        window.location.href = 'http://localhost/notebook/notebook?q='+data
        console.log(data)
    })
}

$(function () {
    $('[data-toggle="tooltip"]').tooltip()
    if($('.notebook')[0] != undefined){
        if(window.location.href.indexOf('notebook?') != -1){
            var tempHTML = $('.notebook')[0].innerHTML
            var editor = new RichTextEditor(".notebook");
            editor.setHTMLCode(tempHTML)
        }
    }
})

window.oncontextmenu = function (e){
    if(window.location.href.indexOf('notebooks?') != -1){
        var temp = e.srcElement.onclick.toString();
        temp = temp.split("`");

        $.ajax({
            url: 'components/get-contents',
            type: 'GET',
            data: {date: temp[1]}
        })
        .done(function(data){
            $('.notebook-view').addClass('open')
            $('.notebook-view').html(data)
        })

        return false; 
    }
}