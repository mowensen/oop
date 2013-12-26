cms.ShwUploads=function(fld,nam){
	cms.Shw="FldFrm"
	cms.SlcFld=fld
	cms.SelectedFld="dev/upl/"+fld+"/"+cms.SelectedBlock+"/"
	cms.Elm('FldFrm').elements["fld"].value=cms.SelectedFld
	cms.SelectedFilNam=nam
	cms.selectedFldFil=0
	cms.chkAjxJsFlg=1
	cms.FldFiles=Array()
	cms.FldSizes=Array()
	AJX.Req("?","do=admin_uploads&fld="+cms.SelectedFld,cms.GotAjxFld)
}
cms.UplPag=40
cms.GotAjxFld=function(){
	y=AJX.GetBuf(cms.chkAjxJsFlg);cms.chkAjxJsFlg=1 //les uploads ne se font pas par ajx => ne pas vérifer l'ancien script
	cms.AdminInit()
	cms.ShwElm('FldFrm')
	cms.ChkElm('Fld'+cms.SlcFld)
	cms.SetHtml('FldFld',cms.SelectedFld)
	var txt="";if(cms.FldFiles[0]!="")for(var i=0;i<cms.FldFiles.length;i++){
		txt+="<div><a id=cmsFldFil"+i+" href=\"javascript:cms.ShwFil("+i+")\">"+cms.FldFiles[i]+" ("+cms.FldSizes[i]+")</a></div>"
		if(cms.SelectedFilNam==cms.FldFiles[i])cms.selectedFldFil=i
		var n=(i+1)/cms.UplPag;if(parseInt(n)==n)txt+="</td><td valign=top>"
	}
	cms.SetHtml('FldLst',y+"<table cellspacing=5><td valign=top>"+txt+"</td></table>")
	if(txt!=""){
		cms.ShwElm('PhoFrm');cms.ShwFil(cms.selectedFldFil)
	}else{
		cms.SetHtml('FldFil','');cms.SetHtml('FldLst','Aucun fichier');cms.HidElm('PhoFrm')
	}
}
cms.ShwFil=function(n){
	var fil=cms.FldFiles[n]
	var x=fil.split(".");var ico=x[x.length-1];
	var isImg=ico=="gif"||ico=="jpg"||ico=="jpeg"||ico=="tif"||ico=="png"?1:0
	var isScr=ico=="js"||ico.substr(0,2)=="ph"||ico.substr(0,3)=="htm"||ico=="txt"||ico=="css"||ico=="sql"||ico.substr(0,1)=="x"||ico.substr(0,2)=="ja"?1:0
	if(isScr){
		scr=ico
		if(ico=="js")scr="javascript"
		if(ico=="txt")scr="text"
		if(ico.substr(0,1)=="x")scr="xsl"
		if(ico.substr(0,2)=="ph")scr="php"
		if(ico.substr(0,2)=="ja")scr="java"
		if(ico.substr(0,3)=="htm")scr="html"
	}else scr=''
	cms.SelectedScript=scr
	cms.SetHtml(	'FldFil',
			(isImg?
				"<img src='"+cms.SelectedFld+"/"+fil+"'>":
				(isScr?
					"<img src='"+OOP_PATH+"img/ico/"+ico+".gif"+"' onclick=\"cms.ShwEdt('"+cms.SelectedFld+"/"+fil+"')\" border=0 style=cursor:pointer>"
					:
					"<a href=\""+cms.SelectedFld+"/"+fil+"\" target=pop><img src='"+OOP_PATH+"img/ico/"+ico+".gif"+"' border=0 alt='"+fil+"'></a>"
				)
			)
	)
	cms.Elm("FldFil"+cms.selectedFldFil).style.color=cms.Elm("FldFil"+n).style.color
	cms.Elm("FldFil"+n).style.color="red"
	cms.selectedFldFil=n
	setTimeout("cms.ShwSclFil()",333);
}
cms.ShwSclFil=function(){
	with(cms.Elm('FldFil')){
		style.overflow="auto";var h=300;var w=300
		var ovw=firstChild.offsetWidth>w-9
		var ovh=firstChild.offsetHeight>h-18
		var ovf=ovw||ovh
		style.width=(ovw?w:firstChild.offsetWidth+9)+"px"
		style.height=(ovh?h:firstChild.offsetHeight+18)+"px"
	}
}
cms.RenFil=function(){
	if(nam=prompt("Renommer",cms.FldFiles[cms.selectedFldFil])){
		cms.SelectedFilNam=nam
		AJX.Req("?","op=ren&do=admin_uploads&fld="+cms.SelectedFld+"&fil="+cms.FldFiles[cms.selectedFldFil]+"&filRen="+nam,cms.GotAjxFld)
		cms.selectedFldFil=0
	}
}
cms.DelFil=function(){
	if(confirm(cms.TXTuploadsAlrtDelFile+" "+cms.FldFiles[cms.selectedFldFil])){
		var op="op=del&do=admin_uploads&fld="+cms.SelectedFld+"&fil="+cms.FldFiles[cms.selectedFldFil]
		if(cms.selectedFldFil<1){cms.selectedFldFil=0;cms.selectedFilNam=cms.FldFiles[1]}else{cms.selectedFldFil--;cms.selectedFilNam=cms.FldFiles[cms.selectedFldFil]}
		AJX.Req("?",op,cms.GotAjxFld)
	}
}



function gotPagImg(ok,pagImg){
	imgPath="data/"+(ok?"upl":"pages/"+ID)+"/";var arI=(ok?arU:ary);var arISz=(ok?arUSz:arySz)
	if(parseInt(pagImg)!=pagImg)for(i=0;i<arI.length;i++)if("/"+pagImg==arI[i])pagImg=i
}
function dimImg(dir,fil){
	open("do.php?do=dim&dir="+escape(dir)+"&fil="+escape(fil),"pop")
}
function getPagImg(){
	getPgImg((aryTables[I*nAT+nATC+4].indexOf("file")!=-1?1:0),0)
}
function getPgImg(ok,pagImg){
	ary=new Array();arySz=new Array();arU=new Array();arUSz=new Array();used="";imgCol0=0;imgFile="";
	loct(tmpUrl+"do.php?do=getPagImg&dir="+webPath+"&ini="+ok+"&pagImg="+pagImg+"&id="+ID)
}
pagImg="";maxImgColLst=33;maxImgCols=3
function sbmPagImg(){warn(1);warn(1);xpopd.form('frImg').submit()}
urlEdt="";imgEdt="";txtEdt="";
function filRen(ok,pagImg,img){
	ren=prompt(TXT.reName+" : ",img.replace("/",""));if(ren)loct("do.php?do=filRen&fil="+webPath+imgPath+img+"&ren="+webPath+imgPath+ren+"&ini=getPgImg("+ok+","+pagImg+")&ID="+ID)
}

function shwLnk(){
	with(xpopd.form("edtFrm")){
		urlEdt=elements[0].value;imgEdt=elements[1].value;txtEdt=elements[2].value;
		var txt=(urlEdt!=""?"<a href=\""+urlEdt+"\">":"")+(imgEdt!=""?"<img src=\""+imgEdt+"\""+(urlEdt!=""?" border=0":"")+">":txtEdt)+(urlEdt!=""?"</a>":"")
		elements[3].value=txt
	}
}
