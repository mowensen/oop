cms.DoTree=function(op,idB,tree,idTree,aft){
	with(cms.SelectedSelTree){
		var idX=id;var txt=innerHTML
		if(op=='mov')if(aft<1){aft=1;idX=id1}
		var idTree=idTr
		var typ=type
	}
	ok=1;var tx="";after=""
	if(op=='add'||(op=='addTree'&&aft==2))ok=(tx=prompt(cms.TXTtreesOptionName))
	if(op=='upd')ok=(tx=prompt("Renommer l'option",txt))
	if(op=='mov'||op=='addTree')var after="&after="+aft
	if(op=='del')ok=confirm('Désirez-vous vraiment supprimer cette option?')
	if(ok)AJX.SetHtm('?','do=admin_trees&shwAdm=1&op='+op+'&idB='+idB+'&tree='+tree+'-'+idTree+'&id='+idX+'&title='+tx+'&type='+typ+after,'cmsTree-'+tree)
}
cms.TreeChged=function(th,tree,idTree,id,si,SI,id1,typ){
	var o=cms.Elm('TreeSpn-'+tree);var obj=o.firstChild
	obj.firstChild.style.display=si?'inline':'none'
	obj.nextSibling.firstChild.style.display=si<SI-1?'inline':'none'
	obj.nextSibling.nextSibling.nextSibling.nextSibling.nextSibling.firstChild.style.display=SI>1||idTree!=""?'inline':'none'
	o.style.display='inline'
	th.style.color='orange'
	try{cms.SelectedSelTree.style.color=''}catch(e){}
	cms.SelectedSelTree=th
	cms.SelectedSelTree.tree=tree
	cms.SelectedSelTree.idTr=idTree
	cms.SelectedSelTree.id=id
	cms.SelectedSelTree.id1=id1
	cms.SelectedSelTree.type=typ
}
cms.clkMnu=function(th){
	cms.SelectedEditableMnu=th
	Evt.Add(th,'keypress',cms.GetMnu)
}
cms.blrMnu=function(th){
	Evt.Rmv(th,'keypress',cms.GetMnu)
	cms.SelectedEditableMnu=null
}
cms.GetMnu=function(ev){
	var key=Evt.GetKey(ev);
	if(key=='ctrlS'){
		Evt.Cancel(ev)
		with(cms.SelectedEditableMnu){
			var x=id.substr(10).split("_")
			var t=innerHTML.replace(/<\/?\s*[^>]*>/gi, "" );
			AJX.Req('?','do=admin_trees&op=upd&tree=blocks-'+x[1]+'&id='+x[0]+'&title='+t,false)
			innerHTML=t
		}
	}
}
