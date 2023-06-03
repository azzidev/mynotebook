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
    $('#modal-new-notebook').addClass('d-block');
}

//create a new group modal
function openModalNewGroup(){
    $('#modal-new-group').addClass('d-block');
}

// create a new notebook with date url
function createNewNotebook(){
    var name = ""
    var queryString = window.location.search;
    var urlParams = new URLSearchParams(queryString);
    var temp = urlParams.get('date');
    var group = false;

    if($('#name-notebook').val() != ''){
        name = $('#name-notebook').val();
    }else{
        name = 'Ainda sem nome';
    }

    if(urlParams.get('uri') != undefined){
        group = urlParams.get('uri');
    }

    $.ajax({
        url: 'components/create-new-notebook',
        type: 'POST',
        data: {notebook: name, date: temp, group: group}
    })
    .done(function(data){
        window.location.href = 'http://localhost/mynotebook/notebook?q='+data;
        console.log(data);
    })
}

//create a new group with date url
function createNewGroup(){
    if($('#name-group').val() != ''){
        var name = $('#name-group').val();
        var queryString = window.location.search;
        var urlParams = new URLSearchParams(queryString);
        var temp = urlParams.get('date');

        $.ajax({
            url: 'components/create-new-group',
            type: 'POST',
            data: {group: name, date: temp}
        })
        .done(function(data){
            window.location.href = 'http://localhost/mynotebook/group?uri='+data+'&date='+temp;
            console.log(data);
        })
    }else{
        $('#name-group').addClass('border border-2 border-warning');
        $('#name-group').parent().children()[0].classList.add('error-no-value');

        setTimeout(function(){
            $('#name-group').removeClass('border border-2 border-warning error-no-value');
            $('#name-group').parent().children()[0].classList.remove('error-no-value');
        }, 3000)
    }
}

//load richtext in notebook page
if(window.location.href.indexOf('notebook?') != -1){
    var tempHTML = $('.notebook')[0].innerHTML;
    var editor = new RichTextEditor(".notebook");
    console.log(tempHTML);
    editor.setHTMLCode(tempHTML);
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

    $('[data-toggle="tooltip"]').tooltip();
})

// get notebook content to preview in page
function viewNotebook(date){
    $.ajax({
        url: 'components/get-contents',
        type: 'GET',
        data: {date: date}
    })
    .done(function(data){
        $('.notebook-view').addClass('open');
        $('.notebook-view').html(data);
        $('body').addClass('notebookOpen');
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

// function to count seconds to sync content on database
function timerIncrement() {
    var queryString = window.location.search;
    var urlParams = new URLSearchParams(queryString);
    var temp = urlParams.get('q');
    var content = editor.getHTMLCode();
    
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
            var obj = JSON.parse(data);
            if(obj.status == 200){
                setTimeout(() => {
                    $('.fa-check').addClass('show');
                    $('.fa-arrow-up').removeClass('show');
                    
                    window.history.pushState('Update', 'Sync Update', '/mynotebook/notebook?q='+obj.date+'');
                }, 2000);
            }
        })
    }
}

// function open page group
function openGroup(id, date){
    window.location.href = 'group?uri='+id+'&date='+date;
}

//function open modal to change year calendar
function openModalSelectYear(){
    $('#modal-select-year').addClass('d-block');
}

//function to get year
function getThisYear(year){
    if(year.length == 4){
        window.location.href = 'http://localhost/mynotebook/index?year='+year;
    }
}

//function to get month
function getThisMonth(month){
    var queryString = window.location.search;
    var urlParams = new URLSearchParams(queryString);
    var temp = urlParams.get('year');
    
    if(temp != ''){
        window.location.href = 'http://localhost/mynotebook/index?year='+temp+'&month='+month;
    }else{
        var currentTime = new Date();
        var year = currentTime.getFullYear();
        window.location.href = 'http://localhost/mynotebook/index?year='+year+'&month='+month;
    }
}