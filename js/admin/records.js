//les fonctions de l'édition en ligne des enregistrements
cms.RecShwOk=1
cms.RecordShw=function(uid,uidP){
	if(cms.RecShwOk){
		var ary=document.getElementsByTagName('cmsfrm');for(var i=0;i<ary.length;i++)ary[i].style.display='none'
		cms.RecShwOk=0
		document.getElementById('cmsxfrm'+uid).style.display='block';
		if(document.getElementById('cmsxfrm'+uid).innerHTML=='')AJX.SetHtm('?','do=admin_records&op=getForm&uid='+uid+'&uidP='+uidP,'cmsxfrm'+uid)
		cms.toId=uid.substr(uid.indexOf('_')+1)
		setTimeout('cms.RecShwOk=1',0)
	}
}
cms.RecordDo=function(uid,uidP,opr){
	var idB=uid.substr(0,uid.indexOf('_'))
	with(document.forms['frm'+uid]){
		elements["op"].value=opr
		if(opr=='mov')elements["toId"].value=uidP.substr(uidP.indexOf('_')+1)
		if(opr=='add'){
			elements['id'+idB].value=''
			elements["op"].value='sav'
		}
		submit()
	}
}

// les fonctions du popup enregistrements
cms.ShwRec=function(){
	var b=cms.SelectedBlock
	var x=cms.Elm("RecFrm").getElementsByTagName('edt');for(var i=0;i<x.length;i++)x[i].innerHTML=b

	cms.HidElm('RecMacDiv');var Fields=cms.Elm('Fields').value;var FieldsLang=cms.Elm('FieldsLang').value
	if(Fields!=""||FieldsLang!=""){	//construction du formulaire
		var mac="";var fds="\n\t<input type=hidden name=tables[] value="+b+">";x=new Array();y=new Array()
		var frm="<iframe name=ifm"+b+" style=display:none></iframe>"+
			"\n<div id=msg"+b+" style=color:red;display:none>¤cmsLbl"+b+" donneesEnregistrees¤</div>"+
			"\n<form name=frm"+b+" method=post enctype=mutipart/form-data target=ifm"+b+">"+
				"\n\t<input type=hidden name=op value=sav>"+
				"\n\t<input type=hidden name=do value=frmSbm>"+
				"\n\t<input type=hidden name=mailTo[] value=''>"
				
		if(cms.Elm('isVrt').checked){
			x[x.length]="id"+b
			fds+="\n\t<input type=hidden name=id"+b+" value=#rtm"+b+".id"+b+"#>";
			fds+="\n\t<input type=hidden name=afterId"+b+" value=#rtm"+b+".afterId"+b+"#>";
		}
		var linkTo=cms.RecGetLnkTo();for(var i=0;i<linkTo.length;i++){
			x[x.length]="id"+linkTo[i]+" "
			fds+="\n\t<input type=hidden name=id"+linkTo[i]+" value=#rtm"+b+".id"+linkTo[i]+"#>";
		}
		y=Fields.split("\n");for(i=0;i<y.length;i++)x[x.length]=y[i]
		y=FieldsLang.split("\n");for(i=0;i<y.length;i++)if(y[i]!="")x[x.length]=y[i]
		var tim="";var m=new Array("Y","m","d","H","i","s");for(i=0;i<m.length;i++)tim+="|input type=checkbox value="+m[i]+" style=position:relative;bottom:-3px>"+m[i]
		for(var i=0;i<x.length;i++){
			var y=x[i].split(" ")
			var f=y[0];var t=y[1]
			mac+=	"<div style=display:table;height:9px>"+
					"<div class=cmsMacElm onclick=cms.EditIns(this.innerHTML)>"+(i?"&lt;":"¤")+"cmsRec"+b+" "+f+(i?">":"¤")+"</div>"+
					"<div class=cmsMacFld>"+f+"</div>"+
				"</div>"
			if(t=="text"||t=="time")fds+="\n\t<input type=text name="+f+b+" value=\"#rtm"+b+"."+f+"#\">"
			if(t=="textarea")fds+="\n\t<textarea name="+f+b+">#rtm"+b+"."+f+"#</textarea>"
			if(t=="checkbox")fds+="\n\t<input type=checkbox name="+f+b+" #rtm"+b+"."+f+"#>"
			if(t=="file")fds+="\n\t<input type=file name="+f+b+">"
			if(t=="tree")fds+="\n\t<cmsTree"+b+" "+f+"-|select>|option>select|option>select-multiple|option>checkbox|option>radio|/select>>"
		}
		frm+="\n</form>";
		cms.Elm('RecMacSpn').innerHTML=frm.replace(/</g,"&lt;").replace(/\n/g,"<br>").replace(/\t/g,"<span style=margin-left:33px></span>").replace(/\|/g,"<")
		cms.Elm('RecMacSpnFds').innerHTML=fds.replace(/</g,"&lt;").replace(/\n/g,"<br>").replace(/\t/g,"<span style=margin-left:33px></span>").replace(/\|/g,"<")
		cms.Elm('RecMac').innerHTML=mac
		cms.Elm('RecMacDiv').style.display='table'
	}else cms.HidElm('RecMacDiv')

	cms.ShwElm("RecDiv")
	Evt.Add(cms.Elm("RecDiv"),"keypress",cms.RecKey)
}
cms.PstMac=function(obj){ // paste CMS Macros
	var t=obj.innerHTML
	if(obj==cms.Elm("RecMacLst")){
		var x=t.replace(/<\/div><\/div>/gi,"\n").replace(/\"/g,"").replace(/<edt>/g,"").replace(/<\/edt>/g,"").split(/<div class=cmsMacElm/i)
		t="\n&lt;cmsRec"+cms.SelectedBlock+"&gt;"
		for(var i=0 ;i<x.length;i++){
			var u=x[i].substr(x[i].indexOf(">")+1)
			t+="\t"+u.substr(0,u.indexOf("<"))+"\n"
		}
		t+="&lt;/cmsRec"+cms.SelectedBlock+"&gt;\n\n"
	}
	if(t.indexOf("select>")>-1){
		var y=t.split(/select>/g);t=y[0].substr(0,y[0].length-1)
		var x=obj.getElementsByTagName('select');
		for(i=0;i<x.length;i++)with(x[i]){var j=2*(1+i);t+=options[selectedIndex].text+y[j].substr(0,y[j].length-(i<x.length-1?1:0))}
	}
	cms.HidMac()
	return t
}
cms.HidMac=function(){ // Hide CMS Macros
	cms.HidElm('FrmDiv');cms.HidElm('LblDiv');cms.HidElm('RecDiv');cms.HidElm('MnuDiv')
}
cms.RecKey=function(ev){
	if(Evt.GetKey(ev)=="ctrlS"){
		Evt.Cancel(ev)
		cms.chkRecFrm()
	}
}
cms.chkVrt=function(th){
	if(!th.checked){
		with(cms.Elm("RecLnkSel")){
			if(selectedIndex<1){
				alert(cms.TXTrecordsAlrtKey)
				th.checked=true
				th.form.isVrt.value=1
			}else th.form.isVrt.value=0
		}
	}else{
		th.form.isVrt.value=1;
		th.checked=true
	}
}
cms.RecInsTyp=function(th,obj){
	obj.focus();
	if(x=prompt(cms.TXTrecordsFieldName)){
		if(x.indexOf(" ")>-1){
			alert(cms.TXTrecordsAlrtFieldSpaces);
		}else{
			var z=obj.value;var y="\n"+z
			if(y.indexOf("\n"+x+" ")<0){
				with(th)obj.value+=(z.substr(z.length-1)!="\n"&&z!=""?"\n":"")+x+" "+options[selectedIndex].text
			}else alert("Ce champs existe déjà!")
		}
	}
}
cms.RecGetLnkTo=function(){
	var ar=new Array()
	with(cms.Elm("RecLnkSel"))for(var i=0;i<options.length;i++)if(options[i].selected)ar.push(options[i].value)
	return ar
}
cms.chkRecFrm=function(){
	with(cms.Elm('RecFrm')){
		var linkTo=cms.RecGetLnkTo()
		var ids='';
		if(isVrt.value=="1")ids+="id"+cms.SelectedBlock+" \n"// les espaces de fin de ligne = nécessaires comme séparateur
		for(var i=0;i<linkTo.length;i++)ids+="id"+linkTo[i]+" \n"
		var fields=cms.Elm('Fields').value.replace(/  /g," ").replace(/\n\n/g,"\n")
		var fieldsLang=cms.Elm('FieldsLang').value.replace(/  /g," ").replace(/\n\n/g,"\n")
		if(fields==''&&fieldsLang==''){
			alert(cms.TXTrecordsAlrtAtLeast1Field)
		}else{
			while(fields.indexOf("\n\n")>-1)fields=fields.replace("\n\n","\n")
			while(fieldsLang.indexOf("\n\n")>-1)fieldsLang=fieldsLang.replace("\n\n","\n")
			var ok=1;var ffL="\n"+fields+"\n"+fieldsLang;while(ffL.indexOf("\n\n")>-1)ffL=ffL.replace("\n\n","\n")
			var xf=ffL.split("\n");for(i=0;i<xf.length;i++){
				if(xf[i]!=""){
					y=xf[i].split(" ");if(y.length<3){ok=0;alert(cms.TXTrecordsAlrtMissingParam+' '+xf[i]);i=xf.length}
					if(y[1].substr(0,5)=='alien'){
						var z=y[1].split(':');if(z.length<3){ok=0;alert(cms.TXTrecordsAlrtMissingParam+' '+xf[i]);i=xf.length}
					}
					if(ok){z=ffL.split("\n"+y[0]+"\n");if(z.length>2){ok=0;alert(cms.TXTrecordsAlrtOnlyOneField+" "+y[0])}}
				}
			}
			if(ok)AJX.Req("?","do=admin_records&op=sav&idB="+cms.SelectedBlock+"&isForm="+isForm.value+"&fields="+encodeURIComponent(ids+"\n"+fields+"\n\n"+fieldsLang),false)
		}
	}
}
cms.RecFrmIns=function(h){
	cms.EditIns(h.replace(/&lt;/g,'<').replace(/<br>/g,'\n').replace(/<span style="margin-left: 33px;"><\/span>/gi,'\t'))
}
