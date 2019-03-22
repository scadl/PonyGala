<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
    "http://www.w3.org/TR/html4/loose.dtd">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>PonyArt Gallary (by scadl) [DB Cleaner]</title>
        <style type="text/css">
            @font-face{
                font-family:CelestiaRedux;
                src: url("../libs/CelestiaMediumRedux1.55.ttf");
            }
            td{
                text-align: center;
                font-family: sans-serif;
                padding: 10px;
                border-radius:5px;
                background: #eee;
            }
            a:link{
                color: #000;
                text-decoration: none;
            }
            a:visited{
                color: #000;
                text-decoration: none
            }
            a:hover{
                color: blue;
                text-decoration: none;
            }
            .tech_lab{
                background:#eee;
                border-radius:5px;
                font-family: sans-serif;
            }
        </style>
    </head>
    <body>

        <div align="center">

            <span style='font-family:CelestiaRedux;'>
                <span style='font-size:45pt;'>Big PonyArt Gallery</span><span style='font-size:8pt;'>v2</span><br>
                <span style='font-size:17pt;'>ArtCache Cleaner - Report</span> <br>
            </span>

            <script type="text/javascript">
                var arts_arr = "";
                var caIntInd = 0;
                var stats = [0, 0, 0, 0, 0, 0];

                function CacheCheck() {

                    if (caIntInd <= arts_arr.length) {

                        //document.getElementById("status").innerHTML="<i>Проверяю кэш...</i>";

                        var ajcheck = new XMLHttpRequest();
                        ajcheck.onreadystatechange = function () {
                            if (ajcheck.readyState == 4) {
                                switch (ajcheck.status) {
                                    case 200:

                                        var resps = JSON.parse(ajcheck.responseText);
                                        for (var key in resps) {
                                            stats[key] += resps[key];
                                        }

                                        document.getElementById("status").innerHTML = "<b>" + (((caIntInd * 100) / arts_arr.length).toFixed(2)) + "% </b>";
                                        
                                        document.getElementById("ttl").innerHTML = stats[0];
                                        document.getElementById("fnm").innerHTML = stats[1];
                                        document.getElementById("tmb").innerHTML = stats[2];
                                        document.getElementById("dap").innerHTML = stats[3];
                                        document.getElementById("auth").innerHTML = stats[4];
                                        document.getElementById("del").innerHTML = stats[5];

                                        caIntInd++;
                                        CacheCheck();

                                        break;
                                    default:
                                        document.getElementById("status").innerHTML = "<span style='color:red'>Ошибка при чтении кэша\n № ошибки: " 
                                                + ajcheck.status + ", " + ajcheck.statusText + " </span>";
                                        break;
                                }
                            }
                        }
                        
                        ajcheck.open('GET', 'cache_processor.php?mode=1&check=' + arts_arr[caIntInd]);
                        ajcheck.send(null);

                    } else {
                        document.getElementById("btnControl").disabled = false;
                    }
                }

                function AjGetList() {

                    document.getElementById("status").innerHTML = "<i>Читаю кэш...</i>";

                    var ajfeed = new XMLHttpRequest();
                    ajfeed.onreadystatechange = function () {
                        if (ajfeed.readyState == 4) {
                            switch (ajfeed.status) {
                                case 200:

                                    arts_arr = "";
                                    arts_arr = ajfeed.responseText.split('|');

                                    document.getElementById("status").innerHTML = "<i>Кэш загружен</i>";
                                    //document.getElementById("log").innerHTML = arts_arr.length;

                                    caIntInd = 0;
                                    CacheCheck();
                                    document.getElementById("status").innerHTML = "<i>Проверяю кэш...</i>";
                                    document.getElementById("btnControl").disabled = true;
                                    //document.getElementById("indb").innerHTML = arts_arr.length;
                                    break;
                                    
                                default:
                                    document.getElementById("status").innerHTML = "<span style='color:red'>Ошибка при чтении кэша\n № ошибки: " 
                                            + ajfeed.status + ", " + ajfeed.statusText + " </span>";
                                    break;
                            }
                        }
                    }
                    ajfeed.open('GET', 'cache_processor.php?mode=0');
                    ajfeed.send(null);
                }
            </script>

            <br>
            <table width="200">
                <tr style="font-size:9pt; color:blue"><td width="70">Status: </td><td> <span id='status'>...</span> </td></tr>
                <tr style="font-size:9pt; color:green"><td>Titles fixed: </td><td> <span id='ttl'>0</span> </td></tr>
                <tr style="font-size:9pt; color:green"><td>FilNames fixed: </td><td> <span id='fnm'>0</span> </td></tr>	
                <tr style="font-size:9pt; color:green"><td>Thumbnails fixed: </td><td> <span id='tmb'>0</span> </td></tr>
                <tr style="font-size:9pt; color:green"><td>dA pages fixed: </td><td> <span id='dap'>0</span> </td></tr>
                <tr style="font-size:9pt; color:green"><td>Authors fixed: </td><td> <span id='auth'>0</span> </td></tr>                
                <tr style="font-size:9pt; color:red"><td>Removed: </td><td> <span id='del'>0</span> </td></tr>
                <tr style="text-align:center"><td colspan="2"> <button onclick='AjGetList()' id='btnControl'> Start Cache Cleaner </button> </td></tr>	
            </table>

            <div id='log'></div>

        </div>
    </body>
