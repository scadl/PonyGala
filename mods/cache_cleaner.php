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
                var daToken = "";
                var stats = {'title':0, 'file':0, 'thumb':0, 'page':0, 'author':0, 'delete':0};

                function CacheCheck(arts_arr, caIntInd) {

                    if (caIntInd <= arts_arr.length-1) {
                    //if (caIntInd <= 5) {

                        //document.getElementById("status").innerHTML="<i>Проверяю кэш...</i>";

                        var ajcheck = new XMLHttpRequest();
                        ajcheck.onreadystatechange = function () {
                            if (ajcheck.readyState == 4) {
                                switch (ajcheck.status) {
                                    case 200:

                                        var resps = JSON.parse(ajcheck.responseText);
                                        for (var key in resps['states']) {
                                            stats[key] += Number(resps['states'][key]);
                                            //console.log(key+" "+stats[key]+" "+resps['states'][key]);
                                        }                                     

                                        document.getElementById("status").innerHTML = "<b>" + arts_arr.length + " arts</b>";
                                        document.getElementById("progres").innerHTML = "<b>" + caIntInd + " ch.k</b>";

                                        document.getElementById("ttl").innerHTML = stats['title'];
                                        document.getElementById("fnm").innerHTML = stats['file'];
                                        document.getElementById("tmb").innerHTML = stats['thumb'];
                                        document.getElementById("dap").innerHTML = stats['page'];
                                        document.getElementById("auth").innerHTML = stats['author'];
                                        document.getElementById("del").innerHTML = stats['delete'];

                                        if(resps['message']=='' || resps['message']=='dA OK!'){
                                            daToken = resps['token'];
                                            CacheCheck(arts_arr, caIntInd+1);
                                        } else if (resps['message']=='dA Refresh!') {
                                            daToken = resps['token'];
                                            CacheCheck(arts_arr, caIntInd);
                                        } else {
                                            console.error(resps['message']);
                                            CacheCheck(arts_arr, caIntInd+1);
                                            document.getElementById("status").innerHTML = "<span style='color:red'>Ошибка: " + resps['message'] + " </span>";
                                        }

                                        break;
                                    default:
                                        document.getElementById("status").innerHTML = "<span style='color:red'>Ошибка при чтении кэша\n № ошибки: "
                                                + ajcheck.status + ", " + ajcheck.statusText + " </span>";
                                        break;
                                }
                            }
                        }

                        ajcheck.open('GET', 'cache_processor.php?mode=1&art=' + arts_arr[caIntInd]+'&token='+daToken);
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

                                    system_repsose = "";

                                    document.getElementById("status").innerHTML = "<i>Кэш загр.</i>";

                                    system_repsose = JSON.parse(ajfeed.responseText);
                                    if(system_repsose['token']){
                                        
                                        //alert(system_repsose['token']);                                        
                                        daToken = system_repsose['token'];
                                        CacheCheck(system_repsose['arts_id'], 0);
                                        
                                    } else{
                                        
                                        alert(system_repsose['expires']);
                                    }                                 
                                   
                                    document.getElementById("status").innerHTML = "<i>Проверяю</i>";
                                    document.getElementById("btnControl").disabled = true;

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
                <tr style="font-size:9pt; color:blue"><td>Scanned: </td><td> <span id='progres'>0</span> </td></tr>
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
