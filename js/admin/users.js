cms.ShwUsers=function(){
	cms.AdminInit()
	cms.HidElm('Tree')
	AJX.SetHtm('?','do=admin_users','cmsUsers')
}
cms.ShwUsr=function(o,g,u,it,b,ic,l,ip){
	if(o=="add")cms.SelectedUsr.style.color=cms.UsrColor
	with(cms.Elm("UsrFrm")){
		elements["op"].value=o
		elements["user"].value=u
		elements["user"].disabled=o!="upd"?false:true
		elements["user"].style.borderWidth=o!="upd"?"1px":0
		elements["user"].style.backgroundColor=o!="upd"?"white":""
		elements["group"].value=g
		elements["group"].disabled=/*o!="upd"?false:*/true
		elements["group"].style.borderWidth=o!="upd"?1:0
		elements["pass"].value=""
		elements["pass1"].value=""
		elements["ips"].value=ip
		cms.Elm("UsrDiv").style.display="block"
		
		// la langue de l'interface
		cmsLngsSel=elements['interface'];cmsLngsOps=cmsLngsSel.options;
		for(var i=0;i<cmsLngsOps.length;i++)cmsLngsOps[i].selected=cmsLngsOps[i].value==it?true:false

		// les langues
		cmsLngsSel=elements['lngsSel'];cmsLngsOps=cmsLngsSel.options;l=" "+l+" "
		for(var i=0;i<cmsLngsOps.length;i++)cmsLngsOps[i].selected=l.indexOf(" "+cmsLngsOps[i].value+" ")>-1||l==' all '?true:false
		elements['lngs'].value=''
		
		// les blocks
		cmsBcksChks=cms.Elm('ChkBlocks').getElementsByTagName('input');b=" "+b+" "
		for(var i=0;i<cmsBcksChks.length;i++)
			if(cmsBcksChks[i].type.toLowerCase()=='checkbox'){
				cmsBcksChks[i].checked=b.indexOf(" "+cmsBcksChks[i].value+" ")>-1||b==' all '?true:false
			}
		elements['blocks'].value=''

		// les icones
		cmsUsrIcos=cms.Elm('UsrIcos').getElementsByTagName('input');ic=" "+ic+" "
		for(var i=0;i<cmsUsrIcos.length;i++)
			if(cmsUsrIcos[i].type.toLowerCase()=='checkbox'){
				cmsUsrIcos[i].checked=ic.indexOf(" "+cmsUsrIcos[i].value+" ")>-1||ic==' all '?true:false
			}
		elements['icons'].value=''
		
		if(o!="upd")elements["user"].focus();else elements["pass"].focus()
	}
	if(u!="")setTimeout("cms.SetUsrColor('"+u+"')",33)
	cms.ShwElm('Users')
	cms.ShwElm("UsrDiv")
}
cms.SelectedUsr=""
cms.SetUsrColor=function(u){
	var cmsUsr=cms.Elm('Usr'+u);cms.UsrColor=cmsUsr.style.color
	if(cms.SelectedUsr!="")cms.SelectedUsr.style.color=cms.UsrColor
	cmsUsr.style.color="red"
	cms.SelectedUsr=cmsUsr
}
cms.DelUsr=function(u,g){
	if(confirm(cms.TXTusersAlrtDelUser))AJX.SetHtm('?','do=admin_users&op=del&user='+u+'&group='+g,'cmsUsers')
}
cms.ChkUsrFrm=function(){
	with(cms.Elm("UsrFrm")){
		if(elements["pass"].value==elements["pass1"].value){
			if(elements["pass"].value.length>2||elements["pass"].value.length<1){
				//les langues
				l="all";for(var i=0;i<cmsLngsOps.length;i++){
					if(!cmsLngsOps[i].selected)l="";else elements['lngs'].value+=" "+cmsLngsOps[i].value
				}
				if(l=='all')elements['lngs'].value=l
				
				//les blocs
				b="all";for(var i=0;i<cmsBcksChks.length;i++)
					if(cmsBcksChks[i].type.toLowerCase()=='checkbox'){
						if(!cmsBcksChks[i].checked)b="";else elements['blocks'].value+=" "+cmsBcksChks[i].value
					}
				if(b=='all')elements['blocks'].value=b
				
				//les icones
				ic="all";for(var i=0;i<cmsUsrIcos.length;i++)
					if(cmsUsrIcos[i].type.toLowerCase()=='checkbox'){
						if(!cmsUsrIcos[i].checked)b="";else elements['icons'].value+=" "+cmsUsrIcos[i].value
					}
				if(ic=='all')elements['icons'].value=b
				
				AJX.SetHtm('index.php','do=admin_users&'+AJX.SrzFrm(cms.Elm("UsrFrm")),'cmsUsers')
			}else alert(cms.TXTusersAlrtPass)
		}else alert(cms.TXTusersAlrtPassNotId+elements["pass"].value+"=="+elements["pass1"].value)
	}
}

