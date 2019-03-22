<?php
function Fool($debug)
{
    print('</td><td style="text-align:center; font-family: sans-serif;"><br>
			<span style="color:red">Поняша, ну честное слово...!<br>
			<strong>Неужели так интересно ломать чужу работу??</strong></span><br><br>
			<span style="color:purple; font-weight:bold;"> Займись-ка лучше чем-нибудь созидательным. ;) </span>
            </div><br>
            <div style="color:silver">'.$debug.'</div>
            <br>');
    
}

function DetectBreak()
{

    $result = false;

    if (!isset($_GET['keyword'])) {

        if (isset($_GET['catn'])) {

            if (preg_match('#^\d{1,2}$#', $_GET['catn'])) {

                if (isset($_GET['date'])) {
                    if (preg_match('#^\d{1,2}\-\d{1,2}\-\d{4}$#', $_GET['date'])) {

                    } else {
                        Fool('DATE_COMPROMISED');
                        $result = true;
                    }
                }
            } else {
                Fool('CAT_COMPROMISED');
                $result = true;
            }
        } else {
            Fool('CAT_NA');
            $result = true;
        }

    } else {

        //$_GET['keyword'] = strip_tags($_GET['keyword']);
        //$_GET['keyword'] = htmlentities($_GET['keyword'], ENT_QUOTES, "UTF-8");
        //$_GET['keyword'] = htmlspecialchars($_GET['keyword'], ENT_QUOTES);

        $badsymb = '([<>(){}""+?%/])';

        if (preg_match($badsymb, $_GET['keyword'])) {
            //$_GET['keyword'] = preg_replace($badsymb, '', $_GET['keyword']);
            Fool('KWS_COMPROMISED');
            $result = true;
        } 
    }

    return $result;
}
