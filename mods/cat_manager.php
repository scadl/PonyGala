<?php
error_reporting(E_ALL);
ini_set('display_errors', 0);

session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: ../index.php');
}

require './../db_init.php';
?>

<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>PonyArt Gallery (by scadl) [Category manager]</title>
        <link rel="stylesheet" href="../libs/main.css">
        <style type="text/css">
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
            }
            .badBtn{
                font-weight: bold;
                color: red;
                width: 100%;
            }
            form{
                margin: 0px
            }
        </style>
    </head>
    <body>

        <div align="center">

            <span style='font-family:CelestiaRedux;'>
                <span style='font-size:45pt;'>Big DigitalArt Gallery</span><br>
                <span style='font-size:17pt;'>Category manager</span> <br>
            </span>

            <?php
            if (isset($_POST['catAction'])) {
                switch ($_POST['catAction']) {
                    case 1:
                        $rq1 = "DELETE FROM categories WHERE cat_id=" . $_POST['catid'] . ";";
                        $sql = mysqli_query($link, $rq1);
                        $rq2 = "UPDATE arts_pub SET category=1 WHERE category=" . $_POST['catid'] . ";";
                        $sql = mysqli_query($link, $rq2);
                        break;
                    
                    case 2:
                        $rq = "INSERT INTO categories(cat_name) VALUES ('" . $_POST['newCat'] . "');";
                        $sql = mysqli_query($link, $rq);
                        break;
                    
                    case 3:
                        $rq = "UPDATE categories SET cat_name='".$_POST['newName']."' WHERE cat_id=" . $_POST['catid'] . ";";
                        $sql = mysqli_query($link, $rq);
                        break;

                    default:
                        break;
                }
                
            }

            if (isset($_POST['catAction'])) {
                
            }
            ?>

            <hr style="width: 300px">
            <br>

            <table  style="width:550px; padding:10px;" border="0">
                <tr>                    

                    <td class="cellTBstyle" colspan="3">
                        <form action='' method='post'>
                            <input type="text" name="newCat" style="width:478px">
                            <input type="hidden" value="2" name="catAction">
                            <input type="submit" value="+" title="Добавить категорию" class="goodBtn">    
                        </form>
                    </td>

                </tr>
                
                <?php
                $rq = "SELECT * FROM categories WHERE cat_id>1 ORDER BY cat_name";

                $sql = mysqli_query($link, $rq);
                while ($row = mysqli_fetch_array($sql, MYSQLI_ASSOC)) {
                ?>
                    <tr>
                        <td class='cellTBstyle'>
                    <?php print($row['cat_name']) ?>
                        </td>
                        <td class='cellTBstyle'>
                            <form action='' method='post' >
                                <input type="hidden" value="3" name="catAction">
                                <input type="hidden" value="<?php print($row['cat_id']) ?>" name="catid">
                                <input type="text" name="newName" style="width:170px">
                                <input type="submit" value="&#10003;" title="Перреименовать категорию" class="goodBtn">
                            </form>
                        </td>
                        <td class='cellTBstyle'>
                            <form action='' method='post' > 
                                <input type="hidden" value="1" name="catAction">
                                <input type="hidden" value="<?php print($row['cat_id']) ?>" name="catid">
                                <input type='submit' value='X' title="Удалить категорию" class="badBtn"> 
                            </form>
                        </td>
                    </tr>

            <?php
                }
            ?>

            </table>

            <br><br>
            <span style="color:grey; font-size:7pt;">	
                Scripting and Design: <b>scadl</b><br>
                <i><a href="http://scadsdnd.net/" target="_blank">SCAD's Design & Develop</a> - Aug 2013</i>
            </span>

        </div>

    </body>
</html>
