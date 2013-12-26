cms.ShwProps=function(){
	cms.AdminInit()
	AJX.Req('?','do=admin_props',cms.GotPrp)
}
cms.GotRst=function(){
	location.replace("?env=dev")
}
cms.GotPrp=function(){
	cms.ShwElm('PrpFrm')
	cms.Elm('DbSavDiv').innerHTML="<select id=cmsDbSav style=text-align:left;width:150px>"+cms.DbSav+"</select>"
	cms.Elm('AutoSav').checked=cms.AutoSav
	cms.Elm('SavEvry').value=cms.SavEvry
	cms.Elm('LngsDiv').innerHTML="<select id=cmsLngs multiple style=\"height:33px;width:99px\" onfocus=\"this.setAttribute('size',25)\" onblur=\"this.setAttribute('size',2)\">"+cms.LngsOpts+"</div>"
	cms.Elm('DevLngDiv').innerHTML="<select id=cmsDevLng multiple style=height:33px>"+cms.DevLngOpts+"</div>"
	cms.Elm('PrdLngDiv').innerHTML="<select id=cmsPrdLng multiple style=height:33px>"+cms.PrdLngOpts+"</div>"
	cms.Elm('PrpFrmRwt').checked=cms.ModRwt
	cms.PropsChkPrd()
}
cms.PropsSetLng=function(fr,to){
	var fs=cms.Elm(fr);var ts=cms.Elm(to);
	var si=fs.selectedIndex
	if(si>-1){
		ts.options[ts.options.length]=fs.options[si]
		ary=new Array();for(i=0;i<fs.length;i++)if(i!=si)ary[i]=fs.options[i];
		try{fs.options=ary}catch(e){}
	}
	cms.PropsChkPrd()
}
cms.PropsDefault=function(){
	var ps=cms.Elm("PrdLng");si=ps.selectedIndex
	if(si>-1){
		var po=ps.options;var pst=po[si].text;var psv=po[si].value
		for(i=si;i>=1;i--){
			po[i].value=po[i-1].value;
			po[i].text=po[i-1].text
		}
		po[0].text=pst;po[0].value=psv;
		ps.selectedIndex=0
	}
}
cms.PropsChkPrd=function(){
	cms.Elm('PrdArw').style.display=cms.Elm('PrdLng').options.length<2?'none':'block'
	cms.Elm('LngUp').style.display=cms.Elm('PrdLng').options.length<2?'none':'block'
	cms.Elm('PrdLng').disabled=cms.Elm('PrdLng').options.length<2?true:false
	cms.Elm('DefLng').innerHTML=cms.TXTpropsDefaultLang+":<b> "+cms.Elm("PrdLng").options[0].text+"</b> ("+cms.TXTpropsFirstLang+")"
}
cms.PropsSavLng=function(){
	var prd=cms.Elm("PrdLng").options;var dev=cms.Elm("DevLng").options
	var tp='';for(i=0;i<prd.length;i++)tp+="lng."+prd[i].value+" = \""+prd[i].text+"\"\n"
	var td='';for(i=0;i<dev.length;i++)td+="lng."+dev[i].value+" = \""+dev[i].text+"\"\n"
	AJX.Req('?','do=admin_props&op=sav&dev='+encodeURIComponent(td)+'&prd='+encodeURIComponent(tp),cms.GotPrp)
}
cms.PropsSavRwt=function(ok){
	AJX.Req('?','do=admin_props&op=savRwt&rwt='+(ok?1:0),false)
}
