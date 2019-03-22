		//alert(document.getElementsByClassName("highslide-number").innerHTML);
		
		var tgido="";
		var tmnm="";
		
		function HsFind(){
		//alert (document.getElementsByClassName("highslide-number")[0].innerHTML);
			if (document.getElementsByClassName("highslide-number")[0].innerHTML!=undefined){
				document.getElementsByClassName("highslide-number")[0].innerHTML=document.getElementsByClassName("highslide-number")[0].innerHTML + "<br>" + document.getElementById("vote_"+tgido).innerHTML;
				//document.getElementsByClassName("highslide-number")[0].innerHTML=document.getElementById("vote_"+tgido).innerHTML;
				//document.getElementsByClassName("highslide-number")[0].innerHTML="just text";
				document.getElementsByClassName("highslide-number")[0].setAttribute("align","center");
				document.getElementsByClassName("highslide-number")[0].setAttribute("style","position:relative; top:33px;");
				document.getElementsByClassName("highslide-number")[0].getElementsByTagName("table")[0].setAttribute("class","vote_panel_dark");
				document.getElementsByClassName("highslide-number")[0].getElementsByTagName("table")[0].setAttribute("style","background: rgb(153, 153, 153)"); 
				
				//alert(document.getElementsByClassName("highslide-image")[0].getAttribute("hgid"));
				clearInterval(hsfinder);
				
				tmnm = document.getElementsByClassName("highslide-image")[0].getAttribute("src");
				changerTimer=setInterval(DetectChange,500);
				
				if(document.getElementsByClassName("highslide-image")[0].getAttribute("src")==undefined){
					clearInterval(hsfinder);
					clearInterval(changerTimer);
				}
				//alert("vote_"+tgido);
			}
		}
		
		function DetectChange(){
		
			if(document.getElementsByClassName("highslide-image")[0]==undefined){
				clearInterval(changerTimer);
				return false;
			}
		
			if (document.getElementsByClassName("highslide-image")[0].getAttribute("src")!=tmnm){
				//alert ("changed");
				//alert("Src: "+document.getElementsByClassName("highslide-image")[0].getAttribute("src"));
				//alert("Class: "+document.getElementsByClassName(document.getElementsByClassName("highslide-image")[0].getAttribute("src"))[0].innerHTML);	background: -moz-linear-gradient(bottom, #bbbbbb, #111111);
				document.getElementsByClassName("highslide-number")[0].setAttribute("align","center");
				document.getElementsByClassName("highslide-number")[0].setAttribute("style","position:relative; top:33px;");
				document.getElementsByClassName("highslide-number")[0].innerHTML=document.getElementsByClassName("highslide-number")[0].innerHTML + "<br>" + document.getElementsByClassName(document.getElementsByClassName("highslide-image")[0].getAttribute("src"))[0].innerHTML;	
				document.getElementsByClassName("highslide-number")[0].getElementsByTagName("table")[0].setAttribute("class","vote_panel_dark");
				document.getElementsByClassName("highslide-number")[0].getElementsByTagName("table")[0].setAttribute("style","background: rgb(153, 153, 153)"); 
			}
			tmnm = document.getElementsByClassName("highslide-image")[0].getAttribute("src");
			
			//clearInterval(changerTimer);
		}
		
		function HsFStart(tgid){
			tgido=tgid;
			hsfinder=setInterval(HsFind, 500);
		}
		
		function LoadSFW(swf,target){
			document.getElementById("swf_"+target).innerHTML="<embed src="+swf+" width='200' height='130'>";
			document.getElementById("swf_controls_"+target).setAttribute("style","display:normal");
		}
		
		function StopSFW(target){
			document.getElementById("swf_"+target).innerHTML="<img src='big_mac_adobe_flash_icon_BW_by_tomcat94-d5l1g07.png' width='170' title='???????, ????? ????????????? ????????'><br>"
			document.getElementById("swf_controls_"+target).setAttribute("style","display:none");
		}