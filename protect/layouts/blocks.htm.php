<?
$adm.=	"<form id=cmsDrgBckFrm class=cmsAdm>".
		"<table>".
			"<tr><td colspan=8 nowrap><span id=cmsDrag1 style=color:red></span> ".TXTmoved."<br>".TXTwhichPosition." <span id=cmsDrop2 style=color:red></span>?<br></td></tr>".
			"<tr>".
				"<td align=right><input type=radio name=rad onclick=cms.DrgAfter=0></td><td>".TXTbefore."</td>".
				"<td align=right><input type=radio name=rad onclick=cms.DrgAfter=1 checked></td><td>".TXTafter."</td>".
				"<td align=right><input type=radio name=rad onclick=cms.DrgAfter=2></td><td>".TXTbelow."</td>".
				"<td>".self::imgLnk("javascript:cms.MovBlock()","save.gif",TXTok)."</td>".
				"<td>".self::imgLnk("javascript:cms.HidElm('DrgBckFrm')","left.png",TXTcancel)."</td>".
			"</tr>".
		"</table>".
		"<input type=hidden name=op>".
	"</form>".
	"<form id=cmsAddBckFrm class=cmsAdm onsubmit=\"cms.ChkAddBlkFrm();return false\">".
		"<table>".
			"<tr><td colspan=4>Fournir un nom de bloc:</td><td colspan=4><input name=title></td></tr>".
			"<tr><td><input type=checkbox name=content checked></td><td colspan=7>".TXTautomaticContent."</td></tr>".
			"<tr><td><input type=checkbox name=isMnu checked></td><td colspan=7>".TXTisInMnu."</td></tr>".
			"<tr><td colspan=8 nowrap>".TXTwhichPosition." <span id=cmsAdd style=color:red></span>?<br></td></tr>".
			"<tr>".
				"<td align=right><input type=radio name=rad onclick=cms.AddAfter=0></td><td>".TXTbefore."</td>".
				"<td align=right><input type=radio name=rad onclick=cms.AddAfter=1 checked></td><td>".TXTafter."</td>".
				"<td align=right><input type=radio name=rad onclick=cms.AddAfter=2 id=cmsAddSub1></td><td><span id=cmsAddSub2>".TXTbelow."</span></td>".
				"<td>".self::imgLnk("javascript:cms.ChkAddBlkFrm()","save.gif",TXTok)."</td>".
				"<td>".self::imgLnk("javascript:cms.HidElm('AddBckFrm')","left.png",TXTcancel)."</td>".
			"</tr>".
		"</table>".
		"<input type=hidden name=op>".
	"</form>".
	"<script>Evt.Add(cms.Elm('AddBckFrm').title,'keydown',cms.ChkCtrSavAddBlkFrm)</script>";
?>
