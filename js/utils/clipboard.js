clp=new Object();
clp.toClip=function(txt){
	if(window.clipboardData){
		window.clipboardData.setData("Text", txt);
	}else{
/*		try{
			netscape.security.PrivilegeManager.enablePrivilege('UniversalXPConnect');
			var clip=Components.classes['@mozilla.org/widget/clipboard;1'].createInstance(Components.interfaces.nsIClipboard);
			if(!clip)return;
			var trans=Components.classes['@mozilla.org/widget/transferable;1'].createInstance(Components.interfaces.nsITransferable);
			if(!trans)return;
			trans.addDataFlavor('text/unicode');
			var str = Components.classes["@mozilla.org/supports-string;1"].createInstance(Components.interfaces.nsISupportsString);
			str.data=txt;
			trans.setTransferData("text/unicode",str,txt.length*2);
			var clipid=Components.interfaces.nsIClipboard;
			clip.setData(trans,null,clipid.kGlobalClipboard);
		}catch (e){
			alert(TXT.toClip)
		}*/
		//document.execCommand("Copy")
	}
}
clp.getClip=function(){
	if(window.clipboardData){
		return window.clipboardData.getData("Text");
	}else{
		try{
			netscape.security.PrivilegeManager.enablePrivilege('UniversalXPConnect');
			var clip = Components.classes["@mozilla.org/widget/clipboard;1"].createInstance(Components.interfaces.nsIClipboard);
			if (!clip) return false;
			var trans = Components.classes["@mozilla.org/widget/transferable;1"].createInstance(Components.interfaces.nsITransferable);
			if (!trans) return false;
			trans.addDataFlavor("text/unicode");
			clip.getData(trans,clip.kGlobalClipboard);
			var str = new Object();var strLength = new Object();
			trans.getTransferData("text/unicode",str,strLength);
			if (str) str = str.value.QueryInterface(Components.interfaces.nsISupportsString);
			if (str) pastetext = str.data.substring(0,strLength.value / 2);
			return pastetext
		}catch (e){
			try{alert(TXT.toClip)}catch(e){alert("netscape.security.PrivilegeManager clipboard not allowed. See about:config")}
		}
	}
}

// GESTION DES SELECTIONS

clp.insertHTML=function(v){
	if (window.getSelection)document.execCommand('inserthtml',false,v);else document.selection.createRange().pasteHTML(v)
}
clp.insertAtCursor=function(text) {
	clp.getSelection()
	if (window.getSelection) {
		var newNode = document.createElement("div")
		newNode.innerHTML=text
		var fragment=document.createDocumentFragment()
		while (newNode.firstChild)fragment.appendChild(newNode.firstChild);
		clp.RANGE.insertNode(fragment)
	} else if (document.selection && document.selection.createRange) {
		clp.RANGE.pasteHTML(text);
	}
}
clp.getSelection=function() {
	clp.RANGE=null;var sel=""; clp.RANGE=null
	if (window.getSelection) {
		sel = window.getSelection();
		if (sel.getRangeAt && sel.rangeCount) clp.RANGE= sel.getRangeAt(0);
	} else if (document.selection && document.selection.createRange){
		clp.RANGE=document.selection.createRange();
		sel=clp.RANGE.text || sel;
	}
	if(sel.toString)sel = sel.toString();
	return sel
}
clp.setSelection=function(range) {
    if (range) {
        if (window.getSelection) {
		sel = window.getSelection();
		sel.removeAllRanges();
		sel.addRange(range);
        } else if (document.selection && range.select) {
		range.select();
        }
    }
}
clp.selectedText=function(){
	var t="";
	if (window.getSelection)t=window.getSelection();
	else if (document.getSelection)t=document.getSelection();
	else if (document.selection)t=document.selection.createRange().text;
	return t
}


