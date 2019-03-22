	function ArchiveFrame(){
                                
		document.getElementById("frm_bg").setAttribute("style","background:#333;"+
                                                               "opacity: 0.9;"+
                                                               "display:normal;"+
                                                               "z-index:4;"+
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
                                                                   "z-index:5;"+
                                                                   "position:absolute;"+
																   "width:600px;"+
																   //"height:400px;"+
                                                                   "top:"+((window.innerHeight/2)-200 + window.pageYOffset )+"px;"+
                                                                   "left:"+((window.innerWidth/2)-300)+"px;");
		document.getElementById("status").innerHTML="";
		document.getElementById("log").innerHTML="";		
	}
	
	function HideFrame(){
		document.getElementById("frm_bg").setAttribute("style","display:none;");
		document.getElementById("poupup_wnd").setAttribute("style","display:none;");
	}