var rows = 0; // Current Added Field
var i = 0; // Current Processed Field
var tp = 0; // Comand type

var pages = 0;
var arts = 0;
var dups = 0;
var last_tweet = "";
var more = false;
var err = 0;
var empty_arts = 0;
var arts_arr;
var faaInt;
var faaIntInd = 0;
var maxart = 0;
var last_date = 0;

function IniTtWFeed() {

    var more = false;

    daInt = setTimeout(function () {
        AJasKtW();
    }, 1000);
    
    document.getElementById("log").innerHTML = "";
    document.getElementById("list_btn").disabled =true;
    document.getElementById("list").disabled =true;
}

function AJasKtW() {
    
    let list = document.getElementById("list").value;
    clearTimeout(daInt);
    
    var ajda = new XMLHttpRequest();
    ajda.onreadystatechange = function () {
        if (ajda.readyState == 4) {
            switch (ajda.status) {
                case 200:
                    var da_data = JSON.parse(ajda.responseText);
                    //var old_cursor = new_cursor;
                    pages = Number(da_data["pages"]) + pages;
                    arts = Number(da_data["arts"]) + arts;
                    last_tweet = da_data["last_id"];
                    more = da_data["more"];
                    dups = Number(da_data["dups"]) + dups;
                    last_date = da_data["last_date"];

                    if (!more) {
                        document.getElementById("daStatus").innerHTML =
                                "<i><span style='color:grey; font-weight:bold;'>Чтение Twitter завершено</span></i>";
                                document.getElementById("list_btn").disabled =false;
                                document.getElementById("list").disabled =false;
                                last_tweet = "";
                    } else {
                        if (more == null) {
                            document.getElementById("daStatus").innerHTML =
                                    "<i>Ошибка связи с dA:<br> <span style='color:red'>Жду уже " + err + " из 60 сек.</span></i>";
                            err++;
                        } else {
                            document.getElementById("daStatus").innerHTML =
                                    "<br><i><span style='color:green'>Читаю ленту на Twitter</span></i>";
                            err = 0;                            
                        }                        
                        IniTtWFeed();
                    }
                    if (Number(da_data["arts"]) == 0) {
                        empty_arts++;
                    } else {
                        empty_arts = 0;
                    }

                    document.getElementById("log").innerHTML =
                            document.getElementById("log").innerHTML + da_data["log"];

                    document.getElementById("status").innerHTML = "<br>" +
                            "Получено страниц: " + pages + " <br>" +
                            "Новых артов: " + arts + " <br>" +
                            "Дубликатов: " + dups + " <br>" +                               
                            "<br>";
                    
                   //document.getElementById("status").innerHTML += da_data["last_date"]

                    break;
                default:
                    document.getElementById("log").innerHTML =
                            "<tr><td colsan='3'>Ошибка!</td></tr>";
                    document.getElementById("status").innerHTML =
                            "<span style='color:red'>Ошибка при обработке запроса </span>";
                    break;
            }
        }
    };

    ajda.open(
            "GET",
            "tW_integrator.php?list=" + list + "&last_id=" + last_tweet
            );
    ajda.send(null);
}
