cms.GetLabels=function(){
	AJX.SetHtm("?","do=admin_labels&op=getLblLst&idB="+cms.SelectedBlock,"cmsLblSpn")
	cms.ShwElm('LblDiv')
}
cms.ShwSubLbl=function(th){
	with(th.nextSibling.style)display=display=='none'?'block':'none'
	with(th.firstChild.style)display=display=='none'?'block':'none'
}
cms.InsLbl=function(bck){
	if(x=prompt(cms.TXTlabelsProvideName)){
		cms.EditInsert('Lbl'+bck,x)
	}
}
cms.DelLbl=function(bck,lbl){
	if(confirm(cms.TXTlabelsAlrtDelLbl.replace("#lbl#",lbl).replace("#bck#",bck)))
	AJX.Req("?","do=admin_labels&op=del&idB="+bck+"&label="+lbl,true)
}
cms.InsFrm=function(t){
	var x=true;
	if(t.indexOf("ontent")<0){x=prompt(cms.TXTlabelsProvideId);t=t.replace(/Id/g,x)}
	if(x)cms.EditIns(t)
}
