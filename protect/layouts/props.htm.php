<?
$adm.=	"<form id=cmsPrpFrm class=cmsAdm>".
		self::imgLnk("javascript:cms.HidElm('PrpFrm')","left.png",TXThide,"","float:right;margin-top:2px").
		TXTgenProps.
		"<table class=cmsFre width=350>".
			"<td>".
				"<table>".
					"<td>".TXTsavDatabase."</td>".
					"<td width=33>&nbsp;</td>".
					"<td>".
						"<table>".
							"<td align=right>".
								"<input id=cmsAutoSav name=autoSav type=checkbox>".
							"</td>".
							"<td>".TXTautomatic."</td>".
						"</table>".
					"</td>".
					"<td width=33>&nbsp;</td>".
					"<td>".
						"<table>".
							"<td>".TXTevery."</td>".
							"<td><input id=cmsSavEvry name=every size=3></td>".
							"<td>Hr</td>".
						"</table>".
					"</td>".
					"<td width=33>&nbsp;</td>".
					"<td valign=top>".cms_admin::imgLnk("javascript:with(cms.Elm('PrpFrm'))AJX.Req('?','do=admin_props&op=auto&every='+every.value+'&auto='+(autoSav.checked?1:0),cms.GotPrp)","save.gif",TXTok)."</td>".
				"</table>".
				"<table>".
					"<td id=response width=33>&nbsp;</td>".
					"<td>".cms_admin::imgLnk("javascript:AJX.Req('?','do=admin_props&op=dump',cms.GotPrp)","dbSav.png",TXTsavNow)."</td>".
					"<td width=33>&nbsp;</td>".
					"<td id=cmsDbSavDiv></td>".
					"<td width=33>&nbsp;</td>".
					"<td onclick=\"\" style=cursor:pointer>".
						cms_admin::imgLnk("javascript:with(cms.Elm('DbSav')){if(selectedIndex<0)alert('".TXTselectDate."');else AJX.Req('?','do=admin_props&op=restore&fil='+options[selectedIndex].value,cms.GotRst)}","dbRst.png","Restorer la date sélectionnée").
					"</td>".
					"<td width=33>&nbsp;</td>".
				"</table>".
			"</td>".
		"<table>".
		"<table class=cmsFre width=350>".
			"<td><input id=cmsPrpFrmRwt type=checkbox onchange=\"javascript:cms.PropsSavRwt(this.checked)\"></td>".
			"<td>".TXTrwtMod."</td>".
		"</table>".
		"<table class=cmsFre width=350>".
			"<tr><td colspan=7>".TXTlanguageDefinition."</td></tr>".
			"<tr><td id=cmsDefLng colspan=7 align=right></td></tr><tr>".
			"<tr>".
				"<td>LNG<div id=cmsLngsDiv></div></td>".
				"<td valign=top>".
					"<br>".cms_admin::imgLnk("javascript:cms.PropsSetLng('Lngs','DevLng')","right.gif",TXTnewLanguages).
					"<br>".cms_admin::imgLnk("javascript:cms.PropsSetLng('DevLng','Lngs')","left.gif",TXTremovelastLanguage).
				"</td>".
				"<td valign=top>DEV<div id=cmsDevLngDiv></div></td>".
				"<td valign=top>".
					"<br>".cms_admin::imgLnk("javascript:cms.PropsSetLng('DevLng','PrdLng')","right.gif",TXTpublishLastLanguage).
					"<br>".cms_admin::imgLnk("javascript:cms.PropsSetLng('PrdLng','DevLng')","left.gif",TXTremovelastLanguage,'id=cmsPrdArw').
				"</td>".
				"<td valign=top>PRoD<div id=cmsPrdLngDiv></div></td>".
				"<td valign=top><br>".cms_admin::imgLnk("javascript:cms.PropsDefault()","up.gif",TXTdefaultLanguage,"id=cmsLngUp")."</td>".
				"<td valign=top><br><br>".cms_admin::imgLnk("javascript:cms.PropsSavLng()","save.gif",TXTok)."</td>".
			"</tr>".
		"</table>".
		"<input type=hidden name=op>".
	"</form>";
?>
