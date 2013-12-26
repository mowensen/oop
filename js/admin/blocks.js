/**** GESTION ARBORESCENCE ****/
cms.AddAfter=1
cms.DrgAfter=1
cms.SelectedBlock=0;
cms.SelectedFolder="php/"
cms.Shw="EdtFrm"
cms.FindASelectedBlock=function(){//rattrapage en cas de suppression d'un block
	try{
		var b=cms.Elm('Block'+cms.SelectedBlock).style
	}catch(e){
		try{
			var b=cms.Elm('Block'+cms.PreviousSelectedBlock).style;
			cms.SelectedBlock=cms.PreviousSelectedBlock
			
		}catch(e){cms.SelectedBlock=0}
	}
}
cms.ShwBlocks=function(){
	cms.AdminInit()
	cms.ShwElm('Tree')
	AJX.Req('?','do=admin_blocks',cms.GotBlocks)
}
cms.GotBlocks=function(){
	cms.SetHtml('TreeLst',AJX.GetBuf(1))
	cms.FindASelectedBlock()
	cms.ShwBlock(cms.Elm('Block'+cms.SelectedBlock))
}
cms.ShwBlock=function(ths){
	cms.FindASelectedBlock()
	cms.AdminInit()
	var block=ths.getAttribute("title")
	tree=ths.getAttribute("tree")
	with(cms.Elm("Block"+block)){
		cms.InitialColor=style.color
		style.color="red"
	}
	if(block!=cms.SelectedBlock)cms.Elm("Block"+cms.SelectedBlock).style.color=cms.InitialColor
	cms.PreviousSelectedBlock=cms.SelectedBlock
	cms.SelectedBlock=block
	cms.SelectedTree=tree
	AJX.Req("?","do=admin_cookie&admBlock="+block,cms.AdmBckOk)
}
cms.AdmBckOk=function(){
	if(cms.Shw!="")cms.Elm(cms.Shw).style.display="block"
	if(cms.Shw=="EdtFrm")cms.ShwEdt(cms.SelectedFolder) // l'éditeur
	else if(cms.Shw=="FldFrm")cms.ShwUploads(cms.SlcFld)// les téléchargements
}
cms.ShwBckEdt=function(id,fld){
	cms.Shw="EdtFrm"
	cms.SelectedFolder=fld
	cms.ShwElm("EdtFrm")
	cms.ShwBlock(cms.Elm('Block'+id))
}
cms.AddBlock=function(block){
	cms.HidElm("EdtFrm");
	with(cms.Elm("AddBckFrm")){
		style.display="block"
		title.focus()
	}
	cms.Elm("Add").innerHTML=cms.Elm("Block"+cms.SelectedBlock).innerHTML
}
cms.ChkCtrSavAddBlkFrm=function(ev){
	if(Evt.GetKey(ev)=="ctrlS"){
		cms.ChkAddBlkFrm()
		Evt.Cancel(ev)
	}
}
cms.ChkAddBlkFrm=function(){
	with(cms.Elm("AddBckFrm")){
		var msg="";var t=elements["title"].value
		ok=t.length>2;if(!ok)msg+=cms.TXTblocksMin3Car;
		if(ok){
			AJX.Req('?','do=admin_blocks&op=add&title='+t+'&idB='+cms.SelectedBlock+'&tree='+cms.SelectedTree+'&after='+cms.AddAfter+"&isMnu="+(elements["isMnu"].checked?1:'')+"&content="+(elements["content"].checked?1:''),cms.GotBlocks)
		}else alert(msg)
	}
}
cms.DelBlock=function(block){
	if(confirm(cms.TXTblocksAlrtDelBlock)){
		AJX.Req('?','do=admin_blocks&op=del&idB='+cms.SelectedBlock+'&tree='+cms.SelectedTree,cms.GotBlocks)
	}
}
cms.DragBlock=function(ths){
	cms.DraggedBlock=ths.getAttribute("title")
	cms.DraggedTree=ths.getAttribute("tree")
	cms.Elm("Drag1").innerHTML=cms.Elm("Block"+cms.DraggedBlock).innerHTML
}
cms.DropBlock=function(ths){
	cms.AdminInit()
	cms.DroppedBlock=ths.getAttribute("title")
	cms.DroppedTree=ths.getAttribute("tree")
	cms.Elm("Drop2").innerHTML=cms.Elm("Block"+cms.DroppedBlock).innerHTML
	cms.Elm("DrgBckFrm").style.display="block"
}
cms.DrgOvrBck=function(th){
	/*var obj=th;var t=0;var l=0;var ok=1;while(ok&&obj.parentNode){obj=obj.parentNode;if(obj.id=="cmsTree"){ok=0}else{t+=obj.offsetTop;l+=obj.offsetLeft}}
	with(cms.Elm('DropAfter').style){
		left=l+"px";top=t+'px';display='block'
	}*/
}
cms.MovBlock=function(){
	AJX.Req('?','do=admin_blocks&op=mov&idB='+cms.DraggedBlock+'&tree='+cms.DraggedTree+'&toidB='+cms.DroppedBlock+'&toTree='+cms.DroppedTree+'&after='+cms.DrgAfter,cms.GotBlocks)
	cms.AdminInit()
}
cms.SearchBlock=function(){
	if(x=prompt("Fournir un id ou un bout de titre de bloc")){
		if(x==parseInt(x)){if(cms.Elm("Block"+x))cms.ShwBlock(cms.Elm("Block"+x));else alert(cms.TXTblocksDoesNotExist)}
	}
}
