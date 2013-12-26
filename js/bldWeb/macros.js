cms.MacroShowHtml=function() {
	th=cms.SelectedLabel;
	var w=th.offsetWidth;var h=th.offsetHeight
	w=Math.max(155,w);w=Math.min(innerWidth-99,w)
	h=Math.max(33,h);h=Math.min(innerHeight-33,h)
	inH=th.innerHTML
	cms.MacroOut(cms.SelectedLabel)
	th.innerHTML=	"<textarea id=txa"+th.parentNode.id+
				" onblur=cms.MacroTxaOut(this)"+
				" style=\"border:1px solid black;width:"+w+"px;height:"+h+"px\">"+
			"</textarea>"
	cms.SelectedLabel=th.firstChild;
	cms.SelectedLabel.value=inH.replace(/<br>/gi,"<br>\n").replace(/<\/li>/gi,"</li>\n").replace(/\n\n/g,"\n")
	cms.SelectedLabel.focus()
	Evt.Add(cms.SelectedLabel,"keydown",cms.LabelKey)
	cms.SelectedEas.style.display='none'
}
cms.MacroTxaOut=function(th) {
	Evt.Rmv(th,"keydown",cms.LabelKey)
	th.parentNode.innerHTML=th.value.replace("< ","&lt;")
	cms.SelectedLabel=th.parentNode
}
cms.MacroOver=function(th) {
	var ok=1;try{ok=th.firstChild.tagName.toLowerCase()!='textarea'}catch(e){}
	if(ok){
		cms.AdminInit()
		if(cms.SelectedLabel)cms.MacroOut(cms.SelectedLabel)
		if(cms.SelectedEas)cms.SelectedEas.style.display='none'
		if(document.getElementById(th.id+'_mnu')){
			cms.SelectedEas=document.getElementById(th.id+'_mnu')
			cms.SelectedEas.style.top=(Evt.mouse.Y-10)+'px'
			cms.SelectedEas.style.display='block'
			cms.SelectedEas.innerHTML=document.getElementById('cms_easEdit').innerHTML
		}
		cms.SelectedLabel=th
		Evt.Add(th,"keydown",cms.LabelKey)
	}
}
cms.MacroOut=function(th) {
	if(th)Evt.Rmv(th,"keydown",cms.LabelKey)
}
cms.GetStyle=function(obj,prop){
	if(obj.currentstyle)str=obj.currentstyle[prop];
	else if( window.getComputedStyle)str=window.getComputedStyle(obj,null).getPropertyValue(prop);
	return str
}
cms.LabelSav=function(){
	if(cms.SelectedLabel){
		var x=cms.SelectedLabel.id.split("_")
		var vl=cms.SelectedLabel.tagName.toLowerCase()=="textarea"
		var ttl=vl?cms.SelectedLabel.value:cms.SelectedLabel.innerHTML
		AJX.Req('?','do=admin_labels&op=upd&idB='+x[1]+'&label='+x[2]+'&title='+encodeURIComponent(ttl),false)
	}
}
cms.LabelKey=function(ev){
	var key=Evt.GetKey(ev);lky=key.toLowerCase();
	var w="";if(key.substr(0,4)=="ctrl")w=key.substr(4)
	var strS='0à1&2Éé3"4\'5(6-BUI';var str=strS+'SLERJ'
	if(key=='tab'||(w!=""&&str.indexOf(w)>-1))Evt.Cancel(ev)
	if(strS.indexOf(w)>-1){
		var s=clp.getSelection();
		if(s){
			s=s.replace(/</g,'&lt;');// à cause des bizarreries des contenteditable
			if(key=="ctrl0"||key=="ctrl&")clp.insertHTML(cms.superClean(s))
			if(key=="ctrl1"||key=="ctrl&")clp.insertHTML('<h1>'+s+'</h1>')
			if(key=="ctrl2"||lky=="ctrlé")clp.insertHTML('<h2>'+s+'</h2>')
			if(key=="ctrl3"||key=='ctrl"')clp.insertHTML('<h3>'+s+'</h3>')
			if(key=="ctrl4"||key=="ctrl'")clp.insertHTML('<h4>'+s+'</h4>')
			if(key=="ctrl5"||key=="ctrl(")clp.insertHTML('<h5>'+s+'</h5>')
			if(key=="ctrl6"||key=="ctrl-")clp.insertHTML('<h6>'+s+'</h6>')
			if(key=="ctrlB")clp.insertHTML('<strong>'+s+'</strong>')
			if(key=="ctrlI")clp.insertHTML('<i>'+s+'</i>')
			if(key=="ctrlU")clp.insertHTML('<u>'+s+'</u>')
		}
	}
	if(key=="ctrlS")cms.LabelSav()
	if(key=="ctrlL")document.execCommand('justifyleft',false,'')
	if(key=="ctrlM")document.execCommand('justifycenter',false,'')
	if(key=="ctrlR")document.execCommand('justifyright',false,'')
	if(key=="ctrlJ")document.execCommand('justifyfull',false,'')
	if(key=="tab")document.execCommand('indent',false,'')
	if(cms.SelectedLabel)cms.SelectedLabel.focus();
}
cms.InsClass=function(c){
	var s=clp.getSelection()
	if(s)clp.insertHTML('<span class='+c+'>'+s+'</span>');else alert(cms.TXTmacrosEmptySelection)
	document.getElementById('cms_easEdit_css').style.display='none'
}
cms.InsTag=function(c){
	var s=clp.getSelection()
	if(s)clp.insertHTML('<'+c+'>'+s+'</c>');else alert(cms.TXTmacrosEmptySelection)
	document.getElementById('cms_easEdit_css').style.display='none'
}
cms.SavMnu=function(id,tree,ttl){
	if(x=prompt('Changer le menu',ttl))AJX.Req("?","do=admin_edit&op=sav&idB="+id+"&tree=blocks-"+tree+"&title="+encodeURIComponent(x),false)
}
cms.superClean=function(html) {
	// Remove HTML comments
	html = html.replace(/<!--[\w\s\d@{}:.;,'"%!#_=&|?~()[*+\/\-\]]*-->/gi, "" );
	html = html.replace(/<!--[^\0]*-->/gi, '');
	// Remove all HTML tags
	html = html.replace(/<\/?\s*HTML[^>]*>/gi, "" );
	// Remove all BODY tags
	html = html.replace(/<\/?\s*BODY[^>]*>/gi, "" );
	// Remove all META tags
	html = html.replace(/<\/?\s*META[^>]*>/gi, "" );
	// Remove all SPAN tags
	html = html.replace(/<\/?\s*SPAN[^>]*>/gi, "" );
	// Remove all FONT tags
	html = html.replace(/<\/?\s*FONT[^>]*>/gi, "");
	// Remove all IFRAME tags.
	html = html.replace(/<\/?\s*IFRAME[^>]*>/gi, "");
	// Remove all STYLE tags & content
	html = html.replace(/<\/?\s*STYLE[^>]*>(.|[\n\r\t])*<\/\s*STYLE\s*>/gi, "" );
	// Remove all TITLE tags & content
	html = html.replace(/<\s*TITLE[^>]*>(.|[\n\r\t])*<\/\s*TITLE\s*>/gi, "" );
	// Remove javascript
	html = html.replace(/<\s*SCRIPT[^>]*>[^\0]*<\/\s*SCRIPT\s*>/gi, "");
	// Remove all HEAD tags & content
	html = html.replace(/<\s*HEAD[^>]*>(.|[\n\r\t])*<\/\s*HEAD\s*>/gi, "" );
	// Remove Class attributes
	html = html.replace(/<\s*(\w[^>]*) class=([^ |>]*)([^>]*)/gi, "<$1$3") ;
	// Remove Style attributes
	html = html.replace(/<\s*(\w[^>]*) style="([^"]*)"([^>]*)/gi, "<$1$3") ;
	// Remove Lang attributes
	html = html.replace(/<\s*(\w[^>]*) lang=([^ |>]*)([^>]*)/gi, "<$1$3") ;
	// Remove XML elements and declarations
	html = html.replace(/<\\?\?xml[^>]*>/gi, "") ;
	// Remove Tags with XML namespace declarations: <o:p></o:p>
	html = html.replace(/<\/?\w+:[^>]*>/gi, "") ;
	// Replace the &nbsp;
	html = html.replace(/&nbsp;/g, " " );

	// Transform <p><br /></p> to <br>
	//html = html.replace(/<\s*p[^>]*>\s*<\s*br\s*\/>\s*<\/\s*p[^>]*>/gi, "<br>");
	html = html.replace(/<\s*p[^>]*><\s*br\s*\/?>\s*<\/\s*p[^>]*>/gi, "<br>");
	
	// Remove <P> 
	html = html.replace(/<\s*p[^>]*>/gi, "");
	
	// Replace </p> with <br>
	html = html.replace(/<\/\s*p[^>]*>/gi, "<br>");
	
	// Remove any <br> at the end
	html = html.replace(/(\s*<br>\s*)*$/, "");
	
	html = html.trim();
	return html;
} 
