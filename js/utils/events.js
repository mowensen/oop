Evt=new Object();
Evt.mouse=new Object();
try{var x=addEventListener; Evt.Ok=true}catch(e){Evt.Ok=false}

Evt.Add=function(obj,ev,fnt){
	if(Evt.Ok)obj.addEventListener(ev,fnt,false);else obj.attachEvent("on"+ev,fnt)
}
Evt.Rmv=function(obj,ev,fnt){
	if(Evt.Ok)obj.removeEventListener(ev,fnt,false);else obj.detachEvent("on"+ev,fnt)
}
Evt.Cancel=function(ev){
	if(Evt.Ok){ev.preventDefault();ev.stopPropagation();return false}else{ev.cancelBubble=true;ev.returnValue=false;return false}
}
Evt.GetKey=function(ev){
	Evt.KeyCode=(Evt.Ok?ev.which:ev.keyCode);
	var key=String.fromCharCode(Evt.KeyCode);
	if(ev.ctrlKey)key="ctrl"+key.toUpperCase()
	if(Evt.KeyCode==9)key="tab"
	if(Evt.KeyCode==27)key="esc"
	if(Evt.KeyCode>111&&Evt.KeyCode<124)key="f"+Evt.KeyCode-111
	return key;
}
Evt.GetTarget=function(ev){
	var targ;
	if (!ev) var ev = window.event;
	if (ev.target) targ = ev.target;
	else if (ev.srcElement) targ = ev.srcElement;
	if (targ.nodeType == 3) targ = targ.parentNode;// defeat Safari bug
	return targ
}
Evt.GetType=function(ev){
	if (!ev) var ev = window.event
	return ev.type
}
Evt.GetMouseBut=function(ev){// A VERIFIER
	var rightclick;
	if (!ev) var ev = window.event;
	if (ev.which) rightclick = (ev.which == 3);
	else if (ev.button) rightclick = (ev.button == 2);
	Evt.mouse.click=ev.which // 2=>droit 3=>
}
Evt.GetMousePos=function(ev){
	var posx = 0;var posy = 0;
	if (!ev) var e = window.event;
	if (ev.pageX || ev.pageY) {
		posx = ev.pageX;
		posy = ev.pageY;
	}else if (ev.clientX || ev.clientY) {
		posx = ev.clientX + document.body.scrollLeft + document.documentElement.scrollLeft;
		posy = ev.clientY + document.body.scrollTop + document.documentElement.scrollTop;
	}
	Evt.mouse.X=posx;Evt.mouse.Y=posy
}


