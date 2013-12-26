/*
	L'objet AJX est un objet permettant de traiter ajax en standard
	AJX.Req(fil,prm,rdy) est la fonction de requête standard (l'option rdy permet de définir une fonction à éxecuter àla fin de la requête ajax)
	AJX.SetHtm(fil,prm,bid) rempli l'objet d'id bid avec le résultat de la requête et évalue les scripts (voir ci-dessous)
	AJX.GetBuf(ok) renvoie le résultat de la requête ajax, enregistre les scripts dans AJX.scripts et si la variable ok est true les traite directement
	AJX.SrzFrm(frm) permet de sérialiser un formulaire (objet frm) pour l'option prm de AJX.Req
	AJX.progressId est l'id d'un visuel que vous pouvez définir afin d'afficher que la transaction est en cours
	AJX.alert est une variable javascript que vous pouvez renseigner (utilisée pour alerter lorsqu'une transaction n'est pas terminée)
*/
AJX=new Object();
AJX.progressId='progress';
AJX.alert='The last ajax transaction could not be fullfilled with the server.\nDo you want to go on with this new request?';
AJX.Req=function(fil,prm,rdy){
	AJX.progress=document.getElementById(AJX.progressId);
	var ok=AJX.progress?AJX.progress.style.visibility!="visible":true
	if(!ok)ok=confirm(AJX.alert)
	if(ok){
		if(AJX.progress)AJX.progress.style.visibility="visible";
		AJX.socket=window.XMLHttpRequest?new XMLHttpRequest():new ActiveXObject("Microsoft.XMLHTTP")
		with(AJX.socket){
			AJX.ready=rdy?rdy:function(){}
			onreadystatechange=AJX.Rdy
			open("POST", fil, rdy?true:false);
			setRequestHeader("Content-Type", "application/x-www-form-urlencoded")
			if(prm)setRequestHeader("Content-length", prm.length)
			send(prm?prm:null);
		}
	}
}
AJX.SetHtm=function(fil,prm,bid){
	AJX.blockId=bid
	AJX.Req(fil,prm,'htm')
}
AJX.Rdy=function(){
	with(AJX.socket){
		if(readyState==4){
			if(status==200){
				if(AJX.progress)AJX.progress.style.visibility="hidden";
				if(AJX.ready!='htm'){
					AJX.GetBuf(1);AJX.ready(); // GetBuf(1) pour évaluer d'éventuels scripts
				}else{
					try{document.getElementById(AJX.blockId).innerHTML=AJX.GetBuf(0);eval(AJX.scripts)}catch(e){}
					AJX.blockId=""
				}
			}else alert("Ajax problem:\n\n"+AJX.GetBuf(0))
		}
	}
}
AJX.GetBuf=function(ok){
	var t=AJX.socket.responseText;AJX.scripts=""
	if(t.indexOf("<script>")>-1){ // attention on veut garder les scripts sans les évaluer lors de l'édition
		var x=t.split("<script>");var t1=x[0];
		for(var i=1;i<x.length;i++){var p=x[i].indexOf("</script>");t1+=x[i].substr(p+9);AJX.scripts+=x[i].substr(0,p)+";"}
		if(ok)try{eval(AJX.scripts);t=t1}catch(e){};
	}
	if(t.indexOf("Fatal error:")>-1||t.indexOf("Notice:")>-1)alert("AJAX OOP MESSAGE:\n\n"+t)
	return t
}
AJX.SrzFrm=function(frm){
	var t=""
	with(frm){
		for(var i=0;i<elements.length;i++)if(elements[i].name!=""){
			t+="&"+elements[i].name+"="+encodeURIComponent(elements[i].value)
		}
	}
	return t.substr(1)
}
