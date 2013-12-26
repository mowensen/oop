<?
$var=array(	"html"=>array("","cms.MacroShowHtml()",TXTshowHtml),
		"link"=>array("","var x=prompt('Fournir une adresse svp:');if(x)document.execCommand('createlink',false,x)",TXTlinkIt),
		"image"=>array("","var x=prompt('Fournir une adresse svp:');if(x)document.execCommand('insertimage',false,x)",TXTinsImg),
		"ordered"=>array("insertorderedlist","",TXTinsOl),
		"unordered"=>array("insertunorderedlist","",TXTinsUl),
		"css"=>array("","with(document.getElementById('cms_easEdit_css').style)display=display!='none'?'none':'block'",TXTinsCss),
		"rmformat"=>array("removeformat","",TXTdelFormat),
		"help"=>array("","with(document.getElementById('cms_easEdit_help').style)display=display!='none'?'none':'block'",TXTshwHelp),
		"save"=>array("","cms.LabelSav()",TXTok)
	);
$adm.=	"<span id=cms_easEdit style=display:none>\n".
		"<div style=white-space:nowrap;width:19px;background-color:yellow;height:20px;overflow:hidden onmouseover=\"with(this.style){width='66px';height='69px'}\" onmouseout=\"with(this.style){width='19px';height='20px'}\">\n";
		$n=0;
		foreach($var as $im=>$ar){
			$n++;
			$adm.=	"<img src=".OOP_PATH."img/easEdit/$im.gif style=\"cursor:pointer;margin:0;padding:0;border:1px solid yellow\"".
					" title=\"$ar[2]\"".
					" onmouseover=\"this.style.borderColor='black'\"".
					" onmouseout=\"this.style.borderColor='yellow'\"".
					" onclick=\"".($ar[1]?"$ar[1]":($ar[0]?"document.execCommand('$ar[0]',false,'')":""))."\" />\n";
			if($n==3){$n=0;$adm.="<br />";}
		}
$adm.=		"</div>\n".
		"<table id=cms_easEdit_css style=\"border:1px solid gray;background-color:white;display:none\" onmouseout=\"this.style.display='none'\" onmouseover=\"this.style.display='block'\">\n";
		$x=file("dev/upl/sta/3/style.css");
		if(count($x))foreach($x as $y){
			$y=trim($y);$p=strpos($y,'{');
			if($y){
				if(substr($y,0,1)=="."){
					$z=trim(substr($y,1,$p-1));
					$adm.="<tr><td style=cursor:pointer class=$z onmousedown=\"cms.InsClass('$z')\">.$z</td><td>".substr($y,$p+1,strlen($y)-$p-2)."</td></tr>\n";
				}elseif(substr($y,0,1)!="#"){
					$z=trim(substr($y,0,$p-1));
					$adm.="<tr><td style=cursor:pointer onmousedown=\"cms.InsTag('$z')\"><$z>$z</$z></td><td>".substr($y,$p+1,strlen($y)-$p-2)."</td></tr>\n";
				}
			}
		}		
$adm.=		"</table>\n".
		"<table id=cms_easEdit_help class=cmsEasEditHelp style=display:none cellspacing=1 cellpadding=0 onmouseout=\"this.style.display='none'\">
			<tr style=cursor:default><td>CTRL-S</td><td width=33>&nbsp;</td><td>Save</td></tr>
			<tr><td><br>".TXTformats."</td></tr>
			<tr><td>CTRL-B</td><td width=33></td><td>".TXTbold."</td></tr>
			<tr><td>CTRL-I</td><td width=33></td><td>".TXTitalic."</td></tr>
			<tr><td>CTRL-U</td><td width=33></td><td>".TXTunderline."</td></tr>
			<tr><td>CTRL-L</td><td width=33></td><td>".TXTjustifyLeft."</td></tr>
			<tr><td>CTRL-M</td><td width=33></td><td>".TXTjustifyCenter."</td></tr>
			<tr><td>CTRL-R</td><td width=33></td><td>".TXTjustifyRight."</td></tr>
			<tr><td>CTRL-J</td><td width=33></td><td>".TXTjustify."</td></tr>
			<tr><td>CTRL-1 .. CTRL-6</td><td width=33></td><td>".TXTheadings." (&lt;h1> .. &lt;h6>)</td></tr>
			<tr><td><br>".TXTselection."</td></tr>
			<tr><td>CTRL-A</td><td width=33></td><td>".TXTselAll."</td></tr>
			<tr><td>CTRL-X</td><td width=33></td><td>".TXTselCut."</td></tr>
			<tr><td>CTRL-C</td><td width=33></td><td>".TXTselCopy."</td></tr>
			<tr><td>CTRL-V</td><td width=33></td><td>".TXTpasteClip."</td></tr>
			<tr><td>CTRL-Z</td><td width=33></td><td>".TXTundo."</td></tr>
			<tr><td>CTRL-Y</td><td width=33></td><td>".TXTredo."</td></tr>
		</table>\n".
	"</span>";
?>
