cms.ShwTraces=function(){
	cms.AdminInit()
	var sels=cms.Elm('TraceDv').getElementsByTagName('select');var ary=[];
	for(var i=0;i<sels.length;i++)if(sels[i].selectedIndex)ary[ary.length]=sels[i].name+'='+sels[i].value
	AJX.SetHtm('?'+ary.join('&'),'do=admin_trace','cmsTraceDv')
	cms.ShwElm('Trace')
}
