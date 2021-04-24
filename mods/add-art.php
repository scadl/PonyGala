<?php
error_reporting(E_ALL);
ini_set('display_errors', 0);

session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: ../index.php');
}

require '../preinit.php';
require '../db_init.php';
?>

<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>PonyArt Gallery (by scadl) [Art Import]</title>
        <link rel="stylesheet" href="../libs/admin_forms.css">
        <link rel="stylesheet" href="//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">
        <script src="//code.jquery.com/jquery-1.9.1.js"></script>
        <script src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
        <script type="text/javascript">
            $(function () {


                $("#datepicker").datepicker({
                    showOtherMonths: false,
                    selectOtherMonths: false,
                    changeMonth: true,
                    changeYear: true,
                    dateFormat: "d-mm-yy",
                    regional: "ru",
                    showWeek: false,
                    firstDay: 1,
                    numberOfMonths: 1
                });
            });

        </script>
        <style>
            .pvImg{
                cursor:default;
            }
            .cellTBstyle{
                height: 10px;
                text-align: left;
                font-family: sans-serif;
                padding: 5px;
                border-radius: 5px;
                background: #ccc;
                vertical-align: middle;
            }
            .goodBtn{
                font-weight: bold;
                color: blue;
                width: 95%;
                padding: 3px;
            }
            .badBtn{
                font-weight: bold;
                color: red;                
            }
            .okBtn{
                font-weight: bold;
                color: green;
            }
            .bigInput{
                width:100%;
                margin-top: 3px;
            }

            form{
                margin: 0px
            }
            table{
                width:450px; 
                padding:10px;
            }
        </style>
    </head>
    <body>

        <div align="center">

            <span style='font-family:CelestiaRedux;'>
                <span style='font-size:45pt;'>Big DigitalArt Gallery</span><br>
                <span style='font-size:17pt;'>Import your arts</span> <br>
            </span>


            <hr style="width: 300px">
            <br>


            <?php

            function SerchCategories() {
                global $link;
                $req = mysqli_query($link, "SELECT * FROM categories ORDER BY cat_name");
                if ($req == TRUE) {
                    while ($row = mysqli_fetch_array($req, MYSQLI_ASSOC)) {
                        print('<option value="' . $row['cat_id'] . '"> ' . $row['cat_name'] . ' </option>');
                    }
                    if (mysqli_num_rows($req) == 0) {
                        print('<option selected value="0"> Не найдено категорий в БД </option>');
                    }
                } else {
                    print('<option selected value="0"> Не найдена таблица категорий </option>');
                }
                unset($req);
                unset($row);
            }
            ?>

            <form action='' method='post'>
                <input type="hidden" value="true" name="addArt">
                <table  border="0">
                    <tr> 
                        <td class='cellTBstyle' colspan="2">
                            Миниатюра<br>
                            <textarea type="text" name="thumb" class="bigInput"></textarea>
                        </td>
                    </tr>
                    <tr> 
                        <td class='cellTBstyle' colspan="2">
                            Фулл арта <span class="badBtn">*</span><br>
                            <textarea type="text" name="file_name" class="bigInput"  required="true"></textarea>
                        </td>
                    </tr> 
                    <tr> 
                        <td class='cellTBstyle' colspan="2">
                            Ссылка на арт<br>
                            <input type="text" name="da_page" class="bigInput">
                        </td>
                    </tr> 
                    <tr> 
                        <td class='cellTBstyle' >
                            Название<br>
                            <input type="text" name="title" class="bigInput">
                        </td>
                        <td class='cellTBstyle'>
                            Имя автора<br>
                            <input type="text" name="author" class="bigInput">
                        </td>
                    </tr> 
                    <tr>                         
                        <td class='cellTBstyle'>
                            Дата добавления <span class="badBtn">*</span><br>
                            <input type="text" name="addate" class="bigInput" id="datepicker" value="<?php print(date("j-m-Y")); ?>" required="true">
                        </td>
                        <td class='cellTBstyle'>
                            В Категорию <span class="badBtn">*</span><br>
                            <select id="cbCat" name="category" required="true"> 
                                <?php SerchCategories(); ?> 
                            </select>
                        </td>
                    </tr>
                    <tr> 
                        <td class='cellTBstyle' colspan="2">
                            Тэги, через запятую<br>
                            <textarea type="text" name="tags" class="bigInput"></textarea>
                        </td>
                    </tr> 
                    <tr> 

                    </tr> 

                    <tr> 
                        <td class='cellTBstyle' style="text-align:center" colspan="2">

                            <input type="submit" value="&#10003; Добавить арт" class="goodBtn">                           

                        </td>
                    </tr>


                </table>
            </form>

            <br>
            <hr style="width: 300px">


            <div><i>Статус добавления предидущего арта: </i>	
                <div id='daStatus'>


                    <?php
                    if (isset($_POST['addArt']) && $_POST['addArt'] == true) {
                        $rqchk = "SELECT * FROM arts_pub WHERE file_name='" . $_POST['file_name'] . "'";
                        $sqlchk = mysqli_query($link, $rqchk);
                        if (mysqli_num_rows($sqlchk) == 0) {

                            $recrq = "INSERT INTO arts_pub (title, file_name, thumb, da_page, author, addate, category, da_id, da_tags) VALUES ("
                                    . "'" . $_POST['title'] . "', '" . $_POST['file_name'] . "', "
                                    . "'" . $_POST['thumb'] . "', '" . $_POST['da_page'] . "', "
                                    . "'" . $_POST['author'] . "', '" . $_POST['addate'] . "', "
                                          . $_POST['category'] . ", 'N/A', '" . $_POST['tags'] . "')";
                            $sqlreq = mysqli_query($link, $recrq);

                            print("<div class='okBtn'>Арт успешно добавлен</div>");
                        } else {

                            print("<div class='badBtn'>Такой арт уже есть</div>");
                        }
                    }
                    ?>

                </div>
            </div>
        </div>

    </body>
</html>
