/**** GESTION ARBORESCENCE ****/
cms.ShwRls=function(){
	cms.AdminInit()
	cms.ShwElm("RlsFrm")
}
cms.GatherRls=function(){
	var quick=cms.Elm('RlsFrm').quick.checked?1:0
	var ids=cms.Elm("RlsFrm").rad[0].checked?'all':cms.SelectedBlock
	if(cms.Elm("RlsFrm").rad[1].checked)cms.MultipleRls(quick,ids);else cms.DoRls(quick,ids,'')
}
cms.DoRls=function(quick,ids,del){
	cms.AdminInit()
	cms.ShwElm("RlsPrg")
	cms.RlsStr="do=admin_rls&quick="+quick+"&ids="+ids+"&toDel="+del
	txt=AJX.Req("?",cms.RlsStr+"&step=1",cms.DoRls2)
}
cms.DoRls2=function(quick,ids){txt+=AJX.Req("?",cms.RlsStr+"&step=2",cms.DoRls3)}
cms.DoRls3=function(quick,ids){txt+=AJX.Req("?",cms.RlsStr+"&step=3",cms.DoRls4)}
cms.DoRls4=function(quick,ids){txt+=AJX.Req("?",cms.RlsStr+"&step=4"+"&time="+cms.RlsTime,cms.DoRls7)}
cms.DoRls7=function(quick,ids){txt+=AJX.Req("?",cms.RlsStr+"&step=7",cms.DoRls8)}
cms.DoRls8=function(quick,ids){txt+=AJX.Req("?",cms.RlsStr+"&step=8",cms.DoRls9)}
cms.DoRls9=function(quick,ids){txt+=AJX.Req("?",cms.RlsStr+"&step=9",cms.DoRls10)}
cms.DoRls10=function(quick,ids){txt+=AJX.Req("?",cms.RlsStr+"&step=10",cms.DoRls11)}
cms.DoRls11=function(quick,ids){txt+=AJX.Req("?",cms.RlsStr+"&step=11"+"&time="+cms.RlsTime,cms.DoRlsEnd)}
cms.DoRlsEnd=function(){cms.ShwBlocks();cms.Shw='';setTimeout("cms.ShwElm('RlsPrg')",555)}


cms.UpdRls=function(id,val){eval("cms.Elm('Rls"+id+"')."+val)}


cms.MultipleRls=function(quick,ids){
	AJX.SetHtm('?','do=admin_rlsMultiple','cmsRlsMulDiv')
	cms.AdminInit()
	cms.ShwElm('RlsMulFrm')
	with(cms.Elm('RlsMulFrm')){ids.value='';toDel.value=''}
}
cms.RlsToggleVal=function(ths,elm,n){
	with(ths.form){
		var ev=elements[elm].value;n=','+n+','
		if(ths.checked){
			if(ev.indexOf(n)<0)ev+=n;ev=ev.replace(/\,\,/g,',')
		}else{
			ev=ev.replace(n,',')
		}
		elements[elm].value=ev
	}
}
cms.MultipleRlsChk=function(quick,ids){
	with(cms.Elm('RlsMulFrm')){
		if(ids.value.replace(/,/g,"")==""&&toDel.value.replace(/,/g,"")==""){
			cms.Elm('QckChkBox').checked=false;quick.value=0
			var x=cms.Elm('RlsMulDiv').getElementsByTagName('input')
			for(var i=0;i<x.length;i++)x[i].style.display='block'
			alert("Acune case cochée")
		}else{
			cms.DoRls(quick.value,encodeURIComponent(ids.value.substr(1,ids.value.length-2)),encodeURIComponent(toDel.value.substr(1,toDel.value.length-2)),true)
		}
	}
}
