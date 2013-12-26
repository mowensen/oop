<?
$adm.=	"<form id=cmsFldFrm enctype='multipart/form-data' method=post target=cmsBuf class=cmsAdm>".
		"<table>".
			"<td style=opacity:0.3>".self::imgLnk("javascript:cms.ShwEdt('php/')","cache.png",TXTcacheView)."</td>".
			"<td style=opacity:0.3>".self::imgLnk("javascript:cms.ShwEdt('php/rtm/')","rtm.png",TXTrealTime)."</td>".
			"<td>".self::imgLnk("javascript:cms.ShwEdt('upl/')","img.gif",TXTuploads)."</td>".
			"<td><b>&nbsp;".TXTuploads."</b></td>".
		"</table>".
		"<table border=0 width=100%>".
			"<td><input id=cmsFldsta name=rad type=radio onclick=\"cms.ShwUploads('sta')\"></td><td>Static</td>".
			"<td width=11> </td>".
			"<td><input id=cmsFlddyn name=rad type=radio onclick=\"cms.ShwUploads('dyn')\"></td><td>Dynamic</td>".
			"<td width=33> </td>".
			"<td align=center>".
				"<input name=upl type=file onchange=\"this.form.submit();this.value=''\">".
				"<input name=op type=hidden value=upl>".
				"<input name=do type=hidden value=admin_uploads>".
				"<input name=fld type=hidden>".
			"</td>".
			"<td>".self::imgLnk("javascript:cms.HidElm('FldFrm')","left.png",TXThide,"Annuler")."</td>".
		"</table>".
		"<div id=cmsFldDiv>".
			"<table cellspacing=5 cellpadding=5 border=0>".
				"<tr><td valign=top nowrap><b>".TXTfolder." : <span id=cmsFldFld><span></b></td></tr>".
				"<tr>".
					"<td valign=top bgcolor=white><div id=cmsFldLst></div></td>".
					"<td align=center valign=top>".
						"<div id=cmsPhoFrm style=position:absolute;display:table;text-align:center;width:300px class=cmsAdm><br>".
							"<div style=float:left;margin-left:30px;white-space:nowrap;cursor:pointer class=a onclick=cms.DelFil()>".
								"<img src='".OOP_PATH."/img/drop.gif' border=0 style=float:left><div class=a style=float:left>".TXTdelete."</div>".
							"</div>".
							"<div style=float:left;margin-left:30px;white-space:nowrap;cursor:pointer class=a onclick=cms.RenFil()>".
								"<img src='".OOP_PATH."/img/ren.gif' border=0 style=float:left><div class=a style=float:left>".TXTrename."</div>".
							"</div>".
							"<div style=float:left;margin-left:30px;cursor:pointer onclick=cms.DimImg()>".
								"<img src='".OOP_PATH."/img/dim.gif' border=0 style=float:left><div class=a style=float:left>".TXTdimension."</div>".
							"</div>".
							"<br><br><div id=cmsFldFil style=margin:auto;overflow:auto></div>".
						"</div>".
					"</td>".
				"</tr>".
			"</table>".
		"</div>".
	"</form>";
?>
