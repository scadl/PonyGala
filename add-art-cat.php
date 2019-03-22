<?php

	$artdb = new SQLite3("art-db.sqlite");
	$artdb -> busyTimeout(45000);
	
	switch ($_GET['type']){
		case 2: // Add Category
			$artdb -> exec("INSERT INTO art_categories (cat_name) VALUES ('".$_GET['name']."')");
			print("<tr><td colspan='3'>Добавлена категория: ".$_GET['name']."</td></tr>"); 
		break;
		case 1: // Add Art (with repat check)
			if ($_GET['name'] == ""){return;}
			$detector = $artdb -> query("SELECT category, addate FROM arts WHERE file_name='".$_GET['name']."'");
			if ($detector){
				if ($detector -> fetchArray()){
					print("<tr><td colspan='3' style='color:blue;'>Этот арт уже существует в базе!</td></tr>
					<tr><td colspan='2' style='color:blue;'>".$_GET['name']."</td>
					<td style='font-weight:bold; color:red;'>".$artdb -> query("SELECT category, addate FROM arts WHERE file_name='".$_GET['name']."'") -> fetchArray()[1]."</td></tr>"); 
				} else {
					if ($artdb -> exec("INSERT INTO arts (file_name, category, addate, like, dislike, old, goodnold, middle) VALUES ('".$_GET['name']."', ".$_GET['cat'].", '".$_GET['date']."', 0, 0, 0, 0, 0)"))
					{
						print("<tr>
						<td>".$_GET['name']."</td>
						<td>".$_GET['cat']."</td>
						<td>".$_GET['date']."</td>
						</tr>"); 
					} else {
						print("<tr><td colspan='3' style='color:red;'>
						<b>Ошибка при добавлени, возможно база перегружена!</b>
						<br><hr>
						<i>".$_GET['name']."</i>
						</td></tr>");
					}
				}
			} else {
				print("<tr><td colspan='3' style='color:red;'>
						<b>Ошибка при добавлени, возможно база перегружена!</b>
						<br><hr>
						<i>".$_GET['name']."</i>
						</td></tr>");
			}
			$artdb -> close();
		break;
		case 3: // Update art
			$artdb -> exec("UPDATE arts SET file_name='".$_GET['name']."', category=".$_GET['cat'].", addate='".$_GET['date']."' WHERE file_name='".$_GET['name_old']."'");
			//print("Обновлён: ".$_GET['name_old']."<br><br>".$_GET['name']." >>> ".$_GET['cat']." >>> ".$_GET['date'].";<hr>"); 
			print("Обновлёно <span style='color:green'><b>УСПЕШНО</b></span> <hr>"); 
		break;
		case 4: // Remove art
			$artdb -> exec("DELETE FROM arts WHERE file_name='".$_GET['name']."'");
			print("<script type='text/javascript'> alert('Удалён: ".$_GET['name_old']."'); </script>");
		break;
		case 5: // Remove ThumbStacks
			foreach ( glob('_cache-thumbs/thumb-stack-CatN*_'.$_GET['date'].'.png') as $oldfile ){
				unlink($oldfile);
			}
		break;
		case 6: // Add subscriber
			$artdb -> exec('CREATE TABLE IF NOT EXISTS subscribers (uid INTEGER PRIMARY KEY AUTOINCREMENT, email TEXT, subscribed TEXT)');
			if ($artdb -> query("SELECT email FROM subscribers WHERE email='".$_POST['email']."'") -> fetchArray()){
				print("Извини няша, но твой почтовый ящик уже есть в cписке рассылки!\n\n Если ты всё ещё не получаешь писем от меня,\n Советую тебе заглюнуть в папку 'Спам'\n и добавить мой адрес 'admin@scadsdnd.syts.net', в 'Белый список' ;)");
			} else {
				$artdb -> exec('INSERT INTO subscribers (email, subscribed) VALUES ("'.$_POST['email'].'", "'.date("j-m-Y").'");');
				print("Спасибо тебе поняша!\nТы не пожалеешь, что подписался на мои подборки! ;)");
			}
		break;
		case 7: // Send to subscribers
			$emails = array();
			$artrq = $artdb -> query("SELECT email FROM subscribers LIMIT 1");
			while ($row = $artrq -> fetchArray(SQLITE3_NUM)){
				
				$emails[]=$row[0];
				
				// Сформируем заголовок письма        
				// Для отправки HTML-письма должен быть установлен заголовок Content-type
				$headers = 'MIME-Version: 1.0' . "\r\n";
				$headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
				// Дополнительные заголовки
				$headers .= 'To: <'.$row[0].'>'. "\r\n";
				$headers .= 'From: PonyGala (scadl) <ponygala@scadsdnd.ddns.net>'. "\r\n";
				$headers .= 'Reply-To: PonyGala (scadl) <ponygala@scadsdnd.ddns.net>' . "\r\n";
				//$headers .= 'X-Mailer: PHP/' . phpversion();

				// Тема письма
				$subject = 'Новые ПониАрты, '.$_GET['artn'].' шт.!';

				// Тело письма
			$message = 'Доброго времени суток, поняша!<br>
			Хочу тебя порадовать свежей подборкой отборных пониартов.<br>
			Она наcчитывает <b>'.$_GET['artn'].'</b> работ, и ждёт тебя 
			<b><a href="http://scadsdnd.ddns.net/myphp/ponygalai/index.php?date='.$_GET['date'].'" target="_blank"> ЗДЕСЬ </a></b><br>
			<span style="color:lightgrey; font-size:8pt;">Если ты хочешь отписаться от этой рассылки, 
			жми <a href="http://scadsdnd.ddns.net/myphp/ponygalai/add-art-cat.php?type=8" target="_blank" style="color:lightgrey;">сюда</a>
			</span>';

				// Отправим письмо
				mail($row[0], $subject, $message, $headers);
				
				
			}
			print( count($emails) );
			//print_r($emails);
			
			/*
			require_once '../swiftmailer_lib/swift_required.php';
			
			$email = Swift_Message::newInstance();
			$email->setCharset('utf-8');
			$email->setSubject('Новые ПониАрты, '.$_GET['artn'].' шт.!');			
			$email->setFrom(array('admin@scadsdnd.ddns.net' => 'ПониАрт Галерея (scadl)'));
			$email->setSender(array('admin@scadsdnd.ddns.net' => 'ПониАрт Галерея (scadl)'));
			$email->setTo(array('admin@scadsdnd.ddns.net' => 'Контролёр ПониАртов'));
			$email->setBcc( $emails );			
			$email->setBody('Доброго времени суток, поняша!<br>
			Хочу тебя порадовать свежей подборкой отборных пониартов.<br>
			Она наcчитывает <b>'.$_GET['artn'].'</b> работ, и ждёт тебя 
			<b><a href="http://scadsdnd.ddns.net/myphp/ponygalai/index.php?date='.$_GET['date'].'" target="_blank"> ЗДЕСЬ </a></b><br>
			<span style="color:lightgrey; font-size:8pt;">Если ты хочешь отписаться от этой рассылки, 
			жми <a href="http://scadsdnd.ddns.net/myphp/ponygalai/add-art-cat.php?type=8" target="_blank" style="color:lightgrey;">сюда</a>
			</span>', 'text/html', 'utf-8');
			$email->setPriority(2);
			
			$header = $email->getHeaders();
			
			$transport = Swift_SmtpTransport::newInstance('relay.ukrpost.ua', 25);
			$transport -> setUsername('djjke_5613sv@dsl.ukrtel.net');
			$transport -> setPassword('78945612');
			
			$mailer = Swift_Mailer::newInstance($transport);
			
			//printf('Sent %d messages\n', $mailer -> send($email) );
			print( $mailer -> send($email) );
			*/
			
			unset($artrq); unset($row); unset($emails);
			unset($email); unset($transport); unset($mailer);
		break;
		case 8: //Unsubscribe
			if (!isset($_POST['email'])){
				print("<br><div align='center' style='font-family: sans-serif;'>
				<form action='?type=8' method='post'>
					<strong> <span style='font-family: serif; font-size:15pt;'> Уже отписываешься? </span> </strong><br>
					Пожалуйста введи свой e-mail,<br>
					на который приходят уведомления:<br>
					<input type='text' name='email'><br>
					<input type='submit' value='Отписаться'>
				</form>
				</div>");
			} else {		
				$artdb -> exec("DELETE FROM subscribers WHERE email='".$_POST['email']."'");
				print("<br><div align='center' style='font-family: sans-serif;'> <b>Поняш, я тебя чем-то обидел?...</b> <br><br>
				<span style='font-family: serif;'><i>Твой адрес '".$_POST['email']."'<br> успешно удалён из списка расссылки...</i></span></div>");
			}
		break;
		case 9: // Publish VK
		
		/*
		// Token: https://oauth.vk.com/blank.html#access_token=45f47b6a980df4625b9754b67f0b77b42cee003f83e80319cc3eb66dea284597f3ed586602cfd6c9b8032&expires_in=0&user_id=5917835
		
		require_once('vk-sdk-by-vatsly.php');
		$accessToken = '45f47b6a980df4625b9754b67f0b77b42cee003f83e80319cc3eb66dea284597f3ed586602cfd6c9b8032';
		$vkAPI = new \BW\Vkontakte(['access_token' => $accessToken]);
		
		//https://vk.com/search?c%5Bsection%5D=people&c%5Bgroup%5D=17462247
		if ($vkAPI->postToPublic(17462247, "Новая подборка!", '/myphp/ponygalai/thum-stack-tb.php', ['ПониАрт', 'MLP', 'MLP-FIM', 'Цифровой рисунок'])) {
		echo "Ура! Всё работает, пост добавлен\n";
		} else {
		echo "Фейл, пост не добавлен(( ищите ошибку\n";
		}*/
		
		$vkdata = file_get_contents('https://api.vk.com/method/users.get?user_id=5917835&v=5.3&access_token=45f47b6a980df4625b9754b67f0b77b42cee003f83e80319cc3eb66dea284597f3ed586602cfd6c9b8032');
		print ( json_decode( $vkdata ) );
		
		break;
	}
		
	unset($artdb);
	//sleep(1);	
?>