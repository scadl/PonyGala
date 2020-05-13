/* Functions Collection */

function AJAXLoadStack(catn, dater, ind) {
            
    var datepar = '';
    if(dater!=''){
        datepar = dater;
    }

    var ajobj = new XMLHttpRequest();
    ajobj.onreadystatechange = function () {
            if (ajobj.readyState == 4) {
            switch (ajobj.status) {

            case 200:
                document.getElementById("blid_" + ind).innerHTML = ajobj.response;
            break;

            //default: alert("Ошибка при генерации стопки\n № ошибки: " + ajobj.status+', '+ajobj.statusText); break;
            default:
                document.getElementById("blid_" + ind).innerHTML = "<div style='padding:25px; font-size:8pt; height:200px; vertical-align:middle;'>" +
                    "Упс, что-то не так, с генерацией стопки...<br><br>" +
                    "Хм, похоже сервер слегка перегружен...<br><br>" +
                    "Обновите пожалуйста страничку ;) </div>";
            break;
            }
        }
    }
    ajobj.open('GET', 'mods/thum-category.php?catn=' + catn + datepar);
    ajobj.send(null);
    }

function StartDate() {
    window.location = 'index.php?date=' + document.getElementById('datepicker').value;
}

$(function () {

    $("#dtbtn").click(function () {
        $("#datepicker").datepicker("show");
    });

    $("#datepicker").datepicker({
        showOtherMonths: false,
        selectOtherMonths: false,
        changeMonth: true,
        changeYear: true,
        dateFormat: "d-mm-yy",
        regional: "ru",
        showWeek: false,
        firstDay: 1,
        beforeShowDay: available,
        numberOfMonths: 1,
        showButtonPanel: false,
        //showOn: "button",
        //buttonImage: "img/calendar1-20.png",
        //buttonImageOnly: true,
        onSelect: function (dateText, inst) {
            window.location = 'index.php?date=' + dateText;
        },
        beforeShow: function() {
        setTimeout(function(){
                $('.ui-datepicker').css({'z-index': 100, 'padding-right':'10px'});
            }, 0);
        }
    }); 


    $("#MLogin").click(function () {
        //alert("click");
        $("#modDialog").dialog({
            resizable: false,
            modal: true,
            buttons: {
                "Пустите меня!": function () {
                    //var umail = $('#umail').val();
                    if ($('#mpass').val() != '') {
                        //window.location = 'index.php?pass=' + $('#mpass').val();
                        document.getElementById("modDialog").submit();
                        $(this).dialog("close");
                    } else {
                        alert('Пустой пароль не допустим');
                    }
                }
            }
        });
    });

    $("#MLogOut").click(function () {
        window.location = 'index.php?logout';
    });
    
    $('#ArchiveFrame').click(function () {
        $("#poupup_wnd").dialog({
            title: 'Поиск в галерее',
            resizable: false,
            modal: true,
            height: "auto",
            width: "auto"
        });
    });

});