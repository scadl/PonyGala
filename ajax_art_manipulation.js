			function AJAXAddData(type, v1, v2, v3, v4){
			//alert("v1 '"+v1+"' v2 '"+v2+"' v3 '"+v3+"' v4 "+"'"+v4+"' type - "+type);
			document.getElementById("status").innerHTML="<i>Добавляю арт.</i>";
			var ajobj = new XMLHttpRequest();
				ajobj.onreadystatechange = function(){
					if (ajobj.readyState==4){
						switch(ajobj.status){
						case 200: 
							document.getElementById("log").innerHTML=document.getElementById("log").innerHTML+ajobj.responseText; 
							document.getElementById("status").innerHTML="<i>Успешно выполнено</i>";
						break;
						default: 
							document.getElementById("log").innerHTML="<tr><td colsan='3'>Ошибка при добавлении</td></tr>"; 
							document.getElementById("status").innerHTML="<span style='color:red'>Ошибка при добавлении арта или категории\n № ошибки: " + ajobj.status+", "+ajobj.statusText+" </span>"; 
						break;
						}
					}
				}
				switch(type){
				  //case 1: ajobj.open('GET', 'temp.php'); break; 
					case 1: ajobj.open('GET', 'add-art-cat.php?type='+type+'&name='+v1+'&cat='+v2+'&date='+v3); break;
					case 2: ajobj.open('GET', 'add-art-cat.php?type='+type+'&name='+document.getElementById("catFullName").value); break;
					case 3: ajobj.open('GET', 'add-art-cat.php?type='+type+'&name='+v1+'&cat='+v2+'&date='+v3+'&name_old='+v4); break;
					case 4: ajobj.open('GET', 'add-art-cat.php?type='+type+'&name='+v1); break;
				}
				ajobj.send(null);			
			}