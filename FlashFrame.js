document.writeln("<div id='frm_bg' style='display: none;' onclick='HideFrame()'></div>");
document.writeln("<div id='poupup_wnd' style='display: none;'> Random data </div>");

	function ShowFlashFrame(target){
		StopSFW(target);	
		document.getElementById("frm_bg").setAttribute("style","background:#333;"+
                                                               "opacity: 0.9;"+
                                                               "display:normal;"+
                                                               "z-index:1;"+
                                                               "width:"+(window.innerWidth)+"px;"+
                                                               "height:"+(document.body.scrollHeight)+"px;"+
                                                               "position:absolute;"+
                                                               "top:0px;"+
                                                               "left:0px;");
                
		document.getElementById("poupup_wnd").setAttribute("style","border-radius:15px;"+
                                                                   "border:solid 3px #333;"+
																   "font-family: sans-serif;"+
                                                                   "background:#ccc;"+
                                                                   "display:normal;"+
                                                                   "z-index:2;"+
                                                                   "position:absolute;"+
                                                                   "top:"+((window.innerHeight/2)-400)+"px;"+
                                                                   "left:"+((window.innerWidth/2)-300)+"px;"+
																   "width:"+800+"px"+
																   "height"+600+"px"
																   );																   
	};
																   
	function HideFrame(){
		document.getElementById("frm_bg").setAttribute("style","display:none;");
		document.getElementById("poupup_wnd").setAttribute("style","display:none;");
	};
	
	//alert(document.body.scrollHeight);