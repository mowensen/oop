function rdyAjax(){
	with(AJAX){
		if(readyState==4){
			if(status==200){
				var t=chkAjxTxt(1);
				if(RDY.toString()!="true"){
					RDY();
				}else{
					try{document.getElementById(ajxBlk).innerHTML=t}catch(e){}
					ajxBlk=""
				}
			}else alert("Ajax problem:\n\n"+chkAjxTxt(0))
		}
	}
}
function ajxHtm(fil,prm,bk){
	ajxBlk=bk
	ajax(fil,prm,true)
}
function ajax(fil,prm,rdy){
	progres=document.getElementById("progress");
	var ok=progres?progres.style.visibility!="visible":true
	if(ok){
		if(progres)progres.style.visibility="visible";
		AJAX=window.XMLHttpRequest?new XMLHttpRequest():new ActiveXObject("Microsoft.XMLHTTP")
		with(AJAX){
			if(rdy){RDY=rdy;onreadystatechange=rdyAjax}
			open("POST", fil, rdy?true:false);
			setRequestHeader("Content-Type", "application/x-www-form-urlencoded")
			if(prm)setRequestHeader("Content-length", prm.length)
			send(prm?prm:null);
			if(rdy);else return chkAjxTxt(0)
		}
	}else{try{alert(prgsNotFinished)}catch(e){alert('Ajax could not finish transaction')}}
}
function chkAjxTxt(ok){
	var t=AJAX.responseText;SCR=""
	if(progres)progres.style.visibility="hidden";
	if(ok)if(t.indexOf("<script>")>-1){
		var x=t.split("<script>");t=x[0];for(var i=1;i<x.length;i++){var p=x[i].indexOf("</script>");t+=x[i].substr(p+9);SCR+=x[i].substr(0,p)+";"}
		eval(SCR);
	}
	if(t.indexOf("Fatal error:")>-1||t.indexOf("Notice:")>-1)alert("AJAX OOP MESSAGE:\n\n"+t)
	return t
}
function srzAjax(frm){
	var t=""
	with(frm){
		for(var i=0;i<elements.length;i++)if(elements[i].name!=""){
			t+="&"+elements[i].name+"="+encodeURIComponent(elements[i].value)
		}
	}
	return t.substr(1)
}
