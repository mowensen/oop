<?  
$adm.=	"<div id=cmsEdtDiv class=cmsAdm style=position:relative>".
		"<form id=cmsEdtFrm>".
			"<table border=0>".
				"<tr>".
			/*	"<td><div id=cmsEdtCache>".self::imgLnk("javascript:cms.ShwEdt('php/')","cache.png",TXTcacheView)."</div></td>".
					"<td><div id=cmsEdtRtm>".self::imgLnk("javascript:cms.ShwEdt('php/rtm/')","rtm.png",TXTrealTime)."</div></td>".
					"<td><div id=cmsEdtRtm>".self::imgLnk("javascript:cms.ShwEdt('php/mdl/')","rtm.png",TXTrealTime)."</div></td>".*/
					"<td><div id=cmsEdtCache onclick=\"cms.ShwEdt('php/')\" title=\"TXTcacheView\" style=cursor:pointer>V</div></td>".
					"<td><div id=cmsEdtRtm onclick=\"cms.ShwEdt('php/rtm/')\" title=\"TXTrealTime\" style=cursor:pointer>C</div></td>".
					"<td><div id=cmsEdtMdl onclick=\"cms.ShwEdt('php/mdl/')\" title=\"TXTrealTime\" style=cursor:pointer>M</div></td>".
					"<td><div id=cmsEdtFld>".self::imgLnk("javascript:cms.ShwEdt('upl/')","img.gif",TXTuploads)."</div></td>".
					"<td nowrap><b>".strtoupper(TXTblock)." <span id=cmsEdtBckId></span></b></td>".
					"<td colspan=4><input name=title style=width:133px></td>".
					"<td><input name=isMenu type=checkbox title='".TXTisInMnu."'></td>".
					"<td>".self::imgLnk("javascript:cms.HidMac();cms.ShwElm('FrmDiv')","ass.png",TXTassemble)."</td>".
					"<td>".self::imgLnk("javascript:cms.HidMac();cms.ShwElm('MnuDiv')","mnu.png",TXTnavigation)."</td>".
					"<td>".self::imgLnk("javascript:cms.HidMac();cms.GetLabels()","dbLbl.png",TXTlabels)."</td>".
					"<td>".
						self::imgLnk("javascript:cms.HidMac();AJX.Req('?','do=admin_records&op=chkTbl&idB='+cms.SelectedBlock,false)","dbRec.png",TXTrecords).
					"</td>".
					"<td width=700>&nbsp;</td>".
					"<td>".self::imgLnk("javascript:cms.EditSave()","save.gif",TXTok)."</td>".
					"<td>".self::imgLnk("javascript:cms.HidElm('EdtFrm');cms.HidElm('RecDiv');cms.HidElm('LblDiv');cms.HidElm('MnuDiv')","left.png",TXThide)."</td>".
				"</tr>".
			"</table>".
			"<textarea id=cmsEdt class=\"codepress php\" style=width:360px;height:200px></textarea>".
		"</form>".
		"<div id=cmsFrmDiv class=cmsAdm style=position:absolute;top:15px;left:333px>".
			self::imgLnk("javascript:cms.HidElm('FrmDiv')","left.png",TXThide,"","float:right;margin:3px").
			"<table>";
			$frames=array(	"<cmsFrameId></cmsFramesId>"=>TXTframeTag." <cmsContent>",
					"<cmsContent>"=>TXTframeContent.".",
					"<cmsBlockId>"=>TXTblockTag.".");
			foreach($frames as $f=>$t)
				$adm.="<tr><td></td><td style=color:orange;cursor:pointer onclick=cms.InsFrm(this.innerHTML)>".str_replace("<","&lt;",$f)."</td><td>$t</td></tr>";			
		$adm.=	"</table>".
		"</div>".
		"<div id=cmsLblDiv class=cmsAdm style=position:absolute;top:15px;left:333px>".
			self::imgLnk("javascript:cms.HidElm('LblDiv')","left.png",TXThide,"","float:right;margin:3px").
			"<span id=cmsLblSpn></span>".
		"</div>".
		"<div id=cmsMnuDiv class=cmsAdm style=position:absolute;top:15px>".
			"<span id=cmsMnuSpn>";
				include OOP_PATH."/protect/layouts/mnus.htm.php";
		$adm.=	"</span>".
		"</div>".
		"<div id=cmsRecDiv class=cmsAdm style=position:absolute;top:15px>";// division déportée à cause du form à l'interieur
			include OOP_PATH."/protect/layouts/records.htm.php";
$adm.=		"</div>".
	"</div>";
	
?>
