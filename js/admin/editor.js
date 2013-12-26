cms.EditInsert=function(k,v){
	with(cmsEdt.editor){
		k=k.replace(/&lt;/g,"<").replace(/&gt;/g,">")
		k=k.replace("<edt>","").replace("</edt>","")
		var s='';if(k.substr(0,1)=='/'){s='/';k=k.substr(1)}
		insertCode('<'+s+'cms'+k+(v!=''?' '+v:'')+'>\n')
		syntaxHighlight()
		cms.HidMac()
	}
}
cms.EditInsLbl=function(id,l){
	cms.EditInsert('Lbl'+id,l)
}
cms.EditIns=function(v){
	with(cmsEdt.editor){
		cms.HidMac()
		v=v.replace(/&lt;/g,"<").replace(/&gt;/g,">").replace("<edt>","").replace("</edt>","")
		insertCode(v+"\n",1)
		syntaxHighlight()
	}
}
/***** EDITION BLOC *****/
cms.ShwEdt=function(fld){
	cms.AdminInit()
	if((USR_BLS==' all '||USR_BLS.indexOf(' '+cms.SelectedBlock+' ')>-1)&&parseInt(SHW_USR_LNS)>0){
		document.getElementById('cmsRlsIco').style.display='block'
		cms.Shw="EdtFrm"
		cms.Elm("EdtCache").style.opacity=fld!="php/"?'0.4':'1';
		cms.Elm("EdtRtm").style.opacity=fld!="php/rtm/"?'0.4':'1';
		cms.Elm("EdtMdl").style.opacity=fld!="php/mdl/"?'0.4':'1';
		cms.Elm("EdtFld").style.opacity=fld.indexOf("/upl/")<0?'0.4':'1';
		if(fld.substr(0,4)!="upl/"){
			if(fld.substr(0,4)=="php/")cms.SelectedScript="php"
			cms.ShwElm("Edt"+(fld=="php/"?"Cache":(fld=="php/rtm/"?"Rtm":"Fld")))
			try{
				var x=cms.Elm("Block"+cms.SelectedBlock).innerHTML;cms.EditBlock(fld)
			}catch(e){
				cms.SelectedFolder=fld;cms.ShwBlocks()
			}
		}else{
			cms.ShwUploads("sta")
		}
	}else{
		document.getElementById('cmsRlsIco').style.display='none'
		//alert('Not allowed to access this block')
	}
}
cms.EditBlock=function(fld){
	cms.SelectedFolder=fld
	cms.SaveBlock=cms.SelectedBlock
	cms.SaveTree=cms.SelectedTree
	cms.SaveElmt=cms.Elm("Block"+cms.SaveBlock)
	with(cms.Elm("EdtFrm")){
		cms.ShwElm("EdtDiv")
		title.value=cms.Elm("Block"+cms.SelectedBlock).innerHTML
		cms.BckIsMnu=cms.Elm("Block"+cms.SelectedBlock).getAttribute("ismenu")!="1"?false:true;isMenu.checked=cms.BckIsMnu
		cms.Elm("EdtBckId").innerHTML=cms.SelectedBlock
		cmsEdt.style.width='357px';cmsEdt.style.height='170px';
		AJX.Req("?","do=admin_edit&op=get&idB="+cms.SelectedBlock+"&fld="+fld,cms.GotEditBlock)
	}
}
cms.GotEditBlock=function(){
	cmsEdt.edit(AJX.GetBuf(0),cms.SelectedScript)
	Evt.Add(cms.Elm("EdtFrm").title,"keydown",cms.EditKey)
	cms.ERN=0;setTimeout("cms.EditRedim()",333)
}
cms.EditRedim=function(){
	cms.ERN++;
	try{obj=cmsEdt.contentWindow.document.documentElement;var ok=1}catch(e){var ok=0}
	if(ok){
		if(obj.scrollWidth+'px'!=cmsEdt.style.width||obj.scrollHeight+'px'!=cmsEdt.style.height){
			if(obj.scrollWidth)cmsEdt.style.width=(obj.scrollWidth+2<357?357:obj.scrollWidth+2)+'px'
			if(obj.scrollHeight)cmsEdt.style.height=(obj.scrollHeight<170?170:obj.scrollHeight)+'px'
			if(cms.ERN<8)cms.ERN=8
		}
	}
	if(cms.ERN<9)setTimeout("cms.EditRedim()",333)
}
cms.EditSave=function(){
	cms.SelectedBlock=cms.saveBlock
	cms.SelectedTree=cms.saveTree
	var add="";with(cms.Elm("EdtFrm")){
		add+="&tree="+cms.SaveTree+"&idB="+cms.SaveBlock
		add+=title.value!=cms.SaveElmt.innerHTML?"&title="+encodeURIComponent(title.value):""
		add+=isMenu.checked!=cms.BckIsMnu?"&isMenu="+(cms.BckIsMnu?0:1):""
	}
	AJX.Req("?","do=admin_edit&op=sav&fld="+cms.SelectedFolder+"&content="+encodeURIComponent(cmsEdt.getCode())+add,cms.ShwBlocks)
}
cms.EditKey=function(ev){
	if(Evt.GetKey(ev)=="ctrlS"){
		Evt.Cancel(ev)
		Evt.Rmv(cms.Elm("EdtFrm").title,"keydown",cms.EditKey)
		cms.EditSave()
	}
}

